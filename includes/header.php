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
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<link rel="stylesheet" href="css/styles.css">
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>$(document).on('mobileinit', function () { $.mobile.ajaxEnabled = false; });</script>
<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
</head>
<body>
<div data-role="page">
  <div data-role="header" class="pulse-header">
    <h1>&#9889; Company Pulse</h1>
    <div data-role="navbar"><ul>
      <?php foreach ($nav as $file => $label): ?>
        <li><a href="<?= $file ?>" data-ajax="false" class="<?= $page === $file ? 'ui-btn-active' : '' ?>"><?= $label ?></a></li>
      <?php endforeach; ?>
    </ul></div>
  </div>
  <div role="main" class="ui-content">
