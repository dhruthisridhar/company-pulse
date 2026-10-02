<?php
require_once 'includes/functions.php'; requireLogin(); handleFollow();
$me = $_SESSION['user'];
$following = column('SELECT friend FROM friends WHERE user=?', [$me]);
$members = column('SELECT user FROM members WHERE user <> ? ORDER BY user', [$me]);
$title = 'Members'; require 'includes/header.php';
?>
<h2>Members</h2>
<?php foreach ($members as $u)
    echo userCard($u, in_array($u, $following) ? followButton($u, 'unfollow', 'Unfollow') : followButton($u, 'follow', 'Follow'));
if (!$members) echo '<p>No other members yet. Invite a teammate!</p>';
pageEnd();
