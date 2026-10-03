<?php
require_once 'includes/functions.php';
$error = ''; $user = '';
if (isPost() && csrfOk()) {
    $user = sanitize($_POST['user'] ?? ''); $pass = $_POST['pass'] ?? '';
    if (!validUsername($user)) $error = 'Username must be 3-16 letters, numbers or underscores.';
    elseif (strlen($pass) < 6) $error = 'Password must be at least 6 characters.';
    elseif (memberExists($user)) $error = 'That username is already taken.';
    else {
        query('INSERT INTO members (user, pass) VALUES (?, ?)', [$user, password_hash($pass, PASSWORD_DEFAULT)]);
        query('INSERT INTO profiles (user, about) VALUES (?, "")', [$user]);
        session_regenerate_id(true); $_SESSION['user'] = $user;
        header('Location: profile.php'); exit;
    }
}
$title = 'Sign Up'; require 'includes/header.php';
?>
<h2>Create your account</h2>
<?php if ($error): ?><p class="bad"><?= e($error) ?></p><?php endif; ?>
<form method="post" data-ajax="false">
  <?= csrfField() ?>
  <label for="user">Username</label>
  <input type="text" name="user" id="user" maxlength="16" value="<?= e($user) ?>" autocomplete="off" required>
  <div id="info" class="hint"></div>
  <label for="pass">Password</label>
  <input type="password" name="pass" id="pass" minlength="6" required>
  <button type="submit" class="ui-btn ui-btn-b ui-corner-all">Sign Up</button>
</form>
<?php pageEnd();
