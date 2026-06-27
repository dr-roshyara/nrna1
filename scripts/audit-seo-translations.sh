#!/bin/bash

# ═══════════════════════════════════════════════════════════════
# audit-seo-translations.sh — SEO Translation Completeness Audit
#
# Governance Architecture:
#   Translation-First SEO
#   ├─ Step 1: Extract canonical page keys from English PHP
#   ├─ Step 2: Verify PHP locale files all have reference keys
#   ├─ Step 3: Verify useMeta()-referenced keys exist in Vue JSON
#   └─ Step 4: Report orphans (Vue JSON keys missing from PHP)
#
# Exit code 0 = all required SEO translations complete
# Exit code 1 = missing keys detected (build should fail)
# ═══════════════════════════════════════════════════════════════

set -euo pipefail
LC_ALL=C.UTF-8
LANG=C.UTF-8

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
PASS=0
FAIL=1
EXIT_CODE=0
HAS_WARNINGS=0

# Supported locales
LOCALES=("en" "de" "np")

echo ""
echo "╔══════════════════════════════════════════════════╗"
echo "║  🌐 SEO Translation Completeness Audit          ║"
echo "╚══════════════════════════════════════════════════╝"

# ──────────────────────────────────────────────
# Step 1: Extract canonical page keys from
#          English PHP reference
# ──────────────────────────────────────────────
echo ""
echo "📋 [1/4] Extracting canonical page keys from English PHP..."

PHP_REFERENCE="${PROJECT_ROOT}/resources/lang/en/seo.php"
if [ ! -f "$PHP_REFERENCE" ]; then
    echo "   ❌ Reference file not found: $PHP_REFERENCE"
    exit $FAIL
fi

# Extract page key names from PHP array using sed
REFERENCE_KEYS=$(sed -n "
    /'pages' => \[/,/^\];/ {
        /'[a-zA-Z0-9_.-]*'[[:space:]]*=>[[:space:]]*\[/ {
            /'pages'/d
            s/^[[:space:]]*'\([a-zA-Z0-9_.-]*\)'[[:space:]]*=>[[:space:]]*\[.*/\1/p
        }
    }
" "$PHP_REFERENCE" | sort -u || true)

REFERENCE_COUNT=$(echo "$REFERENCE_KEYS" | grep -c . || true)
if [ "$REFERENCE_COUNT" -eq 0 ]; then
    echo "   ❌ Failed to extract page keys from English seo.php"
    exit $FAIL
fi
echo "   ✅ Found ${REFERENCE_COUNT} canonical page keys"

# ──────────────────────────────────────────────
# Step 2: Verify all PHP locale files have all keys
# ──────────────────────────────────────────────
echo ""
echo "📁 [2/4] Checking PHP seo.php files..."

for locale in "${LOCALES[@]}"; do
    php_file="${PROJECT_ROOT}/resources/lang/${locale}/seo.php"
    if [ ! -f "$php_file" ]; then
        echo "   ❌ ${locale}: FILE MISSING"
        EXIT_CODE=$FAIL
        continue
    fi

    LOCALE_KEYS=$(sed -n "
        /'pages' => \[/,/^\];/ {
            /'[a-zA-Z0-9_.-]*'[[:space:]]*=>[[:space:]]*\[/ {
                /'pages'/d
                s/^[[:space:]]*'\([a-zA-Z0-9_.-]*\)'[[:space:]]*=>[[:space:]]*\[.*/\1/p
            }
        }
    " "$php_file" | sort -u || true)

    MISSING=$(comm -23 <(echo "$REFERENCE_KEYS") <(echo "$LOCALE_KEYS") || true)
    if [ -n "$MISSING" ]; then
        echo "   ❌ ${locale}: $(echo "$MISSING" | grep -c . || true) key(s) missing —"
        echo "$MISSING" | sed 's/^/       • /'
        EXIT_CODE=$FAIL
    else
        echo "   ✅ ${locale}: all ${REFERENCE_COUNT} keys present"
    fi
done

# ──────────────────────────────────────────────
# Step 3: Verify useMeta()-referenced keys
#          exist in Vue JSON for all locales
# ──────────────────────────────────────────────
echo ""
echo "📁 [3/4] Checking useMeta-referenced keys in Vue JSON..."

# Find all useMeta({ pageKey: '...' }) calls in Vue/JS/TS files
# A small set of page keys use seoTitle/seoDescription override params and
# don't need to resolve in the translation files. They are excluded here.
USEMETA_KEYS=$(grep -rho "pageKey: '[^']*'" \
    "${PROJECT_ROOT}/resources/js" \
    | sed "s/pageKey: '//; s/'//" \
    | sort -u || true)

# Known page keys that use seoTitle/seoDescription overrides
OVERRIDE_KEYS="newsletter-guide"

USEMETA_COUNT=$(echo "$USEMETA_KEYS" | grep -c . || true)
echo "   Found ${USEMETA_COUNT} unique page keys used by useMeta() in frontend"

for locale in "${LOCALES[@]}"; do
    vue_file="${PROJECT_ROOT}/resources/js/locales/${locale}.json"
    if [ ! -f "$vue_file" ]; then
        echo "   ❌ ${locale}.json: FILE MISSING"
        EXIT_CODE=$FAIL
        continue
    fi

    # Parse JSON with Node for accuracy
    JSON_KEYS=$(node -e "
        const fs = require('fs');
        try {
            const data = JSON.parse(fs.readFileSync(process.argv[1], 'utf8'));
            const pages = data.seo && data.seo.pages ? Object.keys(data.seo.pages) : [];
            console.log(pages.join('\\n'));
        } catch (e) { process.exit(1); }
    " "$vue_file" 2>/dev/null || echo "")

    # Check each useMeta key, filtering out known override keys
    MISSING_KEYS=""
    while IFS= read -r key; do
        [ -z "$key" ] && continue
        # Skip keys that have seoTitle/seoDescription overrides
        if echo "$OVERRIDE_KEYS" | grep -qxF "$key"; then
            continue
        fi
        if ! echo "$JSON_KEYS" | grep -qxF "$key"; then
            MISSING_KEYS="${MISSING_KEYS}${key}\n"
        fi
    done <<< "$USEMETA_KEYS"

    if [ -n "$MISSING_KEYS" ]; then
        echo "   ❌ ${locale}.json: $(echo -e "$MISSING_KEYS" | grep -c . || true) useMeta key(s) missing —"
        echo -e "$MISSING_KEYS" | sed '/^$/d' | sed 's/^/       • /'
        EXIT_CODE=$FAIL
    else
        echo "   ✅ ${locale}.json: all useMeta keys present"
    fi
done

# ──────────────────────────────────────────────
# Step 4: Orphan keys in Vue JSON
#          (not in PHP reference)
# ──────────────────────────────────────────────
echo ""
echo "🔍 [4/4] Checking for orphan keys (Vue-only, no PHP counterpart)..."

for locale in "${LOCALES[@]}"; do
    vue_file="${PROJECT_ROOT}/resources/js/locales/${locale}.json"
    [ ! -f "$vue_file" ] && continue

    JSON_KEYS=$(node -e "
        const fs = require('fs');
        try {
            const d = JSON.parse(fs.readFileSync(process.argv[1], 'utf8'));
            const pages = d.seo && d.seo.pages ? Object.keys(d.seo.pages) : [];
            console.log(pages.join('\\n'));
        } catch (e) { process.exit(1); }
    " "$vue_file" 2>/dev/null || echo "")

    ORPHANS=""
    while IFS= read -r key; do
        [ -z "$key" ] && continue
        if ! echo "$REFERENCE_KEYS" | grep -qxF "$key"; then
            ORPHANS="${ORPHANS}${key}\n"
        fi
    done <<< "$JSON_KEYS"

    if [ -n "$ORPHANS" ]; then
        echo "   ⚠️  ${locale}.json: $(echo -e "$ORPHANS" | grep -c . || true) orphan(s) —"
        echo -e "$ORPHANS" | sed '/^$/d' | sed 's/^/       • /'
        HAS_WARNINGS=1
    else
        echo "   ✅ ${locale}.json: no orphans"
    fi
done

# ──────────────────────────────────────────────
# Summary
# ──────────────────────────────────────────────
echo ""
echo "╔══════════════════════════════════════════════════╗"
if [ $EXIT_CODE -eq 0 ]; then
    echo "║  ✅ PASS: All SEO translations complete          ║"
    if [ $HAS_WARNINGS -eq 1 ]; then
        echo "║  ⚠️  With orphans (warnings only, not blocking)  ║"
    fi
else
    echo "║  ❌ FAIL: Missing SEO translations detected      ║"
    echo "║  Fix gaps above before deploying.                ║"
fi
echo "╚══════════════════════════════════════════════════╝"
echo ""

exit $EXIT_CODE
