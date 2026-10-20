# Theory turn 2 (the kernel type, derived from Req): settlement

*2026-10-20. By the Professor. Reports: `REPORT-A.md` (Opus, 20 findings: 3 critical, 7 high, 10 normal), `REPORT-B.md` (Sonnet, 15 findings: 1 critical, 6 high, 6 normal, 2 low). Neither reviewer saw the other's report. Reviewer B had no web tool; reviewer A checked bibliographic records only. Every external work below was verified by the Professor before it entered the book.*

## 0. Deliverable status

**Met.** §9.11 (version 2) classifies 31 laws with a reason for each. Every "C6 cannot express" claim has a counterexample structure. Every "C6 expresses" claim follows from the type's definition, with C3's reach stated per class. R1 (whole histories) is proved, step by step, under two named premises: P-loss, and one shared record for uniqueness across applications. Prior work is checked. Internal guarantees are kept apart from world and environment assumptions.

## 1. Research question (practice 1)

Can C6 express every law and Req's consequences, and for which does C3 hold? Both reviewers found that the first version answered a different question than it claimed. It said "C6 cannot express" where C6's definition admits the law, and the failure was C3's or enforceability's.

## 2. Decisions

| IDs (A / B) | Finding | Severity settled | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|---|
| A-4, A-5 / F1, F2 | R1 fails: H1′'s recorded loss can drop a consumed act, so the record is not a valid history variable; a past cut is not a prefix after a loss; §9.10 Q1's "same call, same answer" contradicts H1′ | **critical** (B) / high (A): settled **critical** — CON-0189 cited it, and it was false (R5 F1) | Accepted. Premise P-loss added (a loss entry keeps the consumed acts' identities; §9.10 O3); R1 proved step by step; law 16 restated with recorded losses; §9.10 Q1's consequence restated (a loss is reported, or the loss entry's kept content is used); replay given Lemma D's premises | R1 states the premise and excludes the run; §9.10 Q1 agrees with H1′ | **yes** |
| A-1 / F3 | "C6 cannot express L" is not derivable; C6's I_R is unrestricted | **critical** (A) / high (B): settled **critical** — a central claim of the turn was false | Accepted. L is expressible as I_R; what fails is C3 (R4 counterexample) and, in an input-driven type, the "eventually" needs an environment assumption. §9.1 C6 states that I_R is unrestricted and that C3 needs the restriction | §9.11.2 and C6 define I_R the same way | **yes** |
| A-2, A-3 / F8, F12 | laws 21 and 24 are not hyperproperties (C6's system contains the applications); the K5 counterexample is false under F26(b); R3's falsifier inverted §9.7 | **critical** (A) / normal (B): settled **critical** — the counterexample was a run where the law holds | Accepted. Law 21: S/O over the composed state, holding under F26 (b), (c) with equal act sets at the cut; law 24: H over the composed run, keeping it needs one shared record (enforceability); only law 22's second part is an HP, 2-safety; the rival's cost withdrawn | R2's HP list holds only HP laws; F26 cited correctly | **yes** |
| A-6 | R1 (iii) confused inputs with mechanisms and hid K4/F22 | high | Accepted: (iii) holds for the kernel's mechanisms; across applications only with one shared record (K4, F22) | R1 and law 24 carry the condition | **yes** |
| A-7 / F6 | F33 not AE as worded; it collides with the input-origin condition | high | Accepted. Law 20 split: 20a AE over X (deadlock), 20b W/E (the outside act arrives); the AE counterexample is a structure in C6's terms | two rows; counterexample in C6's terms | **yes** |
| A-8 / F5, F13 | law 18's "eventually" is an environment obligation; the T reading exists; §9.11.6's "scheduler fairness, internal" wrong | high | Accepted. Law 18 has two readings: (i) T (bounded response 0, C3 holds), (ii) L + E; new class E (environment assumption, Abadi and Lamport 1993); §9.11.5 rewritten | §9.11.5 names E; law 18 shows both readings | **yes** |
| A-9 | no output for refusals: O1's reply and REPORT not requirable | high | Accepted. §9.1 C6 result 8: replies (a Mealy-style output); conditions on replies are O-type | C6 states where the reply lives; law 19 cites it | **yes** |
| A-10 | law 4's guard half (refuse only on an absent record) unseen by S, T, H | high | Accepted. Law 4 split: 4a T; 4b O on replies (C6 result 8), outside C3 (guards) | two rows with classes | **yes** |
| F4 / A-17 | Req's inputs read as EF from s₀ in C2, "from a valid state" in §9.10.2 | high | Accepted. §9.10.2 now says EF from s₀, matching C2; enabledness recorded as a stronger rival (open) | one reading in §9.10.2 and C6 | **yes** |
| F9 / A-15 | the list of laws incomplete; no selection rule | high (B) / normal (A): settled **high** | Accepted. Selection rule stated (sections and search terms); laws 26–31 added (§9.4, §9.5 C1, §9.6 K1, K2, merge, §5.5 C3); H4 noted with law 14; §1.16.1 and §1.16.7 in laws 12 and 26 | the table's scope sentence names the rule | **yes** |
| F10 / A-14 | R4 overstated (guards; closed reading; T3 class); no counterexamples | high (B) / normal (A): settled **high** | Accepted. R4 per class, with the L loop counterexample and the AE trap/exit argument; guards and the T3-class scope named | R4 has a counterexample per class and its scope | **yes** |
| A-11 | R3 missed the third option (a sufficient condition the type carries) | normal | Accepted: option (c) added (bounded response; unwinding-style conditions; enabledness), with its cost | R3 has three options | **yes** |
| A-12 | "C6 does not express AE" needs I_S fixed independently of M | normal | Accepted: §9.1 C6 states the premise that I_S, I_T, I_R and reference definitions are given independently of (M, G) | the premise stated | **yes** |
| A-13 | "AE: neither safety nor liveness of single runs" mis-phrased | normal | Accepted: AE and HP are "not properties of runs" | reworded | **yes** |
| A-16 / F7, F14, F15 | row slips: law 15 "absent" vs partial; law 5 narrowed; law 10's act time is W; law 12 vacuous; law 9 bundled; law 9's home §1.16.7 wrong; "yes" for Q1, Q3, Q4 vacuous; the root act is not in s₀ | normal / low | Accepted: law 5 presence only, presentation as application; law 10 split (10b W); law 12 vacuous with its refinement and serialisation content; law 9 split (9a idempotence, 9b uniqueness from §1.13 and C6); law 15 "vacuous until the reference definition exists"; R1 (i): the root act is an input | each row matches its home | **yes** |
| F11 / A-18 | prior work for the classification exists | normal | Accepted and verified: Manna and Pnueli 1990; Dwyer, Avrunin and Corbett 1999; Owicki and Gries 1976; Terauchi and Aiken 2005; Abadi and Lamport 1993; Podelski and Rybalchenko 2004 (homonym). Not used: KAOS, Goguen and Meseguer, Barthe et al. (not needed for a claim in the text) | §9.11.6 cites them with status | **yes** |
| A-19 | CON-0189's "iff" and "append-only" overstated; the F36 list rested on rejected reasons | normal | Accepted: CON-0189 rewritten; F36 restated (per-law options (a), (b), (c)) | register and map match the settled text | **yes** |
| A-20 | dependencies not cited at the steps using them | normal | Accepted: Lemma D, §9.8's "except for losses", §9.7 F26, C6 "Applications", §9.9.5, §9.10.7 and §9.10.3 E cited at the steps | each step names its dependencies | **yes** |
| B: law 11 | "the answer was computed by the pinned evaluator" is W | — (in B's table) | Accepted: law 11 split (11b W) | row split | **yes** |
| B: law 23 | the reference of Q5's liveness depends on E6's resource bound | — (in B's table) | Accepted (noted in law 23) | noted | **yes** |

**Counts:** 35 findings (A 20, B 15) → 20 settled rows; found by both: 10 rows; one reviewer only: 10 (A 8, B 2, of which two came from B's per-law table). Critical after settlement: 3 (R1; L expressibility; HP misclassification), all resolved.

## 3. Checks run on the corrections

- **Quote check (entry 60):** §9.11, §9.10, §9.1: no failure (no quotes in the checked form).
- **R1 (register homes):** H2 0.
- **R3 (dependants):** §9.10 Q1, §9.10 O3 and §9.10.2 corrected in this settlement; §9.1 C6 (I_R, replies, premise, result 8); CON-0183 and CON-0189; F36; BOOK-OUTLINE; the Ch. 9 index.

## 4. Open items

| Item | Role | Reason | Next action |
|---|---|---|---|
| per-law options (a), (b), (c) of R3 (F36) | Professor; Principal for kernel placement | turn 7 | decide with the boundary questions |
| P-loss against deletion required by law | Professor | open whether law can require deleting identities | when a source on retention is read |
| enabledness reading of Req's inputs | Professor | a rival, open | turn 7 |
| CON-0189, C6 results 7–8, §9.10 changes: corrected, not re-reviewed | reviewers | only a reviewer's verdict validates | later turns that use them |
