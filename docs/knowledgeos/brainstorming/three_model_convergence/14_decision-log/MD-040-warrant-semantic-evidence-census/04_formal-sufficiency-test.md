# Formal Sufficiency Test

Applied to the combined evidence of both threads. Discipline: `named` → `represented` →
`constrained` → `computable` → `formally testable`. No source is upgraded merely for using formal
notation (both `M0036` and `06` use boxed/typed notation for things that remain undefined).

| Component | Status |
|---|---|
| 1. Domain (what inputs `Warrant` takes) | **CONSTRAINED** — `06`: `{Claim,Evidence}`\|`{Hypothesis,Evidence}`; `M0036`: `Assess(E_t,S_t,Q_t,C_t)`/`Validate(A,E,S)` — two *different*, non-identical candidate domains from the two threads (see `05`) |
| 2. Codomain/output | **CONSTRAINED** — `Verdict` (Thread 1, admitted); `A_t`/status component (Thread 2) — again not shown to be the same object |
| 3. Predicate/relation (when `Warrant` holds) | **NOT SPECIFIED**, either thread |
| 4. Derivation rule | **NOT SPECIFIED** for the value itself — only the *carrier-typing* rule (which inputs are required) is given, not how the assessment is computed from them |
| 5. Necessary conditions | **PARTIALLY CONSTRAINED, then explicitly deferred** — `13`/`FINAL` state the operation's own inputs are necessary (`Evidence`+`Claim`/`Hypothesis`) but the *threshold/standard* is explicitly named as a **policy parameter, deliberately kept outside the kernel** (`13`: *"the standard is policy"*; `FINAL`: *"the standard... is governance"*) |
| 6. Sufficient conditions | **NOT SPECIFIED**, and per the same policy-boundary finding, may be **deliberately out of the kernel's own scope to specify** — this is the single most consequential finding of this census |
| 7. Invariants | **NOT SPECIFIED** for `Warrant` itself; `I9` (already known, MD-037/038) concerns `Defeater`, a different object |
| 8. State transition | **NOT SPECIFIED** — no source states how `Warrant`/`A_t` evolves over `t` beyond the generic `state-mutation` atom (Thread 1) or the generic `𝔈_t → 𝔈_{t+1}` update (Thread 2, itself unspecified: *"a candidate transition is..."*, `[PROP]`) |
| 9. Failure/error condition | **NOT SPECIFIED**, either thread |
| 10. Termination condition | **NOT SPECIFIED**, either thread |
| 11. Operational decision rule | **NOT SPECIFIED — and explicitly assigned elsewhere.** `19`'s own audit-response table states the warrant-semantics question is *"level-2/level-4 work"* (state type / adequacy), not level-7 (kernel) — i.e. the kernel-reduction lane's own text explicitly places the operational rule outside its own research scope, not merely omits it |
| 12. Equivalence/preservation criterion | **NOT SPECIFIED** — this is exactly the `P`-dimension gap already found in MD-039 |

## The single most important qualitative finding, stated precisely

**The absence of a `Warrant` definition may not be an oversight — it may be, in part, a deliberate
architectural boundary.** `13-ddd-analysis.md` and `FINAL-kernel-reduction-report.md`
(both unadmitted, both independently making the same claim within Thread 1) state that `Validate`'s
own *operation* is a kernel-internal domain responsibility, while its *standard* (the warrant
threshold) is explicitly classified as **governance/policy**, deliberately kept outside the kernel:
*"`Validate`, `Determine`, `Qualify` are policy-parameterized domain operations — the operation is
domain, the standard... is governance. Kept in the kernel; their parameters kept outside it"*
(`FINAL` §11). If this boundary is taken at face value, searching the kernel-reduction lane's own
evidence for a formal warrant-threshold definition may be searching for something the lane's own
architecture never intended to contain — a possibility this study surfaces but does not adjudicate
further, per its own non-goal boundary (§9 of the authorization).
