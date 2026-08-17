# KnowledgeOS — Context Map v2
## KOS-ARCH-BASELINE-002 · Seven contexts, relationships discovered

**Work item:** `KOS-ARCH-BASELINE-002` · **Grant:** `G-KOS-ARCHBASE2-MODEL` · **Session:** `S4-architecture-landscape-v2` · **Date:** 2026-08-17 · **Status: PROPOSAL — returned for PO/ARB decision.** **[ACCEPTED 2026-08-17 — PO/ARB act; registration: `2026-08-17-KOS-ARCH-BASELINE-002-acceptance-registration.md`; lifecycle annotation only, content at `f278dc54` untouched.]**

> Companion to **Architecture Landscape v2** (same date, same work item), which carries the boundary analysis and the two relationship studies this map compresses. Attribution and the discovery-guard disclosure are stated there and apply here. Relationship labels are **`Inferred`** strategic readings of `Observed` facts unless marked otherwise.

---

## 1 · The map

```
                HUMAN PO/ARB  (authority origin — outside the software)
                     │ performative acts (committed artifacts)
                     ▼
   PROJECT GOVERNANCE (external domain: rulebook A-1…A-8 · ES · ADR series)
        │                                    │
        │ ① policy flow:                     │ ② operating flow:
        │   amendments become                │   the Governance ROLE registers
        │   machine preconditions            │   acts/grants through the schema
        │   CUSTOMER–SUPPLIER,               │   PUBLISHED LANGUAGE seam;
        │   Governance upstream              │   sole authority writer (I-5,
        ▼                                    ▼   convention-backed on 3/5 — C-4)
   ┌───────────────────────────────────────────────────────────────────────┐
   │  BC-7  GOVERNED SESSION ORCHESTRATION          (Supporting Subdomain) │
   │  work items · assignments · roles-as-values · transitions ·           │
   │  grants-as-records · handoffs · human-act registration ·              │
   │  mutation ownership                                                   │
   │  state: runtime/workflow/*.json (append-only; no provenance — C-3)    │
   │  mechanism: AST-015 record machine · AST-016 read-only resolver (V-3  │
   │  open) — physically hosted in CMP-004 (placement, not boundary)       │
   └───────┬───────────────────────────────────────────┬───────────────────┘
           │ OPEN HOST SERVICE:                        │ mutual reference by
           │ fold / identity / authorized              │ identity, no shared model
           │ downstream sessions are CONFORMIST        │ (tokenRef/humanActRef out;
           │ (record outranks prose, I-10)             │ narrative back, never
           ▼                                           ▼ authoritative)
   BC-2 Implementation   BC-3 Verification    BC-1 Knowledge Governance
   Guidance (advisory      & Evidence           (adjudicated identity;
   Tier-2 tripwires)       (Tier-1 gate)        one-or-two systems: U-4 open)
      ▲
      │ SEPARATE WAYS at model level;         BC-4 Adversarial Review Support
      │ CMP-004 cohabitation recognized  ┄┄┄┄ (capability real, realized by the
      │ as PHYSICAL PLACEMENT ONLY            role model — the BC-7↔BC-4 line
      │ (ADR-AIP-03 closing clause)           is DASHED: OQ-2/ADR-C7 open)
                                              BC-5 Design & Decision Support
   BC-6 Session Continuity                    (declared platform Core —
   (disjoint stores; no measured              challenge standing, ADR-C2)
   dependency on BC-7 state;
   SUPPLIER of bootstrap to all sessions)
   ────────────────────────────────────────────────────────────────────────
   PLATFORM (all seven: SUPPORTING)  →  PRODUCT (PublicDigit/Election = CORE
   DOMAIN, AIP-14) — one-way guard seam unchanged: platform reads/guards app/;
   product never invokes platform                                  [Observed]
```

## 2 · Relationship register

| # | Relationship | Pattern | Direction | Ground (evidence) | Class |
|---|---|---|---|---|---|
| R-1 | Project Governance → BC-7 (policy) | **Customer–Supplier**, Governance upstream | one-way | rulebook amendments become machine preconditions (G-1/G-2/G-3/R8 cited in source); guidance changes never move the machine | `Inferred` · high |
| R-2 | Governance role ⇄ BC-7 (operating) | **Published Language** (the record schema) at the writing seam; sole authority writer | writes in, queries out | grant refuses without `humanActRef`; writer restricted mechanically on 2/5 transitions, convention on 3/5 (C-4 standing) | `Observed` (re-measured) + `Inferred` label |
| R-3 | BC-7 → BC-2 / BC-3 / BC-4 / BC-5 role sessions | **Open Host Service** (fold/identity/authorized) with downstream **Conformist** posture | one-way supply of lane truth | AST-016 refuses prose; I-10; observed refusals in practice | `Inferred` · high |
| R-4 | BC-7 ⇄ BC-2 (as contexts) | **Separate Ways** — no shared model; shared **component only** | none at model level | the two languages measured disjoint (baseline §3.2); CMP-004 cohabitation = physical placement per ADR-AIP-03's signed closing clause | `Observed` facts · `Inferred` label · high |
| R-5 | BC-7 ⇄ BC-1 / knowledge estate | **mutual reference across a Published-Language seam** — identifiers out (`tokenRef`, `humanActRef`), narrative back; no shared model, no shared lifecycle discipline | two one-way reference flows | mechanism reads only its own JSON (dependency root); I-10 subordinates prose; adjudicated-vs-folded truth disciplines | `Observed` + `Inferred` · high |
| R-6 | BC-7 ⇄ BC-6 | **Separate Ways** — disjoint stores, no measured dependency | none | day-files vs `runtime/workflow/*.json` disjoint (baseline/Stage-2); bootstrap injects MEMORY/CONTEXT, not workflow records | `Observed` · high |
| R-7 | BC-7 ┄ BC-4 | **UNRESOLVED — dashed line** | — | OQ-2/ADR-C7: rules-only context awaiting a component vs policy-of-the-role-model inside BC-7. **Not decided here**; deciding it is an ADR | `Unknown` |
| R-8 | Platform → Product | **Supporting → Core, one-way guard seam** | one-way | no `app/` reference to `.claude/`; `AIP-14` | `Observed` + `Declared` |

## 3 · What v2 changes against the Stage-2 current-state map

1. **CBC-1 becomes BC-7** — the candidate box is now a declared context (ADR-AIP-03, accepted), Supporting Subdomain.
2. **The two Governance arrows are separated** (R-1 policy vs R-2 operating) — Stage-2 drew one; the relationship study found two flows with different patterns and different change reasons.
3. **The BC-2 seam is re-stated**: Stage-2's "SHARED COMPONENT (C-1)" coupling remains a physical fact, but the *model* relationship is Separate Ways — the smell is dissolved at the model level, standing at the physical level until separately authorized work (if ever; `R-37`).
4. **The BC-4 line is drawn dashed** — v2 refuses to imply a resolution ADR-C7 has not made.
5. **The knowledge seam is named** (R-5) — previously implicit in `tokenRef` practice.

## 4 · Carried open, exactly as inherited

U-2/M-5 artifact-store boundary (ADR-C5) · U-4 knowledge-system line (ADR-C4) · OB-1 composition-root ownership + BC-5 Core challenge (ADR-C2) · registry-first scope, 3 unregistered hooks (ADR-C3) · C-3/C-4 store provenance and writer hardening (ADR-C6, `R-37`-gated) · OQ-2/BC-4 (ADR-C7) · **role-model ownership (ADR-AIP-04, expressly deferred)**.

---

**Next actor:** PO/ARB. **Traceability:** Architecture Landscape v2 (companion, this date) §§3–5 · accepted baseline v1.1 `40026b12` §§3, 5, 6, 8 · Stage-2 report `f4eb4f76` §8 · ADR-AIP-03 (accepted) · `AIP-14` · `R-37`.
