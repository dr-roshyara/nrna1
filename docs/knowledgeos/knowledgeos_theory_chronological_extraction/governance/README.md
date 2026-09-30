# Temporary KnowledgeOS Research Governance

| | |
|---|---|
| **What this is** | ⚠️ **a temporary control mechanism for ONE research programme** — the KnowledgeOS theory reconstruction in this workspace |
| ⛔ **What this is NOT** | platform governance · a registry asset · general research governance · a permanent KnowledgeOS capability |
| **Lifetime** | ends with the research programme. ⭐ **Discarding it is the default outcome**; retention requires evidence that a part of it earned its place |
| **Authority it creates** | ⛔ **none.** It measures and it reports. It cannot accept work |

---

## 1 · Why temporary, and why that matters

⭐ **The platform's own registry already answers the question this mechanism is standing in for:**

```yaml
- id: CMP-006
  name: review_engine
  status: deferred    # reviews are human; automation waits for
                      # evidenced recurring need
```

⛔ **The review engine was deliberately not built.** Building a permanent governance capability here would quietly overturn that decision on no evidence. **So this is explicitly a research-scoped stand-in**, and the research itself is what will produce evidence about which parts, if any, deserve to be permanent.

> ⭐ **research discovery ≠ final theory ≠ final architecture.** The same rule applies to the scaffolding.

## 2 · The parts, and what each is allowed to claim

```
  SESSION START
       │
       ▼
  governance-preflight.sh ──── the DOOR. Holds no rules
       │
       ▼
  gates.yaml ───────────────── the RULE BOOK. Holds no logic
       │
       ▼
  gate-runner.py ───────────── the INSTRUMENT. Measures, never interprets
       │
   ┌───┴────┐
   ▼        ▼
 CLEAR    BLOCK
   │        │
   ▼        ▼
research  STOP
```

| Part | May claim |
|---|---|
| `gates.yaml` | *"this is a rule, and this is its status"* |
| `governance-state.yaml` | ⭐ *"a human activated this"* — **the only place activation exists** |
| `gate-runner.py` | *"this precondition held / did not hold"* |
| `governance-preflight.sh` | *"the applicable activated preconditions passed / did not"* |
| ⛔ **none of them** | *"the research is valid"* · *"the theory is right"* |

## 3 · ⛔ What a verdict does and does not mean

| Verdict | Means | ⛔ Does NOT mean |
|---|---|---|
| **PASS** | the applicable automated precondition passed | the research is valid, or the protocol was followed |
| **FAIL** | the measured precondition did not hold | the theory is wrong |
| **BLOCK** | the session may not proceed **under the currently activated rules** | the theory is wrong |
| **REVIEW_REQUIRED** | a reviewer must judge this | anything about the outcome |
| **NOT_ACTIVATED** | defined and eligible; **no human has activated it** | it passed |
| **ERROR** | could not be evaluated | it passed |
| **INCONCLUSIVE** *(extended 1a, IC-1)* | a required input is **missing or empty**, so nothing was examined. **Blocks like FAIL** on an activated tier-A gate | it passed; it failed |
| **GOVERNANCE_INOPERATIVE** *(run result, exit 3)* | **no verdict of this run can be trusted**: unreadable control surface · duplicate or schema-invalid gate · self-test not 100% · an activated gate that is not `active`, or is REVIEW/HUMAN · an unpinned activation or a pin mismatch | BLOCK; CLEAR. **The door exits 3, never 2 and never 0** |

⭐ **This preserves the distinction the programme already relies on: process conformance is not research outcome.**

## 4 · ⭐ Activation is an act, not an edit

A gate blocks only when **both** hold:

1. `gates.yaml` gives it `status: active` — **Governance proposes**
2. its id appears in `governance-state.yaml` — ⭐ **the human activates**

> ### ⛔ **The mechanism ships INERT. `activated: []`. Nothing blocks until a human puts ids there.**

**That is deliberate and should not be "fixed" by the research session.** An author who activates the gates that judge their own work has produced an approval, not an acceptance (`KOS-G-060`).

⭐ **Measurement does not wait for activation.** The runner reports verdicts with nothing activated, and `--self-test` proves the instrument works. **Activation governs blocking, not measuring.**

### ⭐ Activation pins the gate definition (L0-DEC-16, extended 1a)

An activation entry must carry the **pin** of the exact gate definition it activates:

```yaml
activated:
  - {id: KOS-G-001, pin: "<16 hex>", by: <human>, date: 2026-09-23}
```

- **pin** = sha256 over the canonical JSON of the parsed gate mapping (sorted keys, compact separators), first 16 hex. **Any change to any field of the gate changes it.**
- `python3 governance/gate-runner.py --pin-lines` **prints** these lines for the current activations (or `--pin-lines KOS-G-001,KOS-G-003`). ⛔ **Printing is not activating: the human pastes them.**
- **A bare id, a wrong pin, or a gate edited after activation ⇒ `GOVERNANCE_INOPERATIVE`** until the human re-activates. The pin detects **definition drift**. ⚠️ It does **not** authenticate who pasted it.
- ⛔ **REVIEW and HUMAN gates may not be activated** (L0-DEC-14) until a completion-record mechanism exists. Activating one ⇒ `GOVERNANCE_INOPERATIVE`.

## 5 · ⛔ What this mechanism IS — the name matters

> ### ⚠️ **A controlled research-governance mechanism with human activation and visible tampering.**
>
> ### ⛔ **NOT an independent governance boundary. NOT a trusted security boundary.**

**That distinction is load-bearing and must not erode.** The mechanism is **tamper-EVIDENT, not tamper-PROOF**, and the limitation is *accepted* for this temporary programme rather than solved.

⛔ **Nobody may describe this as an independent control**, in a report, a commit message, or a status line. It is a control whose subject built it.

### The separation defect, stated rather than papered over

> ⛔ **Research Claude built this. Research Claude is governed by it.** That is the exact conflict the mechanism exists to manage, and ⛔ **it cannot be resolved by the mechanism.**

**The standing rule:** Research Claude may **read** these files. It must **not** add entries to `governance-state.yaml`, change a `status` in `gates.yaml`, or modify the hook. Governance modifies; **human approval activates.**

⚠️ **A rule is not a wall, so the runner makes breach VISIBLE instead:** `--json` emits a `sha256` of every governance file. A changed digest with no corresponding human record is evidence, examinable after the fact.

⭐ **This is the platform's own stance, not an invention** — `AST-015` records the same choice: *"an illegal write is a REFUSED transition… the fold refuses; nothing is physically prevented."* **Advisory record, visible violations, no enforcement theatre.**

## 5A · ⛔ `NO_ACTIVE_GOVERNANCE_CONTROLS` is not a kind of `CLEAR`

With `activated: []` **no control is exercised, so nothing is cleared.** The runner and the door therefore report:

```
STATUS: NO_ACTIVE_GOVERNANCE_CONTROLS

Research may continue because NO ACTIVATED CONTROL WAS EXERCISED
(none is activated, or none applies to the requested stage/timing).

THIS IS NOT GOVERNANCE APPROVAL.
No gate was exercised. Nothing here was accepted, reviewed or cleared.
```

⛔ **The word `CLEAR` does not appear in that output, by design.** A reader skimming a log for it would otherwise take **absence of control** for **approval** — and that misreading gets easier, not harder, the longer the mechanism runs quietly.

⭐ **`CLEAR` is reserved for the case where an activated gate was actually exercised and passed**, and even then it claims **process conformance only**.

## 5B · ⭐ Phased activation — do NOT activate all 24

⛔ **Activating the whole catalogue at once is the most likely way this mechanism gets switched off in frustration.** Start with a small set of genuinely deterministic gates, run the research against them, then add another group.

**Recommended first group — 5 gates, all `AUT`, all cheap and stable:**

| | | |
|---|---|---|
| `KOS-G-001` | artifacts-parse | precondition for every other verdict |
| `KOS-G-002` | no-duplicate-ids | pure integrity |
| `KOS-G-003` | no-dangling-references | ⭐ has caught real defects in **two consecutive batches** |
| `KOS-G-010` | theory-discovery-index-coverage | built from the costliest recorded error |
| `KOS-G-022` | theory-document-completeness | ⭐ a set difference; caught **3 silently dropped items** |

⛔ **This is a recommendation, not an activation.** The list sits here; ⭐ **the act happens in `governance-state.yaml`, and it is the human's.**

⚠️ **Deliberately not in the first group:** `KOS-G-027` (*gaps-dispositioned*) — ⛔ its check is weaker than its stated rule (`B-8`), and activating a gate whose implementation under-tests its rule would bank a false pass.

## 5C · ⚠️ The door is not yet automatic — one step remains

⛔ **`governance-preflight.sh` is wired into no `settings.json`.** Nothing calls it automatically; it runs when invoked.

**That is the current, honest state:**

```
Session starts
     │
     ▼
Research Claude
     │
     ├── CAN run governance-preflight manually
     └── ⛔ nothing forces it
```

⭐ **This is deliberate and should stay this way until reviewed:** wiring it would change **project-root configuration**, which is a human act, and a research session silently editing the hook configuration that governs it is precisely the conflict §5 describes.

> ⛔ **So the requirement *"every research session must call Governance at start"* is NOT yet implemented.** It is one step, and **that step is not mine to take.**

## 6 · Using it

```bash
# the door, at session entry or a checkpoint
.claude/hooks/governance-preflight.sh                    # everything applicable
.claude/hooks/governance-preflight.sh PHASE_2 CHECKPOINT # narrowed

# the instrument, directly
python3 governance/gate-runner.py                 # verdicts; exit 1 if blocking
python3 governance/gate-runner.py --json          # machine-readable + digests
python3 governance/gate-runner.py --self-test     # prove the instrument measures
python3 governance/gate-runner.py --list-eligible # ids a human could activate
python3 governance/gate-runner.py --pin-lines     # activation lines WITH pins, for the human to paste

# the regression battery (governance-owned; research data from git archive)
python3 governance/regression/run_regression.py              # control plane from the working tree
python3 governance/regression/run_regression.py --control rev --rev <commit>
```

⭐ **`--self-test` is the honest starting point:** it runs every implemented check against a passing and a failing fixture, plus the **missing / empty / near-miss** cases in `fixtures/cases/<check>/<case>/EXPECT.json`, and confirms each returns the right verdict. **It proves the instrument works while claiming nothing whatever about the research.** ⭐ **Since extended 1a, every evaluation runs the self-test first; anything below 100% ⇒ `GOVERNANCE_INOPERATIVE`.**

**Also INOPERATIVE since L0-DEC-21:** activating a **tier-B** gate (IR-G1: an advisory gate never blocks, so its failure would read as CLEAR), an activated gate whose **check is not implemented** (IR-G4), and a `--stage`/`--timing` value **outside the schema enums** (IR-G2). When no activated gate applies to a valid stage/timing, the door says so, instead of claiming nothing is activated.

**Declared scopes (IC-5):** KOS-G-001 and KOS-G-003 scan **every `.jsonl` under the workspace, recursively**, excluding the top-level `governance/` tree and hidden directories. **KOS-G-003 does not scan markdown**, and says so. Its known F-id set is the **canonical corpus list** `docs/knowledgeos/list_of_files_to_read.log` (L0-DEC-17), not the research registry.

## 7 · The catalogue

**25 gates**, and the unimplemented ones stay listed on purpose — ⛔ **`not_yet_defined` is an honest status, not a placeholder to be cleared.**

| Class | Count | Runner behaviour |
|---|---|---|
| `AUT` | 15 | measured from artifacts — ⚠️ **14 implemented; `KOS-G-047` is `not_yet_defined`** |
| `REVIEW` | 8 | ⛔ **reported `REVIEW_REQUIRED`; NEVER auto-PASSed** |
| `HUMAN` | 2 | ⛔ **never evaluated at all** |

**By status:** `active` 23 · `proposed` 1 · `not_yet_defined` 1.

⭐ **Two gates encode questions that are currently open and unanswerable by any instrument:**

- **`KOS-G-060`** — *engineering never accepts its own work.* **Every acceptance in this programme so far has been the author's.**
- **`KOS-G-061`** — whether a **frozen artifact** may be edited to remove stale state. *(Outstanding: the Q-gate registry inside the frozen Phase-2 protocol carries a compliance-status column that is out of date.)*

⚠️ **One gate is `proposed`, not `active`, on purpose:** `KOS-G-041` (*search before experiment design*) is a real gap — an experiment the corpus had already run was queued twice in one session because `Q38`/`Q53` cover constructing a **formulation**, not designing an **experiment**. ⛔ **It stays proposed because the methodology is frozen and one occurrence is not promotion evidence.**

## 8 · Relation to `check-gates.py`

⛔ **Superseded.** That script was written as a standalone gate runner and, on inspection, **overlapped `AST-010` — a registered, approved, unimplemented platform asset** — without the registry-first steps. It also printed interpretation inside the instrument, which `AST-010`'s own specification forbids.

⭐ **This mechanism withdraws that claim:** the checks live on as named implementations under a rule book, explicitly research-scoped, and the interpretation moved out of the instrument and into this document and the research record.

## 9 · At the end of the programme

```
research complete → retrospective
                        │
              ┌─────────┴─────────┐
              ▼                   ▼
         DISCARD              RETAIN
      the default        only with evidence
              │            it was useful
              ▼                   ▼
      remove hook        candidate platform
      and governance/     capability — via the
                          registry, not by drift
```

⛔ **Nothing here becomes permanent by surviving.** Promotion runs through the platform registry and a human decision, never through the fact that a temporary mechanism was not deleted.
