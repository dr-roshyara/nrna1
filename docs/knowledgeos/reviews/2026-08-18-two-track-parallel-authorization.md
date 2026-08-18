# Registration — Two parallel engineering tracks authorized

**Registered by:** Governance, on the delivered PO/ARB act · 2026-08-18
*Placed under the KnowledgeOS root: the subject is the programme's track structure, and placement is derived from the subject.*

## 1 · The authorization

**Both tracks may proceed in parallel.**

| | **TRACK 1** | **TRACK 2** |
|---|---|---|
| Work | `KOS-CONTRACT-NEUTRALITY-001` | EKS / Knowledge Engineering Platform Evolution |
| Purpose | bounded PHP implementation | ADR-AIP-04 capability/role discovery · session engineering · governance/communication/knowledge capabilities · future platform evolution |
| Scope | PHP adapter · PHP L3 · PHP L4/L5 · LCOM4 · declared conformance evidence | platform evolution |
| Posture | ⛔ **No new architectural decisions** — it **consumes** accepted decisions | **may evolve the EKS platform independently** |

## 2 · ⭐ The coupling rule — what makes parallelism safe

> **Neither track may silently change the shared L3 contract or accepted semantic decisions.**
> **Any shared-boundary change requires a separate Architecture/PO/ARB act.**

**This is the load-bearing clause.** Two tracks running independently over one shared contract is precisely the shape that produces divergence — and the rule forecloses the mechanism: **the shared boundary is not owned by either track.** Track 1 consumes it; Track 2 may not amend it in passing; a change to it is a third act by a third authority.

**Independent Verification remains a separate actor for each deliverable** — parallelism does not merge the verification lanes, and neither track verifies its own output.

## 3 · Track 1 — already running, no action required

`S3-implementation-track1-php-adapter` is **ACTIVE** under the corrected authorization (PHP adapter + PHP L3 + PHP L4/L5), with the fit assessment delivered and **no code yet written**. **Its "no new architectural decisions" posture is already enforced by its grant**, which forbids changing the semantic rules, redefining L3, or changing the conformance authority.

## 4 · Track 2 — authorized as a track; its first assignment still needs commissioning

**What exists:** the **capability-first guard** for ADR-AIP-04, registered *before* any commissioning — the governing question is **"what capabilities does a sustainable AI Engineering Platform require?"**, evaluated **capability → ownership → bounded context/stewardship → human role → agent → shared platform service**, with people and agents **last**. And four backlog items — `EKS-01` distribution · `EKS-02` placement enforcement · `EKS-03` historical relocation · `EKS-04` lifecycle handoff ambiguity — all **BACKLOG, uncommissioned**.

**What does not exist:** an `ADR-AIP-04` document, and **any Track-2 work item, grant or assignment.**

⚠️ **Governance did not create one.** The act names Track 2's *purposes* — four of them, spanning capability discovery, session engineering, platform capabilities and "future platform evolution" — which is **a programme, not an assignment**. Manufacturing a single work item broad enough to cover it would be inventing scope the act did not define, and would hand a lane authority over the shared boundary that §2 explicitly withholds.

**Recommendation:** commission **ADR-AIP-04 capability discovery** first — it is the first-named purpose, its guard is already registered, and the BC-7 role-model question has been formally deferred to it since 2026-08-17. The other three purposes each need their own act.

## 5 · Boundaries

⛔ No Track-2 work item, grant or assignment created · no ADR-AIP-04 drafted · no EKS backlog item commissioned · nothing changed in Track 1 · the shared L3 contract and accepted semantic decisions untouched.

**Traceability:** the PO/ARB act 2026-08-18 · Track-1 corrected authorization `G-KOS-CONTRACT-IMPL-TRACK1-AMD1` · capability-first guard (ARB sequencing determination, 2026-08-18) · `ADR-AIP-03` consequence (b) — role-model ownership deferred to ADR-AIP-04 · `EKS-01`…`EKS-04` backlog
