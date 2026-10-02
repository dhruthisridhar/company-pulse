<?php
// AJAX endpoint: is this username available?
require_once 'includes/functions.php';
$u = sanitize($_GET['user'] ?? '');
if (!validUsername($u)) echo '<span class="bad">&#10007; 3-16 letters, numbers or underscores</span>';
elseif (memberExists($u)) echo '<span class="bad">&#10007; Username taken</span>';
else echo '<span class="ok">&#10003; Username available</span>';
