#!/bin/bash
set -e

# ─── Generate .env from environment variables ────────────────────────────────
# Only write .env if it doesn't already exist (allows volume-mounted overrides)
if [ ! -f /var/www/.env ]; then
    echo "Generating .env file..."
    cat > /var/www/.env <<EOF
APP_NAME="${APP_NAME:-Clinic Appointment System}"
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY:-}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=${LOG_LEVEL:-error}

DB_CONNECTION=sqlite
DB_DATABASE=${DB_DATABASE:-/var/www/database/database.sqlite}

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

CACHE_STORE=file

MAIL_MAILER=log
MAIL_FROM_ADDRESS="${MAIL_FROM_ADDRESS:-hello@example.com}"
MAIL_FROM_NAME="\${APP_NAME}"

VITE_APP_NAME="\${APP_NAME}"
EOF
fi

# ─── Generate APP_KEY if missing ─────────────────────────────────────────────
if grep -q '^APP_KEY=$' /var/www/.env || grep -q "^APP_KEY=\"\"$" /var/www/.env; then
    echo "Generating APP_KEY..."
    php /var/www/artisan key:generate --force
fi

# ─── Ensure database directory and file exist ────────────────────────────────
DB_PATH="${DB_DATABASE:-/var/www/database/database.sqlite}"
DB_DIR="$(dirname "$DB_PATH")"

echo "Ensuring database directory exists: $DB_DIR"
mkdir -p "$DB_DIR"
chown -R www-data:www-data "$DB_DIR"

if [ ! -f "$DB_PATH" ]; then
    echo "Creating SQLite database file: $DB_PATH"
    touch "$DB_PATH"
    chown www-data:www-data "$DB_PATH"
fi

# ─── Clear config cache and run migrations ───────────────────────────────────
echo "Clearing config cache..."
php /var/www/artisan config:clear

echo "Running database migrations..."
php /var/www/artisan migrate --force

# ─── Cache config for production performance ─────────────────────────────────
echo "Caching configuration..."
php /var/www/artisan config:cache
php /var/www/artisan route:cache
php /var/www/artisan view:cache

# ─── Configure Apache to listen on port 8080 ─────────────────────────────────
PORT="${PORT:-8080}"
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

echo "Starting Apache on port ${PORT}..."
exec apache2-foreground
