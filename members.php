<?php
require_once 'includes/functions.php'; requireLogin(); handleFollow();
$me = $_SESSION['user'];
$following = column('SELECT friend FROM friends WHERE user=?', [$me]);
$members = query('SELECT m.user, p.about FROM members m LEFT JOIN profiles p ON p.user = m.user WHERE m.user <> ? ORDER BY m.user', [$me])->fetchAll();
$title = 'Members'; require 'includes/header.php';
?>
<h2>Members</h2>
<?php foreach ($members as $r) {
    $u = $r['user'];
    echo userCard($u, in_array($u, $following) ? followButton($u, 'unfollow', 'Unfollow') : followButton($u, 'follow', 'Follow'),
                  mb_strimwidth((string)$r['about'], 0, 90, '...'));
}
if (!$members) echo '<p>No other members yet. Invite a teammate!</p>';
pageEnd();
