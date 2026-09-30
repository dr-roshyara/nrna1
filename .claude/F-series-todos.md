# F-Series TODOs (KnowledgeOS, F-lane `docs/knowledgeos/chronological_knowelgeos_ablation_theory/`)

**Kind:** session state — the single F-Series working list. Placement: `php scripts/doc-placement.php --scope=session-state` → `.claude/` (exit 0).

**Status:** current as of 2026-09-25. Human-reviewed list, with the corrections agreed in the session.

**Authority:** none. This file decides nothing.
- **Governing sources:** the Phase-1 Master Protocol `docs/knowledgeos/knowledgeos_theory_chronological_extraction/prompts/knowledge_os_protocoll.md` (H-0 CONFIRMED (a)), under `KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md`. The architecture's L0 approval is open (GI-1).
- **Decisions live in:** `…/knowledgeos_theory_chronological_extraction/governance/L0-DECISION-RECORD-01.md`, and in the F-lane `F-GOVERNANCE-LOG.md` (whose L0 standing is H-15).
- **Session log:** `.claude/sessions/2026-09-25.md`, section "F-Series: H-0, refactor map, v1.3-R design, authority review".

**Standing state:** nothing implemented. v1.2 frozen and not approved; v1.3 and v1.3-R are design only; F3082 is READ-COMPLETE and not extracted. No corpus file may be run (L0-DEC-30: no corpus reading).

---

## DONE

Each item was an evidence or assessment deliverable. None is a decision.

- **DD-1…DD-4 architecture review:** `prompts/F-SERIES-v1.3-DD1-DD4-ARCHITECTURE-REVIEW.md` (`d3629aa5e`, F-LOG-0013).
- **H-0 verification and Master-Protocol refactor map:** `prompts/F-SERIES-v1.3-MASTER-PROTOCOL-REFACTOR-MAP.md` (`fce30354e`). H-0 = CONFIRMED (a); R-A CONDITIONAL; 78 artifacts mapped.
- **v1.3-R design for review:** `prompts/F-SERIES-v1.3-R-DESIGN-FOR-REVIEW.md` (`d41c68439`). Both conformity verdicts CONDITIONAL; implementation BLOCKED.
- **Authority, Ownership & Conformance Review:** `prompts/F-SERIES-v1.3-R-AUTHORITY-OWNERSHIP-CONFORMANCE-REVIEW.md` (`aef07a856`, `a8da402ab`, `73484b055`). It contains:
  - [x] the ten authority/conformance questions (§2);
  - [x] the ownership matrix with **DEFINE / CHANGE / APPROVE / EXECUTE** for every mechanism, with evidence tags, and UNRESOLVED where no source settles it (§5);
  - [x] the three-way constraint classification of all 31 rules (20 mechanical / 8 operational / 3 semantic; §3), withdrawing the mislabelled "implementation constraints";
  - [x] the protocol-inside-protocol audit of `exec_*` constructs (§4): `NO-SUBSTANTIVE-CONTENT` and `RESTATES` fail it;
  - [x] the translation table removed from the F architecture and recorded as a proposed protocol-boundary artifact (§6). Its owner is H-18;
  - [x] invariant INV-IND: common-method components must be disclosed, and agreement ≠ corroboration ≠ validation (§8);
  - [x] the TDI measurement rule: no sensitivity/recall/specificity before a reference set exists; class balance and `false` counts only (§7);
  - [x] invariant INV-COV: coverage is syntactic only (§8);
  - [x] the P3A pairing correction and the TDI-5 correction (§5 rows 18, 25);
  - [x] start conditions before **any** file (§9.1) and F3082 analysed separately (§9.2);
  - [x] the mechanism mapping against MP, across the Map, the Design and the Review;
  - [x] which mechanisms may remain in F-Series: 15 / 31 (§5.5);
  - [x] the final conformance verdict (§11) and "What remains for human decision" (§12).

- **Governance decision dependency and sequencing:** `prompts/F-SERIES-GOVERNANCE-DECISION-DEPENDENCY-AND-SEQUENCING.md`. It gives the dependency graph, the branch matrix, the RCA/1b analysis, evidence gaps E-1…E-5 and the decision agenda (§11). No decision.

- **Human governance decision session package:** `prompts/F-SERIES-HUMAN-GOVERNANCE-DECISION-SESSION-PACKAGE.md` (`1c2b45d5f`, F-LOG-0018). Evidence package complete; no decision recorded.

- **Scientific research architecture optimization (design only, PROPOSED):** `prompts/KNOWLEDGEOS-SCIENTIFIC-RESEARCH-ARCHITECTURE-OPTIMIZATION.md` (`294d22b31`, F-LOG-0019). Every S2 / MP addition in it is a candidate L0 protocol change.

- **Minimum scientific research cycle (design minimization, PROPOSED):** `prompts/KNOWLEDGEOS-MINIMUM-SCIENTIFIC-RESEARCH-CYCLE.md` (`df9a29476`, F-LOG-0020). C-M is designable; it needs a release scope (L0-DEC-27/30); C-H is blocked.

- **C-M design corrections (senior review):** per-world-type H0 / metrics, scoped METHOD_VALIDATED, composite-procedure reading, threshold-registration constraint (`139b0bae8`, F-LOG-0021). Architecture minimization is **frozen**; the next artifact, if commissioned, is the C-M Registration & Falsification Review.

- **C-M Registration & Falsification Review (PROPOSED registration):** `prompts/KNOWLEDGEOS-C-M-REGISTRATION-AND-FALSIFICATION-REVIEW.md` (F-LOG-0022). Pre-freeze needs: human review of R-USE and parameters; release scope; implementation + generator self-tests; verifier class; id namespace.

- **C-M final adversarial review:** `prompts/KNOWLEDGEOS-C-M-FINAL-ADVERSARIAL-REVIEW.md` (`1380be1cc`, F-LOG-0023). Supersedes the registration's generator, cells, criteria and N (§12). C-M = 3 claims + NI check, N = 850. The bottleneck remains an F2 KnowledgeOS hypothesis.

- **F2 acceleration pass (analysis):** `prompts/KNOWLEDGEOS-F2-ACCELERATION-PASS.md` (`34844193b`, F-LOG-0024). H-F2-1 (T-0013 × T-0014 × T-0056, axioms A0–A5) has the shortest F2 path. **Next human decision:** a release to record H-F2-1 as a Phase-2 [E] record and to pre-register test T-A; corpus-read release for T-A/T-B.

- **H-F2-1 formal logic & minimality attack:** `prompts/KNOWLEDGEOS-H-F2-1-FORMAL-LOGIC-MINIMALITY-ATTACK.md` + `analysis/h_f2_1/` (`8a180ffd7`, F-LOG-0025). Classified **F2-FORMAL-CANDIDATE** for revised H-F2-1-R. **Before any release request:** human review; independent re-implementation of the model; pre-registered T-A expected findings (incl. F-A6). Open: H-6, g ≡ s, A6 two-level reading.

- **H-F2-1-R scientific closure pass:** `prompts/KNOWLEDGEOS-H-F2-1-R-SCIENTIFIC-CLOSURE-PASS.md` (F-LOG-0026).
  - Verifier `analysis/h_f2_1_verifier/`: SECONDARY_REVIEW, full agreement.
  - LOGICAL QUALIFIED · COMPUTATIONAL REPRODUCED · EMPIRICAL UNTESTED.
  - **Next:** HD-1 (freeze the T-A pre-registration, folding in the proposed axiom → proposition map), then HD-2 (F0018 re-READ release), HD-3 (OQ-6 scope for the schemas/ES-006), HD-4 (independence class; INDEPENDENT verifier re-run?).
  - **Research todos R-1…R-6** are in the report §6.4. ML is used only in T-B candidate retrieval: BM25 / embeddings / adjudicated-label classifier, plus active learning pre-registered before T-B; recall bound and review cost are reported separately.

- **T-A safety pass:** `prompts/KNOWLEDGEOS-T-A-EXECUTION-READINESS-SAFETY-PASS.md` (F-LOG-0028).
  - **NOT READY.** STOP: axiom scope and kind-assignment basis undefined.
  - Pre-registration r2 (A6 → "replacement UNRESOLVED"; §5.0 precedence; PARTIALLY UNTESTED; versions pinned; §10 checklist).
  - `analysis/t_a/aggregate.py` (18 synthetic tests).
  - **Next human decision:** HD-S (recommendation S-U + K-A + SP-S) → HD-1 on the post-HD-S hash → HD-2/HD-3/HD-4 → T-A (no ML) → aggregate → stop.

- **Operation-typing methodology review:** `prompts/KNOWLEDGEOS-T-A-OPERATION-TYPING-METHODOLOGY-REVIEW.md` (F-LOG-0029).
  - HD-S **partial** (S-U + SP-S stated).
  - **Open:** kind basis K-A vs K-S + O-1, plus the action lexicon D-3.
  - Then: r3 + attack-document note + aggregate.py typing checks → HD-1 → releases → T-A.

- **HD-S closed → T-A pre-registration r3** (F-LOG-0030): sha256 `be16deb7…7133`; 32/32 synthetic tests.
  - **Next human decision: HD-1** (freeze r3).
  - Then HD-2 (F0018), HD-3 (ES-006 + history, OQ-6 scope), HD-4 (independence) → T-A per §10 (no ML).

- **HD-1 DONE (human): r3 frozen** `be16deb7…7133`. Release preparation: `prompts/KNOWLEDGEOS-T-A-RELEASE-PREPARATION.md` (F-LOG-0031).
  - **Next:** HD-2 (M-1 object), HD-3 (OQ-6 scope + 7 ES-006 objects + confirm the history erratum), HD-4 (reader classes).
  - Then T-A.

- **T-A execution gate** (F-LOG-0032): HD-2/3/4 recorded; blind reader packet `analysis/t_a/reader_packet/`.
  - **Next (human / governance):** Research Release Check for T-A → L0 OQ-6 scope (M-4) + release record → commission the INDEPENDENT reader.
  - Then Claude runs T-A per r3 §10 and stops.

- **RRC measurements for T-A** (F-LOG-0033): manifest STALE (F2800), OBS-2 (T-0056 without a relation record), OBS-3 (4 untracked governance prompts).
  - **Next (governance session):** classify OBS-1/OBS-3, decide the manifest action, issue GREEN/YELLOW/RED.
  - **Then (L0):** OQ-6 scope + release.

## CURRENT STATE (2026-09-27, after P1 classification and the norm-force finding; F-LOG-0093…0104) — supersedes ALL tables below

**Phase:** formal **model identification** (no longer reconstruction). The corpus is evidence plus brainstorming material; the researcher's task is to derive a robust theory.
**Architecture (working):** evidence → events → states → normative rules (per rule, with force over time) → observed traces → conformance / explanation → candidate theory. The MT / P0 / P1 / M families are **diagnostic only**.

### Established (with their strength)

| Finding | Strength | Record |
|---|---|---|
| Governance status changes only by an explicit decision (ES-001.2) | SOURCE, text corroborated (same-family) | F-LOG-0093/0099 |
| Promotion = one owned act taken from a *derived* readiness state; each chain step is its own HDE; stages never skipped | SOURCE, text corroborated | F-LOG-0095/0099 |
| Non-collapse: frozen ≠ adopted, ready ≠ promoted, recommendation ≠ issuance ≠ adoption | SOURCE (B witnesses it) | F-LOG-0095/0100 |
| "Frozen" is overloaded (author freeze vs governance freeze) | SOURCE (Case E) | F-LOG-0100 |
| Two-phase acts are kind-dependent (work plans: Authorized → Executing) | SOURCE | F-LOG-0101 |
| Two-layer records: decision text immutable, status annotations mutable (supports H1) | SOURCE | F-LOG-0101 |
| **T1 r1 falsified as a claim about practice by P1 (R-41 / ES-004.3):** promoted without prior qualification; the timeline blind-corroborated | N2; scope = SOURCE-DERIVED MEDIUM ("standards candidates") | F-LOG-0102/0103 |
| R-39 and R-41 share "adopt, then validate"; R-39 records an exception, R-41 none | SOURCE | F-LOG-0103 |
| **Force is rule-granular:** ES-006 PROPOSED in all 7 versions (no ratification found), yet hosted rules carry ARB / PA provenance → P1's explanation = **UNDETERMINED (force ambiguous)** | SOURCE / DERIVED | F-LOG-0104 |
| Readiness is not sufficient (B) | F7 Case C (conditional) | F-LOG-0100 |

### Next (ordered)

| # | Item | Status | Needs |
|---|---|---|---|
| ~~1~~ | ✅ **T1 r2 frozen** (F-LOG-0105; `prompts/KNOWLEDGEOS-T1-R2-PREREGISTRATION.md` + `analysis/theory/t1r2_check.py`); dev cases R-39 / B / P1 are sanity only | ✅ | — |
| ~~1b~~ | ✅ r2 tested on P2 (F-LOG-0106): **survives, weak test** (no deviation observable); norm-text vs norm-as-practised gap found | ✅ | — |
| 1b′ | ✅ bar locate (F-LOG-0107): **citation drift** (a track-local verdict 07-26 → R-39 composite → memory/instructions 08-01 → ES-001.3 08-16; ES-006.1 text never had it) → r3 HYP: Text / Force / Interpretation | ✅ | — |
| ~~1d~~ | ✅ drift confirmed by reading (F-LOG-0108): sharpening; two competing ladders; host legitimacy; memory 'adopted' without a recorded act | ✅ | — |
| ~~1e~~ | ✅ T1 r3 frozen (F-LOG-0109) | ✅ | — |
| ~~1f~~ | ✅ R-100 tested (F-LOG-0110): C3 UNDETERMINABLE; an instrument defect found | ✅ | — |
| ~~1g~~ | ✅ r3.1 frozen (F-LOG-0111); L0-REL-23/23b executed (F-LOG-0112/0113): **R-100 CLOSED, C3 UNDETERMINABLE** | ✅ | — |
| ~~1h~~ | ✅ H1–H5 discrimination (F-LOG-0113); instrument r2 + L216 SILENT (F-LOG-0114); P1-excludes-H1 corrected (norm-reading dependent) | ✅ | — |
| ~~1i~~ | ✅ L0-REL-25 L493 (F-LOG-0115): no elimination; H4 untested; minimal pair → HYP H6 (RULING vs PROMOTION route); H-planes | ✅ | — |
| ~~1i″~~ | ✅ L0-REL-26 L205 (F-LOG-0116): H4 untested; H-PLANES supported; lattice B1 + C5 eliminated | ✅ | — |
| ~~1m~~ | ✅ M0 pre-registered + verified (F-LOG-0117): non-Markov object state (tombstone fix); FREEZE on process coordinate; conjecture G-R/force-insensitive | ✅ | — |
| ~~1o~~ | ✅ L0-REL-27 (F-LOG-0118): register has no exception field, no completeness convention → OPEN-WORLD; G-R vs G-O not discriminated | ✅ | — |
| ~~1n′~~ | ✅ L0-REL-28 CLOSED non-discriminating (F-LOG-0119/0120) | ✅ | — |
| ~~1n~~ | ✅ locate (F-LOG-0120): R-88 L74 top; R-89 (collision risk), R-64 next | ✅ | — |
| ~~1p~~ | ✅ L0-REL-29 R-88 (F-LOG-0121): OUT-OF-SCOPE (subdivision); explicit frame clauses + anti-path-dependence clause found; count-term retrieval false positive | ✅ | — |
| ~~1q~~ | ✅ frame experiment (F-LOG-0122): 5/5 frame structure, no M0 contradiction, M0 under-specified; decision-text immutability ×4, scoped authorization ×5 | ✅ | — |
| ~~1s~~ | ✅ M1 hold-out (F-LOG-0123): P1/P2/P3a/P3b/P5 survive (one regime); Frame⁺(ADOPT) falsified; ALLOCATE, PERMIT-CONSIDERATION missing | ✅ | — |
| ~~1t~~ | ✅ M2 generalization (F-LOG-0124): record / frame structure / no-PREPARED / evidence persistence survive a second regime; ACCEPT frame falsified | ✅ | — |
| ~~1u~~ | ✅ M3 attack (F-LOG-0125): 12 invariants jointly satisfiable; R-79 sequence derived from guards; 12 observation templates | ✅ | — |
| ~~1v~~ | ✅ L0-REL-30 (F-LOG-0126): census 64/71 unchanged; 0/71 changed after first day; strict I-B4 falsified (R-96/98/99 same-day edits) | ✅ | — |
| ~~1v′~~ | ✅ L0-REL-31 (F-LOG-0127): H-status falsified, H-window survives; I-B4′ proposed | ✅ | — |
| ~~1w~~ | ✅ I-A1 on WP-4B (F-LOG-0128): P-a/P-b supported; P-c artefact; decisive R-80 question open | ✅ | — |
| ~~1x~~ | ✅ L0-REL-32 + T-min (F-LOG-0129): I-A1 undetermined; sequence ≠ guard; a/k/σ necessary; o/r/ε/x undetermined; missing variable (procedural conformance) | ✅ | — |
| ~~1y~~ | ✅ ε-pair search (F-LOG-0130): no performed supersession in the register; R-94 declines it (chronology); legality ≠ choice | ✅ | — |
| ~~1y′~~ | ✅ ADR status locate (F-LOG-0131): 1 performed supersession (ADR-MP ⟶ D-12 kept as a pointer) | ✅ | — |
| ~~1y″~~ | ✅ (F-LOG-0132): ADR-MP performed supersession, no ε-pair; legality needs a/k/σ + procedural conformance; o/r/ε/x undetermined | ✅ | — |
| ~~1aa(i)~~ | ✅ route-pair locate (F-LOG-0133): none; route ≡ host family → G-R vs G-O undecidable here; needs a prospective designed observation | ✅ | — |
| ~~1aa(ii)~~ | ✅ T-min v1 (F-LOG-0134): 23/24 consistent; 1 internal coding conflict; event dataset exported | ✅ | — |
| ~~1ab~~ | ✅ out-of-sample (F-LOG-0135): guards not contradicted; V6 falsified (early regime); coverage 29%; evidence bar broader | ✅ | — |
| ~~1ae~~ | ✅ v1.1 held-out (F-LOG-0136): coverage 0.71 (threshold 0.70, SUPPORTED barely); no violations; ε NECESSARY via R-36 | ✅ | — |
| ~~1af~~ | ✅ Gate 1 secondary (F-LOG-0137) · reserve (0138) · ablation (0139): the convergence report | ✅ | — |
| **1ag** | **Gate 1 proper**: tarball `~/F-GATE1-PROPER-BUNDLE-01.tar` (08cf04b2…) → non-Claude coder → score with gate1_agreement.py / reserve_agreement.py | 🔴 next | human commissions |
| ~~1ah~~ | ✅ (F-LOG-0140): A +1, K +1, E +1 cluster; RS none → RS-HYPOTHESIS. Remaining: a 2nd independent decision for e; 2nd witnesses for h, o, c (else drop from the core) | ◐ | L0 |
| ~~1ai~~ | ✅ cross-family (F-LOG-0140): invariants not contradicted; operationalization does not transfer | ✅ | — |
| **1aj** | **Coding manual r3 (genre-aware: reported vs performed acts, report-verb stoplist, V6 genre rule, claims, confirm split) → new cross-family sample** | 🔴 next | L0 |
| ~~1ak (round 1)~~ | ✅ (F-LOG-0142): evidence outside R-36 not found; strict correction; state SUPPORTED (R4) / WEAK (disjoint); target = index on state; operation not demonstrated | ✅ | — |
| ~~1ak-2 (state)~~ | ✅ (F-LOG-0143): state 2 disjoint witnesses; authority, kind, state = the strict empirical core | ✅ | — |
| 1ak-3a/b | evidence witness outside R-36: Execution-Contract pair NOT a witness (F-LOG-0148); unit witness UNRESOLVED, H-X no new cluster, `e` overloaded → H-E2 (F-LOG-0149) | ✅ | L0 |
| 1ak-3c | e-vector re-test: falsified in all variants but strict variant hinges on E01 = 1; surviving G_RAISE = e ≥ θ ∨ authorized exception (F-LOG-0150) | ✅ | L0 |
| 1ak-3d | ES-004.3 decision-time evidence = 1 (inference) → G_RAISE ✗; survivors M_A/M_T/M_AT/M_K (+X), confounded (F-LOG-0151) | ✅ | L0 |
| 1am-2d | confound NOT broken; M_A/M_T/M_K/M_AT (+X) observationally equivalent → RAISE branch STOPPED; designed observations recorded (F-LOG-0152) | ✅ | L0 |
| ~~1al~~ | ✅ (F-LOG-0144): per-operation guard signatures; RAISE {a,e}≡{e,t}; SUPERSEDE {k}≡{r}; history absorbed into status | ✅ | — |
| ~~1am-1~~ | ✅ SUPERSEDE: {route} falsified, {kind} survives (F-LOG-0145) | ✅ | — |
| 1am-2 | RAISE determinant under the subagent loop: M_E ✗ · M_T ✗ (cond.) · M_A, M_AT survive · H-X (exception capability) post hoc — F-LOG-0146 | ✅ | L0 |
| 1am-2c | Actor of E02 (2026-08-15 three-planes "NOT promoted to methodology"): DA/PO ⇒ M_A, M_AT falsified → H-X; author ⇒ no discrimination — result UNK; designed observation recorded (F-LOG-0147) | ✅ | L0 |
| 1am-3 | R-86 vs R-91: conformance STRICT (fragile D1/D9) → conformance WEAK (F-LOG-0153) | ✅ | L0 |
| 1am-3b/d | typed-header grain: witness strict only at coarse grain (F-LOG-0154); no second conformance cluster in Acceptance family → conformance branch STOPPED (F-LOG-0155) | ✅ | L0 |
| 1an | formal minimization r1→r3: guard union {a,c,e,h,k,s,t} (model-relative); o UNTESTABLE; only START{s} generalizes (F-LOG-0156) | ✅ | L0 |
| 1an-r4 | generalization (reviewed): no guard shown to generalize by prediction; START UNCLEAR (s recurrence only); RAISE/ADOPT/SUPERSEDE do not; operation UNDETERMINED (F-LOG-0157) | ✅ | L0 |
| 1an-r5 | START {s}: coding-consistent, not discovery (definitional; baseline capped; knife-edge) → formal-generalization branch STOPPED (F-LOG-0158) | ✅ | L0 |
| 1ao | H-AS: analytic START/s,t, ADOPT/c; synthetic ADOPT/a, AUTH-IMPL/a, OPEN-WORK/k, RAISE/e, ASSIGN-ID/h; unknown RAISE/t, SUPERSEDE/k (F-LOG-0160) | ✅ | L0 |
| 1ag-B2 | fresh Claude coder on the Gate 1 bundle (SECONDARY): bias bidirectional; V1/V4 manual-limited (F-LOG-0159) | ✅ | L0 |
| 1ap | ruling force: issuer ✗, adopter ✗, status UNDETERMINED (F-LOG-0161) | ✅ | L0 |
| 1aq | START premise: 0 independent; 10/11 not acts → state downgraded (F-LOG-0162) | ✅ | L0 |
| 1ar | act-attestation audit: empirical core = {authority}; kind/state not demonstrated on attested acts; H-DEONTIC (F-LOG-0163) | ✅ | L0 |
| r3-run | manual r3: trade-off V1/V4 up, V2/V3 down; shared priors (F-LOG-0164) | ✅ | L0 |
| **DECISIONS** | **(1) schema r2: separate norm statements from act observations · (2) reframe theory target as norm-making · (3) manual r4 (after 1) · (4) commission non-Claude coder** | ⏳ human | L0 |
| **DECISION** | **manual r3 (frozen-protocol change)**: adopt the reviewer's 7 clarifications before a non-Claude coder? | ⏳ human | L0 |
| 1am-3c | WP-4B 16:10 state | ⏸ | L0 |
| 1ac | L0 coding decision: an act that creates and applies its own rule → RULE or CHOICE (AUTHORIZE(4C)@R-72) | ⏸ | L0 |
| 1ad | Gate 1 for v1: non-Claude re-coding of the 15 OOS rows → inter-coder agreement (κ) + re-run of the checks | 🟡 | human commissions |
| 1z | Event-level dataset (event identity, copies collapsed, regime, source family) — prerequisite for any statistics | ⏸ | after several minimal pairs |
| 1r | G-R vs G-O: R-64 (L50) after a count-context locate (matched-term windows only) | ⏸ | L0 |
| 1n | Route≠operation falsifier from the rulings register (a RULING raising standing, or a PROMOTION creating a norm), locate-only first | ⏸ | after 1m |
| 1i′ | Resolve P1 kind (knowledge / work) from evidence, not assumption; it decides H3a/H3b and, with the norm reading, H1 | ⏸ | L0 release |
| 1j | Observability-weighted selection (silence probability per source type); consider git-timestamp ordering as SOURCE-FACT order | ⏸ | after 1i |
| 1k | Transition system M = (S, E, δ, Φ): reachability, path dependence, reversibility/persistence (H-asym), temporal safety — after ≥ 1 non-silent IN-force case | ⏸ | after 1i |
| (old 1f) | L0-REL-22: test r3 on R-100 (register L86; exercises C3 via R-39's eligible bar if in scope) | 🔴 next | human go |
| (old 1e) | T1 r3 draft + freeze: norm = Text · Force · Interpretation · HostLegitimacy; plural norms per kind; conformance evaluated against text AND against interpretation; keep r2 claims C1–C4; dev cases R-39 / B / P1 / P2 = sanity only | 🔴 **next** | "T1 r3: draft" |
| (old 1d) | Confirm the drift by reading (small release: session log 2026-07-26 L175 section · `.claude/MEMORY.md` L389 section · commit `5af8111cf` message), then decide r3 | 🔴 **next** | L0-REL-21 |
| **1c** | **A stronger test of r2's C3**: an unread in-scope promotion whose trace can *positively* show a deviation (e.g. a recorded post-hoc qualification) under an IN-FORCE or AMBIGUOUS rule; plus a locate-only search for where the 'repeated pattern' bar is textually hosted (tests the Interpretation(ρ, t) hypothesis) | 🔴 **next** | human go |
| (old 1) | T1 r2 draft + freeze: N (per rule; Force(rule, t) ≠ Status(document, t)) · O (observed traces) · explanation layer {conforms · exception recorded · force ambiguous · out of scope · version difference · unexplained} · authority as a parameter · FreezeAuthor ≠ FreezeGovernance · provisional adoption with a validation obligation · NOT-RECORDED ≠ FALSE. Built from released text only; frozen **before** P2 is read | 🔴 **next** | human go ("T1 r2: draft") |
| 2 | Test r2 on **P2 = ES-001.3** (adopted 2026-08-16, unread) | ⏸ | L0-REL-19, after item 1 |
| 3 | Minimal transition model + verifier (extend `conformance_check.py` / `trace_check.py`): reachability, ordering, persistence, reversibility, path dependence, event vs state | ⏸ | after item 2 (≥ 3 determined in-scope cases) |
| 4 | Optional: a locate-only search for any ES-006.1 / ES-001…006 ratification in session logs and memory (would settle P1's force ambiguity) | ⏸ optional | L0 release |
| 5 | Post-promotion contradiction and reassessment semantics (B2); whether multi-axis standing is dynamically independent | ⏸ | after items 2–3 |
| 6 | **Gate 1**: non-Claude verification of the Gate 2 / Gate 2.5 bundles (and ideally of the evidence bundles 01/02) | 🟡 open | human commissions externally |
| 7 | ML retrieval benchmark (RB-1) | ⚪ only if a recall gap is measured | — |
| 8 | Consolidation → L0 canonicalization | ⏸ last | all above |

**Do not:** broad corpus reading · new model families · MK construction · an MT winner · ML without a measured gap · treating absence as falsification · S-series work.

## CURRENT STATE (2026-09-26, after the secondary verification; F-LOG-0076) — supersedes all tables below for ordering

Same-family verification is CLOSED (no more Claude verifiers). Gate 2 and Gate 2.5 are REPRODUCED UNDER A DIFFERENT READING (SECONDARY only). No model selected.

| Gate | Item | Status | Needs |
|---|---|---|---|
| 0 | formal architecture and experimental controls (Gate 2, Gate 2.5, bundles, comparator, intake) | ✅ | — |
| **1** | **non-Claude verification of Gate 2 and Gate 2.5** (`~/G2-VERIFIER-BUNDLE-02.tar` `71312955…`, `~/G25-VERIFIER-BUNDLE-02.tar` `008d66de…`; instruction only "Verify the hashes, then follow README.md exactly.") | 🔴 **BLOCKED** (Codex disallowed by the human; no other non-Claude mechanism here) | human commissions externally (GPT / Gemini / DeepSeek / a human) |
| 2 | ✅ CAP-001 §9 read (L0-REL-03, F-LOG-0082): **no model constraint**, EP-13c AMBIGUOUS open, OBS-SF-1 attribution unsupported. Earlier: machinery **execution-ready** (`analysis/evidence_phase/`, F-LOG-0077): schema, manifest (S-1 identity to be designated by L0), 19 EP propositions, derivation engine r2 (conflict-preserving; INCOMPLETE-CANDIDATE only; interpretation_confidence; full basis stamp) + 40 unit + 13 CLI contract tests (F-LOG-0079) | ⏸ ready | Gate 1 + L0 release designating the source file/section |
| 2f | ✅ L0-REL-07 (F-LOG-0088): ES-006.1 → EP-02a/02b AMBIGUOUS (evidence grounding, check timing unstated); EQ-3/4 SILENT. Next: EQ-2 timing (R-37 burden-of-proof pointer; the Plan Concept L36 'NOT ruled') | ⏸ | L0-REL-08 |
| 2e | ✅ L0-REL-06 (F-LOG-0087): Metamodel §6 SILENT by declaration; it names the rule hosts ES-006.1 + the Plan Concept → next EQ-3/4 target | ⏸ | L0-REL-07 |
| 2d | ✅ F-SEARCH-06 locate (F-LOG-0086): smallest EQ-3/4 target = Knowledge Metamodel §6 (L102–105); EPIC-004 dropped (scope risk) | ⏸ | L0-REL-06 |
| 2c | ✅ L0-REL-04 (F-LOG-0084): R-39 pointer sections are SECONDARY; no constraint. **IG: next target EQ-3/EQ-4 (persistence / revocation, 1 bit), then EQ-2/EQ-5 (0.811). EP-13c has 0 discriminative power; MT1-P0 ≡ MT2-P0 on all EPs** | ⏸ | L0: a locate-only search for EQ-3/4 sources |
| 2b | LOCATE-ONLY done (F-LOG-0083): R-39 pointer (10 primary candidates, 2 on the same line as `proposed R-69`) · EPIC-004 heading map | ⏸ | L0 release of the next source |
| 3 | EPIC-004, only if CAP-001 leaves EQs unresolved | ⏸ | Gate 2 + L0 release |
| 3h | (non-blocking) end-to-end contract test: evidence builder → schema → engine (L0-REL-03 `source_slot` mismatch) | ⏸ | — |
| 4 | formal model elimination / refinement (FRN-1 witness review, FRN-5 strict TOCTOU, PERSIST-EB), each pre-registered | ⏸ | Gates 2–3 |
| 5 | ML retrieval benchmark RB-1 (PU, narrow scope) | ⏸ design only | L0 corpus release + freeze |
| 6 | A2e / A5e + remaining historical gaps | ⏸ | L0 releases |
| 7 | independent empirical validation | ⏸ | — |
| 8 | theory consolidation | ⏸ | — |
| 9 | L0 canonicalization | ⏸ | — |

## CURRENT STATE (2026-09-26, after Gate 2; F-LOG-0065/0066) — supersedes the gate table below for status

- Gate 2 **FROZEN**: REPRODUCED UNDER A DIFFERENT READING (Claude ×2); no model selected.
- Findings: the old promotion predicate is insufficient for R-39 with the bar intact · D3 splits into history vs state · a **TOCTOU** gap between authorization and promotion · no Pareto-dominant model · persistence semantics OPEN.

| # | Item | Status | Needs |
|---|---|---|---|
| 1 | **non-Claude verification of Gate 2 and Gate 2.5** (separately): `~/G2-VERIFIER-BUNDLE-02.tar` (`71312955…`) · `~/G25-VERIFIER-BUNDLE-02.tar` (`008d66de…`), each with `~/VERIFIER-COMMISSIONING-PROMPT.txt` only; intake per `analysis/verification/INTAKE-PROCEDURE.md` (F-LOG-0070) | 🔴 BLOCKER | human commissions (GPT / Gemini / human) |
| 2 | **Gate 2.5 temporal safety**: addendum r1 + SPEC-G25-r1 (`5423ebb9…`) frozen; reference SEALED `results_ref.json` (`1771c79a…`, not interpreted); bundle `~/G25-VERIFIER-BUNDLE-02.tar` (`008d66de…`) (F-LOG-0068) | 🔴 next | human commissions the non-Claude run → seal → mechanical comparison vs reference and r1-7 predictions |
| 2a | ✅ SECONDARY (Claude-vs-Claude) comparison done: G2 and G25 both REPRODUCED UNDER A DIFFERENT READING; L0 package `analysis/verification/L0-RESEARCH-DECISION-PACKAGE-G2-G25-SECONDARY.md` (F-LOG-0075) | ✅ | L0 decisions §9 |
| 2b | results-blind comparator frozen (`analysis/verification/`, F-LOG-0069); on each independent seal: verify hashes → commit adapter (relocation only) → `compare_mech.py` → outcome under r2-7 → only then inspect truth values | 🟡 ready | independent results |
| 3 | (template: `prompts/KNOWLEDGEOS-TEMPORAL-EVIDENCE-MATRIX-TEMPLATE.md`, EQ-1…13) persistence / revalidation / retroactivity evidence: targeted corpus search (CAP-001 §9 → EPIC-004 → authorization scope, adoption persistence, revocation, revalidation, retroactivity, grandfathering, pending/adopted items, later evidence, rule/bar changes); each read records source id · hash · anchor · exact wording · date · source claim · our assessment separately | ⏸ | after 1+2 reproduced; L0 release |
| 3b | STOP → L0 research-decision package (reproduced formal facts · open alternatives · corpus evidence · gaps · per-model implications · next search), incl. FRN-1…FRN-3 | ⏸ | after 3 |
| 4 | A2e, A5e | ⏸ | L0 releases |
| 5 | ML retrieval benchmark RB-1 (design in `prompts/KNOWLEDGEOS-RETRIEVAL-BENCHMARK-DESIGN.md`) | ⏸ design only | pre-registration freeze + L0 corpus release |
| 6 | independent empirical validation → consolidation → L0 canonicalization | ⏸ | all above |

## CURRENT STATE (2026-09-26, after T-A closure and the R-39 SELF read; F-LOG-0049…0054) — supersedes the tables below for ordering

Status:
- T-A **CLOSED INCONCLUSIVE** (three readers, 0 counterexamples).
- R-2 **REPRODUCED**.
- R-39 (SELF only): explicit one-item governance exception below a multi-context bar; the rule is stated intact. So A6 is **not applicable**, and a **promotion-semantics gap** is recorded.
- A blind Claude reader is impossible here (the commit history leaks findings).

| Gate | Item | Status | Needs |
|---|---|---|---|
| **1** | **independent R-39 re-read**: give a fresh non-Claude reader **only** `~/R39-READER-BUNDLE-01.tar` (`2bcc5b81…`) plus the instruction "follow README.md". ⚠ Do **not** use prompts that name candidate conclusions ("bar bypass", "model gap", "A6 not applicable", …): they prime the reader | 🔴 next | human commissions |
| 1b | seal `answers.json` (hash first) → compare with the pre-registered classes (F-LOG-0053) → STOP | ⏸ | Gate 1 |
| **2** | **formal model comparison** M0 (current Promote) · M1 (exception path) · M2 (eligibility / authorization / promotion-event separation). **Circularity guard:** fix the models plus their predicted R-39 behaviour before the comparison; SELF knows R-39 (disclosed). The exception path's preconditions come **from the source** (evidence present but below the bar · explicit authority record · stated reason · deferred validation), never a bare `ExceptionAuthorized` disjunct. Record as an **S2 Experiment record** (RC-R39-001); no new "Research Case" concept | ⏸ | Gate 1 |
| 3 | finite-state attack of M0/M1/M2: D1…D6, NV, minimal sets, countermodels; **R-2-style independent verification** of any new model (spec only, non-Claude, pre-registered comparison) | ⏸ | Gate 2 |
| 4 | remaining historical items: CAP-001 §9 (n≥2 provenance; A6 pending-item semantics) · EPIC-004 only if necessary · A2e · A5e · each needs its own L0 release | ⏸ | Gate 3 |
| 5 | ML retrieval benchmark: seed items R-39 (found by 2/3 T-A readers, missed by the author) plus the reader-disagreement hard set; lexical → BM25 → structural → embeddings → hybrid → optional LLM rerank; Recall@k, MRR, false-negative rate against the **union of sealed reader findings**; ML never decides truth | ⏸ | Gate 4 |
| 6 | consolidation → human review → L0 canonicalization | ⏸ | all above |

**Process rule (F-LOG-0054):** neutral commit subjects for experiment results.

## CURRENT STATE (2026-09-26, after the blind secondary comparison, F-LOG-0044/0045) — supersedes the WP table below for status

Status: H-F2-1-R = formally specified candidate · T-A SELF = INCONCLUSIVE · T-A BLIND SECONDARY = INCONCLUSIVE (6/8 axiom verdicts identical; 0 counterexamples in either) · **independent replication OPEN** · secondary review ≠ independent.

| Order | Item | Status | Needs |
|---|---|---|---|
| **A1** | **independent T-A reader** (human or other model family; bundle `bbdb8e7e…`; handoff `prompts/KNOWLEDGEOS-T-A-INDEPENDENT-READER-HANDOFF.md`; no R-39/A2e/A5e/A6 hint) | 🔴 blocking | human commissions |
| A2–A3 | hash first → file → seal → **three-way comparison** (SELF / SECONDARY / INDEPENDENT) → frozen aggregation per ledger → T-A closure → STOP | ⏸ | A1 |
| **B (parallel)** | **R-2 independent formal verification**: bundle `05d0ad88…` (SPEC.md + README only); **comparison rule pre-registered** in `prompts/KNOWLEDGEOS-R-2-VERIFIER-HANDOFF-AND-COMPARISON-RULE.md` | 🟡 ready | human commissions |
| **C1** | **R-39** (highest): pre-status · act · post-status · evidence · **bar changed vs bar bypassed vs evidence unmentioned vs composite vs ambiguous** | ⏸ after T-A | new L0 release |
| C2 | CAP-001 §9: A6 (pending/existing/qualified/rejected items under a rule change) + OBS-SF-1 (where n≥2 is stated: corroborated / misattributed / later-derived / ambiguous) | ⏸ | new L0 release |
| C3 | A2e: P-3 demotion-by-decision (evidential / governance component / both / neither) | ⏸ | new L0 release |
| C4 | A5e: P-7 → P-10 (WORK changes e directly, or produces an artifact processed as EVID) | ⏸ | new L0 release |
| D | counterexample branch: reconstruct → ≥ 2 alternatives → finite-state test + NV → no pre-chosen replacement. **A bar *bypass* is not automatically an A6 failure** (A6 constrains bar changes; a bypass may attack D1/D3 through a different path) | ⏸ | C |
| E | survival branch: pre-register **T-B** (lexical → regex → BM25 → embeddings → optional LLM candidates → complete human reading). **Evaluation labels = the union of the sealed T-A ledgers** (recall of passages any reader found; the reader disagreements are a hard-case set). Record model/version, queries, chunking, thresholds, depth | ⏸ | T-A closure |
| F | later: H-F2-1b / Q-GS / Q-D4 (M-2/M-3) · governance leftovers (F2800, batch 3, T-0056 relations, FP-GOV-01…04) · generalization · DDD · full theory · canonicalization | ⏸ | — |

**ML note (optimization):** the blind reader found a passage the author missed (R-39). This is exactly the false-negative risk that retrieval must measure. In T-B, report **recall against the reader-union**, and treat reader-disagreement passages as the adversarial test set; never train on unadjudicated model output.

## CURRENT WORK PACKAGES (2026-09-26, after the T-A SELF run; supersede the item list above for ordering)

Review levels: **SELF** (Claude, author, not blind) · **BLIND SECONDARY** (Claude subagent; tests protocol reproducibility, NOT independent) · **INDEPENDENT** (person or other model family, commissioned by L0; required).

| WP | Contents | Status |
|---|---|---|
| **A — Close T-A** | blind secondary ledger (running) → **independent reader (L0 commissions; bundle `bbdb8e7e…`)** → sealed ledgers → disagreement table (never averaged) → frozen aggregation per ledger → one r3 outcome | 🔴 independent reader not yet commissioned |
| **B — T-A evidence gaps** | smallest-evidence rule, no broad reread: **A6 + OBS-SF-1 together** via the named CAP-001 §9 candidate (**highest value: D1 and D3 hold only if A6 holds**) · A5e via the smallest source on P-7 → P-10 · each needs a new L0 release | 🟡 after WP A |
| **C — Independent formal verification (R-2)** | fresh verifier from `analysis/h_f2_1_verifier/SPEC.md` alone, by a person or another model family. **Independent of T-A; can run now** (commission it separately from the T-A reading) | 🟡 open, parallelizable |
| **D — Branch research** | counterexample → reconstruct the mechanism → ≥ 2 alternatives → finite-state attack · survives → T-B pre-registration (lexical → BM25 → embeddings → LLM candidates, measured against the T-A ledgers) | ⏸ depends on A |
| **E — Later theory** | H-F2-1b / Q-GS / Q-D4 (need M-2/M-3) · generalization · DDD · ML · full theory · canonicalization | ⏸ later |

Status line: **H-F2-1-R = formally specified candidate; first source test (SELF) = INCONCLUSIVE; 0 direct counterexamples; no independent replication yet.** "Supported" axioms are source fidelity to F0018, not corroboration. The reconstruction and methodology areas remain **unrated** (not audited in this review).

## HUMAN DECISIONS PENDING (not tasks for Claude — Claude may only prepare evidence)

- **H-1:** F-Series role (R-A / R-B / R-C).
- **H-14a:** who holds DEFINE / CHANGE / APPROVE / EXECUTE for the execution-assurance machinery (13 rows have an UNRESOLVED definer). Distinguish implementation authority from methodology/protocol authority.
- **H-14b = GIA-9:** ADOPT / ADAPT / REJECT of the research-produced `evidence/` binding.
- **H-15:** whether the F-LOG rulings are transcribed into the L0 record.
- **H-16:** coverage granularity and the operational items (9, 10, 12, 16, 27).
- **H-17:** a governance-approved, human-labelled TDI reference sample.
- **H-18:** owner and change mechanism of the Phase-1 → Phase-2 translation contract.
- **Carried:** H-2 · H-3 (F3082–F3108 identity) · H-4 (look-ahead) · H-5…H-8 (DD-1…DD-4) · H-10 (lane/zone) · H-11 (population/order) · H-12 (the F3082 read standing; R14) · H-13 (duplicates) · HDR-1 · HDR-6.
- **Existing L0 items the F work depends on** (they belong in the governance session and the L0 record, not in the F-LOG):
  - GI-1 (architecture approval);
  - GI-3 (§5A m1/m2);
  - RC-H-05 (RCA adoption);
  - **1b acceptance**, and whether it must precede new reads;
  - RC-H-01 / 02 / 06;
  - GIA-10;
  - SQ-1 / SQ-2, C-5…C-7.

## CORRECTIONS AGREED TO THE ORIGINAL LIST

1. **F0041** is not blocked by a "HOLD". It is blocked by **L0-DEC-30** (no corpus reading) and **SAFE-RESEARCH-EXCEPTION-01** (chronological advancement to F0041+ is barred until C-7; out-of-sequence safe reads are not prohibited by it, L0-DEC-09a).
2. **The Master Protocol is not frozen.** The architecture is. MP changes only by an L0 act (L0-DEC-22 precedent).
3. **No new readiness gate.** Reuse the existing **Research Release Check** (L0-DEC-27; GREEN / YELLOW / RED, L0 releases).
4. **Implementation design branches on H-14a.** If the machinery is governance-owned (the RCA's *proposed* increments 1b/2/3), the implementation design is governance work, not F-Series work.

## ORDER (agreed)

```text
Authority review ✅
  → H-1 + H-14a   (with GI-1, GIA-9, RC-H-05 in the governance session)
  → branch on H-14a: machinery owned by governance | by F-Series | split
  → remaining H-items recorded as explicit human decisions, with consequences
  → implementation design, by the owning party
      · no F3082 execution
      · no v1.3-R implementation before it
      · no MP change outside L0
  → existing Research Release Check (L0-DEC-27) — covers readiness:
      · ownership resolved
      · normative / non-normative boundaries explicit
      · semantic rules outside F-Series
      · invariants testable
      · audit independence defined
      · TDI reference-set prerequisite
      · start conditions
      · blockers closed or accepted
  → execution
```

## OPEN FOR CLAUDE (only if asked)

- Nothing is outstanding. The evidence package is complete for the human review.
- The lane's own records are current: `F-GOVERNANCE-LOG.md` F-LOG-0014…0017 and the `F-SESSION-LOG.md` catch-up segment (`74b0c83d0`).
