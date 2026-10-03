# Company Pulse

An internal social network for startup teams. Colleagues create profiles, find and follow each other, and exchange public messages or private whispers. It works in desktop and mobile browsers.

## Features

- **Accounts:** sign up with a live username availability check (AJAX), log in with PHP sessions, and log out with the session and cookie cleared.
- **Profiles:** a short bio and a profile picture (JPEG, PNG or GIF) that is automatically cropped and resized to a 100 x 100 thumbnail.
- **Members and friends:** a directory of everyone on the network. Follow, unfollow, and see connections grouped as mutual friends, people following you, and people you follow.
- **Messaging:** public posts and private whispers that only the sender and recipient can see. Each message shows the sender, time and picture, and can be deleted from your own feed.
- **Responsive design:** a shared header with navigation that changes with login state, built with jQuery Mobile and custom CSS.

## Technology

| Layer | Tools |
|---|---|
| Frontend | HTML5, CSS3, JavaScript, jQuery, jQuery Mobile |
| Backend | PHP with PDO |
| Database | MySQL |
| Tooling | Shell scripts for setup and launch |

## Quick start (macOS with Homebrew)

```bash
brew install php mysql
./setup_env.sh          # checks requirements, starts MySQL, creates the database and tables
./launch_website.sh     # serves the site at http://localhost:8000 and opens it
```

Requirements: PHP 7.4 or newer with the `pdo_mysql` and `gd` extensions, MySQL or MariaDB, and an internet connection (jQuery, jQuery Mobile and web fonts load from a CDN).

On other systems, start MySQL, open `http://localhost:8000/setup.php` once to create the tables, and run `php -S localhost:8000` from the project folder.

Database settings are at the top of `includes/functions.php`.

### Demo data (optional)

```bash
php seed.php                  # eight sample members, friendships and messages
php seed.php your_username    # also connects the sample team to your account
```

Demo accounts such as `alice_dev` and `ben_design` use the password `Pulse123!`. Running the script again resets only those accounts.

## Project structure

| Path | Purpose |
|---|---|
| `setup.php`, `includes/functions.php` | Database setup. PDO connection, input cleaning, safe queries, sessions, CSRF. |
| `signup.php`, `checkuser.php`, `login.php`, `logout.php` | Accounts and sessions. |
| `includes/header.php`, `css/styles.css` | Shared header, navigation and styles. |
| `index.php`, `profile.php` | Home page and profiles. |
| `members.php`, `friends.php` | Member directory and connections. |
| `messages.php` | Public messages and private whispers. |
| `js/app.js` | Client-side behavior (username check, whisper recipient toggle). |
| `setup_env.sh`, `launch_website.sh`, `seed.php` | Setup, launch and demo data. |
| `uploads/avatars/` | Processed profile pictures (not tracked in git). |

## Security

- Passwords are stored as hashes.
- All database access uses PDO prepared statements, and output is escaped to prevent XSS.
- Forms that change data include a CSRF token, and follow, unfollow and delete actions use POST.
- Uploaded images are validated by their real type and re-encoded as JPEG.
- Whispers are filtered on the server so only the sender and recipient can see them. They are not end-to-end encrypted.

## Documentation

- [User guide](USER_GUIDE.md): setup, every feature, and troubleshooting.
- [Usability study](USABILITY_STUDY.md): test script, observation sheet and findings.
