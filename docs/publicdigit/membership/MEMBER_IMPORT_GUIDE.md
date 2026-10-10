# Member Import Guide

**Audience:** Organisation administrators
**URL:** `/organisations/{your-organisation}/members/import`

---

## Overview

The Member Import feature lets you add between 1 and 50,000 members to your organisation in a single upload. Files are processed in the background — you can close the tab and come back later for large imports.

---

## Quick Start (3 Steps)

1. Prepare a CSV file with at least an **Email** column
2. Go to your organisation page → **Import Members**
3. Upload the file and click **Import**

---

## Preparing Your File

### Supported File Formats

| Format | Extension | Delimiter |
|--------|-----------|-----------|
| CSV | `.csv` | Comma `,` **or** semicolon `;` (auto-detected) |
| Excel | `.xlsx` | — |
| Excel (legacy) | `.xls` | — |

Maximum file size: **50 MB**

---

### Required and Optional Columns

| Column | Required | Accepted names |
|--------|----------|----------------|
| Email address | ✅ Yes | `email`, `Email`, `E-Mail`, `e-mail`, `e_mail` |
| First name | No | `firstname`, `first_name`, `First Name`, `Vorname` |
| Last name | No | `lastname`, `last_name`, `Last Name`, `Nachname` |

> **Column names are not case-sensitive** and hyphens/underscores/spaces are ignored.
> `E-Mail`, `email`, `e_mail` are all recognised as the same column.

---

### Example CSV Files

**Minimal (email only):**
```csv
email
anna.mueller@example.com
peter.schmidt@example.de
maria.huber@example.at
```

**With names (comma-separated):**
```csv
firstname,lastname,email
Anna,Müller,anna.mueller@example.com
Peter,Schmidt,peter.schmidt@example.de
Maria,Huber,maria.huber@example.at
```

**With names (semicolon-separated — common in German Excel exports):**
```csv
firstname;lastname;E-Mail
Anna;Müller;anna.mueller@example.com
Peter;Schmidt;peter.schmidt@example.de
Maria;Huber;maria.huber@example.at
```

> **Tip:** Download the template from the import page to get the exact format.

---

## Step-by-Step: Using the Import Page

### Step 1 — Upload

1. Navigate to **Your Organisation → Members → Import Members**
2. Either **drag and drop** your CSV/Excel file onto the upload area, or click **Select File**
3. The system will show a **preview** of the first 10 rows

### Step 2 — Review

- Check that the columns look correct in the preview table
- If there are validation errors (e.g. missing email column), fix the file and re-upload
- When the preview looks correct, click **Import** — the button shows the total row count

### Step 3 — Processing

For large files, the import runs in the background:

- A **live progress bar** shows rows processed, imported, and skipped
- The counts update every 2 seconds automatically
- You can **close the tab** — the import continues on the server
- Come back to the import page to check the final result

### Step 4 — Complete

When finished, you will see:

- ✅ Total members **imported**
- ⚠️ Total rows **skipped** (see below for reasons)

Click **Back to Organisation** to see your updated member list.

---

## What Happens to Skipped Rows?

A row is skipped (not imported, no error) when:

| Reason | What happens |
|--------|-------------|
| Empty email column | Row is counted as skipped |
| Email already exists in the system | Row is skipped — existing user is **not** duplicated |
| Blank line in the file | Silently ignored |

Skipped rows do **not** stop the import. All other valid rows continue to be processed.

---

## What Happens to Imported Members?

Each imported member:

1. Gets a new **user account** created with their email and name
2. Their email is marked as **verified** (no verification email is sent)
3. A **temporary password** is generated — they will need to use "Forgot Password" to set their own
4. They are automatically **added to your organisation** with the role `voter`

---

## Troubleshooting

### "E-Mail-Spalte erforderlich" (Email column required)

Your file does not have a recognised email column header.

**Fix:** Rename your email column to `email` or `E-Mail` (case-insensitive, hyphens are fine).

```
✅ Correct:  email | Email | E-Mail | e-mail | e_mail
❌ Wrong:    mail | adresse | address | contact
```

---

### Import stuck at 0% for a long time

The queue worker may not be running. Contact your system administrator and ask them to run:

```bash
php artisan queue:work
```

---

### Some rows were skipped unexpectedly

Check the skipped count after the import. Rows are skipped when the email already exists in the system. This prevents duplicate accounts.

If a member should have been imported but was skipped, search for their email address in the existing member list — they may already be registered.

---

### File upload fails with "Invalid file type"

Only `.csv`, `.xlsx`, and `.xls` files are accepted. Files named `.txt` that contain CSV data will also be accepted.

If your file is a different format (e.g. exported from a database as `.sql` or `.json`), convert it to CSV first using Excel or LibreOffice.

---

### Import fails after starting

If the import shows "failed" status, note the error message and check:

1. The file is not corrupted or empty after the header row
2. There is enough disk space on the server
3. The queue worker is running

---

## Exporting from Common Tools

### Microsoft Excel → CSV

1. Open your spreadsheet
2. **File → Save As**
3. Choose **CSV UTF-8 (Comma delimited) (.csv)**
4. Click **Save**

> Use UTF-8 encoding to preserve special characters (ä, ö, ü, ß, etc.)

### LibreOffice Calc → CSV

1. **File → Save As**
2. Choose **Text CSV (.csv)**
3. In the export dialog: set **Field delimiter** to `,` or `;`, **Character set** to UTF-8
4. Click **OK**

### Google Sheets → CSV

1. **File → Download → Comma-separated values (.csv)**

---

## Limits and Performance

| Metric | Value |
|--------|-------|
| Maximum file size | 50 MB |
| Maximum rows | ~50,000 |
| Chunk size (internal) | 500 rows per batch |
| Progress update interval | every 2 seconds |
| Job timeout | 1 hour |

For files over 10,000 rows, expect the import to take several minutes. The processing happens entirely in the background — your browser does not need to stay open.

---

## FAQ

**Can I import the same file twice?**
Yes. Duplicate emails are automatically skipped, so no members will be doubled. Only genuinely new email addresses will be added.

**Will members receive a welcome email?**
Not automatically. Email notification on import is not currently enabled. Members will need to use **Forgot Password** to activate their account.

**What role are imported members given?**
All imported members receive the `voter` role by default.

**Can I import members for a different organisation?**
No. The import page is scoped to the organisation you are currently managing. You must navigate to each organisation separately.

**What encoding should my CSV use?**
UTF-8 is recommended, especially if your file contains German umlauts (ä, ö, ü) or other non-ASCII characters.

**Is there a template I can download?**
Yes — click the **Download Template** link on the import page to get a ready-to-use CSV file.
