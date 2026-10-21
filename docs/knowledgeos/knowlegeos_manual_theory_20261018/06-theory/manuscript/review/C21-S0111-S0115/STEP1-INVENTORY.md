*Window reviewer W (Opus), step 1 blind inventory, returned as hand-back text, verbatim (saved by the Professor at 2026-10-21T05:39:16Z). sha256 of the hand-back text at receipt: `89994c85295a667f55dd6cc305ddfeadaa6a52f0d95fd310afa07e9ddb043315` (UTF-8, the text below this line exactly).*

C21 STEP 1: BLIND INVENTORY

Mode: transition review, step 1 only. I read REVIEWER-INSTRUCTIONS.md and each of the five sources whole, in one read each. All five paths appear in CORPUS-ALLOWED.tsv, one line each, all marked "match": lines 1485–1489, S0111–S0115. I read nothing under W/06-theory/, no git log or diff of W, and no read log. For the repetition check I only grepped a few key terms in S0102 and S0108–S0110 (the commands are in this session). No file was edited.
Independence: this is process independence (a separate session that has not seen the Professor's work), not personal independence.

**Skimmed-source safeguard (Rulebook v1.3 §3):** no source has yet been read at depth "skim", so there is nothing to read blind. My own depth judgements are below.

| Source | Depth deserved | Reason (one line) |
|---|---|---|
| S0111 | full | It separates existence from realization, makes a presupposition argument from D1 and a case-split robustness argument. Neither is in S0102 or S0108–S0110 in this form. |
| S0112 | full | New: D1's binding to a session cannot be attested, so identity binding falls outside the receipt's authority. Also new: the "non-constitutive specification" device. |
| S0113 | full | New: enforcement decides the category by consequence; "Missing = 100 %" (the trivial-state argument); conditions split by detectability; the compensating control. |
| S0114 | full | The densest theory of the window: a historical claim never becomes false; DISPUTED is orthogonal; MISSING splits into M1–M5; the three-way cut STALE/SUPERSEDED/INVALIDATED. |
| S0115 | full (lighter) | Mostly applies S0114. It adds a four-way conjunction gate, the zero-enforcement result, a dispute effect coupled to its denial-of-service gate, and moves the floor from WARN to INFO. |

---

### S0111 — C-10 existence decision-prep (Governance recommends YES)
1. **Existence ≠ realization.** The recommendation is "YES, realization NONE, outer boundary OPEN". Implementation incompleteness must not decide existence (L44, L52).
2. **Presupposition argument:** D1 fixes the claim boundary of C-10's artifact, and that "presupposes a subject", so NO contradicts D1 (L46, L62). [I] This is weak: a specification can describe a hypothetical artifact. S0112 L12–14 and L65–71 later treat such specifications as explicitly non-constitutive, which undercuts this argument.
3. **Robustness by case split:** existence survives both answers to Q6 (applicability inside C-10, or external) (L54). [I] This is valid only if branch 2 ("receipt custodian") is non-empty. That depends on the receipt being C-10's, which is D3's open question, so the reasoning is partly circular.
4. **Five-activity existence test:** one activity occurs and is owned elsewhere (BC-6/BC-7); four do not occur at all (L13–21).
5. **Maturity class "EVIDENCED BUT NOT REALIZED"** separates three levels: the need is evidenced, the semantics are decided, the activities are realized (L38).
6. A **distinct reason to change** (the distribution mechanism versus BC-1's lifecycle clock) is used as the DDD existence signal (L35, L49).
7. **I-K1 invariant:** the session must hold a valid receipt before a governed act. Three load-bearing terms are undefined: valid, applicable, required by the act (L31).
8. **OQ-J:** a receipt can carry 2 of the 5 claims (delivery, possession), never applicability, execution or compliance (L32).
9. **Counter-case at full strength:** a capability none of whose activities has ever occurred is "a plausible hypothesis" (L58). CONTESTED is rejected because the disagreement is about scope, not existence (L60).
10. **Failure as evidence:** EKS-01 is "evidence of its absence" yet supports the need (L21). The decision rests on the "shape of the gap", not on observed behaviour (L68).
11. An act that names no option produces no verdict, so Governance does not record one (L6–7).
12. [I] **Change over time:** the recommendation was YES, but S0112 L12 reports D2 = NOT YET ESTABLISHED, so the recommendation was not adopted. The theory should record the rejected argument and why.

### S0112 — D3 authoritative state and lifecycle (recommends C, SPLIT)
1. **D1's subject binding cannot be attested.** Identity is self-declared (INV-ATTR-1/2), so identity binding cannot be authoritative state in C-10 or anywhere else in the estate (L18–26).
2. [I] **Internal tension:** L24 says the receipt "still authoritatively carries delivery and possession". But possession by a specified session needs exactly the binding that L20 denies. S0114 M4 later acknowledges this; here it is unresolved.
3. **Constitutive versus boundary-specifying decisions:** adopting state would satisfy "limb ①" of the two-limb existence test and so decide existence "by the back door" (L12–14, L65–71).
4. **Option A** assigns state to a vacuum, since every owner is "nobody". This uses the rule "resolve ownership before protecting an invariant" (L32).
5. **Option B overreaches** on two items: identity binding is impossible, and holding dispute adjudication would prejudge bounded-context status (L33).
6. **Option C** matches OQ-J's 2 of 5 claims and D1's reference-only applicability (L34).
7. The **capability's scope** (applicability + receipt) and the **receipt's authority** are different questions (L36).
8. **Would own:** receipt identity; the lifecycle issued → valid → superseded/invalidated; invalidation triggered by supersession; completeness (L44–49).
9. [I] **Defects in item 8:**
   - "Invalidation triggered when the version is superseded" (L48) merges supersession with invalidation. S0114 later separates them.
   - "Completeness" means a receipt exists for each version an act *requires* (L49). That needs an applicability judgement, which L55 excludes. This is a contradiction.
10. **Would not own:** applicability, transport, execution, identity, dispute, execution authority, compliance (L53–61).
11. **Three prohibitions:** receipt ≠ application, ≠ understanding, ≠ compliance (L63).

### S0113 — D4 missing/disputed behaviour (Governance input)
1. **Enforcement decides the category by consequence:** HALT or BLOCK at START makes C-10 control-plane (L13–18).
2. **Missing is 100 % of cases.** Zero receipts exist, so any blocking response halts the whole estate (L38–42). [I] This is a trivial-state test, correctly applied.
3. **Detectability:** of five conditions, only Missing is detectable even in principle; four have neither a detectable state nor an owner (L24–34). A response cannot be assigned to a condition whose state nothing owns (L34).
4. Even the Missing check cannot bind a receipt to the session that presents it (INV-ATTR-1) (L44).
5. Giving C-10 gate authority would resolve the C-14 adjacency, which is contested (L20).
6. **Tiers do not exist for knowledge packages,** only for policies and gates (L53).
7. **The CAP-09 precedent is weaker than claimed:** it is read-only and "deliberately closed" (L57–61).
8. **E (session responsible) is the falsified status quo** (L63–71). [I] All the evidence comes from one work item (n = 1), so "empirically falsified" overstates it.
9. **F (compensating control):** the recorded human START act already is one; seq 25 and seq 27 are instances (L73–77).
10. **Preference:** D as the non-constitutive target, F for the transition, WARN + RECORD as the floor; decide only Missing and leave four conditions OPEN; never enable enforcement in the same act that defines it (L81–91).
11. [I] **Change over time:** L28 says Missing is detectable "in principle yes". S0114 L28 and S0115 L33 say absence in an unrealized store is not evidence. That reverses the status of "Missing" detection.

### S0114 — D4.2 condition semantics (PROPOSED)
1. **A receipt's claim is a historical fact and never becomes false.** STALE, SUPERSEDED and INVALIDATED change what the receipt is *sufficient for*. Calling it "became invalid" is a category error (L15–16). [I] This is foundational, a defeasible split between truth and reliance.
2. **DISPUTED is an orthogonal flag, not a lifecycle state.** The D3.5 chain is recorded as defective but not corrected (L18–19).
3. **MISSING splits into five non-equivalent cases (L23–36):**
   - M1 never issued: C-10's;
   - M2 expected but absent: needs applicability;
   - M3 exists, not retrievable: infrastructure;
   - M4 exists, cannot be bound: NOT ATTESTED;
   - M5 knowledge itself absent: BC-1's.
4. **Absence in an unrealized store is not evidence of absence** (L28, citing C-1).
5. **M4 must be classified NOT ATTESTED,** never MISSING and never PASS (L33–34).
6. **M3 must never be reported as M1,** because that launders an infrastructure failure into a governance fact (L29).
7. **STALE is relational** (receipt ↔ this act) and version-based only. A newer version does not automatically stale a receipt; otherwise every publication would stale every receipt (L40–51).
8. **SUPERSEDED:** BC-1 declares it and C-10 reacts. Open: transitivity, and retraction without replacement (L55–62).
9. **The three-way cut (L64–69):**
   - SUPERSEDED is an objective fact about the version, BC-1's;
   - STALE is a relational fact about sufficiency, unowned;
   - INVALIDATED is an authority act on the receipt, OPEN.
10. **INVALIDATED** is an authority act on the receipt only. "Reversible?" is mis-framed; the real question is whether reliance may be restored (L73–81).
11. **Invalidation is purpose-scoped,** from the ca6039a8 instance: evidence is kept and assurance is refused (L83–85). n = 1.
12. **DISPUTED:**
    - only a D1 claim can be disputed;
    - it is cheap to enter and expensive to exit, which makes it a denial-of-service surface;
    - NOT ATTESTED must never become PROVEN (L89–98);
    - invalidated and disputed run in opposite epistemic directions (L100).
13. **Only one of five conditions has a trigger owner** (SUPERSEDED, BC-1), and its mechanism CAP-03 is not built (L130).
14. **Producer disclosure:** the author may not later verify these definitions (L7–9).
15. [I] **Homonym:** "SUPERSEDED" is a fact about the version (L67) and also a receipt lifecycle state (L61, S0112 L47).
16. [I] **Weak analogy:** "invalidation applies to the receipt only" (L81), but the only instance cited, ca6039a8, is a review artifact, not a receipt.

### S0115 — D4.3 response mapping (PROPOSED)
1. **Every row gets "NO ENFORCEMENT RESPONSE YET".** No row satisfies all four §10 prerequisites (L13–25).
2. **The counts:** semantically defined 9/9, authoritative trigger 1/9, identifiable evidence source 2/9, currently detectable 0/9 (L18–23).
   - [I] **Possible source defect:** the "2/9" is not traceable to the table. Most rows name an evidence item, and which two rows qualify is not stated.
3. **M1 is itself an inference from absence,** so §7 forbids enforcing on it (L33).
4. **M2 and STALE without applicability** must be UNRESOLVED / NOT ATTESTED, never silently MISSING or STALE (L34, L38).
   - [I] **Homonym:** "NOT ATTESTED" now covers unbindable identity (M4) and unresolved applicability (M2, STALE). These are two different meanings.
5. **M5 and M3 may lie outside C-10's mapping altogether.** Gating on M5 would make C-10 gate on another context's state, crossing D3.6 (L35, L37).
6. **SUPERSEDED has three replacement cases:** available and authoritative; unavailable (no remediation path); applicability unresolved, which collapses into the STALE gap (L42–48).
7. **INVALIDATED has three slots:** the use prevented, the evidence remaining, and the replacement. The worked instance is ca6039a8 again, the same instance as in S0114, so it is not independent (L50–56).
8. **Dispute effect:** use is suspended for the disputed claim only (L71). It cannot be adopted without a gate on who may raise a dispute, because of the denial-of-service problem (L76). A dispute cannot make an unenforceable condition enforceable (L78).
9. **The floor moves to INFO + RECORD,** because WARN on a universal M1 is "noise, not notification" (L84). [I] This changes S0113's WARN floor.
10. **The category flag is not reached:** C-10 gates nothing and remains advisory (L88–92).
11. [I] **Trivial-solution case:** the proposed model maps every condition to no response, so it satisfies every must-never constraint and specifies no must-do. That is exactly the "does nothing" trivial model. The theory needs to say whether this is a stable answer or a transitional one.

### Cross-source table [I]
| Topic | S0111 | S0112 | S0113 | S0114 | S0115 |
|---|---|---|---|---|---|
| Existence status | YES recommended | D2 = NOT YET ESTABLISHED (taken as given) | same | same | same |
| Identity binding | not raised | unattestable, outside C-10 | blocks even the Missing check | M4 = NOT ATTESTED | M4 is doubly barred |
| Missing detectable? | — | — | in principle yes | not evidence while the store is unrealized | 0/9; M1 is an inference from absence |
| Invalidation | — | triggered by supersession | owner nobody | authority act, separate from supersession | authority OPEN |
| Floor | — | — | WARN + RECORD | — | INFO + RECORD proposed |

**Lineage and order [I]:**
- All five sources come from one author (Governance b64828fe), on the same day, so they count as one line of support.
- S0112–S0115 cite D2, its registration §6, D4.1 and D4.2 as already decided. The D2 decision itself is S0120 and D4.1 is not in this window, so the S-numbering does not follow the order of the acts.
- S0114 and S0115 each disclose that their producer is conflicted.

Step 1 is finished. I am stopping here and waiting for the comparison material.
