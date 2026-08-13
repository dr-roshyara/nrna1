# Manifesto canonicalization — validation report and Session-3 handover

**Type:** Governance validation + handover · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2)
**Subject:** [`docs/publicdigit/business_rules/ELECTION_MANIFESTO.md`](../business_rules/ELECTION_MANIFESTO.md)
**⛔ No production code, test, fixture, schema or migration change. `ADR-002` not amended. `ElectionConstitution` not modified. `BR-1.12` not resolved. Full Membership not resolved. No suspension question resolved. Session 1's Master Matrix not read, classified, consumed or modified.**

---

## 1 · Placement and naming — derived, with one judgement declared

| | |
|---|---|
| **Root** | **Derived, not chosen:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → **`docs/publicdigit`**, exit **0** *(ruled; not `PENDING`)* |
| **Sub-root** | **`business_rules/`** — the existing sub-root for business rules in that root. **Not `reviews/`** *(evidence)*, **not `adr/`** *(architectural decisions)*, **not `architecture/`* |
| **Competing Manifesto?** | **None.** A filename search for *manifesto* across `docs/`, `architecture/`, `engineering/` and `architecture_legacy/` returns nothing. **No second Manifesto was created** |
| ⚠️ **Naming — a declared judgement** | The sole precedent in `business_rules/` is **timestamped** (`20260806_0818_how_many_organisation.md`). I used a **stable** name, `ELECTION_MANIFESTO.md`, because **stable rule IDs must resolve to a stable path** — a canonical artifact referenced as `EM-EO-001` cannot live behind a moving filename, and the precedent is a dated *decision record* rather than a standing authority. **This is a convention judgement the Product Owner may override; the file can be renamed with no loss** |
| **Knowledge card** | **Not added.** The template states it applies to *"every governed document **under `docs/knowledge/`**"*; this artifact is under `docs/publicdigit/`, and no artifact in that root carries one. **Adding one unasked would invent a convention** — flagged rather than assumed |

## 2 · Governance validation — the pre-commit checklist

| # | Check | Result |
|---|---|---|
| 1 | **Every adopted rule has an explicit prior authority** | ✅ §8 traceability table — 26 rules, each with prior authority, adoption date, scope, status |
| 2 | **No new business rule invented** | ✅ Every rule traces to `D-ENT-1`, an `A`-series adopted rule, a hierarchy clause, the `Q-B1` closure, or the sequencing decision |
| 3 | **No unresolved question accidentally promoted** | ✅ 17 open items are in §9, **explicitly marked as not business rules**. `BR-1.12` appears **only** as `EM-OPEN-001` |
| 4 | **No Full Membership rule leaked into Election-Only** | ✅ `EM-FM-001`…`006` are in a **separate section**, marked **DEFERRED**, with `EM-SEQ-002` forbidding inference |
| 5 | **No implementation detail became a business rule** | ✅ No table, column, class, service or controller is named in any rule. **`EM-VOC-002` was deliberately phrased without naming tables**, to state the business meaning without importing schema |
| 6 | **No Officer Guide statement promoted without authority** | ✅ Excluded in §10; the `D` assessment stands; retained as evidence only |
| 7 | **Every rule has a stable ID** | ✅ `EM-ENT-`, `EM-EO-`, `EM-GOV-`, `EM-VOC-`, `EM-SEQ-`, `EM-FM-`, `EM-OPEN-`. **No collision with existing identifiers** (`PBDIGIT-`, `ADR-`, `ES-`, `EM-` was unused) |
| 8 | **Obsolete wording not silently rewritten** | ✅ Where the Product Owner corrected wording, the **corrected** form is used and the correction is noted *(`EM-ENT-003`)* |
| 9 | **Historical source remains traceable** | ✅ §8 and the closing note point at `PBDIGIT-68` and the review artifacts as the record of **how** each decision was reached |
| 10 | **Rules with an existing canonical home not duplicated** | ✅ §7 **references** `ADR-T11`, `ElectionConstitution`, `ADR-002`, `ADR-001`/`003`, `VoterSourceStrategy` rather than copying them — **duplication is the failure mode this artifact exists to end** |

## 3 · Migrated — 27 adopted rules *(26 initially + `EM-VOT-001` from the completeness sweep, §5a)*

**Entitlement (7):** `EM-ENT-001`…`007` · **Election-Only (4):** `EM-EO-001`…`004` · **Governance (3):** `EM-GOV-001`…`003` · **Vocabulary (3):** `EM-VOC-001`…`003` · **Sequencing (2):** `EM-SEQ-001`, `002` · **Full Membership, deferred (6):** `EM-FM-001`…`006`.

**One annotation carried, not resolved:** `EM-ENT-003`'s *"unless a defined election-level removal rule terminates it"* **forward-references an unratified rule** (`EM-OPEN-004`). It is recorded as ADOPTED because the Product Owner adopted that wording — **with the incompleteness marked rather than smoothed over.**

## 4 · Deliberately NOT migrated

Production behaviour · test names, comments and fixtures · Officer Guide statements · model docblocks and `architecture_legacy/` documents · schema and column semantics · proposed decisions, recommendations and options · unresolved questions · `FM-1`…`FM-15` as Election-Only rules · Session 1's findings · **and any Session 2 *finding* that was not an adopted decision.**

> **The most consequential exclusion:** **production currently yields `active` on admission — and that was NOT migrated.** It is the only evidence for one side of `EM-OPEN-001`, so migrating it would have **decided `BR-1.12` by default.** That is precisely the outcome the canonicalization had to avoid.

## 5 · One thing I did not do, and will not

> **`EM-OPEN-017`: this Manifesto's own ratification is not recorded.**
>
> It was created under an instruction to canonicalize. **Whether this artifact, at this location and under this name, is *the* ratified canonical home is `D-MANIFEST` — a governance decision that has not been separately taken.** The Manifesto says so in its own §9.
>
> **I have not declared it authoritative by its own authority.** It is offered as the canonical home.

---

## 5a · ⚠️ Completeness sweep — the first version of the Manifesto was INCOMPLETE

**Prompted by the Product Owner: *"check the jira tickets in backlog and investigate if it is complete."* I did, and it was not.**

**Swept:** every ticket in `docs/publicdigit/backlog/` for explicit business-rule statements, *"stated by the Product Owner"*, *"(PO decision)"* and approval markers.

| Result | Detail |
|---|---|
| 🔴 **Gap found and closed** | **`PBDIGIT-64:9`** carries an explicit adopted rule — *"**Without a candidate an election must not go into the next phase**"* — **stated by the Product Owner on 2026-08-08**, and labelled *"Business rule"* in the ticket itself. **It was missing.** Migrated as **`EM-VOT-001`** |
| ⚠️ **Candidate found, NOT migrated** | **`PBDIGIT-50`** timezone display. The backlog index annotates it *"(PO decision)"*, **but the ticket's own status is `OPEN — not authorised`** with the fallback undecided. **Migrating it would have promoted a partially-decided item.** Recorded as **`EM-OPEN-018`** |
| ✅ **Correctly out of scope** | `PBDIGIT-30`'s approved rules `B1`, `B2`, `B3`, `B10` — **Organisation-scope, not Election**, and they **already have a canonical home in this same folder** (`20260806_0818_how_many_organisation.md`). *Their presence there independently corroborates the placement choice* |

### 5a.1 Second sweep — `ElectionConstitution`, at the Product Owner's prompting

**The Product Owner then pointed at `app/Domain/Election/Constitution/ElectionConstitution.php`. That produced two results.**

**1 · A structural improvement.** My §7 referenced the Constitution with a single line — *"lifecycle states, transitions, allowed roles and preconditions"*. **That was too coarse: it left the Manifesto reading as though the Constitution held only mechanics.** Its docblock in fact states **six business rules**, now **enumerated** in §7:

* all transitions defined there **and nowhere else**;
* **only committees (chief, deputy) may administer elections**;
* **only the chief may open voting or publish results**;
* the approval workflow `draft → submitted → approved/rejected → setup`;
* **capacity-based approval** — auto-approval below a voter threshold, manual review above it;
* every action carries preconditions that must be verified.

**They are enumerated, NOT restated.** The Constitution remains their canonical home — **an index is not duplication, and a reader who cannot see that a rule exists cannot know to look for it.**

**2 · 🔴 A disputed business rule — `EM-OPEN-019`.**

| Source | Threshold |
|---|---|
| **Implementation** — `ConstitutionalTransitionGuard` *("Free plan (≤40 voters) always eligible", "≤40 → auto-approved")* | **≤ 40** |
| **The Constitution's own docblock** — *"free plan (≤40 voters) auto-approves"* | **≤ 40** |
| **The Product Owner, 2026-08-09** *(runtime verification)* — *"election with voters under 30 can be accepted automatically. no approve necessary from administrator side."* | **≤ 30** |

> **Two figures, two kinds of source. I have NOT resolved it.**
>
> **I cannot choose between a Product Owner statement and the implementation** — and the statement may well have been **operational** (unblocking a 5-voter test election) rather than a rule declaration. **Either reading is plausible, which is exactly why it is a question and not a finding.**
>
> **This is also a case where the Manifesto's standing clause bites in my own favour:** the implementation is not the source of the rule, so *"the code says 40"* does not settle it either.

### Why the first version was incomplete — the honest cause

**I canonicalized the thread I had been working in.** The Manifesto drew from `PBDIGIT-68` and the Session-2 governance artifacts — the entitlement and admission decisions — **and I did not sweep the wider backlog before declaring the artifact canonical.** Its title claims *"canonical Election business rules"*, which **overstated its coverage on first commit.**

**The Manifesto now states its coverage limit explicitly (§9a) rather than implying completeness**, and names two further candidates that exist only in conversation and narrative reviews — the voting-period voter redirect, and auto-acceptance of elections below a voter threshold. **Neither is migrated on my reading of a conversation; both need Product Owner confirmation.**

> **A canonical artifact that silently overstates its coverage is worse than one that states its limit.** The limit is now stated.

## 5b · Defect report — the six ARB questions, answered precisely (2026-08-13)

**The ARB accepted the verification conclusion but, correctly, did not accept the artifact merely because the verification passed. The six questions, answered with git evidence:**

| # | Question | Answer |
|---|---|---|
| **1** | **What was the defect?** | **Self-declaration of unratified authority.** The Manifesto asserted canonical status through its own text while its own governance section (`EM-OPEN-017`) recorded ratification as OPEN and the ARB had ruled *"do not ratify yet"* (2026-08-12). **Three instances of the same defect class, caught in two rounds:** the header, the authority-table row *(both caught by my verification)*, and the separation clause's self-definition *(caught by the Product Owner)* |
| **2** | **What did it originally claim?** | Header: *"**Status: ACTIVE — canonical home** for adopted Election business rules"*. Authority table: *"**canonical Election business rules**"*. Separation clause: *"Manifesto **= adopted business rules and invariants**"* — a definition that conferred the authority the document did not yet hold |
| **3** | **What evidence contradicted it?** | **The document's own `EM-OPEN-017`** (*"is this artifact… the ratified canonical home?" — open*) and **the recorded ARB disposition of 2026-08-12**: *"Manifesto as canonical authority — ⏸ do not ratify yet."* **The contradiction was internal — the artifact disagreed with itself** |
| **4** | **What was changed?** | Header → *"BUSINESS-RULE CATALOGUE — **PROPOSED** canonical home; **NOT YET RATIFIED** (`EM-OPEN-017`)"* · table row → *"catalogue of adopted Election business rules (**proposed** canonical home — unratified)"* · separation clause → *"the governed home … **when ratified by ARB**"*. **Each correction is noted inline, not silently swapped** |
| **5** | **Did the correction change any previously accepted business decision — or only documentation?** | **Documentation only — verified against git, not asserted.** Commits `a58f9201` and `aaf21a90` touched only `docs/` and `.claude/`; **a diff-grep for rule rows (`EM-*`) in the Manifesto shows ZERO rule IDs, rule texts, statuses, adoption dates or traceability rows changed.** The **rules'** adoption was never in question — their authority derives from the PO decisions traced in §8, **not** from the artifact's own status. What was wrong was only the artifact's claim about **itself** |
| **6** | **Was any implementation affected?** | **No.** No production code, test, schema or migration in either commit. And **no downstream consumer had relied on the over-claim**: the `EM-VOT-002` grant cites the rule ID and the SD-14 package — a rule whose adoption is independent of the artifact's ratification — and Session 3's test-citation requirement (`@see EM-VOT-002`) is likewise unaffected |

**Disposition per the ARB's rule — *"if documentation correction only, accept and move on"*: it is documentation only. Accepted; moving on.**

**One honest note on detection:** my verification caught two of the three instances; **the third was caught by the Product Owner, not me.** The defect class is now named — *an artifact must not confer its own authority through header, table, or definition* — which is what makes the next occurrence findable.

## 6 · Session-3 handover

### 6.1 What changed for Session 3

| Before | Now |
|---|---|
| Business rules had to be read from **review documents** | **Read them from the Manifesto.** Review documents are evidence and history |
| Tests had no stable rule identifiers | **Every rule has a stable ID** — cite `@see EM-EO-003` etc. |
| `D-ENT-1` / proposal v2 were being considered as an **interim** source | ❌ **Do not.** That was declined by the Product Owner, and it would recreate the scattering the Manifesto ends |

### 6.2 Rules available for Slice 1

**Election-Only + both-modes rules are available now:** `EM-EO-001`…`004` · `EM-ENT-001`…`002`, `004`…`007` · `EM-GOV-001` · `EM-VOC-001`…`003` · `EM-SEQ-001`, `002`.

**Conditionally available:** `EM-ENT-003` *(termination clause blocked by `EM-OPEN-004`)* · `EM-GOV-002`/`003` *(actor count blocked by `EM-OPEN-002`)*.

**Not available:** `EM-FM-001`…`006` — **DEFERRED.**

### 6.3 🔴 Hard stops

1. **`EM-OPEN-001` (`BR-1.12`) blocks the ADMISSION slice.** **A test asserting that admission yields `active` would DECIDE that question, not verify it** — production is the only evidence for that option. **STOP if the slice reaches it.**
2. **If a test cannot be mapped to a Manifesto rule: STOP.** Do not invent the rule. **Do not modify the Manifesto.** Return the missing-authority question to Session 2 / the Product Owner.
3. **Never add or alter a Manifesto rule to make a test pass.** A missing rule is a governance question, not a gap to fill.
4. **Cross-mode stop:** if a change would simultaneously define Full Membership semantics — **STOP and report the cross-mode impact.** *(Admission is mode-parameterised, so this should not trigger for Slice 1; it will trigger on suspension or removal work.)*

### 6.4 Guardrails carried forward

Do not restore the deliberately dropped organisation foreign key · do not create `Member` rows · do not activate the Full Membership admission policy · do not activate the dormant organisation-membership eligibility query *(it would deny every voter)* · do not vacate the current non-exercisability representation before a replacement enforces · **`ADR-T11`** — nothing may identify or mutate a cast ballot · do not cite `ElectionUser` as prior art *(a dead legacy model built on a retired flag; now parseable, which makes the mistake easier)*.

### 6.5 Session boundaries

**Session 2** establishes and canonicalizes **what is authoritative** · **Session 3** implements **what is authoritative** · **Session 1** independently verifies **whether it is verified**. **No session performs another's job.** The five conformance questions `B-1`…`B-5` remain **engineering** questions, **not** business decisions, and **must not be auto-converted into tickets**.

---

## 7 · Boundary

**No production code · no test · no fixture · no schema · no migration change. `ADR-002` not amended. `ElectionConstitution` not modified. No second Manifesto created. No knowledge card invented. `BR-1.12`, `BR-1.13`, `Q3`, `Q-E1`, `Q-E2`, `BR-1.1`/`1.2`, `BR-1.8` and `FM-1`…`FM-15` not resolved. Officer Guide not promoted. Session 1's Master Matrix not read, classified, consumed or modified.**

**Next action belongs to the Product Owner / ARB: `EM-OPEN-001` (`BR-1.12`) and `EM-OPEN-017` (`D-MANIFEST` — ratify this artifact).**
