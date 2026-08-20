# AMD3 — **INDEPENDENT** technical Architecture review (REMEDY review)

**Artifact under review:** `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` @ **`bb1708b7`** (498 lines) — **Amendment 3**
**Work item:** `KOS-AIP-GOV-STATE-DURABILITY` · **Prior gate:** technical Architecture review `a282d14b` (`PASS WITH DESIGN CLARIFICATIONS` · `CL-1`…`CL-12` · `CL-1`/`CL-2`/`CL-3` = gates on Phase 3)
**Date:** 2026-08-20
**Placement derived, not chosen:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → **`docs/knowledgeos`**, **exit 0**; `reviews/` matches the artifact class.

---

# 1 · Reviewer identity and independence

**Reviewer process, declared before the artifact was opened:** `claude-code-session:9c908e70` (`9c908e70-665d-4cad-af8f-12741257f8d4`).

| Excluded / disclosable process | Role | This reviewer |
|---|---|---|
| `claude-code-session:1c8b041b` | original migration-plan producer (`3817eb2b`) | ⛔ **not this process** |
| `claude-code-session:bc1b47ef` | author of the technical Architecture review `a282d14b`; author of AMD3; proposer of `CL-1`…`CL-12` **and of the remedies now under review** | ⛔ **not this process** |
| `claude-code-session:5e1dd9ee` | registered producer of lane `S4b-architecture-gov-state-impl-design`; author of accepted implementation design `ae451db9` | ⛔ **not this process** |

> ## ✅ **This reviewer has no prior authorship or participation in the migration plan, AMD3, or the accepted implementation design.**
> **Established, not inferred:** the four session records coexist as distinct sessions in the project session store; this session's record was created **2026-08-20 20:54** and its first instruction is this commission. **Independence was checked BEFORE the artifact, the repository or any prior review was read.**
> ⚠️ **`INV-ATTR-2`/`G-2`: this declaration is SELF-DECLARED and NOT ATTESTABLE**, exactly as `bc1b47ef`'s was. Independence rests on where the session was started.

**This review does not accept the plan** (`R-34`/`P-2`) · does not register AMD3 · modified nothing. **Every measurement below was taken read-only; no experiment was run against `.claude/runtime/`.**

---

# 2 · AMD3 governance status — disclosed, not resolved

⚠️ **AMD3 is `PROPOSED · AMENDED`. It is NOT REGISTERED.** **Independently verified:** the Authority State of `KOS-AIP-GOV-STATE-DURABILITY-ADR` carries `G-…-MIGRATION-PLAN` (idx 3), `-AMD1` (idx 4), `-AMD2` (idx 5), all `AUTHORIZED` — **and no `-AMD3` grant exists anywhere in the corpus.**

⇒ **`OPEN-M6` is confirmed OPEN and is preserved by this review.** The authorizing human act is cited by reference in §0; its **registration** is outstanding. ⛔ **This review does not call AMD3 adopted, does not register it, and manufactures no authority.** §0.2's refusal to self-register is **correct** under `G-2`/`R5a`.

✅ **§0.1's two-exposure disclosure is accurate and is the right disclosure to have made.** The remedies were proposed inside the review that demanded them and had never been reviewed by anyone. **This review reviews the REMEDY, not the presence of text.**

---

# 3 · `CL-1` — concurrency / completeness → ⭐ **CLOSED** *(bounded property established; one new gap, `RC-4`)*

## 3.1 The premise, re-verified from the code — not from the prior review

```
append:  loadRecord($path)                             workflow-state.php:303   ← read whole JSON
         assertTransitionAllowed($record, $t)                            :308
         $t['seq'] = count($record['transitions']) + 1                   :309   ← computed from what was read
         $record['transitions'][] = $t                                   :310
         saveRecord($path, $record)                                      :311   ← tmp + rename
saveRecord: file_put_contents($path.'.tmp.'.getmypid(), …); rename($tmp, $path);   :104–106
```

> ## ✅ **CONFIRMED INDEPENDENTLY: `rename` is atomic; the READ-MODIFY-WRITE around it is not.**
> **There is no lock, no lease, no CAS, no version check, and no `flock` anywhere in the file.** ⇒ **two concurrent appends read the same `count()`, compute the same `seq`, and the second `rename` wins.** The `grant` path (`:315`→`:337`→`:338`) has the identical shape.
> ⭐ **This is a STRUCTURAL property readable from the code, not a statistical one.** It therefore holds independently of the prior review's 23/30 measurement — **which this review did not re-execute, and does not need to.** The measured rate bounds *likelihood*; the code establishes *possibility*, and possibility is what a completeness claim must survive.
> ⭐ **Density is preserved BY the loss** — the clobbering writer reuses the lost writer's `seq`. **Verified against the live corpus: `seq` is dense and monotonic in 18 of 18 records**, which is consistent with completeness and *equally* consistent with loss. **The plan is right to withdraw the inference.**

## 3.2 Is the withdrawal real, or reintroduced elsewhere?

**Tested by exhaustive search of the artifact, not by reading §6.3 alone.** Every occurrence of a completeness claim was examined:

| Site | Claim | Verdict |
|---|---|---|
| §6.3 post-reconciliation block, 4th line | *"no record lost **RELATIVE TO** the frozen manifest and both inputs"* | ✅ **bounded** |
| §6.3 `WITHDRAWN` box | the absolute form, explicitly marked withdrawn with its falsifying reason | ✅ **withdrawn, labelled** |
| §10 criterion 2 | bounded form, with *"the absolute form is withdrawn"* and the superseded text shown | ✅ **bounded** |
| §10 criterion 3 | *"sequence integrity preserved — density + monotonicity"* | ✅ **a sequence-integrity criterion, NOT a completeness one — the conflation is not reintroduced** |
| §6.3 closing paragraph | *"dense monotonic `seq` is what makes CASE A mechanically decidable … that is ALL it makes decidable"* | ✅ **the exact over-extension `CL-1` named is explicitly retired** |

> ⭐ **NO SILENT REINTRODUCTION FOUND.** The stronger claim does not survive anywhere in the artifact.

## 3.3 Is the provable/unprovable distinction correctly drawn?

✅ **Yes, and the mechanism is sound.** *"No record was lost"* is not provable against a writer with no lock. ***"No write occurred between Phase 1 and Phase 5"* IS provable** — by comparing 18 file hashes. **Phase 0's freeze does not need to be a lock to do this work:** it does not *prevent* the write, it makes the write *detectable*, and the plan says exactly that (*"the freeze is DECLARATORY: a lane that appends anyway is a governance violation, detected by the re-hash — not a prevented act"*). **That is the correct characterisation of what a declaratory control can buy.**

✅ **The sharpest consequence is stated, not buried:** a loss *inside* the reconciliation delta is imported by CASE A as a legitimate strict extension, indistinguishable from correctness (§6.3, and again at §1.3). **This reviewer independently agrees: a clobbered tail is still dense and still a strict extension.**

## 3.4 🔴 **NEW GAP the remedy does not cover — `RC-4`**

**The freeze is verified at exactly one instant: the Phase-5 re-hash. The destructive act is Phase 7.**

```
Phase 5   re-hash vs manifest → "the window was quiet"     ← the LAST integrity check of the runtime copy
Phase 5   switch writers/readers                            ← not atomic with the re-hash
Phase 6   record the transfer
Phase 7   REMOVE the runtime copy      precondition: "Phases 4 AND 5 passed"   ← no re-check
```

⛔ **A write landing after the Phase-5 re-hash but before the writer is switched goes to the RUNTIME copy, is not in the frozen manifest, is not in either reconciliation input — and is destroyed by Phase 7.** §4.2 justifies Phase 7 as removing *"a redundant, demoted, **byte-verified** copy"*, but the byte-verification it relies on was performed at **Phase 4**, before the switch. **After that point the runtime copy is asserted redundant, never re-checked.**

⚠️ **Note precisely why the AMD3 restatement does not catch this:** the bounded claim is *"no record lost relative to the frozen manifest and the observed inputs"* — and a gap-window write is in **neither**. **The restatement is honest and it is also, here, self-satisfying.** The withdrawn absolute claim would have caught this; the bounded one cannot. **That is not an argument for restoring the absolute claim — it is an argument for a final check.**

⇒ **`RC-4`: make Phase 7's precondition a FINAL re-hash of the runtime copy against `manifest + reconciled delta`. Any difference ⇒ reconcile under §6 first; ⛔ never remove.** Cheap, mechanical, and it closes the only path in the sequence by which the migration itself can destroy evidence.

> ### **CLASSIFICATION: `CL-1` — ⭐ CLOSED.** The remedy establishes the property `CL-1` asks for: the claim is restated to what is provable, the inference is withdrawn and stays withdrawn, and freeze + manifest + re-hash converts an unprovable assertion into a checkable one. **`RC-4` is a new finding against the sequence, not a failure of `CL-1`'s remedy.**

---

# 4 · `CL-2` — execution-time inventory → ⭐ **CLOSED**

| Required property | Where | Verdict |
|---|---|---|
| planning figures are **historical observations only** | §1.1 table + the `PLANNING OBSERVATIONS` heading | ✅ |
| **Phase 1 executes again at migration time** | §4 Phase 1, precondition *"Phase 0 declared"* | ✅ |
| a **frozen manifest** becomes the execution acceptance object | §4 Phase 1 *Produces*; **§10 criterion 1** | ✅ |
| **Phase 4 verifies against that manifest** | §4 Phase 4, *"against the FROZEN MANIFEST"* + all-or-nothing | ✅ |
| **Phase 5 re-hashes against the same manifest** | §4 Phase 5 | ✅ |
| **corpus growth between planning and execution is explicitly handled** | §1.1 — *"cannot distinguish 'a record is missing' from 'the corpus legitimately grew'"* | ✅ |

✅ **The unattestability argument is stronger than staleness and it is verified:** `git log --oneline -- .claude/runtime/workflow/<record>` → **empty**; `git check-ignore -v` → **`.gitignore:32`**. ⇒ `18 / 216 / 109` genuinely cannot be re-derived at any later date. **A criterion pinned to it is not evidence.**

✅ **The corpus figures were re-measured independently by this review:** **18 records · 216 transitions · 110 grants · 109 unique `grantId`s · 0 grants carry `seq` · 2 of 216 transitions carry a time field · `seq` dense and monotonic in 18/18 · no `*.tmp*` remnant.** **Every figure in AMD3 is exact.**

✅ **The `109`/`110` delta is corroborated from a second, independent direction:** the `S4b` lane's own `executionContext` records *"210 transitions and 99 grants"* at its registration — **the corpus demonstrably grows across this work item's lifetime**, which is precisely why a literal cannot be the acceptance object.

✅ **The sequence is technically coherent.** Phase 0 → 1 (manifest) → 3 (copy exactly the manifest) → 4 (exhaust the manifest) → 5 (re-hash the manifest). **Each phase consumes an object a prior phase produced.** ⚠️ **One exception, and it belongs to `CL-6`'s remedy rather than this one — see `RC-1`.**

> ### **CLASSIFICATION: `CL-2` — ⭐ CLOSED.**

---

# 5 · `CL-3` — resolver production → ⭐ **CLOSED** *(two residuals)*

| Required property | Verdict |
|---|---|
| the resolver is explicitly a **prerequisite artifact** | ✅ `INV-R5` (§3), stated as an invariant |
| **Phase 4b identifies the act** that produces it | ✅ *"the separately authorized implementation slice … against `workflow-state.php` and `session-resolve.php`, with its own RED/GREEN evidence"* |
| **Phase 5 cannot begin without it** | ✅ Phase 5 precondition: *"Phase 4 PASSED · Phase 4b landed"* |
| resolver implementation is **outside** the migration-plan artifact | ✅ §3 `INV-R5` + §12, which now names the resolver explicitly among the things NOT built |
| acceptance criteria **do not claim the migration creates the resolver** | ✅ criterion 6 → *"every **DEFAULT** authority-resolution path is unified"*; criterion 7 → `P-4` governed **in its reporting form** |
| **no circular dependency remains** | ✅ **tested below** |

## 5.1 Circularity test, executed

```
Phase 5   depends on → resolver            (INV-R5, Phase 5 precondition)
resolver  produced by → Phase 4b           (§4)
Phase 4b  depends on → Phase 4 PASSED      (§4)
Phase 4   depends on → Phase 3 → Phases 1, 2b
resolver  depends on → Phase 5?            ⛔ NO — the resolver resolves a location Phase 2 already created
```

✅ **No cycle.** The resolver needs the durable *target* (Phase 2), not the *switch* (Phase 5). §3's specification is honestly labelled *"a SPECIFICATION, not an artifact"* — **that sentence is the correct diagnosis of the original defect**, and naming the producing act is the correct repair.

## 5.2 ⚠️ Residual A — the 4b/5 activation boundary is unstated

**If Phase 4b's slice *wires* the resolver into `workflow-state.php`/`session-resolve.php`, then writers redirect at 4b — and 4b becomes the switch, collapsing into Phase 5 and making `CL-4`'s authority-transfer ruling indeterminate.** The distinction is recoverable (Phase 5's act is *"switch all writers and readers **to** the §3 resolver"*, so 4b must deliver it un-wired), **but a plan whose central invariant is about ORDER should not leave the activation instant to inference.** ⇒ **`RC-5b`: state that Phase 4b delivers the resolver DORMANT and Phase 5 is the sole activating act.**

## 5.3 ⚠️ Residual B — §11's enumeration of external dependencies is incomplete

§11 closes: *"`OPEN-M5` and `OPEN-M6` are the two that require another actor."* 🔴 **There are at least four.** **Phase 0 requires a PO/ARB freeze declaration** and **Phase 4b requires an authorization that does not exist** — both introduced by AMD3 itself, both blocking execution, neither carried in §11. ⇒ **`RC-7`.**

> ### **CLASSIFICATION: `CL-3` — ⭐ CLOSED** for the property claimed (producing act named · Phase 5 gated on it · criteria realigned to what the phases deliver · no cycle). **Residuals `RC-5b` and `RC-7` are recorded.**
> ⚠️ **Separately: the SPECIFICATION that Phase 4b must implement is internally contradictory — `RC-2`/`RC-3` below. That is a defect of §3, not of `CL-3`'s remedy, but it lands on the same implementer.**

---

# 6 · `CL-4` … `CL-12`

## `CL-4` — authority transfer and rollback → ⭐ **CLOSED** *(residual `RC-5`)*

✅ **The ruling is mechanism-driven, not preference-driven, and it is right:** Phase 5 redirects **writers**, so after it an append exists only in the new store. **Authority transfers on completion of Phase 5; Phase 6 records it.** This selects between two readings the accepted artifacts already contained — ✅ **it changes no decision and does not contradict `ae451db9`, which said *"after step 3"* (the switch).**

**Rollback semantics, tested per window:**

| Window | Plan says | Verdict |
|---|---|---|
| **before Phase 5** | *"STOP", not "undo"* — runtime still authoritative, durable copy purely additive | ✅ **correct** — `INV-ORDER` guarantees two copies exist |
| **Phase 5 → Phase 6** | **RECONCILIATION**, never *"stop"*; safe direction normally **forward** | ✅ **correct, and the correction is real** — the superseded rule would have abandoned post-switch appends or re-promoted a provably-behind store |
| **after Phase 6** | **a GOVERNANCE ACT** | ✅ **correct** — re-promotion is a change of authority |

✅ **§8's superseded text is shown rather than quietly replaced.** ⛔ **The added prohibition — *"leaving the 5→6 window open"* — is well-founded:** it is the one state in which the two accepted artifacts could be read as disagreeing, *and* the state in which the runtime copy is stale with no on-disk signal.

🔴 **Residual `RC-5` — Phase 5 is not atomic, so the transfer instant is not its completion.** Phase 5 switches `P-1`, `P-2` **and** `P-4` — several call sites. **Authority actually moves the moment the WRITER's resolution changes**, which is *inside* Phase 5. ⇒ **for the interval between the writer switch and Phase 5's completion, the table's *"before Phase 5 ⇒ stop"* rule is false for exactly the reason `CL-4` corrected one window later.** ✅ **Cheap fix, and there is only one writer to order:** mandate Phase 5's internal order — **readers first, the writer LAST** — and define the transfer instant as the writer switch.

## `CL-5` — the two on-disk markers → 🟡 **ADDRESSED BUT NOT CLOSED**

✅ **Both markers are mandated:** the staging target carries a **non-authoritative** marker for the whole 3→5 window (Phase 2), and the runtime copy's **demotion** marker is written **in the same act as the switch** (Phase 5). ✅ **The second is the right placement** — it removes the "stale with no on-disk signal" state `CL-4` identified.

🔴 **But the mechanism is unspecified in exactly the dimension that decides whether it works, and one hard constraint is unstated:**

| Issue | Consequence |
|---|---|
| ⭐ **byte preservation FORBIDS the strongest form** | §4.1 + criterion 5 require **hash equality per file**. ⇒ **the records themselves can never carry the marker.** The marker must be out-of-band — and AMD3 nowhere says so, leaving an implementer free to attempt the one form that would break Phase 4 |
| ⭐ **an out-of-band marker does not reach the reader class that motivated `CL-5`** | The finding was about the **unbounded ad-hoc reader** (§1.4) — `cat`, `jq`, an agent grepping `docs/`. **A sibling marker file is invisible to a reader who opens only the record.** The marker helps a reader who looks at the *directory*; it does not bind the reader §1.4 concedes cannot be bound |
| 🔴 **the marker must NOT be named `*.json`** | `session-resolve.php:130` globs `*.json` and treats every hit as a work item. A `*.json` marker in the record directory would be folded, fail, and inject *"record not interpretable … escalate to Governance"* into **every** resolution report — permanent false-alarm noise. **Also interacts with `CL-12`'s predicate and Phase 4's all-or-nothing manifest exhaustion** |

⇒ **`RC-6`: specify marker granularity (directory-level, out-of-band), state that in-file marking is forbidden by byte preservation, forbid a `*.json` marker name, and state the residual — the marker is advisory for the same reader class §1.4 already bounds.** **The remedy is directionally right; as written it is not implementable without re-deriving all three constraints.**

## `CL-6` — `.gitattributes` byte preservation → 🟡 **ADDRESSED BUT NOT CLOSED** *(gate-class defect `RC-1`)*

✅ **The premise is verified independently:** `.gitattributes:1` is `* text=auto`; `git check-attr text eol -- docs/knowledgeos/evidence/KOS-X.json` → **`text: auto`, `eol: unspecified`**; **no record contains a `CR` byte** (0 of 18), so normalisation is a no-op *today*. ✅ **And the property actually claimed — that a FUTURE verifier can recompute the hash — is genuinely at risk** under `eol: unspecified`, because checkout applies `core.eol` (default *native*). **The finding is real and the pin is the right instrument.**

🔴 **`RC-1` — Phase 2b as written is CIRCULAR with Phase 3, and Phase 3's precondition is therefore unsatisfiable.**

```
Phase 2b  act:          pin .gitattributes  AND  "commit the Phase-1 manifest
                        in the same commit as THE PHASE-3 COPY"
          produces:     "pin + committed manifest"
Phase 3   precondition: "Phases 1 and 2b COMPLETE"
```

⛔ **Phase 2b cannot complete before Phase 3 (its commit must contain Phase 3's output); Phase 3 cannot begin before Phase 2b completes.** ⭐ **This is the same defect class as `CL-3` — a phase consuming an artifact that phase's own predecessor has not produced — reappearing inside the remedy written to close a different clarification.**

✅ **The substance is sound and the repair is mechanical:** the two acts have *different* ordering constraints and must not share a phase.
```
2b-pin      pin .gitattributes                      BEFORE Phase 3
2c-commit   commit manifest + copy together         AT / AFTER Phase 3
```

⚠️ **`RC-8` — `-text` and `text eol=lf` are offered as equivalent (*"`-text`, or `text eol=lf`"*). They are not, for this claim.** `text eol=lf` **keeps text normalisation on** — it merely fixes the checkout direction. A record that ever contained a raw `CR` would be silently rewritten on commit, which is the exact class of silent byte rewrite the pin exists to prevent. **`-text` disables conversion in both directions and is the only form that makes the claim unconditional.** ⇒ **prefer `-text`; do not present them as interchangeable.**

⚠️ **Minor:** the plan says *"hash each file"* without naming the algorithm or whether the hash is of working-tree bytes or the git blob. **Not blocking** — the manifest is committed alongside — but a future verifier benefits from the choice being recorded.

## `CL-7` — grant reconciliation → ⭐ **CLOSED**

**Every load-bearing fact re-measured independently:**

| Claim | Independent check |
|---|---|
| grants have **no `seq`** | ✅ **0 of 110** carry `seq` |
| no time dimension to substitute | ✅ **2 of 216** transitions carry any time field; grants carry none |
| `grantId` is **not** globally unique | ✅ **110 grants, 109 unique** — `G-KOS-GOVGAPS-VERIFY` appears **twice** |
| the collision is **cross-record** | ✅ **index 0 of `KOS-GOV-GAPS-001.json` and index 0 of `KOS-GOV-GAPS-VERIFY-001.json`** — the bounding in §6.4 is exact |
| position is a **stable identity** | ✅ **verified structurally:** the only grant mutation in the writer is `$record['grants'][] = $g;` (`:337`) — **append-only, no `unset`, no splice, no reorder, no update command.** ⇒ array position cannot shift, and byte-preserving copy preserves it |

✅ **Positional + content reconciliation is sufficient for this scope, and the reasoning is sound.** ⛔ **`grantId` is correctly excluded as a key for identity, dedup and the completeness count** — **duplicate grant IDs therefore cannot silently collapse authority records.**

⭐ **One reinforcement the plan could claim and does not:** because the writer offers **no update path**, a grant lifecycle change (`PROPOSED`→`AUTHORIZED`→…) can only be recorded as **another appended grant carrying the same `grantId`**. ⇒ **intra-record `grantId` repetition is not an anomaly but a designed consequence**, which makes the "never key on `grantId`" rule *structurally* required rather than merely prudent. *(Measured: no intra-record duplicate exists today.)* **The rule as written is correct either way.**

## `CL-8` — transition comparison → ⭐ **CLOSED** *(minor `RC-9`)*

✅ **The COPY / COMPARISON distinction is drawn correctly and is legitimate:** §4.1 forbids parse-and-reserialize **for the copy**; comparison may decode. **They are not conflated anywhere in the artifact.**
✅ **The diagnosis is right:** `saveRecord` rewrites the entire file on every append, so a **file-level byte-prefix** comparison is meaningless — the closing brackets move. **Element-wise over the decoded `transitions` array, element `i` vs element `i`, is the implementable form.**

⚠️ **`RC-9` — *"canonical re-encode"* is not defined.** In PHP, `json_decode`→`json_encode` preserves *insertion* order, so two semantically equal objects with different key order compare unequal ⇒ **a false CASE B (escalation) rather than a false CASE A (silent merge)** — the safe direction, and low-risk since one writer produced both copies. **Naming the canonicalization (e.g. recursive key sort) costs one clause and removes the ambiguity.**

## `CL-9` — `INV-R1` split → ⭐ **CLOSED on its axis** *(new collision `RC-2` on a different axis)*

✅ **Verified against the pinned contract itself, not against the prior review's quotation of it.** `session-resolve.php:26–30` states AMENDMENT 1; **`T-12`'s body asserts `0` for RESOLVED, UNASSIGNED and AMBIGUOUS and `assertNotSame(0, …)` only for a usage error.** ✅ **The split is correct:**

| Condition | AMD3 | Contract |
|---|---|---|
| authority **LOCATION** unresolvable / outside the boundary | **refuse to produce a report — exit 65** | ✅ AMENDMENT 1's own *"refusal to produce a report at all (65)"* clause |
| location resolved; **workflow state** `UNRESOLVABLE` | **produce the report — exit 0** | ✅ `T-12` untouched |

✅ **`T-8` is also preserved** — it asserts the *verdict* `UNRESOLVABLE` for an empty and for a corrupt record directory, both of which are workflow-state conditions, not location-resolution failures. **No adopted contract is amended and no test changes.**

🔴 **`RC-2` — but the OTHER half of `INV-R1`'s row-1 cell was not split, and it collides with both contract suites.** The validation cell still reads *"the location is **inside the governed evidence boundary**"*, with failure ⇒ **refuse, exit 65**.

```
SessionAssignmentResolverContractTest::resolve()  →  --dir=sys_get_temp_dir().'/kos-disc-…'
WorkflowStateRecordContractTest                   →  --dir=sys_get_temp_dir().'/kos-orch-…'
```

⛔ **Every test in both suites drives the components through `--dir` into a temp directory that is, by construction, OUTSIDE the governed evidence boundary.** ⇒ **an implementer who applies the boundary check literally makes the entire pinned contract suite exit 65 with no report.** ⚠️ **§7 contains the reconciliation** — *"an override may select where bytes are written, and may never confer authority on the result"* — **but §3's invariant, which is what Phase 4b builds from, does not carry it.** ⇒ **`RC-2`: state in `INV-R1` that a governed override selects a NON-AUTHORITATIVE output location and is NOT a boundary violation; the refusal binds the AUTHORITATIVE resolution only.**

⚠️ **Minor, unpinned:** today `!is_dir($recordDir)` yields `UNRESOLVABLE`/exit 0 (`:127`). Whether a *nonexistent governed directory* is "location cannot be resolved" (⇒ 65) is left open by the split. **No test pins it**, so it breaks nothing — but Phase 4b's implementer must decide it.

## `CL-10` — `KOS_MECHANISM_PATH` as a write-capable path → ⭐ **CLOSED** *(residual `RC-3`)*

✅ **Verified line by line, independently:**
```php
$mechanism = getenv('KOS_MECHANISM_PATH') ?: (__DIR__ . '/workflow-state.php');      // :90
proc_open(array_merge(['php', $mechanism], $args), …);                               // :103
askMechanism($mechanism, ['fold', $workItem, '--dir=' . $recordDir]);                // :156
```
⭐ **`session-resolve.php` executes the environment-named program AND hands it the authority record directory as an argument.** ⇒ **the characterisation is correct: this is a WRITE path, through a component whose own bytes contain no write call.** ✅ **`T-11` pins read purity of *that file* and cannot pin a substituted subprocess** — correct, and the script's own docblock (`:79–88`) is honest about it. ✅ **Relocation raises the value of the path**, since after Phase 5 its target is the durable authoritative store — so `INV-R3` is indeed *more* necessary than the original plan argued.

⛔ **`OPEN-M5` is NOT decided by this review.** ✅ AMD3 is right that the migration executes on the **REPORTING** form and that the **ENFORCING** form would remove the seam `T-13(b)` and `T-15` deliberately exercise — **verified in the test bodies: `T-15` sets `KOS_MECHANISM_PATH` to a substitute and asserts verdict `RESOLVED` with the substituted path reported; `T-13(b)` sets it to `/nonexistent` and asserts a report is still produced naming the unavailable mechanism.**

🔴 **`RC-3` — §3's invariant table still states the ENFORCING form, four lines above the note saying the migration executes the REPORTING form.**

| §3 table, row 2 — **unchanged by AMD3** *(verified against `3817eb2b`)* | §3 AMD3 note, immediately below |
|---|---|
| **Mechanism identity** · Validation: *"the interpreter is **the governed one**"* · Failure: **refuse** · Unresolved: **refuse** | *"✅ REPORTING — substitution **permitted**, authority never conferred, substitution reported … **THIS MIGRATION EXECUTES ON THE REPORTING FORM**"* |

⛔ **These are contradictory, and both are presented as current.** An implementer of Phase 4b building from the table refuses on substitution and **breaks `T-15` outright and `T-13(b)`'s second assertion.** ⇒ **`RC-3`: mark row 2 as the ENFORCING form deferred to `OPEN-M5`, and state the reporting-form cells that Phase 4b must actually implement.** *(This is the one **conflicting current definition** found in the artifact — see §8.)*

## `CL-11` — two senses of "durable" → ⭐ **CLOSED**

✅ **Verified: `saveRecord` contains no `fsync`/`fflush`+`fsync` pair anywhere.** `rename` provides atomicity of **visibility**, not durability across power loss. ✅ **The separation is exact and correctly scoped:**

| Sense | Status | Verdict |
|---|---|---|
| **ATTESTABILITY** — versioned, later-verifiable | ✅ cured by the migration; the whole point of `B′` | ✅ correct |
| **CRASH DURABILITY** — bytes survive power loss | 🔴 unchanged; pre-existing; **not claimed** | ✅ correct, and correctly *not* repaired here |

## `CL-12` — record predicate and quarantine → ⭐ **CLOSED**

✅ **The predicate matches the reader exactly:** Phase 1 enumerates `*.json`; `session-resolve.php:130` globs `*.json`. **One predicate, two components — which is the same disease `INV-R2` cures for paths, avoided here by construction.**
✅ **The tmp name is provably excluded:** `saveRecord` writes `$path . '.tmp.' . getmypid()` → `<workItem>.json.tmp.<pid>`, which **does not match `*.json`**. ✅ **Measured: 0 remnants present** (`find .claude/runtime -name "*.tmp*"` → empty; the record directory holds exactly 18 entries, all `*.json`).
✅ **Quarantine — *"never migrated, never deleted"* — is the correct disposition and is coherent with `R-CONFLICT`:** migrating a partial write would enshrine it as evidence; deleting it would be the destruction the invariant forbids. **A partial writer remnant therefore cannot be promoted into governance evidence.**

---

# 7 · Concurrency / failure model — independent analysis

## 7.1 The premise

> ### ✅ **`atomic rename` ≠ `atomic read-modify-write` — CONFIRMED from the code, not inherited from the prior review.**
> **The loss is structurally reachable**, and the survivor of a loss is **dense and monotonic**, so the only mechanical completeness signal the corpus offers is blind to it. **AMD3's withdrawal of the density inference is therefore not a concession — it is the only defensible position.**

## 7.2 Does freeze + manifest + re-hash bound the risk sufficiently for migration?

| Scenario | Bounded? |
|---|---|
| **interruption** after copy, before verify | ✅ Phase 3 is a pure function of the source; re-run idempotently. Nothing switched, runtime still authoritative |
| **partial verification** | ✅ **all-or-nothing against the manifest** — a PASS set that does not exhaust the manifest is a FAIL. **This is the precise remedy the case needed** |
| **partial copy** | ✅ caught by Phase 4's manifest exhaustion + hash equality |
| **concurrent writer, Phase 1 → Phase 5** | ✅ **detected** by the re-hash. ⛔ **not prevented** — correctly characterised as declaratory |
| **divergence during migration** | ✅ handled as a **named step** (CASE A/B, and §6.4 for grants). ⚠️ **loss *inside* the delta remains undetectable — disclosed, not hidden** |
| ⭐ **concurrent writer, Phase-5 re-hash → Phase 7** | 🔴 **NOT bounded — `RC-4`.** No check exists between the last verification and the destructive act |
| **resolver failure after Phase 5** | ⚠️ **mostly bounded, with one code-level hazard — `RC-10`** |
| **reconciliation after detected divergence** | ✅ union-preserving, no silent winner, escalation on CASE B. ⚠️ subject to `RC-9`'s canonicalization |

## 7.3 🔴 `RC-10` — a mis-resolved path MATERIALIZES rather than failing

```php
function saveRecord(string $path, array $record): void {
    if (!is_dir(dirname($path))) { mkdir(dirname($path), 0777, true); }     // :101–102
```

⛔ **The writer creates its parent directory.** ⇒ **a resolver that returns a wrong-but-well-formed path does not fail loudly — it silently manufactures a NEW evidence location**, which is `B′`'s original defect re-created by the very component built to close it. ⚠️ **`INV-R1`'s refusal must therefore be enforced *before* `saveRecord` is reached; a refusal downstream of this `mkdir` is not a refusal.** **Not in the plan; it belongs in Phase 4b's design brief.**

## 7.4 What the freeze genuinely buys — stated fairly

✅ **A declaratory freeze plus a committed manifest converts "no record was lost" (unprovable) into "no write occurred in the window" (18 hash comparisons).** ⭐ **That is a real and elegant gain, it requires no lock, and it does not propose `Increment 2`.** **The design is sound; its coverage simply stops one phase early (`RC-4`).**

---

# 8 · Canonical document integrity

**Reviewed objectively, over all 498 lines. Duplication was not assumed.**

| Check | Result |
|---|---|
| duplicate **current** sections | ✅ **none found** |
| **superseded** wording presented as current | ✅ **none** — §8's superseded rollback rule is headed *"Superseded text:"*; §10's rewritten criteria each carry *"(superseded: …)"*; §11's `OPEN-M3` is struck through and marked closed-and-retained-as-history. **All labelled** |
| unlabelled obsolete acceptance criteria | ✅ **none** — all four rewritten criteria show their prior text |
| tool / edit transcript residue | ✅ **none** |
| ⭐ **conflicting current definitions** | 🔴 **ONE FOUND — `RC-3`:** §3's invariant table row 2 states the **enforcing** form while the AMD3 note four lines below states the migration executes the **reporting** form. **Verified against `3817eb2b` that row 2 is unchanged by AMD3** — the note was added around it and the table was not reconciled |

⚠️ **Two coherence defects, below the "conflicting definition" bar but real:**

| # | Defect |
|---|---|
| **`RC-7a`** | **§4's `INV-ORDER` preservation argument enumerates two of its three added phases:** *"Phase 0 is prefixed BEFORE the sequence and Phase 4b is inserted between verification and the switch."* **Phase 2b — the one addition that lands INSIDE the 2–4 span — is omitted from the argument**, though §0.3 row 7 names it. ✅ **`INV-ORDER` is not actually violated** (2b creates durability guarantees; 2–4 still precede 6; 7 is last) — **but the justification does not cover its own change set** |
| **`RC-7b`** | **§4's phase table lists row `2b` BEFORE row `2`, while `2b`'s own precondition is *"Phase 2 target resolved."*** ⇒ **the table read top-to-bottom is not executable.** In a plan whose load-bearing invariant is about order, the order of the order-table should not require repair from the precondition column |

> ## ⛔ **This review does NOT record "no material canonicalization defect found."** One conflicting current definition (`RC-3`) exists and is material because §3 is the specification Phase 4b implements. **Neither finding was manufactured from the artifact's length or its visible amendment history — the labelled amendment history is correct practice and is not a defect.**

---

# 9 · `INFO-1` / `INFO-2`

## `INFO-1` — 🔴 **NOT closed. The recommended wording was not adopted, and AMD3 supplied a second reason it matters.**

**The discriminating test was re-executed independently:**

| File | writes? | references `runtime/workflow`? |
|---|---|---|
| `workflow-state.php` | ✅ **real `file_put_contents` (`:105`)** | ✅ |
| `session-resolve.php` | ⛔ **docblock mention only (`:22`)** — no call | ✅ (reads) |
| `session-changes-logger.sh` | ✅ real call | ⛔ **no** |

✅ **The narrower claim survives:** exactly one component both **writes** and **references the governance corpus**. ⛔ **But the artifact still reads `## 1.3 Writers — **exactly one***** — the unqualified form, not the recommended *"one writer **of governance evidence**"*, and it does not cite the discriminating test. **A reader running the naive grep gets three hits and reasonably doubts the claim.**

⭐ **And AMD3 has since made the unqualified form harder to defend, from its own evidence:** `CL-10` establishes that `KOS_MECHANISM_PATH` makes a **second write path reachable**. ⇒ *"exactly one writer"* is true of the **committed code paths today** and false as a **structural guarantee** — and the artifact asserts it without either qualifier while proving the counter-case in §1.5. **Non-blocking; wording, not mechanism. `RC-11`: adopt the recommended narrower claim and cite the discriminating test.**

## `INFO-2` — ✅ **confirmed as a GOVERNANCE PROVENANCE discrepancy; NOT resolved here**

✅ **Verified directly from the authority record:** `KOS-AIP-GOV-STATE-DURABILITY-ADR.json`, transition `seq=4`, `REGISTER` of `S4b-architecture-gov-state-impl-design`, `executionContext` names producer **`claude-code-session:5e1dd9ee`** — **while the migration plan was produced by `1c8b041b` and AMD3 by `bc1b47ef`, each disclosing itself correctly in the artifact.**

⛔ **This reviewer does NOT dispose of it as a technical Architecture issue.** **Reconciling a lane registration against its actual producing processes is a Governance act with exactly one writer** (`G-2`/`R5a`). ⚠️ **One observation, offered as fact and not as disposition:** §0.2 cites this discrepancy as *"precedent for producing while a registration lags."* **It is a precedent for a registration lagging; whether a lagging registration is thereby acceptable is a Governance judgement, and the plan does not claim otherwise.** **The item stays with Governance, alongside `OPEN-M6`.**

---

# 10 · DDD / knowledge-engineering check

| Distinction | Preserved by AMD3? |
|---|---|
| **Execution state ≠ Governance evidence** | ✅ §2's boundary table intact; §1.6 proves the boundary on real files — the five runtime clients stay, and §1.6 correctly names migrating them *"the mirror error"* |
| **Authority ≠ storage location alone** | ✅ **strengthened.** §1.5's two-axis finding (record location **and** interpreter) and §9's *"do not let STORAGE LOCATION accidentally become DOMAIN OWNERSHIP"* both survive; `CL-10` sharpens the second axis |
| **Evidence ≠ proof** | ✅ **this is precisely what `CL-1`'s remedy enforces** — dense-and-monotonic is *evidence*, and AMD3 stops it being treated as *proof* |
| **Derived state ≠ authoritative state** | ✅ §2 and §9 both retain *"the fold, `authorityState`, resolver answers … never a second authority source"* |
| **Historical location ≠ canonical future location** | ✅ §9 retains it; §2 refuses to invent the physical sub-path and defers to placement governance at execution time |

✅ **`B′`, `R-CONFLICT`, `OPEN-M3` Option A and existing placement governance are all untouched.** ⛔ **No second authority boundary is created by AMD3, and none is created by this review.**

---

# 11 · Residual technical risks

| # | Risk | Class | Bears on |
|---|---|---|---|
| ⭐ **`RC-1`** | **Phase 2b is circular with Phase 3; Phase 3's precondition is unsatisfiable as written.** Split into *pin before copy* and *commit with copy* | **gate on Phase 3** | `CL-6` |
| ⭐ **`RC-2`** | **`INV-R1`'s boundary-validation clause refuses `--dir` targets outside the governed boundary — which is how BOTH pinned contract suites drive both components.** State that a governed override yields a non-authoritative location and is not a boundary violation | **gate on Phase 4b** | `CL-9`, `CL-3` |
| ⭐ **`RC-3`** | **§3 table row 2 still specifies the ENFORCING form**, contradicting the reporting-form ruling below it; building Phase 4b from the table breaks `T-15` and `T-13(b)` | **gate on Phase 4b** | `CL-10` |
| ⭐ **`RC-4`** | **No integrity check between the Phase-5 re-hash and Phase-7 removal** — the destructive act is unguarded, and the bounded completeness claim cannot catch it. Make a final re-hash Phase 7's precondition | **gate on Phase 7** | `CL-1` |
| **`RC-5`** | Phase 5 is not atomic; authority moves at the **writer** switch, inside it. Mandate readers-first / writer-last and define the transfer instant | design | `CL-4` |
| **`RC-5b`** | State that Phase 4b delivers the resolver **dormant**; Phase 5 is the sole activating act | design | `CL-3` |
| **`RC-6`** | Marker granularity unspecified; **in-file marking is forbidden by byte preservation**; a `*.json` marker would pollute `session-resolve.php:130`'s glob; residual advisory-reach unstated | design | `CL-5` |
| **`RC-7a/b`** | `INV-ORDER` preservation argument omits Phase 2b; the phase table's row order is not executable | coherence | §4 |
| **`RC-8`** | `-text` and `text eol=lf` presented as equivalent; only `-text` makes the claim unconditional | design | `CL-6` |
| **`RC-9`** | *"canonical re-encode"* undefined (key-order canonicalization). Fails **safe** (false CASE B) | wording | `CL-8` |
| **`RC-10`** | `saveRecord` **`mkdir`s its parent** — a mis-resolved path materializes a new evidence location instead of failing. Refusal must precede `saveRecord` | design brief | Phase 4b |
| **`RC-11`** | §1.3 still says *"Writers — exactly one"*; adopt *"one writer of governance evidence"* + the discriminating test | wording | `INFO-1` |

⛔ **None of these requires reopening `B′`, `R-CONFLICT`, `OPEN-M3`, `INV-ORDER`, `Option D` or placement governance. None requires `Increment 2`. Every one is closable inside the current decision envelope by the plan's owner.**

---

# 12 · Items requiring PO/ARB or Governance

| Item | Actor | Status |
|---|---|---|
| **`OPEN-M6`** — AMD3 carries no registered grant | **Governance** (`G-2`/`R5a`) | ⏳ **OPEN — preserved by this review.** Verified: no `-AMD3` grant exists |
| **`OPEN-M5`** — does governing `P-4` authorize amending `AST-016`'s AMENDMENT-2 contract, or is enforcement a separate act? | **PO/ARB** | ⏳ **OPEN — ⛔ NOT decided here.** ✅ Does not gate the migration: the reporting form changes no adopted contract |
| **Phase 0 freeze declaration** | **PO/ARB** | ⏳ required before Phase 1 — **`RC-7`: not carried in §11** |
| **Phase 4b authorization** (runtime-code slice) | **PO/ARB** | ⏳ required before Phase 5 — **`RC-7`: not carried in §11** |
| **AMD3 acceptance** | **PO/ARB** | ⏳ after registration |
| **`INFO-2`** lane-registration provenance | **Governance** | ⏳ open — ⛔ not a technical Architecture issue |
| `OPEN-M1` (`.gitignore`) · `OPEN-M2` (evidence-before-target) · `OPEN-M4` (unbounded readers) | PO/ARB | ⏳ unchanged; **`RC-1` constrains `OPEN-M2` further without deciding it** |

---

# 13 · Verdict

## Classification of every clarification

| | Result | |
|---|---|---|
| **`CL-1`** concurrency / completeness | ⭐ **CLOSED** | bounded claim established; withdrawal verified non-reintroduced · new gap `RC-4` |
| **`CL-2`** execution-time inventory | ⭐ **CLOSED** | all six properties present; sequence coherent; every figure re-measured exact |
| **`CL-3`** resolver production | ⭐ **CLOSED** | act named, Phase 5 gated, criteria realigned, **no cycle** · residuals `RC-5b`, `RC-7` |
| **`CL-4`** authority transfer / rollback | ⭐ **CLOSED** | three windows coherent; §8 correctly corrected · residual `RC-5` |
| **`CL-5`** two on-disk markers | 🟡 **ADDRESSED · NOT CLOSED** | mechanism unspecified in the deciding dimension; a hard constraint unstated (`RC-6`) |
| **`CL-6`** `.gitattributes` pin | 🟡 **ADDRESSED · NOT CLOSED** | substance sound; **Phase 2b circular with Phase 3** (`RC-1`) · `RC-8` |
| **`CL-7`** grant reconciliation | ⭐ **CLOSED** | append-only verified; positional identity stable; `grantId` correctly never a key |
| **`CL-8`** transition comparison | ⭐ **CLOSED** | copy/comparison correctly separated · minor `RC-9` |
| **`CL-9`** `INV-R1` split | ⭐ **CLOSED on its axis** | verified against `T-12`/`T-8`; no contract amended · **new collision `RC-2`** |
| **`CL-10`** `KOS_MECHANISM_PATH` write-capable | ⭐ **CLOSED** | verified at `:90`/`:103`/`:156`; `OPEN-M5` untouched · residual `RC-3` |
| **`CL-11`** two senses of "durable" | ⭐ **CLOSED** | no `fsync` verified; separation exact |
| **`CL-12`** record predicate / quarantine | ⭐ **CLOSED** | predicate matches the reader; tmp name provably excluded; quarantine coherent |

**10 CLOSED · 2 ADDRESSED BUT NOT CLOSED · 0 NOT CLOSED · 0 NOT APPLICABLE.**

> # 🟡 **PASS WITH DESIGN CLARIFICATIONS**

**The remedies are, in substance, sound.** ⭐ **The three gates were answered at the level the finding demanded, not at the level of adding text:** the completeness claim was genuinely *withdrawn* rather than softened, and the withdrawal survives an exhaustive search of the artifact; the acceptance object was genuinely *moved* from an unattestable literal to a committed manifest; the missing resolver-producing act was genuinely *named* and Phase 5 gated on it, with no cycle introduced. **The freeze/manifest/re-hash construction converts an unprovable claim into a checkable one without a lock and without proposing `Increment 2` — that is the correct instrument for the problem.**

⛔ **NOT BLOCKED.** No remedy is technically unsound. No required gate is impossible — **`RC-1` makes one gate unsatisfiable *as written*, and it is repaired by splitting one phase into two, changing no decision.** No fixed architectural input is violated: **`B′`, `R-CONFLICT`, `OPEN-M3` Option A, placement governance and `INV-ORDER` are all intact, and no second authority boundary is created.** **The migration is not unsafe under the proposed design** — with the caveat that `RC-4` must be closed before Phase 7, which is the one point at which this sequence could itself destroy evidence.

⚠️ **`CL-5` and `CL-6` are not closed, and `RC-1`/`RC-2`/`RC-3`/`RC-4` are gate-class.** ⛔ **Phase 3 must not begin.** **This is a clarification set, not a rejection: every item is a wording or sequencing repair by the plan's owner inside the current envelope.**

---

# 14 · Explicit non-decisions and limitations

⛔ **This review did NOT:** register AMD3 · accept or approve AMD3 or the plan · execute or begin any migration · modify the migration plan, runtime code, `.gitignore`, `.gitattributes` or any authority record · decide `OPEN-M5` · reopen `B′`, `R-CONFLICT`, `OPEN-M3` or placement governance · create any bounded context, capability, owner or rule · dispose of `INFO-2` · certify the work item or close the lane (`G-1`).

**CAN establish:** whether each remedy establishes the property its clarification demanded, tested against the code and the corpus as they stand today · internal coherence of AMD3 · the failure and concurrency cases enumerated in §7 · canonical integrity of the artifact.
**CANNOT establish:** that the enumerated failure cases are exhaustive · that the migration will succeed · anything about executed behaviour — **nothing was executed against `.claude/runtime/`, and the prior review's 23/30 concurrency trial was NOT re-run** (the mechanism was instead established structurally from the code, which is sufficient for the claim at issue and weaker evidence for the *rate*) · acceptance, which is the PO/ARB's alone.
⚠️ **`9c908e70` is self-declared and not attestable (`INV-ATTR-2`).**

---

**INDEPENDENT AMD3 ARCHITECTURE REVIEW DELIVERED · STOPPING.** ⛔ **THE MIGRATION IS NOT EXECUTED AND MUST NOT BE.**

**Next actors, in order:** the plan's owner closes `RC-1`…`RC-4` (gate-class) and `CL-5`/`CL-6` → **Governance registers AMD3 (`OPEN-M6`)** → **PO/ARB reviews AMD3 acceptance and decides `OPEN-M5` if required** → the Phase-0 freeze declaration and the Phase-4b authorization → only then is the execution gate eligible.

**Traceability:** migration plan **`bb1708b7`** §0–§12 (AMD3) · prior technical Architecture review **`a282d14b`** (`CL-1`…`CL-12`) · Governance review (`INFO-1`/`INFO-2`/`INFO-3`) · self-review `13bcfb49` (`INFO-1` writer-claim, `INFO-2` lane provenance, `INFO-4`) · accepted implementation design **`ae451db9`** · original plan **`3817eb2b`** *(diffed against `bb1708b7` to attribute §3's unchanged table row 2)* · `B′` · adopted `R-CONFLICT` · `OPEN-M3` Option A · `AMD1`/`AMD2` · `ADR_20260801_1740` + `scripts/doc-placement.php` (**exit 0 → `docs/knowledgeos`**) · **code read directly:** `workflow-state.php:79–84, 86–97, 99–107, 291–299, 302–312, 314–339` · `session-resolve.php:22, 26–30, 54–74, 79–90, 98–120, 127–134, 143–147, 154–163, 293–299` · `SessionAssignmentResolverContractTest.php` (harness `resolve()`/`mechanism()`, `T-8`, `T-11`, `T-12`, `T-13`, `T-15`) · `WorkflowStateRecordContractTest.php:39, 70` · `.gitignore:25,32` · `.gitattributes:1` · **measured independently, read-only:** 18 records / 216 transitions / 110 grants / 109 unique `grantId`s / **0 of 110 grants carry `seq`** / 2 of 216 transitions carry a time field / `seq` dense-and-monotonic **18 of 18** / `G-KOS-GOVGAPS-VERIFY` at index 0 of two different records / **0** `*.tmp*` remnants / **0** records containing `CR` / `git log` on any record → **empty** / `git check-ignore` → `.gitignore:32` / `git check-attr text eol` → `auto` / `unspecified` / **no `-AMD3` grant exists in the corpus** / no durable target directory exists · `INV-ATTR-2`/`G-2` · `G-2`/`R5a`/`R5b` · `R-34`/`P-2`.
