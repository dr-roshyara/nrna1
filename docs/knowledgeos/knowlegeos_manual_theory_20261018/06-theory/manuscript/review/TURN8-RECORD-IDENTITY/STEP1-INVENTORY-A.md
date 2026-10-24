TURN 8 STEP 1 INVENTORY A

Mode: topic review, step 1 only (blind). Each list is ranked against the topic: (a) what "the record" denotes; (b) how readers and writers come to use the same record; (c) when an entry belongs to the record; (d) when comparing a store with an outside reference detects a loss. Line numbers are the source's own. My own judgements are marked [I]. Independence here means process independence only: I am a separate session that has seen none of the Professor's work for this turn.

## S0300 — KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN (1,440 lines; PROPOSED; never executed)
1. **Single Authority Resolver Invariant.** Every reader and writer of governance evidence resolves the authority location through one governed mechanism (513). The two defaults are to be replaced by one resolution, not kept in sync: "keeping two defaults in agreement is the defect" (551, INV-R2). Two components computing the same default independently is the failure being removed (464–468).
2. **Authority has two axes.** One is where the record lives (P-1, P-2). The other is which program interprets it (P-4, `KOS_MECHANISM_PATH`), and that path can also write (456–488). Reporting form vs enforcing form: 524–525, 555–561.
3. **The invariant binds components, not the filesystem.** It is enforceable for writers and tooling, advisory for ad-hoc readers. So authority must rest on governed location plus provenance, not on every reader passing through the resolver (446–454, 563; OPEN-M4 at 1329).
4. **Authority moves when the writer's resolution changes.** That instant is Phase-5 slot 3(iv). Phase 6 only records it (709–716, 718–722). Readers switch first and the writer last, so the only exposure is a stale-read window, never an invisible write (741). Later correction: the bound on that window is 3(i)–3(iii), and 3(iii-b) is the evidence for it (743–753).
5. **Dense sequence numbers do not prove completeness.** The append is read-modify-write without a lock. In 23 of 30 concurrent trials a transition was lost, the losing process reported ok, and every survivor was dense and monotonic (428–443). "No record silently dropped" is withdrawn. What can be proved is only "no record lost relative to the frozen manifest and the observed inputs" (1176–1193). A clobbered tail is imported as a legitimate extension (1193).
6. **Proving a quiet window replaces proving no loss.** "No write occurred during the window" can be shown by re-hashing against a committed manifest. "No record was lost" cannot be shown in general (622–632). The test has three outcomes: identical; delta inside the declared set; delta outside it, which is a freeze violation (679–686).
7. **The migrator's own writes land in the frozen store.** The lane's own appends go into the store being frozen (636–648). They are declared in advance as an expected-delta class (652–657). The declared set must cover the slot 1, 1b and 2 record classes (659–677). Observed live: the grant count went 110 → 114 through the plan's own commission (1389–1390).
8. **Membership is by predicate.** The record is exactly `*.json` under the resolved directory. A `*.tmp.<pid>` remnant is quarantined, never migrated (609). Markers must not match `*.json` or they would be read as work items (1051). So membership is fixed by a filename predicate plus a location.
9. **The record is closed by content, not by time.** The SOURCE FINAL-STATE ENUMERATION is constructed and closed at 3(ii) and is immutable afterwards (840–846). Content outside it counts as POST-DEMOTION by construction. If order cannot be established, the default is POST-DEMOTION (893–901, 966). Timestamps cannot do this job: only 2 of 216 transitions carry a time field (893, 1204).
10. **A check only covers its operands.** The Phase-7 gate compares the source with the enumeration, so neither operand is the durable copy (864–876, 941–947). "A specification is not a verifying act" (876, 1316).
11. **Quarantine laundering.** Moving the one record that makes the comparison fail makes the comparison spuriously pass, which opens removal (1010–1024).
12. **Location does not confer authority.** `saveRecord` runs `mkdir`, so a wrong path manufactures a new evidence location (571–580). "The record never manufactures authority" (32). Storage location is not domain ownership (1286–1287). Record existence does not establish authority, and physical storage order is not domain-event order (220, 1315).
13. **Identity of record entries.** Grants have no `seq`. `grantId` is not unique: 110 grants but 109 unique IDs, i.e. two distinct authority acts share one ID. Reconciliation is therefore by position plus content and never by ID (1197–1221). Byte copy is required, and canonical re-encoding is allowed for comparison only (688–692, 1165–1170). Hash semantics are SHA-256 of working-tree bytes (609). The `-text` attribute is required, not `eol=lf` (1091–1098).
14. **"Durable" has two senses.** Attestability (versioned) is not crash durability (no `fsync`) (1133–1137). Because the store is unversioned, its historical counts cannot be attested (390–395). Derived state (the fold) is never a second authority source (505, 1289).
15. [I] **Open items and source defects.**
    - Open: a nonexistent governed directory could be exit 65 or a report with exit 0 (534). OPEN-M7: there is no DISPOSE act (233, 1332).
    - Defect: the header status (line 3) still reads "AMD3–AMD6" while the body contains the DV correction and the RV repairs.
    - Defect: 686 says the lane's appends are "handled SAFELY" as strict extensions, but 1193 says a clobbered tail is also a strict extension.
    - Defect: "18 hash comparisons" (1043) hard-codes a count that 101 forbids.
    - The evidence is specification plus self-measurement. Nothing has been executed.

## S0253 — DV-correction independent review (280 lines)
1. **RV-1.** The plan's own Premise 2 says every mechanical write is evidenced by a governance append. Step 3(iii) is such a write, and its evidencing append has no placement (164–191). If placed before the switch, it falls outside the closed enumeration. Phase 7 then sees a delta in the normal case, classifies it POST-DEMOTION and suspends. This is the same degradation relocated a third time (187). The failure direction is safe, but completion is blocked (188).
2. **The comparison object is sufficient for the copy.** It is manifest + reconciled delta + declared pre-switch records, "exactly what the copy must hold at that instant". It subsumes the Phase-4 manifest (66).
3. **The verification is a real act.** It uses byte, hash, count, sequence and provenance comparison plus the exhaustion rule (67). Sequence and provenance are subsumed by byte identity (217).
4. **Markers are outside the compared set** because the predicate is exactly `*.json` (218). This is membership by predicate, used as a premise.
5. **The receipt is evidence about a verification, not an operand of it.** Its authority is limited to its claim: "the comparison was performed and PASS", not "the copy is correct" (145–160). Carrying it inside the switch-over record keeps the boundary discriminator single (156).
6. **DV-3 holds.** "First record in the authoritative store" survives only as labelled history. The live concept is "first governance append after the authority transfer". Record existence does not establish authority, and storage order is not event order (89–107).
7. **After the switch, copy writes are ordinary appends.** Any write into the copy after 3(iii-b) is a normal post-switch governance append, so the DV-1 property is the one delivered (219). On a FAIL, a re-attempt re-measures and builds a fresh enumeration (220, RV-7 at 206).
8. **RV-5: the stated measurement is false but the conclusion holds.** The claim "scripts/ has exactly 2 files" is wrong (there are 13 entries), yet no positional discriminator exists (204).
9. **RV-6.** The same summary gives three irreconcilable counts for one measurement: 26/35, 43/43, 37/37, and 18 tests (205). RV-2: two contradictory current statements of the "byte-verified" claim (201). RV-3 and RV-4 are propagation misses (202–203).
10. **DV-7 checked mechanically.** The set of defined operator IDs equals the set cited, 9 = 9 (119).
11. **Stated limits.** It is judged from the specification, not from execution. A positional discriminator "would pass its own test while encoding the false semantic" (254–255). Reviewer identity is self-declared (257). A pass is evidence, never a release (243).
12. [I] **Lineage.** This is not independent of S0300. S0300 as read already contains the RV-1, RV-2, RV-4 and RV-7 repairs (796, 1043, 1129, 1111), so S0300 postdates S0253. The line numbers S0253 cites (e.g. 794, 1310) belong to an earlier S0300 text (1435 lines, per 8). The two count as one line of support.

## S0917 — Step 25Y, evidence integrity, provenance and custody (1,628 lines)
1. **Integrity ≠ Authenticity ≠ Authority ≠ Truth.** Each is evaluated separately (25–29, 158–176, 1416–1441). Traceability ≠ correctness (1440).
2. **A hash shows integrity, not truth.** If h ≠ h', the content changed (41–67). Fingerprint = content identity, but only under the same algorithm and representation (204–226). The hash must carry its algorithm (230–254).
3. **Raw-byte hash vs canonical hash.** They answer different questions (258–298). Artifact identity ≠ semantic identity (302–314).
4. **Immutable evidence.** A correction is a new event that supersedes the old one, and the original stays retrievable (356–415, 970–982). Invalidation ≠ deletion (729–743).
5. **Merkle roots and hash chains.** A Merkle root is a compact commitment that detects modification, deletion and insertion in a collection (419–443). Hash chains propagate any earlier change forward (447–467). [I] This is the only source in the set that names an outside commitment able to detect deletion.
6. **Chain of custody.** Each transfer becomes provenance (318–352). Custody can be a domain invariant in some contexts (952–966).
7. **Temporal validity of signatures.** Validity is judged at signing time (517–535). Track key ID and version (539–557). Revocation is judged at t_signature (561–579). A trusted timestamp proves existence relative to the timestamping mechanism (583–597).
8. **Provenance graph.** It is traversed backward and forward. Impact = Descendants(E) (601–725). Untraceable conclusions are "orphan knowledge" and get a weaker status (986–1036).
9. **Assess with a vector, not a scalar** (747–851). Evidence must be relevant to the question (855–879). Sufficiency depends on the decision (883–948).
10. **Experiments E and G.** E: replacing an artifact while keeping its database ID is an integrity violation, so ID ≠ content (1284–1294). G: three identical replicas count as one independent source (1320–1332).
11. Integrity ≠ availability (1184–1210). Provenance visibility and content visibility are separate permissions (1158–1180). No blockchain is needed (471–489).
12. **Source defect:** in 25Y.30 "Au = authority" and "Auth = authenticity" are swapped against the order of 25Y.27 (829–849).
13. [I] **What the source does not say.**
    - All "PASS" experiments are thought experiments (1214–1374).
    - It never says where the reference hash h is held, or by whom. A reference held in the same mutable store cannot detect tampering or loss.
    - It does not treat a write lost before any hash was taken.

## S0904 — Step 25L, distributed knowledge, merge and convergence (1,599 lines)
1. **Merge at the history level, then re-derive.** K = Derive(H, Ω, EC, M) (7–9). H_merge = H_A ∪ H_B, then re-derive. This is preferred to merging opaque states (236–270, 1347–1363).
2. **Record identity ≠ meaning identity (KAID).** A merge unifies meaning but keeps both records (145–199). Semantic deduplication must not deduplicate provenance (203–231).
3. **EventID detects duplicates** (274–302). A replicated copy is not independent evidence, so OriginEventID must be kept (306–334). Independence is derived from provenance, not from who owns the evidence (1073–1103).
4. **Order is causal, not wall-clock.** Use logical clocks or version vectors (338–411, 415–428).
5. **Convergence is a candidate theorem.** Same (H, Ω, EC, M) ⇒ equivalent K. It is recorded as a design invariant, and it needs deterministic derivation (480–516, 1367–1383). It is conditional: same inputs and same semantics (1389–1417). A different ontology version gives a ModelVersionDifference, not an inconsistency (1245–1281).
6. **Convergence ≠ consensus.** Nodes may converge on Conflict(A, ¬A) (564–626).
7. **History is monotonic, derived knowledge is not.** Union of histories is commutative, associative and idempotent, a grow-only set (667–709). Derived K can be non-monotonic: retraction, authority, policy (713–754).
8. **Consistent(K) ⇏ Complete(K).** Local consistency ≠ global completeness (840–898). Frontier(K) is the set of events a node knows (902–920). Two different retraction states are not inconsistency, only different histories (784–836).
9. **Contexts.** Context is part of semantic identity, and there is no universal ontology (955–1010). Cross-context mappings are epistemic objects with provenance (1014–1069).
10. **A 10-step merge algorithm**, with dedup at step 2 (1107–1124). Union is O(n), and semantic reconciliation is the expensive part (1130–1154).
11. **Source defect:** "Three possible merged states" (630) lists four.
12. [I] **Tensions with S0300.**
    - The union model assumes immutable events that carry identity. S0300's store rewrites the whole file on each append, and a lost write leaves no event to union (S0300 428–443).
    - Dedup by EventID fails when identifiers collide. Compare S0300's `grantId` collision, where an ID-keyed dedup would silently lose an authority act (S0300 1208–1215).
    - "Consistent ⇏ Complete" says the same thing as S0300's "dense ⇏ complete".
    - The convergence claim is asserted, not proved, and its "PASS" tests are conceptual.

## S1375 — Step 182, "Who owns the truth?" (1,269 lines)
1. **Authority is bounded:** Authority = f(Domain, Proposition, Context, Scope, Time) (13–21, 961–993, 1166–1173). It is not a single number (931–957).
2. **"Single source of truth" has three meanings.** Only "one system is authoritative for a particular fact" is defensible (67–89). Authority is proposition-specific, Authority(Source, P) (93–139).
3. **"The repository records authority; it does not automatically possess authority"** (1027–1039). KnowledgeOS can be authoritative as a record of decision but not as current operational reality (997–1024).
4. **The record is not the world.** A record saying NexusRunning=true means a KnowledgeOS determination under a given time, scope and evidence (606–697). Provenance answers "on what basis do we believe this" (689–719).
5. **Truth is not an ownership object.** Organisations own determinations, records, decisions and responsibilities (28–63).
6. **Roles are separate.** Steward ≠ domain authority ≠ decision authority ≠ operational owner (209–237). The synthesis later lists five: observer, epistemic, decision, accountability, operational (1118–1152). [I] The list grows from four to five inside the same source.
7. **"Owner" should name a relation:** StewardOf, AuthorizedToDecide, AccountableFor and so on (273–347).
8. **Two independent axes,** epistemic and normative, not collapsed into one score (825–885). The authority ladder is a lifecycle, not a ranking (889–929).
9. Authority ⇏ truth (759–793). Evidence ⇏ obligation (797–821). Organisational authority ≠ epistemic evidence (741–755).
10. **Decompose the question.** "Is port 8081 okay?" contains up to six propositions (403–469). Truth is layered: observed, interpreted, determination, decision, authorization, outcome (473–542, 546–580).
11. **Retrieving a decision does not authorize execution.** An AI that retrieves Decision=Approved must still establish that its authorization is valid (1041–1061).
12. **The platform enforces structure, the domain defines meaning** (type-system analogy). Examples of structural invariants: EveryDecisionHasAuthorityBasis, EveryAuthorizationHasScope (1065–1108).
13. Step 183 hypothesis: "reconstructable organizational reasoning" (1196–1268).
14. [I] **Evidence and lineage.** "Confirmed and strengthened" (1158) rests on examples only, with no data. Item 3 matches S0300's "the record never manufactures authority" (S0300 32, 1008), but both are corpus statements with a likely shared vocabulary, so they count as one line of support.

## S2055 — Step 285, canonical state reconciliation (1,155 lines; a research programme, no results)
1. **Blocker: which K is canonical?** K_t (ratified architecture) vs K = (𝒜, ℛ) (formal verification) (14–46). Seven candidate relations (36–42). Models A–F (393–458). "No model shall be selected by intuition" (458).
2. **Dependency chain:** K → ℐ → 𝒪 → δ → kernel. With no canonical K, nothing downstream is canonical (52–91).
3. **Hypothesis:** State ≠ Knowledge about state ≠ Action (153–189). Evidence ≠ State, since Y_t ~ P(Y_t | K_t) and evidence is an observation of the state (321–373).
4. **State vs history.** Candidate replay model K_t = fold(δ, H_t, K_0). Whether history is part of K is open (532–579). "The kernel requires replay" is an unstated premise (534).
5. **Governance act ≠ governance record ≠ state transition** (583–626).
6. **Layered candidate** S_t = (K, E, H, G, Q) (629–661). A five-way classification test: X ⊆ K, X = π_X(K), X ⊥ K, K = π_K(X), or X is a transition/history/governance construct (664–696).
7. **State sufficiency.** Six conditions: evaluate invariants, decide legality, execute, distinguish identities, decide equality, replay (700–730). Information-theoretic test: L(o, K, X) must reduce to L(o, K) unless X is declared external (733–771).
8. **Operation identity.** o ≠ δ(K, o). Two operations with the same result can still differ in provenance and authority (193–317).
9. **"Gita insight ≠ KnowledgeOS theorem."** Mappings are graded ANALOGY / HEURISTIC / DESIGN HYPOTHESIS / FORMAL DERIVATION / GOVERNANCE DECISION. Only the last two are normative (821–857).
10. **Every matrix row is OPEN or DOWNSTREAM** (860–874). Outcomes A–E are listed. Outcome E, "undecidable", means STOP, governance required (974–1016).
11. **Aim:** "the smallest semantically coherent state … containing everything the kernel must know, and nothing that merely happens to surround that knowledge" (1143–1145).
12. [I] **Limits of the source.**
    - It delivers no result. The Gita attributions are paraphrases with no verse cited (97–99, 155).
    - Its classification never separates the record (a representation) from the state it represents. That is the distinction this topic needs, and S1375 4 and S0300 §2/§9 supply it.

## Cross-source table [I]
| Topic | S0300 / S0253 | S0904 | S0917 | S1375 | S2055 |
|---|---|---|---|---|---|
| (a) what "the record" is | `*.json` files at the governed resolved location: transitions with `seq`, grants without (609, 1203) | event history H, append-only set (236–270) | immutable evidence + correction events (356–415) | record of decision ≠ reality (997–1024) | history H_t vs state K, open (532–579) |
| (b) same record for readers and writers | one resolver, writer last; advisory for ad-hoc readers (513, 741, 454) | sync to the same H; convergence is conditional (480–516) | — | authority per proposition (93–139) | — |
| (c) entry belongs to the record | filename predicate + location; content-closed enumeration; location confers no authority (609, 840–846, 578) | EventID dedup + origin (274–334) | content fingerprint; ID ≠ content (204–226, 1284) | repository records authority, does not possess it (1030–1033) | X ⊆ K test (664–696) |
| (d) comparison detects loss | dense ⇏ complete; only relative to the frozen manifest; laundering (443, 1190, 1010) | consistent ⇏ complete (840–898) | Merkle root detects deletion (419–443) | — | — |
| order | content-defined; 2/216 time fields (893) | causal, not wall-clock (379–411) | signing-time validity (517–579) | — | — |

## Contradictions and changes over time [I]
- **Dedup by identity.** S0904 deduplicates by EventID (274–302, 1115). S0300 shows an identifier shared by two distinct acts and forbids ID-keyed dedup (1208–1219).
- **Immutability.** S0917 and S0904 assume immutable, append-only history. S0300's writer rewrites the whole file and can silently lose an append (428–443). "Append-only" there holds only "in practice" (503).
- **Changes inside S0300.** Authority transfer moved from Phase 6 to the Phase-5 writer switch (709–716). "No record dropped" was withdrawn (1185). "First record" became "first governance append" (220). The freeze went from literal to partitioned (634–657). Two markers became three (1052). CASE α/β were renamed (182–190).
- **S0253 → S0300.** S0253's RV findings are already folded into S0300 (796, 1043, 1111, 1129).
- **Inside S1375.** Four "owners" become five questions (209 vs 1118).

## Open questions in the sources
- **S0300:** OPEN-M7, the disposal path, since there is no DISPOSE act (1332). OPEN-M4, unbounded readers (1329). Exit 65 vs exit 0 for a nonexistent governed directory (534). Writes in the measure → switch interval are irreducible without atomicity (905).
- **S0904:** the convergence theorem is unproved.
- **S2055:** every canonical-K question.
- **S1375:** reconstruction (Step 183).

## Files opened
- docs/knowledgeos/knowlegeos_manual_theory_20261018/03-methodology/REVIEWER-INSTRUCTIONS.md
- docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/TURN8-RECORD-IDENTITY/BRIEF-STEP1.md
- docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md (whole)
- docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-DV-CORRECTION-INDEPENDENT-REVIEW.md (whole)
- docs/knowledgeos/brainstorming/phase_measure_theory/20260828-101241_step-025y-evidence-integrity-cryptographic-provenance-authenticity-tamper-detection-and-chain-of.md (whole)
- docs/knowledgeos/brainstorming/phase_measure_theory/20260828-094013_step-025l-distributed-knowledge-merge-convergence-and-consistency.md (whole)
- docs/knowledgeos/brainstorming/phase_measure_theory/20260829-015501_step_182_the-who-owns-the-truth-experiment.md (whole)
- docs/knowledgeos/brainstorming/phase_measure_theory/20260831-173224_step_285_canonical-state-reconciliation.md (whole)

Commands run: one `wc -l` over the six sources. No other searches, no scripts, no files edited.
