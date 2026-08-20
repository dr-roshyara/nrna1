# `KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN`

**Status: 🟡 PROPOSED · AMENDED (AMD3 + AMD4, 2026-08-20).** Planning only — **no migration executed, no file moved, no default changed, no `.gitignore` or `.gitattributes` touched, no authority record modified.**
**Lane:** `S4b-architecture-gov-state-impl-design` (seq 4 REGISTER · 5 HANDOFF · 6 START) · **Grants:** `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN` **+ AMD1 + AMD2** · ⚠️ **AMD3 and AMD4 are BOTH UNREGISTERED — §0.2, §11.1**
**Date:** 2026-08-19 · **amended 2026-08-20 (AMD3), 2026-08-20 (AMD4).** **Producing process of the original plan, self-declared, NOT attestable** (`INV-ATTR-2`/`G-2`): `claude-code-session:1c8b041b`. **Producing process of AMD3 and AMD4:** `claude-code-session:bc1b47ef` — ⚠️ **author of the technical review `a282d14b` whose `CL` clarifications AMD3 closed.** ✅ **AMD4's `RC` findings, by contrast, are `claude-code-session:9c908e70`'s — INDEPENDENT (§0.4.1).** **`R-34`/`P-2`: no producing process may verify or accept this plan.**

**Placement resolved through existing governance, not chosen:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → **`docs/knowledgeos`**, **exit 0**. ⇒ the AMD2 exit-2 `PENDING`/escalate branch **did not fire**; `architecture/` matches the artifact class.

---

# 0 · Amendment 3 — ⚠️ **its two bounds first, because they limit what everything below may claim**

**Authorizing human act, cited by reference and not paraphrased into authority:** the PO/ARB instruction of 2026-08-20 registering the technical Architecture review and routing the next act — *"The next act should be an amendment to the migration plan that closes CL-1, CL-2, and CL-3"*, with the three secondary corrections named in the same act (authority transfer at Phase 5 · `KOS_MECHANISM_PATH` as a write-capable path · grants need their own reconciliation model).

## 0.1 ⛔ **BOUND 1 — AMD3 ADDRESSES `CL-1`/`CL-2`/`CL-3`. It does NOT close them.**

**AMD3 is written by `bc1b47ef`, which authored the technical review that raised `CL-1`–`CL-12` *and proposed the remedies now written in below*.** ⇒ **two `R-34` exposures, not one:**

| Exposure | Consequence |
|---|---|
| **the clarifications are this process's own** | ⛔ **it may not assess whether they are closed** — that is verifying its own work |
| ⭐ **the REMEDIES are also this process's own** | ⭐ **the remedies have never been reviewed by anyone.** They were *proposed* inside a review; a review cannot review its own proposal |

> ## ⭐ **CONSEQUENCE FOR THE NEXT REVIEWER, stated so it cannot be skipped: the next review must review the REMEDY, not merely check that text was added.**
> **A completeness pass confirming *"§6 now says X"* establishes nothing here, because the author of §6's new text is the author of the finding that demanded it.** ⇒ **the gates `CL-1`/`CL-2`/`CL-3` remain `⏳ ADDRESSED · NOT CLOSED` until a process that is neither `1c8b041b` nor `bc1b47ef` judges the remedy sound.**

## 0.2 ⚠️ **BOUND 2 — AMD3 carries NO GRANT, and this plan does not manufacture one**

**`AMD1` and `AMD2` each carry their own registered grant. There is no `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD3`.**

⭐ **The authority exists; its REGISTRATION does not.** The mechanism's own semantics settle which is which: *"a grant registers a recorded human act **by reference** — the record never manufactures authority"* (`workflow-state.php:326`, `G-2`/`R5b`). ⇒ **the human act above IS the authority; the grant would be its record.**
⛔ **Registration is a Governance act with exactly one writer** (`--writer-role=governance`, `G-2`/`R5a`) **and is NOT performed here** — this process holds an Architecture role in this chain, and registering its own authorization would be the manufacture the mechanism forbids.

⇒ **`OPEN-M6`: AMD3 must be registered by Governance before the amended plan is accepted.** *(Precedent for producing while a registration lags: the S4b lane's `executionContext` names `5e1dd9ee` while `1c8b041b` produced the plan — `INFO-2` of the self-review, disposed as *"a Governance act, not performed"*.)*

## 0.3 What AMD3 changes — the full list, so no change is silent

| # | Change | Closes |
|---|---|---|
| 1 | §1.1 · §10 criterion 1 — the corpus figures become **planning observations**; the acceptance object becomes a **frozen, committed manifest** | `CL-2` |
| 2 | §4 — **Phase 0 (declared write freeze)** added; Phase 1 produces the manifest; Phase 4 is **all-or-nothing**; Phase 5 gains a **re-hash** precondition | `CL-1`, `CL-2` |
| 3 | §6.3 · §10 criterion 2 — the completeness claim is **restated to what is provable**, and the *"dense ⇒ nothing lost"* inference is **withdrawn** | `CL-1` |
| 4 | §3 `INV-R5` · §4 Phase 4b — the act that **produces the resolver** is named, and criteria 6/7 are realigned to what the phases deliver | `CL-3` |
| 5 | §4 · §8 — **authority transfers at Phase 5**; Phase 6 **records** it; the rollback rule is corrected for the 5→6 window | `CL-4` |
| 6 | §4 — **two on-disk markers**: staging *non-authoritative*, runtime *demoted* | `CL-5` |
| 7 | §4 Phase 2b — **`.gitattributes` pin** required before Phase 3; manifest committed with the copy | `CL-6` |
| 8 | §6.4 — **grant reconciliation** by position-plus-content, ⛔ never by `grantId` | `CL-7` |
| 9 | §6.3 — CASE A's prefix comparison **specified** as element-wise over the decoded array | `CL-8` |
| 10 | §3 `INV-R1` — **split** into location-refusal (exit 65) and workflow-state `UNRESOLVABLE` (report, exit 0) | `CL-9` |
| 11 | §1.5 · §3 `INV-R3` — `P-4` restated as a **write-capable** path; reporting-vs-enforcing forms separated | `CL-10` |
| 12 | §2 · §5 — the two senses of *"durable"* separated | `CL-11` |
| 13 | §4 Phase 1 — the **record predicate** stated as exactly `*.json`, with quarantine | `CL-12` |

⛔ **AMD3 changes NO decision.** `B′` · `R-CONFLICT` · `OPEN-M3`'s Option A · placement governance · `INV-ORDER` · `Option D` — **all untouched.** ⛔ **`Increment 2` (locks, leases, hooks, enforcement) is NOT proposed** — §1.3 now *reports* a pre-existing writer property and this plan stops claiming a guarantee that contradicts it.

---

# 0.4 · Amendment 4 — closing the independent review's residuals

**Input, consumed and not reinterpreted:** `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD3-ARCHITECTURE-REVIEW.md` — the **INDEPENDENT** AMD3 remedy review by `claude-code-session:9c908e70`, verdict **`PASS WITH DESIGN CLARIFICATIONS`**: **10 `CL` CLOSED · 2 ADDRESSED-NOT-CLOSED (`CL-5`, `CL-6`) · 0 unclosed**, with **`RC-1`…`RC-11`** residual and **`RC-1`/`RC-2`/`RC-3`/`RC-4` gate-class**.
**Authorizing human act, cited by reference:** the PO/ARB commission of 2026-08-20 — *"Produce the next amendment… MUST close or explicitly resolve the residuals identified by the independent AMD3 review."* ⚠️ **Also unregistered — `OPEN-M6` now spans AMD3 and AMD4 (§11.1).**

## 0.4.1 ⭐ **AMD3's §0.1 bound is now PARTLY DISCHARGED, and the discharge is asymmetric. That matters more than the disposition table.**

| | Then (AMD3) | Now (AMD4) |
|---|---|---|
| **who found the problems** | ⛔ **`bc1b47ef` — the same process that wrote the remedies** | ✅ **`9c908e70` — independent, no prior participation** |
| **had the remedies been reviewed?** | ⛔ **no — a review cannot review its own proposal** | ✅ **YES. That is exactly what `9c908e70` did, and 10 of 12 closed** |
| **who writes THIS amendment's remedies?** | — | ⛔ **`bc1b47ef` again** |

> ## ⭐ **So the exposure has CHANGED SHAPE rather than disappeared, and the honest statement is narrow:**
> ✅ **`CL-1`…`CL-12`'s remedies are no longer self-reviewed — an independent process tested them against the code and closed ten.** ⭐ **The `RC` findings AMD4 closes are SOMEONE ELSE'S findings, which is a materially stronger position than AMD3's.**
> ⛔ **But `RC-1`…`RC-11`'s REMEDIES are again written by the process that will not review them.** ⇒ **AMD4 does NOT declare any `RC` closed. It records them `ADDRESSED`, and a fresh independent review decides closure.** **The same bound, one level down — and it will recur until an amendment produces no new remedy.**

## 0.4.2 `RC-1`…`RC-11` disposition — ⭐ **every item ADDRESSED; ⛔ none self-closed**

| # | Class | Where AMD4 addresses it | Disposition |
|---|---|---|---|
| ⭐ **`RC-1`** | **gate · Phase 3** | §4 table — **`2b-pin` (before the copy) and `2c-commit` (at/after the copy) are now SEPARATE phases**; Phase 3's precondition is `2b-pin`, which no longer depends on Phase 3's output | **ADDRESSED — circularity removed** |
| ⭐ **`RC-2`** | **gate · Phase 4b** | §3 table — **row 1 binds the AUTHORITATIVE resolution; new row 2 states a governed override yields a NON-AUTHORITATIVE location and is NOT a boundary violation**; both contract suites' `--dir` usage quoted as the evidence | **ADDRESSED — pinned suites preserved** |
| ⭐ **`RC-3`** | **gate · Phase 4b** | §3 table — **reporting form marked ✅ *"THIS IS WHAT PHASE 4b IMPLEMENTS"*; enforcing form marked ⛔ *"DEFERRED TO `OPEN-M5`, NOT CURRENT"*** | **ADDRESSED — one current definition per row** |
| 🔴 ⭐ **`RC-4`** | **gate · Phase 7** | **§4.1 — a FINAL re-hash against `manifest + reconciled delta` is Phase 7's precondition**; mismatch ⇒ stop, reconcile, re-verify, never remove. **New acceptance criterion 12** · new §8 rollback trigger | **ADDRESSED — the destructive act is guarded** |
| **`RC-5`** | design | **§4.2 — Phase 5's internal order MANDATED: re-hash · readers · WRITER LAST**; the transfer instant is the writer switch; §8's boundary restated | **ADDRESSED** |
| **`RC-5b`** | design | §3 `INV-R5` + §4 table row 8 — **Phase 4b delivers the resolver DORMANT; Phase 5 is the sole activating act** | **ADDRESSED** |
| **`RC-6`** | design | **§4.3 — directory-level · out-of-band · in-file marking FORBIDDEN by byte preservation · ⛔ never `*.json` · advisory reach stated** | **ADDRESSED** |
| **`RC-7a`** | coherence | §4 — the `INV-ORDER` preservation argument now enumerates **all three** additions, including `2b`/`2c` inside the 2–4 span | **ADDRESSED** |
| **`RC-7b`** | coherence | §4 — **the table is sorted by EXECUTION and numbered `1…11`; labels are lineage, row order is authoritative** | **ADDRESSED** |
| **`RC-7`** *(dependencies)* | coherence | **§11.1 — six prerequisites with actors**, including the Phase-0 and Phase-4b acts AMD3 introduced and did not carry | **ADDRESSED** |
| **`RC-8`** | design | **§4.4 — `text eol=lf` WITHDRAWN; `-text` only**, with the `CR` argument; hash semantics (SHA-256, working-tree bytes) recorded in Phase 1 | **ADDRESSED** |
| **`RC-9`** | wording | §6.3 — **canonical re-encode DEFINED as recursive key ordering**, comparison-only | **ADDRESSED** |
| **`RC-10`** | design brief | **§3.1 — refusal MUST precede `saveRecord`'s `mkdir`**, because a mis-resolved path *materializes* rather than failing | **ADDRESSED — recorded, not implemented** |
| **`RC-11`** | wording | **§1.3 — *"one writer of governance evidence"*, with the discriminating test** (*references `runtime/workflow` AND writes*) and the three candidates disposed | **ADDRESSED — closes `INFO-1`** |
| **`CL-5`** | reopened | closed via `RC-6` (§4.3) | **ADDRESSED** |
| **`CL-6`** | reopened | closed via `RC-1` (§4) and `RC-8` (§4.4) | **ADDRESSED** |

⛔ **AMD4 changes NO decision.** `B′` · `R-CONFLICT` · `OPEN-M3` Option A · placement governance · `INV-ORDER` · `Option D` — **all untouched.** ⛔ **`OPEN-M5` NOT decided** · ⛔ **no ledger** · ⛔ **no bounded context** · ⛔ **no `.gitignore` change** · ⛔ **no `.gitattributes` change** *(the `-text` pin is REQUIRED at Phase 2b-pin and NOT performed)* · ⛔ **no runtime code, including the Phase-4b resolver and the `RC-10` guard** · ⛔ **migration NOT executed.**

## 0.4.3 ⭐ The live-corpus rule, preserved verbatim from AMD3

**Planning counts are HISTORICAL OBSERVATIONS.** **At execution, Phase 1 MUST re-inventory the live corpus, and the resulting manifest is the execution acceptance object.** ⛔ **No current grant or transition count is hard-coded as universal truth** — §1.1 and §10 criterion 1.

## 0.4.4 Canonical-document rule

**One canonical CURRENT definition per section.** **Superseded wording remains ONLY where explicitly labelled** — §1.3's heading, §3's enforcing row, §4.4's withdrawn form, §6.3's withdrawn invariant, §8's superseded rule, §10's superseded criteria. ⛔ **Governance history is not deleted, and no two competing current definitions are left standing** — the one such defect the independent review found (`RC-3`) is repaired in §3.

---

# 1 · Current-state inventory — `OBSERVED`, measured, nothing modified

## 1.1 The evidence corpus — ⚠️ **AMD3: these are PLANNING OBSERVATIONS, never an execution criterion**

| | Measured 2026-08-19 | Re-measured 2026-08-20 |
|---|---|---|
| **Authority records** | **18** JSON files under `.claude/runtime/workflow/` | **18** |
| **Transitions** | **216** | **216** |
| **Grants** | **109** | ⚠️ **110** — and ⭐ **only 109 UNIQUE `grantId`s** (§6.4) |
| **Size** | **780 KB** | — |

> ## ⭐ **AMD3 (`CL-2`) — the figures are not merely stale. They are UNATTESTABLE, and that is the stronger reason.**
> ```
> git log --oneline -- .claude/runtime/workflow/<any record>   →   (empty)
> ```
> **The corpus has NO history, because it is gitignored — the very defect `B′` exists to close.** ⇒ **`18 / 216 / 109` cannot be re-derived from anything at any later date.** ⛔ **A criterion pinned to it is not evidence, and it also cannot distinguish *"a record is missing"* from *"the corpus legitimately grew"* — and the corpus grew during the ADR, during the design, during planning, and again during the Governance review.**
> ✅ **The delta is fully accounted for:** the 110th grant is `G-KOS-GOV-STATE-DURABILITY-OPEN-M3-DECISION`, registered after `3817eb2b`. ⭐ **The `109`-unique-`grantId` figure is an INDEPENDENT fact and explains nothing about the delta** (§6.4). ⇒ **the acceptance object becomes the Phase-1 frozen manifest (§4, §10 criterion 1); these figures stay as the planning record they always were.**

**Per-record shape varies and the migration must not normalise it.** Two records carry **grants with zero transitions** (`KOS-ARCH-V3-BOUNDARY-VALIDATION` 0/3 · `KOS-ACTIVATION-REPORTING-001` 0/0) — **an authorized-but-uncommissioned lane is a legitimate shape, not a defect to tidy.**

## 1.2 ⭐ The durability defect, stated as measurement

```
.gitignore:25   .claude/runtime/
.gitignore:32   .claude/runtime/          ← the rule is present TWICE
git check-ignore -v .claude/runtime/workflow/KOS-CONTRACT-NEUTRALITY-001.json  →  IGNORED
```

> **The authoritative governance record is UNVERSIONED.** Every narrative artifact that cites it is versioned; **the record they cite is not.** ⇒ **historical content is unattestable by anyone**, which is the finding `B′` exists to close.

## 1.3 ⭐ **AMD4 (`RC-11`): ONE WRITER OF GOVERNANCE EVIDENCE** — *(superseded heading: "Writers — exactly one")*

| Writer | Evidence |
|---|---|
| `.claude/scripts/workflow-state.php:105` | `$tmp = $path . '.tmp.' . getmypid(); file_put_contents($tmp, …); rename($tmp, $path);` — **atomic write via temp + rename**, and it `mkdir`s the parent |

> ## ⛔ **AMD4 (`RC-11`, closing `INFO-1`) — the unqualified claim is WITHDRAWN, and the discriminating test is cited rather than assumed.**
> **`grep -rln file_put_contents .claude/scripts/` returns THREE files**, so a reader checking *"exactly one writer"* would reasonably doubt it. **The discriminating test — the one that makes the claim checkable — is: *references `runtime/workflow` AND writes*.**
>
> | Candidate | Disposition |
> |---|---|
> | `workflow-state.php:105` | ✅ **the one writer of governance evidence** |
> | `session-resolve.php:22` | **a docblock MENTION, not a call** |
> | `session-changes-logger.sh:60` | **a real write that never references `runtime/workflow`** — session state, not evidence |
>
> ⭐ **And AMD3's own evidence made the unqualified form indefensible: `CL-10`/§1.5 establishes that `KOS_MECHANISM_PATH` makes a SECOND write path reachable.** ⇒ *"one writer"* is true of **the committed code paths today** and false as a **structural guarantee**. ⛔ **This plan does not claim that arbitrary writes to the directory are impossible — §1.4 and `INV-R4` already say the opposite.**

✅ **A single writer of governance evidence is still the migration's biggest asset:** the cutover has one code path to switch, not many — and §4.2 uses exactly that fact to make the authority-transfer instant a single event.

> ## 🔴 **AMD3 (`CL-1`) — the write is atomic; the READ-MODIFY-WRITE is not. This is REPORTED, not repaired.**
> ```
> append :  loadRecord($path)                              (:303)   ← read whole JSON
>           $t['seq'] = count($record['transitions']) + 1   (:309)   ← compute from what was read
>           saveRecord($path, $record)                      (:311)   ← tmp + rename, ATOMIC per write
> ```
> **No lock, no lease, no CAS, no version check** — and the file's own header says so: *"Increment 2 is not authorized: no lock, no lease, no hook, no enforcement."* **Two concurrent appends read the same `count()`, compute the same `seq`, and the second `rename` wins.**
>
> **Reproduced (technical review, `mktemp` dirs via `--dir`, never against the corpus):**
> ```
> 23 / 30 concurrent-append trials silently lost a transition
>     the losing process exited 0 and reported {"ok":true,"seq":2}
>     EVERY survivor was DENSE and MONOTONIC
> the same loss reproduced on the `grant` path
> ```
> ⛔ **CONSEQUENCE — and it is the one claim in this plan that had to be withdrawn: DENSITY IS NOT A COMPLETENESS PROOF. It is preserved BY the loss, because the clobbering writer reuses the sequence number the lost writer took.** ⇒ §6.3 and §10 criterion 2 are restated.
> ⛔ **This is a PRE-EXISTING property of the writer, present with or without relocation. Repairing it is `Increment 2` and is NOT proposed here.** ⚠️ **But three things about the migration interact with it, so it cannot be left unaddressed: the migration WRITES INTO THE CORPUS IT MIGRATES (§5) · it deliberately widens the window (§6.3) · and a loss inside the reconciliation tail is imported as a legitimate extension (§6.3).** ⇒ **Phase 0's declared freeze (§4) exists for exactly this.**

## 1.4 Readers — **enumerable in code, unbounded in fact**

| Reader | Nature |
|---|---|
| `.claude/scripts/session-resolve.php` | **read-only by construction** — its own docblock: *"no `file_put_contents`, no `mkdir`/`rename`/`unlink`"* |
| **Ad-hoc readers** | 🔴 **not enumerable.** The records are plain JSON on disk; any `cat`, `jq`, editor or agent reads them **without resolving anything** |

> ## ⚠️ **A limit that must be stated rather than assumed away**
> **The Single Authority Resolver Invariant can bind COMPONENTS that resolve a path. It cannot bind a reader that needs no resolver.** ⇒ **the invariant is ENFORCEABLE for writers and tooling, and ADVISORY for ad-hoc reads.** **Authority must therefore rest on the record's governed LOCATION and PROVENANCE, not on an assumption that every reader passed through a resolver.** *(`INFERRED`, and it bounds what §3 can promise.)*

## 1.5 ⭐ Path sources — **THREE, on TWO DIFFERENT AXES.** The decision recorded one.

**The commission's Governance Note asked for every default, because the durability decision recorded only `workflow-state.php:81`. Measured:**

### Axis 1 — WHERE THE RECORD LIVES

| # | Source | Evidence |
|---|---|---|
| **P-1** | **default** | `workflow-state.php:81` — `$dir = $opts['dir'] ?? (dirname(__DIR__) . '/runtime/workflow');` *(the one the decision named)* |
| **P-2** | **default** | 🔴 `session-resolve.php:74` — `$recordDir = rtrim($opts['dir'] ?? (dirname(__DIR__) . '/runtime/workflow'), '/');` **— the second default the decision omits** |
| **P-3a/b** | **two independent `--dir` overrides** | one on each script |

⇒ **`RA-2` confirmed and made precise: two components compute the same default INDEPENDENTLY.** **Neither consults the other. A change to one silently diverges from the other** — which is the exact failure `B′` was decided to remove.

### Axis 2 — WHICH MECHANISM INTERPRETS IT

| # | Source | Evidence |
|---|---|---|
| **P-4** | **environment variable** | 🔴 `session-resolve.php:90` — `$mechanism = getenv('KOS_MECHANISM_PATH') ?: (__DIR__ . '/workflow-state.php');` |

> ## ⭐ **A finding beyond `RA-2` as recorded: authority has TWO location axes, not one.**
> **`RA-2` describes where the RECORD lives. `P-4` decides which PROGRAM is treated as the authority INTERPRETER — and it is settable from the environment.**
> **A resolver that governs only the record path leaves the interpreter divertible: the right bytes read by the wrong mechanism.** ⇒ **§3's invariant must cover both axes or it closes half the hole.**

> ## 🔴 **AMD3 (`CL-10`) — `P-4` is WORSE than "the right bytes read by the wrong mechanism". It is a WRITE-CAPABLE path.**
> ```php
> $mechanism = getenv('KOS_MECHANISM_PATH') ?: (__DIR__ . '/workflow-state.php');   // :90
> proc_open(array_merge(['php', $mechanism], $args), …);                            // :103
> askMechanism($mechanism, ['fold', $workItem, '--dir=' . $recordDir]);             // :156
> ```
> ⭐ **`session-resolve.php` EXECUTES the environment-named program and HANDS IT the authority record directory as an argument.** ⇒ **substitution is not only an interpretation path — it is a write path, through a component whose own bytes contain no write call.**
> ✅ **The script is honest about this** (*"technically capable of substituting the workflow interpreter… NOT a hardened boundary and must not be described as one"*), and `T-11` genuinely pins read purity **of that file**. ⛔ **`T-11` does not, and cannot, pin the purity of a substituted subprocess.**
> ⚠️ **Relocation RAISES the value of this path:** after Phase 5 its target is the durable, versioned, authoritative store. ⇒ **`INV-R3` is more necessary than the original plan argued — which is why `OPEN-M3`'s Option A scope extension was required, not optional.**

## 1.6 Runtime clients that must **NOT** migrate — the boundary proved by contrast

Five shell scripts touch `.claude/runtime/` and **none is a governance-evidence client**: `ddd-principles-reminder` · `dev-guide-reminder` · `discipline-gate-reminder` (uses `.claude/runtime/YYYY-MM-DD-…`) · `session-changes-logger` · `session-log-reminder`. **They hold ephemeral reminder/session state.**

✅ **This is the `B′` boundary demonstrated on real files: `.claude/runtime/` legitimately holds runtime state AND illegitimately holds authority evidence. The migration moves the second and leaves the first.** ⛔ **Migrating them would be the mirror error — treating storage location as ownership.**

---

# 2 · Target boundary — conceptual first, ⛔ no physical path invented

| Boundary | Holds | Durability | Authority |
|---|---|---|---|
| **Execution / runtime** | reminder state, per-session scratch, tmp files | ephemeral, may be deleted | **none** |
| **Governance evidence** | transitions · grants · session lineage · the migration's own records | **durable, versioned, append-only in practice** | ✅ **authoritative** |
| **Knowledge artifacts** | ADRs, reviews, plans that *interpret* evidence | durable | interpretive, **never authoritative over the record** |
| **Derived state** | the fold; `authorityState`; resolver answers | recomputable | ⛔ **never a second authority source** |

**Resolved placement root: `docs/knowledgeos` (resolver, exit 0).** ⛔ **The final sub-path and file layout are not chosen here** — that is a placement application at execution time through the same resolver, and inventing it now would bypass the governance the plan is required to use.

---

# 3 · Authority resolution — the Single Authority Resolver Invariant (resolves `RA-2`)

> ## **INVARIANT: every reader and writer of governance evidence resolves the authority location through ONE governed mechanism. No component embeds an independent authority path.**

**And, on §1.5's finding, it must cover both axes:**

> ## ⭐ **AMD4 (`RC-2`, `RC-3`) — THE TABLE BELOW IS THE SPECIFICATION PHASE 4b IMPLEMENTS, so it now carries EXACTLY ONE CURRENT DEFINITION PER ROW.**
> **AMD3 added rulings *around* this table without reconciling the cells inside it, leaving two defects that landed on the same implementer:** row 1's boundary check would have **refused every pinned contract test** (`RC-2`), and row 2 stated the **enforcing** form four lines above the note saying the migration executes the **reporting** form (`RC-3` — *the one conflicting current definition in the artifact*).

| | Resolution input | Output | Validation | Failure | Unresolved |
|---|---|---|---|---|---|
| ⭐ **Record location — AUTHORITATIVE resolution** *(AMD4, `RC-2`)* | work-item id | the durable evidence location | the **authoritatively resolved** location is **inside the governed evidence boundary** | ⛔ **refusal to produce an answer at all — exit 65** | ⛔ **refuse** |
| ⭐ **Record location — GOVERNED OVERRIDE** *(AMD4, `RC-2`)* | an explicit `--dir` | ⭐ **a NON-AUTHORITATIVE output location** | ⛔ **NONE. An override target OUTSIDE the governed boundary is NOT a boundary violation** — it is a technical output location that **carries no authority** | ✅ **no refusal** | n/a |
| ⭐ **Mechanism identity — REPORTING form** *(AMD4, `RC-3`)* — ✅ **THIS IS WHAT PHASE 4b IMPLEMENTS** | none | the interpreter actually used | ⛔ **none on legitimacy.** The interpreter's **identity is REPORTED** (`interpreter.path`/`isDefault`, `[substituted]`) | ✅ **no refusal — substitution is permitted and never confers authority** | n/a |
| ⚠️ **Mechanism identity — ENFORCING form** — ⛔ **DEFERRED TO `OPEN-M5`, NOT CURRENT** | none — fixed by governance | the authority interpreter | the interpreter is the governed one | ⛔ refuse | ⛔ refuse |

> ## ⛔ **AMD4 (`RC-2`) — why the boundary check had to be split, with the evidence.**
> ```
> SessionAssignmentResolverContractTest::resolve()  →  --dir=sys_get_temp_dir().'/kos-disc-…'
> WorkflowStateRecordContractTest                   →  --dir=sys_get_temp_dir().'/kos-orch-…'
> ```
> ⭐ **BOTH pinned contract suites drive BOTH components through `--dir` into a temp directory that is, by construction, OUTSIDE the governed evidence boundary.** ⇒ **an implementer applying AMD3's boundary cell literally would have made the entire pinned suite exit 65 with no report.**
> ✅ **§7 already carried the reconciliation** — *"an override may select where bytes are written, and may never confer authority on the result"* — ⛔ **but §3, which is what Phase 4b builds from, did not.** **Now it does, and it is `INV-R1`'s row-2, not a footnote.**
> ⚠️ **One sub-case is left to Phase 4b's implementer and is recorded rather than silently decided:** today `!is_dir($recordDir)` yields `UNRESOLVABLE`/exit 0 (`session-resolve.php:127`). **Whether a NONEXISTENT GOVERNED directory is *"location cannot be resolved"* (⇒ 65) or a workflow-state condition (⇒ report, exit 0) is not pinned by any test.** ⛔ **AMD4 does not decide it; it names it as a Phase-4b design decision.**

> ## ⛔ **AMD4 (`RC-3`) — the enforcing form is DEFERRED, and row 4 above is labelled so it cannot be built from by mistake.**
> **Verified in the test bodies: `T-15` sets `KOS_MECHANISM_PATH` to a substitute and asserts verdict `RESOLVED` with the substituted path reported; `T-13(b)` sets it to `/nonexistent` and asserts a report is still produced naming the unavailable mechanism.** ⇒ **an implementer building Phase 4b from the enforcing row breaks `T-15` outright and `T-13(b)`'s second assertion.**

⛔ **`INV-R1` — the resolver MUST NOT silently fall back to runtime.** A missing or invalid target is a **refusal**, never a downgrade. *(A silent fallback would re-create the split `B′` removed, and would do it invisibly.)*

> ## ⭐ **AMD3 (`CL-9`) — `INV-R1` is SPLIT, because as first worded it broke a pinned contract.**
> **The original wording — *"refuse and exit non-zero"* — collides with `AST-016` AMENDMENT 1, pinned by `T-12`: *"RESOLVED · UNASSIGNED · AMBIGUOUS · UNRESOLVABLE all exit 0. Non-zero is reserved for usage errors (64) and for refusal to produce a report at all (65)."*** ⇒ **an implementer following Phase 5 literally would have broken `T-12`.**
>
> | Condition | Behaviour | Contract |
> |---|---|---|
> | **the authority LOCATION cannot be resolved, or resolves OUTSIDE the governed boundary** | ⛔ **refuse to produce a report at all — exit 65** | ✅ **AMENDMENT 1's own *"refusal to produce a report"* clause** |
> | **the location resolved; the WORKFLOW STATE is `UNRESOLVABLE`** | ✅ **produce the report — exit 0** | ✅ **`T-12` untouched** |
>
> ⭐ **`INV-R1` binds the FIRST. `T-12` governs the SECOND. So worded, NO adopted contract is amended and NO test changes.**

⛔ **`INV-R2` — `P-1` and `P-2` are replaced by ONE resolution, not synchronised.** Keeping two defaults "in agreement" is the defect, not the fix.

⛔ **`INV-R3` — `P-4` is brought under governance** (`OPEN-M3`, Option A: the resolver governs authority-record location **and** interpreter selection).

> ## ⚠️ **AMD3 (`CL-10`) — `INV-R3` has TWO implementable forms, and only one is inside this migration.**
> | Form | Consequence |
> |---|---|
> | ✅ **REPORTING** — substitution permitted, **authority never conferred on its output**, substitution reported (today's behaviour, `C-1`) | **`T-13(b)` · `T-14` · `T-15` all keep passing** — `T-13(b)` and `T-15` **deliberately set** `KOS_MECHANISM_PATH` |
> | 🔴 **ENFORCING** — the literal reading: *"an environment variable may not select the authority interpreter"* | **removes the exact seam those two tests exercise** ⇒ **amending `AST-016`'s AMENDMENT-2 contract, which is beyond a relocation** |
>
> ⭐ **THIS MIGRATION EXECUTES ON THE REPORTING FORM.** It changes no adopted contract. **Enforcement is a separately authorized act, and whether it authorizes the contract amendment is `OPEN-M5` — routed to PO/ARB, decided by nobody here.**

⚠️ **`INV-R4` — the invariant binds components, not the filesystem** (§1.4). It is enforceable at every resolving call site and **advisory for ad-hoc reads**; the plan does not claim otherwise.

> ## ⭐ **AMD3 (`CL-3`) — `INV-R5`: THE RESOLVER MUST EXIST BEFORE PHASE 5 CONSUMES IT.**
> **The original plan switched every path *"to the §3 resolver"* while no phase produced one, and §7 declined to restrict the overrides that criteria 6 and 7 require closed.** ⇒ **the seven phases asserted an outcome they could not reach.**
> ⭐ **§3 is a SPECIFICATION, not an artifact.** The artifact is produced by a **named act — Phase 4b (§4) — an authorized implementation slice against `workflow-state.php` and `session-resolve.php`,** which is **runtime-code change and therefore outside this planning artifact's fence** (§12). **Phase 5 may not begin until that act has landed and its own RED/GREEN evidence exists.**
> ⛔ **What `INV-R5` does NOT do:** it does not design the resolver, choose its shape, or restrict `--dir`/`KOS_MECHANISM_PATH` (§7 stands). **It names the missing act and refuses to let Phase 5 assume it.**
> ⭐ **AMD4 (`RC-5b`): Phase 4b delivers the resolver DORMANT — built, tested, NOT WIRED.** ⛔ **If 4b wired it, writers would redirect at 4b, 4b would BE the switch, and §4.2's transfer instant would be indeterminate.** **Phase 5 is the sole activating act.**

## 3.1 ⭐ **AMD4 (`RC-10`) — a Phase-4b design brief the plan owes the implementer: REFUSAL MUST PRECEDE `mkdir`**

```php
function saveRecord(string $path, array $record): void {
    if (!is_dir(dirname($path))) { mkdir(dirname($path), 0777, true); }     // :101–102
```

> ⛔ **The writer CREATES ITS PARENT DIRECTORY.** ⇒ **a resolver returning a wrong-but-well-formed path does not fail loudly — it silently MANUFACTURES A NEW EVIDENCE LOCATION.** ⭐ **That is `B′`'s original defect re-created by the very component built to close it: a location acquiring authority because a write happened to land there.**
> ⇒ **`INV-R1`'s refusal MUST be enforced BEFORE `saveRecord` is reached. A refusal downstream of this `mkdir` is not a refusal — the damage is done by the time it fires.**
> ⛔ **AMD4 records this as a Phase-4b design constraint and does NOT implement it** (§12: no runtime code). ⚠️ **It is also the reason `RC-2`'s override row must not be read as loosening row 1: an override yields a non-authoritative location, and the AUTHORITATIVE resolution is exactly where this constraint bites.**

---

# 4 · Migration phases — **AMD4: the table is now in EXECUTABLE ORDER, and Phase 7 is guarded**

> ## ⭐ **`INV-ORDER` (Flag R, promoted): durability is CREATED and VERIFIED (2–4) BEFORE authority is DEMOTED (6), and removal is LAST (7).**
> ### **CONSEQUENCE: at no point in the sequence does the authority record exist in only one place.**
> **Any future re-ordering is therefore visibly a violation, not a preference.**
>
> ## ✅ **AMD4 (`RC-7a`) — the preservation argument now covers ITS OWN FULL CHANGE SET. AMD3's version enumerated two of three additions and omitted the one that lands INSIDE the 2–4 span.**
> | Added phase | Where | Does it disturb `INV-ORDER`? |
> |---|---|---|
> | **0 · freeze** | **prefixed BEFORE** the sequence | ✅ no — nothing is reordered |
> | ⭐ **2b-pin · 2c-commit** | ⭐ **INSIDE the 2–4 span** *(the omission `RC-7a` names)* | ✅ **no — both CREATE durability guarantees, which is what 2–4 is for.** 2–4 still precede 6; 7 is still last |
> | **4b · resolver** | between verification and the switch | ✅ no — it gates the switch, it does not demote anything |
>
> ⛔ **`OPEN-M2`'s reordering question is still NOT resolved** — AMD4 constrains it further (`RC-1`'s split pins *when* the manifest is committed) **without deciding it.**

> ## ⭐ **AMD4 (`RC-1`, `RC-7b`) — EXECUTABLE ORDER. The table is sorted by execution, not by label.**
> **AMD3's table listed `2b` above `2` while `2b`'s own precondition was *"Phase 2 target resolved"*, and folded two acts with DIFFERENT ordering constraints into one phase — making Phase 3's precondition unsatisfiable (`RC-1`). Labels are retained for lineage; ⭐ THE ROW ORDER IS AUTHORITATIVE:**
> ```
> 0  →  1  →  2  →  2b-pin  →  3  →  2c-commit  →  4  →  4b  →  5  →  6  →  7
>                   ↑ BEFORE the copy          ↑ AT/AFTER the copy
> ```

| # | Phase | Act | Precondition | Produces |
|---|---|---|---|---|
| 1 | ⭐ **0 · Declared write freeze** *(AMD3, `CL-1`)* | **PO/ARB declares the migration window: no lane may append to the corpus.** ⛔ **A GOVERNANCE ACT, not a lock — no lock exists and none is proposed** | the authorizing human act | **freeze declaration** |
| 2 | **1 · Inventory** | enumerate every record, transition, grant; **hash each file**. ⭐ **AMD3 (`CL-12`): the record predicate is EXACTLY `*.json`** — matching `session-resolve.php:130` — and ⛔ **any `*.tmp.<pid>` remnant is QUARANTINED: never migrated, never deleted**. ⭐ **AMD4 (`RC-8` minor): the manifest RECORDS ITS HASH SEMANTICS — algorithm (SHA-256) and subject (WORKING-TREE BYTES, not the git blob)** | **Phase 0 declared** | ⭐ **the FROZEN MANIFEST** — hashes + counts + hash semantics (§5) |
| 3 | **2 · Durable target** | resolve the target through existing placement governance; create it. ⭐ **`CL-5`/`RC-6`: the target carries a NON-AUTHORITATIVE MARKER for the whole 3→5 window — see §4.3** | resolver exit 0 | placement evidence |
| 4 | ⭐ **2b-pin · Byte-integrity pin** *(AMD4, `RC-1`/`RC-8`)* | **pin the evidence path in `.gitattributes` as `-text`.** ⛔ **AMD4 (`RC-8`): `-text` ONLY. `text eol=lf` is NOT equivalent and is withdrawn — see §4.4** | **Phase 2 target resolved** | the pin |
| 5 | **3 · Byte-preserving copy** | copy **bytes exactly** | ⭐ **AMD4: Phases 1 and 2b-pin complete** *(no longer "2b", which could not complete before Phase 3)* | the durable copy |
| 6 | ⭐ **2c-commit · Manifest + copy committed** *(AMD4, `RC-1`)* | **commit the Phase-1 manifest AND the Phase-3 copy in ONE commit**, so manifest and artifact cannot drift | **Phase 3 complete** | committed manifest + copy |
| 7 | **4 · Integrity verification** | byte equality · **hash equality** · record count · sequence continuity · provenance continuity — **against the FROZEN MANIFEST**. ⭐ **ALL-OR-NOTHING: a per-file PASS set that does not exhaust the manifest is a FAIL, not progress** | Phase 2c-commit complete | **verification evidence** |
| 8 | ⭐ **4b · Resolver exists, DORMANT** *(AMD3 `CL-3`/`INV-R5`; AMD4 `RC-5b`)* | the separately authorized implementation slice that **PRODUCES** the §3 resolver has landed with its own RED/GREEN evidence. ⭐ **AMD4 (`RC-5b`): it delivers the resolver DORMANT — BUILT BUT NOT WIRED. ⛔ Phase 4b changes no component's effective resolution, so it is NOT a switch and cannot collapse into Phase 5.** ⛔ **Runtime-code change — outside this plan's fence (§12); named here, not performed** | Phase 4 PASSED | resolver *(dormant)* + its test evidence |
| 9 | **5 · Authority path switch** — ⭐ **the SOLE activating act** | ⭐ **AMD4 (`RC-5`): INTERNAL ORDER IS MANDATED — RE-HASH first · then READERS (`P-2`, `P-4`) · then THE WRITER (`P-1`) LAST.** *(`P-3a/b` are NOT restricted — §7 stands.)* The re-hash compares source to the frozen manifest: unchanged ⇒ the window was provably quiet; changed ⇒ **reconcile the delta under §6 as a NAMED STEP.** ⭐ **`CL-5`/`RC-6`: write the runtime copy's DEMOTION MARKER in this same act** | **Phase 4 PASSED · Phase 4b landed** | switch-over evidence · re-hash comparison · ⭐ **the reconciled delta** |
| 10 | **6 · Authority demotion** | ⭐ **RECORD the transfer that the Phase-5 WRITER SWITCH already performed.** ⛔ **Phase 6 does not CAUSE the transfer** — §4.2 | Phase 5 complete | demotion record |
| 11 | **7 · Runtime cleanup** | remove the obsolete runtime copy | 🔴 ⭐ **AMD4 (`RC-4`): Phases 4 AND 5 passed **AND A FINAL RE-HASH PASSES** — see §4.1. ⛔ The Phase-5 re-hash is NOT sufficient** | cleanup evidence · **final re-hash evidence** |

## 4.1 🔴 ⭐ **AMD4 (`RC-4`) — THE FINAL INTEGRITY CHECK. This is the one path by which the migration itself could destroy evidence.**

**The gap the independent review found, stated as the sequence:**

```
Phase 5   re-hash vs manifest  ← the LAST integrity check of the runtime copy under AMD3
Phase 5   switch readers, then the writer     ← not atomic with the re-hash
Phase 6   record the transfer
Phase 7   REMOVE the runtime copy             ← AMD3 precondition: "Phases 4 AND 5 passed" — NO re-check
```

> ⛔ **A write landing after the Phase-5 re-hash but before the WRITER is switched goes to the RUNTIME copy. It is not in the frozen manifest and not in either reconciliation input — and Phase 7 destroys it.**
> ⭐ **And note precisely why AMD3's own restatement cannot catch it:** the bounded claim is *"no record lost **relative to** the frozen manifest and the observed inputs"*, and a gap-window write is in **neither**. **The restatement is honest and here it is self-satisfying. The withdrawn absolute claim would have caught this.** ⛔ **That is NOT an argument for restoring the absolute claim — §6.3's withdrawal stands, and §1.3's evidence is unchanged. It is an argument for a final check.**

**⇒ Phase 7's precondition, in full:**

```
FINAL RE-HASH:  runtime source   vs   frozen manifest + reconciled delta
    identical  →  ✅ removal permitted
    different  →  ⛔ STOP. Do NOT remove.
                  reconcile the difference under §6 (CASE A / CASE B)
                  re-verify (Phase 4 semantics, all-or-nothing)
                  only then may removal proceed
```

⭐ **Why this closes it and costs almost nothing: the check is 18 hash comparisons against an object that already exists, and it is the only guard standing between the last verification and an irreversible act.** ✅ **`§4.2`'s justification for Phase 7 — *"a redundant, demoted, BYTE-VERIFIED copy"* — becomes true at the moment of removal rather than true as of Phase 4.**

## 4.3 ⭐ **AMD4 (`RC-6`) — marker mechanism, specified so it is implementable without re-deriving the constraints**

| Property | Rule |
|---|---|
| **granularity** | **DIRECTORY-LEVEL**, one marker per store — not per record |
| **placement** | ⛔ **OUT-OF-BAND. In-file marking is FORBIDDEN** — it would alter evidence bytes and break §4.1's byte preservation and every hash in the manifest |
| **naming** | ⛔ **MUST NOT match `*.json`** — that glob is `session-resolve.php:130`'s record predicate and §4's Phase-1 predicate; a `*.json` marker would be read as a work item |
| **the two markers** | **staging target: NON-AUTHORITATIVE**, from Phase 2 until Phase 5 · **runtime copy: DEMOTED**, written in the Phase-5 act |
| ⚠️ **reach** | **ADVISORY for the ad-hoc reader class §1.4 already bounds.** ⛔ **The marker does not make the invariant enforceable against a reader that resolves nothing** — it makes the state *visible* to a reader who looks. **Stated, not assumed away** |

## 4.4 ⭐ **AMD4 (`RC-8`) — `-text` and `text eol=lf` are NOT interchangeable. The alternative is withdrawn.**

| Form | Effect | Verdict |
|---|---|---|
| ⛔ `text eol=lf` | **keeps text normalisation ON** and merely fixes the checkout direction. **A record that ever contained a raw `CR` would be silently rewritten on commit** — the exact class of silent byte rewrite the pin exists to prevent | 🔴 **WITHDRAWN — it cannot support an unconditional claim** |
| ✅ **`-text`** | **disables conversion in BOTH directions** | ⭐ **the only form that makes byte preservation unconditional** |

✅ **Measured today: 0 of 18 records contain a `CR` byte, so both forms behave identically NOW.** ⛔ **The claim is about a FUTURE verifier, so it must not rest on a present coincidence.**

> ## ⭐ **AMD3 (`CL-4`) — WHERE AUTHORITY TRANSFERS. Two accepted artifacts disagreed; the disagreement is now resolved.**
> | Source | Said |
> |---|---|
> | implementation design `ae451db9` §3.2/§4.1 | authority moves **at the switch** — *"before step 3: runtime; after step 3: the governance evidence boundary"* |
> | this plan, as first written | **Phase 6** *"declares the runtime copy no longer authoritative"* — a separate later act |
>
> ⭐ **RULING (mechanism, not preference): AUTHORITY TRANSFERS AT THE PHASE-5 WRITER SWITCH, because that is what redirects WRITERS.** After it, an append exists **only** in the new store. **Phase 6 is the RECORD of a transfer that has already happened.**
> ⛔ **CONSEQUENCE FOR ROLLBACK, and §8 is corrected accordingly: after the writer switch, rollback is RECONCILIATION, never *"stop"*.** *"Stop"* would abandon appends that exist only in the new store, or re-promote a store that is **provably behind** — which is the governance act §8 reserved for after Phase 6.

## 4.2 ⭐ **AMD4 (`RC-5`) — the transfer INSTANT, because Phase 5 is not atomic**

**AMD3 said *"on completion of Phase 5."* 🔴 Phase 5 switches `P-1`, `P-2` and `P-4` — several call sites, so its completion is not an instant.** ⭐ **Authority moves the moment the WRITER's resolution changes, which is INSIDE Phase 5** ⇒ **for the interval between the writer switch and Phase 5's completion, AMD3's *"before Phase 5 ⇒ stop"* rule was false for exactly the reason `CL-4` corrected one window later.**

✅ **The fix is cheap because there is only ONE writer to order:**

```
Phase 5, mandated internal order
    1  RE-HASH        source vs frozen manifest            ← still non-authoritative
    2  READERS        P-2 (session-resolve), P-4 (mechanism selection)
    3  ⭐ THE WRITER   P-1 (workflow-state)   ← ⭐ THE AUTHORITY-TRANSFER INSTANT
    4  MARKERS        runtime copy = DEMOTED
```

> ⭐ **Readers first, writer last, and the reason is directional: a reader pointed at the new store before the writer moves reads a copy that is byte-verified and merely not-yet-current; a WRITER pointed at the new store before the readers move would produce evidence no reader can see.** ⛔ **The reverse order creates an invisible-write window; this order creates only a briefly-stale-read window, and the re-hash has already bounded that.**

**⇒ §8's rollback boundary is *"the writer switch"*, not *"Phase 5"*.**

## 4.0 ⭐ Phase 0 — why a freeze, and why it is not a lock

**The original §6.3 rejected quiescing as *"cannot be guaranteed — no lock exists, and no lane can be prevented from acting."* ✅ That is correct about LOCKS and does not settle the question, because a freeze does not need one.**

```
Phase 0   PO/ARB declares the window (a governance act — the program's existing vocabulary)
Phase 1   hash all records → COMMIT the manifest
Phase 5   RE-HASH and compare
              unchanged →  ✅ the window was PROVABLY quiet ⇒ no reconciliation, and no loss possible
              changed   →  reconcile the delta under §6, and RECORD that loss INSIDE the delta is undetectable
```

> ⭐ **This is what converts an unprovable claim into a provable one.** *"No record was lost"* is not provable in general (§1.3). ***"No write occurred during the window"* is provable by comparing the manifest's hashes.** ⛔ **The freeze is DECLARATORY: a lane that appends anyway is a governance violation, detected by the re-hash — not a prevented act.**

## 4.1 ⛔ Phase 3 — what "byte-preserving" forbids

**No parse-and-reserialize · no formatting normalisation · no sequence renumbering · no timestamp rewriting · no "cleanup" of historical records.**

⚠️ **This is not stylistic. The single writer emits `json_encode(..., JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)`. A copy that re-encoded through any other setting — key order, slash escaping, unicode escaping, trailing newline — would produce a semantically equal file with a different hash, and Phase 4 could no longer distinguish "copied correctly" from "silently rewritten".** ⇒ **byte preservation is what makes verification meaningful.**

## 4.2 ⭐ Phase 7 — **removal is NOT deletion** (Flag P, promoted to invariant)

The adopted invariant forbids resolving a conflict by **deleting historical evidence**. **Phase 7 is not that act, and the plan states the distinction rather than relying on the reader to infer it:**

| Deletion (forbidden) | Removal (Phase 7) |
|---|---|
| evidence ceases to exist | evidence **persists at the verified durable target** |
| no surviving copy | **a byte-verified copy exists and is authoritative** |
| history is lost | history is **relocated, provenance intact** |
| may occur at any time | **only after Phase 4 hash verification PASSED and Phase 5 switched** |

> **Phase 7 removes a redundant, demoted, byte-verified copy. It destroys nothing.** ⛔ **Without the Phase-4 precondition stated, Phase 7 reads on its face as history deletion and cannot be told apart from the act the invariant prohibits — which is precisely why the precondition is written into the phase and not into a footnote.**

---

# 5 · Migration evidence — it is itself governance evidence (Flag Q, promoted)

**The migration's own acts produce records that evidence the relocation's integrity:** the Phase-1 inventory · the Phase-4 hash comparison · reconciliation records · the switch-over record · the demotion record · the cleanup record.

> ⛔ **Under `B′` these belong to the GOVERNANCE EVIDENCE boundary, not to runtime.** **If migration evidence were left in `.claude/runtime/`, the migration would generate exactly the class of record the decision was made to protect and leave it in the location the decision rejects.**

> ## ⚠️ **AMD3 (`CL-11`) — *"durable"* has been carrying TWO different properties. They are separated here.**
> | Sense | Status after migration |
> |---|---|
> | **ATTESTABILITY** — the record is versioned, so any later reader can verify what it said | ✅ **this is what the migration cures, and it is the whole point of `B′`** |
> | **CRASH DURABILITY** — the bytes survive power loss | 🔴 **UNCHANGED.** `saveRecord` does **not** `fsync`; `rename` gives atomicity of *visibility*, not durability. **Pre-existing, out of scope, and not claimed** |

✅ **They land at the same resolved durable target as the corpus they attest.** ⭐ **AMD3: the Phase-1 frozen manifest is committed at Phase 2b, in the same commit as the Phase-3 copy, so manifest and artifact cannot drift.** ⚠️ **Ordering consequence:** Phase 1 and Phase 4 produce evidence **before** Phase 2's target may exist — so either the target is created first (Phase 2 before Phase 1's write) or the early evidence is staged and committed to the durable boundary as soon as the target exists. **`OPEN-M2` records this; the plan does not resolve it by reordering the mandated phases.**

---

# 6 · `R-CONFLICT` — the adopted invariant, then migration guidance, **kept apart**

## 6.1 The adopted invariant — quoted, not paraphrased, not extended

> **"A governance evidence conflict MUST NOT be resolved by silently selecting one side, deleting historical evidence, or rewriting sequence history; resolution MUST preserve provenance, sequence integrity and reconstruction capability."**

⛔ **Nothing in §6.2 is invariant text.**

## 6.2 **Migration interpretation** *(operational guidance for this migration only)*

> *If resolution requires a new governance decision, that decision is recorded separately while the conflicting historical evidence is preserved.*

**Labelled as guidance per AMD2 requirement 1, and Flag S is closed on that basis: this clause is NOT adopted into the invariant.**

## 6.3 Why a divergence window exists at all

**Phases 3–5 span real time, and the corpus is live.** A writer may append to the runtime record after Phase 3's copy and before Phase 5's switch. **The window is unavoidable; what is designed is its handling.**

### CASE A — strict extension *(common prefix identical)*

**Migration interpretation:** preserve **both** inputs · preserve the **union** · preserve **every** sequence · **append the missing tail**. ⛔ **This is not selection of one side** — the union is a superset of both.

> ## ⭐ **AMD3 (`CL-8`) — what *"common prefix"* is a prefix OF. The original wording was not implementable.**
> ⛔ **`saveRecord` rewrites the ENTIRE file on every append, so a FILE-LEVEL byte-prefix comparison is meaningless** — the closing brackets move, and the byte prefixes diverge at the first structural difference no matter how well the content agrees.
> ✅ **The comparison is ELEMENT-WISE over the decoded `transitions` array**, element `i` against element `i`, each compared by canonical re-encode.
> ⭐ **AMD4 (`RC-9`) — *"canonical re-encode"* is now DEFINED: RECURSIVE KEY ORDERING before encoding.** In PHP, `json_decode`→`json_encode` preserves **insertion** order, so two semantically equal objects with different key order would compare unequal. ⛔ **That fails SAFE — a false CASE B (escalate) rather than a false CASE A (silent merge) — and it is low-risk today because one writer produced both copies. It is defined anyway, because "fails safe" is not the same as "defined".**
> ⛔ **Canonicalization is COMPARISON-ONLY. It never touches the copied bytes** (§4.1).
> ⭐ **And this is legitimate: §4.1 forbids parse-and-reserialize for the COPY, not for the COMPARISON.** Without saying so, an implementer either performs the wrong comparison or believes parsing is prohibited.

### CASE B — same sequence, different content

**Migration interpretation:** preserve **both** records · **record the conflict** · ⛔ **do not select a winner silently** · **escalate**.

### Post-reconciliation invariants — ⚠️ **AMD3 (`CL-1`): three are checkable; the fourth was WITHDRAWN**

```
sequence set is DENSE          (no gaps introduced)                  ✅ checkable
sequence is MONOTONIC                                               ✅ checkable
result is a SUPERSET of both inputs                                 ✅ checkable — of the INPUTS OBSERVED
no record lost RELATIVE TO THE FROZEN MANIFEST AND BOTH INPUTS      ✅ checkable  ← AMD3 replacement
```

> ## ⛔ **WITHDRAWN: *"NO record silently dropped"* as an absolute guarantee.**
> **It does not follow from the three properties above it, and §1.3 demonstrates it is false in the general case: a lost transition leaves a PERFECTLY DENSE sequence, because the clobbering writer reuses the lost writer's sequence number.**
>
> | Claim | Status |
> |---|---|
> | *"No record was lost relative to the frozen manifest and the observed inputs"* | ✅ **provable — and it is what §10 criterion 2 now says** |
> | *"No record could ever have been lost before observation"* | ⛔ **NOT provable by this or any migration.** Only `Increment 2` could make it so, and `Increment 2` is unauthorized |
>
> ⭐ **And the sharpest operational consequence: a loss INSIDE the reconciliation tail is imported as a legitimate extension.** CASE A appends runtime's tail `M+1…N` after an identical prefix; **a clobbered tail is still dense and still a strict extension**, so CASE A fires and the loss migrates in, indistinguishable from correctness. ⇒ **Phase 0's freeze is the only thing that removes the exposure, by removing the window.**

⭐ **Dense, monotonic `seq` is what makes CASE A mechanically decidable** — a prefix comparison is exact, so "is this an extension or a divergence?" is answered by inspection rather than by judgement. ⚠️ **AMD3: that is ALL it makes decidable. Deciding CASE A vs CASE B is not the same as proving completeness, and the original plan used the one property for both.**

## 6.4 ⭐ **AMD3 (`CL-7`) — RECORD 2: grants have their own reconciliation model, because they have no `seq`**

**Measured across the corpus:**

| | |
|---|---|
| **transitions** | **216** — ✅ **every one carries `seq`**; dense and monotonic in **18 of 18** records |
| **grants** | **110** — 🔴 **ZERO carry `seq`.** No sequence, no ordering identity *(and only **2 of 216** transitions carry any time field, so there is no time dimension to substitute)* |

> ⛔ **§6.3's invariants are stated over `seq`, and `seq` does not exist on grants.** ⇒ **the only mechanical completeness test this plan specified did not reach the Authority State — the authority-bearing half, and the half `G-2` gives a single writer.**

**And the obvious substitute key is unsound. Measured: 110 grants, 109 unique `grantId`s.**

```
G-KOS-GOVGAPS-VERIFY   in KOS-GOV-GAPS-001.json        (index 0)   distinct humanActRef, distinct scope, AUTHORIZED
G-KOS-GOVGAPS-VERIFY   in KOS-GOV-GAPS-VERIFY-001.json (index 0)   distinct humanActRef, distinct scope, AUTHORIZED
```

⭐ **Two distinct authority acts share one identifier.** ⇒ ⛔ **any verification, dedup or merge keyed on `grantId` would treat them as one — a silent loss of an authority act, which is precisely what `R-CONFLICT` forbids.**

### Migration interpretation *(operational guidance for this migration only — ⛔ not invariant text)*

> *Grant reconciliation is **positional and content-based**: grant `i` of the evidence copy is compared with grant `i` of the runtime copy by canonical re-encode of the whole grant object. **CASE A** = the runtime array is a strict extension of an identical prefix ⇒ append the tail. **CASE B** = any positional disagreement ⇒ preserve both, record the conflict, escalate. ⛔ **`grantId` is NEVER the comparison key** — not for identity, not for dedup, not for the completeness count.*

✅ **Bounded honestly, so the finding is not overdrawn:** the two colliding grants sit in **different work items**, hence **different files**, so **per-record reconciliation is unaffected today** *(grant identity inside a record is array position, which byte-preserving copy preserves)*. ⭐ **And this is NOT the cause of the `109`/`110` delta** — that is `G-KOS-GOV-STATE-DURABILITY-OPEN-M3-DECISION` (§1.1). **Two independent facts; neither explains the other.**

---

# 7 · `--dir` override — analysis, ⛔ no restriction implemented here

> ## **INVARIANT (AMD1): an EXECUTION OVERRIDE MUST NOT CHANGE GOVERNANCE AUTHORITY OWNERSHIP.**

**Today `--dir` is unrestricted on both `P-1` and `P-2`, and `KOS_MECHANISM_PATH` is unrestricted on `P-4`.** ⇒ **an invocation can currently divert the authoritative record — and the interpreter — to an arbitrary ungoverned location.**

**The legitimate use survives and must be preserved:** tests and dry runs need to write **somewhere harmless**. **The distinction the design must carry is between a TECHNICAL OUTPUT LOCATION and GOVERNANCE AUTHORITY STORAGE:**

| Use | Verdict |
|---|---|
| `--dir` to a scratch/test location, output **not authoritative** | ✅ legitimate — it produces test artifacts, not evidence |
| `--dir` to redirect **the authoritative record** | ⛔ **must be prevented** — it transfers authority by invocation |
| `KOS_MECHANISM_PATH` to a test harness | ⚠️ same split, on the interpreter axis |

🟡 **PROPOSED shape, not implemented:** an override may select **where bytes are written**, and may **never** confer authority on the result; authority attaches only to the governed resolved location. **The validation mechanism is design work for the implementation act.** ⛔ **This plan does not restrict the flag.**

> ⚠️ **AMD3 (`CL-3`) — the consequence for acceptance, made explicit rather than left to the reader.** **Because §7 restricts nothing, `P-3a/b` survive Phase 5 untouched.** ⇒ **§10 criterion 6 was rewritten to *"every DEFAULT authority-resolution path is unified"*, which is what the phases can deliver.** ⛔ **Override hardening — `--dir` and `KOS_MECHANISM_PATH` alike — is a SEPARATELY AUTHORIZED ACT and is not a migration phase.** ✅ **`INV-R3`'s reporting form (§3) is what this migration carries; the enforcing form is `OPEN-M5`.**

---

# 8 · Rollback

**Trigger conditions:** Phase 4 verification fails · Phase 5 leaves any component unswitched · a CASE B conflict is discovered mid-migration · the resolver cannot resolve.

| Rollback MUST preserve | Rollback MUST NOT |
|---|---|
| historical evidence | ⛔ delete evidence |
| sequence integrity | ⛔ rewrite history |
| provenance | ⛔ silently choose one source |
| reconstructability | ⛔ restore an obsolete runtime source **as authority** without explicit governance handling |

> ## ⛔ **AMD3 (`CL-4`) — CORRECTED. The original rule was right before Phase 5 and WRONG after it.**
> **Superseded text:** *"`INV-ORDER` makes rollback cheap before Phase 6: until authority is demoted, the runtime copy is still authoritative and the durable copy is additive — so rollback before Phase 6 is 'stop', not 'undo'."*
> 🔴 **False in the 5→6 window.** Per §4's ruling, **authority transfers on completion of Phase 5**, so appends after the switch exist **only** in the new store. *"Stop"* would abandon them, or would re-promote a store that is **provably behind** — a change of authority, i.e. the very governance act the sentence deferred to after Phase 6.

**⚠️ AMD4 (`RC-5`): the boundary is THE WRITER SWITCH, not "Phase 5" — because Phase 5 is not atomic (§4.2).**

| Window | Rollback is |
|---|---|
| ⭐ **before the Phase-5 WRITER switch** *(AMD4 — includes the reader switches)* | ✅ **"STOP", not "undo"** — the writer still appends to runtime, so the runtime copy is still authoritative and the durable copy is purely additive. `INV-ORDER` is what makes this cheap |
| ⭐ **after the writer switch, before Phase 6** | 🔴 **RECONCILIATION under §6, never "stop"** — and the safe direction is normally **forward**: complete Phase 6 to record the transfer the writer switch performed |
| **after Phase 6** | **a GOVERNANCE ACT** — re-promoting a demoted source is a change of authority and must be recorded as one |
| 🔴 ⭐ **at Phase 7, on a FINAL re-hash mismatch** *(AMD4, `RC-4`)* | ⛔ **NOT a rollback — a STOP-AND-RECONCILE. Removal is refused until the difference is reconciled and re-verified.** **This is the one trigger that guards an irreversible act** |

⛔ **AMD3 adds one prohibited act: leaving the 5→6 window open.** It is the one state in which the two accepted artifacts could be read as disagreeing about which store is authoritative, and it is also the state in which the runtime copy is stale **with no on-disk signal** — which is why Phase 5 now writes the demotion marker in the same act (`CL-5`).

---

# 9 · DDD / knowledge model

```
EXECUTION            "what is the runtime doing?"        → ephemeral, no authority
GOVERNANCE EVIDENCE  "what authority must survive?"      → durable, authoritative
KNOWLEDGE            "what does the evidence mean?"      → interpretive, durable
AUTHORITY            "who may decide what it means?"     → PO/ARB; not a storage concept
```

**Kept apart, per the commission:** *evidence bytes ≠ evidence meaning ≠ evidence authority* · *historical location ≠ canonical future location*.

> ## ⭐ **The rule this whole migration exists to honour: do not let STORAGE LOCATION accidentally become DOMAIN OWNERSHIP.**
> **`.claude/runtime/` acquired authority because the record happened to be written there — not because runtime owns governance. The migration corrects a location, and in doing so stops an accident from reading as a decision.**

⛔ **Derived state — the fold, `authorityState`, resolver answers — is recomputable and is never a second authority source.** ⛔ **Historical records are not rewritten to make the migration look cleaner.**

---

# 10 · Acceptance criteria

**⚠️ AMD3 rewrote criteria 1, 2, 6 and 7. The superseded text is shown, because a criterion that quietly changes is worse than one that visibly does.**

| # | Criterion | Demonstrated by |
|---|---|---|
| ⭐ **1** | every authority record **in the frozen manifest** accounted for *(AMD3, `CL-2`)* | **Phase 1 manifest, committed at Phase 2b** — ⛔ **not** the literals `18 / 216 / 109`, which are unattestable (§1.1) *(superseded: "Phase 1 inventory vs 18 / 216 / 109")* |
| ⭐ **2** | **no record lost RELATIVE TO the frozen manifest and both reconciliation inputs** *(AMD3, `CL-1`)* | Phase 4 count + superset check + **the Phase-5 re-hash**. ⛔ **The absolute form is withdrawn** (§6.3) *(superseded: "no record silently lost")* |
| 3 | sequence integrity preserved | density + monotonicity check ⚠️ **for transitions; grants per §6.4** |
| 4 | provenance preserved | per-record provenance continuity |
| 5 | byte integrity preserved | **hash equality**, per file — ⭐ **and the `.gitattributes` pin (Phase 2b) is what keeps it re-checkable by a FUTURE verifier** |
| ⭐ **6** | **every DEFAULT authority-resolution path is unified** *(AMD3, `CL-3`)* | `P-1` and `P-2` replaced by one resolution (Phase 4b + Phase 5). ⚠️ **`P-3a/b` are NOT restricted — §7 stands, and override hardening is a separately authorized act** *(superseded: "all writers use one authority-resolution path")* |
| ⭐ **7** | **all readers resolve through the same boundary, and `P-4` is governed in its REPORTING form** *(AMD3, `CL-3`/`CL-10`)* | `P-2` retired; `P-4` under the resolver per `OPEN-M3` Option A; **§1.4's advisory limit stated**; ⚠️ **enforcement deferred to `OPEN-M5`** *(superseded: "`P-4` governed")* |
| 8 | runtime is not an authority source after cutover | ⭐ **Phase 5's demotion marker** *(the on-disk fact)* **and** Phase 6's demotion record *(the governance fact)* |
| 9 | migration evidence is itself durable | §5 |
| 10 | conflict handling follows the adopted invariant | §6, CASE A / CASE B, ⭐ **and §6.4 for grants** |
| ⭐ **11** | *(AMD3, `CL-1`)* **the migration window is shown to have been quiet, or its delta is reconciled as a named step** | **Phase 5 re-hash vs the frozen manifest** |
| 🔴 ⭐ **12** | *(AMD4, `RC-4`)* **the runtime copy is verified IMMEDIATELY BEFORE it is removed** | **Phase 7's FINAL re-hash vs `manifest + reconciled delta`** (§4.1). ⛔ **Criterion 11 does not imply this one — the Phase-5 re-hash precedes the writer switch, and the destructive act is three phases later** |
| ⭐ **13** | *(AMD4, `RC-5`)* **authority moved at ONE identifiable instant** | **Phase 5's mandated internal order — re-hash · readers · writer LAST** (§4.2) |

---

# 11 · Open architectural questions

| | Question | Why it is not closed here |
|---|---|---|
| **`OPEN-M1`** | **Does the governed evidence location remain gitignored?** `B′` requires durability; `.gitignore:25`/`:32` currently exclude the runtime path. **The target is under `docs/knowledgeos`, which is versioned — so the defect resolves by relocation** — but **the plan is forbidden to touch `.gitignore`**, and whether the runtime rule is later narrowed is a separate act | STOP constraint: no `.gitignore` change |
| **`OPEN-M2`** | **Phase 1/4 evidence precedes the Phase 2 target** (§5). Stage-then-commit, or create the target first? | Reordering the mandated phases is not Architecture's to do |
| ✅ **`OPEN-M3`** | ~~`P-4` is a second authority axis the decision did not record — is it within `RA-2`'s letter?~~ | ⭐ **CLOSED by PO/ARB: Option A, explicit scope extension** — the Single Authority Resolver governs **both** authority-record location **and** interpreter selection. **This entry is retained as history; the question is decided** |
| **`OPEN-M4`** | **The reader set is unbounded** (§1.4). The invariant is advisory for ad-hoc reads | A total guarantee would require a storage change — **a ledger — which the scope fence excludes** |
| ⭐ **`OPEN-M5`** *(AMD3, `CL-10`)* | **`INV-R3`'s ENFORCING form removes the seam that `T-13(b)` and `T-15` deliberately exercise. Does bringing `P-4` under governance authorize amending `AST-016`'s AMENDMENT-2 contract, or is enforcement a separate act after the migration?** | ⛔ **A contract question, not a boundary question. `OPEN-M3` is not reopened.** ✅ **The migration executes on the REPORTING form, so this does not gate it** |
| ⭐ **`OPEN-M6`** *(AMD3, §0.2)* | **AMD3 carries no registered grant.** The authorizing human act exists and is cited; its registration as `G-…-MIGRATION-PLAN-AMD3` is outstanding | ⛔ **Registration has exactly one writer — Governance** (`G-2`/`R5a`). **This process holds an Architecture role and will not register its own authorization** |

## 11.1 🔴 ⭐ **AMD4 (`RC-7`) — EXECUTION-GATE RECONCILIATION. AMD3 said *"two require another actor"*; there are SIX, and AMD3 itself introduced two of them.**

| Prerequisite | Actor | Gates | Status |
|---|---|---|---|
| **AMD3 + AMD4 registration** (`OPEN-M6`) | **Governance** — one writer, `G-2`/`R5a` | acceptance | ⏳ **OPEN.** Independently verified: **no `-AMD3` grant exists in the corpus**, and none for AMD4 |
| ⭐ **Phase 0 freeze declaration** | **PO/ARB** | **Phase 1** | ⏳ **required — introduced by AMD3 and not carried in its §11** |
| ⭐ **Phase 4b runtime-code authorization** | **PO/ARB** | **Phase 5** | ⏳ **required — introduced by AMD3 and not carried in its §11** |
| **`OPEN-M5`** — enforcing form / `AST-016` contract amendment | **PO/ARB** | ⛔ **nothing** | ⏳ open — ✅ **does not gate the migration: the reporting form (§3) changes no adopted contract** |
| **Plan acceptance** | **PO/ARB** | execution | ⏳ after registration |
| **`RC-1`…`RC-11` technical remediation** | **Architecture** *(this act)* | Phases 3 · 4b · 7 | ✅ **addressed by AMD4 — ⛔ NOT self-closed** (§0.4) |
| **`INFO-2`** lane-registration provenance | **Governance** | — | ⏳ open — ⛔ not a technical Architecture issue |
| `OPEN-M1` · `OPEN-M2` · `OPEN-M4` | PO/ARB | — | ⏳ unchanged; **`RC-1`'s split constrains `OPEN-M2` further without deciding it** |

⚠️ **`OPEN-M5` and `OPEN-M6` require another actor, and so do the Phase-0 and Phase-4b acts.** ⛔ **None is decided here, and `OPEN-M3` — now closed — is not reopened by any of them.**

---

# 12 · What this plan does NOT do

⛔ **No migration executed** · no file moved or copied · no default changed · **no `.gitignore` change** · ⭐ **no `.gitattributes` change** *(AMD3 REQUIRES the pin at Phase 2b and does not perform it)* · **no runtime code modified** — ⭐ **including the Phase-4b resolver, which AMD3 NAMES and does not build** · **no authority record modified** · **no grant registered** (§0.2) · no ledger introduced · no new placement rule · no repository layout chosen · no implementation technology selected · **`R-CONFLICT` not modified** — ⭐ **§6.4's grant rules are labelled *"Migration interpretation"* and are NOT invariant text** · no `--dir` restriction implemented · **`Increment 2` not proposed** · **no acceptance and no self-verification** · ⛔ **and AMD3 does not declare its own clarifications closed** (§0.1).

⛔ **AMD4 adds nothing to the DOING column.** Every `RC` remedy is a **sequencing, specification or wording change to this planning artifact**: `RC-1`/`RC-7b` reorder phases · `RC-2`/`RC-3` reconcile §3's cells · `RC-4` adds a precondition · `RC-5`/`RC-5b`/`RC-6`/`RC-8`/`RC-9` specify mechanism · `RC-10` records a design brief · `RC-11` narrows a claim. ⛔ **The `-text` pin is REQUIRED and NOT APPLIED. The Phase-4b resolver and the `RC-10` refusal guard are NAMED and NOT BUILT.**

**Verified after amending (2026-08-20, AMD4):** `.claude/runtime/` unchanged — **18 records, 216 transitions, 110 grants**, and **no `*.tmp*` remnant**; `.claude/scripts/` clean; `.gitignore` and `.gitattributes` untouched; **no durable target directory exists**; **no grant registered.**

---

**MIGRATION PLAN AMENDED (AMD4) · STOPPING.** ⛔ **THE MIGRATION IS NOT EXECUTED AND MUST NOT BE.**

> ## ⛔ **PHASE 3 MUST NOT BEGIN.** `RC-1`…`RC-11` are **`ADDRESSED · NOT CLOSED`** — §0.4.1 explains why this process cannot close them, and why its position is nonetheless stronger than AMD3's.
> ⭐ **The three gate points, so they cannot be lost in the list:** **`RC-1` gates Phase 3 · `RC-2`/`RC-3` gate Phase 4b · `RC-4` gates Phase 7** — and `RC-4` is the only one that guards an **irreversible** act.

**Next actors, in order:**

```
AMD4 (this amendment)
      ↓
FRESH INDEPENDENT technical review — neither 1c8b041b, nor bc1b47ef, nor 9c908e70 for its own findings
      ↓   ⭐ reviews the REMEDY again; the bound recurs until an amendment produces no new remedy
Governance registers AMD3 + AMD4          (OPEN-M6)
      ↓
PO/ARB — acceptance · OPEN-M5 if it chooses · the PHASE-0 FREEZE DECLARATION · the PHASE-4b AUTHORIZATION
      ↓
Migration execution — beginning at PHASE 0, never at Phase 3
      ↓
Post-migration verification
```

**Traceability (AMD4):** the PO/ARB commission of 2026-08-20 *(cited §0.4, **unregistered** — `OPEN-M6`)* · **the INDEPENDENT AMD3 remedy review by `claude-code-session:9c908e70`** — `RC-1`…`RC-11`, its §3.4 (`RC-4` sequence gap), §5.1 circularity test, §6 `CL`-by-`CL` classification, §7.3 (`RC-10`), §8 canonical-integrity findings, §12 dependency table, §13 verdict *(10 CLOSED · 2 ADDRESSED · 0 unclosed)* · AMD3 at **`bb1708b7`** · technical review **`a282d14b`** · `INFO-1` *(closed by `RC-11`)* · **contract evidence re-cited:** `SessionAssignmentResolverContractTest` harness `--dir=sys_get_temp_dir()`, `T-8`, `T-11`, `T-12`, `T-13(b)`, `T-15` · `WorkflowStateRecordContractTest:39,70` · `workflow-state.php:101–102` *(`RC-10`'s `mkdir`)* · `session-resolve.php:127,130` · `.gitattributes:1` · **0 of 18 records contain `CR`** · **no `-AMD3`/`-AMD4` grant exists in the corpus** · `INV-ATTR-2`/`G-2` · `G-2`/`R5a` · `R-34`/`P-2`.

**Traceability (AMD3):** the PO/ARB act of 2026-08-20 routing this amendment *(cited §0, **unregistered** — `OPEN-M6`)* · **the technical Architecture review `a282d14b`** — `CL-1`…`CL-12`, its §2 failure analysis, §3 concurrency experiment *(23/30 trials)*, §4 resolver analysis, §5 `R-CONFLICT` analysis, §6 freeze design · `G-KOS-GOV-STATE-DURABILITY-OPEN-M3-DECISION` *(Option A — closes `OPEN-M3`)* · the Governance completeness/provenance review *(`INFO-1` confirmed; `INFO-2` answered by §1.1 and §4)* · **re-measured 2026-08-20:** 18 records / 216 transitions / **110 grants** / **109 unique `grantId`s** / `seq` dense-and-monotonic 18-of-18 / **0-of-110 grants carry `seq`** / 2-of-216 transitions carry a time field / `git log` on any record → **empty** / `git check-attr text` → **auto** · `AST-016` contract `T-11`/`T-12`/`T-13`/`T-15` *(unamended)* · `workflow-state.php:303,309,311,326` · `session-resolve.php:90,103,130,156` · `INV-ATTR-2`/`G-2` · `G-2`/`R5a`/`R5b` · `R-34`/`P-2`.

**Traceability:** `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN` + `AMD1` (flags P/Q/R promoted to invariants; seven sections; CASE A/B) + `AMD2` (wording discipline; placement via the resolver; the Single Authority Resolver Invariant; the escalation trigger) · accepted design `ae451db9` · `B′` · the adopted `R-CONFLICT` invariant (quoted §6.1) · Decision 3 (existing placement governance) · `ADR_20260801_1740` + `scripts/doc-placement.php` (exit 0 → `docs/knowledgeos`) · **primary evidence read directly:** `.claude/scripts/workflow-state.php:25,81,100–107` · `.claude/scripts/session-resolve.php:22,74,90` · the five runtime-client shell scripts · `.gitignore:25,32` · all 18 workflow records · `RA-2` · `E-1` · `INV-ATTR-2`/`G-2` · `R-34`/`P-2`.
