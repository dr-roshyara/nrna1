*Reviewer's step-1 inventory, window C18, verbatim (saved by the Professor, 2026-10-19).*

# STEP 1 INVENTORY — C18

Mode: transition review, step 1, done blind. I read the reviewer instructions and the four sources in full, one read each: S0097 (83 lines), S0098 (492), S0099 (111), S0100 (142). I opened nothing under 06-theory/, not RESEARCH-PURPOSE.md, and no other corpus file. For lineage I ran read-only `git log` on the four files and on five commit hashes the sources cite (dates and subjects only, no file contents). My own judgments are marked [I]. Independence here means process independence only. I am another AI session working under the same operator.

## 0 · Window-level ranking (most important first)

1. [I] **The property the bar protects (independence) is not the same thing as the session identifier used to check it.** A compaction continuation has a new sessionId but inherits the old context [S0099 L43, L76–78]. A session that "ended" can come back under the same id: CORRECTION 1 says 2da45a86 "ended at 08:49:17Z" [S0100 L68], yet 2da45a86 is writing again from 11:10:52Z [S0099 L44]. So a matching or non-matching sessionId neither proves nor rules out shared context. Independence is a property of where a process's information came from, not of its name.
2. **Self-declared identity fails in both directions** [S0100 L95; S0099 L57]. A process can wrongly claim to be barred (false bar) or wrongly claim it is not (false clear). Both come from one property: "the declaration is unverified at the point of use, and the declaring party is the only source" [S0100 L95].
3. **A party must not rule on its own compliance with a control that binds it** [S0100 L138 (OQ-L); S0099 L80]. Both lanes stop rather than decide their own independence. [I] There is a regress: all the identity evidence (transcript counts, clock matching) was gathered by the very processes whose identity is in question.
4. [I] **An invariant with no enforcer.** "No gate reads process identity" (INV-ATTR-1) [S0097 L41] sits beside a bar written by name [S0097 L27–31]. So the by-name bar holds only if each process applies it to itself. No operation in the model guarantees it, which is an invariant-owner gap.
5. **Revision chain: claim → CORRECTION 1 → erratum, all append-only** [S0100 L59, L103; S0099 L59]. The original claim is restored *as true*, but its standing is restored only by "Governance's act" [S0099 L59; S0100 L140]. Whether a claim is true and whether it is authoritatively accepted come apart. In the meantime the record holds the claim, its withdrawal, and the withdrawal of that withdrawal, with no act yet resolving which stands.
6. **An act's correctness is separate from the truth of its premise** [S0100 L88, L84]. The refusal's conduct is "correct on its own premise" even when the premise is thought false. The reasoning was "internally coherent and externally wrong".
7. **Independence covers information flow, not just authorship.** A verifier "must not assess how its own findings were absorbed" [S0100 L32, L38]. The same principle was also registered on Track 1 [S0100 L36], so it appears twice in one estate on one day.
8. **Verification identifies insufficiency; it does not provide the replacement architecture** [S0097 L67; S0098 L197]. [I] Possible counterexample in practice: 4858c37c verified AMD2 (findings F-1…F-7, ca6039a8) and then appears to have produced Correction #3 (c3839624, 13:25 local), "compounding the seq-21 producer mismatch" [S0100 L135]. I have not verified this; it rests only on S0100 and a git subject line.
9. **The assurance-capacity supply problem** [S0100 L49; S0097 L57]. Each assurance pass bars another process, so the eligible pool only shrinks. Ruling that continuations inherit bars would shrink it faster. In a single-operator setting, organisational independence cannot be established.
10. **Every claim in these records is time-indexed.** S0099 says Correction #3 "does not exist" [S0099 L24, L90]. That was true at 13:15 local and false ten minutes later (c3839624 at 13:25). [I] The theory needs claims of the form "valid at time t".

## 1 · S0097: registration of the AMD2 verification (Governance, 2026-08-19 10:45 local, b8077974d)

1. **Unappliable limb, recorded and not waived** [L33–39]. The bar covers the author of the review that caused AMD2 "if identifiable". That review is missing from the estate, so the qualifier lets registration proceed. The residual is pushed onto the verifier, who must state whether it wrote that review [L39]. Three statuses are distinguished: satisfied, unsatisfied, unappliable.
2. **Independence is "declared, not attestable"** (INV-ATTR-2) and **no gate reads process identity** (INV-ATTR-1) [L41]. See item 0.4 for the tension with the by-name bar [L27–31].
3. **Existence is checked separately from correctness** [L21]. Precondition 2 checks only that the amendment exists and is evidently implemented. Correctness is left to verification and is "not pre-empted".
4. **Scope failure is invisible to a quality check** [L45–47]. A decision-preparation artifact that answers its own open questions well has still overstepped. The verifier must test the artifact's *type* (decision-prep vs decision), not only its quality.
5. **The bar names processes and grounds** [L27–31]: producer, V#1 author, V#2 author. R-34/P-2: the producer must not verify or accept its own work [L19].
6. **Decomposing a capability into states** [L53]. Context is split into Published, Selected, Delivered, Available, Acknowledged, Applied, Verified, with authority challenged separately for delivery, possession, applicability, execution and compliance. Independence (C-5) is split into six limbs, organisational independence included [L55]. [I] This is relevant to the kernel: "known", "delivered" and "applied" are distinct states.
7. **Organisational independence must not be treated as established in a single-operator environment** [L57].
8. **Gate G-3: a handoff alone never yields ACTIVE**; START needs a recorded human start act [L77]. The record shows state CREATED after GRANT+ASSIGNMENT+HANDOFF [L5, L75].
9. **The verifier's MAY / MAY NOT boundary** [L63–67]: read-only, no repair, no replacement, no change to the OQ register, no acceptance.
10. **A delivered lane is HANDED_OFF, not COMPLETED**; closure is a separate PO/ARB act (G-1) [L79].
11. **Historical artifacts are not rewritten**: ba74dbdd remains historical evidence [L71]. Settled items are "not reopened" [L69–71].

## 2 · S0098: brainstorming, "five responsibilities" (external chat assistant acting as "ARB Chief"; filename stamp 08-19 10:47:48; committed 08-22 as an intake, b503f8edd)

1. **The role model (who may act) is distinct from the workflow state machine (what happens, in what order)** [L3].
2. **"A deliverable being produced is not the same thing as its lifecycle being closed"** [L290]. Independently matches S0097 L79.
3. **Verification can say "unsupported", "wrong" or "insufficient", but does not supply the replacement** [L189–197], "emerged … from the BC-7 work" [L199].
4. **Proposed layering** [L296–326]: Authority → Governance → Workflow → {Architecture, Implementation} → Verification → Human Acceptance. Knowledge/evidence and communication/context run across all layers. [I] This is a candidate kernel/application cut, but it is asserted, not derived.
5. **Governance Communication is "a coordination capability, not a role"** [L203–227]. It answers who acts now, what the state is, what is blocked, and what is forbidden.
6. **Listed as missing: "Role identity / provenance — who really executed an action?"** and knowledge-evidence provenance [L337–341]. [I] This was written about a minute before the identity episode began (refusal at 10:48:55). It anticipates the window's central defect, which may count as an independent line, since a different system wrote it.
7. **Architecture proposes and does not accept its own work** [L141–143]. Implementation cannot quietly redesign the architecture [L167].
8. **The assistant's honesty boundary** [L407, L482–488]. It "cannot watch your Claude Code terminal" and "will not pretend I am monitoring". Knowledge enters only through the human relay. [I] This is a provenance constraint: the human carries the information channel.
9. **Defect: the state machine mixes transitions and states** [L235–266] (HANDOFF, START listed as states; also REGISTERED, STARTED). It contradicts the record's actual behaviour: S0097 shows state CREATED after REGISTER+HANDOFF [S0097 L5, L75]. [I]
10. **Defect: who holds authority is inconsistent.** The list puts ACCEPT and START authorization under Governance [L101–108], but the diagram and S0097 L77 require a human PO/ARB act. Governance and human authority are merged. [I]
11. **Unsupported assertion**: "That sequencing is already registered in your Governance determination" [L391]. It comes from an assistant that by its own account cannot see the repository [L407]. [I]
12. **"These are future work, not defects"** [L354]. The same day's S0099/S0100 show that item 1 (identity/provenance) is an operating defect. [I]

## 3 · S0099: Correction-3 verification STOP plus erratum to CORRECTION 1 (2da45a86, 13:15 local, eca951d69)

1. **Open question, stated and not decided** [L76–80]: is a compaction continuation of a barred process itself barred? On the identifier reading, no. On the substance, the question is live, because INV-ATTR-1 forbids settling it by sessionId [L78]. The process declines to decide, as the party on the wrong side of it [L80].
2. **Mechanical attribution method** [L38–44]: create (`cat >`) vs append (`cat >>`) in transcripts, UTC vs local clock reconciliation, a 3–4 s commit gap, the isCompactSummary flag, and a three-fold anchor. [I] Create-vs-append is strong evidence. The timing gaps only corroborate. L94 calls items 1–4 "mechanically established", which overstates the timing part.
3. **The misattribution mechanism** [L46–47]: CORRECTION 1 measured its own identity correctly, then attributed an inherited act to itself. "The compacted summary presented the barred process's history as the continuation's own."
4. **Structural stops that hold for any reader** [L8–26]. S-1: an unregistered session has no answerable identity (engine refusal [L14]); identity is defined only relative to the registry. S-2: the subject does not exist ("verifying an undelivered correction is not possible, and no amount of independence would make it possible" [L26]).
5. **Effect on knowledge of record integrity** [L65–73]: F-1…F-7, and so Correction #3's mandate, rest on a verification by a continuation of a barred process. This is explicitly "not an allegation of a bad verification" [L80]. Trust in a provenance chain is separated from soundness of content.
6. **Withdrawal vs "does not follow"** [L51–57]: the erratum separates a falsified claim from conclusions that no longer follow. It "restores none of my own" verdicts; that is Governance's act [L59].
7. **The eligible-verifier set is unknowable** until the ruling on continuation inheritance [L109]. [I] Membership of the trust set depends on an open definition.
8. **Asserted vs mechanically established, kept apart** [L94]: the meaning of a compaction boundary is still only asserted.
9. **Internal inconsistency** [L57 vs L76–80]. It calls 4858c37c "a SECOND instance … a process wrongly claiming NOT to be barred", but that presupposes an answer to the very question L76–80 leaves open. [I]
10. **The chain was one step earlier than the act assumed** [L26]: the START handed out pointed at a delivery that did not exist. The authority act was out of step with the record.
11. **Source defect**: a stray "三" ("Anchored三-fold") [L44]. Trivial.
12. **Time-indexing**: "Correction #3 does not exist" [L24, L90] was overtaken by c3839624 ten minutes later. [I]

## 4 · S0100: independence refusal, CORRECTION 1, ERRATUM (three authorship layers in one file: 2da45a86 10:48 / 4858c37c 11:16 / 4858c37c 13:28 local)

1. **The bidirectional diagnosis** [L95]: one root, an unverified sole-source declaration. INV-ATTR-2 anticipated false clears; this event is a false bar.
2. **Root cause of CORRECTION 1's diagnosis**: identity inferred from the estate (matching described work against context summaries) instead of read from the runtime [L82]. "Internally coherent and externally wrong" [L84]. [I] The erratum shows this diagnosis was itself a mis-diagnosis [L120]: the refusal's own declaration was accurate.
3. **"I made the mirror image of the error I diagnosed"** [L120]. The correct inference was "a different session's act, present in this session's inherited context". [I] So the kernel needs a distinction between acts performed and acts present in context (inherited).
4. **Self-judgement named against itself** [L137–138]: ca6039a8 had settled its own first-person-narration concern in its own favour, which is OQ-L's failure mode. It declines to rule a second time.
5. **The material-participation limb** [L32]: Verification #2's findings W-1…W-6 fed this artifact's lineage. "A verifier must not assess how its own findings were absorbed" [L38]. Generalised from the Track-1 bar [L36] (two instances).
6. **Not consuming START keeps the authority act available** [L22]: "recording the START … would have made the record assert the opposite of the bar." The record must not contain an act that contradicts a standing constraint.
7. **Conduct vs premise** [L88]: the refusal "consumed nothing … nothing has to be undone; only the premise was wrong".
8. **Assurance capacity** [L47–49]: three processes barred, and SB-1 still uncleared after two consecutive verifications. This is a supply question and "not a reason to relax the bar". CORRECTION 1 voided the fourth-exclusion argument [L90]; the erratum reinstated it [L127].
9. **Standing of downstream artifacts** [L132–135]: in ca6039a8 authorship still holds but the participation limb is open; c3839624 inherits the defect. Standing is left to Governance [L140].
10. **The limb "unappliable in general, satisfied for this process"** [L31] answers S0097 L39.
11. **The identity attestation stays layered** [L99, L142]: "self-declared, evidenced by runtime metadata, not third-party attested". Three grades of evidence about identity.
12. **Tension in the sources** [I]: CORRECTION 1 says 2da45a86 "ended at 08:49:17Z" [L68], but S0099 L44 shows it active at 11:10Z. S0099 L44/L111 says 4858c37c's transcript "ends 10:42:21Z", yet 4858c37c claims to file this erratum at 13:28 local (11:28Z). Either sessions resume under the same id, or the erratum's self-declaration is itself unchecked. The episode's own mechanism could recur at a third level.
13. **Counts grow consistently over time** (not a defect): 20 transitions / 12 grants [L42] vs 23 / 13 [S0099 L100]; transcript lines 849 → 886 for 2da45a86 and 97 → 289 for 4858c37c.

## 5 · State-lineage note

- **Dates (local, +02:00, 2026-08-19)**: acdc613f V#2 at 00:18 (2da45a86). b1227c86 Track-1 parallel bar at 00:34. aff41549 AMD1/2 at 10:30 (5e1dd9ee). S0097 at 10:45 (Governance session, process unnamed). S0098 filename stamp 10:47:48, committed 08-22 13:12 as a batch intake. S0100 §1–6 refusal at 10:48:55 (2da45a86). CORRECTION 1 at 11:16 (4858c37c). ca6039a8 AMD2 verification at 11:24 (4858c37c). S0099 at 13:15 (2da45a86). c3839624 Correction #3 at 13:25. S0100 erratum at 13:28 (4858c37c).
- **Git author** is the Principal on every commit. Git authorship therefore cannot tell processes apart; process identity rests only on transcript metadata reported by the processes themselves.
- **Independent lines of support for the central identity finding** (refusal authored by 2da45a86): one evidence source (transcripts), measured twice, by S0099 (create/append plus clocks) and the S0100 erratum (heredoc count). The two measurers share context (4858c37c is a compaction continuation of 2da45a86), and the erratum was triggered by S0099 [S0100 L107]. Treat this as **one line**, with a methodologically distinct re-count, and no third-party attestation.
- **"Verification does not supply replacement"**: S0097 L67 quotes the PO/ARB act verbatim. S0098 L197 is an external assistant attributing it to BC-7. Both plausibly trace back to the Principal, so treat it as **one line, possibly two**.
- **"Self-judgement prohibited"**: Track-1 bar (b1227c86), S0100 L36–38, S0099 L80, S0100 L138. These are one estate on one day, mostly the same two linked processes, so **one line** with several instances.
- **The identity-provenance gap**: S0098 L337 (external system, roughly simultaneous) and S0099/S0100 (internal). Plausibly **two independent lines**, the only case in this window.

## 6 · Cross-source table

| Theme | S0097 | S0098 | S0099 | S0100 |
|---|---|---|---|---|
| Identity is self-declared, not attestable | L41 | L337 (gap named) | L94 | L10, L95, L99, L142 |
| sessionId vs context lineage (continuation) | — | — | L43, L76–80 | L120, L128, L134 |
| Self-judgement prohibited (OQ-L) | L19 (R-34) | L143 | L80 | L38, L138 |
| Verifier supplies no replacement | L67 | L197 | L101 | L43 (but c3839624: L135) |
| Append-only correction, no rewrite | L71 | — | L59 | L59, L103, L140 |
| Truth vs authoritative standing split | — | — | L59, L109 | L140 |
| Human start gate (G-3); produced ≠ closed | L77, L79 | L290 (states conflated L235–266) | L17 | L22, L53 |
| Unappliable / unsatisfied / satisfied limb | L33–39 | — | L92 | L31 |
| Single-operator independence limit | L57 | L407–488 (human relay) | — | L49 (supply) |
| Time-indexed claims overtaken | — | L354 ("future work") | L24 vs c3839624 | L68 vs S0099 L44 |
| Contradiction | — | L101–108 vs S0097 L77; L239 vs S0097 L5 | L57 vs L76–80 | CORRECTION 1 vs erratum (resolved); L142 vs S0099 L44 (open) |

Limits: I did not open the cited artifacts (ca6039a8, c3839624, acdc613f, the analysis), the transcripts, or the governed workflow record. Every transcript measurement above is reported by the sources, not checked by me. The Correction #3 producer point (0.8) rests only on S0100 L135 and a git subject line.
