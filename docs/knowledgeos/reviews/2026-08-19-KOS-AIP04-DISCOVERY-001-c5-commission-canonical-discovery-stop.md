# C-5 Separation Attestation commission — **STOPPED at Canonical Discovery**

**Work item:** KOS-AIP04-DISCOVERY-001 · **Commission:** *"Define and analyze C-5 as an operational capability"* (PO/ARB, 2026-08-19)
**Performing process:** `claude-code-session:b64828fe` — **Governance for this work item throughout**
**Result: NOT PERFORMED. No C-5 analysis produced.** Two independent grounds, either sufficient.

---

## 1 · Ground one — the analysis already exists, and a second one is forbidden

**ES-005.4 / Operating-Loop Phase 3:** *search before modelling — if it exists, consume or extend it, never create a second.*

**It exists:** `docs/knowledgeos/architecture/KOS-AIP04-DISCOVERY-001-capability-architecture-analysis.md` — **922 lines**, C-5 treated at **§4** (§4.1 twenty-six points · §4.2 nine dimensions · §4.3 bounded-context threshold · §4.4 decision-ready block), reworked at **A1.2** (four separate questions), and corrected at **C3.3**.

**All fifteen requested deliverables map to existing sections:**

| # | Requested | Already at |
|---|---|---|
| 1 | C-5 definition | §4, A1.2 |
| 2 | existence assessment | **A1.1** (existence separated from category), A1.2 Q1 — verdict **`YES`** |
| 3 | operational workflow | §4.1(6,16), A1.2 Q3 |
| 4 | separation dimensions | §4.2 · **A1.2 nine dimensions** + **C3.3 external attestation** = the ten asked for |
| 5 | assurance modes | the four-state model (`…NOT_ESTABLISHED` → `EXTERNALLY_ATTESTED` unreached), C3.3 |
| 6 | what C-5 can prove | A1.2 *"What the platform can establish mechanically"* |
| 7 | what it cannot prove | same section; `INV-ATTR-1`/`INV-ATTR-2` |
| 8 | candidate categories | §9, A1.2 *"Why control-plane function fits"* — **cross-context control-plane assurance capability**, bounded context **No** |
| 9 | candidate stewardship | §10 — **options listed, none selected** |
| 10 | OQ-L evidence | **A1.6** — *party · issue · constrain · advisory / authoritative / machine-generated result · independent ratification* **already defined**, OQ-L **not decided** |
| 11 | dependency analysis | **A1.7** — already split semantic / evidence / implementation |
| 12 | implementation implications | §4.1(16), A1.7 |
| 13 | explicit OPEN decisions | §12, §15, A1.10, C3.8 |

**Your §5 constraint is already honoured, not pending:** `ORGANIZATIONAL_INDEPENDENCE` = **`NOT_ESTABLISHED`**, and C3.3 states explicitly why it is *not* `NOT_APPLICABLE` and why **intra-platform third-party ≠ external attestation**.

**Your §4 external-attestation dimension is finding `F-3`, and it is already corrected** (C3.3, `NOT_ESTABLISHED`, `OBSERVED`).

⇒ **Producing a fresh analysis would create a second, competing C-5 model** — while the first is **mid-verification** (`S5` ACTIVE, outstanding). That forks the subject under assurance.

## 2 · Ground two — I am the wrong actor, and C-5 is the reason

I am **Governance** for this work item: I registered its grants, assignments, handoffs, the START transcription, the HANDOFF, and the provenance reconciliation.

**If I author this analysis I become a producer on the item I govern** — and the artifact would be *the analysis of separation attestation itself*. That is the exact defect shape the provenance reconciliation just recorded at **§4**: one process on both sides of a governed separation. **C-5 operating correctly forbids its own analysis being written by the party that governs the record it rests on.**

> ⭐ **This refusal is itself C-5 evidence** — the third separation event on the work item that is modelling separation. **Recorded as evidence; no ownership inferred; `OQ-A` untouched.**

## 3 · What is genuinely outstanding

⚠️ **Nothing in the commission is unaddressed by the existing artifact.** What remains open is not analysis but **disposition**:

1. **§4 of the provenance reconciliation** — the AMD2 findings (`ca6039a8`) and Correction #3 (`c3839624`) share one author, `4858c37c`. **Correction #3's provenance is defective. Awaiting PO/ARB.**
2. **`S5` verification is outstanding** — started, refused on independence, unconsumed. **No qualified verifier has been routed.**
3. **The Group A/B/C/D decision pack** (A1.8) is delivered and **awaiting PO/ARB decisions**, including **OQ-L** and **OQ-A**.

## 4 · Recommendation

⛔ **Do not commission a new C-5 analysis.** Instead, in this order:

1. **Dispose of the §4 provenance defect** — it determines whether Correction #3 stands at all, and every C-5 conclusion in it inherits that answer;
2. **Route `S5` to a qualified process** — barred on evidence: `4858c37c`, `5e1dd9ee`, `2da45a86`, `1c8b041b`;
3. **Then decide the A1.8 decision pack** — the analysis for it is already delivered.

**If a genuine C-5 gap is identified after verification, commission it as a narrow correction to the existing artifact** — by a process that is neither this work item's Governance nor a barred producer.

## 5 · Not done

⛔ C-5 not defined or re-analysed · existence not re-assessed · dimensions not re-derived · assurance modes not re-modelled · **C-5 ownership not decided · C-5 category not decided · OQ-L not decided · assurance level not decided · bounded context not decided · build order not decided** · the existing artifact **not modified** · no transition appended (`S5` holds mutation ownership; Governance manufactures no handoff).

**Traceability:** PO/ARB C-5 commission 2026-08-19 · the existing analysis (`ba74dbdd` → `aff41549` → `c3839624`) §4 / A1.2 / A1.6 / A1.7 / A1.8 / C3.3 · AMD2 verification `ca6039a8` (C-5 = PASS WITH NOTES, `F-3`) · provenance reconciliation §4 · seq 27 (independence refusal) · ES-005.4 · R-34/P-2 · INV-ATTR-1/INV-ATTR-2
