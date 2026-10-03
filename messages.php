<?php
require_once 'includes/functions.php'; requireLogin();
$me = $_SESSION['user']; $err = ''; $to = sanitize($_GET['to'] ?? '');
if (isPost() && csrfOk()) {
    if (isset($_POST['delete'])) {   // delete from own feed: messages you sent or received
        query('DELETE FROM messages WHERE id=? AND (auth=? OR recip=?)', [(int)$_POST['delete'], $me, $me]);
        header('Location: messages.php'); exit;
    }
    $text = mb_substr(sanitize($_POST['message'] ?? ''), 0, 4000);
    $pm = ($_POST['type'] ?? '') === 'private' ? 1 : 0;
    $to = $pm ? sanitize($_POST['to'] ?? '') : null;
    if ($text === '') $err = 'Write something first.';
    elseif ($pm && ($to === $me || !memberExists($to))) $err = 'Choose a valid recipient for a whisper.';
    else {
        query('INSERT INTO messages (auth, recip, pm, message) VALUES (?, ?, ?, ?)', [$me, $to, $pm, $text]);
        header('Location: messages.php'); exit;
    }
}
$members = column('SELECT user FROM members WHERE user <> ? ORDER BY user', [$me]);
$feed = query('SELECT * FROM messages WHERE pm = 0 OR auth = ? OR recip = ? ORDER BY sent DESC, id DESC LIMIT 100', [$me, $me])->fetchAll();
$title = 'Messages'; require 'includes/header.php';
?>
<h2>Messages</h2>
<?php if ($err): ?><p class="bad"><?= e($err) ?></p><?php endif; ?>
<form method="post" data-ajax="false">
  <?= csrfField() ?>
  <textarea name="message" rows="3" placeholder="Write a message..." required></textarea>
  <fieldset data-role="controlgroup" data-type="horizontal">
    <input type="radio" name="type" id="pub" value="public" <?= $to === '' ? 'checked' : '' ?>><label for="pub">Public</label>
    <input type="radio" name="type" id="priv" value="private" <?= $to !== '' ? 'checked' : '' ?>><label for="priv">Private whisper</label>
  </fieldset>
  <div id="whisper-to">
  <label for="to">Whisper to</label>
  <select name="to" id="to">
    <?php foreach ($members as $m): ?><option value="<?= e($m) ?>" <?= $m === $to ? 'selected' : '' ?>><?= e($m) ?></option><?php endforeach; ?>
  </select>
  </div>
  <button type="submit" class="ui-btn ui-btn-b ui-corner-all">Send</button>
</form>
<?php foreach ($feed as $m): ?>
  <div class="card <?= $m['pm'] ? 'whisper' : '' ?>">
    <img class="thumb" src="<?= e(thumb($m['auth'])) ?>" width="100" height="100" alt="">
    <div class="body">
      <strong><a href="profile.php?view=<?= urlencode($m['auth']) ?>" data-ajax="false"><?= e($m['auth']) ?></a></strong>
      <?= $m['pm'] ? '<span class="tag">whispered to ' . e($m['recip']) . '</span>' : '' ?>
      <div class="hint"><?= e(date('M j, g:i A', strtotime($m['sent']))) ?></div>
      <p><?= nl2br(e($m['message'])) ?></p>
      <?php if ($m['auth'] === $me || $m['recip'] === $me): ?>
        <form method="post" data-ajax="false" class="inline"><?= csrfField() ?>
          <button name="delete" value="<?= (int)$m['id'] ?>" class="ui-btn ui-btn-inline ui-mini ui-corner-all">Delete</button></form>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; if (!$feed) echo '<p>No messages yet.</p>'; ?>
<?php pageEnd();
