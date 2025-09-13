# Election Results Management Guide

## Overview

This guide explains how to publish and unpublish election results in the NRNA Germany Election System. The system uses a secure permission-based approach that doesn't require modifying configuration files, allowing for safe and reversible result management.

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Initial Setup](#initial-setup)
3. [Publishing Results](#publishing-results)
4. [Unpublishing Results](#unpublishing-results)
5. [Web Interface Management](#web-interface-management)
6. [Verification](#verification)
7. [User Experience](#user-experience)
8. [Troubleshooting](#troubleshooting)
9. [Technical Details](#technical-details)

---

## Prerequisites

### Required Access
- Server/terminal access to run Artisan commands
- Database access for the application
- Admin/committee member permissions

### System Requirements
- Laravel application with Spatie Permission package installed
- Database connection working
- Election system properly configured

---

## Initial Setup

### Step 1: Seed Election Permissions

Before publishing results for the first time, you need to set up the permission system:

```bash
php artisan db:seed --class=ElectionPermissionSeeder
```

This command creates:
- **Permissions**: `publish-election-results`, `view-election-results`, `manage-election-settings`, etc.
- **Role**: `election-committee` with all election permissions
- **Database tables**: Required permission structures

### Step 2: Assign Committee Role

Assign the `election-committee` role to appropriate users:

```bash
# Via Laravel Tinker
php artisan tinker

# In Tinker console:
$user = App\Models\User::find(USER_ID);
$user->assignRole('election-committee');
```

Or via the application's admin interface if available.

---

## Publishing Results

### Method 1: Interactive Command (Recommended)

```bash
php artisan election:publish-results
```

This command will:
1. Check if results are already published
2. Prompt for confirmation: "Are you sure you want to publish the election results?"
3. Publish results if confirmed
4. Display success message with access information

**Sample Output:**
```
Are you sure you want to publish the election results? (yes/no) [no]:
> yes

✅ Election results have been successfully published!
🔗 Results are now available at: /election/result
📊 Voters can now access the election results from the dashboard.
```

### Method 2: Non-Interactive Command

For scripts or automated processes:

```bash
php artisan election:publish-results --confirm
```

This skips the confirmation prompt and publishes immediately.

### Method 3: Via Application Interface

If you have admin interface access:
1. Log in as a user with `publish-election-results` permission
2. Navigate to `/election/management`
3. Use the "Publish Results" button

---

## Unpublishing Results

### Method 1: Interactive Command (Recommended)

```bash
php artisan election:unpublish-results
```

This command will:
1. Check if results are currently published
2. Show current status and prompt for confirmation
3. Unpublish results if confirmed
4. Display success message

**Sample Output:**
```
📊 Current status: Results are published and available to voters
Are you sure you want to unpublish the election results? This will make them unavailable to voters. (yes/no) [no]:
> yes

✅ Election results have been successfully unpublished!
🔒 Results are now unavailable at: /election/result
📊 Only users with "view-election-results" permission can now access results.
```

### Method 2: Non-Interactive Command

```bash
php artisan election:unpublish-results --confirm
```

### Method 3: Via Application Interface

1. Log in as a user with `publish-election-results` permission
2. Navigate to `/election/management`
3. Use the "Unpublish Results" button

---

## Web Interface Management

### Accessing the Election Management Dashboard

For users who prefer a graphical interface:

1. **Login Requirements**:
   - Must have `manage-election-settings` permission
   - Must be assigned to `election-committee` role or have individual permission

2. **Access the Dashboard**:
   ```
   Navigate to: /election/management
   ```

3. **Available Actions**:
   - **View Current Status**: See if results are published or unpublished
   - **Publish Results**: Click "Publish Results" button
   - **Unpublish Results**: Click "Unpublish Results" button
   - **Real-time Status**: Dashboard updates automatically

### API Endpoints

For programmatic access or custom interfaces:

#### Publish Results
```bash
POST /election/publish-results
Authorization: Bearer <token> or authenticated session
```

#### Unpublish Results
```bash
POST /election/unpublish-results
Authorization: Bearer <token> or authenticated session
```

#### Get Status
```bash
GET /election/status
Authorization: Bearer <token> or authenticated session
```

**Sample Response:**
```json
{
  "electionStatus": {
    "is_active": true,
    "results_published": false
  },
  "permissions": {
    "canPublishResults": true,
    "canViewResults": true,
    "canManageSettings": true
  }
}
```

---

## Verification

### Check Publication Status

Multiple ways to verify the current status:

#### Method 1: Using Scripts
```bash
# Use the provided utility script
php scripts/check_roles.php
```

#### Method 2: Via Tinker
```bash
php artisan tinker

# In Tinker:
use App\Services\ElectionService;
ElectionService::areResultsPublished(); // Returns true if published
```

#### Method 3: Database Verification
```sql
SELECT * FROM permissions WHERE name = 'results-published-flag';
```
- **Results Published**: Returns one record
- **Results Unpublished**: Returns no records

#### Method 4: API Status Check
```bash
curl -X GET /election/status -H "Authorization: Bearer <token>"
```

#### Method 5: Web Interface
- Navigate to `/election/management` to see real-time status

---

## User Experience

### Results Unpublished State (Default)

**Dashboard Appearance:**
- Results card appears **gray/disabled**
- Text: "परिणाम अनुपलब्ध | Results Unavailable"
- Status: "परिणाम अझै उपलब्ध छैन | Results Not Available"
- Not clickable

**Direct Access:**
- `/election/result` redirects back with error message
- Error: "Election results will be available after the election is completed."
- Only committee members with `view-election-results` permission can access

### Results Published State

**Dashboard Appearance:**
- Results card appears **green/active**
- Text: "परिणाम उपलब्ध | Results Available"
- Clickable link to results page
- Hover effects enabled

**Direct Access:**
- `/election/result` shows full election results
- **All users** can access results page (authentication still required)
- Results display vote counts, percentages, and winners

### State Transitions

**Publishing (Unpublished → Published):**
- Command: `php artisan election:publish-results`
- Web: Click "Publish Results" button
- Effect: Creates `results-published-flag` permission

**Unpublishing (Published → Unpublished):**
- Command: `php artisan election:unpublish-results`
- Web: Click "Unpublish Results" button
- Effect: Removes `results-published-flag` permission

---

## Troubleshooting

### Common Issues

#### 1. "Permission denied" or "Access denied"

**Cause:** User doesn't have `publish-election-results` permission.

**Solution:**
```bash
# Assign permission directly
php artisan tinker
$user = App\Models\User::find(USER_ID);
$user->givePermissionTo('publish-election-results');

# Or assign election-committee role
$user->assignRole('election-committee');
```

#### 2. "Results already published" warning

**Cause:** Results were previously published.

**Solution:** This is normal. Results are already available to users.

**To unpublish:**
```bash
# Via command
php artisan election:unpublish-results

# Via web interface
# Navigate to /election/management and click "Unpublish Results"
```

#### 3. Frontend still shows "Results Unavailable"

**Causes:**
- Cache not cleared
- Database connection issue
- Permission record not created

**Solutions:**
```bash
# Clear application cache
php artisan cache:clear
php artisan config:clear

# Check database connection
php artisan tinker
use App\Services\ElectionService;
ElectionService::areResultsPublished();
```

#### 4. Results page shows empty data

**Cause:** No vote data in database or calculation issues.

**Solution:**
- Verify vote data exists in the database
- Check `ResultController.php:21` for results calculation logic
- Ensure posts and candidates are properly configured

### Error Logs

Check Laravel logs for detailed error information:

```bash
tail -f storage/logs/laravel.log
```

---

## Technical Details

### How It Works

The system uses **Spatie Laravel Permission** to manage result publication:

1. **Permission Flag**: Creates a special permission `results-published-flag`
2. **Service Layer**: `ElectionService` manages all result publication logic
3. **Caching**: Results publication status is cached for 5 minutes
4. **Database-Driven**: No configuration file modifications

### Key Files

| File | Purpose |
|------|---------|
| `app/Console/Commands/PublishResults.php` | Artisan command |
| `app/Services/ElectionService.php` | Business logic |
| `database/seeders/ElectionPermissionSeeder.php` | Permission setup |
| `app/Http/Controllers/ResultController.php` | Results display |
| `app/Http/Controllers/Election/ElectionController.php` | Dashboard status |

### Database Tables

| Table | Purpose |
|-------|---------|
| `permissions` | Stores `results-published-flag` |
| `roles` | Contains `election-committee` role |
| `model_has_permissions` | User-permission relationships |
| `role_has_permissions` | Role-permission relationships |

### Security Features

- **Permission-based access control**
- **Role-based management**
- **No direct file system modifications**
- **Audit trail through permissions**
- **Cacheable and performant**

---

## Advanced Usage

### Programmatic Access

```php
use App\Services\ElectionService;

// Check if results are published
if (ElectionService::areResultsPublished()) {
    // Results are available to everyone
}

// Publish results programmatically
ElectionService::publishResults();

// Unpublish results programmatically
ElectionService::unpublishResults();

// Check user permissions
if (ElectionService::canPublishResults($user)) {
    // User can publish/unpublish results
}

if (ElectionService::canViewResults($user)) {
    // User can view results (even if unpublished)
}

// Get complete election status
$status = ElectionService::getElectionStatus();
// Returns: ['is_active' => true, 'results_published' => false]
```

### Custom Roles and Permissions

Create custom roles with specific election permissions:

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Create election observer role (can view unpublished results)
$observerRole = Role::create(['name' => 'election-observer']);
$observerRole->givePermissionTo(['view-election-results']);

// Create election administrator (full control)
$adminRole = Role::create(['name' => 'election-admin']);
$adminRole->givePermissionTo([
    'publish-election-results',
    'view-election-results',
    'manage-election-settings',
    'approve-voters',
    'manage-candidates',
    'export-election-data'
]);

// Assign roles to users
$user->assignRole('election-observer');
$user->assignRole('election-admin');
```

### Available Commands Summary

```bash
# Setup
php artisan db:seed --class=ElectionPermissionSeeder

# Publishing
php artisan election:publish-results         # Interactive
php artisan election:publish-results --confirm  # Non-interactive

# Unpublishing
php artisan election:unpublish-results       # Interactive
php artisan election:unpublish-results --confirm # Non-interactive

# Verification
php scripts/check_roles.php                  # Check roles and permissions

# User Management
php artisan tinker                           # Assign roles/permissions
```

---

## Support

### Getting Help

1. **Check logs**: `storage/logs/laravel.log`
2. **Verify database**: Ensure permissions table has data
3. **Test permissions**: Use Tinker to debug permission issues
4. **Clear cache**: Run cache clearing commands

### Contact Information

For technical support with the election system, contact the development team or system administrator.

---

*Last Updated: $(date)*
*Version: 2.0*
*NRNA Germany Election System*