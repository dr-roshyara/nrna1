# Theory turn 3 (the evaluator's inputs): settlement

*2026-10-20. By the Professor. Reports: `REPORT-A.md` (Opus, 17 findings: 6 high, 11 normal), `REPORT-B.md` (Sonnet, 13 findings: 8 high, 5 normal). Neither reviewer saw the other's report. Reviewer B had no web tool. Every new external work was verified by the Professor before it entered the book. S0053 was checked by the Professor at its anchor (L16–48; sha256 matching; logged).*

## 0. Deliverable status

**Met; one implication returned to the Principal.** §9.12 (version 2) gives CE's inputs (I1–I14), rebuilt from §1.14.1's formula, its answer specification and Q1. It states how they change with act-object granularity, with F37 (on endings: a lifting rule under (B); an aggregation rule or per-instance authority under (A)) and with A1's readings (which fix C1 and κ). For each setting it gives the inputs, the meaning, the falsifier of the reading kept apart from implementation defects, and the internal and world assumptions. **Returned to the Principal:** DB-1 fixes §1.14.1's open alternative C1 ("records constitute authority") by implication; this is for the Principal to confirm.

## 1. Research question (practice 1)

What CE must read, and whether F37 or A1's readings change it. Both reviewers found the first version's two main results too strong. B1 ("the same information in different form") is refuted by a record pair. B2 ("the readings never change the inputs") holds only for the kinds of records read, because the readings fix parameters.

## 2. Decisions

| IDs (A / B) | Finding | Severity | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|---|
| A-01, A-16 / F2, F4 | B1's "same information" false (records R1/R2: (B) depends on the order of acts across carriers); the falsifier searched only single-carrier claims | high | Accepted. B1 restated: the readings differ on **endings** (a lifting rule under (B); an aggregation rule or per-instance authority under (A)); R1/R2 in the text; the falsifier searched over multi-carrier records; they coincide exactly when endings act per carrier | §9.12 states the lifting rule; R1/R2 in §9.12; CON-0190 amended | **yes** |
| A-02, A-03 / F1, F3 | the carry relation with one pinned version is not what the estate's act needs (S0053: item by item, a negative scope, four commits); it is needed under (A) too; act-object granularity is a separate parameter; claim identity missing for (B) | high | Accepted, after the Professor checked S0053 L16–48. New input I14 (the act's coverage: positive list, negative scope, pin set); act-object granularity a parameter (documents vs claims); both readings list claim identity, carrier@version, the carry relation when acts name documents; open question 2 answered by S0053 | §9.12 lists I14; both rows list the same relation inputs; the differences are stated as rules | **yes** |
| A-05 / F5 | B2 fails: on record R0 (no adoption), A1 recognized must answer F, §1.14.1 under C1 = represent answers U; C1 missing from θ | high | Accepted. C1 added to I7; B2 restated: recognized and de jure fix C1 = constitute and κ = constitutive; as fact is compatible with C1 = represent; **the implication of DB-1 on C1 is returned to the Principal** | R0 with its answers per reading; I7 contains C1; the DB-1 consequence names C1 | **yes** (C1 for the Principal) |
| A-06 / F7, F8 | B3's counterexample refutes authenticity, not occurrence; "author not entitled" is authorization; integrity bears on every reading; domain exclusion is an alternative framing; I11 could absorb it | high | Accepted. Integrity status of entries is input I13 for every reading (§1.12-2); B3's counterexample is now an occurrence conflict (an intact denial by the named actor), answered **U**, not F; "author not entitled" removed; the domain-exclusion framing considered and not taken (Q1 must be total); the dependence on I11 stated | B3 uses an occurrence conflict; I13 stated; answer U with its reason | **yes** |
| A-07 / F13 | the input list was incomplete | high / normal: settled **high** | Accepted. I1–I14 rebuilt with a "read in" column: kind of c; validity set by the act; recording time; issuance acts; effective times of endings; C1; the queried scope; loss entries; integrity; coverage. As fact adds O3's marked absences | every symbol of the formula and Q1 maps to a row | **yes** |
| A-08 / F8 (part) | CE_fact under-specified: the reliability of refutations; recorded refutations vs verification material | normal | Accepted: a reliability rule for occurrence evidence and the choice between recorded refutations and verification material are named, with the unflagged forgery as the separating case | named in §9.12.4 | **yes** |
| A-09, A-10 / F9 | the DB-1 consequence restated the brief, overreached ("kernel's"), merged recognized with de jure, and fixed C1 silently | normal | Accepted. The consequence cites the brief's implication (Src); its subject is Q1's reference evaluator; kernel membership is turn 7; de jure and recognized give the same function; C1 returned. §1.6 now quotes the decision's parenthesis | the sentence cites the brief, names Q1 and C1; §1.6 and §9.12.4 agree | **yes** |
| A-04 | "authority" written where §1.6 says "standing" | normal | Accepted: the readings use "standing"; CE computes current authority, derived from standing with validity and P | §9.12.4 uses §1.6's wording | **yes** |
| A-11 | pinning stated as necessary | normal | Accepted: pinned, or live with detection or prohibition (§1.16.6) | the three options cited | **yes** |
| A-12 / F12 | premises Hyp or conditional but used as Ded; labels too strong | normal | Accepted: "acts name documents" is Hyp (and partly refuted by S0053, which lists items); (B) given F6 split; B2 labelled definitional; CON-0190's basis re-labelled | labels match their sources | **yes** |
| A-13 | the null-carrier repair omitted | normal | Accepted | mentioned | **yes** |
| A-14 / F6 | the falsifier column vacuous or conflated; as form needs stored statuses to be checked | normal | Accepted. Table split into "falsifies the reading" and "implementation defect"; as form needs the stored statuses; its falsifier (an unlabelled change) is possible for records not written through C6 | each row has a non-vacuous entry or a stated "—" with reason | **yes** |
| A-15 / F10 | §9.12.7 overstated internal-ness and was not carried into §9.10.6 | high (B) / normal (A): settled **high** | Accepted. §9.12.7 per setting, with the recorded act time and integrity as conditions; key binding and serialisation as world or implementation assumptions; §9.10.6's Q1 row names the reading | per-setting table; §9.10.6 updated | **yes** |
| A-17 / F11 | prior work thin (constitutive vs regulative rules; the rule of recognition; claim-level provenance; SPKI); EXT-057 without DOI; "transaction-time view" loose | normal | Accepted. Verified and added: Searle 1969/1995 (secondary sources), Hart 1961 (secondary sources), RFC 2693, Groth et al. 2010; EXT-057's DOI added; "bitemporal" | §9.12.8 rows; EXTERNAL-SOURCES updated | **yes** |

**Counts:** 30 findings (A 17, B 13) → 14 settled rows; found by both: 10 rows; one reviewer only: 4 (A 4). No critical finding.

## 3. Checks run on the corrections

- **Quote check (entry 60):** §9.12, §9.10: no failure in the checked form. The two S0053 quotations in §9.12.2 were checked by hand against L16–48.
- **R1:** H2 0.
- **R3:** CON-0190; §9.10.6 (Q1 row); §1.6 (DB-1 wording); the Ch. 9 index; BOOK-OUTLINE.
- **Read log:** today's anchor check-reads logged (`e6b41c920`).

## 4. Open items

| Item | Role | Reason | Next action |
|---|---|---|---|
| **C1** (records constitute or represent authority) | **Principal** | DB-1 fixes it by implication; §1.14.1 kept it open | confirm or revise |
| act-object granularity (documents or claims) | Professor | Hyp; S0053 names items | when more approval acts are read |
| lifting or aggregation rule for endings | Professor (decision follows F37, which stays a fork) | B1 | turn 4 states CE with the rule as a parameter |
| covers(B, x), the actor–holder link, the reliability rule | Professor | turn 4 | — |
| CON-0190: corrected, not re-reviewed | reviewers | — | turn 4's reviewers, where used |
