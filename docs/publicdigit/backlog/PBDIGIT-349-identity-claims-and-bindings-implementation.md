# PBDIGIT-349: Identity claims and bindings (I-TDD-02): implementation

**Status:** IN PROGRESS (created 2026-11-14) · **I-1 … I-4 authorised by the Principal 2026-11-15** (development and test only; P-I2 = L1; Account = the legacy `users` row, transitional) · **I-1 DONE 2026-11-15** · **I-2 DONE 2026-11-15** (ordinary claims; production refuses until I-4) · I-3, I-4 not started · production readiness not established · EP-01 plan 2026-11-15 (`docs/plans/20261115-1426-identity-claims-and-bindings-pbdigit-349-plan.md`; readiness **PARTIALLY READY**; Principal questions Q-1 … Q-5) · **Context:** Identity · **Kind:** implementation

## Why (found by PBDIGIT-345 task 6)

The HTTP entry E-1 checks, on every request, that the signed-in account is **currently bound** to the selected organisation (ISO-3, D-178 §5). A binding is Identity's record "reference R of organisation O is Person P" (D-113):
- created only by **claim redemption**;
- ended only by Identity's own act.

The design is accepted with conditions (**I-TDD-02 revision 12, D-200**). Nothing of it is implemented:
- no claim;
- no redemption;
- no binding store;
- no binding query.

**So, until this item is done:**
- Platform's `IdentityBindings` port is answered in production by `NoIdentityBindingsYet`: nobody is bound, and every registry organisation is refused over HTTP (fail closed);
- the registrar's **bootstrap claim** (D-193 F-03, proof obligation **B-8**), outside PBDIGIT-345 task 5, cannot be issued;
- PBDIGIT-341 (accounts resolved by matching e-mail) cannot be replaced by claim codes.

**Business meaning:** the routing is built and proven, but no person can reach a registry organisation until Identity can say who belongs to which organisation. **No production registry organisation exists, so nothing is lost today.**

## Scope (when authorised)

- I-TDD-02 rev 12 as accepted by D-200:
  - claims (issue, replace, revoke, redeem; ordinary and bootstrap);
  - bindings;
  - the build gates PI-1 … PI-48;
  - the security assessment PI-17b.
- **Identity's adapter for Platform's `IdentityBindings` port** (`isBound(accountId, organisation)`: account → active Person → a current binding to O). It replaces `NoIdentityBindingsYet` in `PlatformServiceProvider`. One recorded cross-context adapter, as for `IdentityContactAccounts`.
- The organisation picker: the signed-in person's own bindings, inside Identity (D-178 L-10).

**Never:** answering ISO-3 from the legacy user-organisation tables (D-160: the legacy and registry paths never share an organisation).

## Dependencies

- Governance `manage-claims` / `issue-claim` / `revoke-claim` act kinds (D-196, D-208 thin slice).
- Platform registry (PBDIGIT-345, done to task 6).

## Acceptance

- PI gates executed with evidence.
- PBDIGIT-345's T-1 and T-15 re-run with Identity's real adapter instead of the test double.

## Traceability

D-113, D-178 (ISO-3, ISO-7, L-10), D-193 (F-03, B-8), D-200, D-226 · PBDIGIT-345 task 6 (guide `developer_guide/platform/06_http_entry.md`) · PBDIGIT-341.
