# Implementation Authorization Act — PREPARED FOR SIGNATURE (`EM-IMPL-001`)

**Type:** Act preparation (Governance) · **Date:** 2026-08-17 · **Precondition met:** the commissioned dependency reconciliation concludes **no category-① item remains** (`docs/publicdigit/architecture/2026-08-17-EM-ARCH-001-dependency-reconciliation.md`, accepted by Governance review of its checkable claims).
**⛔ PREPARED, NOT PERFORMED. Implementation, technology selection and deployment remain UNAUTHORIZED until the PO signs. Nothing starts from this document.**

## 1 · The authorization act, ready for signature

> **"I authorize implementation of the approved `EM-ARCH-001` target architecture for the qualified Model A operating core, on the [performing lane], under the constraints below. This authorization covers implementation only: it authorizes no deployment, no production election, and no resolution of any open governance question. Technology choices made under it are implementation decisions within the approved architectural style and the repository's standing layer rules."**

## 2 · Binding constraints (from the record; the act incorporates them)

**C-1** the surviving dependencies bind as the reconciliation classified them: **D-1** build the `OrganisationalAppointmentAuthority` port **with no adapter, default, stub or fallback** · **D-3** the gate subject stays **abstract**; no concrete first-gate binding is implemented · **D-4** **nothing** attaches to OPEN or to inaction — no timer, no escalation, no expiry hook · **D-5** no `C-2` classifier component exists · **D-6** adopted `036` consumed directly; no menu capability · **D-7** event names remain marked non-canonical (technical type naming is Architecture's mapping, per the registered DDD separation) · **D-8** clocks compute between recorded instants only; no civil-time meaning fixed · **D-9** positions = accept/object; the two *cancelled* levels stay distinct types.
**C-2** the adopted vocabulary binds: the terminal state renders as **ELECTION DISCONTINUED** (`EM-GOV-069`), purely terminal per the registered meaning boundary.
**C-3** the constitutional invariants bind build-breakingly: **ADR-T11** (no voter↔vote linkage in any persisted shape, event payload or projection) · `F-PROTO-1` properties 1–7 · `P-2H`.
**C-4** the programme's engineering discipline binds: **RED before GREEN** (tests first, as separate commits so ordering is provable — the registered `EM-BRQ` lesson) · the repository's layer rules (Domain pure) · one aggregate per command.
**C-5** **independent verification follows implementation** (the four-session model): the implementing lane never self-certifies; verification is a separate lane, separately started.
**C-6** deployment remains gated even after GREEN: a concrete first-gate binding (D-3 residue) and the external authority's existence (D-1) still gate any **deployed** election — by design.

## 3 · What the PO adds when signing

**(i) the performing lane** *(Implementation — Session 3 in the four-session model, or as designated)* · **(ii) the increment boundary** — recommended: a first bounded increment (e.g. the domain core: AG-1/AG-2/AG-3 + policies P-1…P-7 + protocol port contracts) rather than the whole design at once, presented as a boundary before code per the programme's pattern · **(iii) checkpoint/deadline · (iv) the signature.** *(Grant and START follow as their own gated acts, per the standing discipline.)*

**Traceability.** Reconciliation (accepted) · design approval registration §2 · `EM-GOV-069` registration §4 · hard gates · ADR-T11 · A-3.
