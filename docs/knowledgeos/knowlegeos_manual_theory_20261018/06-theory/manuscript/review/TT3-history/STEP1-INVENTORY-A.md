*Reviewer A's (Opus) step-1 inventory, theory turn 3, verbatim (saved by the Professor, 2026-10-19).*

# STEP 1 INVENTORY — TT3 (reviewer A)

Mode: topic review, step 1, done blind. I read S0095, S2782 and S0129 whole, plus `03-methodology/REVIEWER-INSTRUCTIONS.md`. I opened nothing under `06-theory/`, not RESEARCH-PURPOSE.md, and no other corpus file. To check S0095's figures I ran read-only `git log`/`git show --numstat` on the commits it cites (9c93e2f0, e8b3842b, de998173). Independence here means process independence (a separate run, no sight of the Professor's work), not personal independence.

## S0095 — record-integrity disposition (2026-08-19, Governance lane; 4 commits e1db7d2f → 519d7a98)
1. **Criterion for a truthful history:** "A recorded truncation with a recovery pointer is a truthful history. A silently reconstructed one is not." [S0095 L162]. A history may be incomplete and still truthful, as long as the loss is itself recorded.
2. **Restoring history is itself a history edit.** A restoration has to be a new, visible act, never a silent return to an earlier text [S0095 L42, L46, L162]. The PO/ARB later decided "DO NOT restore… Retain the recovery reference" [S0095 L227–229].
3. **Successive acts, not a blended text:** "represent this history as SUCCESSIVE ACTS, not as a blended text" [S0095 L166]. Restored material is appended, dated, attributed to its source commit, and does not bring back its old framing [S0095 L177].
4. **Recording order differs from act order.** A late transcription gets seq 16, after the artifact it authorizes, and "the sequence number was deliberately not manipulated", because putting it at seq 15 "would read as though the START had preceded the report" [S0095 L218–221; also L30]. These are two time axes: occurrence and recording.
5. **The record cannot supply facts about past acts.** "A transcription records a human act that was given… Governance may not supply one" [S0095 L141]. The occurrence had to be attested from outside the record, by the PO/ARB [S0095 L210, L216]. [I] So whether a history is truthful about its acts cannot be decided from inside the record.
6. **A recorded absence is part of history:** "that absence is now part of the governed record rather than a gap in it" [S0095 L149]. The record carries a negative fact.
7. **Current state is a fold of the transition log.** `mutationOwner: None` and the blocked gate are computed from 14–15 transitions. They were probed on a scratch copy, and the outcome was a function of what the history contained [S0095 L13–18, L185–196]. [I] This is direct evidence that, in this engine, current state is derived from the record.
8. **The incompleteness here was a missing record, not a rewrite.** The act happened and was not recorded (X-2) [S0095 L26, L147]. [I] This is the "completeness" direction (every act gets a record), as opposed to the "no rewrite" direction.
9. **History depends on the storage layer.** A file that is meant to be append-only lost 73 lines, but the content survives at the version-control (VCS) layer (e8b3842b) [S0095 L38, L43]. Checked: `9c93e2f0` numstat on the session log is 17 added / 73 deleted.
10. **Replacing vs amending.** The register was replaced rather than amended, so the append-only record and the document now disagree ("REMAINS blocked" vs "out of scope") [S0095 L50, L69]: two divergent histories.
11. **The kind of a past act is open.** "were D-1…D-5 PO/ARB decisions, or recommendations transcribed as decisions?" stays OPEN [S0095 L71, L179]. History has to carry what kind each act was, and that can be disputed later.
12. **Partial supersession:** "The analysis method is superseded — not the direction" [S0095 L99]. Separately, the disposition is "superseded" by a later option A, if the PO/ARB takes it [S0095 L150].
13. **A repeat across instances:** this is the second missing-START at a "Verification #3" slot [S0095 L26, L34, L223]. That makes n=2, which is a pattern but not a population.
- **Source defect:** "79 insertions against 166 deletions" [S0095 L50] does not reproduce. For `9c93e2f0` on the register, numstat gives 62/93 with the default diff algorithm and 79/110 with histogram; the file went from 136 to 105 lines. The other parts of that claim (this commit, the replacement) hold.

## S0129 — durability-ADR position registration (2026-08-19 evening, processes 5e1dd9ee and b64828fe; commits 4f8db9b0, 5a598876)
1. **Two questions that must not be merged:** "what is happening now?" vs "what happened, who authorized it, can we reconstruct it?"; Execution Context ≠ Governance Evidence Context [S0129 L47, L194–197].
2. **"An aggregate cannot depend on accidental reconstruction"** [S0129 L44, L187]. The same idea appears later as "History ≠ Reconstruction Guess" [S0129 L102].
3. **R-CONFLICT conditions:** a resolution has to keep sequence integrity, provenance and the ability to reconstruct, and must not "choose one side silently · delete one history · rewrite sequence" [S0129 L45]. It was recommended for adoption ("without this rule, append-only governance history has no protection") [S0129 L102], but is **NOT SELECTED** [S0129 L158, L211].
4. **A dated instance of history loss:** in de998173, 16 narrative documents were committed while "all 28 grants and 33 transitions" were gitignored: "The documents survived; the record they register against did not" [S0129 L187]. [I] One event. It shows that narrative can survive the loss of the record, so rebuilding from narrative would be guessing.
5. **The records persist no derived state** (ADR O-3) [S0129 L60]. The authority record is the original, "never runtime's to export" [S0129 L59, L101]. [I] This supports "record = primitive, state = derived".
6. **Option A rejected:** it makes execution location equal authority location, so a "runtime cleanup accidentally deletes history" [S0129 L43].
7. **Four cases of non-destructive correction, all on one work item** [S0129 L188]: something clarified rather than "fixed" (no transition appended); a correction and its erratum both kept; an act kept while its proposition was superseded; findings kept while only their assurance was refused. These are cases of retraction or supersession without deletion.
8. **The record registers acts by reference:** "the record never manufactures authority" (humanActRef, G-2) [S0129 L147]. This is the same external-attestation point as S0095 #5.
9. **Position vs decision for a past utterance:** "I would select B′" was read provisionally, then withdrawn by its author [S0129 L25–34, L87]. This is the same act-kind problem as S0095 #11.
10. **Additive registration surface:** "Appended… Nothing above rewritten" [S0129 L138]; the amendment keeps everything above it [S0129 L83].
11. **Rule versioning (weak):** "ADR placement continues under existing placement governance". An interpretation was recorded instead of a new rule [S0129 L166–179]. [I] Rules change by interpretation acts too, not only by new versions.

## S2782 — "Part XIX" persistence as semantic preservation (AI-generated chat text, L1 "Yes. I'll make Part XIX…"; entered 2026-09-06 via 6f38df52, renamed in 71f810f8)
1. **Current(K_t) ≠ History(K_t)**. Overwriting established→retracted loses information; K_t→K_{t+1} must stay reconstructible [S2782 L823–859].
2. **Append-only defined as H_{t+1} = H_t ∪ {e_{t+1}}** [S2782 L868]. [I] **Defect:** a set union is unordered and merges duplicates. This conflicts with the source's own points on ordering [S2782 L2126–2153] and duplicate delivery [S2782 L2074–2120]. H has to be a sequence, or a partial order with identity.
3. **Append-only does not imply correct.** An append-only log can still hold wrong or duplicate events, missing provenance, wrong order and invalid transitions [S2782 L878–891].
4. **Event sourcing is "a candidate implementation strategy, not a theorem"** [S2782 L897–923]. The source explicitly leaves open whether history or state is the primitive.
5. **Replay needs version dependencies:** Replay_V4(H) ≠ Replay_V3(H). So it is Replay(H, V_rules, V_models, V_contracts, V_schemas) [S2782 L927–969], with a version vector [S2782 L1788–1807]. [I] State history cannot be derived from the act record alone.
6. **A time-indexed status removes the apparent contradiction:** Determined(p,t1) and Retracted(p,t2) can both hold. "The historical state remains valid as a historical statement" [S2782 L975–1017].
7. **DELETE ≠ RETRACT; soft delete ≠ retraction** [S2782 L1021–1079]. Technical deletion is allowed under a separate retention contract [S2782 L1047, L1873–1905]. [I] So history may be lossy by contract, and truthfulness then needs a recorded loss (compare S0095 #1).
8. **Several time axes:** seven are named (occurrence, observation, recording, publication, acquisition, decision, validity) [S2782 L707–740]. Bitemporal VT/TT is "useful minimum" [S2782 L744–791] but "not universally sufficient" [S2782 L795–819].
9. **Ordering:** wall-clock order ≠ causal order; logical clocks order events without saying anything about physical time [S2782 L2124–2182]. Operations do not commute in general [S2782 L2031–2039].
10. **Theorems 19.1 and 19.3 are conditional tautologies.** Each says: if everything needed for reconstruction/replay is stored, reconstruction/replay is possible [S2782 L2966–3006, L3036–3054]. [I] These are sketches, not proofs. 19.3 also quietly assumes δ is deterministic.
11. **"Persist what must be remembered; derive what can be reconstructed; preserve the provenance of what is derived"** [S2782 L3559]. Derived ≠ disposable: a derived object that cannot be regenerated becomes canonical [S2782 L3372–3379].
12. **History and provenance treated as separate layers** (D_history, D_provenance) [S2782 L3298–3346]. Yet Supersedes is listed as a *provenance* edge [S2782 L645]. [I] The boundary between history and provenance is not drawn.
13. **"Restoring historical events" listed as a repair** [S2782 L2948]; "Repair ≠ GuaranteedTruth" [S2782 L2954]. [I] This is in tension with S0095 #2, where a restoration is itself an act and must be visible.
14. **Neighbouring distinctions:** a backup is not complete history; system recovery ≠ knowledge recovery [S2782 L3156–3201]; recorded corruption "prevents silent rewriting of history" [S2782 L3130–3152]; a representation can be immutable while its meaning or status changes [S2782 L1981–2003].
15. **Knowledge Storage = Data + Semantics + Provenance + Time + History + Contracts + Identity** [S2782 L3730–3745]. History is listed as a component separate from Time and Provenance, but that separateness is only asserted, never shown. The source also lists "who or what authorized the transition" among the questions memory must answer [S2782 L3509].

## Cross-source table

| Point | S0095 | S0129 | S2782 |
|---|---|---|---|
| State = fold of record | observed (engine probe) L13,L185 | O-3 "no derived state" L60 | candidate only L923; needs versions L962 |
| Correction or retraction ≠ deletion | additive restoration L166–177 | 4 cases L188 | DELETE≠RETRACT L1041 |
| Recording order ≠ act order | seq 16 not 15 L221 | "sequence integrity" L45 | TT vs VT, logical clocks L760, L2180 |
| Recorded loss is truthful | L162 | — | retention contract L1887 |
| Restoration as repair | an act that must itself be visible L46 | must not "rewrite sequence" L45 | "restoring historical events" as repair L2948 (tension) |
| External attestation of acts | L141, L216 | humanActRef L147 | — |
| Act kind disputable later | D-1…D-5 L71 | B′ position/decision L87 | — |

## State lineage [I]
- **S0095 and S0129** come from the same estate, the same day (2026-08-19, 09:20–20:58), the same committer, and governance-lane AI sessions with self-declared, non-attestable identities [S0129 L4, L137]. They share vocabulary (append-only, Inv C, G-2/G-3, PO/ARB, ES-004.3). **They count as one line of support.** That line is observational: it cites commits and seq numbers I could check, and the 73-line deletion and de998173 are real. But the events are few: two missing STARTs, one truncation, one gitignore loss, four correction cases on one work item. The "principles" in it (R-CONFLICT, "History ≠ Reconstruction Guess") are recommendations, NOT adopted.
- **S2782** is a separate line: externally generated, generic theory drawn from the literature (bitemporal databases, event sourcing, Lamport clocks, AGM-style retraction). It came 2½ weeks later, uses none of the estate's vocabulary, and contains no observations. **It is a second line, but a conceptual one.** Where it agrees with S0095/S0129 (retraction ≠ deletion, two time axes), it agrees with well-known database practice, which says little about this corpus in particular.
- **Total: two lines of support, neither of them a population.** Nothing in these three sources tests whether history is a primitive. S2782 explicitly declines to decide it [S2782 L923].

## My sketch: a definition of history and an independence test [I]
**Definition (candidate).** A history is H = (E, <_rec, ι, λ, τ):
- E is a set of identified act-records (an identity ι, so duplicates are not merged);
- <_rec is a total recording order per store (seq);
- λ labels each record with an act kind (decide, recommend, transcribe, retract, supersede, restore, absence-notice) and typed links to earlier records;
- τ gives optional occurrence/validity times, which may disagree with <_rec.

Laws:
- **(H1) Monotone growth:** H_t is a prefix of H_{t'} under <_rec.
- **(H2) Corrections are new records:** no record is changed; corrections, retractions and restorations are new records that link back.
- **(H3) Soundness:** every record refers to an attested act. "The record never manufactures."
- **(H4) Completeness or marked absence:** every effective transition has a record, or a recorded absence/loss notice.
- **(H5) Rules are versioned:** derived state is K_t = fold(δ_V, K_0, H_t), with δ indexed by versioned rules.

**Must-do vs must-never.** An empty history, or a store that refuses every write, satisfies H1, H2 and H3 trivially. H4 is the "must-do" requirement, and X-2 is exactly an H4 failure. Truthfulness = H3 ∧ H4. Neither can be checked from inside the record alone: both need an external act set (S0095 #5).

**Derivability claims to test:**
- (a) **State is derivable from history; history is not derivable from state.** δ is not injective: retraction and deletion can produce the same current view. So the sequence of states K_0…K_t loses the acts. A small finite model should be enough to show this.
- (b) **State history (what was believed at t) is derivable from H plus V, but not from H alone** (S2782 L941–969).

**Independence models** (in each, one property fails while the others hold):
- **M1 — overwrite store with full provenance and timestamps on current objects only.** Provenance and time hold; history fails. So history ≠ provenance + time.
- **M2 — append-only log replayed under the current rule version only.** Append-only holds; the as-believed history fails. So rule versioning is independent of append-only.
- **M3 — bitemporal object store with no act records.** As-of-TT queries work; "which act, who authorized it" fails. So bitemporal storage gives state history, not act history.
- **M4 — late transcription inserted by occurrence time.** The order is valid by occurrence time τ, but the recording order is lost, so <_rec and τ are independent.
- **M5 — a file truncated while VCS keeps the lines.** Append-only fails at layer 1 and holds at layer 2. So "history" has to be stated relative to a storage layer.
- **M6 — supersession links only.** These give a per-object partial order of versions, but no global order across objects, no causing act, and no absence notices. So supersession links alone do not yield history.

**Undecided:**
- whether <_rec is a primitive, or derivable from causal links (logical clocks);
- whether H4 belongs to the kernel or to an application contract (S2782's retention contract makes history contract-relative).

**Limits:** I did not read the Professor's §9.8 or any other corpus file. Where the sources cite engine probe outputs or the ARB's words, I took them as quoted; I did not re-run any probe.
