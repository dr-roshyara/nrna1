# Plan — Voting integrity, ballot anonymity and authorization: roadmap (Phases 0–9)

**Date:** 2026-10-05 · **Status:** **APPROVED by the PO (2026-10-05)** as the roadmap; **only Phase 0 is authorised**; Phase 1 is not authorised until Gate 0 is passed; no production security/anonymity change is authorised; every later phase needs explicit approval at its gate
**Evidence base:** `docs/publicdigit/reviews/2026-10-05-voting-authorization-and-security-situation.md` (rev 6) — referenced below as *SR §n*
**Story context:** `PBDIGIT-213` (C4 closing) · `PBDIGIT-220` · `PBDIGIT-221` · `PBDIGIT-222` · new IDs to be created from Phase 1 evidence (frozen-baseline rule)
**Governance:** EP-01 (plan → approval → implement) · strict TDD (RED → GREEN, manual approval per production edit group) · plain Git only (no index manipulation) · `Code.can_vote_now` untouched unless a phase explicitly authorises it

---

## 1. Objective

Make the real-election voting path **robust in three respects, in this order**:

1. **Ballot secrecy** — the system may know *whether* a voter voted, never *which ballot* (today broken by A5: the vote's `vote_hash` is recomputable from voter-side data + config).
2. **Voting integrity** — one voter, one vote, enforced by the database; the final vote fails closed.
3. **Authorization** — one authorization composition evaluated fresh at two boundaries (`forEntry()` before code creation, `forCast()` at the final vote), with one security authority, before any new security evidence (IP binding) is introduced.

## 2. Background (facts, see SR)

- Admission (ALL-of) and standing entitlement are sound and fresh at the final vote (SR §2, §7).
- Final vote `VoteController::store()` is the right single boundary but has defects D1–D9 (SR §8): no DB row lock (D4), denial paths without rollback / likely `TypeError` (D2, D3), ignored `vote_post_check` (D1), `election_memberships.has_voted` never written (D9).
- Anonymity: A1 vote-id + IP log; A2 same-second timestamps code ↔ vote; A3 receipts / notifications (to audit); A4 time-ordered vote UUIDs; **A5 computable `vote_hash`** (SR §8a).
- IP/device security is mostly stored, not enforced; the client IP is spoofable (`trustProxies(at: '*')`) (SR §5, §6).
- `Code.can_vote_now` is overloaded (timer, kill switch, eligibility wording) (SR §4).

## 2a. Re-sequencing after Phase 1 (PO decision, 2026-10-05) — supersedes the earlier order rationale

**Position:** *Phase 1 discovered and characterized the problem. Phase 2.0 decides what anonymity means. Phase 2.1+ implements the consequences.* Until Phase 2.0, voter admission and voting security may proceed; the anonymity correction does not.

```
NOW (before Phase 2.0)                         Phase 2.0 Anonymity ADR                 Phase 2.1+
├─ record the anonymity problem (done)          ├─ threat model                           implementation of the
├─ keep A5 / A6 and all A1–A4, K2–K5 unchanged  ├─ Model A vs Model B                     chosen model (A: governed
├─ continue voter identity verification /       ├─ view-my-vote + receipt semantics       conditional anonymity ·
│  admission (C2–C4 model)                      ├─ administrator dispute resolution       B: strong secrecy)
├─ continue the voting-security / authorization ├─ identifier strategy
│  work: Phase 3 ADR, Phase 4 hardening,        ├─ timestamp / log policy
│  Phase 5 forEntry(), Phase 6 IP prerequisites ├─ historical data + backups
└─ do NOT implement any anonymity correction    └─ Anonymity Gate (8 questions)
```

**Interim rule (BINDING since Gate 1, until Phase 2.0):** *No new persistent voter ↔ ballot link may be introduced.* Existing questionable mechanisms remain untouched and are explicitly marked as pending the Phase 2.0 anonymity decision. Every proposed implementation, schema or logging change passes the **anonymity-boundary checklist**:
1. No new voter field on `votes`.
2. No new voter-side timestamp that can be joined with ballot-side timing.
3. No log entry combining voter-side identity and a ballot-side identifier.
4. No new receipt, notification, export or identifier carrying ballot identity.
5. No new deterministic value computable from voter-side data / configuration that identifies a ballot.
6. No new database relationship that permits voter → ballot traversal.
7. No modification of A5 / A6 merely to "fix" anonymity before Phase 2.0.

The administrator-assisted dispute-resolution intention remains a legitimate requirement candidate and must not be discarded.

### Governance state (PO, 2026-10-05)

| Item | State |
|---|---|
| Phase 0 | CLOSED ✅ (Gate 0 accepted, C4 accepted) |
| **Phase 1** | **CLOSED ✅ — Gate 1 ACCEPTED**: the characterization evidence is accepted; A5 and A6 are confirmed findings; A1–A4, D1–D4, D6, D9, D10 recorded per the evidence report. Gate 1 acceptance is **not** an approval of Model A or Model B. |
| Anonymity model | OPEN / DEFERRED ⏸ — Phase 2.0 is the authoritative decision point |
| A5 / A6 | UNCHANGED — accepted interim risk ⚠ |
| Interim anonymity rule | ACTIVE 🔒 (checklist above) |
| **Phase 3 ADR** | **AUTHORISED — design / documentation only ✅** (no production code, migrations, schema, routes, UI or behavioural change) |
| Phase 7 IP binding | BLOCKED ⛔ pending the anonymity-boundary review; PBDIGIT-225 is a prerequisite |
| Production implementation | NOT authorised ⛔ — manual approval before any production edit |

**Consequences recorded:**
- The earlier rationale "Phase 3 must come after Phase 2" is **superseded**: Phases 3–6 may run before Phase 2.0 because they add no ballot-side data.
- **Phase 7 (IP binding) is not covered by the relaxation by default:** it stores voter-side IP evidence while the cast already logs the vote id with the IP (A1 / PBDIGIT-225) — together that strengthens an existing voter ↔ ballot path. Phase 7 must pass the anonymity-boundary review; in practice it needs the A1 log separation (a Phase 2.2 item) first, or it waits for Phase 2.0.
- **Accepted interim risk:** A5 (PBDIGIT-223), A6 (PBDIGIT-224) and A1–A4, K2–K5 remain live in production until Phase 2.0 + 2.1. Recommendation unchanged: inform the production owner.
- **Not decided before Phase 2.0:** whether administrators may resolve an individual ballot dispute · whether `vote_hash` remains · whether the vote id may be exposed to the voter · whether view-my-vote may reveal the ballot · whether receipts are participation or ballot-verification receipts · whether administrators may retrieve individual ballots · whether historical logs / backups must become unlinkable.
- The `vote_hash` mechanism is **not** to be removed merely because it looks like an anonymity problem — its business intention (administrator-assisted dispute resolution) is a legitimate requirement candidate.

## 3. Constitutional principles (proposed for ADRs; govern every phase)

| # | Principle | Consequence |
|---|---|---|
| P1 | **Participation ≠ Ballot.** `User → Code.has_voted` ║ no link ║ `Vote`. | Never add `user_id`, `code_id`, voter or membership ids to `Vote`. |
| P2 | **Anonymity means non-derivability, not merely "no foreign key".** No voter-associated record, identifier, timestamp, log, notification, receipt, ordering information or other observable metadata — and no ballot-side value computable from voter-side data or configuration — may map a voter to a ballot. | Every change touching the cast path passes an **anonymity-boundary review** (SR §8b checklist). |
| P3 | **Authority ≠ Process state.** Admission → Entitlement → Security → Authorization decide; `can_vote_now`, `has_agreed_to_vote`, `has_voted` are process facts that never decide authority. | No persisted authorization field (`can_cast`, `is_authorized`, `security_passed`, …). |
| P4 | **`Code.has_voted` is the authoritative participation fact** (one code per voter and election — `unique(election_id, user_id)`). Other `has_voted` fields are projections and never decide. | Consumed by atomic compare-and-set in the vote transaction (Phase 4). |
| P5 | **Fresh at the boundary, fail closed.** Authorization is derived, never cached across the final write; any denial or `\Throwable` → rollback, no vote. | |
| P6 | **One authority per decision.** One admission decision (inside entitlement), one security authority (the existing Trust/Legitimacy decision — rename only via ADR), one authorization composition. No gate engine. | |
| P7 | **Characterization never de-anonymizes real data.** Linkage experiments run only on synthetic data in the testing database (`--env=testing`), never against the dev database or any real election. | Applies to Phase 1 test K. |

## 4. Strategic DDD — ownership

| Concern | Owning context / layer | Notes |
|---|---|---|
| Admission policy, evidence, decision | `app/Contexts/Elections` (Domain) | exists; unchanged by this roadmap |
| Voting entitlement (standing + lifecycle) | `app/Contexts/Elections` (Domain + `VotingEntitlementQuery`) | exists; stays *standing* (never absorbs security) |
| Voting security decision | existing `App\Application\Election\Security` (`TrustPolicyEvaluator` → `ConstitutionalLegitimacyDecision`) | home and name decided in the Phase 3 ADR |
| Authorization composition (`forEntry` / `forCast`) | Application layer, Elections | new, small, pure composition; Phase 3–5 |
| Cast transaction, ballot storage, receipts, view-my-vote | Voting process (`VoteController`, `CodeController`, `Vote`, `Code`, `ReceiptCode`) — legacy layer today | anonymity fixes live here (Phase 2); no domain extraction unless the Phase 2 ADR requires it |
| Client-IP trust | Infrastructure (`bootstrap/app.php` trusted proxies) + ops | Phase 6 |

No phase changes context ownership or the context map without an ADR.

---

## 5. Phases

Each phase: **objective · scope · deliverables · tests · gate · out of scope**. A phase starts only after the previous gate is passed.

### Phase 0 — Finish PBDIGIT-213 C4 and synchronize project state · **AUTHORISED, IN PROGRESS**

- **Scope:** C4 regression re-run after the policy-OFF correction; guide `developer_guide/election/voter_admission/04_c4_ui_and_bulk_verification.md`; `developer_guide/http/`; session log `.claude/sessions/2026-10-05.md`; `.claude/CONTEXT-publicdigit.md`; separate commits for C4 `(PBDIGIT-213)` and `PBDIGIT-220`.
- **Deliverables:** regression evidence (0 unexplained delta); Vitest (same pre-existing failure set); design-check ≤ 91; build + `public/build` restored; updated guides; proposed commit scope shown to the PO **before** committing.
- **Open decision:** how to commit `resources/js/Pages/Elections/Voters/Index.vue`, which carries both C4 and PBDIGIT-220 hunks, with plain Git only (no `hash-object` / `update-index`). Options to present with the diff.
- **Gate 0:** PO reviews results + commit scope → commits → PO acceptance of C4.
- **Out of scope:** any voting/security/anonymity change.

### Phase 1 — Characterization (read / test / document only) · NEXT

**Objective:** establish exactly how the current system behaves before changing it. Tests pin **current** behaviour; where current behaviour is the defect, the test is marked as a **known gap** so the later fix flips it into the target assertion. **No production change.**

| Group | Content (SR §11a) |
|---|---|
| 1-Integrity | **A** concurrent double cast → number of votes; is the cache lock sufficient · **B** `has_voted` false → true semantics · **B2** consumption + failed vote insert → rollback? · **C** source of truth (`codes` vs projections) · **D** D9: `election_memberships.has_voted` stale; all consumers (Voters page, `NewsletterService`) · **H** aggregate invariant `count(votes) = count(codes WHERE has_voted)` per election — **aggregate only, never per code** |
| 1A-Anonymity | **K1** `vote_hash` derivation (A5) · **K6** timestamps (A2) · **K7 / J** UUID order and time recovery (A4) · **K8** logs / tracing (A1) · **K9** database identifiers — all on **synthetic test data only (P7)** |
| 1B-View my vote & receipts | **K2** view-my-vote (`submit_code_to_view_vote`, `verify_to_show`) · **K3** receipt generation · **K4** receipt lookup · **K5** notification content and links. Classify each as *participation proof* or *ballot retrieval*; nothing removed |
| 1C-Code regeneration | **F1** an already-voted voter cannot obtain a usable code · **F2** after code 1 regeneration the new code must be verified (D6) |
| 1D-Final denial | **G** security / entitlement / lifecycle denial, exceptions, partial failure → vote written? `has_voted` committed? rollback? lock released? (D2, D3) — does the cast fail closed? |
| 1E-Ignored pre-check | **I** `vote_post_check` result unused by `store()` (D1) |

- **Deliverables:** the characterization test files (testing DB only); a **Phase 1 evidence report** (FACT / REPORTED / INFERENCE / RECOMMENDATION) updating SR; a proposed backlog item per confirmed defect (IDs justified under the frozen-baseline rule).
- **Gate 1:** PO reviews the evidence; each confirmed defect is assigned to a phase; **no fix without explicit approval**.
- **Out of scope:** any production change, including "obvious" one-line fixes.

### Phase 2 — Anonymity correction · NOT STARTED

**Objective:** restore P1/P2 on the cast path — **A5 first**, then confirmed A1–A4 and D9.

- **Sequence (PO, strict):** `Gate 1 → 2.0 Anonymity Gate + ADR (threat model + design) → approval → 2.1 A5 implementation`. **The A5 replacement is never designed and implemented in the same step**: design and implementation are separate approvals.
- **Anonymity Gate (must be answered before any Phase 2 production change):** state exactly what the system may retain about **participation** and what it must never retain or be able to derive about **ballot identity**:
  1. Who may know that voter X voted?
  2. Who may know which ballot X cast?
  3. Can the application itself calculate X → ballot?
  4. Can an administrator calculate X → ballot?
  5. Can logs calculate X → ballot?
  6. Can receipts calculate X → ballot?
  7. Can backups calculate X → ballot?
  8. Can historical data calculate X → ballot?
- **Trust model (decided first in 2.0; PO discussion 2026-10-05):** **Model A — administrative dispute resolution:** an authorised administrator can inspect an individual voter's ballot when the voter disputes the result → ballot anonymity is *conditional*, must be declared as such, and the capability must be governed (access control, four-eyes, audited use); it also weakens coercion resistance. **Model B — strong ballot secrecy:** even the election administrator cannot determine a voter's ballot → an individual "what did I vote" dispute cannot be resolved by the administrator; that is a consequence of secrecy, not a defect. ADR questions: **Q1** strong secrecy against administrators? · **Q2** administrator-assisted individual dispute resolution? · **Q3** dispute resolution without identifying the ballot (voter-held evidence)? The existing `vote_hash` (PBDIGIT-223) embodies a latent, ungoverned Model-A capability; its fate (remove / redesign / retain under a declared model) follows from these answers. **Business intention behind it (PO): dispute resolution — a legitimate requirement candidate.**
- **Purpose of "view my vote" (decided in 2.0, from Phase 1 evidence):** **A** participation receipt — *"I participated"* · **B** ballot verification — *"this is the ballot associated with my voting credential"* · **C** ballot retrieval — *"show me exactly what I voted"*. B and C have very different anonymity and coercion implications from A.
- **2.0 Anonymity ADR (design gate, before any code):** threat models (external observer vs operator); semantics of receipts and view-my-vote (participation proof vs ballot retrieval, coercion risk); identifier strategy for ballots; timestamp policy (voter-side participation time coarse or absent); log separation (voter-side vs ballot-side events, never shared fields); **historical data**: votes already cast keep a recomputable `vote_hash` while code values / timestamps exist (including backups and logs) — decide remediation (e.g. clear code values and completion timestamps of closed elections, re-hash, retention) explicitly.
- **2.1 A5 (only after the ADR is approved):** no ballot-side value derivable from voter-side data or configuration (**candidate solution, not an approved architecture**: a random receipt secret held only by the voter, server stores only its hash with no code relation; code value cleared after use).
- **2.2 A1–A4:** separate logs; remove correlatable timestamps; receipt linkage per the ADR; random, non-time-ordered ballot ids (voter-side ids may stay).
- **2.3 D9:** make `election_memberships.has_voted` a correct projection of `codes.has_voted` **without** a correlatable timestamp (or derive it, or remove it) — per the ADR.
- **Tests:** the Phase 1 known-gap tests flip to target assertions; anonymity-boundary tests (K1–K9) green.
- **Gate 2:** each anonymity change reviewed individually (own story, own commit).
- **Out of scope:** authorization, IP binding.

### Phase 3 — ADR: Voting authorization boundary · NOT STARTED

Freeze: Admission → Entitlement (standing + lifecycle; admission evaluated **once**, inside entitlement) → `forEntry()` → code process → `forCast()` → vote. Decide: home and name of the **one** security authority (extend vs rename Trust/Legitimacy); the reason enum (`Allowed` only success; forwards `VotingEntitlementReason`; `SecurityDenied`; `AlreadyVotedPreCheck` non-authoritative); security policy consolidation and **lock from ReadyForVoting**; demo-election scope; whether `has_agreed_to_vote` becomes a `forCast()` process fact (behaviour change).
- **Gate 3:** ADR accepted (ARB/PO).

### Phase 4 — Harden the final cast boundary · NOT STARTED

`BEGIN → forCast() (fresh) → UPDATE codes SET has_voted = true WHERE id = ? AND has_voted = false (exactly 1 row, else deny) → INSERT anonymous vote → COMMIT`; any denial / `\Throwable` → rollback, lock released, no vote (P5). Fix confirmed D1–D4. The cache lock may remain only as an optimisation.
- **Tests:** concurrency (exactly one vote), atomic rollback, aggregate invariant, every denial branch fails closed.
- **Gate 4:** PO review of RED → GREEN.

### Phase 5 — Entry authorization `forEntry()` · NOT STARTED

`GET code/create → forEntry() → allowed? → getOrCreateCode()` (the code is created and e-mailed there — SR §3); at code entry the same `forEntry()` **replaces** `CodeController::verifyCode`'s own IP check (no second workflow). Optional advisory pre-checks at ballot entry / first submission; only `forCast()` decides.
- **Gate 5:** PO review.

### Phase 6 — Infrastructure prerequisites for IP security · NOT STARTED

Trusted proxies (replace `trustProxies(at: '*')`, ops-verified); IP normalisation (IPv6 canonical, IPv4-mapped); IPv6-capable validation (remove `ipv4` rule, IPv4 regexes, `ip2long` CIDR); matching semantics (exact vs prefix) and operational policy for mobile / CGNAT. The IP-binding policy must be **impossible to enable** before this phase is accepted.
- **Gate 6:** ops + PO sign-off.

### Phase 7 — PBDIGIT-222: IP binding · NOT STARTED

Security, not admission: per (election, voter) binding of a **server-observed IP from the voter's own request**, confirmed by the Chief/Deputy (audited), evaluated fresh in `forEntry()` / `forCast()` by the one security authority; IP change → deny + audited re-bind. Own persistence (`voter_ip_verifications`, schema confirmed against the legacy behaviour recorded in PBDIGIT-222); never writes `voter_verifications`; never influences admission. Restores the "Verify IP Address" entry point.
- **Gate 7:** PO review; anonymity-boundary review (IP evidence must never be stored or logged with ballot data).

### Phase 8 — Device binding · DEFERRED

Only when a device-evidence source exists that the client cannot trivially forge; then a separate policy under the same security authority.

### Phase 9 — High-assurance anonymity · FUTURE

Anonymous credentials, blind signatures, unlinkable voting tokens, cryptographic verifiability — only if the threat model requires it.

---

## 6. Task checklist

- [x] **P0** C4 regression re-run evaluated — 89 suites, 0 changed results, 0 reason delta (only the 3 new A2 tests added)
- [x] **P0** Vitest full run (identical pre-existing failure set) · design-check 91 · build + restore
- [x] **P0** guide `04_c4_…` updated · `developer_guide/http/` created
- [x] **P0** session log 2026-10-05 · CONTEXT updated
- [x] **P0** commit scope approved per commit; `Index.vue` split by temporary manual `HEAD` restoration → C4 `9c482db85` (PBDIGIT-213) · `7ea1dc06f` (PBDIGIT-220) · backlog `ef2af5c3b` (PBDIGIT-221, PBDIGIT-222)
- [ ] **P0** remaining housekeeping (session logs, CONTEXT incl. the PO's pending rows) · backlog README index lines for 220–222
- [x] **P1** characterization tests A–K (synthetic data only, 28 tests in `tests/Feature/Characterization/Phase1/`) · evidence report `docs/publicdigit/reviews/2026-10-05-phase1-characterization-evidence-report.md` · backlog items PBDIGIT-223 … 233 — **Gate 1 ACCEPTED**
- [ ] **P2.0** anonymity ADR (incl. historical data) → **P2.1** A5 → **P2.2** A1–A4 → **P2.3** D9
- [ ] **P3** authorization ADR
- [ ] **P4** final-cast hardening
- [ ] **P5** `forEntry()`
- [ ] **P6** IP infrastructure prerequisites
- [ ] **P7** PBDIGIT-222 IP binding
- [ ] **P8** device binding (deferred) · **P9** high assurance (future)

## 7. Progress

| Phase | State |
|---|---|
| 0 | Nearly complete — guides, Vitest, design-check, build, C4 regression re-run (0 delta) done; C4 `9c482db85`, PBDIGIT-220 `7ea1dc06f`, PBDIGIT-221/222 `ef2af5c3b` committed; housekeeping and README index lines open; Gate 0 pending |
| 0 → closed | Gate 0 accepted by the PO (C4 accepted) |
| 1 | **CLOSED — Gate 1 ACCEPTED (2026-10-05)** (28 tests pass; 11 confirmed defects PBDIGIT-223 … 233; no production change) |
| 3 | **AUTHORISED — ADR / design only** (2026-10-05) · `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` (PROPOSED, rev 2 — **READY FOR PO / ARB REVIEW**) — awaiting the governance decision and answers to Q1–Q7; implementation not authorised |
| 2–9 | Not started (not authorised). Re-sequenced 2026-10-05 (§2a): Phase 2.0 decision postponed; Phases 3–6 may come first under the interim rule; Phase 7 gated by the anonymity-boundary review |

## 8. Risks

| Risk | Mitigation |
|---|---|
| **A5 stays live in production while the roadmap runs** | inform the production/ops owner now; Phase 2 is the first production phase |
| Characterization de-anonymizes real votes | P7 — synthetic data in the testing DB only |
| Historical votes remain linkable after the A5 fix | explicit remediation decision in the Phase 2 ADR (incl. backups and logs) |
| Fixing D9 creates a new timestamp correlation | D9 fixed only inside Phase 2 under the anonymity ADR |
| `VotingEntitlement` drifts into a generic gate | P6; ADR in Phase 3; architecture tests |
| A second security authority appears beside Trust/Legitimacy | P6; no parallel class before the Phase 3 ADR |
| IP binding enabled on a spoofable IP | Phase 6 gate makes the policy impossible to enable before prerequisites |
| Scope creep into `can_vote_now` cleanup | separate story; not part of this roadmap unless a phase authorises it |
| `Index.vue` carries two stories' hunks | decide the plain-Git split at Gate 0 with the diff in front of the PO |

## 9. Open questions

1. Phase 0: how to split `Index.vue` between the C4 and PBDIGIT-220 commits with plain Git.
2. Phase 2 ADR: receipt / view-my-vote semantics; identifier strategy; timestamp policy; historical-data remediation; log retention.
3. Phase 3 ADR: name/home of the security authority; demo scope; security policy consolidation and lock; `has_agreed_to_vote` in `forCast()`.
4. Phase 6: who owns the trusted-proxy configuration (ops vs code); matching rule for IP binding.
5. Story IDs: which phases become new PBDIGIT items (justified under the frozen-baseline rule) vs extend PBDIGIT-222 / existing items (e.g. PBDIGIT-45 for IP density).

## 10. Next actions

1. Finish Phase 0: evaluate the C4 regression, present results and the proposed commit scope (no commit before PO review).
2. After Gate 0: request the narrow Phase 1 authorisation — *"Phase 1 characterization only. Synthetic testing database only. No production changes. Stop at Gate 1."*

~~**Order rationale (PO):** the authorization ADR (Phase 3) stays **after** the anonymity correction (Phase 2): a well-designed authorization layer on top of a ballot system that can deterministically reconstruct voter → vote would still be constitutionally unacceptable.~~ **Superseded 2026-10-05 by §2a** (PO re-sequencing): Phases 3–6 may proceed before Phase 2.0 under the interim rule; the anonymity correction still precedes any claim that ballots are anonymous.

**Traceability:** SR `docs/publicdigit/reviews/2026-10-05-voting-authorization-and-security-situation.md` (rev 6) · `docs/plans/20261004-1549-election-only-voter-admission-v1-plan.md` (C4) · ADR_20261004_1549 · PBDIGIT-213 / 220 / 221 / 222 · brainstorming `docs/publicdigit/brainstorming/20261005_0615_voter_pre_check_authorization.md`, `voter_management/20261005_0616_Claude's architecture review.md`.
