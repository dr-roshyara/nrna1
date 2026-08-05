# PBDIGIT-EPIC-04 — Candidate Management

**Journey segment:** `Candidate Registration → Candidate Approval → (ballot ready)`
**Epic status:** `IMPLEMENTED — NOT VERIFIED` · **Gated by** `PBDIGIT-00`; **depends on** `PBDIGIT-10` (posts must exist before anyone can stand for one)
**Baseline:** `main` @ `9158ef11` · **Created:** 2026-08-05

---

## PBDIGIT-12 — A member stands for a post

| | |
|---|---|
| **Customer goal** | *"I am a member. I want to stand for a position in our organisation."* |
| **Business steps** | see which posts are open → choose a post → submit candidacy (statement, details) → receive confirmation |
| **Route → code** | `apply_candidacy` route `routes/organisations.php:281` (inside the election-middleware group) · `:106,107` candidacy apply form/POST → `CandidacyApplicationController` · `CandidacyController` → `app/Models/Candidacy.php` → constitution action `apply_candidacy` (allowed in `setup_nomination`) |
| **Business rules evidenced** | `apply_candidacy` is the **only citizen-facing constitutional action** in the entire lifecycle · a candidate applies to a **post**, and the post carries the region context (root `CLAUDE.md`) · `position_order` on the candidacy governs ballot ordering (`docs/VOTE_CREATE_CANDIDATE_ORDERING.md`) |
| **Verification** | apply as an eligible member → confirm the candidacy is recorded with `pending` status; apply for a post in another region → confirm the rule (`Evidence not found`: is cross-region candidacy blocked?); apply twice to the same post → confirm duplicate handling |
| **Known findings** | `Evidence not found`: duplicate-application policy · whether a non-member or unapproved member can reach this route (relates to `PBDIGIT-06`/`08`) |
| **Tests** | 7 files match candidacy |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

## PBDIGIT-13 — The committee reviews and decides candidacies

| | |
|---|---|
| **Customer goal** | *"As the election committee, I want to review each application and approve or reject it, with a reason."* |
| **Business steps** | list applications → review each → approve or reject (with reason) → applicant notified |
| **Route → code** | `Election/CandidacyReviewController` · `Election/CandidacyManagementController` · `DeligateCandidacyController` → `Candidacy` model |
| **Business rules evidenced** | review is committee work, consistent with the constitution's *"Only committees (chief, deputy) can administer elections"* — though **candidacy review is not itself a constitutional action** (see findings) |
| **Verification** | approve one, reject one → confirm **only approved candidates appear on the ballot** (the decisive check) → confirm the rejected applicant is informed → confirm a rejection reason is stored |
| **Known findings** | ⚠️ **candidacy approval is outside the constitution.** `ElectionAction` contains no `approve_candidacy`/`reject_candidacy`; authorization therefore rests on controller/policy code rather than the artifact that claims to be *"THE SINGLE SOURCE OF TRUTH for what actions are constitutionally allowed."* **Same class as `P-3` (ungoverned unpublish)** — recorded here as a product-trust concern: who may exclude a candidate, and is it recorded? |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

## PBDIGIT-14 — Publish the candidate list

| | |
|---|---|
| **Customer goal** | *"Members should be able to see who is standing, before voting opens."* |
| **Business steps** | finalise approvals → publish the candidate list → members can view it |
| **Route → code** | `Evidence not found` as a distinct capability. Closest evidence: `complete_nomination` (`routes/organisations.php:330`) ends nomination and emits `NominationCompleted` (`Election.php:1396`); the ballot itself renders approved candidates at `vote/create` (`routes/election/electionRoutes.php:515`) |
| **Verification** | after approvals, look for a member-visible candidate list **outside** the ballot flow — if none exists, this is a genuine product gap, not merely untraced |
| **Known findings** | **This is the epic's clearest candidate for missing functionality.** The Level 0 journey includes *"Publish candidates"*; the code has no constitutional action, no event, and no located route for it. Members may therefore first see the candidate list **only when they open their ballot** — acceptable for some organisations, unacceptable for others (campaigning period) |
| **Status** | `PARTIAL` — `Evidence not found` for a standalone publication step |

---

## Epic summary

| ID | Story | Status | Risk |
|---|---|---|---|
| `PBDIGIT-12` | Member stands for a post | `IMPLEMENTED — NOT VERIFIED` | low-medium — duplicate/eligibility policy unconfirmed |
| `PBDIGIT-13` | Committee reviews candidacies | `IMPLEMENTED — NOT VERIFIED` | **medium-HIGH — decision authority sits outside the constitution** |
| `PBDIGIT-14` | Publish candidate list | `PARTIAL` | **medium — possibly missing functionality** |

**Recommended order:** `PBDIGIT-14` first — it is the one story where the answer may be *"the customer cannot do this at all"*, and that is worth knowing before anything is polished.

**Nothing is authorized by this epic.**
