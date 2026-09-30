# L0-REL-07 evidence pass — ES-006.1 + Plan Concept heading locate (reader SELF) — REPORT

| | |
|---|---|
| Release | L0-REL-07 ("yes"): (a) ES-006.1 in `engineering/governance/ES-006-Engineering-Knowledge-Governance.md` for EQ-3/EQ-4; (b) heading-only locate on `docs/implementation/Plan_Concept_Decision_Paper.md` |
| (a) hash | `349b7d5d5e2df4ffea052c06c35e2c2d5503c22ffd79654946ad671d2f0c42a0` ✔ |
| (a) unit (declared reading) | **no heading "6.1" exists** (headings: L1, L12 `## Hosted rules`, L50 `## Registered (pointers)`). "ES-006.1" resolves uniquely to the entry `**ES-006.1 — The Promotion Ladder**` at **L14**, bounded by the next sibling entry `**ES-006.2` at L22 → **read L14–21**. L24 and L45 merely mention ES-006.1 and were not read. **Voidable by L0** if the entry boundary is not accepted |
| Reader disclosure | the SELF reader has read historical ES-006 objects before (T-A, L0-DEC-31, T-A scope only). That prior reading is not used here |
| Files | `evidence.json` `7811cc98…` · `constraints.json` `c76c744b…` · cumulative `0de0f413…` / `f702f3d4…` |
| Formal basis | SECONDARY-REPRODUCED |

## What ES-006.1 states (L14–21, its own wording)
- **L14:** "ES-006.1 — The Promotion Ladder *(ARB, refined 2026-07-11)*".
- **L17:** Research → Pilot → Qualification → Engineering Standard → Stable Engineering Capability. **Forward only; no reverse transition stated.**
- **L20:** "nothing is promoted because it is a good idea; **everything is promoted because operational evidence demonstrated necessity** (the burden-of-proof rule, R-37, applied to promotion)". Also a maturity vocabulary.
- The mechanical span check finds **no** n ≥ 2 / "single occurrence" wording and **no** demotion / revocation / invalidation / supersession / reassessment term.

## Evidence (5 cells, 1 event)

| Cell | Proposition | Assessment | Why |
|---|---|---|---|
| E6-c1 | EP-02a (floor at every promotion event) | **AMBIGUOUS** (MEDIUM) | universal evidence-grounding of promotion, but **the timing of the check (t_a vs t_p) is not stated** |
| E6-c2 | EP-02b (bar met at every promotion event) | **AMBIGUOUS** (MEDIUM) | same; nor does it say the bar is re-met at the event |
| E6-c3/c4/c5 | EP-03a / EP-04a / EP-04b | SILENT (HIGH) | forward-only ladder; no post-promotion loss mechanism stated. **Absence ≠ persistence** |
| E6-e1 | event | OTHER | rule refined by the ARB on 2026-07-11 |

**Why not SUPPORTED.** SUPPORTED on EP-02a would eliminate MT1-P1, the only model with strict TOCTOU. SUPPORTED on EP-02b would make every primary model inconsistent. Either would rest on a sentence that does not state when the evidence condition is checked. The ambiguity is kept explicit, as the rules require.

## Engine (frozen)
- **This pass:** EP-02a and EP-02b AMBIGUOUS (open); 17 SILENT; **no constraint**; every model NOT-ELIMINATED; no flag.
- **Cumulative** (18 cells, 5 events): **15 SILENT · 1 OUT-OF-SCOPE (EP-01) · 3 AMBIGUOUS (EP-02a, EP-02b, EP-13c)**. No constraint; no flag.

## (b) Plan Concept, headings only (hash `9c6c61fadf502ce7587af2cfb6362b7d9b8f21c8b14df3b717febff0e038ad43` ✔, 48 lines)
- L1 `# Plan Concept Decision Paper — Is "Plan" One Domain Concept or Two?`
- L7 `## Answer`
- L11 `## The two concepts`
- L24 `## Why "two representations of one concept" fails`
- L32 `## Historical plans are a state, not a third concept`
- **L36 `## Architectural observation — promotion between bounded concerns (recorded, NOT ruled)`**: the only promotion-related heading. It is self-declared **non-ruling** (an observation)
- L40 `## Recommendation`

## What is now the decisive open question
- **EQ-2 timing:** is ES-006.1's evidence condition checked at the promotion event or only at the earlier decision?
- Resolving it would move EP-02a out of AMBIGUOUS. If SUPPORTED as event-time, **MT1-P1** would be the one inconsistent model (IG 0.811).
- **EQ-3/EQ-4 remain SILENT.** No released source has yet stated any post-promotion loss or persistence rule.
