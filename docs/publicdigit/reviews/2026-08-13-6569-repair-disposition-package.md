# PBDIGIT-65/69 Repair Disposition — decision-ready, nothing chosen

**Type:** Governance disposition package (Session 2) · **Date:** 2026-08-13 · **Commission:** PO — reconcile the Decision-B audit; evaluate repair options **without choosing**; distinguish *audit complete* from *repair authorized*
**⛔ No option chosen · no repair location selected · no grant created or signed · no code/test/Constitution change · EM-VOT-002 not reopened · EM-OPEN-021 untouched · Session 1/3/4 remain stopped.**

> ## **AUDIT COMPLETE ≠ REPAIR AUTHORIZED.** The evidence threshold is crossed; the authorization threshold is not.

---

## 1 · What the audit established (reconciled, with strength labels)

**Source:** [`2026-08-13-belongstotenant-family-audit.md`](2026-08-13-belongstotenant-family-audit.md) (`9cf441fc`) — executed exactly under the PO-authored charter; nothing repaired; stop conditions honored.

| Finding | Strength |
|---|---|
| **41 true trait consumers** (enumeration self-corrected from 24 after failing its own sanity check — disclosed) | MEASURED |
| **9 Decision-A-relevant (class A) models**; **19 models (classes D+E) explicitly OUTSIDE Decision A** — a shared technical mechanism does not imply shared domain semantics; Membership/Contestation/Adjudication tenancy semantics belong to their own contexts, unadjudicated | MEASURED / boundary held |
| **TWO confirmed Decision-A violations, ONE mechanism**, both feeding the same live voting stack: **(1)** `User::isVoterInElection()` (`User.php:315`) — scoped relation, no bypass, **plus** tenant-free cache key `user.{id}.voter.{election_id}` — **runtime-proven (P6)**; **(2)** the `isEligible` projection at **`app/Http/Controllers/ElectionVotingController.php:37-44`** *(path precision: not under `Http/Controllers/Election/`)* — same scoped relation, no bypass — **the original PBDIGIT-65 site, verbatim unchanged; proven by the ticket's 2026-08-09 runtime A/B + static re-verification now.** Session 2 re-read both sites: the relation is a plain `hasMany` on the trait-scoped model; verified | PROVEN (2 sites, 1 mechanism) |
| **EM-VOT-002 is NOT tenant-contaminated** — both enforcement paths reach candidacies through `withoutGlobalScopes()` at the relation | verified statically + P0 tests · **do not reopen** |
| `Election::getVoterCountAttribute` — same tenant-free-key/tenant-dependent-answer pattern | ⚠️ **STATIC only, not runtime-reproduced** |
| **`has_voters` hybrid** (one branch bypassed, one scoped) — `complete_administration` can flip with ambient context | ⚠️ STATIC · **lifecycle-transition concern, NOT voting-time entitlement — Decision A does not govern it** (§4) |
| **375 `withoutGlobalScopes()` calls repo-wide** | **MEASURED COUNT ONLY — 375 bypasses ≠ 375 proven-correct sites; per-site correctness NOT audited.** Meaning: *domain correctness on this path currently depends on developers remembering a per-site bypass* — an architectural smell on the record; whether it warrants redesign is a separate decision |

**Documentation arithmetic discrepancies — preserved, NOT silently fixed (PO instruction):** ① audit §3 class C says **"8"** but lists **9** names (the audit's own parenthetical partially acknowledges the Demo pair); ② `OrganisationUser` is deliberately dual-listed (B+E), so class totals must not be summed; ③ audit §1's headline says **"ONE demonstrated violation (runtime-proven)"** while §4/§8 record **"2 sites, 1 mechanism"** — consistent once proof provenance is stated (site 1 proven by P6 now; site 2 by the ticket's own earlier runtime A/B + verbatim-unchanged static check). None of these invalidates the 41-consumer count, which is independently stated.

> **Update (2026-08-13, `67ace629`):** Session 1 has appended its own §11 correction record — original text left standing, corrections not silently applied: **class C = 9 confirmed**; totals must not be summed (dual-listing); and the §1 sentence *"the sites that remembered are compliant"* **withdrawn as too strong** — the accurate statement is now on their record: *"the bypass count measures how widely correctness depends on per-site developer memory, not how often it was achieved."* Core findings unchanged. Items ① and ② of this list are thereby resolved by their author; item ③ (proof provenance) stands as explained here.

## 2 · The repair subject, precisely

One mechanism, two expressions, one live stack:

```
tenant-scoped relation (BelongsToTenant on ElectionMembership)
        read WITHOUT bypass at two voting-time sites
                +
tenant-DEPENDENT answer cached under a tenant-FREE key (site 1)
                =
valid Election entitlement invisibly revoked by ambient context,
and the wrong answer transported across contexts by the cache
```

This violates the accepted Decision-A rule and the adopted entitlement model (Model B; `EM-ENT-004`; `EM-GOV-001`): *the entitlement's answer must be a function of the election and the voter's admitted status in it — never of ambient context.*

### 2a · PO INVARIANT CLARIFICATION (2026-08-14 — performative; supersedes the §2 phrasing above and template v2's invariant)

**The PO clarified the rule** — it is **NOT** *"a voter may vote regardless of tenant context"*. It is, verbatim:

> **"For a given voter and election, voting-time entitlement must be evaluated against the organisation/tenant context belonging to that election. A matching context may permit voting; a non-matching context must not. The result must not be corrupted by cache state from another context."**

With the PO's expected-behaviour table (verbatim in substance):

| Situation | Expected |
|---|---|
| Voter belongs to Election **and** tenant matches Election | ✅ may vote |
| Voter belongs to Election, tenant does **not** match Election | ❌ may not vote |
| Voter belongs to another Election | ❌ may not vote |
| Correct tenant, but cached result originated from another tenant | **must be recalculated correctly — never reuse the wrong-context answer** |

**Consequences — registered, not silently absorbed:**

1. **Decision A sentence (i) is CLARIFIED, not repealed:** ambient context is forbidden **as the SOURCE of scope derivation** (the election defines which organisation must match — the election still owns resolution); the active context participates **as an explicit correspondence COMPARAND**. *"The problem is not that tenant context exists"* (PO) — it is that the current implementation lets an arbitrary ambient value act as an invisible filter, and lets a cache transport answers across contexts.
2. **`PBDIGIT-65`'s ticket acceptance criterion is SUPERSEDED IN PART by this ruling.** The ticket said *"a voter's right to vote must not depend on which page they visited last"*; the PO now rules that **exercise requires the matching context** (the durable *entitlement* persists — Model B — but wrong-context *exercise* is denied). **Tests must encode the PO's four-row table, not the ticket's sentence.** Tickets are evidence, never authority — this is that rule operating.
3. **P6's evidence re-reads under the clarified invariant:** step 1 (wrong tenant → FALSE) is **outcome-CONSISTENT with row 2** — though produced by a non-conformant mechanism (implicit ambient filter + platform-org fallback, not an election-derived correspondence check); step 2 (correct tenant + warm cache → FALSE) **remains the proven outcome defect — row 4 violated** (`PBDIGIT-69`); step 3 (correct tenant, cleared → TRUE) is row 1, correct.
4. **PA confirmation of the P6 re-reading (2026-08-14), with the precise formulation now on record:** *"The system correctly denies voting in a mismatching tenant, but it does not reliably distinguish that legitimate denial from a denial produced by an incorrect tenant context and subsequently cached."* Also confirmed: the cache observation stays an observation — the grant must not pre-decide *"change the cache key in `User.php`"*; cache identity is Session 3's design question after the grant, under the invariant that a result produced under a different organisational context is never reused.

### 2b · Q-TEN-1 and Q-TEN-2 — two OPEN semantic questions that now GATE the grant (2026-08-14; Session 1 re-evaluation §12, `0c88a02b`; PA-confirmed)

Session 1 re-evaluated the audit under the clarified rule: **both violations STAND — the reclassification changes the reason, not the verdict** — and its §12.2 measurement surfaced a genuine domain ambiguity inside the clarified invariant itself:

**Measured (Session 2 re-verified the primary site):** the only DELIBERATE election-derived organisation comparison on the voting stack is **`VerifyVoterSlugConsistency:58`** — `election.organisation_id === voterSlug.organisation_id` — a comparison against the **voting-session (slug) context**, not the session tenant. **`EnsureRealVoteOrganisation`, despite its name and docblock, performs no organisation comparison** (verifies a slug exists and delegates — Session 1 measurement). **No site deliberately compares the AMBIENT SESSION tenant to the election's organisation; the session tenant's only participation is the accidental global scope at the two violating sites.**

> ## **Q-TEN-1 (OPEN — PO):** when a voter tries to vote, WHAT organisational context must match the Election?
> **(a) the current session/tenant organisation** — then no site deliberately enforces the clarified rule today, and the accidental scope is the only enforcement; **(b) the voting-session/credential (slug) organisation** — then the required comparison already exists, election-derived, at `VerifyVoterSlugConsistency:58`. **Materially different repairs follow. Implementation evidence must not choose. This is Election domain semantics** — *"which organisation does this Election belong to"* is domain data; *"which organisation is this request operating under"* is execution/infrastructure context; **the domain must not silently derive the first from the second, and a Laravel ambient session mechanism must not become the owner of an Election business rule.**

> ## **Q-TEN-2 (OPEN — PO):** what happens when there is NO execution context?
> **deny** (safest explicit boundary) · **derive from the Election** · **platform/default organisation** (the current trait fallback — **implementation evidence, not business authority**). ⛔ Session 3 must not choose from current behaviour.

**Adjacent observation carried (not a defect claim):** `VerifyVoterSlugConsistency:59-60` treats `organisation_id === 1` as "platform" — an integer comparison while organisation ids are UUIDs in current data; branch reachability NOT ESTABLISHED (Session 1).

### 2c · Q-TEN-1 RULED (2026-08-14 — performative PO ruling, verbatim) — Q-TEN-2 is now the sole blocker

> **"Q-TEN-1 DECIDED: The voting-session/credential organisation must match the Election's organisation. The Election is authoritative for the required organisation; the voting-session/credential supplies the organisation against which that requirement is compared."**

**Business rule (PO wording):** *a voter may vote in an election only through a voting session/credential that belongs to the same organisation as that election.* Credential table (verbatim in substance): election-org A + credential-org A → ✅ · A + B → ❌ · B + B → ✅ · B + A → ❌. **The Election determines the required organisation — infrastructure context never owns this rule.**

**Consequences traced:**

1. **The required comparison already exists at the authoritative site** — `VerifyVoterSlugConsistency:58` is election-derived and credential-compared. *(Measured evidence of current enforcement — NOT an instruction that the repair must live there.)*
2. **The ambient SESSION tenant now has NO legitimate role anywhere in voting-time entitlement:** it is neither the comparand (the credential is) nor a permissible filter (the accidental scope is the defect at both violating sites). **Derived reading (Session 2, from the ruling — cheap one-line confirm at boundary presentation):** for an admitted voter, the *entitlement predicate's* correct answer is independent of session-tenant state — `PBDIGIT-65`'s original "independent of navigation" criterion is effectively **reinstated at the predicate layer**, while the deny-duty for organisation mismatch lives at the **credential layer** (already enforced). *(This is P6 step 1's third reading: defect → outcome-consistent under reading (a) → defect again at the predicate layer under ruling (b). The verdicts on both sites never changed; the reason now settles.)*
3. **`PBDIGIT-69` unchanged and unambiguous:** a cached FALSE produced under any wrong context must never be replayed — the warm-cache failure remains the proven defect under every reading.
4. **The `=== 1` platform branch (§2b observation) now sits ON the authoritative comparison site** — its reachability/correctness becomes a targeted-verification candidate once Q-TEN-2 is ruled. Not commissioned here.
5. **No mechanism is prescribed** — "change the cache key in `User.php`" remains explicitly un-said; the invariant is the authority, the technique is Session 3's design under TDD.

**Q-TEN-2 restated under ruling (b) — OPEN, now the SOLE blocker (PA-directed: do not sign until explicitly resolved):** *what must the system do when there is no voting-session/credential context at all?* — **deny** (explicit boundary) · **derive from the Election** · **platform/default** (current implementation behaviour, evidence not authority).

### 2d · Q-TEN-2 RULED (2026-08-14 — performative PO ruling) — ALL semantic blockers now CLOSED *(the signature followed the same day: §2e)*

> **PO ruling:** *"Q-TEN-2 — no voting credential/context → deny."*
> **Formal wording (PA-drafted, ruled in substance by the line above):** *"If no voting-session/credential context exists, the voter must be denied voting. The system must not derive the context from the Election and must not fall back to the platform/default organisation."*

**Consequences traced:**

1. **The deny boundary is explicit:** absent credential/context is a denial, never an inference. The two rejected alternatives are rejected *by ruling*, not by preference: no derive-from-Election, no platform/default fallback.
2. **The trait's platform-org fallback is now ruled NON-CONFORMANT for voting-time entitlement paths** — previously "implementation evidence, not authority"; now measured behaviour that the ruled rule forbids on this path. *(How the repair achieves conformance remains Session 3's design; other paths' fallback semantics belong to their own contexts — nothing family-wide is ruled here.)*
3. **The `=== 1` platform branches at `VerifyVoterSlugConsistency:59-61`** ("valid if same org OR election is platform OR user is platform") now warrant scrutiny **at boundary presentation**: they sit on the authoritative comparison site, their reachability is NOT ESTABLISHED (integer vs UUID), and their bypass semantics must be checked against the Q-TEN-1/Q-TEN-2 rulings. **Flagged, not adjudicated.**
4. **Scope-of-statement guard (PA, registered):** *"the session tenant has no legitimate role"* holds **for the entitlement predicate** — it must not be overread as "tenant context has no role anywhere in the voting workflow." The clean model, on the record: **Election → determines the required organisation · Credential → provides the organisation to compare · Ambient session tenant → must not determine entitlement.**

**Track A semantic state: Decision A CLOSED · Decision B CLOSED (audit executed) · Q-TEN-1 CLOSED · Q-TEN-2 CLOSED. The grant (v5, §5) is final-form and awaits ONE explicit PO signature. Session 3 reopens only after it.**

## 3a · *(renumbering note: §3 below and all option analysis stand; where v2 text says "same answer regardless of ambient context", the §2a invariant governs)*

## 3 · The three repair options — evaluated, NONE chosen

| | **Option A — repair the two confirmed sites** | **Option B — Election-owned voting-time access pattern** | **Option C — broader infrastructure remediation** |
|---|---|---|---|
| Content | fix `isVoterInElection()` + the `ElectionVotingController` projection; `BelongsToTenant` mechanism untouched | the Election context explicitly resolves its own organisational scope for voting-time entitlement — no reliance on *scope + remembered bypass* | rule on whether unconditional `BelongsToTenant` on election-keyed models is itself structurally inappropriate |
| Expresses Decision A… | at the two defect sites only | **architecturally** — *Election → entitlement → Election-owned scope → no ambient dependency* replaces *ambient tenant → global scope → hope the caller remembered the bypass* | at the mechanism level, family-wide |
| Size / risk | smallest; bounded; testable against the P6 A/B | larger; touches the voting-time read path; **WHERE it lives is an architecture decision, not stack-trace-derived** | largest; crosses bounded contexts (9 class-A models; 19 models outside Decision A); **must NOT be smuggled into 65/69** |
| What it leaves open | the mechanism smell persists (recorded risk); `voter_count` + `has_voters` + 375-bypass fragility untouched unless separately included | same residuals minus the voting-path fragility | class-D/E semantics need their own contexts' rulings first |
| Prerequisite for a grant | none further — evidence complete | an architecture design decision (home + shape) recorded first | its own programme decision; audit evidence *considers* it, does not authorize it |
| Composability | **A now + B later is coherent** (A cites B as recorded follow-up intent) | can follow A without waste (A's tests survive as regression) | independent track |

**Principal-Architect recommendation on record (not a ruling):** do not choose B or C yet; disposition around the two confirmed violations first.

## 4 · Adjacent findings needing their own rulings *(not silently swept into any option)*

1. **`voter_count` cache** — same pattern, static evidence only. Sub-question: include in the A-grant explicitly, or leave for the pattern track?
2. **`has_voters` hybrid** — lifecycle concern, outside Decision A. **Relevance flag (evidence, not a decision):** it gates `complete_administration` on the Election-Only critical path, and the **adopted `EM-VOT-003` voter-half will make voter presence constitutionally significant at the voting boundary too** — when EM-VOT-003's implementation is designed, the *same ambient-context question* arises there. Sub-question: rule on it with the voting-time repair (shared mechanism), or as its own item (different boundary)?
3. **Cache rule candidate** — *"tenant-dependent answer ⇒ tenant-qualified key"*: two instances now exist; **meets ES-006.1's threshold for a PKS OBSERVATION, not promotion.** Recordable on request.
4. **375-bypass fragility** — recorded architectural smell; a per-site audit or fitness rule would be its own commissioned work.
5. **Class D/E consumers** — explicitly outside this programme's authority; their contexts' governance, if ever.

## 5 · Grant template — Option A, PREPARED AND EXPLICITLY UNSIGNED *(EM-VOT-002 package precedent)*

> ~~**☐ NOT GRANTED — ALL semantic blockers CLOSED (Q-TEN-1 §2c; Q-TEN-2 §2d). This is the FINAL-FORM grant (v5), presented for the PO's EXPLICIT SIGNATURE. Until signed it authorizes nothing; Session 3 remains stopped.**~~
> **⚠️ SUPERSEDED SAME DAY — concurrent-write reconciliation (2026-08-14):** this status line was written in parallel with the PO's acceptance commit (`f6bb5504`) and is **overtaken by §2e/§5a: the grant is ☑ GRANTED, gated on boundary presentation.** Kept struck-through for the record; the authoritative status is §5a.
>
> **Invariant (v5 — the ruled wording, complete):** *"A voter may vote in an election only through a voting session/credential that belongs to the same organisation as that election. The Election is authoritative for the required organisation; the voting-session/credential supplies the organisation against which that requirement is compared. If no voting-session/credential context exists, the voter must be denied voting — the system must not derive the context from the Election and must not fall back to the platform/default organisation. The voting-time entitlement evaluation must not be filtered by ambient session/tenant context, and a result produced under any other context must never be reused."*
> **Acceptance encodes:** the §2c credential table · the §2d no-credential→deny rule · the cache-never-replays rule. **At boundary presentation:** the predicate's session-independence derived reading (§2c.2, one-line confirm) · the `=== 1` platform-branch semantics (§2d.3, scrutiny against the rulings).
>
> *Template revision v4 (2026-08-14): invariant replaced with the Q-TEN-1-ruled wording; v3's "organisation/tenant context belonging to that election" made precise — the comparand is the CREDENTIAL organisation, and the session tenant is excluded from entitlement evaluation entirely.*
> *Template revision v5 (2026-08-14): Q-TEN-2 clause added (absent credential/context → deny; no derivation; no platform fallback). No open semantic question remains inside the grant. Corrections noted, never silently swapped.*
>
> **Scope:** repair of **the two confirmed voting-time entitlement violations identified by the Decision-B audit** — scoped by the violations, **not by file prescription**. *(The observed sites — `User::isVoterInElection()` `User.php:315-328` incl. its cache behaviour, and the `isEligible`/`canVote` projection `ElectionVotingController.php:37-44` — are cited as EVIDENCE of where the violations manifest, not as instructions to edit those files.)*
> **Invariant to preserve (the grant's real content — PO wording, 2026-08-14, verbatim):** *"For a given voter and election, voting-time entitlement must be evaluated against the organisation/tenant context belonging to that election. A matching context may permit voting; a non-matching context must not. The result must not be corrupted by cache state from another context."* The §2a four-row expected-behaviour table is part of this invariant; **tests encode the table, not `PBDIGIT-65`'s superseded sentence.** The null/absent-context case (§2a.4) is flagged for one PO line at boundary presentation.
> **Boundary presentation:** Session 3 determines the **smallest implementation boundary and technique** under strict TDD — and **must present that boundary before production implementation** (six-question reconciliation), so the design is reviewed as a boundary, not discovered in a diff.
> **Test obligation:** RED first; tests cite **Decision A (as clarified §2a)** and `PBDIGIT-65`/`69`; the acceptance scenario is the **§2a four-row table** — the P6 three-step A/B maps onto it as: step 1 → row 2 (❌, and via an election-derived correspondence check, not an accidental ambient filter) · step 2 → row 4 (recompute, never replay) · step 3 → row 1 (✅).
> **Out of scope (explicit):** `voter_count` · `has_voters` · the broader `BelongsToTenant` family · `EM-VOT-003` implementation · `EM-OPEN-021` · class B–F consumers · cache keys beyond the violating mechanism · any refactor of the 375 bypass sites. **No broader infrastructure refactoring is authorized.**
> **Verification:** Session 1 verifies independently; Session 3 does not self-certify. Baseline `SD-1` = 1,376 respected.
>
> *Template revision v2 (2026-08-13): rewritten to the Principal-Architect-recommended ruling shape — violations-scoped rather than file-prescriptive; boundary-presentation step added. v1 named the two sites as scope; that prescription is withdrawn (sites are evidence, the invariant is the scope).*
> *Template revision v3 (2026-08-14): invariant replaced with the PO's clarified wording (§2a) — v2's "same answer regardless of ambient context" was **too strong** and is withdrawn: the ruled behaviour is context-CORRESPONDENCE (matching context permits; non-matching denies; cache never transports cross-context answers). Correction noted, not silently swapped.*

Options B and C have **no draftable grant yet** — B needs a recorded architecture design decision (home + shape), C needs its own programme ruling.

## 6 · Questions for the PO *(the disposition, unbundled — Principal-Architect recommendations now on record, 2026-08-13; recommendations ≠ rulings)*

1. **Which option** — A / B / C / a composition? *(PA recommends: **A**; do not turn a confirmed Election defect into a premature infrastructure refactoring project.)*
2. **`voter_count`** — inside the A-grant or the pattern track? *(PA recommends: **OUT** — static evidence only; do not enlarge a runtime-proven defect on a static suspicion.)*
3. **`has_voters`** — with this repair or its own item? *(PA recommends: **OUT**, but recorded as an explicit **follow-up governance item** — EM-VOT-003 will make voter presence relevant at the voting boundary.)*
4. **The PKS cache observation** — *(PA: yes, record as OBSERVATION, do not promote → **DONE**: recorded at n=2, NOT promoted — `docs/pks/2026-08-13-tenant-qualified-cache-key-observation.md`; recording an observation is within engineering's remit, promotion stays Human-decides per ES-006.1.)*
5. **On A: sign the §5 template (v2)** — one signature converts it into the bounded grant.

## 7 · Authorization state *(unchanged by this package)*

```
Active implementation grants:  NONE          Decision A: CLOSED        Decision B: COMPLETED (audit delivered)
PBDIGIT-65/69:                 CONFIRMED DEFECTS · NOT AUTHORIZED      EM-VOT-002: CLOSED — not reopened
EM-VOT-003:                    ADOPTED — implementation pending its own grant
EM-OPEN-021:                   OPEN — independent, evidence complete for deciding
Sessions 1 / 3 / 4:            STOPPED
```

**Traceability:** family audit (`9cf441fc`) · audit charter (PO-authored, verbatim) · P6 (`fc86049f`) · Decision A acceptance + ADR-002 annotation (`dc105c5d`) · Decision B ruling (§5e, `23b6becd`) · `EM-VOT-003` (Manifesto §4a) · decision package §§5a–5e · `PBDIGIT-65`/`69` tickets.

---

## 2d · Q-TEN-2 RULED (2026-08-14 — performative PO ruling, verbatim)

> **"The Q-TEN-2 decision is also accepted: if no voting-session/credential context exists, voting must be denied."**

**Fail-closed, and grounded in the ruled model:** the business rule requires a credential belonging to the Election's organisation; **with no credential there is no organisation correspondence to establish.** Deriving a context from the Election was explicitly considered and **not** chosen — it could bypass the very organisational boundary the rule enforces. **The trait's platform-org fallback remains implementation evidence and acquires no authority from this ruling.**

**The complete voting-time organisation boundary rule — six clauses, now all ruled:**

1. The **Election** determines the required organisation.
2. The **voting credential** provides the organisation to compare.
3. Matching organisation → voting **may proceed**, subject to the other voting rules.
4. Non-matching organisation → voting is **denied**.
5. **No voting credential/context → voting is denied.**
6. **A cached result from another context must never be reused.**

## 2e · OPTION A ACCEPTED — explicit authorization (2026-08-14, verbatim)

> **"So Accept Option A. … Please register both as formal rulings, update the Option-A grant accordingly, and prepare the final bounded implementation authorization. Do not begin implementation. Session 3 must first perform the six-question boundary reconciliation and present the proposed implementation boundary for review."**

**Disambiguation, recorded at the PO's own instruction:** *"'Accept Option A' is now an explicit authorization, whereas your earlier 'I agree with your argument' was agreement with the reasoning, not necessarily a grant. This wording removes that ambiguity."* **The grant below is therefore GRANTED — not merely ready for signature — with a mandatory pre-implementation gate.**

## 5a · Grant — **Option A · v5 · GRANTED, gated on boundary presentation** *(supersedes §5's status line; §5's v2–v4 history stands unmodified)*

> **☑ GRANTED (2026-08-14, §2e verbatim authorization) — with ONE mandatory sequencing gate:**
>
> **GATE: Session 3's FIRST task is the six-question boundary reconciliation** — *given Q-TEN-1 + Q-TEN-2 + Decision A + Decision B, what is the smallest Election-owned implementation boundary that satisfies the invariant?* — **and it must PRESENT that boundary for review BEFORE any production edit.** The two observed sites remain **evidence, not file instructions.** RED tests follow the reviewed boundary, never precede it.
>
> **Invariant (v5 — v4 extended by the Q-TEN-2 ruling):** *"A voter may vote in an election only through a voting session/credential that belongs to the same organisation as that election. The Election is authoritative for the required organisation; the voting-session/credential supplies the organisation against which that requirement is compared. **If no voting-session/credential context exists, voting is denied.** The voting-time entitlement evaluation must not be filtered by ambient session/tenant context, and a result produced under any other context must never be reused."*
>
> **Acceptance scenarios:** the §2c credential table **plus row 5 (no credential → deny)** and row 6 (cache never replays cross-context). The P6 mapping stands (step 1 → deny via election-derived correspondence, not accidental filter · step 2 → recompute, never replay · step 3 → permit).
>
> **Out-of-scope list of §5 carries forward unchanged:** `voter_count` · `has_voters` · the wider `BelongsToTenant` family · `EM-VOT-003` · `EM-OPEN-021` · class B–F consumers · the 375 bypass sites. **No broader infrastructure refactoring is authorized.**
>
> **Verification:** Session 1 verifies independently after GREEN; Session 3 does not self-certify.
>
> *Revision v5 (2026-08-14): status ☐→☑ per §2e; invariant gains the ruled clause 5; boundary-presentation step promoted from template text to a HARD GATE. Nothing else altered; v2–v4 revision notes stand.*

## 7a · Authorization state — superseding §7's table as of 2026-08-14

```
Active implementation grants:  ONE — Option A v5 (§5a), GATED on boundary presentation
Q-TEN-1: CLOSED (§2c)          Q-TEN-2: CLOSED (§2d)       Decision A: CLOSED     Decision B: COMPLETED
PBDIGIT-65/69:                 CONFIRMED DEFECTS · repair AUTHORIZED under §5a's gate
EM-VOT-002: CLOSED             EM-VOT-003: ADOPTED — implementation pending its own grant
EM-OPEN-021:                   OPEN — independent lifecycle track; NOT mixed with this grant
Session 3:                     REOPENED FOR THE §5a GATE ONLY — boundary reconciliation + presentation;
                               production implementation begins only after the boundary is reviewed
Sessions 1 / 4:                STOPPED
```

**Session 2 implements nothing. This package records rulings and the grant; the boundary review that the gate requires is the next human touchpoint.**

---

## 8 · `start()` out-of-grant observation — governance disposition (2026-08-14; Option A stays CLOSED)

**Context:** the Option-A track is **CLOSED and independently verified** (Session 1, `a8f5afc7`: 8 tests / 27 assertions GREEN; P6 mirror B→A→B→A = TRUE×4; non-member FALSE under every ambient; cache identity `(voter, election)` with `.v2` isolation; no demonstrated regression). Session 1 also confirmed one **out-of-grant** observation — `ElectionVotingController::start()` reads `electionMemberships()->where('election_id',…)->first()` **without bypassing the ambient scope** — and correctly did **not** repair it. **Detected ≠ authorized.** This section is the commissioned disposition; nothing here reopens the verified result, modifies code, or authorizes Session 3.

**The six commissioned questions, answered from the code (`ElectionVotingController.php:96-150`, read in full by Session 2):**

| # | Question | Answer |
|---|---|---|
| 1 | What business operation does `start()` perform? | **Ballot-opening**: resolve election (bypassed, ✅) → **evaluate voting-time entitlement** (`!membership ∥ role≠voter ∥ status=removed` → deny *"You are not registered as a voter for this election"*) → `has_voted` check → lifecycle `canVote()` → IP check → **create/reuse the VoterSlug credential** → redirect into the flow |
| 2 | Is it evaluating the same (voter, election) entitlement fact? | **YES — the flagged lookup feeds an inline entitlement evaluation with identical predicate content** to the repaired sites (existence ∧ role=voter ∧ status≠removed), producing a voter-facing denial |
| 3 | Or a separate credential/ballot-opening responsibility? | It *also* performs credential issuance — **downstream of the gate**. Decisive nuance: **at `start()` no credential exists yet (this operation creates it)**, so the credential-correspondence layer (Q-TEN-1/Q-TEN-2's deny-duty) **cannot** cover this point — the entitlement gate is **load-bearing pre-credential** |
| 4 | Does the accepted Decision-A rule apply? | **YES** — the Decision-A test (*"does this operation determine whether this person is entitled to vote in THIS election at voting time?"*) is answered affirmatively by the code itself; the ruled ENT invariance applies to every entitlement evaluation wherever it occurs |
| 5 | Classification | **A — another manifestation of the already-established entitlement violation** (same fact · same mechanism · same relation · same missing bypass · same false-denial family — the third site of the 65-family). **Scope note:** no cache at this site, so the 69-family (replay) is **not** implicated here. **Evidence strength stated exactly:** static (code read); runtime reproduction **NOT performed** — the same strength at which audit site 2 was carried before its confirmation |
| 6 | Smallest next governance action | **One PO line choosing between:** **(i)** a **new bounded grant "Option A-2"** — extend the already-ruled invariant's enforcement to `start()`'s entitlement gate (no new business rule or architecture decision is needed: Decision A, Q-TEN-1, Q-TEN-2 and the v5 invariant already govern; RED mirrors TE2's method for `start()`; Session 1 verifies) — **or (ii)** a **pre-grant bounded runtime verification** by Session 1 first: one measurement — *valid membership in election E of org A; ambient context org B; POST `start()`; ticket-family prediction: redirect with "not registered" denial* — then (i) on confirmation |

**What this disposition does NOT do:** repair anything · authorize Session 3 (the Option-A grant is CONSUMED — similarity of code confers no authorization) · reopen the verified Option-A result · touch `EM-OPEN-021` (separate, still the open Track-B business ruling) · promote the PKS observation · extend to the other 36 unaudited class-B–F consumers (the family audit's boundaries stand).

**Register effect:** `start()` entitlement gate = **classification-A manifestation, static evidence, NOT AUTHORIZED** — awaiting the PO's §8.6 choice.
