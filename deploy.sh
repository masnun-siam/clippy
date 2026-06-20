#!/bin/bash
set -e

# ──────────────────────────────────────────────
# cPanel Deployment Script for Laravel
# Generates a ready-to-upload zip archive
# ──────────────────────────────────────────────

PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"
OUTPUT_NAME="clippy-deploy-$(date +%Y%m%d-%H%M%S).zip"
OUTPUT_PATH="$PROJECT_DIR/$OUTPUT_NAME"
TEMP_DIR="$PROJECT_DIR/.deploy-temp"

# Cleanup on failure or interrupt
cleanup() {
    echo ""
    echo "Cleaning up temp files..."
    rm -rf "$TEMP_DIR"
    # Remove temp .env if we created one
    if [ "$CREATED_ENV" = "1" ] && [ -f "$PROJECT_DIR/.env" ]; then
        rm -f "$PROJECT_DIR/.env"
        rm -f "$PROJECT_DIR/bootstrap/cache/config.php"
        rm -f "$PROJECT_DIR/bootstrap/cache/routes-v7.php"
        rm -f "$PROJECT_DIR/bootstrap/cache/compiled.php"
    fi
}
CREATED_ENV=0
trap cleanup EXIT

echo "━━━ Clippy cPanel Deploy ━━━"
echo ""

# ── Clean previous temp & zips ──
rm -rf "$TEMP_DIR"
rm -f "$PROJECT_DIR"/clippy-deploy-*.zip
mkdir -p "$TEMP_DIR"

# ── 1. Install production PHP dependencies ──
echo "[1/4] Installing composer deps (no-dev)..."
composer install --no-dev --optimize-autoloader --no-interaction

# ── 2. Build frontend assets ──
echo "[2/4] Building frontend assets..."
if [ -f "$PROJECT_DIR/package-lock.json" ]; then
    npm ci
else
    npm install
fi
npm run build

# ── 3. Create .env locally if missing (needed for artisan to work) ──
if [ ! -f "$PROJECT_DIR/.env" ]; then
    cp "$PROJECT_DIR/.env.example" "$PROJECT_DIR/.env"
    php artisan key:generate --no-interaction
    CREATED_ENV=1
fi

# ── 4. Copy files to temp dir ──
echo "[3/4] Preparing archive..."
rsync -a --delete \
    --exclude='.git' \
    --exclude='.github' \
    --exclude='.claude' \
    --exclude='.a5c' \
    --exclude='node_modules' \
    --exclude='tests' \
    --exclude='docker-compose.yml' \
    --exclude='.env' \
    --exclude='.env.example' \
    --exclude='deploy.sh' \
    --exclude='.DS_Store' \
    --exclude='phpunit.xml' \
    --exclude='.phpunit.result.cache' \
    --exclude='.phpcd.vim' \
    --exclude='.editorconfig' \
    --exclude='.gitattributes' \
    --exclude='.gitignore' \
    --exclude='.autoload.php' \
    --exclude='_ide_helper.php' \
    --exclude='_ide_helper_models.php' \
    --exclude='clippy-deploy-*.zip' \
    --exclude='.scribe' \
    "$PROJECT_DIR/" "$TEMP_DIR/"

# Copy .env.example as reference — strip APP_KEY so key:generate works on server
cp "$PROJECT_DIR/.env.example" "$TEMP_DIR/.env.example"
sed -i.bak 's/^APP_KEY=.*/APP_KEY=/' "$TEMP_DIR/.env.example"
rm -f "$TEMP_DIR/.env.example.bak"

# Create storage directory structure (rsync may not preserve empty dirs)
mkdir -p "$TEMP_DIR/storage/app/public"
mkdir -p "$TEMP_DIR/storage/framework/cache/data"
mkdir -p "$TEMP_DIR/storage/framework/sessions"
mkdir -p "$TEMP_DIR/storage/framework/testing"
mkdir -p "$TEMP_DIR/storage/framework/views"
mkdir -p "$TEMP_DIR/storage/logs"

# Include a server-side setup script
cat >"$TEMP_DIR/setup.sh" <<'SETUP'
#!/bin/bash
set -e
echo "Setting up Clippy..."
cp .env.example .env
chmod -R 775 storage bootstrap/cache
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
php artisan migrate --force
echo "Done! Edit .env if you need to change DB creds or APP_URL."
SETUP
chmod +x "$TEMP_DIR/setup.sh"

# ── 5. Create zip ──
echo "[4/4] Creating zip..."
cd "$TEMP_DIR"
zip -r "$OUTPUT_PATH" . -q
cd "$PROJECT_DIR"

# ── Cleanup ──
rm -rf "$TEMP_DIR"

SIZE=$(du -h "$OUTPUT_PATH" | cut -f1)
echo ""
echo "━━━ Done ━━━"
echo "Archive: $OUTPUT_NAME ($SIZE)"
echo ""
echo "cPanel upload steps:"
echo "  1. Upload $OUTPUT_NAME to your domain root in File Manager"
echo "  2. Extract it there"
echo "  3. Set document root to the 'public/' subfolder"
echo "     (cPanel → Domains → Document Root → /home/USER/clippy/public)"
echo "  4. SSH in and run:"
echo "       bash setup.sh"
echo "     This will: copy .env, set permissions, generate key, link storage, migrate"
