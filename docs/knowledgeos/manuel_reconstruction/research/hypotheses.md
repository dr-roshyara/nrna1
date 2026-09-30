# Hypotheses — pilot F0001–F0005

Allowed statuses: SPECULATIVE · PLAUSIBLE · STRONGLY SUPPORTED BY CURRENT CORPUS · UNRESOLVED · WEAKENED · REJECTED.
"Current corpus" means only these five files. Each hypothesis ends with its chronology.

---

## H1 — Knowledge items live on two independent axes: evidence and authority

- **Claim:**
  - Every governed knowledge item has at least two coordinates. One is **evidential**: provenance, source grade, instance count. The other is **institutional standing**: hypothesis, candidate, canonical.
  - The two are logically independent. Neither implies the other.
  - Promotion rules couple them one way only: standing may rise only if evidence reaches a threshold (a necessary condition), and only an authority act actually raises it (the sufficient trigger).
- **Evidence:** O3, O12, O13, O14, O19, O20, O34, C8.
- **Counterevidence:**
  - C9: the same n=1 maps to Candidate in one file and to Hypothesis in another, so the evidence→standing threshold is not applied consistently.
  - F0003's buckets mix knowledge states with decision outcomes (O22).
- **Alternative explanation:** ordinary separation of duties, or change-management hygiene, with no deeper epistemology.
- **Prediction:** later files will keep separate fields for "where it came from / how strong" and "what status it has been granted". They will also contain cases of high evidence with low standing, and of canon with little evidence.
- **Falsification:**
  - A sanctioned promotion to canonical status by evidence alone, with no authority act.
  - Or standing *defined* as a function of evidence, which would make the axes dependent.
- **Status:** **STRONGLY SUPPORTED BY CURRENT CORPUS.** Independence holds in both directions (O19, O20).
- **Chronology:**
  - First observed: F0001, which says a baseline becomes a baseline by adoption.
  - First explicit formulation: F0002 L26 (`authority = provenance × standing`).
  - Later support: F0003 (definitional guard; C-12), F0004 L6.
  - Later contradiction: none yet (C9 is a tension, not a refutation).

---

## H2 — The corpus behaves as an event-sourced knowledge ledger

- **Claim:**
  - Knowledge state is changed only by typed events, namely *EvidenceRecorded* and *AuthorityRuled*, and possibly *DemotedOnChallenge*.
  - Documents are projections of that state (views derived from it).
  - Corrections are appended, not edited in.
- **Evidence:** O10, O21, O26, O27, O30, O36; P6 (corrections kept alongside the claims they correct).
- **Counterevidence:**
  - C7: argument or review changes standing, which is not one of the two declared event types.
  - The files *are* edited (REV 2), so they are not strictly append-only.
- **Alternative:** git-style versioning. The event language may be metaphor.
- **Prediction:** later sources contain append-only registers (rulings logs, evidence registers) with dashboards regenerated from them. They will also name the transition types.
- **Falsification:** status changes are routinely made by editing documents, with no event record.
- **Status:** **PLAUSIBLE.**
- **Chronology:**
  - First observed: F0001 (a correction made by annotation).
  - First explicit formulation of the update rule: F0004 L7 (2026-08-03).

---

## H2a — Asymmetric transition rule: raising standing needs evidence; argument is enough to lower it

- **Claim:** this refines H2 and resolves C7. Promotion requires evidence plus authority, while demotion can happen on reasoned challenge alone.
- **Evidence:** C7 — F0003's downgrade and R-12 rejection on review, set against F0004's "never by re-reasoning".
- **Logic:** the rule preserves P1. Lowering a claim's strength can never violate the ceiling (strength ≤ g(evidence)), so demotion needs no evidential warrant.
- **Falsification:** a promotion justified by re-reasoning alone and accepted by the corpus. Or a demotion refused for lack of evidence.
- **Status:** **SPECULATIVE.** It is elegant, and this pilot contains only about 3 instances.
- **Chronology:** becomes visible only once F0003 and F0004 are compared. No single file states it.

---

## H3 — Most corrections separate realization levels that had been merged

- **Claim:**
  - The dominant error mode in the corpus is *level collapse*: treating a specification as an implementation, a capability as enforcement, bootstrap as method, informal as formal.
  - The dominant correction is to separate the levels.
- **Evidence:** O36 (three corrections), O37, C1, C2, F0002 "designed ≠ demonstrated", F0001 "the port exists; the adapter is a person".
- **Counterevidence:** some corrections are of other kinds: arithmetic (C10), misattribution (F0005 correction #1, SD-1 attributed to the wrong responsibility), and novelty (O10).
- **Prediction:** if all later corrections are classified, level-separation will be the plurality class.
- **Falsification:** level-separation is a minority of corrections in a larger sample.
- **Status:** **PLAUSIBLE.**
- **Chronology:**
  - First observed: F0001 (responsibility vs component).
  - Densest in F0005 (dated 2026-08-02).

---

## H4 — METHOD / BINDING / EVIDENCE is the structural core of extraction

- **Claim:** every reusable artifact decomposes as method (a closed term), binding (a parameter) and evidence (traces). The kernel and the PKS are those projections.
- **Evidence:** F0001 §3–4 and F0005 §7–8 (P4). It explains O5, the failure of grep.
- **Counterevidence:**
  - **The decomposition is absent from all three 2026-08-03 files.** F0003 aims to classify "EVERYTHING discovered" (53 concerns) and does not list it.
  - It rests on n=1 (Round47-OP).
  - The F0001 annotation says P1 was *re-derived* from an older Principle/Form framework.
- **Alternative:** a local analytic tool for one question ("can Round47-OP be extracted?").
- **Prediction:** if H4 is right, the decomposition reappears in later extraction and kernel work.
- **Falsification:** it vanishes after F0005, or it is replaced by a different axis (e.g. Principle/Form/Ambiguous).
- **Status:** **UNRESOLVED**, and **WEAKENED** compared with the prominence F0001 gives it.
- **Chronology:**
  - First formulated: F0001 (2026-08-02), as a claimed contribution, then annotated as re-derived.
  - Reused: F0005 (same date).
  - Absent: F0002–F0004.

---

## H5 — KnowledgeOS's distinctiveness depends on two edges that currently stand at n≈0

- **Claim:**
  - What would distinguish KnowledgeOS from a governance rulebook is (a) *generating* a PKS and (b) a *canon-level* back-edge from evidence to platform.
  - Both are at n≈0.
  - If they stay there, the best-supported model of the corpus is T2 (a knowledge-state system) or T1 (a governance system), not T3 (a generator).
- **Evidence:** P7, O38, F0003 H-5 ("the distinctive bet"), F0004 L22–23.
- **Counterevidence:** the programme is at a bootstrap stage, so n≈0 may reflect timing rather than kind.
- **Prediction:** watch for the first `generates → PKS` event and the first canon-level traversal.
- **Falsification:** a PKS produced by a repeatable mechanism with n≥2; or a canon rule changed by recorded evidence.
- **Status:** **PLAUSIBLE.**
- **Chronology:** the n=0 status is present in every pilot file. The "distinctive bet" wording appears first in F0003.

---

## H6 — Genesis is a missing base case

- **Claim:**
  - The admissibility rules are inductive (a decision needs prior governed evidence), so without an axiom the admissible set is empty.
  - F0005's day-zero clause is exactly such an axiom.
- **Evidence:** P8, O8, O35.
- **Counterevidence:** real programmes start anyway, through ungoverned human acts. The "gap" may be deliberate (F0005 L163).
- **Prediction:** later sources will either adopt a day-zero axiom (Provisional first decision) or rule Genesis out of scope.
- **Falsification:** a later source shows an existing governed route from idea to first decision that the pilot overlooked.
- **Status:** **PLAUSIBLE.**
- **Chronology:** stated in F0001 L184, with the fix sketched in F0005 L162, both dated 2026-08-02.

---

## H7 — The platform is advisory by design, not by immaturity

- **Claim:**
  - "AI evaluates and recommends; authority remains with governance" (F0003 C-8 canon), together with the Canonical = human-only guard, implies that the platform's own capabilities *must not* enforce.
  - On this reading, "adoptable by habit, never by mechanism" (F0005) is a consequence of design, not a defect.
- **Evidence:** O19, O32, F0003 C-8.
- **Counterevidence:** F0005 treats the absence of enforcement as a gap, and it names `knowledgeos init` as an enforcing mechanism the vision needs.
- **Alternative:** it is simple immaturity. The runtime already enforces 41 controls, so enforcement is possible.
- **Discriminating test:** does a later source add a capability that *enforces* (fails closed — PGP "fail closed" is canon, F0003 C-4)? The canonical "fail closed" pattern argues **against** H7.
- **Status:** **UNRESOLVED.** There is canonical evidence on both sides.
- **Chronology:** only visible when F0003 C-4/C-8 and F0005 §2 are read together.

---

## H8 — Verdict logic is three-valued (open world)

- **Claim:** KnowledgeOS verdicts follow a three-valued logic (PASS / FAIL / INCONCLUSIVE). Absence of evidence yields INCONCLUSIVE, never PASS; that is, it refuses negation-as-failure.
- **Evidence:** O9; the "closed verdict vocabulary" (F0001 L55; F0005 L55); the Open Question bucket.
- **Counterevidence:** F0003 notes two *colliding* closed verdict sets (ES-003.1 vs CAP-001 §5), so the logic is not yet unified.
- **Status:** **SPECULATIVE.** There is one explicit instance.
