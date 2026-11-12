# Platform (tenancy) revision 10: change trace from IR-P-02-R5

| | |
|---|---|
| **What this is** | A packaging aid, **written by the author (Claude)**. It maps each IR-P-02-R5 finding to the place in revision 10 that addresses it. **It is not evidence that anything is resolved:** the reviewer judges the source |
| **Subject** | revision 10, SHA-256 `30ecb53eb9d2acd526ef55ce743cbbd320e89d1071ab691ce88c98c179f54aeb`; diff against the revision 9 snapshot (`4e131bfb…`) |
| **Revision 10 text** | the section headed "Revision 10" (R10-1 … R10-9), appended after revision 9; **no earlier revision text was changed** |

| R5 finding | Class (GPT-6) | Revision 10 location | Decision used | What changed (author's description) |
|---|---|---|---|---|
| R5-01 act kinds | [c] | R10-1 | **D-190** (§11.89) | **Draft** Governance addition note for `designate-platform-contacts` and `give-case-grant`. **Not ratified; not appended to the thin slice** |
| R5-02 PersonRef → Account | [UNKNOWN] | R10-9 X-2; register E-7 | — | Declared an external dependency (Identity, I-TDD-02); predicate fails closed until accepted |
| R5-03 GD-11 / GD-12, M-4 | [UNKNOWN] | R10-9 X-3; R10-1 (per-part `ruleRef` / `policyVersion` sentence) | D-190 (scope to be judged) | **The per-part persistence sentence is proposed inside the draft note. Whether D-190 covers it is open.** GD-11 / GD-12 remain external |
| R5-04 inbox | [a] | R10-3 | — | One transaction: event-id dedupe, `FOR UPDATE` (insert version 0 if absent), apply iff higher version and the copy is not terminal |
| R5-05 timer gap | [a] | R10-4 | D-189 | `m0` taken before the admission request; failover recomputes from the central clock |
| R5-06 live termination | [a] | R10-5 | — | Positive confirmation via session list; containment (role revoke, sessions terminated, network closed); grant `contained` |
| R5-07 report fields | [a] | R10-6 | D-188 | Per-operation record, count check, versioned re-emission |
| R5-08 registry-only failure | [a] | R10-7 | — | `registered` state; retry reuses or creates the single admission; abandonment window |
| R5-09 proofs | [b] | register section E | — | E-1 … E-8 |
| R5-10 central metadata | [UNKNOWN] | R10-2 | **D-191** (§11.90) | Field list per D-191 |
| R5-11 subject id / Identity | [TU] | R10-1; E-7, E-8 | — | proof obligations; blocked |
| R5-12 termination guarantees | [TU] | R10-5; E-5 | — | proof obligation |
| R5-13 earlier findings | [d] / [b] | registers A–D | — | carried forward |

**Author's notes for the reviewer (not findings):**
- **The "confirmation window" (R10-5) and the "abandonment window" (R10-7) are platform settings with no stated value.**
- **R10-4 recomputes after failover with `D − t_now`.** Its bracketing reading is described in prose only.
