# How to export voting codes (manual fallback when email fails)

Use this when an election's voting-code emails aren't going out and you need
to send codes to voters some other way (phone, WhatsApp, a second email
attempt, etc.).

Command: `app/Console/Commands/ExportVotingCodesCommand.php`

## Basic usage

```bash
php artisan election:export-codes <election-slug>
```

Example:

```bash
php artisan election:export-codes onf-europe-3-f99187bc
```

Output:

```
Election: onf-europe-3 (onf-europe-3-f99187bc)
Codes exported: 2
Already voted: 2
Not yet voted: 0
Code email never sent: 0
Written to: /path/to/storage/app/exports/voting-codes-onf-europe-3-f99187bc-2026-09-13_213138.csv
```

The CSV has columns: **Name, Email, Telephone, Has Voted, Code To Open Voting
Form, Code Sent At** — everything needed to identify who to contact and how,
plus the actual code to give them.

## Options

| Option | What it does |
|---|---|
| `--email=someone@example.com` | Look up just that one voter and print their code directly to the terminal — no CSV written. Use this when only one or two people are missing their email, rather than exporting everyone |
| `--only-not-voted` | Skip anyone who has already voted — only exports codes for people who still need one (ignored when `--email` is used) |
| `--path=some/folder/file.csv` | Write the CSV somewhere other than the default `exports/voting-codes-<slug>-<timestamp>.csv` (path is relative to `storage/app/`) |

Example — only the people who still haven't voted:

```bash
php artisan election:export-codes onf-europe-3-f99187bc --only-not-voted
```

Example — just one person's code, printed directly:

```bash
php artisan election:export-codes onf-europe-3-f99187bc --email=roshyara@gmail.com
```

```
Election: onf-europe-3 (onf-europe-3-f99187bc)
Name: Nab Roshyara
Email: roshyara@gmail.com
Telephone: (none on file)
Has voted: Yes
Code to open voting form: E2K4D9CQ
Code sent at: 2026-09-13 21:00:06
```

If no code is found for that email in that election, it prints an error and
exits with status 1 — nothing is written to disk in `--email` mode.

## Getting the file off the server

The file is written to `storage/app/exports/`, not anywhere web-accessible.
Pull it down with `scp`/`sftp`, e.g.:

```bash
scp user@server:/path/to/nrna1/storage/app/exports/voting-codes-onf-europe-3-f99187bc-2026-09-13_213138.csv .
```

## Why not the old raw tinker query

The old approach (see `how_to_read_code.md`) selected `user_name` directly
from the `codes` table — that column doesn't exist there (only `user_id`
does), so it would error. The command instead pulls the name/email/phone
from the related `User` via `Code::user()`.

## Security note

The CSV contains **live voting codes** — treat it like a password list:

- Don't commit it, don't email it as a plain attachment if you can avoid it,
  don't leave it in a publicly-reachable folder.
- `storage/app/exports/` is **not** currently in `.gitignore` — be careful not
  to `git add -A` after running this command. (Worth fixing separately.)
- Delete the file once you've distributed the codes.
- `--email` mode prints the code straight into your terminal, which means it
  now sits in your shell history/scrollback too — clear it if that matters
  for your setup.
