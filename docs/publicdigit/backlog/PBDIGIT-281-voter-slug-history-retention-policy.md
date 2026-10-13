# PBDIGIT-281: Retention and erasure policy for voting-link history

**Type:** Policy / data governance · **Epic:** none (voting) · **Created:** 2026-10-13 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from `PBDIGIT-274` (decision O-12 = (a): "a retention / erasure policy backlog item is created at closure"; to-do item D2). **Index row:** added 2026-10-13.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · **needs a business / legal decision first** |

## Background
Since `PBDIGIT-274`, real voting links are **never hard-deleted** by the application:
- an expired link is soft-deleted and kept as history;
- `voting:clean-expired-slugs` keeps every real slug.

**Voted-slug immutability holds on application paths while the parent records exist** (`VotedSlugImmutabilityTest`).

## The open question
How long is voting-link history kept, and how is it erased?
- **No rule exists today:** history grows without bound.
- **Hard-deleting a parent destroys history.** A user, organisation or election hard delete cascades to `voter_slugs`, `voter_slug_steps` and `codes`; a user delete removes S and C but not V.
  - **Application side:** today it hard-deletes a slug parent only in two demo-election setup commands (`VoterSlugParentHardDeleteScanTest`). That is limited evidence: raw SQL, jobs and database administration are not covered.
- **Erasure requests** (e.g. under data-protection law) must be reconciled with election audit needs. **Vote anonymity must be preserved:** the votes table has no voter reference, and that must not change.

## To decide
- The retention period(s) per election state (open, closed, results published, archived).
- **Erasure method:** delete, anonymise, or archive, and in which order relative to the parents.
- **Whether parents get `restrict` foreign keys or archival** (the O-12 option (b): its own migration and approval).

## Related
- `PBDIGIT-274`: plan section 10.4; guides `developer_guide/election/voter_slug/08_…` and `13_…`.
- `PBDIGIT-278` (the cleanup command is not scheduled).
