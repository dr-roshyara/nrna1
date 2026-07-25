# EPIC-002 Relationship Pattern Selection

**Kind:** strategic design decision document — the **final Strategic DDD activity**. **Role:** architect performing design decisions from accepted truths, not researcher discovering new ones.
**Fixed architectural decisions (constitutional constraints — not questioned, not reopened):** the Canonical Context Map is ACCEPTED · Scenario C (Hybrid) is adopted · Collection and Contemporaneous Record-Fixing are separate Bounded Contexts · CB-3-Alt remains an annotation on Adjudication · the Strategic DDD methodology is complete · no further governance artifacts.
**Inputs (only):** `EPIC-002_Canonical_Context_Map.md` (ACCEPTED) and the previously accepted EPIC-002 artifacts. No new sources, no new analysis.
**Decision criteria (exclusive):** business ownership · autonomy · reasons to change · model protection · dependency direction · organizational responsibility · language ownership. **Communication frequency is not a criterion. Implementation convenience is not a criterion. The absence of a pattern is a valid conclusion.**
**Out of scope:** APIs, events, commands, repositories, services (as implementation constructs), modules, packages, databases, messaging, CQRS, aggregates, entities, code, deployment, Tactical DDD. *(Note: "Open Host Service" below is the strategic DDD context-mapping pattern of that name, not an implementation service — no interface is defined anywhere in this document.)*

---

## Edge Decisions

### COL-1 — Collection & Aggregation → Adjudication (the evidence body)

**Relationship: Customer–Supplier** (Collection upstream/supplier · Adjudication downstream/customer).

**Rationale.** Dependency direction is settled by the map (one-directional, never reversed). The decisive question is whether the downstream has legitimate influence over the upstream — and it does, constitutionally: the adjudicative side's evidentiary requirements (sufficiency, kinds, completeness) rightfully drive collection priorities, and the **declare-failure obligation is the customer's bargaining power made explicit** — Adjudication does not accept whatever arrives; it refuses to certify on insufficient evidence. A relationship where the downstream's needs shape upstream planning, backed by a real refusal mechanism, is Customer–Supplier by definition. Language ownership stays clean: Collection owns evidence-kind vocabulary; Adjudication owns sufficiency/scrutiny vocabulary.

**Alternatives considered.** *Conformist* — rejected: declare-failure is precisely non-conformity; a Conformist Adjudication would certify whatever it received, which the evidence base treats as "theater." *Partnership* — rejected: they change for different reasons (source/format/acquisition-policy changes vs. standards-of-review changes) and do not co-evolve. *ACL* — considered seriously because of K2 (separability), but rejected as the edge pattern: keeping evidence distinguishable from judgment is a model-discipline obligation *inside* Adjudication (differentiated scrutiny is its own typing), not a translation defense against a hostile upstream model. *Shared Kernel* — rejected: sharing a model kernel across this boundary would collapse exactly the separation K2 protects.

**Consequences.** Collection retains autonomy over *how* it aggregates; Adjudication retains authority over *what suffices*. Future Tactical DDD must give the customer's requirements an explicit expression (left to that phase). The upstream may not silently redefine evidence kinds without the customer's contract being revisited.

---

### COL-2 — Contemporaneous Record-Fixing → Adjudication (the fixed record)

**Relationship: Conformist** (Adjudication conforms to the record as fixed).

**Rationale.** This is the rare case where Conformist is not an organizational weakness but a **constitutional invariant deliberately chosen**: contemporaneity (P3, Chenery/hard-look) means the reviewing side takes the record exactly as it was fixed at act-time, with zero influence over it at determination time. The record's evidentiary standing *derives from* its non-negotiability. Any pattern granting the downstream influence over the upstream artifact would violate the invariant the boundary exists to protect. Model protection is achieved by conformity itself: an unaltered record is the protection.

**Alternatives considered.** *Customer–Supplier* — rejected for the current record: it would imply Adjudication can demand changes to what was fixed, which is post-hoc rationalization by another name. (Prospective influence exists — see COL-5b — but it is a different edge with a different temporal scope.) *ACL* — rejected: translating the record on ingestion would undermine the very as-fixed property that gives it authority. *Partnership / Shared Kernel* — rejected outright; no co-evolution, no shared model.

**Consequences.** Adjudication's model must be able to *receive* records it did not shape — a real constraint on future Tactical DDD. Record-Fixing evolves solely for its own reasons (recording/legal/timestamping requirements), never on adjudicative demand for a live record.

---

### COL-3a — Custodial Integrity → Adjudication (custody attestation, custodial streams)

**Relationship: Customer–Supplier** (Custodial Integrity upstream/supplier · Adjudication downstream/customer).

**Rationale.** Business ownership: what a determination demands of a chain of custody — attribution, continuity, tamper-evidence thresholds — is defined by the adjudicative side (admissibility requirements flow from where evidence is weighed), and custody practice exists to serve those requirements. That downstream-shapes-upstream influence with a real supplier obligation is Customer–Supplier. The custody vocabulary (custody log, possession events, hash comparison — DF/NIST language) stays owned by Custodial Integrity; Adjudication consumes attestations as one evidence kind under its own differentiated scrutiny.

**Alternatives considered.** *Published Language* — considered seriously: custody-log conventions are genuinely standardized (NIST SP 800-86). Rejected as the governing pattern because the standardization serves a *specific downstream's admissibility requirements*, not an open population of consumers — the relationship's driver is the customer's demands, and the standard form is how the supplier meets them. *Conformist* — rejected: Adjudication does not adopt DF vocabulary into determination language; it weighs attestations under its own model. *Open Host Service* — rejected: custody attestation is institution-facing, not published to arbitrary observers.

**Consequences.** If adjudicative admissibility requirements change, custody documentation practice must follow — the dependency's direction is explicit and owned. Custodial Integrity's internal mechanics remain autonomous.

---

### COL-3b — Self-Verifying Integrity → Adjudication (public-verifiability result, self-verifying streams)

**Relationship: Open Host Service with Published Language** (one combined decision — the canonical pairing). ***[SUPERSEDED on adversarial review → Published Language alone; see the Adversarial Design Review section. Original text preserved as history.]***

**Rationale.** The business purpose of self-verification is that **any observer, not a privileged downstream, can verify** — the evidence base's "no canonical verifier" finding is this pattern stated as a design fact: the context publishes a stable, documented protocol (the published language) and hosts verification openly (the open host), so that independently-built verifiers — Adjudication among them, but never exclusively — can consume it. A downstream-specific contract (Customer–Supplier) would contradict universal verifiability at the level of business purpose, not merely convenience. Language ownership is unambiguous: the verification protocol is the self-verifying context's own published model.

**Alternatives considered.** *Customer–Supplier* — rejected: privileging Adjudication as *the* customer would re-introduce a canonical verifier, which the ruled model's own evidence explicitly rejects. *Conformist* — inapplicable in this direction; see COL-4b for where conformity genuinely lives. *Shared Kernel* — rejected: public verifiability requires the protocol be consumable *without* sharing internals.

**Consequences.** The published protocol becomes a stability commitment: changes to it are breaking changes for every independent verifier, so it evolves deliberately and versioned-in-spirit (mechanics left to Tactical DDD). Adjudication holds no special standing over the protocol — by design.

---

### COL-4a — Contemporaneous Record-Fixing → Custodial Integrity (the fixed record as custody's object)

**Relationship: No pattern — deliberate model-blind handoff.** *(The absence of a pattern is the decision, not an omission.)*

**Rationale.** Custody is **deliberately model-blind**: tamper-evidence operates on the record as an opaque artifact (multi-point digest comparison does not parse what it protects), and possession-trail vocabulary has no dependency whatsoever on the record's internal model. There is a real existential dependency (custody needs an object) but **no semantic relationship to govern** — no translation, no conformity, no contract about meaning. Imposing a model-mediating pattern here would manufacture coupling where the domain's own mechanism (opacity) is the protection. Autonomy is maximal precisely because neither side understands the other's model — and that is the design.

**Alternatives considered.** *Conformist* — rejected: conformity presupposes adopting a model; custody adopts none. *Customer–Supplier* — rejected: custody imposes no requirements on how records are fixed, and record-fixing imposes none on how custody is kept. *ACL* — rejected: there is nothing to translate.

**Consequences.** Either context can evolve its internal model with zero impact on the other — the loosest coupling on the map, and it must be *kept* that way: any future proposal that custody should "understand" record contents is an architectural regression against this decision.

---

### COL-4b — Contemporaneous Record-Fixing → Self-Verifying Integrity (fixing-as-commitment, self-verifying streams)

**Relationship: Conformist** (Record-Fixing conforms to Self-Verifying Integrity's published language, for the streams assigned to it).

**Rationale.** This is the fusion finding, preserved by ruling as an edge property, now given its architectural expression: for a stream to be publicly self-verifiable, the record must be **created in the verifiable form from the moment of fixing** (commitment at act-time is the fixing). The verification protocol's model is owned and published by Self-Verifying Integrity (per COL-3b); Record-Fixing *adopts* that model for those streams. Language ownership is asymmetric — the protocol is not negotiated per-record-producer — so the correct pattern is downstream conformity to the published language, not partnership.

**Alternatives considered.** *Partnership* — the serious alternative (fixing and verification must work in lockstep for these streams), rejected because partnership implies joint model ownership and aligned mutual evolution; here one side owns and publishes the model and the other adopts it. *No pattern (as COL-4a)* — rejected: unlike custody, self-verification is emphatically **not** model-blind; the record's very form is prescribed. The asymmetry between COL-4a and COL-4b is real and intended — it is the hybrid ruling made visible.

**Consequences.** For self-verifying streams, record-fixing practice cannot evolve independently of the published protocol — a genuine, accepted constraint that Tactical DDD must respect. For custodial streams (COL-4a) it remains fully independent. Stream assignment (deferred, per the map) therefore determines which regime each stream's fixing lives under.

---

### COL-5a — Adjudication → Collection (insufficiency finding / correction demand, conditional)

**Relationship: Subsumed into COL-1's Customer–Supplier contract — no separate pattern.**

**Rationale.** A declare-failure outcome flowing back to Collection *is* the customer expressing unmet requirements — the return channel of the Customer–Supplier relationship already selected, not a second relationship. Assigning it an independent pattern would double-govern one contract and invite drift between the two halves.

**Alternatives considered.** *Customer–Supplier (separately)* — rejected as redundant; *Partnership* — rejected: a conditional corrective demand is not aligned co-evolution.

**Consequences.** The conditional backward flow is bounded by the contract: Adjudication may demand *more or better evidence*; it may not direct *how* Collection works. This containment answers the watch-item recorded in the collaboration analysis (the risk of adjudication becoming a de-facto controller of upstream contexts) — the pattern choice is the control.

---

### COL-5b — Adjudication → Contemporaneous Record-Fixing (record-deficiency finding, prospective only)

**Relationship: Customer–Supplier, prospective-only** (Adjudication customer · Record-Fixing supplier — **with an explicit temporal constraint**).

**Rationale.** Deficiency findings legitimately shape *future* recording requirements — the adjudicative side is the authority on what a record must contain to survive review, and record-fixing practice serves that. But the contract carries a hard constitutional limit inherited from COL-2: **it may never reach back to an already-fixed record.** The pair (COL-2 Conformist for the live record; COL-5b Customer–Supplier for future practice) is not a contradiction — it is the temporal structure of the domain made explicit: conformity at determination-time, influence only prospectively.

**Alternatives considered.** *Conformist (extending COL-2 to cover this edge)* — rejected: it would mute legitimate prospective influence and freeze recording practice against learning. *No pattern* — rejected: the influence is real and needs an owner and a boundary, precisely so it cannot silently grow into retroactive reach.

**Consequences.** Tactical DDD must keep the two temporal regimes distinguishable. Any mechanism that lets a deficiency finding alter an existing record violates both this decision and P3.

---

### Custodial Integrity ↔ Self-Verifying Integrity (no edge on the map)

**Relationship: Separate Ways** (explicit, not an oversight).

**Rationale.** The two integrity contexts are alternatives assigned per stream — and for belt-and-suspenders streams, their entire redundancy value comes from **independence of assurance channels**: each assures the stream on its own model, each reports to consumers on its own edge, and neither consults the other. Coupling them — shared model, mutual awareness, coordinated attestation — would collapse the redundancy the hybrid ruling exists to provide. Separate Ways is the pattern that *names* this deliberate non-relationship so it cannot be "helpfully" bridged later.

**Consequences.** No shared vocabulary, no cross-attestation, no dependency in either direction. If a future need for correlation between the two assurances arises, it belongs in a consumer (e.g., Adjudication weighing both), never between them.

---

### COL-6 — Authority-Validity (annotation)

**Relationship: none assigned — not an edge.** CB-3-Alt remains an annotation on Adjudication per the fixed rulings. A relationship pattern would presuppose a context that has not been ruled to exist. If the split is ever ruled, pattern selection for its edges happens then, not preemptively now.

---

## Decision Summary

| Edge | Relationship | One-line ground |
|---|---|---|
| COL-1 | Customer–Supplier | Declare-failure is the customer's bargaining power made explicit |
| COL-2 | Conformist | Conformity to the fixed record IS the constitutional invariant (P3) |
| COL-3a | Customer–Supplier | Admissibility requirements flow from where evidence is weighed |
| COL-3b | ~~Open Host Service +~~ Published Language *(revised on adversarial review)* | "No canonical verifier" — the openness lives in the published language; no host exists |
| COL-4a | No pattern (model-blind handoff) | Opacity is the protection; coupling would be regression |
| COL-4b | Conformist (to the published language) | Fixing-as-commitment: the record's form is prescribed for these streams |
| COL-5a | Subsumed into COL-1 | The return channel of one contract, not a second relationship |
| COL-5b | Customer–Supplier, prospective-only | Influence over future practice; never over a fixed record |
| Integrity pair | Separate Ways | Independence of assurance channels is the redundancy's value |
| COL-6 | None — annotation | No ruled context, no edge, no pattern |

**Patterns deliberately used nowhere:** *Shared Kernel* (rejected on every edge — no two contexts share a model kernel; K2 and tamper-evidence both depend on boundaries not sharing internals) · *Partnership* (rejected on every edge — no pair co-owns a model or co-evolves by agreement; every real influence found was asymmetric).

---

## Strategic Architecture Consistency Review (quality gate — checklist, not a new artifact)

| # | Question | Answer |
|---|---|---|
| 1 | Does every Bounded Context have a clear ownership boundary? | **Yes.** Collection owns evidence-kind language and aggregation; Record-Fixing owns act-time recording and its legal/procedural requirements; Custodial Integrity owns custody vocabulary and the possession trail; Self-Verifying Integrity owns the published verification protocol; Adjudication owns determination, sufficiency, and scrutiny language, plus D1 — and carries the CB-3-Alt annotation explicitly. |
| 2 | Is every relationship pattern consistent with the canonical model? | **Yes.** Every decision traces to a ruled fact: C–S on COL-1/COL-3a/COL-5b to declare-failure and admissibility ownership; Conformist on COL-2 to P3; OHS+PL on COL-3b to no-canonical-verifier; the COL-4a/COL-4b asymmetry to the hybrid ruling's fusion edge-property; Separate Ways to the belt-and-suspenders independence rationale. No decision contradicts the accepted map or any ARB ruling; the COL-2/COL-5b pair is temporally partitioned, not contradictory. |
| 3 | Can Tactical DDD begin without reopening any strategic decision? | **Yes — with the two recorded entry items, which are entry-checklist work, not reopenings:** (a) the Adjudication-name reconciliation against the implemented `app/Contexts/Adjudication` (flagged at map acceptance); (b) stream assignments under the hybrid rule (which determine, per stream, whether COL-4a's or COL-4b's regime applies). The CB-1/CB-4 reversal condition remains dormant unless its trigger is met. |

**Self-review against the quality gates:** every map edge has exactly one relationship decision (COL-5a's subsumption is its decision) ✅ · no decision contradicts the accepted Context Map or prior ARB rulings ✅ · no implementation concepts introduced — "Open Host Service"/"Published Language" are used strictly as strategic context-mapping patterns; no interface, protocol mechanics, or technology is defined ✅ · every decision justified from the authorized criteria; communication frequency used nowhere ✅ · consistency review complete ✅.

**Ready-for-Tactical-DDD signal: GIVEN**, conditional on ARB acceptance of this document.

---

**Stop condition:** Relationship Pattern Selection is complete. This was the final Strategic DDD activity. **STOP.** No Tactical DDD, no implementation guidance, no new governance phase. Await explicit ARB authorization — upon acceptance of this document, Strategic DDD is complete and the Tactical DDD gate (with its two entry items) is next.

---

# Adversarial Design Review (ARB-commissioned, 2026-07-25 — appended to this document, not a new artifact)

**Method:** every selected pattern is assumed wrong until justified. For each: the strongest competing pattern, why it ultimately loses (or wins), and the explicit architectural assumptions under which the surviving choice is valid — stated as falsifiability conditions, so each decision remains testable rather than dogmatic.

**Outcome up front: one decision is REVISED (COL-3b), eight are confirmed — several with sharpened scope — and the global no-Partnership/no-Shared-Kernel claim now carries its own architectural justification.**

## Per-edge adversarial results

### COL-1 (Customer–Supplier) — CONFIRMED
**Strongest competitor: Partnership.** The attack: when audit standards evolve (e.g., risk-limiting audits are introduced), collection practice and adjudication standards change *together*, often planned by one institutional body — looks like aligned co-evolution. **Why it loses:** joint *planning* is not joint *model ownership*. Every influence in the evidence is asymmetric: adjudicative requirements drive collection; collection never drives scrutiny standards — and it must not, because an evidence-producer negotiating the evidence-weigher's standards corrupts the independence P2's whole evidence base presupposes. Partnership would grant exactly that influence. **Validity assumption (falsifiable):** Adjudication retains genuine refusal power (declare-failure). In any deployment where certification is forced regardless of evidence sufficiency, this C–S collapses into upstream-dominant Conformist and the decision must be revisited.

### COL-2 (Conformist) — CONFIRMED, WITH SHARPENED SCOPE
**The challenge (the strongest one raised against any decision): does Adjudication adopt Record-Fixing's model, or merely trust an immutable artifact?** Trusting immutability is not automatically Conformist — if Adjudication kept the record as an opaque token, the correct answer would be "no pattern" (as COL-4a). **Resolution:** Adjudication does more than trust — it *reasons in the record's own terms*. Hard-look/Chenery review grounds determinations in the record's own statements, quoted and cited untranslated; the record's articulation, not a paraphrase, is what determinations attach to. That is genuine model adoption of the record sublanguage. **Sharpened scope:** Conformist applies to the *record-as-evidence sublanguage only* — Adjudication adopts the record's terms verbatim for grounding; its judgment overlay (scrutiny standards, sufficiency) is its own model and is not part of this edge (this is K2 restated as pattern scope). **Competitor ACL loses** because translating the record breaks the as-fixed standing that gives it evidentiary force. **Validity assumption (falsifiable):** determinations are grounded in the record's own terms. A deployment that translated records into internal representations *for determination-grounding* would convert this edge to ACL and must trigger review.

### COL-3a (Customer–Supplier) — CONFIRMED, WITH A RECORDED REVISION TRIGGER
**Strongest competitor: Published Language.** The attack: custody-log conventions are genuinely standardized (NIST SP 800-86) and in the wider world serve many consumers — courts, auditors, regulators — which smells like PL, not a bilateral contract. **Why it loses here:** on the ruled map, the only consumer of custody attestations is Adjudication, and the relationship's active ingredient is the *direction of requirement flow* — admissibility demands shape custody documentation, with the standardized form being the medium through which the supplier meets them, not the governance itself. **Honest concession, recorded as a revision trigger:** if a future map adds further attestation consumers (e.g., an external-audit context), this decision should be revisited toward Published Language — the concession is written here so the future change is a planned evolution, not a contradiction.

### COL-3b — **REVISED: Published Language alone. Open Host Service is withdrawn.**
**The challenge stands: there is no host.** On adversarial inspection, the original OHS+PL pairing overstated the relationship. OHS means a context *offers its capability as a host* that consumers interact with. But the entire design fact this edge rests on — "no canonical verifier" — cuts the other way: the self-verifying context publishes the record and the protocol specification, and verification then happens **entirely in the consumers' own space, with no interaction with the publisher at verification time**. That independence is not incidental; it is the point — a verifier that depended on the publisher's hosted capability would not be independently verifying. The openness lives in the *publication of the language*, not in any hosted relationship. **Revised decision: Published Language (alone).** The record-and-spec publication is the shared, well-documented language; every independent verifier — Adjudication included, privileged never — consumes it without any host interaction. **Why this revision matters downstream:** Tactical DDD must not manufacture a hosted verification capability on this edge; doing so would contradict both this decision and the no-canonical-verifier evidence. **Validity assumption (falsifiable):** verification requires nothing from the publisher beyond the published record and spec. If a future protocol required live interaction with the publisher to verify, OHS would re-enter consideration.

### COL-4a (No pattern — model-blind handoff) — CONFIRMED, WITH THE BURDEN OF PROOF MET EXPLICITLY
"No pattern" is unusual and owes a stronger argument. **The test that justifies it:** *could either side change its internal model arbitrarily without the other noticing?* Both directions answer YES — custody re-hashes whatever bits it is handed (digest comparison does not parse); record-fixing neither knows nor cares how possession is tracked. When that test passes, the crossing is purely existential (an object changes hands) with zero semantic dependency, and imposing any model-mediating pattern **manufactures governance for a dependency that has no semantic content**. **Each competitor loses concretely:** *Customer–Supplier* — custody imposes no real requirements (a determinate, hashable object is guaranteed by P3's own fixing discipline, not by custody's demand); *ACL* — nothing is translated because nothing is interpreted; *Conformist* — custody adopts no model, deliberately; *Partnership* — no joint model exists to co-own. **Validity assumption (falsifiable):** custody remains content-blind. The moment custody is asked to "understand" record contents, this edge needs a real pattern — and that request should itself be treated as an architectural regression alarm, per the original decision.

### COL-4b (Conformist to the published language) — CONFIRMED
**Strongest competitor: Shared Kernel** — sharper than the originally-considered Partnership. The attack: the commitment format is literally shared between fixer and verifier; is that not a shared kernel? **Why it loses:** Shared Kernel means *joint ownership with joint change control*. Here change control is unilateral — the protocol is owned and published by Self-Verifying Integrity (per COL-3b as revised), and record-fixing *adopts* it for assigned streams; changes flow one way (spec evolves → fixing follows), exactly the ElectionGuard reality (spec authors own the spec; devices conform). Adoption under unilateral change control is Conformist, not SK. **Validity assumption (falsifiable):** spec ownership stays unilateral. If governance ever grants record-fixers joint change control over the protocol, this edge genuinely becomes Partnership/SK and must be re-decided.

### COL-5a (subsumed into COL-1) — CONFIRMED
**The attack:** subsumption hides an edge; episodic, corrective, potentially adversarial demands have different dynamics than routine supply and deserve their own contract. **Why it loses:** rejection-and-renewed-demand is not a different relationship — it is the customer side of the same contract exercising its defining power. Two contracts over one relationship is how the two halves drift apart; the watch-item (adjudication becoming a de-facto upstream controller) is better contained by one contract with explicit bounds than by a second, separately-evolving one.

### COL-5b (Customer–Supplier, prospective-only) — CONFIRMED
**Strongest competitor: no direct relationship at all** — the attack says deficiency findings influence record-fixing only *mediated through the external legal environment* (findings → updated regulations → fixing conforms to law), which would make this edge Separate Ways with environmental mediation. **Why it loses:** the domain evidence shows direct requirement flow without waiting for legislative mediation — audit-governance remediation (ICFR: identified deficiencies create direct remediation obligations on the producing side) is the ruled model's own family for this mechanism. **On the temporal qualifier:** "prospective-only" is not a new pattern; it is a domain invariant (P3) bounding the contract's scope — the pattern is C–S, the constraint is constitutional.

### Custodial ↔ Self-Verifying (Separate Ways) — CONFIRMED, STRENGTHENED BY THE ATTACK
**The attack:** belt-and-suspenders streams need *someone* to notice when the two assurances disagree — doesn't that require a relationship between the assurers? **Why it fails — and strengthens the decision:** the noticing belongs to the *consumer* (Adjudication weighs both attestations on its own edges), which is exactly where the original decision placed it. More: independence between assurers is what makes a disagreement *informative* — correlated assurance channels fail together and their agreement proves little. The attack, followed to its end, re-derives the original rationale.

## The global claim, given its own justification (as the review demanded)

**"No Partnership and no Shared Kernel anywhere" is not a stylistic preference — it is a consequence of the domain's accountability requirement.** The decomposition's purpose is constitutional trust, and constitutional trust requires every trust-bearing crossing to be *attributable*: it must always be answerable **who owed what to whom** when evidence fails, custody breaks, or a determination is challenged. Jointly-owned models blur precisely that attribution — a co-owned invariant has no single owner to hold to account. Independently: every influence actually discovered across the entire falsification program was asymmetric (requirements flow one way on every edge above; no co-owned model was ever observed in any of the eight evidence families); and K2 plus tamper-evidence both depend on boundaries *not* sharing internals. Zero Partnership/SK is therefore what the evidence and the accountability requirement jointly produce. **Falsifiability:** should a genuinely co-owned model ever be discovered in later work, admitting it is an ARB-gated architectural change, not a silent pattern upgrade.

## Completeness note (recorded, not a map revision)

The Strategic Domain Discovery's dependency matrix listed an evidence-object handoff from Collection to the integrity contexts. The accepted map carries the object handoff on COL-4 (Record-Fixing → Integrity). If later work treats a direct Collection → Integrity handoff as a distinct edge, it **inherits COL-4a's decision by identical grounds** (custodial side: model-blind, no pattern) and COL-4b's for self-verifying streams — recorded here so the question is pre-answered without reopening the accepted map.

## Post-review decision summary

| Edge | Pre-review | Post-review |
|---|---|---|
| COL-1 | Customer–Supplier | **Confirmed** (falsifiability: declare-failure must remain real) |
| COL-2 | Conformist | **Confirmed, scope sharpened** (record sublanguage only; judgment overlay excluded) |
| COL-3a | Customer–Supplier | **Confirmed** (+ recorded revision trigger: additional consumers → reconsider PL) |
| COL-3b | OHS + Published Language | **REVISED → Published Language alone** (no host exists; independence of verification is the point) |
| COL-4a | No pattern | **Confirmed, burden met** (the could-either-side-change-unnoticed test) |
| COL-4b | Conformist | **Confirmed** (vs. Shared Kernel: change control is unilateral) |
| COL-5a | Subsumed into COL-1 | **Confirmed** |
| COL-5b | C–S prospective-only | **Confirmed** (vs. environmental mediation: direct remediation obligation is the ruled family) |
| Integrity pair | Separate Ways | **Confirmed, strengthened** (the disagreement-detection attack re-derives the rationale) |
| COL-6 | None (annotation) | **Unchanged** (nothing to review — no ruled context) |

**Consistency re-check after revision:** the COL-3b revision strengthens rather than disturbs consistency — Published Language alone sits *closer* to the no-canonical-verifier design fact than OHS+PL did; no other decision depended on the withdrawn host relationship. The Strategic Architecture Consistency Review's three answers stand.

---

**Stop condition (post-review):** the adversarial design review is complete — one revision, eight confirmations, the global claim justified, every decision now carrying an explicit falsifiability condition. **STOP.** Await ARB acceptance of the reviewed document; upon acceptance, Strategic DDD is complete.

---
*Charter: `EPIC-002_Problem_Statement.md` · Inputs: `EPIC-002_Canonical_Context_Map.md` (ACCEPTED) and prior accepted EPIC-002 artifacts · No new sources consulted.*
