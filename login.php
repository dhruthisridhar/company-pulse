<?php
require_once 'includes/functions.php';
$error = '';
if (isPost() && csrfOk()) {
    $user = sanitize($_POST['user'] ?? '');
    $row = query('SELECT pass FROM members WHERE user=?', [$user])->fetch();
    if ($row && password_verify($_POST['pass'] ?? '', $row['pass'])) {
        session_regenerate_id(true); $_SESSION['user'] = $user;
        header('Location: members.php'); exit;
    }
    $error = 'Invalid username or password.';
}
$title = 'Log In'; require 'includes/header.php';
?>
<h2>Log in</h2>
<?php if ($error): ?><p class="bad"><?= e($error) ?></p><?php endif; ?>
<form method="post" data-ajax="false">
  <?= csrfField() ?>
  <label for="user">Username</label><input type="text" name="user" id="user" required>
  <label for="pass">Password</label><input type="password" name="pass" id="pass" required>
  <button type="submit" class="ui-btn ui-btn-b ui-corner-all">Log In</button>
</form>
<?php pageEnd();
