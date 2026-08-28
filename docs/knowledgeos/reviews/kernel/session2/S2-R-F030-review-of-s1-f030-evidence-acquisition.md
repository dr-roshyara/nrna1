# Session-2 Review — S1-F030

## 1 · Source · 2 · Session-1 claim
`session1/S1-F030-evidence-acquisition-as-its-own-domain-and-the-acquisition-procedure-constitutes-evidence.md`

*"The acquisition procedure itself determines whether an observation can function as evidence"* — classified against law as **NOT ADDRESSED**; evidence acquisition proposed as its own domain, in direct tension with `S1-F011`'s exclusion of it as a mechanism; *association ≠ intervention*; *"do not automatically turn every noun into a domain object"*; and two governance rules from a misfiled baseline.

## 3 · Independent assessment
**Evidence class:** CLAIM · DESIGN PROPOSAL · DISTINCTION · CLAIM (modelling discipline). **Question types:** EVIDENCE, BOUNDARY, METHODOLOGY. **Level 1:** UNVERIFIED, 0 URLs.

## 4 · Challenged

**`.1` *NOT ADDRESSED* is wrong — law already carries acquisition method — CORRECTION**
Session 1: *"v1.1… says nothing about **acquisition procedure** as a condition of evidential function. Classified **NOT ADDRESSED**."*

⟦L⟧ `EvidenceLinks` (l. 209): *"the justification: evidence **references** + **acquisition method** + **reliability conditions**; pseudo-evidence never admitted."*

**Acquisition method is literally a component of the member.** So the *retention* requirement — that acquisition provenance be preserved alongside the reference — is **already law**, and *"pseudo-evidence never admitted"* is a gate condition tied to evidential quality.

⚠ **What survives the correction, and it is the interesting half.** Law **records** acquisition method; whether acquisition method **constitutes** evidential status — whether two identical observations differ in standing because of how they were obtained — is a further claim law does not make. Session 1's classification is wrong for the retention claim and right for the constitutive one. **Split accordingly.**

⚠ Note this is the same line I verified for `S2-R-F018`; the fact was already in this register and Session 1 did not have it.

**`.2` The `F011` ↔ `F030` tension dissolves on scope level — tenth dissolution**
`S1-F011` excludes *Evidence Acquisition* as a mechanism (*"the domain admits evidence, but does not acquire it"*); `S1-F030` proposes acquisition as its own domain. Session 1 records *"direct tension… unresolved"*, then notes a possible dissolution and declines to apply it.

Applying the A/B/C/E classification: acquisition may be **its own bounded context (C — surrounding architecture)** while remaining **outside the Kernel's extent (A — no change)**. *"Not a Kernel responsibility"* and *"a domain in its own right"* are then both true, of different things.

**Classification: DISSOLVES — DIFFERENT SCOPE LEVEL (bounded context vs Kernel extent).** Tenth dissolution, tenth mechanism.
⚠ **Instrument provenance declared** (`X-006` addendum): the classification is an HPA-supplied instrument, not corpus evidence. Nothing is corroborated by it.

**`.3` The constitutive claim's sharpest consequence is unrecorded**
If acquisition constitutes evidential standing, then an evidence **Entity** shared across claims carries **one** acquisition history that may qualify it for one use and disqualify it for another. ✅ Session 1 sees this as a third cost on `S1-F018`'s Entity horn. ⚠ It compounds with `S2-R-F029` Candidate B: **role** and **acquisition** together would determine admissibility per use — which makes a shared evidence Entity progressively harder to defend. Recorded; `S2-R-F018` already shows law took the third route (`EvidenceLinks`, content external), so the horn's costs are of historical interest, not architectural.

## 5 · Confirmed
*Association ≠ intervention* as a new non-collapse (✅ the causal member of `S1-F027`'s inference-licensing family). *Do not automatically turn every noun into a domain object* (✅ the corpus's most compact statement of the failure mode it repeatedly commits — the eight formulations were largely built by promoting nouns). The misfiled-baseline flag (✅ census C12).

## 6 · DDD classification
Acquisition method → **already a member component**. Acquisition-as-domain → candidate **bounded context**, outside Kernel extent. *association / intervention warrant* → candidate **non-collapse**. *Don't promote nouns* → candidate **modelling policy**.

## 7 · Implementation relevance — **what would we build?**

| Candidate | A/B/C/E | Build what? | Verdict |
|---|---|---|---|
| Acquisition provenance retained on evidence | — | Nothing | **DO NOT IMPLEMENT (already protected)** — `EvidenceLinks` carries *acquisition method* (`.1`) |
| Acquisition **constitutes** evidential standing (gates admission) | **B or C** | An admission condition keyed to acquisition method | **NEEDS FURTHER EVIDENCE** — one uncited source; and *"pseudo-evidence never admitted"* may already do this work. Same semantic-denotation problem as `S2-R-F029` Candidate B |
| Acquisition as its own bounded context | **C** | A context, not a Kernel change | **NEEDS FURTHER EVIDENCE** — a context-map question, hence **adjudication. NOT ADJUDICATED** |
| *Association ≠ intervention* | **D** | Nothing | **PRESERVE AS KNOWLEDGE ONLY** — it governs what a claim **licenses** (action), and action/workflow is excluded by three formulations |
| *Don't turn every noun into a domain object* | **D** | Nothing | **PRESERVE AS KNOWLEDGE ONLY**, strong form. **Eleventh instrument**, and the only one that applies at **modelling** time rather than review time |
| *Deterministic does not mean demanded* | — | — | **NOT ROUTED.** Bears on `W:C-2`/`C-K3`; adjudication material, and the source is a baseline document, not a ruling |

## 8 · Conclusion vs reasoning
Constitutive-acquisition claim: **conclusion partly survives** (constitutive half), **classification fails** (`.1`). `F011`↔`F030` tension: **conclusion fails** — it dissolves (`.2`) — **reasoning sound**, and Session 1 identified the dissolution route without taking it. *Don't promote nouns*: **both valid.**

## 9 · Effect on previous findings
`S2-R-F018` — the Entity horn's costs accumulate but are moot; law took the third route. `S2-R-F029` Candidate B — **compounded**: role *and* acquisition together.

## 10 · Open questions · Adjudication · Status
Does *"pseudo-evidence never admitted"* already encode an acquisition condition · is acquisition a context outside the Kernel.
**ADJUDICATION: NONE. STATUS: CONTINUE.**
