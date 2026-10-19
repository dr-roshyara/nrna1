# Review C3, Phase 2: records E-S0016…E-S0020 and their book integration, compared with my frozen blind extraction

## Verdict: UNSOUND, because of the book integration

- **The five records are mostly faithful.** On their own they would rate SOUND WITH MINOR ISSUES.
- **The book integration overstates the evidence at four of the five foundational points: RC-36, RC-37, RC-40 and RC-42.** RC-43 (Ch. 0) has the same problem. Each repair is local; no restructuring is needed.
- **The problems are:**
  - a TODO closed without meeting its own resolution criterion (T1-31);
  - a deduction phrased more strongly than the source (RC-37);
  - a partition claim the source's own table contradicts (RC-42);
  - a validation whose basis cites a record that does not contain the concept (CON-0012);
  - an identity marked VALIDATED and called "settled" although the book's own TODOs say it is disputed (CON-0027).

## Access and line counts
- **What I read:** only the files listed by the coordinator, plus the five sources (anchors re-checked against my Phase 1 Read output).
- **Frozen Phase 1 file:** I verified its sha256 (`86e58ccc…793c`). It is unchanged.
- **Accidental exposure:** a `grep` over `chapter_0*/TODO.md` also matched **`chapter_02/TODO.md`**, which is not on the list. It printed one row (T2-04). I did not use it. The `wc` listing also showed the existence of `chapter_03/review/`. I did not open it.
- **Line totals: the coordinator's explanation is not right.** The Read tool showed **177 / 129 / 157 / 199 / 170** lines, with no trailing empty line. The last numbered line of S0016 was L177 ("Submitted to the Decision Authority…"). My Phase 1 totals (178 / 130 / 158 / 200 / 171) were a **miscount on my part**. The records' totals are correct. My line anchors are unaffected; for example L177, L129 and L199 match.

## Findings

**F-01 · FOUNDATIONAL · T1-31 closed without meeting its own criterion**
- **Where:** Ch. 1 TODO, T1-31 resolution row (L75); Ch. 1 §1.2 L155 (RC-36 Int, "settles TODO T1-31 at its source"); E-S0018 M1; BASELINE RC-36 ("T1-31 → Resolved (pair)").
- **What is wrong:** the TODO's resolution criterion is "the defining passage in its source" (Ch. 1 TODO L52). S0018 L94 writes "an immutable PROVENANCE fact × a mutable STANDING judgment". This still uses "×" and never defines the operation. The pair reading remains an interpretation, the same Int as RC-6 (Ch. 1 L128). E-S0018 itself marks the point [I]. The book promotes it to "Closed by evidence".
- **Correction:** reopen T1-31, or close it as "resolved by interpretation (Int), consistent with S0018 L94". RC-36 should say the source is *consistent with* the pair reading, not that it settles it.

**F-02 · FOUNDATIONAL · "standing" has several senses inside S0018, yet CON-0018 is validated under the bare word**
- **Where:** CONCEPT-REGISTER CON-0018; Ch. 1 §1.2 table (L147); RC-36.
- **Evidence:** S0018 uses "standing" in at least three senses:
  - "reusability standing" (L46);
  - "evidential standing crossing a bar" (L105) and "its evidential standing" (L117);
  - the STANDING half of authority (L81–83, L94).
- **Why it matters:** the book's own T1-07 ("standing", book vs corpus) is still *Under Research*. CV-1 §2(3) and §2a(5) require separate IDs and qualified terms when one word carries several meanings. Check 3 requires any contradiction to be resolved.
- **Correction:** qualify the concept as "standing (authority-field value)". Register "evidential standing" separately, or record the clash. Until T1-07 is resolved, CON-0018 should be INVALID (partly valid).

**F-03 · FOUNDATIONAL · RC-37's deduction overstates and misattributes the source**
- **Where:** Ch. 1 §1.6 L299: "An act cannot produce evidence, and evidence cannot produce standing"; E-S0018 [I-2].
- **What the source says:** "No ARB ruling can make an observation Replicated. No amount of evidence can make a document **frozen**" (L68). "Frozen" is a *status* value (P-1). S0018 keeps status and authority orthogonal, so this sentence is not about standing.
- **What the source states as the rule:** evidential progression "cannot be decreed" (L62). That is a ban on *decree*, not on *producing*.
- **Counter-evidence in the source itself:**
  - "P-7 produces P-10: work produces evidence" (L106), and P-7 (the EEP) includes an *approval* step (L33). So acts do cause evidence, through the work they authorize.
  - Evidence does produce "evidential standing" (L105).
- **Correction:** restate the deduction as: "no act can decree an evidential progression; evidence alone cannot effect a governance-act progression" [S0018 L61–62, L68]. Record a counterexample attempt for CON-0019 and CON-0020, which CV-1 check 4 requires for a logical concept and which neither basis records.

**F-04 · FOUNDATIONAL · RC-42's "partition" is contradicted by the source table**
- **Where:** Ch. 1 §1.11 L633–634 (Int): "grant scopes are partitioned by subject matter"; "a question that falls between two systems has no designated decider".
- **Evidence:** S0019 L102–103 lists the ARB as a holder in **two** systems: POLICY (DA/ARB) and METHOD (sponsor + ARB). POLICY decides "what is permitted, adopted, frozen, ratified", which includes adopting method. So the holders overlap, and the systems are not a partition. The source claims only that "no authority spans all three" (L107). A policy/method question does have a common holder (the ARB).
- **Also missing from RC-42:**
  - the source's standing: CANDIDATE and generated, with the cut "adopted from correction 4" of a reviewer (L19, L98);
  - the warning on the individual holder, recorded as a succession risk (L103, L130, MQ-4).
- **Correction:** subjects differ, holders overlap, and no holder spans all three. Only questions between *domain knowledge* and another system lack a common holder. State the source's standing.

**F-05 · FOUNDATIONAL · RC-40 / CON-0012: the validation cites a record that does not contain the concept**
- **What the register says:** CON-0012's check 4 names "the Engineering Decision Model, DRAFT, ARB-accepted" as the governing record.
- **What the source shows:**
  - "DECISION-READINESS" is S0017's own term (L92), in a CANDIDATE document that grades **"every conclusion a hypothesis until evidenced"** (L5).
  - The Decision Model is quoted only for the stance "the AI evaluates and recommends" (L22).
  - "Each arrived shaped: evidence graded, options separated, unknowns carried" is a generalization over 77 rulings with no sample. It is empirical and needs a population.
  - RC-40 also omits U-DM-4 (L110): "no evidence was found that the catalog is consciously consulted as a whole". RC-40 and CON-0013 nonetheless present the eight decisions as "catalogued in advance" and used.
- **Correction:**
  - CON-0012 basis: no governing record (S0017 is a CANDIDATE and grades itself a hypothesis). Mark it INVALID (partly valid), or state the actual standing and coverage.
  - Mark the 77-rulings sentence as the source's unchecked generalization.
  - Add U-DM-4 and S0017's L5 self-grade to RC-40.

**F-06 · FOUNDATIONAL · RC-43 / CON-0027: identity validated on a premise the source withdrew**
- **Where:** Ch. 0 §0.2 L101 and L109: "the corpus's settled reading on 2026-08-02 is the governance-architecture identity under the adopted purpose".
- **Why it fails:**
  - The L173 answer derives from the body's "mission, as adopted" (L44–48), built on AIP-14 as mission M-A. The annotation (L23–29) withdraws exactly that: AIP-14 is a constraint, not a mission.
  - The enacted mission's evidence is "none PublicDigit-specific" (L29), which conflicts with "for the purpose of improving the delivery of PublicDigit" (L173).
  - The book's own T0-02 says "disputed, Awaiting Human Decision" and T0-03 says "ambiguous … not reconciled". MQ-1 is open, and the source is a CANDIDATE.
- **Correction:** delete "settled". Mark CON-0027 INVALID (partly valid: fidelity passes; consistency fails against the source's own annotation and against T0-02/T0-03), or rename it to show that the premise was superseded.

**F-07 · ORDINARY · S0019 has three temporal layers, not two**
- **Where:** E-S0019 multi-temporal note; T0-14; the CON-0025 basis.
- **Evidence:** L29 "CORRECTED at REV 2" implies an earlier annotation (REV 1). L31's "Mission (VACANT)" is most plausibly that REV-1 remnant. L29 also says "What is absent is a mission STATEMENT", so "VACANT" can be read as the empty statement slot.
- **Consequence:** the "inconsistency inside the annotation" is weaker than recorded.
- **Correction:** record three layers. Rest CON-0025's invalidity on:
  - (i) no ratification record (MQ-1, D-5);
  - (ii) conflict with the PublicDigit purpose (CON-0027);
  - (iii) eight artifacts named but not verified.

**F-08 · ORDINARY · Status confused with content in several invalidity reasons**
- **Where:** CON-0028 ("a proposed, unapproved hypothesis"), CON-0030 ("DOES NOT EXIST"), CON-0031 ("partial"), CON-0015 ("no governing record").
- **What is wrong:** each reason invalidates the *concept* because its *referent* is unapproved, absent or incomplete. CV-1 check 4 for rule-type concepts asks for the governing record "and its standing (proposed, exploratory, adopted, superseded) stated". A PROPOSED standing can pass that check.
- **Correction:** restate each reason as a failed check (see the §8a table).

**F-09 · ORDINARY · CON-0003 is invalidated against the wrong value set**
- **Where:** §3.1 L17 and L36–42; T3-01.
- **What is wrong:** the row defines governance status by the 8 states in `statuses.yaml`. The failure reason cites GKC's "canonical, superseded", but "canonical" is not one of the 8.
- **In-scope evidence that does show inconsistency:**
  - S0016's own test uses values outside the 8: "accepted-with-refinements" (L130) and "frozen (ADOPTED)" (L128).
  - The ADR-M status vocabulary (S0018 L31) is a second vocabulary for the same position, and S0018 PM-3 (L131) leaves it open.
- **Correction:** restate the reason on this evidence.

**F-10 · ORDINARY · S0016's success test needed values outside its declared sets**
- **Where:** E-S0016 P8; §3.1 L28 ("classified … without changing it"); CON-0004.
- **Evidence:**
  - GOV-STATUS "accepted-with-refinements" (L130) is not among the 8 states (S0020 L27).
  - SCOPE "product" (L129, L130) is missing from the book's CON-0004 value list.
  - The criterion is "every existing document" (L10), but it was tested on six artifacts, one of them the document itself (L132).
- **Consequence:** the source's own falsification rule ("If any row required changing the model, the model would be wrong", L123) is arguably triggered.
- **Correction:** record this in E-S0016 §10 and §3.1, and reconcile CON-0004's value list.

**F-11 · ORDINARY · Sufficiency was never tested**
- **Evidence:** S0016's test table has only "Orthogonal?" and "Necessary?" columns (L39). L145, L165 and L175 still say each dimension passes all three tests.
- **Where the book follows it:** CON-0009's basis ("applied consistently in S0016") overstates. §3.1 L47 treats the six-artifact test as a sufficiency test, which the source never says; that reading is Int but unlabelled.
- **Correction:** label the reading as Int. CON-0009's basis should say only orthogonality was applied, with necessity asserted.

**F-12 · ORDINARY · S0016's annotation overrides its body, and E-S0016 does not say so**
- **Evidence:**
  - The hierarchy at L102–106 still places PKS below ARTIFACT and CAPABILITY above KNOWLEDGE.
  - The annotation contradicts this (L30, L33), and so does §2's dependency column (L81).
  - E-S0016 P9 lists the corrections but does not mark the file as multi-temporal or note that the body was never revised.
  - §3.3 and §3.4 cite L30 and L88 without saying that §2's concept set was itself flagged as "level mixing" (L29).
- **Correction:** add a multi-temporal note, as E-S0019 and E-S0020 already have.

**F-13 · ORDINARY · MM-4 is missing from CON-0006 and §3.1**
- **Evidence:** S0016 L154 asks whether OPERATIONAL-STATE belongs to the artifact or to the rule it carries ("Round39-MC is enforced because of what it says"). What carries the value is a definitional question, and it is unresolved. Yet §3.1's "Independence shown by use" compares artifact-level values.
- **Correction:** record MM-4 in CON-0006's basis and in §3.1. Mark CON-0006 INVALID (partly valid), or argue why MM-4 does not affect the definition.

**F-14 · ORDINARY · DP-2 is attributed to the wrong owner**
- **Where:** §3.5 L42 ("The documentation ontology already defines … through the design policy DP-2"); Ch. 1 L1067 (RC-44).
- **Evidence:** the source says "CAP-002's DP-2" (S0020 L89), and CAP-002 was DEFERRED (L100). DP-2 is not part of L-A. CON-0033's basis does not state the standing of the record that governs the concept (CV-1 check 4).
- **Correction:** attribute DP-2 to CAP-002 (deferred) and state that standing.

**F-15 · ORDINARY · §3.5 L44 softens the falsification**
- **Evidence:** S0020 L80 says "STRONGER THAN 'blocked'", "POSITIVELY AUTHORITATIVE", "FALSIFIED for every artifact that exists today". The book says the annotation "adds that the existing PKS artifacts are authored and accepted".
- **Correction:** use the source's word, FALSIFIED.

**F-16 · ORDINARY · "Dissolved" overstates a proposal**
- **Where:** Ch. 1 L1062 (RC-21a), reused in RC-44 and §3.5 L44; E-S0020 §10 ("S0018 dissolved").
- **Evidence:** S0018's split is only proposed (PM-1, routed to the ARB, L129). The executable schema is still one ranked field (S0020 L28, L89). Under that schema, W-3's "incompatible with derived" holds.
- **Correction:** write "proposes to dissolve (PM-1, undecided)" and keep both readings.

**F-17 · ORDINARY · Unsupported "later" in RC-44**
- **Evidence:** RC-44 (Ch. 1 L1068) says PM-1 is proposed "later" than the fusion in S0020. S0018 and S0020 are both dated 2026-08-02, and their order cannot be established from these files.
- **Correction:** delete "later".

**F-18 · ORDINARY · §3.5 L10 overstates the key-collision proof**
- **Book:** "putting platform concepts into the document vocabulary would break it, mechanically".
- **Evidence:** the collision is in `bounded-contexts.yaml` (S0020 L69). The recommendation being withdrawn targeted `knowledge-types.yaml` (L14). S0020 L74 concedes that a separate vocabulary file would avoid the collision.
- **Correction:** narrow the claim to the specific enum.

**F-19 · ORDINARY · L-A and L-C overlap, and the book drops the overlap silently**
- **Evidence:** `bounded-contexts.yaml` is a factor of L-A ("× 12 contexts", S0020 L46) and is also L-C (L48). §3.5 L16 and CON-0029's meaning drop the contexts from L-A without saying so.
- **Correction:** record the overlap. It affects CON-0029 and CON-0031.

**F-20 · ORDINARY · L-B's membership is inconsistent, and "does not exist" may be stale**
- **Evidence:**
  - There is no D-4 row (L57–63).
  - D-6b is rated product-side and not platform architecture (L62), yet the source says "ALL SEVEN belong to L-B" (L146).
  - E-S0020 and §3.5 L17 repeat "seed D-1…D-7" without noting either point.
  - S0016, which is later (it cites S0020's key-collision proof, L144), models "all six concepts" and calls its §2 "an ontology" (L31, L144). It is arguably a first L-B candidate, so "DOES NOT EXIST" may be stale.
- **Correction:** record all three points in E-S0020 and §3.5.

**F-21 · ORDINARY · Knowledge ≠ artifact conflicts with S0020's declined layer**
- **Evidence:**
  - §3.4 (CON-0010) rests on S0016 L88.
  - §3.5 L20 adopts S0020 L49: L-A "is the engineering-knowledge ontology, at document granularity". Under S0016's split, an ontology of documents is an ontology of artifacts, so the two positions conflict.
  - CON-0010 is VALIDATED while T0-05 (the same concept) is *Under Research* ("status as a foundation … not yet set").
  - Its support, the deletion test, is itself "CANDIDATE status, pilot-gated" (S0017 L50).
- **Correction:** record the conflict, and align the register with T0-05.

**F-22 · ORDINARY · RC-39 / CON-0015 omit in-scope counter-evidence**
- **Book:** "The recorded freezes show no grant, no procedure and no way to end them" (Ch. 1 L1182), stated in the book's own voice, although it is S0017's claim.
- **Counter-evidence in scope:**
  - `frozen` is a status with roles and guards (S0016 L43; S0018 L27).
  - A lint rule `frozen_changed_without_adr` exists (S0020 L24).
  - POLICY (DA/ARB) decides "what is … frozen" (S0019 L102).
  - So S0017's "extends NOTHING" (L55) holds at most for freezes of *activity*.
- **Correction:** attribute the claim to S0017, and add the tension with S0019 L102 (it complements T1-06's two senses).

**F-23 · ORDINARY · E-S0017 omits the source's self-grade**
- **Evidence:** S0017 L5 says "every conclusion a hypothesis until evidenced". L9 claims "freeze lawfulness", which sits awkwardly with the document's own Finding 2. The record carries the items as "finding" without the overall hypothesis grade.
- **Correction:** add both to the record and carry the grade into the book.

**F-24 · ORDINARY · CON-0024 (mission vs constraint) is over-validated**
- **Evidence:**
  - The test was built from, and applied to, a single case (AIP-14). No counterexample attempt is recorded.
  - In-scope tension: "A mission with no falsifier is not a mission" (S0019 MQ-7, L163) pulls against "a mission does not [ship with a metric]" (L27).
  - AIP-14's own text contains "measurable progress" (L41), so the test classifies the one case it was built from.
- **Correction:** INVALID (partly valid) until a second case or a recorded counterexample attempt exists.

**F-25 · ORDINARY · CON-0032 and CON-0029 are over-validated against the letter of CV-1**
- **CON-0032:** "declared by an act" is the book's own interpretation. The source says only "a scope decision that someone made" (L35), with no record of the act and no author.
- **CON-0029:** its basis says "as reported, not run here". CV-1 check 4 for software-type concepts requires a check against configuration with coverage stated.
- **Correction:** state the coverage gap, or downgrade to INVALID (partly valid).

**F-26 · NOTE · RC-38 presents an interpretation as source text**
- "The act of attestation is what changes standing" (Ch. 1 L1083) is presented as Src. E-S0018 §10 itself says the source "does not spell this out".
- **Correction:** label it Int.

**F-27 · NOTE · Two small misreadings in E-S0017**
- "Model amnesia occurrence #12": the source says "occurrence #12 of proposing-before-searching" (L26).
- "CAP-001 §5": L54 contains no "§5".

**F-28 · NOTE · INTENT-STATUS values misquoted**
- E-S0016 §0 and §3.2 L16 give "proposed · intended · ratified · operational".
- The source says "proposed · **sponsor-intended** · ratified · operational" (L47).

**F-29 · NOTE · Chapter 3 index is out of date**
- `03.00-index.md` L6 lists its sources without S0020, although §3.5 is built from S0020.

**F-30 · NOTE · "Act" used for an AI author's annotation**
- E-S0019 says the annotation "governs … by the author's own act". The author is an AI-generated document, and the book reserves "act" for human governance acts.
- **Correction:** write "supersedes as the author's later statement".

**F-31 · NOTE · Identifier collisions recorded nowhere**
- D-n means Decision Docket items in S0017 (D-5 mission, D-8 ontology) but platform domains in S0020.
- R-n means rulings (S0017, and "R-23" in S0019 L41) but responsibilities R-1…R-8 in S0019.
- The book cites D-5, D-8 and R-23 (T0-15, T3-05, CON-0027).
- **Correction:** record the collisions in the records and qualify the citations.

**F-32 · NOTE · Points missing from CON-0017 and the RC-36 notes**
- No counterexample attempt for CON-0017's "never changes": a wrongly recorded provenance value can be corrected (record ≠ fact; compare book §1.12).
- S0018's guard "`baseline` requires authority ∈ {authoritative, derived}" (L104) mixes a provenance value with a standing value. Under the proposed split it becomes ill-formed, and none of the records or notes say so.

## CV-1 §8a: second attempt on the INVALID concepts

| ID | My result | Basis |
|---|---|---|
| CON-0003 governance status | **INVALID-CONFIRMED** (partly valid); reason restated | Fidelity and the 8 values pass (S0016 L43; S0020 L27). Consistency fails on in-scope evidence: S0016's own test uses values outside the set (L128, L130), and a second vocabulary, ADR-M, covers the same position (S0018 L31, PM-3). The book's GKC-based reason uses a different value set (F-09). The S0012 evidence is out of scope. |
| CON-0007 intent | **INVALID-CONFIRMED** (partly valid); reason restated | "Not closed" is not a contradiction: S0016 nowhere requires derived functions to take dimensions only, and it lists owner per concept (§2) and TRUST/PROVENANCE outside the axes (L119). The real failures: the source leaves sufficiency open (MM-3, L153); the function's output is unspecified ("draft/approved with owner: sponsor" maps two statuses to one intent, L55); owner is only "required-ish" (L55). |
| CON-0008 kind | **INVALID-CONFIRMED (out of scope)** | Only passing in-scope mentions ("kinds→archetypes", S0017 L58; "archetype classification of the Meta-Model pass", S0017 L52, which S0016 does not contain). The evidence is in S0009. |
| CON-0011 knowledge space | **INVALID-CONFIRMED** (partly valid) | In scope: container with a declared boundary, `scope.include` (S0016 L30). New in-scope failure: the paradigm instance, PKS, lies *outside* `scope.include` (S0020 L96), so the only instance lacks the defining feature. Nesting and owner evidence is out of scope (S0008, S0012, S0013). |
| CON-0015 freeze (decision type) | **INVALID-CONFIRMED**; reason restated | Definition fails: two senses. Freeze of activity (S0017 L55) vs the governed `frozen` status (S0016 L43; S0018 L27; S0020 L24 `frozen_changed_without_adr`). Authority is contested in scope (S0019 L102 assigns "frozen" to DA/ARB). "No governing record" holds only for the three activity freezes. |
| CON-0022 composite trajectory | **INVALID-CONFIRMED** (partly valid) | A positive reading exists: P-3 = "evidential standing crossing a bar + a governance act" (L105); P-6 = "a trajectory across P-3, P-7, P-10" (L49). But it does not separate a composite from guard-coupled P-1 (status consults authority, L104), and PM-5 is open (L133). |
| CON-0023 qualification | **INVALID-CONFIRMED** | Three senses under one word (S0018 L17, L132). CV-1 §2(3) requires three IDs with qualified terms. I recommend the split rather than one INVALID row. |
| CON-0025 enacted mission | **INVALID-CONFIRMED**; reason restated | The L29/L31 clash is a partly updated REV-1 line (F-07). The decisive failures: no ratification record (MQ-1, D-5); conflict with CON-0027's PublicDigit purpose (L29 vs L173); the eight artifacts are named, not verified. |
| CON-0028 AI-native product | **INVALID-CONFIRMED (not verifiable in scope)**; reason restated | The concept is clearly defined (S0019 L42). Under CV-1 check 4, a PROPOSED standing would suffice once the charter, the governing record, is opened. It is quoted only by S0019. "Unapproved" is a status, not a failed check (F-08). Note: the annotation recasts it as "Vision (hypothesis, sponsor)" (L31). |
| CON-0030 platform ontology (L-B) | **INVALID-CONFIRMED**; reason restated | Membership is incoherent (no D-4; D-6b product-side, yet "ALL SEVEN", L57–63, L146). "Does not exist" is possibly stale given S0016 (F-20). Absence of an instance alone should not be the reason (F-08). |
| CON-0031 product ontology (L-C) | **INVALID-CONFIRMED**; reason restated | Definition overlaps L-A (`bounded-contexts.yaml` counted in both, S0020 L46, L48). "Partial" is a status of the instance, not a failed check. The Round29 catalogs were not seen. |

**Spot-check of the VALIDATED concepts CON-0012…CON-0033.** I would downgrade or re-base:
- CON-0012 (F-05);
- CON-0018 (F-02);
- CON-0019 and CON-0020 (no counterexample attempt, F-03);
- CON-0024 (F-24);
- CON-0027 (F-06);
- CON-0029 and CON-0032 (F-25);
- CON-0033 (F-14).

These hold as recorded:
- CON-0013 (add U-DM-4);
- CON-0014;
- CON-0016 (term properly qualified; standing stated);
- CON-0017 (add the counterexample, F-32);
- CON-0021;
- CON-0026 (the concept holds; the RC-42 Int around it does not, F-04).

Outside the requested range, briefly: CON-0005 rests on counts that are never shown ("ten rediscoveries"); CON-0006 has F-13; CON-0009 has F-11; CON-0010 has F-21.

## Checked and found correct
- **Quotes and anchors:** every line anchor I checked in RC-36…RC-44, Ch. 0 §0.2 and §3.1–§3.5 points to the cited line. The quotes are verbatim once emphasis is removed. This includes S0018 L79, L87, L94, L96, L121; S0019 L27, L54–56, L106, L173; S0020 L34, L69, L89, L147; S0017 L55, L68, L92.
- **Record headers:** dates, line totals, the S0019 and S0020 multi-temporal notes (apart from F-07), and the E-S0020 inference that L80 postdates S0011 (it cites S0011).
- **Labelled interpretations in the records:**
  - E-S0016 M2 and M3 (owner outside the model; one counter-pair shows non-dependence, not full independence), carried correctly into §3.1 L32;
  - E-S0018 §10 (chain vs act) and M2 (refutation vs a monotonic counter);
  - E-S0019's catch of L31 vs L29, and of R-7 "asserted; the artifact is missing".
- **RC-36:** the table of values (L79–83) and its status line ("CANDIDATE; PM-1 not decided") are faithful.
- **RC-37:** its table and quotes are faithful; only the deduction overreaches.
- **RC-39:** the dates and the n≥3 quote are faithful.
- **Ch. 0 §0.2:** the withdrawal of "two missions" and the N-1 breach are faithful.
- **§3.2:** the symmetry Int (L56) is faithful.
- **§3.5:** the three proofs and their strength words are reported "as given", which is correct attribution.
- **Where the records go beyond my blind extraction** and are faithful: S0017's catalog/log asymmetry, and the `derived` rank-2 fusion note (E-S0020 §10).
