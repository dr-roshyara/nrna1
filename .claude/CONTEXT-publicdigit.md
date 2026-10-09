<!-- Created 2026-09-28: a dedicated context log for PublicDigit-specific engineering
work (the product itself, distinct from KnowledgeOS governance/theory-research work,
which continues to live in CONTEXT.md). Started empty deliberately -- the existing
CONTEXT.md and its archive (CONTEXT-ARCHIVE-2026-07-08.md) contain no separable
PublicDigit-only history worth migrating (checked directly: 0 purely-PublicDigit
blocks in the active period, 8 small blocks / ~106 lines in the archived period,
judged not worth splitting out after this file was created empty by explicit choice).
Log new PublicDigit-specific engineering entries here going forward. -->

# Current Working State — PublicDigit

**Updated:** 2026-10-05 *(additive — the **Q3 security policy surface** block is the newest)*

---

## 📍 UPDATE (2026-10-09, **Membership Slices 0 / 1a / 1b implemented; 1b acceptance review done; Slice 2 decisions open**)

| | |
|---|---|
| ✅ **Implemented** | Slice 0 kernel (`PBDIGIT-258`), Slice 1a port and concurrency (`PBDIGIT-259`), Slice 1b PostgreSQL adapter (`PBDIGIT-260`, migration **not rolled out**) |
| 🔎 **Review** | `docs/publicdigit/reviews/2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md`: independent review recommends ACCEPT WITH CONDITIONS; F-1 far-date defect → `PBDIGIT-264` |
| 📄 **Plan** | `docs/publicdigit/reviews/2026-10-09-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md` (264 plan, cutover gate C-1…C-10) · participation draft `2026-10-09-participation-semantic-contract-draft.md` · new `PBDIGIT-265` |
| 🧭 **PR dispositions** | F-4b · N1 · F-3 approved · DV-1 primary record found (countersign recommended) · plan §10; kernel-admissibility research in `docs/publicdigit/membership/architecture/models/` |
| 🏛️ **Committee discovery** | `docs/publicdigit/reviews/2026-10-09-committee-responsibility-authority-and-membership-boundary-discovery.md`: CQ-1…CQ-10 open (Election Committee vocabulary collision); G-AUTH-1 port gap; `PBDIGIT-266` security investigation |
| ❓ **Waiting** | Principal Reviewer: accept 1b; authorise 264; ratify D-1 … D-5(b) (consent basis, no pending member, End kinds, interim authority, effectiveAt range); approve the participation research contract |
| ▶️ **Next** | `PBDIGIT-264` → Slice 2 (command wiring, no entry point) → Slice 3 (cutover R10) → Slice 4 (HTTP). **Nothing starts without authorisation** |

---

## 📍 UPDATE (2026-10-05 night, **Q3 decided (D1/D2/D3/D6) — characterization RED committed; 2B not authorised**)

| | |
|---|---|
| 📐 **Q3** | `docs/publicdigit/reviews/2026-10-05-q3-security-policy-surface.md` — policy model = constitutional fields (`max_votes_per_ip`, `network_binding_strategy`, `device_binding_strategy`, `ip_whitelist`); evaluation only at `forEntry()` / `forCast()`; `MAX_USE_IP_ADDRESS` not a runtime source; binding / device = inert candidates |
| 🧪 **Characterization** | `SecurityPolicySurfaceCharacterizationTest` 11/11 confirm C1–C6 (whitelist fail-closed, settings ineffective, env overrides policy, step-4 env-unset refusal, dead start check, inert binding) — characterization only |
| 🐞 **PBDIGIT-233** | step-4 occurrence reopened: missing `components.error_message` view → generic `system` error |
| ❓ **Next** | PO: Q3-D4 (start page), D5 (whitelist semantics), D7 (tenant scope) → authorise 2B stories one at a time (2B-1 whitelist recommended first) |

---

## 📍 UPDATE (2026-10-05 night, **PBDIGIT-240 RESOLVED — I3 for the final cast**)

| | |
|---|---|
| ✅ **PBDIGIT-240** (I3) | Design `7d948969f` (D-240-1…4) · RED `c6490376c` · GREEN: `store()` consumes one `SecurityAssessment` (verdict, outcome, envelope) from the Security Verdict Authority; `verdictFor()` derived; no inline evaluation. **I3: one security authority is enforced for the final-cast security assessment.** Regression 583/333/19, no pass → fail. Guide `final_cast/09` |
| 📊 **Matrix** | I3 → 🟢 final cast, 🔴 whole path (checks #2–#6 outside the authority) · I9 → 🟢 four-field boundary, 🟡 surface (Q3) |
| 🧭 **Next** | **Q3** — define the security-policy surface (facts: the authority reads `max_votes_per_ip`, `network_binding_strategy`, `ip_whitelist`; `ip_restriction_*` read only by `resolveIpBlock`; `voter_verification_mode` read by nothing) → **2B** consolidate / retire #2–#6 · decisions A8 / A5 / A6, D6, PBDIGIT-242, D4.1 |

---

## 📍 UPDATE (2026-10-05 night, **PBDIGIT-244 RESOLVED — I9 enforced for the four-field boundary**)

| | |
|---|---|
| ✅ **PBDIGIT-244** (I9) | RED `58b0ec769` · GREEN: `Election::SECURITY_POLICY_FIELDS` (`ip_restriction_enabled`, `ip_restriction_max_per_ip`, `ip_whitelist`, `voter_verification_mode`) locked from ReadyForVoting — application refusal (whole PATCH) + model guard. `SecurityPolicyLifecycleLockTest` 26/26; regression: only the `VotingSecurityVerdictAdapterTest` whitelist fixture changed, repaired fixture-only. Guide `election/settings/01` |
| 📊 **Matrix** | I9 → 🟢 for the current boundary, 🟡 policy surface (Q3). I3 remains 🔴 (PBDIGIT-240) |
| 🧭 **Next** | PO architectural review → PBDIGIT-240 / I3 (decision first: verdict-only vs richer assessment, telemetry owner) · Q3 · anonymity decisions A8 / A5 / A6 · D6 · PBDIGIT-242 · D4.1 |

---

## 📍 UPDATE (2026-10-05 night, **PBDIGIT-243 RESOLVED — final-cast exits fail closed**)

| | |
|---|---|
| ✅ **PBDIGIT-243** (F-V9) | RED `da54c03c5` · GREEN: Exit B (`validateVotingIpWithResponse()`, after the lock) now calls `failCastClosed()` — transaction rolled back, lock released; latent until `users.voting_ip` exists (PBDIGIT-234). Exit A (fresh membership, before the lock) already rolled back itself — kept as a characterization. Evidence: `FinalCastExitSafetyTest` 3/3, Phase 4 suites 4/4 · 7/7 · 6/6, 97-suite regression unchanged from `33bef9f7d` (579 / 334 / 19, no status change). Guide `final_cast/08` |
| 📊 **Completion matrix** | `docs/publicdigit/reviews/2026-10-05-voting-constitution-i1-i13-completion-matrix.md` — I1/I4/I6/I7/I11/I12/I13 enforced on the real final cast; I2/I5/I8/I10 partly; **I3 not met**, **I9 not implemented** |
| 🧭 **Agreed order (next)** | PBDIGIT-240 (Stage C) → Q3 / 2B → F-V4 → R3 — not started until the matrix review |
| ❓ **Next** | PO: review the matrix findings (I3, I9) · PBDIGIT-240 decision (verdict-only vs richer assessment, telemetry owner) · decisions A8 / A5 / A6, D6 (PBDIGIT-236), PBDIGIT-242, D4.1, Q3 |

---

## 📍 UPDATE (2026-10-05 night, **VE-01 ballot integrity GREEN — PBDIGIT-237 RESOLVED**)

| | |
|---|---|
| ✅ **PBDIGIT-237** (VE-01) | ADR revision 3 `22c3e5f40` · GREEN `33bef9f7d`: real-election final cast validates the session ballot against the authoritative ballot (`BallotValidator`, `AuthoritativeBallotQuery` over `BallotAssemblyService`) after `forCast()` and before the CAS; only a `ValidatedBallot` is persisted (`saveValidatedVote()`); D5 approved-only; demo unchanged. `FinalCastBallotInvariantTest` 22/22; 97-suite regression vs `22c3e5f40`: 0 pass → fail, 334 failures unchanged. Guide `final_cast/07` |
| 🔒 **Decisions in force** | missing regional list = empty · 8 `BallotIntegrityCharacterizationTest` cases kept · B2 / R4-4 use a throwing `Vote::created` double · D6 still open (`users.region` interim) |
| 🆕 **Backlog** | `PBDIGIT-242` — fully empty real ballot accepted as a participation-only vote (decision needed) |
| 🧭 **Agreed order (next)** | final-cast exit story (membership redirect reachable, IP-validation exit latent) → completion matrix (parallel) → PBDIGIT-240 → Q3 / 2B → F-V4 → R3 · A8 decision |
| ❓ **Next** | PO: authorise the final-cast exit story (RED first) · decisions D6 (PBDIGIT-236), A8, PBDIGIT-242, D4.1 environment, Q3 |

---

## 📍 UPDATE (2026-10-05 night, **PBDIGIT-238 DONE · PBDIGIT-239 Stage A + B GREEN · Stage C deferred → PBDIGIT-240**)

| | |
|---|---|
| ✅ **PBDIGIT-238** (CodeController real-election boundary — was "Track B") | R0 blueprint + characterization `b90547add` · R1 RED `f722520dc` · R1 GREEN `7a8407d06`: `getElection()` refuses non-real elections (403) before any Code / session / step access; demo session bypass and demo re-vote reset (reset `has_voted`) removed; unconditional `has_voted` guards; no plaintext code in logs. Baseline 47 files unchanged per test |
| ✅ **PBDIGIT-239** (entry authorization, ADR §3.1) | blueprint `ca9629b99` · 2A RED `430d28742` · **Stage A** `0578091f8` — one Security Verdict Authority (`VotingSecurityVerdictQuery` + `TrustLegitimacySecurityVerdict`, extraction of the final cast's inline assessment; adapter == inline oracle) · **Stage B** `d90131361` — `VotingAuthorization::forEntry()` (E1 fresh `decide()` + E2 supplied verdict) in `CodeController::create()` (after the tenant binding, before code creation / e-mail) and `store()` (own tenant binding, validation, then decision); fail closed. Intended change: the existing IP-density rule also refuses at entry |
| ⏸ **PBDIGIT-240** (deferred Stage C) `f5d920b80` | `VoteController::store()` keeps its inline security assessment — it needs the four-valued `LegitimacyOutcome` and the trust envelope (gate messages, audit, divergence telemetry). Decide verdict-only vs richer assessment and telemetry ownership first |
| 📘 **Guides / log** | `developer_guide/election/code_step/` (00–02) + session log `8bb7236cc`; corrected after source review: on `create()` denial the tenant may already be bound; decision order E1 → supplied E2; `isUserEligible()` still a transitional gate after `forEntry()` (R3). Stale `CodeController` comments corrected `0c5aa0f91` (comment-only) |
| ✅ **PBDIGIT-241** (F-V6 / F-V7, constitutional anonymity) | RED `19f0d3356` → GREEN `826a8f70d`: receipt secret and user-id + ballot-id pairs removed from four `VoteController` log contexts; Phase 1 K8 secret assertion inverted. **A8 (audit receipt hash) attached decision OPEN.** Still open: A1, vote id + `voter_slug_id` entry, A6. Guide `final_cast/06` |
| 🧭 **Agreed order** | ADR revision 3 signature → VE-01 GREEN → final-cast exit story (membership redirect reachable, IP-validation exit latent) → completion matrix (parallel) → PBDIGIT-240 → Q3 / 2B → F-V4 → R3 · A8 decision |
| 🔒 **Decisions in force** | demo on real code routes → 403 · E2 verdict only from the port · tenant decision (a) transitional, `store()` binds its own tenant · `verifyCode()` IP count stays until **2B (after Q3)** · no `PreVotingGate` |
| 🟡 **Open / uncommitted** | ADR revision 3 draft rev 2 (S2 = real only) — signature + decision record · review `2026-10-05-real-election-code-and-vote-controller-review.md` (F-C*, F-V*) uncommitted · backlog candidates to confirm: **F-V6 / F-V7 (user id + receipt in one log line — anonymity)**, whitelist defects (cast / `json_decode`, hashed vs raw), DashboardResolver demo routing + reverse type check, F-C2, remaining sensitive logging |
| ❓ **Next** | PO: ADR revision 3 signature → VE-01 GREEN authorization · D6 (PBDIGIT-236) · D4.1 environment · Q3 (→ 2B) · confirm backlog candidates. *Supersedes the VE-01 block's "Track B not authorised" — Track B is done as PBDIGIT-238* |

---

## 📍 UPDATE (2026-10-05 late, **VE-01 ballot integrity — `PBDIGIT-237` · RED written · GREEN NOT authorised**)

| | |
|---|---|
| 🎯 **Active story** | **`PBDIGIT-237`** — the final cast commits the session ballot without checking it against the voter's ballot (VE-01, critical, proven T1). **`PBDIGIT-236`** = D6 regional ballot authority — separate, open |
| 🚦 **Current gate** | **D4.1 authorization** — D4.0 schema verification + query set **approved**; SQL validated on the empty test DB (13/13 EXPLAIN, read-only proven). Execution against election data **not authorised** (needs environment: staging if representative, else production read-only role; via `psql`) |
| 🔒 **Frozen** | D1–D3, D5 (approved candidacies only), N1–N5 |
| 🟡 **Open** | D6 (`PBDIGIT-236`) · ADR revision act (amended I1, I11–I13 approved in principle) |
| ⛔ **Not authorised** | VE-01 GREEN · T2–T9 · historical data repair · Track B (CodeController real/demo) edits |
| 📄 **Artifacts (committed, VE-01 RED + docs)** | `tests/Feature/Voting/{BallotIntegrityCharacterizationTest, FinalCastBallotInvariantTest}.php` · `docs/publicdigit/reviews/2026-10-05-{voting-engine-verification-gate-report, ve01-ballot-integrity-design-and-adr-amendment-proposal, d4-forensic-schema-verification-and-query-set, complete-voting-engine-business-audit}.md` · PBDIGIT-236/237 + README rows · guide `developer_guide/election/final_cast/05_…` |
| ❓ **Next** | PO: D4.1 environment + authorization → D6 decision → ADR revision act → GREEN authorization (engineering recommends D4/D6 not block GREEN — PO to decide) |

---

## 📍 UPDATE (2026-10-05, **`PBDIGIT-213` C0–C4 committed and accepted · Phase 1 closed · Phase 3 ADR approved · Phase 4 COMPLETE (E1–E4)**)

| | |
|---|---|
| 🎯 **Story** | `docs/publicdigit/backlog/PBDIGIT-213-election-only-voter-admission-v1.md`. ADR `docs/publicdigit/adr/ADR_20261004_1549_Voter_Admission_Policy_And_Voting_Entitlement.md`. Plan `docs/plans/20261004-1549-election-only-voter-admission-v1-plan.md` (rev 3, PO-approved) |
| ✅ **C1** | One authoritative `VotingEntitlementQuery` (`app/Contexts/Elections`), with scope derived from the election; it replaces 5 diverging entitlement copies. TDD: RED 17 → GREEN 33/33. Regression: 45 suites, pre-C1 worktree vs C1, **0 delta** (166 pre-existing failures, same causes) |
| ⚠️ **Overlap** | C1 resolves the defect of **`PBDIGIT-201`** (`start()` + ambient tenant). Recorded for PO acceptance; status not changed by engineering |
| 🔐 **Observation** | `.env.back_20260419_0138` and `.env.backup-laravel12` are tracked in git (may hold credentials; not opened). PO/ops decision |
| ✅ **C2** | Voter Admission Policy: "Identity verification required" ON/OFF; default OFF everywhere; Election-Only only; locked from ReadyForVoting (handler + model guard); `PATCH /elections/{slug}/admission-policy`; audited; affected-voter count; evidence columns `verifier_role`/`method`/`revocation_reason`. **Not enforced until C3.** RED 48 → GREEN 49/49. Regression: 54 suites, 0 delta. Two additive migrations; the dev DB is **not** migrated yet (needs confirmation) |
| ✅ **C3** | Identity admission **enforced**: policy ON + unverified ⇒ `NotAdmitted` at every authoritative real-election voting decision. Chief/Deputy-of-the-election authority; window SetupAdministration→VotingActive; a confirmed suspension revokes the current evidence effect (reinstatement **and** re-verification both needed); cache invalidated both ways. `Code.can_vote_now` untouched; `users.can_vote`/`is_voter` are dropped legacy columns (classified A–G, no bypass). C3 82/82, C1+C2+C3 167/167; 77-suite regression clean after a PO-approved fixture-only adaptation of `SimpleStoreTest`. Findings → **PBDIGIT-214…219** |
| ✅ **C4** | UI: Voter Admission card; Identity Verification column (policy ON only); Verify Identity (`IdentityAttestationDialog`, server-side `attested`); Verify All (C4-D1…D4); "Suspend Identity"; voter notice. **Correction:** identity only on the page; the legacy "Verify IP Address" UI entry point removed (IP verification = untouched legacy → `PBDIGIT-222`). Voting-process boundary pinned (`VotingProcessEntitlementBoundaryTest` 41/41, no CodeController change; `can_vote_now` = code step completed). 89-suite regression 0 delta; Vitest same 8 pre-existing failures. Dev DB migrated for C2 (2026-10-04, additive only) |
| 🆕 **Follow-ups** | `PBDIGIT-220` left column removed (implemented, separate commit) · `PBDIGIT-221` assign members as voters (open) · `PBDIGIT-222` IP verification as its own legacy record (open; legacy behaviour + PO decisions recorded) |
| 🔁 **C4 correction (2026-10-05)** | Identity-verification **evidence** is independent of the admission policy: Verify Identity / Re-verify Identity and Verify All available with the policy OFF; only the status column is policy-conditional; badge "Identity verified" (evidence, not final admission). Guide `04_c4_…` and `developer_guide/http/` updated (Phase 0) |
| 🔎 **Situation report** | `docs/publicdigit/reviews/2026-10-05-voting-authorization-and-security-situation.md` (rev 6): final-vote defects D1–D9; **anonymity A1–A5 — A5 critical: `vote_hash` recomputable from voter-side data + config (computable voter → ballot link)**; target `forEntry()` / `forCast()`; compare-and-set on `codes.has_voted`; Phase 0–7 plan; Phase 1 scope A–K |
| ✅ **Committed (2026-10-05)** | C4 `9c482db85` (PBDIGIT-213) · `7ea1dc06f` (PBDIGIT-220) · `ef2af5c3b` (PBDIGIT-221/222 backlog) · `c1a132065` (situation report + roadmap `docs/plans/20261005-0647-…-roadmap-plan.md`, PO-approved; only Phase 0 authorised) |
| 🧪 **Phase 1 (2026-10-05)** | Gate 0 accepted (C4 accepted). Characterization complete: 28 tests `tests/Feature/Characterization/Phase1/` (synthetic data), evidence `docs/publicdigit/reviews/2026-10-05-phase1-characterization-evidence-report.md`, defects **PBDIGIT-223 … 233** — **A5 confirmed; A6 (stored voter → ballot link in step 5) and D10 (unset `MAX_USE_IP_ADDRESS` blocks every vote) new**. No production change |
| 🧭 **Governance state** | Phase 1 CLOSED · **Gate 1 ACCEPTED** · anonymity model OPEN/DEFERRED (Phase 2.0) · A5/A6 unchanged = accepted interim risk · **interim anonymity rule ACTIVE** (7-point checklist, roadmap §2a) · **Phase 3 ADR AUTHORISED — design only** · Phase 7 IP binding BLOCKED (PBDIGIT-225 prerequisite) · production implementation NOT authorised |
| 📐 **Phase 3 ADR** | `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` — **✅ APPROVED by PO / ARB (2026-10-05), architectural baseline, frozen**; decision record `docs/publicdigit/reviews/2026-10-05-phase3-authorization-boundary-po-arb-decision-record.md`; implementation NOT authorised |
| 🛠️ **Phase 4 (2026-10-05)** | Final-cast hardening authorised per edit group. Plan `docs/plans/20261005-1105-phase4-final-cast-hardening-plan.md` (rev 3), decision record `docs/publicdigit/reviews/2026-10-05-phase4-final-cast-hardening-po-decision-record.md` (Q2 Deny, Q4 demo excluded, Q5 A, P4-D1 B, D2 A, D3 A, **D4 retired** → PBDIGIT-234). **✅ COMPLETE** — E1 `7aa33df68` (PBDIGIT-232), E2 `aa07a9160` (`forCast()` → `CastDecision`), E3 `8cea53c73` (PBDIGIT-233 for the final cast, 219), E4 `b1daf26a2` (PBDIGIT-230, compare-and-set before the vote insert). Found: PBDIGIT-234 (IP binding unreachable), PBDIGIT-235 (soft-deleted code usable). Guide `developer_guide/election/final_cast/` |
| 🔎 **Phase 4 audit** | `docs/publicdigit/reviews/2026-10-05-phase4-e1-e4-architecture-audit.md` — single-authority model confirmed, 0 blocking findings; **PO: Phase 4 architectural status ACCEPTED, frozen**. C1 (step-4 `verify()` still runs `vote_post_check()`; D10 blocks real voting there when `MAX_USE_IP_ADDRESS` is unset) elevated |
| ❓ **Next** | **Phase 5 — Voting Entry & Pre-Cast Authorization Discovery**, charter `docs/plans/20261005-1442-phase5-discovery-charter-plan.md` **APPROVED** → **Round 1 = C1 step-4 gate, `VoteController::verify()` only** (read-only + characterization, no RED/GREEN) → decision (KEEP / DELEGATE / REMOVE / RESTRUCTURE) → PO/ARB → only then implementation. `forEntry()` = Round 2 candidate (Q6, Q7; Q4 for demo). Ops: verify (not change) `MAX_USE_IP_ADDRESS` in every live-capable environment · ops: `MAX_USE_IP_ADDRESS` everywhere (code entry still reads it), inform the production owner about A5/A6 · decisions pending on PBDIGIT-234, 235 · R2a architecture test (deferred) · remove worktree `scratchpad/wt-before` |

---

## 📍 UPDATE (2026-10-01, **PBDIGIT delivery inventory baseline: CLOSED WITH KNOWN GOVERNANCE ITEMS** — `PBDIGIT-00`…`208`; ⛔ nothing implemented, nothing authorised, nothing committed)

| | |
|---|---|
| 📄 **Records** | completeness audit `docs/publicdigit/reviews/2026-10-01-pbdigit-completeness-audit.md` (specs `159`…`208`) · integrity review `…-pbdigit-story-integrity-review.md` · **final closure audit `…-pbdigit-story-integrity-final.md`** |
| 🧮 **Inventory** | 203 IDs (highest `PBDIGIT-208`); 1,040 source items classified across 7 source classes; 0 unclassified; 0 duplicates; 0 unowned obligations inside the boundary |
| 🔴 **Live defects (runtime-confirmed)** | `201` (`start()` refuses a valid voter: ambient tenant scope) · `202` (Full Membership import → `BadMethodCallException`) · `195` (canonical committee endpoint: 201, nothing persisted) · `203` (import reports success, 0 memberships, errors swallowed) |
| 🧪 **Test estate** | full `Unit` + `Feature` run: 6,762 tests · **1,965 failing in 335 files**; every file mapped to one owner (76–78 · 139 · 157 · 158 · RED · 165 · 167 · **208**) · 99 status-only write tests → `207` |
| 🧊 **Frozen (PO, 2026-10-01)** | *Delivery baseline frozen at PBDIGIT-208. New IDs only for a delivery obligation shown not to be covered by PBDIGIT-00…208.* |
| ✅ **Track 2 executed** | `docs/publicdigit/reviews/2026-10-01-strategic-knowledge-traceability-audit.md`: 215 strategic outcomes dispositioned (A 43 · B 35 · C 59 · D 19 · E 14 · F 9 · G 14 · H 22); **2 candidates** (Evidence-side bounded contexts unbuilt; Q-2 EPW anchor interim); two ratified BC sets unreconciled (H, ARB); **no PBDIGIT-209+**; K1–K10 PASS |
| 🆕 **Next-phase review** | `docs/publicdigit/reviews/2026-10-01-next-phase-status-and-todo.md`. The narrative's direction holds, but WP-4C-1 acceptance (R-93) and PB-006 (R-94) are already done, and the "production path verified end to end" claim is overstated. Runtime audit exists in 4 stores, with no unified review and no integrity protection (`election_audit_logs` is write-only); its purpose is a PO decision. ~~New `PBDIGIT-209`: release to `main`~~ **withdrawn before any commit** (PO: merge/release is class E, a later concern). `PBDIGIT-209` now denotes *"a membership fee payment cannot be recorded"*. The merge facts are kept in the review §5. |
| 🧭 **Backlog Completion Mode (PO)** | `docs/publicdigit/reviews/2026-10-01-backlog-completion-review.md`. **The merge story draft was withdrawn** (merge/release is class E). All 46 `app/` throw sites of the full run were classified. **New `PBDIGIT-209`: a fee payment cannot be recorded** (`membership_payments` lacks `currency`/`payment_reference`; 14/14 tests). Proposed corrections (not applied): 41 (third demo path broken: non-UUID `user_id`), 177 (R-73 done), 134 (commit hash), 167→120 re-link, 188→186 merge. ~~167/208 still own the classification of 1,636 remaining test failures~~ → classified in the following passes (see the rows below). |
| 🧪 **Test-estate classification (ARB: continue)** | All 1,636 remaining failures were classified (§8 of the completion review). **New `PBDIGIT-210`** (participant import roles vs the DB CHECK constraint) and **`PBDIGIT-211`** (scheduled jobs see no tenant data: the `BelongsToTenant` fallback looks for slug `platform`, which doesn't exist, so the filter is `organisation_id IS NULL`; fees, overdue, reminders and the cache flush do nothing). 209 updated (`income_id`). B: 158 root cause, 63, 195, 96, 29, 37, 74. Test isolation on PostgreSQL is absent or inconsistent per class (verified). One UNDETERMINED item (grace-period 403) remained at that point → **resolved as `PBDIGIT-212`** (next row). |
| 🛑 **Controlled closure pass: DISCOVERY CLOSED** | The last UNDETERMINED item was resolved as **`PBDIGIT-212`**: the timeline pages return 403 to every user (`string\|Election` + `can:`; on main since April/May). All 1,965 failures reconciled: **classified 1,965 · explained-verified 381 · explained by signature 1,265 · explained by agents 319 · resolved 0**. 211 now gated on an ARB decision (tenant-context model). **Ready for PO authorisation:** 212, 201, 202, 158; the fixture-only test stories 165, 166, 76/77/78, 139A, 157. Decisions: D-1, 209 schema, 210 vocabulary, the 211 architecture, member-import columns, H-7. |
| ❓ **Next (human)** | **D-1** (do engineering/doc items get PBDIGIT stories?) · ~~whether to commission the traceability audit~~ (commissioned) (the delivery inventory is closed; the knowledge-to-delivery chain is not yet shown closed) · then authorise a first story (candidates: `201`, `202`) |

---

## 📍 UPDATE (2026-09-30, **UC-4 / `GREEN-5` PO/ARB SCOPING DECISION BRIEF PREPARED — decision PENDING; `PBDIGIT-73` corrected additively (4 wrong claims, central finding survives); ⛔ nothing implemented, no option chosen** — newest block)

| | |
|---|---|
| 📄 **Brief** | `docs/publicdigit/reviews/2026-09-30-UC-4-GREEN-5-scoping-decision-brief.md` (placement derived, exit 0) — 12 sections; **no option selected** (`R-34`). |
| ❓ **The one decision** | **May UC-4 assume that an established `AcceptanceGateDecision` exists?** Governance/scoping only — not implementation, not `BND-1`, not aggregate ownership, not persistence. |
| 🔴 **What re-verification changed** | **`ADR-1` is ✅ DECIDED** (2026-08-18, governed composite absence semantics) and **its Rule-8 gate is ⛔ BLOCKED** — *"a separate DOMAIN slice is required first"*, because the contract §6(b) requires **does not exist**. `PBDIGIT-73` had cited neither. Gate **Q2** (no contract discriminating *required-but-absent* from *not-yet-reached-phase*) **is `BND-1`**. |
| ⚠️ **The sequence this decision would amend** | The gate's §5 records: `ADR-1 signed → Rule-8 gate → PO/ARB authorizes a DOMAIN slice → domain RED/impl/verify → normalization slice (UC-1/2/3) → pin GREEN → **GREEN-5**`. **`GREEN-5` is the LAST of four steps.** So the question is **not free-standing** — "yes" amends a recorded governance sequence. That is stated for the PO, not argued. |
| ✅ **Central finding CONFIRMED independently** | `ADR-1` **§6(c) authorizes the normalization slice for UC-1/UC-2/UC-3 only** ⇒ UC-4 is outside it ⇒ the two things called `GREEN-5` genuinely are different slices. The ADR corroborates `PBDIGIT-73` on its own terms. |
| ✅ **Also re-verified** | all four UC-4 REDs seed a **present** gate via `seedGate()`; **none** exercises `find()` → `null` · code's arc `GREEN-5`=UC-4 · `GREEN-6`=UC-5 · `GREEN-7`=UQ-1…4 · `H-2` **COMPLETE** (re-run: OK, 3/21) · pin still **RED** (1 test, 1 failure, both sites `FillCommitteeSeatHandler`) · `BND-1` **DEFERRED** (`D3`) · `BND-3` **DEFERRED** (`D4`) · `GREEN-5` **unauthorized** (no record names it) · `ADR-2` DECIDED, its Rule-8 gate also 🔴 BLOCKED (*"six B-class dependencies"*). |
| ⚠️ **`PBDIGIT-73` corrected (§9, `ES-004.3`)** | **C-1** `AcceptanceGateDecision` is in **six** application files, not one (the earlier grep searched the bare string `AcceptanceDecision`) · **C-2** `FillCommitteeSeatHandler` is **UC-3/GREEN-4**, not UC-1/GREEN-2 · **C-3** the pin **cannot flag an unimplemented body**, so its silence about UC-4 proves UC-4 is unwritten, not absence-safe · **C-4** *"far cheaper"* framing **withdrawn** — cost is not a recommendation engineering may make. Central finding, the `H-2` correction and the 16-RED mapping all **stand**. |
| ⚠️ **Repository contradiction found, NOT repaired** | `AbsentAggregateReferenceRedTest`'s docblock still says the absence ADR's *"decision block is blank"* — **stale since 2026-08-18**. Recommended as a separate one-line doc fix; the pin is binding and out of scope here. |
| 🧷 **Independence caveat disclosed** | The brief was prepared by the **same process** that wrote `PBDIGIT-73`. It re-derived from the repository and found four of its own errors (§2.6) — offered as the test of whether the re-check was genuine. A fresh process would be stronger evidence. |
| ▶️ **Next (human PO/ARB)** | Choose Option A (*may assume present*) or Option B (*absence must be defined first*). §11 carries proposed decision-record wording for each, to accept, amend or reject. If **A**: UC-4 needs its own authorization, then existing REDs → GREEN → independent verification → STOP. If **B**: the next act is PO/ARB authorization of a **domain slice** for the missing contract, which runs into `BND-1`. |
| 🚫 **Non-actions** | no option selected · no implementation · no RED added/changed/removed · **`app/` and `tests/` byte-untouched** · `BND-1`/`BND-3` untouched · `ADR-1`/`ADR-2` unamended · Act B not reopened · `GREEN-*` not renumbered · no domain contract designed/named/placed · stale pin docblock not repaired · `PBDIGIT-72` not extended (its `HaltedAtGate`-producer cause is **independent** and unaffected). |

---

## 📍 UPDATE (2026-09-30, **`PBDIGIT-73` RAISED — `GREEN-5` labels two different slices; `H-2` found already COMPLETE; the real gate on `GREEN-5` narrowed from `BND-1` to a one-line scoping question** — evidence recorded, ⛔ nothing implemented, no ruling made)

| | |
|---|---|
| 🎫 **Ticket** | `docs/publicdigit/backlog/PBDIGIT-73-green-5-labels-two-different-slices.md` (placement derived, exit 0) |
| 🔀 **The label collision** | **the code** says `GREEN-5` = **UC-4 `ReportPeriodExpiry` behaviour** (`ReportPeriodExpiryHandler.php:33`); **the Act-B design map** says `GREEN-5` = the **`ADR-1` §6(c) normalization slice**, blocked by `BND-1`/gate `G-6` (`…act-b-domain-design-map.md:276`). Two different slices, two different blockers, one label. The circulating roadmap carries the design map's reading. |
| 📐 **Code's real arc** | `GREEN-5` = UC-4 · `GREEN-6` = UC-5 · `GREEN-7` = UQ-1…UQ-4 — read from the six `BadMethodCallException` pending-guards, corroborated by guide 02's `Q-UC5` STOP "at GREEN-6". |
| 🎯 **Where the normalization concern actually lives** | `AcceptanceDecision` appears in **exactly one** application file — `FillCommitteeSeatHandler` (**UC-1**, already GREEN-2, `8ea13835a`). The failing pin names its two dereference sites, **both in that file** (`:105`, `:106`, helper `:206`). Not UC-4. |
| ✅ **§5.3 ANSWERED from evidence** | **All four UC-4 REDs seed a *present* gate** (`seedGate()` → `gates->seed()`, `OperatingCoreApplicationTestCase.php:128-138`); **none** exercises `find()` returning `null`. ⇒ **UC-4's pinned behaviour does not require `BND-1`.** The absence path is **unpinned**, so the real gate is the narrow question: **may UC-4 assume an established `AcceptanceGateDecision` exists, or must the slice pin absence first?** Far cheaper than `BND-1`. |
| ⚠️ **`H-2` is COMPLETE, not open** | The roadmap lists it 🟡 Open **twice** and calls it *"the immediate small step"*. Delivered 2026-08-19: RED `4da898886` → GREEN `3da3053d4` → **independently VERIFIED** `33f7316e7` → complete `29b8051b6`, with authorization + verification docs + guide 05. **Re-run today: OK, 3 tests / 21 assertions.** Commissioning a fresh `H-2` lane would redo verified work. |
| 🧪 **The suite is not broken** | `tests/Unit/Contexts/Election/` → **136 tests · 3,884 assertions · 15 errors · 1 failure**, and **all 16 map to planned slices**: UC-4 ×4 (`GREEN-5`) · UC-5 ×3 (`GREEN-6`) · queries ×5 (`GREEN-7`) · cross-cutting ×3 · **1 failure = the `ADR-1` §6(c) normalization pin** (`BND-1`-blocked; its own message forbids improvising the outcome). **"Existing unrelated failures" is wrong** — and invites someone to "fix" deliberate pins. |
| 🧭 **Roadmap steer accepted** | Do **not** reopen `BND-1` merely because Act B is complete; keep operational-status (B→C→D), normalization (`BND-1`→normalization slice) and `EM-GOV-063` reachability as separate tracks. That judgement is sound and unaffected by this finding. |
| ▶️ **Next (PO/ARB)** | Rule the scoping question. If *"may assume present"* → authorize the `GREEN-5` slice (four existing REDs → GREEN → independent verification → STOP), the pattern `EM-IMPL-002` already ran for GREEN-1…4. `PBDIGIT-72`'s UC-4 cause may then be clearable by an ordinary slice; its other cause (no authorized `HaltedAtGate` producer) is untouched. |
| 🚫 **Non-actions** | no UC-4/UC-5/query implementation · no scoping ruling (evidence only, `R-34`) · `BND-1`/`BND-3` untouched · no RED added or changed · **`app/` and `tests/` byte-untouched** · Act B not reopened · `ADR-1`/`ADR-2` not touched · `GREEN-*` guards not renumbered · `PBDIGIT-72` not extended · deliberate pins not "repaired". |
| 🗒️ **Housekeeping observed, not fixed** | the backlog holds **two `PBDIGIT-69` files** (`-operating-core-post-verification-items`, `-voter-eligibility-cache-is-tenant-unaware`). Duplicate id breaks traceability; recorded, **not renamed**. |

---
