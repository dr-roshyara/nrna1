#!/bin/bash

# 🔍 Diagnostic Script for Circular Redirect Issue
# Run this script BEFORE clicking demo start button

echo "=========================================="
echo "DIAGNOSTIC: Pre-Click State"
echo "=========================================="

# 1. Clear logs
echo "1️⃣ Clearing logs..."
truncate -s 0 storage/logs/laravel.log
echo "✅ Logs cleared"

# 2. Get current user state
echo ""
echo "2️⃣ Current User State:"
php artisan tinker --execute="
\$user = Auth::user();
if (\$user) {
    echo '  User ID: ' . \$user->id . PHP_EOL;
    echo '  User Org: ' . \$user->organisation_id . PHP_EOL;
    echo '  User Email: ' . \$user->email . PHP_EOL;
} else {
    echo '  ❌ No authenticated user' . PHP_EOL;
}
"

# 3. Check available demo elections
echo ""
echo "3️⃣ Available Demo Elections:"
php artisan tinker --execute="
\$demos = DB::table('elections')->where('type', 'demo')->get(['id', 'name', 'organisation_id']);
foreach (\$demos as \$demo) {
    echo '  - ID: ' . \$demo->id . ', Org: ' . \$demo->organisation_id . ', Name: ' . \$demo->name . PHP_EOL;
}
"

# 4. Check existing voter slugs for this user
echo ""
echo "4️⃣ Existing Voter Slugs:"
php artisan tinker --execute="
\$user = Auth::user();
if (\$user) {
    \$slugs = DB::table('voter_slugs')->where('user_id', \$user->id)->get(['id', 'slug', 'election_id', 'organisation_id', 'is_active', 'expires_at']);
    if (\$slugs->count() > 0) {
        foreach (\$slugs as \$slug) {
            echo '  - ID: ' . \$slug->id . ', Active: ' . \$slug->is_active . ', Election: ' . \$slug->election_id . ', Org: ' . \$slug->organisation_id . PHP_EOL;
        }
    } else {
        echo '  ℹ️ No existing slugs' . PHP_EOL;
    }
}
"

echo ""
echo "=========================================="
echo "✅ Ready to reproduce!"
echo "=========================================="
echo ""
echo "NEXT STEPS:"
echo "1. Click the Demo Election Start button in your browser"
echo "2. Wait for the redirect loop to occur"
echo "3. Run: tail -100 storage/logs/laravel.log | grep -E '(🎬|✅|❌|ORGANISATION|Election|slug)'"
echo "4. Share the output with diagnostic details"
echo ""
