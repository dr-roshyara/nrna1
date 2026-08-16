# Decision Request — `EM-OPEN-054`: the business scope of each acceptance gate

**Type:** Governance decision request (Session 2) · **Date:** 2026-08-16 · **Business language only**
**⛔ Nothing adopted. No scope chosen. `EM-OPEN-055` (standing) not touched — it waits on this. Architecture ⏸️ · Session 3 🛑.**

---

## The seven questions

**Q1** pre-counting acceptance — election-wide, post-specific, or another defined scope? · **Q2** post-counting acceptance — the same choice · **Q3** what exactly is the object accepted at each gate? · **Q4** if the two scopes differ, is that intentional and governed? · **Q5** if post-specific, must every post be accepted before its result may be published? · **Q6** what happens when one post is accepted and another is not? · **Q7** does a failure for one post prevent publication of the others?

---

## ⚠️ 1 · The finding that must be seen before Q1–Q7 are answered

> ## **Post-specific acceptance implies per-post progression — and the adopted model has only election-wide progression.**

**`EM-GOV-010`** governs the progression of **the election** through its phases; **`EM-GOV-009`** fixes a schedule of **phase windows**; **`EM-VOC-004`** ties an opportunity to a **published schedule**, and schedules are per phase. **Nothing in the adopted record is scoped to a post.**

**Consequence, and it is a genuine fork:**
* **(i)** acceptance is post-specific but **progression stays election-wide** — then **one refused post halts the whole election's progression**, and Q6/Q7 answer themselves; or
* **(ii)** **progression itself becomes post-scoped** — a substantial extension of `EM-GOV-010` **and** of the opportunity model, since **a per-post acceptance would correspond to no opportunity that currently exists.**

⛔ **Governance does not choose. But Q1–Q7 cannot be answered without choosing, and (ii) is a much larger decision than it appears from the question.**

## 2 · `Q3` — an object question hiding inside Gate 2

**Gate 1's object is a *process*.** **Gate 2's is stated as *"the completed counting process AND resulting tabulation"* — which bundles two different objects:**

* **the counting *process*** — *"the count was properly conducted"*;
* **the *tabulation*** — *"these numbers are correct"*.

> ⚠️ **These are not the same acceptance, and the difference reaches the constitution.** **`ADR-T11` (constitutional, build-breaking) forbids any voter↔vote linkage.** **Accepting a *process* can be done without seeing any figures. Accepting *figures* requires seeing them.** **The narrower the object, the less the gate strains anonymity.**
>
> **Governance raises this because `Q3` is the question that decides it, and it is easy to answer "both" without noticing what "both" requires.**

## 3 · `Q6`/`Q7` — the consequences of a split outcome

**Post A accepted · Post B refused · Post C accepted.** Candidates named by the PO: publish A and C · wait for B · enter recovery · publish partial results · require an election-wide decision. **None endorsed.** Two consequences must be visible first:

**⚠️ 3.1 · Partial publication is irreversible and forecloses election-wide remedies.** **`PBDIGIT-60` (PO ruling, on record): publication is an immutable constitutional fact; visibility is a separate control — hide/show, never "unpublish."** **So if A and C are published and B's failure later proves to indicate an election-wide problem, A and C cannot be withdrawn.** **Publishing partially spends an option that cannot be recovered.**

**⚠️ 3.2 · Observation, offered as an observation and not a recommendation:** **publishing results for A and C while B remains unresolved makes those results available to whatever process resolves B.** In elections generally, partial results becoming known while a contest is still open is a recognised integrity concern. **Governance does not claim it applies here; it records that the question exists and is not addressed by any adopted rule.**

**Already settled, whichever way Q6 goes (`EM-GOV-011` + `EM-GOV-012` pending):** a refusal **halts** progression at that point with the reason recorded, and **does not by itself determine the election-level outcome** — so *"refused post ⇒ election cancelled"* is not available as a default. ⚠️ **`EM-GOV-012` is still unadopted, so that protection currently rests on a report.**

## 4 · `Q4` — why differing scopes may be correct rather than untidy

**The two gates accept different objects, and the objects have different natural scopes:** a **process** is election-wide; a **tabulation** is per post. **A single scope imposed on both would force one of them into an unnatural shape.** **Governance states this as the reason the asymmetry may be intended — not as a recommendation that it should be.**

## 5 · What is NOT decided here

`EM-OPEN-055` (standing) · participant composition · quorum · threshold · objection, abstention, absence and replacement · acceptance timing (`EM-OPEN-053`) · whether the gates are mandatory at all. **All wait on this answer, as the agreed sequence requires.**

## 6 · Governance's position

**None on the substance.** Governance poses the seven questions as given, exposes the per-post progression dependency (§1), separates the two objects bundled in Gate 2 (§2), and records the two consequences of a split outcome (§3). **The choice is the PO's.**

**Traceability.** `EM-GOV-009`/`010`/`011` · `EM-GOV-012` *(pending)* · `EM-VOC-004`/`005` · `ADR-T11` · `PBDIGIT-60` · `EM-OPEN-053`/`055`/`056`.
