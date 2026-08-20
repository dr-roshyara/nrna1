# AMD4 — **INDEPENDENT** Principal Architecture review (REMEDY validation)

**Work item:** `KOS-AIP-GOV-STATE-DURABILITY`
**Artifact reviewed:** `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` — **AMD4**, commit `0a2fa71d` *(secondary: the AMD4 summary at the same commit)*
**Input consumed, not reinterpreted:** `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD3-ARCHITECTURE-REVIEW.md` — `RC-1`…`RC-11` as its author stated them
**Date:** 2026-08-20
**Verdict:** 🟡 **PASS WITH DESIGN CLARIFICATIONS** — **11 of 11 `RC` items CLOSED · 7 new residuals · 3 document-integrity defects · ⛔ Phase 3 must not begin**

**Placement derived, not chosen:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos`, exit 0.

---

# 1 · Reviewer identity and independence

**Reviewer process:** `claude-code-session:870305e0`

| Excluded process | Role | Match? |
|---|---|---|
| `claude-code-session:1c8b041b` | original migration-plan producer | ✅ **no** |
| `claude-code-session:bc1b47ef` | technical review `a282d14b` · AMD3 · AMD4 producer | ✅ **no** |
| `claude-code-session:9c908e70` | independent AMD3 remedy review · author of `RC-1`…`RC-11` | ✅ **no** |
| `claude-code-session:5e1dd9ee` | implementation design `ae451db9` *(disclosure-only)* | ✅ **no** |

> **This reviewer has no prior authorship or material participation in the migration plan, AMD3, AMD4, or the prior independent reviews.** **Self-declared and NOT attestable** (`INV-ATTR-2`/`G-2`). **`R-34`/`P-2`: this review supplies evidence and a recommendation; it does not accept, and it accepts nothing of its own.**

⭐ **The bound in AMD4 §0.4.1 is DISCHARGED for `RC-1`…`RC-11`:** the findings are `9c908e70`'s, the remedies are `bc1b47ef`'s, and the judgement below is a third process's. **This review produces no remedy, so it hands no new self-review exposure to the next actor.**

---

# 2 · AMD4 governance status — disclosed, not resolved

| | |
|---|---|
| **AMD4** | 🟡 **PROPOSED · AMENDED — NOT REGISTERED** |
| **Grant** | ⛔ **none.** Independently verified: `grep -o 'G-KOS-GOV-STATE-DURABILITY[A-Z0-9-]*' .claude/runtime/workflow/*.json` returns `…-MIGRATION-PLAN`, `-AMD1`, `-AMD2`, `-DECISION`, `-OPEN-M3-DECISION` — **no `-AMD3`, no `-AMD4`** |
| **`OPEN-M6`** | ⏳ **OPEN — spans AMD3 and AMD4** |

⛔ **This review does not register AMD4, does not call it adopted, does not accept it, and manufactures no authority.** Registration has exactly one writer — Governance (`G-2`/`R5a`).

---

# 3 · `RC-1` … `RC-11` — defect · remedy · independent test · verdict

## `RC-1` — phase order → ⭐ **CLOSED**

**Defect:** Phase 2b (pin **and** commit-with-copy) was circular with Phase 3, whose precondition *"2b complete"* was therefore unsatisfiable.
**Remedy:** the phase split into `2b-pin` (before the copy) and `2c-commit` (with the copy); the table re-sorted into execution order `0 → 1 → 2 → 2b-pin → 3 → 2c-commit → 4 → 4b → 5 → 6 → 7`, rows numbered `1…11`, row order declared authoritative.

**Independent test — every row's precondition resolved against row order:**

```
row 1  0          human act                        ✅ satisfied
row 2  1          Phase 0 declared                 ✅ row 1
row 3  2          resolver exit 0                  ✅ external
row 4  2b-pin     Phase 2 target resolved          ✅ row 3
row 5  3          Phases 1 and 2b-pin complete     ✅ rows 2, 4      ← the former cycle
row 6  2c-commit  Phase 3 complete                 ✅ row 5
row 7  4          Phase 2c-commit complete         ✅ row 6
row 8  4b         Phase 4 PASSED                   ✅ row 7
row 9  5          Phase 4 PASSED · 4b landed       ✅ rows 7, 8
row 10 6          Phase 5 complete                 ✅ row 9
row 11 7          4 · 5 · FINAL re-hash            ✅ rows 7, 9, §4.1
```

✅ **No precondition references a later row. Phase 3 no longer consumes its own output. The manifest exists at row 2, four rows before the copy is committed. Manifest and copy are committed in one act at row 6. The sequence is mechanically coherent.**
⚠️ **Residual `RD-8` (document integrity):** three live statements still say *"Phase 2b"* where the split now means `2c-commit` — see §6 `DI-2`.

## `RC-2` — governed override → ⭐ **CLOSED**

**Defect:** `INV-R1`'s row-1 validation cell refused any location outside the governed evidence boundary — which is how **both** pinned contract suites drive **both** components, so a literal implementation makes the whole suite exit 65 with no report.
**Remedy:** `INV-R1` split into an **AUTHORITATIVE resolution** row (boundary-checked, refusal ⇒ exit 65) and a **GOVERNED OVERRIDE** row (`--dir` ⇒ **non-authoritative** output location, **no** validation, **no** refusal).

**Independent test — read from the test sources, not from either prior review:**

```
SessionAssignmentResolverContractTest::resolve()    :68  --dir=$this->dir   (sys_get_temp_dir()/kos-disc-…)
SessionAssignmentResolverContractTest::mechanism()  :81  --dir=$this->dir
SessionAssignmentResolverContractTest T-14 human    :379 --dir=$this->dir
WorkflowStateRecordContractTest::cli()              :70  --dir=$this->dir   (sys_get_temp_dir()/kos-orch-…)
```

✅ **EVERY invocation in both suites passes through a `--dir` temp path — there is no un-overridden call site in either suite.** ⇒ **the premise is confirmed independently, and row 2 is what preserves the suites.**
✅ **`B′` is NOT reintroduced through invocation semantics:** authority attaches to the **authoritatively resolved** location; an override produces bytes and no authority (§3 row 2, §7's invariant, §9's *storage ≠ ownership*). The override row loosens **validation**, never **authority** — and §3.1 places the refusal that guards the authoritative branch **before** `saveRecord`.
⚠️ **Residual `RD-4`:** because every pinned call is overridden, **the AUTHORITATIVE branch — the boundary refusal, exit 65, and `RC-10`'s refusal-before-`mkdir` — has NO pinned coverage in either suite and cannot be regression-verified by them.** Phase 4b must supply its own RED tests for the authoritative branch; otherwise the only refusal path the design specifies is the one nothing tests.
⚠️ **Residual `RD-5`:** an override output directory receives **no marker** (§4.3 defines exactly two: staging, runtime). Bytes written under `--dir` are on-disk indistinguishable from evidence; separation rests on location + provenance. **Already bounded by §1.4 and `OPEN-M4` — recorded, not a new exposure.**

## `RC-3` — reporting vs enforcing → ⭐ **CLOSED**

**Defect:** §3's table stated the **enforcing** form four lines above the ruling that the migration executes the **reporting** form — the one *conflicting current definition* in the artifact.
**Remedy:** row 3 = **REPORTING**, marked ✅ *"THIS IS WHAT PHASE 4b IMPLEMENTS"*; row 4 = **ENFORCING**, marked ⛔ *"DEFERRED TO `OPEN-M5`, NOT CURRENT"*.

**Independent test — read in the test bodies:**

| Test | What it pins | Under the ENFORCING form |
|---|---|---|
| **`T-15`** `:393` | sets `KOS_MECHANISM_PATH` to a copy of the mechanism; asserts `interpreter.path` **follows the substitute** and verdict is **`RESOLVED`** — *"identity is information only — a substituted interpreter is reported, never refused"* | 🔴 **breaks outright** |
| **`T-13(b)`** `:439` | sets it to `/nonexistent`; asserts a report is produced naming the unavailable mechanism via `json_encode($degraded['out'] ?? $degraded['raw'])` | 🔴 **second assertion breaks** — a refusal that emits nothing leaves `out` null and `raw` `""`, which contains no `"mechanism"` |
| ⭐ **`T-14`** `:354` | **the strongest, and AMD4 does not cite it:** its docblock is the contract — *"Identity is INFORMATION ONLY: reported, never validated, never compared against the registry, never a ground for refusal (grant `G-KOS-DISC-C12-IMPL`)"* — asserted across **all four verdicts in both renderings** | 🔴 **contradicted as a contract, not merely as behaviour** |

✅ **Exactly one CURRENT definition per row. The remedy is correct and, on `T-14`, better founded than AMD4 argues.**
⚠️ **Residual `RD-6` — an INPUT to `OPEN-M5`, ⛔ not a decision on it:** `T-14` makes the reporting form an **adopted contract bound to its own grant**, not just today's behaviour. ⇒ **the enforcing form would require amending `T-14`'s contract in addition to `AST-016` AMENDMENT-2.** **`OPEN-M5`'s scope is larger than AMD4 states. `OPEN-M5` remains open and undecided here.**

## `RC-4` — final pre-removal integrity → ⭐ **CLOSED** *(the guard holds; the disposition branch is under-specified — `RD-3`)*

**Defect:** the last integrity check was the Phase-5 re-hash, three phases before Phase 7 destroyed the runtime copy; the bounded completeness claim (*"relative to the frozen manifest and the observed inputs"*) is self-satisfying against a gap-window write and cannot catch it.
**Remedy:** §4.1 — Phase 7's precondition is a **FINAL re-hash of the runtime source against `manifest + reconciled delta`**; identical ⇒ removal permitted; different ⇒ **STOP, do not remove, reconcile under §6, re-verify with Phase-4 all-or-nothing semantics, only then remove.** New acceptance criterion 12; fourth §8 trigger.

**Independent evasion test — can any write between Phase 5 and Phase 7 escape the check?**

| Write | Detected? |
|---|---|
| **append to an existing runtime record** *(gap window: after the Phase-5 re-hash, before the writer switch)* | ✅ **yes — bytes differ from `manifest + delta`** ⇒ STOP. **This is exactly the case `RC-4` named** |
| **append after the writer switch** *(via `P-3a` `--dir`, an ad-hoc write, or an unswitched client)* | ✅ **yes — same comparison** ⇒ STOP |
| **a NEW record appearing in runtime** *(e.g. a work item registered during the window)* | ✅ **yes — "identical" is set-wise, and Phase-4 semantics are all-or-nothing: a PASS set that does not exhaust the reference object is a FAIL** |
| **a record REMOVED from runtime** | ✅ **yes — same reason, in the other direction** |
| ⚠️ **a write inside the interval between the FINAL re-hash and the removal itself** | ⛔ **no — irreducible without a lock.** `Increment 2` is unauthorized, so this cannot be closed by this plan. **The window shrinks from three phases to one step, which is the whole gain** |

✅ **The destructive act is guarded. The check costs 18 hash comparisons against an object that already exists. `§4.2`'s justification for Phase 7 — *"a redundant, demoted, BYTE-VERIFIED copy"* — is now true at the moment of removal.**
🔴 **Residual `RD-3` — the mismatch branch routes to a model that does not cover its own trigger.** §4.1 sends a Phase-7 mismatch to *"reconcile under §6 (CASE A / CASE B)"*, but §6.3 scopes the divergence window to **Phases 3–5**, and CASE A **appends the runtime tail as a legitimate extension**. A Phase-7 mismatch has two causes with **opposite correct dispositions:**

```
pre-writer-switch  (gap-window write, runtime still authoritative)  →  §6 reconciliation is CORRECT
post-writer-switch (a write to a DEMOTED store)                     →  §6 CASE A would IMPORT
                                                                       non-authoritative bytes into the
                                                                       authoritative store — and §8 says
                                                                       re-promoting a demoted source is a
                                                                       GOVERNANCE ACT, not a merge
```

⇒ **recommend: split the Phase-7 mismatch disposition — gap-window writes reconcile under §6; post-demotion writes are QUARANTINED and ESCALATED, never silently imported.** ✅ **Safety is unaffected either way: the STOP is unconditional and nothing is destroyed. This is a disposition defect, not a destruction defect.**

## `RC-5` — authority transfer → ⭐ **CLOSED** *(two residuals)*

**Defect:** Phase 5 is not atomic — it switches `P-1`, `P-2`, `P-4` — so *"on completion of Phase 5"* is not an instant, and §8's *"before Phase 5 ⇒ stop"* was false for the interval after the writer moved.
**Remedy:** §4.2 mandates `1 re-hash · 2 readers (P-2, P-4) · 3 WRITER (P-1) — the transfer instant · 4 markers`; §8's boundary restated to *"the writer switch"*.

**Independent test:**
✅ **The order is directionally justified and the justification holds:** readers-first yields a *briefly-stale-read* window already bounded by the re-hash; writer-first would yield an *invisible-write* window. **`RC-5`'s recommendation is implemented as recommended.**
✅ **There is genuinely only one writer to order** — verified independently: `grep -rln file_put_contents .claude/scripts/` returns three files, of which only `workflow-state.php:105` both references `runtime/workflow` and writes.
✅ **Rollback semantics are coherent across all four windows** (§8): before the writer switch = STOP; after it and before Phase 6 = **reconciliation, safe direction forward**; after Phase 6 = a governance act; at Phase 7 on mismatch = stop-and-reconcile, not rollback.
⚠️ **Residual `RD-1`:** §4 row 9 requires *"changed ⇒ reconcile the delta under §6 as a NAMED STEP"*, but **§4.2's four-step order has no slot for it.** Read literally, readers switch (step 2) to a store whose reconciliation has not yet run — readers would see a store missing the delta. ⇒ **place reconciliation explicitly as step `1b`, between the re-hash and the reader switch.** One clause.
⚠️ **Residual `RD-2`:** the demotion marker is step **4**, after the writer switch at step **3** — so a *stale-runtime-with-no-on-disk-signal* window survives inside Phase 5. It is the same class §8 closes for the 5→6 window, now one step wide and not further reducible without atomicity. **Record it; do not block on it.**

## `RC-5b` — dormant resolver → ⭐ **CLOSED**

✅ **§3 `INV-R5` and §4 row 8 state it in the deciding dimension:** Phase 4b delivers the resolver **built, tested, NOT WIRED**; *"Phase 4b changes no component's effective resolution, so it is NOT a switch and cannot collapse into Phase 5"*; Phase 5 is *"the SOLE activating act"*. ✅ **The activation instant is no longer left to inference, which is precisely `RC-5b`'s ask.** ✅ **Consistent with `RC-5`: the sole activation event contains the sole transfer instant.**

## `RC-6` — markers → ⭐ **CLOSED** *(all five asked properties; one NEW gap — `RD-7`)*

| `RC-6` asked for | §4.3 delivers | Independent check |
|---|---|---|
| granularity | **directory-level, one per store** | ✅ |
| out-of-band placement | **in-file marking FORBIDDEN** | ✅ **and correctly grounded — an in-file marker would change evidence bytes and invalidate every manifest hash** |
| not `*.json` | ⛔ **MUST NOT match `*.json`** | ✅ **verified: `session-resolve.php:130` is `glob($recordDir.'/*.json')`, and Phase 1's predicate is the same glob** |
| the two markers | staging **NON-AUTHORITATIVE** · runtime **DEMOTED** | ✅ |
| residual reach stated | **ADVISORY** for the §1.4 reader class | ✅ **not assumed away** |

✅ **Markers do not become a second authority source:** they are visible signals of a status decided elsewhere; §2 keeps derived state out of authority, and §9 keeps location out of ownership.
🔴 **NEW gap `RD-7` — no phase RETRACTS the staging marker.** §4.3 gives its lifetime as *"from Phase 2 until Phase 5"*, but **Phase 5's mandated step 4 writes only the runtime DEMOTED marker**, and no row removes the staging one. Since the staging store is committed at `2c-commit`, the consequence is durable and versioned: **after the writer switch the AUTHORITATIVE store carries an on-disk NON-AUTHORITATIVE label** — the exact reader-visible contradiction the marker exists to prevent. ⇒ **add the retraction to the Phase-5 act, ordered after the writer switch.** *(Same defect shape as `RC-1` and `RC-7`: a required act named in a specification and not carried by any phase.)*
⚠️ **Minor:** §10 criterion 8 offers the demotion marker as acceptance evidence alongside Phase 6's record. Since §4.3 calls the marker **advisory**, recommend criterion 8 name the Phase-6 record as the demonstration and the marker as corroboration.

## `RC-7a` — order invariant → ⭐ **CLOSED**

✅ **All three additions are enumerated, including the one that lands inside the 2–4 span** (`2b-pin`/`2c-commit`), with the reason `INV-ORDER` survives: both **create** durability guarantees.
✅ **Independently checked against the executable order:** durability is created (rows 4–6) and verified (row 7) **before** demotion (rows 9–10) and **before** removal (row 11). **`INV-ORDER` holds for every added phase.** ✅ **`OPEN-M2` is constrained further and not decided.**

## `RC-7b` — execution table → ⭐ **CLOSED**

✅ **Rows are numbered `1…11` in execution order, labels retained for lineage, row order declared authoritative — and the ordering is not merely asserted: the precondition audit in `RC-1` above confirms it.**
⚠️ **`RC-7`'s dependency table (§11.1):** the six prerequisites `RC-7` asked for are present **with actors**, including the Phase-0 and Phase-4b acts AMD3 dropped. ⛔ **But the heading claims *"there are SIX"* over a table of EIGHT rows, and no reading of the rows yields six** — the same enumeration-versus-change-set mismatch `RC-7a` was raised for, one level down. See §6 `DI-3`.

## `RC-8` — byte preservation → ⭐ **CLOSED** *(one residual)*

**Independent verification of the premise:**
```
.gitattributes:1   * text=auto        ← normalisation is ON for every path by default
CR bytes in the 18 records            ← 0
```
✅ **`text eol=lf` is WITHDRAWN with the correct reason** (it keeps normalisation on and would silently rewrite a future record containing a raw `CR`); **`-text` only**, labelled as the withdrawn alternative rather than deleted.
✅ **The *"0 of 18 today"* measurement is correctly refused as a basis** — the claim is about a future verifier.
✅ **Hash semantics are explicit and unambiguous: SHA-256 over WORKING-TREE bytes, not the git blob** — which is the right subject, since the blob is what normalisation would alter.
⚠️ **Residual `RD-9`:** §10 criterion 5 claims the pin *"keeps it re-checkable by a FUTURE verifier"* — that requires the pin to be **committed**. `2b-pin` produces *"the pin"*; `2c-commit` commits *"manifest + copy"*. ⇒ **state that `2c-commit` includes `.gitattributes`.** *(The copy's own bytes are safe either way: check-in normalisation obeys the working-tree attributes at commit time.)*

## `RC-9` — canonical comparison → ⭐ **CLOSED**

✅ **Defined: recursive key ordering before encoding**, with the PHP-specific reason (`json_decode`→`json_encode` preserves **insertion** order).
✅ **Comparison-only, stated twice, and the apparent contradiction with §4.1's copy rule is resolved explicitly** — *"§4.1 forbids parse-and-reserialize for the COPY, not for the COMPARISON"*. **Copy bytes are never modified.**
✅ **CASE A:** element-wise over the decoded `transitions` array, element `i` vs element `i`, strict extension ⇒ append the tail. **The file-level byte-prefix comparison is correctly rejected** — verified from the writer: `saveRecord` rewrites the whole file each append.
✅ **CASE B:** preserve both · record the conflict · no silent winner · escalate. ✅ **§6.4's grant model uses the same canonical re-encode positionally and forbids `grantId` as a key — independently confirmed: 110 grants, 109 unique `grantId`s, 0 carrying `seq`.**
⚠️ **Minor:** the *encoding* is not fully pinned (sort collation, unicode/slash escaping, number formatting). Determinism holds within one run because both sides use one encoder, and the failure direction is safe (false CASE B ⇒ escalate). **One clause naming the encoder flags would close it.**

## `RC-10` — refusal before `mkdir` → ⭐ **CLOSED** *(as a recorded design brief)*

**Independently verified in the writer:**
```php
function saveRecord(string $path, array $record): void {
    if (!is_dir(dirname($path))) { mkdir(dirname($path), 0777, true); }   // :101–102
```
✅ **The hazard is real and correctly characterised: a wrong-but-well-formed path does not fail — it MANUFACTURES a new evidence location.** ✅ **§3.1 orders it correctly — authority validation **before** `saveRecord`, therefore before `mkdir`** — and ties it to the right place, the AUTHORITATIVE branch of `INV-R1`, so `RC-2`'s override row cannot be read as loosening it.
✅ **Correctly NOT implemented** — runtime code is outside the artifact's fence (§12), and `RC-10` asked for a design brief.
⚠️ **See `RD-4`:** nothing in either pinned suite exercises this path, so Phase 4b must pin it with new RED tests.

## `RC-11` — writer claim → ⭐ **CLOSED**

✅ **Exact wording adopted: *"ONE WRITER OF GOVERNANCE EVIDENCE"***, with the superseded heading *"Writers — exactly one"* explicitly labelled as superseded (permitted by §0.4.4).
✅ **The discriminating test is stated and correct — *references `runtime/workflow` AND writes* — and independently reproduced:**

| `grep -rln file_put_contents .claude/scripts/` | Disposition | Independent check |
|---|---|---|
| `workflow-state.php:105` | ✅ the one writer of governance evidence | ✅ `file_put_contents($tmp,…)` + `rename` |
| `session-resolve.php:22` | a docblock **mention** | ✅ inside the read-purity docblock; no call in the file |
| `session-changes-logger.sh:60` | a real write that never touches `runtime/workflow` | ✅ session state |

⛔ **Neither forbidden restoration is present:** *"exactly one writer"* appears only as labelled superseded wording, and the plan states the opposite of *"arbitrary writes are impossible"* — *"⛔ This plan does not claim that arbitrary writes to the directory are impossible"*, consistent with §1.4 and `INV-R4`. ✅ **`INFO-1` is closed on its wording axis.**

---

# 4 · Cross-cutting review — the full state machine

```
freeze → inventory → target → byte pin → copy → commit → verify
       → dormant resolver → pre-switch re-hash → readers → writer
       → demotion → final re-hash → removal
```

| Failure case | Handling | Verdict |
|---|---|---|
| **concurrent append, Phase 0 → 1** | declaratory freeze; detected by the Phase-5 re-hash; **loss inside the tail remains undetectable and is stated** | ✅ bounded and honestly bounded |
| **concurrent append, Phase 3 → 5** | §6 CASE A/B on the re-hash delta | ✅ |
| **concurrent append, Phase-5 re-hash → Phase 7** | 🔴 was unbounded; **now caught by the FINAL re-hash** | ✅ **`RC-4` closed** — disposition caveat `RD-3` |
| **interruption 2b-pin → 3** | nothing copied; runtime authoritative | ✅ |
| **interruption 3 → 2c-commit** | copy uncommitted; `INV-ORDER` intact | ✅ |
| **interruption 2c → 4** | copy committed, unverified — **and marked NON-AUTHORITATIVE**; this is what the staging marker buys | ✅ |
| **interruption inside Phase 5** | step 1→2 stale-read window (re-hash-bounded) · step 2→3 readers ahead of the writer (stale reads only) · step 3→4 **unmarked stale runtime — `RD-2`** | ⚠️ bounded, one residual |
| **partial copy** | Phase 4 **all-or-nothing** against the frozen manifest | ✅ |
| **partial verification** | a PASS set that does not exhaust the manifest is a **FAIL, not progress** | ✅ |
| **failed writer switch** | §8 trigger *"Phase 5 leaves any component unswitched"*; single call site | ✅ |
| **resolver failure after Phase 5** | `INV-R1` refuses (exit 65) rather than falling back to runtime; §3.1 stops a wrong path from materialising | ✅ **fail-stop, coherent end to end** |
| **final re-hash mismatch** | STOP · reconcile · re-verify · never remove | ✅ **guard sound; `RD-3` on the branch** |
| **CASE A / CASE B** | union-preserving · no silent winner · escalation | ✅ subject to `RD` on encoder flags |
| **rollback, all four windows** | STOP · reconciliation · governance act · stop-and-reconcile | ✅ coherent, and consistent with §4.2 |

## 4.1 🔴 **`RD-10` — the freeze is not self-consistent for the migrating lane.** *(new, cross-cutting)*

**Phase 0 declares that *"no lane may append to the corpus."* But the migration's own governance acts ARE corpus appends** — every transition and grant is written by `P-1` into `.claude/runtime/workflow/*.json`, which is the store being frozen and hashed. §1.3 flags *"the migration WRITES INTO THE CORPUS IT MIGRATES"*; **no phase says what happens to those writes.** Consequences, in order:

```
Phase 1 manifest is stale the moment the migration records its own next transition
Phase 5 re-hash therefore reports a DELTA in the normal case
⇒ criterion 11's "the window was PROVABLY quiet" branch is unreachable by construction
⇒ the plan's headline gain — unprovable claim → provable claim — degrades to "always reconcile"
⇒ and a genuine freeze VIOLATION becomes indistinguishable from the migration's own bookkeeping
```

⚠️ **`OPEN-M6`'s registration, plan acceptance, the Phase-0 declaration itself and Phase 6's demotion record are all corpus writes.** ⇒ **recommend: the Phase-0 declaration explicitly carves out the migration lane's own records and names them as expected delta inputs, so a violation stays detectable.** ⛔ **This constrains `OPEN-M2` further; it does not decide it, and this review does not reorder any phase.**
✅ **Not unsafe:** the migration's own appends are strict extensions, so CASE A handles them. **The loss is evidential clarity, not integrity.**

---

# 5 · Open questions — status preserved, none closed here

| | Status | Note from this review |
|---|---|---|
| **`OPEN-M1`** `.gitignore` | ⏳ **OPEN** | untouched |
| **`OPEN-M2`** Phase 1/4 evidence vs Phase 2 target | ⏳ **OPEN** | ⚠️ **further constrained by `RC-1`'s split and by `RD-10`. NOT decided. No phase is reordered here** |
| ✅ **`OPEN-M3`** | **CLOSED** (Option A) | ⛔ **not reopened** |
| **`OPEN-M4`** unbounded readers | ⏳ **OPEN** | `RD-5` sits inside its envelope |
| **`OPEN-M5`** enforcing form / `AST-016` | ⏳ **OPEN** | ⭐ **`RD-6`: scope is LARGER than AMD4 states — `T-14`'s contract is also implicated. ⛔ NOT decided here** |
| **`OPEN-M6`** AMD3 + AMD4 registration | ⏳ **OPEN** | ✅ independently verified: no `-AMD3`/`-AMD4` grant exists |
| **`INFO-2`** lane-registration provenance | ⏳ **OPEN** | a Governance matter |
| **`B′` · `R-CONFLICT` · `INV-ORDER` · placement governance · Option D** | **FIXED** | ⛔ **not reopened; AMD4 changes none of them — independently confirmed** |

---

# 6 · Document integrity

⛔ **This review does NOT record *"no material canonicalization defect found."*** **Three defects; two are material because they land on the acceptance object and on a gate-class precondition's own cross-reference.**

| | Defect |
|---|---|
| 🔴 **`DI-1`** | ⭐ **DUPLICATE CURRENT SECTION IDENTIFIERS, introduced by AMD4 itself.** `## 4.1` occurs at line 349 *(AMD4 `RC-4`, the final integrity check)* **and** line 436 *(Phase 3 — what byte-preserving forbids)*; `## 4.2` occurs at line 404 *(AMD4 `RC-5`, the transfer instant)* **and** line 442 *(Phase 7 — removal is not deletion)*. **Live cross-references bind to both senses:** §4 row 11 *"see §4.1"* → 349 · §4.3 *"break §4.1's byte preservation"* → 436 · §6.3 *"never touches the copied bytes (§4.1)"* → 436 · §4.1 *"§4.2's justification for Phase 7"* → 442 · §4 row 10 *"§4.2"* → 404. ⇒ **the reference in `RC-4`'s own Phase-7 precondition is ambiguous, and the AMD4 summary propagates the collision into a second artifact.** ⚠️ **Section order is also non-monotonic — 4.1, 4.3, 4.4, 4.2, 4.0, 4.1, 4.2 — while `RC-7b` established for this artifact that order is authoritative. Renumber the AMD4 additions; the fix is mechanical and changes no decision** |
| 🔴 **`DI-2`** | ⭐ **STALE *"Phase 2b"* AFTER `RC-1`'s SPLIT — in two live places.** §5: *"the Phase-1 frozen manifest is committed at Phase 2b, in the same commit as the Phase-3 copy"* — **impossible as written**, since `2b-pin` executes **before** Phase 3; the commit is `2c-commit`. §10 **criterion 1**: *"Phase 1 manifest, committed at Phase 2b"* — **the acceptance object names a phase that no longer commits it.** *(§0.3 row 7 and §12 keep *"Phase 2b"* as AMD3 lineage — correctly labelled, not defects. §10 criterion 5's *"the pin (Phase 2b)"* is benign shorthand for `2b-pin`.)* |
| ⚠️ **`DI-3`** | §11.1's heading asserts *"there are SIX"* over an **eight-row** table, and no grouping of the rows yields six. **The enumeration does not match its own content — the defect shape `RC-7a` was raised for** |
| ✅ | **Obsolete wording presented as current:** ⛔ **none found.** Every superseded item is explicitly labelled — §1.3's heading · §3 row 4 · §4.4's withdrawn form · §6.3's withdrawn invariant · §8's superseded rule · §10's superseded criteria 1/2/6/7. **The labelled amendment history is correct practice and is not a defect** |
| ✅ | **Conflicting current definitions:** ⛔ **none.** `RC-3`'s was the only one and it is repaired. ⚠️ `DI-2` is a **stale reference**, not a competing definition |
| ✅ | **Tool/edit transcript residue:** ⛔ **none found** |

---

# 7 · DDD / knowledge-engineering check

| Distinction | Held? |
|---|---|
| **Execution state ≠ governance evidence** | ✅ §1.6 proves it on real files — five runtime clients stay, evidence moves; §2's four-boundary table |
| **Evidence ≠ proof** | ✅ **the sharpest thing in the artifact:** density is *preserved by* the loss, so the completeness claim is bounded to the manifest and the observed inputs, and the absolute form stays withdrawn (§6.3, §10 criterion 2) |
| **Evidence identity ≠ authority** | ✅ `grantId` collision (110/109, verified) forbidden as a key; identity is reported and never validated (`RC-3`) |
| **Authority ≠ storage location** | ✅ `RC-2` row 2 is exactly this: an override selects bytes and confers nothing. §9 states the accident `B′` corrects |
| **Derived state ≠ authoritative state** | ✅ fold, `authorityState`, resolver answers, markers — all recomputable/advisory, never a second source. ⚠️ minor: §10 criterion 8 leans on the marker as evidence |
| **Historical ≠ canonical future location** | ✅ Phase 7 is removal of a redundant verified copy, not deletion; the final re-hash makes that true **at the moment of removal** rather than as of Phase 4 |

✅ **No new bounded context, no ledger, no second authority boundary, no decision changed. `Increment 2` is not proposed.**

---

# 8 · Residual risks

| | Risk | Class | Gates |
|---|---|---|---|
| 🔴 **`RD-7`** | **no phase retracts the staging NON-AUTHORITATIVE marker** ⇒ after the writer switch the authoritative store is on-disk labelled non-authoritative, durably and versioned | design gap | **Phase 5 completion** |
| 🔴 **`RD-3`** | **Phase-7 mismatch disposition routes post-demotion writes into §6 CASE A**, which would import non-authoritative bytes that §8 says require a governance act | design gap | **Phase 7** |
| 🔴 **`RD-10`** | **the freeze does not exempt the migration's own corpus appends** ⇒ criterion 11's *"provably quiet"* branch is unreachable and a real violation is masked | design gap | **Phase 0 declaration** |
| ⚠️ **`RD-1`** | Phase 5's mandated order has **no slot for the reconciliation step** row 9 requires | specification | Phase 5 |
| ⚠️ **`RD-4`** | **the AUTHORITATIVE branch of `INV-R1` — boundary refusal and refusal-before-`mkdir` — has no pinned test coverage**; both suites override every call | test readiness | **Phase 4b** |
| ⚠️ **`RD-9`** | the `-text` pin must be **committed** for criterion 5's future-verifier claim to hold; `2c-commit` does not name `.gitattributes` | specification | Phase 2c |
| ⚠️ **`RD-6`** | **`OPEN-M5`'s scope is larger than stated** — `T-14` pins the reporting form as a contract, so enforcement implicates it too *(input to PO/ARB, ⛔ not a decision)* | governance input | — |
| ⚠️ **`RD-2`** | one-step unmarked stale-runtime window inside Phase 5 (step 3→4); irreducible without atomicity | accepted residual | — |
| ⚠️ **`RD-5`** | `--dir` output directories carry no marker; inside `OPEN-M4`'s existing envelope | accepted residual | — |
| ⚠️ **`RD-8`/`DI-1`/`DI-2`/`DI-3`** | duplicate `§4.1`/`§4.2` identifiers · stale *"Phase 2b"* in §5 and §10 criterion 1 · the *"SIX"* count | document integrity | **acceptance** |
| ⚠️ **pre-existing, not AMD4's** | read-modify-write without lock/lease/CAS (`Increment 2`) · crash durability (no `fsync`) · unbounded ad-hoc readers (`OPEN-M4`) | out of scope | — |

---

# 9 · Verdict

| `RC` | Verdict |
|---|---|
| **`RC-1`** phase order | ⭐ **CLOSED** — circularity removed; all 11 preconditions verified against row order |
| **`RC-2`** governed override | ⭐ **CLOSED** — suites preserved; `B′` not reintroduced via invocation · `RD-4`, `RD-5` |
| **`RC-3`** reporting vs enforcing | ⭐ **CLOSED** — one current definition per row; `T-14` strengthens it · `RD-6` |
| **`RC-4`** final pre-removal integrity | ⭐ **CLOSED** — no write between Phase 5 and Phase 7 evades detection; removal refused on mismatch · `RD-3` |
| **`RC-5`** authority transfer | ⭐ **CLOSED** — instant defined, order mandated, §8 corrected · `RD-1`, `RD-2` |
| **`RC-5b`** dormant resolver | ⭐ **CLOSED** |
| **`RC-6`** markers | ⭐ **CLOSED** on all five asked properties · 🔴 **new gap `RD-7`** |
| **`RC-7a`** order invariant | ⭐ **CLOSED** |
| **`RC-7b`** execution table | ⭐ **CLOSED** · `RC-7`'s dependency table present · `DI-3` |
| **`RC-8`** byte preservation | ⭐ **CLOSED** — premise independently verified (`* text=auto`, 0 `CR`) · `RD-9` |
| **`RC-9`** canonical comparison | ⭐ **CLOSED** |
| **`RC-10`** refusal before `mkdir` | ⭐ **CLOSED** as a design brief · `RD-4` |
| **`RC-11`** writer claim | ⭐ **CLOSED** — wording exact; forbidden restorations absent |

## 🟡 **OVERALL: PASS WITH DESIGN CLARIFICATIONS**

⛔ **NOT BLOCKED, and the four `BLOCKED` conditions are tested one by one:**

| Condition | Finding |
|---|---|
| **a remedy is technically unsound** | ⛔ **no.** Eleven of eleven establish the property claimed; every premise I re-derived from the code held |
| **a mandatory gate is impossible** | ⛔ **no.** `RC-1`'s gate is now satisfiable and `RC-4`'s costs 18 hash comparisons. `RD-7`, `RD-3` and `RD-10` are acts to name, not gates to invent |
| **fixed architecture violated** | ⛔ **no.** `B′`, `R-CONFLICT`, `OPEN-M3` Option A, placement governance, `INV-ORDER` and Option D are intact; no decision changed; no second authority boundary |
| **the migration remains unsafe** | ⛔ **no.** `RC-4`'s guard makes the one irreversible act conditional on a check immediately preceding it, and every mismatch branch is a STOP |

⚠️ **But three design gaps and two document-integrity defects must be repaired before execution** — `RD-7` (staging marker retraction) · `RD-3` (Phase-7 disposition split) · `RD-10` (freeze self-consistency) · `DI-1` (duplicate `§4.1`/`§4.2`) · `DI-2` (stale *"Phase 2b"* in §5 and §10 criterion 1). ⭐ **Each is a wording, sequencing or enumeration repair by the plan's owner inside the current envelope. None requires a decision, and none reopens anything.**

⛔ **`PHASE 3 MUST NOT BEGIN`** — and not because of this verdict: `OPEN-M6` registration, the Phase-0 freeze declaration, the Phase-4b authorization and plan acceptance all remain outstanding, and every one of them belongs to another actor.

---

# 10 · Explicit non-decisions and limitations

⛔ **This review did NOT:** modify AMD4 · register AMD4 · accept AMD4 · execute or begin any migration act · modify runtime code · touch `.gitignore` or `.gitattributes` · decide `OPEN-M5` or `OPEN-M6` · reopen `B′`, `R-CONFLICT`, `OPEN-M3` · reorder any phase · write or propose any remedy text.
⚠️ **Limitations, stated rather than implied:** the reviewer's identity is **self-declared and not attestable** (`INV-ATTR-2`) · findings rest on the artifact plus the repository as of `d7507da4`, not on any execution · the corpus has **no git history** (verified: `git log` on any record is empty), so its content is unattestable by anyone, which is the defect `B′` exists to close · I did **not** run the contract suites, only read them, so my `T-13(b)`/`T-14`/`T-15` conclusions are static analyses of the assertions · `RD-4` is a coverage observation, not an assertion that Phase 4b will fail.

**Next actors, in order:**

```
this review
      ↓
the plan's owner repairs RD-7 · RD-3 · RD-10 · DI-1 · DI-2          (no decision required)
      ↓   ⭐ AMD5's remedies are again unreviewed — the bound recurs
Governance registers AMD3 + AMD4                                    (OPEN-M6)
      ↓
PO/ARB — acceptance · OPEN-M5 if it chooses (⚠️ RD-6 enlarges it)
      ↓
PO/ARB — the PHASE-0 FREEZE DECLARATION (⚠️ RD-10) · the PHASE-4b AUTHORIZATION (⚠️ RD-4)
      ↓
Migration execution — beginning at PHASE 0, never at Phase 3
```

**Traceability:** AMD4 at **`0a2fa71d`** · AMD3 at `bb1708b7` · technical review `a282d14b` · **the independent AMD3 review by `claude-code-session:9c908e70`** (`RC-1`…`RC-11`, §3.4, §5.1, §7.3, §8, §11, §13) · accepted design `ae451db9` · **primary evidence read directly and re-derived, not quoted from a prior review:** `.claude/scripts/workflow-state.php:81,101–102,105,303–311` · `.claude/scripts/session-resolve.php:22,74,90,103,127,130,156` · `tests/Unit/Platform/WorkflowEngine/SessionAssignmentResolverContractTest.php:44,68,81,310,330,354,379,393,415,439` · `tests/Unit/Platform/WorkflowEngine/WorkflowStateRecordContractTest.php:39,70` · `.gitattributes:1` (`* text=auto`) · `.gitignore:25,32` · `git check-ignore` → `.gitignore:32` · **re-measured 2026-08-20: 18 records · 216 transitions · 110 grants · 109 unique `grantId`s · 0 of 110 grants carry `seq` · 0 records contain `CR` · 0 `*.tmp*` remnants · no `-AMD3`/`-AMD4` grant · `git log` on any record → empty** · `INV-ATTR-2`/`G-2` · `G-2`/`R5a` · `R-34`/`P-2`.

**INDEPENDENT REVIEW DELIVERED · STOPPING.** ⛔ **THE MIGRATION IS NOT EXECUTED, NOT AUTHORIZED, AND MUST NOT BEGIN.**
