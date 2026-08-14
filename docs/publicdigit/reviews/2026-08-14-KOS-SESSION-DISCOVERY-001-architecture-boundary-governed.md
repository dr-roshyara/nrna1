# KOS-SESSION-DISCOVERY-001 — Session-Assignment Resolution
# Governed Architecture Boundary (Review + Revalidation)

**Session 4 — `S4-architecture-discovery` · state ACTIVE (record seq 3, A-3-verified act, formal confirmation registered — `4b182135`/`2b125a7a`) · mutationOwner = S4 · grant `G-KOS-DISC-ARCH` (design-only) · 2026-08-14**

> ## PROPOSED — NOT HUMAN APPROVED
> This is the **governed** architecture boundary for the Session-Assignment-Resolution capability. It supersedes-by-reference the pre-activation draft at `cee1ee6b`, which is classified below as **prior/untrusted architectural input** and is neither rewritten nor inherited. No code, no `workflow-state.php` change, no hooks, no Session 5. Next gate: PO/ARB approval → implementation authorization → S3.

---

## 1 · Provenance audit of the prior input (`cee1ee6b`)

**Status of that document:** produced by this same terminal while the record showed `CREATED` (the OBS-0 incident) — therefore **prior/untrusted input**, per the PO's Option-② disposition and Session 2's §H registration note. This review re-derived its load-bearing claims from their sources rather than trusting the document:

| Claim re-verified | Source re-checked | Holds? |
|---|---|---|
| mechanism read surface = `fold`/`identity`/`authorized`, all requiring work-item id | `workflow-state.php:17-30` usage block | ✅ |
| `authorized` = exact-string scope equality (O-2) | `:375-391` | ✅ |
| **L-2**: `identity.authorizationLinkage` links the *last AUTHORIZED grant of the work item*, unscoped to the session | `:354-374` — and now **demonstrated live**: my own `identity` output this session links `G-KOS-DISC-ARCH` correctly only because it is the *sole* grant; with multiple grants the linkage is arbitrary | ✅ (upgraded: live demonstration) |
| **L-1**: transitions carry `seq`, no timestamps | all three records | ✅ |
| no roster capability ("which work items exist") | usage block; intake §C | ✅ |
| verdict/STOP-safe/ambiguity model | design reasoning, not evidence — re-adopted under governed judgment below | ✅ with amendments §3 |

**Honesty note (R-34):** same-author revalidation is not independent verification — S1 verifies later. What changed between the drafts is the state under which judgment is exercised, plus the new evidence (§2) the prior draft could not contain.

## 2 · New evidence incorporated

**E-A · O-4 (registered, `increment-1-governance-closure.md` §5):** verification work occurred read-only while `S1-verify` stayed `CREATED`; Governance declined retroactive transitions. With O-1 (ACTIVE ⇒ mutation owner), the mechanism **cannot express authorized read-only participation**. My own OBS-0 was a second specimen of the same shape.

**E-B · The §6 sharpening (PO, 2026-08-14):** `authorized(scope)` proves only that *an AUTHORIZED grant with the exact scope string exists*. Full authorization is a **six-fact conjunction** — correct session ∧ correct role ∧ ACTIVE ∧ mutation owner ∧ grant-holder ∧ act-covered-by-scope — which the mechanism cannot currently evaluate (grant↔session linkage does not exist as data: L-2).

**E-C · The provenance family (three specimens, one invariant):** (a) *false claim* of a registered act — the premature-test docblock; (b) *live act, unregistered*, work proceeded — OBS-0, overruled by Option-②; (c) *true report of an act that never reached the registrar* — the S4 START episode, resolved only when the act was performed **in the registrar's stream**. Rule established: **a human act becomes registrable only when the registrar receives the performative act itself; delivery channel is part of provenance.**

## 3 · The design, revalidated with amendments

Everything structural from the prior input is **re-adopted under governed judgment**: RESOLUTION-not-detection vocabulary · verdicts `RESOLVED / UNASSIGNED / AMBIGUOUS / UNRESOLVABLE`, the last three first-class and STOP-safe · `operable` ≡ (state == ACTIVE) and nothing more · **consumer-never-twin**: the resolver consumes `fold`/`identity`, never re-implements folding · CMP-004 ownership, sibling read-only script, qualified mechanism untouched · no environment-derived identity · verbatim grant surfacing · no best-effort identity · edge cases A–Q with the STOP-safe table · non-goals incl. hooks/wiring deferral.

**Amendments (the governed delta):**

**AM-1 · O-4 surfacing field.** The ResolutionReport gains one informational field on every candidate:
`"readOnlyParticipation": "NOT EXPRESSIBLE — the record cannot represent authorized read-only work (O-1/O-4); any such participation is governed by convention, not by this record"`.
The resolver **names the gap; it does not fill it**. Inventing a pseudo-state (e.g. `OBSERVING`) would be a mechanism-vocabulary change — recorded as **dependency D-6** (below), not designed.

**AM-2 · The six-fact authorization answer (E-B), decided as the commission required.** The O-4/§6 question — *surfaced information, dependency, or separate capability?* — resolves as **all three, partitioned**:
- **Surfaced:** the resolver reports each of the six facts **separately and verbatim**, with the sixth (act-covered-by-scope) and fifth (grant-holder) explicitly marked `UNKNOWN — not evaluable from the record` where the mechanism lacks the data.
- **Dependency:** grant↔session/role linkage as *data* = **D-2** (already recorded); read-only participation vocabulary = **D-6** (new).
- **Separate future capability:** *evaluating* the conjunction — an authorization-evaluation capability with its own work item, per the PO's explicit constraint ("do not assume Discovery should implement Authorization"). **The resolver never emits `authorized: yes`.** It emits facts and UNKNOWNs; judgment stays with the session's startup check and the human.

**AM-3 · Provenance attestation boundary (E-C) — new invariant INV-DISC-7.** *The resolver attests only what the record contains. It cannot attest that a human act occurred, only that a registration of one exists (`humanActRef` verbatim). Its report must never present prose claims — its own or anyone's — as record facts.* Report wording duty: `missingForActivation: ["recorded human START"]` — "recorded" is load-bearing; a live-but-unregistered act still resolves to *missing*, per Option-②.

**AM-4 · Q-F closed.** The prior draft's open question Q-F is answered by the Option-② disposition + the registrar-delivery rule (E-C). Removed from the open list; recorded here as settled rule context the implementation must not soften.

**AM-5 · L-2 upgraded from limitation to demonstrated defect-candidate.** This session's own `identity` call is the clean reproduction case (§1). Disposition remains the PO's (Q-D) — surfaced, not repaired; the resolver lists grants verbatim and never reproduces the `authorizationLinkage` inference.

## 4 · Invariants (consolidated, governed)

INV-DISC-1 read purity (zero writes, zero transitions — structurally: no `append`/`grant`/`init` code path) · INV-DISC-2 no environment identity (TTY/PID/host/process never inputs) · INV-DISC-3 resolution ≠ activation ≠ authorization ≠ START ≠ ownership (the read-side G-3; never a second path to ACTIVE) · INV-DISC-4 verbatim authority surfacing (no paraphrase, no lifecycle guessing — E-15 caveat attached) · INV-DISC-5 ambiguity honesty (AMBIGUOUS/UNASSIGNED/UNRESOLVABLE are success verdicts; silent selection is a contract violation; absence is never permission) · INV-DISC-6 record over prose, read side (the report outranks any prose/prompt identity claim; contradiction ⇒ stop and escalate) · **INV-DISC-7 attestation boundary (AM-3)**.

## 5 · Smallest implementation boundary (unchanged from prior input, re-adopted)

One new read-only resolver script (CMP-004's second implementation asset, sibling of `workflow-state.php`) · hermetic contract tests, edge cases A–Q + read-purity byte-identity + the AM-1/AM-2/AM-3 report fields · **one** registry-first asset entry in `.claude/platform/registry.yaml` · session-log/CONTEXT bookkeeping · **nothing else** — no existing script, hook, mechanism, record, or Election file.

**Dependencies requiring separate authorization (not designed):** D-1 transition timestamps · D-2 grant↔session/role linkage data (also cures L-2) · D-3 grant lifecycle exercise (E-15) · D-4 native `resolve` command alternative · D-5 SESSION_START wiring · **D-6 read-only-participation vocabulary (O-4)**.

## 6 · Questions for PO/ARB

| # | Question |
|---|---|
| Q-A | Approve this governed boundary as S3's implementation scope? |
| Q-B | Shape: separate resolver script (**recommended** — qualified mechanism untouched) vs native `resolve` command (= D-4, own authorization)? |
| Q-C | On implementation: ResolutionReport as step 0 of the D-4 startup convention (a registered convention amendment), or optional tooling until operational evidence? |
| Q-D | L-2 disposition — now with a live reproduction: accept as known limitation, or open a defect-candidate work item? |
| Q-E | Dependency priority: D-1/D-3/**D-6** before or after the resolver? (It works without them; it is more honest with them.) |
| ~~Q-F~~ | **CLOSED** — Option-② + registrar-delivery rule (AM-4). |

---

**Traceability:** prior input `cee1ee6b` (audited §1, superseded-by-reference) · START act seq 3 + §H registration (`4b182135`) + formal confirmation (`2b125a7a`) · intake §A–§G (`15f4f484`) · grant `G-KOS-DISC-ARCH` · O-4 (`increment-1-governance-closure.md` §5; session log 2026-08-14) · O-1/O-2/E-15 · the PO's §6 sharpening + "do not assume Discovery should implement Authorization" + "resolve, don't detect" · Option-② disposition + registrar-delivery rule (E-C) · `workflow-state.php:17-30,354-391` · this session's own `identity`/`fold` outputs (live L-2 demonstration; ACTIVE verification) · A-3 · G-3 · R6/R8 · R-34 · ES-001.1 · ES-005.4.

---

> # PROPOSED — NOT HUMAN APPROVED
