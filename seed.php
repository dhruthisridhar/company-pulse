<?php
// Demo data: sample members, bios, avatars, friendships and messages.
// Run from the project folder:  php seed.php [your_username]
// (or open http://localhost:8000/seed.php?me=your_username in the browser)
// Safe to re-run: it removes and recreates only the demo members below.
require_once 'includes/functions.php';
if (PHP_SAPI !== 'cli') header('Content-Type: text/plain; charset=utf-8');
const DEMO_PASSWORD = 'Pulse123!';
$me = sanitize($argv[1] ?? $_GET['me'] ?? '');

$people = [   // username => [bio, avatar colour]
    'alice_dev'   => ['Backend engineer. Coffee, PHP and tidy database schemas.', [58, 91, 217]],
    'ben_design'  => ['Product designer. Sketches first, pixels second.', [217, 91, 58]],
    'carla_pm'    => ['Product manager keeping the roadmap honest.', [46, 158, 107]],
    'dev_raj'     => ['Full-stack developer who loves fast APIs.', [142, 68, 173]],
    'emma_ops'    => ['Operations lead. If it is scheduled, I scheduled it.', [230, 160, 40]],
    'farah_sales' => ['Sales and partnerships. Always happy to talk to customers.', [192, 57, 43]],
    'george_hr'   => ['People and culture. Here to make the team feel at home.', [52, 152, 219]],
    'hana_qa'     => ['QA engineer. I break things so customers do not have to.', [90, 110, 120]],
];
$follows = [   // [who follows, whom]
    ['alice_dev', 'ben_design'], ['ben_design', 'alice_dev'],
    ['alice_dev', 'carla_pm'], ['carla_pm', 'alice_dev'],
    ['ben_design', 'carla_pm'], ['carla_pm', 'ben_design'],
    ['dev_raj', 'emma_ops'], ['emma_ops', 'dev_raj'],
    ['george_hr', 'hana_qa'], ['hana_qa', 'george_hr'],
    ['dev_raj', 'alice_dev'], ['emma_ops', 'carla_pm'], ['farah_sales', 'george_hr'],
];
$messages = [   // [author, recipient (null = public), hours ago, text]
    ['carla_pm', null, 70, 'Welcome to Company Pulse, everyone! Use this feed for team-wide updates.'],
    ['alice_dev', null, 52, 'Staging database is migrated. Ping me if anything looks off.'],
    ['ben_design', null, 30, 'New landing page mockups are ready for feedback. Thursday review?'],
    ['hana_qa', null, 26, 'Found three bugs in the checkout flow and filed them all.'],
    ['farah_sales', null, 8, 'We closed our first pilot customer today. Thank you all!'],
    ['emma_ops', null, 3, 'Reminder: office hours moved to 3 pm on Friday.'],
    ['alice_dev', 'ben_design', 40, 'Can you send me the final logo files before the review?'],
    ['ben_design', 'alice_dev', 39, 'Sure, sending them over in a few minutes.'],
    ['carla_pm', 'dev_raj', 20, 'Great work on the API. Can we talk roadmap tomorrow?'],
    ['george_hr', 'emma_ops', 12, 'Please submit the vendor paperwork this week.'],
];
if ($me !== '' && !isset($people[$me]) && memberExists($me)) {   // make the demo team interact with you
    foreach (['alice_dev', 'ben_design', 'carla_pm'] as $u) { $follows[] = [$me, $u]; $follows[] = [$u, $me]; }
    $follows[] = ['dev_raj', $me]; $follows[] = ['emma_ops', $me]; $follows[] = [$me, 'farah_sales'];
    $messages[] = ['ben_design', null, 5, "Welcome to the team, $me!"];
    $messages[] = ['alice_dev', $me, 4, 'Glad you are here. Let me know if you need anything.'];
}

function avatar($user, $c) {   // simple 100x100 placeholder picture
    if (!function_exists('imagecreatetruecolor')) return;
    $im = imagecreatetruecolor(100, 100);
    imagefill($im, 0, 0, imagecolorallocate($im, $c[0], $c[1], $c[2]));
    $white = imagecolorallocate($im, 255, 255, 255);
    imagefilledellipse($im, 50, 38, 34, 34, $white);
    imagefilledellipse($im, 50, 92, 64, 50, $white);
    if (!is_dir('uploads/avatars')) mkdir('uploads/avatars', 0755, true);
    imagejpeg($im, "uploads/avatars/$user.jpg", 90);
}

try {
    $names = array_keys($people);
    query('DELETE FROM members WHERE user IN (' . implode(',', array_fill(0, count($names), '?')) . ')', $names);
    $hash = password_hash(DEMO_PASSWORD, PASSWORD_DEFAULT);
    foreach ($people as $user => [$bio, $colour]) {
        query('INSERT INTO members (user, pass) VALUES (?, ?)', [$user, $hash]);
        query('INSERT INTO profiles (user, about) VALUES (?, ?)', [$user, $bio]);
        avatar($user, $colour);
    }
    foreach ($follows as [$a, $b]) query('INSERT IGNORE INTO friends (user, friend) VALUES (?, ?)', [$a, $b]);
    foreach ($messages as [$auth, $to, $hours, $text])
        query('INSERT INTO messages (auth, recip, pm, sent, message) VALUES (?, ?, ?, DATE_SUB(NOW(), INTERVAL ? HOUR), ?)',
              [$auth, $to, $to === null ? 0 : 1, $hours, $text]);
    echo count($people) . " demo members, " . count($follows) . " follows, " . count($messages) . " messages created.\n";
    echo 'Log in as any of: ' . implode(', ', $names) . "\nPassword for all: " . DEMO_PASSWORD . "\n";
    if ($me !== '') echo isset($people[$me]) || !memberExists($me) ? "No account named '$me' was found, so the demo team was not linked to anyone.\n" : "The demo team is now connected to your account '$me'.\n";
} catch (PDOException $ex) {
    echo 'Seeding failed: ' . $ex->getMessage() . "\nHave you run setup.php yet?\n";
}
