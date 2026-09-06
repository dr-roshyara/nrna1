# II.3 · Three-lens audit (math/statistics + DDD + evidence fidelity)
**Commissioned:** HPA supervisory instruction, 2026-08-29 (II.3 = AMBER, bounded verification).
**Method:** every §5–§6 claim traced to its evidence record; F-4 audited order-theoretically;
census membership traced to primary records; BA-ED2-11 table checked field-by-field; no edit applied.

## A · RED — corrections required (2 + 2 table cells; exact wording in §F)

**RED-1 · §6 "the stratification's conjunction executes" — WRONG ATTRIBUTION.**
The reference suite (`ladder_dc_reference.py` + output) executes: I-12 covering relation (skip
rejected, in-order promotion), A6 boundary (10^6 evidence inert; authority act crosses), governed
adjacent states, Ω_A probe, and the **042 DC admissibility conjunction** — which belongs to no
GN-19 repair. **I-11 (R-1 stratification) has NO reference execution anywhere in the test
directory.** The chapter's sentence upgrades I-11 from read-level to executed. The conjunction
that executes is the DC's, and the register's own verdict on it is MV-F-5 (no ratified
conjunction matches the ratified six-tuple) — so the sentence also silently borrows a *finding-
laden* result as if it were a clean pass.

**RED-2 · §5 "a later independent audit found the pre-repair model preserved byte-identical
beneath its banner" — UNSUPPORTED AT TWO LEVELS.** (i) No audit record makes that finding: the
math report lists v0.1 among audited *inputs* (line 75); the Part-III gate report contains no
v0.1/md5 line; the evidence-map's citation ("GN-51 gate review, md5 discipline") does not contain
the claim — a producer citation-verification lapse, owned. (ii) Byte-identity is **unverifiable
in principle here**: `model/canonical-architecture.md` is not tracked by git (pathspec unknown to
git), so no baseline exists against which any auditor could byte-compare. What the record
actually carries: v0.2 §8's ledger statement "v0.1 retained unedited (banner only) as the
pre-falsification record," and the file's continued presence under its corrected banner.

**RED-3/RED-4 · BA-ED2-11 table, rows 2–3, `Tested?` cells** — inherit RED-1/RED-2:
row 2 "executes-as-reference (verified byte-level)" (no byte-level verification exists; only
R-3/R-4 semantics execute, R-1 unwitnessed); row 3 "executes-as-reference" (uniform overclaim —
the later verification is part executed, part read-level).

## B · AMBER verified → cleared (no correction)

**B-1 · F-2 provenance (strict five-way distinction).** The record supports exactly: *no residual
role was found in the reviewed corpus* (v0.2 §8 R-2: "no corpus evidence establishes any
G-residual inside Zero") + evidence-conservative choice + η-totality held open (OQ-1). It does
NOT support: demonstrated impossibility, mathematical redundancy, semantic redundancy as proven,
or formal reducibility. **The chapter states only the supported form** — §3: "found *no evidence
for any residual*"; §5: "evidence-conservative resolution with the totality assumption held
open." No collapse present. §6's "directly source-attested" is MV-F-14 verbatim-in-substance
("The binary Zero(K,EC) is directly source-attested (25D.34; 025e); R-2's evidence basis is
stronger than the ruling recorded").

**B-2 · F-4 covering relation — formal audit (the 8 questions).**
1. *Underlying relation:* permitted status transitions on the admission axis, plus one boundary
   edge. 2. *Domain:* {Candidate, Supported, Accepted} ∪ {Committed}; FA layered states
   (REJECTED/CONFLICTED) live outside the ladder, reachable by governed act only. 3. *Order:*
   explicitly enumerated in the ratified text with the ⋖ symbol and "immediate predecessor"
   language (v0.2 §1: `Candidate⋖Supported⋖Accepted`, `Accepted⋖Committed` across the boundary);
   its reflexive-transitive closure is a 4-element total order. 4. *Poset properties:* a finite
   chain — reflexivity/antisymmetry/transitivity hold trivially in the closure. 5. *Intermediate
   element:* another status strictly between two statuses in that chain — well-defined.
   6. *Mathematical or rhetorical:* MATHEMATICAL — and on a finite chain, covering pairs and
   adjacent pairs provably coincide, so the order-theoretic reading and "one step at a time" are
   the SAME statement for this structure; the feared divergence cannot arise here. 7. *What the
   implementation tested:* rejection of the admission-axis skip (Candidate→Accepted — the only
   two-step skip on a 3-chain), in-order promotion, boundary reachable only from Accepted,
   authority-act requirement, evidence-volume inertness. Near-exhaustive on this finite domain,
   but still execution. 8. *Property vs examples:* executed examples over the reference
   implementation — the register's own grade is exactly this (MV-F-20: "I-12's covering relation
   is well-defined, consistent with the FA layered extension, and executes; AF-F-3 — what moves
   an item — unaffected and still open"). **Verdict: the axiom is well-defined as ratified; the
   chapter reports it in the ruling's own vocabulary; no definition needs inventing; the open
   residues (AF-F-3 trigger; demotion only via governed layered states) are already registered.**
   No theorem-by-execution occurs once RED-1 is corrected.

**B-3 · "Fourth sighting" census — SUBSTANTIATED, contemporaneously recorded.** The four members
and provenance: **(1)** Step 121 corpus audit (boxed "CRITICAL GOVERNANCE GAP"); **(2)** CON-06
freeze practice (corpus, enacted); **(3)** F-1 (3B, synthesis); **(4)** CF-003 (3C, repository:
Constitution v1.0's in-force status asserted downstream, no locatable act; GN-27 resolution —
"ratification act located; defect = stale banner only," matching the chapter's "mildest form").
Family membership is NOT retrospective: CF-003's own 3C text says "the same failure family
v0.2's F-1/I-11 guards against… a **fourth, implementation-side sighting**," and archaeology
finding **AF-008 ⟦HISTORICAL-FACT⟧** records the census as such and confirms sightings 1–2
historically independent of 3–4. Wording retained.

**B-4 · §6 remaining claims.** "F-1… sighted a fourth time" → AF-008/CF-003 (above) ✓.
"F-2 … source-attested" → MV-F-14 ✓. "covering relation executes" → test output line "skip
Candidate->Accepted rejected : PASS" ✓. "passed a reference-implementation check" → true only for
the ladder side (RED-1 rewording covers this). "pre-repair model … byte-identical" → RED-2.

**B-5 · Statistical-word sweep.** "third independent sighting" (§3) ✓ AF-008 members 1–3;
"ACCEPT four times over" (§4) ✓ GN-19 ruling text has four ACCEPTs; "Nothing else changed; the
ledger says so" (§5) — correctly *attributed* to the ledger (v0.2 §8 "Nothing else was
modified"), not asserted independently ✓; "two later independent reviews" (§8) ✓ = GN-44 hostile
+ GN-46 math; "single recorded governance breach" (§4) — verified under the definition
*executing an unruled governance act*: unique in the ledger (AF-F-24, AF-F-30 and the CONTEXT
overwrite are production/delivery errors, not authority breaches). Holds; definition noted here.

## C · GREEN — explicitly protected
Breach narrative §4 (both quote layers, order, non-sanitization) · discipline formula §1 ·
F-1/F-3 DDD content §3 · ruled ledger §5 (minus the byte-identical clause) · "the ledger enforces
detectability, not incorruptibility" §8 · "audited decisions ≠ theorems" §6 · six-step pattern §7
· self-reporting disclosure §8 · word count (no padding).

## D · MUST NOT CHANGE (architecture/research boundary)
OQ-1 (η-totality) stays open · AF-F-3, MV-F-5, MV-F-20, PF-6/7 residues stay register-level ·
no formalization of I-12 beyond the ratified text · no I-11 axiomatization (PF-9 lane) ·
"inherited its sources' diseases" (§3, [M]-note): audited as EXPLANATORY carry-over language, not
a formal lineage mechanism — F-1 (gap present in corpus, flagged by Step 121), F-2 (the literal
historical signature 025d), F-4 (practiced-unstated in corpus) are recorded carry-overs; F-3's
source carried both the conflation and its antidote (A6), which the chapter itself shows. Retain.

## DDD lens (instruction §4)
Concept→responsibility→boundary→relationship→lifecycle checked for all five separations; the
chapter uses "policy" only in the R-1 pair (content vs in-force), "authority" only in the act
sense (the ⪰_C precedence sense never appears), "status" always typed (epistemic vs boundary),
"finding/ruling" only as typed record kinds. No catch-all is created by the explanation.
Consistent with GN-58 (A6 exemplary; R-1 sound; discipline = typed records).

## E · Producer-control observation (reported, not adopted)
RED-2 exposed a control gap: the nine-control verification confirmed the evidence-map row EXISTS
but did not open the cited file to confirm it CONTAINS the claim. Candidate BA-ED2-12 rule:
"evidence-map citations are spot-verified to contain the claim, not merely to exist." HPA's call.

## F · Exact proposed wording (only where necessary)
1. §6 replace: "and the repairs' invariants passed a reference-implementation check (the covering
   relation executes; the stratification's conjunction executes)" → **"and the ladder-side
   repairs passed a reference-implementation check (the covering relation executes — a skip is
   rejected, in-order promotion runs; the A6 boundary semantics execute — evidence volume cannot
   cross, an authority act can); the stratification (I-11) has no reference execution and remains
   read-level"**.
2. §5 replace: "and a later independent audit found the pre-repair model preserved byte-identical
   beneath its banner" → **"and the pre-repair model remains preserved in the record, unedited
   but for its corrected banner — a preservation the ledger asserts and later audits read from,
   not one any audit byte-verified"**.
3. Table row 2 `Tested?`: "executes-as-reference (verified byte-level)" → **"partly
   executes-as-reference (R-3/R-4 ladder semantics; R-1 unwitnessed)"**.
4. Table row 3 `Tested?`: "executes-as-reference" → **"partly executes-as-reference"**.

## G · Final recommendation
**GREEN AFTER CORRECTIONS** — the four items in §F, nothing else. The chapter's structure,
breach narrative, census, F-2/F-4 mathematics, and DDD separations all substantiate; the two
defects are exactly the disease the book teaches (silent upgrade in synoptic/verification prose),
found by tracing claims to records rather than checking tokens.
