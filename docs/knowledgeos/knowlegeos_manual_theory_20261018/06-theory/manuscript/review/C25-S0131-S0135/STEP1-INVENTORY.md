*Window reviewer W (Opus), step 1 blind inventory, verbatim (saved by the Professor at 2026-10-23T17:56:55Z). sha256 of the hand-back text at receipt: `679d4c6baa7e4e3697228ec566b3b56d06159564e9f76cb33e27744d30ab1b59` (UTF-8, the text below this line exactly).*

C25 STEP 1: BLIND INVENTORY

**Disclosure.** My session context included the repo git status and recent commit subjects. One subject names "window C25 draft (S0131-S0135; §8.18, CON-0239-0243)", so I know a draft exists, but I have not seen its content. I also had the project CLAUDE.md files. I read REVIEWER-INSTRUCTIONS.md. I confirmed that all five paths are in CORPUS-ALLOWED.tsv (one match each), then read each source whole. For context I grepped S0128, S0129 and S0130, and read S0130 L1–30 and L56–86. I did not read the migration plan (S0300), the C-2 stage-2 document that S0134 cites, anything under W/06-theory, W/archive or W/tmp, any read log or START-TIMES file, or any blocked file. I ran no git commands on W. Independence here is process independence only.

---

## S0131 — Implementation design (architecture/…IMPLEMENTATION-DESIGN.md, 180 lines)

**Depth: FULL.** It adds a lot of new material relative to S0128–S0130: the second default (RA-2), the byte-copy rule, the order argument, a two-shape reconciliation test, and the `--dir` mutation path.

1. "WHAT was decided ≠ HOW it will be implemented" (L7). This is the design's governing distinction.
2. There is one writer, `saveRecord()`, with three call sites; writes are atomic (tmp+rename) (L22). S0133 L91–94 later narrows this to "one writer *of governance evidence*".
3. No derived state is persisted; fold-derived state is rebuilt at read time (L24). This restates ADR O-3.
4. `seq` is dense and monotonic, so omission can be detected but not prevented (L25). Only 2 of 213 transitions carry a date: the log has no time dimension (L26, L134).
5. RA-2: `session-resolve.php:74` computes the same default path independently, and the decision's follow-up list omits it (L33). I checked this: S0130 follow-up 3 names only `workflow-state.php`.
6. `--dir` can already be overridden, so "only the DEFAULT contradicts B′" and no engine redesign is needed (L38–39).
7. Four boundaries: evidence, execution, ownership, lifecycle. Lifecycle is the discriminator: written once vs rebuilt on demand, "two lifecycles ⇒ two locations" (L45–52). It also restates B′ as "placed correctly for the first time, not exported" (L52).
8. Copy bytes, never re-emit. Re-serialising could change bytes without changing meaning, which destroys hash comparison (L62–66).
9. Ordered sequence COPY → VERIFY → SWITCH → DEMOTE → REMOVE, with "durability before demotion": removal counts as deletion only if no durable copy exists (L70–79).
10. There is an unavoidable divergence window between steps 1 and 3. Quiescing writes cannot be guaranteed, so R-CONFLICT becomes a *required step*, not a contingency (L81–90).
11. Inside the window, neither side is authoritative by default: "no automatic winner" (L98–102). Divergence is recorded in a divergence record and never merged in place (L106–110).
12. Divergence is said to have "exactly two shapes". STRICT EXTENSION is resolved by appending the tail; the source argues this is a union, not "choosing a side". PREFIX DISAGREEMENT must be escalated to PO/ARB (L114–119). Compliance test: the resulting seq set is dense, monotonic, and a superset of both inputs (L121).
13. Switching only one default creates a "hidden second truth" (L130, L157). Historical references get a mapping, not edits, reusing EKS-03 (L132). An import must not be presented as a chronology (L134).
14. Options A–D; D ("relocate now, ledger later") is recommended. A format change (C) would amend the decision by implementation, which the source treats as impermissible (L140–149).
15. New finding: `--dir` is an unconstrained mutation path whose consequence grows after relocation (L160). Three items are returned to PO/ARB (L168–171).

**Lineage.** Produced by `5e1dd9ee`, the ADR's own author, in lane S4b seq 4–6. It binds itself not to approve its own work (L9). D1–D3 are taken as fixed from S0130, which records the human PO/ARB's selections. The code facts are the author's own re-reads, and the counts were "re-counted at START" with no command shown.

**Defects.**
- (a) **A step is missing from the stated procedure.** §3.3 makes reconciliation *required*, but the five-step sequence (L71–75) has no RECONCILE step. Step 5 is declared safe "after step 2" (L79), yet appends made between steps 2 and 3 exist only in runtime. Removing the runtime copy without reconciling would delete them.
- (b) The two parts conflict. VERIFY "stops the migration" on any hash failure (L72), while §3.3 accepts the window. Any append between COPY and VERIFY makes the verification fail. The source does not say whether the migration stops or reconciles.
- (c) **The compliance test does not test what it claims.** It checks *seq sets* only (L121), so a resolution that keeps every seq number but swaps content passes. In the prefix-disagreement case, keeping both entries puts two entries on one seq, which can never be "dense, monotonic" in a single record. The test is unsatisfiable in exactly the case it is meant to govern.
- (d) "Exactly two shapes" (L114) leaves out the mirror case, where the evidence copy is longer. That case is reachable through the `--dir` path the source itself finds at L160.
- (e) Appending the runtime tail is defended as a "union". Under the S0130 wording ("silently selecting one side"), that is the source's own interpretation, not decided text [I].
- (f) "Append-only, immutable once written" (L47) is a *logical* property. Physically, `saveRecord` rewrites the whole JSON file on every append [I].
- (g) "No engine redesign" (L39) is later overtaken by a Single Authority Resolver (S0132, S0133).
- (h) "tokenRefs unaffected (verified)" (L131) shows no evidence.
- (i) The design migrates "each record", i.e. all 17. But S0130 L68 lists the migration subject as only two work items (28+33 and 2+3). The scope widens without comment [I].

---

## S0132 — Status chat (how_far_we_are/202060819_2137_session.md, 706 lines)

**Depth: FULL for L1–339, SKIM for L341–706.** The first part adds the resolver requirement, a seven-step order and the self-reference of migration evidence. The second part is business-value advocacy with no corpus anchors.

1. Six-phase lifecycle; Phase 4 (migration planning) is current (L5–25).
2. AMD1 "P/Q/R invariants added" and AMD2 "Resolver invariant added". These are new relative to S0128–S0130 and are given only as labels (L41–42).
3. The before/after picture: runtime mixes execution state with authority evidence, so when runtime disappears, the lineage disappears (L50–68).
4. **Single Authority Resolver**: every reader and every writer goes through one resolution mechanism, with "no hidden second authority" (L123–157). This turns S0131's RA-2 into a design element.
5. A seven-step order adds Inventory and Create-location before Copy (L163–183).
6. R-CONFLICT is glossed as "compatible ⇒ merge preserving both; same seq, different content ⇒ preserve, record, escalate" (L198–227). "Compatible" is never defined, and S0131's byte-identical prefix condition is lost.
7. **Migration evidence must itself be durable.** Otherwise "migration proves durability but its proof is not durable" (L231–253). This is a self-referential requirement.
8. The C-10 status (D2 NOT ESTABLISHED) is unchanged and kept separate from this work; so is Correction #3 (L259–286).
9. Next actor is Architecture. PO/ARB is needed only on four escalation triggers (L304–310).
10. Business value: audit ("who decided, when"), drift prevention, AI-readable history (L341–706). [I] All of it is assertion.

**Lineage.** No producer is named. The style and the embedded Principal prompt at L341 ("what we get after the implemenation") point to an advisory chat transcript pasted by the Principal [I]. The filename date "202060819" is malformed. Every status it reports is a gloss, with no quotation of any act.

**Defects.**
- (a) L40 marks the Implementation Design "✅ Accepted" with the meaning "How to migrate is being designed". That is self-contradictory, and no acceptance act is cited.
- (b) **Contradiction:** L401–407 claims the organisation "can prove … when it was decided". S0131 L26/L134 and ADR O-7 say only 2/213 transitions are dated and that relocation "neither creates nor cures" the gap.
- (c) "Which alternatives were rejected" is not content of the transition record.
- (d) The progress bars (40%, 100%) have no basis.
- (e) "Durability problem proven" (L35) is overstated relative to the ADR's measured but partial reconstructability (O-9).

---

## S0133 — Migration plan self-review (reviews/…MIGRATION-PLAN-REVIEW.md, 151 lines)

**Depth: FULL.** It introduces the two axes P-1…P-4, OPEN-M1…M4 and the limits of self-review, all new.

1. **What a self-review can and cannot establish.** It can establish mechanical completeness and provenance, which are re-runnable. It cannot establish soundness, correctness or unlisted omissions. Completeness against the author's own checklist is "close to tautological" (L6–13, L38).
2. Authority path sources sit on **two axes**, record location and interpreting mechanism (L23, L126). `session-resolve.php:90` `KOS_MECHANISM_PATH` is the mechanism axis (L51).
3. The resolver has invariants INV-R1…R4, and INV-ORDER is stated as an invariant *of the order* (L26–28).
4. The rule "removal ≠ deletion" puts the precondition inside the phase (L29).
5. Five runtime client scripts are excluded from migration: the boundary is proved by contrast (L72).
6. INFO-1: the obvious grep finds three `file_put_contents` writers. The discriminating test is "references runtime/workflow AND writes", so the claim is rescoped to "one writer of governance evidence" (L91–94).
7. INFO-2: the lane registration names `5e1dd9ee`, but the plan was produced by `1c8b041b`. This attribution discrepancy is left unrepaired (L97).
8. INFO-4: the §1 counts are a dated observation of a live corpus and must not replace the Phase-1 run (L102–103).
9. OPEN-M3: is the mechanism axis inside the decided boundary? "NOT DETERMINED" (L126). OPEN-M2: Phase-1/4 evidence comes before the Phase-2 target (L130). OPEN-M4: the reader set is unbounded, so the resolver invariant is "advisory" for ad-hoc reads; a total guarantee would need a ledger (L131).

**Lineage.** Produced by `1c8b041b`, the plan's author, although the commission routed the review to Governance (L13, L100). The decision constraints (B′, R-CONFLICT, AMD1, AMD2) are cited, not quoted, here.

**Defects.**
- (a) It speaks of what "Governance must not determine" (L126) although it was not written by Governance. S0134 L30 then quotes it as "the Governance review", a misattribution.
- (b) It cites "§0's limitation" (L112), but the document has no §0.
- (c) It missed what S0135 later found: acceptance criterion 1 pins frozen figures. This is direct evidence for its own L11 limitation [I].
- (d) OPEN-M2 is a real conflict with S0132 L244. Migration evidence produced before the durable target exists must sit somewhere non-durable first [I].
- (e) OPEN-M4 describes an invariant that no operation enforces, and therefore has no owner [I].
- (f) "No runtime defaults changed: `.claude/scripts/` clean" (L82) shows a clean tree, not that nothing changed between commits.

---

## S0134 — OPEN-M3 decision prep plus the appended decision (reviews/…OPEN-M3-DECISION-PREP.md, 127 lines)

**Depth: FULL.** It brings an interpretation-authority distinction, a premise correction, and the clearest passage in the window on how an authority's words relate to a gloss.

1. The premise is corrected: the condition is not new. It is C-2 of 2026-08-17 ("interpretation authority is environment-swappable", class Declared). The plan added *precision*, not discovery (L8–17).
2. C-2 already has a remedy route (R-37, ADR-C6). The plan "supplied exactly the measured evidence of insufficiency R-37 requires" (L19–20).
3. The delivered plan already presumes option A, since Phase 5 switches P-4. So the plan and the review disagree (L22–33).
4. **Right bytes plus wrong interpreter equals wrong authority.** A swappable interpreter is a second authority source: the same bytes give a different fold (L39, L87).
5. The text of an adopted invariant fixes its scope: the AMD2 text governs *location*. Reading interpretation into it would be silent expansion (the "Category B rule") (L39, L51).
6. Option B overstates novelty and risks a second owner for one problem. Option C leaves Phase 5 resting on an unadopted assumption (L40–41, L93–94).
7. The recommendation is A as an *explicit scope extension*, with draft wording supplied (L45–51).
8. Appended DECIDED: the invariant is extended to location and interpreter, "verbatim" (L77–85). Rejections of B and C "as decided" (L91–94).
9. D3: "the presumption is now ADOPTED; Phase 5 stands" (L100). D4: **boundary decided ≠ problem remediated**; C-2 stays open until Phase 5 (L109–110). D5: two separate gates; execution is gated on independent review, not on OPEN-M3 (L112–121).

**Lineage.** Prepared and registered by Governance (`b64828fe`). The decision is labelled PO/ARB. The decision text is called "verbatim" and the rationale "as given", with quoted fragments at L87 and L94.

**Defects.**
- (a) **Shared origin.** The "verbatim" decision (L77–85) closely reproduces Governance's own draft wording (L49). L4 says "One letter settles it". If the authority chose a letter, the verbatim paragraph may be Governance's text endorsed, not the authority's own words. The source does not say which.
- (b) The adopted text "does NOT create … a new governance owner" (L85). Yet D4 has this decision supply C-2's remediation boundary while ADR-C6 "remains authoritative" (L107). The two-owner problem is settled by declaration, not by any operation [I].
- (c) "Exactly the measured evidence of insufficiency" (L20) is an overclaim. A line address shows the coupling exists, not that the current handling is insufficient.
- (d) The decision ratifies after the fact an answer the plan had already built in. The "discipline satisfied" note at L102 is self-assessment.
- (e) "Interpreter" is left undefined across versions: a changed `workflow-state.php` default is also a different interpreter [I].
- (f) C-2 is outside my access and is unverified by me.

---

## S0135 — Independent Governance review of the plan (reviews/…MIGRATION-PLAN-GOVERNANCE-REVIEW.md, 114 lines)

**Depth: FULL** (short). It adds the stale-literal finding, a scale of three review kinds, and a disclosure of the reviewer's own error.

1. Independence means "did not author the plan" (L5). Scope: completeness, provenance and conformity only. Safety and soundness need a separate technical review (L7–10, L103, L109–111).
2. Readers are "enumerable in code, unbounded in fact" (L21). There are "three sources on two axes" (L19).
3. Counts re-measured: 18/216/**110**. The drift is attributed to the OPEN-M3 grant written by this reviewer (L41, L89–90).
4. Exact line quotes for `:81`, `:74`, `:90` and `:105` (L43–46). The reviewer's own near-miss is disclosed (L51).
5. **INFO-2:** acceptance criterion 1 pins 18/216/109. Against a live corpus it either fails or compares with a stale manifest, so a record added between planning and execution goes undetected. No re-run requirement was found by grep (L92–93).
6. INFO-3: the plan's §11 is accurate as of `3817eb2b` but superseded by the decision (L95–96).
7. The next technical reviewer must be neither the author nor Governance (L111).

**Lineage.** `b64828fe`, on a PO/ARB act ordering an independent review (L114). **Limit on that independence:** this process drafted the OPEN-M3 extension wording, registered the decision, and created the 110th grant. Its check that the plan conforms to the OPEN-M3 extension (L61) therefore checks conformity to its own recommendation [I].

**Defects.**
- (a) "Three sources" (L19) does not match S0133's P-1…P-4 with P-3a/b (four or five).
- (b) The finding of no re-run requirement rests on a grep miss (L93), hedged but still a search miss.
- (c) "0 candidates exist" for the target (L77) cannot be checked while no path is chosen.

---

## Cross-source points

| Point | Sources | Type |
|---|---|---|
| Migration order: 5 steps (S0131 L71–75), then 7 (S0132 L166–178), then "seven phases" (S0133 L27). None of them shows an explicit RECONCILE step. | S0131/S0132/S0133 | Status change; a gap that persists |
| The fix moves from "switch two defaults atomically, no redesign" to a Single Authority Resolver with INV-R1…R4 | S0131 L39 → S0132 L123, S0133 L26 | Status change |
| Authority axes: one (location, RA-1/RA-2), then two (location plus mechanism, P-4), then extended by decision | S0131 → S0133 → S0134 | Settled by S0134 D1; remediation still pending (D4) |
| Count of path sources: RA-1..5 / P-1..P-4 / "three" | S0131, S0133, S0135 | Contradiction |
| Corpus counts: 16/210/99 (ADR), 17/213/102, 18/216/109, then 18/216/110. Grants grow faster than transitions. | S0128, S0131, S0133, S0135 | Consistent drift. Shows the subject is live. |
| Migration subject: two work items (S0130 L68) vs all records | S0130 vs S0131/S0133/S0135 | Unexplained widening [I] |
| "Can prove when" vs "no time dimension; relocation does not cure it" | S0132 L404 vs S0131 L26/L134 | Contradiction |
| R-CONFLICT: decided text (S0130 L28), S0131's union/escalate test, S0132's "compatible ⇒ merge", S0133's "migration interpretation ≠ invariant" | S0130–S0135 | The interpretations drift. S0133 and S0135 confirm that the plan labels them as guidance. |
| Writer count: "exactly one" narrowed to "one writer of governance evidence" | S0131 L22 → S0133 L94 | Refined |
| Self-review vs independent review: the self-review passed what the independent review found (the stale literal) | S0133 vs S0135 | Evidence for the self-review limit |
| The S0133 quote is attributed to "the Governance review" | S0134 L30 | Misattribution |
| Gates: OPEN-M3 closed; independent completeness review delivered (S0135); technical review still outstanding; execution not authorised | S0134 D5, S0135 §8 | Pending |
| Still open across the window: `--dir` mutation path (S0131 L160), ledger/format (Option C, OPEN-M4), OPEN-M1 `.gitignore`, OPEN-M2 evidence before target, C-2 remediation, INFO-2 lane attribution, `ADR:OQ-2` | all | Pending |
| Who accepted the design (`ae451db9`, "accepted" in S0132/S0133)? No act is in the window. | S0132 L40, S0133 L151 | Lineage gap |

