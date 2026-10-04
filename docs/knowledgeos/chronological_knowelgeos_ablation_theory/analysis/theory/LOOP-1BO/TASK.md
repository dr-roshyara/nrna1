# Task (frozen): R4 r3, adding reading robustness (human act F-LOG-0232)

Use ONLY:
- `FORMAL-THEORY-R4.md`: r2, reviewed and finite-verified;
- `V1A-LEMMA.md`: r1, reviewed;
- `V1-RECONCILIATION.md`: the V1a/V1b results;
- `R3-FROZEN.md`: the frozen R3 record summary.

No commands, no web.

Produce R4 **r3** with **only** these changes, each marked `[r3]`:

1. **New §9, "Reading robustness".** It contains:
   - Lemma M (statement, with its proof or an exact reference to V1A-LEMMA);
   - the reading table: R-a … R-k, each with its HYP licence in one line, M / not-M, and the status of (i), (ii) and (iii);
   - the **V1b finite results** per reading, with the scope caveat: U, one target, Claude-family, predictions not independent;
   - the **weak form**, stated as a labelled proposition with its exact scope: records-only O does not separate the hypotheses under every tested reading, except the (ii)-O contrast under R-j;
   - the **R-d dependency on Cap** being unobserved;
   - R-a marked untested.
2. **Scope qualifiers** on Prop 4.1, Lem 4.2 and Cor 4.3: one sentence each, "holds for the Def 3.1 readings and every reading with property M (§9); fails generically under …". **No proof changes.**
3. **§6 ledger:** add the §9 statements with their labels.
4. **§8 compliance:** remove or replace the citations of the quarantined F09, F10 and F12 (see R3-FROZEN.md).
5. **The header status line:** "reviewed draft r3; R5 finite-verified (r2 content); robustness per §9".

**Nothing else changes.** Statement ids stay stable.

Write **FORMAL-THEORY-R4.md** (r3) and **CHANGELOG-r3.md**.
