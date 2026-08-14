# KOS-SESSION-DISCOVERY-001 corrective increment — **Session 1 independent verification**

**Date:** 2026-08-15 · **Verifying:** `84100bb0` (C-1/C-2 corrective) · **Assignment:** `S1-verify-discovery-corrective` · **Grants:** `G-KOS-DISC-C12-VERIFY` · `G-KOS-DISC-V3-ASSESS`
**Session 3's report was not used as evidence. The six checks were run independently and V-3 was investigated by controlled experiment.**

---

## 1 · Startup — valid, including the R-34 self-check

`identity` → role `verification`, **state `ACTIVE`**, predecessor `S3-impl-discovery-corrective`, linkage `G-KOS-DISC-V3-ASSESS`. Both grants `AUTHORIZED`.

**R-34 self-check — the load-bearing one:** my last act in this process was `beb26177`. `84100bb0` and the four governance commits **post-date it and were authored by another process.** **This process did not implement the increment under verification.** *(Per `O-4` I did not treat any `authorized: true` as proof of authority; the operative fact is `identity` → `ACTIVE`.)*

## 2 · The six registered checks

| # | Check | Evidence | Verdict |
|---|---|---|---|
| **1** | Interpreter identity in **both** renderings | machine: `interpreter{path, available, isDefault}` · human: `interpreter: …/workflow-state.php [default AST-015]` | ✅ **PASS** |
| **2** | Normal resolution still correct | `RESOLVED`/`AMBIGUOUS`/`UNASSIGNED`/`UNRESOLVABLE` all behave as before; `--session=S1-verify-discovery-corrective` → `RESOLVED`, `operable:false`, `CREATED` | ✅ **PASS** |
| **3** | Substitution **changes reported identity** and is **not silently hidden or rejected** | with the lying stub: `path: …/liar.php`, **`isDefault: false`**, `available: true`, verdict still produced (`AMBIGUOUS`) — substitution is *disclosed*, not *refused* | ✅ **PASS** — this is exactly C-2's cure for my `V-2` |
| **4** | No independent interpretation; remains AST-015-dependent | mechanism absent → `UNRESOLVABLE`, **0 candidates**, `isDefault:false`; no fallback branch exists in source | ✅ **PASS** |
| **5** | Read purity | MD5 of all workflow records before/after the campaign (incl. substituted- and absent-mechanism runs): **byte-identical** | ✅ **PASS** |
| **6** | No authorization / activation / ownership / state mutation | `caveat`: *"Resolution is not activation. This report creates no authority, no ownership, no state change."* `operable` derives only from AST-015's reported state | ✅ **PASS** |

**`V-2` is genuinely cured:** the seam is no longer undocumented — every report now names its interpreter and flags non-default substitution.

## 3 · 🔑 V-3 — reproducible, systematic, and **not the resolver's defect**

### The controlled experiment

Two records built through AST-015 itself, **identical except for one fact**, with neither verifier started:

```
CASE-A : HANDOFF from=impl to=verif  RECORDED      (verified present in the record)
CASE-B : that handoff NOT recorded                  (verified absent)
```

**Resolver output — identical in both:**

```
CASE-A  verdict RESOLVED  state CREATED
        missingForActivation: ["a recorded predecessor HANDOFF carrying its token",
                               "a recorded human START act"]
CASE-B  verdict RESOLVED  state CREATED
        missingForActivation: [ …the same two… ]
```

> **V-3 REPRODUCED and SYSTEMATIC.** The resolver reports a missing predecessor handoff for a session whose handoff **is** recorded. It cannot distinguish A from B.

### Why — and this reverses the obvious conclusion

**AST-015's `fold` does not expose the handoff fact.** Emitted keys: `workItem · workflow · roles · sessions · mutationOwner · workItemState · grants`. **`handoffsTo` is computed inside AST-015** — its `START` validator uses `$fold['handoffsTo'][$id]` — **but is never emitted through the read interface.**

> **Therefore the resolver cannot tell the truth here without violating Safeguard B.** To distinguish A from B it would have to read the records and derive the fact itself — precisely the independent interpretation the approved architecture forbids and which I falsified twice at the previous gate. **Its over-reporting is the architecturally correct behaviour given what it is told.**

### Classification

| Option | Verdict |
|---|---|
| **A · implementation defect** | 🔴 **NO.** The resolver cannot compute what the qualified mechanism does not expose, and computing it would breach Safeguard B |
| **B · architecture / specification gap** | ✅ **YES.** The approved architecture requires the resolver to state activation prerequisites, but AST-015's **read interface does not carry the fact needed to state them truthfully.** The gap sits at the *interface between two qualified components*, and it is in **AST-015**, not the resolver |
| **C · acceptable limitation** | ⚠️ **Only if** the disposition is to reword `missingForActivation` as *"the two conditions activation requires"* rather than *"the ones that are missing"* — a truthfulness-of-wording choice, not a capability |

**Does the architecture require record-derived truth here?** **NOT ESTABLISHED** — the approved architecture requires the resolver to *report* prerequisites and to *delegate* interpretation. **It does not appear to state which component must supply the handoff fact.** That silence is the gap, and naming it is as far as my authority goes.

**Consequence already observed in the wild:** this behaviour caused a competent session to file a false mechanism defect against a sound record. **The record was right; the report was wrong.** Cost so far: one wasted reconciliation cycle.

## 4 · Findings, classified

**VERIFIED FACT** — all six checks pass · `V-2` cured by C-2 · read purity byte-verified · Safeguard B holds under mechanism absence · substitution disclosed rather than concealed.

**ARCHITECTURE / SPECIFICATION GAP (`V-3`)** — AST-015's `fold` does not expose `handoffsTo`, so no delegating consumer can report activation prerequisites accurately. **Disposition is Governance/ARB's**; the candidate remedies I can see — expose the fact in `fold`, or reword the resolver's list — sit in **different components under different grants**, which is exactly why I do not choose between them.

**IMPLEMENTATION DEFECT** — **none.** **SCOPE VIOLATION** — none: `84100bb0` touched 2 files (resolver +31/−6, its contract test +66), `workflow-state.php` untouched.

**UNRESOLVED QUESTION** — whether the architecture intends the handoff fact to be record-derived, mechanism-supplied, or not reported at all.

## 5 · Limitations

The `missingForActivation` list is emitted whenever a session is `CREATED`; I did **not** test whether a *partially* activated session (handoff recorded **and** START recorded, yet still `CREATED`) exists or is reachable — the mechanism should make that impossible, but I did not prove it. Concurrency, large estates and permission-denied records remain untested, as before. Fixtures were built in a scratchpad directory; **the real estate was never written to.**

## 6 · Verdict

> ## ⚠️ **VERIFIED WITH FINDINGS**

**The corrective increment does what it was granted to do — all six checks pass, and `V-2` is cured.** **`V-3` is confirmed, systematic, and correctly attributed away from the resolver:** it is an interface gap in the qualified mechanism, and the resolver's imprecision is the price of obeying Safeguard B. **No implementation change is recommended here, and none is authorized.**

---

**VERIFICATION COMPLETE · STOPPING**
**Nothing implemented, repaired, adopted, qualified or closed · `AST-016` untouched · `workflow-state.php` untouched · no record, test or registry modified · scratchpad fixtures only**

**Traceability:** `84100bb0` (2 files, +91/−6) · `identity` on `S1-verify-discovery-corrective` · R-34 check against `beb26177` · CASE-A/CASE-B fixtures and their resolver outputs · `fold` key set (no `handoffsTo`) · `workflow-state.php` START validator's internal `handoffsTo` use · MD5 before/after · `G-KOS-DISC-C12-VERIFY` · `G-KOS-DISC-V3-ASSESS` · prior verification `beb26177` (`V-1`, `V-2`)
