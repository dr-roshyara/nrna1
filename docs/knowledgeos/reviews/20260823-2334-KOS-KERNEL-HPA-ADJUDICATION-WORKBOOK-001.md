# KOS-KERNEL-HPA-ADJUDICATION-WORKBOOK-001

> **Role:** the **HPA Adjudication Workbook** — structured decision support for the 22 open Kernel questions, classified by architectural altitude, ordered by dependency, with options and consequences for each. **The next actor is the HPA/ARB.**
> **Commission:** HPA, 2026-08-23 — *KOS KERNEL — HPA ADJUDICATION COMMISSION*. Thirteen mandatory fields per item · altitude classes A–H · dependency graph before numerical order · the eleven special tests · fifteen aggregate sections · an HPA DECISION SHEET with the ruling field **left empty**.
> **⛔ THIS WORKBOOK DECIDES NOTHING.** Field 13 recommends only the **phrasing** of a ruling, never its substance. Where an option is more consistent with law, the workbook states the law and lets the consequence speak; it does not select. **No architectural choice is made here.**
> **Status:** 📋 **DECISION SUPPORT ONLY · PROPOSED · NON-AUTHORITATIVE · HPA DECISION REQUIRED.** No code · no implementation architecture · no technology choice · no research · no new aggregate/context/event/state · no DSL/NLP/FST · no brainstorming promoted. Register **25+4** · contexts **6→6** · aggregates **5→5** · members **12→12** · events **10→10** · states **7→7** · invariants **11→11** · Constitution **FROZEN** · SNF **CLOSED** · OQ-4 **UNAUTHORIZED** · AH-5 **deferred** · **implementation ungated · the Kernel is not built.**
> **Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos --maturity=research` → `docs/knowledgeos` (exit 0).

---

## 0 · Correction to the adjudication pack: **22 items, not 21**

The pack states "21 items" three times and "COMPLETENESS (12)" where the completeness thread lists **13**. Verified by counting its own item headings: **22**. The referent map §S.5 is correct with 22 rows; only the headline arithmetic was wrong. Cause: **F-CM-2 was folded into C-16's row** and then mis-added.

**No item was lost** — all 22 were analysed and mapped. **This workbook uses 22 and treats F-CM-2 as a distinguished sub-question of C-16.** The pack's own text stands unrewritten (ES-004.3); this correction is recorded here.

| Thread | Pack said | Actual |
|---|---|---|
| Constitutional adequacy | 5 | **5** |
| Scope | 3 | **3** |
| Completeness | 12 | **13** |
| Consistency | 1 | **1** |
| **Total** | 21 | **22** |

---

## 0.1 ⚖️ The constitutional sufficiency test *(added 2026-08-25, established by the F-CM-1a ruling)*

> **Record a new constitutional distinction only when the existing law is insufficient to prevent materially different architectural interpretations.**
>
> **The absence of an explicit distinction in the Constitution does not automatically justify adding one.**

```
Existing law
    ↓
Is the existing invariant sufficient?
    │
    ├── YES → clarify elsewhere / defer
    │
    └── NO  → constitutional amendment candidate
```

**Effect on this workbook.** The burden for **every amendment-class option** is now **demonstrated insufficiency of existing law** — not the usefulness, elegance or intuitive appeal of the clarification. It applies to: **C-15** (R-1/R-2) · **C-17** (I-1) · **C-18** (V-1) · **C-14** (N-2) · **C-2** (new invariant) · **C-3** (contexts 6→7) · **C-11** (member vestigial) · **C-7** (model change) · **C-6** (justification-over-time) · **Wisdom** (V.3) · **F-CM-1b** (if it reaches law).

**Why the HPA established it:** without this test, every distinction surfaced by analysis attempts the escalation **Entity → VO → State → Event → Invariant → Constitutional Article**, and *"that would destroy the smallest possible Kernel objective."* The Constitution must not become a catalogue of every possible semantic confusion.

**A companion distinction, recorded with it:** **repeated confusion ≠ constitutional gap.** That designers repeatedly misread a point is an architectural observation about **interpretation-drift risk**; it is not evidence that law is defective.

---

## 1 · Altitude classification (A–H)

Per the commission: *never allow a lower-level decision to silently decide a higher-level question.* Items whose altitude is **determined by the answer** are marked with the alternatives — this is not indecision; it is the finding from referent-map §S.5.3.

| ID | Question (short) | Altitude | Note |
|---|---|---|---|
| **F-CM-1a** | record *Ambiguity ≠ Contradiction* as a distinction? | **A** | §15 row |
| **F-CM-1b** | does interpretation-**selection** sit inside or outside? | **B** | separable from F-CM-1a |
| **C-15** | retraction/withdrawal representation | **A** | a state (§9 seven) or an event (⟨A-3⟩ guard) |
| **C-17** | internally impossible claim | **A** | state vocabulary |
| **C-18** | constitutional-version binding | **A → C** | **trigger §16 (A), remedy on the aggregate (C)** |
| **C-14** | `NOT_ASSESSED` representation | **A or F** | a **reclassification**: currently ruled F |
| **C-3/CC-2** | authority **adequacy** | **B or A** | tenth capability (B) or contexts 6→7 (A) |
| **Wisdom** | Wisdom as a core concept | **A** | V.3 |
| **CC-1** | historical diagram disposition | **G** | no architecture |
| **C-1** | capability-list re-typing | **D** | touches ⬜ OPEN F-1…F-5 |
| **C-2** | determinism | **A / E / F** | **three options, three altitudes** |
| **C-4** | targeting an existing `KnowledgeId` | **B** | + Port Contract (⬜ unratified) |
| **C-11** | Confidence derivability | **D → A/C** | escalates if the member is vestigial |
| **C-8** | identity-continuity test | **E** | **law already states the requirement** |
| **C-10** | evidence-reference resolvability | **B or F** | capability scope or Port Contract |
| **C-6** | stale outward reference | **C or A** | reference semantics over time |
| **C-7** | `CONFLICTED` ↔ `ConflictRecord` cardinality | **C** | **D** if only a rule is stated |
| **C-12** | `Relations` referential-integrity test | **E** | checkable internally |
| **C-16** | idempotency | **D** | with **F-CM-2** as sub-question |
| **F-CM-2** | is non-unique identity-of-meaning intended? | **C** | identity semantics |
| **C-5** | supersession coordination locus | **C → F** | DEF-1-deferred like DS-CAND-2 |
| **C-9** | anti-capability register gaps | **D + E** | prohibitions + tests |
| **C-13** | run the reasoning chain on capabilities | **G** | process |
| **C-19** | ecosystem consistency check | **H** | can only clear or falsify |

**Altitude tally:** A = 6 (+3 conditional) · B = 4 (+2 conditional) · C = 4 (+3 conditional) · D = 5 · E = 3 (+1) · F = 1 (+3 conditional) · G = 3 · H = 1.

---

## 2 · Dependency graph

```
                    ROOTS — no unresolved prerequisite
  ┌─────────┬──────────┬─────────┬─────────┬─────────┬─────────┬────────┐
F-CM-1a   C-15      C-17     C-18     C-1    C-3    Wisdom   CC-1 C-13 C-19
  │         │         │        │        │      │       ·       ·    ·    ·
  │         └────┬────┘        │        │      │   (independent of all)
  │              ▼             ▼        │      │
  │           C-7 ◄────────────┘        │      │
  │        (state vocabulary            │      │
  │         changes the                 │      │
  │         member/record rule)         │      │
  │                                     │      │
  ├──────────────► F-CM-1b ◄────────────┼──────┘
  │               (selection            │
  │                placement)           │
  │                   │                 │
  │                   ▼                 ▼
  ├──────────────► C-4 ◄──────────── C-3
  │              (targeting)
  │                   │
  ├──────────────► C-16 ──► F-CM-2
  │              (idempotency)
  │
  └─ C-18 ──► C-2 ──┬──► C-8   (fitness family:
                    ├──► C-12   every E item
                    └──► C-9    presupposes determinism)
                    
        C-1 ──┬──► C-9   (register entries are capability entries)
              ├──► C-11 ──► C-14   (if the member is vestigial,
              ├──► C-16            C-14's representation dissolves)
              └──► C-13

        C-10 ──► C-6   (if references are accepted as-supplied,
                        staleness disposition follows)

        C-5  — independent of all others (a locus record)
```

### 2.1 The three load-bearing dependencies

1. **C-18 → C-2.** If nothing binds an admitted state to the law version that admitted it, determinism across an amendment is unachievable — so C-2 cannot be ruled "a boundary property" without C-18 first, or the property is unverifiable after the first amendment.
2. **C-2 → the whole fitness family (C-8, C-12, C-9, and T-2).** Every fitness test presupposes that identical input yields identical disposition. Ruling C-2 as *not* a required property changes what any of those tests can assert.
3. **C-1 → C-9, C-11, C-16, C-13.** If the capability list is re-typed, entries that are prohibitions rather than capabilities move to the anti-capability register (C-9), and the question *"what may Confidence be computed from"* changes shape depending on whether confidence assignment remains a capability at all.

### 2.2 Explicitly independent

**Wisdom · CC-1 · C-13 · C-19 · C-5** are independent of every other item and of each other. Any of them may be ruled at any time, in any order, without prejudicing another. **C-19 requires no decision to execute** — only to disposition a conflict if one is found.

### 2.3 Blocked — do not resolve prematurely

| Blocked item | Blocked by | Why |
|---|---|---|
| **C-2** | C-18 | determinism is unverifiable across an amendment with no version binding |
| **C-8 · C-12 · C-9** | C-2 | a fitness test's assertion depends on whether determinism is required |
| **C-4** | C-3 · F-CM-1b | *what authorizes* and *may the core compare* both bear on targeting |
| **C-16 · F-CM-2** | F-CM-1b | idempotency requires sameness comparison, whose placement F-CM-1b fixes |
| **C-14** | C-11 | if the member is vestigial, the representation question dissolves |
| **C-7** | C-15 · C-17 | both propose state-vocabulary changes that alter the member/record rule |
| **C-6** | C-10 | staleness disposition follows from whether resolvability is checkable |

---

## 3 · Recommended decision order

Per the commission's priority ladder, with dependencies honoured:

```
WAVE 1 — CONSTITUTIONAL, unblocked
   Wisdom · C-15 · C-17 · F-CM-1a · C-18 · C-14(reclassification only)

WAVE 2 — MEANING OF INVARIANTS / BOUNDARY MEMBERSHIP
   C-3 · F-CM-1b · C-10

WAVE 3 — CAPABILITY PLACEMENT
   C-1 · C-11 · then C-4 (needs C-3 + F-CM-1b) · then C-16/F-CM-2

WAVE 4 — DOMAIN MODEL
   C-7 (needs C-15, C-17) · C-6 (needs C-10) · C-5

WAVE 5 — FITNESS / ASSURANCE
   C-2 (needs C-18) · then C-8 · C-12 · C-9 (need C-1, C-2)

WAVE 6 — DOCUMENTATION / PROCESS / CONSISTENCY  (any time)
   CC-1 · C-13 · C-19
```

**Wave 1 has the highest leverage:** six rulings, all unblocked, and four of them (Wisdom, C-15, C-17, F-CM-1a) close constitutional questions that otherwise sit under every later ruling.

---

## 4 · Item analyses

> **Field key.** 1 ID · 2 question · 3 altitude · 4 governing law · 5 existing evidence · 6 settled · 7 unresolved · 8 options · 9 consequence · 10 change class · 11 downstream · 12 drift risk · 13 ruling **format**.
> **Change classes:** `NONE` · `MAPPING` · `BOUNDARY` · `DOMAIN-MODEL` · `AMENDMENT` · `DEF-1`.

---

### F-CM-1a · Should *Ambiguity ≠ Contradiction* be recorded as a non-collapse distinction?

| | |
|---|---|
| **Altitude** | **A — CONSTITUTIONAL** (a §15 row) |
| **Governing law** | §15 lists **eleven** non-collapse distinctions; this is not among them. `ambiguity` occurs in v1.1 **twice** (§13 LLM row, OQ-4 corpus) — never as a modelled distinction. INV-KOS-CONTRADICTION-001 governs *conflicting knowledge*. ⟨C-5⟩ + obligation 3 give underdetermination a home: **declared insufficiency → `UNKNOWN`** |
| **Existing evidence** | Capability mapping declared it unanswerable from law and refused to resolve it. Critique **confirmed** by direct search. Corpus **anticipated** it most heavily — five artifacts reach for an `AMBIGUOUS` state (`103255`, `104148`, `104251`, `111647`, `123630`); `105200` §10 supplies the layered alternative. **P5 Established item 7**: *underdetermined rather than resolvable* |
| **Settled** | The distinction is **absent from law** — verified. `UNKNOWN` is the lawful home of mechanism-declared underdetermination (⟨C-5⟩). `AMBIGUOUS` as an eighth state is barred (§9 seven · OBS-2) |
| **Unresolved** | Whether the distinction must be **recorded** in §15 at all |
| **Options** | **O1** record as a §15 row · **O2** rule that ⟨C-5⟩ + obligation 3 already cover it and no row is needed · **O3** defer |
| **Consequence** | **O1** twelve non-collapse rows; future acts can cite it; sets up F-CM-1b · **O2** the distinction is deliberately unexpressed, and every later actor must re-derive that `UNKNOWN` is the whole answer · **O3** F-CM-1b stays blocked |
| **Change class** | O1 **AMENDMENT** (interpretation-class, like ⟨r4⟩) · O2 **NONE** (a ruling of record) · O3 **NONE** |
| **Downstream** | **F-CM-1b · C-16 · C-4 · C-7** |
| **Drift risk** | 🔴 **HIGH** — an implementation that meets two viable interpretations will compare them, which is a sameness determination ⟨C-1⟩ forbids. Only T-2 detects it |
| **Ruling format** | `RECORD AS §15 ROW — interpretation of existing invariants, no new law` · or `REJECT — ⟨C-5⟩ + obligation 3 are sufficient; the absence is deliberate` · or `DEFER` |

---

### F-CM-1b · Does interpretation-**selection** sit inside or outside the boundary?

| | |
|---|---|
| **Altitude** | **B — SCOPE / BOUNDARY** |
| **Governing law** | §16 — the kernel *"cannot generate a conclusion"*. Obligation 4 — a mechanism *"never proposes or derives a KnowledgeId"*. r4-4 — candidate metadata is **port vocabulary, never a member**. Obligation 3 — a mechanism that cannot determine **must declare insufficiency**. ⟨C-1⟩ — similarity never becomes identity |
| **Existing evidence** | Critique: Model B (`CONFLICTED` + ConflictRecord) requires the core to judge two candidates *about the same thing* — forbidden. Model A (external selection) **survives**: the selector produces a candidate, never assigns identity, its selection is metadata never authority, and it must abstain rather than assert |
| **Settled** | A mechanism's selection **cannot cross as authority** (r4-4, obligation 4). The core **cannot compare by meaning** (⟨C-1⟩) |
| **Unresolved** | Whether plural independently-justified meanings may be **admitted separately** with distinct `KnowledgeId`s, and whether that is selection at all |
| **Options** | **O1** selection is **outside**; plural viable meanings admit separately as distinct identities · **O2** selection is **outside**; plural meanings must be resolved to one before submission or declared insufficient · **O3** selection is **inside** (requires reconciling with §16) |
| **Consequence** | **O1** identity-of-meaning is plural by design, reinforcing F-CM-2 · **O2** the port must express "unresolved plurality", which may need contract vocabulary · **O3** the boundary becomes a semantic arbiter; §16 must be amended |
| **Change class** | O1 **MAPPING** (state the rule) · O2 **BOUNDARY** + Port Contract · O3 **AMENDMENT** |
| **Downstream** | **C-4 · C-16 · F-CM-2** |
| **Drift risk** | 🔴 **HIGH** — see F-CM-1a |
| **Ruling format** | `ACCEPT — selection is OUTSIDE the boundary, subject to [O1/O2]` · or `AMENDMENT REQUIRED — §16` |

---

### C-15 · Does retraction/withdrawal require a lawful representation?

| | |
|---|---|
| **Altitude** | **A — CONSTITUTIONAL** |
| **Governing law** | The **ten** events (§8) contain no withdrawal. INV-KOS-HISTORY-001 forbids deletion. `KnowledgeSuperseded` requires a **successor**, which a retraction lacks. ⟨A-3⟩ guards event additions. §9's seven states contain no `WITHDRAWN` |
| **Existing evidence** | Critique: a **law gap, not a mapping gap** — the mapping's eleven acts mirror the ten events faithfully. Corpus **anticipated it four times**: `111647` (`ADMITTED → WITHDRAWN`), `113410` (UNRESOLVED), `123630` (`WITHDRAWN ≠ FALSE`), `123619` (scenario 12) |
| **Settled** | Retraction cannot be deletion (INV-KOS-HISTORY-001) and cannot be supersession (no successor) |
| **Unresolved** | Whether the domain must express *"the author no longer asserts this"* as distinct from false / superseded / rejected |
| **Options** | **O1** add a state · **O2** add an event · **O3** rule that retraction is expressible as a `BeliefRevised` to an existing state · **O4** reject — retraction is outside the domain |
| **Consequence** | **O1** states **7→8**, contradicting OBS-2 · **O2** events **10→11**, requiring an ⟨A-3⟩ exception · **O3** collapses *withdrawn* into an existing state, losing the distinction `123630` flagged · **O4** the domain cannot record that an author retracted, and the record stands as asserted |
| **Change class** | O1/O2 **AMENDMENT** · O3 **MAPPING** · O4 **NONE** (ruling of record) |
| **Downstream** | **C-7** (state vocabulary) |
| **Drift risk** | 🟠 **MEDIUM-HIGH** — the likely default is `REJECTED`, collapsing retraction into failed justification |
| **Ruling format** | `AMENDMENT REQUIRED — [state/event]` · or `REJECT — retraction is not a domain concept; [reason]` · or `ACCEPT — expressible as [O3]` |

---

### C-17 · What state does an internally impossible claim bear?

| | |
|---|---|
| **Altitude** | **A — CONSTITUTIONAL** |
| **Governing law** | `CONFLICTED` is a relation **between** claims (INV-KOS-CONTRADICTION-001 + ConflictRecord references two aggregates). `FALSE` requires *grounds that it is not so*. `REJECTED` requires an absent justification path. §16/A-2 forbid the boundary reading meaning — which is what detecting internal contradiction requires |
| **Existing evidence** | Critique's independent ZERO pass over 15 conditions: *impossible* is the **only** condition with no lawful state. Corpus **SILENT** — it covers contradiction *between* claims and *"contradictory determination"* (the boundary producing inconsistent verdicts), never P ∧ ¬P within one claim |
| **Settled** | The boundary **cannot detect** internal contradiction without reading meaning (forbidden). A self-contradictory claim may arrive with a complete, well-formed justification path |
| **Unresolved** | Whether the domain needs any representation at all |
| **Options** | **O1** add a state · **O2** a port-vocabulary route (the mechanism declares internal inconsistency) · **O3** reject — internal consistency is the mechanism's obligation before submission · **O4** record as an accepted limitation |
| **Consequence** | **O1** states 7→8 (OBS-2) · **O2** Port Contract addition; the determination is mechanism-side and crosses as metadata, never as state · **O3** the boundary admits such claims and no state is wrong — **the defect is invisible in operation** · **O4** same as O3 with the limitation recorded |
| **Change class** | O1 **AMENDMENT** · O2 **BOUNDARY** + Port Contract · O3/O4 **NONE** |
| **Downstream** | **C-7** |
| **Drift risk** | 🟡 **LOW-VISIBILITY / HIGH-CONSEQUENCE** — nothing fails; the claim is simply admitted. **The only item in the set whose drift produces no symptom** |
| **Ruling format** | `RECORD ONLY — accepted limitation; internal consistency is a mechanism obligation` · or `AMENDMENT REQUIRED` · or `ACCEPT — port-vocabulary route` |

---

### C-18 · Must an admitted state bind the constitutional version that admitted it?

| | |
|---|---|
| **Altitude** | **A → C** — **trigger** at the §16 altitude, **remedy** on the aggregate |
| **Governing law** | §16 — *"nothing moves between altitudes except by constitutional amendment"*, so amendment is possible; **r5 has demonstrated that v1.1 revises**. INV-KOS-HISTORY-001 preserves the transition, not the governing law. No member, event or invariant binds a state to a law version |
| **Existing evidence** | Corpus **anticipated it three times**: `104148` (rules *"versioned… like a real constitution"*), `110248` §12 (*"the Kernel applies the current version… references the new version"*, **and** warns *KnowledgeOS history ≠ Kernel software history*), `110950` §3 (asks *"who determines the authoritative constitutional version? who certifies it? how is it identified?"* — unanswered) |
| **Settled** | Amendment is lawful and has occurred (r5). Nothing currently records the governing version |
| **Unresolved** | Whether the binding is domain-owned (a member / an `EvidenceLink` / `History`) or infrastructure metadata (outside) |
| **Options** | **O1** a new member · **O2** record as an `EvidenceLink` · **O3** record in `History` · **O4** infrastructure metadata, outside · **O5** rule out of scope |
| **Consequence** | **O1** members **12→13** · **O2/O3** no count change; uses existing structure · **O4** the domain cannot reproduce a past admission's basis · **O5** same as O4, ruled deliberately |
| **Change class** | O1 **AMENDMENT** · O2/O3 **MAPPING** or **DOMAIN-MODEL** · O4/O5 **NONE** |
| **Downstream** | **C-2** — determinism across an amendment is unverifiable without this |
| **Drift risk** | 🟠 **MEDIUM** — silent until the first invariant amendment, after which prior admissions are unreproducible |
| **Ruling format** | `ACCEPT — bind via [O2/O3], no new member` · or `AMENDMENT REQUIRED — new member` · or `REJECT — out of scope; [reason]` |

---

### C-14 · Is `NOT_ASSESSED ≠ LOW_CONFIDENCE` a non-collapse requirement rather than a representation choice?

| | |
|---|---|
| **Altitude** | **A or F** — a **reclassification** question; currently ruled **F** (Logical Architecture) as UQ-4 |
| **Governing law** | ⟨C-5⟩ **already forbids** the collapse — a mechanism's inability maps to `UNKNOWN`, *"never to a low-confidence accept."* ⟨R-1⟩ forbids a mechanism scalar becoming Confidence. **The positive representation of a not-assessed Confidence is undefined** |
| **Existing evidence** | Mapping recorded it as **UQ-4**; the HPA **deferred it to Logical Architecture**. Critique argued it is law-adjacent (F-CM-1's class). Corpus: `123630` lists it among six ZERO non-collapse pairs beside `UNKNOWN ≠ ABSENT` (which **is** law); `20260822-161933` makes **abstention quality** and **false collapse** first-class metrics |
| **Settled** | The **prohibition is law** (⟨C-5⟩). Only the representation is open |
| **Unresolved** | Whether the existing **F** deferral stands, or the item is **A** |
| **Options** | **O1** the F deferral stands · **O2** reclassify as A and record a §15 row · **O3** rule that ⟨C-5⟩ suffices and no representation is owed |
| **Consequence** | **O1** the representation is settled at realization — where the collapse would be implemented · **O2** twelve/thirteen §15 rows · **O3** implementations may represent it freely provided ⟨C-5⟩ holds |
| **Change class** | O1 **DEF-1/F** · O2 **AMENDMENT** · O3 **NONE** |
| **Downstream** | none — but **blocked by C-11** (if the member is vestigial, the question dissolves) |
| **Drift risk** | 🟠 **MEDIUM** — the likely realization is a nullable scalar, which is the collapse ⟨C-5⟩ forbids |
| **Ruling format** | `CONFIRM DEFERRAL — remains Logical Architecture (UQ-4)` · or `RECLASSIFY — constitutional; §15 row` · or `REJECT — ⟨C-5⟩ is sufficient` |

---

### Wisdom · Does Wisdom enter KnowledgeCore, and does that require a V.3 act?

| | |
|---|---|
| **Altitude** | **A — CONSTITUTIONAL (V.3)** |
| **Governing law** | §4.1 — *Wisdom Formation*: *"no register row; the character ends at **trustworthy truth discovery**; adopting it would extend the frozen Constitution (V.3)"* → **REJECTED at the boundary**, amendment-gated. §17 lists it among the Chapter IV refusals against **V.3**. §8 — *"`WisdomDerived` — recorded for traceability, **NOT admitted**… V.3 forbids adding concepts"* |
| **Existing evidence** | ADR-KOS-SCOPE-001 §16 states the position with sources and **makes no amendment and no recommendation**. Corpus is extensive (Gaṇeśa/Wisdom character work, `WISDOM-MECH-001..006`) but the 26-lens system files the wisdom family in **tier 3, mechanism candidates** — **the corpus never claims Wisdom is core** |
| **Settled** | Wisdom as a **mechanism behind a port** requires nothing from law. Wisdom as a **core concept** adds a concept, which V.3 forbids |
| **Unresolved** | Only whether the programme wishes to open a V.3 act. **Nothing in the current chain requires it** |
| **Options** | **O1** status quo — Wisdom stays outside as research / lens / mechanism candidate · **O2** open a separate V.3 amendment act |
| **Consequence** | **O1** nothing changes; the ADR position stands · **O2** a constitutional act with its own commission, independent of the Kernel chain |
| **Change class** | O1 **NONE** · O2 **AMENDMENT** |
| **Downstream** | none — **fully independent** |
| **Drift risk** | 🟢 **NONE** — the status quo already excludes it. **The only item where inaction is safe** |
| **Ruling format** | `RECORD ONLY — status quo confirmed; Wisdom remains outside the core` · or `AMENDMENT ACT COMMISSIONED — separate from the Kernel chain` |

---

### C-3 / CC-2 · Who checks that an authority is *adequate in scope* for the act?

| | |
|---|---|
| **Altitude** | **B or A** — a tenth capability (B) or contexts **6→7** (A) |
| **Governing law** | INV-KOS-AUTHORITY-001 — authority is **assigned, never emergent**; evidence, assessment and source **never self-authorize**; locus *"the AuthorityGrant boundary; the aggregate references, never holds."* §5.3 declares **six** contexts and **Governance is not one**; `Authority`'s purpose is *"record the human act that assigns authority"* and it owns the **grant** only |
| **Existing evidence** | Mapping names **one** failure mode (never self-authorize); **no capability checks scope**. Critique found the responsibility **unhomed**. **CC-2:** a Governance context appears in the corpus (`110248` §4/§14/§15, `105200` §12), in the critique's C-3, **and in the HPA's own architectural view** — **three independent models; the law has none** |
| **Settled** | The core **references, never holds** authority. Self-authorization is refused. **Scope adequacy is checked by nothing** |
| **Unresolved** | Whether checking adequacy is a domain responsibility at all |
| **Options** | **O1** a tenth boundary capability · **O2** a seventh bounded context owns it · **O3** reject — adequacy is the submitting authority's obligation, and the core checks only that a reference exists · **O4** the determination crosses as port vocabulary |
| **Consequence** | **O1** the boundary must hold policy it currently does not, risking PROTECT→PRODUCE drift · **O2** contexts **6→7**, amendment-class · **O3** INV-KOS-AUTHORITY-001 is enforced at *reference-existence* strength only, which should be stated explicitly · **O4** an external determination is trusted; the trust boundary moves to the port |
| **Change class** | O1 **BOUNDARY** + MAPPING · O2 **AMENDMENT** · O3 **NONE** (ruling of record) · O4 **BOUNDARY** + Port Contract |
| **Downstream** | **C-4** — *what authorizes* an operation on an existing aggregate |
| **Drift risk** | 🔴 **HIGH** — any well-formed authority reference is treated as sufficient, undetectably |
| **Ruling format** | `ACCEPT — [O1/O4], subject to X` · or `AMENDMENT REQUIRED — contexts 6→7` · or `REJECT — adequacy is not a domain responsibility; INV-KOS-AUTHORITY-001 is satisfied by reference existence` |

---

### C-10 · Who verifies that an evidence reference resolves?

| | |
|---|---|
| **Altitude** | **B or F** |
| **Governing law** | ⟨C-3⟩ — the core owns *references + acquisition method + reliability conditions*, **never content**; holding content drifts toward the Chapter IV **database** refusal. **D-1** — the core depends on **nothing**. Article 6 requires the justification **path** |
| **Existing evidence** | Critique: verifying resolvability requires reaching outside, which **D-1 forbids** — so the capability reduces to accepting unverified references, and **T-6 presumes an observation the boundary cannot make**. Corpus **anticipated it and placed the check outside**: `110248` §9 models `admissibility: enum[ADMISSIBLE, INADMISSIBLE, PENDING]` and assigns checking to an Evidence or Governance context; §15 lists an *"Evidence Admissibility Checker → provides admissibility status"* |
| **Settled** | The core may not hold content (⟨C-3⟩) and may not reach out (D-1) |
| **Unresolved** | Whether admissibility status may cross as port vocabulary, and what "evidence admission" then asserts |
| **Options** | **O1** references are accepted **as-supplied**; state it and amend T-6 · **O2** admissibility status crosses as **port vocabulary** (r4-4: never a member) · **O3** defer to the Port Contract's ratification |
| **Consequence** | **O1** "evidence admission" asserts only *a reference was supplied*, which should be stated so no later actor over-reads it · **O2** the trust boundary moves to the port; a mechanism's admissibility judgement becomes a precondition · **O3** C-6 stays blocked |
| **Change class** | O1 **MAPPING** · O2 **BOUNDARY** + Port Contract · O3 **DEF-1/F** |
| **Downstream** | **C-6** |
| **Drift risk** | 🟠 **MEDIUM-HIGH** — an implementation either calls out (breaking D-1) or accepts anything (making the capability vacuous) |
| **Ruling format** | `ACCEPT — references accepted as-supplied; T-6 amended` · or `ACCEPT — admissibility crosses as port vocabulary` · or `DEFER — Port Contract ratification` |

---

### C-1 · Is the nine-item capability list to be re-typed?

| | |
|---|---|
| **Altitude** | **D — CAPABILITY** (touches the ⬜ OPEN F-1…F-5 record) |
| **Governing law** | Law types **invariants** (§7), **members** (§6), **events** (§8) and **contexts** (§5.3). **Law does not type "capabilities" at all.** The nine come from F-1…F-5, whose amendment candidates are ⬜ **OPEN** |
| **Existing evidence** | Critique tested each: **6 capabilities + 1 invariant + 1 anti-capability + 1 split**, two of the six compound. *Single admission gate* is INV-KOS-VERIFICATION-001 rendered as a boundary property; *representation-agnostic intake* is an anti-capability. Corpus **anticipated with a different count** — `110248` §14 produced a **six-item** MUST-EXIST list independently; `110950` §2 states the test the mapping never ran |
| **Settled** | Two of the nine are **not capabilities** — verified against law. The placements are unaffected |
| **Unresolved** | Whether the correction happens in a revised mapping or by disposing of the F-1…F-5 record |
| **Options** | **O1** re-type in a revised mapping, F-record untouched · **O2** dispose of the F-1…F-5 amendments first, then re-type · **O3** reject — the typing is a presentational matter |
| **Consequence** | **O1** two artifacts disagree on the typing until the F-record is dispositioned · **O2** ordered but slower; the F-record's ⬜ OPEN status is resolved first · **O3** an implementation builds nine things, two of which cannot be built |
| **Change class** | O1/O2 **MAPPING** · O3 **NONE** |
| **Downstream** | **C-9 · C-11 · C-16 · C-13** |
| **Drift risk** | 🟠 **MEDIUM-HIGH** — the likely failure is inventing a component for *representation-agnostic intake*, which is the opposite of what the entry means |
| **Ruling format** | `ACCEPT — re-type in a revised mapping` · or `ACCEPT — subject to F-1…F-5 disposition first` · or `REJECT — presentational` |

---

### C-11 · From what lawful input may `Confidence` be computed?

| | |
|---|---|
| **Altitude** | **D → A/C** — escalates if the member is vestigial |
| **Governing law** | ⟨R-1⟩ — Confidence is **structured**, assigned **inside**; a mechanism-supplied score *"must never cross the port and become Confidence."* ⟨C-3⟩ — links, never content. §16 — the kernel *"cannot generate a conclusion."* Obligation 5 — no scalar in place of epistemic structure. **OQ-5 ruled the member stays; derivability is expressed nowhere** |
| **Existing evidence** | Critique: **every candidate input is forbidden** — content (⟨C-3⟩), mechanism score (⟨R-1⟩), reasoning (§16), quality (needs content) — leaving only structural facts. Corpus **asked it verbatim and never answered**: `110950` §13 *"How does the Kernel determine confidence without understanding evidence content?"*; `114530` §4 offers *"the Kernel is the **assigner**, not the **evaluator**"* as a candidate shape; `110950` §7 rates *"confidence is essential"* **🔴 Unproven**; `114358` *"Confidence is not a number"* |
| **Settled** | ⟨R-1⟩ fixes the **locus** of assignment. **OQ-5 settled membership — this question is derivability, and is not a reopening of OQ-5** |
| **Unresolved** | Whether any lawful input exists |
| **Options** | **O1** enumerate lawful **structural** inputs (e.g. path completeness, count of resolvable references) and state that Confidence is a function of those only · **O2** rule that an external assessment crosses as port vocabulary and Confidence records it without deriving it · **O3** rule the member vestigial under ⟨R-1⟩+⟨C-3⟩+§16 taken together · **O4** record the gap and defer |
| **Consequence** | **O1** Confidence becomes a restatement of structure — defensible but arguably trivial; must be stated so no one over-reads it · **O2** the trust boundary moves to the port; ⟨R-1⟩'s *"no mechanism score becomes Confidence"* must be reconciled with *records an external assessment* · **O3** touches OQ-5's ruling and needs its own act; members would go **12→11** · **O4** an implementation decides it |
| **Change class** | O1 **MAPPING** · O2 **BOUNDARY** + Port Contract · O3 **AMENDMENT** (and reopens OQ-5) · O4 **NONE** |
| **Downstream** | **C-14** |
| **Drift risk** | 🔴 **HIGH** — whatever an implementation picks is either trivial or a hidden reasoner. **ANTI-REASONER RISK: FLAGGED** |
| **Ruling format** | `ACCEPT — Confidence is a function of [enumerated structural inputs] only` · or `ACCEPT — external assessment crosses as port vocabulary, subject to reconciling ⟨R-1⟩` · or `AMENDMENT REQUIRED — member vestigial (reopens OQ-5)` · or `RECORD ONLY — gap acknowledged, DEFER` |

---

### C-4 · How does a submission target an existing `KnowledgeId` for a revision?

| | |
|---|---|
| **Altitude** | **B — SCOPE / BOUNDARY** (+ Port Contract, ⬜ unratified) |
| **Governing law** | **Six of the ten events operate on an existing aggregate** — `EvidenceAdded` (*EvidenceLinks extended*), `BeliefRevised` (*prior state → History*), `MeaningTranslated` (*KnowledgeId unchanged*), `ContradictionDetected`/`Resolved`, `KnowledgeSuperseded`. **Obligation 4** forbids a mechanism proposing or deriving a `KnowledgeId`. **⟨C-1⟩** forbids the core matching by meaning. **Nothing states how the target is identified** |
| **Existing evidence** | Critique: the **one outright falsification** — Q1 answers only the creation case; neither a target identity nor the current state is listed among the eight elements that cross. Corpus **anticipated it exactly**: `110248` §15 lists *"**Current Knowledge State** (the state to be transitioned)"* among what must cross — **the corpus listed the input the mapping dropped**; `111647` §5 enumerates twelve transitions on existing claims |
| **Settled** | The horn is real: a mechanism may not supply an identity (obligation 4) and the core may not resolve one by meaning (⟨C-1⟩) |
| **Unresolved** | Which horn gives: obligation 4's **scope**, or a third route |
| **Options** | **O1** clarify obligation 4 as *"never propose a **new** identity"*, permitting a **target reference** to an existing one · **O2** add a target-reference element to what crosses the port · **O3** rule that revisions enter by a different governed route (not the Verification Port) — which would create a **second admission path** and breach INV-KOS-VERIFICATION-001 · **O4** defer to Port Contract ratification |
| **Consequence** | **O1** the narrowest reading; obligation 4's text is narrowed by ruling, which should be recorded as such · **O2** the port's element list grows; the contract is ⬜ unratified so this rides on that act · **O3** **breaches the single-gate invariant** — recorded for completeness, not as a live option · **O4** six of ten events remain unspecified in the mapping |
| **Change class** | O1 **MAPPING** + Port Contract clarification · O2 **BOUNDARY** + Port Contract · O3 **AMENDMENT** (INV-KOS-VERIFICATION-001) · O4 **DEF-1/F** |
| **Downstream** | **C-16 · F-CM-2** · blocked by **C-3** and **F-CM-1b** |
| **Drift risk** | 🔴 **HIGHEST IN THE SET** — an implementation lets the mechanism name the `KnowledgeId`, quietly narrowing obligation 4 with no ruling |
| **Ruling format** | `ACCEPT — obligation 4 is new-identity-only; a target reference is admissible` · or `ACCEPT — target reference added to port vocabulary` · or `DEFER — Port Contract ratification` |

---

### C-16 · Is admission idempotent? *(with **F-CM-2**: is non-unique identity-of-meaning intended?)*

| | |
|---|---|
| **Altitude** | **C-16 → D — CAPABILITY** · **F-CM-2 → C — DOMAIN MODEL** (identity semantics) |
| **Governing law** | ⟨C-1⟩ — similarity and canonical-form equality **never** become identity. INV-KOS-IDENTITY-001 — identity is **assigned**. **Nothing requires `KnowledgeId` uniqueness per meaning**; uniqueness is per assignment |
| **Existing evidence** | F-CM-2 established the inability to deduplicate **violates no invariant** — it is ⟨C-1⟩ working as designed. Critique added the unstated consequence: **replay produces a second identity; there is no idempotency concept at the gate**. Corpus **anticipated and CONFLICTS WITH LAW**: `103606` **K1** wants the Kernel to distinguish *"same entity / different entity"* — **exactly the discrimination ⟨C-1⟩ forbids**; `123619` scenarios 18–19 are *"Duplicate identity attempt"* and *"Replay of admission"* |
| **Settled** | Non-uniqueness **violates nothing**. Deduplication and idempotency both require a sameness comparison the core is forbidden to make |
| **Unresolved** | Whether non-uniqueness is **intended**, and whether the assigner may consult existing knowledge without *deriving* identity |
| **Options** | **O1** confirm intended; state that admission is **not idempotent** and replay produces distinct identities · **O2** idempotency is a mechanism-side responsibility before submission · **O3** the assigner may consult existing knowledge under stated constraints (requires distinguishing *consulting* from *deriving*) · **O4** defer |
| **Consequence** | **O1** identity-of-meaning is plural by design; downstream consumers must not assume uniqueness · **O2** the port must carry a submission identity or nonce — Port Contract territory · **O3** the hardest to keep ⟨C-1⟩-safe: *consult* and *derive* differ only by what the result is used for · **O4** an implementation adds deduplication and breaks ⟨C-1⟩ |
| **Change class** | O1 **MAPPING** · O2 **BOUNDARY** + Port Contract · O3 **DOMAIN-MODEL** (identity semantics) · O4 **NONE** |
| **Downstream** | none · blocked by **F-CM-1b** |
| **Drift risk** | 🟠 **MEDIUM-HIGH** — a retried submission duplicates, or the implementer adds dedup and breaks ⟨C-1⟩ |
| **Ruling format** | `ACCEPT — non-uniqueness intended; admission is not idempotent` · or `ACCEPT — idempotency is mechanism-side` · or `AMENDMENT/DOMAIN-MODEL REQUIRED — consult-vs-derive distinction` |

---

### C-7 · What is the cardinality and precedence between `CONFLICTED` and `ConflictRecord`?

| | |
|---|---|
| **Altitude** | **C — DOMAIN MODEL** (**D** if only a rule is stated) |
| **Governing law** | §7's enforcement locus for INV-KOS-CONTRADICTION-001 is *"`EpistemicState (CONFLICTED)` **+** `ConflictRecord`"* — **jointly**. §6.1 — ConflictRecord *"references the conflicting aggregates by identity"*; ConflictState is `CONFLICTED · RESOLVED`; resolution forward-only, record survives. **Neither is designated authoritative if they diverge; cardinality unstated** |
| **Existing evidence** | Boundary act §7 (post-ruling): atomic consistency between state and record is necessary *under the current invariant definition*. Critique: the relation is **one-to-many against a single-valued member**, so resolving one conflict cannot lawfully clear `CONFLICTED` while others stand. Corpus **anticipated** (`113410` leaves four candidates UNRESOLVED as state/relationship/event/derived-condition; `111647` — `RECONCILED` *"may describe a relationship between two previously conflicting claims"*) **and CONFLICTS WITH LAW** (`113645`/`114530` conclude states are *derived projections* — refuted by §9's *no implicit transition* and INV-KOS-PROJECTION-001) |
| **Settled** | Both are required jointly. States are **not** derived projections — law is explicit |
| **Unresolved** | Cardinality and precedence |
| **Options** | **O1** state the rule: `CONFLICTED` holds while **any** unresolved ConflictRecord exists; the records are authoritative · **O2** the member is authoritative and records are evidence · **O3** make the relationship explicit in the model (a member change) · **O4** defer |
| **Consequence** | **O1** a `MAPPING` statement suffices; no model change · **O2** inverts the current joint locus and needs justification against §7 · **O3** members/structure change — amendment-class · **O4** an implementation picks, and either choice can leave the two disagreeing |
| **Change class** | O1 **MAPPING** · O2 **DOMAIN-MODEL** · O3 **AMENDMENT** · O4 **NONE** |
| **Downstream** | none · blocked by **C-15 · C-17** (both propose state-vocabulary changes) |
| **Drift risk** | 🟠 **MEDIUM** — one resolution clears the state while others stand, or never clears it |
| **Ruling format** | `ACCEPT — [O1/O2] stated as a rule; no model change` · or `DOMAIN-MODEL REVISION REQUIRED` · or `DEFER — pending C-15/C-17` |

---

### C-6 · What is the disposition of a stale outward reference?

| | |
|---|---|
| **Altitude** | **C or A** — reference semantics over time |
| **Governing law** | Three outward reference kinds: `Authority` (Article 3), `EvidenceLinks` (⟨C-3⟩), `Relations` / `TemporalValidity.superseded-by` (⟨C-4⟩). **D-1** forbids the core reaching out; **D-5** forbids anything written outside returning as knowledge |
| **Existing evidence** | Mapping records only **UQ-5** (revoked authority) as one question. Critique generalised it to a **class** with fan-out. Corpus **anticipated with the fan-out**: `111647` (*evidence invalidated… causes downstream epistemic reassessment*), `123619` §13 (the many-to-many test `E1 → A, B, C`), scenarios *"Evidence invalidated"* and *"Evidence removed"* |
| **Settled** | The core is **structurally unable** to learn a referent changed (D-1), and the outside may not push it in (D-5). **There is no lawful repair path within the current dependency rules** |
| **Unresolved** | What an admitted state's justification *means* once a referent has moved |
| **Options** | **O1** references are **as-of-admission**; staleness is out of scope and stated · **O2** invalidation re-enters as a **new candidate submission** through the single gate (consistent with D-5) · **O3** treat it as a constitutional question about justification over time · **O4** defer |
| **Consequence** | **O1** admitted knowledge may retain justification that no longer exists — defensible if stated, since History preserves what was true at admission · **O2** preserves the single gate and D-5; requires a submission route for *"the evidence I cited is invalid"* · **O3** amendment-class; touches INV-KOS-VERIFICATION-001's meaning · **O4** implementations diverge |
| **Change class** | O1 **MAPPING** · O2 **BOUNDARY** · O3 **AMENDMENT** · O4 **NONE** |
| **Downstream** | none · blocked by **C-10** |
| **Drift risk** | 🟠 **MEDIUM** — silent; justification decays invisibly, with fan-out across many claims |
| **Ruling format** | `ACCEPT — references are as-of-admission; staleness out of scope` · or `ACCEPT — invalidation re-enters via the single gate` · or `AMENDMENT REQUIRED` |

---

### C-5 · Is supersession a second cross-aggregate coordination locus?

| | |
|---|---|
| **Altitude** | **C → F** — a locus record, DEF-1-deferred like DS-CAND-2 |
| **Governing law** | `TemporalValidity` holds *valid-from · valid-until · **superseded-by***; ⟨C-4⟩ forbids containment; `KnowledgeSuperseded` is forward-only with the prior state retained. **The supersession invariant spans a pair of aggregates** |
| **Existing evidence** | Mapping records **DS-CAND-2** for cross-aggregate contradiction coordination and **no equivalent for supersession**. Corpus **anticipated**: `111647` — *"`SUPERSEDED` may involve a relationship between **two KnowledgeAggregates**, not merely a state transition inside one"*; `113410` investigates SUPERSEDED, **UNRESOLVED** |
| **Settled** | ⟨C-4⟩ forbids containment, so no single aggregate owns the supersession invariant — the same structure as contradiction |
| **Unresolved** | Only whether to record the locus |
| **Options** | **O1** record **DS-CAND-3**, DEF-1-deferred · **O2** reject — supersession is expressible within one aggregate's `TemporalValidity` · **O3** defer |
| **Consequence** | **O1** three candidate loci recorded, none created; symmetry with contradiction preserved · **O2** must explain how a reference to another aggregate is maintained forward-only without coordination · **O3** an implementation couples the two aggregates and breaches ⟨C-4⟩ |
| **Change class** | O1 **MAPPING** (a record only) · O2 **NONE** · O3 **NONE** |
| **Downstream** | none — **fully independent** |
| **Drift risk** | 🟡 **LOW-MEDIUM** — supersession implemented inside one aggregate, coupling it to another |
| **Ruling format** | `ACCEPT — record DS-CAND-3, DEF-1-deferred` · or `REJECT — expressible within one aggregate` |

---

### C-2 · Is determinism a boundary property, a fitness constraint, or DEF-1 realization?

| | |
|---|---|
| **Altitude** | **A / E / F — three options at three different altitudes.** *This is the item whose altitude the ruling itself fixes* |
| **Governing law** | **Not among the eleven.** §9 (*"no implicit transition"*) and INV-KOS-HISTORY-001 imply reproducibility without stating it. §19's gates do not test it |
| **Existing evidence** | Absent from the mapping's capabilities, anti-capabilities and tests — **yet T-2 SEMANTIC-INVARIANCE presupposes it**. Corpus **anticipated four times as a first-class property**: `103606` **K8** (*"same admissible input and same prior state → same resulting state"*, tied to *replayability · auditability · deterministic testing · reproducibility · governance confidence* **and to existing EKS/PKS deterministic-assurance work**), `104148` §2, `110248` §17, `205735` (a deterministic-assurance lens) |
| **Settled** | T-2 cannot be executed without it. Law does not state it |
| **Unresolved** | Its altitude |
| **Options** | **O1** a **new invariant** (a boundary property in law) · **O2** a **fitness constraint** only (testable, no law change) · **O3** **DEF-1 realization** (deferred) · **O4** reject as a requirement |
| **Consequence** | **O1** invariants **11→12** — amendment-class, and the strongest guarantee · **O2** T-2 becomes executable and the anti-reasoner constraint keeps its detector, without touching law · **O3** T-2 remains unexecutable until realization decides; the anti-reasoner constraint has **no detector in the interim** · **O4** the fitness family's assertions weaken across the board |
| **Change class** | O1 **AMENDMENT** · O2 **MAPPING** · O3 **DEF-1** · O4 **NONE** |
| **Downstream** | **C-8 · C-12 · C-9 · T-2** — the whole fitness family · blocked by **C-18** |
| **Drift risk** | 🔴 **HIGH** — without it T-2 is unrunnable, and T-2 is the only test that detects the invisible PROTECT→PRODUCE drift |
| **Ruling format** | `ACCEPT — fitness constraint (no law change)` · or `AMENDMENT REQUIRED — new invariant` · or `DEFER — DEF-1` · **note: the choice fixes the altitude** |

---

### C-8 · Must a fitness test assert identity continuity across transitions?

| | |
|---|---|
| **Altitude** | **E — FITNESS / ASSURANCE** |
| **Governing law** | **Law already states the requirement.** §8 — `MeaningTranslated`: *"meaning preserved; **KnowledgeId unchanged**."* INV-KOS-IDENTITY-001 — identity *"assigned once, stable through representation, expression, context and projection change."* §4.3 — remove identity → claim-store, a Chapter IV refusal |
| **Existing evidence** | T-7 tests that identity is **not derived**; **no test asserts it persists** across `EvidenceAdded`, `BeliefRevised`, `MeaningTranslated`, `KnowledgeSuperseded`. Corpus **anticipated as the central question of two lenses**: `112855` (*"state is Shakti; identity continuity is Shiva"*), `210001` §9 (*"can a knowledge object evolve continuously without losing identity?"* — used to distinguish **revision from replacement** and **supersession from identity destruction**) |
| **Settled** | **This is not a law gap.** Law states it; the test suite omits it |
| **Unresolved** | Only whether to add the test — and, per the commission's identity test, whether *identity continuity* is to be distinguished from *semantic · epistemic-state · contextual · relationship* continuity in the assertion |
| **Options** | **O1** add a test asserting `KnowledgeId` invariance across all four transition types · **O2** as O1, plus separate assertions for the other four continuities · **O3** reject — T-7 is sufficient |
| **Consequence** | **O1** the architecture's most important invariant becomes testable for persistence · **O2** stronger, but requires deciding whether law fixes the other four continuities — **and the commission notes law may not, in which case they must be recorded unresolved rather than asserted** · **O3** nothing detects an implementation that reassigns identity on revision |
| **Change class** | O1/O2 **MAPPING** · O3 **NONE** |
| **Downstream** | none · blocked by **C-2** |
| **Drift risk** | 🟠 **MEDIUM-HIGH** — undetectable identity reassignment on revision |
| **Ruling format** | `ACCEPT — add the identity-continuity test` · or `ACCEPT — subject to recording the other four continuities as unresolved` · or `REJECT — T-7 sufficient` |

---

### C-12 · Must a fitness test assert `Relations` referential integrity?

| | |
|---|---|
| **Altitude** | **E — FITNESS / ASSURANCE** |
| **Governing law** | ⟨C-4⟩ — Relations are *"by KnowledgeId reference only, never containment — so relations cannot create transactional coupling across aggregates."* Article 1.5 — knowledge is a relationship. **Relation targets are internal `KnowledgeId`s**, so unlike evidence this **is** checkable without violating D-1 |
| **Existing evidence** | No test asserts a relation's target exists. Corpus **anticipated as a named failure mode**: `210001` §11 — *"**False connection**: the system assumes A and B are related although no valid relation exists"* and *"**Broken connection**: a relationship required for identity/justification has disappeared"*; `123619` §13 asks whether the support relation can change independently of its ends |
| **Settled** | ⟨C-4⟩ keeps relations as references; targets are internal and therefore checkable |
| **Unresolved** | Whether integrity is asserted, and — per the commission's relations test — **whether the relation itself requires atomic consistency or the boundary need only preserve a supplied representation of it** |
| **Options** | **O1** add the test (target must exist) · **O2** rule that relations are *supplied representations* and dangling targets are lawful · **O3** defer |
| **Consequence** | **O1** the cheapest gap in the set to close; a *false connection* the boundary can detect becomes detected · **O2** Article 1.5's *knowledge is a relationship* is enforced only as far as the supplied reference, which should be stated · **O3** relations may point at nothing |
| **Change class** | O1 **MAPPING** · O2 **MAPPING** (a statement) · O3 **NONE** |
| **Downstream** | none · blocked by **C-2** |
| **Drift risk** | 🟡 **LOW-MEDIUM** |
| **Ruling format** | `ACCEPT — add the relation-integrity test` · or `ACCEPT — relations are supplied representations; dangling targets lawful` |

---

### C-9 · Should the anti-capability register gain the three uncovered prohibitions?

| | |
|---|---|
| **Altitude** | **D + E** — prohibitions and their tests |
| **Governing law** | **Article 11 — *"freshness never truth, expiry never absence"*: explicit law with no anti-capability and no test.** INV-KOS-DIMENSION-001 · ⟨R-1⟩ — no scalar surrogate for structure. INV-KOS-CONTRADICTION-001 — resolution is **governed**, with an authority reference |
| **Existing evidence** | The register's twenty entries catch **fifteen of the corpus's twenty drift points**; **three are uncovered**: a scalar deciding **contradiction resolution** (A-8 covers *Confidence* only) · **age/recency deciding truth** (no entry) · **automatic contradiction resolution without a recorded authority act** (no entry). Corpus **supplies all three counterexamples** (`104251`: `SUPERSEDE: weight(A) > weight(B) × 1.5` · `ENTRENCH: age(A) > age(B) × 10` · `observation_not_ancient: < 30 days` · a `CONTRADICTION_RULE` resolving by arithmetic with no authority act) |
| **Settled** | All three prohibitions **are already law**. The register omits them |
| **Unresolved** | Only whether to add them |
| **Options** | **O1** add all three entries + tests · **O2** add only the Article 11 entry (the one with no coverage at all) · **O3** reject — law suffices without a register entry |
| **Consequence** | **O1** the register's coverage matches law · **O2** partial; the scalar-resolution and unauthorised-resolution gaps stay · **O3** a prohibition in law with no entry and no test is unenforced |
| **Change class** | O1/O2 **MAPPING** · O3 **NONE** |
| **Downstream** | none · blocked by **C-1 · C-2** |
| **Drift risk** | 🟠 **MEDIUM-HIGH** — recency or a weight decides truth, the exact collapse §15 forbids |
| **Ruling format** | `ACCEPT — add three register entries and their tests` · or `ACCEPT — Article 11 entry only` · or `REJECT — law suffices` |

---

### CC-1 · What is the disposition of the historical stack diagram?

| | |
|---|---|
| **Altitude** | **G — DOCUMENTATION / PROCESS** (no architecture) |
| **Governing law** | §16's KERNEL altitude members: *"the eleven invariants · KnowledgeAggregate · ConflictRecord · **the three small supporting aggregates**."* §6.2 places `AuthorityGrant` in **Authority**, `DerivedView` in **Projection**, `DecisionRecord` in **Decision Boundary** |
| **Existing evidence** | **CC-1:** the diagram draws `Kernel ⊂ KnowledgeCore`, which **does not hold under the altitude reading** — the altitude spans **four contexts**. It draws containment true of the *admission boundary* while using the name belonging to the *altitude*. Corpus **supplies the falsification argument**: `110950` §1 warns the term conflates *bounded context · aggregate boundary · domain capability*; `123619` §9 asks *"is the Kernel an aggregate at all?"* |
| **Settled** | The diagram is **inconsistent with law** — confirmed. **This falsifies the diagram, not the boundary** |
| **Unresolved** | Its disposition |
| **Options** | **O1** supersede — replace with the ADR's three separated views · **O2** annotate as historical, retained · **O3** retain unchanged |
| **Consequence** | **O1** one architectural picture; the HPA's own concern about two pictures coexisting is closed · **O2** both exist but the historical one is marked · **O3** the diagram remains citable as current nesting and the K-1 distinction erodes by reuse |
| **Change class** | all **NONE** (documentation) |
| **Downstream** | none — **fully independent** |
| **Drift risk** | 🟡 **LOW (architecture) / MEDIUM (comprehension)** — a future actor cites it as nesting |
| **Ruling format** | `SUPERSEDE — replaced by the ADR's three views` · or `RECORD ONLY — retained as historical, annotated` |

---

### C-13 · Must the required reasoning chain be run on the capabilities?

| | |
|---|---|
| **Altitude** | **G — PROCESS** |
| **Governing law** | Not a law question |
| **Existing evidence** | `20260823_1239_working_state.md` specifies the chain: *capability → domain responsibility → invariant → **what must change atomically** → **what may change independently** → consistency boundary → aggregate/service/process*. The mapping classified the nine by PROTECT/PRODUCE and **skipped the atomicity and independence steps**; they were run in the prior act on the twelve **members** only. Corpus **anticipated as its own methodological demand**: `110950` §2 (*"the stronger DDD test is which invariant cannot be protected if this concept is outside the aggregate?"*), `123619` §12 (*"if responsibility X is removed: which invariant breaks? If no invariant breaks: X is not Kernel responsibility"*) |
| **Settled** | The chain was not run on the capabilities. Had it been, **C-1 would have surfaced there** |
| **Unresolved** | Whether a revised mapping must run it |
| **Options** | **O1** require it for any mapping revision · **O2** record as guidance · **O3** reject |
| **Consequence** | **O1** the minimality test becomes procedural, not optional · **O2** the next revision may repeat the omission · **O3** same as O2 |
| **Change class** | all **NONE** (process) |
| **Downstream** | affects the **conduct** of C-1's revision · otherwise independent |
| **Drift risk** | 🟡 **LOW (architecture) / MEDIUM (recurrence)** |
| **Ruling format** | `ACCEPT — required for any mapping revision` · or `RECORD ONLY — guidance` |

---

### C-19 · Does the proposed boundary contradict the established EKS/PKS/AIP/KnowledgeOS architecture?

| | |
|---|---|
| **Altitude** | **H — CONSISTENCY CHECK** |
| **Governing law** | Not a law question — a comparison of an established ownership map against the proposed boundary |
| **Existing evidence** | **Reframed by the HPA**: a bounded consistency check, direction **downward into a smaller boundary, never outward**; **EKS/PKS/AIP are validation context, not Kernel contents**. Two checks executed: **CC-1** (diagram inconsistent with §16) and **CC-2** (a Governance context in three models, none in law → C-3). **Neither moved a responsibility; neither found anything in EKS/PKS/AIP that must be inside.** Corpus **anticipated and proposed the matrix never built**: `103606` §8 (a strict capability matrix over eleven inputs including existing EKS, PKS and the AI Engineering Platform), `110950` §5 (historical continuity 🔴 MISSING) |
| **Settled** | **So far the check supports the boundary.** It can only clear or falsify — never enlarge |
| **Unresolved** | The remainder is unexecuted |
| **Options** | **O1** commission the remainder · **O2** accept the two executed checks as sufficient · **O3** defer until after the other rulings |
| **Consequence** | **O1** either clears the boundary against established ownership or produces a third CC finding · **O2** the boundary is cleared on partial evidence, which should be stated · **O3** ordering only |
| **Change class** | **NONE** unless a conflict is found |
| **Downstream** | none — **fully independent; requires no decision to execute** |
| **Drift risk** | 🟢 **NONE from inaction** — the risk is only that a conflict is discovered late |
| **Ruling format** | `COMMISSION — execute the remaining consistency check` · or `ACCEPT — two checks sufficient` · or `DEFER` |

---

## 5 · Aggregate sections

*(§1 gave the altitude classification; §2 the dependency graph; §3 the decision order. Sections 3–15 of the commission follow.)*

### 5.1 · Constitutional questions — **A**

| ID | Question | Amendment required? |
|---|---|---|
| **F-CM-1a** | record *Ambiguity ≠ Contradiction*? | **only if recorded as a §15 row** |
| **C-15** | retraction representation | **yes, for a state or event** |
| **C-17** | internally impossible claim | **yes, for a state**; no for a port route or a recorded limitation |
| **C-18** | constitutional-version binding | **only if a new member** |
| **C-14** | not-assessed representation | **only if reclassified from F to A** |
| **Wisdom** | Wisdom as a core concept | **yes — V.3**, and only if the programme chooses to open it |
| *conditional* | **C-2** (if ruled a new invariant) · **C-3** (if contexts 6→7) · **C-11** (if the member is vestigial) · **C-7** (if the model changes) | |

**Six certain, four conditional.** Only **Wisdom** requires no action to remain lawful.

### 5.2 · Boundary questions — **B**

**F-CM-1b** (selection placement) · **C-3** (authority adequacy, if a tenth capability) · **C-4** (targeting) · **C-10** (evidence resolvability). Conditional: **C-6** (if invalidation re-enters via the gate) · **C-16** (if idempotency is port-side).

**All four touch the Verification Port, and the Port Contract is ⬜ PROPOSED / unratified.** That is a single shared dependency worth noting: a ruling on any of them may need the contract's ratification act to land.

### 5.3 · Domain-model questions — **C**

**C-7** (member/record cardinality) · **C-6** (reference semantics) · **C-5** (coordination locus) · **F-CM-2** (identity semantics). Conditional: **C-18**'s remedy · **C-11** (if the member is vestigial) · **C-14** (Confidence representation).

### 5.4 · Capability questions — **D**

**C-1** (typing) · **C-11** (Confidence derivability) · **C-16** (idempotency) · **C-9** (anti-capability register) · **C-3** (if a tenth capability).

**C-1 is the root of this group** — re-typing changes what the other three are questions *about*.

### 5.5 · Fitness / assurance questions — **E**

**C-8** (identity continuity) · **C-12** (relation integrity) · **C-9** (register tests) · **C-2** (if ruled a fitness constraint).

**All are blocked by C-2.** A fitness test's assertion depends on whether determinism is a required property.

### 5.6 · Deferred implementation questions — **F**

**C-5** (DS-CAND-3 → DEF-1, like DS-CAND-2) · **C-14** (currently ruled F) · **C-2** (if ruled DEF-1) · **C-10** (if deferred to Port Contract ratification).

**Standing DEF-1 items untouched by this workbook:** how the boundary is realized (commands · methods · domain services · policy evaluation), which is why the mapping's items 5 and 7 remain deferred.

### 5.7 · Documentation / process questions — **G**

**CC-1** (diagram disposition) · **C-13** (reasoning chain as procedure).

**Neither changes architecture.** Both may be ruled immediately and independently.

### 5.8 · Consistency check — **H**

**C-19.** Requires **no decision to execute** — only a disposition if a conflict is found.

### 5.9 · Independent questions — rulable in any order

**Wisdom · CC-1 · C-13 · C-19 · C-5.** Independent of every other item and of each other.

**Near-independent** (only one prerequisite each): **C-12** and **C-8** (both need only C-2) · **C-14** (needs only C-11) · **C-6** (needs only C-10).

### 5.10 · Questions blocked by other rulings

| Blocked | By | Reason |
|---|---|---|
| **C-2** | C-18 | determinism unverifiable across an amendment with no version binding |
| **C-8 · C-12 · C-9** | C-2 | a test's assertion depends on whether determinism is required |
| **C-4** | C-3 · F-CM-1b | *what authorizes* and *may the core compare* both bear on targeting |
| **C-16 · F-CM-2** | F-CM-1b | idempotency needs a sameness comparison whose placement F-CM-1b fixes |
| **C-14** | C-11 | if the member is vestigial the representation question dissolves |
| **C-7** | C-15 · C-17 | both propose state-vocabulary changes |
| **C-6** | C-10 | staleness follows from whether resolvability is checkable |
| **F-CM-1b** | F-CM-1a | placement is cleaner once the distinction is settled *(soft — separable)* |

**Ten of 22 are blocked.** Twelve are rulable now.

### 5.11 · Questions that could silently change architecture if implemented

Ranked by consequence, from the pack's §S.4 and refined here:

| # | ID | Silent change |
|---|---|---|
| 1 | **C-4** | the mechanism names the `KnowledgeId` → **obligation 4 narrowed with no ruling** |
| 2 | **C-11** | Confidence computed from something → trivial, or a **hidden reasoner** |
| 3 | **F-CM-1** | the boundary compares candidates for sameness → **semantic arbiter**, breaching ⟨C-1⟩ and §16 |
| 4 | **C-3** | any well-formed authority reference treated as sufficient → **INV-KOS-AUTHORITY-001 at half strength** |
| 5 | **C-2** | T-2 unrunnable → **the anti-reasoner constraint loses its only detector** |
| 6 | **C-17** | claim admitted, no state wrong → **defect invisible in operation** |
| 7 | **C-18** | after the first amendment, prior admissions **unreproducible** |
| 8 | **C-15** | retraction collapses into `REJECTED` |
| 9 | **C-6** | justification retained that no longer exists, with fan-out |
| 10 | **C-16** | replay duplicates, **or** dedup is added and ⟨C-1⟩ breaks |
| 11 | **C-9** | recency or a weight decides truth |
| 12 | **C-1** | nine things built, two of which are prohibitions |

**Five are 🔴 HIGH: C-4 · C-11 · F-CM-1 · C-3 · C-2.**

### 5.12 · Questions requiring constitutional amendment

**Certain if the option is taken:** C-15 (state/event) · C-17 (state) · Wisdom (V.3) · F-CM-1a (if a §15 row) · C-18 (if a new member) · C-14 (if reclassified).
**Conditional:** C-2 (new invariant) · C-3 (contexts 6→7) · C-11 (member vestigial — also reopens OQ-5) · C-7 (model change) · C-6 (justification-over-time) · C-4 (only under the second-gate option, which breaches INV-KOS-VERIFICATION-001 and is recorded for completeness only).

### 5.13 · Questions resolvable without changing law

**C-1** (mapping) · **C-5** (a locus record) · **C-8** · **C-12** · **C-9** (fitness additions) · **C-13** (process) · **CC-1** (documentation) · **C-19** (a check) · **C-7 O1** (a stated rule) · **C-6 O1** (as-of-admission) · **C-10 O1** (as-supplied) · **C-16 O1** (state non-idempotence) · **C-11 O1** (enumerate structural inputs) · **C-2 O2** (fitness constraint) · **C-4 O1** (obligation 4 clarified) · **C-3 O3** (reject as a domain responsibility) · **C-14 O1** (confirm the deferral).

> **Seventeen of the 22 have at least one option that requires no law change.** Only **Wisdom**, **C-15** and **C-17** have no such option in their leading forms — and each of those has a **`REJECT` / `RECORD ONLY`** option that also leaves law untouched.

### 5.14 · Questions to record but not solve yet

**C-19** (execution pending, no decision needed) · **C-14** (if the F deferral is confirmed) · **C-5** (a record, DEF-1-deferred) · **C-17** (if recorded as an accepted limitation) · **C-6** (if deferred pending C-10) · and the two **RESEARCH REQUIRED — NOT AUTHORIZED** items from the critique: the invariant-conflict meta-rule, and whether a structurally-derived Confidence carries epistemic meaning. **Neither is authorized and neither is researched here.**

---

## 6 · Special-test results

The commission's eleven special tests, applied and reported.

| Test | Applied to | Result |
|---|---|---|
| **MINIMALITY** — *what invariant becomes invalid if this element changes independently?* | the two aggregates and the twelve members | **Boundary survives.** `ConflictRecord`'s inclusion rests on the **atomicity** ground (§7's joint locus + Article 8.3), not conceptual proximity. **Confidence** and **Relations** remain the weakest atomicity proofs — confirmed independently by the corpus's own pair-by-pair falsification. **No element's inclusion is argued here from usefulness, convenience or workflow participation** |
| **PROTECT vs PRODUCE** | all capabilities | Holds. **🚩 ANTI-REASONER RISK FLAGGED on C-11** (Confidence with no lawful input) and on **F-CM-1b** (selection). **C-10** weakens *evidence admission* to accepting unverified references |
| **SEMANTIC INVARIANCE** | the fitness family | **T-2 presupposes determinism (C-2)**. No new semantic engine introduced or proposed |
| **IDENTITY** | C-8 · C-16 · F-CM-2 · C-4 | *Identity is assigned, never derived* — preserved. The five continuities are **distinguished, not conflated**: law fixes **identity continuity** (§8, INV-KOS-IDENTITY-001); **semantic · epistemic-state · contextual · relationship** continuity are **not fixed by law and are recorded unresolved**, per the commission's instruction not to infer either direction |
| **STATE** | C-7 · C-15 · C-17 · C-14 | Law establishes seven states as **authoritative members**, not projections (§9's *no implicit transition* · INV-KOS-PROJECTION-001). Where a needed distinction exceeds law it is **recorded as a law gap**, not theorised |
| **AUTHORITY** | C-3 | The five senses are separated: **reference · validity · adequacy · ownership · determination**. Law fixes reference and ownership (the core references, never holds). **Adequacy is unhomed**; **determination** by the core *"requires strong justification and may violate the boundary"* — recorded, not decided |
| **EVIDENCE / JUSTIFICATION / CONFIDENCE** | C-10 · C-11 · C-14 | Kept separate throughout. *evidence exists* ≠ *sufficient* ≠ *true* ≠ *confidence high*. **Confidence has no lawful input and none was invented — the gap is recorded** |
| **CONTRADICTION** | F-CM-1 · C-7 · C-17 | *ambiguity · difference · contextual variation · perspective · uncertainty · contradiction* are **not equated**. Where law does not distinguish two concepts the architecture needs, the **law gap is recorded** and **no state was invented** |
| **REJECTION / NON-ADMISSION** | C-15 · C-17 · the failure-path trace | The six senses are distinguished: **pre-domain refusal · admission refusal · post-admission rejection · `UNKNOWN` · `REJECTED` · `WITHDRAWN` · `SUPERSEDED`**. The *"rejection is preserved"* vs *"non-admitted candidates are not domain state"* tension was **resolved by the ⟨Z-1⟩ ruling and applied as r5** — the exact constitutional conflict was identified rather than implemented around |
| **EXISTING AGGREGATES** | C-4 | All five sub-questions asked. **What identifies the target is unspecified** (C-4); *what authorizes* is C-3; the aggregate owns the transition; identity continuity is INV-KOS-IDENTITY-001; **the current mapping does not describe this path** — the admission path was **not** assumed sufficient for the whole lifecycle |
| **RELATIONS** | C-12 · C-5 · C-7 | Atomicity test applied. ⟨C-4⟩ makes relations **references**, so the boundary preserves a supplied representation rather than owning the relation's lifecycle — which is **why C-6 and C-10 bite** |
| **CONTEXT** | *(no open item)* | The five senses noted; `ContextTuple` is constitutive of identity (Article 1.4). **No context import is proposed** |
| **ZERO / ABSENCE** | the 15-condition pass | Used **as a falsification mechanism**, not to complete the model. **⟨Z-1⟩ preserved**: absence of a constitutive prerequisite is **pre-domain, not an epistemic state**. **No absence was converted into a convenient state** — C-17 is recorded as a gap precisely because inventing a state was refused |
| **C-19 / ECOSYSTEM** | C-19 · CC-1 · CC-2 | Used **only** to ask whether the boundary violates established dependency, responsibility or ownership. **Direction validated downward.** EKS/PKS/AIP treated as validation context; **nothing was moved into the Kernel for another component's convenience** |

---

## 7 · HPA DECISION SHEET

**One row per decision. The ruling field was deliberately EMPTY when this workbook was delivered; this workbook does not decide.**

> ⚖️ **Rulings received are entered as they arrive. 1 of 24 filled** (F-CM-1a, HPA 2026-08-25). All others remain open.

| ID | Decision | Altitude | Options | Consequence (headline) | Amendment? | Dependency | **HPA RULING** |
|---|---|---|---|---|---|---|---|
| **Wisdom** | Wisdom as a core concept? | A (V.3) | status quo / open a V.3 act | inaction is safe | only if opened | none | |
| **C-15** | retraction representation? | A | state / event / revise-existing / reject | states 7→8 or events 10→11 | yes (O1/O2) | none | |
| **C-17** | internally impossible claim? | A | state / port route / reject / record | drift is **invisible** | yes (O1) | none | |
| **F-CM-1a** | record *Ambiguity ≠ Contradiction*? | A | §15 row / ⟨C-5⟩ suffices / defer | gates F-CM-1b | yes (O1) | none | **⚖️ RULED 2026-08-25 — O3 DEFER / NO NEW §15 ROW.** Necessity not demonstrated; the terminology belongs to F-CM-1b. `AMBIGUOUS` retained as implementation-drift risk, **not** a constitutional defect. F-CM-1b **OPEN and widened** ("which actor establishes semantic distinctness"). §15 stays at **11 rows**. |
| **C-18** | bind the constitutional version? | A→C | member / EvidenceLink / History / outside / reject | unblocks **C-2** | yes (O1) | none | |
| **C-14** | reclassify not-assessed? | A or F | confirm F / reclassify A / ⟨C-5⟩ suffices | realization would implement the collapse | yes (O2) | **C-11** | |
| **C-3** | who checks authority **adequacy**? | B or A | capability / 7th context / reject / port | 🔴 half-strength invariant | yes (O2) | none | |
| **F-CM-1b** | is selection inside or outside? | B | outside+plural / outside+resolve-first / inside | 🔴 semantic arbiter risk | yes (O3) | F-CM-1a *(soft)* | |
| **C-10** | who verifies reference resolvability? | B or F | as-supplied / port vocabulary / defer | capability may be vacuous | no | none | |
| **C-1** | re-type the nine capabilities? | D | revise mapping / F-record first / reject | root of the D group | no | none | |
| **C-11** | lawful input for Confidence? | D→A/C | structural / port / vestigial / record | 🔴 **ANTI-REASONER RISK** | yes (O3) | **C-1** *(soft)* | |
| **C-4** | how is an existing `KnowledgeId` targeted? | B | ob-4 clarified / port element / (2nd gate) / defer | 🔴 **highest drift risk** | yes (O3 only) | **C-3 · F-CM-1b** | |
| **C-16** | is admission idempotent? | D | not idempotent / mechanism-side / consult-vs-derive | replay duplicates | possible (O3) | **F-CM-1b** | |
| **F-CM-2** | is non-unique identity intended? | C | confirm / revise identity semantics | plural identity by design | possible | **F-CM-1b** | |
| **C-7** | `CONFLICTED` ↔ record cardinality? | C | state a rule / invert / model change / defer | records may disagree | yes (O3) | **C-15 · C-17** | |
| **C-6** | disposition of a stale reference? | C or A | as-of-admission / re-enter via gate / constitutional | justification decays silently | yes (O3) | **C-10** | |
| **C-5** | record a third coordination locus? | C→F | record DS-CAND-3 / reject | ⟨C-4⟩ coupling risk | no | none | |
| **C-2** | determinism: property, test, or DEF-1? | **A/E/F** | invariant / fitness / DEF-1 / reject | 🔴 T-2 unrunnable without it | yes (O1) | **C-18** | |
| **C-8** | assert identity continuity? | E | add test / add + record 4 continuities / reject | most important invariant untested | no | **C-2** | |
| **C-12** | assert relation integrity? | E | add test / supplied-representation | cheapest gap to close | no | **C-2** | |
| **C-9** | add three register entries? | D+E | all three / Article 11 only / reject | recency decides truth | no | **C-1 · C-2** | |
| **CC-1** | disposition of the historical diagram? | G | supersede / annotate / retain | two pictures coexist | no | none | |
| **C-13** | require the reasoning chain? | G | require / guidance / reject | C-1 recurs | no | none | |
| **C-19** | execute the remaining consistency check? | H | commission / accept two / defer | can only clear or falsify | no | none | |

**24 rows for 22 items** (F-CM-1 and C-16 are each split into two decisions the HPA may take separately).

---

## 8 · Stop

**This workbook is DECISION SUPPORT ONLY · PROPOSED · NON-AUTHORITATIVE · HPA DECISION REQUIRED.**

Nothing was implemented. The Kernel was not modified. The boundary was not revised. v1.1, the Constitution, the Port Contract and the aggregate model are untouched. No aggregate, context, event or state was created. No DSL, NLP, parser or FST architecture was introduced. No research was started and no closed track reopened. No brainstorming concept was promoted to architecture. No further philosophical analysis was performed. No implementation architecture was produced. **No substantive architectural answer was selected** — field 13 throughout recommends only the *phrasing* of a ruling.

**Register 25+4 · contexts 6→6 · aggregates 5→5 · members 12→12 · events 10→10 · states 7→7 · invariants 11→11 · Constitution FROZEN · research CLOSED · SNF CLOSED · OQ-4 UNAUTHORIZED · AH-5 deferred.**

**The next actor is the HPA/ARB. Implementation remains ungated. The Kernel is not built.**

---

## Traceability

- **Commission:** HPA, 2026-08-23 — *KOS KERNEL — HPA ADJUDICATION COMMISSION*: governed decision-support executor; strict source hierarchy; altitude classes A–H; dependency graph before numerical order; thirteen mandatory fields; the eleven special tests; C-19 direction validated downward; fifteen aggregate sections; an HPA DECISION SHEET with the ruling field left empty; explicit stop condition.
- **Source hierarchy honoured:** (1) Constitution / v1.1 **r5** law · (2) HPA rulings — the boundary acceptance, ⟨Z-1⟩ readings, K-1, AH-1…AH-5, OQ-2/3/5, the C-19 reframing · (3) aggregate and invariant definitions · (4) F-1…F-5 (amendments ⬜ **OPEN**) · (5) Kernel Boundary Definition (`20260823-1306`) · (6) Capability Mapping (`20260823-2103`) · (7) Independent DDD Critique (`20260823-2154`) incl. §25 · (8) Scope ADR (`20260823-2229`) · (9) Adjudication Pack + Referent Map (`20260823-2249`) · (10) historical brainstorming — **evidence and hypothesis only, never authority; no observation promoted to law.**
- **Corrections recorded in this act:** the pack's item count (**22, not 21**; completeness **13, not 12**) — the pack's text stands unrewritten per ES-004.3.
- **Status:** 📋 **DECISION SUPPORT ONLY · PROPOSED · NON-AUTHORITATIVE · HPA DECISION REQUIRED.** 22 items · 24 decisions · 8 altitudes · 12 rulable now · 10 blocked · 5 at 🔴 HIGH silent-drift risk · 17 with at least one no-law-change option. **The Kernel is not built.**
