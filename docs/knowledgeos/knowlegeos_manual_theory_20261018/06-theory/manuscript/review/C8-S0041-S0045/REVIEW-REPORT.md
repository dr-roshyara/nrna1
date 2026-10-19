*Saved verbatim 2026-10-19 from the reviewer's step-2 hand-back (window C8; one step-2 message).*

TRANSITION REVIEW, WINDOW C8 (S0041–S0045 + S0129, S2834): STEPS 2–3

The step-1 inventory was re-read first. Independence here is process independence only: I am a separate session, not a separate person. Time for step 2 was about 25 minutes. I wrote no files.

## 1. Assessment

- **Reliable within scope:** The four new sections quote their sources faithfully, and the quote check passed. The lineage is mostly right. The source descriptions of S0044 and S0045 are reliable within their dates and scope.
- **Interpretations:** Three of them fail.
  - §5.5 C1 uses "decider" in a sense that the book's own A1 and §1.14.3 forbid.
  - §9.3 C2 and C4 map onto §9.1 with type errors.
  - §8.3 C1 groups the counterfactual claim "prevented incorrect decisions" with the claims that cases can show.
- **Largest risk:** §5.5 re-derives Ch. 1 §1.12–§1.14.3 without citing it. That creates a second home for the role laws, with an inconsistent role set: three roles here against five there.

## 2. Findings (ranked)

| # | Sev | Type | Claim / topic | Source line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | high | defect | §5.5 C1 / CON-0109 "executor, decider, authorizer: three roles" | S0044 L42 | S0044 names **two** roles: "who-executed from who-authorized". The "decider" was "the AI at the user's direction". Under A1 (§1.6) and §1.14.3, the AI cannot decide in the act sense: only a human act confers standing. So "decide" is a homonym: (a) choosing or computing, versus (b) performing the authorizing act. Sense (a) is part of execution or recommendation; sense (b) is the authorizer. Also, the "recorder" role, which §5.5 C2 is all about, is missing from the three. | The three-role set is neither minimal nor consistent with Ch. 1 | Restate as executor ≠ authorizer (n=1, observed), with recorder as a third, already law-bound role (§1.14.3). Mark "decider" with its sense or drop it. |
| 2 | high | defect | CON-0109 consequence: "merging them makes the record unable to show A1 holding" | S0044 L42 | Counterexample from the case itself. The single field held the **user**, i.e. the authorizer. A record that names the authorizer is the one that *can* show A1. What it hides is who **executed**, which is a provenance failure (CON-0016's provenance half), not an A1 failure. | The consequence is mis-aimed | Re-aim: the merge loses execution provenance. A1 is threatened only if the field is read as authorizer when it holds an executor. |
| 3 | high | omission / redundancy | §5.5 C1–C2 duplicate Ch. 1 | Ch. 1 §1.12, §1.13.1–2, §1.14.3 | Five pieces already exist in Ch. 1, uncited: <br>• §1.12's "residual trust assumption": "the record was written by the deciding person and not by an assistant on their behalf". RD-6 is a direct instance of it. <br>• §1.13.1's grant points at an external human act. This is C2. <br>• §1.13.2 pt 4: the holder and "how the act arrived" are mixed in one free-text field. This is the same conflation as RD-6. <br>• §1.14.3's laws recorder ≠ decider and producer ≠ verifier. <br>• S0014's "real as PROCESS, unevidenced as PEOPLE". This is C2's limit. <br>§5.5 cites only §1.2 (CON-0016), and by analogy. | Two homes for one law. RD-6 is counted as new when it is the first observed instance of an assumption already stated. | Rewrite §5.5 C1–C2 as **instances** of §1.12 and §1.14.3. Keep C3 and C5 and the RD-6 datum as new. Link CON-0109 to §1.14.3 rather than to CON-0016. |
| 4 | high | defect | §5.5 C2 Ded "a record can attest authority only if what it points at is produced by someone other than the recorder" | S0129 L149 | Proof check: <br>(i) "a self-reference carries no information about an outside act" is true by definition (tautological). <br>(ii) The conclusion needs a hidden premise: the recorder holds no standing. Without it, a counterexample exists: a human PO/ARB who writes the record of their own decision attests authority. <br>(iii) "Producer cannot approve own ADR" is a different law: producer ≠ verifier, which is about independence of judgment, not self-reference. Two laws are merged. | It is a sketch, not a deduction | State the premise ("the recorder is a non-human process", per §1.13.1 and S0129 ground 1). Cite §1.14.3. Keep the two laws apart. |
| 5 | high | omission | Invariant owner of "the record never manufactures authority" | S0129 L147–149; Ch. 1 §1.13.2 pt 1 | The workflow engine refuses a grant only when the `humanActRef` is **absent**. Ch. 1's census checked presence only. Nothing mechanical detects a reference that points at the recorder's own output. The invariant was held in S0129 by the process's self-restraint, not by an operation. S0129's "the precondition exists to prevent exactly that" overclaims. | Invariant without an enforcing owner (CON-0102: stated ≠ enforced) | Add to §5.5: the owner is the recorder's discipline; the mechanism guarantees presence, not externality. |
| 6 | high | defect | §9.3 C2: "preventive mechanisms are such [must-never] conditions at work" | S0045 L64–69; §9.1 C1–C3 | Step check: <br>(1) §9.1's must-never items are **properties** (P, A2, I). The S0045 mechanisms are **members of M**, specifically guards. A refusing mechanism is not a condition, and seeing refusals does not show that P holds. <br>(2) Guards place the platform in §9.1's **guard reading**, where C3's step 2 (monotone Req) fails, so §9.1's existence result does not apply there. <br>(3) Req is **reachability**. "Never exercised" is a fact about runs, so S0045 cannot place KnowledgeOS on either side of Req. <br>(4) Level shift: S0045's refusals act on engineering work items, not on knowledge-state transitions. | The "same form" claim is an analogy at best | Rewrite as an analogy that states (2)–(4). Withdraw "has the form of §9.1's distinction". |
| 7 | high | defect | §9.3 C4: S2834's "necessity and sufficiency … independent claims" = "§9.1's P/Req split" | S2834 L1431–1437 | Wrong correspondence. Necessity of a basis corresponds to **Minimal** (A2 and its repair), and sufficiency to **Req**. P (preservation) corresponds to neither. | This mis-describes CON-0100, a kernel definition | Correct to N ↔ Minimal and S ↔ Req. Note that P has no counterpart in S2834. |
| 8 | high | defect | CON-0110 "generative half never run" | S0045 L69 vs S0041 L23; §4.1 C7 | S0045 is Run 1 (08-03, OE-KOS-1/2 only). By S0041's later count, OE-KOS-3 is a **formal back-edge at the instrumentation level**: platform improvement from recorded evidence. That is generative, once. In addition, S0045's own 4th mechanism (placement derived, harvest asked, triage) is not refuse, block or constrain, yet the source and the book still say "preventive only". | "Never" and "only" overstate. The claim is true only at canon level, as of 08-03. | Scope it to Run 1, 08-03, canon level. Note the instrumentation-level n=1 and the source's internal tension. |
| 9 | high | defect | §8.3 C1 / CON-0112: "constrained · prevented: shown by single cases" | S0045 L22 | "Prevented **incorrect** decisions" is counterfactual: the refused work would have been wrong. Its correctness criterion (missing authorization, prohibition by accepted architecture) is the governance's own rule, so it is circular as evidence of benefit. Only "refused / blocked / constrained" is shown by cases. | The five-way split misgroups one part | Regroup: refused or blocked (cases) · prevented-incorrect, better, decreasing (counterfactual or comparative) · loop (not observed). |
| 10 | high | defect | §4.3 C1 / CON-0113 "a term enters the language only when used" | S0044 L26–31 | Distortion. S0044 already lists the HYPOTHESIZED terms *in the table* (L30). It admits unexercised vocabulary as a grade (L29) and an EMERGING term with no record (L31). The rule governs **grade promotion**, not admission. "Used" (in speech or a CLI) ≠ "performed" (as behaviour): IGNORED is used but not performed. Trivial case: an empty language satisfies "only when used". | The admission rule is stated wrongly | Restate: terms are graded by exercise, and promotion requires a recorded performance. Qualify "used". |
| 11 | high | omission (earlier section) | CON-0107 (§4.2): "the developer decides, always"; "a decision is a human act" | S0044 L42 (RD-6) | The first real decision in the cycle was made by an AI at the user's direction. It is the first test of §4.2's stage rule, and it is mixed: the authorizer was human, but the executor and chooser was the AI. Not linked. | §4.2's validated description is untested against its only run | Note it as a finding on §4.2. Link to #1 and #3. |
| 12 | normal | omission | S0129's history invariants | S0129 L45, L102, L187, L194–197 | Missing from the book: <br>• R-CONFLICT (preserve sequence, provenance, reconstruction; never silently pick a side, delete or rewrite); <br>• "History ≠ Reconstruction Guess"; <br>• "An aggregate cannot depend on accidental reconstruction"; <br>• Execution Context ≠ Governance Evidence Context; <br>• dated evidence (de998173: 28 grants and 33 transitions outside git while the narrative was committed). The last corroborates Ch. 1 §1.13.2 pt 5. | Material for the history and lifecycle primitives | Add to Ch. 1 §1.13.2 pt 5 / history, as the same line as AMS and V3 (see lineage). |
| 13 | normal | omission | S2834 distinctions | S2834 L864–878, L2051–2071 | Missing from the book: Unknown ≠ Uncertain, and E_t / A_t (attributed epistemic state) / K_t with factive Knows(a,p,c,t) separate. A_t is the attribution layer that the actor-provenance topic needs. | A fork is missing | Record it as a candidate attribute split under F13. |
| 14 | normal | omission | RD-5: time-to-decision computable from existing timestamps | S0044 L41, L52 | This is a derived attribute (§3.2: reported, never stored). It is a direct minimality instance. Absent. | Lost instance | Add it to §4.3, linked to §3.2. |
| 15 | normal | omission | RD-7's count skew (decided=2 for one recommendation) | S0044 L43 | This is CON-0070's counting **unit** failing in practice. | Lost instance | One line in §4.3 or §5.5 C5. |
| 16 | normal | defect | §5.5 C3: "the weaker reading cannot violate A1" | S0129 L34 | The logic is true, but a registrar that never registers anything also satisfies it (the trivial case). The rule needs its must-do half: a decision that occurred must be registered or returned for confirmation. S0129 did return it (L72–73), and "a blank is not a decision" (L160) is the counterpart. | The rule as stated is trivially satisfiable | Add the obligation half. |
| 17 | normal | defect | §9.3 C5: S0042 "product-specific" vs the kernel level; owner vs S0037 | S0042 L7–8 | Homonym. The placement "domain: knowledgeos" classifies the platform's own *documentation*. The "owner" is the owner of the doc root's layout, not the owner of KnowledgeOS (S0037). Different objects. | Overreach | Withdraw it as F14/§6.4 evidence, or mark it a weak lexical signal. |
| 18 | normal | defect | §8.3 C5: "falsifiers fixed in advance" | S0041 L45–54 | §8.3 C4 itself shows S0041 was edited after its date. "In advance" is therefore not established, and the signals have no thresholds. | Weakens the CON-0070 link | Mark "fixed in advance" unverified. |
| 19 | normal | defect | §8.3 C6: "each rung presupposes the one below" | S0043 L78–90 | As **inclusion** it is valid: automated ⇒ repeatable. As temporal precedence ("you do not skip") it is advice, and there is a counterexample: a directly built CI metric. | Overstates | State it as inclusion. |
| 20 | normal | defect | §4.3 C4: RD-4 "is the type/instance split of §6.3 (CON-0086)" | S0044 L40 | CON-0086 is INVALID and concerns contexts whose instances are bounded contexts. Recommendation definition/instance is generic type/instance. Step 8 says "comparison only", but the text says "is". | Cites an invalid concept as identity | Change "is" to "resembles", or drop it. |

## 3. Concept verdicts

| ID | Verdict | Reason · scope | Kernel/app · minimal · stable |
|---|---|---|---|
| CON-0109 | **INVALID as stated** | "Decider" is a homonym under A1 and §1.14.3 (#1), and the consequence is mis-aimed (#2). The valid core is executor ≠ authorizer, observed n=1 (S0044 RD-6). | Kernel candidate (an attribute of the act record, F13). Not minimal as three roles. Stable: yes. |
| CON-0110 | **INVALID as stated** | "Never" and "only" are defeated by OE-KOS-3 and by S0045's own 4th mechanism (#8), and the §9.1 mapping is a type error (#6). Restated "no canon-level generative run as of S0045 Run 1 (08-03)": VALIDATED as a source description. | Evidence about the platform, not a kernel concept. |
| CON-0111 | **VALIDATED** (restricted) | Capability ≠ primitive holds as a non-identity, and is definitional. Two cautions: <br>• S2834's example is asserted, not shown. Its derivation uses Dependency and Lifecycle, which are absent from S2834's own 7-item foundation list (L2093–2101): a source defect. <br>• The P/Req mapping must be corrected (#7). | A boundary principle (meta). Minimal: yes. Stable: yes. |
| CON-0112 | **INVALID as stated** | "Prevented" is misgrouped (#9). Restated split (cases / counterfactual or comparative / not observed; usage ≠ outcome): VALIDATED, scope S0045 Run 1. | Method, not kernel. |
| CON-0113 | **INVALID as stated** | This is a distortion (#10): the source grades vocabulary and does not exclude it. Restated "grade promotion requires recorded performance": VALIDATED as a source description, one instrument. | Application or method. Stable: yes. |

## 4. Coverage

- **Inventory vs book:**
  - **COVERED:** S0041 items 2, 5, 12 · S0043 item 9 (ladder, §8.3), with 1–3 in §3.9/T1 · S0044 RD-1, 3, 4, 6, 7 and the impact measure · S0045 items 1, 2, 4, 5, 7, 9, 11 · S0129 grounds 1–2, the role chain, the weaker reading · S2834 stage separation and capability ≠ kernel.
  - **DISTORTED:** CON-0113 (#10) and S0045's "preventive only" (#8).
  - **MISSING:** #12–15; S0045's self-measurement limit ("Run 1 measured only KnowledgeOS governing itself", L87: evaluator = evaluated); S2834's two status ladders, which disagree (L1249–1261 vs L1447–1465).
  - **Not checked:** S0041's four orders → §3.8, PROVENANCE-as-hypothesis and FREEZE/RETIRE (grep hits in Ch. 0/1/3/6, content not verified).
  - **JUSTIFIABLY EXCLUDED:** S0042 apart from one fact; S0043's rejected five-layer model; S0044's "no ML" constraint.
- **Lineage check:**
  - I agree on S0041/S0043/S0045 = one line, S2834 = one file, S0042 separate.
  - S0044: I accept "one line" for its *interpretations*, but RD-6 and RD-7 are first-hand JSONL records, so the data points are primary.
  - S0129 is "partly independent" of S0044, but it is the **same line as Ch. 1's AMS/V3 sources**: humanActRef, G-2, R-34, the governance engine. For §5.5 C2 it is a repeat of Ch. 1's support, not new support.
  - S2834 is an AI-to-AI dialogue built on a different programme (Parts LXXIX…, factivity experiment). Its own second turn rates its "can" claims "ahead of the evidence" (L1918–1948, L2219). §5.5 C4 uses its separations as if established; they are proposals.
- **§12a selection:**
  - The literal phrase "actor provenance" occurs in **neither** added source. `grep -ci "actor provenance"` gives S0129: 0 and S2834: 0, and S2834 contains no "actor" at all. So the logged search was broader than the record states. The 26-hit list and the query are not in STEP8-AND-SCAN, so "earliest and latest relevant" cannot be reproduced.
  - S2834 contributes only stage separation (one line on "who authorized", L1871). It fits §5.5 C4's story better than the search topic.
  - The earlier and more direct source, AMS (08-16, docs/knowledge_tranfer, "two actors, eight spellings"), is already in Ch. 1. It lies outside docs/knowledgeos, so the search root decides what counts as "earliest".
- **New fork:** the attribution layer of an act (executor · authorizer · recorder · attributed state A_t). This is an F13 sub-fork. It is not in the theory map.
- **Source defects (listed separately):**
  - S2834 L2154–2160 vs L2093–2101 (dependency and lifecycle are missing from its foundations).
  - S0045 L69 vs L67 (the "preventive only" label vs the routine-decision mechanism).
  - S0041 is dated 08-03 but counts OE-KOS-4. I did not check this myself.

## 5. Next steps (dependency order)

1. Rewrite §5.5 C1–C2 as instances of Ch. 1 §1.12/§1.14.3 (#1–4). Done when CON-0109 is restated as two roles plus recorder, linked to §1.14.3, and C2's premise is explicit.
2. Add the invariant-owner line for humanActRef externality (#5). Done when the owner (process discipline) and the mechanism's limit (presence only) are stated.
3. Restate CON-0110, CON-0112 and CON-0113 (#8–10). Done when each carries its date or run scope and the corrected grouping or grade rule.
4. Correct §9.3 C2 and C4 against §9.1 (#6–7). Done when the analogy names the guard reading, reachability vs exercise, and N↔Minimal, S↔Req.
5. Findings on earlier sections, to apply at settlement:
   - §4.2/CON-0107 vs RD-6 (#11);
   - Ch. 1 §1.13.2 pt 5 gains S0129's dated durability evidence (#12);
   - F13 gains the attribution sub-fork (#13).
6. Record the §12a query string and hit list. Done when the selection can be re-run.

No question for the Principal is needed.

## 6. Limits

- I did not read: AMS, V3, S0014, S0027, S0037, S0040 (only the book's citations of them), the 26-hit search log, Ch. 1 beyond §1.2, §1.6, §1.12–1.14.3 and §1.17.1, or 03.09/06.03/06.04 beyond grep hits.
- I did not run the quote-check script. I did not search the corpus myself, because of the file rules. I did not verify OE-KOS-3/4 dates.
- The coverage grep (e.g. "R-CONFLICT", "Uncertain(p)", "A_t" with 0 hits) is a signal, not proof of absence.
- Running measure for this review: 20 findings · 0 critical · 9 high · 5 material omissions (#3, #5, #11, #12, #13) · defects found later in passed reviews: none detected (CON-0107's untested status, #11, is an omission against a description verdict, not a reversal).

## Appendix (lower priority)

- §5.5 C4's "the three are where 'Zero detects; Lord acts' falls" depends on §5.4, which is PROVISIONAL. Mark it unverified.
- "Attest" in §5.5 C2 collides with the ATTESTED lifecycle state (S0045 U-5). Qualify it.
- S0129 A1.4: the PO/ARB says "refusal is evidence the separation model works", and the source itself flags the interested party. If §5.5 uses it, it counts as self-confirming evidence.
- S0041/S0043/S0045 counters (deny 19 / ask 22 / advisory 16) are asserted counts from one snapshot. §3.9 already scopes this; keep it so.
