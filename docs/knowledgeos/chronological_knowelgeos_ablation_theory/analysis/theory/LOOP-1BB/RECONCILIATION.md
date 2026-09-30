# 1bb: minimum execution-evidence specification. Reconciliation (specification only; nothing collected)

| | |
|---|---|
| Frozen | `92b02ddcc` |
| Review verdict | **PASS_WITH_LIMITATIONS** |

## Reconciled findings
1. **D1: a formal identifiability limit (substantive).** The observation "R-89 not adopted, but work ran under it" satisfies an **H-D falsifier** (force without adoption) **and** an **H-B falsifier** (execution under a norm not in force) **simultaneously**. More generally: **behavioural uptake cannot distinguish "the norm changed without an act" from "work violated an unchanged norm".**
   - **FORMAL CONSEQUENCE:** H-D and H-B become discriminable by execution evidence **only if the norm in force at the time of the act is observable independently of *both* the act records and the behaviour.** An example is a third-party state register, maintained by an actor other than the norm-maker and the executor, with its own system times.
   - This is the **precise missing observable**.
2. **Does the spec smuggle in H-D? Yes, partially (the check the human requested).** Entry into force is taken from the adopter's own record, which is the status-conferring act itself, and the norm state is defined through act records. That is H-D's framing. Other residue: actor-set git identities; refusal artefacts that are session-log self-statements.
3. **Missing fields:** R-72's gate in force at t_work; the link from the WP-4C-1 work artefact to R-89 (does it cite R-89 as authority?); the full closed-channel write history.
4. **Feasibility overclaims:** CI runs, push times and DA session logs are not shown to exist. **Candidates only.**
5. **Minimums:** none arbitrary (each 1 is a counter-instance to a universal clause). But the H-B census falsifier refutes only H-B within window W, and the universal-vs-tendency reading of H-B stays **UNKNOWN**.
6. **Untestable falsifiers:** P1-HB (no closed world for work); the closed-world capability register assumes a single channel, which is not sourced; P4-HD has no rule fixed in advance for what counts as adoption.

## Corrected acquisition ranking (reviewer, adopted)

| Rank | Acquisition | IG / effort |
|---|---|---|
| 0 | **Screen: does WP-4C-1 work exist, and when did it start (system time)?** | **HIGH**: it decides whether rank 1 can discriminate at all |
| 1 | P4 + P7 (R-89 / R-87 / WP-4C-1), with the D1 split and the D2 items (gate in force; work→R-89 link; write history) | HIGH if the screen is positive |
| 2 | P2: the contested R11 case (the full R-73..R-80 texts; entry-into-force records; the diff classified by a rule fixed in advance) | HIGH, one-sided; first if the screen is negative |
| 3 | P5 (R-94) | MEDIUM |
| 4 | P8 (R-91) | LOW |
| 5 | P1 (a norm change with no act) | LOW |

## Model impact
- **The deepest result of the H-D / H-B line** (INFERENCE + FORMAL CONSEQUENCE; same-family): **the two hypotheses are non-identifiable without an independent observable of the norm in force.**
- In this corpus, norms are observable **only through the acts that make them** (self-report) or **through behaviour**. Neither separates them.
- Any future evidence acquisition must include such an observable, or it cannot discriminate.
