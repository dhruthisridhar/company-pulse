<?php
require_once 'includes/functions.php'; requireLogin();
$me = $_SESSION['user']; $view = sanitize($_GET['view'] ?? $me); $own = ($view === $me); $msg = '';
if ($own && isPost() && csrfOk()) {
    query('INSERT INTO profiles (user, about) VALUES (?, ?) ON DUPLICATE KEY UPDATE about = VALUES(about)',
          [$me, mb_substr(sanitize($_POST['about'] ?? ''), 0, 4000)]);
    $msg = 'Profile saved.';
    $f = $_FILES['image'] ?? null;
    if ($f && $f['name'] !== '') {
        $types = [IMAGETYPE_JPEG => 'imagecreatefromjpeg', IMAGETYPE_PNG => 'imagecreatefrompng', IMAGETYPE_GIF => 'imagecreatefromgif'];
        $info = $f['error'] === UPLOAD_ERR_OK ? @getimagesize($f['tmp_name']) : false;
        if (!function_exists('imagecreatetruecolor')) $msg = 'The PHP GD extension is not enabled.';
        elseif (!$info || !isset($types[$info[2]]) || $f['size'] > 2 * 1024 * 1024) $msg = 'Upload a JPEG, PNG or GIF under 2 MB.';
        else {
            $src = $types[$info[2]]($f['tmp_name']); $w = imagesx($src); $h = imagesy($src); $s = min($w, $h);
            $dst = imagecreatetruecolor(100, 100);                       // 100x100 thumbnail, centre-cropped
            imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
            imagecopyresampled($dst, $src, 0, 0, intdiv($w - $s, 2), intdiv($h - $s, 2), 100, 100, $s, $s);
            if (!is_dir('uploads/avatars')) mkdir('uploads/avatars', 0755, true);
            imagejpeg($dst, "uploads/avatars/$me.jpg", 90);
            $msg = 'Profile and picture saved.';
        }
    }
}
$exists = memberExists($view);
$about = $exists ? query('SELECT about FROM profiles WHERE user=?', [$view])->fetchColumn() : '';
$title = 'Profile'; require 'includes/header.php';
if (!$exists) { echo '<p class="bad">Member not found.</p>'; pageEnd(); exit; }
?>
<h2><?= $own ? 'Your profile' : e($view) ?></h2>
<?php if ($msg): ?><p class="ok"><?= e($msg) ?></p><?php endif; ?>
<img class="thumb" src="<?= e(thumb($view)) ?>" width="100" height="100" alt="">
<?php if ($own): ?>
<form method="post" enctype="multipart/form-data" data-ajax="false">
  <?= csrfField() ?>
  <label for="about">About you</label>
  <textarea name="about" id="about" rows="5"><?= e($about) ?></textarea>
  <label for="image">Profile picture (JPEG, PNG or GIF; resized to 100x100)</label>
  <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/gif">
  <button type="submit" class="ui-btn ui-btn-b ui-corner-all">Save profile</button>
</form>
<?php else: ?>
  <p><?= $about !== '' ? nl2br(e($about)) : '<em>No bio yet.</em>' ?></p>
  <a href="messages.php?to=<?= urlencode($view) ?>" data-ajax="false" class="ui-btn ui-btn-inline ui-corner-all">Send a message</a>
<?php endif; pageEnd();
