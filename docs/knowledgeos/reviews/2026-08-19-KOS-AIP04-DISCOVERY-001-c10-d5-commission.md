# C-10 D5 — Establishment Criteria · **ARCHITECTURE COMMISSION REGISTERED**

**Work item:** KOS-AIP04-DISCOVERY-001 · **Grant:** `G-KOS-AIP04-C10-D5` — **AUTHORIZED**
**Registered by:** Governance (`b64828fe`), on the PO/ARB act 2026-08-19 · **START: NOT PERFORMED**

> **Authorship route selected by the PO/ARB: COMMISSION FROM ARCHITECTURE.**
> Governance registers the commission; **it does not author D5.** D5 defines the threshold that would flip **D2 — a decision Governance registered** — so Governance writing the test whose satisfaction it would later record would concentrate authorship and registration in one actor. That is the `R-34`/`P-2` shape this work item has spent the day adjudicating.

---

## 1 · What is commissioned

**Define the MINIMUM EVIDENCE SUFFICIENT to move `C-10 = NOT YET ESTABLISHED` → `C-10 = ESTABLISHED`.**

> ⛔ **This defines a THRESHOLD. It does not establish C-10 now. D2 MUST NOT be changed.**

**Three evidence classes, mandated by the act:**

| Class | Items | Rule |
|---|---|---|
| **A · REALIZATION** | ① C-10-owned receipt created for a real governed act · ② acknowledgement actually recorded · ③ a lifecycle transition actually occurred · ④ authoritative receipt state actually produced | classify each **SUFFICIENT / SUPPORTING / INSUFFICIENT / NOT REQUIRED** |
| **B · BOUNDARY** | ⑤ distinct outcome · ⑥ applicability consumed as an external fact without owning the decision · ⑦ distinct reason to change · ⑧ identifiable invariants | same classification |
| **C · FUTURE / ARCHITECTURAL** | proposed schema · proposed implementation · planned receipt store · intended ownership · future enforcement · **an adopted invariant that has never been exercised** · architecture documentation alone | ⛔ **MUST NOT establish existence by themselves** |

⭐ **Class C formally closes the D2-prep error:** **D1 is Class C** — an adopted semantic never exercised. **`I-K1` does not even reach Class C**, being still `PROPOSED`. *(The D2 prep treated D1 as existence evidence; the register already corrected that at D2 §3. Class C makes the correction structural rather than case-by-case.)*

**Six questions on the minimum sufficient set**, including ⭐ **"Can one realized instance establish the capability?"** ⛔ *Do not automatically require all dimensions. Do not automatically accept a single event without testing whether it demonstrates a coherent capability rather than an incidental action.*

**Establishment ≠** bounded context · ownership · full implementation · control-plane authority · BLOCK/HALT enforcement.

**Revisit rule:** define the exact evidence event(s) causing D2 to be revisited — **observable and auditable**; form *"When [specified evidence] occurs, PO/ARB may reopen D2."* ⛔ **Not automatic unless governance explicitly decides it should be.**

## 2 · Prep input handed over — **INPUT ONLY, not an authoritative basis**

The Governance-authored **D4.2** (condition semantics) and **D4.3** (response mapping) carry a producer disclosure and are `PROPOSED` decision-input. **Architecture may consume, correct or reject them.**

**Three Governance sharpenings, handed over as input only:**

| | |
|---|---|
| **(i)** | **Class B is not independent of Class A.** ⑤⑥⑧ are observable only through realization, and **⑦ (distinct reason to change) requires a HISTORY of changes — no single instance can satisfy it.** ⑧ must be split: **invariant IDENTIFIED (B)** vs **invariant EXERCISED (A)**, or it collides with Class C |
| **(ii)** | **The estate already has a worked incidental-vs-owned test.** Canonical §5.1: delivery *"occurs incidentally"* via `CAP-01` bootstrap and BC-7 `tokenRef` leakage, and was **explicitly not counted as C-10 realization.** The operative question: **does the acting context OWN the outcome, or does it fall out of another context's mechanism?** |
| **(iii)** | **The revisit trigger has a trigger-ownership problem.** D4.3 established **monitoring is owned by nobody**, so a condition requiring an observer will never fire. **It must be SELF-EVIDENCING** |

## 3 · Producer eligibility

**`R-34`/`P-2` binds: the authoring process MUST NOT later verify or accept D5.**

⚠️ **Disclosure, not a bar:** `claude-code-session:5e1dd9ee` authored the canonical C-10 analysis whose `NOT YET ESTABLISHED` verdict **D2 ratified**. If it authors D5 it **writes the establishment test for its own model** — permissible Architecture continuity **only so long as it does not also judge satisfaction of that test.**

⛔ The barred set at `G-KOS-AIP04-CORRECTION3-VERIFY-AMD2` governs **verification of Correction #3**; it is **not automatically a bar on D5 authorship.** **Any independence claim must be established at START, never inferred.**

## 4 · ⚠️ The workflow lane is NOT registered — and here is why

**Only the GRANT is registered.** No session assignment and no handoff.

**The record's own state blocks it:**

```
mutationOwner = S5-verification-aip04-correction3   (ACTIVE, seq 27)
```

Per **Inv C**, a handoff to a new D5 lane must come **from the current mutation owner — `S5`.** But:

> ⛔ **Handing off from `S5` would set `S5` to `HANDED_OFF`, misrepresenting an OUTSTANDING verification as concluded.** The provenance disposition §C recorded `S5` as *"a valid independence-gate refusal … the assignment remains OUTSTANDING and UNCONSUMED."*

**And per G-3, a lane with no recorded handoff can never legally START.** ⇒ **Registering an assignment now would create a permanently unreachable lane.** Governance does neither.

**Consequence:** **D5 can proceed as a PO/ARB-directed act with Governance registration** — the pattern D1–D4.3 have used, which has touched no transitions. **If D5 is instead to run as a governed lane, `S5`'s disposition must be decided first.** That is the same blocker recorded after the provenance disposition, now reached from a second direction.

## 5 · Not done

⛔ D5 **not authored** · D2 **not changed** · C-10 category · bounded context · ownership / stewardship · Knowledge Engineer ownership · `OQ-K` · implementation · build order · final enforcement mapping · **no second C-10 model** · **canonical analysis not modified** · **no session assignment · no handoff · START not performed.**

**Next actor:** **Architecture**, on a human START — or the PO/ARB, if D5 is to run as a directed act rather than a lane. **After delivery: PO/ARB (D6 — category).**

**Traceability:** PO/ARB act 2026-08-19 (D5 approval, the refined prompt, and the authorship selection) · `G-KOS-AIP04-C10-D5` · **D1 · D2** (+ register §3, §6) · **D3 · D4.1 · D4 · D4.2 · D4.3** · D4.2/D4.3 preps (producer disclosure) · canonical §5.1 · `CAP-01` · BC-7 `tokenRef` (`EKS-01`) · provenance disposition §C (`S5` outstanding) · seq 27 · Inv C · G-3 · `R-34`/`P-2`

---

# APPENDED 2026-08-19 — **AMD1 · the full Architecture work statement**

Registered as **`G-KOS-AIP04-C10-D5-AMD1`**, additive. Nothing above superseded.

## AMD1 §1 · What the amendment adds

**Five analysis axes**, evaluated separately: **(A) realization** · **(B) boundary** — now including **distinct language** and **non-overlap with BC-1 / BC-6 / BC-7** · **(C) authority** — distinguishing *recording a fact · asserting a fact · authoritative state · identity attestation · applicability authority*, with **⛔ do not assign C-5 responsibilities to C-10** · **(D) repeatability** — *is one instance enough? must invalidation be demonstrated?* · **(E) negative test** — evidence that would **not** suffice.

**Seven-part deliverable**, each criterion marked **NECESSARY / SUFFICIENT / SUPPORTING / NOT SUFFICIENT**, plus **explicit counterexamples** and the D2 revisit trigger.

**Output constraint, verbatim and binding:**

> ⛔ The artifact **must not** say *"C-10 is established."*
> ✅ It **must** say *"These criteria would be sufficient for PO/ARB to establish C-10."*

## AMD1 §2 · ⭐ The prohibition that changes a standing OPEN in this register

> **"DO NOT import C-5's existence test automatically. DO NOT assume 'adopted invariant + performed capability' is a universal rule."**

**This refines — and partly answers — the open question recorded at D2 §6.** That entry asked *whether ONE limb of the C-5 two-limb test suffices to move C-10*, which presumed C-10 inherits the test. **The prohibition removes the presumption:** the two-limb test was **derived from C-5's evidence**, never adopted as a general rule, so **C-10 does not inherit it at all.**

⇒ **D2 §6's question is superseded in form:** it is no longer *"does one limb suffice?"* but **"what is C-10's own test?"** — which is exactly what D5 must construct. ⛔ **Recorded as a refinement of the open question, not as its answer.**

## AMD1 §3 · ⚠️ Performer — this work statement does not revoke the routing

**The PO/ARB selected "Commission from Architecture" on 2026-08-19**, in preference to Governance-authored prep. **That selection is not revoked by delivering the work statement**, so:

> ⛔ **The performer is NOT the Governance process `b64828fe`.** Governance registers this assignment; it does not author D5.

**The reason stands unchanged:** D5 defines the threshold that would flip **D2 — a decision Governance registered.** Authoring the test whose satisfaction it would later record concentrates authorship and registration in one actor.

⭐ **The work statement's own `INDEPENDENCE / ROLE BOUNDARY` clause reinforces this** — *"declare the producing process and prior participation; do not verify or accept this work later if you authored it."* Governance declaring itself as producer would be a disclosure of the very concentration the routing avoided.

**If the PO/ARB intends to reverse the routing, that is a one-line act** — and Governance will produce D5 with full producer disclosure, accepting that it must then never verify or accept it.

## AMD1 §4 · Still not done

⛔ D5 **not authored** · **no session assignment · no handoff · START not performed** *(the §4 lane blocker above is unchanged: `S5` holds mutation ownership while holding a refusal)* · D2 unchanged · no category · no ownership · no implementation · canonical analysis unmodified.

**Traceability (AMD1):** PO/ARB act 2026-08-19 (Architecture work statement) · `G-KOS-AIP04-C10-D5-AMD1` · `G-KOS-AIP04-C10-D5` · the authorship routing selection 2026-08-19 · **D2 register §6** (the open question this refines) · D4.2 / D4.3 (governed decision-input, not authoritative model) · `R-34`/`P-2` · Inv C · G-3

---

# APPENDED 2026-08-19 — **AMD3 · the mandated proposal structure**

Registered as **`G-KOS-AIP04-C10-D5-AMD3`**, additive. Nothing superseded. **The commission remains OPEN.**

## AMD3 §1 · Artifact and structure

**Mandated name:** `KOS-AIP04-C10-D5-ESTABLISHMENT-CRITERIA-PROPOSAL.md`
⚠️ **Placement must be DERIVED** — `php scripts/doc-placement.php`, never hard-coded; **exit 2 = unruled ⇒ record `PENDING` and escalate.** The canonical C-10 analysis sits under `docs/knowledgeos/architecture/`, which is the expected neighbourhood but **not a substitute for resolving placement.**

**Seven sections:** ① purpose and scope *(establishment only — no category, no ownership)* · ② evidence model · ③ **criteria definitions — for each `E1`–`E10`, all five fields: Definition · Purpose · Evidence · Classification · Counterexamples** · ④ minimum sufficient combination *(conjunctive? layered? assurance levels?)* · ⑤ revisit trigger · ⑥ negative boundaries · ⑦ recommendation to PO/ARB.

## AMD3 §2 · ⭐ FLAG 1 — three evidence schemes now bear on one artifact

**§2 introduces `GOVERNED` / `OBSERVED` / `REPRODUCIBLE` / `INDEPENDENT`.** That is a **third** scheme:

| # | Scheme | Source |
|---|---|---|
| **i** | GOVERNED · OBSERVED · **REPRODUCIBLE** · **INDEPENDENT** | this act, §2 |
| **ii** | **A** realization · **B** boundary · **C** future/excluded | `-AMD1` and the D5 decision |
| **iii** | OBSERVED **53** · PROPOSED **29** · GOVERNED **24** · INFERRED **16** · EXTERNAL **13** · CORROBORATIVE **10** | **measured in the canonical analysis, 2026-08-19** |

**Measured facts:** `REPRODUCIBLE` and `INDEPENDENT` **exist nowhere in `docs/knowledgeos/` today**; `INFERRED`, `PROPOSED`, `EXTERNAL`, `CORROBORATIVE` are **absent from the new list**.

> ⛔ **The three must not be silently merged, and a fourth must not be invented.** The performer **must state the relationship explicitly** — is §2 a **new orthogonal dimension**, an **overloaded existing dimension**, or **merely another set of values**?
> **Per the estate's model-integrity rule, a new dimension is admissible only if ORTHOGONAL and NECESSARY and SUFFICIENT — all three.**

*(Scheme ii classifies **what the evidence is about**; scheme iii classifies **how strongly it is held**. They are plausibly orthogonal — but that is the performer's determination to argue, not Governance's to assume.)*

## AMD3 §3 · ⭐ FLAG 2 — the revisit trigger is strengthened from three parts to four

| `-AMD2` required | §5 now requires |
|---|---|
| observable trigger · evidence source · responsible observation path | **Trigger · Evidence · Observer · AUTHORITY** |

**`AUTHORITY` is new, and the strengthening is adopted:** ⭐ **an observer who notices the trigger but holds no authority to reopen D2 does not close the loop.** All four fields are required. *"Someone should notice eventually"* remains expressly insufficient, and the trigger must not depend on an unowned observer — **D4.3: monitoring is owned by nobody.**

## AMD3 §4 · All prior constraints remain in force

five analysis axes (`-AMD1`) · **minimum sufficient combination OPEN — options A/B/C, and OPTION A MUST NOT BE ASSUMED** (`-AMD2`) · do not import C-5's existence test · no second C-10 model · canonical analysis unmodified · the output constraint *(must not say "C-10 is established"; must say "These criteria would be sufficient for PO/ARB to establish C-10")* · **`E1`–`E10` is not the final definition until this proposal defines it — a name is not a criterion** · `E10` is a maturity criterion outside the minimum set **but must still be defined** · independence/role declaration · **`R-34`/`P-2`** · **performer is NOT Governance `b64828fe`.**

## AMD3 §5 · ✅ The commission is now fully specified

**Nothing further is required before Architecture may begin.** Purpose, five axes, seven-part deliverable, mandated artifact name, seven-section structure, evidence-scheme reconciliation duty, four-part revisit trigger, the open combination decision, output constraint, and independence duty are all registered.

⛔ **START not performed.** ⚠️ **Lane blocker unchanged:** `S5` holds mutation ownership while holding a refusal, so no D5 lane can be made reachable (Inv C / G-3). **D5 may proceed as a PO/ARB-directed act, or as a lane once `S5` is disposed.**

**Traceability (AMD3):** PO/ARB act 2026-08-19 (artifact name + seven-section structure) · `G-KOS-AIP04-C10-D5-AMD3` · `-AMD2` · `-AMD1` · `G-KOS-AIP04-C10-D5` · DEC-D5 · DEC-D5-AMD1 · **D4.3** (monitoring unowned) · canonical analysis evidence-class census 2026-08-19 · ES-004 placement rule · model-integrity rule (orthogonal · necessary · sufficient) · `R-34`/`P-2`
