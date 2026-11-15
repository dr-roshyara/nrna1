# D-166 review of the task 12 restore drill and in-place recovery (2026-11-15)

**Question:** does the restore drill (`restore-drill`) and the documented in-place recovery (D-234) meet D-166 and the accepted controls for it (multi-database boundary design, ISO-8 and M-6, accepted under D-178)?
**Code:** `8b6fcd569`: `app/Contexts/Platform/Infrastructure/PgDumpRestoreDrill.php`, `OperateTechnically.php`, `developer_guide/platform/90_administrator_notes.md` (task 12).
**Author:** Claude, the implementer. Not independent. **Nothing is changed by this review.**

## What the accepted texts require

- **D-166 (1):** technical operations (provisioning, migrations, **backups, restores**) are granted once by the organisation at joining, and **never read business content**.
- **D-166 (2):** looking at business content needs a case grant, recorded and reported.
- **ISO-8:** technical operations need the joining grant and an explicit context; no grant → refused.
- **M-6 (release-1 acceptance criteria for INV-ACCESS-1, "to be proved before go-live")**, among others:
  - per-organisation **backup encryption with key-use log**;
  - **restore only by the automated process**;
  - **temporary copies encrypted and expiring**;
  - **no human standing credentials** on organisation business tables;
  - **operator roles non-interactive**.

**D-166 is read here as written:** a prohibition on technical operations *reading* content, **plus** M-6's controls on the copies. It is not merely a rule against *disclosing* content. A backup or scratch copy holds the organisation's whole business content, so the controls apply to it as to the original.

## Findings

| # | Question | Finding | Status |
|---|---|---|---|
| 1 | Are the dump and the scratch copy encrypted at rest? | **No.**<br>- **The dump** is `pg_dump -Fc` (compressed, **not encrypted**), written owner-only (`0600`) into `organisation_routing.restore_drill.directory`.<br>- **The scratch copy** is an ordinary PostgreSQL database on the organisation's own cluster. **PostgreSQL 15 has no built-in encryption at rest**, so the copy is encrypted only if the cluster's storage is, which PublicDigit does not configure or check | **GAP** (M-6 "temporary copies encrypted") |
| 2 | Which credentials can access them? | - **The dump file:** the operating-system account that runs PHP (and `root`).<br>- **The scratch copy:** the organisation's database login (`env:` reference, the same one the application uses for routing), whoever holds the cluster's superuser, and roles the source database grants (`pg_restore` restores privileges; `--no-owner` makes the restoring login the owner).<br>PostgreSQL grants `CONNECT` on a new database to `PUBLIC` by default, but other roles cannot read its tables without those grants | **GAP** (M-6 "no human standing credentials"): **the same exposure exists for the live database today**; the drill adds a second copy, not a new class of access |
| 3 | Can a human inspect the dump or copy without a case grant? | **Technically yes**, while they exist: anyone with that OS account or that database login. **Nothing technical prevents it**; only the absence of a command does. PublicDigit offers no content-reading command (`case-grant-required`, T-16.5) | **GAP** (M-6; not enforced, only not offered) |
| 4 | Is the restore automated and technically restricted? | - **The drill:** yes. It is one `platform:operate … restore-drill` command, needs the joining grant (refused otherwise: unit tests), is recorded before it runs, and is reported. The operator cannot choose what it reads. It reads only `migrations`, `organisation_identity` and Membership's readiness function on the copy (`PostgresOrganisationReadinessProbe::factsOn`).<br>- **Not test-proven:** that the drill reads no business table on the copy. T-16 proves this for the repair, with `pg_stat_user_tables`; no such counter test exists for the drill.<br>- **The in-place recovery:** **not automated.** Step 3 of the procedure is a **manual `pg_restore`** by an administrator | drill: **largely met**; recovery: **GAP** (M-6 "restore only by the automated process") |
| 5 | Are temporary copies reliably deleted after success and failure? | **Yes, within the process:** `finally` drops the scratch database (`DROP DATABASE … WITH (FORCE)`) and deletes the dump.<br>- **Proven** for success and for a copy that does not verify (`RestoreDrillTest`; mutants V6, V7 assertion-killed).<br>- **Not proven** on PostgreSQL for a **failing `pg_dump` / `pg_restore`**: that path is covered only by a unit fake (`restore-failed`) | **largely met** |
| 6 | What if the process is interrupted or killed? | **`finally` does not run** on `SIGKILL`, a default `SIGTERM` (no handler in the CLI), a host crash or a power loss. **The scratch database and the dump then remain indefinitely:** nothing expires them, and nothing finds them except the administrator note asking a person to drop `scratch_restore_*` | **GAP** (M-6 "expiring") |
| 7 | Does the implementation meet D-166 as accepted? | - **D-166 (1), ISO-8:** **met** for authority. The drill and take-out are technical operations under the joining grant, recorded and reported, and the code reads technical metadata only.<br>- **M-6:** **not met.** Copies are not encrypted, have no expiry, and no human or standing-credential restriction exists. Recovery is manual.<br>M-6 is a **go-live** criterion, not a slice-2 one, so this is a recorded gap, not a slice-2 failure. **But it means the drill and the recovery must not be used on production data before PBDIGIT-351** | **NOT PROVEN** (B-10) |
| 8 | Implemented, missing, or only documented? | see the table below | |

| Control | Implemented | Only documented | Missing |
|---|---|---|---|
| joining grant required; refused otherwise | ✔ (tests) | | |
| recorded before running; reported to the organisation | ✔ (tests) | | |
| reads technical metadata only on the copy | ✔ (code); proven not to judge the live database (V8 test) | | counter-based proof of no business read on the copy |
| copy never routable; registry untouched | ✔ (tests) | | |
| dump owner-only (`0600`); password only via `PGPASSWORD`, redacted from errors | ✔ (code) | | |
| copy and dump removed after success or failure | ✔ (tests for success and verify-failure) | | test for a failing `pg_dump` / `pg_restore` on PostgreSQL |
| dump encrypted; key-use log | | | ✘ |
| scratch copy on encrypted storage | | | ✘ (storage choice) |
| expiry of leftover copies (killed process) | | ✔ (admin note: drop `scratch_restore_*`) | ✘ automatic sweep |
| restore only by an automated process | ✔ drill | ✔ in-place procedure (manual `pg_restore`) | ✘ automated in-place restore |
| no human standing credentials; non-interactive operator roles | | | ✘ (release-1 go-live, not slice 2) |

## Consequence and recommendation

- **No decision of D-166 or D-234 is changed.** D-234 (in place, permanent locator) stands; the gaps are in the **controls around the copies**, which M-6 already requires.
- **Interim (documentation only, done in task 13):** guide 12 and the administrator notes now say that `restore-drill` and the in-place recovery are **development and test only until PBDIGIT-351**.
- **PBDIGIT-351** (created) carries the controls. **Two of them need a Principal decision first** (infrastructure and key custody); the rest follow from M-6 directly. See the options in the task 13 report.
