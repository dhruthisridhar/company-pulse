#!/usr/bin/env bash
# Company Pulse: check requirements, start MySQL and create the database tables (macOS + Homebrew).
set -e
cd "$(dirname "$0")"
step() { printf '\n==> %s\n' "$1"; }
have() { command -v "$1" >/dev/null 2>&1; }

step "Checking PHP"
have php || { echo "PHP not found. Install it with: brew install php"; exit 1; }
php -v | head -1
for ext in pdo_mysql gd; do
  php -m | grep -qi "^$ext$" || { echo "Missing PHP extension: $ext"; exit 1; }
done
echo "PHP extensions pdo_mysql and gd are available"

step "Checking MySQL"
have mysql || { echo "MySQL not found. Install it with: brew install mysql"; exit 1; }
if ! mysql -u root -e "SELECT 1" >/dev/null 2>&1; then
  if have brew; then echo "Starting MySQL..."; brew services start mysql >/dev/null; sleep 8
  else echo "Please start MySQL and run this script again."; exit 1; fi
fi
mysql -u root -e "SELECT 1" >/dev/null 2>&1 || { echo "Cannot connect to MySQL as root. Check DB_* in includes/functions.php"; exit 1; }
echo "MySQL is running"

step "Creating the database and tables"
php setup.php

step "Done"
echo "Optional demo data:  php seed.php"
echo "Start the site:      ./launch_website.sh"
