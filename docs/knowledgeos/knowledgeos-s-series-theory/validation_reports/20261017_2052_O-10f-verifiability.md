# O-10f: recoverability, verifiability, reproducibility, contestability

| Field | Value |
|---|---|
| Author / clock | Session B, 2026-10-17 20:52 |
| Authorization | Synthetic and analytical only. **Gate: BLOCKED.** No restricted material read. |
| Protocol | `../synthetic-checks/o10fig/PROTOCOL.md`, sha256 `24b93f0896fe6dbdb92cf6cfba0ed18a09db50715edee3ea868987fb7ed783a7`, frozen at 20:32:01 |
| Code / results | `o10fig_checks.py` (`cb61e3b78ac1aa3c41cbdc6b3b201dc7161143c78ed9c18e8d1398ddca3a297f`), `RESULTS.txt` (run 20:52:10) |
| Review | A refute-first fresh-context sub-agent: **INTERNALLY REVIEWED — SAME MODEL FAMILY**. Its corrections are integrated below; the claims as originally drafted are in the scratch packet and are superseded where marked. |

## 1. Model

**Histories and outputs.**
- A step is a letter (x, o). x consists of **attested** raw fields; o is the system's **actual** output.
- The **correct** decision is d = F(x), under a **published** rule F.
- *Fault model:* o may differ from d.

**What the auditor trusts.**
- **Trust set T:** the attested fields and F.
- **Not trusted:** o, and any derived entry, unless a trusted attester signs it (see V4).

**The four properties, kept distinct.**

| Property | Meaning | Formal requirement |
|---|---|---|
| **Recoverability of what was decided** | the record determines o | ker r ⊆ ker o |
| **Recoverability of what should have been decided** | the record determines d | ker r ⊆ ker d (O-10a's (P)) |
| **Verifiability** under T | from **trusted** record parts alone, an auditor can decide [o_t = d(h_t)] for every t | — |
| **Reproducibility** | an authorized party can re-execute the *actual* implementation and obtain o | needs everything that implementation consumed: inputs, version, seeds, external responses |
| **Contestability** for party p | under p's own trust set T_p, p can establish d and the basis for it | **not yet formally defined: OPEN, O-10f1** |

## 2. Results

| ID | Statement | Status |
|---|---|---|
| **V1** | Under the fault model, the outcome log ⟨o_t⟩ determines **what was decided** but not whether it was **right**. *Example:* F = identity, (x = 0, o = 0) vs (x = 1, o = 0): the same log, different correctness. | ANALYTIC EXAMPLE + COMPUTATIONALLY CHECKED (F1) |
| **V1′** *(reviewer correction)* | **The gap between recovering and verifying is about trust, not information.** If the record holds the o-log and *true* values of d, it is verifiable information-theoretically. The problem is that the auditor cannot *trust* stored d-values without an attester. | MATHEMATICAL ARGUMENT |
| **V2** | Verifiable ⇔ the attested log E determines 𝔇 at every step (ker E ⊆ ⋂ker d). E's kernel is automatically in PC. | MATHEMATICAL PROOF, **under the hidden assumptions below** |
| **V3** | If records may contain only o and **raw** attested projections, minimal verifiable records need not be unique. Named instance: (0,0), (0,1), (1,2), (1,3), (2,2), with F = [x ≥ 1]: {x} and {y} are both minimal and incomparable, with o included. | COUNTEREXAMPLE (reviewed). 7 / 300 random instances is an **exploratory** frequency, not prevalence |
| **V4** | If a trusted component **attests the decision itself**, then (o-log, attested d-log) is the **unique minimum** verifiable record of that form. Verification then reduces to trusting the attester. | MATHEMATICAL ARGUMENT |
| **V5** | **Separations:** (a) verifiability ⇏ reproducibility; (b) reproducibility ⇏ verifiability; contestability ≥ auditor verifiability when T_p ⊆ T. | ANALYTIC EXAMPLES; see the reviewer's limits below |
| **V6** | ⊑ applies to deterministic records only. | ANALYTIC; literature unverified |

**V2's hidden assumptions** (reviewer). The (⇒) direction needs all three:
- **(a)** histories range over the full product of x-histories and outputs;
- **(b)** d and E depend on x only;
- **(c)** o is unconstrained.

*Counterexample when (c) fails:* if o ∈ {F(x), F(x)+2}, the empty evidence set is verifiable but does not determine F. **Constrained fault models need their own analysis (O-10f2).** The multi-step form is analytic only; F2 checked the single-step form, with 0 mismatches over 300 × 8.

**V5's limits** (reviewer):
- **(a):** reproducing a faulty o may need a random seed or another execution detail that checking correctness does not.
- **(b)** is largely definitional: with F unpublished, nothing can be verified.
- **Contestability:** strictness needs an instance and a definition (O-10f1).

**V6's limits** (reviewer):
- Commitments are **randomized**, so ⊑ does not apply to them directly.
- Only *perfectly binding* commitments are injective. *Perfectly hiding* ones carry zero information-theoretic content.
- Zero-knowledge proofs can give verifiability with computational privacy (literature, **unverified**).
- **"Binding ⇒ ⊑-finest" is withdrawn.**

## 3. Answer to the cycle question: can a record be both data-minimal and independently verifiable?

**Yes, conditionally.**
- Under the trust model with an unconstrained fault model, a minimal verifiable record is **(the outcome log) + (a minimal set of attested evidence that determines each decision)**.
- **Uniqueness:** that minimum is unique if the rule's inputs admit a single coarsest attested evidence set. **It is not unique in general (V3).** Choosing among incomparable evidence sets is then a **stakeholder** decision, by cost, privacy or availability of attestation.
- **A cheaper alternative:** attesting the decision itself (V4) is minimal, but it moves trust to the attester. It is **not independent** verification.
- **Not covered by this model:**
  - **mechanisms** (signed evidence references, rule and version identifiers, commitments, proof-carrying records): each is sufficient only under its own assumptions (integrity of the referenced store, version immutability, cryptographic hardness). None was proved here;
  - security, privacy, operational cost and legal acceptability.

## 4. Architecture mapping (ARCHITECTURAL PROPOSAL; not adopted)

**Mathematical result → operational implication → candidate requirement → affected process → candidate context → open stakeholder decision.**

| Result | Implication | Candidate requirement | Process | Candidate context | Stakeholder decision |
|---|---|---|---|---|---|
| V1, V1′ | an outcome log shows *what* was decided, not *whether it was right* | for each **critical** decision, retain trusted evidence sufficient to recompute it, or an attestation of it | audit, appeal | Evidence & Provenance, Governance | which decisions need independent verification (D9's critical list) |
| V2 | sufficiency of evidence = "evidence determines the decision under the published rule" | record the **rule version** with every decision; publish rules | decision-making | Contracts & Assessment | rule-publication policy |
| V3 | several equally minimal evidence sets may exist | choose an evidence set explicitly, per decision type | data minimization | Evidence & Provenance | privacy vs cost vs attestation availability |
| V4 | attestation is cheaper but not independent | name the trusted attesters | trust model | Governance | who may attest |
| V5 | reproducibility needs execution data that verification does not | decide separately whether reproducibility is required | incident analysis | Kernel / infrastructure | retention of execution data |

## 5. Open questions

| ID | Question |
|---|---|
| O-10f1 | A formal definition of contestability, and a strictness instance |
| O-10f2 | Constrained fault models, where V2 (⇒) fails |
| O-10f3 | Verifiability when F reads past outputs, or when E reads o (assumption (b) violated) |
| O-10f4 | Combining verifiability with revisions (O-10i): which evidence version verifies a superseded decision |
