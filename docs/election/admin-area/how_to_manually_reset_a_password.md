# How to manually reset a password (when email isn't working)

Use this when outbound mail is down (e.g. the SMTP account gets
`554 5.7.1 Outbound sending is disabled for this account`) and a voter can't
get their password-reset email.

Command: `app/Console/Commands/ManualPasswordResetCommand.php`

## Option 1 — print a reset link (recommended)

```bash
php artisan user:reset-link voter@example.com
```

Output:

```
Reset link for Jane Doe (voter@example.com):
http://publicdigit.com/reset-password/<token>?email=voter%40example.com
Send this link to the voter directly (WhatsApp, SMS, in person, etc.) — it
works exactly like the one the broken email would have sent, and expires the
same way.
```

Send that link to the voter yourself (WhatsApp, SMS, phone call, in person).
They click it and pick their own password — nobody but them ever knows it.
This is the same token mechanism the email would have used, so it expires
the same way (see `config/auth.php` → `passwords.users.expire`, minutes).

## Option 2 — set the password directly

For a voter who can't manage clicking a link:

```bash
# Let the command generate a random password and print it
php artisan user:reset-link voter@example.com --random-password

# Or choose the exact password yourself
php artisan user:reset-link voter@example.com --set-password="SomeChosenPassword123"
```

Output:

```
Password set directly for Jane Doe (voter@example.com).
New password: k7Hs!qP2mXaZ
Give this to the voter directly (phone call, in person, etc.) and suggest
they change it after logging in.
```

Relay that password to the voter by phone/in person. Tell them to change it
once they've logged in.

## If you don't have the command deployed yet

You can do the same thing with a plain `tinker` one-liner — no deploy
needed, since it only uses Laravel's own password-broker mechanism:

```bash
php artisan tinker --execute="
\$user = App\Models\User::where('email', 'voter@example.com')->firstOrFail();
\$token = \Illuminate\Support\Facades\Password::broker()->createToken(\$user);
echo url(route('password.reset', ['token' => \$token, 'email' => \$user->email], false)) . PHP_EOL;
"
```

## Security note

- `--set-password`/`--random-password` mean an admin now knows the voter's
  password, even if briefly — prefer Option 1 (the link) when the voter can
  use it, since with a link nobody but the voter ever sees the password.
- The printed password/link is real and works immediately — treat it like
  any other credential (don't paste it somewhere public, delete it from your
  terminal history if that matters for your setup).
- This doesn't fix the underlying problem — outbound mail is still down.
  See the mailer error and contact whoever manages the SMTP account/hosting
  to re-enable outbound sending.
