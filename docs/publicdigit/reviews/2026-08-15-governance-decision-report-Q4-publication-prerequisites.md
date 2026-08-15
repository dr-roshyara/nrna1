# Governance Decision Report — `Q4` Publication Prerequisites, Publication Boundary, Authority & Four-Eyes

**Type:** Governance Decision Report (Session 2) · **Date:** 2026-08-15 · **Commission:** PO/ARB, *"Continue the `EM-OPEN-023` business decision process"*
**⛔ Business and governance language only. No architecture. No code, tests, fixtures, schemas, migrations, state machines, services, jobs or UI were inspected as authority or touched. No implementation authorized. Nothing filled in from existing behaviour or convenience.**

**Labels:** **ADOPTED RULE** · **DERIVED CONSEQUENCE** *(follows from an adopted rule; adds no policy)* · **GOVERNANCE RECOMMENDATION** *(not binding)* · **OPEN QUESTION** *(PO/ARB)*.

---

## 1 · Authority verification — and one provenance finding that must be recorded

**Verified adopted in the committed record:** `EM-VOT-004` · `EM-VOC-004` *(+ confirmed reading)* · `EM-VOC-005` *(+ `D-1`, `D-2`, `D-3`)* · `EM-VOT-005` *(scope-extended)* · `EM-GOV-004` *(principle only)* · `EM-GOV-005` · **`EM-GOV-006`** *(adopted today)* · `P-2H`.

> ### ⚠️ **Provenance finding — `Q1`, `Q2`, `Q3`**
>
> The commission states these *"have already been established."* **On the record they had not been.** They were open questions Governance posed in the `EM-OPEN-023` working paper earlier today, and no prior act adopted them.
>
> **Governance therefore treats the commission text itself as the establishing act** — it is performative in effect (*"must be treated as governing context"*), durable, and recorded. **It is registered with that provenance: PO/ARB ruling delivered within the `Q4` commission, 2026-08-15 — not a reference to an earlier act.**
>
> **This is recorded, not corrected away**, for the same reason invariant 8 was: so that nobody later reconstructs a decision that never happened. **If the PO intended something other than a ruling, one line reverses it.**

**Search performed as `Q3` requires:** Governance searched the committed rules for **any existing binding rule requiring joint action**. **None exists.** Therefore `Q3`'s default stands: **either the Chief or the duly authorized Deputy may publish alone** — unless a Four-Eyes rule is adopted (§7).

⚠️ **One asymmetry, flagged for deliberateness, not decided:** the Constitution's referenced rule set holds *"only the chief may open voting or publish results."* `Q3` gives **schedule** publication to Chief **or** Deputy. **Different acts, so no contradiction** — but the asymmetry (results: chief only; schedule: chief or deputy) should be **deliberate rather than accidental.**

---

## 2 · `Q1`–`Q3` registered as adopted rules

| ID | Rule | Source |
|---|---|---|
| **`EM-VOC-007`** | **Publication makes an election schedule an official, valid, public and transparent election commitment.** Publication is **a business act, not merely a notification mechanism.** Once published: the schedule is an official commitment; it must not be secretly changed or manipulated; subsequent changes must be **governed** and **attributable to an authorized actor**; the original published fact **remains part of the election history**; a later correction **never silently rewrites** the original schedule. | `Q1` |
| **`EM-GOV-007`** | **The official election schedule must be visible to all relevant participants of that election — members, eligible voters and candidates. There must never be competing versions of the "official" schedule for different participant groups.** | `Q2` |
| **`EM-GOV-008`** | **The Chief Election Officer and the duly authorized Deputy Election Officer are authorized to publish the official election schedule; either may act independently** *(no existing rule requires joint action — searched and confirmed)*. **This is operational authority, not sovereignty:** the Chief remains bound by the Election Constitution, the Manifesto, the Election Rules and applicable governance decisions, and **no individual authority may override a mandatory Election Rule.** | `Q3` |

---

## 3 · Two previously-blocked questions are now answered — by derivation

> ### **DERIVED · `EM-OPEN-023`(a) is RESOLVED: publication is a distinct business act (the "Option A" reading).**
>
> `EM-VOC-007` says so directly — *a business act, not merely a notification mechanism* — and `EM-GOV-008` names who performs it. **The two alternatives Governance had put to the PO (a defined milestone; no separate act) are excluded by the adopted text.** Governance did not choose this; the ruling did.

> ### **DERIVED · The voting opportunity comes into existence AT PUBLICATION.**
>
> `EM-VOC-004` (ADOPTED): *a published voting schedule defines a specific voting opportunity.* If publication is now a determinate act, then **before it there is no published schedule and therefore no opportunity; at it, there is.** This closes the question Governance had raised as *"logically inseparable from publication"* — **inseparable, and now separated by the same ruling.**
>
> ⚠️ **Flagged for one-line PO confirmation**, because it closes a question previously marked BLOCKED. Governance states it as a derivation, not as a ruling of its own.

**Consequence, now determinate rather than conditional:** **`D-1` has a real boundary.** An edit before the publication act is preparation; an edit after it is a governed correction under `EM-VOC-005`. **The ambiguity the commission's §6 sought to prevent — *"'published' without a clear business event"* — is closed.**

---

## 4 · `Q4` — Mandatory prerequisites for official publication

| # | Candidate prerequisite | Class | Reason |
|---|---|---|---|
| 1 | **An election exists and is identifiable** | **C · DERIVED** | `EM-VOC-007` makes publication *"an official commitment **of the election**"*. There must be an election to commit. |
| 2 | **The election has been formally approved/established** *(as distinct from merely existing)* | **D · OPEN** → `EM-OPEN-033`(a) | *Evidence, not authority:* the Constitution's referenced rule set contains an approval workflow, which shows an approval concept **exists**; it does **not** establish that approval must precede schedule publication. **Governance will not infer the rule from the workflow's existence.** |
| 3 | **An appointed Chief (or duly authorized Deputy) exists at the moment of publication** | **C · DERIVED** | `EM-GOV-008`: only they may publish. An act only they may perform cannot occur when neither exists. |
| 4 | **The schedule states when the phase begins and ends** | **C · DERIVED** | A commitment that cannot say *when* is not a commitment (`EM-VOC-007`). |
| 5 | **The schedule's times have a determinate meaning** | **D · OPEN, and DEPENDENT** → `EM-OPEN-024` | ⚠️ **A schedule cannot be an "official, valid, public and transparent commitment" while what its times MEAN is undecided.** `EM-OPEN-024` (kind of value · governing timezone · who sets it · DST) is open. **This is the sharpest dependency in the commission: the publication rule can be adopted, but a schedule cannot be *fully* specified until `EM-OPEN-024` is ruled.** |
| 6 | **The schedule is internally coherent** *(an end not preceding its start)* | **C · DERIVED** | An incoherent schedule cannot be complied with, so it cannot be a valid commitment. **Minimal coherence only.** |
| 7 | **Inter-phase ordering is respected** *(e.g. nomination closes before voting opens)* | **D · OPEN** → `EM-OPEN-033`(c) | **No adopted rule orders the phases as a schedule constraint.** Interacts with `G-PROG-1` and `EM-OPEN-030`. |
| 8 | **The schedule contains all mandatory information** | **D · OPEN** → `EM-OPEN-033`(b) | **"Mandatory information" is nowhere defined.** Items 4–7 are its only currently-derivable members. |
| 9 | **The schedule complies with mandatory Election Rules *that apply to schedules*** | **C · DERIVED — with an important limit** | `EM-GOV-008`: no individual authority may override a mandatory rule; publishing a schedule that commits the election to a prohibited thing would do exactly that. ⚠️ **LIMIT, stated to prevent over-checking:** this covers rules **about the schedule**, not rules about **progression**. `EM-VOT-003` (≥1 approved candidate, ≥1 admitted voter) is evaluated **when the Chief requests progression** (`EM-VOT-004`) — **it is NOT a publication prerequisite**, and requiring it at publication would make it impossible to publish a schedule before candidates exist. |
| 10 | **Stakeholder agreement has occurred** | **D · OPEN** → `EM-OPEN-034` | see §5 |
| 11 | **A Deputy/second reviewer is appointed** | **D · OPEN, CONDITIONAL** → `EM-OPEN-035` | a prerequisite **only if** Four-Eyes is adopted; meaningless otherwise |
| 12 | **No unresolved governance prohibition exists** | **E · NOT REQUIRED** | it collapses into #9 and adds nothing but vagueness. **A catch-all prerequisite would hand Architecture the job of deciding what counts as a "prohibition" — precisely the policy surface the `EM-OPEN-022` ruling rejected.** |
| 13 | **The election is in a particular lifecycle condition** | ⛔ **OUT OF SCOPE as phrased** | the question is posed in lifecycle terms. **Its business form is #1 and #2, which are answered above.** Governance will not translate a business prerequisite into a lifecycle condition. |

**Also DERIVED and worth stating:** **no prerequisite may require anything that cannot exist yet.** Publication necessarily precedes nomination outcomes in the ordinary case, so publication prerequisites must be satisfiable at scheduling time. *(This is what makes #9's limit load-bearing rather than pedantic.)*

---

## 5 · Stakeholder agreement

> ## ⛔ **OPEN — returned to PO/ARB. `EM-OPEN-034`.**

**Governance will not answer this, and the reason is the commission's own instruction: *"Do not invent an organizational structure."*** The available authority names **no** body, quorum, or agreement procedure. Every candidate answer — *the members agree* · *a committee decides* · *the Chief decides after consultation* — **presupposes an organisational model that does not exist in the record.** Constructing one would be Governance legislating an organisation into being.

**Four parts returned:** **(a)** is stakeholder agreement a prerequisite for publication at all, or a practice that precedes it without gating it? · **(b)** whose agreement counts? · **(c)** unanimity, majority, or none? · **(d)** must the agreement be a formal recorded decision, or is the Chief the authorized person who settles the schedule after consultation?

**DERIVED constraints binding any answer:** if agreement gates publication it must be **recordable and attributable** (`EM-VOC-007` + `EM-GOV-005`); and it must be **determinate** — publication cannot be a business act with a clear boundary (§3) if whether the agreement occurred is arguable.

**Governance observation, not a decision:** answer **(a)** first. If agreement does not gate publication, (b)–(d) become process description rather than rule, and need no governance decision at all.

---

## 6 · Publication as a boundary — §6 answered

| Property | Status |
|---|---|
| **a deliberate business act** | **ADOPTED** — `EM-VOC-007` |
| **attributable to an authorized person** | **DERIVED** — `EM-GOV-008` names who may perform it; `EM-VOC-007` requires changes to be attributable |
| **a determinate event** | **DERIVED** — a business act performed by a named actor is determinate; and constraint **C-2** required it |
| **non-retroactive** | **DERIVED** — **C-2**; also `EM-GOV-005`, since a retroactive publication would rewrite history |
| **recorded as a material election event** | **DERIVED** — `EM-GOV-005` covers material events; publication changes what every later change *is* (preparation → governed correction), which is as material as an election event gets |

> **The boundary the commission asked for exists: *schedule being prepared* and *schedule officially published* are separated by a deliberate, attributable, determinate, non-retroactive, recorded act.** ⛔ **No storage or event mechanism is proposed** — that is Architecture's, bound by `F-PROTO-1`'s seven properties.

---

## 7 · Four-Eyes assessment

**Status: `B` — a NEW business rule requiring PO/ARB adoption.** Governance does **not** adopt it. `EM-GOV-008` currently permits either officer to act alone.

**DERIVED and already binding, whatever is decided:** **two authorized people cannot jointly authorize what mandatory Election Rules prohibit.** Four-eyes constrains *who acts*; it never enlarges *what may be done* (`EM-GOV-008` + `EM-VOT-004`'s authority boundary). **Four-eyes is an accountability control, never a permission.**

### GOVERNANCE RECOMMENDATION *(not binding, and the case against is given equal weight)*

**For:** publication is the act that converts everything afterwards into a governed correction. It is the highest-leverage single-signature act in the schedule's life, and a second pair of eyes is proportionate to that.

**Against — two real costs Governance will not minimise:** ① **a mandatory two-person rule can BLOCK an election.** If the second officer is unavailable, the schedule cannot be published, and an availability problem becomes an election problem. ② It creates pressure to appoint a **nominal** Deputy, which yields the ceremony of four-eyes without the substance.

> ### ⚠️ **The finding that matters most: four-eyes on publication alone would put the safeguard on the wrong door.**
>
> The threat this programme has been guarding against is **schedule manipulation used to bypass a refusal** — and that runs through **correction**, not publication. **If publication requires two signatures but correction requires one, the weaker door is the one an officer would use.** **Schedule-correction authority is expressly excluded from this commission (`EM-GOV-004` boundaries, open), so Governance raises this rather than resolving it: any Four-Eyes decision should be taken for publication and correction TOGETHER, or knowingly for publication alone.**

**If adopted, its business meaning must state:** who proposes · who independently confirms · whether the confirmer must hold a distinct role rather than merely be a second person · whether a rejection blocks publication or returns the schedule for revision · **whether a rejected attempt is recorded** *(Governance's view: `EM-GOV-005` already requires it — a refused publication is a material event)* · and whether Chief and Deputy are interchangeable in the two positions. **Registered as `EM-OPEN-035`.**

---

## 8 · Obligations arising from publication

| Obligation | Status |
|---|---|
| participants may **rely** on the schedule | **DERIVED** — `EM-VOC-007`: an official, valid, public commitment is by definition relied upon |
| later changes become **governed corrections** | **DERIVED** — `EM-VOC-005` + `D-1`, now with a determinate boundary (§3) |
| the original schedule **remains historically visible** | **ADOPTED** — `EM-VOC-007` + `EM-GOV-005` + `D-2` |
| the published schedule **cannot be silently overwritten** | **ADOPTED** — `EM-VOC-007`, expressly |
| **no competing "official" versions** per participant group | **ADOPTED** — `EM-GOV-007` |
| the **corrected** schedule must be visible to all participants | **DERIVED** — `EM-GOV-007` speaks of *the official schedule*; after a correction, that is the corrected one |
| participants must be **actively informed** of a correction | ⛔ **OPEN** — `EM-OPEN-036` |
| a correction **creates a new voting opportunity** | ✅ **ALREADY ADOPTED — `EM-VOC-005`** |

⚠️ **Two clarifications Governance considers important.**

**① `EM-OPEN-022` is not open.** The commission says not to answer it *"unless already formally resolved in the authoritative record."* **It was resolved earlier today** — Reading A, adopted as `EM-VOC-005`: a correction to a published schedule creates a new voting opportunity, the previous one superseded and permanently recorded. **Cited, not reopened, not re-decided.**

**② Visible ≠ informed.** `EM-GOV-007` requires the official schedule to be **visible**. **Active notification is a different and stronger duty**, and no adopted rule imposes it. **Governance flags the gap plainly: a participant who was told 10:00 and never looks again would, under visibility alone, arrive at the wrong time through no fault of their own.** Not decided — `EM-OPEN-036`.

---

## 9 · Election Manifesto implications

| Layer | Belongs in | Content |
|---|---|---|
| **Business rule** | **Manifesto** | what makes publication official; what a published schedule commits the election to; visibility obligations; what a correction means |
| **Governance authority** | **Manifesto** | who may publish; that it is operational authority, not sovereignty; that no authority overrides a mandatory rule |
| **Operational procedure** | **Officer guide** *(not the Manifesto)* | how an officer prepares, reviews and performs a publication in practice |
| **Technical implementation** | **Architecture** | ⛔ never the Manifesto — no fields, APIs, controllers, jobs, UI or mechanisms |

**Applied in this commission:** `EM-VOC-007` · `EM-GOV-007` · `EM-GOV-008` entered the Manifesto; **nothing procedural or technical did.**

---

## 10 · Newly surfaced governance questions

| ID | Question |
|---|---|
| **`EM-OPEN-032`** | 🔴 **Is publication ONE act for the whole election schedule, or a separate act per phase?** `EM-GOV-007` says *"the official election schedule"* (singular); the post-schedule-decision-period work assumed **per-phase** values. **If publication is per-phase, each phase has its own publication moment and therefore its own opportunity boundary; if it is one act, the whole schedule commits at once and a later phase cannot be published separately at all.** ⚠️ **Governance considers this the most consequential question surfaced by this commission**, because §3's derivation — *the opportunity comes into existence at publication* — has a different meaning under each reading. |
| **`EM-OPEN-033`** | 🔴 **(a)** must an election be formally approved before its schedule may be published? · **(b)** what is the **mandatory content** of a schedule? *(depends on `EM-OPEN-024`)* · **(c)** what **inter-phase ordering** constraints bind a schedule? *(interacts with `G-PROG-1`, `EM-OPEN-030`)* |
| **`EM-OPEN-034`** | 🔴 **Stakeholder agreement** — (a) does it gate publication at all · (b) whose · (c) unanimity · (d) formal decision or Chief-after-consultation. **Returned unanswered: no organisational model exists in the record and Governance will not invent one.** |
| **`EM-OPEN-035`** | 🔴 **Does the Four-Eyes Principle apply to publication?** — and ⚠️ **should it be decided for publication and CORRECTION together**, since the anti-circumvention risk runs through correction? |
| **`EM-OPEN-036`** | 🔴 **Must participants be actively INFORMED of a schedule correction, or is visibility sufficient?** |

---

## 11 · Explicitly unresolved / untouched

`EM-OPEN-021` · `EM-OPEN-024` · `EM-OPEN-025` · **`EM-OPEN-026`(a) — untouched, and `EM-GOV-006` is NOT reinterpreted as deciding what a post-schedule decision period means** · `EM-OPEN-027`…`031` · `EM-GOV-004`'s six boundaries · expiry · cancellation · credential consequences · technical representation · lifecycle design · implementation · testing. **`EM-VOC-004`'s wording ratification remains pending on one line.**

---

## 12 · Architecture handoff status

```
RESOLVED THIS COMMISSION
  Q1/Q2/Q3 registered as EM-VOC-007 / EM-GOV-007 / EM-GOV-008  (provenance: ruled HERE,
                                                                not previously established)
  EM-OPEN-023(a)  RESOLVED by derivation — publication IS a distinct business act
  Opportunity start  RESOLVED by derivation — it comes into existence AT PUBLICATION
                     ⚠️ one-line PO confirmation invited; previously marked BLOCKED
  Publication boundary  deliberate · attributable · determinate · non-retroactive · recorded
  Q4 prerequisites  6 DERIVED · 1 NOT REQUIRED · 1 out of scope as phrased · 4 OPEN

STILL BLOCKING A COMPLETE PUBLICATION RULE
  EM-OPEN-032  publication granularity — per phase or whole schedule   ← most consequential
  EM-OPEN-033  approval-first · mandatory content (needs EM-OPEN-024) · inter-phase ordering
  EM-OPEN-034  stakeholder agreement — returned, no organisational model exists
  EM-OPEN-035  four-eyes — and whether it must cover correction too
  EM-OPEN-036  informed vs merely visible
  EM-OPEN-024  time semantics — a schedule cannot be fully specified without it

ARCHITECTURE     ⏸️ STOPPED — per the commission's own stop condition: business prerequisites
                 are NOT all decided, so Architecture does not proceed
SESSION 3        🛑 STOPPED       IMPLEMENTATION   NOT AUTHORIZED
```

**Traceability.** Commission (PO/ARB, 2026-08-15) · working paper (`e00c472e`) · `EM-GOV-006` adoption (`2abc0dd1`) · `EM-VOC-004`+confirmed reading · `EM-VOC-005`/`D-1`/`D-2` · `EM-VOT-003`/`EM-VOT-004`/`EM-VOT-005` · `EM-GOV-004`/`EM-GOV-005` · `P-2H` · `F-PROTO-1` · `PBDIGIT-60`.

**STOP — end of Governance report. Architecture does not follow.**
