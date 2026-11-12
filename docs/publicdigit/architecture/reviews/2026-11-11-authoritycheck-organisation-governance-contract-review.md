# AuthorityCheck: Organisation–Governance contract validation

| | |
|---|---|
| **Status** | **Review.** Records no ruling; changes no ratified text or code. Written by Claude, **not independent** (author of the Governance thin slice) |
| **Commission** | "Principal DDD Architect Instruction — Organisation–Governance AuthorityCheck Contract Validation", accepted by the Principal |
| **Sources read** | Ratified Membership architecture §72 (authority evidence) and §156 (AuthorityCheck port); ratification act §10 (:285–296); `app/Contexts/Membership/Domain/Organisational/AuthorityEvidence.php`; `…/Application/Organisational/Ports/AuthorityCheckPort.php`; `…/Infrastructure/Organisational/InterimRoleAuthority.php`; PO-ORG-MEM-01 A; D-12, D-29, D-65, D-70, D-144, D-148, D-149, D-154, D-157, D-159; Governance thin slice §3, §6.2 and correction note; integrated review Addendum A-2; model document §49.7, §50.9 |

**Labels:** [RAT] ratified · [AD] decided · [BF] fact · [AC] conclusion · [TD] tactical design · [TU] proof obligation · [OQ] Principal question.

## 1. Business explanation

When something changes in an organisation's own records (a new district, a member moved from one district to another), PublicDigit must be able to show **that the people or body behind the change were entitled to make it under the organisation's written rules, on that date.**

**Organisation asks Governance one question:**

> *Was this person, or this committee, entitled to do this act, for this unit, on this date, as far as was known at this time?*

The answer is never "PublicDigit decided". It is **"the rules say X (article, version); this person held office Y (or this committee decided at sitting Z); checked at time τ"**. Organisation stores that answer as the basis of its record.

**Five things are kept apart:**

| | Meaning | Owner |
|---|---|---|
| Authority **exists** | The rules give the competence | the organisation's constitution (outside PublicDigit) |
| Authority **is evidenced** | Rules document and decision on file | Governance (records) |
| Authority **is valid at d** | Office held, rules version in force, on that date | Governance (evaluation) |
| Authority **was checked** | Evaluated at τ, with a result | Governance (the answer) |
| **The act was performed** | The change recorded with that answer as basis | Organisation |

## 2. The authoritative contract

**[RAT] Ratified Membership architecture §72:**
- *decision provenance = actor + **authority evidence {actor, capacity, ruleRef, policyVersion, result, checkedAt}** + person declaration*;
- §156: "**Governance: the AuthorityCheck port; Membership stores evidence only**";
- ratification act §10: the aggregate holds "authority evidence relevant to Membership acts".

**[BF] Code:**
- `AuthorityEvidence` carries these six fields **plus `competenceVerified` (bool)** ("`false` marks a technical application-role check only").
- `result` is a **bool**.
- The port `AuthorityCheckPort` is **owned by Membership** (consumer-defined) and speaks Membership's language (`MembershipAct::Admit | End`).

**Scope of what is ratified:** **one consumer (Membership)** and **this evidence shape**.
- The extension to Organisation (and Communication, Deliberation) is **accepted (D-157), not ratified**.
- **This review keeps the six fields unchanged** and puts everything else in an accompanying **basis** (Addendum A-2).

## 3. Field-by-field validation (for Organisation's use)

| Field | Business meaning | Owner of the value | Kind | Time | Required | Organisation uses it to | If missing or unknown |
|---|---|---|---|---|---|---|---|
| **actor** | Who acted: a **person** (`PersonRef`) for an office holder's act, or a **committee** (organ ref) for a decision taken at a sitting | Identity issues `PersonRef`; Governance owns organ ids | reference | — | yes | name who stands behind the record | **refused** (no anonymous authority) |
| **capacity** | The office held (tenure ref), or the organ and sitting, in which the act was done | Governance | reference | **valid at the business date d** | yes | show "as district secretary" / "Central Committee, sitting of 3 May" | refused |
| **ruleRef** | The provision relied on (rules document + article) | Governance (RuleProvision; the document is the organisation's) | reference | the provision as in force at d | yes (code rejects empty) | cite the rule; let members challenge | **result = false**, reason "not established" |
| **policyVersion** | The rules-document version in force at d, as known at τ | Governance | reference | **D-12: competence on the decision date** | yes | show which version applied | result = false |
| **result** | Governance's conclusion: authorised or not | Governance | **conclusion** | for d, at τ | yes | **accept or refuse the act** | treat as false |
| **checkedAt** | When the check was made (τ) | Governance | knowledge time | τ | yes | explain later what was known then (D-73) | refused |
| *`competenceVerified`* (code only) | Whether competence was **machine-checked** against a structured grant | Governance | conclusion qualifier | — | code: yes | show "checked" versus "not machine-checked" | false |

**Assessment of the fields:**
- **None is redundant.** **None is wrongly owned**: all values are Governance's, except `PersonRef` (Identity's reference, used as is).
- **Three are insufficient for Organisation's needs, without changing them:**
  1. **`result` is two-valued.** It cannot tell **"not authorised"** (a negative determination) from **"cannot be determined"** (no designation yet, D-149 / D-154; rules missing). **The business difference matters**: the first is a refusal on the merits; the second means "waits until a committee designates".
  2. **No field states the business date evaluated.** `checkedAt` is the knowledge time; **the date d for which competence was judged** (D-12, the decision date) is absent. For a back-dated act, the evidence alone cannot show which date was assessed.
  3. **`competenceVerified` (bool) cannot express the three kinds that exist:** worked out, **asserted from a cited rule** (thin slice §3), and **interim role check**.

**Resolution [TD], keeping the six fields:** the answer carries a **basis** next to them:

| Basis item | Content |
|---|---|
| `outcome` | `Authorised` · `NotAuthorised(reason)` · **`Undetermined(reason)`** (no designation, rules not on file, Governance unable to evaluate) |
| `evaluatedFor` | the business date d (decision date, D-12) |
| `competenceKind` | `workedOut` · `asserted` · `contradiction` (D-70) · `interimRole` |
| `decisionRef` | the Resolution / sitting reference where a committee decided |
| `evidence` | fingerprints of the rules document and minutes (thin slice §3, four elements) |

**Mapping:**
- `result = (outcome = Authorised)`;
- `competenceVerified = (competenceKind = workedOut)`.

**Membership keeps receiving exactly its ratified fields** (plus its existing code field). **Organisation stores the six fields and the basis.**

## 4. Failure and uncertainty

| Situation | Outcome | Organisation does |
|---|---|---|
| Office not held on d, or term expired | `NotAuthorised(no valid tenure)` | refuse; reported |
| Rules give the competence to someone else | `NotAuthorised(competence elsewhere)` | refuse |
| **Rules silent and no designation yet** (D-149, D-154) | **`Undetermined(no designation)`** | refuse **this act**; nothing is rejected on the merits; the matter waits |
| Rules document not on file / provision not citable | `Undetermined(rules unavailable)` | refuse |
| Evidence (minutes) missing for an asserted competence | `Undetermined(evidence missing)` | refuse |
| **Conflicting provisions** (D-70) | `Authorised`, `competenceKind = contradiction`, **effective until challenged** (D-70, §33.5 scenario 6) | accept; the contradiction is visible and challengeable |
| **Governance unavailable or beyond its `closedThrough`** | no answer | **refuse** (D-73); never "assume authorised", and never record "not authorised" either |

- **A missing answer never becomes permission.**
- **"Undetermined" never becomes a constitutional prohibition.**

## 5. Context boundary

| Question | Owner | Source |
|---|---|---|
| Existence and identity of a unit | **Organisation** | D-106, D-142 |
| Committee, office, appointment, term | **Governance** | D-144 |
| Whether a body or office has competence | **Governance** (from the organisation's rules) | D-12, D-38, D-157 |
| Recording the structure that results | **Organisation** | D-29, §49.7 step 2 ("Organisation does not re-decide it") |

**Two shapes of the same evidence, by kind of act [AC]:**
1. **A committee decision** (structural change set, D-21 / D-29 R-1): Governance records the Resolution, evaluates competence and **sends the decided change set with its evidence** (push). Organisation applies it. Here `actor` is the organ and `capacity` is the sitting.
2. **An office holder's act** (a transfer by a designated office, D-154; the registrar's recording, D-148 / D-159): Organisation **asks** Governance at the moment of the act (pull). Here `actor` is the `PersonRef` and `capacity` is the tenure.

**Both produce the same six fields plus basis.**

**Avoiding a call cycle [TD]:**
- To judge "is unit U within this office's or organ's scope", Governance needs the structure, while Organisation is asking Governance.
- **Organisation sends, with its request, U's ancestor chain as at (d, τ)**, from its own authoritative structure.
- Governance does not call back. **Organisation is the owner of that fact, so supplying it is not a trust breach.**

**Relationship direction:**
- **Governance is upstream for authority** (Organisation conforms to its published evidence language).
- **Organisation is upstream for structure** (Governance consumes `StructureAsAt`).
- Different facts each way: **watched coupling, not shared ownership** (the same pattern CR-01 §6 records for Governance ⇄ Membership).

**Geography:** scopes are **units, never official references** (G-I10). That stays true when area rules arrive (round 7 precedence is also unit-scoped).

## 6. Gaps and risks (with evidence)

| # | Finding | Kind | Resolution |
|---|---|---|---|
| **G-1** | **`InterimRoleAuthority` grants admission to organisation `owner` / `admin`** [BF]. That contradicts **PO-ORG-MEM-01 A** (admin rights are not authority) and **D-149** (nobody admits until designated). It is "not bound in the container" (held) | contract / dependency | **[AC] It must never be bound for a ledger organisation (D-160).** Release 1 admission needs Governance's AuthorityCheck. **The interim adapter is a legacy-cutover artefact, not a release-1 path** |
| **G-2** | `result` two-valued | contract insufficiency | basis `outcome` (§3) |
| **G-3** | No business date in the evidence | contract insufficiency | basis `evaluatedFor` (§3) |
| **G-4** | `competenceVerified` cannot express "asserted" | contract insufficiency | basis `competenceKind`; mapping (§3) |
| **G-5** | **The registrar's recording (D-148, D-159) has no act kind** in the thin slice | tactical gap | add **`record-from-evidence(kind)`**: actor = registrar `PersonRef`, capacity = registrar tenure, ruleRef = the founding decision's basis, `competenceKind = asserted` |
| **G-6** | **Admission naming a unit (D-154):** the unit is Organisation's fact, the admission Membership's (§50.9 [TU] gap) | contract | **one authority answer** for the admission act, reused as basis for Organisation's placement record, **with the same act reference**. No second check |
| **G-7** | A potential synchronous cycle Organisation ↔ Governance | tactical | the caller supplies the ancestor chain (§5) |

**No gap in strategic domain understanding, and no missing context.**

## 7. Principal decisions

**None.**
- G-1 follows from PO-ORG-MEM-01, D-149 and D-160.
- G-2 … G-7 are tactical, inside D-157.

## 8. Recommendation and verdict

**READY AFTER SPECIFIC CORRECTIONS:** add to the Governance thin slice (correction note, no change of meaning to D-157):
- the basis items `outcome`, `evaluatedFor`, `competenceKind`, `decisionRef`, `evidence`, with the mapping to the six fields;
- the push and pull shapes;
- the caller-supplied ancestor chain;
- the act kind `record-from-evidence`;
- one answer reused for admission and placement;
- **the exclusion of the interim role adapter for ledger organisations.**

**Next:** with this contract, **every release-1 cross-context contract has been checked field by field:**
- CR-01/CR-02 (D-161, D-162);
- AuthorityCheck (this review);
- the Organisation queries named in the D-158 design.

The architecture's next step is the **whole-baseline assessment** the Principal DDD Architect named: ownership, relationships, dependencies and remaining unknowns, in one place. It should be short, since §3, §4 and Addendum A of the integrated review already hold most of it.
