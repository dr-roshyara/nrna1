# Production Deployment Guide - Bug Fixes for 403 Errors

## Current Situation
- Local commits with bug fixes exist (commit 7552f25)
- Production server is at `/var/www/nrna-eu` (from logs)
- Need to deploy email validation and middleware fixes

## Option 1: Deploy via Git Pull (Recommended)

### Step 1: SSH into Production Server
```bash
ssh your-username@publicdigit.com
# Or: ssh roshyara@ubuntu-s-1vcpu-1gb-fra1-01
```

### Step 2: Navigate to Application Directory
```bash
cd /var/www/nrna-eu
```

### Step 3: Pull Latest Changes
```bash
# Backup current state first
git branch backup-$(date +%Y%m%d-%H%M%S)

# Fetch and pull changes
git fetch origin
git pull origin public-digit

# If you get conflicts, stash local changes first:
# git stash
# git pull origin public-digit
# git stash pop
```

### Step 4: Clear All Caches (CRITICAL)
```bash
# Clear application caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# If using OPcache, restart PHP-FPM
sudo systemctl restart php8.1-fpm  # Adjust version as needed
# Or: sudo systemctl restart php-fpm
```

### Step 5: Set Correct Permissions
```bash
sudo chown -R www-data:www-data /var/www/nrna-eu/storage
sudo chown -R www-data:www-data /var/www/nrna-eu/bootstrap/cache
sudo chmod -R 775 /var/www/nrna-eu/storage
sudo chmod -R 775 /var/www/nrna-eu/bootstrap/cache
```

### Step 6: Test the Slug
```bash
# Run debug command
php artisan debug:slug t6g2os_vJmpm3VYj7QM-UZmo8hRh4V8Uqf-

# Check logs in real-time
tail -f storage/logs/laravel.log
```

### Step 7: Test in Browser
Visit: `https://publicdigit.com/v/t6g2os_vJmpm3VYj7QM-UZmo8hRh4V8Uqf-/code/create`

Check logs for the new middleware debug messages.

---

## Option 2: Manual File Upload (If Git Fails)

If git pull doesn't work, manually upload these files to production:

1. `app/Http/Controllers/CodeController.php`
2. `app/Http/Controllers/VoteController.php`
3. `app/Http/Middleware/EnsureVoterSlugWindow.php`
4. `app/Http/Middleware/EnsureVoterStepOrder.php`
5. `app/Notifications/SendFirstVerificationCode.php`
6. `app/Notifications/SendVoteSavingCode.php`
7. `app/Console/Commands/DebugVoterSlug.php`
8. `routes/web.php`

Then run the cache clear commands from Step 4 above.

---

## Option 3: Deploy from Local Machine

### Fix SSH Key Issue First
```bash
# Generate new SSH key if needed
ssh-keygen -t ed25519 -C "your-email@example.com"

# Add to GitHub
cat ~/.ssh/id_ed25519.pub
# Copy output and add to GitHub -> Settings -> SSH Keys
```

### Then Push Changes
```bash
# Add remote if not already added
git remote -v

# Push to GitHub
git push origin public-digit

# Then SSH to production and pull (see Option 1)
```

---

## What Was Fixed

1. **Email Validation** - No more crashes when users don't have valid email addresses
2. **Middleware Logging** - Detailed logs to debug 403 errors
3. **Route Binding** - Better error handling for invalid slugs
4. **Defensive Checks** - Added instanceof checks throughout

## Expected Log Output After Deployment

After deployment, you should see these logs when accessing the slug:

```
[timestamp] local.INFO: EnsureVoterSlugWindow middleware check
[timestamp] local.INFO: Voting link validated successfully
```

Or if there's an issue:

```
[timestamp] local.WARNING: Invalid voting link - not a VoterSlug instance
[timestamp] local.WARNING: Voting link is not active
[timestamp] local.WARNING: Voting link has expired
```

## Troubleshooting

### If 403 persists after deployment:

1. Check the slug exists:
```sql
SELECT * FROM voter_slugs WHERE slug = 't6g2os_vJmpm3VYj7QM-UZmo8hRh4V8Uqf-';
```

2. Verify slug is active:
```sql
UPDATE voter_slugs
SET is_active = 1, expires_at = DATE_ADD(NOW(), INTERVAL 24 HOUR)
WHERE slug = 't6g2os_vJmpm3VYj7QM-UZmo8hRh4V8Uqf-';
```

3. Check route cache was cleared:
```bash
php artisan route:list | grep vslug
```

4. Verify file permissions:
```bash
ls -la storage/logs/
# Should be owned by www-data
```

## Contact

If issues persist, check:
- `storage/logs/laravel.log` for detailed error messages
- Run `php artisan debug:slug <slug>` for diagnosis
- Verify PHP version compatibility (requires PHP 8.0+)
