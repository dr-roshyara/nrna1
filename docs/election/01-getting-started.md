# Getting Started - Login & Access

**Welcome to the Election Management System!**

This guide walks you through your first steps - logging in and navigating to the voter management area.

---

## 📋 Table of Contents

1. [Before You Start](#before-you-start)
2. [Step 1: Login](#step-1-login)
3. [Step 2: Navigate to Your Organization](#step-2-navigate-to-your-organization)
4. [Step 3: Access the Voter List](#step-3-access-the-voter-list)
5. [First Time? Common Questions](#first-time-common-questions)
6. [Troubleshooting](#troubleshooting)

---

## Before You Start

✅ You need:
- Valid login credentials (username/email and password)
- Access to your organization
- A modern web browser (Chrome, Firefox, Safari, or Edge)
- JavaScript enabled in your browser

---

## Step 1: Login

### 1️⃣ Go to the Login Page

1. Open your web browser
2. Navigate to your organization's election platform URL
3. You should see the login page

### 2️⃣ Enter Your Credentials

```
┌─────────────────────────────────┐
│   Election Management System    │
│                                 │
│  Email or Username              │
│  ┌───────────────────────────┐ │
│  │                           │ │
│  └───────────────────────────┘ │
│                                 │
│  Password                       │
│  ┌───────────────────────────┐ │
│  │ •••••••••••••             │ │
│  └───────────────────────────┘ │
│                                 │
│  [ ] Remember Me                │
│                                 │
│  ┌─────────────────────────────┐│
│  │      Sign In                ││
│  └─────────────────────────────┘│
│                                 │
│  Forgot Password?               │
└─────────────────────────────────┘
```

**Steps:**

1. Enter your **email address** or **username**
2. Enter your **password**
3. (Optional) Check "Remember Me" if using a personal computer
4. Click **"Sign In"** button

### 3️⃣ Two-Factor Authentication (if enabled)

If your organization uses two-factor authentication:

1. You'll see a code entry screen
2. Check your email or authenticator app for the code
3. Enter the 6-digit code
4. Click **"Verify"**

### ✅ Success

You're now logged in! You should see the dashboard with your organization's name.

---

## Step 2: Navigate to Your Organization

### From the Dashboard

After logging in, you'll see:

```
┌──────────────────────────────────────────┐
│         Welcome Dashboard                │
├──────────────────────────────────────────┤
│                                          │
│  ✓ Welcome, John Smith                   │
│                                          │
│  My Organizations                        │
│  ┌────────────────────────────────────┐  │
│  │ Organization Name                  │  │
│  │ Role: Commission Member            │  │
│  │                                    │  │
│  │ [ View Organization ] [ Settings ]│  │
│  └────────────────────────────────────┘  │
│                                          │
└──────────────────────────────────────────┘
```

**To access your organization:**

1. Find your organization name in the list
2. Click **"View Organization"** button
   - OR -
3. Click the organization name directly

---

## Step 3: Access the Voter List

### From Organization Page

Once inside your organization, you'll see:

```
┌──────────────────────────────────────────┐
│     Organization: Election2024           │
├──────────────────────────────────────────┤
│                                          │
│  Navigation Menu:                        │
│  ┌────────────────────────────────────┐  │
│  │ > Dashboard                        │  │
│  │ > Voters        ← Click Here       │  │
│  │ > Statistics                       │  │
│  │ > Settings                         │  │
│  │ > Members                          │  │
│  └────────────────────────────────────┘  │
│                                          │
└──────────────────────────────────────────┘
```

**Steps:**

1. Click **"Voters"** in the navigation menu (or left sidebar)
2. The voter list page will load
3. You should see a table with voter information

### 🎯 You're Ready!

You now have access to the voter management page. You can:

- ✅ **View voters** in your organization
- ✅ **Search** for specific voters
- ✅ **Filter** voters by status
- ✅ **Approve** voters (if you have commission access)
- ✅ **Suspend** voters (if you have commission access)
- ✅ **Perform bulk operations** (if you have commission access)

---

## First Time? Common Questions

### Q: What if I don't see "Voters" in the menu?

**A:** You might not have permission to access the voter list. This typically means:
- Your role doesn't include voter management access
- You need to contact your organization administrator to grant access
- Check that you're in the correct organization (you may be part of multiple organizations)

### Q: Can I access multiple organizations?

**A:** Yes! If you belong to multiple organizations:
1. Go back to the dashboard
2. Click on a different organization
3. Each organization has its own voter list

### Q: How do I change my password?

**A:**
1. Look for your profile menu (usually in the top-right corner)
2. Click your name or avatar
3. Select "Account Settings" or "Change Password"
4. Enter your current password and new password
5. Click "Save"

### Q: What's the difference between "Member" and "Commission Member" roles?

**A:**
- **Member/Staff**: Can view voters and statistics
- **Commission Member**: Can also approve and suspend voters
- **Admin**: Full system access (usually platform administrators)

See [Managing Voters](./04-managing-voters.md) for more details.

### Q: Is my login secure?

**A:** Yes!
- ✅ Your password is encrypted in transit (HTTPS)
- ✅ Your password is hashed in our database
- ✅ We never store your password in plain text
- ✅ Session timeout removes inactive sessions after 1 hour
- ✅ Two-factor authentication available for extra security

---

## Troubleshooting

### ❌ "Invalid Email or Password"

**Problem:** Login fails with authentication error

**Solutions:**
1. ✅ Check that you're using the correct email address
2. ✅ Verify your password is correct (check CAPS LOCK)
3. ✅ Try resetting your password if you forgot it
4. ✅ Wait a few minutes and try again (account may be temporarily locked after multiple failed attempts)

**Get Help:**
- Click "Forgot Password?" to reset
- Contact your organization administrator

---

### ❌ "Access Denied" or "403 Error"

**Problem:** You can log in but can't access the voter list

**Solutions:**
1. ✅ Verify you're in the correct organization
2. ✅ Check that your role includes voter management access
3. ✅ Refresh the page (F5 or Ctrl+R)
4. ✅ Clear your browser cache and cookies

**Get Help:**
- Contact your organization administrator to check your permissions
- See [Language Settings](./09-language-settings.md) if you're seeing a different language than expected

---

### ❌ "Session Expired"

**Problem:** You're logged out unexpectedly

**Reasons:**
- Your session timed out after 1 hour of inactivity
- You logged in from another device
- Browser cookies were cleared

**Solutions:**
1. Click "Sign In" and login again
2. Check "Remember Me" if using a personal device
3. See [Tips & Troubleshooting](./07-tips-troubleshooting.md#session-management) for more info

---

### ❌ "Page Not Found" or "404 Error"

**Problem:** The voter list page doesn't exist

**Solutions:**
1. ✅ Make sure you're accessing the correct URL for your organization
2. ✅ Click through the navigation menu instead of typing the URL
3. ✅ Refresh the page
4. ✅ Check that the organization slug in the URL is correct

**Example correct URL:**
```
https://yourplatform.com/organizations/election2024/voters
                                      ^^^^^^^^^^^^^^
                                      Organization slug
```

---

### ❌ "Browser Compatibility Issue"

**Problem:** Page looks broken, buttons don't work, or strange formatting

**Solutions:**
1. ✅ Update your browser to the latest version
2. ✅ Try a different browser (Chrome, Firefox, Safari, or Edge)
3. ✅ Enable JavaScript (check your browser settings)
4. ✅ Clear browser cache: Settings → Privacy → Clear Browsing Data
5. ✅ Disable browser extensions (ad blockers, password managers might interfere)

**Supported Browsers:**
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

---

## Next Steps

👉 **Ready to work with voters?** Go to [Voter List Overview](./02-voter-list-overview.md)

👉 **Want to search for a specific voter?** Go to [Searching & Filtering](./03-searching-filtering.md)

👉 **Need to approve voters?** Go to [Managing Voters](./04-managing-voters.md)

---

## 🆘 Still Need Help?

- Review [Tips & Troubleshooting](./07-tips-troubleshooting.md)
- Check [Accessibility Guide](./08-accessibility.md) if using a screen reader
- Contact your organization administrator
- Email support at: support@yourplatform.com

---

**Happy voting! 🗳️**
