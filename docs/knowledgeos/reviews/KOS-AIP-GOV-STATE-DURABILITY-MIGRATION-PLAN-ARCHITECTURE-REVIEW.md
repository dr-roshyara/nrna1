# Migration Plan — **INDEPENDENT** Technical Architecture (design) review

**Artifact under review:** `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` @ **`3817eb2b`** (293 lines)
**Work item:** `KOS-AIP-GOV-STATE-DURABILITY` · **Prior gate:** `KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-GOVERNANCE-REVIEW.md` (COMPLETE · TRACEABLE · PRESERVED · PLANNING-ONLY · 3 INFO · 0 BLOCKING)
**Reviewer:** Architecture — `claude-code-session:bc1b47ef` *(self-declared, **not attestable** — `INV-ATTR-1`/`INV-ATTR-2`)*
**Placement derived, not chosen:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos`, **exit 0**.

✅ **INDEPENDENCE:** the plan was authored by **`1c8b041b`**; the Governance review was performed by **`b64828fe`**. **This process is neither.** It did not author the plan, the implementation design (`5e1dd9ee`), or any prior review in this chain.

> ## ⛔ **SCOPE — read before the verdict**
> **This review establishes:** technical soundness · internal coherence · failure and interruption behaviour · concurrency behaviour · resolver sufficiency · `R-CONFLICT` mechanics · whether the inventory must be re-executed.
> **It does NOT re-perform** the completeness/provenance review — that gate is closed and is not reopened here. **It does not accept the plan** (`R-34`/`P-2`): acceptance is the PO/ARB's.
> ⛔ **Nothing was modified.** No migration executed · no file moved · no default changed · no `.gitignore` or `.gitattributes` change · no authority record touched. The two experiments in §3 ran **in `mktemp` directories via `--dir`**, never against `.claude/runtime/`.

---

# 1 · TECHNICAL VERDICT

> # 🟡 **PASS WITH DESIGN CLARIFICATIONS**

**The migration design is sound in its load-bearing structure.** `INV-ORDER` (durability created and verified before authority is demoted, removal last) is correct, and it is the property that makes the migration **non-destructive under every interruption examined in §2**. Byte-preserving copy is the right mechanism, and it is right for the reason the plan gives. `Option D` (relocate now, ledger later) does not exceed the decision. **The plan does not destroy evidence, and no rollback path examined requires rewriting history.**

**It is not yet executable**, for one structural reason and one evidentiary one:

| | |
|---|---|
| ⭐ **The plan asserts a completeness guarantee its own writer cannot deliver.** §6's fourth post-reconciliation invariant — *"NO record silently dropped"* — **does not follow from the three checkable ones above it, and §3 demonstrates that it is false in the general case.** A concurrent write loss leaves the sequence **dense and monotonic**. | **CL-1** |
| ⭐ **Phase 5 consumes an artifact no phase produces.** The plan switches every path *"to the §3 resolver"*; **no phase builds the resolver**, and §7 explicitly declines to restrict the overrides that acceptance criteria 6 and 7 require to be closed. | **CL-3** |

⛔ **NOT BLOCKED.** No defect found requires reopening `B′`, `R-CONFLICT`, `OPEN-M3` or placement governance; none shows the migration destroying evidence; every clarification below is closable inside the current decision envelope. **Three are gates on Phase 3, not advice** (`CL-1`, `CL-2`, `CL-3`).

**One question is ROUTED to PO/ARB** (§7.11) — it does not change this verdict, because the migration can proceed on the *reporting* form of `INV-R3` and the *enforcing* form is a separable authorized act.

---

# 2 · FAILURE / INTERRUPTION ANALYSIS

**Method:** each case is stated as *expected on-disk state · safe recovery · prohibited action*. **The reference property throughout is `INV-ORDER`.**

## 2.1 Interruption **after Phase 3 copy, before Phase 4 verification**

| | |
|---|---|
| **Expected state** | runtime = **authoritative and complete**; durable target = **partial, unverified, non-authoritative**; no default switched |
| **Safe recovery** | ✅ **re-run Phase 3 idempotently**, then Phase 4 over the full set. The copy is a pure function of the source |
| ⛔ **Prohibited** | proceeding to Phase 5 · treating a partially populated target as a verified one · deleting the partial target *"to start clean"* without recording that the removal was of an unverified staging artifact |

> ## ⭐ **The sharpest new failure mode in the whole sequence, and it is not in the plan.**
> **The partial durable copy is MORE attractive to an ad-hoc reader than either endpoint.** §1.4 concedes the reader set is unbounded and the invariant is **advisory** for ad-hoc reads. The runtime original is **gitignored and invisible**; the staging copy lands **tracked, visible, greppable under `docs/`**. ⇒ **during Phases 3–5 the least authoritative copy is the most discoverable one.** ⇒ **`CL-5`: the staging location must carry an explicit on-disk non-authoritative marker for the whole window.**

## 2.2 Interruption **during Phase 4 verification**

| | |
|---|---|
| **Expected state** | a **partial** verification manifest; nothing switched |
| **Safe recovery** | ✅ discard the partial manifest and re-verify in full |
| ⛔ **Prohibited** | ⭐ **treating a partial manifest as a partial PASS.** Phase 4 is the precondition of Phases 5 and 7; a per-file PASS set that does not cover the frozen inventory is **a FAIL, not progress** |

⚠️ **The plan does not say this.** Phase 4 lists five checks but no all-or-nothing rule. **`CL-2` supplies it by making the frozen manifest the object Phase 4 must exhaust.**

## 2.3 Interruption **after Phase 5 switch, before Phase 6 demotion** — 🔴 **the plan is internally inconsistent here**

| Source | Where authority moves |
|---|---|
| Implementation design `ae451db9` §3.2 / §4.1 | ⭐ *"after step 3"* (**the switch**) — *"before step 3: runtime; after step 3: the governance evidence boundary"* |
| Migration plan §4 | **Phase 6** is *"authority demotion — declare the runtime copy no longer authoritative"*, a **separate later act** |

⇒ **In the window between Phase 5 and Phase 6, the two accepted artifacts disagree about which store is authoritative.** And the plan's own rollback rule is wrong in exactly that window:

> §8: *"until authority is demoted, the runtime copy is still authoritative and the durable copy is additive — so rollback before Phase 6 is 'stop', not 'undo'."*

🔴 **False after Phase 5.** Phase 5 redirects **writers**. Any append landing after the switch exists **only** in the new store. *"Stop"* would then abandon it, or would re-promote a runtime copy that is **provably behind** — which is a change of authority, i.e. precisely the governance act §8 says is required only *after* Phase 6.

| | |
|---|---|
| **Expected state** | new store = **latest**; runtime = **stale**, with **no on-disk signal** that it is stale |
| **Safe recovery** | ✅ **complete Phase 6** (record what Phase 5 already did), then continue. Backward recovery is reconciliation under §6, **never** *"stop"* |
| ⛔ **Prohibited** | rollback-as-stop · restoring runtime as authority without a governance act · leaving the window open |

⇒ **`CL-4`: define the authority-transfer point as the completion of Phase 5, and make Phase 6 the RECORD of a transfer that already happened — not the event.** *(This is a wording-of-mechanism fix, not a new decision: it selects between two readings the accepted artifacts already contain.)*

## 2.4 Interruption **after Phase 6, before Phase 7**

✅ **Safe and intended.** Both copies exist; the durable one is authoritative and recorded as such; the runtime one is demoted and redundant. **Recovery is simply to run Phase 7.** ⛔ Prohibited: reading the runtime copy as current. ⚠️ **But the demotion is a declaration in a document — invisible to the ad-hoc reader class §1.4 concedes cannot be bound.** ⇒ **`CL-5` again: the runtime copy needs an on-disk demotion marker written in the same act as the switch, not only a record elsewhere.**

## 2.5 Interruption **during Phase 7 removal**

✅ **Safe.** Removal is per-file and the durable copy is verified and authoritative. A half-removed runtime directory is untidy, not lossy. **Recovery: finish.** ⛔ Prohibited: removing any file whose Phase-4 PASS is not in the manifest.

## 2.6 Resolver failure at any point after Phase 5

| | |
|---|---|
| **Expected** | ⛔ **refusal, non-zero, no answer** (`INV-R1`) |
| **Prohibited** | ⭐ **the silent fallback to runtime.** `INV-R1` is the correct rule and it is stated correctly |

⚠️ **But as literally worded it collides with an adopted, test-pinned contract** — §4.3, **`CL-9`**.

## 2.7 A stray writer artifact — small, real, and cheap to close

`saveRecord` writes `$path . '.tmp.' . getmypid()` then renames (`workflow-state.php:104–106`). **An interruption between the two leaves `<workItem>.json.tmp.<pid>` in the record directory.**

✅ **Measured: none present today** (`find .claude/runtime -name "*.tmp*"` → empty), and neither experiment in §3 left one. ✅ **`session-resolve.php:130` globs `*.json`, which excludes that name.**
⚠️ **Phase 1 nowhere states the record predicate.** A recursive copy or a `*` glob would import a **partial write into the durable governance boundary as evidence.** ⇒ **`CL-12`: state the predicate as exactly `*.json` (matching the reader) and quarantine — never migrate, never delete — anything else found.**

---

# 3 · CONCURRENCY ANALYSIS — ⭐ **the one finding that changes what the plan may claim**

## 3.1 The mechanism, read from the code

```
append :  loadRecord($path)            ← read whole JSON              (workflow-state.php:303)
          assertTransitionAllowed()    ← fold, then refuse or allow           (:308)
          $t['seq'] = count($record['transitions']) + 1                       (:309)
          $record['transitions'][] = $t                                       (:310)
          saveRecord($path, $record)   ← tmp + rename (ATOMIC per write)      (:311)
```

⭐ **`rename` makes each write atomic. Nothing makes the read-modify-write sequence atomic.** There is no lock, no lease, no CAS, no version check — and the header says so plainly: *"Increment 2 is not authorized: no lock, no lease, no hook, no enforcement."* **Two concurrent appends both read the same `count()`, both compute the same `seq`, and the second `rename` wins.**

## 3.2 ⭐⭐ **CONFIRMED BY EXPERIMENT — the loss is silent, likely, and DENSE**

**Reproduced in a temp directory, never against the corpus:**

```bash
D=$(mktemp -d); W=.claude/scripts/workflow-state.php
php $W init WI-R --workflow=wf --roles=governance,implementation --dir=$D
php $W append WI-R --dir=$D --json='{"type":"REGISTER","session":"S-B",...}' &
php $W append WI-R --dir=$D --json='{"type":"REGISTER","session":"S-C",...}' &
wait
```

| Result over **30 trials** | |
|---|---|
| **trials that lost a transition** | 🔴 **23 / 30** |
| **exit status of the losing process** | 🔴 **0 — accepted, reported `{"ok":true,"seq":2}`** |
| **sequence set of every survivor** | 🔴 **DENSE and MONOTONIC (`[1,2]`)** |
| same test on the `grant` path | 🔴 **`grants=1`, `ids=G-TWO` — `G-ONE` gone, exit 0** |

> ## ⛔ **CONSEQUENCE, stated as precisely as the evidence allows.**
> **§6's post-reconciliation invariants list four properties as if they were one family:**
> ```
> sequence set is DENSE      ✅ checkable, and TRUE after a loss
> sequence is MONOTONIC      ✅ checkable, and TRUE after a loss
> result is a SUPERSET       ✅ checkable — but only of the INPUTS OBSERVED
> NO record silently dropped ⛔ NOT checkable, and NOT implied by the three above
> ```
> ⭐ **Density is not a completeness proof. It is preserved BY the loss** — the clobbering writer reuses the very sequence number the lost writer took. **The plan's own load-bearing sentence — *"Dense, monotonic `seq` is what makes CASE A mechanically decidable"* — is TRUE for deciding CASE A vs CASE B, and does not extend to completeness. The plan uses it for both.**

## 3.3 What this does and does not mean for the migration

✅ **The defect is PRE-EXISTING and is not caused by the migration.** It is present today, with or without relocation, and repairing it is `Increment 2` — **explicitly unauthorized, and this review does not propose it.**

🔴 **But three things about the migration interact with it, and the plan addresses none:**

| # | Interaction |
|---|---|
| **1** | ⭐ **The migration writes into the corpus it is migrating.** §5 mandates that the migration's own inventory · verification · switch-over · demotion · cleanup records **are governance evidence** in the same boundary. ⇒ **the migration is itself a concurrent writer**, and can be either the victim or the cause of a loss |
| **2** | **The plan deliberately widens the window.** §6.3 accepts a live corpus across Phases 3–5 and rejects quiescing as *"cannot be guaranteed."* ⚠️ **Correct about locks. It then treats the window as merely requiring reconciliation, when under contention the measured loss rate is 77 %** |
| **3** | ⭐ **A loss inside the reconciliation tail is imported as a legitimate extension.** CASE A appends runtime's tail `M+1…N` after a byte-identical prefix. **If a transition was clobbered inside that tail, the tail is still dense and still a strict extension** ⇒ CASE A fires, the union is formed, and **the loss is migrated in, indistinguishable from correctness** |

> ## ✅ **RECOMMENDED MITIGATION — available today, no lock required, mechanically verifiable**
> **A declared write freeze is a GOVERNANCE act, not a lock, and this program already has the vocabulary for it.**
> ```
> Phase 0   PO/ARB declares the migration window: no lane may append
> Phase 1   hash all 18 records → COMMIT the frozen manifest
> Phase 3–4 copy · verify against the frozen manifest
> Phase 5   RE-HASH the runtime corpus and compare to the manifest
>              unchanged →  ✅ the window was quiet; NO reconciliation needed and NO loss possible
>              changed   →  reconcile under §6, and RECORD that loss inside the delta is UNDETECTABLE
> ```
> ⭐ **This converts an unverifiable claim into a verifiable one.** *"No record was lost"* is not provable in general; *"no write occurred during the window"* is provable by 18 hash comparisons. **`CL-1`.**

---

# 4 · RESOLVER ANALYSIS — is §3's invariant sufficient?

## 4.1 The two axes are correctly identified — ✅ and this is the plan's best work

✅ **`RA-2` verified exactly:** `workflow-state.php:81` and `session-resolve.php:74` compute `dirname(__DIR__) . '/runtime/workflow'` **independently, neither consulting the other.** ✅ **`INV-R2` is right to replace rather than synchronise them** — two defaults kept in agreement is the defect, not the fix.
✅ **`P-4` is verified at `session-resolve.php:90`** and the §1.5 finding — that authority has a **location** axis and an **interpreter** axis — is correct and materially sharper than the decision it elaborates.

## 4.2 ⭐ **`P-4` is technically stronger than the plan states, and the direction matters**

**The plan characterises `P-4` as *"the right bytes read by the wrong mechanism."* The code is worse than that:**

```php
$mechanism = getenv('KOS_MECHANISM_PATH') ?: (__DIR__ . '/workflow-state.php');   // :90
proc_open(array_merge(['php', $mechanism], $args), …);                            // :103
askMechanism($mechanism, ['fold', $workItem, '--dir=' . $recordDir]);             // :156
```

> ⭐ **`session-resolve.php` EXECUTES an arbitrary program named by the environment and HANDS IT the authority record directory as an argument.**
> ⇒ **This is not only an interpretation path. It is a WRITE path — through a component whose own bytes contain no write call.**
> ✅ **The script's docblock is admirably honest** (*"technically capable of substituting the workflow interpreter… NOT a hardened boundary and must not be described as one"*), and `T-11` genuinely pins read purity **of this file**. ⛔ **`T-11` does not, and cannot, pin the purity of a substituted subprocess.**
> ⚠️ **Relocation raises the value of this path**: after Phase 5 its target is the durable, versioned, authoritative store.

**⇒ `INV-R3` is therefore MORE necessary than the plan argues, and its enforcement is a genuine behaviour change rather than a tidy-up.** *(See `CL-10`; the enforcement form is the routed question.)*

## 4.3 🔴 `INV-R1` as worded collides with an adopted, test-pinned exit contract

| | |
|---|---|
| **`INV-R1`** (plan §3) | *"the resolver MUST NOT silently fall back to runtime… ⛔ **refuse and exit non-zero**"* — and *"Unresolved → ⛔ refuse"* |
| **`AST-016` AMENDMENT 1**, pinned by **`T-12`** | *"A produced ResolutionReport is a SUCCESS: RESOLVED · UNASSIGNED · AMBIGUOUS · **UNRESOLVABLE all exit 0**. Non-zero is reserved for usage errors (64) and for **refusal to produce a report at all** (65)"* |

⇒ **Phase 5 as written instructs an implementer to make an unresolved state exit non-zero, which breaks `T-12`.**

✅ **They are reconcilable, and the reconciliation is a distinction the plan simply has not drawn:**

| Condition | Correct behaviour | Contract |
|---|---|---|
| **the authority LOCATION cannot be resolved, or resolves outside the governed boundary** | ⛔ **refuse to produce a report at all — exit 65** | ✅ consistent with AMENDMENT 1's own *"refusal to produce a report"* clause |
| **the location resolved; the workflow state is `UNRESOLVABLE`** | ✅ **produce the report, exit 0** | ✅ `T-12` untouched |

⭐ **`INV-R1` binds the FIRST. `T-12` governs the SECOND. Stated that way, no contract is amended and no test changes.** **`CL-9`.**

## 4.4 Is the invariant sufficient to prevent the three named hazards?

| Hazard | Sufficient? |
|---|---|
| **two writers using different authority locations** | ✅ **Yes for defaults** — there is exactly **one** writer (`workflow-state.php`, `saveRecord` called from `:291`/`:311`/`:338`), so `INV-R2` closes it at one site. ⛔ **No while `--dir` is unrestricted**: `P-3a/b` survive Phase 5 untouched by §7's own admission, so acceptance criterion 6 is **not achievable by this plan's phases** (`CL-3`) |
| **interpreter divergence** | ⚠️ **Only in the reporting form.** Today substitution is *visible* (`interpreter.isDefault`, `[substituted]` on every rendering) and **never refused** — by design, per `C-1`. `INV-R3` demands it be *governed*; **nothing in the phase list builds that** (`CL-3`, `CL-10`) |
| **silent fallback** | ✅ **Yes, and it is already true of the code**: `askMechanism` returns `null` and the resolver reports `UNRESOLVABLE` — *"it never falls back to reading records itself"*, pinned by `T-13(b)`. ✅ `INV-R1` preserves this property; ⚠️ subject to §4.3's wording fix |

## 4.5 ⭐ The gap that makes Phase 5 non-executable

```
Phase 2  create the durable target                    ✅ produces a location
Phase 5  "switch all writers and readers TO THE §3 RESOLVER"
              ↑
         ⛔ NO PHASE PRODUCES THE RESOLVER.
```

**§3 specifies the resolver's inputs, outputs, validation and failure behaviour — a specification, not an artifact.** §7 then explicitly declines to implement the override restriction (*"This plan does not restrict the flag"*), and §12 fences out *"no runtime code modified."* ⇒ **acceptance criteria 6 and 7 assert outcomes the seven phases cannot reach.** **`CL-3`.**

---

# 5 · `R-CONFLICT` ANALYSIS

## 5.1 Against the four mandated tests

| Test | Verdict |
|---|---|
| **no silent data loss** | ⚠️ **HOLDS relative to the inputs observed; FAILS as an absolute guarantee** — §3. The plan must claim the former |
| **no sequence rewriting** | ✅ **HOLDS.** CASE A appends a tail; no `seq` is renumbered; §4.1 forbids re-encoding. `seq` verified **dense and monotonic in all 18 records** |
| **no silent winner** | ✅ **HOLDS.** CASE A is a union, not a selection — correctly argued. CASE B escalates and selects nothing. ✅ §6.1/§6.2 keep adopted invariant text and migration guidance apart, exactly as `AMD2` required |
| **deterministic detection of divergence** | ⚠️ **HOLDS BETWEEN the two stores; is IMPOSSIBLE WITHIN one store** — §3.3(3). The plan should state which one it is claiming |

## 5.2 🔴 The reconciliation algorithm is defined for **RECORD 1 only**

**Measured across the corpus:**

| | |
|---|---|
| transitions | **216** · **every one carries `seq`** · dense and monotonic in **18/18** records |
| grants | **110** · ⭐ **`0` carry `seq`** — no sequence, no ordering identity, and only **2 of 216** transitions carry any time field |

> ⛔ **§6's post-reconciliation invariants — dense · monotonic · superset — are stated over `seq`, and `seq` does not exist on grants.** ⇒ **the only mechanical completeness test the plan specifies does not reach RECORD 2 — the Authority State, which is the authority-bearing half and the half `G-2` gives a single writer.**

⚠️ **And the obvious substitute key is unsound.** Measured: **110 grants, 109 unique `grantId`s.**

```
G-KOS-GOVGAPS-VERIFY   in KOS-GOV-GAPS-001.json        (idx 0)  scope: "…of the six governance-model gaps G-1..G-6 …against the RUNNING MECHANISM…"
G-KOS-GOVGAPS-VERIFY   in KOS-GOV-GAPS-VERIFY-001.json (idx 0)  scope: "…FOR EACH of the six, determine … TRUE, PARTIALLY TRUE, FALSE…"
```

⭐ **Two distinct authority acts — different `humanActRef`, different `scope`, both `AUTHORIZED` — share one identifier.** ⇒ **any verification or dedup keyed on `grantId` would treat them as one, which is a silent loss of an authority act — the precise act `R-CONFLICT` forbids.**

✅ **Mitigating facts, stated so the finding is not overdrawn:** the two collide across **different work items**, so **per-record reconciliation is unaffected** (grant identity inside a record is array position, which byte-preserving copy preserves). ✅ **And this is NOT the cause of the `109`/`110` delta** — that is grant index 6, `G-KOS-GOV-STATE-DURABILITY-OPEN-M3-DECISION`, registered after `3817eb2b`, exactly as the Governance review's `INFO-1` concluded. **Two independent facts; neither explains the other.**

⇒ **`CL-7`: extend the reconciliation rules and the completeness check to grants explicitly, keyed on position-plus-content, never on `grantId`.**

## 5.3 🟡 *"common prefix byte-identical"* is underspecified

**`saveRecord` rewrites the ENTIRE file on every append.** ⇒ **a file-level byte-prefix comparison between the copy and a since-appended runtime record is meaningless** — the closing brackets move, so the byte prefixes diverge at the first structural difference regardless of content agreement.

✅ **The intended comparison is element-wise over the decoded `transitions` array**, and that is legitimate: **§4.1 forbids parse-and-reserialize for the COPY, not for the COMPARISON.** ⚠️ **The plan never says so**, leaving an implementer to either perform the wrong comparison or believe parsing is prohibited. **`CL-8`.**

## 5.4 Byte preservation — ✅ correct, and ⚠️ one dependency the plan does not fence

✅ **The argument in §4.1 is exactly right and verified in the code:** `json_encode($record, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"` (`:105`) — re-encoding under any other flags yields a semantically equal file with a different hash, which would destroy the only cheap integrity check. ✅ **A single writer means one encoding to preserve.**

🔴 **But the durable artifact is a git blob, and git — not the copy — decides the committed bytes.**

```bash
$ cat .gitattributes | head -1
* text=auto
$ git check-attr text eol -- docs/knowledgeos/evidence/KOS-X.json
… text: auto        … eol: unspecified
```

| | |
|---|---|
| **Today** | ✅ **harmless** — all 18 records are LF-only (verified: no `CR` in any record), so normalisation on commit is a **no-op**, and this working tree's checkout is byte-identical |
| **The property actually claimed** | ⚠️ **that a FUTURE verifier can recompute the hash.** Under `* text=auto` with `eol` unspecified, checkout applies `core.eol` (default **native**) ⇒ **a clone on a CRLF platform materialises different bytes and the Phase-4 hashes no longer verify** |
| ⭐ **The fence gap** | **§12 forbids touching `.gitignore`** — and is **silent about `.gitattributes`**, which is the file that actually governs whether committed bytes equal copied bytes |

⇒ **`CL-6`: pin the evidence path in `.gitattributes` (`-text`, or `text eol=lf`) BEFORE Phase 3, and commit the Phase-1 manifest in the same commit as the copy so manifest and artifact cannot drift.**

## 5.5 ⚠️ *"Durable"* is carrying two different properties

`saveRecord` does **not** `fsync`. `rename` gives **atomicity of visibility**, not durability across power loss. ⇒ **the migration cures UNATTESTABILITY (the record is unversioned), not CRASH DURABILITY.** Both are real; they are different; the plan uses one word for both. **`CL-11`** — one sentence, not a redesign.

---

# 6 · INVENTORY / FREEZE — ✅ **YES, RE-EXECUTION IS REQUIRED**

**The Governance review's `INFO-2` flagged the literal as stale and correctly declined to propose a remedy. This review supplies the technical answer, and the decisive argument is stronger than staleness.**

## 6.1 The literal is not merely stale — it is **unattestable**

```bash
$ git log --oneline -- .claude/runtime/workflow/KOS-AIP-GOV-STATE-DURABILITY-ADR.json
(empty)
$ git check-ignore -v .claude/runtime/workflow/…   →  IGNORED (.gitignore:25, :32)
```

> ⭐ **The corpus has NO history, so `18 / 216 / 109` cannot be re-derived at any later date from anything.** ⇒ **acceptance criterion 1 pins the migration to a number that is, by the very defect `B′` exists to close, not evidence.**
> ⚠️ **This also bounds the Governance review's own conclusion — *"the plan was exact at its commit"* is a reasonable inference, not an attestable fact, and for the same reason.** **The migration's first act is what makes the figure attestable for the first time.**

**Measured now, independently:** **18 records · 216 transitions · 110 grants** *(109 unique `grantId`s — §5.2)*. **Both circulating figures are explainable; neither is a criterion.**

## 6.2 Required design

```
Phase 0   PO/ARB declares the migration window (a governance act — NOT a lock)
Phase 1   FRESH inventory: enumerate `*.json`, hash each file, count transitions and grants
          ⇒ COMMIT the manifest as the FIRST migration-evidence artifact
Phase 3   migrate EXACTLY the frozen manifest — nothing discovered later joins this run
Phase 4   verify the destination AGAINST THE FROZEN MANIFEST, all-or-nothing
Phase 5   RE-HASH the source and compare to the manifest:
              unchanged → ✅ the window was quiet; no reconciliation, and no loss possible
              changed   → reconcile the delta under §6 CASE A/B as a NAMED STEP,
                          and RECORD that loss inside the delta is undetectable (§3.3)
```

**⇒ the committed figures become PLANNING EVIDENCE. The frozen manifest becomes the acceptance criterion.** ⭐ **This also repairs the deeper flaw in criterion 1: a fixed literal cannot distinguish *"a record is missing"* from *"the corpus legitimately grew"*, and the second is certain to happen — the corpus grew during the ADR, during the design, during planning, and again during the Governance review.**

> ### **CLASSIFICATION: `DESIGN CLARIFICATION` — ⛔ NOT a new architectural decision.**
> It selects the *object* of an existing acceptance criterion. It creates no boundary, no owner, no rule, and no capability. **`B′`, `R-CONFLICT`, `OPEN-M3` and placement governance are untouched.** **`CL-2`.**

---

# 7 · REQUIRED DESIGN CLARIFICATIONS

**Ranked. ⭐ `CL-1`–`CL-3` are GATES ON PHASE 3 — the migration must not begin until they are closed.**

| # | Clarification | Class |
|---|---|---|
| ⭐ **CL-1** | **Add the write-freeze + re-hash discipline of §6.2, and restate §6's fourth invariant and acceptance criterion 2 as *"no record silently lost RELATIVE TO THE OBSERVED INPUTS."*** ⛔ Do not assert absolute completeness: §3 falsifies it, and density is preserved by the loss | **GATE** |
| ⭐ **CL-2** | **Re-execute Phase 1 at execution time; commit the frozen manifest; make criterion 1 reference the manifest, not `18/216/109`. Phase 4 verification is all-or-nothing against it** | **GATE** |
| ⭐ **CL-3** | **Name the act that PRODUCES the §3 resolver, and restate criteria 6 and 7 to what the seven phases can actually deliver** (defaults unified; `--dir`/`KOS_MECHANISM_PATH` hardening is a separate authorized act, per §7's own fence) | **GATE** |
| **CL-4** | **Define the authority-transfer point as the completion of Phase 5**, make Phase 6 the *record* of it, and **correct §8: rollback in the 5→6 window is reconciliation, not *"stop"*** | design |
| **CL-5** | **Two on-disk markers:** the staging target marked **non-authoritative** for the whole 3→5 window (§2.1), and the runtime copy marked **demoted** in the same act as the switch (§2.4) — because the reader class that needs the signal is the one §1.4 concedes cannot be bound | design |
| **CL-6** | **Pin the evidence path in `.gitattributes` (`-text` / `text eol=lf`) BEFORE Phase 3**, and commit the manifest with the copy. **§12's fence names `.gitignore` and misses the file that governs the committed bytes** | design |
| **CL-7** | **Extend reconciliation and the completeness check to RECORD 2 (grants):** 110 grants carry **no `seq`**, and ⛔ **`grantId` is not unique corpus-wide** — key on position-plus-content, never on `grantId` | design |
| **CL-8** | **Specify *"common prefix byte-identical"* as element-wise over the decoded `transitions` array** — and state that parsing for COMPARISON is permitted, while §4.1's prohibition binds the COPY | design |
| **CL-9** | **Split `INV-R1`:** unresolvable/out-of-boundary **location** ⇒ refusal to produce a report, **exit 65**; **workflow-state** `UNRESOLVABLE` ⇒ report, **exit 0**. ⭐ **So worded, `T-12` needs no amendment** | design |
| **CL-10** | **State `INV-R3`'s technical basis accurately:** `session-resolve.php:103/156` **executes** the env-named program **with the authority directory as an argument** — a write path through a structurally read-only component, whose target becomes the durable store after Phase 5. **`T-11` cannot pin a subprocess** | design |
| **CL-11** | **Separate the two senses of *"durable"*:** the migration cures **unattestability**; `saveRecord` has no `fsync`, so **crash durability is unchanged** | wording |
| **CL-12** | **State Phase 1's record predicate as exactly `*.json`** (matching `session-resolve.php:130`), and **quarantine — never migrate, never delete —** any `*.tmp.<pid>` remnant | design |

## 7.11 ⚠️ **ONE QUESTION ROUTED TO PO/ARB — it does not change the verdict**

**`OPEN-M3` is decided: Option A extended the Single Authority Resolver's scope to cover interpreter selection. This review does not reopen it. The routed question is narrower, and it is a contract question, not a boundary question:**

> **`INV-R3` has two implementable forms:**
> | Form | Consequence |
> |---|---|
> | **REPORTING** — substitution permitted, **authority never conferred**, substitution reported (today's behaviour, `C-1`) | ✅ **`T-13(b)`, `T-14`, `T-15` all continue to pass** — `T-13(b)` and `T-15` **deliberately set `KOS_MECHANISM_PATH`** |
> | **ENFORCING** — *"an environment variable may not select the authority interpreter"* (the literal wording) | 🔴 **removes the exact seam `T-13(b)` and `T-15` exercise** ⇒ **amending `AST-016`'s AMENDMENT-2 contract**, which is beyond a relocation |

**⇒ Question for the PO/ARB, decided by no one here: does bringing `P-4` under governance in its ENFORCING form authorize an amendment to the `AST-016` contract tests, or is enforcement a separate act after the migration?**

✅ **The migration can execute on the REPORTING form** — it changes no adopted contract and leaves `INV-R3`'s enforcement to a named later act. **Hence a routed question, not a block.**

---

# 8 · EXPLICIT NON-DECISIONS

⛔ **Not decided, not proposed, not implied by anything above:**

**No new bounded context · no `C-10` existence or lineage · no `D2` capability establishment · no `D6` category · no `D7` ownership · no ledger architecture (`Option C` remains deferred, and `OPEN-M4` remains open) · no new placement governance and no new placement rule · no physical path, sub-path, file granularity or naming · `B′` not revisited · `R-CONFLICT` NOT modified, extended, or reinterpreted · `OPEN-M3` not reopened · `ADR:OQ-2` untouched · `OPEN-M1` (`.gitignore`) not decided · `OPEN-M2` (evidence-before-target ordering) not decided — `CL-2` constrains it but does not resolve it · `Increment 2` (locks, leases, hooks, enforcement) NOT proposed: §3 REPORTS a pre-existing writer defect and asks the plan to stop claiming a guarantee it contradicts · no amendment to `T-11`–`T-15` or `R1`–`R8` · no `--dir` or `KOS_MECHANISM_PATH` restriction designed or implemented.**

⛔ **And this review does not:** modify the migration plan · execute or begin any migration · approve or accept the plan · certify the work item · close the lane (`G-1`) · verify its own findings (`R-34`/`P-2`).

## 8.1 Limitations of this review, stated rather than buried

**CAN establish:** internal coherence of the plan against the code as it runs today · behaviour under the interruption and concurrency cases enumerated in §2–§3 · whether the resolver design covers the hazards it names · whether the inventory must be refrozen.
**CANNOT establish:** that the enumerated failure cases are exhaustive · that the migration will succeed · anything about executed behaviour, since **nothing was executed against the corpus** · that the plan is complete or traceable — **that is `b64828fe`'s gate, and this review relied on it rather than repeating it** · acceptance, which is the PO/ARB's alone.
⚠️ **`bc1b47ef` is self-declared and not attestable** (`INV-ATTR-2`): independence rests on where the session was started, which is `G-2` itself.

---

# 9 · GATE STATE AFTER THIS REVIEW

```
✅ Architecture decision            B′ · R-CONFLICT · placement
✅ Implementation design            ae451db9
✅ Migration plan                   3817eb2b
✅ Governance review                completeness · provenance · planning-only
🟡 Technical Architecture review    PASS WITH DESIGN CLARIFICATIONS  ← this artifact
⏳ CL-1 · CL-2 · CL-3               GATES — Phase 3 must not begin until closed
⏳ PO/ARB                           §7.11 routed question · acceptance
⏳ Migration execution
⏳ Post-migration verification
```

⭐ **`C-2` is unchanged by this review and the record's existing distinction is correct: architecturally resolved by the `OPEN-M3` scope extension, operationally unresolved until Phase 5 actually brings `P-4` under the resolver.** **§4.2 and `CL-10` sharpen why that operational gap matters — they do not close it.**

---

**TECHNICAL ARCHITECTURE REVIEW DELIVERED · STOPPING.**
**Next actor: the migration-plan owner, to close `CL-1`–`CL-3` as a plan amendment; then PO/ARB for §7.11 and acceptance.** ⛔ **Execution after those gates, not before.**

**Traceability:** plan `3817eb2b` §1–§12 · Governance review (`INFO-1`/`INFO-2`/`INFO-3` all confirmed independently; `INFO-2` answered in §6) · implementation design `ae451db9` §3.2/§4.1 (authority-transfer contradiction, §2.3) · ADR `67a8e75e` · `B′` · adopted `R-CONFLICT` · `OPEN-M3` Option A · `AMD1`/`AMD2` · `ADR_20260801_1740` + `scripts/doc-placement.php` (exit 0 → `docs/knowledgeos`) · **code read directly:** `workflow-state.php:79–84, 99–107, 291, 302–312, 314–339` · `session-resolve.php:74, 90, 98–120, 127–134, 143–147, 154–163, 293–299` · `tests/Unit/Platform/WorkflowEngine/SessionAssignmentResolverContractTest.php` T-11/T-12/T-13/T-15 · `WorkflowStateRecordContractTest.php` §8-concurrency header · `.gitignore:25,32` · `.gitattributes:1` · **measured:** 18 records / 216 transitions / 110 grants / 109 unique `grantId`s / `seq` dense-and-monotonic 18-of-18 / 2-of-216 transitions carry a time field / 0-of-110 grants carry `seq` · **reproduced:** 23-of-30 concurrent-append trials lost a transition with exit 0 and a dense sequence; concurrent `grant` loss reproduced · `RA-2` · `INV-ATTR-1`/`INV-ATTR-2` · `R-34`/`P-2` · `G-1`.
