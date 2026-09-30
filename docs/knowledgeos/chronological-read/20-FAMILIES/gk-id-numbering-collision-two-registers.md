# gk-id-numbering-collision-two-registers

**Scope(s):** OBJECT · **Row count:** 10 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `GK-09 = Sañjaya (v0.1) vs Buddhi (r286)`, `GK-16 = Sañjaya (v0.1) vs Guṇa (r286)` · **Aliases:** `GK identifier collision`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0051`, scope `OBJECT`: A discovered defect: the Translation Register v0.1 (19 entries) and the revised Step-286 register (16 entries) assign different GK-nn identifiers to the same Gita term, making a bare cross-document GK-nn citation unresolvable; classified as the same defect class as the earlier H-K identifier collision but worse for being cross-document. Resolved locally via GK-nn/v0.1 vs GK-nn/r286 disambiguation, not renumbering.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2108 §"**`GK-16` means Sañjaya in one document and Guṇa in the other. `GK-09` means Sañjaya in one and Buddhi in the other.** ... **This is the same defect class as the `H-K04`/`H-K06` collision — now at the GK level and *across two documents*, which is worse: a cross-document citation of `GK-16` is unresolvable.**"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2110 §"**Always spell the term. Where an ID is used, qualify it: `GK-nn/v0.1` or `GK-nn/r286`.** **A bare `GK-nn` is not a resolvable reference in this programme.**"]

## Lifecycle
last_seen: S2131. Candidate lifecycle: **CONTESTED**. Evidence: contested_by_own_contradiction_type: true (this label's own rows contain a CONTRADICTION-type entry)

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S2110 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2119, S2131 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S2108, S2110, S2129 |
| experiments | PRESENT | S2110 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Warning: Identifies GK-16 as the single worst collision: it means Guna (an unopened, unexamined measurement item) in v0.1 but Sanjaya (the major observation-layer recovery finding) in r286 — a bare citation of GK-16 could refer to either the strongest finding in the whole research programme or an item nobody has yet examined. [S2110]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2108] types=['CONTRADICTION', 'WARNING'] scope=OBJECT — "Discovers a new defect: the Translation Register v0.1 (19 entries) and the revised Step-286 register (16 entries) assign different GK-nn IDs to the same terms (e.g. GK-16 = Sanjaya in one, Guna in the other; GK-09 = Sanjaya in one, Buddhi in the other; only Jnana=GK-13 agrees across both); classified as the same defect class as the earlier H-K04/H-K06 identifier collision but worse because it is cross-document, making a bare GK-16 citation unresolvable without specifying which register. Resolution: disambiguate locally as GK-nn/v0.1 or GK-nn/r286 wherever ambiguous, without renumbering either registry (renumbering is a registry decision, not a research one)." (anchor: "**`GK-16` means Sañjaya in one document and Guṇa in the other. `GK-09` means Sañjaya in one and Buddhi in the other.** ... **This is the same defect class as the `H-K04`/`H-K06` collision — now at the GK level and *across two documents*, which is worse: a cros…")
- [S2110] types=['EXPERIMENTAL-RESULT'] scope=OBJECT — "Produces the full GK-nn crosswalk between the v0.1 (19-entry) and revised-286 (16-entry) registers: 20 distinct terms total, 8 IDs agree across both registers (Krishna, Arjuna, Kshetra, Kshetra-jna, Atman, Karma, Karma-phala, Sarathi, Jnana), 6 IDs collide (Sanjaya/Guna at GK-16, Sanjaya/Buddhi at GK-09, Dharma/Vairagya/Yoga/Mokṣa each shifted by one or two positions), and 3 terms exist only in v0.1 (Manas, Omega, Zero/boundary) with none unique to r286 beyond the collision set." (anchor: "**Measured: 20 distinct terms · 8 IDs agree · 6 collide · 3 exist only in v0.1 · 3 only in r286.**")
- [S2110] types=['WARNING', 'ANALYSIS'] scope=OBJECT — "Identifies GK-16 as the single worst collision: it means Guna (an unopened, unexamined measurement item) in v0.1 but Sanjaya (the major observation-layer recovery finding) in r286 — a bare citation of GK-16 could refer to either the strongest finding in the whole research programme or an item nobody has yet examined." (anchor: "**`GK-16`** | **Guṇa** | **Sañjaya** | ⚠️ **worst.** Sañjaya is the observation-layer recovery; Guṇa is an unopened measurement item. **A citation of `GK-16` could mean the strongest finding or an unexamined one**")
- [S2110] types=['GOVERNANCE', 'CONSTRAINT'] scope=METHODOLOGICAL — "Adopts a standing citation rule for this research programme: always spell out the term, and whenever a GK-nn ID is used, qualify it as GK-nn/v0.1 or GK-nn/r286 — a bare GK-nn is declared not a resolvable reference. Notes retroactively that prior artifacts in this programme used the r286 scheme throughout, so Sanjaya = GK-16/r286 in this research's own usage." (anchor: "**Always spell the term. Where an ID is used, qualify it: `GK-nn/v0.1` or `GK-nn/r286`.** **A bare `GK-nn` is not a resolvable reference in this programme.**")
- [S2110] types=['GOVERNANCE'] scope=METHODOLOGICAL — "Issues a non-binding recommendation to the registry owner: adopt v0.1's numbering (the more complete of the two, 19 entries, the only one carrying Omega and Zero as explicit research constructs) and extend it, since renumbering the smaller r286 set is cheaper than the reverse — but only after strengthening three of v0.1's own classifications on corpus evidence: Kshetra-jna from Candidate to RATIFIED, Sanjaya from Analogy to corpus-native construct, and Jnana->Knowledge from Partial to REFUTED (relocated to delta). Explicitly not adopted or renumbered here, only recorded for the registry owner's decision." (anchor: "**One numbering must become authoritative.** Either is workable; **v0.1 is the more complete** ... **Recommended: adopt v0.1's numbering and extend it**, since renumbering the smaller set is cheaper ... ⚠️ **But three v0.1 classifications need strengthening on…")
- [S2113] types=['VALIDATION'] scope=METHODOLOGICAL — "Endorses the GK registry reconciliation as a genuine infrastructure correction rather than a cosmetic fix: a bare GK-nn citation must now be considered invalid/unresolvable within this programme, since otherwise later citations could silently point to different concepts." (anchor: "**a bare `GK-nn` must now be considered invalid/unresolvable within this programme.** That is not cosmetic; otherwise later citations can silently point to different concepts.")
- [S2118] types=['VALIDATION', 'GOVERNANCE'] scope=METHODOLOGICAL — "Endorses making the GK-nn citation rule permanent: no philosophical claim may be cited by a bare GK identifier, the spelled-out term is always mandatory, characterizing the registry collision as a genuine governance/data-quality issue the research lane correctly discovered inside itself, not a cosmetic problem." (anchor: "**No philosophical claim may be cited by bare GK identifier. The term itself is mandatory.** That is excellent.")
- [S2119] types=['PRINCIPLE', 'DISTINCTION'] scope=METHODOLOGICAL — "Names a recurring symmetry across the programme: research may discover a registry defect (like the GK-id collision) without thereby becoming the registry's correcting authority, exactly mirroring Step 285's relationship where research establishes a state relationship while governance alone ratifies canonical status -- recommends moving this into an explicit 'Governance boundary' paragraph for Step 286." (anchor: "**research may discover a registry defect; research does not thereby become the registry authority.** That mirrors Step 285: **research establishes relationship; governance ratifies canonical status.** There is a nice architectural symmetry here.")
- [S2129] types=['WARNING', 'LIMITATION'] scope=CROSS-OBJECT — "Identifies a residual identifier-level ambiguity distinct from the document's content quality: 'Step 287' now names two separate artifacts (REFINED-STEP-287.md for equality and REFINED-STEP-287-INVARIANTS.md for invariants), and the Step 288 mandate refers to 'the corrected 287' without distinguishing which -- a registry decision on naming should precede running Step 288, or it will inherit an ambiguous input, echoing the earlier GK-id registry collision pattern." (anchor: "**287-EQUALITY is ready as a document.** It is **not** ready as an *identifier* — the numbering collision (§0a) is unresolved, and **Step 288's mandate names "the corrected 287"** without distinguishing equality from invariants. **A registry decision should pr…")
- [S2131] types=['GOVERNANCE', 'DISTINCTION'] scope=METHODOLOGICAL — "Records a second, distinct identifier collision (beyond the GK registry one): 'Step 287' now names two separate real artifacts, one on equality and one on invariants, with the numbering itself contested and its resolution declared a registry decision, not a research one -- until resolved, both must be cited by subject (287-EQUALITY vs 287-INVARIANTS), never by bare number alone." (anchor: "⚠️ **NUMBERING NOTICE.** Three sources place **equality at 285** and **Invariants at 287**; my existing `REFINED-STEP-287.md` is the **equality** artifact. **Both bodies of work are real; the identifier is contested and its resolution is a REGISTRY decision, n…")

## Notes for P3
- This label's own rows include a CONTRADICTION-type entry — the lifecycle is CONTESTED and P3 should reconcile the conflicting claims rather than pick one silently.
