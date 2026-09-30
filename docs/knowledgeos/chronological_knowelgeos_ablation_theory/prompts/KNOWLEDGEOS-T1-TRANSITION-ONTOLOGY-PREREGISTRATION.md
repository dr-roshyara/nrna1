# T1 transition ontology — PRE-REGISTRATION r0 (results-free; frozen before any R-39 re-read)

| | |
|---|---|
| Status | **r0, frozen at commit.** L0 may amend **before** the test only. Amendments after the R-39 re-read are forbidden (they would be post-hoc) |
| Kind | a candidate transition ontology (not a KnowledgeOS theory; not canonical) |
| Sources used for the definitions | only L0-released fragments: ES-001.2 (L26), ES-006.1 (L14–21), ES-006.2–.4 (L22–49), R-37 (L23), Phase-02.6 L32 and §5 (L155–161), PKS_ARB_Review_Discipline L216–221, Metamodel §6 (L102–105) |
| **Circularity disclosure** | the author has prior exposure to R-39 (SELF read, F-LOG-0053). **No R-39 text was consulted in writing this document.** READY is defined **only** from the PKS chain wording (L218) |

## 1. Primitive state (per object x)
- `id(x)`, `version(x)`, `derived_from(x)`
- `kind(x)` ∈ K (e.g. platform artifact · PKS baseline item · engineering knowledge artifact)
- `pos(x)` ∈ C_kind(x), a linear chain per kind:
  - C_platform = Generated → ARB Review → Capability Certification → ADR Approval → Authoritative → Frozen (Phase-02.6 L32);
  - C_PKS = Review Complete → Findings Dispositioned → Remediation Verified → Promotion Ready → [Authority Promotion Decision] → Promoted Baseline (PKS L218).
- `E(x)`: an evidence vector, non-aggregated (ES-006.3)
- The decision record D(x): a finite sequence of HDEs d = (act, x, stage, owner, outcome ∈ {Approve, Reject, Defer}, t)

## 2. Derived predicates (never decisions)
- **READY(x) :⇔ kind(x) = PKS ∧ RC(x) ∧ FD(x) ∧ RV(x)**
  - RC = Review Complete, FD = Findings Dispositioned, RV = Remediation Verified;
  - all three are recorded as completed, in chain order, each by its owner (PKS L218: "Promotion Ready — a state DERIVED BY VERIFICATION, not a decision by anyone").
- **READY does NOT assert the evidence bar (EB) or the floor (EF).** It is defined purely by chain completion. The relations READY ⇒ EF and READY ⇒ EB are **open questions (Q-EF, Q-EB)** and are reported separately in every test.
- **BAR(x)** (the evidence bar) is **undefined in T1 r0.** No released fragment defines it for promotion. A test that needs BAR must report it as UNDEFINED, never infer it.

## 3. Events (acts)
- `HDE(x, stage, owner, outcome)`: each stage transition of C_kind is exactly one HDE (Phase-02.6 L32; PKS L220).
- `PromotionDecision(x)` (kind PKS): the act (PKS L218).
- `Supersede(x → x′)`: decision-triggered; for the dictionary the source says it "produces a superseding version" (L157).
- `Propose(p, evidence) → Accept | Reject(default)` (R-37).

## 4. Axioms (each with its source; scope-limited per note 07)
- **A1′:** a change of governance status ⇒ ∃ HDE by the authority (ES-001.2).
- **A2:** pos advances only by an HDE, one stage per HDE, never skipped (L32; PKS L220).
- **A3:** READY is derived; a derived state is never an HDE (PKS L218).
- **A4 (non-collapse):** verified-ready ≠ promoted · frozen ≠ promoted · review outcome ≠ adoption · recommendation ≠ issuance ≠ adoption · closing a review ≠ adopting (PKS L220).
- **A5′:** a frozen artifact changes only through the new-ADR mechanism; A5-dict: the dictionary's ADR yields a superseding version (L157).
- **A6′:** the attested evidence burdens are on proposals (R-37) and promotion (ES-006.1). No other burden is asserted.
- **A7:** in C_platform, Frozen ⇒ previously Authoritative (L32 order + A2).

## 5. Falsifiers (each a predicate over a released historical record)
- **F1:** a governance-status change with no HDE in the record.
- **F2:** a PromotionDecision(x) with ¬READY(x) immediately before it (READY exactly as defined in §2).
- **F3:** a stage skipped in C_kind.
- **F4:** a frozen artifact changed without the new-ADR mechanism.
- **F5:** a derived state (e.g. READY) recorded or treated *as* a decision.
- **F6:** a loss of governance status with no HDE (automatic loss).

## 6. Test protocol (fixed now)
- **Input:** a historical case (the first is R-39), read only under an L0 release. It is coded into the frozen evidence engine plus a per-falsifier table.
- **Outcomes per falsifier:** TRIGGERED · NOT-TRIGGERED · UNDETERMINABLE (a required predicate cannot be decided from released text).
- **Required reports:** READY(x), with RC / FD / RV each marked determinable or not; Q-EF; Q-EB; BAR = UNDEFINED.
- **R-39 decision rule:**
  - (i) READY = yes → no F2 counterexample;
  - (ii) READY = no and a promotion occurred → F2 TRIGGERED (a T1 counterexample);
  - (iii) READY undeterminable → T1 is untested on F2, and the missing predicate becomes the next retrieval target.
- A kind mismatch (x is not of kind PKS) is reported as **OUT-OF-SCOPE for F2**. It is neither triggered nor passed.
- **No amendment of §§1–5 after the first case has been read.**
