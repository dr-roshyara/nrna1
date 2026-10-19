# External review packet: stage-1 addendum (O5-12, D10-1, O-10a)

| Field | Value |
|---|---|
| Prepared | Session B, 2026-10-17 (clock 20:35) |
| Status | Review material. **Nothing in it is externally reviewed.** The internal reviews are same model family. |
| Use | Stage 1: attempt every claim below **before** reading the stage-2 reports. Stage 2: `../validation_reports/20261017_1951_O5-12-D10-1-and-filter-r4.md` and `../validation_reports/20261017_2016_O-10a-ordering-history-dependent-records.md`; **§8 of the latter governs**. |

## A. O5-12: approximation vs coarsening

**Claim.** In the pre-declared synthetic population, the best N-state recursive approximation **strictly beats** every coarsening of the exact kernel in 35 of 60 machines at T = 6, and 30 of 60 at T = 10. The population is minimal trim 4-state Moore machines over {0,1} that each have a non-trivial congruence, with budget N = the smallest non-trivial congruence size.

**Instance to verify.**
- *Machine:* δ(0,·) = (2,1), δ(1,·) = (3,3), δ(2,·) = (3,1), δ(3,·) = (3,2); outputs [1,1,1,0].
- *Loss:* L = (1/7) Σ_{t=0..6} P[error], inputs i.i.d. uniform.
- *Best coarsening:* {0}|{1,2,3}, loss 27/64.
- *Global 2-state optimum:* "last input was 0", loss 67/448, which is not a function of M's state.

**Evidence.**
- Protocol sha256 `60c0b9566a91f35bc432e95873f91914eb9a1ed548c0ed33119706cc6e26564e`, frozen at 19:29:05; run at 19:45.
- **Deviations and corrections:**
  - the protocol's Q2 is redundant (= 60 − Q1);
  - "smallest such machine" in the protocol is undefined, and the first one found is reported;
  - the losses are floating point (the exact values agree).
- **Limits:** synthetic only; n = 4; one distribution family.

## B. Proposition D10-1: fixed per-input recording

**Claim.**
- Assume decisions are defined on **all** of Σ\*.
- Define a ≈ b iff d(haw) = d(hbw) for all h, w and all d.
- Then ≈ is the **greatest** fixed letter-wise abstraction that preserves all decisions.
- A fixed letter-wise privacy map ψ is jointly infeasible with the decisions iff it merges some a ≉ b.

**Corrections (internal review).**
- The result is false for position- or history-dependent records (counterexample: d = first letter).
- The claim "context-dependent optimum = the kernel transition" was **withdrawn**. O-10a supersedes it, under its assumptions.

## C. O-10a: an order on records, and the minimal recoverable record

**Claims.**

| ID | Claim |
|---|---|
| L1 | r = g ∘ r′ ⇔ ker r′ ⊆ ker r ⇔ the distinguishable pairs of r are a subset of those of r′. |
| P0 | Online append-only records with **delimited** entries, each possibly depending on the whole past, have exactly the length-preserving prefix-closed kernels (PC). |
| T2 | ≡\* (same length, equal decision vectors at every prefix) is the **greatest** PC relation inside ⋂ker d. It is realized by the decision-outcome log, under these assumptions: 𝔇 known in advance, computable online, never revised. |
| T2a | The resumable version logs (𝔇, Nerode class). |
| T2b | The letter-wise record of D10-1 is contained in ≡\*, strictly in some instances. |
| P3 | Records restricted to **raw** field projections can have incomparable minima. Instance: letters (0,0), (0,1), (1,2), (1,3), (2,2), with d = [x ≥ 1]. |
| P4 | For **upward-closed** privacy predicates on kernels, a compliant recoverable record exists iff the outcome log complies. **DP is removed** as an instance. |
| P5 | Naive storage is not monotone. The global per-length code is monotone; the online case is O-10g. |
| P6 | A record minimized for 𝔇 need not preserve 𝔇′ ⊋ 𝔇. |

**Disclosed deviations** (the author's error, disclosed by internal review):
- **C5:** the pre-declared instance (x, y, z = x⊕y) **failed** to show incomparability and was **silently replaced** after the fact. The replacement is valid but post hoc.
- **C6** was labelled computational but was an analytic comparison of constants.
- **C1** was close to circular.
- **Test power:** in the declared population, 239 of 600 instances had a single feasible record. A post-hoc high-power run was added. **A pre-declared high-power replication has since been run (O-10h):** C2 and C4 held on 300 / 300 instances, median 133 feasible records. Its own design defect is disclosed: one wrong candidate was vacuous.
- **Hashes:** protocol `77da84f5463e58196eaca03111384918fae14fb2b191c438229d33a87ef1c6ec`; code `2c5732e3415aa0b1fed6de7d695e730acd855a8e938337a4b7cd82aa37d1d6f6` (the header abbreviation was corrected to …d6f6).

**Renaming.** (P) is **decision recoverability**, not "audit preservation". Recoverability does not give verifiability (O-10f, in progress).

## D. What the reviewer is asked to decide

1. Are T2, T2a and P4 correct as stated, under their assumptions?
2. Is ⊑ the right information order for records, or does a review of the literature point to a better one (for example from information flow or database provenance)?
3. Does O5-12's construction reasonably formalize "coarsening of the exact kernel"?
4. Are the disclosed deviations adequately handled?

## E. Literature not yet verified, added in this addendum

- predictive-state and bisimulation approximations (O-5);
- differential-privacy post-processing (now removed from P4);
- provenance and information-flow orders (requested in D.2).

---

## F. Second addendum (20:52): O-10f, O-10i, O-10g, O-10h

These claims are stated **after** a refute-first internal review (same model family). Stage 2:
- `../validation_reports/20261017_2052_O-10f-verifiability.md`
- `../validation_reports/20261017_2052_O-10i-revisable-decisions.md`
- `../validation_reports/20261017_2052_O-10g-storage-and-online-encoding.md`
- `../validation_reports/20261017_2052_O-10h-pre-declared-replication.md`

Hashes: protocols `24b93f0896fe6dbdb92cf6cfba0ed18a09db50715edee3ea868987fb7ed783a7` (o10fig) and `5cf1cce8fcf93ad668845816e5ab5a6cac15100882a1774c62ada9aa03efd682` (o10h), both frozen at 20:32:01.

### Verifiability (O-10f)

**Model.**
- Letters are (x, o). x holds attested fields; o is the actual output, which may be faulty.
- d = F(x), under a published rule F.
- The auditor trusts the attested fields and F.

**Claims.**
- **V2.** (o-log, E-log) is verifiable ⇔ E determines every decision.
  - It needs: histories over the full product of inputs and outputs; d and E depending on x only; o unconstrained.
  - The (⇒) direction fails for constrained outputs: o ∈ {F(x), F(x)+2} is a counterexample.
- **V3.** With raw projections only, the minimal verifiable records can be incomparable. Instance: (0,0), (0,1), (1,2), (1,3), (2,2), with F = [x ≥ 1].
- **V4.** The attested-decision log is the unique minimum of that form. It is not independent.
- **V1′.** The recoverability/verifiability gap is about trust, not information.

### Revisable decisions (O-10i)

- **B1.** For bitemporal D(h_u, t), the greatest append-only record preserving every D(h_u, t), t ≤ u, is Theorem 2 with a vector-valued decision.
- **B2.** A current-only log fails: "1c" vs "10".
- **B3.** The difference log is information-equivalent to B1's log.
- **B5.** Concurrent corrections need a commutative **and** associative merge, or a recorded order. Commutativity alone fails: floor-average is a counterexample.

### Storage (O-10g)

- The global per-length code is monotone under coarsening (exhaustive, 735,776 pairs).
- Online integer per-step codes are **not** monotone:
  - a single global width: 71,401 pairs;
  - a per-level width: 1,612;
  - along some history: 66,690;
  - maximum over histories: 2,000;
  - expected Huffman length: 1,088.
- Ideal fractional coding is monotone (telescoping entropy).

### Replication (O-10h)

- C2 and C4 held on 300 / 300 instances.
- **Disclosed defects:**
  - W4 was vacuous;
  - W5 was constant;
  - W1–W3 detection is guaranteed once C2 holds;
  - the same enumerator as O-10a was used;
  - the code hash was recorded only after the run, via a re-run with identical output.

### Vacuous check, disclosed

The I3 computation tested nothing (it checked that `max` is commutative). B5 rests on analysis instead.
