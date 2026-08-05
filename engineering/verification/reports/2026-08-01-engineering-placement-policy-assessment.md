# Engineering Knowledge — Placement Policy Assessment

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Repository Integrity Gate:** ✅ PASSED. **No file relocated · nothing adopted · no rule created.**

---

> # ✍️ CORRECTION — my previous report was wrong about the gap
>
> I concluded: *"The genuine gap is placement… the ladder is complete; the filesystem does not reflect it."*
>
> **There is no placement gap. `ES-005.3 — The Placement Litmus` (ARB 2026-07-10) already governs it, and its final clause is decisive:**
>
> > **"Research artifacts remain project-side (`docs/implementation/`) until promoted through qualification (ES-006 ladder)."**
>
> **All four commissioned deliverables already exist canonically.** The programme was not missing a lifecycle, a placement policy, a promotion policy, or a state between project knowledge and canon.
>
> ### **The rules were not absent. They were not followed.**
>
> That is a materially different finding — and a less comfortable one, since the artifact that broke them is one I wrote.

---

## 1. Every deliverable, already canonical

| Commissioned deliverable | Canonical home | Status |
|---|---|---|
| **Lifecycle states** | **ES-006.1** — `Research → Pilot → Qualification → Engineering Standard → Stable Engineering Capability` | ✅ exists |
| **Entry criteria** | **ES-006.4** — only *Project Outcome*; *"never arbitrary mid-coding speculation"* | ✅ exists |
| **Exit / promotion criteria** | **ES-006.1** + **R-37** burden of proof + **ES-003** qualification | ✅ exists |
| **Governing authority** | ARB / Decision Authority; audited by promotion-ladder audits | ✅ exists |
| **Placement policy** | **ES-005.3 — The Placement Litmus** | ✅ **exists — this is what I missed** |
| *"Where does a pre-canonical principle live?"* | **ES-005.3, final clause: project-side until qualified** | ✅ **already answered** |

**Nothing needs defining. Two standards, written weeks apart, already compose into a complete answer.**

## 2. The interpretive point that matters — ES-005.3 has two clauses, and they can appear to conflict

**ES-005.3** asks: *"Could a different project adopt the document **unchanged**? **Yes → `engineering/`.**"* … and then: *"**Research artifacts remain project-side until promoted through qualification.**"*

**`Layer_Verification_Rule.md` satisfies the first and fails the second.** It is domain-independent — that criterion passed in the promotion review — **and it is Research-stage.**

> ### **Maturity governs, not reusability.**
>
> The first clause tests **is it reusable?**; the last tests **is it proven?** **If reusability alone admitted an artifact to `engineering/`, anything domain-neutral could enter on the day it was written** — which is precisely what happened here.
>
> **Read the other way, the litmus would contradict ES-006.1 entirely**, since a research artifact that is domain-neutral would skip Pilot and Qualification by virtue of its wording. **The specific clause governs the general one.**

**This is the only genuine ambiguity found**, and it is a reading question, not a missing rule.

## 3. Consequence for `Layer_Verification_Rule.md` — identified, not acted on

| Property | Value |
|---|---|
| **Ladder stage (ES-006.1)** | **Research** — never piloted, never qualified |
| **Correct placement (ES-005.3)** | **project-side**, until promoted through qualification |
| **Actual placement** | `engineering/knowledge/methodology/` — beside an **ADOPTED** module |
| **Rule broken** | **ES-005.3, final clause** — and **ES-006.3**, *"research dossiers are input-only: never architecture until promoted"* |

**Not moved.** The constraint forbids relocation, and — more to the point — **moving it is a placement act under ES-005, which is the ARB's to direct.**

## 4. What "Engineering Proposal" turns out to be

**The proposed fifth state is not missing from the ladder — it is a *location* question the ladder deliberately leaves to ES-005.**

**ES-006.1 names maturity; ES-005.3 names where each maturity lives.** A pre-canonical principle is a **Research artifact**, and Research artifacts live **project-side**. **Adding an "Engineering Proposal" state would give the same artifact two names in two standards** — and ES-006.2's warning applies unchanged: *"no Level 5 exists; do not invent one."*

**The concept was never missing. Its home was simply not consulted.**

## 5. Recommendation

**Create nothing. Define nothing. Adopt nothing.**

**One question for the ARB, and it is now a compliance question rather than a design one:**

> **Should `Layer_Verification_Rule.md` be relocated project-side, as ES-005.3 requires of a Research-stage artifact — or should the ARB record an explicit exception, as it did for the DDD module in R-39?**

**Both are legitimate. What is not legitimate is the current state: a Research artifact in the canon directory with no ruling either way** — which is how it got there.

**📝 Recording Note — worth stating because it is the actual lesson.** Three commissions in a row have now searched for a missing rule. **There was none.** The failure was **mine, at the moment I created the file**: I did not consult ES-005.3 or ES-006.1 before choosing a location, and every subsequent finding — *placement front-ran promotion*, *location implies canon*, *the filesystem does not reflect the ladder* — was a symptom of that single omission. **The governance framework detected the breach reliably; what it could not do was prevent an author from skipping it.**

---

**Traceability:** **ES-005.3** (the Placement Litmus — the rule I missed) · **ES-006.1** (the ladder) · **ES-006.2** (*do not invent one*) · **ES-006.3** (research is input-only) · **ES-006.4** (entry criteria) · **ES-003** · **R-37** · **R-39** (the recorded exception precedent) · this session's promotion review and lifecycle assessment (**the latter's "placement gap" conclusion is corrected here**).
