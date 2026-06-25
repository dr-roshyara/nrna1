# Round 40-05 — Governance Architecture Review Board

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Governance **theory** review (NOT method review) · **Under MB-39.1 (frozen)**
**Role:** External Constitutional Review Board — Senior Governance / Constitutional / Election-Security / Strategic-Domain Architects.
**Status:** 🧭 REVIEW REPORT (findings, not design changes). Reference under review: `Round40-03`, `Round40-04` — **not modified**.
**Date:** 2026-06-25

> **Mandate.** Challenge the governance architecture *as if to reject it*. Produce findings only. **No** methodology change, **no** new mechanisms, **no** software/DDD, **no** edits to 40-03/04, MB-39.1, the Register, or the Constitution. Methodology observations are *informational only* and route through the established ADR process — not actioned here.
> **Issue tags:** **[CONFIRMED]** (a real, agreed issue) · **[POTENTIAL GAP]** (plausible, needs evidence) · **[OPEN RQ]** (genuine research question) · **[DEFER→F-REV]** (best resolved by the next discovery).

---

## A. Completeness

| ID | Finding | Tag |
|----|---------|-----|
| GAR-A1 | **Eligibility / Franchise integrity** — *who may participate* (voter-roll legitimacy, enfranchisement) is owned by no current family (F-AUTH appoints officials, not voters; F-PROC assumes a roll; F-THR defends a roll it does not define). | **[CONFIRMED]** missing family candidate |
| GAR-A2 | **Coercion-resistance / receipt-freeness** — protecting the voter's *free* choice (vote-buying, coercion, family pressure — acute in diaspora settings) is only partially inside F-THR. It is a deep, distinct voting-security property and may be its own capability. | **[POTENTIAL GAP]** |
| GAR-A3 | **Voter identity assurance** — authenticating *voters* (distinct from appointing *officials*, F-AUTH) is unmodeled. The platform's code/anonymity model touches it but no governance capability owns it. | **[POTENTIAL GAP]** |
| GAR-A4 | **Constitutional-change governance** — who governs amendment of the *governance constitution itself*? A meta-capability above all families, currently implicit (the program has S-1 entrenchment but no explicit *amendment* capability). | **[OPEN RQ]** |
| GAR-A5 | Transparency: family vs cross-cutting concern — recurs strongly; standing unresolved. | **[OPEN RQ]** |

**Section verdict:** completeness is the **weakest dimension.** At least one **confirmed** missing family (Eligibility) and two plausible gaps (coercion-resistance, voter identity). The family set is **not demonstrably complete.**

---

## B. Cohesion (family boundaries)

| ID | Finding | Tag |
|----|---------|-----|
| GAR-B1 | **F-THR is cross-cutting, not a peer** (Round40-04 X-01): it protects every other family. "Capture resistance" may be a *quality attribute of every family* rather than a sibling of them. | **[OPEN RQ]** |
| GAR-B2 | **F-AUTH + F-REV** both inhabit "Oversight & Adjudication" — candidate **merge** into one meta-family (appointment + review of the same bodies). | **[DEFER→F-REV]** |
| GAR-B3 | **F-PROC may split** — "process integrity" (voting) vs "result/tally integrity" (counting/certification) have different actors and failure modes. | **[POTENTIAL GAP]** |

**Section verdict:** boundaries are **provisional**. The F-THR peer-vs-cross-cutting question (B1) is the most consequential — it changes the shape of the whole taxonomy and should be settled before any ontology is frozen.

---

## C. Orthogonality

| ID | Finding | Tag |
|----|---------|-----|
| GAR-C1 | **Capability families are NOT orthogonal** — F-THR explicitly spans the others (B1). The "family" set is therefore **not an orthogonal basis**; it mixes peer families with a cross-cutting concern. | **[CONFIRMED]** |
| GAR-C2 | **Constitutional properties may be correlated** — S-1 (entrenched mandate) and S-3 (appointment insulation) both serve *independence*; S-4 (jurisdiction) and S-5 (standing) both serve *authority-to-act*. The five may reduce to ~3 underlying principles (independence · finality · contestability). | **[OPEN RQ]** (observation only — Constitution not modified) |
| GAR-C3 | **Candidate domains may overlap** — "Independence" is arguably a *property of* "Oversight & Adjudication," not a separate domain. | **[OPEN RQ]** |

**Section verdict:** the architecture does **not** currently claim orthogonality, and C1 confirms it is not orthogonal. This is acceptable *if stated* — but the ontology round must not silently treat families/properties/domains as independent axes.

---

## D. Dependency correctness

| ID | Finding | Tag |
|----|---------|-----|
| GAR-D1 | **F-PROC→F-AUTH may be over-stated** — process integrity could hold under illegitimate administrators *if* integrity is cryptographically/structurally guaranteed (end-to-end verifiability). The dependency may be *weaker* than "Direct." | **[OPEN RQ]** |
| GAR-D2 | **GRP-THR-REV is irreducible given Option B** — the recursion is real and has no internal terminus; it is a *consequence* of choosing functional (not structural) independence, not an error. | **[CONFIRMED]** |
| GAR-D3 | Could any dependency disappear under stronger platform guarantees? (e.g. F-THR detection less dependent on F-PROC evidence given verifiable receipts) — but this edges into the **software domain** and must stay gated. | **[DEFER→F-REV]** |

**Section verdict:** dependencies are mostly sound; D1 is a genuine candidate over-claim worth testing. D2 is correctly modeled.

---

## E. Hidden recursion (beyond GRP-THR-REV)

| ID | Finding | Tag |
|----|---------|-----|
| GAR-E1 | **Appointment ↔ Accountability** — if appointed bodies hold the appointers accountable, a second recursion exists (who disciplines the appointer?). | **[POTENTIAL GAP]** hidden recursion |
| GAR-E2 | **Audit ↔ Evidence (meta-audit)** — auditors verify evidence, but who audits the auditors / guarantees evidence integrity? Potential infinite-regress unless terminated. | **[POTENTIAL GAP]** |
| GAR-E3 | **Constitutional self-interpretation** — S-5 is *standing to challenge self-interpretation*; the body interprets its own mandate → the known **Meta-CVI** recursion. | **[CONFIRMED]** (already named; managed not eliminated) |
| GAR-E4 | **Review ↔ Oversight** — F-REV's legitimacy depends on oversight, which depends on appointment, which F-REV may adjudicate → candidate cycle. | **[DEFER→F-REV]** |

**Section verdict:** Round 40 found **one** recursion; this review finds **at least three more candidates** (E1, E2, E4) plus the confirmed Meta-CVI (E3). **Recursion is more pervasive than 40-04 stated** — a significant finding. The architecture may have a *family* of recursion points, not a single GRP.

---

## F. Missing actors

| ID | Finding | Tag |
|----|---------|-----|
| GAR-F1 | **Sponsor / funder** — an economic-capture actor (the program itself runs on *sponsor authority*); withdrawal or pressure is a real capture vector, unmodeled as an actor. | **[CONFIRMED]** |
| GAR-F2 | **Technical supply chain / vendors** — supply-chain capture is in the threat taxonomy (Round40-02 §5) but has **no actor**. | **[CONFIRMED]** (taxonomy/actor mismatch) |
| GAR-F3 | **Host-country authorities** — diaspora members sit under real external jurisdictions; relevant to coercion and legal capture. | **[POTENTIAL GAP]** |
| GAR-F4 | Constitution-amender (links GAR-A4). | **[OPEN RQ]** |

**Section verdict:** three real actor gaps, two **confirmed** (sponsor, supply chain). The sponsor gap is notable because the program's *own* governance (sponsor authority) is an unmodeled capture surface.

---

## G. Missing lifecycle

| ID | Finding | Tag |
|----|---------|-----|
| GAR-G1 | **Inter-election / dormant period** — capture often happens *quietly between elections*; the lifecycle (Round40-02 §3) runs pre-election→archival but omits the dormant governance period. | **[CONFIRMED]** |
| GAR-G2 | **Constitutional founding / bootstrapping** — the moment *before any election*, where founding-stage maturity risk (F-4) actually originates, is not a modeled stage. | **[POTENTIAL GAP]** |
| GAR-G3 | Institutional-learning phase — present as a feedback loop (40-03 D3) but not as a first-class lifecycle stage. | **[OPEN RQ]** minor |

**Section verdict:** the lifecycle is election-centric and **omits the between/before-election periods** where slow capture and maturity failure live. G1 confirmed.

---

## H. Missing failure modes

| ID | Finding | Tag |
|----|---------|-----|
| GAR-H1 | **Constitutional deadlock** — S-2 (final rulings) + S-1 (entrenchment): a captured body issues *final, entrenched* rulings that cannot be overturned → entrenchment-enabled capture lock-in. | **[CONFIRMED]** (variant of legitimate-threshold capture) |
| GAR-H2 | **Silent legitimacy erosion** — gradual delegitimization with no single detectable failure event; the detection loop is event-oriented and may miss it. | **[POTENTIAL GAP]** |
| GAR-H3 | **Institutional decay** — the slow Archive→Learning loop never matures (founding-stage F-4); a *non-event* failure. | **[CONFIRMED]** (already the dominant residual risk) |
| GAR-H4 | **Participation collapse** — low turnout / apathy as a legitimacy failure independent of capture. | **[POTENTIAL GAP]** |

**Section verdict:** the failure model is strong on *acute* capture but **weak on slow/silent failures** (erosion, decay, apathy) — the failure modes that event-driven detection structurally misses.

---

## I. Cross-family semantic consistency

| ID | Finding | Tag |
|----|---------|-----|
| GAR-I1 | **"Independence" is polysemous** — *source*-independence (F-AUTH), *functional/vantage*-independence (F-THR), *decisional*-independence (F-REV). Three senses under one word → the single most important ontology risk. | **[CONFIRMED]** semantic split |
| GAR-I2 | **"Oversight" vs "Authority" vs "Adjudication"** overlap and are used loosely across families. | **[OPEN RQ]** |
| GAR-I3 | **"Evidence"** — F-PROC audit trail vs F-THR detection evidence vs F-REV adjudication evidence: *appear* consistent (reviewable record) but unconfirmed. | **[OPEN RQ]** |
| GAR-I4 | **"Legitimacy"** — used as the emergent apex everywhere; consistent, but never *defined*. | **[POTENTIAL GAP]** undefined keystone term |

**Section verdict:** semantic consistency is **not yet established.** GAR-I1 ("independence" = 3 concepts) and GAR-I4 (undefined "legitimacy") would each corrupt a DDD ubiquitous language if frozen now. **This is the dimension most blocking for Strategic DDD.**

---

## J. Scientific simplicity

| ID | Finding | Tag |
|----|---------|-----|
| GAR-J1 | **Merge candidate:** F-AUTH + F-REV → "Oversight & Adjudication" (GAR-B2). | **[DEFER→F-REV]** |
| GAR-J2 | **Reclassify candidate:** F-THR from family → cross-cutting quality (GAR-B1, C1). | **[OPEN RQ]** |
| GAR-J3 | **Constitutional reduction candidate:** S-1..S-5 → ~3 principles (independence/finality/contestability) (GAR-C2). | **[OPEN RQ]** observation only |
| GAR-J4 | **Domain reduction candidate:** Independence as a property of Oversight, not a peer domain (GAR-C3). | **[OPEN RQ]** |

**Section verdict:** several real simplifications are available, all **deferred** (acting now would be premature and would touch the Constitution). Recorded for the ontology round.

---

## Consolidated issue register

| Tag | Count | IDs |
|-----|------:|-----|
| **[CONFIRMED]** | 8 | A1, C1, D2, E3, F1, F2, G1, H1, H3, I1 *(10 — see note)* |
| **[POTENTIAL GAP]** | 9 | A2, A3, B3, E1, E2, F3, G2, H2, H4, I4 |
| **[OPEN RQ]** | 9 | A4, A5, B1, C2, C3, D1, G3, I2, I3, J2, J3, J4 |
| **[DEFER→F-REV]** | 4 | B2, D3, E4, J1 |

*(Counts indicative; several issues carry secondary tags. The point is the distribution, not the arithmetic.)*

**Highest-severity confirmed findings (would damage a DDD ontology or assurance claim if left unaddressed):**
1. **GAR-I1** — "Independence" is three distinct concepts under one term.
2. **GAR-A1** — a confirmed missing family (Eligibility / Franchise).
3. **GAR-E1/E2/E4** — recursion is a *family*, not a single point.
4. **GAR-H2/H3** — slow/silent failures (erosion, decay) under-modeled.
5. **GAR-F1** — the sponsor (the program's own authority) is an unmodeled capture surface.

---

## Verdict — is the governance architecture stable enough to proceed to F-REV?

**Structurally:** yes — the meta-model, dependency graph, responsibility model, and state machine are internally consistent (40-03 D9 confirmed; this review found no *structural* contradiction).

**Semantically and for completeness:** **no — not yet.** Three classes of confirmed issue stand between the current state and a defensible governance ontology:
- **semantic polysemy** (GAR-I1 "independence"; GAR-I4 undefined "legitimacy"),
- **a confirmed missing family** (GAR-A1 Eligibility) and the unresolved **F-THR peer-vs-cross-cutting** question (GAR-B1),
- **under-modeled recursion and slow-failure modes** (GAR-E*, GAR-H2/H3).

**Recommendation (review board):**
1. Record the **[CONFIRMED]** findings in a **Governance Decision Record** (the theory-side analogue of an ADR — *not* an ADR-M; methodology stays frozen).
2. Proceed to **Round 40-06 Governance Ontology** to resolve the semantic findings **first** (especially GAR-I1) — a stable ontology is the prerequisite for both F-REV discovery quality and eventual Strategic DDD.
3. Treat the **missing-family** candidates (Eligibility, coercion-resistance) and the **F-THR reclassification** as **explicit discovery targets** — F-REV is the natural occasion to test them, but the *taxonomy* question (B1) should be provisionally settled in the ontology round so F-REV runs against a stable frame.
4. **Do not** run the Methodology Governance Review yet — it correctly remains *after* the governance theory stabilizes (the methodology has low residual uncertainty; the theory has high).

**Gates preserved:** MB-39.1 frozen · Register locked · methodology unchanged (all observations informational) · Strategic DDD closed (every candidate domain/term remains observational; GAR-I1 explicitly *blocks* premature ubiquitous-language adoption).

---

*Round 40-05 — Governance Architecture Review Board — REPORT ISSUED.*
*~30 findings across A–J; 10 confirmed. Architecture is structurally consistent but semantically/completeness-unstable. Recursion is a family, not a point. Top blockers: "independence" polysemy, missing Eligibility family, slow-failure modes, sponsor actor. Recommend: Governance Decision Record → Round 40-06 Ontology → (taxonomy settled) → F-REV → Methodology Governance Review. MB-39.1 FROZEN · DDD GATED.*
