*Window reviewer W (Opus), step 1 blind inventory, returned as hand-back text, verbatim (saved by the Professor at 2026-10-20T21:53:51Z). sha256 of the hand-back text at receipt: `dfc75e5ada5f9d9437e16ec2915a7e62829306fcd6c4f97b530cea8c2e33edfa` (UTF-8, the text below this line exactly).*

C19 STEP 1: BLIND INVENTORY (transition review, S0101–S0105)

Preliminaries
- All five paths appear once each in docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv (grep -cF = 1 for each). Each was read whole. S0102 (922 lines) was read in two consecutive parts, 1–460 and 460–922.
- Skimmed-source safeguard: no source in this window, and none in the read log, was read at depth "skim". There is therefore no skimmed source to read blind.
- Nothing under W/06-theory/ was read, and no git log or diff of W was looked at. One scratch script, /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/c19w_deltas.py (run with python3 -I), recomputed S0105's eight Δ values (all match), the 13.45-minute gap, and the count of mandated OQ-L terms (7).
- Independence here is process independence (a separate reviewer process with no access to the Professor's work in this step), not personal independence.

S0101 — AMD2 verification (224 lines)
1. Evidence classes for identity: self-declared < "evidenced by runtime metadata this process read itself" < third-party attested. It claims the middle class is "strictly weaker than attestation" (L50-52).
2. [I] The central factual claim of §0 is false, and S0105 shows it. §0 says the morning refusal 12351dd6 was "this session's" and that its claim to be 2da45a86 was false (L16-28, L45). S0105 L30, L100 and L108 show that 2da45a86 created the refusal and that 4858c37c did not yet exist. So the "mechanically established" correction was wrong: runtime metadata read by the process itself produced a false self-attribution.
3. [I] Faulty inference at L23: "no compaction/summary boundary, so it did not inherit that session's context." Absence of compaction was taken to mean absence of inheritance. S0105 L91-98 refutes this: the context was inherited through a pasted opening user turn. Inheritance has channels other than compaction.
4. The observation that the bar "failed in the false-bar direction" is the mirror image of INV-ATTR-2 (L206-208). [I] It rests on item 2's false premise, so that observation is itself unsound.
5. Existence and category are separate questions, and neither is derived from the other (L79-92). The two grounds given for C-5's existence are an adopted invariant that "presupposes" the capability, and one performance.
6. Six mandated C-5 separations, of which external attestation was missing (F-3, L104). [I] F-3 shows that a state value differs from an assessed dimension.
7. Receipt is at most two of the five authorities, and a single yes/no would have let one authority cover five assertions (L112).
8. Lifecycle defects are excluded from C-10 as a deliberate self-limitation: recording an act is not the same as knowledge arriving (L114).
9. F-2: an exception ("deterministic automated checks") turns on an undefined term, so decision B-3 cannot be taken (L135-143). Source slip: the text says "2 of 6 short" (L199), but 7 terms are mandated, as S0102 L803 says.
10. A dependency is not ownership or containment, and each of the three dependency kinds has its own consequence for deferral (L147).
11. The count changed over time: 13 verdicts became 14, and "the later act governs" (L184).
12. F-7: off-record execution recurs, now the third time; a lane cannot record its own START (L204).
13. The appended erratum (L220-224) moves the material-participation limb to "open" and calls the verifier's earlier self-favourable resolution "OQ-L's failure mode". [I] It gives the mechanism as "compacted continuation", which contradicts S0105's "compaction NO". The conclusion (inherited context) agrees.

S0102 — Capability architecture analysis with AMD1 and Correction #3 (922 lines)
1. Existence vocabulary has four values: YES, NO, CONTESTED, NOT YET ESTABLISHED. Existence comes before category, and two inferences are forbidden: "not a bounded context ⇒ not a capability" and "not a bounded context ⇒ cross-context" (L28, L463-482).
2. Sufficient condition for existence: "a capability with an adopted governing invariant that has been performed exists" (L475). C-10's need is evidenced but it has never been performed, so: "necessity INFERRED; existence not" (L476).
   [I] Trivial-solution test: INV-ATTR-2 is a prohibition ("must never be represented as attested"), which the empty mechanism satisfies. A prohibition does not presuppose a capability, so ground ① is weaker than claimed.
3. Separation is nine dimensions at three maturity levels, not a boolean. It is strong where it is authority-shaped and absent where it is identity- or access-shaped (L123-137).
4. Four-state model: NOT_APPLICABLE, NOT_ESTABLISHED, ESTABLISHED, EXTERNALLY_ATTESTED. NOT_APPLICABLE must never collapse into PASS. Organizational independence moves from N/A to NOT_ESTABLISHED (L518-529).
   [I] The same table uses PARTIAL and PROCEDURAL ONLY, which are not in the four-state vocabulary. The states have no stated order.
5. Mechanically establishable is distinguished from established today. Process separation is possible and was done once, by hand. Independent execution is partial: provenance shows which process acted, not isolation (L531-538).
6. External attestation added as a dimension (Correction #3, F-3): "intra-platform third-party ≠ external attestation" (L828-840).
7. C-10 path: the smallest coherent boundary is applicability + receipt. There are seven states, Published through Verified (L546-558), and receipt is not application, understanding or compliance. I-K1 has three undefined terms (L562-564).
   [I] A receipt is a record made by the session itself (ContextAcknowledged), so "possession authority" is self-asserted. That is the very failure §8.3 names.
8. C-14: "no platform-wide capability is established" differs from "no capability exists", and only the first is supported (L589-592). There are four sub-concerns with different owners; enforcement assurance is C-5-shaped (L596-605). Reading 1 vs Reading 2 (tiering as the decision-right) stays open (L229-237).
9. Structure: one control loop of four capabilities, related by flow rather than boundary, with four independent change clocks (L42-52, L308-310).
10. Candidate invariant: "a gate's verdict may not be issued by the party the gate constrains". It is implemented twice (G-1, CAP-09) and stated as OQ-L (L312-317, L653). The single-operator fallback may make it unsatisfiable, and "an unsatisfiable invariant is worse than none" (L664).
11. Correction #3 definitions (L807-815):
    - "constrain" is a relation between a control and the party whose act it conditions, not the party that runs the check;
    - a machine-generated result's trust comes from its execution conditions, not from any party;
    - ratification needs a party (answerability), a party other than the constrained one, and a route that party cannot write.
12. Three orthogonal axes: mode of production, effect (advisory or authoritative), and answerability. The 2×2 grid has all cells occupied. "Authority is about effect, ratification about answerability" (L817-824).
13. Dependencies come in three kinds:
    - semantic: constrains meaning, not deferrable;
    - evidence: constrains what can be proven, deferrable only by weakening the claim;
    - implementation: constrains sequence only, deferrable.
    The original single "build order" is reclassified (L673-684).
14. Record operations kept apart: preservation, additive amendment, replacement (a strikethrough counts as a change of wording), and workflow transcription, which acts on a different object (L863-870).
15. A §A1.0 observation: inputs recur from outside the governed channel (3 times), described as a "knowledge-channel property" with no owner inferred (L458-459). The Correction #3 producer declares itself 4858c37c, under the heading "the finder is the fixer", and the PO/ARB accepted the risk (L775-780).

S0103 — Correction #3 verification registration (204 lines)
1. An assignment identifier is not a selected or started process (L13-16).
2. Seven criteria are all required. Independence means process identity + lineage + working-context provenance, and a different session ID alone is insufficient (L20-32).
3. Classification has three values: INDEPENDENT, NOT INDEPENDENT, NOT ATTESTED. If lineage cannot be established the result is NOT ATTESTED, never PASS. NOT INDEPENDENT means STOP before substantive verification (L34-36).
4. Inv C: only the mutation owner may hand off. A delivered-but-never-started lane holds no ownership and cannot hand off; the refusal itself is the finding (L57-77).
5. Recording `from: null` is refused because, although technically accepted, it would assert that no predecessor exists, which is false (L84). [I] Here a well-formed record carries a false implied claim.
6. A Governance self-correction: a precondition report that "never executed" (broken pipe) is withdrawn. A claim that is true on other evidence was still not established by that report (L86-88).
7. A START transcribed after the fact sits at seq 25, after the work: the sequence position is when the confirmation was received, not when execution began. Nothing is back-dated (L126-146).
8. Handoff is not start. The mutation owner is NULL and "held" until START. G-3 is a conjunction (handoff recorded AND human START), and a human act alone never yields ACTIVE (L99, L181-202).
9. The defect is carried forward and "not laundered" by the handoff (L189-191).
10. [I] The barred-producer attribution (5e1dd9ee as author of Correction #3, L40) contradicts S0102 L775, where Correction #3 self-declares 4858c37c, and S0105 refutes it.
11. [I] The record keeps two orders (sequence order and real-time order), and the source keeps them explicitly apart.

S0104 — Independence-gate refusal (117 lines)
1. Identity was bound from the live transcript's modification time plus the scratchpad path, described as "mechanically corroborated, not merely self-declared" but still evidential (L14-22). [I] Most-recent-mtime is fragile when sessions run concurrently, and S0105 L143 records a concurrent window.
2. Four of seven criteria fail, so NOT INDEPENDENT (L34-44).
   [I] Criterion 1 ("distinct identity") is tested only against 1c8b041b and 2da45a86, not against 5e1dd9ee, which is the process itself. The reference set of the criterion is undefined.
3. Criterion 6: "not a lineage OF the barred producer — it IS the barred producer" (L41). [I] Self-identity is not lineage; the case belongs under criteria 1, 2 and 4, and the criteria do not partition the cases.
4. Criterion 3 passes because "AMD2 is a PO/ARB act" (L38). [I] This rests on the AMD2 homonym (grant vs amended artifact) that S0105 §2.1 names; under the artifact sense it would fail.
5. The gate comes before substantive reading: provenance first, artifact never opened, because "a disqualified process that has already formed views cannot un-form them" (L51-58).
6. "A refusal is not a verdict"; issuing either value would turn a failed gate into an assurance claim (L96-99). Recording the START "consumes nothing" (L107).
7. I-1: an attribution in a binding grant is not derived from attested evidence. Two readings: impersonation, or anticipation read as fact. It is returned unresolved because the process is an interested party (L64-72).
8. I-2: the identity substrate failed twice in one day in opposite directions (false negative and false positive), and "the gate is only as strong as the identity substrate" (L74-80). This is C-5 failing in production.
9. A qualified verifier needs Correction #3's actual producer established first (L111).

S0105 — Provenance reconciliation (167 lines)
1. Evidence hierarchy: write-class tool_use provenance correlated with commit timestamps is the discriminating evidence. Grant text, self-declaration, filenames and summaries are refused as evidence (L11-15).
2. Git metadata is not probative of process authorship: one human identity and one trailer on every commit, so it serves only as a timestamp anchor (L17).
3. Quality vocabulary: AUTHORED, OBSERVED, ASSERTED, NOT ATTESTED (L19).
4. Reconciliation table: eight committed artifacts, Δ between 3 and 58 seconds (my recomputation matches), plus one uncommitted file. Corroboration from a single writer and from payload size (+179/−5) (L25-38).
5. Homonym: AMD2 means both the grant amendment (Governance) and the amended artifact (5e1dd9ee), and the record conflates them (L51-58).
6. The grant's attribution is wrong. Its cause: an attribution true of the predecessor work was carried forward from a self-declaration without re-testing (L60-73).
7. The finding that outranks the misattribution: one process (4858c37c) wrote both the findings and the correction, and both sides of the identity dispute. The defect is in the artifact's lineage, so choosing another verifier cannot repair it (L77-85).
8. Lineage has separate relations: compaction NO; continuation/fork NOT ATTESTED; inherited working context ESTABLISHED, from the opening user turn that pastes 2da45a86's first-person output (L89-98). [I] This explains S0101's false self-identification: the inherited first-person text was taken as the process's own.
9. A temporal-existence argument: a process that did not exist yet cannot have authored the artifact (L100).
10. CORRECTION 1 is false and the ERRATUM is supported, both settled on provenance, neither by the documents' own word (L102-112).
11. Robustness of a conclusion with several grounds: one ground (criterion 2) is withdrawn and the NOT INDEPENDENT classification survives on criteria 4, 6 and 7. "The bar is right, the reason was wrong" (L153-163).
12. The record changed while this work was being done (concurrent seq 27), and that is disclosed (L143).
13. [I] The reconciler ("Governance") never names its own producing process or tests its own independence or lineage. The source that settles the provenance of everything else is itself unattested, an exact instance of the regress risk.

Cross-source table (who authored what, and the identity claims)

| Point | S0101 | S0102 | S0103 | S0104 | S0105 |
|---|---|---|---|---|---|
| Author of refusal 12351dd6 | 4858c37c ("this session") | — | — | — | 2da45a86 (wins) |
| Author of Correction #3 | — | 4858c37c (self-declared) | 5e1dd9ee (grant) | "no record of producing it" | 4858c37c (AUTHORED) |
| How context was inherited | none (no compaction); erratum: "compacted continuation" | erratum, same | — | — | compaction NO, inherited context YES (pasted turn) |
| AMD2 | grant, with "its implementation" | header "under AMD2" | "did not author AMD2" | the PO/ARB act, so criterion passes | homonym named |
| Identity evidence class | runtime metadata the process read itself (it was wrong) | self-declared | self-declared at seq 21 | transcript mtime + scratchpad path | write-class provenance |

The table is the backbone: the same question ("who acted?") is answered with evidence classes of increasing strength, and the weaker classes are shown wrong in both directions. That is direct evidence on C-5 and OQ-L.

Inventory delivered. Stopping for the comparison material.
