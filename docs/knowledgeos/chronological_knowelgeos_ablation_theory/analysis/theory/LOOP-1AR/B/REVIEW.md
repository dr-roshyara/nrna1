# Independent review of ANSWER.json / ANSWER.md

Inputs read: REVIEW-TASK.md, TASK.md, WITNESSES.md, EVENTS.json, CODING-LINES.txt, SOURCES.md, ANSWER.json, ANSWER.md. Nothing else was used.

**Bottom line:**
- I agree with 35 of A's 36 event classes. I would reclassify one: R-89 moves from ACT-REFUSED to ACT-PERFORMED.
- A's conclusions mostly hold: **a** keeps strict support, **k/s/h** lose all of it, **t** never had any.
- A overstates **e**. Its 12 pairs are one cluster-level pair resting on one register row.
- A omits the coarse **c** pair that WITNESSES.md records.
- The collapse of **s** is not just caused by the attested-act filter. The START s-witnesses are also circular by construction.

---

## 1. Evidence scope and quotes

**Evidence scope.**
- A used only EVENTS.json, CODING-LINES.txt and SOURCES.md, and did not read WITNESSES.md.
- TASK.md does contradict itself here: it says "Use ONLY" those three files, and it also says "list the event pairs named … in WITNESSES.md". A disclosed the conflict and its choice, which is defensible.
- The consequence is that A's witness list is **not** the list the task asked for:
  - For **e**, A lists 12 item-level pairs; WITNESSES.md has one cluster-level pair.
  - For **s**, A has 3 extra pairs.
  - The coarse **c** pair is missing.
- §3 recomputes the verdicts on the WITNESSES.md list. *(evidence scope)*

**Quotes.** I checked all 33 non-empty quotes against SOURCES.md. Every one is a verbatim substring after emphasis markers are removed, and every one sits at its stated anchor. Minor exceptions:
- **git-6a67da5d7:** A joins the subject line and the body with a period (`…its redrive. Four keystones…`). The subject line has no period, so the splice is not strictly verbatim. *(wording)*
- **Backticks:** A also strips backticks (R-36, R-70), although its notes say only `**`/`*` were removed. No meaning changes. *(wording)*
- **R-56:** the first half of A's quote ("Execution of 7B is a SEPARATE act and has NOT been issued") comes from a passage marked *"Recording note, not part of this ruling"*. By the register's own ruling/note distinction (R-60), that text does not carry the ruling's content. The class is unaffected, because R-56's outcome column says "**execution still NOT authorized**". That column is the better anchor. *(source interpretation; no class change)*

## 2. Event classes

My classes match A's for 35 of 36 events. There is **one class disagreement**:

| Event | A | Reviewer | Why | Type |
|---|---|---|---|---|
| AUTHORIZE-IMPL R-89 (Chief, PREPARED) | ACT-REFUSED | **ACT-PERFORMED** | See below | coding |

Why R-89 changes:
- Criterion (b) requires a *decision* that refuses or holds an identified proposal. No such decision on R-89 is recorded.
- R-89 says it "**awaits** the Decision Authority". Awaiting a decision is not being held by one.
- The only thing keeping R-89 out of force is R-86's standing rule ("Any future ruling issued by the ARB Chief remains PREPARED"). That is a general rule, not an act directed at R-89.
- By criterion (a), R-89 is a ruling that performs an authorization ("WP-4C-1 … is AUTHORIZED"), in the prepared state.
- Its NOT-IN-FORCE outcome describes the ruling's legal effect. It does not record a refusal.

**Checked closely, and I agree with A:**
- **ADOPT R-81..85 by the Chief: ACT-REFUSED.** The five rulings were filed and claimed effect: R-81's outcome reads "Batch 7 partially released". The determination then held them PREPARED ("“BATCH 7 IS RELEASED” DOES NOT HOLD"). So an identified item's claimed effect was refused.
  - A could have argued this more tightly. The determination's *premise* is an authority statement ("THE DECISION AUTHORITY IS THE HUMAN AUTHORITY, NOT THE CHIEF"). What makes the event a refusal rather than a PERMISSION-STATEMENT is the denied effect.
- **OPEN-WORK by a recording note: PERMISSION-STATEMENT.** This is consistent with the Chief case. There, the claimed effect was *denied*. Here, the opening was *granted*: the text was refiled as a ruling and WP-7B-R1 opened. Nothing was turned down; the event rests on the capability statement "A recording note cannot open a work package".
- **SUPERSEDE §12 (R-83): PERMISSION-STATEMENT.** R-83 says "no evidence was presented for superseding it", so no proposal existed. The sentence states what exclusion would *require*, which is a conditional permission.
- **REGISTER constitutional decision (rule): GENERIC-PRACTICE.** "the register holds constitutional decisions" is a general rule about what the register contains. No particular registration is recorded.
- **REGISTER R-90: ACT-REFUSED.** A row was filed, the Decision Authority ruled on it, and it was withdrawn. One nuance: the row stays *in* the register, so what was refused is its standing as a registered decision, not its physical presence.
- **The 10 START events from register rows: PERMISSION-STATEMENT.** R-47, R-58, R-65 and R-86 perform AUTHORIZE, not START. R-56, R-72, R-79 and R-81 state non-authorizations or prohibitions. Criterion (c) applies, because no attempted start is recorded at any of them.
- **R-36 items: ACT-PERFORMED / ACT-REFUSED at ruling level.**
  - Risk: several not-promoted items may fall under "platform-doc (C) 4" or "candidates (D) 5". Those categories are re-routing, not express refusal.
  - The item-to-category mapping sits in the unprovided matrix.
  - I keep A's classes because R-36 does *expressly* decline identified candidates. The item-level assignment is UNKNOWN.
- **UNCLEAR:** L493-B, ADR-MP and register-numbering. All three follow criterion (d).

**Reviewer counts:** ACT-PERFORMED 11 · ACT-REFUSED 8 · PERMISSION-STATEMENT 13 · GENERIC-PRACTICE 1 · UNCLEAR 3.

## 3. Witness pairs from WITNESSES.md, recomputed

| Var | Pair (WITNESSES.md) | Classes (reviewer) | Verdict | Note |
|---|---|---|---|---|
| a | Chief (declined) vs DA R-86 | REF + PERF | **SURVIVES** | Only **a** differs |
| a | R-70 vs R-89 | PERF + PERF | **SURVIVES** | See note (a) below |
| k | REGISTER S0804-L576 pair | REF + GEN | **FAILS** | |
| k | OPEN-WORK note vs ruling (R-60) | PERM + PERF | **FAILS** | |
| s | START 7C at R-58 vs 7C at R-65 | PERM + PERM | **FAILS** | Also circular; see §4 |
| s | START §12 at R-81 vs at R-86 | PERM + PERM | **FAILS** | Also circular |
| e | R-36 promoted vs not promoted (one cluster) | PERF + REF | **SURVIVES** | See note (e) below |
| c | ADOPT R-86 vs R-91 | PERF + REF | **SURVIVES on classes; not strict** | See note (c) below |
| h | never-used vs retired R-90 | UNCL + PERM | **FAILS** | |

Notes on the pairs:
- **(a) R-70 vs R-89.** The pair survives under the task's rule whichever class R-89 takes. Under my class, though, both sides are performed acts. The pair then contrasts *in force* with *prepared*, not act with refusal. Its validity depends on A's assumption 12, that NOT-IN-FORCE counts as the opposite of PERFORMED. *(formal reasoning)*
- **(e) R-36.** A counts 12 pairs; I count 1 cluster pair. All 7 events rest on a single row and a single adoption act, so they are not 12 independent observations. *(independence)*
  - The e-values are only partly attested. The row gives "(1 slice)" for Registration ≠ Delivery, which supports e=1 on one not-promoted item.
  - Nothing in the row attests e=2+ for any promoted item. That value comes only from the unprovided matrix. *(source interpretation / evidence scope)*
- **(c) R-86 vs R-91.** In EVENTS.json the pair differs in **k** (`same:R-81..85` vs `ruling`), **t** and **c**. So it is not a single-variable witness, which is why WITNESSES.md marks it "coarse grain". A dropped it (c=0). That is correct at strict grain, but A did not report it. *(formal reasoning)*
- **A's extra s pairs:** R-47 7B vs R-58, R-56 vs R-58, and R-72 vs git. All three FAIL, so this changes nothing.
- **The R-72 vs git pair.** This pair is doubtful on another ground as well. The git event's **s** is `UNK` in EVENTS.json and `auth-full` in CODING-LINES.
  - The sources do not support `auth-full`. R-72 (08-02) says "no RED begins", and R-77 (08-03) says "WP-4B REMAINS BLOCKED".
  - The only record of the proviso being met would be R-73–R-76, which are not provided. The ordering of the 16:10 commit against R-77 is UNKNOWN.
  - EVENTS.json's `UNK` is the right code. *(coding)*

**Surviving support (reviewer, WITNESSES.md list):** a=2 · k=0 · s=0 · t=0 · e=1 (cluster) · c=0 strict (1 coarse) · h=0.
**A's figures:** a=2 · k=0 · s=0 · t=0 · e=12 · c=0 · h=0.

## 4. Patterns by operation, and whether they are artifacts

| Operation | PERF | REF | PERM | GEN | UNCL | Mostly |
|---|---|---|---|---|---|---|
| ADOPT | 1 | 2 | | | | attested acts |
| AUTHORIZE-IMPL | 2 | | | | | attested acts |
| RAISE | 5 | 3 | | | 1 | attested acts |
| ANNOTATE | 1 | | | | | attested acts |
| SUPERSEDE | | 2 | 1 | | 1 | mixed, leaning to acts |
| OPEN-WORK | 1 | | 1 | | | mixed |
| REGISTER | | 1 | | 1 | | mixed |
| START | 1 | | 10 | | | **permission statements** |
| ASSIGN-ID | | | 1 | | 1 | no attested act |

**SOURCE FACT:** The norm-making operations (ADOPT, AUTHORIZE, RAISE, ANNOTATE, and in part SUPERSEDE) are attested because the register row *is* the act, per criterion (a). START is attested only once, by the git commit.

**INFERENCE: yes, the START pattern is an artifact. It has two separate causes.**
1. **Definitional (formal reasoning).**
   - In EVENTS.json, every START event with s=`authorized` is PERFORMED. Every START event with s ∈ {`not-authorized`, `auth-proviso-unmet`, `permission-only`} is REFUSED.
   - The git event (s=`UNK`) is the only exception.
   - So START's outcome was coded from the same authorization text that fixes **s**, and the s-witnesses are true by construction.
   - They would fail even if the attested-act filter did not exist. They cannot count as evidence that **s** governs legality. A presents the s-witnesses as real pairs that happen to fail the filter; I disagree.
2. **Source genre (evidence scope).**
   - The evidence base is a *norm register*. For execution operations such a register can only state permissions.
   - Actual execution records (commits) were supplied only once.
   - The same mechanism produces the "counterfactual" REFUSED events built from rule statements ("ground: RULE"): OPEN-WORK by note, ASSIGN retired R-90, and the REGISTER rule. Each turns a norm into a negative event that never happened.
3. **Counter-evidence.**
   - The one attested START (the git commit) occurs when the provided sources show WP-4B as unauthorized or blocked (R-72, R-77), not authorized.
   - If that ordering holds, attested behaviour and the permission-coded outcome diverge. That would be direct evidence against reading START outcomes off authorization rows. **(UNKNOWN:** the order of the commit and R-77 within 08-03 cannot be established.)

**Missed but attested in the sources (substantive; does not change the frozen counts):**
- R-90 records an actual ASSIGN-ID attempt that was refused: the ruling "was put forward as “R-89”. R-89 WAS ALREADY TAKEN … Issued as R-90".
- That gives an attested refused assignment of a *held* number and an attested performed assignment ("Issued as R-90").
- This is a possible attested **h**-contrast (held vs unused), not the coded one (retired vs unused). It is not among the 36 frozen events.

## 5. Empirical status of each variable (attested acts only)

- **a (authority): retains strict support. 2 pairs, but they are not fully independent.**
  - The two pairs are in different operations and different clusters.
  - Both express the same Chief-vs-Decision-Authority rule: the Session-1 determination, restated in R-86.
  - Pair 2 contrasts *prepared* with *in force* rather than act with refusal.
  - This is the best-supported dimension.
- **e (evidence): weak, conditional support. 1 cluster pair.**
  - One row carries it (R-36).
  - The contrasting e-values are attested only on the not-promoted side ("1 slice").
  - Item mapping is UNKNOWN.
- **c (conformance): no strict support.** The only pair (R-86 vs R-91) is coarse. It differs in k and t as well as c.
- **k, s, h: no support on attested acts.** For s, the support was circular before any filter was applied.
- **t: never had strict witnesses.**

## Disagreement register (not averaged)

| # | Item | Type |
|---|---|---|
| D1 | WITNESSES.md not read, so the witness list differs from the frozen list | evidence scope |
| D2 | R-89: ACT-REFUSED → ACT-PERFORMED (no refusing or holding decision recorded) | coding |
| D3 | e support: 12 → 1 (a single row and a single act) | independence |
| D4 | c: the coarse pair R-86/R-91 was omitted; strict c=0 agreed | formal reasoning |
| D5 | Git quote splice (added period); undisclosed backtick stripping | wording |
| D6 | R-56 quote half-anchored in a "not part of this ruling" recording note | source interpretation |
| D7 | R-36 row does attest e=1 for one not-promoted item ("1 slice"); A says all e-values come from the matrix | source interpretation |
| D8 | Git event's s: A left the EVENTS/CODING conflict open. UNK is correct; `auth-full` is unsupported | coding |
| D9 | START s-witnesses are circular by construction, not merely filtered out | formal reasoning |
| D10 | Attested ASSIGN-ID acts at R-89/R-90 (collision; "Issued as R-90") were not noted | substantive |
