# Constitutional Evidence Commission — the Voter Activity Trail

**Date:** 2026-08-01 · **Role:** Senior Principal Architect · **Commission:** is the evidence **sufficient for the ARB to rule** on whether the observed artifact violates the anonymity invariant? Evidence assembly and classification only.
**Repository Integrity Gate:** ✅ PASSED. Evidence read from the working tree at HEAD.

> ## ⚠️ CORRECTION TO THE PREVIOUS COMMISSION — I OVER-REACHED
>
> The prior report stated the artifact *"fails admissibility against a constitutional invariant."* **That conclusion was not mine to reach, and it rested on a summary rather than the authority.** I cited ADR-T11 from the project charter's paraphrase **without reading ADR-T11's own normative text.** Having now read it, the scope question is **materially narrower than I implied** — see §1. **The finding stands as evidence; the conclusion is withdrawn.**

---

## 1. Constitutional authorities — source language, not paraphrase

### ADR-T11 (the decisive authority)

| Field | Exact content |
|---|---|
| **Normative statement** | *"**Anonymity invariant (Q7)** — no voter↔vote linkage in **any aggregate, event payload, or projection**; hashes only"* |
| **Status** | *"constitutional, build-breaking"* |
| **Enforcement** | *"fitness test asserts **no linkage/`user_id` reconstruction**"* |
| **Scope, read literally** | **three enumerated artifact kinds: aggregate · event payload · projection** |
| **Applicable business concept** | voter↔vote linkage |

**The scope question in one line: a log file is not an aggregate, not an event payload, and not obviously a projection.** Whether it falls inside *"projection"*, or inside the enforcement column's broader *"`user_id` reconstruction"*, **is an interpretive question about the ADR's reach** — not a fact I can read off the artifact.

### Supporting authorities

| Authority | Relevant content | Bearing |
|---|---|---|
| **ADR-T13** | *"current integrity = hash + **audit-trail**, weaker than voter-verifiable crypto proof"* — a recorded **known limitation** | ⚠️ **Cuts against my earlier reading:** the ADR authors treated the **audit trail** as part of the integrity story, and recorded its weakness as a *known limitation* rather than a violation |
| **Project charter** (`CLAUDE.md`) | *"votes table: NO user_id column"* · *"No vote coercion possible"* · and of this audit design: *"Candidate selections **(anonymous)**"* | Charter language is **decisive about the votes table**; its *"(anonymous)"* claim about the audit log is an **assertion whose accuracy is now in question** |
| **Constitutional Policy 2** | the Retention Invariant | Governs *how long* protected evidence lives — **silent on admissibility** |
| **AT-Q7-001** (executable) | scans **`app/Contexts/*` + the messaging surface** for forbidden linkage tokens | ⚠️ **Scope evidence:** the enforcement mechanism examines **PHP source in the greenfield contexts only** — never `app/Helpers`, never `storage/logs` |
| **`VoteAnonymityTest`** | asserts the vote hash uses `code_id` not `user_id`; asserts `demo_votes` has **no `user_id` column** | Confirms the invariant is enforced **at the database/hash layer** |

**Composite scope reading, offered as evidence rather than as a ruling:** every enforcement mechanism in the repository targets **aggregates, payloads, contexts and tables**. **Nothing enforces anonymity over log files.** That is consistent with two opposite interpretations — the scope genuinely excludes logs, **or** the scope includes them and enforcement has a gap. **Both readings fit the evidence; only the ARB can choose.**

## 2. Evidence inventory

| Field | Observation |
|---|---|
| **Producer** | `app/Helpers/ElectionAudit.php` — `voter_log()`, `log_vote_submission()` |
| **Storage** | `storage/logs/organisation_{id}/{election_name}/{user_id}_{user_name}.log` |
| **Business purpose (as documented)** | *"per-person voter activity logging … for easy auditing and compliance verification"* |
| **Current usage** | **zero readers** — no controller, service, command or test consumes it |
| **Execution path** | **NONE from application code.** `voter_log(` appears only inside its own helper file; `log_vote_submission()` has no caller anywhere in `app/` or `routes/` |
| **Autoload status** | ⚠️ **registered in `composer.json`** (`app/Helpers/ElectionAudit.php`) — loaded on every request |
| **Operational status** | **dormant capability + historical residue** |

### The three categories, deliberately not merged

| Category | Content | Extent |
|---|---|---|
| **Active runtime behaviour** | **NONE** | no execution path exists |
| **Dormant capability** | `voter_log()` / `log_vote_submission()` — autoloaded, callable, would write `user_id` beside `candidate_id` | 2 functions, 0 call sites |
| **Historical residue** | **1 file**, `organisation_null/demo_election/10_nab_roshyara.log`, dated **2026-02-19**, 3 lines, demo tenant | 1 file · 1 subject · 1 election |

## 3. Evidence-to-constitution mapping

| Observed fact | ADR-T11 mapping | Reasoning |
|---|---|---|
| `demo_votes` / `votes` have **no `user_id`** | ✅ **explicitly permitted / satisfied** | the charter's decisive claim, test-enforced |
| No linkage in any **aggregate** | ✅ satisfied | AT-Q7-001 passes across all contexts |
| No linkage in any **event payload** | ✅ satisfied | same guard, messaging surface included |
| A **log file** records `user_id` + `candidate_id` + `vote_id` together | ⚠️ **AMBIGUOUS** | turns on whether a log file is a *"projection"*, or whether *"`user_id` reconstruction"* reaches beyond the three enumerated kinds. **Not addressed explicitly by any authority** |
| The **audit trail** is part of the integrity model | ⚠️ **ambiguous, and cuts both ways** | ADR-T13 names the audit trail a *known limitation* — acknowledging weakness without declaring violation |
| The residue lives in the **demo tenant** | ⚠️ **not addressed** | no authority states whether demo data is constitutionally in scope |
| A **dormant** capability could write such a record | ⚠️ **not addressed** | every authority speaks of what artifacts *contain*, never of latent capability |

**Four of seven facts are ambiguous or unaddressed. No fact maps to "explicitly prohibited."**

## 4. Admissibility assessment

> ### **Admissibility is UNDECIDABLE from the available evidence, and depends on an ARB interpretation of ADR-T11's scope.**

**Stopping here, as the commission requires.** The single interpretive question, stated as narrowly as the evidence permits:

> **Does ADR-T11's *"any aggregate, event payload, or projection"* — with its enforcement clause *"no `user_id` reconstruction"* — extend to an application log file?**

- **If YES:** the artifact is inadmissible, and the enforcement mechanism (AT-Q7-001) has a **coverage gap** it was never designed to close.
- **If NO:** the artifact is admissible-but-unowned, and the earlier Domain Audit Boundary finding stands unchanged — a concept with no owner.

**Both outcomes are internally consistent with every artifact in the repository.** That is precisely why this is a ruling and not an observation.

## 5. Runtime risk classification — three severities, evaluated independently

| Category | Severity | Reasoning |
|---|---|---|
| **Active runtime behaviour** | **NONE** | there is no execution path; nothing is being written today |
| **Dormant capability** | **LOW–MODERATE, and contingent** | autoloaded and callable, so a single future call site would activate it. But activation requires a deliberate code change, and the change would be visible in review. **Its severity depends entirely on the §4 ruling** — if the scope excludes logs, this is not a risk at all |
| **Historical residue** | **LOW, bounded, and NOT self-mitigating** | 1 file · demo tenant · 5 months old. Bounded — but it **exists now** and will persist until an authority decides its disposition. **No mechanism will remove it**, because artifact B has no retention lifecycle at all |

**The three must not be collapsed into one severity.** Collapsing them would produce either alarm (treating residue as an active leak) or complacency (treating a live capability as merely historical).

## 6. Architectural consequences of each possible ruling

### If ruled **ADMISSIBLE**

| Consequence | Detail |
|---|---|
| Ownership | the Domain Audit Boundary finding stands: **B remains an unowned concept** needing an owner |
| Lifecycle | B needs a retention lifecycle; Policy 2's applicability becomes a live question |
| Enforcement | AT-Q7-001's scope is **confirmed correct** as written |
| WP-7 | **unchanged** — B still sits outside its scope |
| Ubiquitous language | the charter's *"Candidate selections (anonymous)"* annotation **must be corrected**, because the selections are not anonymous in that file — an accuracy defect regardless of the ruling |

### If ruled **INADMISSIBLE**

| Consequence | Detail |
|---|---|
| Ownership | **the ownership question dissolves** — nothing is owned, and the concept is retired rather than assigned |
| Enforcement | AT-Q7-001 has a **coverage gap**: it scans `app/Contexts` and the messaging surface, never `app/Helpers` or `storage/**`. Closing it becomes an executable-architecture question |
| Residue | disposition becomes a **constitutional** matter, not housekeeping |
| Dormant capability | becomes a **defect** rather than a latent risk |
| WP-7 | **still unchanged** — this was never a retention question |

**Note what is common to both branches:** WP-7 is unaffected either way, and the charter's *"(anonymous)"* annotation is inaccurate either way. **Those two consequences require no ruling.**

## 7. ARB decision questions

| # | Question | Evidence | Governing authority | Unresolved ambiguity | Decision required |
|---|---|---|---|---|---|
| **C-1** | Does ADR-T11's scope (*aggregate · event payload · projection*; *no `user_id` reconstruction*) extend to an **application log file**? | §1, §3 | **ADR-T11** | *"projection"* and *"reconstruction"* are undefined with respect to logs | **Interpretation — the decisive question** |
| **C-2** | Is the **demo tenant** (`organisation_null`) constitutionally in scope? | 1 residue file, demo org | none found | no authority addresses demo data | Interpretation |
| **C-3** | Is a **dormant** capability to write a forbidden record itself a defect? | 2 functions, 0 call sites, autoloaded | none found | authorities speak of artifacts, not capabilities | Interpretation |
| **C-4** | What is the **disposition of the residue** file? | 1 file, 2026-02-19 | Policy 2 (if applicable) | whether an unlawfully-formed record is protected evidence | Decision |
| **C-5** | If C-1 is *yes*, should **AT-Q7-001's scan scope** extend beyond `app/Contexts`? | the guard scans contexts + messaging only | ADR-T11's enforcement clause | scope was never stated to include helpers or storage | Decision |
| **C-6** | Should the charter's *"Candidate selections (anonymous)"* be corrected? | the file shows selections keyed to a named voter | project charter | none — the annotation is inaccurate on the evidence | **Decision, required under either ruling** |

**None is answered here.** C-1 governs C-2 through C-5; **C-6 stands independently.**

## 8. Recommendation

**On the process, not the substance:**

1. **Rule C-1 first.** Every other question is downstream of it, and answering them in any other order risks deciding consequences before the premise.
2. **Treat the three runtime categories separately** in whatever ruling follows. They differ in severity by an order of magnitude and one of them (active behaviour) is empty.
3. **Correct the charter annotation regardless** (C-6). *"Candidate selections (anonymous)"* is inaccurate on the evidence under either ruling, and leaving it invites a future reader to trust a guarantee the artifact does not provide.
4. **Do not let this touch WP-7.** It is unaffected under both branches; folding it in would recreate exactly the scope creep the last three commissions prevented.
5. **No file has been modified, moved or deleted**, and none should be until C-4 is decided — deleting a record that may be constitutional evidence is not housekeeping.

**On my own error, recorded because the pattern matters:** I asserted a constitutional conclusion from the **charter's paraphrase** of ADR-T11 rather than from ADR-T11. The authority's actual text enumerates three artifact kinds and a log file is arguably none of them. **The lesson is narrower than "be careful": when a conclusion turns on an authority's scope, read the authority, not a document that cites it.**

---

**Traceability:** ADR-T11 (`docs/adr/ADR-T-LOG-Tactical-Implementation.md:18`) · ADR-T13 (audit trail as recorded known limitation) · Constitutional Policy 2 · project charter `CLAUDE.md` · `AT-Q7-001` (`tests/Architecture/GreenfieldCoreArchitectureTest.php:69`) · `tests/Feature/Demo/VoteAnonymityTest.php` · previous commission `2026-08-01-voter-activity-trail-ownership-discovery.md` (whose conclusion this report **withdraws**). **Evidence read from `ElectionAudit.php`, `composer.json`, `storage/logs/organisation_null/**`, and a zero-result call-site search. No code written; no file modified, moved or deleted; no constitutional conclusion reached.**
