#!/usr/bin/env bash
# Company Pulse: make sure MySQL is up, then serve the site.
#   ./launch_website.sh                 serve at http://localhost:8000 (this computer only)
#   ./launch_website.sh 8001            use another port
#   ./launch_website.sh --network       also reachable from other devices on the same Wi-Fi (for testers)
cd "$(dirname "$0")"
HOST=localhost
PORT=8000
for arg in "$@"; do
  case "$arg" in
    --network) HOST=0.0.0.0 ;;
    ''|*[!0-9]*) echo "Usage: ./launch_website.sh [--network] [port]"; exit 1 ;;
    *) PORT="$arg" ;;
  esac
done

if ! mysql -u root -e "SELECT 1" >/dev/null 2>&1; then
  echo "MySQL is not reachable. Trying to start it with Homebrew..."
  brew services start mysql >/dev/null 2>&1 && sleep 6
fi
if lsof -ti ":$PORT" >/dev/null 2>&1; then
  echo "Port $PORT is already in use. Try another one, for example: ./launch_website.sh 8001"
  exit 1
fi

if [ "$HOST" = "0.0.0.0" ]; then
  IP=$(ipconfig getifaddr en0 2>/dev/null || ipconfig getifaddr en1 2>/dev/null)
  if [ -z "$IP" ]; then echo "Could not find your Wi-Fi address. Connect to Wi-Fi and try again."; exit 1; fi
  echo "Company Pulse is running  (press Ctrl+C to stop)"
  echo "  On this computer:   http://localhost:$PORT"
  echo "  Network (testers):  http://$IP:$PORT"
  echo "Only share this on a network you trust, and stop the server when the session is over."
  export PHP_CLI_SERVER_WORKERS=4
else
  echo "Company Pulse is running at http://localhost:$PORT  (press Ctrl+C to stop)"
fi
if command -v open >/dev/null 2>&1; then ( sleep 1; open "http://localhost:$PORT/index.php" ) >/dev/null 2>&1 & fi
exec php -S "$HOST:$PORT"
