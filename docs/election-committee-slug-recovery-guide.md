# Election Committee: Voter Slug Recovery Guide

## Overview

This guide provides step-by-step instructions for election committee members to help voters who have expired or problematic voting links (slugs). The recovery system ensures secure, auditable assistance while maintaining election integrity.

## 🔧 Prerequisites

- Access to the server command line
- Election committee member permissions
- Basic familiarity with Laravel Artisan commands

## 📋 When to Use Slug Recovery

### Common Scenarios:
- ✅ Voter reports "My voting link expired"
- ✅ Voter says "Link doesn't work anymore"
- ✅ Voter started voting but got interrupted/disconnected
- ✅ Voter accidentally closed browser during voting
- ✅ Technical issues prevented voting completion

### When NOT to Use:
- ❌ Voter has already completed voting (`has_voted = true`)
- ❌ Voter is not eligible to vote (`can_vote = false`)
- ❌ Voter is not registered (`is_voter = false`)

## 🚀 Quick Start Guide

### Step 1: Find the Voter's User ID

```bash
# Option 1: Search by email
php artisan tinker
>>> User::where('email', 'voter@example.com')->first();
>>> exit

# Option 2: Search by name (partial match)
php artisan tinker
>>> User::where('name', 'LIKE', '%John%')->get(['id', 'name', 'email']);
>>> exit

# Option 3: Search by NRNA ID
php artisan tinker
>>> User::where('nrna_id', '12345')->first();
>>> exit
```

### Step 2: Generate Recovery Slug

```bash
php artisan election:recover-voter {USER_ID} --reason="Brief explanation" --admin="Your Name"
```

### Step 3: Provide URL to Voter

The command will output a recovery URL. Send this to the voter via secure communication.

## 📖 Detailed Usage Instructions

### Command Syntax

```bash
php artisan election:recover-voter {user_id} [OPTIONS]
```

**Parameters:**
- `{user_id}` - Required: The database ID of the voter
- `--reason="text"` - Optional: Reason for recovery (default: "Expired slug recovery")
- `--admin="name"` - Optional: Your name for audit logs (default: "Election Committee")

### Example Commands

```bash
# Basic usage
php artisan election:recover-voter 123

# With custom reason and admin name
php artisan election:recover-voter 456 --reason="User reported technical issues during Step 2" --admin="Sarah Johnson"

# Emergency recovery
php artisan election:recover-voter 789 --reason="Server maintenance interrupted voting" --admin="IT Support Team"
```

## 🔍 Step-by-Step Walkthrough

### Example: Helping John Smith (User ID: 150)

**1. Voter Contact:**
> "Hi, I was voting but my link stopped working. It says expired."

**2. Find User ID:**
```bash
php artisan tinker
>>> User::where('name', 'LIKE', '%John Smith%')->first(['id', 'name', 'email']);
=> App\Models\User {#4850
     id: 150,
     name: "John Smith",
     email: "john.smith@example.com",
   }
>>> exit
```

**3. Generate Recovery:**
```bash
php artisan election:recover-voter 150 --reason="User reported expired link during voting" --admin="Election Committee"
```

**4. Command Output:**
```
🔍 User Found:
   Name: John Smith
   Email: john.smith@example.com
   NRNA ID: 12345
   Is Voter: Yes
   Can Vote: Yes
   Has Voted: No

🔍 Current Slugs:
   abc123def - Step 2 - INACTIVE (EXPIRED)
   Created: 2024-01-15 14:30:00, Expires: 2024-01-15 15:00:00

❓ Generate recovery slug for John Smith? (yes/no) [no]:
> yes

✅ Deactivated 1 existing slug(s)

🎉 Recovery slug generated successfully!
   Slug: xyz789new
   Expires: 2024-01-15 16:30:00
   Step: 1

🔗 Recovery URL:
   http://localhost:8000/v/xyz789new/code/create

📋 Instructions:
   1. Provide this URL to the voter
   2. The voter can use this link to continue/restart voting
   3. This slug expires in 30 minutes
   4. The voter will start from step 1 (code verification)
```

**5. Contact Voter:**
> "I've generated a new voting link for you: http://localhost:8000/v/xyz789new/code/create
> Please use this link within the next 30 minutes to complete your voting."

## 🛡️ Security Features

### Automatic Protections

- **Eligibility Check**: Verifies voter can still vote
- **Completion Check**: Prevents recovery for completed voters
- **Slug Deactivation**: Automatically deactivates old/expired slugs
- **Time Limits**: Recovery slugs expire in 30 minutes
- **Audit Logging**: All actions logged for election integrity

### What Gets Logged

Every recovery action creates audit logs including:
- Admin name and timestamp
- Voter information
- Reason for recovery
- Old slug(s) deactivated
- New slug details
- IP address and session info

## 🔍 Troubleshooting

### Error: "User not found"
```bash
❌ User with ID 123 not found.
```
**Solution:** Double-check the user ID. Search again by email/name.

### Error: "User has already completed voting"
```bash
❌ Cannot generate recovery slug - user has already completed voting.
```
**Solution:** This voter has finished voting. No recovery needed.

### Error: "User is not eligible to vote"
```bash
❌ Cannot generate recovery slug - user is not eligible to vote.
```
**Solution:** Check if voter registration was approved. Contact admin to verify eligibility.

### Warning: No existing slugs found
```bash
📋 No existing slugs found.
```
**Solution:** This is normal for first-time voters. Recovery will still work.

## 📊 Monitoring Commands

### Check System Status
```bash
# View all security violations
curl http://localhost:8000/admin/voting-security/violations

# Real-time monitoring
curl http://localhost:8000/admin/voting-security/monitor

# Generate security report
curl http://localhost:8000/admin/voting-security/report
```

### Find Users Needing Help
```bash
# Find users with expired active slugs
php artisan tinker
>>> VoterSlug::where('is_active', true)->where('expires_at', '<', now())->with('user')->get();
>>> exit
```

## ⚡ Quick Reference Card

| Task | Command |
|------|---------|
| Find user by email | `User::where('email', 'voter@example.com')->first()` |
| Find user by name | `User::where('name', 'LIKE', '%John%')->get()` |
| Basic recovery | `php artisan election:recover-voter 123` |
| Recovery with details | `php artisan election:recover-voter 123 --reason="Link expired" --admin="Admin Name"` |
| Check expired slugs | `VoterSlug::where('expires_at', '<', now())->count()` |

## 🚨 Emergency Procedures

### Mass Recovery (Server Issues)
If multiple voters are affected by technical issues:

1. **Identify affected users:**
```bash
php artisan tinker
>>> $affectedUsers = VoterSlug::where('created_at', '>', '2024-01-15 14:00:00')
    ->where('is_active', true)
    ->where('expires_at', '<', now())
    ->pluck('user_id')
    ->unique();
>>> exit
```

2. **Batch recovery:**
```bash
# Generate recovery for each affected user
for user_id in $affectedUsers; do
    php artisan election:recover-voter $user_id --reason="Server maintenance affected voting" --admin="Emergency Recovery Team"
done
```

### Security Incident Response
If suspicious activity is detected:

1. **Audit user activity:**
```bash
php artisan election:recover-voter 123 --reason="Security audit - regenerating secure slug" --admin="Security Team"
```

2. **Check logs:**
```bash
tail -f storage/logs/laravel.log | grep "Recovery slug generated"
```

## 📞 Support Contacts

- **Technical Issues**: Contact IT Support Team
- **Election Process Questions**: Contact Election Committee Chair
- **Security Concerns**: Contact Election Security Officer

---

## 📝 Recovery Checklist

For each voter assistance request:

- [ ] Verify voter identity
- [ ] Confirm the issue (expired link, technical problems, etc.)
- [ ] Find user ID in system
- [ ] Run recovery command with appropriate reason
- [ ] Verify command completed successfully
- [ ] Provide new URL to voter via secure channel
- [ ] Confirm voter can access new link
- [ ] Document the assistance provided
- [ ] Follow up to ensure voting completion

## 🔒 Important Reminders

- **Never share recovery URLs publicly**
- **Always include a meaningful reason for audit trails**
- **Recovery slugs expire in 30 minutes - inform voters**
- **Voters restart from Step 1 (code verification) with recovery slugs**
- **Each voter can only vote once - recovery doesn't bypass this rule**
- **Log all assistance provided for election transparency**

---

*This guide ensures secure, efficient voter assistance while maintaining election integrity and providing complete audit trails.*