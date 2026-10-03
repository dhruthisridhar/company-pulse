#!/usr/bin/env bash
# Company Pulse: make sure MySQL is up, then serve the site (default http://localhost:8000).
cd "$(dirname "$0")"
PORT="${1:-8000}"

if ! mysql -u root -e "SELECT 1" >/dev/null 2>&1; then
  echo "MySQL is not reachable. Trying to start it with Homebrew..."
  brew services start mysql >/dev/null 2>&1 && sleep 6
fi
if lsof -ti ":$PORT" >/dev/null 2>&1; then
  echo "Port $PORT is already in use. Try another one, for example: ./launch_website.sh 8001"
  exit 1
fi

echo "Company Pulse is running at http://localhost:$PORT  (press Ctrl+C to stop)"
if command -v open >/dev/null 2>&1; then ( sleep 1; open "http://localhost:$PORT/index.php" ) >/dev/null 2>&1 & fi
exec php -S "localhost:$PORT"
