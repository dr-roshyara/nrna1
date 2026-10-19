# Review C4, Phase 2: records and book integration for S0021–S0025, with CV-1 and SQ-1

I wrote no files. The verdict is **UNSOUND**, and the cause is the book integration. Two foundational findings (F-01 and F-02) are the reason. Five concepts marked VALIDATED should be INVALID, and two more need their basis re-based. The five records alone are **SOUND WITH MINOR ISSUES**: their quotes and core content are accurate, but there is one chronology error and several omissions that matter.

## Scope and what I read

- **Read in full:**
  - records E-S0021 to E-S0025;
  - `CONCEPT-REGISTER.tsv`;
  - `RULE-CONCEPT-VALIDATION-20261018.md` and `RULE-SEQUENTIAL-THEORY-20261018.md`;
  - Ch. 3 §3.5, §3.6, §3.7 and the Ch. 3 TODO;
  - Ch. 5 index, §5.1, §5.2 and the Ch. 5 TODO;
  - Ch. 8 index, §8.1 and the Ch. 8 TODO;
  - the Ch. 0, Ch. 1 and Ch. 2 TODO files;
  - `BASELINE-REVISIONS.md` from L250 to the end, which contains C-4 parts 1–5.
- **Read by paging with offset and limit, not grep:**
  - Ch. 0: all of it;
  - Ch. 1: L1–1159 and L1400–1624, which covers RC-50, 51, 53, 55, 59, 64 and 65;
  - Ch. 2: L1–460 and L560–792, which covers RC-46, 47, 52, 60, 66, 67 and 68.
- The five sources were not re-read; the Phase 1 reads of all five are still in context and were used to check anchors.
- I opened no other file. I used one `wc -l` on the authorized book files to plan the paging.

---

## Findings

### F-01 · FOUNDATIONAL · Ch. 1 §1.11 RC-50; CON-0044; TODO T1-53

**What is wrong.** The Int says: "external authority is a source of binding force that the act model does not cover. This is an open gap in the book's theory." That is stronger than the evidence.

- **Hypothetical support.** The support is two hypothetical domains that the author built, plus the author's assertion about PublicDigit (S0022 L27, L115). RC-50 says "Projected into a hospital and an ERP system" and never says these are hypothetical.
- **The book already covers outside acts.** Ch. 1 §1.13.1 defines authority as "a recorded permission … whose legitimacy derives entirely from a human act performed outside the system". An external regulation can enter the act model in two ways: through an internal act that recognises it, or through a grant record that points to an external act. Neither alternative is considered.
- **Token counterexample.** The CON-0044 counterexample asks whether the organisation can amend an external rule by an internal act. That only restates the definition's own property ("non-amendable").
  - A real attempt would use a voluntarily adopted external standard, such as ISO certification or a standard accepted by contract. It is obeyed because of an internal act, and the organisation can drop it by withdrawing adoption.
  - That case breaks both "imposed, non-amendable" and the clean split between "who decides" and "who must be obeyed".
- **Same-day source not linked.** S0012 had already raised the same gap the same day ("obligation ≠ ownership", LG-6). It is recorded in the T1-15 evidence row but not linked from RC-50.
- **Duplicate TODO.** T1-53 duplicates T1-15.

**Evidence.** S0022 L27 (hypothetical), L108–117, L123, L66 (S0022 itself counts a regulation voiding policy as I-10 "held, and strengthened"); Ch. 1 L680 (AMS L49); Ch. 1 TODO T1-15 and its S0012 evidence row.

**Correction.**
- Rewrite the Int as Hyp/Open: external authority "may not be covered".
- State both alternatives (an internal recognition act; a grant referencing an external act).
- Say in §1.11 that the hospital and ERP domains are hypothetical.
- Downgrade CON-0044 to INVALID (partly valid).
- Merge T1-53 into T1-15.
- Cite S0012 LG-6.

### F-02 · FOUNDATIONAL · Ch. 1 §1.14.1 RC-59, Int bullet; CON-0054

**What is wrong.**
- The bullet heading "Genesis: the architecture has no root" and the sentence "The platform's own architecture has no equivalent of a root act" state the book's interpretation as if the source said it.
- The Int claims that the proposed clause "would play the role that root acts play … a starting point that needs no prior grant or evidence". That is wrong on both points:
  - The genesis gap is an **evidence prerequisite**. Reference Architecture §7 requires operational evidence before a first decision.
  - The clause makes the stakeholder's stated need *admissible evidence*. So it supplies evidence; it does not need none.
  - The clause records the first decision as *Provisional/Proposed*, which is not authoritative standing.
  - Root acts in §1.14.1 are authorized by stipulation and confer standing.
- This merges the evidential and governance-act engines that the book itself keeps apart (RC-37a: "granted vs earned … cannot be collapsed").
- The authority-side analogue of a root act is S0025 **F-1**: who may approve at day zero. RC-59 does not use it.
- S0025 also classifies genesis as a **governance** gap (L155), not an architectural one.

**Evidence.** S0024 L36, L159, L162; S0025 L115, L139, L155; Ch. 1 L877 (root acts), L300 (RC-37a).

**Correction.**
- Label the heading and first sentence as Int.
- Restate genesis as a gap in which evidence is admissible at day zero.
- Map root acts to the F-1 approval question instead.
- Keep T1-17 open with both readings.

### F-03 · ORDINARY · Ch. 1 §1.8 RC-8 against RC-64 and Ch. 8 §8.1: the same experiment, contradictory accounts, never connected

**What is wrong.**
- RC-8, citing S0002, says of the kernel-bootstrap experiment: "Every element survived unchanged."
- The primary report S0025, now read, says otherwise:
  - ES-005.1 "names PublicDigit and was ignored", and .4 is silent (L90);
  - PKS_Knowledge_Integrity_Model is "~10% used, ~90% source case law" (L92);
  - Phase_II is "~95% source issuance record" (L93);
  - the methodology documents are 73% of the kit and "mostly non-portable" (L97).
- RC-8's Ded ("portability is a property of the set, not of its elements") is still logically valid, but its evidential instance is contradicted.
- RC-64 calls S0025 the "primary source" but does not revisit RC-8. Ch. 8's index lists methods met elsewhere and omits RC-8.

**Correction.** Annotate RC-8 with S0025's per-document results, and link RC-8, RC-64 and §8.1 to each other.

### F-04 · ORDINARY · Ch. 0 §0.2 RC-54; CON-0051 (foundational candidate)

**What is wrong.** The Src statements are accurate, but four points overreach or are mislabelled:
- **AIP-14 not read.** "AIP-14's metric is not yet defined" and "exists as a name, not yet as a measure" are stated as facts about AIP-14, which was not read.
  - The evidence is the secretary's docket. Its REV 1 said "Compute the AIP-14 Platform Cost metric" (S0023 L20).
  - It was the PA's question (L32) that made the basis contested.
- **The test is not weakened.** "This weakens the mission-vs-constraint test further" does not follow. That test is about *form* (the statement ships with a metric and a review trigger). A disputed measurement basis does not remove either.
- **"Proposes" changes the status.** The docket does not offer a new text. It asks the sponsor to "Ratify (or amend and ratify) the mission the machinery already enacts".
- **Unlabelled comparison.** "It follows the annotation of S0019, not its body" is a comparison, not Src, and carries no label.

**Correction.**
- Attribute the claim to the docket: "per the docket (REV 2), the basis is undecided; AIP-14's own text not read".
- Drop or hedge "weakens … further".
- Use the docket's own ratification wording.
- Label the S0019 comparison Int.

### F-05 · ORDINARY · Ch. 1 §1.14.3 RC-65 Int; T1-54 (foundational candidate)

**What is wrong.** The Src is faithful (S0025 L114, L129, L141). The Int ("this tests the reading above. If 'party' means a role-holder in a session, fresh sessions satisfy §2") narrows S0025's F-1 to the meaning of the word "party". But F-1 is a conjunction of several blocks:
- §2 (one party may not be Engineer and Independent Reviewer);
- §3 (no skipping);
- §5 (no implementing before approval; approval is a human act, and sessions cannot supply it by the book's own §1.6 and §1.14.3);
- the only light gate belongs to the source project.

Fresh sessions can satisfy at most §2. S0025's "an organization with a named architect … WARN" also points to a person. Process independence, as the book reads it, does not lift F-1.

**Correction.** State in the Int that the "party" reading settles only §2, and that §5 and the light gate are separate blocks.

### F-06 · ORDINARY · Ch. 2 §2.4 RC-66; CON-0062

**What is wrong.** "This is a genuine conflict in the sense of §2.3 … Both rules bind" does not follow:
- **Standing.** §2.0 (RM-1) requires a rule to have standing. ES-003 is among the "six PROPOSED standards" (S0023 L114; Ch. 1 T1-47), and CAP-001 is "unauthorized" in its catalog (S0023 L102).
- **Shared values.** The two sets share PASS, WARN and FAIL, so both can be obeyed at once. Under the §2.3 definition (mandatory effects jointly unsatisfiable), a conflict exists only in the region where a case requires INCONCLUSIVE or PASS AFTER CORRECTION.
- **A possible scope by actor.** Ch. 1 §1.9 records PASS AFTER CORRECTION as *reserved to human review acts* (`Verdict.php`), and records TR's finding that two verdict vocabularies "never meet". CAP-001 forbidding a mechanism to *emit* PASS AFTER CORRECTION may therefore be a scope by actor, not a collision. "Nothing scopes them" is the session's claim; the book's own §1.9 contests it.
- **Token counterexample.** CON-0062's attempt restates the definition's own clause ("nothing scoping them").

**Correction.**
- Reclassify as a candidate conflict, limited to that region.
- Address standing (§2.0) and the §1.9 reserved-verdict reading.
- CON-0062 → INVALID (partly valid).

### F-07 · ORDINARY · Ch. 2 §2.10 RC-60; CON-0055, CON-0056

**What is wrong.**
- **Not rules by §2.0.** The 41 "enforced controls" are `permissions.deny` and `permissions.ask` (S0024 L74–78). §2.0 (RM-1) explicitly excludes "permissions/access controls" from rules. So the Int "the two sets (rules with meaning, rules with enforcement) sit in different components" compares rules with things that are not rules by the chapter's own definition.
- **The "advisory 16" count conflicts with the book's own evidence.**
  - The book redefines the kinds by *effect* (CON-0055 basis), while the counts are by *mechanism*.
  - The book itself records a destructive-command gate that blocks (Ch. 2 §2.8.6, SD L77–81, `db-safety-check.sh`).
  - It also records guards whose printed message and actual effect differ (Ch. 1 §1.12).
  - So "10 hooks + 6 scripts = 16 advisory" and S0024's "Every automation is advisory" (L86) are not established under the effect reading.
- **Governing record not seen.** The CON-0055 governing record ("Boundaries ≠ Triggers") was not seen, so check 4 fails.

**Correction.**
- State that the enforced items are tool permissions in the RM-1 sense.
- Reconcile the count with the blocking gate, or mark the count as by mechanism only.
- CON-0055 → INVALID (partly valid / not verifiable).

### F-08 · ORDINARY · Ch. 5 §5.2; CON-0053

**What is wrong.** The paradigm case is classified inconsistently across and within the sources:
- S0024 calls genesis the architectural gap with "no place in the architecture to put it" (L155), yet sizes it as "one clause" (L162).
- S0025 puts genesis under governance (L155), and its addition A gives the clause existing homes, Reference Architecture §7 and ES-002.1 (L139).
- S0024 marks the Addendum's "the gap is not architecture, it's evidence" as CONFIRMED (L24) while counting one architectural gap.
- The book's rescue, "by primary closure", is the book's own addition with no criterion for which need is "primary".

**Correction.** CON-0053 → INVALID (partly valid). Record the S0025 classification and define "primary closure", or drop it.

### F-09 · ORDINARY · Ch. 5 §5.1 Int ("a general rule"); CON-0050

**What is wrong.**
- **Too strong.** "An item counts as evidence for a decision only if its meaning does not depend on the answer to that decision" overstates. Evidence that supports the conclusion under every candidate answer (robust evidence) does count. The source only downgraded *its own* line pending the definition (S0023 L34).
- **Token counterexample.** The attempt ("kept as illustration: consistent") restates the source.
- **Unbounded claim.** "Stated once in the corpus" covers a corpus the book has not fully read.

**Correction.**
- Qualify: "unless it supports the conclusion under every candidate answer".
- Say "in the corpus read so far".
- CON-0050 → INVALID (partly valid) until restated.

### F-10 · ORDINARY · Ch. 8 §8.1: misreadings and silent edits

**What is wrong.**
- **(a) Wrong count.** "The report proposes seven additions … and puts them to the Authority 'as candidates'": the source puts only **A, B, C and F** forward (S0025 L173).
- **(b) Silent edit of a quote.** "Missing: … a product-neutral strategic method [L187]": the source says "strategic method". The book has silently corrected the quote to fit the F-3 refutation. The body never carried that refutation forward: L130 still calls F-2 and F-3 "the firm findings", L169 says "Close F-2 and F-3 first", and L187 still lists strategic method as missing. This should be reported, not patched.
- **(c) Endorsement.** "Three others **rightly** rejected": the source says "deliberately rejected".
- **(d) Overstatement.** "A non-empty [register] refutes it" is too strong. Contamination reaches (#11, #12) and reach #2 (the template existed but was unindexed) measure the session or the indexing, not only the kit.
- **(e) Tension not reported.** The scope correction calls the EEP process "VALIDATED" (L22). The per-scope verdict "Execution governance (EEP conformance)" is FAIL (L108), and the EEP is "also the source of the blocking finding" (L86). §8.1 lists the protocol under "What transferred" without noting this.

**Correction.** Fix (a) and (b). Report the propagation failure and the EEP tension. Soften (c) and (d).

### F-11 · ORDINARY · Ch. 8 index premise and the decision to open Ch. 8

**What is wrong.**
- The index says "The theory claims … that KnowledgeOS is independent of any one product". The book has not established this as its own theory's claim. It is CON-0027 (INVALID) and the EEP's DRAFT claim (S0025 L161).
- §8.1 validates a **platform** claim of the corpus, not the book's theory. Placing it in Ch. 8 ("How the theory is validated") is defensible only as a methods chapter. The index should say it collects the corpus's validation methods and their limits as candidate methods for the book.

**On the decision to open the chapters.** Opening Ch. 5 for S0023 fits well. §5.2 (gap kinds) is a weaker fit; its labelled Int rationale ("which kind of decision is needed") is acceptable.

**Correction.** Reword the index: the claim under test is the corpus's, through CON-0027 (invalid).

### F-12 · ORDINARY · Record E-S0022, "Order with S0021"

**What is wrong.** "S0021's I-11 restatement (S0021 L115) precedes this projection" is wrong as stated. The S0021 L115 row reports projection *results* ("The restatement SURVIVED cross-product projection", "validated in all three domains"), so the row as written postdates S0022. Only the restatement *event* precedes the projection (S0022 L12, L16).

**Correction.** Restate it: the event precedes S0022; the S0021 L115 text was written after S0022 (likewise L7).

### F-13 · ORDINARY · Record E-S0024: misplaced strength word and omissions

**What is wrong.**
- **Strength word on the wrong claim.** P1 attaches "CONFIRMED" to the 1/1/4 count. At L24 the source attaches CONFIRMED to the Addendum's "the gap is not architecture, it's evidence", which conflicts with its own one architectural gap. That tension is not recorded.
- **Count failures the record missed.**
  - BINDING says "4 of 11" (L65), but 5 items are listed in the same cell and 6 rows have a coupling.
  - "Only 3 have more than a single instance" (L66) is stale after row 2 was corrected to 92 artifacts.
  - "9 of 11" (L35) is never identified.
  - Rows 2, 5, 7 and 9 sit in neither §4 nor §5.
  - The correction places enforcement in the runtime adapter, yet row 7 still shows "not enforcing".
  - Row 2's level was not re-scored, although L14 says its scoring is affected.
  - L155 ("cannot be closed by a ruling") conflicts with L162 ("one clause").
- **Cross-file contradiction missed.** L129 says a Product PKS is "one per product, by definition", against S0022 F-2. Neither the record nor §3.6 RC-61 notes it; RC-61 even drops "one per product, by definition".
- **Unlabelled inference.** "Corrections … must therefore postdate the body" is an inference without an [I] label.

**Correction.** Add these to §7 and §10 of the record, and add the PKS contradiction to §3.6.

### F-14 · ORDINARY · Record E-S0023 and Ch. 5 §5.1: a cross-file contradiction missed

**What is wrong.**
- **Selective citation.** Package 7 supports ratification by citing the bootstrap's success ("survived a foreign-domain bootstrap with zero translation", L116). It omits S0025's EEP-conformance FAIL (L108).
- **ES-005 first.** The docket recommends starting with "ES-005 and the EEP" (L121). S0025 L90 and S0024 L52/L97 find ES-005 partly unusable and binding-leaked.
- **The 13% figure.** The docket uses "the 13%-declared-scope finding" as evidence of falsification (L64). S0021 L75 and Ch. 3 §3.5 (S0020) treat 13% as a scope decision, not a bug.
- **Stale scope line.** L10 still says "the ten D-items".
- **A possible fourth ontology.** Package 4 counts three ontologies and does not mention S0021. Ch. 3 §3.5 repeats the "three".

**Correction.** Record these in E-S0023 §10, and note the Package 7 tension in the §5.1 table, row 7.

### F-15 · ORDINARY · Record E-S0025: omissions

**What is wrong.**
- The F-3 refutation was not carried into the body (L130, L169, L187); the record does not say so.
- The scope correction ("validated: the engineering process") conflicts with the EEP FAIL; not recorded.
- §9 reaches 15 files only by assuming "three PKS methodology documents" that the source never names. §4 names 14 files.
- Reach #13 came from the brief, not from the inherited `CLAUDE.md`, so "refused two, disclosed a third" (L43) frames contamination differently.

**Correction.** Add these to §7 and §10 of the record.

### F-16 · NOTE · Records E-S0021 and E-S0022: smaller omissions

**What is wrong.**
- S0021 L10 says "One is admitted", but Strategy, Principle, Design Policy and Knowledge Space are all admitted.
- "12+ charter artifacts" names only 11. §3.6 repeats "12+" without this caveat.
- VISION is absent from the chain (L38–41).
- `depends on` contains a **cycle**: PKS depends on Product (L92) and Product depends on PKS (L93). E-S0021 §7 says "acyclic as written", scoped to L83–88, which invites a misreading.
- In S0022, PRODUCT is graded INVARIANT and counted among "8 of 12 … intact" (L48, L158), yet listed as "Requires specialization" (L161).
- S0022 says the model was held fixed, but accepted correction 3 (L18); the record does not note this.

### F-17 · NOTE · Ch. 3 §3.6 L76

**What is wrong.** "A capability protects exactly one design policy (I-2)" misquotes the source, which says "EXACTLY ONE invariant (one DP-n)" (S0021 L106). Ch. 2 RC-46 states it correctly.

### F-18 · NOTE · Ch. 1 §1.2 RC-51

**What is wrong.** The heading "Where the split first appears" contradicts the same paragraph ("order not established"). S0012 L152 states the same split on the same day.

**Correction.** Change "first appears" to "also appears".

### F-19 · ORDINARY · Ch. 1 §1.1 is stale

**What is wrong.** L37 still says of I-11(RO): "Cited-only: the Relationship Ontology is a corpus document I have not read". S0021 has now been read, and RC-45 and CON-0039 use it. §1.1's configuration counterexample is stronger than the one CON-0039 records.

**Correction.** Update §1.1 to Src (S0021 L115), and link it to §3.6 and CON-0039.

### F-20 · ORDINARY · Ch. 3 §3.7; CON-0043, CON-0046: earlier book text not linked

**What is wrong.**
- The book's own Ch. 1 §1.11 RC-26 (S0012) says "a deployment IS a Knowledge Space", nested, with no new concept needed. That is a definition beyond example, but contested (RC-26a).
- T1-41 records S0012 calling SPECIALIZES the one genuinely ontological gap.
- Neither is linked from §3.7, and CON-0043's reason ("defined only by example") ignores both.

### F-21 · NOTE · CON-0045 basis

**What is wrong.** The basis omits that S-3's evidential ground (F-1, the I-4 counterexample) was **withdrawn by S0022 itself** (L73–79).

### F-22 · NOTE · CON-0052 reason may be mistaken

**What is wrong.** Ch. 0 §0.1 cites S0002 L17 as saying both "observed eleven times" and that redoing the discovery "would be occurrence #12". So n = 11 against "#12" may be actual against prospective, not a discrepancy. The real failing check is that the occurrence list is not shown.

### F-23 · ORDINARY · Ch. 2 §2.8.3 RC-67 Int: a step is missing

**What is wrong.** SC-2 governs an *unspecified scope dimension of a rule*. It says nothing about a rule that is absent or deferred. "Under SC-2 … a deferred rule therefore leaves the question undetermined" needs an argument.

**Correction.** Derive the point from §2.11 ("Effective = standing adopted ∧ t ∈ validity") instead (see CON-0063 below).

### F-24 · NOTE · TODO and record hygiene

**What is wrong.**
- T1-15 and T1-53 both track external authority.
- T0-01 cites "Ch. 0 L167–177", but the invariant table is now at L231–243.
- The BASELINE-REVISIONS hash tables omit the new or changed TODO files: chapter_05/TODO.md and chapter_08/TODO.md (both new), and the edits to the Ch. 0–3 TODO files.

### F-25 · NOTE · Ch. 1 §1.17.1 RC-53

**What is wrong.** "Eleven one-page packages, each with evidence on both sides, risks both ways …": Packages 8–10 lack some fields, as §5.1 itself notes, and Package 11 is not a D-item.

### F-26 · ORDINARY · Ch. 3 §3.6 RC-61; CON-0057: missing links

**What is wrong.**
- The kernel test is not linked to Ch. 0 §0.9 Q2 ("what are 'K' and the 'kernel'?").
- S0025 L153 says the Kernel "remains HYPOTHESIS". The book does not mention it.
- S0024's kernel list includes the "EP-01/02/03 role model". S0025 reach #12 treats that as source-project property (contamination).

### F-27 · NOTE · Ch. 5 §5.2 L43

**What is wrong.** The book quotes Ch. 1 §1.8's LA-4 ("n ≥ 2, exercised by another") as "the rule it breaks". S0024's own P3 reads "n ≥ 2 with a repeatable pattern extracted" (L38). The two wordings differ, and the book does not say so.

---

## CV-1 table

**VALIDATED concepts: spot-check**

| ID | Counterexample attempt | Governing record and its standing stated? | My result |
|---|---|---|---|
| CON-0038 invariant vs implementation choice | Real but narrow: it tests who changed I-4/7/11, not whether the criterion decides which items are invariants | yes (generated candidate) | **Agree VALIDATED**. Note: the source gives no test for classifying a new relation |
| CON-0039 knowledge does not execute | **Token**: a lint rule is the textbook case. Ch. 1 §1.1's configuration case is stronger and shows the line is drawn by "who executes", which makes the statement close to definitional (SEQ-12) | partly ("review not identified") | **VALIDATED only if re-based** on §1.1's counterexample with its condition stated |
| CON-0044 external authority | **Token** (it tests the definition's own property) | yes | **INVALID (partly valid)**, see F-01. Fails on a voluntarily adopted standard; support is hypothetical only |
| CON-0047 falsification by projection | Real (the domains are hypothetical) | yes | **Agree VALIDATED**. Add a limit: the method's one "falsification" (I-4) was itself withdrawn as a misdiagnosis |
| CON-0048 decision package | Real (fields vary) | yes | **Agree VALIDATED** |
| CON-0049 gating order | Real (application is partial) | "candidate" only | **Agree VALIDATED** |
| CON-0050 presupposing evidence | **Token** | yes | **INVALID (partly valid)**, see F-09: fails on evidence that holds under every candidate answer |
| CON-0053 gap kinds by closure | Real, but the rescue ("primary closure") is undefined | yes | **INVALID (partly valid)**, see F-08 |
| CON-0055 boundary / trigger / advisory | Real, and it actually exposes a defect: the kinds are by effect, the counts by mechanism, and the book records a blocking gate | **not seen** | **INVALID (not verifiable / partly valid)**, see F-07 |
| CON-0056 maturity ≠ enforcement | Weak (it tests the reverse direction) | yes | **Agree VALIDATED** (the meaning gives the components). The L0–L4 levels are undefined (SEQ-2) |
| CON-0058 reach register | Real | yes (n = 1) | **Agree VALIDATED**. The §8.1 wording "refutes" is too strong (F-10d) |
| CON-0059 domain-free ≠ portable | Real (both directions) | yes | **Agree VALIDATED** |
| CON-0060 scaffolding contamination | Real (qualified) | yes | **Agree VALIDATED** |
| CON-0062 closed-vocabulary collision | **Token** | yes | **INVALID (partly valid)**, see F-06 |

**INVALID concepts: §8a second attempt**

| ID | Result | Basis |
|---|---|---|
| CON-0034 design policy as anchor | **INVALID-CONFIRMED** | The catalog binding table is out of scope. "EVERY" rests on four examples. RO-1 is undecided |
| CON-0035 strategy layer | **INVALID-CONFIRMED** | The charters are not in scope. "12+" names 11. The kind is outside the declared set |
| CON-0036 principle layer | **INVALID-CONFIRMED** | The registers are not in scope. The SD register holds SD-1, which S0024 calls a possible "product binding" (L167), so membership in a principle layer is unsettled |
| CON-0037 relation types | **INVALID-CONFIRMED** | The source applies the relations inconsistently, and `depends on` has a PKS↔Product cycle (S0021 L92–93) |
| CON-0040 void-overriding | **INVALID-CONFIRMED** | Undefined. Round39-MC and Round47-00 are out of scope |
| CON-0041 read across a boundary, never own | **INVALID-CONFIRMED** | Only one actual instance (n = 1) |
| CON-0042 PKS | **INVALID-CONFIRMED** | I-7 is breached. "One per product" conflicts: S0022 F-2 says it fails, S0024 L129 says it holds "by definition". The boundary is unstated |
| CON-0043 deployment | **INVALID-CONFIRMED, reason to restate** | A definition does exist (S0012, via Ch. 1 RC-26) but is contested (RC-26a), so it is not "defined only by example" |
| CON-0045 inert vs attested | **INVALID-CONFIRMED** | Add: its evidential basis (F-1) was withdrawn by the source itself |
| CON-0046 structural vs transformational | **INVALID-CONFIRMED** | Grouping partly definitional; link T1-41 |
| CON-0051 platform-cost metric | **INVALID-CONFIRMED** | Basis undecided according to the docket; AIP-14 itself not read |
| CON-0052 model amnesia | **INVALID-CONFIRMED, reason to restate** | 11 against #12 may be actual against prospective (Ch. 0 §0.1). The real reason is that no occurrence list or measurement is shown |
| CON-0054 genesis | **INVALID-CONFIRMED, reason to restate** | "OQ-S3 open" is a status, not a failed check. The failing check is support: Reference Architecture §7 not read, n = 1, and S0024 (architectural) and S0025 (governance) classify it differently |
| CON-0057 kernel admission | **INVALID-CONFIRMED** | Tiers undefined. The list includes items not adopted. S0025 L153 says the Kernel remains a hypothesis |
| CON-0061 trigger-bound deferral | **INVALID-CONFIRMED (not verifiable)** | No deferral record read. The "who decides" problem belongs to the instance, not the definition. A second instance exists for a retry: Ch. 1 RC-19, the unfired PB-004 trigger |
| CON-0063 a deferred rule cannot be cited as authority | **VALIDATED** | Derived from Ch. 2 §2.11 ("Effective = standing adopted ∧ t ∈ validity"): a deferred rule is not effective, so it cannot bind. Counterexample attempt: both senses of "deferred" (an undecided rule; an adopted rule whose effect is postponed) give "not currently effective", so the claim holds. Source fidelity: S0025 L74. Standing: a book derivation; the source rule's own record is not seen. RC-67's further step via SC-2 is not validated (F-23) |

---

## SQ-1: sequence and gap check

**What I checked.** Every RC-45 to RC-68 passage named above, Ch. 3 §3.6 and §3.7, Ch. 5 §5.1 and §5.2, Ch. 8 §8.1 and the indexes. For each I checked the concepts used against the earlier definitions in Ch. 0 §0.2–0.9, Ch. 1 §1.1–1.17 and Ch. 2 §2.0–2.11.

**Back-pointers from Ch. 5 and Ch. 8 into Ch. 1 that resolve correctly:** §1.6/RC-37a; §1.8 LA-4; §1.17.1/RC-40a; §1.14.3/RC-65; root acts/RC-59.

**Forward references that are correctly marked:** RC-46 → Ch. 3 §3.6; RC-53 → Ch. 5 §5.1; RC-54 → Ch. 5 §5.1; RC-57 → T5-03.

**Gaps found (provisional numbers):**

| SEQ | Type | Location | Concepts | What would close it |
|---|---|---|---|---|
| SEQ-1 | undefined, plus an unmarked forward use | "runtime adapter": Ch. 1 §1.5, §1.11 RC-10; Ch. 2 §2.10 RC-60; Ch. 3 §3.6 (kind and owner only) | C-12 (no CON ID) | A definition, or an "undefined" TODO, with a pointer from §2.10 |
| SEQ-2 | undefined | "maturity" / levels L0–L4: Ch. 2 §2.10 RC-60 | CON-0056 | Define it, or cite the source of the levels |
| SEQ-3 | inconsistent order | Ch. 2 §2.10 RC-60 counts tool permissions as "rules with enforcement", against §2.0 (RM-1) | CON-0055, CON-0056 | State the RM-1 status of the 41 controls |
| SEQ-4 | inconsistent order | Ch. 2 §2.4 RC-66 "both rules bind", against §2.0 (standing) and Ch. 1 RC-34/T1-47 (proposed standards) | CON-0062 | Address standing |
| SEQ-5 | missing link | Ch. 2 §2.4 RC-66 against Ch. 1 §1.9 (reserved verdicts; "never meet"; Decision B) | CON-0062 | Link them and reconcile |
| SEQ-6 | missing step | Ch. 2 §2.8.3 RC-67: SC-2 extended to a deferred rule | CON-0063 | Derive from §2.11 instead |
| SEQ-7 | missing step / inconsistent order | Ch. 1 §1.14.1 RC-59: genesis clause equated with a root act, against RC-37a | CON-0054 | See F-02 |
| SEQ-8 | missing link | Ch. 1 §1.8 RC-8 (S0002 account) against RC-64 and Ch. 8 §8.1 (S0025): the same experiment, conflicting statements | CON-0054, CON-0058, CON-0059 | See F-03 |
| SEQ-9 | missing link | Ch. 1 §1.1 (I-11(RO) still "cited-only") against Ch. 3 §3.6 | CON-0039 | Update §1.1 and cross-link |
| SEQ-10 | missing link | Ch. 3 §3.7 against Ch. 1 §1.11 RC-26 (deployment = nested space) and T1-41 (SPECIALIZES) | CON-0043, CON-0046, CON-0011 | Link them; restate the CON-0043 reason |
| SEQ-11 | missing link | Ch. 1 §1.11 RC-50 against T1-15 and S0012 LG-6 (an earlier same-day statement of the gap) | CON-0044, CON-0026 | Link; merge T1-53 into T1-15 |
| SEQ-12 | circular | Ch. 1 §1.1 draws the knowledge/capability line by "who executes"; CON-0039 then says "knowledge does not execute; capabilities execute" | CON-0039 | Give an independent criterion for one side, or record it as a stipulation |
| SEQ-13 | missing step | Ch. 8 index: "The theory claims … independent of any one product" is not established before this point; it rests on CON-0027 (INVALID), Ch. 0 §0.2 | CON-0027 | Restate it as the corpus's claim under test |
| SEQ-14 | undefined | "primary closure" in Ch. 5 §5.2 (counterexample paragraph) | CON-0053 | A criterion, or drop it |
| SEQ-15 | missing link | Ch. 3 §3.6 RC-61 kernel test against Ch. 0 §0.9 Q2 (the kernel) and S0025 L153 (Kernel is a hypothesis) | CON-0057 | Link both |
| SEQ-16 | missing link (imprecise back-pointer) | Ch. 5 §5.1, "the three-valued reading of Ch. 1" with no section given | CON-0048 | Point to Ch. 1 §1.14.1 / §1.17.3 |
| — | undefined (already recorded) | "party" in Ch. 1 RC-65 | T1-54 | Already tracked; no new ID needed |

**Ch. 2 §2.4–§2.11 using terms first defined in Ch. 3 or Ch. 5.**
- RC-46 marks its forward use of Ch. 3 §3.6.
- RC-60 uses "runtime adapter" without a pointer (SEQ-1).
- No term from Ch. 5 is used in Ch. 2.

---

## Checked and found correct

- **Records.** All five records' line totals, dates and status words match the sources. The quotes I spot-checked match; Q-anchors were checked against my Phase 1 reads.
- **Count inconsistencies the records claim.**
  - S0022's tally is correct as stated: the table shows 8 / 1 / 2 / 1 = 12, against "2 specialize" at L52 and 3 specializations at L161.
  - S0024's "4 evidence" against "3" (L24 and L208 against L175 and L199) is correct.
  - 41 = 19 + 22 and 16 = 10 + 6 are correct.
- **Chronology in E-S0025.** "S0024 follows S0025" is correct: S0024 L8 lists the bootstrap in its closed baseline, and S0024 L161 repeats S0025 L120.
- **Multi-temporal layers** in E-S0021, E-S0022, E-S0024 and E-S0025 are correctly identified.
- **Book passages faithful to their sources:** RC-46, RC-47, RC-48, RC-52 (Src part), RC-53, RC-55, RC-57, RC-58, RC-62 (Src part), RC-63 method table, RC-64 quotes, RC-68.
- **§3.7.** The tally, I-6 "vacuous" and I-7 notes are correct. The §3.7 L40 quote of L115 matches once markup is removed.
- **§5.1.** The missing-fields claim is correct: Package 8 has no "Why it exists", and Packages 9 and 10 have no "Downstream".
- **Ch. 5 §5.1 opening.** It is placed and scoped correctly ("recommendations; none is a decision").
- **CON-0063** can be validated by a derivation already in the book (§2.11).
