# The Identifier Minting Process — Domain Discovery

| | |
|---|---|
| **Kind** | **DOMAIN DISCOVERY.** ***Describes an existing business process. Creates no aggregate, no automation, no governance, no hook.*** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | PA, 2026-08-02 — *"discover the domain process by which governed identifiers become authoritative… think in commands, decisions, policies, events, invariants, lifecycle — not files"* |
| **Placement** | ⭐ **DERIVED** — resolver → `docs/pks` |
| **Method** | states, events and orderings admitted **only on repository evidence**. Where evidence is absent, that absence is recorded rather than filled |
| **Status** | ⛔ **FROZEN — PA, 2026-08-02, at Part III rev 2.** *“New evidence is now more valuable than new wording.”* **No further refinement.** ⭐ **The next change must be driven by a capability CHANGING AN ENGINEERING DECISION — not by an execution, and not by analysis.** *Execution demonstrates correctness; a changed decision demonstrates value* |

---

## 0. The hypothesis, tested

**Commissioned question: is there ONE ubiquitous identifier-minting process, or several bounded ones?**

> ### **Answer: SEVERAL — and the minting surface is far narrower than the repository suggests.**

**Most governed artifacts mint no identifier at all.**

| Artifact family | Naming | Participates in this process? |
|---|---|---|
| Verification reports · commissions · plans | **date-based** | ⛔ **NO — they mint nothing** |
| Rulings `R-nn` | register row | ✅ yes |
| Methodology candidates `PMR-nn` | register row | ✅ yes |
| ADRs `ADR-T/MP/PL/UL-nn` | filename + log row | ✅ yes |
| Standards `ES-00n` | filename | ⚠️ **arguably — no minting event has ever been observed** |

**Derived: the date-named families are the majority of governed output, and they are outside this process entirely.** *Any claim about "identifier discipline in the repository" therefore applies to a minority of artifacts.*

---

## 1. Identifier lifecycle(s) — per series, not unified

**⛔ The commission's example lifecycle is NOT confirmed.** Two of its states have no repository evidence, and one pair is a single event.

### 1.1 `R` — rulings

```
(proposed in a package/commission — TEXT ONLY, no repository state)
        ↓
    ⚠️ RESERVED   ← n=1, prose in one report. NOT a repository concept (§5.2)
        ↓
      MINTED  ═══  RECORDED        ⭐ ONE EVENT, not two (§2.2)
        ↓
    REFERENCED
        ↓
    ANNOTATED (optional)           ← R-53, R-62. Never amended, never retired
```

| Claimed state | Evidence |
|---|---|
| Candidate · Validated | ⛔ **none.** No artifact records either |
| Reserved | ⚠️ **n=1**, prose only |
| Minted · Recorded | ✅ **the same act** — appending the register row |
| Superseded · Retired | ⚠️ **NO SUCH STATE HAS YET BEEN OBSERVED** *(wording corrected per PA, 2026-08-02 — the evidence shows absence of occurrence, **not** that retirement is impossible)*. *(`"R-44 superseded"` refers to R-44 superseding a **mechanism**, not to R-44 being superseded)* |

> ### ⭐ **No `R` identifier has been observed to change after minting.** Corrections are **annotations** (R-53's forward-pointer, R-62's type corrections); no decision text has been observed rewritten.
>
> *Immutability has a **rule** basis — PMR-10's adoption note, "identifier stability forbids renaming" — but the repository evidence establishes only **non-occurrence**, which is the weaker and correct claim here.*

### 1.2 `PMR` — methodology candidates

**Does not follow R's lifecycle.** It follows the **already-governed Axis A admission ladder**:

```
raised  →  routed to MCA-class assessment  →  ADOPTED | DEFERRED | DECLINED | WITHDRAWN
```

*Observed instances: PMR-10 **adopted** · PMR-6 **declined at n=0** · PMR-8 **deferred** · PMR-2/3/7 **deferred**.*

⭐ **The identifier is minted at RAISING, before any decision.** *The opposite of `R`, where minting IS the decision record.*

### 1.3 `ADR` — architectural decisions

```
Proposed  →  Accepted  →  Superseded (observed: ADR-T17 → ADR-T23)
```

*Observed statuses: `Accepted` ×7 · one `Living index`.* **Supersession is real here and absent in `R`.**
⚠️ **Four sub-series** (`ADR-T` · `ADR-MP` · `ADR-PL` · `ADR-UL`) plus bare `ADR-001..008` — **collapsed by the current identity model** (M4's *bare-ADR unregistered series* liability).

### 1.4 `ES` — standards

```
PROPOSED  →  (ratified)
```

⛔ **All six are `PROPOSED`. The second transition has never occurred.** *A lifecycle with one observed state.*

> ### **Derived: four series, four lifecycles. They are NOT variations of one process — they differ on when the identifier is minted relative to the decision.**

| Series | Identifier minted… |
|---|---|
| `R` | **at the decision** (the row *is* the ruling) |
| `PMR` | **before the decision** (raised, then disposed) |
| `ADR` | **at authoring**, decided later by a Status field |
| `ES` | **at authoring**; no decision has followed |

---

## 2. Domain events

### 2.1 Classified

| Event | Kind | Observable? |
|---|---|---|
| an identifier is **proposed** in a draft | *intention, not an event* | ⛔ text only |
| an identifier is **reserved** | ⚠️ **practice, not a repository event** | n=1, prose |
| an identifier is **validated** | **command** *(CAP-001)* | ✅ but **never yet invoked at a minting** |
| an identifier is **minted** | ⭐ **repository mutation + decision, simultaneously** | ✅ the register row |
| the register is **updated** | ⭐ **the same event as minting** | ✅ |
| the artifact is **committed** | repository mutation | ✅ |
| the identifier is **first referenced** | observable outcome | ✅ by citation |

### 2.2 ⭐ Minting and recording are ONE event

**The commission's example separates `Minted` from `Recorded`. The evidence does not.**

**For `R`, appending the register row simultaneously (a) allocates the identifier, (b) records the decision, and (c) makes it authoritative.** *There is no intermediate state in which an `R` identifier is allocated but its ruling unrecorded.*

> ### **Consequence: there is exactly ONE observable transition in the `R` lifecycle, and it is atomic.**

---

## 3. Consistency rules — the boundary a future aggregate would protect

⛔ **No aggregate is created here.** These are the invariants such an aggregate would defend:

| # | Invariant | Evidence |
|---|---|---|
| **C-1** | **Uniqueness within a register(ns)** — never globally | AP-4: *collisions are prevented by register discipline, **not by a central authority*** |
| **C-2** | **Immutability after minting** — an identifier is never renamed or reused | M4 · PMR-10's adoption note: *existing collisions **cannot be cured*** |
| **C-3** | **Append-only history** — corrections are annotations, never rewrites | R-53 · R-62 · AIP-11 |
| **C-4** | ⚠️ **The boundary SPANS TWO FILES for `R`** — R-1..R-29 sealed in one, R-30+ in another | the register's own statement |
| **C-5** | **No reservation ownership exists** — nothing records who holds a reserved identifier, or for how long | §5.2 |

> ### ⚠️ **C-4 is the finding most consequential for any future aggregate: the consistency boundary is NOT one file.** *An aggregate assuming a single register document would be wrong on the largest series.*

---

## 4. Natural execution point for CAP-001 — evidence-based

| Candidate point | Attachable to an observable transition? | Verdict |
|---|---|---|
| before drafting | ⛔ no transition exists | **not addressable** |
| **before reservation** | ⛔ **reservation is not a repository concept (§5.2)** | ⛔ **NOTHING TO ATTACH TO** |
| ⭐ **before the register append** | ✅ **the only observable transition** | ✅ **the only evidence-supported point** |
| before commit | ✅ but the row is already written | later than the rule requires |
| at review / merge | ✅ but the identifier is already circulating | latest possible |

> ### ⭐ **This CORRECTS the previous workflow assessment.**
> It recommended *"weigh reservation-time alongside mint-time."* **Reservation is not a repository concept, so there is nothing for a check to attach to.**
>
> ***The reservation hazard cannot be closed by placing a check earlier. Closing it would require making reservation a repository concept — which is a GOVERNANCE change, not an integration choice.*** ⛔ **Not recommended here; recorded as OQ-3.**

**And per §1.4, the point differs by series:** for `PMR` the identifier is minted *at raising*, so a check belongs there; for `R` it belongs at the register append. **One integration point cannot serve both.**

---

## 5. Repository consistency — facts, practice and policy separated

### 5.1 No observable ordering exists

**The register row, the record, `CONTEXT.md`, the plan and the session log land in a SINGLE commit** *(verified on `c19ece6ad`: 6 files, one commit)*.

> ### **Git history therefore establishes NO ordering between artifact creation and register update. The mutation is atomic in the record.**

### 5.2 ⚠️ Reservation is practice, not a repository concept

| Layer | Status |
|---|---|
| **Repository fact** | ⛔ **no reservation record, field, column or artifact type exists.** One prose sentence, in one verification report, about `R-67` |
| **Engineering practice** | ✅ **real** — an identifier was reserved, the reservation lapsed, and the lapse was recorded in prose |
| **Governance policy** | ⛔ **silent.** PMR-10 governs *minting*; no rule governs reservation, its ownership, or its expiry |

> ⚠️ **Method note, recorded because it nearly reversed this finding:** a first count showed `reserved` appearing 4× in the rulings register and 3× in the PMR register. **Every hit was the substring inside "preserved".** *The corrected count of genuine reservations in either register is **zero**.*

---

## 6. Process variations — confirmed

**⛔ There is no ubiquitous process.** §1 establishes four lifecycles differing on the load-bearing question — *when is the identifier minted relative to the decision?* — plus a majority of artifact families that mint nothing at all.

---

## 7. Open questions *(none resolved here)*

| # | Question | Owner |
|---|---|---|
| **OQ-1** | Is **identifier state** a third axis, orthogonal to the two governed axes — Axis A *control admission* and Axis B *artifact lifecycle*? *`R-67` moved between identifier states while belonging to no artifact, which suggests yes* | **Authority** |
| **OQ-2** | Should the four `ADR` sub-series be modelled as four register(ns) or one? *(M4's bare-ADR liability)* | **Authority** |
| **OQ-3** | ⭐ **Should reservation become a repository concept?** *Today the hazard is real and unaddressable — a check cannot attach to a practice* | **Authority** |
| **OQ-4** | Do `ES` identifiers participate in this process at all? *No minting event has ever been observed* | **Authority** |
| **OQ-5** | Is `C-4` (a consistency boundary spanning two files) acceptable, or should the `R` register be unified? *Unification would be a change-control act* | **Authority** |

---

*Traceability: PA domain-discovery commission 2026-08-02 · **the "one process" hypothesis is REFUTED — four lifecycles, plus a majority of artifact families that mint no identifier** · `Minted` and `Recorded` shown to be ONE event · `Candidate`, `Validated`, `Superseded` and `Retired` found to have NO evidence in the `R` lifecycle · reservation classified as **practice, not repository concept**, with the substring false positive that nearly reversed it disclosed · **§4 CORRECTS the previous assessment's reservation-time recommendation** · five consistency rules identified, **no aggregate created** · five open questions raised, none resolved · ⛔ **no automation, no hook, no governance, no capability change.***

> **⛔ Discovery only. CAP-001 remains frozen except for bug fixes.**

---

# Part II — Engineering Transactions *(Steps 8–10, appended 2026-08-02)*

> **Method shift, per the PA: stop modelling STATES, model TRANSACTIONS.**
> *The consistency boundary in an engineering process is usually enforced at the transaction,
> not at an abstract lifecycle stage.* **Every claim below is from commit evidence.**

## 8. Observable engineering transactions

| # | Question | Answer from the commit log |
|---|---|---|
| **1** | What causes a new identifier to exist? | ⚠️ **TWO independent causes** — a **register row** is appended, *or* the identifier is **cited in a committed document**. §8.2 |
| **2** | What repository objects change together? | register row · the record/report · `CONTEXT.md` · the work plan · the session log. **4–7 files** |
| **3** | Does one commit represent one engineering transaction? | ✅ **YES — 4 of 4 ruling commits touch the register exactly once**, bundled with their supporting artifacts |
| **4** | Can an identifier exist before the register records it? | ⭐ **YES — demonstrated.** §8.2 |
| **5** | Is reservation observable in repository state? | ⛔ **NO.** Practice only *(§5.2; the count of genuine reservations in either register is **zero**)* |
| **6** | Is retirement observable in repository state? | ⛔ **NOT OBSERVED.** No instance found; this is absence of occurrence, not proof of impossibility |

**Evidence for #3 — the transaction is consistently shaped:**

```
c19ece6ad  register×1 + 5 others   R-68 subdivides WP-4; R-69 accepts WP-4A
828cf88d8  register×1 + 4 others   R-67 — WP-3A ACCEPTED
96eda6a4b  register×1 + 6 others   R-66 — Slice 7C accepted
ffe895f9f  register×1 + 3 others   R-61..R-64 dispose of the validation
```

⭐ **One commit = one engineering transaction. The register is never touched alone**, and a single transaction may mint **more than one** identifier (`c19ece6ad` mints two).

### 8.2 ⭐ The decisive transaction finding

**`R-70` is cited in `2026-08-01-classification-model-approval-record.md`, which is committed (`70ba504a6`).**
**No commit has ever added `R-70` to the register** — `git log -S "R-70" -- <register>` returns nothing.

> ### ⛔ **An identifier can exist in committed repository state without the register ever recording it.**
>
> **So the register is NOT the sole source of an identifier's existence.** *There are two entry paths — **citation** and **registration** — and they are unordered.*
>
> ***This is the structural reason the R-65..R-71 problem was possible. It is not a discipline failure; it is a property of the process.***

## 9. Process invariants — tested, not assumed

| Candidate invariant | Verdict | Evidence |
|---|---|---|
| **every minted identifier has exactly one register entry** | ✅ **HOLDS** | `grep ^\| R-nn \| sort \| uniq -d` → **empty across the whole register** |
| **minting and recording occur atomically** | ✅ **HOLDS** | 4/4 commits bundle the row with its record |
| **identifiers do not change after commit** | ✅ **not observed to fail** | no rename observed; rule basis in PMR-10 |
| **one transaction mints one identifier** | ⛔ **REFUTED** | `c19ece6ad` mints **R-68 and R-69** together |
| ⭐ **an identifier does not exist until registered** | ⛔ **REFUTED** | §8.2 — `R-70` exists in committed state, unregistered |

> ### **The process protects ONE invariant reliably: *one register row per identifier*. It does NOT protect *one meaning per identifier*.**
>
> **`R-68` satisfies the first and violates the second** — one row in the register, two meanings across the corpus.

## 10. Where CAP-001 belongs — the transaction test

**The commissioned question: *which engineering transaction would become inconsistent if CAP-001 were omitted?***

**Applying it to the R-68/R-69 evidence:**

| | |
|---|---|
| Did the **register-append transaction** become inconsistent? | ⛔ **NO.** The register is internally perfect — one row, no duplicate, correctly formed |
| Did **anything** become inconsistent? | ✅ **YES** — the relationship between the **register** and the **citing corpus** |

> ### ⭐⭐ **THE ANSWER: the transaction CAP-001 protects DOES NOT CURRENTLY EXIST.**
>
> **CAP-001's consistency boundary is `register ∪ corpus`. No commit, act, gate or review spans that boundary.**
>
> ⚠️ **ANNOTATED by Part III (rev 2):** the boundary is restated there as a **lower bound** — *at least* the register and the corpus. The set is **not closed**; reservation records and future projections may also lie inside it. The register-append transaction is the *closest* thing — but it is **strictly smaller** than what CAP-001 protects, which is why omitting CAP-001 leaves that transaction perfectly consistent while the corpus fractures.

**Three consequences, each following from the evidence rather than preference:**

| # | Consequence |
|---|---|
| **1** | ⭐ **This explains the adoption failure mechanically.** `R-68`/`R-69` were minted without the check **not because a step was forgotten, but because no transaction boundary owns the check.** *A step that belongs to no transaction has nothing to remind anyone of it* |
| **2** | **The implementation already embodies the correct boundary — accidentally.** `MarkdownSeriesContentsReader` reads **minted from the register** and **cited from a corpus scan**. *It was built to answer a verdict; it turns out to span exactly the boundary the process lacks* |
| **3** | ⚠️ **Attaching CAP-001 to the register append is therefore a COMPROMISE, not the natural home.** It is the only observable transition available, and it catches the collision — **but it cannot catch the citation hazard, because citation is a different, earlier, unordered entry path** |

**⛔ What is NOT recommended here:** creating the missing transaction. *That would mean making citation-or-reservation a governed repository event — a **governance** change with a real cost, and the evidence for it is two incidents in one day.* **Recorded as OQ-3/OQ-6, owned by the Authority.**

## 11. Open questions added by Part II

| # | Question | Owner |
|---|---|---|
| **OQ-6** | ⭐ **Should a transaction spanning `register ∪ corpus` exist at all?** *Today CAP-001 protects a boundary no engineering act owns.* ⚠️ **Part III reframes this: the “transaction” is the wrong unit — the question is whether any COMMAND can own an invariant carried by unordered PROJECTIONS** | **Authority** |
| **OQ-7** | Is *one meaning per identifier* a governed invariant, or only an expectation? *The process demonstrably does not protect it* | **Authority** |

---

*Part II traceability — ⛔ **PARTIALLY SUPERSEDED BY PART III:** its central measurement, **one commit = one engineering transaction**, was **FALSIFIED** by wider sampling (7 rows in one commit; two governance categories in one commit). *The evidence Part II gathered stands; the transaction framing does not.* Read Part II for its findings, Part III for the model. · Steps 8–10 executed against the commit log · ~~**one commit = one engineering transaction (4/4)**~~ · **two invariants REFUTED by evidence**, including *"an identifier does not exist until registered"* · **§10 answers the transaction test: the boundary CAP-001 protects is `register ∪ corpus`, and no transaction owns it** · the adoption failure is explained structurally rather than behaviourally · ⛔ **no transaction created, no automation proposed, no governance authored.***

---

# Part III — The Consistency Boundary *(Steps 1–6, appended 2026-08-02)*

> **Method correction, per the PA — and it corrects Part II rather than extending it.**
> **A Git commit is EVIDENCE, not the domain model.** Part II reasoned about "engineering transactions"
> while measuring commits. *PMR-10 is an invariant — "an identifier must be checked for collision
> before it is minted" — not a Git workflow rule.*

## 1. Step 1 · The engineering act is not the commit — the decisive test

**The commissioned question: *if the same repository changes were split across two commits, would PMR-10 still be satisfied?***

> ### ✅ **YES — and that settles it.**
>
> PMR-10 requires that a **check occur before an identifier is minted**. Whether the resulting text
> lands in one commit or five has no bearing on whether the check happened.
>
> ### ⛔ **The commit is therefore NOT the consistency boundary.**

⚠️ **“Engineering Act” is THE CURRENT EXPLANATORY MODEL — not adopted vocabulary.**
*It is the best available account of what is happening, and it is expected to be superseded once
several capabilities exist (see the **Deferred refinement** at the end). ⛔ Nothing downstream should
treat `EA-n` as a stable term, and no governed artifact should adopt it.*

**Observed engineering acts, stated without reference to Git.**
⭐ **They are NOT all the same kind of thing — commands change state, projections expose it.**

### Commands *(change domain state)*

| # | Engineering act | Who performs it | What it changes |
|---|---|---|---|
| **EA-1** | ⭐ **An Authority binds an identifier to a subject** | ARB / Decision Authority | ⭐ **the denotation itself** — this is the act PMR-10 constrains |
| **EA-4** | ⚠️ An identifier is **reserved** ahead of a decision | a commission author | a *claim* on an identifier, ahead of any binding |
| **EA-5** | An existing entry is **annotated** | the operator | status, cross-reference, correction — ⛔ **never the denotation** *(§3, D)* |

### Projections *(expose existing state)*

| # | Representation | Carrier | Note |
|---|---|---|---|
| **EA-2** | the binding **recorded** in a register | a register row | the *primary* projection, and still only a projection |
| **EA-3** | the identifier **cited** in the corpus | prose in any governed document | ⚠️ an **unordered** projection — no act sequences it |
| — | generated documentation, indexes, graphs | *(none exist today)* | listed so future work does not mistake them for commands |

> ### ⭐ **This distinction is the model's load-bearing structure, not presentation.**
> **A command can occur with no projection** — `R-70` was bound and never registered *(§6, obs. 3)*.
> **A projection can be edited without a command** — annotation *(§6, obs. 4)*.
> ⛔ **Treating every artifact as equal is exactly the error that produced Part II's commit model.**
> *When CAP-002+ appear, they will protect commands and read projections — never the reverse.*

## 2. Step 4 · The commit hypothesis, FALSIFIED

| Falsification probe | Result |
|---|---|
| commits carrying **multiple independent identifier decisions** | ⭐ **FOUND — repeatedly.** `ffe895f9f` **7 rows** · `98b48f7ca` 3 · `c19ece6ad` 2 · `efd19e7c4` 2 · `216bc6e93` 2 |
| are those decisions genuinely **independent**? | ⭐ **YES, provably** — see below |
| merge / squash commits touching the register | ⛔ none — history is linear |
| a register row landing **without** its record | ⛔ none observed *(8/8 row-adding commits touch 3–9 other files)* |

> ### ⭐⭐ **The strongest refutation comes from the programme's OWN rule.**
>
> *"Every governance transition belongs to exactly ONE governance category… one that appears to
> belong to two **IS two transitions, requiring two authority acts**."*
>
> `c19ece6ad` carries **R-68 — Architecture Governance · Approval** and
> **R-69 — Delivery Governance · Acceptance**.
> **By the programme's own invariant that is TWO authority acts inside ONE commit.**

**And the reverse direction fails too:** `ffe895f9f` touched **four files**, added or amended **seven
rows** (R-43, R-48, R-50, R-61, R-62, R-63, R-64) and carried **one** governing artifact
(`2026-08-01-governance-validation-disposition-record.md`).

## 3. Step 3 · Candidate invariants, tested independently

| # | Candidate | Verdict | Evidence | Confidence |
|---|---|---|---|---|
| **A** | every minted identifier has **exactly one register entry** | ✅ **HOLDS** | `uniq -d` over every register row → **empty** | **High** — exhaustive over the register |
| **B** | every register entry corresponds to **exactly one governing artifact** | ⛔ **REFUTED** | `ffe895f9f`: **one** artifact → **seven** rows | **High** — one decisive counterexample suffices |
| **C** | register update and governing artifact become authoritative **together** | ⚠️ **PRACTICE, NOT INVARIANT** | observed 8/8 — but §1 shows integrity **survives a split**, so co-occurrence is habit | **Medium** |
| **D** | identifiers are **immutable after minting** | ✅ **HOLDS — with a distinction** | ⭐ `ffe895f9f` **amended existing rows R-43, R-48, R-50** (R-62's recording corrections). **The ROW is mutable; the identifier→subject BINDING is not** | **Medium-High** |

> ### ⭐ **D's distinction is load-bearing: immutability attaches to the DENOTATION, not to the row.**
> Rows are annotated by design — R-53's forward-pointer, R-62's type corrections.
> **What may never change is *what the identifier denotes*.**

## 4. Step 2 · The consistency boundary

| Element | Must it stay consistent with the identifier? |
|---|---|
| the **subject** it denotes | ⭐ **YES — this IS the invariant** |
| the **register entry** | ✅ yes — the denotation's primary carrier |
| **citations** across the corpus | ⭐ **YES — and this is where the boundary actually fails** |
| the governing artifact | ⛔ **no** — refuted by B |
| status, metadata, annotations | ⛔ no — mutable by design |

> ### **THE INVARIANT PMR-10 PROTECTS:**
> ### ***An identifier denotes exactly one subject.***
>
> ⚠️ **NOT *"one row per identifier"* — that is invariant A, which holds and is insufficient.**
> **`R-68` satisfies A perfectly and violates the real invariant: one row, two live denotations.**

**Therefore the candidate consistency boundary is:**

```
                  IDENTIFIER  ──denotes──▶  SUBJECT
                          (the thing that must stay true)
            ┌────────────────────┴────────────────────┐
            │                                         │
      REGISTER ENTRY                            ALL CITATIONS
      (one projection)                    (the other projections)
                    … and possibly others not yet observed
```

> ### **Current evidence indicates the protected invariant spans AT LEAST the register and the corpus.**

⚠️ **Stated as a lower bound, deliberately.** Two independent observations force the register *and* the
corpus into scope — Part II §8.2, where **`R-70` exists as a citation with no register entry at all**,
and Part I §1.1, where **EA-4 (reservation) puts an identifier into circulation before any register row
exists.** ⛔ **Neither observation closes the set.** Reservation records, projections, generated views
and traceability graphs may all prove to lie inside it; none has been examined. **"At least" is the
strongest form the present evidence supports.**

## 5. Step 5 · Where CAP-001 belongs

**The commissioned question: *at which engineering act would omission of CAP-001 violate a demonstrated invariant?***

> ### **Immediately before EA-1 — *the Authority binds an identifier to a subject*.**
>
> ⛔ **Not before commit, merge or push.** Those are **carriers of the act, not the act.**

| Why this act and not the register append | |
|---|---|
| the register append is the act's **most common trace**, not the act | one act may produce several rows — `ffe895f9f`: **7** |
| the act can occur **without** any register append | ⭐ **R-70** — bound to a subject in a governance record, never registered |
| the act is exactly what the rule names | *"checked **before it is minted**"* — **minting is the binding**, not the file write |

**⚠️ And the honest scope statement, refined from Part II §10:**

> The invariant CAP-001 protects at EA-2 is **A** *(one register row per identifier)* — **demonstrated,
> and demonstrably held.** The broader invariant — **one denotation per identifier** — has **no single
> engineering act that owns it**, because citation (EA-3) and reservation (EA-4) are unordered
> projections that no command sequences.
>
> ### **CAP-001 intentionally protects one demonstrated invariant. Additional invariants may require additional capabilities.**
>
> ⛔ **This is not a finding of incompleteness.** *Protecting exactly one demonstrated invariant is what
> capability-first organisation is for* — **README §8-C: "CAP-001 stops growing."** Whether the broader
> invariant warrants a capability at all is a question for evidence, **not a gap to be filled.**

## 6. Step 6 · Observation separated from interpretation

| # | **Repository observation** *(survives future change)* | **Architectural interpretation** *(may not)* | Confidence obs / interp |
|---|---|---|---|
| 1 | No `R` identifier appears as more than one register row | The register is the canonical carrier of minted identifiers | **High / Medium** |
| 2 | One commit added seven rows; one governing artifact produced seven | Engineering acts are **finer-grained than commits** | **High / High** |
| 3 | `R-70` is cited in committed state and absent from the register | Citation is a **second, unordered carrier** of identifier existence | **High / Medium-High** |
| 4 | Rows R-43, R-48, R-50 were amended after minting | **Annotation is permitted; denotation is not** | **High / Medium-High** |
| 5 | `R-68` carries two meanings across register and corpus | The process **does not protect denotational uniqueness** | **High / High** |
| 6 | No merge or squash commit touches the register | Git history is currently a faithful but **coarse** record | **High / Low** |
| 7 | Reservation appears once, in prose only | Reservation is **practice, not a repository concept** | **High / Medium** |

> ⚠️ **Interpretation 6 is deliberately marked Low — it is precisely the assumption the PA warned against.**
> If CI or any automation ever updates registers separately, observation 6 stops holding, and **any
> model resting on it breaks.** That is the reason this Part models the act rather than the commit.

## 7. Deliverable summary

| Commissioned item | Finding |
|---|---|
| **1 · Observed engineering acts** | ⭐ **Commands:** **EA-1** bind identifier→subject · **EA-4** reserve · **EA-5** annotate — **Projections:** **EA-2** register row · **EA-3** citation. ⛔ *The two kinds are not interchangeable* |
| **2 · Demonstrated invariants** | **A** one register row per identifier *(High)* · **D** the denotation is immutable once bound *(Medium-High)* |
| **⛔ Refuted** | **B** one entry ↔ one governing artifact · *one commit = one engineering act* · *an identifier does not exist until registered* |
| **3 · Candidate consistency boundary** | ⭐ **the identifier→subject denotation.** Present evidence places **at least** the register and the corpus inside it; ⛔ **the set is not closed** |
| **4 · Evidence** | §2 falsification probes · §3 per-candidate evidence column · §6 observation table |
| **5 · Confidence** | per-row above. **Overall: Medium-High** — *one repository, one operator, one week. The **invariant** is well-evidenced; the **extent of the boundary** is a lower bound inferred from a small number of instances* |
| **Where CAP-001 belongs** | **immediately before EA-1** — protecting **one** demonstrated invariant, by design |

---

*Part III traceability: PA consistency-boundary commission 2026-08-02 · **corrects Part II's conflation of Git commit with engineering act** · the commit hypothesis is **FALSIFIED** by seven-rows-in-one-commit and by two governance categories in one commit — the latter refuted using the programme's **own** one-category-one-transition invariant · **Candidate B REFUTED**, C demoted to practice, D refined to denotation-not-row · **the protected invariant is identified as *an identifier denotes exactly one subject*, which invariant A does not imply** · observation and interpretation separated with per-row confidence · ⛔ **no automation proposed · no hook proposed · CAP-001 not redesigned.***
>
> *Revision 2 — three PA refinements applied, each narrowing a claim to its evidence: **(1)** the boundary is stated as a **lower bound** (*"at least the register and the corpus"*), leaving reservation records, projections and generated views open; **(2)** *"structurally insufficient"* withdrawn in favour of **"intentionally protects one demonstrated invariant"** — protecting exactly one is what capability-first organisation is **for**, not a gap; **(3)** the engineering acts are split into **commands** (EA-1, EA-4, EA-5) and **projections** (EA-2, EA-3), so future capabilities cannot treat every artifact as equal.*

> **⛔ CAP-001 remains frozen except for bug fixes.**


---

## ⏳ Deferred refinement — RAISED, NOT APPLIED

**Recorded so it is not lost. ⛔ Deliberately not implemented — the evidence to justify it does not exist yet.**

| | |
|---|---|
| **Raised by** | PA, 2026-08-02, at the freeze |
| ⚠️ **Status of what it would replace** | **`EA-n` is the current explanatory model, NOT canonical vocabulary.** *This refinement is therefore an expected evolution, not a correction of an error* |
| **The refinement** | Split the `EA-n` acts, which still mix business and technical semantics, into three distinct concepts — **Engineering Commands** (`BindIdentifier`, `ReserveIdentifier`, `AnnotateIdentifier`) → **Engineering Events** (`IdentifierBound`, `IdentifierReserved`, `IdentifierAnnotated`) → **Representations** (`RegisterRow`, `CorpusCitation`, `KnowledgeGraphNode`, `DocumentationProjection`). Then *“EA-2 register row”* becomes **“`RegisterRow` projects `IdentifierBound`”** |
| **Why it is right** | it is closer to event-driven DDD, and it would make later capabilities easier to express |
| ⛔ **Why it is NOT applied today** | **the three concepts are expected to emerge from having several capabilities. There is one.** Introducing the vocabulary now would name a distinction no evidence has yet forced — the precise failure this document was written to correct |
| **What would justify it** | **CAP-002 or CAP-003 existing**, and the command/event/representation separation being needed to express how they relate. *Evidence, not preference* |

> ### ⛔ **Do not apply this refinement as a wording change. It is a modelling change, and it waits on capabilities that do not exist.**

---

## ⭐ What this discovery turned out to be about

**Recorded at the freeze, because it is larger than identifier validation and should not be lost in a document titled after minting.**

| Phase | What was being discovered |
|---|---|
| **Phase II** | **governance** — who decides, by what authority, recorded where |
| **Phase III** | ⭐ **an engineering domain model** — what engineers *do*, and what must remain true when they do it |

> ### **These are not the same thing, and the document stopped modelling files somewhere in Part III.**

**The abstraction ladder that emerged — read downward, and note that the top level was discovered FIRST:**

```
      DOMAIN INVARIANT      an identifier denotes exactly one subject
              ↓
      ENGINEERING ACTS      bind · reserve · annotate  │  register row · citation
      (provisional vocabulary)
              ↓
      CAPABILITIES          CAP-001 — one invariant, by design
              ↓
      IMPLEMENTATIONS       validator · register · CLI
              ↓
      REPOSITORY ARTIFACTS  markdown, YAML, commits
```

⛔ **The order matters more than the ladder.** *The invariant was found before any mechanism was chosen — which is why validator, register, hook, Git and CI are all still interchangeable. Part II inverted this, reasoning upward from commits, and reached a model that seven-rows-in-one-commit destroyed.*
