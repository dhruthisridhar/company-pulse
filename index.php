<?php $title = 'Home'; require 'includes/header.php'; ?>
<div class="hero">
<?php if (loggedIn()): ?>
  <h1>Welcome back, <?= e($_SESSION['user']) ?></h1>
  <p>Catch up on messages from your team, or see who has joined.</p>
  <a href="messages.php" data-ajax="false" class="ui-btn ui-btn-b ui-btn-inline ui-corner-all">Read messages</a>
  <a href="members.php" data-ajax="false" class="ui-btn ui-btn-inline ui-corner-all">Browse members</a>
<?php else: ?>
  <h1>Where your team keeps in touch</h1>
  <p>Share a short profile, follow your colleagues, and send messages that stay inside the company.</p>
  <a href="signup.php" data-ajax="false" class="ui-btn ui-btn-b ui-btn-inline ui-corner-all">Create account</a>
  <a href="login.php" data-ajax="false" class="ui-btn ui-btn-inline ui-corner-all">Log in</a>
<?php endif; ?>
</div>
<?php pageEnd();
