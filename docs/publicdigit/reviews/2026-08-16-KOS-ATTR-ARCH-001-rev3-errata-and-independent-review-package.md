# KOS-ATTR-ARCH-001 — Stage 1 rev 3
# Directed Errata + Independent Architecture Review Package

**Session 4 — `S4-architecture-attr-target` · ACTIVE · 2026-08-16**
**Applies:** the two corrections directed by the ARB review of rev 3 (`6c345e4d`). **Prepares:** the independent architecture review the ARB requires — **which this session must not perform.**

> **Status unchanged: PROPOSED — NOT APPROVED.** Aggregate = *leading recommendation* · bounded-context placement = *not yet confirmed* · target architecture = *not approved*. No C4, no technology, no schemas, no implementation. `INV-ATTR-4/5/6` remain CANDIDATE.

---

## 1 · Errata, as directed

**E-1 · `AssessmentEstablished` — the disambiguating sentence added verbatim:**

> **"Established" means established as the current Assurance-domain conclusion; it does not mean business acceptance, authorization, or approval.**

This now sits alongside the rev-3 §3 definition. The risk it closes is a future consumer reading *established* as *approved* — the same class of error the original `Accepted` name carried.

**E-2 · Aggregate framing corrected throughout:**

| Was (rev 3) | Now |
|---|---|
| "Decision: **B** — a separate Assurance aggregate" | **"Leading aggregate-boundary recommendation: a separate Assurance aggregate."** |

**Why the ARB is right:** an aggregate can live *inside an existing bounded context* or *inside a future separate one*. Those are related but different decisions, and rev 3's language let the tactical one read as settled while the strategic one was explicitly deferred. The recommendation stands; its status does not rise to "approved aggregate architecture."

---

## 2 · Independent Architecture Review package

**Reviewer requirement:** a **different Architecture process**. This session is disqualified — it produced rev 1, rev 2, rev 3 and this package. Under **P-2** its own review is **Class-A ownership review only**, and the record already shows why that is insufficient: **all three substantive corrections in this design's history were found by the ARB, none by this session.**

### 2.1 Attack targets, in the ARB's priority order

**T-1 · The aggregate boundary.** Rev 3 claims the minimal boundary `{Claim · Assessments · current outcome}` is justified by **approved INV-ATTR-2 alone**.
**T-2 · The claim / evidence / assessment model.** Does the three-concept split survive contact with real evidence patterns, or does one concept turn out to be a projection of another?
**T-3 · One claim per (Governed Act, dimension).** See the specific hole in §2.2 below.
**T-4 · The dependency direction.** Search for *indirect* paths by which an assurance outcome could reach a gate — not the declared one, which is clean.

### 2.2 Where I expect to be wrong — found while preparing this package, not by the ARB

**W-1 · The INV-ATTR-2 justification may prove too much (attacks T-1).**
Rev 3 §2 makes the current outcome a **derivation**, not stored state. But a derivation is a *read*. If nothing is stored, it is not obvious that INV-ATTR-2 requires a **transactional** boundary at all — a read rule can be satisfied by a pure function over whatever assessments exist.

The invariant that actually seems to need the boundary is a **uniqueness** rule I never stated: *at most one established assessment per claim at a time.* Without it, the derivation is ambiguous rather than merely stale. **That rule appears nowhere in the approved invariant set, which would put me back in the position the ARB corrected — justifying a boundary by an invariant nobody has adopted.**
**Reviewer should determine:** is the uniqueness rule (a) implied by INV-ATTR-2, (b) a separate invariant requiring ARB adoption, or (c) evidence that the boundary is smaller — or differently shaped — than proposed?

**W-2 · One-claim-per-(act, dimension) may not survive multiple claimants (attacks T-3).**
Rev 2 made **Claimant** a property of the claim. Then consider one act, one dimension, two claimants: the actor asserts *"lane X performed T-123"*; Governance asserts *"the performer is not established."* Under the rev-3 rule these must be **one claim** — but they are different assertions with different claimants and potentially different outcomes.
**Either** the rule is wrong (claims are per `(act, dimension, claimant)`), **or** Claimant belongs on the *Assessment*/evidence rather than the Claim, **or** the second assertion is not a claim at all but an assessment of the first. **I do not know which, and rev 3 does not say.** This is the sharpest unresolved question in the model.

**W-3 · Assessor standing may deserve first-class treatment (attacks T-2).**
P-2 Class-B standing is currently a property of the Assessor, asserted where? If assessor standing is itself a claim requiring evidence, the model may be recursive in a way not yet acknowledged — *who assures the assurer?* Rev 3 assumes standing is given.

### 2.3 What the reviewer should NOT re-litigate

The ARB has affirmed these; attacking them would be re-work, not review: the dependency **direction** itself (INV-ATTR-1 enforcement) · the evidence-ownership split (source domain owns artifacts; Assurance owns reference + evaluation) · the four-domain-fact event pruning · the epistemic wording of the actor-identity constraint (*currently observed*, not axiomatic) · the deferral of bounded-context placement to Stage 2.

### 2.4 Materials

Rev 1 `8dab1be1` → rev 2 `7feec4ff` → rev 3 `6c345e4d` → this package. Approved inputs: Business Assurance Model rev 3 (`5ab3b4e6`), P-1…P-6, accepted root gap `487fce74`. Running domain: `workflow-state.php` + the live records. **`KOS-ARCH-BASELINE-001` is unaccepted — its conclusions must not be presumed by the reviewer either.**

---

## 3 · Decision gates — nothing proceeds until these are held

| Gate | Holder | State |
|---|---|---|
| **Q-A1** vocabulary (`Assurance Claim` generic; *Attribution* = dimension) | Human/ARB | open — ARB recommends approving now |
| **Q-A2** canonicality now vs after Stage 2 | Human/ARB | open — ARB recommends deferring |
| **Q-B1…Q-B8** architecture decisions | Human/ARB, **after** independent review | open |
| **Q-C1** Stage 2 as new assignment vs new work item | Human/ARB | open |
| **Q-D1** independent review required | **ARB has ruled: YES** | **closed — review pending** |

**Sequence, per the ARB:** independent architecture review → Human/ARB decisions on Q-A1/Q-A2/Q-B1…Q-B8 → **then** Stage 2 against the accepted baseline. **C4 and technology selection remain out of scope at every step above.**

---

**Traceability:** rev 3 `6c345e4d` (errata E-1/E-2 applied against it) · ARB review of rev 3, 2026-08-16 (verdict: strong Stage-1 proposal, independent review required before approval; two directed corrections; Q-D1 ruled YES) · rev 2 `7feec4ff` · rev 1 `8dab1be1` · Business Assurance Model rev 3 APPROVED (`5ab3b4e6`) · P-2 Class-A/B · P-5 · P-6 · `R-34` · the LCOM4 precedent for arming a successor process with attack targets (`32a35ddf`).

---

> # PROPOSED — NOT APPROVED · this session is DISQUALIFIED from the independent review it has prepared
