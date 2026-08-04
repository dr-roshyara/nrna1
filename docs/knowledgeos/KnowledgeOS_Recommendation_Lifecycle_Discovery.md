# Recommendation Lifecycle Discovery *(EP-02 · living instrument · Run 1 = baseline)*

| | |
|---|---|
| **Kind** | ⭐ **DOMAIN DISCOVERY BY OBSERVATION** — *what is Recommendation inside the engineering domain?* ⛔ ***Evidence only · no architecture unless evidence demands it · behavior before classes · states recorded only when engineering actually performs them.*** |
| **Status** | ⚠️ **LIVING — Run 1 (2026-08-04) is the BASELINE; runs accumulate as usage accumulates** |
| **Commission** | EP-02 Recommendation Lifecycle Discovery — *"discover the lifecycle, not the engine; the engine exists"* |
| **Evidence base, declared** | ⛔ **ONE engine run · 10 recommendations issued · ZERO developer decisions · ZERO lifecycle transitions.** *Run 1's honest job: record what issuance alone shows, open the instrument, refuse everything else* |

---

## 1. Behavioral Observation Report *(Run 1 — what has ACTUALLY happened)*

| Observed behavior | Evidence | Count |
|---|---|---|
| **Issuance** | 10 records in `recommendations.jsonl` | 10 |
| **Dedup identity held** *(one open item per rule+subject; stable IDs)* | re-run produced 0 new *(“all open items already issued”)* | 1 re-run |
| **Honest silence** *(rules refusing to fire without grounds)* | R1 silent on Committee(16) · R2 silent (no untested change) · R3 silent (CBO flat) · R4 silent (insufficient history) | 4 behaviors |
| **False-positive candidates surfaced by design** | R5 flagged 2 ServiceProviders *(flat bar vs stereotype bands — known approximation)* | 2 |
| ⛔ **Everything else** — viewing · deciding · deferring · satisfying · expiring · superseding · reopening | **NOT OBSERVED — no developer has interacted with any recommendation yet** | 0 |

**Anomalies:** none yet. **Unanswered questions:** all of §3–§5's.

## 2. Candidate Ubiquitous Language *(only terms in actual use)*

| Term | Grade |
|---|---|
| recommendation · issued · open · rule · subject · evidence refs | **OBSERVED** *(exist in records and console output)* |
| decision (ACCEPTED / IGNORED / DEFERRED) · rationale · reason code | **OBSERVED as vocabulary, UNEXERCISED as behavior** *(the CLI exists; no record yet)* |
| viewed · satisfied · expired · superseded · reopened · merged | ⛔ **HYPOTHESIZED — not introduced.** *They enter this table only when engineering performs them* |
| intervention | **EMERGING** *(review vocabulary; no record uses it)* |

## 3. Recommendation Domain Discovery Log *(append-only; entries earn their place)*

| # | Discovery | Evidence | Freq | Counterexamples | Confidence | Promotion |
|---|---|---|---|---|---|---|
| RD-1 | **A recommendation is immutable once issued; responses live in SEPARATE records** | the contract chain by construction; decisions.jsonl is a different file | structural | none | HIGH *(for the implementation; domain meaning unproven)* | none — observe |
| RD-2 | **Identity = (rule, subject)** — duplicates cannot exist while open | dedup behavior, 1 re-run | 1 | ⚠️ *untested: does the SAME (rule,subject) deserve re-issuance after the subject changes? (designed, not yet exercised)* | MEDIUM | none — observe |
| RD-3 | **Rules produce structurally honest silence** *(absence of grounds → absence of advice)* | 4 silence behaviors, Run 1 | 1 run | none | MEDIUM | none |
| RD-4 *(hypothesis staged, review 2026-08-04)* | **identity may need THREE layers: RecommendationDefinition / Instance / Revision** — *(rule,subject) dedup cannot answer "did the rule change?" · "re-issued after conditions changed?" · "v1 ignored but v2 accepted?"* | none yet — the questions have never been ASKED by real usage | 0 | — | HYPOTHESIZED | ⛔ **think, never implement, until a real question requires it** |
| RD-5 *(protocol note, review 2026-08-04)* | **recommendations accumulate AGE — and age is already computable from existing timestamps** *(issued ts vs decision ts; no new fields)* | structural | — | — | OBSERVED-computable | **Run 2 computes time-to-decision from existing records; future: mean-time-to-decision · recommendation half-life** |
| RD-6 *(Run 2, 2026-08-04)* | ⭐ **ACTOR PROVENANCE GAP: the decision record's `actor` stamps the git user, but the decider was the AI at the user's direction** — *when AI participates in decisions, the actor model needs to distinguish who-executed from who-authorized; today the honest attribution lives only in the rationale comment* | the first real decision record | 1 | — | **OBSERVED** | ⛔ observe — a second AI-mediated decision makes this a real modeling question |
| *(next entries as decisions accumulate)* | | | | | | |

## Run 2 (2026-08-04) — fired by the first decision, per protocol

| What changed | Evidence |
|---|---|
| ⭐ **DECIDING observed for the first time** — `ACCEPTED` with reason code + comment | `decisions.jsonl`: REC-eaf245474a → ACCEPTED (IMMEDIATE_VALUE) |
| **UL upgrades:** decision · ACCEPTED · rationale · reason code → **OBSERVED-exercised** *(were vocabulary-only)*. IGNORED · DEFERRED remain unexercised | the records |
| ⭐ **RD-5 validated on first use: time-to-decision = 6.5 hours, computed from EXISTING timestamps, zero new fields** | issued 00:01 → decided 06:30 |
| **The two-record separation held in practice** — decision and rationale written as distinct records, exactly as contracted | two JSONL lines, two types |
| ⛔ **Impact: STILL insufficient** — the first learning event is **3/5 complete** *(recommendation ✓ decision ✓ rationale ✓ · commit ✗ outcome ✗)*. The celebrated milestone record needs a real code change and a metric delta after N commits | — |
| **DDD classification: unchanged (none yet)** — one decision cannot reclassify | — |

## 4. DDD Assessment *(answered only from evidence)*

> **Is Recommendation an Application Service · Domain Service · Aggregate · Domain Capability · Process Manager?**
>
> # ⛔ **NONE YET — insufficient evidence, said explicitly.**
>
> The only admissible observation: the implementation shape *(immutable issued record + separate decision/rationale records + a future outcome joining both)* **resembles an event-with-a-process-around-it more than an aggregate — but one day of issuance with zero responses cannot ground a classification.** The discriminator stands as planned: *lifecycle, confidence, versioning, effectiveness emerging → domain forming; mere transformation persisting → service it stays.* **The evidence that decides: decisions, rationales, and outcomes accumulating — none exist.**

## 5. KnowledgeOS Impact *(the measure: engineering changed, never recommendations issued)*

| Measure | Value |
|---|---|
| accepted / ignored / implementation rate / defects prevented / refactoring avoided / confidence change | ⛔ **INSUFFICIENT EVIDENCE — zero decisions exist.** *10 issued is an activity count and counts for nothing by the protected sentence* |
| **What Run 2 needs** | ≥1 decision *(the first complete learning event)* — then per-rule acceptance, rationale-code distributions, and the first outcome joins |

## Observation protocol for future runs

Re-run this discovery when: **the first decision lands** *(Run 2 — immediate)* · every ~10 decisions thereafter · any anomaly *(reopen? merge request? expiry need?)* appears in practice. Each run appends to §1/§3, upgrades/downgrades §2 grades, and re-answers §4/§5 **only from the accumulated records.** ⛔ Constraints standing: no ML · no ranking · no confidence scoring · no adaptive behavior — *unless deterministic v1 demonstrably fails observed needs.*

---

*Traceability: EP-02 commission 2026-08-04 · Run 1 baseline over one engine run (10 issued · 0 decided) · UL graded observed/unexercised/hypothesized · discovery log opened (RD-1..3, all held at observe) · DDD classification refused for insufficient evidence · impact = insufficient evidence, stated explicitly · the instrument now waits on the same thing everything waits on: developer decisions.*

> **⛔ Submitted as evidence instrument. Architecture emerges from future runs or not at all.**
