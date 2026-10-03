<?php
// Shared header: loaded on every page. Navigation depends on login state.
require_once __DIR__ . '/functions.php';
$page = basename($_SERVER['SCRIPT_NAME']);
$nav = loggedIn()
    ? ['index.php' => 'Home', 'members.php' => 'Members', 'friends.php' => 'Friends', 'messages.php' => 'Messages', 'profile.php' => 'Profile', 'logout.php' => 'Log Out']
    : ['index.php' => 'Home', 'signup.php' => 'Sign Up', 'login.php' => 'Log In'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'Home') ?> | Company Pulse</title>
<script>try { if (sessionStorage.getItem('traced')) document.documentElement.className += ' traced'; else sessionStorage.setItem('traced', '1'); } catch (e) {}</script>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&family=Sora:wght@700&display=swap">
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<link rel="stylesheet" href="css/styles.css">
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>$(document).on('mobileinit', function () { $.mobile.ajaxEnabled = false; });</script>
<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
<script src="js/app.js"></script>
</head>
<body>
<div data-role="page">
  <header class="site-head"><div class="wrap">
    <div class="top">
      <a class="brand" href="index.php" data-ajax="false">Company Pulse</a>
      <?php if (loggedIn()): ?>
        <a class="me" href="profile.php" data-ajax="false"><img src="<?= e(thumb($_SESSION['user'])) ?>" alt=""><?= e($_SESSION['user']) ?></a>
      <?php endif; ?>
    </div>
    <svg class="trace" viewBox="0 0 760 28" preserveAspectRatio="none" aria-hidden="true"><path d="M0 14 H150 L166 14 L182 3 L200 25 L214 9 L226 14 H760"/></svg>
    <nav class="tabs" aria-label="Main">
      <?php foreach ($nav as $file => $label): ?>
        <a href="<?= $file ?>" data-ajax="false" class="<?= $page === $file ? 'active' : '' ?>"<?= $page === $file ? ' aria-current="page"' : '' ?>><?= $label ?></a>
      <?php endforeach; ?>
    </nav>
  </div></header>
  <div role="main" class="ui-content">
