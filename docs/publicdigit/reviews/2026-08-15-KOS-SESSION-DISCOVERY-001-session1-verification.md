# KOS-SESSION-DISCOVERY-001 — **Session 1 independent verification / falsification**

**Date:** 2026-08-15 · **Verifying:** `73d056c8` (Session 3) · **Assignment:** `S1-verification-discovery` · **Grant:** `G-KOS-DISC-VERIFY`
**Session 3's GREEN report was treated as an untrusted claim throughout. I attempted to break the implementation, not to confirm it.**

---

## 1 · Machine authorization evidence

`identity` → role `verification` · **state `ACTIVE`** · predecessor `S3-implementation-discovery` · linkage `G-KOS-DISC-VERIFY`. Fold → `S4-architecture-discovery: COMPLETED` · `S3-implementation-discovery: HANDED_OFF` · **owner: `S1-verification-discovery`**.

⚠️ **Read under the `O-4` correction:** I did **not** treat an `authorized: true` as proof of my authority — that query checks only scope-string existence. The condition that actually holds is `identity` returning `ACTIVE` with the `-VERIFY` linkage.

## 2 · 🔑 Safeguard B — the primary falsification: **could not break it**

**Attack 1 — remove the mechanism.** `KOS_MECHANISM_PATH=/nonexistent/nope.php`:

```
verdict: UNRESOLVABLE   candidates: 0   exit 0
reason: "qualified workflow mechanism unavailable … this resolver cannot
         determine workflow state independently (AMENDMENT 2)"
```

**It did not fall back to reading the records it can plainly see.** Source confirms why: `$fold = $mechanismAvailable ? askMechanism(...) : null;` then `if ($fold === null) { …continue; }` — **there is no interpretation branch to fall back to.**

**Attack 2 — substitute a *lying* mechanism.** A stub returning well-formed JSON asserting `EVIL-session` / `executionContext: "fabricated"` — a session existing in **no** record:

```
candidate: { "session": "EVIL-session", "state": "ACTIVE",
             "executionContext": "fabricated", "mutationOwner": "EVIL-session" }
```

> **The resolver relayed the fabrication verbatim and never cross-checked the records.** **This is the decisive positive evidence for Safeguard B:** a resolver holding a second interpreter would have contradicted the stub. **It cannot disagree with AST-015 because it has no independent opinion to disagree with.**

**Attack 3 — record enumeration.** The one place it touches the filesystem is `glob($recordDir.'/*.json')` → `basename($path,'.json')`. **Filenames only; no record content is ever decoded by the resolver** — the only `json_decode` consumes the *mechanism's stdout*. **Enumeration is not interpretation.**

**Source inspection (§5):** no fold loop, no transition state machine, no schema assumptions beyond the mechanism's response shape, **no fallback interpreter**.

## 3 · Safeguard A — exit contract holds under all four verdicts

| Invocation | Verdict | Exit |
|---|---|---|
| `--json` | `AMBIGUOUS` | **0** |
| `--json --session=S1-verification-discovery` | `RESOLVED` | **0** |
| `--json --session=NOBODY-AT-ALL` | `UNASSIGNED` | **0** |
| `--json --work-item=NOSUCH` | `UNRESOLVABLE` | **0** |
| `--bogus-flag` | usage error | **64** |

> ✅ **`UNASSIGNED` and `AMBIGUOUS` cannot be swallowed by ordinary shell failure handling — both exit 0.** Amendment 1 verified against behaviour, not against T-12.

**`AMBIGUOUS` genuinely lists and chooses nothing:** the lying-stub run produced **3 candidates**, all surfaced, **none selected**, `operable: false`.

## 4 · Read purity — byte-identical across every path

MD5 fingerprints of all workflow records taken before the campaign and after **eight** invocations spanning RESOLVED / UNASSIGNED / AMBIGUOUS / UNRESOLVABLE / absent-mechanism / lying-mechanism: **byte-identical**. No registry, session-log or `CONTEXT.md` mutation. *(The only dirty file is `.claude/settings.local.json`, mutated by the harness recording my own denied commands — not by the resolver.)*

## 5 · Authority separation — verified, and unusually well stated

`RESOLVED` + `operable: true` is accompanied by:

* `caveat`: *"Resolution is not activation. This report creates no authority, no ownership, no state change. G-3 gates are untouched."*
* `interpretationAuthority`: *"AST-015 workflow-state.php — this resolver performs no independent …"*
* `grantLifecycleCaveat`: grant status reported **as recorded**.

**No START, no state change, no grant creation, no ownership creation.** `operable` is derived solely from AST-015's reported `state === 'ACTIVE'` and is explicitly *not* authorization.

## 6 · The six AuthorizationFacts — refuses to manufacture certainty

```
correct session          -> S1-verification-discovery
correct role             -> verification
state is ACTIVE          -> yes
holds mutation ownership -> yes
is the grant holder      -> UNKNOWN — grants carry no session/role linkage
scope covers the act     -> UNKNOWN — scope exists only as a string
```

> 🔑 **The two `UNKNOWN`s are precisely the two gaps `O-4` measured** (`authorized` ignores role/state/ownership and matches scope by exact string). **The resolver reports them as unknown instead of inventing a decision — which is the correct behaviour and, notably, makes a previously invisible platform gap visible at the point of use.** `readOnlyParticipation` likewise reports *"NOT EXPRESSIBLE by the current record"* rather than guessing — the `O-1` gap, surfaced honestly.

## 7 · State semantics — all approved states present

`ACTIVE` operable · `CREATED` not operable, naming **both** missing G-3 facts · `HANDED_OFF` passed to successor · `STOPPED` sticky, continuation-only, *"this resolver states that requirement; it cannot perform it"* · `COMPLETED`/`CANCELLED`/`FAILED` terminal, *"a new need is a NEW assignment (R8)"* · unrecognised state → not operable. **Zero candidates → `UNASSIGNED`; multiple → `AMBIGUOUS`.**

## 8 · Identity safety — no inference

`getmypid` · `posix_*` · `gethostname` · `php_uname` · `$_SERVER` · `getcwd` → **0 occurrences.** Exactly **one** `getenv`, for `KOS_MECHANISM_PATH`. **Identity comes from the record and the explicit filters, never from the process.**

## 9 · Boundary / scope — clean

`73d056c8` = **3 files, +706/−0**: the resolver, its contract test, a registry row. **`workflow-state.php` untouched — last modified by `c2f5a831`, the qualification commit.** None of the excluded items appear: no authorization engine, activation, `SESSION_START`/inject-context wiring, hooks, locks, leases, role journals, D-1…D-6, Increment-2, Election code, registry-schema change, new bounded context. **`AST-016` remains `adoption: planned`.**

**Suites re-run independently:** resolver **15/142 OK** · **AST-015 regression 12/142 OK** — the qualified mechanism is undisturbed.

## 10 · Findings, classified

**VERIFIED FACT** — Safeguard B holds under two independent attacks · Safeguard A holds across all four verdicts · read purity byte-verified · authority separation explicit · identity never inferred · boundary clean · AST-015 unmodified and still green · `UNKNOWN` used instead of manufactured certainty.

**OBSERVATION `V-1` — `--dir` pointing at a non-existent directory exits 0 with `UNRESOLVABLE`.** Defensible under Amendment 1 (a report *was* produced, and "no readable records" is a legitimate answer) — but it means a **typo'd `--dir` is indistinguishable from an empty estate** to a caller checking only exit status. **Not a defect against the approved contract; a usability edge the operator contract should state.**

**OBSERVATION `V-2` — `KOS_MECHANISM_PATH` is a real runtime surface, not merely a test seam.** It is read by `getenv` in the production path with the sibling script only as a *default*, and it is honoured with **no validation** — my lying stub was accepted unconditionally. It is absent from the registry entry and the operator contract, so it is *undocumented* rather than *inert*. **Consequence: anyone able to set the environment can substitute the interpretation authority.** Since the resolver is read-only and creates no authority, the blast radius is **misinformation, not privilege escalation**. **Classified OBSERVATION, not DEFECT** — the approved architecture permits the seam; what is unstated is that it is env-settable at runtime. **Architecture's call, not mine.**

**DEFECT** — **none found.** **SCOPE VIOLATION** — none. **PRE-EXISTING FAILURE** — none encountered.

**UNRESOLVED QUESTION** — whether `V-2` warrants a validation or a contract note is an architecture decision I deliberately do not take.

## 11 · Limitations — what I did NOT test

Concurrent invocation · very large record estates · non-UTF8/permission-denied records *(I could not create a corrupt record without mutating the estate — `§6`'s no-mutation rule and the corrupt-record path are in tension, so that path rests on T-x evidence I did not reproduce)* · the human-rendered (non-`--json`) output beyond exit codes · behaviour when AST-015 exists but returns a non-zero exit.

## 12 · Verdict

> ## ⚠️ **VERIFIED WITH FINDINGS**

**Both human-approved safeguards hold under adversarial attempt.** The architectural invariant is not merely asserted — it is *demonstrated*: given a mechanism that lies, the resolver relays the lie; given no mechanism, it refuses. **Only a component with no second interpreter behaves that way.**

**Two OBSERVATIONS (`V-1`, `V-2`), no defect, no scope violation.** Neither, on my evidence, blocks qualification — **but the qualification decision is the PO/ARB's, and `V-2` is an architecture question I have deliberately left open.**

---

**VERIFICATION COMPLETE · STOPPING**
**Nothing implemented, repaired, adopted or closed · `AST-016` still `planned` · `workflow-state.php` untouched · no test or record modified · mutation ownership held and unused**

**Traceability:** `73d056c8` (3 files, +706) · `session-resolve.php:85,98,112,125,150-158,205-245` · absent-mechanism run · lying-stub run (`EVIL-session`/`fabricated` relayed) · four-verdict exit matrix · MD5 before/after · six AuthorizationFacts · `identity`/`fold` on `KOS-SESSION-DISCOVERY-001` · resolver 15/142 · AST-015 12/142 · `AST-016` registry row
