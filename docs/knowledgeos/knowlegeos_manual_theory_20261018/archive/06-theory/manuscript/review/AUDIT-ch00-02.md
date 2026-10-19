# Independent audit — Chapters 0–2, pilot reviews 01–02, idea register

| Field | Value |
|---|---|
| Date | 2026-10-18 |
| Auditor role | independent reviewer (mathematics, logic, software architecture); read-only except this file |
| Read | CH-00, CH-01, CH-02, REVIEW-pilot-01, REVIEW-pilot-02, IDEA-REGISTER.tsv; all 21 listed background sources at the cited lines; `Verdict.php`, `Assessment.php`, `AssessesIdentifierIntegrityTest.php`, `design-rules.json`, `design-check.sh`, `db-safety-check.sh`, `config/app.php`, `phpunit.xml`, `.github/workflows/*`, `docs/knowledge/schema/*.yaml`; counts only (no content shown) over `.claude/runtime/workflow/*.json` |
| Not read | anything else under `docs/knowledgeos/`; `.env*`; `.gitignore` (not on the access list, so register row P2-F3-09 is unchecked) |
| Severity | **HIGH** wrong claim or wrong logic · **MEDIUM** overclaim, mislabel, missing correction · **LOW** wording or reference precision |
| Line refs | manuscript lines are file line numbers (CH-00 = `Ch0 Lnn`, and so on) |

---

## 1. Are the corrections really in the chapters?

### 1.1 Confirmed present

| Review item | Where it is | Verdict |
|---|---|---|
| R01-A ① authored-by; EP01's producer ≠ reviewer violation | Ch1 §1.2 L66 | present |
| R01-A ② n ≥ 2 threshold | Ch1 §1.8 L213 | present |
| R01-A ③ only realized invariant in unowned code | Ch1 §1.9 L265 | present |
| R01-A ③ vs ② the collision claim corrected | Ch1 §1.9 L263 | present |
| R01-A ④ rule definition owned apart from rule evaluation | Ch2 §2.4 L181 | present |
| R01-B precedence ≠ priority | Ch2 §2.8.4a L327–331 | present |
| R01-B "predicted" withdrawn | Ch2 §2.4 L179 | present |
| R01-B authority = act × scope | Ch1 §1.6 L164 (box) | present, but its logic is flawed (see L-06) |
| R01-B regression caveat, later resolved | Ch1 §1.2 L64 | present (but see H-01) |
| R01-B empty-set containment edge case | Ch2 §2.8.4 L317 | present, but **wrongly formalized** (L-12) |
| R01-B kept-open items | Ch0 §0.8 L227–231, Ch1 §1.1 L42 | present (C-07 says the configuration one is now out of date) |
| R01 §0.6 status → standing | Ch0 L169 (box) | present |
| R01 §0.2 generative tiers | Ch0 L67 (box) | present |
| R02 scope ↔ ownership | Ch2 §2.8.6 L350 | present |
| R02 subject list of rules | Ch2 §2.0 L39 | present |
| R02 §2.8.4 universal-dimension error | Ch2 L288 (box), §2.8.6 L366–378 | present |
| R02 §2.3(b) MAY | Ch2 L150–154 (box) | present, with leftover traces (C-05) |
| R02 renumbering 2.8.5 → 2.8.8 | Ch2 L430 | present (C-06) |
| R02 "standing obligation" word clash | Ch2 §2.0 L37 | present |
| R02 challenges: adding a dimension, adding a value | Ch2 L380–384, L421 | present (logic issues: L-15, L-16) |
| R02 independent checks (vocabularies, 144 grants, design-rules, gate, fallback) | Ch1 §1.13.2, Ch2 §2.8.6–2.8.7, §2.9 | present; I re-checked the numbers: 31/8/12/3/9 types; 144 records with `registeredBy` = governance and `humanActRef`; AUTHORIZED 142 · REVOKED 1 · CONSUMED 1; 18 work items; 9/9 rules carry `allowlist_patterns`; `exit_on_fail: false`; `config/app.php` L53 |
| R02 counts (70 ideas; classification 18/5/19/14/3/5/6; status 53/10/3/1/1/1/1) | register | match the register exactly |

### 1.2 Missing or only partly made

| ID | Location | Sev. | Evidence | Fix |
|---|---|---|---|---|
| C-01 | REVIEW-02 L106 vs chapters | MEDIUM | REVIEW-02 lists "single canonical home is machine-enforced (②)" under "Omissions found and fixed". No chapter contains it. Register P2-L-03 says "register only". | Either add it (Ch1 §1.10 identity, or Ch2 §2.4 next to DUPLICATES) or re-label it in REVIEW-02 as "registered, not integrated". |
| C-02 | Ch2 §2.8.1 L243, §2.8.2 L256–264; Ch0 §0.3 L80 | MEDIUM | The canon scope / grant scope split (§2.8.4a) is not applied in the text that comes before it. S-2 is still called "authority scope". §2.8.2 says "⊆ authority scope" and "the actor's authority scope". Ch0 §0.3 still says "Authority scope" with no forward note. | Rename S-2 to grant scope in §2.8.1 and §2.8.2. Add a forward note in Ch0 §0.3 that the GKC dimension is **canon scope**. |
| C-03 | Ch0 §0.2 L53, §0.7 L194–200, §0.8 L230 | MEDIUM | REVIEW-01 says the feedback theory was weakened ("the loop has closed zero times"). Chapter 0 was not revised: §0.2 still says "built, one completed cycle", and §0.8 says "the runtime has completed one cycle". Only Ch1 §1.8 L215 has the weakening. | Add a revision box in Ch0 §0.7 and §0.8 pointing to Ch1 §1.8. Reconcile "one cycle" with "zero traversals" (H-07). |
| C-04 | Ch2 §2.8.2 L260 | LOW | "Grant scope is prose (③)" was meant to come from AMS L100–113 (register P2-L-01). The text cites only SC L149. AMS's 20/20 scope discipline is not mentioned. | Cite AMS L102–113 next to SC L149. |
| C-05 | Ch2 §2.2 L87, L102; §2.3 table and refined definition L148; §2.9 L484 | LOW | After the revision that "permission belongs to exceptions", MAY still appears as a rule effect: in the formal effect set, in the refined conflict definition, and as the polarity "permit". | Mark MAY in §2.2 and §2.3 as superseded by the box. In §2.9, put "permit" on exceptions, not on rule polarity. |
| C-06 | Ch2 L286–433; L310, L441 | LOW | After renumbering there is no §2.8.5: the order is 2.8.4, 2.8.4a, 2.8.6, 2.8.7, 2.8.8. The change log (L553–554) still says "§2.8.5" with no note. "§2.3a" is cited twice, but no heading of that name exists (it is item "(a)" of §2.3). | Renumber 2.8.4a → 2.8.5 or add a note. Label §2.3(a) as "§2.3(a)". |
| C-07 | Ch1 §1.1 L42 | MEDIUM | The kept-open question "does *knowledge does not execute* survive configuration-as-rule?" now has concrete test cases in Ch2: `design-rules.json` thresholds and allow-lists are read as rules (§2.9), and `config/app.php`'s fallback changes behaviour (§2.8.7). Ch1 §1.1 still reads as if no case had been found. | Add a forward note in §1.1 and decide or re-pose the question using these two cases. |

---

## 2. Reference check (84 reference groups sampled, every section)

### 2.1 Graded sample

S = SUPPORTED · P = PARTIAL · U = UNSUPPORTED · W = WRONG-LINE · M = MISLABELLED

| # | Ms. location | Claim (short) | Ref | Grade |
|---|---|---|---|---|
| 1 | Ch0 §0.1 | KnowledgeOS proposed as "AI-native Knowledge OS"; hypothesis to be falsified | Charter L4 | S |
| 2 | Ch0 §0.1 | "existence proof, not validation"; one data point | Charter L25 | S |
| 3 | Ch0 §0.1 | phenomena: rationale least captured, docs decay in months, validity invisible | Synth L19–22; GKC L56–66 | P/W (R-02) |
| 4 | Ch0 §0.1 | four executors, one mission | RQ-002 Charter L52–62 | P (R-03) |
| 5 | Ch0 §0.2 | placeholder, "not a decision" | Charter L5 | S |
| 6 | Ch0 §0.2 | Evidence-Governed Engineering Knowledge OS | DevConst L22 | S |
| 7 | Ch0 §0.2 | runtime "built, one completed cycle" | DG01 | U (R-01) |
| 8 | Ch0 §0.2 | "KnowledgeOS is the T1 platform" | v3 L26 | S |
| 9 | Ch0 §0.2 | "AI is a capability … not the system"; "never true" | DevConst L24, L40 | S |
| 10 | Ch0 §0.3 | four dimensions and their values; claim = content + coordinate + provenance + status + scent | GKC L13–24, L36–39, L19 | S |
| 11 | Ch0 §0.3 | rationale gap = Decision × Tacit | Meta-Model L22 | S |
| 12 | Ch0 §0.3 | tacit cannot carry status | Meta-Model L65 | S (the source already calls this an "asymmetry": L-02) |
| 13 | Ch0 §0.3 | off-list values "Runtime", "composite" | GKC L103 | S (incomplete: "Canonical-with-verdict-history" and "Canonical-Stable" are also off-list) |
| 14 | Ch0 §0.4 | two moves plus consumption feedback; lifecycle is a cycle | GKC L78, L74 | S |
| 15 | Ch0 §0.4 | the platform adopted append-only and link-supersession first | GKC L80, L103 | P (L103 names invariants 1/2/7/9; "append-only" is not stated) |
| 16 | Ch0 §0.4 | "exactly two regimes" vs four values | Synth L40; Meta-Model L56 | S |
| 17 | Ch0 §0.5 | invariants I-1…I-11 | GKC L56–66 | S (R-20) |
| 18 | Ch0 §0.5 | I-5 supported by the failure of SSOT | Raw L13 (F3.4) | S |
| 19 | Ch0 §0.5 | I-8 counterexample absorbed by re-description | Synth L15 | S |
| 20 | Ch0 §0.5 | I-10 "added at 17:00 on 07-11" | none | U (R-19) |
| 21 | Ch0 §0.6 | decisions belong to humans | DevConst L14–18 | S |
| 22 | Ch0 §0.6 | stages cannot be skipped | DevConst L55–56 | S (heading "no state may be skipped" is at L52) |
| 23 | Ch0 §0.6 | "Evidence not found" respectable; "no new capability" a success | DevConst L101, L61 | S |
| 24 | Ch0 §0.7 | need → working context → evaluation; unsatisfiable needs = gaps; I-8 by construction | PKSM L35, L47, L50, L124 | S |
| 25 | Ch0 §0.7 | "feedback theory of knowledge growth" (Ded/Hyp) | PKSM L128 | M (R-12) |
| 26 | Ch0 §0.7 | 20–30 task validation horizon | PKSM L129 | S |
| 27 | Ch0 §0.8 | parsimony of growth | DevConst L65–79 | P (R-18) |
| 28 | Ch0 §0.8 | never a copy; retrieval demoted; scenario | PKSM L17, L36, L31 | S |
| 29 | Ch1 §1.1 | I-11(RO) "knowledge does not execute" | EP01 L34–36 | S (cited-only, correctly marked) |
| 30 | Ch1 §1.1 | ontology vs normative semantics; normative-semantics field list | EP01 L50–55 | P (R-10) |
| 31 | Ch1 §1.1 | two schema files with two version fields | EP01 L92–94 | S |
| 32 | Ch1 §1.2 | provenance ⟂ standing; D-D | EP01 L246–262, L275 | S |
| 33 | Ch1 §1.2 | N-1 authored_by; N-2 no creation→authority edge | EP01 L166–167 | S (that producer ≠ reviewer "cannot be checked" is the manuscript's inference) |
| 34 | Ch1 §1.2 | EP01's own producer ≠ reviewer violation | EP01 L291 | S |
| 35 | Ch1 §1.3 | five grades; only OBSERVED and ADOPTED bind | EP01 L12 | S |
| 36 | Ch1 §1.4 | 6 transitions vs 1 of 38 closed | EP01 L222–234 | S |
| 37 | Ch1 §1.5 | knowledge ≠ artifact; four recorded instances plus OQ-1 | GA L147–153 | S |
| 38 | Ch1 §1.5 | "the same knowledge" carried in YAML, MD, PHP, JSONL, shell | GA L157–169 | P (H-06: GA puts *capabilities* in PHP and *adapters* in shell) |
| 39 | Ch1 §1.6 | ≠-family; "strongest convergence" | GA L424, L288–296 | P (R-08) |
| 40 | Ch1 §1.7 | read-access gap; "narrow, never widen" | GA L257–263 | S |
| 41 | Ch1 §1.8 | five tiers; generative relation; AIP-14 | GA L67–80, L88–93 | S (cited-only) |
| 42 | Ch1 §1.8 | subject error | GA L84, L110 | P (heading at L63) |
| 43 | Ch1 §1.8 | LA-4 n ≥ 2 | GA L401 | S |
| 44 | Ch1 §1.8 | 0 traversals | GA L376–378 | S |
| 45 | Ch1 §1.8 | three duplicate ontologies; ARB amendment | GA L438–455, L492–496 | S |
| 46 | Ch1 §1.9 | five-stage chain; 1 of 20 contracts complete | TR L58–72 | S |
| 47 | Ch1 §1.9 | "TR's file path for the tests is wrong" | TR L28, L127 | U (R-05) |
| 48 | Ch1 §1.9 | GA's "HIGH-severity collision" | GA L281 | W (R-09) |
| 49 | Ch1 §1.9 | Decision B ruled; the generalization "ruled" | DR-AB L51; L124–133 | S; M (R-06) |
| 50 | Ch1 §1.9 | "the ladder, as adopted" | DR-AB L161–181 | P (R-07) |
| 51 | Ch1 §1.10 | AP-1 collision; AP not a governed register | TR L78–87 | S |
| 52 | Ch1 §1.11 | Decision A; method/binding/evidence; 45 → one decomposition | DR-AB L32, L43, L76–92 | S |
| 53 | Ch1 §1.12 | "A report of an act is not the act" | DR-AB L9 | S |
| 54 | Ch1 §1.13 | authority definition; pointer, not content; three properties | AMS L38–51, L214–238 | S |
| 55 | Ch1 §1.13.3 | AM-1…AM-5; "not every narrower grant is a delegation" | AMS L284–288 | S (but see R-11) |
| 56 | Ch2 §2.0 | Track B, governs nothing; "true statements about a model nobody…" | RS L13–22, L391–395 | S |
| 57 | Ch2 §2.0 | RM-1 definition; exception is constitutive; 3 of 8 | RS L74, L78–87 | S |
| 58 | Ch2 §2.0 | rule/decision/recommendation table | RS L65–70 | P (R-15) |
| 59 | Ch2 §2.1 | prose rule inert; "genuine Core Domain" | RM L122–124, L59–63 | S |
| 60 | Ch2 §2.1 | engine is a threshold matcher, none of the six fields | GA L240–251 | S |
| 61 | Ch2 §2.2 | seven-part rule; effect set | RM L96–104, L131 | S |
| 62 | Ch2 §2.2 | eight dimensions | RS L349–359 | S |
| 63 | Ch2 §2.3 | conflict definition; OAuth2 example; decidable and explanatory | RM L149–168 | S |
| 64 | Ch2 §2.3 | SHOULD vs MUST_NOT asymmetry is policy | RM L137 | S |
| 65 | Ch2 §2.4 | nine-value taxonomy incl. UNKNOWN; burden of proof | RM L178–191, L207–209 | S |
| 66 | Ch2 §2.4 | analyser may evaluate, never define | RM L83–86 | S |
| 67 | Ch2 §2.5 | priority rejected; resolution factors; UNRESOLVED | RM L215–231 | S |
| 68 | Ch2 §2.6 | O(n²); partition first | RM L259–267 | S |
| 69 | Ch2 §2.7 | candidate vs proven; no confidence on proofs | RM L296–310 | S |
| 70 | Ch2 §2.8.1 | S-1/S-2/S-3; S-1 vs S-3 conflation | SC L97–116 | S |
| 71 | Ch2 §2.8.2 | containment chain | SC L135–149, L208–210 | P (R-14) |
| 72 | Ch2 §2.8.3 | opposite readings of silence; SC-2; unknown ≠ ∅; candidate invariant | SC L165–174, L217–228, L249–271 | S |
| 73 | Ch2 §2.8.4a | "First match wins" | SC L52 | S |
| 74 | Ch2 §2.8.6 | admission test; SC-1 ruled; loophole and its closure; residual | SD L18–23, L263–269, L324–373 | S |
| 75 | Ch2 §2.8.6 | ruled table "applies to" column | SD L156–160 | P (R-16) |
| 76 | Ch2 §2.8.6 | adding a dimension is cheap | SD L210–215 | S (challenged; L-15) |
| 77 | Ch2 §2.8.6 | 3/12/31/8; no environment | SD L297–307 | S (also re-checked in the YAML files) |
| 78 | Ch2 §2.8.7 | local/testing declared; production only as fallback; no deploy | EV L28–56 | S (re-checked: `config/app.php` L53; `phpunit.xml` L66, L70; 6 workflows, 1 sets testing) |
| 79 | Ch2 §2.8.7 | gate permits only on "testing" | EV L84, L101; script L26–49 | P (R-17) |
| 80 | Ch2 §2.8.7 | cosmetic BLOCKED | script L31–36 | S |
| 81 | Ch2 §2.9 | strength scales; RM-2 split; declining thresholds | RS L156–191, L145–148 | S (re-checked: 592 → … → 50 → 0, `exit_on_fail: false`; note: rule 9 has target −1, not 0) |
| 82 | Ch2 §2.10 | semantics and enforcement are disjoint sets | RS L195–220, L365–371 | S (H-03, H-04 concern the manuscript's own reading) |
| 83 | Ch2 §2.11 | allow-lists = unnamed exceptions; RM-4; RM-3 path | RS L237–278 | S |
| 84 | Ch2 §2.11 | lifecycle position ≠ validity | RS L144 | S |

### 2.2 Reference findings

| ID | Location | Sev. | Evidence | Fix |
|---|---|---|---|---|
| R-01 | Ch0 §0.2 L53; also §0.8 L230 | MEDIUM | "built, one completed cycle [DG01]". DG01 describes the pipeline (L23–24, L99–110) and marks learning as FUTURE and gated (L110). It never says a cycle completed. | Cite the actual evidence for the completed cycle, or remove it (see H-07). |
| R-02 | Ch0 §0.1 L33 | LOW | Synth L19–22 supports only "validity status invisible" (L21). "Rationale most demanded" is at Meta-Model L22. "Decays within months" is not at the cited lines. GKC L56–66 are the invariants, not phenomena. | Re-point the references. |
| R-03 | Ch0 §0.1 L35 | LOW | RQ-002 Charter L52–62 gives the standing mission but no count. "Four independent executors" is at GKC L47. | Add GKC L47. |
| R-04 | Ch0 §0.1 L41 | LOW | The quoted phrase "validity status invisible at the point of discovery" is at Synth L21, not L20. | Fix the line. |
| R-05 | Ch1 §1.9 L245 | MEDIUM | "TR's file path for the tests is wrong." TR L28 gives `Tests/Domain/AssessesIdentifierIntegrityTest.php` and L127 gives `scripts/lib/EngineeringKnowledge/.../Tests/Domain/`. Both are elisions consistent with the real path `Capabilities/IdentifierIntegrity/Tests/Domain/`. TR does say the throw is at `Assessment.php:38`; it is at L39 (off by one). | Withdraw "wrong"; at most write "abbreviated". |
| R-06 | Ch1 §1.9 L267 | MEDIUM | "The ARB … generalised this as a rule … [DR-AB L124] … applied the rule to the analyser findings [L126–133]." DR-AB L197 says: "§§1–2 are the ARB's decisions …; §§3–6 are Architecture's derivation and are PROPOSED." L124–133 is in §4. Only L51 is ruled. | Write: "Architecture (PROPOSED) generalises Decision B …". Register P2-F1-05 needs the same change. |
| R-07 | Ch1 §1.9 L273 | MEDIUM | "The ladder, as adopted" cites DR-AB §6 (L161–181), which sits in the PROPOSED part, although it reports an ARB instruction. DR-AB L177 also calls the traced rule the **existence proof that completes this chain**. The manuscript's "by the adopted pattern … not finished" contradicts the source and does not say so. It also mixes up *code* ownership (TR L132) with ownership of the *governed definition* (TR L59 names a "SEMANTIC OWNER"). | Mark the ladder as an ARB instruction reported in a proposed section. State the disagreement with DR-AB L177. Specify which owner is missing. |
| R-08 | Ch1 §1.6 L148–154 | LOW | GA L294 calls the "records, never grants" invariant the strongest convergence. The ≠ family is listed separately at L424 as promotion items. | Attribute separately. |
| R-09 | Ch1 §1.9 L263 | LOW | "HIGH" severity is at GA L468 (R-4), not L281. | Cite L281 together with L468. |
| R-10 | Ch1 §1.1 L33 | LOW | EP01 L52 lists applicability · scope · authority · temporal validity · normative effect. "Subject" and "exceptions" were imported from RM. | Mark the import. |
| R-11 | Ch1 §1.13.3 L417 | LOW | "Containment necessary but not sufficient (Ded)" is already in the AM-2 refinement: "not every narrower grant is a delegation — independent authorization must remain possible" (AMS L285). | Re-label as Src (AM-2). The derivation-edge part can stay Ded. |
| R-12 | Ch0 §0.7 L194 | LOW | The chain is labelled "Development (Ded/Hyp)", but PKSM L128 itself names it "a fourth theory — Knowledge Evolution". | Re-label as Src (ARB observation, watched, unbuilt). |
| R-13 | Ch2 §2.8.7 L409; register P2-F5-03 | LOW | EV L84 already states that the gate "checks for testing and blocks otherwise — so an unset environment fails safe". Only the general design rule is the researcher's. | Mark the observation Src and the generalization Ded. Change the register class from "NEW IDEA (researcher)" to "UPDATE OR REFINEMENT". |
| R-14 | Ch2 §2.8.2 L258–260 | LOW | SC L328 grades the containment relation INFERRED. SC L209 uses strict ⊂ ("narrower"), the manuscript ⊆. | Mark it "Src (inferred)". Decide whether the inclusion is strict or not. |
| R-15 | Ch2 §2.0 L49–53 | LOW | In the table copied from RS L65–70, "broken means: a violation" was silently changed to "non-conformance". | Mark the edit (it follows RM-1's refinement). |
| R-16 | Ch2 §2.8.6 L341–348 | MEDIUM | SD L156–160: lifecycle is "⚠️ different sense" for services and code, not "everything". Environment applies to code components (✅), which do not run, so "running things only" is wrong. | Copy SD's matrix and keep its ⚠️ cells. |
| R-17 | Ch2 §2.8.7 L409 | MEDIUM | `db-safety-check.sh` L27 runs a case-insensitive regex over the **command string** (`APP_ENV=testing\|--env=testing\|DB_DATABASE.*test`). It does not inspect the process environment, and one of the three "detections" is a database-name pattern, not an environment. | Describe the check accurately; this feeds L-17. |
| R-18 | Ch0 §0.8 L220 | LOW | DevConst L65–79 governs proposals of "capabilities, domains, documents, concepts". "Knowledge structures … cannot express a need" is a paraphrase that goes further. | Mark it as Int. |
| R-19 | Ch0 §0.5 L141 | LOW | "Added at 17:00 on 07-11" has no source among those cited (GKC L65 gives no time). | Cite the source (T-01?) or drop it. |
| R-20 | Ch0 §0.5 L128 | LOW | GKC's heading says "The Constitutional Invariants (ten)" (L54) but lists eleven. The manuscript says "eleven" without noting the inconsistency. | Note it; it bears on I-11's late addition. |
| R-21 | Ch2 §2.0 L16 | LOW | RS L30 says "Seven things …", while its table and L43/L87 say eight. | Note the source inconsistency. |

---

## 3. Mathematics and logic

| ID | Location | Sev. | Evidence / derivation | Fix |
|---|---|---|---|---|
| L-01 | Ch0 §0.3 L96 (a) | **HIGH** | The set is **ill-typed**. Every tuple in S ⊆ N×R×G×A has some g ∈ G, so "g undefined" is never true and "r = Tacit ⇒ g undefined" forces r ≠ Tacit. Read literally, S therefore contains **no tacit points**, and with "Skill ⇒ Tacit" **no Skill points** either. That removes exactly the knowledge the constraint was meant to describe. | Define S ⊆ N × R × (G∪{⊥}) × (A∪{⊥}) with r = Tacit ⇔ g = ⊥ (if Meta-Model L65 is read as an equivalence) and r = Tacit ⇒ a = ⊥ (canonicity cannot hold where status cannot: GKC L39, L74), plus n = Skill ⇒ r = Tacit. |
| L-02 | Ch0 §0.3 L90–98 (a) | MEDIUM | "The sources call the space a product of independent dimensions. By their own statements it is not." No source claims every combination exists. Meta-Model L63 says "orthogonal, **unevenly populated**" and L65 says "orthogonal **with one asymmetry**". The constraint is the source's own, so this is a restatement, not a correction. The second weakness is understated: GKC L103 also uses status values off the list ("Canonical-with-verdict-history", "Canonical-Stable"). | Re-frame as "formalizing the source's asymmetry". Add the off-list status values. |
| L-03 | Ch0 §0.5 L144–150 (b) | **HIGH** | (T) says trust requires knowing **where knowledge came from**. From I-11 + (T), only I-3 follows, for every claim because by I-11 every claim serves some need. **I-2 and I-6 do not follow.** They need a stronger premise, (T′): trust requires knowing the claim's *current validity* (status, freshness). The manuscript adds this silently ("status and freshness are part of what makes knowledge trustworthy"). Even (T′) gives only the *visibility* part of I-2, not "explicit, reasoned" transitions. **I-1 and I-7** need a reconstructability premise (R) ("users must be able to recover why and what was"), which nearly restates them. **I-9 is unaccounted for**: it is in neither the generated set {1,2,3,6,7} nor the remainder {4,5,8,10}. GKC L66 claims to generate specific *rules* (minimal sufficient context, never-a-copy, ephemeral assembly, gap evidence, selective capture), not I-1…I-10, so the test is aimed at a stronger claim than the source makes. | Restate: I-11+(T) ⊢ I-3; I-11+(T′) ⊢ I-6 and the visibility part of I-2; I-1, I-7 and the reasoned part of I-2 need (R); I-9 follows most naturally from (T′); I-4, I-5, I-8, I-10 remain. Test GKC's actual list (P6, never-a-copy, …) separately. |
| L-04 | Ch0 §0.4 L106 vs §0.6, Ch1 §1.2 | MEDIUM | "Knowledge changes in exactly two ways." GKC L23 itself lists *canonical → superseded* as a Knowledge Transition, and §0.6 and §1.2 make standing transitions a separate, act-labelled kind of move. That is a third move type (a change of status or standing, with no new claim and no change of representation). | Make it three moves: derivation · representation transition · standing transition (by act). |
| L-05 | Ch1 §1.6 L156–162 (c) | MEDIUM | The theorem is valid, but analytic: from "standing changes only by recorded acts", no act-free property changes standing. Its **domain is the standing of claims**. "Active ≠ authorized", "existence ≠ permission" and "ownership ≠ authority" are about *actors' permission to act* (grants, §1.13). They need the separate premise "permission exists only via a grant referencing an act". Ch2 §2.8.6 then cites "relevance ≠ applicability" as new evidence for §1.6, but applicability is a semantic predicate that no act confers, so it is not an instance. Because the theorem is analytic, its "predictions" (popularity, age) are not empirical tests. | State the axiom over *normative status* (claim standing **or** actor permission). Remove "predicts". Drop the applicability instance. |
| L-06 | Ch1 §1.6 L164; Ch2 §2.8.2 L262–264; §2.8.4a L325 (c) | MEDIUM | The scope refinement comes in two **different** forms. §1.6 and §2.8.2 say grant scope ⊇ *subject scope* of what is changed. §2.8.4a says *canon scope* ⊆ grant scope. Canon scope values {context-local, project, platform, constitutional} and SC-1's four dimensions share no common order, so the containment is undefined until a mapping exists. The refinement also omits **time**: endings exist (§1.13.2), so the act must fall within the grant's validity. That gives act × scope × time. And SD L317 rules the chain a "CANDIDATE RELATIONSHIP … not an adopted universal law", which the box in §1.6 does not mention. | Choose one statement. Define the scope space in which containment is evaluated. Add the time condition. Mark it as a candidate. |
| L-07 | Ch2 §2.3 L146–156 (d) | MEDIUM | The exception revision is not built into the conflict test. In standard deontic logic, P(φ) ∧ O(¬φ) stays inconsistent whether or not P comes from an authorized exception. "Legitimate iff authorized exception" only works with a defeasible reading, in which the exception **narrows the rule's effective applicability**: A′ᵣ = Aᵣ ∧ ¬(A_e1 ∨ … ∨ A_ek) over current, contained, authorized exceptions. The refined definition still flags the overlap as a conflict. | Define the conflict test on effective applicability. Report unauthorised permissions as a separate finding class. |
| L-08 | Ch2 §2.9 L483–487 vs §2.3 L144 (d) | MEDIUM | (i) "Conflicts are defined on polarity at mandatory strength" contradicts §2.3's own row MUST φ vs MUST φ′ ("TLS 1.3" vs "TLS 1.2 only"). That is a conflict with the *same* polarity, arising from the propositions. (ii) "Deontic effect = polarity × strength" is not a Cartesian product: MAY has no mandatory/recommended counterpart, so the cell (permit, mandatory) is undefined. After the exception revision, permit is not a rule polarity at all. | "Proven conflicts arise only between mandatory effects, from the joint unsatisfiability of their propositions on the overlap." Treat effect as a partial map, or drop permit. |
| L-09 | Ch2 §2.9 L482 | **HIGH** | "Enforcement consequence **is** the realization level … a property of the rule's representation." The section's own evidence refutes this. `design-check.sh` is executable (mechanized) yet `exit_on_fail: false` makes it advisory, and the consequence changes by a flag **with no change of representation** ("becomes hard-blocking when violations drop below threshold"). Realization is at most an **upper bound** on consequence: you cannot block without an executable check, but an executable check need not block. | Restate: consequence ≤ realization. The two are separate properties, so "no new dimension needed" must be argued again. |
| L-10 | Ch2 §2.3(a) L126–130 (e) | LOW | The core is right. Satisfiability is decidable for propositional formulas over finite domains (NP-complete) and for linear arithmetic, and undecidable for first-order logic over unrestricted domains. Two cautions. RM L167's "decidable" means *determinate* ("does not depend on who asks"), not algorithmically decidable, so the critique answers a different notion. First-order logic over a *fixed finite* attribute domain is decidable, so the real boundary is unbounded domains and quantified or non-linear arithmetic. Even in decidable fragments, a bounded solver can still return UNKNOWN. | Note the equivocation. State the boundary precisely. |
| L-11 | Ch2 §2.6 L211–215 (f) | MEDIUM | "The key must be a function of its applicability" is **neither sufficient nor necessary**. Counterexample to sufficiency: key(r) = hash(predicate text) is a function of applicability, yet two rules with different texts and overlapping regions get disjoint keys. Necessity fails because partitioning by validity interval or scope is also sound. The sound condition is **over-approximation**: for a projection π shared by all rules, key(r) ⊇ π(Region(r)). Then non-empty overlap ⇒ π-overlap ⇒ keys intersect. Under SC-2, a rule with ⊥ on the key attribute must go to every partition, or its pairs must be reported UNKNOWN. | Replace the requirement with the over-approximation condition. |
| L-12 | Ch2 §2.8.4 L312–317 (g, containment) | **HIGH** | The empty-set edge case is stated **per dimension**, but its correct meaning is **global**. A product (box) region with any empty side is empty, and so is contained in everything. Counterexample: A = (X₁ = ∅, X₂ = {a}), B = (Y₁ = V₁, Y₂ = {b}). Per dimension: true ∧ ({a} ⊆ {b} = false) = **false**, yet A = ∅ ⊆ B is **true**. The precedence between "B unspecified → unknown" and "Xᵢ = ∅ → true" is also unstated. Other cases, checked under supervaluation semantics (⊥ = an unknown X ∈ 𝒫(Vᵢ), and the result is true/false iff true/false for every completion): both specified → Xᵢ ⊆ Yᵢ ✓; Xᵢ specified ≠ ∅, Yᵢ = ⊥ → unknown ✓ (Y = Vᵢ vs Y = ∅); Xᵢ = ⊥, Yᵢ = Vᵢ → true ✓; Xᵢ = ⊥, Yᵢ ≠ Vᵢ (including Yᵢ = ∅) → unknown ✓; both ⊥ → unknown ✓. Strong-Kleene conjunction across dimensions is exact for independent dimensions ✓. | "If some Xᵢ = ∅, A ⊆ B is true; otherwise the Kleene conjunction of the per-dimension cases." State the semantics of ⊥. |
| L-13 | Ch2 §2.8.4 L292–308 (g, applicability and overlap) | LOW | Applicability (true/false/unknown; false dominates) is **correct** for a fully described context ✓. Overlap is **sound but not tight**. If ⊥ ranges over all of 𝒫(Vᵢ), then Xᵢ = ∅ with Yᵢ = ⊥ is false under every completion, but the rule gives unknown. If ⊥ excludes ∅, then Xᵢ = Vᵢ with Yᵢ = ⊥ is always true, but the rule gives unknown. Contexts with unknown values of their own (cᵢ unknown) and the tree semantics of system location (membership must become "cᵢ lies in the subtree of some x ∈ Xᵢ") are not covered. The temporal and predicate parts of "applies" are left outside the Kleene combination. | Fix the semantics of ⊥ and add the tight cases. Restate membership for the tree. Integrate Tᵣ and Aᵣ. |
| L-14 | Ch2 §2.8.6 L376–378 (g, three states) | MEDIUM | (i) Containment and overlap between **different subject kinds** (exception vs rule vs grant, or rules about documents vs services) are undefined when their app-profiles differ. (ii) A further loophole: the **kind assignment** κ : subject → K. An author who classifies a service as a document drops *environment*. Governing K is necessary but not sufficient; κ must be governed or checkable too. (iii) Category slip: SD L373 and the manuscript treat the governed list of **31 artifact kinds** as K. But K must include running services and code components (SD L156–160), which are not artifact kinds, and "artifact kind" is itself a scope dimension that applies only to knowledge objects. | Define the cross-kind comparison. Add κ. Separate K from the artifact-kind vocabulary. |
| L-15 | Ch2 §2.8.6 L380 (h) | **HIGH** | "Adding d to app(k) turns **every** existing statement … from determinate into CANNOT DETERMINE." This is false by the manuscript's own §2.8.4 rule (false dominates). Contexts where a statement already evaluates to DOES NOT APPLY stay determinate. Only APPLIES results (and *true* overlaps, hence every proven conflict) degrade to unknown. The conclusion "adding is not cheap" survives, because exactly the positive results degrade. | "… turns every positive applicability result and every proven overlap or conflict over kind k into CANNOT DETERMINE; negative results survive." |
| L-16 | Ch2 §2.8.7 L421 (h) | MEDIUM | "EV's claim holds **iff** scope is written by positive enumeration." (i) This fails for the **hierarchical** system-location dimension the chapter has just adopted (§2.8.6 L354: a value denotes its subtree). Adding a component under product P silently widens every statement that names P, even with positive enumeration. (ii) "All" written as a symbol that resolves to the current Vᵢ widens like a complement. (iii) "Iff" is too strong: positive enumeration is sufficient for flat dimensions, not necessary in general (a governed default may intend widening). | "Sufficient for flat dimensions with extensional value sets; for hierarchies, adding a child widens its ancestors' statements by design." |
| L-17 | Ch2 §2.8.7 L409–413 | **HIGH** | "An allow-list on the safe value is robust to unknown **or mislabelled** states." This is false for mislabelling **as the safe value**. The gate's positive evidence is a substring of the requester's own command (script L27, `grep -qiE`). So `php artisan migrate:fresh # --env=testing`, or a main database whose name contains "test" (e.g. `…latest…`), is **permitted**. This contradicts the chapter's own Idea 3 ("a self-report is a claim"): the permitting evidence here *is* a self-report. The principle holds only for missing or unknown information and for mislabelling as a non-safe value. | Restate it with that scope, and add: "the permitting evidence must have provenance other than the requester". Keep the running case as one confirmation, n = 1. |
| L-18 | Ch1 §1.13.2 L402 (i) | MEDIUM | The arithmetic is right: 3/144 = 2.08%, and the exact one-sided 95% bound is 1 − 0.05^{1/144} = 2.06%. But (i) the property is written by the registering tool, so a structural guarantee is not a Bernoulli trial and the bound adds nothing. (ii) The records are cumulative and very likely include AMS's 20, so "holds at 7× the sample" is not independent replication. (iii) The 144 records include the 2 ending records (REVOKED, CONSUMED), so there are 142 grants plus 2 endings. (iv) The check is for the *presence of a field*, not that the referenced act exists or covers the scope. | Report it as a census of field presence (144/144 records). Drop or heavily qualify the bound. Separate grants from ending records. |
| L-19 | Ch1 §1.13.3 L417–419 (j) | MEDIUM | The core is valid: containment cannot tell a delegated grant from an independent narrower one, so extra information (an edge, or a flag plus parent) is required. Gaps: (i) "a forest" assumes one parent per grant, but joint authorizations ("PO/ARB") would give a DAG. (ii) A missing condition: the parent must carry a **right to delegate**; otherwise any holder could sub-delegate. (iii) Validity containment (a) should be paired with transitive scope containment. (iv) Part of it is AM-2 (R-11). | Add the delegation-right condition; say "DAG unless single-parent is ruled". |
| L-20 | Ch1 §1.9 L255–261 | MEDIUM | "The reserved verdicts are statements about standing … exactly §1.3's separation, now implemented." The code comment (`Verdict.php` L14–17) reserves by **who performs the act** ("belong to review and certification acts"). PASS AFTER CORRECTION and EMERGENT are review *outcomes about evidence and novelty*, not standing. So the code implements "reserved to review acts", which is broader than "confers standing". "Never confer standing" is a sufficient reason for reserving an outcome, not the code's actual criterion. | Downgrade to Int: "consistent with", not "exactly implemented". |
| L-21 | Ch2 §2.8.8 L440, Ch0 §0.7 | MEDIUM | E5 identifies "knowledge does not exist" with "an unsatisfiable need". By §1.5's own argument, a failed assembly can be E3 (not reached) or E4 (no carrier) as well as E5, and PKSM's *unsatisfiable* state does not tell them apart. | "Unsatisfiable need = E3 ∪ E4 ∪ E5 (∪ E6) until diagnosed; gap evidence must record which." |
| L-22 | Ch2 §2.5 L196–201 | MEDIUM | "A theorem of the foundations." A priority applied *visibly at use* does not violate I-4 ("resolve at consumption"), and §2.10 L510 itself says I-4/PK-P7 require "explicit rank". The valid ground is Ch1 §1.6 (an author's number is not an authority act) together with §2.8.4a (independent obligations vs alternatives). | Re-derive from those two premises. Label it Ded, not "theorem". Reconcile with "explicit rank" (H-05). |
| L-23 | Ch1 §1.7 L172–179 | LOW | vis(d) ⊆ ∩ vis(sᵢ) correctly formalizes "never widen" ✓. "A conflict with never a copy" is a consequence, not a conflict. Legitimate declassification (e.g. aggregates) should be possible by an act, which the formula forbids absolutely. | Add "unless widened by a recorded act". Re-word "conflict". |
| L-24 | Ch2 §2.2 L94 | LOW | The ten-part "union" double-counts. RS L349–359 merges subject and applicability into obligation and scope, and the union then lists all four. By the repository's own model-integrity rule (orthogonal · necessary · sufficient), the parts are not orthogonal. | Give a mapping table RM↔RS instead of a union. |

---

## 4. Epistemic labelling

| ID | Location | Sev. | Evidence | Fix |
|---|---|---|---|---|
| E-01 | Ch1 §1.9 L267–273, §1.11 L306 | MEDIUM | RS L22: "The lane applies to **this whole knowledge-transfer track** … every artifact in it … inherits the same status." This makes Decisions A and B (DR-AB) Track B as well. The manuscript writes "Adopted by the ARB", "as adopted", "Src, ruled" with no Track-B qualifier, while REVIEW-02 L116 limits the Track-B caveat to the five pilot-2 sources. R-06 and R-07 add proposed text labelled as ruled. | Mark every DR-AB ruling "ruled, Track B". Replace "adopted" with "ruled (Track B)" throughout. |
| E-02 | Ch0 L67 box; Ch1 §1.8 L201–209; §1.11 L322 | MEDIUM | A cited-only source (the 2026-08-02 tier model, through GA) is used as established. "KnowledgeOS is therefore not a knowledge base but the producer …" is stated as a conclusion in Chapter 0, and "a PKS is …" (§1.11) is built on it. | Mark them conditional: "if the cited tier model holds". |
| E-03 | Ch1 §1.6 L164 | MEDIUM | The refined axiom is presented as part of the theory's axiom, but the ARB ruled the containment chain a candidate, "not an adopted universal law" (SD L317). The caveat appears only later (Ch2 L388). | Add the caveat in the box (see also L-06). |
| E-04 | Ch1 §1.6 L156–162; Ch2 §2.5 L201 | LOW | Labels: "theorem … predicts" (analytic) and "a theorem of the foundations, not a taste" (needs extra premises). Logic is counted in L-05 and L-22; only the labels change here. | Use "analytic consequence" and "Ded under premises …". |
| E-05 | Ch2 §2.8.4 L321 | LOW | "Two independent mechanisms … chose the same three-valued logic." The research verifier was "built for this programme", plausibly by the same author or assistant, so independence is not shown. | Write "consistent with", or show independence. |
| E-06 | Ch1 §1.10 L290, Ch2 §2.4 L179, §2.8.4 L321, Ch0 §0.6 L158 | LOW | Background notes (B-05, B-10, B-12, KT-8, T-01, "KT documents") are used as evidence but are not in any chapter's source list, so a reviewer cannot check them. | List them as sources, with locations. |
| E-07 | Ch1 §1.1 L36, §1.6 L154 | LOW | Stale "I have not opened …" notes contradict later independent checks: the code was read in §1.9, and 10 YAML files were read in pilot 2. | Update to Ind. |
| E-08 | Register P2-F1-05, P2-F2-10, P2-F5-03 | MEDIUM | P2-F1-05 is labelled "SOURCE FACT (ruled)" for a PROPOSED generalization (R-06). P2-F5-03 is labelled "NEW IDEA (researcher)" for a fail-safe EV states (R-13), and its "accepted" status ignores L-17. P2-F2-10 is "provisional" while the manuscript text treats it as settled. | Re-label. Move P2-F5-03 and P2-F4-08 to "provisional" until L-15 and L-17 are fixed. |

---

## 5. Coherence

| ID | Location | Sev. | Evidence | Fix |
|---|---|---|---|---|
| H-01 | Ch1 §1.2 L60–64 vs Ch2 §2.8.6 L386, §2.11 L537; GKC L38 | **HIGH** | Three definitions clash. GKC defines *governance status* as "**lifecycle position**" (L38). Ch1 §1.2 says GKC's status values "are what EP01 calls standing". Ch2 §2.8.6 says lifecycle phase ≠ standing. The governed schema has **both** a lifecycle `status` field (`statuses.yaml`, 8 values) and an `authority` field, with the header comment "It is INDEPENDENT of `status` (lifecycle)" (`authorities.yaml` L4). So the schema keeps lifecycle separate. What it conflates is provenance with standing, a split GKC never made either: GKC has Provenance and Status, and no standing distinct from lifecycle. The claims "the foundations already had it" and "regression" therefore do not follow. | Write a mapping table: GKC status ↔ schema `status` ↔ standing ↔ provenance. Restate the regression claim: the schema *added* a standing-like field and fused it with provenance. |
| H-02 | Ch2 §2.0 L55 vs §2.9 L471–479, §2.8.6 L347 | **HIGH** | "Strictly, the running system has **no machine-read rules in the RM-1 sense at all**." But §2.9 reads `design-rules.json` as fixed obligations (target 0) with severities and allow-lists (exceptions), which are rules with binding strength. SD L103–108 shows per-kind traceability obligations "enforced by a linter". The statement over-generalizes from the recommendation engine. | Limit it to the recommendation engine ("GA's threshold matcher is not a rule store"). |
| H-03 | Ch2 §2.10 L496 vs L510 | MEDIUM | "Governance rules … carry … declared supersession" contradicts, in the same section, "Rulings are append-only with no supersession links" (RS L231; only standards declare supersession, L230). | Restrict to standards. |
| H-04 | Ch2 §2.10 L505 vs Ch1 §1.9; §2.9 | MEDIUM | "Two ends with nothing in between." Yet the reserved-verdict rule completes all five stages (§1.9), quality gates are mechanized but advisory (§2.9), and document-validation rules are owned and lint-enforced (RS L40; SD L105). | "Mostly two ends; the exceptions are …" |
| H-05 | Ch2 §2.10 L510 vs §2.5 L196–199 | MEDIUM | §2.10 says I-4/PK-P7 require coexistence "**with explicit rank**". §2.5 says ranking at rest violates I-4. Visible ranking and silent resolution are not distinguished. | Distinguish rank as annotation (allowed) from rank as automatic resolution (forbidden for independent obligations). |
| H-06 | Ch1 §1.5 L135 vs §1.1 L23–34 | MEDIUM | "The same knowledge is carried at once in YAML, Markdown, PHP, JSONL and shell." GA's table (L157–169) lists PHP as *capabilities acting on knowledge* and shell as *runtime adapters*. Under I-11(RO), these execute and are therefore not knowledge carriers. The reading contradicts §1.1. | Keep YAML, MD and JSONL as carriers. Treat PHP and shell as capabilities, or test I-11(RO) on them explicitly. |
| H-07 | Ch0 §0.2 L53, §0.8 L230 vs Ch1 §1.8 L215 | MEDIUM | "One completed cycle" vs "fed back … **zero** times … never once operated end-to-end". Without a definition of "cycle", one of these is wrong. | Define it (e.g. one pass from observation to assessment vs one feedback into governance) and correct both sides. |
| H-08 | Ch1 header L7–9; Ch2 header L3–6 | LOW | The source lists are incomplete ("further sources are added as the pilot proceeds"). GA, TR, DR-AB, AMS, RS, SC, SD and EV appear only inline. | Complete the headers. |
| H-09 | across chapters | LOW | "Lifecycle" names at least five things: the GKC cycle (Ch0 §0.4), the RM-3 rule path (= standing transitions), the subject lifecycle phase (SC-1), exception standing (granted, withdrawn, expired), and authority endings (AM-1). §2.8.6 separates only three. | Add a glossary entry. |
| H-10 | Ch2 §2.8.4 L303, §2.8.6 L366 vs §2.3(a) | LOW | "Undecidable/undeterminable" is used both for three-valued indeterminacy and for computability. | Use "indeterminate" for SC-2 cases. |
| H-11 | Ch0 §0.9 Q2; Ch0 §0.5 I-4 "Open for machine users"; Ch1 §1.1 | MEDIUM | Some open questions are no longer accurately visible. (i) PKSM L125 (an authorized source) names **Context Assembly as the candidate kernel** of the knowledge ecosystem, which partly answers "what is the kernel" (Q2); the manuscript does not mention it. (ii) The I-4 machine-user problem is never revisited after §2.3–2.7, which bear directly on it (one answer at use, UNRESOLVED as an output). (iii) Ch1 §1.1, see C-07. | Add these as dated updates to the open list. |

---

## 6. Register traceability (18 rows sampled)

| Row | Manuscript location exists and says it? | Note |
|---|---|---|
| P2-F1-01 | yes, Ch1 §1.11 L306 | — |
| P2-F1-03 | yes, §1.11 L322 | cited-only premise (E-02) |
| P2-F1-05 | location yes (§1.9 L267); **label wrong** | E-08, R-06 |
| P2-F1-07 | yes, §1.12; follow-up (residual trust) done at L359 | — |
| P2-F1-10 | yes, §1.11 L342–347 | — |
| P2-F2-02 | yes, §2.0 L30–37 | — |
| P2-F2-07 | yes, §2.11 L537–541 | — |
| P2-F2-10 | yes, §2.9 L483–487 | logic issue L-08; source anchor (RS L156–191) is loose for a researcher idea |
| P2-F2-13 | yes, §2.11 L517, L533 | 9/9 rules carry `allowlist_patterns` (re-checked) |
| P2-F2-18 | register only, as stated | RS L101 "Two of the six" ✓ |
| P2-F3-04 | yes, §1.13.3 L411, L417 | R-11, L-19 |
| P2-F3-06 | yes, §1.13.2 L395 | counts re-checked (142/1/1) |
| P2-F3-07 | yes, §1.13.2 L404 | n = 1 correctly marked provisional |
| P2-F3-09 | location yes (§1.13.2 L399, L406) | `.gitignore` is outside my access list, so the "VERIFIED via git check-ignore" claim is **unchecked** |
| P2-F4-02 | yes, §2.8.6 L341 | 31/8/12/3/9 re-checked |
| P2-F4-08 | yes, §2.8.6 L380 | the challenge is overstated (L-15); "challenged" stands but needs correcting |
| P2-F4-11 | yes, Ch1 §1.2 L64 | correct, but see H-01 on what it proves |
| P2-L-03 | register only, as stated | contradicts REVIEW-02 "fixed" (C-01) |

| ID | Location | Sev. | Evidence | Fix |
|---|---|---|---|---|
| G-01 | P2-F2-10 | LOW | A researcher idea is anchored to a source range that does not state it. | Put "researcher (Ch2 §2.9)" in the location column. |
| G-02 | P2-F3-09 | LOW | Its verification cannot be reproduced under the audit's access rules. | Keep the exact `git check-ignore -v` output in a background note that may be read. |
| G-03 | P2-F4-08, P2-F5-03, P2-F5-08 | LOW | The statuses "challenged/accepted/accepted with condition" rest on arguments that §3 finds need correcting (L-15, L-17, L-16). | Re-review these after the fixes. |

---

## Severity counts

| Section | HIGH | MEDIUM | LOW |
|---|---|---|---|
| 1 Corrections | 0 | 4 | 3 |
| 2 References | 0 | 6 | 15 |
| 3 Mathematics and logic | 6 | 14 | 4 |
| 4 Epistemic labelling | 0 | 4 | 4 |
| 5 Coherence | 2 | 6 | 3 |
| 6 Register | 0 | 0 | 3 |
| **Total** | **8** | **34** | **32** |

## Overall judgement

The manuscript is careful about sources at the level of single lines. About 80% of the sampled references are fully supported, the code, configuration and record counts re-check exactly, and both pilot reviews were largely carried into the chapters. The weaknesses are in what the author added. Several formal statements are wrong as written:
- the constrained subset S is ill-typed and literally excludes all tacit knowledge;
- the telos derivation silently strengthens premise (T) and loses I-9;
- the empty-set containment rule is stated per dimension and gives a false result;
- "enforcement consequence = realization level" is refuted by the chapter's own design-check example;
- "adding a dimension makes every statement indeterminate" contradicts the chapter's own strong-Kleene rule;
- the "allow-list is robust to mislabelling" principle fails on the very gate it generalizes from, which permits on a self-reported string.

The second systematic problem is standing. Proposed derivations in DR-AB are presented as ARB adoptions, the whole knowledge-transfer track is Track B by RS L22 but is not treated so in Chapter 1, and cited-only tier claims become conclusions in Chapter 0. Finally, the core vocabulary (governance status vs standing vs lifecycle phase; canon vs grant scope) is not yet consistent across chapters, and that is where the "regression" and "one authority axiom" claims are weakest. None of this destroys the framework. The claim-in-a-space model, the authority-as-reference pattern, SC-2 with three states, and the separation of evidential grade from standing all survive. But eight findings need correcting before the chapters can count as a stable base for the corpus phase.
