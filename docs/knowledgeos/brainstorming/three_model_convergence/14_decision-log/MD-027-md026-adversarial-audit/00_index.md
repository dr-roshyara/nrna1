# MD-027 — Adversarial Audit of MD-026 Corpus-Boundary Findings

**Status: COMPLETE.** A quality gate, not a research phase. **Does not modify MD-026. Does not admit
any directory. Does not perform composition. Does not open Stage 07. Does not reopen K-1/K-2.**

## Verdict, stated up front (full reasoning in `01`–`07`)

**Two corrections to MD-026's wording are required, both accepted exactly as the authorizing critique
named them:**

1. *"Did not exist to omit"* → corrected to *"was not Git-tracked at the time of the boundary
   decision — filesystem existence prior to tracking cannot be established from available evidence."*
2. *"Two independent, decisive pieces of evidence"* → corrected to *"two convergent but
   evidentially-dependent observations, both drawn from the same repository-history chain — the MD-
   010/MD-011 date and the 2026-09-06 commit are not independent confirmations of intent."*

**One new, genuinely additional data point found this audit** (filesystem `mtime`, disclosed with its
own reliability caveat): `kernel-reduction/04-operator-contracts.md` and M0030 carry `mtime`s 19
minutes apart on the same evening (2026-09-01) — consistent with, not proof of, same-session
authorship; `mtime` is well-known to be a checkout/copy artifact, not reliable provenance evidence on
its own.

**Governance-readiness verdict: `YES`, with the two corrections applied.** The evidence package,
corrected, does not mislead a decision-maker about what is fact, reconstruction, interpretation, or
unknown — see `07`.

## Frozen input

MD-026 (14 files, read in full, unmodified). MD-025, Stage 06, Phase 1–4/6, Phase 5A–5N, the handover,
MD-022 — read where directly relevant, not modified. P-series not consulted.

## Artifact map

| File | Contents |
|---|---|
| `00_index.md` | This file. |
| `01-claim-audit.md` | The required 12-claim status table. |
| `02-temporal-evidence-matrix.md` | Every dated event, what it establishes and does not. |
| `03-ddd-boundary-audit.md` | The 5 distinct boundary types, kept separate. |
| `04-evidence-dependence-audit.md` | Whether MD-026 double-counted dependent evidence. |
| `05-falsification.md` | The 8 required falsifiers, tested. |
| `06-md026-correction-register.md` | Required narrowings, MD-026's own text not edited. |
| `07-resolution-status.md` | ESTABLISHED / NARROWED / NOT ESTABLISHED / GOVERNANCE-READY? / SMALLEST NEXT ACTION. |
