# Book integration, sources S0001–S0005 (M-04 steps 3–7 and the five-file review)

| Field | Value |
|---|---|
| Date | 2026-10-18 (≈21:25Z) |
| Method | M-04 v1.0 (approved) and its working practice. This file carries out steps **3** (compare with the book), **4** (examine by reasoning), **5** (write into the book), **6/6a** (light record and completeness against the source) and **7** (look back), plus the **review after five files** |
| Why a separate file | Chapters 0–2 are versioned baselines (sha256 prefixes: Ch. 0 `c2b74357…`, Ch. 1 `562230ad…`, Ch. 2 `c4eb8452…`) and are not edited in this session. The additions below are the **text to enter the book**, keyed to the section each one extends. **Placement under `tmp/theory/` is PROPOSED** (rules file, Part F); it follows the established instruction to keep research artifacts under `tmp/` |
| Evidence | each item cites a verified record and quote, e.g. `E-S0003 Q6`. All quotes in E-S0001…E-S0005 PASS the anchor check |

**Labels:**
- step 3 labels: New · Repeat · Refinement · Contradiction · Evidence · Open;
- markers: [Src] the source says · [Ctx] other corpus documents say (contextual lookup) · [Ext] external · [I] my interpretation · [Hyp] hypothesis.

---

## Part 1 — Disposition of every meaningful idea (steps 3, 6, 6a)

| # | Idea | Source evidence | Book today | Label | Disposition |
|---|---|---|---|---|---|
| W-01 | Authority has two parts: an **immutable provenance** and a **standing changed only by acts** | E-S0003 Q6 (L56); E-S0002 L37 ("authority = provenance × standing") | Ch. 1 §1.2 states exactly this (provenance immutable; standing mutable only by acts) | **Repeat + Evidence** | the book already explains it; S0002/S0003 are **two further occurrences, same date, same origin** (the Engineering Progression Model), so they count as **one** source of support, not three (M-04 rule 2: repetition is not proof) |
| W-02 | "×" in "provenance × standing" | E-S0002 L37; E-S0003 L56 | Ch. 1 §1.2 treats them as **two separate components** (a pair) | **Refinement** | text W-02 below: the book's reading (a pair, not a product) is the one consistent with "immutable vs act-changed"; the source never defines × |
| W-03 | Promotion needs two keys: **evidence earns, governance grants** | E-S0002 Q8 | Ch. 1 §1.6 ("nothing but an act confers authority") | **Evidence** | supports §1.6; adds the *necessity of evidence* side, which §1.6 does not state. Text W-03 |
| W-04 | **Ownership is conferred by adoption.** A candidate without an owner is correct; an **operating** asset without an owner is a defect (one instance: the Runtime Adapter) | E-S0001 Q11, Q12, Q13, Q20 | Ch. 1 §1.6 (ownership ≠ authority); §1.11 (who owns method, binding, evidence) | **New (refinement of §1.6/§1.11)** | text W-04 |
| W-05 | **Shared ownership is evidence against separate bounded contexts, not proof of one**; viewpoints are projections, not contexts | E-S0001 Q7, Q8 | not in the book (no "viewpoint" anywhere) | **New** | text W-05; status: source conclusion, n = 1 |
| W-06 | Ownership boundaries follow **spaces** (platform → knowledge platform → product), not viewpoints | E-S0001 Q9, Q10, Q21 | Ch. 1 §1.11's platform/product split is compatible | **New, Hyp** | text W-06; held as a hypothesis, as the source itself downgraded it |
| W-07 | KnowledgeOS's purpose is to cure **"model amnesia"**: re-deriving what is already known | E-S0002 Q10 | Ch. 1 §1.8 records GA's finding that the August work re-derived an existing ontology "because nobody searched first" | **Repeat + Evidence** | S0002 names the phenomenon (C-1, n = 11) and applies it to itself by refusing re-discovery |
| W-08 | **Survival ≠ sufficiency:** every kernel element was portable, but the set failed to bootstrap a product | E-S0002 Q9 (L50–52) | Ch. 1 §1.8 (loop never closed) touches on this; the compositional point is absent | **New** | text W-08 |
| W-09 | The loop's **head (generation) is unproven (n = 0); its tail (software → evidence) is observed** | E-S0005 Q6, Q7; E-S0002 L36 | Ch. 1 §1.8: "the loop has never closed"; generator identity | **Repeat + Refinement** | S0005 locates the break **at exactly one edge**. Text W-09 |
| W-10 | KnowledgeOS **governs** retrieval and does not compete with it: policy administration/decision (KnowledgeOS) vs enforcement (retrieval boundary), as in the XACML PAP/PDP/PEP split | E-S0003 Q10, L42; E-S0005 L19 | Ch. 2 §2.9 ("how strongly a rule binds is not what enforces it"); Ch. 1 §1.14.5 (enforcement presupposes ratification) | **Evidence + Refinement** | external standard supports the separation; text W-10 |
| W-11 | **No rule says when a RULE stops being true, only when a document does**; retirement machinery exists but retirement was never observed | E-S0004 Q8, Q9 | Ch. 0 §0.3: status is governed **claim by claim** (GKC); I-1 "knowledge never silently disappears"; Ch. 1 §1.16.2 temporal authority | **Refinement + Contradiction-in-time** | GKC (earlier) already puts status on claims; on 2026-08-03 the corpus treats "artifact or rule?" (MM-4) as **open**. Both kept. Text W-11 |
| W-12 | The knowledge states Candidate · Approved · Current · Deprecated · Historical · Retired · Superseded · Archived | E-S0004 Q7 | Ch. 0 §0.3 governance status: Draft · Canonical · Superseded/Historical · Deprecated · Archived | **Refinement + Open** | the 2026-08-03 list adds Approved, Current, Retired. Text W-12 ([Hyp]: more than one dimension) |
| W-13 | **"Prompt" has no place in the capability model** (Capability ≠ Script ≠ Prompt ≠ Adapter ≠ Implementation) | E-S0002 Q7, Q12 | not in the book | **Open** | recorded as a corpus open question; Ch. 1 §1.9 (realization ladder) is the nearest home |
| W-14 | **External validation cannot close an internal link** | E-S0005 Q9 | not stated | **New (epistemic rule)** | text W-14 |
| W-15 | AIP-14: KnowledgeOS is a **Supporting** subdomain; the Core/Generic inversion is conditional | E-S0002 L34 | Ch. 1 §1.8 cites AIP-14 (cited-only) | **Repeat** | S0002 is a second, independent occurrence (different document type), so cited-only becomes cited twice |
| W-16 | Internal models stay internal until a **second adopter**; n = 1 claims are marked | E-S0001 L31, L55; E-S0003 L30, L89 | Ch. 1 §1.8: "a responsibility becomes a component only at n ≥ 2" (LA-4) | **Evidence** | the same parsimony threshold, applied to *generalization claims* as well as to components |
| W-17 | Research discipline: no research without a named question; five-question protocol; admission rule | E-S0004 Q4, Q6; E-S0005 L9 | — | **Excluded from theory scope** | it is the **programme's method**, not the KnowledgeOS theory. Kept as evidence of how the corpus was produced (relevant to source reliability) |
| W-18 | Freeze order: three live streams; no new domains, capabilities, layers, folders or terminology | E-S0004 Q12 | — | **Excluded from theory scope (historical context)** | a governance state of the programme on 2026-08-03; recorded for chronology |
| W-19 | Identifier collisions (I-9, C-1, C-5, R-n mean different things in different documents) | E-S0001 §10, E-S0002 §10, E-S0004 §10 | Ch. 1 §1.10 ("identity of knowledge items needs governed names") | **Evidence** | the corpus itself exhibits the defect §1.10 describes. Text W-19 |

**Completeness against the source (6a).** For each of S0001–S0005, every claim row in the record (§4) was matched to a W-item or excluded:

| Record | Claim rows | Mapped | Excluded (with reason) |
|---|---|---|---|
| E-S0001 | P1–P9 | P1, P2, P3 → W-05 · P4, P5 → W-06 · P6 → W-04 · P7 → W-05 (governance asset located; supports W-05's single-owner census) · P8 → W-05 · P9 → **Open**, kept (fifth viewpoint; presentation question) | — |
| E-S0002 | P1–P10 | P1 → W-07 · P2, P3 → book Ch. 0 §0.1 (**Repeat**: the charter's "to be validated or falsified") · P4 → **Excluded** (programme domain list PD-1…PD-6; not yet theory, revisit when the domain documents are read) · P5 → W-15 · P6 → W-10 (context map) · P7, P9 → W-08/W-09 · P8 → **Open** (I-7 stated and breached; revisit when I-7's source is read) · P10 → W-13 | P4 (reason given) |
| E-S0003 | P1–P8 | P1, P4 → W-10 · P2 → **Excluded** (market positioning) · P3 → W-16 · P5, P6 → W-01/W-11 (provenance dispersed) · P7 → W-17 · P8 → **Excluded** (positioning sentence; sponsor decision) | P2, P8 |
| E-S0004 | P1–P7 | P1 → W-11 · P2 → W-11/W-12 · P3, P4 → W-01 (provenance) · P5 → W-17 · P6 → W-11 (validity over TIME untested) · P7 → W-18 | — |
| E-S0005 | P1–P10 | P1 → W-10 · P2, P3 → **Evidence** for the edges R-3, R-4, R-5, R-8, R-10, which map to the book: R-3 → Ch. 1 §1.5 (knowledge ≠ carrier), R-4 → §1.14.4, R-5 → W-03, R-8 → W-15, R-10 → Ch. 1 §1.9 · P4–P7 → W-09 · P8 → W-14 · P9 → **Evidence** for Ch. 0 §0.2 (PKS vs context engineering: extends, not identical) · P10 → **Evidence**, n = 1 (OE-KOS-1) | — |

---

## Part 2 — Text for the book (step 5)

Each block is written to be inserted at the named section. Plain language first, then precision.

### W-02 → Ch. 1 §1.2, after the rule "provenance cannot have a state machine"

**Is authority a product?** Two sources of 2026-08-03 write "authority = provenance × standing" [Src: E-S0002 L37; E-S0003 Q6]. Neither defines ×.

The surrounding words fix the intended reading: provenance is *immutable* and standing is *act-changed* [Src: E-S0003 L56]. Two components, one of which can never change while the other changes only by acts, are a **pair** (provenance, standing), not a number. A product would let a change in one factor compensate for the other, which the sources nowhere allow.

**Status:**
- [I] the pair reading;
- the source's notation stays as written;
- TODO — UNDEFINED in the source (R-01-T5).

### W-03 → Ch. 1 §1.6, as a second half

**Evidence is necessary, not sufficient.** The programme's own rule is "evidence EARNS · governance GRANTS · promotion requires BOTH" [Src: E-S0002 Q8]. §1.6 says no property except an act confers standing. This rule adds the converse condition: an act of promotion **without** evidence is also not the programme's practice.

In business terms, there are two keys. The evidence key shows the knowledge is worth trusting; the authority key is turned by someone entitled to turn it. Neither opens the door alone.

**Status:**
- [Src] the rule, as programme practice;
- [I] that it is a *necessary condition on acts*: the source states it for promotion only.

### W-04 → Ch. 1 §1.11, new paragraph "When a missing owner is a defect"

Ownership is something **adoption confers**: "a candidate with an owner would be adoption smuggled past the gate" [Src: E-S0001 Q11]. Two different situations therefore look the same in a register:

| Situation | Missing owner means |
|---|---|
| the item is a **candidate** (not adopted) | the system is working correctly |
| the item is **operating** (has effect in production) | a real defect: something acts and nobody may decide about it |

In 2026-08 one instance of the defect was found, the Runtime Adapter [Src: E-S0001 Q12, Q20].

**Rule for the theory [I]:** an empty owner field is a defect **only in conjunction with operational effect**. Absence alone is not a finding. This matches Ch. 1 §1.17.3 ("not found is not unauthorized").

### W-05 → new section in Ch. 1 or Ch. 2 (placement open): "Ownership as evidence of boundaries"

The corpus tested the DDD heuristic "ownership signals bounded contexts" on its own five viewpoints. All platform-side viewpoint assets have **one owner** [Src: E-S0001 L27]. The author first concluded "one owner ⇒ one context", then corrected it:

> shared ownership is **evidence against** separate contexts, but **insufficient by itself** to establish a single one [Src: E-S0001 Q7].

The counterexample: one architect may own three genuine contexts.

**Theory statement:** viewpoints are **projections** of one model, not separate domains; they must not be owned or governed separately [Src: E-S0001 Q8]. The conclusion rests on three supports: ownership, a "language test" (no viewpoint has its own vocabulary) and an ARB line ("complementary siblings"). Only the first is shown in this source.

**Status:** source conclusion, candidate; the language test is TODO — UNDEFINED (R-01-T1).

### W-06 → same section, as a hypothesis

Where ownership **does** change, it changes between **spaces**: the platform (Decision Authority), the knowledge platform (its space owner) and the product (the producing team) [Src: E-S0001 Q9]. The author downgraded "the space map is the real ownership map" to a **hypothesis**, observed on one platform [Src: E-S0001 Q10, Q21]. Kept as [Hyp], n = 1.

### W-08 → Ch. 1 §1.8, after "the loop has never closed"

**Every part travels; the whole does not.** The success test was "if PublicDigit disappeared, what of KnowledgeOS survives?" It was run as an experiment, not imagined: a fresh session received only the candidate kernel and tried to bootstrap a different product.
- **Every element survived unchanged.**
- **The set still failed:** there was no route to start a new knowledge space, roles that one person cannot fill, and one product-coupled piece [Src: E-S0002 Q9, L50–52].

**Theory statement [I]:** portability of a platform is a property of the **set**, not of its elements. Elementwise independence is necessary and not sufficient.

### W-09 → Ch. 1 §1.8, refine "the loop has never closed"

The full KnowledgeOS claim is a loop: discover → model → govern → generate → evolve a product knowledge space → guide product engineering → build software → collect evidence → improve KnowledgeOS [Src: E-S0005 L55–57]. As of 2026-08-03:
- the **tail** is observed (software built; evidence collected);
- the **head** is not: **generation has never happened, inside or outside the platform** (n = 0) [Src: E-S0005 Q6, Q7].

The loop is broken at one place.

The author adds that the broad claim is **harder** to prove than the narrow one [Src: E-S0005 Q8].

**Note:** "no external instance found" is a search result, not proof of absence.

### W-10 → Ch. 2 §2.9, new paragraph "Deciding is not enforcing"

KnowledgeOS **decides**; something else **enforces**. In the standard access-control architecture (XACML) these are three separate points:
- where policy is administered (PAP);
- where a decision is made (PDP);
- where it is enforced (PEP) [Ext: OASIS XACML 3.0; the source's fetched pages, E-S0005 L100].

The corpus maps this as governance → DP-n/capability → retrieval or runtime boundary [Src: E-S0005 L19; E-S0003 L42], and concludes that KnowledgeOS **governs retrieval and competes with none of it** [Src: E-S0003 Q10].

**Status:**
- [Src+Ext] the separation;
- the **DP-n layer** between principles and capabilities is the corpus's own, externally unmatched hypothesis [Src: E-S0005 L19];
- DP-n is TODO — UNDEFINED until its source is read.

### W-11 → Ch. 0 §0.3 and Ch. 1 §1.16.2, cross-reference note "When does a rule stop being true?"

On 2026-08-03 the corpus records a gap: the platform has machinery to retire **documents** (superseded, archived), "but no rule says when a RULE stops being true, only when a document does", and retirement "has NEVER been observed in several registers" [Src: E-S0004 Q8, Q9]. Whether lifecycle state belongs to the artifact or to the rule it carries is recorded as **open** (MM-4) [Src: E-S0004 L36].

The book's foundation (GKC) had already put status on **claims**, not documents (Ch. 0 §0.3). The two positions are kept side by side:
- **GKC:** state attaches to claims (a model statement);
- **2026-08-03:** whether it does is an open question for the *platform* (a realization statement).

**[I] No contradiction in substance:** the model says *where* state should live; the platform record says it does not yet live there. This is the "STATE ≠ AUTHORITY" pattern of §1.14.1 applied to time. It is also the business gap behind this lane's TD-001 work (validity of a claim over time).

### W-12 → Ch. 0 §0.3, note on the status dimension

The 2026-08-03 list of knowledge states (Candidate, Approved, Current, Deprecated, Historical, Retired, Superseded, Archived) [Src: E-S0004 Q7] extends GKC's governance status (Draft, Canonical, Superseded/Historical, Deprecated, Archived).

**[Hyp]** The list mixes at least two questions:
- **adoption:** Candidate → Approved;
- **currency and retention:** Current, Deprecated, Superseded, Historical, Retired, Archived.

Per the book's model-integrity rule, a new dimension is admissible only if orthogonal, necessary and sufficient. Not decided here (R-01-T11).

### W-14 → Ch. 1 §1.16.8 ("acceptance requires an attack"), add a rule

**Outside evidence validates patterns, never this platform's realization of them.** The capability → runtime chain is standard practice externally, yet internally the link between capability and execution asset is undeclared; "external validation cannot close an internal link" [Src: E-S0005 Q9].

**[I]** Confidence from literature attaches to the *type* of relation; whether *this* system realizes it needs internal evidence.

### W-19 → Ch. 1 §1.10 (governed names), evidence paragraph

The corpus itself shows the defect §1.10 describes. The same short identifiers name different things in different documents:
- "I-9" = "governance-status ⟂ authority" (Relationship Ontology) and "exactly one canonical owner" (knowledge-domain-model report);
- "C-1" = "model amnesia" and the engine rule "absence never implies ownership";
- "C-5" = "correction propagation" and "separation attestation";
- "R-n" = residue items, relationship rows and validation rows

[Src+Ctx: E-S0001 §10, E-S0002 §10, E-S0004 §10]. An identifier without its namespace is ambiguous.

---

## Part 3 — Reasoning checks (step 4), for the new items

| Item | Premises | Counterexample sought | Result |
|---|---|---|---|
| W-04 | ownership is conferred by adoption; operation implies decisions are being made | an operating item that legitimately needs no owner (e.g. a pure read-only mirror)? | **conditional:** a mirror with no decisions to make might need no owner. Refined wording: "operating **and** subject to change decisions". TODO — check against later sources |
| W-05 | shared ownership; language test; ARB line | one owner, several contexts (the source's own counterexample) | **survives only as evidence**, exactly as the source's REV 2 states |
| W-08 | elements portable; the set fails | — | **holds** as an empirical finding (one experiment, n = 1) |
| W-09 | generation n = 0 inside; none found outside | an external platform that generates governed knowledge spaces | **open:** the search scope is unstated (R-01-T14) |
| W-14 | external = pattern-level; internal = instance-level | a case where external evidence *does* prove an internal link (e.g. a certified component used unchanged) | **conditional:** holds unless the internal component *is* the externally validated artefact. Refine when evidence appears |

---

## Part 4 — Look back (step 7)

1. **Missed anything?** Re-reading the five records against the source claim rows found one omission in the first pass: E-S0002 P8, I-7 "stated AND breached". It is now carried as **Open** (Part 1). E-S0001 P9 (fifth viewpoint) is carried as Open.
2. **Does the book still make sense?** The additions touch Ch. 0 §0.3, Ch. 1 §§1.2, 1.6, 1.8, 1.10, 1.11, 1.16.2, 1.16.8 and Ch. 2 §2.9. None changes an established definition.
   - W-02 confirms §1.2's pair reading.
   - W-11 places a 2026-08-03 open question next to the GKC model without overriding either.
   - No full-book sweep is needed: no established term changed meaning (working-practice rule for step 7, pending E-3).

## Part 5 — Review after five files (M-04 quality check)

**Decision: CORRECT, then CONTINUE.**

| Finding | Action |
|---|---|
| **Process defect:** S0001–S0005 were recorded (extraction) but not compared with or written into the book (M-04 steps 3–7), and the rules file omitted those steps | corrected by this file; the rules file is corrected by an appended, dated section (nothing deleted) |
| **Process defect (earlier):** S0105–S0116 were read without records | stand as unread for progress purposes; re-read in order (already logged) |
| **Content:** S0001–S0005 are all **CANDIDATE, AI-generated, never authoritative without human review** (each record §1) | every W-item carries the source's own status. None is treated as a ruling |
| **Source independence:** S0001–S0005 come from one programme on one day and largely cite each other (S0003 ↔ S0004 ↔ S0005) | their agreement counts as **one** line of support (M-04 rule 2) |
| **Independent AI review** | **not run:** corpus content may not go to a sub-agent without the Principal's separate approval (M-04 correction 1; G-LOG-01xx §5). Recorded, not a blocker |

**Next:** S0006 (`KnowledgeOS_Engineering_Decision_Model_Validation.md`; index date 2026-07-11, which precedes S0001–S0005's 2026-08-03, so its date will be checked from the file itself).
