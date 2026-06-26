# Round 46 / LIT-2 — Translation & Terminology Review

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** LIT-2 (external calibration) · **Under MB-39.1 (frozen)**
**Status:** 📚 TRANSLATION & TERMINOLOGY REVIEW — external positioning of **Domain Knowledge Package v1.0.** **FORBIDDEN to redesign governance theory/ontology/admissibility.** Outputs: Terminology Mapping Matrix + Canonical Vocabulary Report.
**Date:** 2026-06-26

> **⚠️ Source caveat.** Literature here is drawn from **model training knowledge**, not live-fetched primary sources. Specific scholarly attributions (Beetham, Pettit, Scharpf, Weber, PKI/MDA usage) are **confidence-graded** and flagged **[verify]** where a citation would be made in a publication. This is calibration, not a systematic review.
> **Charter discipline.** LIT-2 may rename concepts for interoperability and record limitations/positioning — it may **NOT** reopen the governance theory. Any governance limitation surfaced is routed to a Research Question, not actioned.

---

## 1. Terminology Mapping Matrix

| Internal term | Literature equivalent(s) | Domain | Recommendation | Conf. |
|---------------|--------------------------|--------|----------------|-------|
| **Semantic Projection** | ontology-to-model transformation; PIM→PSM (MDA/MDE); ontology grounding | ontology eng. / MDE | **Adopt** "ontology-to-model transformation" as formal term; retain "Semantic Projection" as shorthand | Med [verify] |
| **Truth-of-Record** | **System of Record (SoR)** | enterprise arch. / data mgmt | **Align** → System of Record | High |
| **Truth-of-Computation** | Read Model / Projection (CQRS); Derived View; materialized view | DDD/CQRS | **Align** → Derived View / Read Model | High |
| **Mandate** | mandate (legal); delegation of authority; authorization grant (RBAC) | admin law / RBAC | **Retain** "Mandate" (precise legal term); DDD note: Authorization Grant | Med-High |
| **Finality** | **res judicata**; final judgment; terminal state | law | **Retain**; anchor to *res judicata* | High |
| **Determination / CaseDecision / Ruling** | adjudication; determination; judgment; ruling | law | **Synonyms — canonicalize to ONE** (recommend **"Determination"**) | Med |
| **Trust-Anchor** | **Root of Trust / Trust Anchor (PKI)** — *cryptographic, different domain* | security/PKI | **Disambiguate** → "Constitutional Trust-Anchor" (NOT a PKI key) | High (collision) |
| **Authority-Delegation** | delegation of authority; delegated powers | admin law / org theory | **Align** → "Delegation of Authority" | High |
| **Independence (4 facets)** | **judicial independence taxonomy**: institutional/structural · decisional/adjudicative · administrative · **appearance of independence** | legal theory | **Adopt established sub-names** (see §3) — strong corroboration | High [verify] |
| **Legitimacy** | Beetham (legality+justifiability+consent); Weber (traditional/charismatic/legal-rational); input/output/**throughput** legitimacy (Scharpf/Schmidt) | political science | **Retain** (emergent); position vs Beetham | High [verify] |
| **Consent** | consent of the governed (Locke/social contract); tacit vs express; "manufactured consent" (Herman/Chomsky) | political theory | **Retain**; note social-contract lineage | Med-High [verify] |
| **Contestability** | **contestatory democracy (Pettit, republicanism)** — contestability as alternative to consent | political theory | **Retain**; see limitation L-1 | Med [verify] |
| **Anonymity** | **ballot secrecy**; (related, stronger: receipt-freeness, coercion-resistance) | voting systems | **Align** → "ballot secrecy"; note receipt-freeness = blocked RQ-COERCE | High |
| **Evidence / Review / Appeal / Audit** | standard legal terms (judicial review, appeal, audit) | law | **Retain**; disambiguate "Review" (overloaded) | High |

---

## 2. Canonical Vocabulary Report

**Names that SURVIVE (retain):** Evidence · Finality · Legitimacy · Consent · Anonymity · Mandate · Appeal · Audit · Contestability.

**Names that CHANGE / align to established terms:**
- Truth-of-Record → **System of Record**
- Truth-of-Computation → **Derived View / Read Model**
- Semantic Projection → **ontology-to-model transformation** (retain as shorthand)
- Authority-Delegation → **Delegation of Authority**

**Names to DISAMBIGUATE (term collision):**
- Trust-Anchor → **Constitutional Trust-Anchor** (distinct from PKI Root-of-Trust).
- Anonymity → tie to **ballot secrecy** to avoid generic "anonymity" connotations.

**SYNONYMS to collapse (pick ONE before DDD):**
- Determination = CaseDecision = Ruling → **canonicalize to "Determination."** *(naming these differently across hundreds of classes later is the expensive error this report prevents.)*

**OVERLOADED terms (require qualifier):**
- **"Review"** — *Constitutional* Review (interpret rules) vs *Case* Review (adjudicate a dispute) vs *Audit* Review (check evidence). Always qualify.
- **"Independence"** — never use unqualified (4 facets — see §3).
- **"Authority"** — institutional vs decisional vs delegated (qualify).

**PROHIBITED names (from Forbidden Transformations):** no class/aggregate named bare "Independence"; no persisted "Legitimacy" entity; no "TrustAnchor" entity (external).

---

## 3. Terminological correspondence — Independence ↔ judicial-independence scholarship

*(Wording note: "correspondence," not "corroboration" — our independently-derived concepts **align with** established terminology/classifications; this is alignment, not proof.)*

The program **independently** split Independence into Institutional / Operational / Decisional / Perceived (Round 41). This **corresponds** to established **judicial-independence** theory **[verify against primary sources]**:

| Our facet | Established term |
|-----------|------------------|
| Institutional | institutional / structural independence |
| Operational | administrative independence |
| Decisional | decisional / adjudicative independence |
| **Perceived** | **appearance of independence** ("justice must be *seen* to be done") |

**This is a positioning win (correspondence, not proof):** an independently-discovered taxonomy aligning with established legal theory strengthens the result's *credibility* (and supplies ready terminology) — it does not *prove* the theory. **Recommendation:** adopt the established sub-names; retain the dependency-chain finding (Decisional⟹Operational⟹Institutional) and the consent-coupling of "appearance" as the program's *novel* contributions.

---

## 4. Positioning findings & one limitation (routed, NOT actioned)

- **Novelty (genuinely new vs literature):** the **ternary Trust-Anchor {enforcement, consent, tradition}** as a *recursion-termination* construct; the **consent-coupling of perceived independence**; the **Semantic Projection / persistence-split** as an explicit research-to-DDD QA layer; the **Translation Assurance** gate. These appear to be the program's distinctive contributions [verify].
- **Correspondence (already in literature):** System of Record, read-model/projection, res judicata, judicial-independence facets, Beetham legitimacy — our terms have established equivalents; adopt them for interoperability.
- **⚠ Limitation L-1 (overlooked alternative):** **Pettit's republican "contestatory democracy"** argues legitimacy rests on **contestability** rather than **consent.** Our meta-architecture made **consent** the NRNA-class anchor. *Does contestability rival consent as the anchor?* **Per LIT-2 charter this is NOT redesigned** — routed to a new governance question **RQ-ANCHOR-01** (does contestability-vs-consent change the anchor analysis for NRNA's class?), for a *future* governance round, not now. Notably our model *already* contains Contestability as a (context-dependent) concept and S-5 standing — so the tension may be reconcilable, but that is governance work, deferred.
- **No literature found that invalidates the translation strategy** (ontology→projection→ownership→admissibility is consistent with MDE/ontology-engineering practice) [verify].

---

## 4b. Conceptual positioning + boundary comparison (LIT-2 scope expansion)

*(Per review: LIT-2 is terminology **+ conceptual positioning + boundary comparison** — never governance redesign.)*

**Conceptual positioning — where each artifact sits in the ontology-engineering stack** [verify]:
```
Governance Theory      ≈ Conceptual model (domain-level)
Governance Ontology v1 ≈ formal Domain Ontology (Guarino-style; axioms = our AX-1..9)
Semantic Projection    ≈ ontology→model transformation (MDA PIM→PSM; ontology grounding)
DDD Landscape/Contexts ≈ Information / Logical model
Implementation         ≈ Physical model
```
So **Semantic Projection** is precisely the *ontology→information-model transformation* step — established as a category in ontology engineering / MDE, though our explicit **Translation Assurance** + **persistence split** appear to be additions.

**Boundary comparison — our Ownership Architecture vs enterprise ontology** [verify]:
- *Similar to:* DEMO (Enterprise Ontology — actor roles, transactions, responsibility) and ArchiMate (business actor/role/collaboration) — both assign responsibility/ownership.
- *Different from:* ours derives ownership from **constitutional governance** and is defined as much by **what it refuses to own** (anonymity = no owner; enforcement = unowned; legitimacy = emergent/unowned) — *negative ownership* is not standard in DEMO/ArchiMate.
- *Boundary:* our "ownership" = **semantic ownership of authoritative truth** (systems-of-record), **not** organizational-structure modeling. Adopt DEMO/ArchiMate vocabulary where it aids communication; do **not** adopt their org-modeling frame.

**Finding:** terminology aligns (System of Record, Read Model, ontology-to-model transformation, judicial-independence facets); the **conceptual position** (ontology→information-model transformation) is established; the **distinctive boundary** is *negative/semantic ownership* + Translation Assurance + persistence split. No governance redesign.

## 5. Conclusion (feeds Round 46B)

LIT-2 **calibrated terminology without disturbing the theory.** Several internal terms align to established vocabulary (System of Record, Derived View, Delegation of Authority, res judicata, judicial-independence facets, ballot secrecy); a few must be disambiguated (Constitutional Trust-Anchor) or collapsed (Determination); "Review/Authority/Independence" are overloaded and must always be qualified. One genuine **limitation (L-1, Pettit contestability)** is recorded as **RQ-ANCHOR-01** for future governance work — explicitly **not** actioned. The corroboration of the four Independences against judicial-independence scholarship is a credibility win and a terminology gift.

**For 46B:** terminology is now calibrated → the Canonical Vocabulary is the seed of the eventual Ubiquitous Language. The final gate may incorporate this + the 46C carried findings (TA-1/2/3).

```
46A internal-ready ✓ → 46C assurance ✓ → LIT-2 (this) ✓ → Round 46B FINAL gate → Strategic DDD
```

---

*Round 46 / LIT-2 — Translation & Terminology Review — ISSUED (calibration only; no governance redesign).*
*Terminology Mapping Matrix + Canonical Vocabulary Report. Align: SoR / Read-Model / Delegation-of-Authority / res-judicata / ballot-secrecy. Disambiguate: Constitutional Trust-Anchor. Collapse: Determination. Overloaded: Review/Authority/Independence. Corroboration: 4 Independences ↔ judicial-independence theory. Limitation L-1 (Pettit contestability) → RQ-ANCHOR-01 (deferred). Sources = training knowledge [verify]. MB-39.1 FROZEN · DDD GATED.*
