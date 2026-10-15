# PBDIGIT-302: Mail transport TLS policy and the misleading `verify_peer` block

**Type:** Security / configuration · **Epic:** none · **Created:** 2026-10-16 · **Raised by:** Claude Code session (self-declared, not attestable), from the local Amazon SES investigation (2026-10-15) and the Laravel 12 upgrade (ADR `ADR_20261015_2346_Laravel_12_And_Excel_4_Upgrade.md`, finding F-4). **Index row:** added 2026-10-16.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · creation approved by the PO 2026-10-16 |
| **Classification** | **observed** in source (symfony/mailer 7.2.3); **unknown** for 7.4.19 |
| **Scope** | investigation, then a config decision for all environments; production mail stays on Hostinger until separately approved |

## Finding
- **STARTTLS is opportunistic.** On symfony/mailer 7.2.3 (`EsmtpTransport.php:165`), port 587 upgrades only if the server advertises STARTTLS. A stripped advertisement leads to plaintext AUTH. 7.2.3 has no option to require TLS.
- **Port 465 (`smtps`) avoids this.** TLS starts from the first byte (`MailManager.php:197`). The local SES configuration uses port 465 for this reason.
- **`config/mail.php:44–50` contains a dead nested `stream.ssl.verify_peer => false` block.** Laravel 11 and 12 do not read it, and verification is effectively **on**. The block is misleading and dangerous if someone "fixes" it into a top-level `verify_peer` option.
- **The Laravel 12 upgrade moved symfony/mailer to 7.4.19.** Whether 7.4 offers an enforced-TLS option is **not yet checked**.

## Proposed direction (nothing decided)
1. Read `vendor/symfony/mailer` 7.4.19 for a require-TLS option and record the result.
2. Decide the policy for all environments: port 465 everywhere, or 587 with enforced TLS if available.
3. Remove the dead `stream` block. Add a test that builds the mail transport and asserts implicit or enforced TLS with peer verification on.

## Acceptance criteria
1. The policy is recorded (ADR or decision note) and production impact is assessed.
2. The dead `verify_peer` block is removed, and the transport test passes.
3. No change to production mail without separate approval.
