# 1ao: reconciliation. Compact task report

| | |
|---|---|
| Question (H-AS) | For each guard in the model-relative family (F-LOG-0156): ANALYTIC (true by definition), SYNTHETIC (a contingent, testable rule) or UNKNOWN? |
| Frozen | `PREREG.md` + bundle (`c38ee20c1`) |
| Result | A and the reviewer agree on **10 of 11** items. The one disputed item is REGISTER/k (A: UNKNOWN; reviewer: SYNTHETIC, for consistency with OPEN-WORK/k; D1, source interpretation). Both readings are **preserved** |
| Bias check | the sealed expectation was **partly reversed** (ADOPT/a and AUTHORIZE-IMPL/a were expected ANALYTIC, and are SYNTHETIC; ADOPT/c was expected SYNTHETIC, and is ANALYTIC) |

## Classification (formal; strict coding only)

| Class | Guards | Proper test | State of that test |
|---|---|---|---|
| **ANALYTIC** | START/s · START/t (derivative of s, not independent; D8) · ADOPT/c | **premise independence** | START/s: **2 INDEPENDENT, 9 UNCLEAR** of 11 events (reviewer, D2; A's count of 7 rested on coder labels). ADOPT/c: **all UNCLEAR**. "conformant" is not sourced *within this bundle*; it came from the reviewed 1am-3 coding (F-LOG-0153), which is evidence scope, disclosed |
| **SYNTHETIC** | ADOPT/a · AUTHORIZE-IMPL/a · OPEN-WORK/k · RAISE/e · ASSIGN-ID/h · (REGISTER/k, disputed) | **prediction / contrast** | every contrast is **WEAK**: within-cluster, a pseudo-contrast (a generic practice as the PERFORMED side: ASSIGN-ID, REGISTER), or possibly CHOICE (ADOPT/a "declined") |
| **UNKNOWN** | RAISE/t · SUPERSEDE/k | — | — |

## Findings that change the model (INFERENCE; not promoted)

1. **The strongest formal guard is analytic, and its premise is weakly established.** START is lawful iff the target is authorized, by definition. Only **2 of 11** coded pre-act states are independently established in the coding lines. So the empirical content of the best-supported rule is mostly **unverified premise establishment**.
2. **Every synthetic guard rests on weak or non-independent contrasts.** Together with F-LOG-0157 (no synthetic guard generalizes by prediction), there is **no well-evidenced synthetic governance rule** in the strict data.
3. **A hidden variable in AUTHORIZE-IMPL (D10).** a works *through* the authorizing ruling's own status: PREPARED ⇒ (by definition) NOT-IN-FORCE. That status is **uncoded** (s = "guards-met" on both sides). So "authority determines AUTHORIZE-IMPL" may really be "**the status of the authorizing act** (PREPARED vs ADOPTED) determines it, and authority determines the status". That is a *chain*, **a ⇒ status(ruling) ⇒ in force**, consistent with the bisimulation result that status is behaviour-relevant (F-LOG-0156).
4. **The criterion itself (D7):** field type where the field is typed, otherwise value meaning. The reviewer found A consistent under this criterion. The ANALYTIC/SYNTHETIC line therefore depends on how variables are typed, which is **a coding-ontology choice**. It connects to the pending manual-r3 decision.

## Surviving / eliminated
- **Structure H-AS: SUPPORTED as a classification** (10/11 agreement, same-family).
- The theory currently consists of **constitutive definitions** with weakly established premises, plus **synthetic rules** with weak contrasts.

## Next (dynamic queue)

| Candidate | Discrimination | Needs human? |
|---|---|---|
| **1ap:** AUTHORIZE-IMPL hidden status: recode the authorizing ruling's status (PREPARED / ADOPTED) from the source for the two AUTHORIZE-IMPL events, and test whether status replaces a (chain vs direct) | **HIGH** (cheap; decides direct authority vs status-mediated authority) | no |
| premise-independence locate for START's 9 UNCLEAR events | MEDIUM–HIGH | no (targeted locate) |
| evidence split (RAISE/e) | MEDIUM | no |
| manual r3 | — | **yes** (pending, F-LOG-0159) |
