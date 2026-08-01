# WP-8 — Definition Finding

**Date:** 2026-08-02 · **Prepared by:** Principal Architect / Recording Architect
**Commission:** prepare a WP-8 Definition Package.
**Repository Integrity Gate:** ✅ PASSED. **No implementation · no architecture redesigned · no methodology change · WP-7 not reopened.**

---

> # FINDING — **WP-8 is already defined in canon, and its prerequisites are not met.**
>
> **A Definition Package is not required. WP-8 is specified in `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md`** — the same roadmap WP-7 was executed against.
>
> **And WP-8 cannot be authorized yet: it depends on WP-4, which is open.**

---

## 1. Correcting my own repeated claim

**I stated several times that "no authoritative definition of WP-8 was found in the sources examined."** That was accurate about the sources I examined — CONTEXT, the backlog, the WP-7 plan — **and I did not search the roadmap those artifacts cite.**

**The WP-7 plan references "roadmap §WP-7" throughout. The same roadmap has a §WP-8.** **The citation was in front of me the whole time.**

**Eighth occurrence of the same pattern this programme has recorded: the answer was already on the record, and the failure was mine — searching the artifacts I knew rather than the one they pointed at.**

## 2. WP-8 as canon defines it

> **WP-8 — End-to-end operational validation: the full loop with its head over the real path (raise → route → APM → authority decision → issue → correct → resolve; and the failure-declared path) · triple qualification of every new element · CI green on the merge-gate.**
> **Traces to:** EPIC-003 R-1 closure · the house qualification rule. **Size: M.**

**Keystone tests, already specified:** *"IT-style full-loop suites (upheld · dismissed · failure-declared · expired), **ONE CorrelationId per conversation asserted end-to-end**."*

**Rollout position, already specified:** *"Full-loop production activation is **gated on WP-8's acceptance**."*

**Phase Definition of Done, already specified:** all eight WPs accepted · WP-8's end-to-end proof green in CI · **zero frozen-invariant tests modified** · the two flagged externals (challenge-filing entry point; Governance authority-contract design) **recorded as the next tracks' inputs, not silently absorbed.**

### Mapped to the Definition Package's fields

| Field | Answer — **from canon, not composed** |
|---|---|
| **Business capability** | the correction loop proven end to end over the real path, including the failure-declared branch |
| **Owning bounded context** | **none newly** — WP-8 validates the loop across Contestation · Adjudication · Election; it is **validation, not construction** |
| **Strategic boundaries** | unchanged by definition — *"zero frozen-invariant tests modified"* is a stated DoD condition |
| **Tactical model** | **consume only.** WP-8 introduces no element; it qualifies the elements the earlier WPs built |
| **Acceptance criteria** | the four full-loop suites green · one CorrelationId per conversation asserted end-to-end · triple qualification of every new element |
| **Verification strategy** | IT-style suites · CI green on the merge gate |

**Every field is answered. Nothing needed inventing — which is why no Definition Package should be written.**

## 3. ⛔ Prerequisites are not met

**The roadmap's dependency graph:** `WP-2 → WP-4 → WP-8`, with `WP-5 → WP-8`. Its implementation order ends *"WP-7 → WP-8, last, as the operational proof"*, under the standing rule *"no slice starts before its predecessor's acceptance."*

| Prerequisite | Status |
|---|---|
| WP-1 · WP-2 | ✅ accepted |
| **WP-3A** | ⚠️ **GREEN, acceptance pending** |
| **WP-3B** | ⚠️ **deferred** — needs a routing application service |
| **WP-4 — APM wiring** | ⛔ **OPEN.** Not started; its RED is the recorded next action |
| WP-5 — raise path | recorded closed |
| WP-6 · WP-7 | ✅ accepted |

> **WP-8 validates a loop whose wiring does not yet exist.** **WP-4 supplies the inbox handler, the issuance request path, the failure-declared event and the authority-decision intake port — every one of which WP-8's suites must exercise.**
>
> **Authorizing WP-8 now would authorize validating something unbuilt.**

## 4. Recommendation

**No Definition Package.** **Canon defines WP-8; writing a second definition would create a competing home for it** — the defect this programme removed repeatedly.

**The next governance activity is not WP-8.** On the roadmap's own ordering it is **WP-4**, with **WP-3A acceptance** and **WP-3B's deferral** to resolve first.

**Unchanged and available now:** **WP-7B-R1** may proceed as an independent refinement under R-60 — it depends on none of the above.

**If the ARB nonetheless wants WP-8 opened**, the act needed is **not a definition** but a **dependency ruling** — whether WP-8 may be authorized ahead of WP-4, against the standing *no slice starts before its predecessor's acceptance* rule. **That is a governance decision and is not recommended here.**

---

**Traceability:** `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` §WP-8 · §dependency graph · §implementation order · §keystone tests · §rollout · §Phase DoD · **R-66** (WP-7 closed) · **R-60** (WP-7B-R1) · `.claude/CONTEXT.md` (WP status). **No Definition Package written · no capability invented · no work authorized.**
