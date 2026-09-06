# BA-ED2 · BOOK ARCHITECTURE AMENDMENT — EDITION 2 (RATIFIED BY GN-42, 2026-08-28)

**Amends the ratified Book Architecture (BA-1…BA-6, GN-34) for Edition 2 production ONLY.
BA-1…BA-6 remain in force and unmodified as documents; this amendment layers Edition-2
requirements over them. Edition 1 remains frozen under the un-amended BA. Authority: GN-42
Decision 1 (approved as proposed in `analysis/book-edition-2-plan.md` §13, with the HPA's
explicit requirement list).**

## BA-ED2-01 · Chapter depth
Per-chapter S/M/L/XL profiles and word RANGES per plan §5 (never fixed targets). Each Edition-2
chapter opens with its Edition-1 text verbatim as a labeled abstract ("Edition 1 abstract") —
traceability, not rewriting. Substantive sections are REQUIRED per the chapter's depth profile.

## BA-ED2-02 · Definitions
Explicit definition blocks wherever a concept is introduced: **Definition — <name>** · formal
notation · semantic meaning · scope · evidence/grade · relations · example · limitation.
A definition may never exceed its source's grade (GN-14 applies with full force). Inventory:
plan §6. No definition may lack ratified/evidence support.

## BA-ED2-03 · Derivations
The derivation inventory (plan §7) governs. Status vocabulary is mandatory and immutable through
prose: DERIVED · RECONSTRUCTABLE · ASSUMED · HYPOTHESIS · NOT ESTABLISHED · RESEARCH REQUIRED.
A NOT-ESTABLISHED derivation is TAUGHT AS OPEN with its closing act named. Never invented.

## BA-ED2-04 · Worked examples
The running example (plan §8, election-certification) threads Part III under the three-voice
discipline: CONCEPTUAL MODEL / ARCHITECTURE / IMPLEMENTATION-HONESTY. The example is
[INTERPRETATION]/[EDITORIAL] — an illustration of ratified machinery, never evidence for it, and
never product design.

## BA-ED2-05 · Diagrams
Plan §9 policy: every figure declares purpose · abstraction level · elements · relations · source ·
fact-or-visualization flag. L2 conceptual objects NEVER appear as C4 containers/components.
Justified figures only — no quota. Existing puml diagrams require FA-conformance audit before reuse.

## BA-ED2-06 · References
A reader-facing bibliography as the projection of the provenance spine; every substantive claim
carries evidence/provenance (BA-4 continues to govern the audit layer).

## BA-ED2-07 · Glossary
Generated from the ratified FA-4/BA-3 registers; collision-prone terms (Zero, K_t, Kernel, Lord,
Committed, Determination) flagged with their sense registers.

## BA-ED2-08 · Research-status marking (THE master apparatus)
Every section opens with its status line drawn from: fact [FA/RATIFIED] · definition · derivation
(with §03 status) · evidence [E] · interpretation [IN] · hypothesis [H*] · open [U/OQ-n].
**No upgrading of an [U]/open proposition into architectural fact — the GN-42 master constraint:
Edition 2 may explain the architecture more deeply, but may not expand the architecture because
deeper explanation creates theoretical gaps.**

## BA-ED2-09 · Historical evidence depth
Plan §11 table governs Part I. **I.3 is evidence-gated on the separate kernel-research commission
(GN-42 Decision 3) and is never filled from inference.** No historical claim may exceed its
evidence; the hindsight ban continues.

## BA-ED2-10 · Reader/editorial apparatus
Reading paths · notation table + corpus source-code legend · assumption register (η-totality
first entry) · indexes · appendices (mathematical: formal statements collected; architectural: RA
mapping tables) · verify-a-claim guide. Audit layer (claims/evidence-map/unresolved per chapter)
RETAINED, grown proportionally, not reader-facing.

## Imported standing rules (binding, from the ruled record)
GN-14 no-epistemic-upgrade · the D-2 verbatim rule (ratified wording reproduced exactly, never
paraphrased) · GN-10 status vocabulary (never "validated") · OQ non-resolution · DeepSeek and all
external mathematics excluded pending independent establishment + intake gate · Edition 1 frozen.

## Production order (GN-42 Decision 2)
**Part III → Part II → Part IV → evidence-ready Part I (I.2, I.4, I.5, I.6, I.1-light) → I.3 after
kernel research.** Reviews per plan §15: per-part producer + independent review (dimensions B/D/H
+ S status-fidelity), correction gates as needed, one final acceptance act.

## BA-ED2-11 · Relation-status tables (ADDED BY GN-50, 2026-08-29 — additive)
Every major formal relation taught in an Edition-2 chapter carries a table row:
| Relation | Source | Ratified? | Formal status | Computable? | Tested? | Open issue |
Purpose: no relation may look mathematically more complete than it is. Applies from III.9 onward;
earlier chapters acquire their tables at the Part III review's correction pass if ruled.

## BA-ED2-12 · Production controls (ADDED BY GN-54, 2026-08-29 — additive; binding for Parts II/IV/I)
1 Post-edit re-verification: any edit after a chapter's conformance check invalidates the check;
the mechanical suite re-runs before the chapter is citable as conformant. 2 Negative assertions
('no "X"', "n/n", only/never/all/none/complete/fully/always) are grep-verified, never
hand-asserted. 3 Cross-artifact summary gate: every synoptic census/arity/uniqueness claim is
checked row-by-row against the artifact it summarizes. 4 Running-example continuity ledger
(element × stage × status × evidence-count), machine-checkable, updated per chapter.
5 BA-ED2-11 "Tested?" column vocabulary: tested / executes-as-reference / enacted-in-L5-practice /
no. 6 Phrase-strength sweep in the producer suite. 7 BA-ED2-11 retrofit of III.1–III.8: ruled a
correction-pass candidate (pending). 8 CLAIM-STRENGTH INHERITANCE CHECK (GN-54): a compression may
never carry stronger epistemic status than its source representation.

## BA-ED2-13 · Delivery Artifact Integrity (ADOPTED BY GN-57, 2026-08-29 — binding)
The artifact subjected to final verification must be the artifact delivered for review, or any
transformation between them must be mechanically demonstrated lossless. Producer rule: review
deliveries are verbatim machine-emitted file content with md5 + line/word counts (or direct file
reading); never a re-typed/condensed rendition labeled as the artifact.

## BA-ED2-14 · Citation-content substantiation control (ADOPTED BY GN-61 — HPA label GN-58 — 2026-08-29 — additive)

**Citation existence is not citation substantiation.** A substantive verification claim may not
pass production verification merely because an evidence-map citation exists. For every claim
using a citation as evidentiary support, verification must establish, in order:
1. the cited artifact exists;
2. the cited location is identifiable;
3. the cited artifact actually CONTAINS the asserted evidence;
4. the evidence supports the claim at the STATED strength (claim strength ≤ evidence strength);
5. no retrospective interpretation has been silently substituted for the recorded evidence.

Applies with particular force to: "verified" · "tested" · "independently audited" · "all" ·
"none" · "only" · census claims · implementation-execution claims · byte-identical/hash claims ·
mathematical-conformance claims. This extends the BA-ED2-12 suite (as its ninth control); it is a
production-integrity control, not an architectural change. Origin: GN-60 audit of II.3 — the
nine-control pass verified evidence-map rows existed but not that cited files contained the
claims (mechanical presence checking ≠ semantic evidence checking); two silent upgrades survived
until an independent audit traced claims to records. The control lives in the production/
governance stream only — it is NOT retrofitted into any chapter's historical narrative.
