# `P-66` — Sufficiency Audit of the 11-Cell Persistence Kernel

**2026-09-09 · Lane T.** After [`83` `P-65`](./83-P65-PSTAR-THEOREM-VS-ANALYTIC-DEFINITION-AUDIT.md).

# 1. Executive verdict

$$\boxed{\mathbf{[OPEN]\ —\ SUFFICIENCY\ CANNOT\ YET\ BE\ TESTED,\ AND\ THE\ BLOCKER\ IS\ ALREADY\ KNOWN.}}$$

⭐⭐⭐ **`Z_K` cannot be typed.** Sufficiency needs $Z_K : X \to \cdots$ — ⛔ **and there is no `X`.**
`P-60` established `𝒳_R` is `[UNWITNESSED]` **by design**: the charter puts carrier selection out of
scope as `OQ-1`. ⭐ **The eleven cells cannot be composed into a tuple-valued function over a carrier
that has not been selected.**

⭐⭐ **Conducted conditionally anyway** — *what would happen if a carrier existed* — and the result is
informative:

$$\boxed{\textbf{Of the five } \mathbf{FATAL} \textbf{ rows: } \mathbf{1\ recovers\ cleanly \cdot 2\ CONDITIONAL \cdot 2\ OPEN.} \textbf{ ⛔ Zero refuted.}}$$

$$|K| = 11 \textbf{ — unchanged, } \mathbf{[REC]}. \textbf{ ⛔ No } \mathtt{UNKNOWN}/\mathtt{ACCEPTABLE} \textbf{ row promoted. No necessity, no minimality.}$$

# 2. Concession and disagreement

**2.1 ⭐⭐ Conceded.** `P-65` called the sufficiency question *"fully well-posed"* because the objects were
**named**. ⛔ **Named is not typed** — and that is the `P-65` failure mode repeating one audit later.
⭐ The typing audit runs **first**, and it is what produces the verdict.

**2.2 ⭐ Disagreement — one, small.** Step 4 asks for the smallest countermodel where `P` collapses,
`Z_K` is identical, and `d` differs. ⛔ **That test presupposes `d` is a total function on `X`.** For
`W1` it is not *(§6)*: for a pair the estate never adjudicated, **`d` has no value**, so the pair is
neither a countermodel nor a confirmation. ⭐⭐ **I report the typing failure rather than manufacturing a
refutation out of it** — which the commission's *"do not manufacture"* rule requires.

⛔ **No `three_model_convergence` · no `|K|` change · no minimality · no necessity · no canonicalization ·
no external mathematics unmarked · no `UNKNOWN`→`FATAL` promotion.**
⚠️ **Pre-registered `[CONDITIONAL]`.** ⭐⭐ **Wrong — the typing audit produced `[OPEN]`.** Record 21 of 31.

# 3. ⭐⭐⭐ The research folder DOES hold a verification *(commissioned check)*

`docs/knowledgeos/research/theory-v1.2-simulation/` — **32 documents**, experiments `A`–`Z`, an
experiment-ID registry, two adjudication briefs and a **`FINAL-VERDICT.md`**, `[EXP]`-tagged. Its
sibling `theory-v1.1-simulation/` carries its own. ⛔ **Neither has ever been cited by Lane T.**

⭐⭐⭐ **And its `§3 WHAT FAILED` lands directly on kernel territory:**

> **`CE-3` (revision without retraction)** — *"reproduced exactly. §VIII names revision / supersession /
> contradiction / retraction / history and **defines no retirement relation**."*

⚠️ **`K4c-i` *retired identity* and `K4c-ii` *retired-into* are the lane's cells for exactly that.**
⭐ **Reproduced across two theory versions** — `v1.1` and `v1.2` — so it is a **standing, twice-witnessed
gap**, ⛔ **and I do not map it onto `K4c` by name** *(the `P-38` rule)*; it is recorded as the single
strongest external test the kernel has never been run against.

⭐ Also `[EMP]` from the same verdict: `CE-1` factivity reproduced · `Zero_strict` **unreachable in every
case tested** · the five relations **non-substitutable in 13 of 20 directions**.

# 4. Step 1 — objects frozen, with types

| object | type | corpus witness | status |
|---|---|---|---|
| `K1` | retention obligation on **three adjudicated identities** | `P-08` §16 | ⭐ `[EMP]` |
| `K2` | current-state retention, **three updated items** | §16 | `[EMP]` |
| `K3a` · `K3b` | prior value · warrant, **non-monotone transitions only** | `P-09.3` | `[EMP]` |
| `K4a-i/ii` · `K4b-i/ii` · `K4c-i/ii` | prior proposition · supersedes · withdrawn ground · invalidated-by · retired identity · retired-into | `P-09.1`–`P-09.3` | `[EMP]`, `K4c` **conditional on `O-P09-1`** |
| `K5` | ground stays checkable without a mutable external | §16 | `[EMP]` |
| `τ1`…`τ11` | **witnessed transition CLASSES**, ⛔ **not functions** *(`P-60`)* | `P-08` §1 | `[EMP]`; `τ4` `[OPEN]` |
| the projection per `τ` | ⛔ **`[OPEN]`** — no `τ` is given a state-transformer semantics | — | `[OPEN]` |
| `X` the carrier | ⛔ **`[UNWITNESSED]`** — `OQ-1`, out of scope by charter | `P-60` | `[UNWITNESSED]` |
| loss taxonomy | **5 FATAL · 1 RECOVERABLE-with-downgrade · 3 ACCEPTABLE · 2 UNKNOWN** | `P-08` §18 | `[EMP]` |
| witnesses `W1`–`W7` | smallest-missing-datum records | §19 | `[EMP]` |

# 5. Step 2 — formalization, and where it stops

$$\textbf{Sufficiency: } \exists\, r \;\textbf{ s.t. }\; r\big(P(x),\, Z_K(x)\big) = d(x) \quad \forall x \in X$$

⭐ Three typing obligations, checked in order:

| | obligation | result |
|---|---|---|
| **①** | `X` exists | ⛔ **FAILS** — `[UNWITNESSED]` by design |
| **②** | each `τ` induces a projection `P : X → Y` | ⛔ **FAILS** — transition **classes**, not functions *(`P-60`)* |
| **③** | `K1`…`K11` compose into one $Z_K : X \to \prod_i Z_i$ | ⛔ **NOT LEGITIMATE** — tuple formation needs a common domain, which ① denies. ⭐ **And the cells are heterogeneous**: `K2` is state-valued, `K3b` warrant-valued, `K4a-ii` relation-valued, `K5` a **checkability predicate** — ⛔ **`K5` is not even a stored datum** |

$$\boxed{\textbf{⭐⭐⭐ Three obligations, three failures. } \mathbf{[OPEN]\ —\ sufficiency\ cannot\ yet\ be\ tested,} \textbf{ reported rather than repaired.}}$$

# 6. Step 3 — the five `FATAL` rows, tested conditionally

⭐ **Under the hypothesis that ①–③ were discharged**, each row is tested against its own witness:

| `FATAL` loss | `d` | witness | cell | recovery | status |
|---|---|---|---|---|---|
| **superseded assertion deleted** | the superseded text + relation | `W4` | `K4a-i/ii` | ⭐⭐ `r` **reads the retained text** | ⭐⭐ **DIRECTLY RECOVERABLE** |
| **prior association lost on re-disposition** | *was this re-dispositioned?* | `W2` | `K3a`+`K3b` | ⭐ record present ⇒ yes; **absent ⇒ no** | ⚠️⭐⭐⭐ **CONDITIONAL — on a CLOSED-WORLD reading of the transition log, which `P-37` established the estate does NOT have** |
| **absent endpoint recorded as null** | *awaiting adjudication* vs *adjudicated to none* | `W7` | `K2` state fidelity | ⭐ recoverable **iff the state representation separates them** | ⚠️ **CONDITIONAL — a REPRESENTATION requirement, not a retention one** |
| **two candidates collapse into one identity** | `SameId` or not | `W1` | `K1` | ⭐⭐⭐ `K1` covers **three adjudicated** identities; `Q3`: *"the estate cannot always tell"* | ⛔ **OPEN — `d` is NOT TOTAL** *(§2.2)* |
| **corpus state lost for a cited measurement** | the measurement's interpretation | `W6` | `K5` | ⭐ `P-08` itself: *"the corpus-state identifier — **this is the one remaining gap**"* | ⛔ **OPEN — `O-P08-2`, live** |

$$\boxed{\mathbf{1\ clean \cdot 2\ conditional \cdot 2\ open \cdot 0\ refuted.} \textbf{ ⛔ } \mathtt{UNKNOWN}/\mathtt{ACCEPTABLE} \textbf{ rows untouched, as instructed.}}$$

⭐⭐⭐ **The `W2` finding is the sharpest and is new:** recovery from `Z_K` requires reading *"no
transition record"* as *"no transition occurred"* — ⛔ **a closed-world assumption the estate's own
open-world semantics reject.** ⭐ **`K3`'s sufficiency therefore rests on a premise `P-37` refuted.**

# 7. Step 4 — countermodels

⛔ **No countermodel constructed, and the reason is reported rather than papered over:** a countermodel
needs $P(x_1)=P(x_2)$, $Z_K(x_1)=Z_K(x_2)$, $d(x_1)\neq d(x_2)$ — ⭐ **and none of the three is a
function yet** *(§5)*. ⭐⭐ **The `W1` pair is the nearest candidate and it fails on `d`'s totality, so it
is recorded as a typing failure, not as a refutation.**

$$\boxed{\textbf{⛔ Absence of a countermodel is } \mathbf{NOT\ proof.} \textbf{ Required for a proof: a witnessed } X\textbf{, } \tau \textbf{ as functions, a total } d \textbf{ per FATAL row, and a closed-world ruling for } W2.}$$

# 8. Steps 5–7 — the separations held

⭐ **`A` sufficiency** is the only claim tested. ⛔ **`B` necessity** and ⛔ **`C` minimality** are
untouched, and `P-65` established `A ⇏ B, C`.
⭐⭐ **DDD:** `W1`, `W2`, `W4` are **domain distinctions** — witnessed acts on estate records. ⚠️ **`W7`
is partly a formalization artifact**: *"null vs absent"* is a **representation** distinction, and
⛔ **promoting it to a domain invariant would import a modelling choice.**
⛔ **Statistics:** 11 cells · 5 FATAL · 7 witnesses · 32 documents are **corpus witnesses, not
observations.** No count is used as evidence of general validity.

# 9. Required reports

**Mathematical:** ⛔ sufficiency does not type; ⭐ conditionally, 1 of 5 recovers cleanly.
**Corpus:** ⭐⭐ every `FATAL` row already carries a named witness and a named cell — `P-08` §19 did this
work; ⭐⭐⭐ **and an unconsumed executable verification exists** *(§3)*.
**DDD:** 3 domain distinctions, 1 partly representational, 1 untestable.
**Kernel consequence:** ⭐ **nothing changes.** `|K| = 11`, `[REC]`, unfrozen.
**Unproved:** everything — ⛔ **no sufficiency claim is made in any direction.**

# 10. Backlog — ⛔ **nothing new; `EKS-18` extended**

⭐⭐ `theory-v1.2-simulation/`'s `CE-3` is a **third instance of `EKS-18`** — a recorded result naming a
gap, never reaching the lane working on it. ⛔ **`ES-005.4` puts a third instance on the existing
record**, and it is appended there. ⭐ **Sixth audit without a new ticket.**

# 11. Status register
**`[EMP]`** ⭐⭐⭐ `theory-v1.2-simulation`, 32 documents with a `FINAL-VERDICT` and `CE-3` *"defines no
retirement relation"*, reproduced across two versions · `Zero_strict` unreachable · 13 of 20
non-substitutable · `P-08` §18's 5/1/3/2 taxonomy · `W1`–`W7` and their cell assignments · `W6` named by
`P-08` as *"the one remaining gap"*.
**`[DERIVED]`** ⭐⭐⭐ `Z_K` is not typeable — three obligations, three failures · `W2`'s recovery needs a
**closed-world** transition log, which `P-37` refuted · `W1`'s `d` is **not total**.
**`[QUALIFIED]`** ⚠️ the conditional row analysis — valid **only** under an assumption the estate denies.
**`[OPEN]`** ⭐⭐ sufficiency itself · `𝒳_R`/`OQ-1` · `O-P08-2` · `Q3`/`τ4` · `O-P09-1` · the closed-world
ruling · all carried opens · ⛔ **well-foundedness / terminality / acyclicity — DEFERRED.**
**Governance:** `|K| = 11` **`[REC]`** — unchanged, unfrozen, ⛔ **and now not even testable for
sufficiency until a carrier exists.**

### 12. ONE NEXT UNRESOLVED QUESTION

$$\boxed{\textbf{Does the estate read } \mathbf{"no\ transition\ record"} \textbf{ as } \mathbf{"no\ transition\ occurred"} \textbf{ — and if not, what makes } K3 \textbf{ recoverable at all?}}$$

⭐⭐⭐ **This is the one dependency `P-66` actually discovered, and it is small, sharp and answerable.**
`K3` retains prior value and warrant **on non-monotone transitions only**; recovery reads the absence of
a record as evidence that no non-monotone change happened. ⭐ **That is a closed-world inference**, and
`P-37` established the estate is **open-world**. ⛔ **If the open-world reading holds, `K3` cannot
distinguish *"never re-dispositioned"* from *"re-dispositioned but unrecorded"* — and `W2`, the
insufficiency proof `K3` was derived from, would not be discharged by `K3`.** ⚠️ **It is a ruling, not a
computation — and it must not be settled by observing that the estate's records happen to look
complete.**

```
[OPEN] — SUFFICIENCY CANNOT YET BE TESTED, AND THE BLOCKER IS ALREADY KNOWN.

Z_K cannot be typed. Sufficiency needs Z_K : X -> ..., and there is no X: P-60 established that the
carrier is UNWITNESSED BY DESIGN, since the charter puts carrier selection out of scope as OQ-1. Three
typing obligations were checked in order and all three fail: X does not exist; no tau is given
state-transformer semantics, so no projection P : X -> Y is induced; and tuple formation is not
legitimate without a common domain — the cells are heterogeneous anyway, and K5 is a checkability
predicate rather than a stored datum.

CONCEDED: P-65 called this question "fully well-posed" because the objects were NAMED. Named is not
typed, and that is the P-65 failure mode repeating one audit later. Pre-registered [CONDITIONAL]; the
typing audit produced [OPEN].

CONDUCTED CONDITIONALLY ANYWAY, and the result is informative. Of the five FATAL rows: the deleted
superseded assertion recovers cleanly from K4; the null-versus-absent endpoint is CONDITIONAL on the
state representation separating them, which is a representation requirement rather than a retention one;
the collapsed candidate identity is OPEN because d is NOT TOTAL — for a pair the estate never
adjudicated there is no value to recover, and Q3 says the estate cannot always tell; and the lost corpus
state for a cited measurement is OPEN because P-08 itself calls it "the one remaining gap". ONE CLEAN,
TWO CONDITIONAL, TWO OPEN, ZERO REFUTED. The UNKNOWN and ACCEPTABLE rows were left untouched.

THE SHARPEST FINDING IS NEW: recovery of the re-disposition distinction from K3 requires reading "no
transition record" as "no transition occurred" — a CLOSED-WORLD assumption, which P-37 established the
estate does not have. K3's sufficiency therefore rests on a premise the lane's own earlier audit refuted.

No countermodel was constructed, and the reason is reported rather than papered over: a countermodel
needs three functions and none of the three is a function yet. The W1 pair is the nearest candidate and
fails on d's totality, so it is recorded as a typing failure, not manufactured into a refutation.
Absence of a countermodel is not proof.

THE COMMISSIONED CHECK — DOES THE RESEARCH FOLDER HAVE THIS VERIFICATION? YES, AND IT HAS NEVER BEEN
CITED BY THIS LANE. research/theory-v1.2-simulation/ holds 32 documents, experiments A through Z, an
experiment-ID registry and a FINAL-VERDICT, with a sibling v1.1 run. Its "WHAT FAILED" section reports
CE-3, revision without retraction, "reproduced exactly — §VIII names revision, supersession,
contradiction, retraction and history and DEFINES NO RETIREMENT RELATION", across two theory versions.
K4c-i and K4c-ii are this lane's cells for exactly that territory. The mapping is NOT made by name, per
the P-38 rule; it is recorded as the strongest external test the kernel has never been run against.

Separations held: sufficiency only. Necessity and minimality untouched, and P-65 established sufficiency
implies neither. DDD: three domain distinctions, one partly a formalization artifact — "null versus
absent" is representational, and promoting it to a domain invariant would import a modelling choice.
Counts are corpus witnesses, never observations.

No new backlog item — CE-3 is a third instance of EKS-18 and is appended there. Sixth audit without a
new ticket.

|K| = 11 UNCHANGED, [REC], UNFROZEN, and now not even testable for sufficiency until a carrier exists.

ONE NEXT UNRESOLVED QUESTION: Does the estate read "no transition record" as "no transition occurred" —
  and if not, what makes K3 recoverable at all? K3 retains prior value and warrant on non-monotone
  transitions only, and recovery reads the absence of a record as evidence that no non-monotone change
  happened. That is a closed-world inference, and P-37 established the estate is open-world. If the
  open-world reading holds, K3 cannot distinguish "never re-dispositioned" from "re-dispositioned but
  unrecorded", and W2 — the insufficiency proof K3 was derived from — would not be discharged by K3. It
  is a ruling, not a computation, and it must not be settled by observing that the records happen to look
  complete.

NO three_model_convergence INSPECTION — excluded by path from every command, unread.
```
