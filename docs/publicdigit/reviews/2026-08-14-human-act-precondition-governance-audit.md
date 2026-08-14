# Human-Act Precondition — Governance Audit

**Type:** Governance analysis (Session 2) · **Date:** 2026-08-14 · **Commission:** PO — determine whether *"Governance may register authority only after an existing performative Human/PO/ARB act"* is already sufficiently normative, or whether KOS-AI-ORCH-001 requires a minimal amendment
**⛔ Analysis only. No rule is created or amended by this document. The candidate amendment in §4 is a PROPOSAL for the PO/ARB — Session 2 does not turn its own recommendation into the amendment.**

---

## 1 · The candidate rule, decomposed

*"Governance may register authority only after an existing performative Human/PO/ARB act"* contains four distinct claims:

| # | Claim | |
|---|---|---|
| (a) | **WHO** — only Governance registers authority | writer restriction |
| (b) | **WHAT** — what is registered is a human act, by reference | content restriction |
| (c) | **WHEN/EXISTENCE** — the act must already exist as a durable performative record at registration time | temporal/existential precondition |
| (d) | **WHAT IS NOT AN ACT** — intent, recommendation, template, or the registration artifact itself never qualifies | negative definition |

## 2 · The existing normative surface, examined *(the commissioned artifacts, exact texts)*

| Source | Text (verbatim where load-bearing) | Covers |
|---|---|---|
| **G-2** (accepted amendment, rule §10a) | *"Only the Governance role writes the Authority State, and only to register a **recorded** Human/PO/ARB act"* | **(a) explicitly · (b) explicitly · (c) by the word "recorded" — plain reading: the act exists, durably, before the write** |
| **A-2.4** (registered ruling Q-5) | *"Human authority remains in the **existing performative/committed** artifacts; Governance registers those acts **by reference**"* | **(b) explicitly · (c) explicitly ("existing … committed")** |
| **§15 / §10a authority chain** | *Human/PO/ARB → governance decision → explicit bounded authorization → …*; *"the record never manufactures authority"* | (c) directionally — authority originates upstream of registration |
| **R5a/R5b** (verified contracts; helper `workflow-state.php:321-326`) | non-Governance write → refused (exit 65); grant with empty `humanActRef` → refused: *"a grant registers a recorded human act by reference — the record never manufactures authority"* | (a), (b) at the mechanism level. **(c) NOT machine-checked — the helper verifies the reference is non-empty, not that the act exists.** Under ruled Q-4 (no machine parses prose for authority) this is **correct, not a defect**: act-existence verification is structurally a GOVERNANCE-role duty, unautomatable by design |
| **D-4** (startup convention, A-1.3) | question 5 "authorization"; *"never infer authority"* | consumption side — sessions must not act on inferred authority |
| **Precedent practice** (not rule text) | the intent-vs-ruling discipline: *"I would do X"* twice held unregistrable until the performative act arrived (2026-08-13/14, PO-endorsed both times); the role-records registration's own note *"the registration artifact did not create the authority"*; the Q-1–Q-6 STOP report (*"no performative ruling available"*) | **(d) — covered ONLY by precedent and per-artifact notes, not by any rule sentence** |

## 3 · Answer to the commissioned question

> **SUBSTANTIVELY NORMATIVE ALREADY for claims (a), (b), (c):** G-2's "recorded" and A-2.4's "existing performative/committed artifacts" state the writer, content, and existence preconditions in accepted/registered rule text, and the verified mechanism enforces (a)+(b) to the limit that ruled Q-4 permits.
>
> **NOT YET RULE TEXT for claim (d):** the two facets that were **load-bearing in real incidents** — *intent/recommendation/template is not a registrable act* (the "I would do…" episodes, twice) and *a registration may never be its own `humanActRef`* (self-reference/authority-laundering) — exist only as precedent practice and per-artifact notes. Precedent held both times, but the orchestration rule's own founding lesson (§2: "what held the line was convention, and every convention was invented during the incident it mitigates") argues that a twice-exercised load-bearing discipline deserves one sentence of rule text.

## 4 · Proposed minimal amendment — **A-3 candidate (PROPOSED — the PO/ARB accepts, amends, or rejects; parsimony checked)**

> **A-3 (candidate):** *"A registrable Human/PO/ARB act is one that has already been performed and durably recorded. A statement of intent, recommendation, preliminary position, or template is not a registrable act. A registration artifact can never serve as its own `humanActRef` — registration preserves authority; it never creates it."*

**Parsimony check (ES-001.1):** one three-sentence clause; closes exactly claim (d); adds no new concept (every term already exists in the rule); amendment route per the standing D-1/Q-6 pattern — **no ORCH-002, no new document.** **If the PO instead rules "already sufficient — plain reading of G-2 covers (d)"**, nothing changes and this audit stands as the recorded interpretation; **either outcome is one line from the PO.**

## 5 · What this audit does NOT do

Enact A-3 · touch the helper or any mechanism (the R5b reference-only check is correct under ruled Q-4) · reopen Increment 1 (verification gate closed, `a8477f5a`) · touch the role-records track, Increment 2, or Election matters.

## 6 · ACCEPTANCE RECORD (signed PO/ARB ruling, 2026-08-14 — verbatim)

> *"PO/ARB RULING — KOS-AI-ORCH-001 — A-3 Human-Act Precondition Amendment — Date: 2026-08-14. I rule as follows: **A-3 is ACCEPTED.** The following text is added to KOS-AI-ORCH-001: 'A registrable Human/PO/ARB act is one that has already been performed and durably recorded. A statement of intent, recommendation, preliminary position, or template is not a registrable act. A registration artifact can never serve as its own humanActRef — registration preserves authority; it never creates it.' Parsimony check: Amendment to ORCH-001. No ORCH-002. This ruling authorizes Governance to REGISTER this amendment. This ruling does NOT authorize implementation. The verified R5b mechanism remains correct under Q-4. Increment 1 remains closed and is not reopened. — Signed: PO/ARB Chief, 2026-08-14."*

**Registered as Amendment A-3 on KOS-AI-ORCH-001 (same commit as this record). §4's candidate is thereby consumed; this audit's §3 stands as the analysis of record.**

**Traceability:** G-2 (`51ba56fd`) · A-2.4 (`57dbf419`) · §10a/§15 (accepted rule) · R5a/R5b (verified `aac62274`; source `workflow-state.php:321-326`) · D-4/A-1.3 (`f5981933`) · intent-vs-ruling precedents (session logs 2026-08-13 "Twenty-second/Twenty-third entries", 2026-08-14 role-records STOP report) · ES-001.1.
