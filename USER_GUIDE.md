# Company Pulse: User Guide

Company Pulse is a private social network for startup teams. Colleagues create a profile, find each other, follow one another, and exchange public messages or private whispers. It works in desktop and mobile browsers.

## 1. Getting started

### Requirements
- PHP 7.4 or newer with the `pdo_mysql` and `gd` extensions
- MySQL or MariaDB
- A modern browser and an internet connection (jQuery, jQuery Mobile and web fonts load from a CDN)

### First-time setup (macOS with Homebrew)
```bash
brew install php mysql
./setup_env.sh        # checks requirements, starts MySQL, creates the database and tables
./launch_website.sh   # serves the site at http://localhost:8000 and opens it
```
On another system, start MySQL yourself, open `http://localhost:8000/setup.php` once to create the tables, and run `php -S localhost:8000` from the project folder.

Database settings (host, name, user, password) are at the top of `includes/functions.php`.

### Optional demo data
```bash
php seed.php                 # eight sample members, friendships and messages
php seed.php your_username   # also connects the sample team to your account
```
Demo accounts include `alice_dev`, `ben_design` and `carla_pm`. They all use the password `Pulse123!`. Running the script again resets only these demo accounts.

## 2. Using the app

### Create an account
1. Choose **Sign Up** in the top navigation.
2. Type a username (3 to 16 letters, numbers or underscores). A message under the field shows immediately whether the name is available.
3. Choose a password of at least 6 characters and press **Sign Up**. You are signed in and taken to your profile.

### Log in and out
Use **Log In** with your username and password. **Log Out** ends your session and clears your session cookie. Pages for members only send you back to the login page if you are not signed in.

### Navigation
The tabs under the logo change with your login state. Guests see Home, Sign Up and Log In. Members see Home, Members, Friends, Messages, Profile and Log Out. Your avatar and name at the top right link to your profile.

### Your profile
Open **Profile** to write a short "About you" text and upload a picture.
- Accepted pictures: JPEG, PNG or GIF, up to 2 MB.
- Pictures are cropped to a square and resized to a 100 x 100 pixel thumbnail automatically.
- Press **Save profile**. You can update the text and the picture at any time.

To see someone else's profile, select their name anywhere in the app. From there you can send them a message.

### Members
**Members** lists everyone on the network, with their short bio. Press **Follow** to follow a colleague and **Unfollow** to stop. Following is one-way until the other person follows you back.

### Friends
**Friends** sorts your connections into three groups:
- **Mutual friends:** you follow each other. You can send them a message or **Remove** the connection.
- **Following you:** they follow you, but you don't follow them. Press **Follow back** to become mutual friends.
- **You are following:** you follow them, but they don't follow you yet.

### Messages
- **Public:** type a message, keep **Public** selected and press **Send**. Everyone on the network can read it.
- **Private whisper:** choose **Private whisper**, pick the recipient from the list that appears, and press **Send**. Only you and the recipient can see it. Whispers are highlighted and show who they were sent to.
- Each message shows the sender's picture and name and the time it was sent.
- **Delete:** you can delete messages you sent, and whispers you received. Deleting a whisper removes it for both people.

## 3. Privacy and security
- Passwords are stored as hashes, never as plain text.
- Database queries use PDO prepared statements, and all user text is escaped when displayed.
- Forms that change data include a CSRF token, and follow, unfollow and delete actions use POST requests.
- Whispers are filtered on the server so only the sender and recipient can see them. They are not end-to-end encrypted.

## 4. Troubleshooting

| Problem | What to try |
|---|---|
| "Invalid username or password" | Check capitalization. Passwords are case-sensitive. |
| Database error or blank page | Make sure MySQL is running (`brew services list`) and that you have run `setup_env.sh` or `setup.php`. |
| Picture will not upload | Use a JPEG, PNG or GIF under 2 MB. Check that the PHP `gd` extension is enabled (`php -m`). |
| Port already in use | Run `./launch_website.sh 8001` to use a different port. |
| Styles look wrong after an update | Refresh while holding Shift (Cmd+Shift+R on Mac) to clear the cached stylesheet. |

## 5. Project structure

| File or folder | Purpose |
|---|---|
| `setup.php`, `includes/functions.php` | Database setup. PDO connection, input cleaning, safe queries, sessions, CSRF. |
| `signup.php`, `checkuser.php`, `login.php`, `logout.php` | Accounts, live username check, sessions. |
| `includes/header.php`, `css/styles.css` | Shared header, navigation and responsive styles. |
| `profile.php` | Bio and picture upload. |
| `members.php`, `friends.php` | Member directory, following and connections. |
| `messages.php` | Public messages and private whispers. |
| `js/app.js` | Client-side behavior (username check, whisper recipient toggle). |
| `setup_env.sh`, `launch_website.sh`, `seed.php` | Setup, launch and demo data helpers. |
| `uploads/avatars/` | Processed profile pictures. |
