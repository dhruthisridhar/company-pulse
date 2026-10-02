# Company Pulse
Internal social network for startup employees. Stack: HTML5, CSS3, JavaScript, jQuery, jQuery Mobile, PHP (PDO), MySQL.

## Run locally (XAMPP / MAMP / any PHP 7.4+ with MySQL and GD)
1. Copy this folder into your web root (e.g. `htdocs/company-pulse`).
2. Start Apache and MySQL. Edit `DB_*` in `includes/functions.php` if your MySQL login differs.
3. Open `http://localhost/company-pulse/setup.php` once to create the database and tables.
4. Open `index.php`, sign up, and create a second account to try follows and messages.

## Files
| File | Purpose |
|---|---|
| setup.php, includes/functions.php | Database setup; PDO connection, input cleaning, safe queries, sessions, CSRF |
| signup.php, checkuser.php, login.php, logout.php | Accounts, AJAX username check, sessions |
| includes/header.php, css/styles.css | Shared header, login-aware navigation, responsive styles |
| profile.php | Bio and picture upload (resized to 100x100) |
| members.php, friends.php | Directory, follow/unfollow, mutual / followers / following |
| messages.php | Public messages and private whispers, delete from own feed |
