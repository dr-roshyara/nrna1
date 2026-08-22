# KnowledgeOS — EP-01 Q-B TERMINOLOGY REVIEW — is `declared insufficiency` the correct parent for both `NO_FILLER` and `FILLER_UNKNOWN`? (2026-08-23)

> **Source:** Human Principal Architect (HPA), 2026-08-22 — **message 7** (recorded verbatim-in-substance in §0). The HPA reviewed the EP-01 plan, confirmed it is correctly completed and stopped at the right gate, and before approving raised one scrutiny point — Q-B — the parent-term question — and commissioned this **read-only terminology review** of the existing ubiquitous language.
> **Position:** between the EP-01 plan (commit `c5040e4f`) and its approval gate. **This review is advisory — it recommends, decides nothing, and creates no authority.** The HPA decides: approve the plan as written, approve with a rename, approve with a structural split, or revise.
> **Status:** ✅ **DELIVERED · RECOMMENDATION ADOPTED — the HPA ruled **option C** on 2026-08-23** (approve the EP-01 plan with the structural split — see §6). No code · no contract edit · no v1.1 change · no Constitution change. The EP-01 plan is now **APPROVED (planning only)**; implementation (T-2/T-3) awaits a separate explicit HPA act.

---

## 0 · The act (HPA, verbatim-in-substance)

> *"One thing I would scrutinize before approving. There is one genuinely important conceptual question in Q-B: Should `NO_FILLER` really live under the term 'declared insufficiency'?"*
>
> The two semantic acts:
>
> > Provider says: **"I could not determine the filler."** ↓ **`FILLER_UNKNOWN`** ↓ **epistemic insufficiency** — *the mechanism's own limits.*
> > Provider says: **"I determined that this role has no filler in this candidate representation."** ↓ **`NO_FILLER`** ↓ **determinate semantic observation** — *a claim about the candidate meaning.*
>
> *"Calling both insufficiency could therefore create exactly the kind of semantic leakage we have been trying to eliminate."*
>
> On the plan's proposed broadening (§5.4 — "what it could not determine **or determined as absent**"): *"That is defensible, but I would not automatically approve that wording."*
>
> *"Before approving the plan, I would ask Claude to perform one very small terminology review, still read-only: Is `declared insufficiency` actually the correct bounded-context term for both `NO_FILLER` and `FILLER_UNKNOWN`, or should the parent concept be renamed to something semantically neutral such as `declared determination` / `declared qualification`, while retaining the AH-1 distinction? This is worth resolving before implementation, because after implementation the vocabulary becomes part of the published language."*
>
> *"If it concludes the terminology is sound, then approve EP-01."*

---

## 1 · The question, made precise

Two value-cases, one proposed parent:

```
declared insufficiency        ← the proposed parent (plan §5.1 · §5.4)
  └── what_is_undetermined    ← the dimension label
        ├── NO_FILLER         "the role is not expressed" — determinate negative
        └── FILLER_UNKNOWN    "a filler may exist; could not determine which" — insufficiency
```

The question is not a naming taste. It is a **DDD ubiquitous-language question**: do the two children have the same *rendering* (same invariant) — and if not, does forcing a shared parent misbind one of them? And: is there a **neutral parent** available that does not collide with the already-reserved language?

---

## 2 · What the existing ubiquitous language already reserves (grounded)

The review swept the authoritative documents (Reference Architecture v1.1 · Constitution v1.0 · Port Contract · decision-support and confirmation instruments · brainstorming/P5 corpus). The vocabulary at and around the port is **dense and deliberate** — every natural parent label is already reserved at some altitude:

| Term | Existing reserved meaning | Where | Altitude |
|---|---|---|---|
| **insufficiency / abstention** | *"the mechanism's statement of what it could not determine"* · *"I did not determine this"* — the **inability-to-determine** act, first-class, → UNKNOWN | Port obligation 3 · Port §4 · ⟨C-5⟩ · Port §Q4 | **Port** (this is the term's home) |
| **determine / determination** | **Domain adjudication:** *"only the KnowledgeOS domain determines the constitutional admissibility"* (P-2) · *"EPISTEMIC STATE determined at the aggregate boundary, by the domain alone"* · mechanism *"translates, never determines identity"* (⟨C-1⟩) — **BUT** ⟨C-5⟩ and obligation 3 also use the verb for the mechanism: *"a mechanism's inability to **determine meaning**"* · *"I did not **determine** this."* The mechanism **may determine meaning**; it **never determines identity or epistemic states** | v1.1 P-2 · ⟨C-1⟩ · ⟨C-5⟩ · Port 3 · Q6 | Domain (reserved) + mechanism-meaning (allowed) |
| **qualification** | The **promotion ladder**: research → pilot → **qualification** → standard (ES-006.1) · *"Evidence qualification requires boundary crossing"* | ES-006.1 · brainstorming | Governance |
| **resolution** | **Conflict/contradiction resolution**: ConflictState **CONFLICTED · RESOLVED** · *"CONFLICTED coexists until governed resolution"* · *"premature resolution"* | v1.1 · Constitution | Domain |
| **absence / ABSENT** | A **domain epistemic state** (§9, one of exactly seven). The mechanism must never emit it — the port renders, never mints states (obligations 1 · 6) | v1.1 §9 · Port obligations 1, 6 | Domain |
| **confidence** | ⟨R-1⟩ — a guarded, measurement-adjacent term | v1.1 ⟨R-1⟩ | Domain |
| **observation** | **Collision/candidate-equality observation** — port Q8 · INV-KOS-IDENTITY-001 | Port Q8 · v1.1 | Port |

**Reading the table:** `declared insufficiency` is not a generic word — it is a **reserved term whose meaning is the inability-to-determine act**. The act it names (obligation 3) is exactly the `FILLER_UNKNOWN` case. `NO_FILLER` is a different act, performed in a different voice (a claim *about the candidate*, not a statement *about the mechanism's limits*), and the language already reserves a different destiny for it: it is **evidence toward the core's ABSENT-direction determination** — the core evaluates it (Q6 · obligation 2), it never maps to UNKNOWN, and the mechanism never emits ABSENT (obligations 1 · 6).

---

## 3 · The decisive analysis — two declarations, two renderings, two concepts

**Different renderings ⇒ different concepts.** This is the DDD rule the whole chain has been applying (a forced parent term misbinds; different invariants ⇒ different concepts; ES-005.4 — never a copy, never a forced same).

| Declaration | What it is | Rendered by | Routes to |
|---|---|---|---|
| `FILLER_UNKNOWN` | A statement about the **mechanism's limits** (epistemic insufficiency) | obligation 3 · ⟨C-5⟩ · INV-KOS-UNKNOWN-001 | **UNKNOWN** |
| `NO_FILLER` | A claim **about the candidate meaning** (determinate negative) | Q6 · §9 ABSENT-direction · obligation 2 | **the core evaluates** — never auto-mapped, never ABSENT emitted |

Binding `NO_FILLER` under the word *insufficiency* attaches it to the inability act — and by association to its UNKNOWN routing. **That is the exact conflation P5 measured**: `unknown_vs_not_expressed`, **110/110** producers failed it; v0.1 collapsed the two at **0.247** until v0.2 separated all four states (gate 14/14). The AH-1 ruling exists *because* of this conflation. The plan's structure, as written, re-creates it at the vocabulary level — under a single label that any future reader must remember to split.

**The dimension label contradicts its own child.** Plan §5.1 names the grouping `what_is_undetermined`. `NO_FILLER` is not undetermined — the mechanism *determined* the role is not expressed. The label is correct for `FILLER_UNKNOWN` and wrong for `NO_FILLER`. That internal contradiction is not cosmetic: it is the plan telling us `NO_FILLER` does not live naturally in that dimension.

**The broaden-the-definition fix does not remove the misbinding.** Plan §5.4 proposes re-scoping *declared insufficiency* to *"what it could not determine **or determined as absent in the candidate**."* This stretches a reserved term until it no longer means what obligation 3 says. The tension is resolved **by definition, not by semantics** — exactly why the HPA declined to auto-approve the wording. A reader who encounters "insufficiency" will still hear the inability act; the clause "or determined as absent" asks them to hold two incompatible acts under one word.

**Conclusion of the analysis:** `declared insufficiency` is the **correct term for `FILLER_UNKNOWN`** and the **wrong parent for `NO_FILLER`**. The HPA's instinct was right.

---

## 4 · Options

### A — Keep `declared insufficiency` as the umbrella, broaden the definition (current plan §5.4)
- **Assessment: rejected as the recommendation.** Leakage persists by definition — the reserved term keeps naming the inability act; the stretch does not change what the word carries. The dimension label `what_is_undetermined` remains wrong for `NO_FILLER`.
- The HPA's own words apply: defensible, **not** automatically approved.

### B — Rename the umbrella to a semantically neutral parent (the HPA's suggestion)
- **`declared determination`** — **viable, and the best of the candidates.** Grounding: the mechanism *already* "determines meaning" (⟨C-5⟩: *"a mechanism's inability to determine meaning"*; obligation 3: *"I did not determine this"*). The reservation on "determine" is narrow — the mechanism never determines **identity** (⟨C-1⟩) and never determines **epistemic states** (the aggregate boundary does). A port term "the mechanism's declaration of what it determined / could not determine about the candidate's roles" stays cleanly in the ⟨C-5⟩ lane if the definition explicitly excludes identity and epistemic-state determination. The dimension label must change too (e.g. **determination status**: determined / undetermined).
- **`declared qualification`** — soft collision. "Qualification" is reserved at governance altitude (ES-006.1 promotion ladder · "evidence qualification"). A port term would share the word with a governed ladder. Avoidable.
- **`declared resolution`** — **reject.** "Resolution" is reserved for conflict resolution (ConflictState CONFLICTED·RESOLVED · "governed resolution"). A port term would collide with constitutional vocabulary.
- **Net assessment of B:** the rename is **workable** (`declared determination`), but the umbrella now sits over **heterogeneous children** — a content claim (`NO_FILLER`, about the candidate) and an epistemic admission (`FILLER_UNKNOWN`, about the mechanism). The shared label is honest only if it is understood as "declaration of determination status," which the children already say. The umbrella buys little beyond a container the ruling's wording assumed.

### C — Two sibling port terms, no forced umbrella (recommended)
```
declared insufficiency       the mechanism's statement of what it could not determine
  └── FILLER_UNKNOWN          "a filler may exist; could not determine which"
        └── reason            PARSE_UNAVAILABLE | READING_UNDERDETERMINED   (AH-3)
                                   ↓
                             UNKNOWN (⟨C-5⟩ · obligation 3) — unchanged from today

declared determination        the mechanism's statement of what it determined
  └── NO_FILLER               "the role is not expressed in this candidate representation"
                                   ↓
                             evidence toward the core's ABSENT-direction — the core
                             evaluates (Q6 · obligation 2); never auto-mapped to
                             ABSENT; the mechanism never emits ABSENT (obligations 1 · 6)
```
- **The AH-1 distinction is preserved — it becomes two terms instead of two values under one label.** The port's core purpose (honest, non-conflating declarations) is served **structurally**: no reader must remember a re-scope; the distinction is visible in the vocabulary itself.
- **No new collision.** `declared insufficiency` keeps its exact reserved meaning; `declared determination` sits in the already-allowed ⟨C-5⟩ lane (mechanism determines meaning, never identity/state).
- **Obligation 3's text is untouched** — it stays about the inability act, which is exactly `FILLER_UNKNOWN`.
- **Extensibility is preserved and clearer:** future *determinate* declarations (a settled negative beyond fillers) grow under `declared determination`; future *insufficiency* reasons grow under `declared insufficiency`.
- **Honest consequence to surface:** this **re-reads the AH-1 ruling's container framing** — the ruling says "declared insufficiency distinguishes 'no filler' from 'filler unknown'"; the split makes the *port* distinguish them via **two sibling terms**, dissolving the umbrella the ruling's wording assumed. The distinction itself is fully preserved. That framing adjustment is **the HPA's to authorize** — the review only surfaces it.

---

## 5 · Recommendation

**Primary: C — two sibling terms (`declared insufficiency` · `declared determination`), no forced umbrella.**

It is the most DDD-faithful outcome: two renderings ⇒ two concepts (the rule this chain has applied throughout); the port's honesty purpose is served structurally rather than by a remembered re-scope; obligation 3 stays untouched; no new collision; and it dissolves the plan's internal label contradiction (`what_is_undetermined` cannot contain `NO_FILLER`).

**Alternative: B — rename the umbrella to `declared determination`** (with a re-labelled dimension, e.g. determination status), if the HPA prefers to keep one structured component as the ruling framed it. It is viable on the ⟨C-5⟩ grounding, but the umbrella then covers heterogeneous children and adds little beyond the label.

**Explicitly not recommended: A** — keep-and-broaden. The HPA's instinct was correct; the stretch recreates the leakage by definition.

**Effect on the plan (still planning, read-only — nothing is edited in this act):**

- **If C is adopted:** plan §5.1 drops the `what_is_undetermined` dimension → two sibling terms; §5.4 loses the "or determined as absent" re-scope (the tension disappears); §5.6 sites adjust (obligation-3 pointer unchanged · §3 Q2/Q4 wording: `NO_FILLER` is not an abstention · §4 table: insufficiency row unchanged, `NO_FILLER` gains a sibling row, `reason` attaches to insufficiency only · §6 OQ-1 resolution record · §7 gates unchanged). Composition closure, routing, anti-laundering guard, gates G-1…G-8, evidence, and every boundary are **unchanged** — they are value-case-level, not parent-label-level.
- **If B is adopted:** parent + dimension labels only; value-cases, gates, routing, boundaries unchanged.
- **If the HPA concludes the terminology is sound as written (A):** approve EP-01 exactly as committed — this review does not force a change.

---

## 6 · The decision (HPA) — ✅ **C — APPROVE WITH THE SPLIT** (2026-08-23)

> **HPA Decision — Q-B: Choose C** (verbatim-in-substance):
>
> *"Approve the EP-01 plan with the structural split recommended by the terminology review:
> * `declared insufficiency` is reserved for `FILLER_UNKNOWN` and its `reason` dimension (`PARSE_UNAVAILABLE` / `READING_UNDERDETERMINED`).
> * `declared determination` is the sibling port-level term for `NO_FILLER`.
> * Do not use `declared insufficiency` as an umbrella for both.
> * Do not broaden the existing meaning of `declared insufficiency`.
>
> The rationale is DDD/ubiquitous-language integrity: `FILLER_UNKNOWN` represents provider inability, whereas `NO_FILLER` represents a provider determination about the candidate representation. Their downstream routing and semantic responsibility differ and therefore they must remain distinct published-language concepts.
>
> Record this as an HPA decision and update the EP-01 plan accordingly, but do not implement T-2/T-3 yet. Approval of the revised plan is approval of the planning act only. Stop again and await a separate explicit implementation authorization.
>
> Preserve all existing boundaries: no v1.1 change, no Constitution change, no aggregate change, no Kernel implementation, no SNF implementation, no corpus/research reopening, no OQ-4."*

**The HPA also affirmed the review's key insight:** *"the two declarations belong to different semantic categories"* — `FILLER_UNKNOWN` = the provider **cannot determine** the filler → epistemic insufficiency → UNKNOWN; `NO_FILLER` = the provider **determined** the candidate representation has no filler → a determinate negative the core evaluates — and **"option C is the strongest DDD decision."** The routing is preserved:

```text
FILLER_UNKNOWN → UNKNOWN → core evaluates
NO_FILLER      → determinate candidate-side statement → core evaluates → may contribute to ABSENT
Neither mechanism gets to emit an epistemic state or identity.
```

**Consequence:** the EP-01 plan is **approved** in the option-C form (§5.1 two sibling declarations · §5.4 no broadening · §5.6 sites · Q-B closed). Approval is **planning-only**: the T-2/T-3 implementation slice remains a **further, separate, explicit HPA act**. The sequence stands: `P5 closed → AH decisions → EP-01 planning → Q-B resolved → revised EP-01 plan → HPA approval → separate implementation authorization → G-1…G-8 → EP-02 → v1.1 reassessment`. **The Kernel still waits.**

---

## Traceability

- **Commission:** HPA, 2026-08-22 (**message 7**) — reviewed the EP-01 plan (commit `c5040e4f`), confirmed it correctly completed and stopped at the right gate, raised Q-B (the `declared insufficiency` parent question), declined to auto-approve the §5.4 broadening, and commissioned this read-only terminology review. *"If it concludes the terminology is sound, then approve EP-01."* Recorded verbatim-in-substance (§0).
- **Grounding inspected (read-only):** Reference Architecture v1.1 (P-2 · §9 seven states · §10 ⟨A-2⟩ obligations 1, 2, 3, 6 · §14 ⟨C-5⟩ · §15 ⟨C-1⟩/⟨R-1⟩ · ConflictState) · Constitution v1.0 (Article 9 · Contradiction/resolution) · Expression↔Meaning Port Contract (obligation 3 · §4 vocabulary table · §Q4 · §Q6 · §Q8) · P5 competition corpus (`unknown_vs_not_expressed` 110/110 · v0.1 0.247 collapse · v0.2 four-way 0.45–1.00 gate 14/14) · ES-006.1 promotion ladder · AH-1…AH-5 decision record / confirmation (commits `29e28d05` · `1d6a5ea9`).
- **HPA decision (2026-08-23):** **option C — approve the EP-01 plan with the structural split** (verbatim-in-substance in §6) · *"the two declarations belong to different semantic categories"* · *"option C is the strongest DDD decision"* · routing preserved (FILLER_UNKNOWN → UNKNOWN → core evaluates; NO_FILLER → determinate candidate-side statement → core evaluates → may contribute to ABSENT; neither mechanism emits a state or identity) · boundaries preserved (no v1.1 · no Constitution · no aggregate · no Kernel · no SNF · no corpus/research reopening · no OQ-4).
- **Discipline honored:** read-only — no code, no contract edit, no plan edit, no v1.1 change, no Constitution change · advisory — the review recommends, the HPA decides · the strongest statement never exceeds the evidence (renderings and reservations are cited; P5 is toy-world) · acceptance and act-authorization kept strictly separate — approval of the plan authorizes only planning, not the T-2/T-3 slice · register **25+4 unchanged** · Constitution **FROZEN** · **the Kernel still waits.**
- **HPA decision (2026-08-23):** **option C — approve the EP-01 plan with the structural split** (recorded verbatim-in-substance in §6) · **"option C is the strongest DDD decision."** The terminology review's recommendation was **adopted**; the EP-01 plan was revised to the option-C form and is **APPROVED (planning only)**.
- **Status:** ✅ **Q-B TERMINOLOGY REVIEW — DELIVERED · RECOMMENDATION ADOPTED.** Decision field **✅ C (HPA, 2026-08-23)** · EP-01 plan **✅ APPROVED (planning only)** · **implementation (T-2/T-3) NOT authorized** — a separate, explicit HPA act · no further engineering act authorized by this act.
