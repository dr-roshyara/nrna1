*Reviewer B's (Sonnet) step-1 inventory, theory turn 3, verbatim (saved by the Professor, 2026-10-19).*

# STEP 1 INVENTORY — TT3 (reviewer B)

Process independence only. I read S0095, S0129 and S2782 whole, and the reviewer instructions. I did not open anything under 06-theory/, RESEARCH-PURPOSE.md, or any other corpus file. I produced no files.

Source line anchors use the form [S0095 Lnn], [S0129 Lnn], [S2782 Lnn]. "S0095" is KOS-AIP04-DISCOVERY-001, "S0129" is GOV-STATE-DURABILITY, and "S2782" is theory-part-19. Judgments of mine are marked [I].

## 1. Ranked inventory (most important to the topic first)

**1. History is separated from current state, and history is required to be reconstructible.** [S2782 L823-L859]
- Current(K_t) and History(K_t) are named as distinct.
- Overwriting `status=established` with `status=retracted` is rejected if the prior state is needed. The transition K_t→K_{t+1} "must remain reconstructible".
- The requirement is conditional: "if the previous state is required for reconstruction" [L837-L849]. [I] So history is contract-relative, not unconditional.
- The same split appears as memory = ⟨Current, Historical, Provenance, Semantic, Operational⟩ [L3474-L3511].
- It is stated at the end as Knowledge Storage = Data+Semantics+Provenance+Time+History+Contracts+Identity [L3729-L3745].
- History is listed as a separate term from Provenance and Time in Preserve(P,History) [L186-L188] and in the sum at [L3696-L3707]. Whether it is an independent term is asserted by listing, not argued. [I]

**2. Append-only log and replay (the fold).**
- H_{t+1}=H_t∪{e_{t+1}} [S2782 L863-L871].
- K_t=Replay(E_1..E_t) [S2782 L899-L902].
- Event sourcing is explicitly "a candidate implementation strategy, not a theorem" [S2782 L917-L923].
- Append-only ≠ automatically correct: the log can hold wrong, duplicate, mis-ordered or invalid events [S2782 L875-L891].
- Theorem 19.3: if the transition and "all semantic dependencies required for replay" are stored, then Replay(H_t,V_t)≡_Γ K_t [S2782 L3036-L3054].
- [I] The theorem is close to tautological. The hypothesis "all dependencies required for replay are stored" assumes what is to be shown. It is a sketch, not a proof.
- [I] The set notation H∪{e} drops order, while L899 uses a sequence. This is an unmarked inconsistency inside S2782.

**3. Replay depends on rule and model versions.**
- Replaying E_7 under rule v4 instead of v3 can give K'_t≠K_t without the original being wrong [S2782 L927-L971].
- Replay therefore needs (H_t, V_rules, V_models, V_contracts, V_schemas) [S2782 L959-L963].
- The object version vector is V_x=⟨schema, semantic, contract, rule, model, policy, identity⟩ [S2782 L1788-L1807].
- Contract persistence: a historical determination cannot be understood without EC's version [S2782 L1760-L1784].
- [I] This is the core derivability problem. History is not derivable from "log + fold" alone. It needs the fold function's own history (versioned rules). That is a regress: the history of the rules is another history.

**4. Retraction is not deletion; soft delete is not retraction.**
- Determined(p,t1) and Retracted(p,t2) are both true without contradiction, with Status indexed by time [S2782 L973-L1017].
- DELETE destroys the representation; RETRACT changes epistemic standing and keeps historical existence [S2782 L1021-L1049].
- Soft delete is a storage notion and retraction an epistemic one [S2782 L1053-L1079].
- Deletion policy ≠ epistemic policy; a RetentionContract mediates [S2782 L1873-L1905].
- Fitness function F8, "retraction does not silently become deletion" [S2782 L3284-L3286]. Constitutional statement XIX-C8 [S2782 L3598-L3600].
- [S2782 L3156-L3177] A backup is not complete knowledge history. [S2782 L3181-L3201] System recovery ≠ knowledge recovery.

**5. Several time axes.**
- Occurrence, observation, recording, publication, acquisition, decision and validity time [S2782 L703-L742].
- Bitemporal valid time VT and transaction/knowledge time TT give AsOf_valid ≠ AsOf_knowledge [S2782 L744-L791].
- Two time dimensions are not a complete temporal ontology [S2782 L795-L821].
- [I] "History" in S2782 is TT-indexed (what the system held). "What was true at t" is VT-indexed. The text does not say which one "History" means. Part of the topic question is that ambiguity.

**6. Ordering is not wall-clock.**
- Wall-clock order ≠ causal order [S2782 L2124-L2156]. Logical clocks give happened-before, which is not physical time [S2782 L2158-L2182].
- Concurrency: the operator δ is non-commutative [S2782 L2007-L2039]. Idempotency is per-operation [S2782 L2043-L2070].
- Duplicate event delivery ≠ two domain events; deduplication needs an identity contract [S2782 L2074-L2120].
- [I] Time is therefore not what orders history. If order is primitive, it is not derivable from time. [S0095 L218-L221] is the empirical instance (item 8).

**7. Truthful history, record-level (the operational line).**
- A truncation that is recorded, with a recovery pointer, is a truthful history. A silently reconstructed one is not [S0095 L162]. "Restoring append-only history is itself a history edit" [S0095 L46, L162].
- "History ≠ Reconstruction Guess" is a new principle placed beside "Recording ≠ Asserting" and "Reference ≠ Ownership" [S0129 L103].
- "An aggregate cannot depend on accidental reconstruction" [S0129 L44].
- Authority record: option C (reconstruct from narrative documents) was rejected as the final model. The 16 missing amendments were "authorization changes, constraint changes, routing changes, governance corrections" [S0129 L44].
- R-CONFLICT: a resolution must preserve sequence integrity, provenance and reconstruction ability. It must not choose one side silently, delete one history, or rewrite sequence [S0129 L45]. The invariant is also stated as "Without this rule, append-only governance history has no protection" [S0129 L102].
- Dated first-hand failure: commit de998173 versioned the narrative lineage (16 review documents) while the 28 grants and 33 transitions stayed outside git (gitignored) [S0129 L187].
- [I] This is the only empirical instance in the three sources of "the record of what happened was lost while narrative survived". It shows history needs a durable, authoritative carrier. It does not show history is a primitive.

**8. A transcription later than the thing it authorizes (order of record ≠ order of occurrence).**
- The missing START was recorded at seq 16, "later than the artifact it authorizes — the anomaly is visible in the record rather than smoothed out" [S0095 L218-L221].
- "The sequence number was deliberately not manipulated" [S0095 L221]. The transition declares itself a transcription; seq 1–15 are untouched [S0095 L216-L219].
- The earlier determination: absence of the START is "now part of the governed record rather than a gap in it" [S0095 L149].
- [I] Observed fact: two orders exist, record sequence (seq) and occurrence order, and they can disagree. The log encodes this disagreement honestly. This is an empirical counterexample to "history = log order = time order". If ordering is derived from the log's append order, occurrence order is not derivable from it.

**9. Restoration as an act, and history as successive acts, not a blended text.**
- B(1) restoration "is itself visible as an act" [S0095 L42].
- "The authoritative record must represent this history as SUCCESSIVE ACTS, not as a blended text" [S0095 L166]. RESTORATION R1 was appended, dated and attributed to its source commit, and "does NOT reinstate the OPEN framing" [S0095 L168-L177].
- C(1) was chosen as "restore, do not merge". The replacing document had replaced the register (79 insertions against 166 deletions) rather than amending it [S0095 L50].
- Four D-1…D-5 omissions included a finding that the replacement's own premise check P-b corroborates [S0095 L56, L174].
- [I] Supersession here is additive. The old reading is retained and the new reading is a later act. The two readings (OPEN, DECIDED) are both kept as historical statements (cf. S2782 L1017). This agrees with S2782 on retraction. It is operational evidence, not independent theory.

**10. Retained error and erratum (retention rather than correction).**
- Four instances of the practice of not "fixing" history: S5 HANDED_OFF clarified rather than changed; CORRECTION 1 and its ERRATUM both retained, the false one not deleted; Act A retained as historical while its proposition was superseded; ca6039a8 findings left intact [S0129 L188].
- Governance adopts this as codifying existing practice, and calls adoption "the legitimate promotion path" (ES-006.1).
- [I] This is the only place where "history" is treated as an invariant that existing practice already obeys. The evidence is four instances on one work item, one lane, one author line.

**11. History carries authority (who was entitled to write the act).**
- Chain: Architecture proposal → Principal Architect recommendation → PO/ARB decision → Governance registration [S0129 L93-L94].
- Recording ≠ asserting: a process that both decides and registers would have humanActRef point at its own output; the engine refuses this [S0129 L147-L149].
- [I] This bears on "what counts as a truthful history". An act in the log is truthful only if the actor could author it. The attribution of the recording process is "self-declared, not attestable" (INV-ATTR) [S0129 L4]. A recorded act therefore has weaker evidential standing than the log format suggests.
- Position vs decision [S0129 L28-L34, L89]: an utterance ("I would select B′") is not itself the performative act. Which entry in a log counts as a decision is not settled by the log's content.

**12. Event vs transition identity.**
- Duplicate message ≠ duplicate fact [S2782 L2074-L2120]. S2782 rejects "primary key = domain identity" [S2782 L455-L461]. Hash/content identity ≠ domain identity [S2782 L1946-L1979].
- Immutability has five senses: bytes, DB records, domain facts, historical events, semantic claims. ImmutableRepresentation ≠ ImmutableMeaning [S2782 L1981-L2003]. [I] Which immutability "history" claims is open.

**13. Corruption and repair are part of history.**
- CorruptionDetected is kept as a system event [S2782 L3130-L3153].
- Repair_P reduces the gap Δ_P, but repair is not guaranteed truth [S2782 L2925-L2962].
- [I] This echoes S0095's refusal to repair silently, as a general principle.

**14. Provenance vs audit log, and typed supersession.**
- Audit ⊆ potential provenance, but audit ≠ provenance [S2782 L660-L699].
- The provenance graph has typed edges, and "Supersedes" is one edge type [S2782 L599-L656]. [I] This supports "supersession link" as a candidate derived-history ingredient. But supersession links are the edges and not by themselves the history, because they are not ordered and do not carry rule versions.

**15. Re-evaluation and reproducibility + revisability.** Preserving sources and provenance lets determinations be revised while history stays intact [S2782 L3432-L3472]. The Reconstruction Theorem 19.1 [L2966-L3006] is again conditional: "if persistence preserves all required ..."

**16. Open questions stated in the sources.**
- Was D-1…D-5 a decision or a recommendation? OPEN [S0095 L71].
- R-CONFLICT: adopt, reject or defer? OPEN. D1 B′ vs A/C: not selected. [S0129 L215]
- Placement of the rule is decided by existing governance, and any insufficiency goes to a new work item [S0129 L164-L179].
- C-10 existence, category and ownership are untouched [S0095 L97, L223; S0129 L203].
- [I] None of these bears on the primitive question directly.

## 2. Cross-source table

| Topic | S0095 | S0129 | S2782 |
|---|---|---|---|
| History ≠ current state | implicit: current text vs deleted lines | "what is happening now?" vs "what happened, who authorized it, can we reconstruct it?" (two aggregates) [L47] | explicit Current vs History [L823-L859] |
| Append-only | session logs append-only (ES-004.3) [L38] | "append-only governance history has no protection" without R-CONFLICT [L102] | H∪{e}; correct ≠ append-only [L863-L891] |
| Truncation / loss | recorded truncation + pointer = truthful [L162] | gitignored records lost, narrative survived [L187] | CorruptionDetected as event [L3130] |
| Order | seq 16 later than artifact [L218-L221] | "sequence integrity" [L45] | wall-clock ≠ causal [L2124] |
| Retraction | superseded readings retained [L168-L177] | errata retained [L188] | RETRACT ≠ DELETE [L1021] |
| Rules over time | not present | not present | replay under v3 vs v4 [L927-L971] |
| Reconstruction | recovery commit e8b3842b [L38] | "accidental reconstruction" rejected [L44] | Rec_EC theorem [L2966] |

## 3. State-lineage note

- **S0095**: dated 2026-08-19 [L3]. Author: Governance lane, an AI session (self-declared, not attestable), on a PO/ARB commission. It has an original brief, Amendment A1 and a Disposition. The PO/ARB acts are quoted verbatim at L141, L210, L227. It carries a process-record style. It is a direct observation of an incident (commits 9c93e2f0, e8b3842b).
- **S0129**: dated 2026-08-19, three appended acts. Recording processes are claude-code-session:5e1dd9ee (which authored the ADR 67a8e75e) and then b64828fe [S0129 L4, L137]. Both are self-declared. It quotes the PO/ARB verbatim. The principle "History ≠ Reconstruction Guess" originates in the PO/ARB act and is not an independent derivation.
- **S2782**: file named 20260906-070417 (2026-09-06, about 18 days later), opening "Yes. I'll make Part XIX substantially more detailed…" [L1]. This is a chat-style AI continuation of an earlier theory series (Part XVIII precedes it [L11]). The author is unnamed. It is a general theory, not tied to any repository incident.
- **Independent lines of support**: two. (1) S0095 and S0129 are one line. They share the same date, estate vocabulary (ES-004.3, G-3, R-34, INV-ATTR, seq numbering), the same governance process and the same incident cluster (commits e8b3842b, de998173). S0129 repeats the append-only principle. [I] Their agreement on "do not rewrite history" is the same practice described twice and not independent confirmation. (2) S2782 is separate, a general-theory AI chat. It does not cite the incidents, and they do not cite it. I found no cross-citation. Whether the 2026-09-06 chat was exposed to the 2026-08-19 vocabulary cannot be established from these three sources.
- **Direction of influence**: nothing in S0095 or S0129 derives history as a primitive. Both treat "append-only" as a given rule. S2782 treats history as required, but only contract-relatively.
- **Strength of the evidence**: the three sources agree on practice (retain, do not overwrite). They do not argue primitivity.

## 4. Sketch of a precise definition of history and an independence test [I]

**Candidate definitions.** Let a state space K and an operator δ: K×O→K.
- **(H1) State-trace history.** History(K_t) = the sequence ⟨(K_0,o_1,K_1),…,(K_{t-1},o_t,K_t)⟩ with an order relation. This is what S2782 L837-L871 and L899-L902 describe.
- **(H2) Record history.** A log L of act records a_i = ⟨actor, authority, content, occurrence-time?, record-seq⟩, where state is Fold_ρ(L) for a rule set ρ. S0095 and S0129 describe this, with the extra requirement that the log carries authorship and authority.
- **(H3) Epistemic history.** TT-indexed: Status_t(p) for all t. This is the S2782 L1005-L1017 reading.

**Three things that must be kept apart before the question is well-posed.**
1. The log (a record artifact).
2. The fold or replay function, including its versions [S2782 L927-L971].
3. The sequence of states.
Claim "history is derivable" is true for H1 given H2 and a fixed ρ. It is a claim about the log, not about history.

**Dimensions of what could be primitive.** A candidate primitive "history" can be any of: (a) the record order, (b) the occurrence order, (c) time-indexing of status, (d) the append-only constraint, (e) the retention requirement (nothing is silently removed), (f) the authored act as unit.

**Independence test, using the instructions' method: find a model where one candidate alone fails while the others hold.**
- **M1 (current state + supersession links + time, no log).** Two logs with different intermediate states can fold to the same K_t. The fold is non-injective, so no function of K_t alone, supersedes pointers, or time stamps recovers the intermediate states unless the links name them. Design a model where the links point at deleted nodes. This shows history is not derivable from state, which is not a surprise.
- **M2 (log without order).** H∪{e} as a set with timestamps from unsynchronized clocks, with no happened-before. Fold is undefined or ambiguous (δ is non-commutative [S2782 L2031-L2039]). This tests whether order is derivable from time. It is not, by S2782 L2124-L2156 and the seq-16 instance [S0095 L218-L221].
- **M3 (ordered log, no rule versions).** The fold under v3 vs v4 differs [S2782 L941-L971]. This tests whether ρ-history is derivable from the log. It is not unless rule changes are themselves logged acts, which is the regress noted in item 3.
- **M4 (log with silent truncation).** A log that is append-only by form but a prefix was removed and not recorded. Truthfulness fails while append-only holds. The truncation event must be logged (S0095 L162 and its recovery pointer). This tests whether "append-only" suffices for "truthful". It does not. The extra ingredient is that truncation, restoration and transcription are acts in the log (a reflexive requirement).
- **M5 (log that records occurrence order disagreeing with record order).** The seq-16 case. It separates record-seq from occurrence-order, so two order relations are needed unless occurrence order is itself recorded as data (a field).
- **M6 (forged or unattestable actor).** Log intact but authorship self-declared [S0129 L4]. A truthful-history property that includes "who entitled the act" is not implied by the other properties.
- **M7 (the trivial models).** Empty log (no acts): consistent, history trivially empty. A log that refuses everything: fold stays at K_0. The model is satisfiable without any must-do requirement. This is the instructions' trivial-solution test. [I] "Append-only" is a must-never constraint only. A must-do (every state change is recorded) is needed beside it.

**Decision rule.** "History is a primitive" would be supported only if each of M1–M6 shows a failure that cannot be repaired by adding only {append-only log, fold with versioned ρ, supersession links, time}. [I] M1–M3 are repaired by the log; M4 and M5 by logging the meta-acts and a second order field; M6 requires authority attestation, which is not in that list. So my tentative reading is that "history" splits into (i) a record (derivable as a fold ingredient) and (ii) a truthfulness condition (record completeness + order + authority), and that only the second contains anything not already in "append-only record + time". This is a hypothesis, not a result, and it is what step 2 should test.

**Open points a definition must settle.**
- Which time axis indexes history (VT or TT)?
- Whether an act (not a state) is the unit.
- Whether a reflexive clause is needed (acts on the history are in the history).
- Whether history is contract-relative [S2782 L837-L849], which would make it relative and not primitive.
- Whether the rule history needs its own log (regress).

## 5. Caveats on the sources [I]
- The S2782 theorems (19.1, 19.3, 19.4) are conditional restatements and are sketches. No derivation is given.
- The "evidence" in S0095 and S0129 is a single incident cluster by one process line. Where S0129 L188 counts four instances, all are on one work item.
- S0095 L112-L120 itself flags an external research artifact as unregistered and not independent evidence ("agreement with an unread source is not independent evidence when authorship/exposure cannot be attested"). The same standard applies to the relation between S0095/S0129 and S2782.
- Source slip: S0095 L81 says "twelve of its fifteen parts" are DDD analysis; not checkable here and not relied on.
- Search misses (for example, no "fold" or "supersession" definition in S0095/S0129 beyond the passages quoted) are not evidence of absence elsewhere in the corpus.
