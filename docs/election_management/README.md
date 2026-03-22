# Election Management — User Guide

**For Election Officers of the Public Digit Platform**

This guide covers everything an election officer needs to know — from accepting your appointment to opening voting, managing the voter list, and publishing results.

---

## 📚 Table of Contents

| # | Guide | Who Should Read |
|---|-------|-----------------|
| 1 | [Your Role as an Election Officer](./01-your-role.md) | Everyone |
| 2 | [Accepting Your Appointment](./02-accepting-invitation.md) | All new officers |
| 3 | [Management Dashboard](./03-management-dashboard.md) | Chief & Deputy only |
| 4 | [Managing the Voter List](./04-voter-list.md) | Chief & Deputy only |
| 5 | [Viewboard — Read-Only Monitor](./05-viewboard.md) | All officers (especially Commissioner) |
| 6 | [FAQ & Troubleshooting](./06-faq-troubleshooting.md) | Everyone |

---

## 🎯 Quick Start by Role

### I am the **Chief Election Officer**
1. [Accept your appointment email](./02-accepting-invitation.md)
2. Open the [Management Dashboard](./03-management-dashboard.md)
3. Review and [approve voters](./04-voter-list.md) before election day
4. [Open voting](./03-management-dashboard.md#open-and-close-voting) when ready
5. [Publish results](./03-management-dashboard.md#publish-results) after voting closes

### I am a **Deputy Election Officer**
1. [Accept your appointment email](./02-accepting-invitation.md)
2. Open the [Management Dashboard](./03-management-dashboard.md) — same access as Chief except publishing results
3. Help [manage the voter list](./04-voter-list.md)
4. [Open or close voting](./03-management-dashboard.md#open-and-close-voting) if needed

### I am a **Commissioner**
1. [Accept your appointment email](./02-accepting-invitation.md)
2. Access the [Viewboard](./05-viewboard.md) to monitor the election in real time
3. You have read-only access — you cannot change election settings or voters

---

## 👥 Role Summary

| Action | Chief | Deputy | Commissioner |
|--------|-------|--------|-------------|
| View election status | ✅ | ✅ | ✅ |
| View voter list | ✅ | ✅ | ✅ |
| Approve / Suspend voters | ✅ | ✅ | ❌ |
| Open / Close voting | ✅ | ✅ | ❌ |
| Publish / Unpublish results | ✅ | ❌ | ❌ |
| Access Management Dashboard | ✅ | ✅ | ❌ |
| Access Viewboard | ✅ | ✅ | ✅ |

---

## 🔑 How to Reach the Right Page

After logging in, your role determines which pages you can access:

```
Login
  │
  ├─ Chief / Deputy
  │     ├─ Management Dashboard  → /elections/{id}/management
  │     └─ Voter List            → /organisations/{slug}/elections/{id}/voters
  │
  └─ Commissioner
        └─ Viewboard             → /elections/{id}/viewboard
```

> **Tip:** If you try to open a page you don't have access to, you will see a "403 Forbidden" error. This is correct — it means your role does not permit that action.

---

## 🌐 Languages

The election management interface is available in:
- **English** (en)
- **Nepali / नेपाली** (np)
- **German / Deutsch** (de)

---

**Ready to begin? Start with [Your Role as an Election Officer →](./01-your-role.md)**
