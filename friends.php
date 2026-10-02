<?php
require_once 'includes/functions.php'; requireLogin(); handleFollow();
$me = $_SESSION['user'];
$following = column('SELECT friend FROM friends WHERE user=? ORDER BY friend', [$me]);
$followers = column('SELECT user FROM friends WHERE friend=? ORDER BY user', [$me]);
$mutual = array_intersect($following, $followers);
$title = 'Friends'; require 'includes/header.php';
function section($heading, $list, $render) {
    echo "<h3>$heading</h3>";
    if (!$list) echo '<p><em>Nobody here yet.</em></p>';
    foreach ($list as $u) echo userCard($u, $render($u));
}
?>
<h2>Your connections</h2>
<?php
section('Mutual friends', $mutual, fn($u) => '<a href="messages.php?to=' . urlencode($u) . '" data-ajax="false" class="ui-btn ui-btn-inline ui-mini ui-corner-all">Message</a>' . followButton($u, 'unfollow', 'Remove'));
section('Following you', array_diff($followers, $following), fn($u) => followButton($u, 'follow', 'Follow back'));
section('You are following', array_diff($following, $followers), fn($u) => followButton($u, 'unfollow', 'Unfollow'));
pageEnd();
