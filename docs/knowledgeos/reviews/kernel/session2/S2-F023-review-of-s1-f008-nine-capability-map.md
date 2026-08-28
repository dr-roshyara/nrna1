# S2-F023 · Per-artifact review of `S1-F008` — the nine-capability "minimum capability map"

| | |
|---|---|
| **Session-2 finding ID** | `S2-F023` |
| **Session-1 artifact reviewed** | `session1/S1-F008-nine-capability-minimum-map-derived-from-existing-law.md` |
| **Session-1 findings reviewed** | the law-derivation claim · the nine-capability map · the anti-minimalist argument · the member-list comparison · the v1.1 coverage comparison · the `-duplicate` anomaly |
| **Finding class** | CONTRADICTION DISSOLVED (DIFFERENT QUESTION) · CONFIRMATION ×3 · CHALLENGE ×2 |
| **Status** | OPEN |

---

## LEVEL 1 · EXTRACTION FIDELITY → **UNVERIFIED — and here it is unverifiable twice over**

Corpus sealed; I cannot check `S1-F008` against its source. This artifact has a **second** unverifiable layer, and Session 1 identifies it precisely and unprompted: *"This document **reports** what F-1…F-5 established; the F-1…F-5 artifacts themselves are **not** in `kernel/`. Its claims about 'existing law' are therefore **not independently verified here**."*

The full chain by the time a claim reaches me:

```
v1.1 / constitutional law
      ↓  (link 1 — unverifiable from here)
F-1…F-5 governed act            ← not in the corpus at all
      ↓  (link 2 — unverifiable from here)
source document (110305)        ← reports F-1…F-5 second-hand
      ↓  (link 3 — unverifiable from here)
S1-F008                         ← reports the source
      ↓
this review
```

**Three unverifiable links.** So *"these are existing law rather than newly designed capabilities"* reaches me at fourth hand. Session 1's *"low-to-medium"* confidence rating on the law-derivation is therefore **correct and if anything generous**. ✅ **Level-1 handling confirmed — the best in the batch.**

⚠ Nothing in Levels 2 and 3 below implies any part of Level 1 was verified.

## LEVEL 2 · ANALYTICAL VALIDITY

### `.1` The `F007` ↔ `F008` "opposition" → **DISSOLVES: DIFFERENT QUESTION, one residual tension**

`S1-F008` records: *"direct tension: F007 says the Kernel is being made **too large**; F008 says a rule-evaluator Kernel is **too small**. ⟦INFERENCE⟧ **Opposed directions, same week, unreconciled.**"*

Run the contradiction test on the two exclusion/inclusion sets as Session 1 itself extracted them:

| `F007` wants OUT | `F008` wants IN |
|---|---|
| NL interpretation / intent parsing · rule **engine** · evidence **weighting** · self-audit | admission gate · contract conformance · identity assignment · evidence **admission** · justification preservation + sufficiency · representation-agnostic intake · epistemic-state determination · confidence assignment · history recording |

**Not one item F008 wants to keep appears on F007's exclusion list.** F007 removes **mechanisms**; F008 retains **domain responsibilities**. F007 row 2 even states the positive form of F008's item 4 — *the Kernel "should **preserve** evidence and its provenance", not weight it.*

So *"too large"* and *"too small"* are not opposed magnitudes on one axis. They are **one principle applied to two disjoint sets**: *mechanisms out, domain responsibilities in.* Same subject, different propositions, no shared proposition to contradict → **DIFFERENT QUESTION.**

**The residual tension is real and narrower.** F007 row 4 (*Adjudication ≠ Execution*) versus F008's map, which contains both adjudication (item 1, the gate) and execution (items 3, 6 — assigning identity, recording history). **That** is a genuine same-altitude disagreement about one thing, and it is worth more than the diffuse "too large / too small" framing that currently carries it.

**Consequence for `S1-F009`.** `S1-F009` Finding 3 presents its equilibrium criterion as *"directly address[ing] the `S1-F007` ↔ `S1-F008` opposition."* If the opposition is largely a framing artifact, the criterion resolves a smaller problem than claimed — see `S2-F024`.

### `.2` The v1.1 coverage claim → **CHALLENGE: work shown for six, conclusion drawn for nine**

`S1-F008` maps items 1, 3, 5, 6, 8, 9 to v1.1 features and concludes *"**Substantially CONSISTENT in coverage**."* Items **2** (contract-conformance enforcement), **4** (evidence admission) and **7** (representation-agnostic intake) are not mapped.

⚠ **Proportionality note:** all three are *plausibly* mappable (v1.1 carries a port contract, evidence links, and representation-agnosticism). So the likely defect is **incomplete showing of work**, not error — a weaker charge than `S2-F014`'s, and I state it as such. But it is the same shape: a conclusion covering the whole set from a mapping performed on part of it, with no measure and no statement of what was left unmapped.

### `.3` The `-duplicate` filename anomaly → **CONFIRMED hedge · one limit on the test**

Session 1 verified *"no md5-identical counterpart exists in `kernel/` (verified: 1 match, itself)"* and marked ⟦PROVENANCE: UNCERTAIN⟧ on the label's meaning. ✅ Correct hedge.

⚠ One limit worth recording: **md5 tests byte-identity, not duplication.** A copy differing by a header line, whitespace, or a date stamp passes as distinct. So the verification establishes *no byte-identical twin*, which is narrower than *not a duplicate*. Session 1's uncertainty marker already covers this; the note is for whoever later reads the verification as stronger than it is.

### `.4` *"Authority absent from this map"* → **CONFIRMED as stated · significance rests on contested reasoning**

As a fact about the list, true, and Session 1 draws no inference about which list is right — correct. But the significance offered (*Authority is present in two of three Phase 1 lists*) is the member-list-comparison reasoning challenged in `S2-F016`. Under the Zero lens, `Authority`'s status here is **unmentioned**, not demonstrably **excluded** — item 1 (*who may admit?*) and item 2 (*contract conformance*) are both places an authority concern could be living unnamed.

## LEVEL 3 · CROSS-ARTIFACT SIGNIFICANCE

| Relation | Classification |
|---|---|
| `F007` ↔ `F008` "opposition" | **CONTRADICTION DISSOLVED** → DIFFERENT QUESTION (new; 4th dissolution in the register) |
| `F008` row 4 tension with `F007` | **POSSIBLE CONTRADICTION** — narrow, same-altitude, **untested by me** |
| `F008` ↔ `F011` Q7–10 (*are these domain concepts at all?*) | **still the strongest untested candidate.** Unaffected by `.1` — `F011` questions the *category* of the nine, not their size |
| `F008` as a fourth member list | **RECURRENCE** — `S2-F017`'s count discipline applies; no new finding |
| The law-derivation claim | **RECURRENCE** of `S2-F020` — the reasoning-base question applies directly: this document reasons from F-1…F-5, not visibly from v1.1 |
| The god-object critique's reach | **REINFORCEMENT** of `S2-F022.6` — if `F007` applies to A/B/C by form, it applies to this map by the same form |

---

## WHAT SESSION 1 GOT RIGHT

The second-hand-evidence flag and the *"recorded, not verified"* discipline — exemplary, and the reason Level 1 could be assessed at all. The `-duplicate` hedge. Drawing no preference between member lists. Recording `Authority`'s absence as a fact without inference. Marking the anti-minimalist direction as a *direction*, not a conclusion.

## WHAT MAY BE OVERSTATED

*"Opposed directions … unreconciled"* (`.1`) · *"substantially CONSISTENT in coverage"* on a partial mapping (`.2`) · the md5 verification's reach (`.3`).

## UNCERTAINTY

Level 1 **unverified across three links**. Whether the source had v1.1 — **unknown** (`S2-F020`). Whether F007 row 4 and F008's map genuinely conflict — **untested**; it needs the source's own account of who executes.

## CONSEQUENCE

The register's headline tension between `F007` and `F008` should be **restated as one narrow disagreement about adjudication-versus-execution**, not a size dispute. That is a smaller claim and a much more tractable one. `S1-F009`'s criterion inherits the correction.

**Potential relevance — NOT ADJUDICATED.** `W:C-11` (item 9 vs F007's three-way confidence split) · `W:C-2`/`DEF-1` (the adjudication/execution residue) · `W:F-CM-1b`. **None routed.**

---

# IMPLEMENTATION RELEVANCE · `S1-F008` (nine-capability map)

The artifact's own claim decides most of this: the nine were *"confirmed capabilities **already present** in the existing law"* — **not** new proposals. If the claim is true, **there is nothing here to implement**; if false, the map is a design proposal with no warrant. Either way, **the map yields no work item.**

## 1 · DO WE NEED THIS?

**The map — no.** Every item is claimed to exist already, and `S1-F008`'s own coverage comparison finds six of nine present in v1.1 (`.2` notes the other three are plausibly present but unmapped). A restatement of existing law is not a requirement.

**The warning — yes, and it is the artifact's real deliverable.** *"The Kernel **cannot simply be a generic rule evaluator**"* identifies a failure mode with a concrete consequence: if the boundary is drawn too small, **identity assignment migrates into a mechanism** — which `INV-KOS-IDENTITY-001` and `INV-KOS-AGENCY-001` both forbid, and which no amount of rule-evaluation correctness would detect.

## 2 · WHERE WOULD IT BELONG?

The nine capabilities: **Domain/Core**, where they already sit. `JustificationPath` with its stated ownership split (*domain owns the admitted path and its sufficiency; mechanism owns production*): **Domain/Core + Port** — and this is the one element with a shape a schema would express.

The warning: **Governance/Process** — a review criterion, not a component.

## 3 · IMPLEMENTATION STATUS

- **The nine-capability map → DO NOT IMPLEMENT.** Nothing new is proposed.
- **The anti-minimalist warning → PRESERVE AS KNOWLEDGE ONLY**, as a standing review question at boundary decisions.
- **The `JustificationPath` ownership split → NEEDS FURTHER EVIDENCE.** It reaches me at fourth hand (Level 1); the F-1…F-5 act that allegedly establishes it is not in the corpus. **An ownership rule must not be implemented from a report of a report.**
- **The adjudication/execution residue (`.1`) → NEEDS FURTHER EVIDENCE.**

## 4 · WHY?

Refusing the map is not scepticism about its content — it is that **a claim of law-conformance is a reason to change nothing.** Implementing from it would either duplicate existing protection (`ES-005.4`) or smuggle in a design that presented itself as a summary. And `S2-F020`'s open question applies directly: this document reasons from F-1…F-5, with no visible sign it had v1.1, so its account of "existing law" cannot be the basis for changing anything.

## 5 · WHAT WOULD WE LOSE?

**By ignoring the map: nothing.**

**By ignoring the warning: a real and specific thing** — the guard against a minimal-kernel design that satisfies every stated rule while relocating identity assignment outside the boundary. That failure is invisible to conformance testing (each rule passes) and visible only to the question the warning supplies: *does anything the domain must own now happen somewhere else?* Losing that question costs an invariant, not a feature.

## 6 · IMPLEMENTATION CONSEQUENCE

None recommended. The warning's cheapest form is **a question at boundary decisions**, not code. Recorded so that a future *"we implemented the nine capabilities"* claim can be checked against the fact that this artifact proposed none.

---

**Independent assessment: the map is TRUE-IF-VERIFIED + REDUNDANT + DO NOT IMPLEMENT; the warning is UNVERIFIED-BUT-VALUABLE + PRESERVE.** A finding can be the weaker half of an artifact and still be the half worth keeping. **NOT ADJUDICATED.**
