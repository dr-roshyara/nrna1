# PBDIGIT-299: Audit stored SVG logos and images now that Laravel 12 rejects SVG uploads

**Type:** Security / data audit · **Epic:** none · **Created:** 2026-10-16 · **Raised by:** Claude Code session (self-declared, not attestable), from the Laravel 12 upgrade (`feature/laravel-12-upgrade`, ADR `ADR_20261015_2346_Laravel_12_And_Excel_4_Upgrade.md` §7). **Index row:** added 2026-10-16.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · creation approved by the PO 2026-10-16 |
| **Classification** | **decided:** accept Laravel 12's SVG rejection (option 1, PO 2026-10-16). **Unknown:** whether SVG files are already stored |
| **Scope** | read-only audit of stored files and database references; no code change to the upload rules |

## Finding
- From Laravel 12, the `image` validation rule **rejects SVG** unless `image:allow_svg` is used, even when `mimes:` lists `svg`.
- Three endpoints currently list `svg` and so **stop accepting SVG** after the upgrade:
  - `app/Http/Requests/StoreImage.php:27` (image upload, `image|mimes:jpeg,jpg,png,gif,svg`);
  - `app/Http/Controllers/OrganisationController.php:304` (organisation `logo`);
  - `app/Http/Controllers/Election/ElectionManagementController.php:720` (election `logo`).
- The PO accepted this as an intentional security tightening. SVG can embed script, and these logos are shown to every voter, which makes them an XSS vector.
- **Not yet known:** whether SVG files uploaded **before** the upgrade are stored and still served.

## Proposed direction (nothing decided)
1. **Read-only inventory:**
   - Count stored files with the `.svg` extension or `image/svg+xml` content under the public and image storage paths.
   - Count database rows whose logo or image path ends in `.svg` (organisations, elections, images).
   - Report counts and owning tenants only; no file contents in reports.
2. **For each SVG found:** check it for script, event handlers or external references.
3. **Decide remediation per finding:** convert to PNG, remove, or serve with `Content-Security-Policy` / `Content-Disposition: attachment`. Each is a separate authorised change.
4. **Optionally:** remove the now-misleading `svg` from the three `mimes:` lists. That is a code change with its own review.

## Acceptance criteria
1. The inventory is recorded with counts per storage location and per tenant, with no file contents and no personal data.
2. Every stored SVG is classified (clean, suspicious or malicious), and remediation is decided by the PO.
3. A test proves an SVG upload is rejected at the three endpoints (regression guard for the Laravel 12 behaviour).
4. Production data is inspected only under separate authorisation.
