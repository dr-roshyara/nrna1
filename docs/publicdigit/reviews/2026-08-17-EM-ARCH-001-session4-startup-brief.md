# `EM-ARCH-001` — Session 4 Startup Brief

**Type:** Startup brief (Governance, for the PO's use) · **Date:** 2026-08-17
**Purpose: how to START the commissioned Architecture lane. This document adds NO design instructions — it packages what is already signed.**

## 1 · What Session 4 is

**A separate working session in this repository, acting as the ARCHITECTURE lane commissioned by `EM-ARCH-001`.** Not Governance, not implementation, not a continuation of any other session's context. It starts fresh — which is a feature: it reads the qualified rules as written, not as remembered.

## 2 · How to start it — three steps

**① Open a new session** (new terminal / new Claude Code session in this repo). **② Perform the START act** — the recorded human act that begins the lane, e.g.:

> *"I START EM-ARCH-001 on the Architecture — Session 4 lane, under the signed commission of 2026-08-17."*

**③ Paste the startup prompt** (§5). Per the programme's startup convention, the session works read-only until the START act is given, and STOPs rather than assumes when an input is missing.

## 3 · The input package (all committed; exact paths)

| Input | Path |
|---|---|
| **The signed commission** (scope · guardrails · §5 signature) | `docs/publicdigit/reviews/2026-08-17-election-architecture-commission-prepared.md` |
| **Signed registration** (lane · effects · role separation) | `docs/publicdigit/reviews/2026-08-17-EM-ARCH-001-commission-signed-registration.md` |
| **The qualified corpus** — ADOPTED rows only; freeze `52587d41`, sha256 `b6f232cd…` | `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md` |
| **Final qualification report** — esp. §4 KNOWN constraints | `docs/publicdigit/reviews/2026-08-17-EM-BRQ-001-final-qualification-report.md` |
| **Hard review gates G-1…G-6** *(the lens the deliverable will be reviewed under — transparency, not instruction)* | `docs/publicdigit/reviews/2026-08-17-EM-ARCH-001-review-criteria.md` |
| Constitutional invariants | ADR-T11 (anonymity) · `F-PROTO-1` (protocol properties), as cited in the Manifesto |

## 4 · The commissioned work, restated in one line each

**Phase ① reconstruct** which adopted rules participate in Model A (no redesign) → **② domain model** (concepts · responsibilities · relationships · lifecycle behaviour · invariants) → **③ boundaries** (acceptance · Committee membership/recovery · gates · external-authority boundary · terminal-state boundary · timing/recovery) → **④ behavioural flows** (normal acceptance · objection/failure · temporary unavailability · vacancy · Inoperative · restoration · expiry→terminal · OPEN-gate) → **⑤ target architecture** (bounded contexts · layer responsibilities · interfaces · state transitions · persistence responsibilities · justified events · dependency directions). **Deliverable returns for Human/PO/ARB approval; implementation is NOT authorized.**

## 5 · Ready-to-paste startup prompt for Session 4

> You are **Architecture — Session 4**, the performing lane of the signed commission **`EM-ARCH-001`** (2026-08-17). Read, in this order: the signed commission (`docs/publicdigit/reviews/2026-08-17-election-architecture-commission-prepared.md`), its registration (`…-EM-ARCH-001-commission-signed-registration.md`), the final qualification report (`…-EM-BRQ-001-final-qualification-report.md`, especially §4), and the Election Manifesto (`docs/publicdigit/business_rules/ELECTION_MANIFESTO.md`) — **ADOPTED rows only are rules**.
> Your task is the commission's, verbatim: **design the target domain and architecture structure that REALIZES the qualified Model A business rules WITHOUT CHANGING THEIR BUSINESS MEANING**, in five phases: reconstruction → domain model → boundaries → behavioural flows → target architecture. **Every architectural decision must cite the qualified rule that requires it** — "cleaner" is not a reason. **Where a design decision needs an unresolved governance answer (e.g. `EM-OPEN-053`, `049`, `066`, `077`/`076`), name the dependency and stop that branch — never assume.** Do not invent or change any rule, do not name the terminal state (`EM-OPEN-110` — carry a placeholder), do not substitute the external organisational authority, do not commit to technology, do not write production or test code. **The four conditions `Inoperative ≠ Halted ≠ OPEN ≠ Terminal` are distinct business meanings and must stay distinct; OPEN must not become a technical timeout.**
> Deliver a design document (resolve placement with `php scripts/doc-placement.php`), phase-ordered, with per-element traceability, and return it for Human/PO/ARB review. **You are architecture-only: implementation is not authorized.**

## 6 · Boundary notes

Session 4 does not modify Governance artifacts, the Manifesto, or other sessions' files; it produces its own deliverable. Session 2 reviews on return, against G-1…G-6. Session 1 (Verification) may later be commissioned against the design — not started by this brief.

**Traceability.** `EM-ARCH-001` signed commission §5 · signed registration · review criteria §3a · A-3.
