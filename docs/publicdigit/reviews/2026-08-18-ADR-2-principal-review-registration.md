# Registration — Principal Architect review of ADR-2 (Recovery Origin Provenance Ownership)

**Registered by:** Governance, on the delivered review · 2026-08-18
**Subject:** `docs/publicdigit/adr/ADR_20260817_2300_Recovery_Origin_Provenance_Ownership.md` (`63ec7e7f`)
**⚠ This registration records a REVIEW. It is not the PO/ARB decision — §6 of the ADR remains deliberately blank.**

## 1 · The review's verdict

**Approved for PO/ARB signature.** Eight areas approved — problem framing · causal ownership framing · DDD bounded-context ownership · provenance-vs-data-availability distinction · protocol authority boundary · the `RestorationOrigin` concept · decision-block completeness — with implementation readiness marked **⛔ waiting for PO/ARB decision**.

**The review's headline observation, recorded because it states what the ADR got right:**

> **The lane did not overreact by adding mechanisms. It added decision dimensions. That is exactly what a good ADR should do.**

And on the hold's purpose:

> **The architecture hold has achieved its purpose: it stopped the team from "fixing null" while accidentally inventing domain semantics.**

## 2 · The two additions the review singled out

**Per-origin ownership** — *"a concept is not owned because it has a class; it is owned because one bounded context defines its meaning"*, with its corollary *"two contexts writing the same origin type is co-ownership, which is no ownership."* The review's worked failure case: a shared `RestorationOrigin` written by two contexts and interpreted by a third — *"the model looks clean technically but is architecturally broken."*

**Vocabulary discipline** — separating *domain fact* from *protocol/event-sourcing event*, so a future reader of an `…Event` name does not infer an event stream, replay, aggregate versions or reconstruction semantics that do not exist. Registered as it was framed: the ADR *"protects against accidental architectural promises."*

## 3 · The review's one final recommendation — verified ALREADY PRESENT

The review asked for one sentence near §5c/§6:

> *"An origin type may be referenced by other bounded contexts, but only its owning bounded context may create, validate, or change its meaning."*

**It is already in the ADR**, at §6 in materially the review's own words, added by the ADR's most recent commit (`63ec7e7f`, *"add the consumption rule underpinning the ownership decisions"*) — including the rationale the review gives for it: it forecloses the half-claim *"we don't own the concept, but we need to validate it"* — **consumers may use meaning; they do not define it.**

**No edit was made.** The recommendation is satisfied as written; adding a second copy would duplicate a rule that already has a canonical place (`ES-005.4`).

## 4 · State, unchanged by this registration

`§6` **BLANK** — the seven decision dimensions (a)–(g) and the pre-drafted *"what this decision does NOT mean"* clause await the PO/ARB. **No implementation is authorized**; the review's own sequence is: ADR-1 + ADR-2 decisions → authorization check → possible domain slice → application normalization → GREEN-5. The review states **no further ADR refinement is necessary unless the PO decision exposes a contradiction.**

**Traceability:** the delivered Principal review 2026-08-18 · ADR-2 `63ec7e7f` (§5c, §5c.1, §5d.0, §6 (a)–(g), §6a) · the consumption rule at §6 · `ES-005.4`
