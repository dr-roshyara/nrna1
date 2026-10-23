*Window reviewer W (Opus), step 1 blind inventory, returned as hand-back text, verbatim (saved by the Professor at 2026-10-23T16:41:24Z). sha256 of the hand-back text at receipt: `db39e97dfdeceb6602e0247bc30e451c74168af278dcfd4dd62b888c03185b4f` (UTF-8, the text below this line exactly).*

C24 STEP 1: BLIND INVENTORY

**Disclosure about context.** The git status and recent-commit context attached to this session reveal some of the Professor's work. Commit 1991acc60 reads "window C24 draft (S0126-S0130; §8.17, CON-0231-0235)", so I know the section number and concept-ID range, but no content. Earlier commits show that S0123 and S0125 were skimmed in C23, and the C23 verdicts on CON-0226/0227/0230. They do not show his depth choices for C24. W/tmp/corpus-reading/START-TIMES.tsv shows as modified, but I did not open it. I read nothing under W/06-theory/, no git log or diff of W, and no read log.

**Allow-list.** All five paths are in CORPUS-ALLOWED.tsv as S0126–S0130, rows 1500–1504, status "match". Each file was read whole, in one read. For the repetition check only, I grepped S0117 (D5 proposal), S0122 (E2 completeness) and S0124 (E2 governance review) for key terms.

**Independence.** This is process independence, not personal independence: I am a separate session that has not seen the Professor's text.

---

### S0126 — E2 adoption decision (83 lines)
**Depth: SKIM.** [I] Most of it restates S0122/S0124: the twelve fields, the 7/3/1/1 counts, and INFO-1..3 (S0124:91–99). Only two points are new: a decision text can override a sentence in the artifact it adopts, and adopting a model does not count as evidence that the capability exists.

1. A definition is not evidence of existence. Under D5's scheme, an adopted completeness model is Class C evidence and cannot establish existence by itself (L73).
2. The decision text overrides the artifact. The adoption fixes the counts at 7/3/1/1, so the artifact's §1 claim of "four SUPPORTING" is "superseded by this decision" (L46). The decision corrects the content of the object it adopts.
3. The adopted field model (L19–26): twelve fields; actor_reference FORBIDDEN; session_reference "RECORDED, NOT ATTESTED"; knowledge_version REQUIRED. Identity boundary: the receipt can be referred to but does not bind. Authority boundary: no authority beyond D1's claim.
4. Adoption rests on three conditions — COMPLETE, TRACEABLE, PRESERVED — with zero blocking findings (L34). [I] Adoption is therefore conditional on a review result, not only on the human act.
5. Four options were weighed (A accept / B accept with follow-up / C return / D reject, L38–43). The selection rule is proportionality: a finding blocks only if it has architectural impact.
6. INFO-2 carries a constraint forward. The same process shaped both sides of the E2↔D5 interface, which limits who may later verify or accept D5 (L64, L67).
7. Non-decisions (L71): C-10 not established (D2 stays NOT YET ESTABLISHED), no bounded context, no owner, D5/D6/D7 not decided.
8. Order of acts: E2 → D5 → D2, with D2 reopened only through the D5 trigger, then D6 → D7 (L78).
9. [I] Source detail: S0122:99 classifies invalidation_reference as "SUPPORTING (REQUIRED when lifecycle_state = invalidated)", a conditional deontic status. The 7/3/1/1 fixing hides this condition. The "four vs three" slip may come from it. Not proven.
10. [I] Open: the producer is "self-declared, not attestable" (L55), so the INFO-2 adjacency rests on self-declaration.

### S0127 — D5 establishment-criteria adoption (157 lines, with an appended act)
**Depth: FULL.** [I] E1–E10 and L1–L3 repeat S0117 (L236, L283–288). But the L3 divergence between summary and object, its settlement, and the three-way distinction are new and carry theory.

1. Existence, authority and maturity are three separate things: "Capability existence ≠ Capability authority ≠ Operational maturity" (L132). L1 is a milestone, L2 is establishment (the D2 boundary), and L3 is authoritative establishment (L123–127).
2. D2 changes at L2, not L1 (L29–31). One instance cannot tell an owned outcome from an incidental one that merely has the receipt shape. E6 (≥2 independent governed acts) is what discriminates.
3. The conjunctive levels (L23–27): L1 = E1∧E2∧E5(creation+ack)∧E7∧E8∧E9∧E3(identified); L2 = L1∧E6∧E5(supersession ∨ invalidation)∧E4(exercised); L3 = L2∧E3(exercised)∧E10. [I] This is a monotone chain. E3 and E5 appear at different strengths ("identified" vs "exercised"; creation vs supersession), so a criterion is not a single predicate.
4. Option A is rejected because no model can satisfy it (L33). E10 needs a history, which cannot exist at establishment time. R-6 adds that ⑦ "distinct reason to change" can never appear in any minimum set (L80). [I] This is a satisfiability argument about time, not a preference.
5. The evidence model has four orthogonal dimensions: SUBJECT, EPISTEMIC, PROVENANCE, REPRODUCIBILITY (L35). INDEPENDENT is excluded, because requiring it would import C-5's unresolved state. [I] Homonym: E6 still requires "≥2 *independent* governed acts" (L30). "Independent" is used in two senses (acts vs evidence provenance).
6. A summary versus the governed object (L39, L119): "A summary must never silently replace the governing artifact." This is the same pattern as the Flag O resolution. [I] Contrast S0126 L46, where the decision text *does* override the artifact's own sentence. The precedence direction depends on which text is "the decision object". Possible tension.
7. The L3 divergence (L47–50): reading L3 as maturity only drops E3(exercised) from the model, so L3 would mean "kept working" rather than "may be relied upon". The appended act settles it in the proposal's favour (L114–117).
8. Trigger (L58–67): an observable event, plus evidence (the governed record), plus an observation path that piggybacks an existing read and is "observer-minimised, not observer-free", plus a two-part authority path: Governance MUST register a detection; only the PO/ARB may reopen D2. "Detection is never automatic revision" (L63).
9. E6 versus E10 (L88, L143): repetition across acts versus duration across time. If they collapse, L2 absorbs L3. Recorded as OPEN.
10. Open: who verifies the first satisfaction judgement? An eligible, disclosed process is required (L86, L141).
11. Existence comes before categorisation, so D6 cannot be reached while D2 is open (L147). Current state: C-10 is "DESIGNED, not ESTABLISHED" (L155).
12. [I] The required future chain (L152) is "repeat realization → evidence reconstruction → PO/ARB". The trigger text (L67) adds "provenance independently reconstructable" — "independent" again, despite item 5 above.

### S0128 — Governance-state durability ADR, PROPOSED (193 lines)
**Depth: FULL.** [I] This is a new topic with no precedent in S0101–S0125: measured facts, an authority–durability inversion, a classification reframing, options and a new invariant.

1. Classification finding (L66–68, L138–139): the records store no derived state (O-3, L45). Mutation owner, sessions and item state are computed by the fold. So "there is no runtime state in the runtime directory": the content is governance evidence filed in a runtime location. The defect is classification, not durability.
2. Inversion (L80–83, INFERRED): I-10 makes the record outrank prose (O-8). Yet the outranking artifact is the only untracked one, while its derivatives are durable. The ADR states this is an inference and a risk only under failure scenarios.
3. Three-way split (L74–78): source of execution (tracked) / evidence of execution (untracked) / authoritative decision record (tracked).
4. Measured reconstructability (L51, L114–118): session lineage 58/58 = 100%; grants 83/99 = 83%. The 16 uncited grants are "overwhelmingly -AMD*". [I] "Overwhelmingly" is not quantified. "Two independent methods agreeing exactly" is reported, not shown.
5. The lossy layer is the amendment layer, which carries the load-bearing constraints (L120–123). Consequence: "who acted" can be known while "what they were authorised to do after amendment" cannot. Failure mode: "authority drift by attrition" — prose becomes authoritative by default.
6. Concurrency under Option A (L95–96): a git merge conflict on an append-only JSON array, resolved naively, drops transitions silently. O-6 (dense, monotonic seq) makes loss *detectable, not prevented*. [I] Necessary vs sufficient: a seq gap detects a dropped *tail-internal* transition. A dropped final transition leaves no gap — a counterexample to "detectable" as stated.
7. Option B (L104–108): a snapshot must be a projection, never a source (I-4: registry ≠ authority). Two truths would arise with no divergence detector. A snapshot can be append-only by construction. Who takes it? It must be mechanical, not an act, or Governance preserves its own authority record.
8. Recommendation B′ (L145–157): relocate the record to a tracked, append-only location; runtime stays ephemeral. It has one representation, so the anti-corruption rule is met trivially. Fallback: A plus R-CONFLICT. It is out of the commissioned option set.
9. R-CONFLICT, PROPOSED (L161): a conflict is never resolved by choosing a side; a resolution that leaves a seq gap is invalid by construction.
10. DDD (L132–135): BC-7's aggregate (CAP-14) is persisted in an infrastructure space whose contract is ephemerality, a placement violation. The transition log is GOVERNED but REPRODUCIBLE only while it exists, because it cannot be re-derived.
11. Time dimension (O-7 L49, M-3/M-5 L171–173): only 2 of 210 transitions are dated, so order comes from seq alone. An import makes commit times *import* times. "Do not let an import be mistaken for a chronology."
12. Producer interest (L22–25): the producing process recorded much of the evidence at issue and has an interest in preserving it. Hence the two-direction reporting and the bar on self-approval.
13. Placement (L10–16): cross-product + research returns PENDING (exit 2) and is escalated. The ADR is placed at the reading that exits 0 (product-specific).
14. [I] Source detail: O-1 cites .gitignore line 32 only; S0129/S0130 cite lines 25 and 32.

### S0129 — PO/ARB position registration, with Amendment 1 and an appended decision-act registration (217 lines)
**Depth: FULL.** [I] It contains the position-vs-decision distinction, the refinement, a mechanical ground for refusing, and new principles. The three layers change state over time.

1. Position is not decision; registration is not decision (L19, L29–34). A conditional "I would select" plus routing the decision onward makes it a position. The process does not resolve the ambiguity by interpretation, and registers the weaker reading.
2. Refusal grounds (L14–15): decision authority is reserved to the human; and the producer is barred from approving its own ADR, a bar it registered on itself at seq 1.
3. Mechanical ground (L147–149): the engine refuses any grant without a humanActRef ("the record never manufactures authority"). If one process decides and registers, the humanActRef points at its own output. [I] This makes the authority boundary enforced, not only declared — an invariant with an owner (the engine).
4. The refinement (L53–60): it is not a "snapshot". The authority record was never runtime's to export, so B′ is relocation to its correct original placement. The ADR's producer concedes its own wording was still anchored in export vocabulary.
5. Principles stated in the act: "An aggregate cannot depend on accidental reconstruction" (L44); "History ≠ Reconstruction Guess" (L102), placed beside "Recording ≠ Asserting" and "Reference ≠ Ownership".
6. Option A rejected as target, with four risks (L43): cleanup deletes history; operational files become authority; merge conflicts become governance conflicts; ephemeral and authoritative data become indistinguishable.
7. R-CONFLICT strengthened (L45): it must preserve sequence integrity, provenance and the ability to reconstruct; it must not choose a side silently, delete history or rewrite sequence. Not adopted here.
8. Chain of standing (L94): Architecture proposal → Principal Architect recommendation → PO/ARB decision → Governance registration. The PO/ARB withdraws the "it IS the decision" reading himself (L87–89).
9. Reuse before creation (L109–113): a falsified hypothesis is reported (the ARB *is* declared). The INFERRED idea that OQ-2 may need only a reading of the existing ARB-only matrix becomes input to D3.
10. Refusal as evidence (L117–119): "the refusal is evidence the separation model is working". Recorded as OBSERVED, with an interested-party caveat: the credited process is the refuser. [I] A single self-reported instance.
11. Appended act (L141–179): D1 and D2 are blank, so NOT SELECTED. Placement is DECIDED ("existing governance applies; no new rule"), corroborated by checks: OQ-2 already sits in documentation-placement.yaml:31; cross-product+adopted exits 0.
12. First-hand evidence against C (L187): commit de998173 committed 16 review documents while all 28 grants and 33 transitions stayed outside git.
13. R-CONFLICT already matches practice (L188): four precedents are cited. "Adoption is the legitimate promotion path, not accretion" (ES-006.1).
14. DDD constraints (L194–197): Runtime Context owns execution mechanics; Governance Evidence Context owns authoritative history. Avoid Execution = Authority (excludes A) and Reconstruction = Source of truth (excludes C).
15. [I] Corroboration gap: the D3 check queried maturity=*adopted* (exit 0), while the ADR's escalated gap was maturity=*research* (exit 2, S0128 L10–11). The check does not test the escalated case.

### S0130 — Durability decision (86 lines)
**Depth: FULL** — short, but new. [I] The rationale (§3–4) largely restates S0129. The consequences (§5), the "target, not state" point, and a lineage gap are new.

1. DECIDED (L13–17): D1 = B′ Governance Evidence Relocation; D2 = ADOPT R-CONFLICT (S0129's strengthened form, L28–29); D3 = existing placement governance applies.
2. Governance records this decision and did not make it; both selections came from the human (L5).
3. [I] Lineage gap: S0129 L157–158 and L209–212 record the second act's D1/D2 fields as *blank*. S0130 cites "both acts" (L86), yet no third act supplying the selections is registered in the sources. Where D1/D2 came from is unrecorded. This is a contradiction with S0129, or a missing source.
4. D2 constrains D1 (L58–59): relocation is itself an operation on governance evidence, so R-CONFLICT binds the migration. If a relocated record and a runtime record diverge, neither may be chosen silently.
5. A decision changes the target, not the state (L70): until relocation happens, the estate stays in the C condition the decision rejects. [I] This is a temporal distinction between decided and realised, parallel to S0127's DESIGNED vs ESTABLISHED.
6. Relocation, not duplication (L67): after relocation, runtime does not hold the record. .gitignore stays correct for runtime (L66).
7. Recorded fact: the engine default `--dir` = `.claude/runtime/workflow` (L65) contradicts B′.
8. Reason for rejecting B, changed from the ADR (L53): "runtime remains the origin of authority; durability is a derived copy". The ADR rejected B for its second representation and missing divergence detector. [I] The rationale shifted between sources.
9. Reason for rejecting C (L54): "authority lineage cannot be reconstructed". [I] This overstates S0128 O-9: session lineage is 100% and grants 83% reconstructable. The accurate claim is "partially, in the amendment layer".
10. [I] Migration subject (L68) is 28+33 and 2+3 for two work items. S0128 O-2 has 16 records, 210 transitions and 99 grants. The scope differs or the figures are inconsistent; unexplained.
11. Placement versus physical location (L84): B′ decides the boundary the record belongs to, not where it sits. Repository layout is a separate act.
12. D3 (L33) says "OQ-2 remains open under its existing owner", while S0129 L162 says placement is "DECIDED". [I] Deciding that there is no new rule while the question stays open: consistent, but two statuses for one item.
13. Implementation planning is now permitted; no implementation is authorised (L74).

---

### Cross-source table [I] — labels with more than one meaning

| Label | Meaning A | Meaning B |
|---|---|---|
| D1 | C-10 receipt-semantics decision (S0126 L23; S0127 L99) | Durability model selection (S0129 L157; S0130 L15) |
| D2 | C-10 capability-existence decision (S0126 L30; S0127 L13) | R-CONFLICT adopt/reject/defer (S0129 L158; S0130 L16) |
| E2 | Receipt-completeness artifact (S0126) | Criterion E2 within E1–E10 (S0127 L25) — possibly the same, not stated |
| Option A/B/C | E2 adoption options (S0126 L40–43) / D5 combination options (S0127 L21, L33) | Durability options (S0128 §5) |
| independent | Excluded evidence dimension INDEPENDENT (S0127 L35) | "≥2 independent governed acts" (S0127 L30); "independently reconstructable" (L67); "independent methods" (S0128 L51) |
| precedence of texts | Decision text overrides the artifact's own sentence (S0126 L46) | Governing artifact overrides the act's summary (S0127 L119) |
| authority / reconstruction | C rejected as "cannot be reconstructed" (S0130 L54) | 100% / 83% reconstructable (S0128 L51) |
