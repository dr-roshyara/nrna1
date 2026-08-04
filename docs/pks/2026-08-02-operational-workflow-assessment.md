# CAP-001 — Operational Workflow Assessment

| | |
|---|---|
| **Kind** | **OPERATIONAL WORKFLOW ASSESSMENT.** ***Describes the existing process. Proposes no automation. Extends no capability.*** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | PA, 2026-08-02 — *"understand how CAP-001 naturally fits into the engineering lifecycle… study the workflow, do not redesign the capability"* |
| **Placement** | ⭐ **DERIVED** — resolver → `docs/pks` (exit 0) |
| **Scope executed** | Steps 1 · 2 · 4 (measurable part) · 6. ⛔ **Steps 3 and 5 require elapsed use and are reported at n=0** |

---

## 1. Current identifier minting workflow

**Observed, not proposed.** Most governed artifacts are **date-named**; only three kinds carry a minted identifier.

| Artifact | Naming | Mints an identifier? |
|---|---|---|
| Verification reports | `YYYY-MM-DD-<subject>.md` | ⛔ no |
| Commissions | `YYYY-MM-DD-<subject>-commission.md` | ⛔ no |
| Plans | `YYYYMMDD-HHMM-<what>-plan.md` (ES-004.2) | ⛔ no |
| **Rulings** | row in the rulings register | ✅ **`R-nn`** |
| **PMRs** | row in the PMR register | ✅ **`PMR-nn`** |
| **ADRs** | `ADR-<CLASS>-nn` filenames | ✅ (four sub-series) |

**The observed sequence for a ruling — the dominant minting path:**

```
1  a commission or package is prepared            date-named · no identifier
2  ⚠️ an identifier may be RESERVED in that text  ← THE IDENTIFIER ENTERS CIRCULATION HERE
3  the ARB decides
4  a record is written                            date-named · no identifier
5  ⭐ a row is APPENDED to the register           ← THE MINTING ACT
6  committed as  docs(governance): R-nn — <subject>
```

**⛔ Confirmed absent:** no identifier allocation service · no template · no generator · no hook · no gate. `grep` over `scripts/`, `.claude/scripts/` and `composer.json` returns nothing referencing identifiers except CAP-001 itself.

> ### **Identifier allocation is a manual human activity, performed by editing markdown.**

### 1.1 ⭐ The finding that matters most — there are TWO moments, not one

**Step 2 and Step 5 are different events, separated by days.**

**Evidence:** *"Identifier: **not assigned here. R-67 was reserved for WP-4 authorization**, and authorization is moot. Rulings are not minted by inference (R-34)."*

> ### **The citation hazard is created at RESERVATION. The collision is realized at MINTING.**
>
> **PMR-10 says "checked before it is minted" — but by mint time the identifier has already been cited elsewhere.** *That is exactly how R-65..R-71 became citable-but-unminted.*

## 2. Natural integration points — identified, not decided

| Point | Friction | Satisfies PMR-10? | Note |
|---|---|---|---|
| **Before drafting** | low | ⚠️ partial | too early — the subject is not yet known |
| ⭐ **At reservation** *(step 2)* | low | ✅ **and catches the hazard at its source** | **the earliest point that prevents the citation problem** |
| ⭐ **Immediately before the register append** *(step 5)* | low | ✅ **exactly what the rule says** | **the literal reading of PMR-10** |
| Before commit *(hook)* | medium | ✅ | catches it, but after the text is written |
| During review | high | ✅ | too late — the identifier is already circulating |
| At merge / CI | medium | ✅ | latest possible; a gate, not a guide |

**Observation, not a decision:** *the two lowest-friction points are **reservation** and **register append**. They are also the two moments a human is already thinking about the identifier* — so the check adds no new context switch. **No point is selected here; Step 5 of the commission reserves that for evidence.**

## 3. Observed usage during real PublicDigit work

> ### ⛔ **Tool executions during real minting: ZERO.**

| Date | Task | Identifier | Verdict available | Tool run? | Outcome |
|---|---|---|---|---|---|
| 2026-08-02 | Capability catalog labels | 3 of 8 collided | — | ⛔ manual grep | ✅ **2 renamed, 1 qualified** |
| 2026-08-02 | WP-3A acceptance | `R-67` | would have been WARN | ⛔ manual | ✅ **reservation disposed, reuse correct** |
| 2026-08-02 | WP-4 subdivision | **`R-68`** | ⭐ **WARN — already recorded in the baseline** | ⛔ **NO** | ⚠️ **COLLISION OCCURRED** |
| 2026-08-02 | WP-4A acceptance | **`R-69`** | ⭐ **WARN — already recorded in the baseline** | ⛔ **NO** | ⚠️ **COLLISION OCCURRED** |

**The last two rows are the assessment's principal evidence, and the claim is bounded:**

| Established | Not established |
|---|---|
| The baseline recorded `R-68`/`R-69` as **cited-but-unminted** hours before they were minted for different subjects | **who** minted them, or whether the reuse was a considered judgement |
| The old senses (*ES-005 resolves by rule* · *R-39 is the precedent*) remain live in **two pre-existing governance records** not authored by this assessment | that the old senses were still intended to be authoritative — they were *approved in substance, unminted* |
| ⛔ **No disposition was recorded**, unlike `R-67`'s explicit *"reserved… moot"* | that a disposition did not occur privately |

> ### ⭐ **This is the counterfactual TESTED rather than reconstructed.** The tool's own prior output flagged both identifiers before the minting. **It was not consulted.**

## 4. Workflow fit — measured where measurable

| Dimension | Measurement |
|---|---|
| **Execution time** | **~0.4 s** single check · ~25 s full series audit *(corpus-wide citation scan)* |
| **Interruption cost** | **low** — one command, no context switch; the human is already at a terminal committing |
| **Discoverability** | ⛔ **NIL.** Nothing announces the tool. It is reachable only by prior knowledge |
| **Output comprehensibility** | verdict + evidence sentence; ⚠️ **one defect found and fixed — see §6** |
| **Cognitive load** | ⏳ **not measurable at n=0** |
| **Usefulness in flow** | ⏳ **not measurable at n=0** |

## 5. Evidence of behavioural change

> ### ⛔ **NONE. The tool has changed no engineering behaviour.**

**What *has* changed behaviour is the RULE, applied manually — and inconsistently:**

| | |
|---|---|
| `R-67` | reuse **explicitly disposed** and recorded |
| `R-68` · `R-69` | reuse **not disposed**, same class of act, **same day** |

> ***Two standards for one act within a single day is itself the evidence: manual discipline is not uniformly applied, even by people who demonstrably know the rule.***

## 6. Bug fixes applied *(Step 6 — no scope change)*

| Defect | Fix |
|---|---|
| **WARN prescribed *"choose another"*** — wrong advice when a reservation has lapsed, which `R-67` proves is a real and correct case | evidence text now names **both readings** and states *"a human must dispose it — the tool cannot tell which"*. **The tool reports the hazard; it does not prescribe the action** |

*Found from real repository behaviour, not from review. 36 tests remain green.*

## 7. Recommendation

**On whether manual execution is sufficient:**

> ### ⛔ **The evidence available today says NO — and it is stronger than expected this early.**
>
> **Manual adoption was available and did not occur, in the same day, for two identifiers the tool had already flagged.** *This is not a prediction of future friction; it is an observed failure.*

**But the recommendation is deliberately narrower than that evidence would license:**

| # | Recommendation |
|---|---|
| **1** | ⛔ **Do NOT add a hook, gate or CI step yet.** *n=2 collisions, one day, one operator.* The commission's own bar is *"sustained friction"*, and one day is not sustained |
| **2** | ✅ **Continue manual use and keep the log.** If the log shows further zero-execution mintings over the coming weeks, the case becomes evidence rather than an incident |
| **3** | ⭐ **When integration is considered, weigh RESERVATION (§1.1) alongside mint-time.** *A mint-time check would not have prevented the R-65..R-71 citation hazard, because those identifiers were already circulating* |
| **4** | ⚠️ **Raise the R-68/R-69 disposition with the Authority.** The old senses remain live in two governance records with no recorded release. **Renaming is forbidden; annotation is the available instrument.** ⛔ **Not this capability's act** |
| **5** | ⛔ **No CAP-002.** Zero recorded executions of CAP-001 |

---

*Traceability: PA operational-validation commission 2026-08-02 · Steps 1, 2, 4 (measurable), 6 executed; **Steps 3 and 5 reported at n=0 rather than simulated** · workflow modelled from artifact naming, register history and commit messages · **§1.1 (reservation vs minting) and §3 (R-68/R-69) are new findings from real repository behaviour** · one bug fixed, no scope added · **no automation proposed · no capability extended · no CAP-002 work.***

> **⛔ CAP-001 remains frozen except for bug fixes.**
