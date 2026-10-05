# HTTP layer — Developer Guide (index)

**Area:** `app/Http/` — controllers, middleware, form requests (the thin HTTP boundary).
**Rule:** the HTTP layer authorises the request, validates input, delegates to application commands/queries and shapes **display read-models**. It never decides voting entitlement, admission or security itself — those decisions live in `app/Contexts/Elections` (see `developer_guide/election/voter_admission/`).

This area holds **short, step-scoped notes** for HTTP-layer changes. Where a change belongs to a feature that already has a guide, the note **points** to that guide instead of repeating it.

## Steps

| File | Change | Feature guide |
|---|---|---|
| [01_voter_management_identity_evidence_read_model.md](./01_voter_management_identity_evidence_read_model.md) | `ElectionVoterController::identityVerificationStates()` returns the identity **evidence** state independently of the Voter Admission Policy (PBDIGIT-213 C4 correction) | [`election/voter_admission/04_c4_ui_and_bulk_verification.md`](../election/voter_admission/04_c4_ui_and_bulk_verification.md) |
