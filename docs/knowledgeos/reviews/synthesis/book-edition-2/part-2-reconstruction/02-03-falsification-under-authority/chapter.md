# II.3 · Falsification Under Authority

> **Edition 1 abstract** *(frozen baseline, verbatim opening)*: When the reconstruction had
> produced a canonical model (v0.1), the programme did not proceed to celebrate it. It
> commissioned an attack: can the synthesis be proven wrong from its own materials? — *Full
> Edition-1 text (as corrected under GN-45/GN-54):
> `../../../book/part-2-reconstruction/02-03-falsification-under-authority/chapter.md`.*

## 1 · The chapter's subject — a method under maximum load — *status: method [M]; events explicitly recorded [E]*

[M] This chapter narrates programme events, not corpus history — everything below is **explicitly
recorded** — in the governance ledger and the phase reports — with both layers preserved where an error occurred. The
subject is the discipline formula that carried the whole reconstruction:

```
FINDING  ≠  PROPOSED REPAIR  ≠  AUTHORIZED REPAIR
```

and the day it was tested three ways at once: by a commissioned attack on the synthesis, by the
attack's success, and — most instructively — by the discipline's own single recorded breach at
the gate.

## 2 · The commission — attack your own synthesis — *status: explicitly recorded [E]*

[E] With the first canonical model (v0.1) assembled from the corpus, the programme's next act was
a falsification pass: *can the synthesis be proven wrong from its own materials?* The reviewer
was instructed to attack, not to admire — grade every relation, hunt contradictions, and propose
repairs it had no authority to apply. Four findings returned.

## 3 · The four findings, with their arguments — *status: explicitly recorded [E]; derivations reconstructed from the report*

**F-1 · The self-referential admission policy.** [E] The model's AcceptancePolicy governed what
may become knowledge — and was itself an unowned, unversioned model primitive: the policy that
governed admission was subject to nothing. The report recognized the convergence with the
corpus's own Step-121 audit, which had reached the same defect — its verdict box reads
"CRITICAL GOVERNANCE GAP", beside the warning that the "Constitution can be silently weakened."
The wider census came later: the archaeology's AF-008 records four sightings of this
failure mode and places F-1 third. Second in that census are the corpus's freeze episodes, which
had *enacted* the defect rather than naming it. **Proposed repair:** stratify — policy-as-content (admissible like any
claim) versus policy-in-force (versioned; changed only by governed decision).

**F-2 · The goal inside the gap.** [E] The historical signature `Zero(K, G, EC)` carried the goal
twice — inside the contract's derivation and as a direct argument. The attack asked what work G
does inside Zero that EC does not already carry, and found *no evidence for any residual*.
**Proposed repair (resolution left open):** either establish the residual or simplify the
signature — explicitly *not* both, and explicitly not the reviewer's call.

**F-3 · The smuggled status.** [E] The ladder was written `Candidate → Supported → Accepted →
Committed` — four rungs of one epistemic scale. But the corpus's own source had argued that
commitment is an authority act, not an evidence level ("a manager's authority does not make
evidence more truthful"). A decision-boundary status was living inside an epistemic ladder.
**Proposed repair:** re-type — three epistemic rungs; Committed a boundary status conferred by
act, under the rule now known as A6.

**F-4 · The practiced, unstated axiom.** [E] Every worked example climbed the ladder rung by
rung; no text forbade skipping. A discipline in practice, absent in law. **Proposed repair:** one
axiom — transitions form a covering relation.

[M] Note the findings' common shape, visible only in Part II's framing: each is the synthesis
caught doing what the corpus itself had done — the model inherited its sources' diseases along
with their insights, and only an adversarial pass distinguished the two.

## 4 · The breach — and why it is this chapter's centerpiece — *status: explicitly recorded [E], both layers preserved*

[E] Between the findings and the ruling sits the programme's single recorded governance breach.
The supervising authority discussed the repairs and wrote, hedged: *"I would now make the actual
governance decision explicitly, e.g.: …"* — the trailing examples, per-finding acceptances,
survive not as recorded quotation but as the premature entries they induced — a
**recommendation wearing a ruling's clothing**. The synthesis engineer executed it: recorded a
ruling that had not occurred, produced the repaired model, bannered the old one superseded.
The same day the authority corrected the record — no ruling had been made, and the governance
state was still at the gate; the ledger's correction layer carries the regrade verbatim:
*"GN-19 status: PROVISIONAL — awaiting the explicit HPA ruling."*

[E] What happened next is why the episode is taught rather than buried: the premature entries
were re-graded PROVISIONAL, the repaired model re-bannered PREPARED-UNRATIFIED, the original
model's banner corrected to "last authorized" — and **the erroneous text was retained unmodified
beneath its correction**, exactly as the architecture's history discipline demands of any record.
The gate then closed properly: the explicit ruling arrived, in unhedged form, ACCEPT four times
over, and only then did the repaired model acquire authority. [M] The discipline proved itself
precisely by being violated once and holding: the breach was *detectable* because findings,
proposals, and rulings were separate record types; it was *correctable* because records are
append-only; and it left the method stronger — "a recommendation is not a ruling" ceased to be a
slogan and became a checked property of the ledger.

## 5 · The ruling and the ledger — *status: currently ratified [R]*

[R] The authorized repairs entered as a change ledger in the model itself, every row carrying its
finding, its ruling, and its epistemic grade: the stratification (F-1 → R-1) with new invariant
**I-11**, graded REQUIRED-BY-COHERENCE with corpus-adjacent support — the grade announcing openly
that this invariant was argued into the model under ruling, not read from one source; the
signature simplification (F-2 → R-2), chosen as the *evidence-conservative* resolution with the
totality assumption held open and revisitable — an openness later deepened, not embarrassed, by
the discovery that the source itself had left contract-construction unresolved; the re-typing
(F-3 → R-3) making A6 the explicit crossing rule; and the covering-relation axiom (F-4 → R-4) as
new invariant **I-12**. Nothing else changed; the ledger says so; and the pre-repair model remains preserved in the
record, unedited but for its corrected banner — a preservation the ledger asserts and later
audits read from, not one any audit byte-verified.

## 6 · What later verification added — *status: subsequently tested / explicitly recorded [E]*

[E] The falsification pass was itself later put under review, and its results held with
refinements worth recording: the F-2 simplification turned out to be *directly source-attested*
(a binary signature exists in the sources — the ruling's evidence basis was stronger than the
ruling recorded); the F-1 disease was sighted a **fourth** time, on the implementation side, in
its mildest form (a stale status banner masking a real ratification act); and the ladder-side
repairs passed a reference-implementation check (the covering relation executes — a skip is
rejected, in-order promotion runs; the A6 boundary semantics execute — evidence volume cannot
cross the boundary, an authority act can); the stratification (I-11) has no reference execution
and remains read-level. [M] None of this converts the repairs into proven
theorems — it converts them into *audited decisions*, which is all this method ever promises.

## 7 · The pattern for reuse — *status: method [M]*

[M] Extracted once, plainly, for any programme that wants it: (1) commission the attack from
outside the authorship; (2) type the outputs — finding / proposed repair / ruling — as distinct
records; (3) let no proposal self-execute, and treat hedged language ("I would…", "e.g.:") as
non-rulings by rule; (4) when the gate is breached, correct the record additively — never erase;
(5) grade every repair, and let REQUIRED-BY-COHERENCE announce what ruling supplied that reading
could not; (6) re-verify later, independently, and record what strengthened as readily as what
qualified. **Relation-status ledger (BA-ED2-11):**

| Relation | Source | Ratified? | Formal status | Computable? | Tested? | Open issue |
|---|---|---|---|---|---|---|
| finding → proposed repair → ruling | ledger record types | yes (standing) | typed discipline | partly (record greps) | enacted-in-L5-practice | hedged-language detection is judgment |
| ruling → applied repair (ledger rows) | model §8 ledger | yes | complete per row | yes (diff-verifiable) | partly executes-as-reference (R-3/R-4 ladder semantics; R-1 unwitnessed) | none |
| repairs → later verification | audit records | evidence, not correspondence | corroborated with refinements | partly | partly executes-as-reference | audited decisions ≠ theorems |

## 8 · Limitations — *status: register [U]*

No governed OQ owned. Standing: F-2's totality assumption remains the model's OQ-1 (open by the
same ruling that applied the repair); the breach's detection depended on record typing plus a
human correction — the ledger enforces detectability, not incorruptibility; and this chapter's
events are self-reported by the programme that committed them, mitigated by the two later
independent reviews and the preserved double-layer records, and stated here so the reader can
weigh it.

## 9 · Conclusion — *status: method [M], summary*

[M] The synthesis was attacked before it was trusted; the attack won four times; the repairs
entered only under explicit authority, through a breach that was caught, preserved, and made
into method. Two of the architecture's twelve invariants are children of this chapter — and so is
the sentence a reader should carry out of it: in this programme, *the record of being wrong is
kept as carefully as the claim to be right.* The final chapter of this Part follows the ruled
model out into the world — the conformance pass that asked what the living repository actually
honors.
