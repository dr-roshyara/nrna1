# `KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN`

**Status: 🟡 PROPOSED.** Planning only — **no migration executed, no file moved, no default changed, no `.gitignore` touched, no authority record modified.**
**Lane:** `S4b-architecture-gov-state-impl-design` (seq 4 REGISTER · 5 HANDOFF · 6 START) · **Grants:** `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN` **+ AMD1 + AMD2**
**Date:** 2026-08-19 · **Producing process, self-declared, NOT attestable** (`INV-ATTR-2`/`G-2`): `claude-code-session:1c8b041b` — **not** the Governance registrar `b64828fe`, as the routing requires. **`R-34`/`P-2`: this process must not verify or accept this plan.**

**Placement resolved through existing governance, not chosen:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → **`docs/knowledgeos`**, **exit 0**. ⇒ the AMD2 exit-2 `PENDING`/escalate branch **did not fire**; `architecture/` matches the artifact class.

---

# 1 · Current-state inventory — `OBSERVED`, measured, nothing modified

## 1.1 The evidence corpus

| | Measured |
|---|---|
| **Authority records** | **18** JSON files under `.claude/runtime/workflow/` |
| **Transitions** | **216** |
| **Grants** | **109** |
| **Size** | **780 KB** |

**Per-record shape varies and the migration must not normalise it.** Two records carry **grants with zero transitions** (`KOS-ARCH-V3-BOUNDARY-VALIDATION` 0/3 · `KOS-ACTIVATION-REPORTING-001` 0/0) — **an authorized-but-uncommissioned lane is a legitimate shape, not a defect to tidy.**

## 1.2 ⭐ The durability defect, stated as measurement

```
.gitignore:25   .claude/runtime/
.gitignore:32   .claude/runtime/          ← the rule is present TWICE
git check-ignore -v .claude/runtime/workflow/KOS-CONTRACT-NEUTRALITY-001.json  →  IGNORED
```

> **The authoritative governance record is UNVERSIONED.** Every narrative artifact that cites it is versioned; **the record they cite is not.** ⇒ **historical content is unattestable by anyone**, which is the finding `B′` exists to close.

## 1.3 Writers — **exactly one**

| Writer | Evidence |
|---|---|
| `.claude/scripts/workflow-state.php:105` | `$tmp = $path . '.tmp.' . getmypid(); file_put_contents($tmp, …); rename($tmp, $path);` — **atomic write via temp + rename**, and it `mkdir`s the parent |

✅ **A single writer is the migration's biggest asset:** the cutover has one code path to switch, not many.

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

| | Resolution input | Output | Validation | Failure | Unresolved |
|---|---|---|---|---|---|
| **Record location** | work-item id (+ optional governed override) | the durable evidence location | the location is **inside the governed evidence boundary** | ⛔ **refuse and exit non-zero** | ⛔ **refuse** |
| **Mechanism identity** | none — it is fixed by governance | the authority interpreter | the interpreter is the governed one | ⛔ refuse | ⛔ refuse |

⛔ **`INV-R1` — the resolver MUST NOT silently fall back to runtime.** A missing or invalid target is a **refusal**, never a downgrade. *(A silent fallback would re-create the split `B′` removed, and would do it invisibly.)*
⛔ **`INV-R2` — `P-1` and `P-2` are replaced by ONE resolution, not synchronised.** Keeping two defaults "in agreement" is the defect, not the fix.
⛔ **`INV-R3` — `P-4` is brought under governance.** An environment variable may not select the authority interpreter.
⚠️ **`INV-R4` — the invariant binds components, not the filesystem** (§1.4). It is enforceable at every resolving call site and **advisory for ad-hoc reads**; the plan does not claim otherwise.

---

# 4 · Migration phases — seven, and **the order is itself an invariant**

> ## ⭐ **`INV-ORDER` (Flag R, promoted): durability is CREATED and VERIFIED (2–4) BEFORE authority is DEMOTED (6), and removal is LAST (7).**
> ### **CONSEQUENCE: at no point in the sequence does the authority record exist in only one place.**
> **Any future re-ordering is therefore visibly a violation, not a preference.**

| Phase | Act | Precondition | Produces |
|---|---|---|---|
| **1 · Inventory** | enumerate every record, transition, grant; hash each file | — | **inventory evidence** (§5) |
| **2 · Durable target** | resolve the target through existing placement governance; create it | resolver exit 0 | placement evidence |
| **3 · Byte-preserving copy** | copy **bytes exactly** | Phase 1 complete | — |
| **4 · Integrity verification** | byte equality · **hash equality** · record count · sequence continuity · provenance continuity | Phase 3 complete | **verification evidence** |
| **5 · Authority path switch** | switch **all** writers and readers to the §3 resolver — `P-1`, `P-2`, `P-3a/b`, `P-4` | **Phase 4 PASSED** | switch-over evidence |
| **6 · Authority demotion** | declare the runtime copy **no longer authoritative** | Phase 5 complete | demotion record |
| **7 · Runtime cleanup** | remove the obsolete runtime copy | **Phases 4 AND 5 passed** | cleanup evidence |

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

✅ **They land at the same resolved durable target as the corpus they attest.** ⚠️ **Ordering consequence:** Phase 1 and Phase 4 produce evidence **before** Phase 2's target may exist — so either the target is created first (Phase 2 before Phase 1's write) or the early evidence is staged and committed to the durable boundary as soon as the target exists. **`OPEN-M2` records this; the plan does not resolve it by reordering the mandated phases.**

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

### CASE A — strict extension *(common prefix byte-identical)*

**Migration interpretation:** preserve **both** inputs · preserve the **union** · preserve **every** sequence · **append the missing tail**. ⛔ **This is not selection of one side** — the union is a superset of both.

### CASE B — same sequence, different content

**Migration interpretation:** preserve **both** records · **record the conflict** · ⛔ **do not select a winner silently** · **escalate**.

### Post-reconciliation invariants — checkable, not aspirational

```
sequence set is DENSE          (no gaps introduced)
sequence is MONOTONIC
result is a SUPERSET of both inputs
NO record silently dropped
```

⭐ **Dense, monotonic `seq` is what makes CASE A mechanically decidable** — a prefix comparison is exact, so "is this an extension or a divergence?" is answered by inspection rather than by judgement.

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

---

# 8 · Rollback

**Trigger conditions:** Phase 4 verification fails · Phase 5 leaves any component unswitched · a CASE B conflict is discovered mid-migration · the resolver cannot resolve.

| Rollback MUST preserve | Rollback MUST NOT |
|---|---|
| historical evidence | ⛔ delete evidence |
| sequence integrity | ⛔ rewrite history |
| provenance | ⛔ silently choose one source |
| reconstructability | ⛔ restore an obsolete runtime source **as authority** without explicit governance handling |

⭐ **`INV-ORDER` makes rollback cheap before Phase 6:** until authority is demoted, the runtime copy is still authoritative and the durable copy is additive — **so rollback before Phase 6 is "stop", not "undo".** **After Phase 6, rollback is a governance act**, because re-promoting a demoted source is a change of authority and must be recorded as one.

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

| # | Criterion | Demonstrated by |
|---|---|---|
| 1 | every existing authority record accounted for | Phase 1 inventory vs **18 / 216 / 109** |
| 2 | no record silently lost | Phase 4 count + superset check |
| 3 | sequence integrity preserved | density + monotonicity check |
| 4 | provenance preserved | per-record provenance continuity |
| 5 | byte integrity preserved | **hash equality**, per file |
| 6 | all writers use one authority-resolution path | `P-1` retired; the single writer switched |
| 7 | all readers use the same boundary | `P-2` retired; `P-4` governed; **§1.4's advisory limit stated** |
| 8 | runtime is not an authority source after cutover | Phase 6 demotion record |
| 9 | migration evidence is itself durable | §5 |
| 10 | conflict handling follows the adopted invariant | §6, CASE A / CASE B |

---

# 11 · Open architectural questions

| | Question | Why it is not closed here |
|---|---|---|
| **`OPEN-M1`** | **Does the governed evidence location remain gitignored?** `B′` requires durability; `.gitignore:25`/`:32` currently exclude the runtime path. **The target is under `docs/knowledgeos`, which is versioned — so the defect resolves by relocation** — but **the plan is forbidden to touch `.gitignore`**, and whether the runtime rule is later narrowed is a separate act | STOP constraint: no `.gitignore` change |
| **`OPEN-M2`** | **Phase 1/4 evidence precedes the Phase 2 target** (§5). Stage-then-commit, or create the target first? | Reordering the mandated phases is not Architecture's to do |
| **`OPEN-M3`** | ⭐ **`P-4` (`KOS_MECHANISM_PATH`) is a second authority axis the decision did not record.** Bringing it under the resolver is within `RA-2`'s spirit — **is it within its letter?** | If this is a **new** boundary rather than an application of the decided one, the AMD2 escalation trigger fires |
| **`OPEN-M4`** | **The reader set is unbounded** (§1.4). The invariant is advisory for ad-hoc reads | A total guarantee would require a storage change — **a ledger — which the scope fence excludes** |

⚠️ **`OPEN-M3` is the one that may require PO/ARB.** The plan **does not decide it** and flags it exactly as the escalation trigger requires.

---

# 12 · What this plan does NOT do

⛔ **No migration executed** · no file moved or copied · no default changed · **no `.gitignore` change** · **no runtime code modified** · **no authority record modified** · no ledger introduced · no new placement rule · no repository layout chosen · no implementation technology selected · **`R-CONFLICT` not modified** · no `--dir` restriction implemented · **no acceptance and no self-verification.**

**Verified after writing:** `.claude/runtime/` unchanged (18 records, 216 transitions, 109 grants); `.claude/scripts/` clean; `.gitignore` untouched.

---

**MIGRATION PLAN DELIVERED · STOPPING.**
**Next actor: Governance — completeness and provenance review.** ⚠️ **That review is bounded to completeness and provenance; it is not substantive verification of this plan and must never be cited as independent verification of it.** **PO/ARB only if `OPEN-M3` is judged a new architectural boundary.**

**Traceability:** `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN` + `AMD1` (flags P/Q/R promoted to invariants; seven sections; CASE A/B) + `AMD2` (wording discipline; placement via the resolver; the Single Authority Resolver Invariant; the escalation trigger) · accepted design `ae451db9` · `B′` · the adopted `R-CONFLICT` invariant (quoted §6.1) · Decision 3 (existing placement governance) · `ADR_20260801_1740` + `scripts/doc-placement.php` (exit 0 → `docs/knowledgeos`) · **primary evidence read directly:** `.claude/scripts/workflow-state.php:25,81,100–107` · `.claude/scripts/session-resolve.php:22,74,90` · the five runtime-client shell scripts · `.gitignore:25,32` · all 18 workflow records · `RA-2` · `E-1` · `INV-ATTR-2`/`G-2` · `R-34`/`P-2`.
