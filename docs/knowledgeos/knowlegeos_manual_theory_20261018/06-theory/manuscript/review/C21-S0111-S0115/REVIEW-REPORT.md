*Window reviewer W (Opus), steps 2-3 review report, returned as hand-back text, verbatim (saved by the Professor at 2026-10-21T05:46:12Z). sha256 of the hand-back text at receipt: `e90c9bced9e4dfce24b5a705b0b1c52cafbafc703be0b19ed707e4cf8f08eeeb` (UTF-8, the text below this line exactly).*

C21 STEP 2-3: REVIEW REPORT

**Process independence.** I am independent of the Professor's process, not as a person. My step-1 inventory was handed back before I read any of the Professor's work. I re-read the saved copy: its sha256 89994c85… matches the text below its header line, without the final newline. One slip of mine: I gave M1 as S0114 L28; it is L27, and the book has it right.

## 1. Assessment (3 lines)
§8.14 quotes its sources faithfully: my scratch check, c21w_quotes.py, found every quote at its cited line. C4, C6 and C7 are sound within their scope. C1's interpretation is contradicted by a line inside the window, and C3 merges two operations into one.
**Largest risk:** the R4a v7 changes. "Above the issuer's scope" is tested against the wrong scope. A single-use grant can be used twice through the guard alone. And carrying the record of uses depends on a premise that moves (C + O) towards (C + K). Check 24l cannot see any of this, because it encodes the model's own readings in one record.

## 2. Findings (ranked)

| ID | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-1 | **C1 / CON-0208:** the authority's NOT YET ESTABLISHED is read as evidence for §8.12 C7's performance limb. S0112 L14 says the estate's existence test has a limb ① ("an adopted commitment about C-10"). Adopting the receipt as state "would supply limb ①" and so "decide existence by the back door". So the decided boundary (D1) was not counted as limb ①, and supplying limb ① alone would bear on existence. The act is silent on performance, and arguably points the other way. | high | S0112 L14; §8.14 L29; register CON-0208 | Restate: "D1 was not counted as an adopted commitment; the act is silent on performance." Remove the "requiring performance" interpretation. Read the grounds in S0120 when its turn comes. | §8.14 C1 and CON-0208 restated |
| W-2 | **R4a v7 "above the issuer's scope" is applied as "above the grant's scope".** The model accepts a transfer by the issuer's own grantee, at the issuer's own scope (my case A4, below). In 24l, the actor Q, who is called "above", holds gQ, which the issuer P issued from root at P's own scope. So Q is beneath the issuer, not above it. | high | `append_guard` (covers(bb.scope, **gr**.scope)); c21w_attack.py A4: accepted | Test against the scope of the basis the issuance cites, and require strict cover of it. Whether a grantee of the issuer counts as "above" goes to the Principal. Add the A4 case to 24l. | guard and 24l revised; case A4 refused |
| W-3 | **Single use is not prevented through the guard alone.** The sequence: use u1 → annul u1 → use u2 (accepted, since u1 is F) → annul that annulment. Result: u1 = T and u2 = T, in D, no mark. With consumptions, CE* gives k2 F, but both deliveries have already happened. By R3a's own reason, annulling u1 does not recall the work delivered under it, so reopening the grant once u1 is F contradicts "prevent". 24l never annuls uses or annulments. | high | c21w_attack.py A1, A2; R3a row 1; §9.13 L37 ("annulled consumption does not block") | Count every accepted use, whatever its present value. If the Professor keeps "not F", state why this is consistent with R3a. Add annulments of uses and of annulments to 24l. | rule stated; 24l extended |
| W-4 | **A second use that is not a consumption is invisible in standing.** Two adoption-uses by the owner, both arriving unguarded (two replicas of the owner): both T, no mark. R3a's "CE* already gives a second consumption F" covers only the kind consume. G4 is stated for consumptions. | high | A3; R3a; §9.15 G4 | Add a mark "second use of a single-use grant", and extend G4 to uses. | mark and check added |
| W-5 | **Carrying the record of uses rests on "the kernel sees each use".** Under (C + O) the uses are recorded on the owner's record. If the kernel sees and owner-checks every use, it becomes a runtime point for every use, which is the defining property of (C + K). In the case transfers exist for (an absent owner), a use the old owner recorded that has not yet reached the kernel makes last_use wrong, and a second use becomes possible. The model's single record cannot represent this. | high | R4a v7 "Premise"; `uses_of` on one record | Name the premise as a K-assumption and say what the kernel stores for each use. Add a two-record test. Restate how (C + O) differs from (C + K). | premise in §9.15; two-record check |
| W-6 | **Consumable grants not marked single-use lose prevention across applications.** This contradicts R3a's "prevent single use (laws 9b, 24)" and G4. In addition, §9.13 L36 ("single use bounds consumptions, not acts") is not reconciled with v12. | normal (normative) | §9.13 L36, L40; R4a v7 "Professor's reading" | Either derive "consumable ⇒ single-use", or return the point to the Principal. | decided; §9.13 L36 updated |
| W-7 | **C3 / CON-0210 merges two operations.** S0114's own candidate triggers include "receipt issued in error" (L78), where the claim was false from the start. The forged receipt appears only in the step-8 table. "Invalidated" covers (a) correction of an error, from the start, for which §9.13's annulment is the right analogue, and (b) withdrawal of reliance for one purpose (ca6039a8). "CE* lacks purpose" overstates: one adoption per claim already gives purpose-scoped withdrawal. What CE* lacks is a way to partition one act by claim. | high | S0114 L78 vs L80; §8.14 L46 | Split the concept into the two operations. Bound "never false" to claims recorded correctly. Restate the CE* comparison. | CON-0210 restated |
| W-8 | **C1's "decision by invariance" is not proof-checked:** the cases are not exhaustive (see proof table). | normal | S0111 L54 | Report it as a sketch with a missing case. | text says so |
| W-9 | **C5:** "a failed read is a refusal" is sound for a total failure. A partial read that happens to be a prefix in D gives F silently, so it needs R4 (iii)'s rule for cuts ("as of the entries received"). Absence also needs L4 (completeness), not only a realized store. Step 8's "cached copy" counterexample misses this case. | normal | §9.13 L133; R4 (iii) | Add the partial-read case to the proposed revision. | proposal amended |
| W-10 | **Homonym NOT ATTESTED:** it covers an unbindable identity (M4) and also an unresolved applicability (M2, STALE). | normal | S0115 L34, L38 | Qualify the two senses; only M4 is CON-0201 (b). | register qualified |
| W-11 | **Omissions:** (a) the compensating control: a recorded human authorization as a third way between prevent and detect, which bears on R3a and on the cost of R4a (S0113 L73–77). (b) The possession claim stays authoritative while the session's identity cannot be attested, so the subject is self-declared (S0112 L24 vs L20). (c) Changes over time: invalidation "triggered by supersession" (S0112 L48) becomes a separate act in S0114; Missing "detectable in principle" (S0113 L28) becomes non-evidential (S0114, S0115); the floor moves from WARN to INFO. (d) "Completeness" needs applicability, which is excluded (S0112 L49 vs L55). | normal | as cited | Add (a) and (b) to §8.14; record (c) and (d) as changes and contradictions. | §8.14 v2 |
| W-12 | **C6:** in this estate the recording parties *are* sessions ("Recording process: claude-code-session…, self-declared", S0108 L4). So L10 is unmet for the estate's actual recorders, not only for an analogue. | normal | S0108 L4 | Sharpen one line. | — |

**Proof check of S0111 L54 (C1, decision by invariance):**

| Step | Justified? |
|---|---|
| Q6 has two resolutions: applicability inside C-10, or outside | **No.** A third exists: applicability outside **and** the receipt owned elsewhere (BC-6 issues receipts), and then C-10 is empty |
| branch 1 is non-empty (applicability + receipt) | yes, given the premise |
| branch 2 is non-empty (receipt custodian) | **hidden premise:** the receipt is C-10's, which is D3's open question |
| so existence holds under every resolution | a **sketch**; it does not follow |

**Proof check of R4a's claim "the closure reaches every earlier use":** it holds only for records appended through the guard, because D checks only that a pointer names "an earlier use", not the immediately previous one. It also holds only within one record (W-5). The claim is valid only within that scope.

## 3. Verdicts

| Concept | Verdict | Reason (scope) |
|---|---|---|
| CON-0208 | **INVALID as stated** | its "requiring performance" interpretation is defeated by S0112 L14 (W-1); the descriptive part stands |
| CON-0209 | **VALIDATED** | a definitional consequence of the source's criterion "control-plane if it gates START"; one producer |
| CON-0210 | **INVALID as stated** | two operations merged; a counterexample in the source itself, L78 (W-7) |
| CON-0211 | PROVISIONAL | the interpretation is sound; CE* has no reversible, claim-scoped suspension |
| CON-0212 | PROVISIONAL | needs the partial-read and L4 conditions (W-9) |
| R4a v7, as applied | **"above" INVALID as applied (W-2)**; uses and chain PROVISIONAL (W-3 to W-5) | "never the holder", even when the holder is the issuer, is sound and follows the Principal's own reason; it is a normative choice, so it should be stated as one |

## 4. Coverage
- **COVERED:** the historical claim and the three-way cut; DISPUTED as a flag; the denial-of-service gate; absence in an unrealized store, laundering, M4 = NOT ATTESTED; universal Missing; the four prerequisites; non-constitutive wording; identity that cannot be attested.
- **DISTORTED:** C1's interpretation (W-1); invalidation (W-7).
- **MISSING:** W-11 (a)–(d); M2 and M5 (expected-but-absent needs applicability; a missing receipt is not missing knowledge); "no automatic staleness".
- **JUSTIFIABLY EXCLUDED:** the maturity class and necessity-vs-existence (already in §8.12 C7); the CAP-09 precedent; option E (n = 1, about the estate).
- **New forks for the map:** purpose-scoped standing; dispute as a mark or as a suspension; failed versus partial read. The THEORY-MAP has no entries for C19–C21, and its Position section is stale (it says S0100).
- **Depth:** my blind judgement and the Professor's agree: all five full, with S0115 the nearest to skim.
- **Source defect:** S0115 L22 "2/9 identifiable evidence source" cannot be traced to its table. The theory does not rely on it.
- **C20 settlement spot-check:** rows W-1, W-2 (technical), W-3, W-5, W-6 and W-10 are met; checks 24h, 24i, 24j and 24k pass in the v13 run. W-12 is superseded by decision 2, but §9.13 L36 is stale (my W-6). The §9.13 tool pointer is v13 ✓. §9.16's reference row is still v12, and R4a L268 says "300 sequences" where the run has 400.

## 5. Next steps (in dependency order)
1. Fix W-2 and W-3 in the guard, and extend 24l: annulments of uses and of annulments, a grantee of the issuer, a two-record case for W-5. Done when the A1–A4 cases behave as the restated rule says.
2. W-6 and the "above" subordination question go to the Principal.
3. Restate CON-0208 and CON-0210, then amend §8.14 to v2 with W-9 to W-12.
4. Update the theory map.

## 6. Limits
- I did not read S0120 (the D2 decision and its grounds), Ch. 0–2, or §9.15 beyond L4, L9 and L10.
- My counterexamples ran on model v13 (/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/c21w_attack.py). They show defects in that model only, not in a general design.
- I re-checked only six rows of the C20 settlement.

**Summary:** the quotes are faithful and C2, C4, C6 and C7 hold. C1 misreads the authority's act (S0112 L14), and C3 merges correcting an error with withdrawing reliance. Applying the Principal's decisions left three model defects: the wrong scope for "above", a single-use grant that can be used twice through annulment, and a premise about the kernel's view of uses that check 24l cannot test.
