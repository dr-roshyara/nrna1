# Engineering Translation — Register-Discipline Validator (Stage 1A.5)

| | |
|---|---|
| **Kind** | **ENGINEERING TRANSLATION** — converts the strategic constraints validated in Stage 1A into implementable guidance for **one** capability. ***No code written. No component created. No engineering authorized.*** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Status** | **DELIVERED.** Stage 1A.5, inserted at PA direction (2026-08-02): *"AD-1 is strategic… those are implementation design questions… they should not be answered directly from Strategic Discovery"* |
| **Input** | `docs/pks/2026-08-02-pks-architecture-validation-report.md` (Stage 1A) — its **twelve implementation constraints** are the binding input |
| **Placement** | ⭐ **DERIVED** — `php scripts/doc-placement.php --scope=product-specific --domain=pks --maturity=qualified` → `docs/pks` (exit 0) |
| **Label check** | **PMR-10 performed:** `Engineering Translation` · `Stage 1A.5` · `Register-Discipline Validator` — **all verified unused** |

---

## 0. ⚠️ Parsimony note — this step's scope is already owned

**The label is free, but the SCOPE is not new.** **ADR-PKS-001:** *"**Phase II.D (Implementation) is where implementation-specific guidance belongs** — prompt governance, tool contracts, service decomposition, technology selection."* Its five questions are exactly the ones this document answers.

> ### **Recommendation: record this as PHASE II.D's FIRST SLICE, not as a new sibling phase.** *ES-001.1: "which existing concept owns this?" — **Phase II.D does.** Minting a parallel phase would grow the phase set faster than the software.*

⚠️ **Phase II.D is otherwise UNSTARTED — no Phase II.D artifact exists.** *This is therefore the first entry in it, and it decides the paradigm for **one capability only**, not for PKS.*

---

## 1. The five commissioned questions, answered from constraints

### Q1 — Which bounded context owns execution?

> ### ⛔ **NONE. And that is a derived answer, not an evasion.**

| Step | |
|---|---|
| **PKS is not software** | *"The PKS is software — ❌ No: it is knowledge specifications (YAML + Markdown)"* |
| **A validator IS software** | therefore it is **not a member of PKS** |
| **AD-1's two components are LOGICAL and deployment-neutral** | *"no statement below constrains or presumes any infrastructure, runtime, protocol, or technology"* — **so AC-1 is not a code location and cannot own a file** |
| **House precedent is 4-for-4** | `doc-placement.php` · `link-check.php` · `knowledge-lint.php` · `knowledge-graph.php` all live in **`scripts/`** — **none inside `app/Contexts/`** |

**Consequence:** the validator **REALIZES an AC-1-shaped responsibility** *(record evidence → evaluate against criteria → issue a verdict)* **without being inside AC-1, and without being inside any bounded context.** ⛔ **Do not place it in `app/Contexts/`** — that would assert a context membership the strategic model does not grant.

### Q2 — Where does the validator execute?

**Constraint:** AC-1 **cannot self-issue**. ⚠️ **But AD-1 Q-7 records that WHERE the trigger lives is NOT DETERMINED** — so *"a hook is architecturally correct"* is **not** derivable, and Stage 1A already struck that claim (F-3).

| What IS derived | Any **externally triggered** invocation satisfies non-self-issuance. ⛔ **A resident daemon or self-scheduling process does not** |
| What is NOT derived | **Which** external trigger. **CLI · merge-gate · PreToolUse hook are all lawful; the choice is an ENGINEERING decision, recorded as such** |

**Recommendation (engineering, not architecture): CLI first**, matching the 4-for-4 precedent, with a merge-gate entry once it has run for a while. **A hook is optional and should not be presented as required.**

### Q3 — What classes implement validation?

> ⛔ **None. There should be no classes.**

**AD-1 is `Boundary Fidelity`-gated: *zero aggregates, entities, value objects, repositories, APIs, schemas, deployment units, or technologies*.** More decisively, the **house pattern is already established four times** and each script states it in its own header:

```
Behaviour ONLY in the script  ·  POLICY in an ADR  ·  CONFIGURATION in schema/*.yaml
```

**So: one PHP script, ~150–250 lines, no framework, no DI, no classes beyond what PHP requires.** *A tactical DDD structure here would be the elegant-partition error in code form — inventing internal structure that AD-1 declines to define.*

### Q4 — How is evidence collected?

**Shape already in use in `docs/pks/`:** `Observation → Evidence (with method) → Classification → Recommendation → Reproduction command`.

**Two metrics, and only two** *(Phase III's own questions)*: **did the check fire?** · **did it change what engineering did?**

⛔ **Not a metric:** number of identifiers scanned. *"One corpus is one observation" applies — volume is not evidence.*

### Q5 — Which layer invokes validation?

**No application layer. It is repository tooling, outside `app/`.** Invoked by **the author before minting** (the act PMR-10 governs) and, once stable, by **`composer merge-gate`**.

---

## 2. The design, derived constraint-by-constraint

| # | Constraint (Stage 1A §1.5) | Design consequence |
|---|---|---|
| **1** | **AP-4** — no global identifier scheme; collisions prevented by **register discipline** | ⛔ **NO registry file is created.** The validator **reads the existing governed registers** as its source. ***It introduces no new source of truth*** |
| **2** | **DR-1** — nothing may consume a projection as evidence | ⛔ **No cached index.** Every run reads the registers themselves. *If an index is ever added it is a projection: regenerable, never consulted for a decision* |
| **3** | **DR-2** — source governs on conflict | the register wins; any derived listing is corrected |
| **5** | **DR-4** — criteria are read-only | ⛔ **The validator may not define, amend or version a numbering scheme.** **M4 supplies the criteria** *(register = namespace unit; identity modes 1/2/3)* |
| **8** | **AP-8 / DR-8** — closed verdict vocabulary | see §3 |
| **9** | **AC-1 non-self-issuance**; Q-7 open | externally triggered only |
| **12** | Medium-High ceiling, one corpus | claims stay bounded; **no promotion to KnowledgeOS from this alone** |
| — | **M4** — *"no new numbering scheme, no renumbering, no remediation of the named collision liabilities"* | ⛔ **The validator PREVENTS; it never REPAIRS.** *PMR-10's own adoption note: "the two existing collisions cannot be cured — adoption prevents the third"* |
| — | **AR-2** — no component may be defined over it | ⛔ **The validator models IDENTIFIERS and REGISTERS only. It must not model Decision · Term · Model element · Contract** |

---

## 3. The verdict vocabulary — mapped, not invented

**AP-8's closed set is binding. Stage 1A finding F-2 struck the earlier ad-hoc set.**

| Situation | Verdict | Why |
|---|---|---|
| identifier free in its register | **PASS** | |
| identifier already minted in that register | **FAIL** | a collision — the defect PMR-10 exists to prevent |
| identifier cited but **unminted** *(the live R-65..R-71 case)* | **WARN** | not yet a collision; **it is a collision hazard** |
| series is **not a governed register** *(the bare-ADR liability M4 names)* | **INCONCLUSIVE** | *the criteria do not cover it — the validator must not invent coverage* |
| cross-kind reuse *(the `R-nn` Risk/Ruling case; the `C-n` case)* | **WARN** | M4: *"the collision bites only when citations omit register context"* |

⛔ **`PASS AFTER CORRECTION`, `EMERGENT` and `CERTIFIED` are not emitted by this validator** — they belong to review and certification acts, not to a mechanical check. *Recorded so the omission is not read as an oversight.*

---

## 4. Scope — and the four things it must not become

**IN:** read the governed registers · answer *"is `<id>` free in `<register>`?"* · emit one verdict from §3 · report the R-65..R-71 and C-1..C-4 hazards · a reproduction command.

⛔ **OUT — each with its blocking rule:** a central registry **(AP-4)** · renumbering or repair **(M4)** · a new numbering scheme **(DR-4)** · a bounded context, component or capability boundary named *"Identifier …"* **(AD-1 §6.2 · Q-2 · M6 §7.5 trigger unmet)**.

> ### **The line: this delivers an ENGINEERING capability called *identifier validation*. It does NOT create a STRATEGIC boundary called *Identifier Capability*.** *(PA, 2026-08-02 — the distinction Stage 1A §1.4 now records.)*

---

## 5. Open items this translation could NOT resolve

| # | Item | Owner |
|---|---|---|
| **1** | **Which registers are governed registers?** *M4 names the liability (bare, unregistered series) but no canonical list exists* — ⚠️ **the validator's criteria are therefore incomplete on day one** | **Authority** *(a UL/register ruling — also M6 §7.5's reopening trigger)* |
| **2** | **Where the issuance trigger belongs** | **Authority — Q-7** |
| **3** | **Whether IBC-1's absence blocks Stage 1B** | **Authority — Stage 1A §3** |
| **4** | **R-65..R-71 allocation** | **ARB — 7C decision C-4** |

> ⚠️ **Item 1 is the honest gate.** *A validator whose criteria are incomplete will emit **INCONCLUSIVE** for every unregistered series — **which is correct behaviour, and also the evidence that the register ruling is needed.*** **That is a reason to build it, not a reason to wait — but it must be stated before, not discovered after.**

---

## 6. Definition of Done

Plan approved (**EP-01**) · RED before GREEN · verdicts from §3 only · **no new source of truth** · reproduction command recorded · one PKS observation in the §1-Q4 shape · developer guide · `composer merge-gate` green · **the four OUT items verifiably absent**.

---

*Traceability: PA direction 2026-08-02 (insert Engineering Translation before coding) · derives solely from the Stage 1A validation report's twelve constraints, AD-1, M4 and the four-fold house precedent · **placement DERIVED** (`doc-placement.php`, exit 0) · **PMR-10 collision check performed on three new labels, all free** · parsimony note recommends recording this as **Phase II.D's first slice** rather than a new phase · **no code, no component, no boundary, no authorization.***

> **Stage 1A.5 complete. Engineering has implementable guidance that is faithful to the strategic model. Stage 1B remains unauthorized.**
