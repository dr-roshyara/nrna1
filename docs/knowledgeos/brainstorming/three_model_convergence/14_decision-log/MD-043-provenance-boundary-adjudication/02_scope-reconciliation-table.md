# MD-043 §2 — Scope Reconciliation Table

MD-042's own authorization named eight items, but two of them ("other `docs/knowledgeos/reviews/`
folders" and "`research/` (nrna1 top-level)") are directory *families*, not single directories, and
resolved into multiple distinct paths once actually walked. The table below lists every actually
distinct path this reconstruction has touched or considered, replacing the "eight directories"/
"five of eight" figures with a precise accounting.

| Requested landscape | Actual path(s) | Content inspected? | Reason excluded/searchable | Provenance evidence | Current status |
|---|---|---|---|---|---|
| 1. `brainstorming/kernel/` | `docs/knowledgeos/brainstorming/kernel/` (189 files incl. 17 nested) | Yes, full | Already-admissible core corpus (main sequential pass) | Register-confirmed corpus membership | SEARCHED, unaffected |
| 2. `brainstorming/verification/` | `docs/knowledgeos/brainstorming/verification/` (482 files) | No — structure/filenames only | MD-010/011 exclusion (pre-existing) + provenance findings this phase (see §3) | Mixed: ESTABLISHED for the `step-272`/`280`/`281`/`282`/`handoff`/`witnesses`/`canonical-construction`/`consolidation` cluster (shared first-commit `70fee73c8`); PLAUSIBLE/UNRESOLVED for the rest | FIREWALLED (narrowed reasoning, same outcome) |
| 3. `brainstorming/synthesis/` | `docs/knowledgeos/brainstorming/synthesis/` (8 files, 4 real) | README only | MD-010/011 exclusion + "EXTRACTION" naming (weakened this phase) | PLAUSIBLE that "EXTRACTION" is a generic methodology term, not a theory-extraction reference — see §6 | 3 files still not opened (precautionary); provenance hypothesis corrected |
| 4. `mathematical_ideas_that_can_be_implemented/` | same, 401+ files (2 additional files read this turn per direct user instruction) | Yes, light pass + 2 targeted files | Already-admissible math lane | Register-confirmed | SEARCHED, unaffected |
| 5. `reviews/kernel/` (named directly) | `docs/knowledgeos/reviews/kernel/` (session1/, session2/, sy/) | No — filenames + git metadata only | Precaution (MD-042); now re-evidenced (see §3) | STRONGLY INDICATED to be a review/synthesis layer over `brainstorming/kernel/`'s own corpus, not theory-extraction | FIREWALLED (different reason than MD-042 gave) |
| 6a. "other `reviews/` folders" → `reviews/synthesis/` | `docs/knowledgeos/reviews/synthesis/` | Partial, per user's own prior resolution | User's own explicit ruling | ESTABLISHED (via commit-message content) that its subject matter directly discusses K_t/8-primitive/`pi_K(K_t)` | FIREWALLED, unchanged, sharpened |
| 6b. "other `reviews/` folders" → `reviews/exec/` | `docs/knowledgeos/reviews/exec/` (3 `.py` files) | Filenames + full commit message | "No code execution" rule + naming (withdrawn this phase) | ESTABLISHED that this belongs to the same K-1/K2/GN-ruling research family (different, later ruling `GN-77`, not yet in the frozen record) | FIREWALLED (different, corrected reason) |
| 7. `verification/` (nrna1 top-level) | `/verification/zero-algebra/` (12 `KR-*` dirs) | Yes | Model B's own already-partially-known executable evidence | Register/concept-register-confirmed | SEARCHED, unaffected |
| 8a. `research/` (nrna1 top-level) → `research/kernel-reduction/` | `/research/kernel-reduction/` | Already characterized (MD-030) | Already admitted, narrow scope | Established via MD-028/029 admission | Already covered, not re-opened |
| 8b. `research/` (nrna1 top-level) → `research/knowledgeos-sim/` | `/research/knowledgeos-sim/` | No | MD-030's own prior exclusion + **zero git history found this phase (new finding)** | UNRESOLVED — entirely untracked, cannot be dated or attributed via git at all | FIREWALLED, stronger reason found |

## Corrected count

**Of these 10 distinct paths: 3 were genuinely searched for content this reconstruction's own way
(kernel/, math lane, zero-algebra), 1 was already covered elsewhere (kernel-reduction), and 6 remain
excluded from content-level search — but three of those six (`reviews/kernel/`, `reviews/exec/`,
`brainstorming/synthesis/`'s 3 files) now rest on materially different and better-evidenced grounds
than MD-042 gave them, one (`brainstorming/verification/`) is narrowed from a whole-tree claim to a
specific, well-evidenced subtree, one (`reviews/synthesis/`) is unaffected (a human ruling), and one
(`knowledgeos-sim/`) is strengthened with a new negative finding (no git history at all).** The
"five of eight" figure from MD-042 is retired — it was never a clean count of eight comparable units.
