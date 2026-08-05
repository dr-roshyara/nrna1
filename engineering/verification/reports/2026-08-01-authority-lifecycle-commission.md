# Authority Lifecycle Commission

**Date:** 2026-08-01 · **Role:** Senior Principal Architect · **Commission:** how is authority **created, maintained, superseded and retired**? Lifecycle documentation only — governance not redesigned, authorities not reinterpreted, implementation untouched.
**Repository Integrity Gate:** ✅ PASSED.

> ## THE COMMISSION'S RESULT
>
> **The project has a rich, largely documented authority lifecycle — richer than the previous commissions assumed.** Succession, append-only history, status annotation, promotion ladders and a classification rule for *how something becomes a ruling* all exist and are in force.
>
> **Two lifecycle defects were found, and one is mine from the previous commission:**
> **L-1** — I cited **ADR-T13**, whose status is **"DEFERRED (Proposed)"**, as interpretive evidence. *A Proposed ADR is not binding.* **Non-binding authority used as if effective.**
> **L-2** — **ADR-T17 is superseded by ADR-T23**, yet six live references still present it as an open decision *"to be resolved as the first agenda item of Push B."* **Stale references to a superseded authority.**

---

## 1. Authority inventory — issuer, approval mechanism, status

| Authority | Layer | Issuing body | Approval mechanism | Status |
|---|---|---|---|---|
| **Constitutional Policies 1–4** | Business Authority | **ARB, constitutional capacity** | *"THE FOUR DECISIONS — RULED (ARB, 2026-07-25, explicit per-item)"* | **BINDING** |
| **Q-2 parameter rulings** | Business Authority | ARB | Q-2 Resolution Package; values *"RATIFIED as implementation bootstrap defaults, NOT constitutional defaults"* | **BINDING as bootstrap** |
| **ADR-T1..T22** | Architecture | ARB, architectural capacity | ADR issuance, recorded in the T-log | mixed — **see §3** |
| **ADR-T13** | Architecture | ARB | ⚠️ **"DEFERRED (Proposed)"** | **PROPOSED — not binding** |
| **ADR-T17** | Architecture | ARB | ⚠️ **superseded by ADR-T23** | **SUPERSEDED** |
| **ADR-MP series** | Platform architecture | ARB | ADR issuance | binding |
| **Rulings register R-1..R-42** | Governance | ARB | append-only ruling log | binding, per-row |
| **ES-001..006** | Governance | Engineering governance | standards index | binding |
| **EPIC-004K et al.** | Architecture | ARB | *"the architecture — APPROVED"* | approved |
| **Cross-Context Integration Contract** | Architecture | ARB | **PARTIALLY NORMATIVE** by explicit ruling | partially binding |
| **Implementation Protocol** | Operational practice | DA | adopted; **frozen for refinement** | operational |
| **`CLAUDE.md` · `.claude/MEMORY.md`** | Operational Guidance | ⚠️ **none** | ⚠️ **none — never issued** | **NOT authority** |
| Docblocks · plans · reports | Implementation / evidence | authors | none | **never authority** |

## 2. Authority creation matrix

| Question | Observed answer |
|---|---|
| Who creates? | ARB (business + architecture), engineering governance (ES), DA (protocol) |
| Who approves? | The issuing body, **explicitly and per-item** — *"RULED (ARB, explicit per-item)"* |
| When binding? | **On issuance, with the status recorded**. Q-2's wording shows the precision: *values ratified as bootstrap, **not** constitutional defaults* |
| How is scope established? | In the issuance text itself — ADR-T11 enumerates *"any aggregate, event payload, or projection"*; Policy 3 enumerates CL-1/CL-2/CL-3 |

**The governing rule already exists — R-34:** three classes of review information — *reviewer observations* (session logs only) · *ARB decisions* (rulings/ADRs, **created only on explicit ARB adoption, never by inference from praise or suggestion**) · *implemented engineering changes*. **Default classification is observation.**

> **R-34 already forbids authority-by-popularity.** *"Reviewer suggestions are NOT automatically rulings."* The capability the ARB asked about is **present and dated 2026-07-08** — what was missing was my consistent use of it.

## 3. Authority evolution matrix

| Mechanism | Observed instance | Rule |
|---|---|---|
| **Supersession** | **ADR-T23 supersedes ADR-T17**; R-38 supersedes R-34's detail | *"Decisions evolve by SUCCESSION, never retroactive editing"* |
| **Status annotation** | ADR-T21/T22 annotated *"since AUTHORIZED… decision text unchanged"* | **ES-004.3:** *"Decision text immutable / status annotations evolve; sync touches ONLY the mutable portion"* |
| **Clarification** | R-38 recorded as *"a consolidation/assessment ruling, not a sixth freeze"* | interpret existing rulings rather than add one (R-38's own precedent) |
| **Deferral** | **ADR-T13 "DEFERRED (Proposed)"**; ADR-T18 "POSTPONED" | deferred ≠ retired; **and deferred ≠ binding** |
| **Promotion** | ES-006.1 ladder; R-39's one-time exception **with the multi-context bar preserved** | evidence-gated |
| **Freeze** | R-27 · R-37 · R-38 | burden of proof reversed |
| **Retirement** | *"AST-008: deprecate → remove next release"* | one migration cycle |
| **Can a lower layer evolve a higher authority?** | **No** — protocol A-2: *"the authority wins and this document is the thing that changes"* | ✅ enforced |

**The evolution model is complete.** All six states the ARB anticipated exist in practice, and one — *append-only succession* — is stronger than typically found.

## 4. Authority consumption review

| Authority | Primary consumers | Secondary references | Substitution risk |
|---|---|---|---|
| Constitutional Policies | EPIC-004K, roadmap, WP plans | **MEMORY:57** (summary) | ⚠️ **realized** — MEMORY stood in for the primary all session |
| ADR-T11 | AT-Q7-001 guard | **charter** paraphrase | ⚠️ **realized** — withdrawn conclusion |
| EPIC-004K §11 | store docblock | that docblock | ⚠️ **realized** — H-2 |
| PB-006 | provider registration | `InboxHandler` docblock | ⚠️ latent |
| ADR-T3/T4 | inbox/outbox | docblocks | ⚠️ latent |
| §197 · §81 · §142 | WP-6 | — | ✅ none — read at source |

**Consumers becoming substitute authorities is the dominant failure mode in this project, and it has now occurred three times.** The mechanism is constant: an accurate summary **loses scope and adjacency**.

## 5. Traceability assessment — two defects

### L-1 · A non-binding authority used as effective *(mine, previous commission)*

| Field | Content |
|---|---|
| **What I did** | cited **ADR-T13** — *"current integrity = hash + audit-trail, weaker than voter-verifiable crypto proof … recorded known limitation"* — as evidence that the audit trail is constitutionally contemplated |
| **The defect** | **ADR-T13's status is "DEFERRED (Proposed)."** A *Proposed* ADR records a **candidate** decision. It is not an issued constraint and cannot support an interpretive conclusion |
| **Effect on that commission** | ADR-T13 was one of two evidence items cutting against my violation reading. **The other — AT-Q7-001's scan scope — is executable and stands.** So the *direction* of the correction survives; **one of its two supports must be withdrawn** |
| **Corrected status** | ADR-T13 may be cited as **a recorded limitation of the current design**, never as authority for what is permitted |

### L-2 · Stale references to a superseded authority

| Field | Content |
|---|---|
| **Superseded** | **ADR-T17** — the T-log records **ADR-T23** as *"supersedes ADR-T17 — the authority decides, the PM receives"* |
| **Stale references** | 6 in two live documents, e.g. *"One architectural decision is intentionally postponed (**ADR-T17**)"* · *"Carry **ADR-T17** … as the first decision of Push B"* |
| **Why it matters** | a reader arriving via `Architecture_Baseline_1.1.md` finds an **open decision that has been resolved.** Both statements were true when written |
| **Not a defect of those documents** | they are **historical** artifacts (readiness review, baseline) — under ES-004.3 history is **never rewritten**. The gap is that **no forward annotation** points from them to T23 |
| **Effective authority today** | **ADR-T23** |

**Traceability result:** every WP-6 conclusion traces to a currently-effective authority. **The two defects are both about authorities I consumed for *reviews*, not for implementation** — no shipped code rests on a superseded or proposed authority.

## 6. Authority lifecycle state model — as observed, not designed

```
        ┌───────────┐
        │ PROPOSED  │  recorded, NOT binding        ← ADR-T13 · ADR-T18
        └─────┬─────┘
              │ explicit per-item ARB adoption (R-34)
        ┌─────▼─────┐
        │ APPROVED /│  binding on issuance, scope fixed by its own text
        │  BINDING  │  ← Policies 1–4 · ADR-T11 · R-1..R-42
        └─────┬─────┘
              ├──────────────► CLARIFIED       status annotation only; decision text immutable (ES-004.3)
              │                                 ← ADR-T21/T22 annotations · R-38
              ├──────────────► FROZEN           refinement closed; reopening needs stated evidence
              │                                 ← R-27 · R-37 · R-38
              ├──────────────► SUPERSEDED       a NEW authority cites what it replaces; the old text stays
              │                                 ← ADR-T17 → ADR-T23
              └──────────────► DEPRECATED ──► RETIRED   one migration cycle (AST-008)
```

**Two properties worth naming, because they are stronger than typical practice:**
- **Text immutability with mutable status** — an authority's *content* never changes; only its *status annotation* does. This is why a superseded ADR remains readable and why history stays intact.
- **Succession, not amendment** — a superseding authority **cites what it supersedes**, so the chain is followable forward. **L-2 is the mirror image:** the chain is followable forward from T23, but **not backward from documents that cite T17.**

## 7. Governance risks

| # | Risk | Severity | Mechanism |
|---|---|---|---|
| **G-1** | **Summaries substituting for primaries** | **HIGH — realized 3×** | accurate summary, lost scope/adjacency |
| **G-2** | **Proposed/deferred authority read as binding** | **MODERATE — realized once (L-1)** | status is one column away from the text |
| **G-3** | **Stale references to superseded authorities** | **MODERATE — realized (L-2)** | supersession is forward-linked only |
| **G-4** | Unissued documents (`CLAUDE.md`, MEMORY) accruing de-facto force | **MODERATE** | frequent citation; **R-34 already forbids it** |
| **G-5** | Docblocks as architecture sources | MODERATE — realized (H-2) | proximity to code |
| **G-6** | Undocumented precedence between layers | **HIGH — open (H-3)** | ADR-T11's *"constitutional"* label vs Policy 3 |

**None of these is a gap in the lifecycle model.** Every one is a **consumption** failure — reading the right family of documents at the wrong status, level, or generation. **The lifecycle is sound; its use has been the weak point.**

## 8. Recommendations requiring ARB

| # | Item | Decision required |
|---|---|---|
| **A-1** | **L-1 correction** — ADR-T13 is *Proposed* and was cited as interpretive evidence. Confirm that Proposed/Deferred ADRs may be cited **as recorded limitations only**, never as authority for what is permitted | Confirm the reading rule |
| **A-2** | **L-2 stale references** — 6 live references present ADR-T17 as open. Since history is never rewritten (ES-004.3), the remedy is a **forward annotation** pointing to ADR-T23 | Authorize the annotation; **confirm it touches only the mutable portion** |
| **A-3** | **H-3 precedence** (carried) — ADR-T11's *"constitutional"* label vs Policy 3 | Precedence, and whether the label is a layer claim or a severity label |
| **A-4** | **G-4** — reaffirm that `CLAUDE.md`/MEMORY carry **no** authority, per R-34, and consider stating it in those documents themselves | Reaffirm; optionally annotate |

**Recorded, not requested:** no new governance mechanism is proposed. **Every capability this commission looked for already exists** — R-34 for creation, ES-004.3 for annotation, succession for evolution, ES-006.1 for promotion, AST-008 for retirement. **The finding is that the model is complete and my consumption of it was not.**

---

**Traceability:** **PRIMARY sources read:** `docs/adr/ADR-T-LOG-Tactical-Implementation.md` (ADR-T13 *"DEFERRED (Proposed)"* :20 · ADR-T17 · ADR-T23 supersession · T21/T22 status annotations) · `EPIC-003 §THE FOUR DECISIONS` (issuance wording) · `ADR-AIP-LOG-Platform-Rulings.md` (R-34 classification · R-37/R-38 freezes · R-39 promotion bar · AST-008 retirement) · `.claude/IMPLEMENTATION_PROTOCOL.md` (A-2 hierarchy) · stale references in `Architecture_Baseline_1.1.md` and `Architecture_Release_1.1_Readiness_Review.md`. **No governance redesigned; no authority reinterpreted; no document rewritten.**

---

## Completion statement — recorded WITH its two qualifications

The commission's completion statement is recorded, and the two defects found are **not** hidden inside it:

> **The authority lifecycle is verified. Every governing authority has an identified issuer, approval path, effective lifecycle and current status. Summaries are distinguished from governing sources.**
>
> **Two qualifications, both from §5:**
> - **Superseded authorities are identifiable FORWARD but not BACKWARD.** A superseding authority cites what it replaces (ADR-T23 → T17), so the chain reads forward. **Documents citing the superseded authority carry no pointer to its successor** — six live references still present ADR-T17 as open (**L-2**).
> - **Architectural conclusions can be traced to the currently effective authority — with one correction applied:** ADR-T13 is **"DEFERRED (Proposed)"** and was cited as interpretive evidence in the previous commission (**L-1**). Corrected: *Proposed* authorities may be cited as **recorded limitations**, never as authority for what is permitted.
>
> **No shipped code rests on a superseded or proposed authority.** Both defects occurred in **review consumption**, not in implementation.
