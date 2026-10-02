<?php $title = 'Home'; require 'includes/header.php'; ?>
<?php if (loggedIn()): ?>
  <h2>Welcome back, <?= e($_SESSION['user']) ?></h2>
  <p>See who's around in <a href="members.php" data-ajax="false">Members</a>, or check your <a href="messages.php" data-ajax="false">Messages</a>.</p>
<?php else: ?>
  <h2>Your team's private social network</h2>
  <p>Company Pulse helps startup colleagues connect through profiles and messages.</p>
  <a href="signup.php" data-ajax="false" class="ui-btn ui-btn-b ui-corner-all">Sign Up</a>
  <a href="login.php" data-ajax="false" class="ui-btn ui-corner-all">Log In</a>
<?php endif; ?>
<?php pageEnd();
