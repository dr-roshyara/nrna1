*Reviewer A's (Opus) steps 2–3 report, theory turn 3, verbatim (saved by the Professor, 2026-10-19).*

# REVIEW REPORT — TT3 (reviewer A)

*Topic review of §9.8, STEP8-AND-SCAN, CON-0148 and CON-0149, plus the changed theory of the C15 settlement (commit 39612b8d4). I re-read my saved step-1 inventory first. Independence here is process independence, not personal independence. Because there are three objects of review, the report runs past about 1,200 words; lower-priority points are in the appendix.*

## 1. Assessment
- **What holds:** the decomposition D holds as a conditional sketch: under H1–H3 and a total CE, Hist is a function of the record. §9.8 quotes its sources accurately (11 of 11 checked).
- **What does not hold:** the two headline results. The book's own definitions of authority and policy defeat "independent of the six other candidates". The step from M1–M3 to "history is not a primitive" reverses §12b: models in which history fails while the six hold are independence models *for* history.
- **Largest risk:** BOOK-OUTLINE replaces "History" with "the record" on inverted logic, while the must-do side of a truthful history (soundness, and completeness or a recorded absence) stays unstated.

## 2. Findings (ranked)

| # | Sev | Type | Claim | Line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | **critical** | defect | "So 'history' is not a primitive in the §12b sense" (result 3; CON-0148; plain language L18) | §9.8.8-3; RULEBOOK §12b(a) | §12b: a concept is derivable when no model exists in which it alone fails while the other primitives hold. M1–M3 are exactly such models for Hist relative to the six. D derives Hist from H1–H3 + CE, and none of these is on the list. That shows history is **not atomic** (it decomposes). It does not show history is **derivable from the other primitives**. | The turn's main conclusion is not supported. | Restate: "Hist decomposes into H1–H3 + CE (conditional). Its status relative to the six is undecided (finding 2)." Replace "history" in the list only once "the record" has its own independence model. |
| 2 | **critical** | defect | Result 2: H1–H3 are "independent of the six other candidates"; M1–M3 "keep them" | §9.8.5 L53, L57, L62, L67; §9.8.7 bullet 2 | Under the book's own definitions none of the three models keeps all six: **M1** breaks authority as §1.2 defines it ("a claim's history is valid iff … every change is labelled by a recorded act", §1.2 L53), and BOOK-OUTLINE lists §1.2 under Authority. **M2** breaks authority's time index: §1.16.2 "valid at the time of the act", and §1.6's act × scope × time (§9.7.6 C1). **M3** needs θ to change without a recorded act, which contradicts policy as §2.11 RM-3 defines it ("changing an authoritative rule requires explicit authorization"). §9.8.7's boundary case 2 is therefore the actual case, not a hypothetical. "Authority is not one primitive either" does not follow: it may only mean that authority includes the record. | Independence from the six is unshown. History may be a component of authority and of policy. | Re-run M1–M3 against §1.2, §1.16.2 and §2.11. Report which of the six fails in each model. |
| 3 | major | defect | H3 independent of H1, H2 | §9.8.3 H3; M3 | If θ and CE's definition are governed objects (CON-0142, §2.11), then their versions are acts. H1 keeps those acts, so H3 follows from H1 + A1-for-rules. Finding "the θ in force at s" is then Hist applied to policy items. That makes D **recursive** (Hist needs θ(s), which needs Hist under θ′…). It is well-founded only with a fixed meta-evaluator or root. | H3 may be derivable, and D has a hidden regress. | State: "H3 is independent only if θ lies outside the record". Otherwise derive H3 from H1 and settle the regress. |
| 4 | major | omission | H1–H3 have no must-do | S0095 L141, L147, L149; S0129 L147; §1.16.8 | An empty record, or a record missing an act, satisfies H1–H3 and gives a well-defined but false Hist. X-2 is exactly that case: the report exists and no START was recorded. Hist is defined "as the record stood at s", so D shows that a record-relative quantity is a function of the record: close to a tautology. **Soundness** (each entry reports an attested act: "the record never manufactures") and **completeness or marked absence** ("that absence is now part of the governed record") are missing. Their home is §1.16.8, "absence must be visible". | The trivial-solution test fails. A *truthful* history (S0095 L162) is not captured. | Add H4 (soundness relative to acts, attested outside the record) and H5 (every effective act has an entry, or a recorded absence). Note that neither can be checked from inside the record. |
| 5 | major | defect | H1: "R≤s recoverable … No entry is removed or changed" | §9.8.3 | (a) Two conditions are merged: *recoverable* (information) and *never removed* (operation). The git case meets the first and not the second. (b) Deletion required by law or retention (S2782 L1047, L1873–1905) is a counterexample to "never removed" as a must-never. (c) S0095 L162: "a recorded truncation with a recovery pointer is a truthful history". | As stated, H1 is too strong for the kernel and is a homonym. | H1′: every change to the record is itself an entry. A removal happens only by a recorded act, with a pointer or a recorded loss. |
| 6 | major | contradiction | Result 4: "The estate keeps H1 at the level of git" | S0129 L187 | In de998173, "all 28 grants and 33 transitions remained outside git" (gitignored, `.gitignore:25`, `:32`): "The documents survived; the record they register against did not." | A generalization from one file is contradicted by a source that this turn itself selected. | "The session log's lines survived at the git level. The workflow record was outside git on 2026-08-19." |
| 7 | major | defect | D: Hist = CE(c, t; θ(s), R≤s) | §9.8.4 | (a) The choice of θ(s), rather than θ(t_x) or θ(now), settles §9.7 step 4's contract-time question and F27 by stipulation. (b) Step 2 needs F26 decided; that is a premise, not a "tie broken as F26 decides". (c) The scope x is on the left side and missing on the right. | D's conditionality is understated. | List the premises: CE total, F26 settled, θ-index stipulated (Hyp). Add x to CE. |
| 8 | major | defect | M2 breaks H2 only | §9.8.5 M2 | H3 is stated "for every s", over times. With no times, H3 cannot be stated at all. The model also removes τ and t_x together, so it shows that the conjunction H2 is independent, not each order separately. | Independence of H2 from H3 is not shown. | Restate H3 per cut, by position. Add a model with τ and no t_x. |
| 9 | major | omission | Hist as the whole content of "history" | S0129 L47; S2782 L3509 | "What happened, who authorized it" is outside Hist. A bitemporal store of computed standings answers Hist with no act record and no H3. Only CON-0134 ("derived state never stored") rules it out, and that premise is unstated. So D gives sufficiency; the necessity of the record and of H3 rests on CON-0134. | CON-0149's motivation is weaker than stated. | Name CON-0134 as a premise. Split state history (Hist) from act history. |
| 10 | major | omission | Selection: earliest and latest hit only | STEP8 L6 | S1609 ("state vs history sufficiency test") names this turn's exact question, and 3 of 8 source slots were unused. S1524 L690 ("state ≠ history ≠ accessible memory") is in the map but was not read. A rule fixed before reading guards against cherry-picking, not against missing the key source. | The central claim was not tested against the most relevant hit. | Read S1609 topic-full before settling (allowed under §12b's limit of 8). |
| 11 | normal | distortion | §9.8.6-5: conflict is a property of the state space, not of the record | S0129 L43, L45 | R-CONFLICT ("delete one history · rewrite sequence") is about merging records written by several writers ("merge conflicts become governance conflicts"). *Int.* It bears on H1 for unions of records. Its status, **NOT SELECTED** (S0129 L158), is not stated. | One F24 source is misread, and a constraint on H1 is lost. | Separate a record-merge conflict from a claim conflict. State that R-CONFLICT is not adopted. |
| 12 | normal | defect | M1: "the earlier cut no longer exists" | §9.8.5 M1 | M1 keeps the superseded objects with links (I-7) and their times, so part of the history survives in the state. The route the map names ("append-only + I-7 + fold") is not tested on its own (supersession links only). | M1 overclaims. One alternative route is untested. | Restrict M1's failure to changes with no state trace (revocation, retraction). Add an M-I7 model. |

## 3. Concept verdicts

| Concept | Verdict | Reason · scope |
|---|---|---|
| Hist (definition) | PROVISIONAL | Record-relative only. Act history is excluded. The θ index is stipulated. |
| H1 | INVALID as stated | Homonym, and it fails the retention case (finding 5). H1′ is PROVISIONAL. |
| H2 | PROVISIONAL | Needed. Act times may be unknown: the transcribed START has no time (S0095 L210). |
| H3 | PROVISIONAL | Possibly derivable from H1 + §2.11; has a regress (finding 3). |
| D | PROVISIONAL | The steps are valid given the premises. Premises are hidden (finding 7). It is a decomposition, not a derivation from the six. |
| Mutual independence of H1–H3 | PROVISIONAL | M1 holds. M2 is defective (finding 8). M3 holds only with θ outside the record. |
| Result 2 (independent of the six) | **INVALID** | Finding 2. |
| CON-0148 | **INVALID as stated** | Findings 1–2. "Decomposes, conditional on a total CE" is PROVISIONAL. |
| CON-0149 | PROVISIONAL (Hyp) | The record is treated under Authority (§1.12–1.14, §6.5) and History (§1.13.2), but not as a named primitive. It has no independence model of its own. |
| Result 4 | **INVALID** | Finding 6. |
| §9.8.6-3 (§1.2 presupposes history) | VALIDATED | Quote checked (§1.2 L53). It undercuts result 2. |
| §9.8.6-4 (reconstructed ≠ history) | VALIDATED (Src) | Quotes checked: S0095 L162, S0129 L102. |
| C15 changes: §9.7 step 2/result 3/F26(c) | VALIDATED (scoped) | Follows from (t_x, s_x), act time first. |
| CON-0142 restated | VALIDATED, under K3 + K5 | — |
| CON-0144 restated | VALIDATED (Src) | S0079 L32, L57, L73 and S0076 L59 checked. |
| §5.7.1 "is recognized" | VALIDATED | — |
| §6.7 restatements C2, C4, C8, C9 | VALIDATED (Src) | 18 cited diagram lines checked. |
| §1.13.3-2 anchors | VALIDATED | S0014 L53, L68 and S0015 L58 checked. |

## 4. Coverage of my step-1 inventory (summary; details in the appendix)
- **COVERED:** recording order ≠ act order (M2), restoration as a history edit, layer-relativity (M1 note), versioned replay (H3), current ≠ history, "History ≠ Reconstruction Guess".
- **MISSING, ranked:**
  - external attestation and soundness (S0095 L141, L216; S0129 L147);
  - completeness or marked absence (S0095 L147–149);
  - act history, "what happened, who authorized it" (S0129 L47);
  - de998173 (S0129 L187, contradicts result 4);
  - retention deletion (S2782 L1873–1905);
  - successive acts vs blended text, and the register replacement (S0095 L50, L166–177);
  - act kind disputable afterwards (S0095 L71; S0129 L87; links to F21);
  - wall-clock ≠ causal order (S2782 L2124–2182), which bears on F26(c)'s "trusted" act times;
  - state as a fold, probed (S0095 L13, L185; S0129 L60 O-3), which supports D's form;
  - the four non-destructive corrections (S0129 L188).
- **DISTORTED:** R-CONFLICT (finding 11).
- **Sketch items:** H4 soundness / H5 completeness: MISSING. Bitemporal store (my M3): MISSING (finding 9). Supersession-only model (my M6): partly, inside M1 (finding 12).
- **New forks:**
  - F29, state history vs act history;
  - F30, is H1 absolute or "every change recorded" (the retention case);
  - F31, the regress of θ(s).

## 5. Next steps (dependency order)
1. Restate CON-0148 and result 3 as a decomposition (finding 1). Done when no text says "not a primitive in the §12b sense".
2. Re-run M1–M3 against §1.2, §1.16.2 and §2.11. Report which of the six fails in each (finding 2).
3. Restate H1 as H1′. Add H4 and H5 (findings 4–5).
4. Make D's premises explicit: F26, the θ index, x, CON-0134 (findings 7, 9). Decide whether H3 is derivable, and settle the regress (finding 3).
5. Correct result 4 and §9.8.6-5 (findings 6, 11).
6. Read S1609 topic-full. Re-test D and the models against it (finding 10).
7. **Question for the Principal**, only if step 2 confirms that history is part of authority: should the list carry "the record" as part of Authority, or as its own primitive?

## 6. Limits
- **Not read:** S1609, S1524, S2775, S0078, S0128; reviewer B's files; §1.14.1 in full.
- **Not run:** no small-scope model finding. Findings 2, 3, 8 and 9 are definitional arguments, not computations.
- **Checked:** 11 quotes in §9.8, and 25 lines cited in the C15 changes, by `sed` on the cited lines.
- **Not checked:** the §9.6.6a item 3 extension under K4 in full; the diff of the CON-0138 register row beyond its meaning column.

## Appendix
- **Normal/minor points:**
  - "two consecutive days": S0078 is dated 08-17 and S0095/S0129 08-19.
  - H1 cites S2782 L868 (H_{t+1} = H_t ∪ {e}, a set union that drops order and merges duplicates) "in meaning" without flagging this.
  - D restates S2782's own Replay(H, V) (L962; Theorem 19.3, L3036–3054), which the source calls "a candidate implementation strategy, not a theorem" (L923). Credit it as the same line.
  - H1's cut by recording time is a prefix only if τ is non-decreasing along positions, an unstated link between H1 and H2.
  - S0095 L221 shows a recording position that was deliberately not aligned with the report. The START's act time is not recorded (L210), so "act order differs" is Int.
- **Changed theory, minor:**
  - F26(c) needs no serialization point for *evaluation*. Write-time admission checks (Inv C, S0095 L13, L189) remain K4 questions. §9.6.6a item 3 keeps the K4 caveat, so this is consistent but worth stating.
  - CON-0144 at S0079 L57: the refusal there coincides with an owner ruled ineligible, an alternative explanation of the refusal.
- **Source defect (not relied on):** S0095 L50's "79 insertions against 166 deletions". `git show --numstat 9c93e2f0` gives 62/93 with the default diff algorithm and 79/110 with histogram.
