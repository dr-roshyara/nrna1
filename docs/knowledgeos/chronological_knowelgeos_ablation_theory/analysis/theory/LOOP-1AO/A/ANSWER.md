# Guard classification: analytic vs synthetic

Sources: `SCHEMA.md`, `EVENTS.json`, `CODING-LINES.txt` only. Full per-item detail is in `ANSWER.json`.

Labels: **SOURCE FACT** (stated in the files) · **INFERENCE** (my reading) · **FORMAL CONSEQUENCE** (follows mechanically from SCHEMA rules R1–R5) · **UNKNOWN**.

## Summary

| Operation / var | Class | Decisive text | Premise (ANALYTIC) / rule (SYNTHETIC) | Contrast / premise status |
|---|---|---|---|---|
| ADOPT / a | SYNTHETIC | "`a` actor/authority"; Chief "(declined)" REFUSED vs "by the DA (R-86)" PERFORMED | The DA, not the Chief, adopts PREPARED rulings (a contingent allocation) | Strict under R2 (k, c by identity), 1 cluster-pair, so WEAK |
| ADOPT / c | ANALYTIC | "`c` role conformance"; R-91 c="collapsed" REFUSED | Roles actually conformed or collapsed before the act | All 3 events UNCLEAR; 'conformant' is unsourced |
| AUTHORIZE-IMPL / a | SYNTHETIC | R-70 ARB PERFORMED vs R-89 "(Chief, PREPARED)" NOT-IN-FORCE | Only an ARB ruling takes effect; the Chief alone only prepares | Differs only in a, but the outcome is NOT-IN-FORCE (not REFUSED); WEAK at most |
| OPEN-WORK / k | SYNTHETIC | "by a recording note (R-60)" REFUSED vs "by a ruling (R-60 refiled)" PERFORMED | Form rule: only a ruling opens work | Strict, but within one cluster, so WEAK; the refiling was caused by the rule |
| REGISTER / k | UNKNOWN | "REGISTER constitutional decision (rule)" PERFORMED | Register scope: definitional or policy? Not defined | No genuine contrast: the PERFORMED side is the rule itself |
| START / s | ANALYTIC | s="authorized"/"not-authorized" (the TASK's own analytic example) | An authorizing act existed or was absent before the START | 6 INDEPENDENT, 5 UNCLEAR, 0 shown OUTCOME-DERIVED (per event in JSON) |
| START / t | ANALYTIC (relational) | R-47 source: both s="auth-full(7A)", t=7A PERFORMED / t=7B REFUSED | Target ∈ scope of the existing authorization | Scope INDEPENDENT; 'no other 7B authorization' UNCLEAR |
| RAISE / e | SYNTHETIC | R-36: e="2+" PERFORMED, e="1" REFUSED | Promotion threshold ≥ 2 contexts | 12 strict pairs, 1 cluster, so WEAK; R-36 lines missing from CODING-LINES |
| RAISE / t | UNKNOWN | L493-B t=methodology REFUSED vs R-41 t=ES-004.3 PERFORMED | No rule stated | No t-only contrast; confounded by a, k, route |
| ASSIGN-ID / h | SYNTHETIC | "`h` history summary"; h="unused" PERFORMED vs h="retired" REFUSED | No-reuse rule for retired numbers | Strict, 1 cluster-pair, so WEAK |
| SUPERSEDE / k | UNKNOWN | refusals coded e="insufficient"/"0"; PERFORMED has r="ADR-ACCEPTANCE", a=U, e=U | No kind rule stated; e appears to be the operative reason | POSSIBLE at best |

**Tally:** ANALYTIC 3 (ADOPT/c, START/s, START/t) · SYNTHETIC 5 (ADOPT/a, AUTHORIZE-IMPL/a, OPEN-WORK/k, RAISE/e, ASSIGN-ID/h) · UNKNOWN 3 (REGISTER/k, RAISE/t, SUPERSEDE/k).

## Findings by label

### SOURCE FACT
- SCHEMA defines fields by name only ("`c` role conformance", "`h` history summary", "`a` actor/authority", "`s` state before", "`e` evidence condition", "`k` object kind"). No field value (e.g. "collapsed", "retired", "DECISION-AUTHORITY", "recording-note") is defined anywhere.
- CODING-LINES.txt holds Python `E(...)` calls. Apart from the `event_id` label, they contain **no quoted source basis**. So each coded value's "basis" is only its label.
- **The coding lines and EVENTS.json disagree:**
  - ADOPT R-81..85 (×2): source `k="same:R-81..85"`, `c="same:R-81..85"`; EVENTS `k="ruling"`, `c="conformant"`.
  - START WP-4B 08-03: source `s="auth-full"`; EVENTS `s="UNK"`.
  - START 7A: source `s="auth-full(7A)"`; EVENTS `s="authorized"`.
  - START 7B after AUTHORIZE(7A): source `s="auth-full(7A)"`; EVENTS `s="not-authorized"`.
  - RAISE L493-B: source `a="PO/ARB"`; EVENTS `a="UNK"`.
  - SUPERSEDE PB-006 (R-94, CHOICE) is in the source but not in EVENTS.
  - The seven R-36 RAISE items are in EVENTS but have **no coding line** (semobs.py lines 25, 35 and 36 are absent).
- "REGISTER constitutional decision (rule)" is labelled as a rule, not as an act.
- AUTHORIZE-IMPL R-89's outcome is `NOT-IN-FORCE`, while the guard definition in TASK uses PERFORMED vs REFUSED.
- The ADOPT refusal by the Chief is labelled "(declined)" and coded ground RULE, speech_act "performed".

### INFERENCE
- **ANALYTIC items** are those whose field or value is normative in its meaning. "Conformance" is conformance to a role norm. "authorized" / "not-authorized" is permission for this act. Scope match follows from an authorization indexed to a target. Testing them by prediction can only uncover miscoding. Their empirical content is whether the premise was established independently.
- **SYNTHETIC items** use descriptive fields or values (role identity, instrument kind, context count, history), where the link to the outcome is an allocation, form, threshold or no-reuse rule that could have been different.
- **START/s** premise status: most refusals and grants are anchored to a separately named prior act or state ("after AUTHORIZE(7A)", "execution not issued", "Batch 7 frozen/released"), so INDEPENDENT. There is a *reverse* risk, though: in single register rows (R-58, R-65), the PERFORMED/REFUSED outcome may have been inferred *from* the authorization status, with no observed start.
- **ADOPT/c**: the only value supporting a c-contrast on the performed side ('conformant') appears only in EVENTS.json. The most plausible origin is the PERFORMED outcome, which would make it outcome-derived. This cannot be shown from the files, so it is marked UNCLEAR.
- **SUPERSEDE/k**: the coder's own fields put the refusal reason in e, not k. The k guard may be misattributed.

### FORMAL CONSEQUENCE (R2–R5)
- ADOPT/a: under R2 (k, c shared by identity) the Chief/DA pair differs only in a, so it is 1 strict witness and 1 cluster-pair: **WEAK**. Under EVENTS.json's filled values it is also strict, but only because values were filled without a source, against "Nothing is filled in silently".
- OPEN-WORK/k, RAISE/e, REGISTER/k: all their strict contrasts are within one cluster (R-60, R-36, S0804-L576), so under R4 each counts once and gives **WEAK**.
- ASSIGN-ID/h: 1 strict witness across two clusters: **WEAK**.
- START/t: in the *source* coding, R-47 is a strict witness for t. In EVENTS.json it is not, because s was recoded. The same historical contrast is credited to t or to s depending on an undocumented recoding.
- RAISE/t, SUPERSEDE/k: no strict witness, POSSIBLE at most: **NOT DEMONSTRATED (possible)**.
- AUTHORIZE-IMPL/a: WEAK if NOT-IN-FORCE counts as "opposite" under R3, otherwise NOT DEMONSTRATED. R3 does not settle this.
- R-94 is CHOICE-grounded and excluded from witnesses by R3.

### UNKNOWN
- The meanings of "collapsed", "retired", "recording-note", "DECISION-AUTHORITY", "permission-only", and the register's scope.
- Whether the R-36 e values were coded independently of the matrix's promote / not-promote column (no coding lines exist).
- Whether START 7C at R-58 and similar refusals were attempted acts or were inferred from the lack of authorization.

## Ambiguities
1. **Name vs definition.** Value names like "DECISION-AUTHORITY" and "retired" *suggest* a built-in permission or prohibition, but nothing defines them. I classified by field type (descriptive vs normative). A glossary could flip ADOPT/a and ASSIGN-ID/h to ANALYTIC.
2. **"collapsed"** may be a factual description (the same person held both roles) or a normative one (the role norm was violated). This decides whether ADOPT/c is ANALYTIC or SYNTHETIC.
3. **NOT-IN-FORCE vs REFUSED** (AUTHORIZE-IMPL R-89): TASK's binary outcome and R3's wording do not cover it.
4. **"declined"** (Chief, ADOPT R-81..85): if this is self-restraint (CHOICE), the ADOPT/a witness disappears under R3.
5. **Indexical t = "own-slice"** (AUTHORIZE-IMPL): nominally equal, possibly different targets.
6. **"(rule)" pseudo-event** (REGISTER): an event built from the rule cannot test the rule, and it breaks "one historical act".
7. **The generic event "ASSIGN a never-used number"** (cluster "register-numbering") may also be a practice rather than an act.
8. **Silent fills and recodings in EVENTS.json** (k/c for R-81..85, s for 7A/7B/WP-4B, a for L493-B) move witnesses between variables, most visibly t → s at R-47.
9. **The START/t guard** is not a guard on a bare value: the same target receives both outcomes. It is meaningful only as a scope relation with s, which I classified ANALYTIC; SYNTHETIC (a contingent scoping practice) is the main alternative.
10. **Route r** is compared for every operation, but the RAISE pair L493-B / R-41 and the SUPERSEDE pairs differ in r. That puts part of the guard into the route rather than into e or k.
