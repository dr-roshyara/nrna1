# PKS — **Repository-Scope Determination: Authority Commission**

| | |
|---|---|
| **Act** | **COMMISSION of an Authority determination** (Authority instruction, 2026-07-31 — priority ⭐1) |
| **⚠️ THE COMMISSION'S SHAPE IS NOT WHAT THE QUESTION APPEARED TO BE** | **Evidence verification, performed before drafting, establishes that a GOVERNED STANDARD ALREADY ADDRESSES REPOSITORY SCOPE. This is therefore NOT a greenfield determination — see §1** |
| **Status** | 🏁 **EXECUTED 2026-07-31 — OUTCOME B. Record: `PKS_Repository_Scope_Determination.md`.** *A fresh scope determination is NOT the required act; the operative act is ES-005's ADOPTION. Nothing amended, no cleanup authorized, no file moved.* |

---

## 1. ⛔ EVIDENCE VERIFICATION — the premise of the original framing does not hold

**The repository-scope claim entered the record through the AFV-F4 commission (2026-07-31), which asserted: *"PublicDigit and PKS are the same project. The repository is the PKS domain"*, with `./docs/` · `./engineering/` · `./app/` · `./.claude/` in scope and **`./architecture/` excluded as legacy brainstorming, ungoverned**. Four verified facts bear on it:**

| # | Verified fact | Consequence |
|---|---|---|
| **1** | ⛔ **`engineering/governance/ES-005-Repository.md` EXISTS and its §ES-005.1 — *The Three-Concern Separation (EM-001, **ARB 2026-07-10**)* — reads: `docs/` + **`architecture/`** + `app/` + `tests/` = **Product** (what PublicDigit is) · `engineering/` = **Engineering*** | ***`architecture/` is explicitly INSIDE the Product concern under an ARB-ruled rule. The exclusion claim CONTRADICTS it*** |
| **2** | **ES-005 declares it *"supersedes README/MEMORY as the rule homes for the folder rule and placement litmus"*** | **A rule home already exists. A determination made without citing it would amend it by implication** |
| **3** | **`.claude/CLAUDE.md` states: *"Legacy folders during transition: `architecture/` = **Think**, `docs/` = Official Truth, `developer_guide/` = Build"*** | ***`architecture/` is assigned a ROLE, not an exclusion. "Legacy during transition" ≠ "ungoverned"*** |
| **4** | **The repository has at least twelve top-level roots** — `app` · `architecture` · `bin` · `bootstrap` · `business_analysis` · `claude` · `config` · `database` · `developer_guide` · `developer_issues` · `docs` · `engineering` — and **`architecture/` alone contains 465 markdown files** | **The four-root framing omits at least seven roots and characterizes a 465-file tree as residue** |

> ### ***The AFV-F4 commission's repository claim contradicts an ARB-ruled standard, omits most of the repository, and mischaracterizes a governed folder's status. The earlier refusal to act on it is vindicated: had the disposition rested on that framing, it would have amended ES-005.1 by implication.***

---

## 2. What this commission therefore asks — **ES-005.1's standing, FIRST**

**Binding first task, before any scope question is answered:**

> ### **Does ES-005.1 GOVERN repository scope today?**

| Fact the assessment must reconcile | |
|---|---|
| **ES-005's document status is *PROPOSED*** | *not adopted* |
| **But ES-005.1 cites *"(EM-001, ARB 2026-07-10)"*** | *the RULE carries an ARB ruling even where the DOCUMENT is proposed* |

**Three outcomes, and only after this is settled does anything else follow:**

| | If | Then |
|---|---|---|
| **A** | **ES-005.1 governs** | **Repository scope is ALREADY DETERMINED. No new determination is required — and the AFV-F4 framing was simply wrong.** *The remaining act is at most a clarification citing ES-005.1* |
| **B** | **The rule is ARB-ruled but its document is not adopted** | **The question is ES-005's ADOPTION, not a fresh scope determination** — *a different act with a different path* |
| **C** | **Neither governs** | **Then and only then is a fresh Authority determination in scope** |

***The ordering is the commission's binding constraint: a determination made before settling ES-005.1's standing would either duplicate a governed rule or amend it silently.***

---

## 3. Constraints

| | |
|---|---|
| **No embedded factual assertions** | ***This commission asserts no repository facts as premises. §1's four facts are cited with their sources so each can be re-verified, and the assessment may overturn any of them*** |
| **⛔ Must NOT retro-ground AFV-F4** | **AFV-F4 was disposed ACCEPT on *"the contingency the model already states."* A repository-scope determination must NOT be read as re-justifying it on new grounds** — ***that would be a RATIONALE change to a discharged act, which is itself a governance change requiring its own authority*** |
| **⛔ No cleanup authorized** | **The AFV-F4 commission's proposed cleanup — move documents out of `./architecture/`, archive the tree — is NOT authorized here and would, on fact 1, potentially VIOLATE ES-005.1** |
| **Promoted artifacts** | **AD-1 · C4-1 · AFV-1 · M7 · M8 · RET-1 are PROMOTED. Any consequence reaching their content requires a change-control act, not this determination** |
| **Asymmetry — to be VERIFIED, not asserted** | *The Authority should consider whether determining scope broadly or narrowly has the practical effect of deciding matters reserved elsewhere (OQ-PKS-7 among them). **If so, that consequence must be stated explicitly in the determination.*** |

---

## 4. Deliverables

1. **ES-005.1's standing** — outcome A, B or C, with evidence.
2. **Only if C:** the determination itself, with **what is decided** and **what is NOT decided** stated separately.
3. **A statement on every top-level root**, or an explicit declaration that the determination is scoped to fewer — ***the four-root framing must not be inherited silently.***
4. **The relationship to `.claude/CLAUDE.md`'s "Think/Official Truth/Build" roles** — consistent, superseded, or unaddressed.
5. **Explicit non-decisions:** OQ-PKS-7 · CBC-3 status · promotion state · AFV-F4's rationale · any cleanup.

## 5. Out of scope

**Amending ES-005 · adopting ES-005 · moving or archiving any file · editing any promoted artifact · deciding OQ-PKS-7 · raising methodology candidates.**

**No further planning or verification layer is required, and none should be created.**

---

*Traceability: **Repository-Scope Determination COMMISSIONED** (Authority instruction, 2026-07-31, priority ⭐1) · **⛔ evidence verification performed BEFORE drafting establishes that the original framing's premise does NOT hold: ES-005.1 — The Three-Concern Separation (EM-001, ARB 2026-07-10) — places `architecture/` explicitly INSIDE the Product concern, ES-005 declares itself the superseding rule home for the folder rule, `.claude/CLAUDE.md` assigns `architecture/` the "Think" ROLE rather than excluding it, and the repository has ≥12 top-level roots while `architecture/` alone holds 465 markdown files** · ***the AFV-F4 commission's repository claim contradicts an ARB-ruled standard, omits most of the repository, and mischaracterizes a governed folder — the earlier refusal to act on it is vindicated, since a disposition resting on that framing would have amended ES-005.1 by implication*** · **BINDING FIRST TASK: settle whether ES-005.1 GOVERNS, with three outcomes (already determined / the question is ES-005's adoption / a fresh determination is in scope) — *a determination made before settling this would either duplicate a governed rule or amend it silently*** · **constraints: no embedded factual assertions; must NOT retro-ground AFV-F4 (a rationale change to a discharged act); NO cleanup authorized and the proposed cleanup would potentially violate ES-005.1; promoted artifacts reachable only by change control; asymmetry to be VERIFIED not asserted** · five deliverables including **a statement on every top-level root so the four-root framing is not inherited silently** · **NOT EXECUTED.***
