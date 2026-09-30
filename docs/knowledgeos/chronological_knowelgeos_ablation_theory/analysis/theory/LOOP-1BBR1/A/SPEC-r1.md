# SPEC 1bb-r1: corrected specification, plus two frozen search protocols

**Status.** This is a specification only. **Nothing has been collected.** No command in this document has been run. Every command in Parts B and C is written to be run later, verbatim, by a worker, and then checked by a reviewer.

**Inputs used (only these):** `SPEC-1bb.md`, `SPEC-1bb.json`, `REVIEW-1bb.md` (defects D1–D14), `HYPOTHESES.md`, `IDENTIFIABILITY.json`, `CONFORMANCE-CASE.json`, `STATE.md`, `HUMAN-DECISIONS.md`.

**Labels** (kept from 1bb): SOURCE FACT (stated in an input file), INFERENCE (derived from inputs), PROPOSAL (a specification choice), UNKNOWN.

**Change marks** used in the corrected specification:
- **[UNCHANGED]**: kept from 1bb word for word, or with wording changes only.
- **[CHANGED Dn]**: changed to fix defect Dn.
- **[NEW Dn]**: added to fix Dn.
- **[REMOVED Dn]**: deleted to fix Dn.
- **[RELABELLED D14]**: same content, different label.

Every change is also listed in `CHANGELOG.md` with an ID (CH-nn).

**Not available.** REVIEW-1bb.md mentions a review JSON with `missing_fields` and `self_report_residue`. That file is not among the inputs. This document fixes only the missing fields that REVIEW-1bb.md names in its text (D8), and the self-report residue it names (D7, D13, D14).

---

# PART A. Defect-by-defect correction (D1–D14)

For each defect, this part gives: (1) the defect, quoted from REVIEW-1bb; (2) the correction; (3) why the correction is logically sufficient; (4) whether it changes a scientific prediction, and which one; (5) the corrected rule, stated so it can be frozen.

A distinction used throughout item (4):
- The **hypothesis-level claims** are the clauses of H-D and H-B in HYPOTHESES.md. They are frozen, and no correction changes them.
- The **operational prediction** is the observation this spec says each clause predicts, together with its falsifier. Corrections can change this.

---

## D1: the same observation serves both sides

**(1) Defect, quoted.**
> "**The same observation serves both sides.** 'R-89 not adopted, but work ran under it' is read as H-D's status clause failing. But the spec's own P2 and P7 falsifiers read the same observation as **non-conformance**: execution under an act that is not in force, before WP-4B's acceptance and with no waiver. That falsifies H-B. H-D can say 'R-89 had no force; engineering broke the norm'. H-B can say 'work changed the norm'. Behavioural uptake cannot tell *the norm changed without an act* from *work violated an unchanged norm*. The same confound affects P5's 'not in force' branch and P1's H-D falsifier, which requires only that the after-state is 'acted on by engineering'."

**(2) Correction.**
- Introduce the **six distinctions** of HUMAN-DECISIONS.md as separate recorded categories (§0.3).
- Introduce an **independent observable of the norm in force** (NIF, §0.4), in three tiers:
  - **NIF-A**: an independent norm-status record.
  - **NIF-B**: application of the norm as binding by a third party, meaning an actor who is not the maker or adopter of that norm and not the executor of the work under test.
  - **NIF-C**: a statement by such a third party that the norm governs.
- Work by the executor is **never** a NIF. It is recorded only as an act, either according to the norm or contrary to it.
- Add the generic rule **G-D1** (§0.4). If a falsifier premise about the norm in force is established only through (a) executor behaviour or (b) applying H-D's status rule to the norm-maker's own records, the result is recorded as **DISJUNCTIVE**: "at least one of {clause X of H-D, clause Y of H-B} fails; which one is not identified". It is not recorded as a falsification of either hypothesis.
- For **each** prediction, state which NIF is required. Without that NIF, declare the prediction NON-DISCRIMINATING, DISJUNCTIVE or UNDETERMINED (§1.7).
- Split the headline test into two limbs with disjoint observables:
  - **H-D force limb:** NIF-A or NIF-B shows the norm in force while no adoption form exists.
  - **H-B conformance limb:** an execution trace, plus a NIF (or the graded fallback) showing that the operation was not permitted at t⁻.
- This narrows the reviewer's fix in one respect. The reviewer proposed "citation as governing by a non-adopting, non-executing actor" as the H-D force test. A citation shows only that the norm is *known (believed) to be in force* (distinction 3), which HUMAN-DECISIONS.md requires to be kept apart from *in force* (distinction 2). So bare citation is tier NIF-C: it is recorded but cannot fire a falsifier. Only **application** by such an actor (NIF-B) or an independent status record (NIF-A) can.

**(3) Why this is sufficient.**
- D1 is a failure of identification: one observation O (work under R-89 while R-89 is not adopted) is predicted by the conjunction "H-D status clause holds, and H-B conformance fails", and also by "H-D status clause fails, and H-B conformance holds". So O cannot separate them.
- After the correction, each limb rests on an observable the other limb does not use:
  - The H-D limb uses NIF-A or NIF-B about R-89's force. Neither is an act of the executor, so H-B's conformance clause says nothing about it.
  - The H-B limb uses the norm in force at t⁻ established by NIF (or the graded fallback). It does not use H-D's status rule, because G-D1 downgrades any premise that does.
- The executor's behaviour enters only as distinction 4 or 5 (an act according to or contrary to a norm), never as distinction 2 (the norm in force) or 6 (a norm change without an act). So no single observation can be counted for both sides.
- When only O is available, G-D1 records the result as DISJUNCTIVE. That is the strongest conclusion that follows logically: O is incompatible with "both clauses hold" (given no waiver in force), but it does not say which clause fails.

**(4) Does it change a scientific prediction?** Yes, at the operational level. The hypothesis-level claims are unchanged.
- **P4 H-D falsifier:** "work executed citing R-89" no longer counts. It now requires NIF-A or NIF-B.
- **P1 H-D falsifier:** the after-state must be established by NIF. "Acted on by engineering" no longer suffices.
- **P5:** the "effective" branch and the "not in force" branch both require NIF. Otherwise the result is DISJUNCTIVE.
- **P2 and P7 H-B falsifiers:** if the non-permission premise rests on the status rule (for example, "R-81..R-85 governed only via R-86", or "R-87 was not yet adopted"), the result becomes DISJUNCTIVE.
- **§5, branch 1:** changed from "H-D's status clause falsified" to DISJUNCTIVE, unless NIF is present.

**(5) Frozen rule.**
> **FR-D1.** An observation made only of executor behaviour establishes, at most, an act according to or contrary to a norm (distinctions 4 and 5). It never establishes a norm being in force (distinction 2) or a norm change without an act (distinction 6).
>
> A premise "norm N is (not) in force at t" counts toward a falsifier only if it is established by NIF-A or NIF-B. NIF-C, statements by the maker, a ruling's self-assertion and recording times never count.
>
> **G-D1.** If a premise about force is established only by (a) executor behaviour or (b) applying H-D's status rule (force comes only from adoption) to the maker's records, then any falsifier that uses the premise is graded **DISJUNCTIVE {H-D clause, H-B clause}**.
>
> Each prediction carries the NIF requirement listed in §1.7. Without it, the prediction is scored NON-DISCRIMINATING or DISJUNCTIVE, as listed there.

---

## D2: the three items are not sufficient

**(1) Defect, quoted.**
> "**The three items are not sufficient.** The spec's own P4 and P7 falsifiers also need: (a) a link showing that the WP-4C-1 work was done under R-89 or cites it; (b) the closed-channel history of waivers and responses up to WP-4C-1's acceptance; (c) R-72's gate being in force at t_first_work; (d) citations by non-adopting actors. Also, 'no adoption up to 2026-08-15' needs the **whole** write history over the window, so 'about 3 records' understates the effort. The outcome list also leaves out **'no WP-4C-1 work by 2026-08-15'**. That outcome is plausible for a PREPARED, NOT ADOPTED ruling, and in it neither falsifier can fire, so the acquisition discriminates nothing."

**(2) Correction.** Replace the three-item acquisition with a **two-stage acquisition** (§5).
- **Stage 0** has two parts:
  - the rank-0 WP-4C-1 check (Part B);
  - the norm-status-channel scoping search (Part C).
- **Stage 1** runs only if Stage 0 finds an execution trace. It adds items (a)–(d):
  - (a) the link from the work to R-89;
  - (b) the full write history of the declared channels over the P7 window, covering adoption, waiver and response entries;
  - (c) R-72's gate being in force at t_w;
  - (d) NIF-B applications and NIF-C citations by third parties.

  It also adds R-89's text, to test whether R-89 itself waives or supersedes the gate.
- The effort is restated. The whole write history over the window is required, so this is not "about 3 records".
- Freeze an **outcome table** (O0–O8) that includes **O0: no WP-4C-1 execution trace in the capture**, before anything is acquired.

**(3) Why this is sufficient.**
- Each falsifier that §5 relies on (P4-HD, P7-HB) now has every premise mapped to an acquisition item:
  - "work under R-89" maps to (a);
  - "no adoption" and "no waiver" map to (b), together with the completeness rule in §0.8;
  - "gate in force" maps to (c);
  - "force without adoption" maps to (d), or to NIF-A from Part C.
- The outcome table is exhaustive over the branches: trace present or absent; R-89 adopted, not adopted, or not identifiable; NIF present or absent; acceptance before or after the work.
- Because O0 is explicit, the case where the acquisition discriminates nothing is known in advance, and the ranking is made conditional on it (D3).

**(4) Does it change a scientific prediction?** No. It changes the acquisition, its effort estimate and the outcome table. The newly listed outcome O0 predicts nothing for either hypothesis.

**(5) Frozen rule.**
> **FR-D2.** The smallest acquisition is two-stage.
> - Stage 0 = Part B and Part C, both run before any Stage 1 item.
> - Stage 1 runs only if Stage 0 returns at least one execution trace (ET-1 or ET-2). It acquires items S1-a to S1-g (§5).
>
> Outcomes are scored only against table O0–O8 (§5), which is frozen now. O0 ("NOT-EVIDENCED-IN-CAPTURE" or "CAPTURE_INVALID") means that P4 and P7 discriminate nothing, and P2 moves to rank 1.

---

## D3: contradictory "only" claim

**(1) Defect, quoted.**
> "The spec calls P5 'low effort and two-sided'. It then says P4+P7 is 'the only acquisition this small that puts **both** hypotheses at risk'. These cannot both be true."

**(2) Correction.**
- Delete the "only" claim.
- Compare P4+P7 and P5 on their **likely outcomes** and on their **NIF requirement**, not on whether they are two-sided in principle (§4).

**(3) Why this is sufficient.**
- The contradiction exists only because both statements are present. Deleting the universal "only" statement removes it.
- The replacement comparison uses only claims made elsewhere in the spec: P5's likely outcome is operational scope, in which neither hypothesis is at risk (INFERENCE, kept from 1bb); and P4+P7's value depends on the result of the O0 screen. So the replacement cannot contradict any other entry.

**(4) Does it change a scientific prediction?** No. It changes only the ranking text.

**(5) Frozen rule.**
> **FR-D3.** No acquisition is described as the "only" one with a given property. Candidate acquisitions are ranked by (i) the probability that the outcome is not NON-DISCRIMINATING, given the outcome of Stage 0 and its NIF availability, and then (ii) effort. The ranking is a PROPOSAL.

---

## D4: windows start at recording times

**(1) Defect, quoted.**
> "The P7 window starts at **R-89's recording time**. WP-4C-1 work that began *before* R-89 was recorded is the strongest possible case against H-B, and this window excludes it. P4 and P5 also start at recording times, although STATE says recording time ≠ decision time ≠ entry into force. The §5 item 'first WP-4C-1 commit' has no window at all."

**(2) Correction.**
- **P7:** the window starts at the earlier of (i) the earliest recorded trace of R-72 and (ii) the earliest WP-4C-1 execution trace. It ends at the earlier of WP-4C-1's acceptance entering force and 2026-08-15.
- **P4 and P5:** the window starts at the earliest recorded trace of the ruling in **any** declared channel, not at its register row.
- **Rank-0 check:** it has **no lower bound** at all (Part B §6), so the "earliest WP-4C-1 artefact" is found and not assumed.
- **§5:** item "first WP-4C-1 commit" gets its window from Part B.

**(3) Why this is sufficient.**
- The defect is that the window excludes admissible falsifying cases. The new P7 start is ≤ every time at which a WP-4C-1 trace could exist in the capture, because it takes the minimum with the earliest trace, and the rank-0 search that finds that trace has no lower bound. So no WP-4C-1 execution in the capture can fall before the window.
- For P4 and P5, "earliest recorded trace in any declared channel" is ≤ the register row's time by construction. So no earlier trace is excluded.
- The windows still use recording times, because no earlier time is observable. But they use them only as lower bounds on existence, never as decision times or times of entry into force (§0.9).

**(4) Does it change a scientific prediction?** Yes.
- **P7 H-B falsifier:** it can now be fired by WP-4C-1 work that began before R-89 was recorded, and even before R-72's earliest trace. In that case the relevant norm is "no authorization", not R-72's gate.
- **P4 and P5:** earlier traces are brought into scope. The falsifier conditions themselves are unchanged.

**(5) Frozen rule.**
> **FR-D4.** Every window starts at the earliest recorded trace of the relevant object in the declared channel set, or earlier. The rank-0 search has no lower bound.
> - P7 window = [min(earliest trace of R-72, earliest WP-4C-1 ET), min(WP-4C-1 acceptance in force, 2026-08-15)].
> - P4 window = [earliest trace of R-89, min(first adoption or withdrawal form, 2026-08-15)].
> - P5 window = [earliest trace of R-94, attested execution], followed by the P8 window.
>
> A recording time is used only as a bound on existence.

---

## D5: the closed-world register is not well defined

**(1) Defect, quoted.**
> "**Not well defined.** (a) R-86 names the *adopter*; the *single channel* is the spec's own assumption. The Chief's operational dispositions (R-94, and R-90's operational/constitutional split) and the DA's 'R-89 already governs' may sit in other channels, such as session logs or progress files. An act made there would show up as an 'act-less change'. (b) **Late recording:** an act decided in the window but recorded afterwards looks like 'no entry in window'. The spec has no rule for this, although recording ≠ decision is a SOURCE FACT. (c) There is no rule, fixed in advance, for **what form counts as an adoption**. The spec says the DA's statement 'may itself be the adoption' and leaves it there, so the outcome can be decided after the fact. (d) Actor identity rests on git author/committer names, which the actor sets. (e) There is **no closed world for work**, yet P1-HB ('executed nothing'), P7 ('first work event') and the status-mediation contrast ('no execution') all need an absence of work. (f) 'Fixed before looking at the data' is impossible: R-86 and R-90 have already been read."

**(2) Correction.** One fix for each sub-defect (§0.6 and §0.7).
- **(a)** A **declared channel set** for norm-making acts, v0, is frozen now:
  - the register;
  - session logs;
  - IMPLEMENTATION_PROGRESS.md;
  - any hosting-server approval channel that Part C finds.

  It is closed once more, by Part C's output, before Stage 1. An act found outside the set is labelled OUT_OF_CHANNEL_ACT. Such a find makes the "no act" premise UNDETERMINED. It never makes it a falsification.
- **(b) Late recording.** Acts are placed by their recording time only for RECORDING_ORDER. If an act is recorded after the window but states an effect inside the window, then both "no act in the window" and "act in force in the window" become NOT_IDENTIFIABLE for that target, and the falsifiers that use them are UNDETERMINED.
- **(c) Adoption forms.** The forms AF-1, AF-2 and AF-3 are frozen as an exhaustive list. Descriptive statements by the maker that force already exists ("R-89 already governs") are MAKER_FORCE_ASSERTION, **not** adoption. A sensitivity result under the opposite reading is reported but never scored.
- **(d) Identity.** AUTH_ID means a verified signature against a key list fixed before Stage 1, or an account record on the hosting server. ACTOR_SET_ID means git name and email fields, and names in text. A premise that rests on ACTOR_SET_ID caps the grade at FIRED-CONDITIONAL.
- **(e) Work.** Add an **execution-channel register** (§0.7). A premise of *absence of work* is admissible only if the execution channel is shown complete (§0.8). Otherwise it is NOT-EVIDENCED-IN-CAPTURE.
- **(f) Timing of freezing.** "Fixed before looking at the data" is replaced by "fixed before acquiring any new evidence (Stage 0 or Stage 1)". The capability register v0 is stated openly to rest on texts that have already been read (R-86 as quoted in the inputs), so it is not blind. The capability for operational dispositions is UNKNOWN, and it is not settled by choice.

**(3) Why this is sufficient.**
- Each sub-defect is a way the "no act" or "no work" premise could come out true when an act or work in fact existed, or be decided after the fact. Each fix blocks that path:
  - (a) An act outside the assumed channel can no longer produce a false act-less change, because finding it blocks the premise, and not finding it counts as absence only within a declared, closed set.
  - (b) A late-recorded act can no longer produce a false act-less change or a false "waiver in force". Its effect time is NOT_IDENTIFIABLE, which blocks both.
  - (c) The adoption verdict is fixed by a rule stated now, so it cannot be chosen after seeing the outcome.
  - (d) An identity set by the actor can no longer support a clean result.
  - (e) An absence of work now needs a demonstrated complete channel, as an absence of acts does.
  - (f) The claim that cannot be met is replaced by one that can be verified (the freeze precedes acquisition), and the residual non-blindness is disclosed.
- None of these fixes removes a falsifier. They only add the premises the falsifiers already needed.

**(4) Does it change a scientific prediction?** Yes, at the operational level.
- **P1-HD, P4-HD and P5-HD** ("no act" or "no adoption"): each now needs a complete declared channel set and the adoption-form rule. A late-recorded act gives UNDETERMINED.
- **P1-HB, P7-HB and the status-mediation contrast** ("no work" premises): each now needs execution-channel completeness.
- **R-89:** under the frozen form rule, the DA's "already governs" is not an adoption. So if no AF-1, AF-2 or AF-3 exists, R-89 counts as not adopted.

**(5) Frozen rule.**
> **FR-D5.**
> - (a) Norm-making acts are sought only in the declared channel set DCS. DCS v0 = {K-REG, K-SESS, K-PROG}. It is extended only by Part C's result, and it is closed before Stage 1. An act found outside DCS is labelled OUT_OF_CHANNEL_ACT, and the "no act" premise for that target becomes UNDETERMINED.
> - (b) An act placed after the window by recording time, but claiming an effect inside the window, makes the relevant premises NOT_IDENTIFIABLE.
> - (c) Adoption = AF-1, AF-2 or AF-3 (§0.6) and nothing else.
> - (d) Premises that rest on identity require AUTH_ID for a clean grade. With ACTOR_SET_ID only, the grade is at most FIRED-CONDITIONAL.
> - (e) Absence of work requires a complete execution channel (§0.7, §0.8).
> - (f) All rules are frozen before any Stage 0 command runs. Non-blindness toward R-86 and R-90 is disclosed.

---

## D6: P2 adds a response requirement

**(1) Defect, quoted.**
> "HYPOTHESES says only that work 'is expected to **conform**'. The spec's P2 claim adds that 'the system responds (sanction, remediation or waiver) before acceptance'. That makes H-B harder to falsify, overlaps P8, and makes C1's bearing depend on R-87. The window end ('acceptance takes force') is not justified. A later execution-governance repair (R-91 deviation → R-94 Constitutional Review, listed by the spec under P8) might be a response to R11, and this window would ignore it."

**(2) Correction.**
- **P2 claim:** reduced to conformance as stated. Every execution event occurs when the norm in force at t_exec⁻ permits the operation. The falsifier is one clean non-permitted execution with no waiver in force *ex ante*.
- **Response** (sanction, remediation, or a retroactive waiver) is moved to **P8**, where non-conformance counts as a deviation. P8 has its own window (from the deviation to the next execution on the same target). Late responses are recorded as LATE_RESPONSE, whenever they occur.
- A retroactive waiver does not turn a non-conforming execution into a conforming one. It is a response.
- Stage 1 checks whether R-91 or R-94 refer to R11.
- The **C1 bearing** no longer depends on R-87.
- The same change applies to **P7-HB**, which had the same response clause.

**(3) Why this is sufficient.**
- The defect is that P2 tested the conjunction "conformance, or else a timely response", which H-B does not state. Removing the disjunct makes the tested clause equal to the stated one ("expected to conform").
- The response clause is still tested, as H-B's feedback clause (P8), so no part of H-B is left untested.
- The arbitrary window end disappears with the clause. P8's window end (the next execution on the same target) is the one 1bb already justified for P8.
- R-91 and R-94 are captured, because P8 records every response that refers to R11, whenever it occurs.

**(4) Does it change a scientific prediction?** Yes.
- **P2 (H-B conformance):** H-B becomes easier to falsify, because a non-permitted execution counts even if a response followed. The C1 bearing on H-B changes from "NON-CONFORMS and no response before R-87" to "NON-CONFORMS".
- **P7-HB:** the same.
- **P8:** its domain is extended to deviations of non-conformance.

**(5) Frozen rule.**
> **FR-D6.** A conformance falsifier (P2, P7-HB) = an execution whose operation class is not permitted by the norm in force at t_exec⁻, with no waiver in force at t_exec⁻. Any later response is scored under P8 and never under P2 or P7.
>
> P8 applies to verified failures and to verified deviations, including non-conformance. Its window runs from the deviation's recording time to the next execution trace on the same target. Every response that cites the deviation is recorded; responses outside the window are recorded as LATE_RESPONSE.

---

## D7: the attestation rule contradicts itself

**(1) Defect, quoted.**
> "The attestation rule contradicts itself. Condition (i) requires an author or process different from the norm-making record, but entry into force is taken from the **adoption record written by the adopter**, which is the status-conferring act. Condition (ii) excludes artefacts that are 'citing that record', but P4 needs uptake artefacts that cite R-89."

**(2) Correction.**
- Separate **being the observation** from **attesting a field of** the observation (§0.5).
- Conditions (i) to (iii) apply only to *attesting* a field.
- The act's own record *is* the observation of the act. It may attest only two fields of that act: who wrote it (as AUTH_ID or ACTOR_SET_ID), and its recording time. It never attests its stated effective date, and never the force of the norm.
- Citing the ruling is allowed for artefacts that are *observations* of uptake (NIF-B, NIF-C, ACT). What is excluded is only the use of a citing artefact's copy of the ruling's self-statements to attest a field.

**(3) Why this is sufficient.**
- The two contradictions came from applying one rule to two roles.
- Once the roles are separated:
  - the adoption record satisfies the rule in the only role it plays (the observation of an adoption, which attests who and when);
  - the uptake artefacts satisfy it in theirs (observations that cite R-89, where the citation is the thing observed).
- No field is attested by a self-report. The stated effective date and the force are never attested by the act itself, so the rule's purpose is kept.

**(4) Does it change a scientific prediction?** No. It fixes a rule's consistency. It does narrow what an adoption record can establish (never the effective date), and this matters for the time premises of P4 and P7.

**(5) Frozen rule.**
> **FR-D7.** A record R of an act is the observation of that act. It may attest (i) its writer identity, graded AUTH_ID or ACTOR_SET_ID, and (ii) its recording time, graded by §0.9. It attests nothing else about itself, including a stated effective date or force.
>
> A field of any *other* observation is independently attested only if its attesting artefact (i) has a writer different from the maker, the adopter and the executor, (ii) does not copy the ruling's self-statements, and (iii) carries a timestamp generated by a system.
>
> Artefacts that cite a ruling are admissible as *observations* of uptake (NIF-B, NIF-C, ACT).

---

## D8: missing fields

**(1) Defect, quoted.**
> "**Missing fields**, see JSON `missing_fields`. The chief ones: the norm in force at the time of the act is missing for P7 (R-72's gate) and for P5 (the capability norm in force when R-94 was made); P1 has no response/legitimation field (needed for D1) and no attested eligibility criterion for the census; P8 does not define or attest an 'active target'."

**(2) Correction.** Add the following fields. (The review JSON is not in the inputs, so only these named fields are added.)
- **P7:** `r72_gate_in_force_at_t_w`.
- **P5:** `capability_norm_in_force_at_t_R94`.
- **P1:** `response_or_legitimation` and `eligibility_criterion`.
- **P8:** `active_target` (definition and attestation).
- **Every prediction:** NIF fields, as D1 requires.

**(3) Why this is sufficient.**
- Each named field is a premise of a falsifier that already exists but was not acquired:
  - The P7-HB falsifier says "gate in force". Without the field, it could fire against a gate that was not in force.
  - P5-HD needs the capability norm at the time R-94 was made, not the capability norm as it is now.
  - The P1 census needs eligibility to be decided by a rule, not after the fact. P1's D1 split needs to know whether a later act legitimated the apparent change.
  - P8-HB is conditioned on "active target".
- Adding the premise as a field makes each falsifier's condition checkable.

**(4) Does it change a scientific prediction?** Yes, at the operational level.
- **P7-HB:** cannot fire unless R-72's gate is in force at t_w.
- **P5-HD:** judged against the capability norm in force at t_R94.
- **P1:** the census is limited to targets that are eligible under the frozen criterion. An apparent act-less change followed by a legitimating act is scored as legitimated, not as a norm change without an act.
- **P8-HB:** limited to targets that are active under the frozen definition.

**(5) Frozen rule.**
> **FR-D8.**
> - P7-HB requires `r72_gate_in_force_at_t_w` = IN_FORCE (NIF, or the graded fallback).
> - P5-HD requires `capability_norm_in_force_at_t_R94`.
> - A P1 target is **eligible** iff a norm-making act recorded before t1 made its deontic state conditional on a stated work condition, and a system-recorded artefact inside W shows that condition met under a satisfaction rule written per clause before Stage 1.
> - P1 records `response_or_legitimation`: any later act that ratifies, sanctions or reverses the apparent change, with its time.
> - A P8 target is **active** at t iff an authorization on it is in force at t (NIF or fallback), and no closure, acceptance, withdrawal or hold act on it is in force at t.

---

## D9: the C1 rule is asymmetric and under-specified

**(1) Defect, quoted.**
> "The C1 rule will probably return UNDETERMINED, and it is asymmetric. If R-72's proviso was unmet but no blocking text covers RED, the rule says UNDETERMINED. By R-72's own logic the conditional authorization never became operative, so the result should be NON-CONFORMS by default. No default reading of 'WP-4B REMAINS BLOCKED' is fixed for the likely case where no scoping text exists. Entry-into-force times for R-77 to R-80 are to come 'from adoption records', but these rulings may not be status-mediated at all, and no fallback is given. The MD and the JSON also scope the 'and' in the CONFORMS branch differently."

**(2) Correction.** (§2)
- **Default state:** WP-4B work is UNAUTHORIZED at 16:10 unless R-72's proviso is *shown* met, by texts in force before 16:10.
- **Default scope:** "WP-4B REMAINS BLOCKED" covers all WP-4B work, including producer-agnostic RED test code, unless a text in force before 16:10 explicitly narrows it.
- **Fallback for rulings that are not status-mediated:** entry into force is not earlier than the ruling's recording time, graded by §0.9. A stated date is SOURCE_STATED.
- **One structure** for CONFORMS: **P ∧ ¬B**. The MD version is kept, because R-72's authorization and R-77's block are separate conditions, and both must be satisfied.
- **UNDETERMINED** is reserved for two cases:
  - required texts cannot be obtained;
  - the time at which a relevant text entered force, relative to 16:10, is NOT_IDENTIFIABLE.
- If the texts are obtained and do not show the proviso met, the result is NON-CONFORMS by default.

**(3) Why this is sufficient.**
- **Asymmetry removed.** The default follows R-72's own conditional form: an authorization that is conditional on a proviso is operative only if the proviso holds. So the case "proviso unmet, no covering block" now yields NON-CONFORMS.
- **Default scope fixed.** The case with no scoping text now has a determinate reading.
- **Fallback added.** Rulings without an adoption record now have a time premise.
- **Consistency.** A single boolean structure makes the MD and the JSON identical.
- **Guard against unseen evidence.** UNDETERMINED remains only where a premise is actually unobservable. So the default is never applied to evidence that has not been seen: missing texts give UNDETERMINED, not NON-CONFORMS.

**(4) Does it change a scientific prediction?** Yes.
- **C1 / P2:** the likely verdict moves from UNDETERMINED to NON-CONFORMS, if R-73..R-76 are obtained and do not show the proviso met.
- **C1 bearing:** changes as in D6. It no longer requires that nothing responded before R-87.
- **G-D1 applies.** If the absence of a permitting text before 16:10 relies on R-81..R-85 having had no force until R-86 adopted them, the result is DISJUNCTIVE {H-D status clause, H-B conformance}.

**(5) Frozen rule.**
> **FR-D9.**
> - **P** = R-72's RED condition and proviso ("no unresolved governance blockers"; the named ARB decisions and scope ruling have landed) are shown met by texts in force before 16:10.
> - **B** = a text in force before 16:10 blocks WP-4B, read by default as covering all WP-4B work including producer-agnostic RED, and no text in force before 16:10 narrows that scope to exclude RED.
>
> Verdicts:
> - **CONFORMS** iff P ∧ ¬B.
> - **NON-CONFORMS** iff the required texts (R-72 to R-80) were obtained and ¬(P ∧ ¬B).
> - **UNDETERMINED** iff a required text could not be obtained, or the entry-into-force time of a text that decides P or B is NOT_IDENTIFIABLE relative to 16:10.
>
> Entry into force: an adoption form, if the ruling is status-mediated; otherwise, not earlier than its recording time. Stated dates are SOURCE_STATED.
>
> The 16:10 time of R11 is itself a git date (SOURCE_STATED). The verdict carries that label.

---

## D10: the P1 census and the P8 tendency are under-specified

**(1) Defect, quoted.**
> "The P1-HB census refutes only **H-B restricted to window W**, and the spec does not say so. Reading H-B's clause as existential is also an interpretive choice that is not labelled UNKNOWN. Under the other reading ('when the work condition is met, the state changes'), one clean counter-instance would be enough. The P8 'tendency ⇒ census' option gives no threshold, so it cannot be tested."

**(2) Correction.**
- **P1-HB:** label the quantifier reading UNKNOWN, and test both readings:
  - **Reading 1a (existential):** the census over W. A negative result is stated as "H-B reading 1a is not instantiated in W". It is never stated as "H-B refuted".
  - **Reading 1b (conditional-universal):** one eligible target whose work condition is met, and whose state is unchanged by NIF until a separate act, refutes 1b.
- **P8:** drop the tendency reading. No source gives a threshold, and inventing one would be arbitrary. P8 is tested as a universal. If H-B's authors intend a tendency, P8 is declared NOT_TESTABLE_AS_SPECIFIED and is not scored.

**(3) Why this is sufficient.**
- The logical force of each test now matches its quantifier:
  - An existential claim is refuted only by exhausting its domain, so the conclusion is limited to the exhausted domain W.
  - A conditional-universal claim is refuted by one instance.
- Reporting both readings keeps the interpretation from being decided after the fact.
- Dropping the tendency reading removes the only untestable option. Keeping it without a threshold would have left P8 unfalsifiable.

**(4) Does it change a scientific prediction?** Yes.
- **P1-HB:** the conclusion is scoped to W, and a second, stronger test (1b) is added.
- **P8-HB:** only the universal reading remains.

**(5) Frozen rule.**
> **FR-D10.** P1-HB is scored under both readings.
> - 1a: "not instantiated in W" or "instantiated".
> - 1b: FIRED if at least one eligible counter-instance is found (with NIF).
>
> Neither result is generalized beyond W. P8 is universal only. The tendency reading is NOT_TESTABLE_AS_SPECIFIED.

---

## D11: attestation fields assume artefacts that no input establishes

**(1) Defect, quoted.**
> "The feasibility section is hedged, but the **attestation fields assume** artefacts that no input establishes: CI runs with CI start times; 'server push time' (git history does not record it); 'adopted' R-86 and R-90 (R-90 appears in the inputs in a withdrawal context); adoption records for R-77, R-79 and R-80; 'DA session logs'; version control of IMPLEMENTATION_PROGRESS.md; a register kept as a git file. STATE lists only a register, a few session logs and **one** commit."

**(2) Correction.**
- Phrase every such field as "if such an artefact exists".
- Give a fallback for each, with its grade, in the table in §0.11.
- Mark R-90's status as UNKNOWN.
- Note that git dates (including R11's 16:10) are client-set (§0.9).

**(3) Why this is sufficient.**
- The defect is an existence assumption hidden inside field definitions.
- After the correction, every field is defined whether or not its preferred artefact exists: either the fallback applies with a stated grade, or the field is UNDETERMINED.
- So no result can depend silently on an artefact that no input shows exists.

**(4) Does it change a scientific prediction?** No. Some outcomes are downgraded, for example to FIRED-CONDITIONAL or UNDETERMINED, when the preferred artefacts do not exist.

**(5) Frozen rule.**
> **FR-D11.** Every attestation names a preferred artefact "if it exists", a fallback, and the grade of the fallback (§0.11). A field whose preferred and fallback sources are both missing is UNDETERMINED. The capture reports which artefacts exist.

---

## D12: P7-HD depends on motive; P8-HD repeats P1

**(1) Defect, quoted.**
> "P7's H-D falsifier ('treated as authorized **because** the predecessor was accepted') depends on motive and cannot be observed as behaviour. It also cannot fire in the R-89 episode, because an authorizing act (R-89) exists. P8's H-D falsifier just repeats P1."

**(2) Correction.**
- **P7-HD:** NOT_APPLICABLE to the R-89 episode.
- **P7-HD in other episodes:** restated in behavioural and NIF terms. NIF shows the successor authorized and in force, and no norm-making act in a complete DCS produced that authorization. This *is* P1 on the gate, and it is scored under P1 only.
- **P8-HD:** points to P1 and is not scored separately.

**(3) Why this is sufficient.**
- The motive clause ("because") is removed, so every remaining condition is observable.
- In the R-89 episode, the premise "no authorizing act" is false by construction, since R-89 exists as a text. So declaring the limb NOT_APPLICABLE is exact.
- Scoring P7-HD and P8-HD under P1 only removes the double counting.

**(4) Does it change a scientific prediction?** Yes.
- **P7-HD:** it is dropped for the R-89 episode, and elsewhere merged into P1.
- **P8-HD:** it is merged into P1.
- H-D's operational predictions are therefore tested only through P1, P4 and P5.

**(5) Frozen rule.**
> **FR-D12.** P7-HD is NOT_APPLICABLE when any norm-making act authorizing the successor exists in the DCS, whatever that act's status. Otherwise, P7-HD and P8-HD are instances of P1-HD and are scored only there.

---

## D13: the status-mediation upgrade is only partial

**(1) Defect, quoted.**
> "Item 1 (an adoption entry written by the adopter) is still the norm-maker's own act; only its system time and authenticated identity are independent. The behavioural contrast (item 3) is empty unless work was ready and waiting during the prepared interval. As written, the upgrade moves status mediation only **part of the way** to independent observation."

**(2) Correction.** (§3)
- **Item 1:** relabelled as the norm-maker's own act. Only its recording time and its AUTH_ID are independent (FR-D7).
- **New item 0:** NIF-A for status, if Part C finds such a channel.
- **Item 2:** uptake is restricted to NIF-B (third-party application). NIF-C is recorded only.
- **Item 3:** the contrast gets an **eligibility condition**. During the prepared interval, a work item that depends on the ruling was *pending*: its preconditions were met, and it was queued or planned, attested by an artefact with a system-recorded time. A contrast without such an item is VACUOUS and is not scored.
- **Absence of work** in the prepared interval requires a complete execution channel (FR-D5e).

**(3) Why this is sufficient.**
- The upgrade was partial on two counts:
  - Its first item was not independent. The correction now claims only what is independent (time and identity), and it adds the only fully independent source (NIF-A).
  - Its contrast could be vacuous. The eligibility condition ensures that "no execution during the prepared interval" is informative: work was ready, so its absence is a behavioural difference and not a lack of opportunity.

**(4) Does it change a scientific prediction?** Yes, for H-D's status clause (P4). An observed contrast now counts only if it is eligible. H-B's predictions are not affected, because H-B is silent here (SOURCE FACT: IDENTIFIABILITY P4).

**(5) Frozen rule.**
> **FR-D13.** Status mediation counts as independently observed only if:
> - (0) NIF-A records the status transition, **or**
> - (1) an adoption form has a recording time and AUTH_ID, **and** (2) NIF-B uptake follows adoption, **and** (3) an **eligible** contrast is present: pending dependent work existed during the prepared interval, and a complete execution channel shows no execution on it and no NIF-B application before adoption.
>
> Otherwise, status mediation stays SELF_REPORTED or PARTIALLY_INDEPENDENT.

---

## D14: labels and optional items

**(1) Defect, quoted.**
> "The ranking is labelled INFERENCE but is a judgement (PROPOSAL). The R-86 'waiting' quote is a SOURCE FACT; reading it as a behavioural instance is INFERENCE. P4 lists R-88's text, which only orders the rulings and says nothing about force. P7's GREEN/VERIFY CI run serves C2's evidence limb, not the gate falsifier. The 'input conflict' may be only apparent: '§WP-4 remains OPEN' may refer to WP-4 as a whole, not WP-4B, and C2's textual order does not establish the order of entry into force."

**(2) Correction.**
- **Ranking:** relabelled PROPOSAL.
- **R-86 "waiting":** the quote is SOURCE FACT; reading it as a behavioural instance is INFERENCE.
- **R-88 text:** OPTIONAL.
- **GREEN/VERIFY run:** OPTIONAL, and serves C2's evidence limb only.
- **Input conflict:** restated as "possibly only apparent", for two reasons. "§WP-4 remains OPEN" may refer to WP-4 as a whole. And C2 orders texts, which is SOURCE_STATED_ORDER of drafting, not an order of entry into force.

**(3) Why this is sufficient.** Each item is a mislabel or a wrongly required field. Relabelling or marking a field OPTIONAL fixes each one completely, and none of them is a premise of a falsifier.

**(4) Does it change a scientific prediction?** No.

**(5) Frozen rule.**
> **FR-D14.** Judgements are labelled PROPOSAL. Readings of quotes are labelled INFERENCE. Fields that are not premises of any falsifier are marked OPTIONAL. Textual order is labelled SOURCE_STATED_ORDER, and it never establishes the order of entry into force.

---

# PART A (continued). The complete corrected specification 1bb-r1

## §0. Scope and shared rules

### §0.1 Scope [UNCHANGED]
- **SOURCE FACT:** No prediction is identified on the current corpus (IDENTIFIABILITY `identified: []`).
- **PROPOSAL:** A prediction is in scope if some observation puts one hypothesis at risk while the other survives it. In scope: **P1** (only under H-B reading 1), **P2, P4, P5, P7, P8**. Excluded:
  - **P3**: act-mediated feedback fits both hypotheses, and its act-less part is P1.
  - **P6**: both hypotheses are met at every record boundary.
- **[NEW D1]** Being in scope is necessary but not sufficient. Each prediction's discrimination status also depends on NIF availability (§1.7).

### §0.2 Labels [UNCHANGED, extended]
- SOURCE FACT, INFERENCE, PROPOSAL and UNKNOWN, as in 1bb.
- **[NEW D1, D5, D11]** Grades, order labels and categories are defined in §0.3, §0.4 and §0.9.

### §0.3 The six distinctions [NEW D1; HUMAN-DECISIONS]
These categories are recorded separately for every episode. None is inferred from another, except by the rules stated here.

| Code | Distinction | What can establish it | What can never establish it |
|---|---|---|---|
| **N-EXISTS** | The norm exists: a text of the norm is recorded (including PREPARED texts). | The record's text. Its time is a recording time (§0.9). | — |
| **N-FORCE** | The norm is in force at t. | NIF-A, or NIF-B (labelled N-FORCE[OPERATIONAL]). The graded fallback (§0.4) gives at most FIRED-CONDITIONAL. | The ruling's self-assertion; statements by the maker; recording time; executor behaviour; N-EXISTS alone. |
| **N-KNOWN** | The norm is known, or claimed, to be in force by actor X. | X's statement: NIF-C if X is a third party; MAKER_FORCE_ASSERTION if X is the maker; SELF_ASSERTION if the statement is inside the ruling. | It never establishes N-FORCE. |
| **ACT-CONF** | An act according to the norm: an execution at t whose operation is permitted by the norms in force at t⁻. | An execution trace plus N-FORCE of a permitting norm at t⁻. | An execution trace alone. |
| **ACT-CONTRA** | An act contrary to the norm: an execution at t whose operation is not permitted at t⁻. | An execution trace plus N-FORCE of a prohibiting norm at t⁻, or the absence of any permitting norm in force at t⁻ (which requires a complete DCS, §0.8). | An execution trace alone. |
| **N-CHANGE-NOACT** | The norm changes without a norm-making act. | N-FORCE at t1 ≠ N-FORCE at t2, both by NIF; plus a complete DCS showing no norm-making act in [t1,t2], no prior conditional act producing the change, and no late-recorded act claiming effect in [t1,t2]. | A work event alone (HUMAN-DECISIONS). A ruling asserting its own adoption. |

### §0.4 Independent observables of the norm in force (NIF) and rule G-D1 [NEW D1]
- **NIF-A (independent norm-status record).** An entry in a channel that meets independence criteria I1–I4 (Part C §C.4) and records the *normative state* of the norm (in force or not in force) at t, with a system-generated time. Its availability is determined by Part C.
- **NIF-B (third-party application).** An act by an actor who is not the maker or adopter of the norm and not the executor of the work under test. The act applies the norm as binding: it grants, refuses, accepts or blocks on the norm's basis. It must carry AUTH_ID and a recording time.
  - It establishes N-FORCE[OPERATIONAL]. This is a PROPOSAL: application produces normative consequences, which are observable effects of force; citation does not.
  - If the application is later expressly voided as erroneous, its grade drops to FIRED-CONDITIONAL.
- **NIF-C (third-party citation).** A statement by such an actor that the norm governs, without applying it. It establishes N-KNOWN only. It is recorded, and it never fires a falsifier.
- **Not NIF:**
  - the executor's work under the norm (ACT);
  - the ruling's self-assertion ("effective immediately");
  - statements by the maker or adopter ("R-89 already governs authorization");
  - register recording times.
- **Graded fallback (maker's records).** This fallback is allowed only where §1.7 permits it. A premise about force may be taken from the norm-maker's records (text, adoption forms, recording times) in a DCS shown complete (§0.8). A falsifier built on such a premise is at most **FIRED-CONDITIONAL**, with the condition "the maker's records state force accurately".
- **G-D1.** If a premise "N (not) in force at t" is established only by (a) executor behaviour or (b) applying H-D's status rule (force only through adoption) to the maker's records, then every falsifier that uses it is graded **DISJUNCTIVE {H-D clause, H-B clause}**. This means "at least one fails; which one is not identified".

### §0.5 Attestation rule [CHANGED D7]
1B's three conditions are kept, and their scope is corrected.
- **Observation versus attestation.** A record R of an act is the observation of that act. R may attest only two things about itself:
  - its writer identity, graded AUTH_ID or ACTOR_SET_ID;
  - its recording time, graded by §0.9.

  R never attests its stated effective date or the norm's force.
- A field of any other observation is **independently attested** only if its attesting artefact:
  1. has a writer different from the maker, the adopter and the executor **[UNCHANGED in substance]**;
  2. does not copy the ruling's self-statements. Artefacts that *cite* the ruling are admissible as *observations* of uptake (NIF-B, NIF-C, ACT) **[CHANGED D7]**;
  3. carries a system-generated timestamp. A committer or author date is client-set, and is SOURCE_STATED (§0.9) **[CHANGED D11]**.
- Anything a ruling says about itself is self-report: "effective immediately", "R-89 already governs" **[UNCHANGED]**.
- **SOURCE FACT:** recording time ≠ decision time ≠ entry into force **[UNCHANGED]**. **[CHANGED D5, D9]** Entry into force comes from an adoption form (AF-1 to AF-3) when the ruling is status-mediated. Otherwise it is "not earlier than its recording time". It never comes from the time a row was recorded, taken as a decision time.

### §0.6 Declared channel set and capability register [CHANGED D5]
- **Capability register v0** **[CHANGED D5(f)]**. This is frozen before acquisition, not before reading: R-86 and R-90 have been read through the inputs.
  - **SOURCE FACT:** R-86 names the Decision Authority (DA) as the only adopter.
  - **SOURCE FACT (STATE):** Chief-prepared rulings gain force when the DA adopts them.
  - **UNKNOWN:** who is capable of making operational dispositions (R-94 type). The operational/constitutional split is known only through IDENTIFIABILITY's reading of R-90, which appears in a withdrawal context. It is resolved only by R-90's text and status in Stage 1. If it remains unresolved, P5 is NON-DISCRIMINATING.
- **Declared channel set (DCS) for norm-making acts, v0** **[CHANGED D5(a)]**:
  - **K-REG**: the register of rulings, and its write history if it is versioned.
  - **K-SESS**: session logs (S0804, S0815, and later ones).
  - **K-PROG**: IMPLEMENTATION_PROGRESS.md, which holds Chief dispositions and execution reports.

  DCS v1 = DCS v0 plus any channel for which Part C returns Q1 = YES and Q2 ∋ {ADOPTION_EVENT, APPROVAL_EVENT, NORM_STATE}. DCS v1 is closed before Stage 1.
- **"No act"** means "no norm-making act in DCS v1 in the window, with DCS v1 shown complete (§0.8)". It never means proving that no unrecorded act happened anywhere **[UNCHANGED in intent]**.
- **OUT_OF_CHANNEL_ACT** **[NEW D5(a)]**: a norm-making act found outside DCS v1. It shows that DCS v1 is incomplete for that target, and it makes the "no act" premise UNDETERMINED. It is never scored as N-CHANGE-NOACT.
- **Late recording** **[NEW D5(b)]**: see §0.9.
- **Adoption forms** **[NEW D5(c)]**. This list is exhaustive.
  - **AF-1**: the status annotation of the ruling row changes to ADOPTED (or "adopted"), written under the DA's identity.
  - **AF-2**: a separate entry by the DA that names the ruling id or ids and contains an adoption performative ("adopt", "adopted", "hereby adopts").
  - **AF-3**: a DA ruling that declares named prepared rulings governing, in performative form. This is the R-86 pattern: "These become governing rulings effective immediately".
  - **Not an adoption:** a descriptive assertion by the maker that force already exists, such as "R-89 already governs authorization". This is MAKER_FORCE_ASSERTION.
  - **Sensitivity:** the outcome under the opposite classification is reported, but never scored.
- **Identity** **[NEW D5(d)]**:
  - **AUTH_ID**: a signature verified against a key list fixed before Stage 1, or an account-bound record on the hosting server (an approval, merge or audit event).
  - **ACTOR_SET_ID**: git author or committer fields, and names in text.

  If an identity-dependent premise has only ACTOR_SET_ID, the grade is at most FIRED-CONDITIONAL.

### §0.7 Execution-channel register [NEW D5(e)]
- **Execution channel EC v0:**
  - the git repository or repositories named by the human before Part B runs (Part B §1);
  - CI run records, if Part C finds that they exist.
- An **execution trace** is defined in Part B §3.
- An **absence of work** on target X is admissible only if EC is shown complete for X (§0.8). Otherwise it is NOT-EVIDENCED-IN-CAPTURE.

### §0.8 Absence and completeness [NEW D2, D5]
An absence claim ("no act", "no adoption", "no waiver", "no work", "no response") counts as a falsifier premise only if the channel is shown complete over the window. That requires all four of the following:
1. **Coverage:** the channel is *required* for that kind of event, by a rule in force over the window, shown by NIF-A or by configuration enforced by the host (Part C Q6).
2. **Capture:** the capture is not shallow or partial, and its refs equal the server's refs at capture time.
3. **Retention:** records covering the window are retained.
4. **Integrity:** there is no undetected deletion or rewrite in the window, shown by an audit log or by tamper-evidence.

Otherwise the absence is **NOT-EVIDENCED-IN-CAPTURE**, and a falsifier that needs it is **UNDETERMINED**.

### §0.9 M-TIME: order labels, late recording, grades [NEW D4, D5, D11]
- **Order labels**, for any claimed order between two events:
  - **RECORDING_ORDER**: an order of recording established by the recording system's structure. Example: git ancestry, where a child commit embeds its parent's hash, so the parent existed first.
  - **SOURCE_STATED_ORDER**: an order stated by a source, or by metadata set by an actor. Examples: git author and committer dates, dates written in texts, the textual order of citations.
  - **INFERRED_ORDER**: an order derived by combining two or more premises of different kinds, for example ancestry plus a stated date.
  - **NOT_IDENTIFIABLE**: none of the above holds, or the premises contradict each other (for example, a clock anomaly against ancestry).
- **None of these labels establishes a decision time or entry into force.** An order relative to entry into force needs N-FORCE evidence (§0.3).
- **Late recording** **[D5(b)]**. An act whose recording time is after window W, but whose text states an effect inside W, makes both "no act in W" and "act in force in W" **NOT_IDENTIFIABLE** for that target.
- **Falsifier grades:**
  - **FIRED**: all premises are established by NIF-A or NIF-B, with AUTH_ID, and absences are shown complete.
  - **FIRED-CONDITIONAL(conditions)**: fires given listed weaker premises (SOURCE_STATED times, ACTOR_SET_ID, the maker's records).
  - **DISJUNCTIVE{…}**: by G-D1.
  - **NOT-FIRED**: premises established, and the falsifier condition is false.
  - **UNDETERMINED**: some premise is NOT_IDENTIFIABLE, or an absence is NOT-EVIDENCED-IN-CAPTURE.
  - **NOT_APPLICABLE**: the falsifier's precondition cannot hold in the episode.

### §0.10 Independence of episodes [UNCHANGED]
Two episodes are independent if all of these hold:
- they concern different targets or work packages;
- they are governed by different acts;
- different adoption acts bring those acts into force;
- their execution artefacts are separate (not successive commits of one batch or session);
- their attestation sources are not copies of each other.

### §0.11 Artefact existence and fallbacks [NEW D11]

| Field or assumption in 1bb | Preferred artefact (if it exists) | Fallback | Grade of fallback |
|---|---|---|---|
| CI start time | CI run record from the CI system | Commit dates plus ancestry | SOURCE_STATED / RECORDING_ORDER |
| Server push time | Hosting server audit or event record | None in git | UNDETERMINED |
| "Adopted" R-86 | AF-1/2/3 entry for R-86 with recording time | R-86 text as quoted (SOURCE FACT: names the DA as sole adopter) | FIRED-CONDITIONAL premise |
| "Adopted" R-90 | R-90 text plus status | **UNKNOWN** (withdrawal context) | P5 is NON-DISCRIMINATING while unresolved |
| Adoption records for R-77, R-79, R-80 | AF-1/2/3 | Recording time of the ruling as "not earlier than" | SOURCE_STATED unless versioned |
| DA session logs | Session logs with system time | S0804 and S0815 as known session logs | SOURCE_STATED |
| IMPLEMENTATION_PROGRESS.md under version control | Revision introducing line 58 | Line-58 text as a report | Execution for P5 becomes a report only (MENTION/REPORT); P5 is UNDETERMINED |
| Register kept as a git file | Write history of the register file | Register text with its stated dates | No write history; "no act" absences are NOT-EVIDENCED-IN-CAPTURE |
| R11 time "16:10" | CI or server record | Git author and committer dates | SOURCE_STATED |

---

## §1. Specifications

### P1: norm changes occur only through norm-making acts [CHANGED D1, D5, D8, D10]
- **Target and claim [UNCHANGED].**
  - **H-D:** "only through acts".
  - **H-B, reading 1:** work changes a norm with no act.
  - **UNKNOWN:** which reading of H-B is intended. Under reading 2 (acts triggered by work), P1 cannot discriminate even in principle.
- **Claim [UNCHANGED]:** every change in a target's deontic state is preceded by an act in the DCS whose content produces that state. Conditional content counts if the act stated the condition in advance.
- **Fields.**
  - `target` [UNCHANGED]
  - `norm_state_before` **[CHANGED D1]**: N-FORCE at t1 by NIF-A or NIF-B. The last execution or refusal is recorded as ACT only.
  - `norm_state_after` **[CHANGED D1]**: N-FORCE at t2 by NIF-A or NIF-B. "First execution relying on the new state" is recorded as ACT only, and is not an after-state.
  - `acts_in_DCS over [t1,t2]` **[CHANGED D5]**: writer (AUTH_ID or ACTOR_SET_ID), act id, content, recording time, adoption form, and late-recording flag.
  - `DCS_completeness over [t1,t2]` **[NEW D5]**
  - `prior_conditional_clauses` [UNCHANGED] (e.g. R-72, R-79)
  - `work_event` **[CHANGED D5(e)]**: an execution trace (Part B §3) in EC.
  - `capability_register` **[CHANGED D5(f)]**: v0 (§0.6).
  - `eligibility_criterion` **[NEW D8]**
  - `response_or_legitimation` **[NEW D8]**
- **NIF requirement [NEW D1]:** NIF-A or NIF-B for both before-state and after-state. Without it, P1 is **NON-DISCRIMINATING**: engineering uptake of a new state is either ACT-CONTRA against an unchanged norm, or N-CHANGE-NOACT.
- **Min episodes.**
  - **H-D: 1** [UNCHANGED].
  - **H-B [CHANGED D10]:** reading 1a needs a census of all eligible targets in W, with at least 1 eligible target. Reading 1b needs 1.
- **Falsifier H-D [CHANGED D1, D5, D8].** All of the following hold:
  - NIF shows state S1 at t1 and S2 ≠ S1 at t2, after an execution trace on the target;
  - DCS v1 is complete over [t1,t2] and shows no norm-making act producing S2;
  - no prior conditional act made S2 conditional on that work;
  - no late-recorded act claims an effect in [t1,t2];
  - no OUT_OF_CHANNEL_ACT exists;
  - `response_or_legitimation` shows no act that ratifies the change. If such an act exists, the case is scored LEGITIMATED and not N-CHANGE-NOACT.
- **Falsifier H-B (reading 1 only) [CHANGED D10].**
  - **1a:** the census over W finds that every eligible target whose work condition was met changed its N-FORCE state only at, or after, a separate DCS act. The conclusion is "H-B reading 1a not instantiated in W". It is scoped to W and is never generalized.
  - **1b:** one eligible target whose work condition was met, where NIF shows the state unchanged until a separate act.
  - In both, "engineering executed nothing on the new state before that act" is corroborating. It requires a complete EC. The judgement uses behaviour only.
  - **SOURCE FACT:** R-86 says "engineering waiting solely on a constitutional act". **[RELABELLED D14] INFERENCE:** reading this as a behavioural instance of waiting.
- **Window [UNCHANGED, with D4 lower bound].**
  - Per episode: from the earliest recorded trace of the work event to the first NIF-established state change, or the next DCS act on the target, whichever is earlier.
  - Census W: from the earliest corpus record to 2026-08-15 (SOURCE FACT: S0815 is the latest record). Fixed before Stage 1 **[CHANGED D5(f)]**.
- **Discrimination status [NEW D1]:** DISCRIMINATING only under H-B reading 1 **and** with NIF. Otherwise NON-DISCRIMINATING.
- **Information gain per effort [RELABELLED D14 → PROPOSAL]:** LOW.

### P2: work conforms to the norm in force [CHANGED D1, D6, D9, D11]
- **Target [UNCHANGED]:** H-B. It is one-sided, because H-D makes no conformance prediction.
- **Claim [CHANGED D6]:** every execution event on a target occurs at a time when the norm in force at t_exec⁻ permits that operation on that target. The 1bb clause "if not, the system responds before acceptance" is **[REMOVED D6 → moved to P8]**.
- **Fields.**
  - `execution_event` **[CHANGED D11]**: actor (AUTH_ID or ACTOR_SET_ID), diff, time (SOURCE_STATED git dates; a CI record if it exists).
  - `operation_class` [UNCHANGED]: from the diff, classified by a rule fixed before inspection (test paths only, or also production paths).
  - `norm_in_force_at_t_exec⁻` **[CHANGED D1]**: NIF-A or NIF-B. The allowed fallback is the maker's texts R-72 to R-80 in a complete DCS, which gives at most FIRED-CONDITIONAL.
  - `status_rule_dependence` **[NEW D1]**: does "not permitted" rely on a prepared, non-adopted permitting text having no force (for example, R-81..R-85 before R-86)? If yes, G-D1 gives DISJUNCTIVE.
  - `scope_determination` **[CHANGED D9]**: default reading per FR-D9. A later interpretive ruling is recorded as post hoc.
  - `waiver_in_force_at_t_exec⁻` **[NEW D6]**
  - `response` **[REMOVED D6 → P8]**
- **NIF requirement [NEW D1]:** N-FORCE of the non-permission at t_exec⁻, by NIF-A or NIF-B, or by the graded fallback. If the non-permission holds only under H-D's status rule, the result is **DISJUNCTIVE {H-D status clause, H-B conformance}**. Without a NIF or the fallback, it is UNDETERMINED.
- **Min episodes [UNCHANGED]:** 1.
- **Falsifier H-D [UNCHANGED]:** none. P2 is one-sided.
- **Falsifier H-B [CHANGED D6]:** an execution whose operation class is not permitted by the norm in force at t_exec⁻, with no waiver in force at t_exec⁻. Later responses are not considered here (see P8).
- **Window [CHANGED D6]:** the norm-in-force interval, from the earliest trace of the relevant norms to t_exec. There is no response window.
  - For R11: norms up to 2026-08-03 16:10. That time is a git date, so it is SOURCE_STATED **[CHANGED D11]**.
- **Discrimination status:** ONE-SIDED (H-B only).
- **Information gain per effort [RELABELLED D14 → PROPOSAL]:** HIGH.

### P4: force depends on the status of the making act [CHANGED D1, D4, D5, D7, D14]
- **Target [UNCHANGED]:**
  - **H-D:** a PREPARED ruling has no force until it is adopted.
  - **H-B:** silent (one-sided).
- **Fields.**
  - `making_act_text` **[CHANGED D14]**: R-89 (required); R-87 (for P7); R-88 **OPTIONAL**, since it orders the rulings and says nothing about force.
  - `status_sequence` **[CHANGED D5, D7]**: adoption forms AF-1/2/3 only. Each is attested for writer (AUTH_ID or ACTOR_SET_ID) and recording time, never for a stated effective date. Late-recording flags are recorded.
  - `adopting_actor` **[CHANGED D5(d)]**: checked against capability register v0.
  - `force_indicator` **[CHANGED D1]**: NIF-A or NIF-B for R-89 during the interval when it was not adopted. NIF-C is recorded only.
  - `work_citing_R89` **[CHANGED D1]**: recorded as ACT only. It is **not** a force indicator.
  - `maker_force_assertions` **[NEW D5(c)]**: e.g. "R-89 already governs authorization", recorded as MAKER_FORCE_ASSERTION.
- **NIF requirement [NEW D1]:** NIF-A or NIF-B showing R-89 in force. Without it, P4 is **NON-DISCRIMINATING**. Work under R-89 alone feeds only the DISJUNCTIVE outcome O4 in §5.
- **Min episodes [UNCHANGED]:** 1.
- **Falsifier H-D [CHANGED D1, D5].** All of the following hold:
  - DCS v1 is complete up to t and shows no adoption form for R-89;
  - no late-recorded adoption claims an effect ≤ t;
  - NIF-A or NIF-B shows R-89 in force at t.

  The DA's own statements never count.
- **Falsifier H-B [UNCHANGED]:** none on P4. **[CHANGED D1]** Combined with P7, it is scored only through the outcome table in §5.
- **Window [CHANGED D4]:** from the earliest recorded trace of R-89 in any DCS channel to min(first adoption or withdrawal form, 2026-08-15).
- **Discrimination status:** ONE-SIDED (H-D only). It requires NIF.
- **Information gain per effort [RELABELLED D14 → PROPOSAL]:** HIGH if Stage 0 finds a trace and NIF is available. LOW otherwise.

### P5: norms are made by actors holding the capability [CHANGED D1, D4, D5, D8, D11]
- **Target [UNCHANGED]:** H-D (capability). R-94 is the candidate. H-B is coupled through P2.
- **Fields.**
  - `capability_norm_in_force_at_t_R94` **[NEW D8]**: NIF, or the fallback of R-86 and R-90 texts and status. R-90 is UNKNOWN **[D11]**.
  - `norm_type_of_R94` [UNCHANGED]: constitutional or operational, per the R-90 text if obtained.
  - `disposing_actor_and_act` **[CHANGED D5(d)]**: AUTH_ID or ACTOR_SET_ID.
  - `execution_under_R94` **[CHANGED D11]**: the revision of IMPLEMENTATION_PROGRESS.md that introduced line 58, *if versioned*. Otherwise it is a MENTION/REPORT only, and P5 is UNDETERMINED.
  - `R94_effect_NIF` **[NEW D1]**
  - `adoption_or_absence in DCS v1` **[CHANGED D5]**
- **NIF requirement [NEW D1]:**
  - The "effective" branch needs NIF-A or NIF-B that R-94 was applied as binding by a third party.
  - The "not in force" branch needs NIF-A, or the fallback, that R-94 was not in force.
  - With execution only, the result is **DISJUNCTIVE {H-D capability clause, H-B conformance}**.
  - If the capability norm cannot be resolved, P5 is NON-DISCRIMINATING.
- **Min episodes [UNCHANGED]:** 1.
- **Falsifier H-D [CHANGED D1, D8].** All of the following hold:
  - `capability_norm_in_force_at_t_R94` requires DA adoption for R-94-type dispositions;
  - DCS v1 is complete and shows no adoption;
  - NIF-A or NIF-B shows R-94 in force.
- **Falsifier H-B, via P2 [CHANGED D1, D6]:** NIF (or the fallback) shows R-94 not in force at execution, and the execution is attested. The P2 grading applies, and the response is scored under P8.
- **INFERENCE [UNCHANGED]:** the likely outcome is operational scope (R-90), in which neither hypothesis is at risk.
- **Window [CHANGED D4]:** from the earliest recorded trace of R-94 to the attested execution. The response then goes to the P8 window.
- **Information gain per effort [RELABELLED D14 → PROPOSAL]:** MEDIUM in principle. The likely outcome discriminates nothing.

### P7: successor work is gated on the predecessor's acceptance [CHANGED D1, D2, D4, D6, D8, D12, D14]
- **Target and claim [UNCHANGED]:** H-B. Successor work starts only after the predecessor's acceptance takes force. R-72: "after its predecessor is accepted".
- **Fields.**
  - `predecessor_acceptance` **[CHANGED D1, D7]**: R-87 text. Its N-FORCE comes from NIF-A or NIF-B, or from the fallback of adoption form plus recording time. If "not in force" relies on R-87 not yet being adopted, G-D1 applies.
  - `WP4B_GREEN_VERIFY_run` **[CHANGED D14 → OPTIONAL]**: this serves C2's evidence limb only.
  - `r72_gate_in_force_at_t_w` **[NEW D8]**
  - `R89_status_and_text` **[CHANGED D2]**: includes whether R-89 waives or supersedes the gate.
  - `first_WP4C1_execution_trace` **[CHANGED D2, D4]**: the rank-0 output (Part B). Its time and order carry labels per §0.9.
  - `link_work_to_R89` **[NEW D2(a)]**: whether the trace cites R-89 (message, trailer or diff).
  - `waiver_in_force_at_t_w⁻` **[CHANGED D2(b), D6]**: from the full DCS v1 history over the window.
  - `third_party_NIF_B / NIF_C` **[NEW D2(d)]**
- **NIF requirement [NEW D1]:** N-FORCE, by NIF-A, NIF-B or the graded fallback, of three things:
  - WP-4B acceptance *not* in force at t_w⁻;
  - R-72's gate in force at t_w⁻;
  - no waiver in force.

  If "acceptance not in force" rests only on H-D's status rule, the result is DISJUNCTIVE. Without a NIF or the fallback, it is UNDETERMINED.
- **Min episodes [UNCHANGED]:** 1. The draft's "2 pairs" is not needed to discriminate.
- **Falsifier H-D [CHANGED D12]:** NOT_APPLICABLE to the R-89 episode, because an authorizing act exists. In other episodes it is an instance of P1-HD and is scored only there.
- **Falsifier H-B [CHANGED D6, D8].** All of the following hold at t_w⁻, where t_w is the time of the earliest WP-4C-1 execution trace:
  - WP-4B's acceptance is not in force;
  - R-72's gate is in force;
  - no waiver is in force. This includes R-89, if R-89 is in force and its text waives or supersedes the gate.

  The 1bb clause "no response before WP-4C-1 is accepted" is **[REMOVED D6 → P8]**.
- **Window [CHANGED D4]:** [min(earliest recorded trace of R-72, earliest WP-4C-1 execution trace), min(WP-4C-1 acceptance in force, 2026-08-15)].
- **Information gain per effort [RELABELLED D14 → PROPOSAL]:** HIGH if Stage 0 finds a trace. LOW if not.

### P8: failures and deviations feed back into remediation [CHANGED D6, D8, D10, D12]
- **Target and claim [CHANGED D6].** H-B. After a verified failure **or deviation**, including non-conformance (for example R11, if C1 = NON-CONFORMS), on an **active** target, a remediation act or an explicit deferral with a trigger takes force before work on that target resumes.
  - **Source:** the draft P8 fields in IDENTIFIABILITY say "verified failure/deviation record".
- **Fields.**
  - `failure_or_deviation_record` [UNCHANGED]: a failing CI run, or a commit or log entry. It must not come from the ruling that responds to it. R-91 is the candidate.
  - `active_target` **[NEW D8]**: defined in FR-D8.
  - `next_execution_on_same_target` [UNCHANGED]: an execution trace.
  - `remediation_or_deferral_in_force` **[CHANGED D1]**: NIF, or the fallback in a complete DCS.
  - `references_to_R11_by_R91_R94` **[NEW D6]**
  - `late_responses` **[NEW D6]**
- **NIF requirement [NEW D1]:**
  - "No remediation or deferral in force before resumption" needs a complete DCS v1, or NIF-A.
  - "Active target" needs N-FORCE of the authorization.

  Otherwise, P8 is UNDETERMINED.
- **Min episodes [CHANGED D10]:** 1, under the universal reading only. The tendency reading is dropped and is NOT_TESTABLE_AS_SPECIFIED.
- **Falsifier H-D [CHANGED D12]:** this is P1. It is scored only under P1.
- **Falsifier H-B [UNCHANGED in substance].** A verified failure or deviation on an active target, then a further execution trace on that target, with no remediation or deferral-with-trigger in force in between.
- **Window [UNCHANGED, extended D6].** From the recording time of the failure or deviation to the next execution trace on the same target.
  - If no further execution occurs, the falsifier is not triggered.
  - Responses after the window are recorded as LATE_RESPONSE.
  - For R11: from 16:10 (SOURCE_STATED) to the next WP-4B execution trace.
- **Information gain per effort [RELABELLED D14 → PROPOSAL]:** LOW. It is one-sided and may need records after 2026-08-15.

### §1.7 NIF requirement matrix (how D1 is handled) [NEW D1]

| Pred. | Limb | Independent observable of the norm in force required | Without it |
|---|---|---|---|
| P1 | HD | N-FORCE before and after, by NIF-A or NIF-B | NON-DISCRIMINATING. Uptake is ACT only. |
| P1 | HB (1a, 1b) | N-FORCE unchanged until the act, by NIF-A or NIF-B, over the eligible targets | NON-DISCRIMINATING |
| P2 | HB | Non-permission at t_exec⁻, by NIF-A, NIF-B or the graded fallback, with no dependence on the status rule | DISJUNCTIVE {HD status, HB conformance} if it depends on the status rule; UNDETERMINED if there is no source |
| P4 | HD | R-89 in force while not adopted, by NIF-A or NIF-B | NON-DISCRIMINATING (work → O4 DISJUNCTIVE) |
| P5 | HD | R-94 applied as binding, by NIF-A or NIF-B, and the capability norm at t_R94 | DISJUNCTIVE if execution only; NON-DISCRIMINATING if capability is unresolved |
| P5 | HB (via P2) | R-94 not in force, by NIF-A or the fallback | DISJUNCTIVE |
| P7 | HB | WP-4B acceptance not in force, R-72 gate in force, and no waiver, at t_w⁻, by NIF-A, NIF-B or the fallback | DISJUNCTIVE if it rests on the status rule; UNDETERMINED otherwise |
| P7 | HD | — | NOT_APPLICABLE (R-89 episode); otherwise → P1 |
| P8 | HB | Remediation or deferral absent (complete DCS, or NIF-A); target active | UNDETERMINED |
| P8 | HD | — | → P1 |

### §1.8 Apparent conflict between the inputs [CHANGED D14]
- **INFERENCE (IDENTIFIABILITY P7):** R-89 very probably authorized WP-4C-1 before WP-4B was accepted ("§WP-4 remains OPEN").
- **SOURCE FACT (CONFORMANCE-CASE C2):** C2 orders R-87 (the WP-4B acceptance) before R-89 by text.
- **[NEW D14] The conflict may be only apparent**, for two reasons:
  - "§WP-4 remains OPEN" may refer to WP-4 as a whole, not WP-4B.
  - C2's textual order is SOURCE_STATED_ORDER of drafting. It does not establish the order of entry into force.
- Only the N-FORCE of R-87 and R-89 (§0.3) can settle this.

### §1.9 Feasibility [UNCHANGED, with D11]
These are *candidate* sources; this spec does not claim they contain the records. **[CHANGED D11]** Each applies "if it exists", with the fallbacks in §0.11.
- The write history of the register (K-REG), if it is versioned.
- Commits on WP-4B and WP-4C-1 (EC). CI runs, if they exist.
- Revisions of `IMPLEMENTATION_PROGRESS.md`, if it is versioned.
- The queue, work plan and `CONTEXT.md`.
- Session logs S0804, S0815 and any later ones.
- The texts and status of R-86 and R-90. R-90's status is UNKNOWN.
- **[NEW D1, D5]** Channels found by Part C.

---

## §2. (a) What would resolve the contested case (C1: the R11 commit "WP-4B RED") [CHANGED D6, D9]

**Acquire [UNCHANGED, with one addition]:**
- the full texts of R-73 to R-76, R-78 and R-80;
- entry-into-force times for R-77 to R-80, from adoption forms if they are status-mediated. **[CHANGED D9]** Otherwise, "not earlier than the recording time";
- the R11 diff, classified by a rule fixed in advance, with its time. **[CHANGED D11]** Git dates are SOURCE_STATED; a CI or server time is used if it exists;
- any scope text in force before 16:10;
- **[CHANGED D6]** R-87's text is **OPTIONAL** for C1. It is needed only for P8, as a candidate response;
- **[NEW D9, D1]** whether any permitting text (for example R-81..R-85) was recorded before 16:10 in PREPARED status. This feeds G-D1.

**PROPOSAL, decision rule [CHANGED D9]:** FR-D9.
- **P** = R-72's RED condition and proviso shown met by texts in force before 16:10.
- **B** = a blocking text in force before 16:10 covers the RED class. The default scope is all WP-4B work, and there is no narrowing text in force before 16:10.
- **CONFORMS** iff P ∧ ¬B.
- **NON-CONFORMS** iff the texts R-72 to R-80 were obtained and ¬(P ∧ ¬B). This includes the default case where P is not shown.
- **UNDETERMINED** iff a required text could not be obtained, or the entry into force of a text that decides P or B is NOT_IDENTIFIABLE relative to 16:10.

**Bearing [CHANGED D6, D1]:**
- NON-CONFORMS bears against H-B's conformance clause, whatever R-87 says.
- Grade: FIRED-CONDITIONAL(the maker's texts; the SOURCE_STATED 16:10) unless NIF is available.
- DISJUNCTIVE {HD status, HB conformance} if "no permitting text before 16:10" relies on status mediation (for example, R-81..R-85 governing only via R-86).
- Any response to R11 is scored under P8.

## §3. (b) Moving status mediation from self-reported to independently observed [CHANGED D13]
- **SOURCE FACT [UNCHANGED]:** there are 2 episodes, and both are self-declared inside the rulings.
- **PROPOSAL:** acquire the following.
  - **0. [NEW D13]** NIF-A for the status transition, if Part C finds such a channel. This alone makes status independently observed.
  - **1. [CHANGED D13]** An adoption form (AF-1/2/3) in DCS v1. It is still **the norm-maker's own act**. Only its recording time and its AUTH_ID are independent.
  - **2. [CHANGED D1, D13]** Uptake after adoption by NIF-B (third-party application). NIF-C is recorded only. Executor work is recorded as ACT only.
  - **3. [CHANGED D13]** An **eligible** behavioural contrast:
    - during the prepared interval, a work item that depends on the ruling was pending: its preconditions were met, and it was queued or planned, attested by an artefact with a system-recorded time;
    - a complete EC shows no execution on that item;
    - there was no NIF-B application before adoption.

    A contrast without a pending item is VACUOUS and is not scored.
- **FR-D13:** status mediation is independently observed iff (0), or (1) ∧ (2) ∧ (3). **[UNCHANGED]** One contrary episode would falsify H-D's status clause. Even so, this only tests H-D, because H-B is silent.

## §4. Ranking by information gain per unit of acquisition effort [RELABELLED D14: PROPOSAL; CHANGED D2, D3]
0. **Stage 0, do first [NEW D2]:** Part B (rank-0 WP-4C-1 check) and Part C (scoping). Both are cheap, and both are prerequisites. Part C decides whether NIF-A exists and closes DCS v1 and EC.
1. **P4+P7 (R-89/R-87):**
   - HIGH if Stage 0 returns an execution trace **and** NIF-A or NIF-B is obtainable.
   - MEDIUM if a trace exists but only the graded fallback is available. Then P7-HB can be at most FIRED-CONDITIONAL, and P4 is NON-DISCRIMINATING.
   - LOW if O0 (no trace).
2. **P2 (R11):** HIGH. One-sided, but the execution is known to exist and the texts are named. It moves to rank 1 if O0.
3. **P5 (R-94):** MEDIUM. **[CHANGED D3]** It is compared with P4+P7 on likely outcome, not on two-sidedness. Its likely outcome is operational scope, where neither hypothesis is at risk. Its other branches need NIF, or they are DISJUNCTIVE. The 1bb claim that P4+P7 is "the only" two-sided small acquisition is **[REMOVED D3]**.
4. **P8 (R-91, R11):** LOW. It is one-sided and may need records after 2026-08-15.
5. **P1:** LOW.

## §5. The smallest discriminating acquisition, now two-stage [CHANGED D1, D2, D3, D4]
- **Stage 0.**
  - **S0-a:** Part B. Does a WP-4C-1 execution trace exist in the capture? It also yields the earliest trace, its labels, and whether the trace cites R-89.
  - **S0-b:** Part C. Does an independent norm-status channel exist? It also closes DCS v1 and EC.
- **Stage 1** (only if S0-a found at least one ET-1 or ET-2):
  - **S1-a:** R-89 text, including whether it waives or supersedes R-72's gate.
  - **S1-b:** the full DCS v1 write history over the P7 window (FR-D4): every AF-1/2/3 form, waiver and response entry for R-72, R-87, R-89, WP-4B acceptance and WP-4C-1, with writer and recording time.
  - **S1-c:** R-87 text and its N-FORCE (NIF, or the fallback).
  - **S1-d:** R-72 text and gate N-FORCE at t_w.
  - **S1-e:** NIF-A records for R-89 and R-87 at t_w, if Part C found a channel.
  - **S1-f:** NIF-B applications and NIF-C citations of R-89 or of WP-4B's acceptance by third parties, from DCS v1 and EC.
  - **S1-g:** the link from the earliest ET to R-89 (from the Part B message and trailer output).
- **Effort [CHANGED D2]:** the whole write history of DCS v1 over the window, plus the NIF channel. This is not "about 3 records".

**Outcome table (frozen before acquisition) [NEW D1, D2].** Let:
- t_w = the earliest execution trace;
- ACC = WP-4B acceptance in force at t_w⁻;
- G = R-72 gate in force at t_w⁻;
- WV = a waiver in force at t_w⁻;
- AD = an R-89 adoption form before t_w;
- F89 = R-89 in force at t_w by NIF-A or NIF-B.

| # | Observation | Scoring |
|---|---|---|
| **O0** | Part B: NOT-EVIDENCED-IN-CAPTURE, or CAPTURE_INVALID | P4 and P7 discriminate nothing. Neither falsifier can fire. P2 moves to rank 1. |
| **O1** | Trace exists; ACC holds before t_w; R-89 is AD or F89 before t_w | Neither hypothesis falsified. C2's order is confirmed as an order of force if NIF-graded; otherwise it stays SOURCE_STATED. |
| **O2** | Trace exists; ¬ACC; G; ¬WV (R-89, if in force, does not waive the gate); premises by NIF | P7-HB **FIRED**: H-B conformance falsified. Any response goes to P8. |
| **O2c** | As O2, but premises only by the graded fallback, with no status-rule dependence | P7-HB **FIRED-CONDITIONAL**(the maker's records) |
| **O3** | ¬AD (DCS v1 complete, no late-recorded adoption); F89 by NIF-A or NIF-B during the interval without adoption | P4-HD **FIRED**: H-D status clause falsified. This holds whatever the work was. |
| **O4** | Trace cites R-89; ¬AD; no NIF about R-89 in either direction; ¬ACC or ACC unknown | **DISJUNCTIVE {H-D status clause, H-B conformance}**. "At least one fails; which one is not identified." |
| **O5** | ¬AD, complete; NIF-A shows R-89 not in force at t_w | Work was unauthorized → P7-HB / P2-type **FIRED**, if G or the baseline of no authorization holds. H-D is not at risk. |
| **O6** | Any required absence is NOT-EVIDENCED-IN-CAPTURE, or a premise is NOT_IDENTIFIABLE (late recording, clock anomaly) | UNDETERMINED for the affected falsifier |
| **O7** | Trace exists but does not cite R-89 | P4-HD cannot fire through this work. P7-HB is scored as in O2, O2c, O5 or O6. |
| **O8** | Only variant or LOCAL_ONLY hits (Part B) | O0 for scoring purposes. The case is escalated to the human for a protocol r2 decision. |

**[REMOVED D3]** The 1bb claim "This is the only acquisition this small that puts both hypotheses at risk" is removed.

---

# PART B. Frozen protocol: rank-0 WP-4C-1 check

**Question.** Did WP-4C-1 work actually occur, and what is its earliest recorded trace? This is to be executed later by the deterministic commands in §7, then reviewed (§8). Nothing here has been run.

## B.1 Channels searched
- **Repository (REPO).** The git repository that contains the corpus's R11 commit (message containing `WP-4B RED`).
  - **UNKNOWN from the inputs:** its path and remote URL. The human names REPO, and any further repositories, before the run. That list is the execution-channel set for rank-0.
  - Repositories not named are out of channel.
  - If several repositories are named, the protocol runs once per repository.
- **Refs.** All refs present after the pre-capture fetch (§7, script `rank0-fetch.sh`): `--all`, meaning refs/heads, refs/remotes, refs/tags, refs/notes, refs/stash and any others. Commits reachable from them are PRIMARY.
- **Auxiliary channels in the worker's clone**, labelled **LOCAL_ONLY**:
  - commits reachable only from reflogs (`--reflog`);
  - unreachable commit objects (`git fsck --unreachable --no-reflogs`).

  These establish that an object existed in that clone. They do not establish that the object was ever on a shared ref.
- **Paths.** All paths. There is no path restriction on message or diff search. The path of each hit is recorded and classified (§3). A separate path-name pass looks for file names containing the identifier.
- **Parts of each commit searched:**
  - the full message (subject, body, trailers) via `--grep`;
  - diff content (added and removed lines) via `-G`, for non-merge commits, plus merges against their first parent;
  - path names via pathspec.
- **Out of channel for rank-0:** CI run records, the hosting-server audit, issue trackers, and repositories not named. Part C checks their existence only.

## B.2 Target: how "WP-4C-1" is matched
- **Primary identifier:** the literal token `WP-4C-1`, case-sensitive, with boundaries.
  - POSIX ERE: `PAT = (^|[^0-9A-Za-z-])WP-4C-1($|[^0-9A-Za-z])`
- **Aliases permitted by the sources:** none. The inputs use only "WP-4C-1". No alias is admitted to the primary bucket.
- **Excluded from the target:**
  - `WP-4C-10`, `WP-4C-11`, `WP-4C-1a` and similar (excluded by the right boundary);
  - bare `WP-4C` (the parent package or other sub-packages);
  - `WP-4`, `§WP-4`, `WP-4B`;
  - identification through technical content (class names, "hydrator", file topics). This would be an inference, and it is excluded.
- **Variant bucket (reported, never counted):** case-insensitive `VAR = w[^0-9a-z]{0,2}p[^0-9a-z]{0,2}4[^0-9a-z]{0,2}c[^0-9a-z]{0,2}1($|[^0-9])`. This catches forms such as `wp4c1`, `WP 4C.1` and `WP‑4C‑1` with non-ASCII hyphens.
  - VARIANT hits = VAR hits that are not PAT hits.
  - If only variant hits exist, the result is O8, and the case is escalated for a protocol r2 decision. A variant hit is never reclassified ad hoc.
- **Path-name pass:** a path counts as a primary hit only if the path string matches PAT. Other path-pass hits go to the variant bucket.

## B.3 Execution trace versus mention (frozen classification)
- **Path classes.** Match case-insensitively on the full path.
  - **DOC_GOV:** the path matches `[.](md|markdown|txt|rst|adoc|org)$`, **or** it has a segment matching `^(docs?|governance|rulings?|decisions?|adrs?|sessions?|logs?|notes|plans?|planning|minutes)$`. This includes the register, session logs, IMPLEMENTATION_PROGRESS.md and CONTEXT.md.
  - **NON_DOC:** any other path (code, tests, build files, configuration, CI definitions).
- **Classes.** Each primary hit commit is placed in exactly one:
  - **ET-1 (execution trace):** PAT occurs in the commit's **subject line or a trailer value**, **and** the commit changes (A, M, D, R, C or T) at least one NON_DOC path. For a merge, this counts only if its first-parent diff contains NON_DOC changes that are not present in any other hit commit.
  - **ET-2 (weak execution trace):** not ET-1, and one of the following holds:
    - PAT occurs only in the message body, and the commit changes at least one NON_DOC path;
    - PAT occurs on an **added** line in a NON_DOC path;
    - a NON_DOC path name matches PAT.
  - **MENTION:** any other primary hit. Every occurrence is in DOC_GOV paths, or in the message of a commit that changes only DOC_GOV paths, or only on removed lines. Descriptive sub-labels, which do not affect the result:
    - MENTION/PLAN (plans, queue, work plan);
    - MENTION/RULING (register rows, rulings);
    - MENTION/REPORT (text that *reports* execution, such as a progress entry saying WP-4C-1 was executed. This is a SOURCE_STATED report, not a trace);
    - MENTION/DOC;
    - MENTION/REMOVAL.
- **Flags** (recorded alongside the class):
  - **MIXED**: the subject also names another work-package identifier matching `WP-[0-9]+[A-Z]?(-[0-9]+)?` other than WP-4C-1.
  - **CITES_R89**: R-89 is matched in the message or in the added lines.
  - **LOCAL_ONLY**: the hit comes only from the reflog or unreachable passes.
  - **SIGNED**: a `gpgsig` header is present. This is independent of the keyring.
- **Out of rank-0:** rulings, plans and documentation are never execution traces, even when they say that work was done. CI runs are execution traces in principle, but they are outside this channel.

## B.4 Timestamps to record, and what each can establish under M-TIME

| Timestamp | Set by | Can establish | Cannot establish |
|---|---|---|---|
| **Author date** `%aI` | The git client at first commit of the change. It comes from the local clock or `GIT_AUTHOR_DATE` / `--date`, and it survives rebase, cherry-pick and amend. | The time the author's tooling *states* for authoring: a SOURCE_STATED time. A SOURCE_STATED_ORDER between commits. | The real time; push or receipt time; decision time; entry into force; any order relative to norms. |
| **Committer date** `%cI` | The git client when *this* commit object was created. It is reset by rebase, amend and cherry-pick, and it is settable through `GIT_COMMITTER_DATE`. | A SOURCE_STATED time of creation of this object. Usually ≥ the author date. | The same as for the author date. It is not a server time. |
| **Ancestry** (parent hash embedded) | The git object model | **RECORDING_ORDER**: an ancestor's content existed before its descendant was created. | Absolute times; order across unrelated branches; order relative to events outside git. |
| **Reflog times** | The worker's local clone | When *this clone's* refs moved (LOCAL_ONLY) | Anything about other clones or the server. They expire and can be rewritten. |
| **Signature timestamp** (inside `gpgsig`) | The signer's clock | A SOURCE_STATED time bound to a key | A server-verified time |
| **Server push / receipt time** | The hosting server | — (not in git) | Unavailable in rank-0. Part C checks whether a server channel exists. |

- **Rule.** The earliest execution trace is the **ancestry-minimal** trace. Its "time" is reported as its author and committer dates, both SOURCE_STATED.
- **Clock anomaly.** If any ancestry pair (a is an ancestor of b) has cI(b) < cI(a), the pair is flagged CLOCK_ANOMALY. Date-based order involving those commits is NOT_IDENTIFIABLE.
- **No git timestamp** establishes the decision time or entry into force of any norm. Order relative to the decision time or entry into force of R-72, R-87 or R-89 is always **NOT_IDENTIFIABLE** from this channel.

## B.5 What counts as absence, and why it is NOT-EVIDENCED-IN-CAPTURE
- **Absence** means all of the following:
  - the positive control is non-empty;
  - the capture is valid (no command error; refs recorded);
  - there are zero ET-1 and zero ET-2 hits among the PRIMARY (reachable) hits.
- **Label: NOT-EVIDENCED-IN-CAPTURE.** This does not mean "WP-4C-1 work did not occur". The reasons:
  1. `--all` sees only the refs in this clone. Deleted or force-pushed branches, refs that were never fetched, and other repositories are invisible.
  2. The search is based on the identifier. Work committed without the token is invisible, and the sensitivity of the search beyond the positive control is unknown.
  3. Work may exist outside git: in CI only, locally without a commit, or in another system.
  4. The clone may be shallow or partial.
- **Completeness.** Upgrading absence to **ABSENT_IN_COMPLETE_CHANNEL** requires all four of the following:
  - **C1:** the clone is not shallow and has no partial-clone filter. This is checked here.
  - **C2:** every object named by `git ls-remote` for each remote is present locally. This is checked here.
  - **C3:** server-side evidence that no refs were deleted or force-pushed over the window, from the audit log (Part C).
  - **C4:** an execution-channel rule, recorded in a channel independent of the executor, that makes REPO the sole channel for WP-4C-1 work **and** requires the identifier in commit messages, with records of its enforcement (Part C).

  Rank-0 can show only C1 and C2. So absence is NOT-EVIDENCED-IN-CAPTURE unless Part C shows C3 and C4.
- **CAPTURE_INVALID:** the positive control is empty, or C1 or C2 fails. This is not an absence.

## B.6 Search window
- **Lower bound: none.** The search covers the full history from the root commits. Reasons:
  - FR-D4: WP-4C-1 work that began before R-89 was recorded, or even before R-72, is the strongest case against H-B, so it must not be excluded.
  - git's `--since` and `--until` filter on committer dates set by the actor. `--since` can also stop the traversal early when clocks are skewed, and silently drop commits.
- **Upper bound:** the ref snapshot at capture time (`01_refs.tsv`), which is recorded and hashed.
- **After capture, no filtering.** Each hit is tagged by both its SOURCE_STATED dates into:
  - PRE_R72_DATE (< 2026-08-02, R-72's stated date);
  - CORPUS_SPAN (2026-08-02 to 2026-08-15);
  - POST_CORPUS (> 2026-08-15).

  These tags are descriptive only. The P7 window (FR-D4) is applied in Stage 1, not in the search.

## B.7 Exact commands and hashing
- **Prerequisites.** git ≥ 2.31 (`--diff-merges`), GNU coreutils and bash. The worker records `git --version`.
- **Scripts.** Three scripts. The worker saves them verbatim from this section. The reviewer hashes each script before the run, and the worker's hashes must match (§8).

**Script 1: `rank0-fetch.sh`** (network; run once, before capture; writes to `$PRE`)
```bash
#!/usr/bin/env bash
# rank0-fetch.sh -- frozen by SPEC-r1 Part B. Do not edit. Run from the root of the worker's clone of REPO.
set -euo pipefail
export LC_ALL=C TZ=UTC GIT_PAGER=cat
PRE="${PRE:?set PRE to a new empty directory}"
mkdir -p "$PRE"
git fetch --all --tags --no-prune > "$PRE/06_fetch.log" 2>&1
for r in $(git remote); do git ls-remote --refs "$r" | sed "s|^|$r |"; done | sort > "$PRE/07_ls_remote.txt"
awk '{print $2}' "$PRE/07_ls_remote.txt" | sort -u | while read -r s; do if git cat-file -e "$s" 2>/dev/null; then echo "present $s"; else echo "MISSING $s"; fi; done > "$PRE/08_remote_objects.txt"
git bundle create "$PRE/repo.bundle" --all 2> "$PRE/09_bundle_create.log"
git bundle verify "$PRE/repo.bundle" > "$PRE/09_bundle_verify.txt" 2>&1
( cd "$PRE" && find . -type f | sort | xargs -r sha256sum ) > "$PRE.SHA256SUMS"
mv "$PRE.SHA256SUMS" "$PRE/SHA256SUMS"
```

**Script 2: `rank0-wp4c1.sh`** (no network; writes to `$OUT`)
```bash
#!/usr/bin/env bash
# rank0-wp4c1.sh -- frozen by SPEC-r1 Part B. Do not edit. Run from the root of a clone of REPO.
set -euo pipefail
export LC_ALL=C TZ=UTC GIT_PAGER=cat GIT_CONFIG_NOSYSTEM=1 GIT_NO_REPLACE_OBJECTS=1
OUT="${OUT:?set OUT to a new empty directory}"
mkdir -p "$OUT"
g() { git --no-replace-objects -c core.quotepath=off -c log.showSignature=false -c log.mailmap=false -c diff.renames=false -c color.ui=never "$@"; }
hx() { { grep -hE '^[0-9a-f]{40}' "$@" || true; } | cut -f1 | sort -u; }
PAT='(^|[^0-9A-Za-z-])WP-4C-1($|[^0-9A-Za-z])'
VAR='w[^0-9a-z]{0,2}p[^0-9a-z]{0,2}4[^0-9a-z]{0,2}c[^0-9a-z]{0,2}1($|[^0-9])'
R72='(^|[^0-9A-Za-z-])R-72($|[^0-9])'
R87='(^|[^0-9A-Za-z-])R-87($|[^0-9])'
R89='(^|[^0-9A-Za-z-])R-89($|[^0-9])'
CTRL='WP-4B RED'
FMT='%H%x09%P%x09%an%x09%ae%x09%aI%x09%cn%x09%ce%x09%cI%x09%s'

# S0 capture identity
{ git --version; g rev-parse --show-toplevel; g rev-parse --is-shallow-repository; g config --get extensions.partialClone || echo 'partialClone=unset'; g config --get remote.origin.promisor || echo 'promisor=unset'; } > "$OUT/00_env.txt"
g for-each-ref --format='%(objectname)%09%(objecttype)%09%(refname)' | sort > "$OUT/01_refs.tsv"
g rev-list --all --count > "$OUT/02_commit_count.txt"
g replace -l | sort > "$OUT/03_replace_refs.txt"
g remote -v | sort > "$OUT/04_remotes.txt"

# S1 positive control (empty => CAPTURE_INVALID)
g log --all -F --grep="$CTRL" --format="$FMT" > "$OUT/05_control.tsv"

# S2 primary passes (all refs)
g log --all -E --grep="$PAT" --format="$FMT" > "$OUT/10_msg.tsv"
g log --all -E --grep="$PAT" --format='%H%x1f%B%x1e' > "$OUT/11_msg_bodies.txt"
g log --all -E --grep="$PAT" --format='%H%x1f%(trailers:unfold)%x1e' > "$OUT/12_msg_trailers.txt"
g log --all -G"$PAT" --no-ext-diff --no-textconv --format="$FMT" --name-status > "$OUT/13_diff.txt"
g log --all --merges --diff-merges=first-parent -G"$PAT" --no-ext-diff --no-textconv --format="$FMT" --name-status > "$OUT/14_diff_merges.txt"
g log --all --no-ext-diff --format="$FMT" --name-status -- ':(glob,icase)**/*4c-1*' ':(glob,icase)**/*4c1*' ':(glob,icase)**/*4c_1*' > "$OUT/15_paths.txt"

# S3 variant passes (reported separately; never counted as primary)
g log --all -i -E --grep="$VAR" --format="$FMT" > "$OUT/20_msg_variant.tsv"
g log --all -i -G"$VAR" --no-ext-diff --no-textconv --format="$FMT" --name-status > "$OUT/21_diff_variant.txt"

# S4 auxiliary LOCAL_ONLY channels
g log --reflog -E --grep="$PAT" --format="$FMT" > "$OUT/30_reflog_msg.tsv"
g log --reflog -G"$PAT" --no-ext-diff --no-textconv --format="$FMT" --name-status > "$OUT/31_reflog_diff.txt"
{ g fsck --unreachable --no-reflogs --no-progress 2>/dev/null || true; } | { grep -E '^unreachable commit ' || true; } | cut -d' ' -f3 | sort > "$OUT/32_unreachable.txt"
if [ -s "$OUT/32_unreachable.txt" ]; then g log --no-walk --stdin -E --grep="$PAT" --format="$FMT" < "$OUT/32_unreachable.txt" > "$OUT/33_unreachable_msg.tsv"; else : > "$OUT/33_unreachable_msg.tsv"; fi

# S5 anchors: recording of ruling ids in REPO (never evidence of decision or force)
for id in R72 R87 R89; do
  p="${!id}"
  g log --all -E --grep="$p" --format="$FMT" > "$OUT/40_anchor_${id}_msg.tsv"
  g log --all -G"$p" --no-ext-diff --no-textconv --format="$FMT" --name-status > "$OUT/41_anchor_${id}_diff.txt"
done

# S6 hit set, details, matching lines, signatures, ancestry
hx "$OUT/10_msg.tsv" "$OUT/13_diff.txt" "$OUT/14_diff_merges.txt" > "$OUT/90_hitset.txt"
hx "$OUT/05_control.tsv" "$OUT"/40_anchor_*_msg.tsv "$OUT"/41_anchor_*_diff.txt > "$OUT/90b_refset.txt"
while read -r h; do echo "== $h"; g show --no-ext-diff --no-textconv --format=fuller --name-status "$h"; done < "$OUT/90_hitset.txt" > "$OUT/93_details.txt"
while read -r h; do echo "== $h"; g show --no-ext-diff --no-textconv --format= -U0 "$h" | awk -v pat="$PAT" '/^[+][+][+] /{f=$2} /^[+-]/ && !/^([+][+][+]|---) / && $0 ~ pat {print f " " $0}'; done < "$OUT/90_hitset.txt" > "$OUT/94_matching_lines.txt"
while read -r h; do echo "$h $(g cat-file commit "$h" | grep -c '^gpgsig' || true)"; done < "$OUT/90_hitset.txt" > "$OUT/92_sig_presence.txt"
cat "$OUT/90_hitset.txt" "$OUT/90b_refset.txt" | sort -u > "$OUT/95a_nodes.txt"
: > "$OUT/95_ancestry.txt"
while read -r a; do while read -r b; do if [ "$a" != "$b" ] && g merge-base --is-ancestor "$a" "$b"; then echo "$a $b" >> "$OUT/95_ancestry.txt"; fi; done < "$OUT/95a_nodes.txt"; done < "$OUT/95a_nodes.txt"
awk 'NR==FNR{h[$1]=1; next} ($1 in h) && ($2 in h){print $2}' "$OUT/90_hitset.txt" "$OUT/95_ancestry.txt" | sort -u > "$OUT/96_hits_with_hit_ancestor.txt"
comm -23 "$OUT/90_hitset.txt" "$OUT/96_hits_with_hit_ancestor.txt" > "$OUT/97_ancestry_minimal_hits.txt"

# S7 hashing
cat "$OUT/01_refs.tsv" "$OUT/90_hitset.txt" | sha256sum | cut -d' ' -f1 > "$OUT/98_canonical_key.txt"
( cd "$OUT" && find . -type f | sort | xargs -r sha256sum ) > "$OUT.SHA256SUMS"
mv "$OUT.SHA256SUMS" "$OUT/SHA256SUMS"
sha256sum "$OUT/SHA256SUMS"
```

**Script 3: `rank0-order.sh`** (run after classification; `ET` = file listing ET-1 and ET-2 hashes, one per line, sorted)
```bash
#!/usr/bin/env bash
# rank0-order.sh -- frozen by SPEC-r1 Part B. Do not edit.
set -euo pipefail
export LC_ALL=C
OUT="${OUT:?}"; ET="${ET:?}"
awk 'NR==FNR{h[$1]=1; next} ($1 in h) && ($2 in h){print $2}' "$ET" "$OUT/95_ancestry.txt" | sort -u > "$OUT/99a_et_with_et_ancestor.txt"
comm -23 "$ET" "$OUT/99a_et_with_et_ancestor.txt" > "$OUT/99b_et_ancestry_minimal.txt"
while read -r h; do git --no-replace-objects log -1 --format='%H%x09%aI%x09%cI' "$h"; done < "$OUT/99b_et_ancestry_minimal.txt" > "$OUT/99c_et_minimal_dates.tsv"
sha256sum "$ET" "$OUT/99a_et_with_et_ancestor.txt" "$OUT/99b_et_ancestry_minimal.txt" "$OUT/99c_et_minimal_dates.tsv" > "$OUT/99_SHA256SUMS"
```

- **Hashing.**
  - Every output file is hashed with SHA-256 into `SHA256SUMS` (and `99_SHA256SUMS`).
  - The SHA-256 of `SHA256SUMS` is written into the report.
  - `98_canonical_key.txt` = SHA-256 of (refs snapshot ‖ sorted hit set). This key does not depend on the git version, so two runs on the same refs must produce the same key.
  - `repo.bundle` is hashed in `$PRE/SHA256SUMS`.
  - API responses are not part of rank-0.

## B.8 Worker and reviewer procedure
**Freeze (before any run).**
- The human names REPO (§1).
- The reviewer extracts the three scripts verbatim from this document, computes their SHA-256, and records the hashes in `FREEZE.txt`.
- No rule in this Part may change after this point. A change means protocol r2, frozen again before any rerun.

**Worker.**
1. **W1.** Verify the script hashes against `FREEZE.txt`. Stop on any mismatch.
2. **W2.** Run `rank0-fetch.sh` in the working clone (PRE).
3. **W3 (Run A).** Run `rank0-wp4c1.sh` in the working clone (OUT_A). This includes the LOCAL_ONLY channels.
4. **W4 (Run B).** Mirror-clone the bundle (`git clone --mirror "$PRE/repo.bundle" rb && cd rb`), then run `rank0-wp4c1.sh` there (OUT_B). This run is reproducible. PRIMARY results come from Run B. Run A's primary hit set must be ⊇ Run B's. Extra hits in Run A are labelled LOCAL_ONLY.
5. **W5.** On any command error: stop, keep stderr, and report CAPTURE_ERROR. No ad-hoc fixes and no reruns with changes.
6. **W6.** Check validity:
   - `05_control.tsv` is non-empty;
   - `00_env.txt` shows `is-shallow-repository = false` and no partial clone (C1);
   - `08_remote_objects.txt` contains no `MISSING` (C2).

   Otherwise the result is CAPTURE_INVALID.
7. **W7.** Classify every hit in `90_hitset.txt` by §3, using `93_details.txt`, `94_matching_lines.txt`, `11_msg_bodies.txt` and `12_msg_trailers.txt`. Produce `CLASSIFICATION.tsv` with the columns: hash, class, flags, rule clause applied, path classes, `%aI`, `%cI`, date tag (§6), and signature presence.
8. **W8.** Write the ET list and run `rank0-order.sh`. Assign the order labels (§9).
9. **W9.** Write `RANK0-REPORT.md` containing: the result labels (§9), the SHA-256 of each `SHA256SUMS`, the canonical key, variant counts, LOCAL_ONLY counts, and the date tags.
   - It makes no statement about the hypotheses.
   - Mapping the result to outcomes O0–O8 is a separate step, done by the reviewer after acceptance.

**Reviewer** (an independent agent or person: no shared session, and no sight of the worker's classification before R3).
1. **R1.** Verify the script hashes and all `SHA256SUMS`, and the bundle hash.
2. **R2.** Mirror-clone the same bundle, rerun `rank0-wp4c1.sh`, and compare:
   - with the same git version, the outputs must be byte-identical;
   - otherwise, `98_canonical_key.txt` must be equal.
3. **R3.** Classify blind from the raw outputs, then compare with `CLASSIFICATION.tsv`.
   - Each disagreement is resolved only by quoting the rule clause in §3.
   - A disagreement that is still unresolved is labelled DISPUTED and passed to the human. The worse class for the claim is not silently chosen.
4. **R4.** Check four things: the positive control; C1 and C2; that variant hits are not counted; and that the absence wording is exactly NOT-EVIDENCED-IN-CAPTURE unless C3 and C4 are shown.
5. **R5.** Check that no statement goes beyond the labels, and in particular that no git time is presented as a decision time or entry into force.
6. **R6.** Give a verdict: ACCEPT, ACCEPT_WITH_CORRECTIONS (list them), or REJECT (with reasons).

Neither role may widen the search. Any new idea is written down as a proposal for protocol r2.

## B.9 Output labels
- **Existence** (one of):
  - **EXECUTION_TRACE_FOUND** (ET-1 and/or ET-2, with counts);
  - **MENTION_ONLY**;
  - **NOT-EVIDENCED-IN-CAPTURE**;
  - **ABSENT_IN_COMPLETE_CHANNEL** (only with C1–C4);
  - **CAPTURE_INVALID**;
  - **CAPTURE_ERROR**.

  Each can carry the qualifiers VARIANT_HITS_PRESENT(n) and LOCAL_ONLY_HITS(n).
- **Earliest trace:** the ancestry-minimal ET set (`99b`), with SOURCE_STATED `%aI` and `%cI`.
- **Order**, for each claimed ordered pair (earliest ET versus other ETs, the R11 control, and the anchor commits for R-72, R-87 and R-89):
  - **RECORDING_ORDER**: `95_ancestry.txt` contains the pair (ancestry).
  - **SOURCE_STATED_ORDER**: there is no ancestry, and both the author-date order and the committer-date order agree, with no CLOCK_ANOMALY. This also applies to orders taken from text, such as "after R-87" or `(cherry picked from commit …)` trailers.
  - **INFERRED_ORDER**: the order follows only from a chain that combines RECORDING_ORDER and SOURCE_STATED_ORDER links, and the chain is written out in the report.
  - **NOT_IDENTIFIABLE**: none of the above; or the dates disagree; or there is a CLOCK_ANOMALY; or the other event is outside git.
- **Always NOT_IDENTIFIABLE from this channel:** the order of any WP-4C-1 trace relative to the *decision* or the *entry into force* of R-72, R-87, R-89 or WP-4B's acceptance. Anchor commits show only when text containing those ids was recorded in REPO.

---

# PART C. Frozen protocol: norm-status-channel scoping search

**Question** (Decision 3). Does an independent record of the norm in force exist, maintained by someone other than the norm-maker, the executor or the self-asserting ruling?

**Approach.** The search checks the **existence** of a fixed list of candidate channels, and collects only enough metadata to answer the 8 questions. **No general archaeology.**

## C.1 Rules of restraint
- Only the channels in C.2 are checked. Only the commands in C.3 are run.
- **Allowed outputs:** existence (yes/no/HTTP status), path lists, counts, earliest and latest timestamps, access-level categories (roles, not personal names), configuration flags, and CODEOWNERS lines (configuration of control).
- **Prohibited:**
  - reading register or ruling contents, session-log contents, commit diffs, CI job logs, merge-request discussions, or issue contents;
  - following links;
  - searching for WP identifiers, which is Part B's job.
- **Stop rule:** when every C.3 command has run, stop. A new candidate channel met along the way is recorded as CANDIDATE_NOT_CHECKED, for a human decision.
- Part C runs **before Stage 1**, in parallel with Part B. Its result closes DCS v1 (§0.6) and EC (§0.7).

## C.2 Candidate channels (existence only)

| ID | Channel | Where | Why it is a candidate |
|---|---|---|---|
| K-REG | Register write history | REPO paths matching register/ruling/decision | Reference channel. It is the norm-maker's channel, so it is not independent by construction. Its properties determine DCS completeness. |
| K-CFG | CODEOWNERS | `CODEOWNERS`, `.github/CODEOWNERS`, `.gitlab/CODEOWNERS`, `docs/CODEOWNERS` | Configured control over who approves changes to the register |
| K-CI | CI definitions and CI run history | `.gitlab-ci.yml`, `.github/workflows/`, `Jenkinsfile`, `.circleci/`, `azure-pipelines.yml`, `.drone.yml`, `bitbucket-pipelines.yml`, `.buildkite/`, `.woodpecker.yml`, `.woodpecker/`; server pipelines | System-timestamped work records; possible policy gates |
| K-HOOK | Hooks | `.githooks/`, `.husky/`, `.pre-commit-config.yaml`, `lefthook.yml`, `.lefthook.yml`, `core.hooksPath`; local `.git/hooks` (LOCAL_ONLY) | Machine enforcement of message and identity rules (for C4) |
| K-STATUS | Status or audit files in the tree | Tree paths matching the status-file regex (C.3) | A possible norm-status file. Usually written by the maker or executor. |
| K-SIG | Signed commits and tags; git notes | Objects and refs/notes | AUTH_ID (D5(d)); an attestation layer |
| K-HOST-CFG | Protected branches, push rules, approval rules | Hosting server | Server-enforced coverage (Q6) and control (Q3) |
| K-HOST-MR | Merge / pull request records | Hosting server | Server-timestamped approval events on register changes |
| K-HOST-AUDIT | Audit and event logs | Hosting server | Server-timestamped, independent of the actors; retention; integrity (C3) |
| K-HOST-MEMBERS | Membership roles | Hosting server | Who can write or alter each channel (Q3, I1, I4) |

## C.3 Exact commands

**Script 4: `scope-nsc.sh`.** It needs REPO (the same clone as Part B, Run A), `jq`, and, if a host is detected, `glab` or `gh`, authenticated by the worker. The worker records the access level used.
```bash
#!/usr/bin/env bash
# scope-nsc.sh -- frozen by SPEC-r1 Part C. Existence/metadata only. Do not edit. Run from REPO root.
set -euo pipefail
export LC_ALL=C TZ=UTC GIT_PAGER=cat GIT_CONFIG_NOSYSTEM=1
OUT="${OUT:?set OUT to a new empty directory}"
mkdir -p "$OUT"
g() { git --no-replace-objects -c core.quotepath=off -c color.ui=never "$@"; }
DEF="$(g symbolic-ref --short refs/remotes/origin/HEAD 2>/dev/null || g rev-parse --abbrev-ref HEAD)"
echo "$DEF $(g rev-parse "$DEF")" > "$OUT/50_tree_ref.txt"
g ls-tree -r --name-only "$DEF" | sort > "$OUT/51_tree_paths.txt"
STATUS_RE='(status|audit|ledger|adopt|in[-_]?force|norm|register|ruling|decision|approval|sign[-_]?off|queue|work[-_]?plan|context|progress)'
CFG_RE='(^|/)CODEOWNERS$'
CI_RE='^([.]gitlab-ci[.]yml|[.]github/workflows/|Jenkinsfile$|[.]circleci/|azure-pipelines[.]yml$|[.]drone[.]yml$|bitbucket-pipelines[.]yml$|[.]buildkite/|[.]woodpecker([.]yml$|/))'
HOOK_RE='^([.]githooks/|[.]husky/|[.]pre-commit-config[.]yaml$|[.]?lefthook[.]yml$)'
{ grep -iE "$STATUS_RE" "$OUT/51_tree_paths.txt" || true; } > "$OUT/52_K-STATUS_paths.txt"
{ grep -E "$CFG_RE" "$OUT/51_tree_paths.txt" || true; } > "$OUT/53_K-CFG_paths.txt"
{ grep -E "$CI_RE" "$OUT/51_tree_paths.txt" || true; } > "$OUT/54_K-CI_paths.txt"
{ grep -E "$HOOK_RE" "$OUT/51_tree_paths.txt" || true; } > "$OUT/55_K-HOOK_paths.txt"
while read -r p; do echo "== $p"; g show "$DEF:$p"; done < "$OUT/53_K-CFG_paths.txt" > "$OUT/56_codeowners_content.txt"
{ g config --get core.hooksPath || echo 'hooksPath=unset'; ls -1 .git/hooks 2>/dev/null | { grep -v '[.]sample$' || true; }; } > "$OUT/57_local_hooks_LOCAL_ONLY.txt"
# ever-existed (history) for status/config/CI/hook paths: first-add commit metadata only
for f in 52_K-STATUS 53_K-CFG 54_K-CI 55_K-HOOK; do
  while read -r p; do echo "== $p"; g log --all --diff-filter=A --format='%H%x09%aI%x09%cI' -- "$p" | tail -n 1; g log --all --format='%an%x09%ae' -- "$p" | sort | uniq -c | sort -rn; done < "$OUT/$f"_paths.txt
done > "$OUT/58_path_history_meta.txt"
# signatures and notes (keyring-independent presence)
g log --all --format='%H' | while read -r h; do if g cat-file commit "$h" | grep -q '^gpgsig'; then echo S; else echo U; fi; done | sort | uniq -c > "$OUT/59a_commit_sig_counts.txt"
g for-each-ref refs/tags --format='%(objecttype) %(refname)' | awk '{print $1}' | sort | uniq -c > "$OUT/59b_tag_types.txt"
g for-each-ref refs/notes --format='%(refname)' > "$OUT/59c_notes_refs.txt"
g remote -v | sort > "$OUT/59d_remotes.txt"
# host detection
HOST=none
if grep -qi 'gitlab' "$OUT/59d_remotes.txt"; then HOST=gitlab; elif grep -qi 'github[.]com' "$OUT/59d_remotes.txt"; then HOST=github; fi
echo "$HOST" > "$OUT/60_host.txt"
: > "$OUT/61_api_status.txt"
api() { # $1=name $2=endpoint $3=jq projection
  if [ "$HOST" = gitlab ]; then cmd=(glab api "$2"); else cmd=(gh api "$2"); fi
  if "${cmd[@]}" > "$OUT/tmp.json" 2> "$OUT/62_$1.err"; then jq -S "$3" "$OUT/tmp.json" > "$OUT/62_$1.json"; echo "$1 OK" >> "$OUT/61_api_status.txt"; else echo "$1 FAIL $(head -c 200 "$OUT/62_$1.err" | tr '\n' ' ')" >> "$OUT/61_api_status.txt"; fi
  rm -f "$OUT/tmp.json"
}
if [ "$HOST" = gitlab ]; then
  api project 'projects/:id' '{id, path_with_namespace, default_branch, created_at, merge_requests_enabled, only_allow_merge_if_pipeline_succeeds, merge_method}'
  api protected_branches 'projects/:id/protected_branches' '[.[] | {name, allow_force_push, code_owner_approval_required, push: [.push_access_levels[]?.access_level_description], merge: [.merge_access_levels[]?.access_level_description]}]'
  api push_rule 'projects/:id/push_rule' '{commit_message_regex, reject_unsigned_commits, commit_committer_check, member_check}'
  api approval_rules 'projects/:id/approval_rules' '[.[] | {name, rule_type, approvals_required}]'
  api audit_before_window 'projects/:id/audit_events?created_before=2026-08-02T00:00:00Z&per_page=1' '[.[] | .created_at]'
  api audit_in_window 'projects/:id/audit_events?created_after=2026-08-01T00:00:00Z&created_before=2026-08-16T00:00:00Z&per_page=1' '[.[] | .created_at]'
  api events_before_window 'projects/:id/events?before=2026-08-02&per_page=1' '[.[] | .created_at]'
  api pipelines_in_window 'projects/:id/pipelines?updated_after=2026-08-01T00:00:00Z&updated_before=2026-08-16T00:00:00Z&per_page=1' '[.[] | {created_at, source}]'
  api mrs_in_window 'projects/:id/merge_requests?state=all&created_after=2026-08-01T00:00:00Z&created_before=2026-08-16T00:00:00Z&per_page=1' '[.[] | {created_at, merged_at}]'
  api member_roles 'projects/:id/members/all?per_page=100' '[.[] | .access_level] | group_by(.) | map({level: .[0], n: length})'
elif [ "$HOST" = github ]; then
  api repo 'repos/{owner}/{repo}' '{full_name, default_branch, created_at, allow_merge_commit, allow_squash_merge, allow_rebase_merge}'
  api branch_protection "repos/{owner}/{repo}/branches/${DEF#origin/}/protection" '{required_signatures: .required_signatures.enabled, enforce_admins: .enforce_admins.enabled, reviews: .required_pull_request_reviews.required_approving_review_count, codeowners: .required_pull_request_reviews.require_code_owner_reviews, allow_force_pushes: .allow_force_pushes.enabled}'
  api rulesets 'repos/{owner}/{repo}/rulesets' '[.[] | {name, target, enforcement}]'
  api runs_in_window 'repos/{owner}/{repo}/actions/runs?created=2026-08-01..2026-08-15&per_page=1' '{total_count}'
  api pulls_first 'repos/{owner}/{repo}/pulls?state=all&per_page=1&direction=asc' '[.[] | .created_at]'
  api collaborator_roles 'repos/{owner}/{repo}/collaborators?per_page=100' '[.[] | .role_name] | group_by(.) | map({role: .[0], n: length})'
fi
( cd "$OUT" && find . -type f | sort | xargs -r sha256sum ) > "$OUT.SHA256SUMS"
mv "$OUT.SHA256SUMS" "$OUT/SHA256SUMS"
sha256sum "$OUT/SHA256SUMS"
```
- If HOST = none, every K-HOST-* channel is recorded as **SERVER_CHANNELS_UNCHECKED**, with Q1 = UNKNOWN.
- An API FAIL with HTTP 401, 403 or 404 is recorded as NOT_ACCESSIBLE. It is never recorded as NO.
- API projections are hashed. Reproducibility of API data is LIMITED, because server data change over time.

## C.4 Criteria

**Independence** (of the norm-maker, the executor and the self-asserting ruling). Each criterion is scored per channel.
- **I1 (writer):** entries are written by a process or actor that is not the maker or adopter (DA, Chief), not the executor (engineering), and not the ruling. Examples: the hosting server, the CI system, an independent secretariat.
- **I2 (derivation):** entries are not copies or summaries of the rulings' self-statements.
- **I3 (clock):** timestamps are generated by the channel's system (the server clock), not by the writer's client.
- **I4 (integrity):** the maker and the executor cannot silently alter or delete entries. The channel is append-only, or its changes are themselves audited.

Grades:
- **INDEPENDENT** = I1 ∧ I2 ∧ I3 ∧ I4.
- **PARTIAL(list)** = at least one of them holds.
- **NOT_INDEPENDENT** = none holds.

A channel that records an act of the maker (for example, an MR approval by the DA) can be PARTIAL: I3 and I4 hold for time and identity. It still records the maker's act. It is not an independent record of the norm in force (FR-D13).

**Completeness** over the window 2026-08-02 to 2026-08-15, for the event type in question:
- **K1 coverage:** the event type is *required* to pass through the channel (for example, a protected branch with no direct push for DA, Chief or engineering roles, or a mandatory MR).
- **K2 retention:** records exist from before the window start. The before-window queries return non-empty results.
- **K3 config stability:** the coverage configuration was unchanged over the window. This needs the audit log. The current configuration alone does not show the configuration in force then (M-TIME).
- **K4 integrity:** I4 holds, or the audit log shows no deletions.

Grades:
- **COMPLETE_SHOWN** = K1 ∧ K2 ∧ K3 ∧ K4.
- **PARTIAL(list)** = some of them hold.
- **UNKNOWN** = none could be checked.

## C.5 How each of the 8 questions is answered (per channel)

| Q | Answer vocabulary | Derived from |
|---|---|---|
| 1. Does such a channel exist? | YES / NO / NOT_ACCESSIBLE / UNKNOWN | Path lists (52–55), API status (61) |
| 2. What does it record? | ⊆ {NORM_STATE, ADOPTION_EVENT, APPROVAL_EVENT, WORK_EVENT, CONFIG_RULE, IDENTITY_ATTESTATION} | The channel type (C.2), not its content. For K-STATUS paths, the value is UNKNOWN_CONTENT, since the content is not read. |
| 3. Who controls it? | HOSTING_SYSTEM / CI_SYSTEM / NORM_MAKER / EXECUTOR / MIXED / UNKNOWN | Access levels (member roles, protected-branch push/merge levels), CODEOWNERS, and author metadata of the path history (ACTOR_SET_ID; indicative only) |
| 4. Are its timestamps independently generated? | SYSTEM_GENERATED / CLIENT_SET / NONE / UNKNOWN | The channel type. Git paths are CLIENT_SET; server API records are SYSTEM_GENERATED. |
| 5. Can it establish the norm in force at time t? | YES (records NORM_STATE, with I3, and K2 covers t) / PARTIAL (only ADOPTION_EVENT or APPROVAL_EVENT times; force still needs the maker's records) / NO | Q2, Q4, K2 |
| 6. How complete is it? | COMPLETE_SHOWN / PARTIAL(K-list) / UNKNOWN | C.4 K1–K4 |
| 7. How independent is it? | INDEPENDENT / PARTIAL(I-list) / NOT_INDEPENDENT | C.4 I1–I4 |
| 8. Can it be reproduced and verified? | YES (content-addressed, or re-queryable by another party with the stated access) / LIMITED (access-restricted, mutable or limited by retention) / NO | Channel type; API status |

**Overall result:**
- **NIF_A_CHANNEL_EXISTS** iff some channel has Q1 = YES, Q5 = YES, Q7 = INDEPENDENT, and Q6 ≠ UNKNOWN.
- **NO_NIF_A_CHANNEL_IN_CHECKED_SET** otherwise, with SERVER_CHANNELS_UNCHECKED noted where it applies.

**Consequences (frozen now):**
- **NO_NIF_A_CHANNEL_IN_CHECKED_SET:** predictions whose §1.7 requirement accepts only NIF-A fall to the "without it" column. Those that accept NIF-B stay open until Stage 1.
- **DCS v1:** it adds every channel with Q2 ∋ ADOPTION_EVENT or APPROVAL_EVENT.
- **EC:** it adds CI run history if Q1 = YES.
- **Part B C3 and C4:** these are satisfied only by K-HOST-AUDIT (C3), and by K-HOOK or K-HOST-CFG with a commit-message rule plus K3 (C4).

## C.6 Worker and reviewer
The procedure is the same as in Part B §8, with these differences:
- the script hash is frozen in `FREEZE.txt`;
- one run;
- the reviewer checks that every answer cell cites the output file it was derived from, and that no prohibited content was read;
- the reviewer reruns the git part (files 50–59) and compares hashes. API parts are compared by status and by projection shape, since their values may have changed.

**Output:** `SCOPING-REPORT.md`, with one row per channel K-* answering Q1–Q8, the overall result, the resulting DCS v1 and EC, and the hash of `SHA256SUMS`.
