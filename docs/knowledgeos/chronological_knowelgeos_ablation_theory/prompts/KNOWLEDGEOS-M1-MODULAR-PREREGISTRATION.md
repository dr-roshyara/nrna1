# M1 (modular): pre-registration, frozen before any hold-out row is read

| | |
|---|---|
| Status | **Frozen at commit, before rows R-82…R-87 and R-89 are read.** Not canonical. Authority: none. M0 (`KNOWLEDGEOS-M0-…`) is untouched |
| Motivating data | development only: F-LOG-0117…0122 (M0 run, R-88, R-47, R-53, R-66, R-81, R-91, R-90) |
| Hold-out | **prospective temporal/template hold-out cases**, not an independent test set. The rows are `ADR-AIP-LOG-Platform-Rulings.md` (`7795c14b…`) L68–L73 (R-82…R-87) and L75 (R-89). All are unread and all use the R-81…R-91 template, so they share template, era, authoring convention, vocabulary and precedents. **No IID or population claim is permitted** |
| Rule | No amendment after the first hold-out row is read; revisions go to M2 |

## 1. Components (each HYPOTHESIS; tested separately; a violation falsifies only its component)
- **M1-status.** A ruling's status ∈ {PREPARED, ADOPTED, HELD, WITHDRAWN}. The transition semantics:
  - (s1) an ARB-Chief-issued ruling starts PREPARED and **is not governing** while PREPARED;
  - (s2) PREPARED → ADOPTED only by a Decision-Authority act, never by the issuer's own act;
  - (s3) HELD → ADOPTED is possible (HELD is not void);
  - (s4) WITHDRAWN is absorbing and **retires the number**; HELD does not;
  - (s5) adoption creates **no standing delegation**: a later Chief ruling is again PREPARED;
  - (s6) the status annotation changes; the decision text does not.
- **M1-scope.** Authorization is a relation authorized(authority, scope). Every stated authorization names a bounded scope, and authorization of a scope never extends to another scope by implication.
- **M1-freeze.** Freeze is scope-indexed: lifting it for one scope leaves the other scopes frozen.
- **M1-target.** Counter-evidence has a target (a decision, an implementation, evidence, …). **Evidence never changes the standing/status of anything without a separate operation**, and evidence about one target is not evidence about another.
- **M1-record.** Two-layer record: another ruling's **decision text is never amended**. Change happens only by annotation (status/pointer) or by a new ruling (supersession, refinement).
- **M1-operations.** A candidate vocabulary with declared Frame⁺ (the coordinates the operation may change):

  | Operation | Frame⁺ |
  |---|---|
  | AUTHORIZE | {authorization(scope)} |
  | ADOPT | {status} |
  | HOLD | {status} |
  | WITHDRAW | {status, registry} |
  | ANNOTATE | {annotation} |
  | ACCEPT | {acceptance(target)} |
  | CLOSE-WORK / OPEN-WORK | {work-lifecycle(target)} |
  | SUBDIVIDE | {work-structure} |
  | DETERMINE | {evidence-status, classification} |
  | FREEZE / LIFT | {regime(scope)} |
  | CONTRA | {contra(target)} |
  | CREATE-NORM | {norm, standing} |
  | RAISE | {standing} |
  | REJECT | ∅ |

  **Frame⁻** = the effects a row explicitly excludes. They are recorded as SOURCE-FACT, never inferred from silence.

## 2. Predictions (exact interpretation)

| # | Component | Prediction | Applicable when | VIOLATED iff |
|---|---|---|---|---|
| **P1** | record | no row amends another ruling's decision text | the row refers to another ruling | the row states that it edits, amends or replaces another ruling's decision text *in place* (not by annotation or by being a new ruling) |
| **P2** | scope | every authorization stated is scope-bounded | the row authorizes something | an authorization is stated without a bounded scope, or is stated to extend beyond it by implication |
| **P3a** | status (vocabulary) | every status named ∈ the four values | the row names a ruling status | a fifth status value is used for a ruling |
| **P3b** | status (semantics) | s1–s6 hold | the row records a status fact | e.g. a PREPARED ruling treated as governing · self-adoption · a WITHDRAWN number reused · HELD voiding the number · adoption creating delegation · decision text changed by a status act |
| **P4** | operations | every explicitly stated change lies in Frame⁺ of an operation the row performs | the row states changes | a stated change lies in no performed operation's Frame⁺ while every performed operation is in the vocabulary (otherwise NOT-MODELLED) |
| **P5** | target | evidence changes no standing/status without a separate operation | the row records evidence or counter-evidence | the row states that a standing/status changed *because of* evidence alone |

Consistency check (Frame⁺ vs Frame⁻): no row states the same effect as both performed and excluded.

## 3. Coding rules (anti-fitting)
1. **Operations** are coded **first**, from the row's named act (headline) and its explicit performative verbs. **Changes** are coded **second**, from "WHAT IT CHANGES" / Effect text. P4 compares the two. Operations are never chosen to absorb a change.
2. Result per prediction per row: **SUPPORTED** (applicable and holds) · **VIOLATED** · **UNTESTABLE** (not applicable) · **NOT-MODELLED** (operation outside the vocabulary) · **UNKNOWN** (applicable, but the needed fact is NOT-RECORDED). NOT-RECORDED never becomes ABSENT.
3. P3a occurrences are reported but **weighted as vocabulary-level only**. P3b is the substantive status test.
4. Path dependence, per row: does the row state that history alters (or does not alter) future permissions? Is the needed history a finite status/registry value?
5. **Reporting:** "k/7 within one template regime", never "k independent confirmations".
