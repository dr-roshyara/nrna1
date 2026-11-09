# Identity ARB act XD-3: canonical Person, per-organisation PersonRef, organisation-local personal data

| | |
|---|---|
| **Status** | ✅ **ACCEPTED — explicit ruling by the Principal as human ARB Chief, 2026-11-09** ("Yes I accept XD-3"). Recorded as D-75. XD-3a, XD-3b and XD-3c accepted by append-only notes |
| **Formalises** | `2026-11-01-identity-xd3a-person-identity-scope-decision-record.md` (option A; C-1…C-5) · `2026-11-01-identity-xd3b-person-vs-account-decision-record.md` (B2; §2a option B) · `2026-11-01-identity-xd3c-account-lifecycle-decision-record.md` (option C; R-XD3c-1…5) |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |

## 1. Already answered by the Principal (2026-11-01)

- A person may belong to several organisations (OQ-3a-4; BR-4).
- People without an account must be supported (XD-3b business part).
- Personal data lives with each organisation (XD-3b §2a, option B).
- An account stays active after the last organisation (XD-3c, option C).

## 2. What this act decides

1. **One canonical Person** exists, owned by Identity, holding only the minimum needed for identity and authentication (a bare `PersonId`).
2. **Each organisation receives a different opaque `PersonRef`** for the same human. *(New: adopts the Principal's preferred direction of 2026-11-01 as the meaning of `PersonRef`; compatible with the ratified Membership model, "opaque `PersonRef`, unique per organisation".)*
3. **Only Identity can resolve** one organisation's `PersonRef` to the canonical Person or to another organisation's reference. **No ordinary business context may query that relationship**, and no context may use its reference to discover another organisation's identity, affiliation or activity.
4. **Identity exposes no** memberships, offices, decisions, names or contact details across organisations (C-1, C-3).
5. **Names and contact details are organisation-local.** Their exact owning context within the organisation is **not** decided here; it follows the separate INV-AFF-1 decision.
6. **Person and Account are separate**; zero or one Account per Person. **No automatic account creation, takeover or linking based on matching email addresses or names.** Linking requires an explicit, verified mechanism (the organisation-issued claim code of XD-3b §2a).
7. **Ending a membership, removing access, suspending an account and deleting an account are separate acts**; none triggers another (R-XD3c-5). Leaving an organisation does not delete or suspend an account.
8. **Conditions C-1 to C-5 are binding** on every mechanism chosen later; C-4 (minimised central index) and C-5 (merges only by Identity's explicit act) in particular.

## 3. Explicitly not decided

- **Mechanism:** L1 (stored central mapping) or L2 (reference derived with a secret key, storing no organisation). L2 feasibility, key management, reversibility and operational risk are **not established**; evaluate against privacy, security, recoverability, uniqueness, merge handling and maintainability by one developer.
- **K4:** mapping of imported people who have not registered.
- Legal basis, controllers and processors (OQ-3a-1); legally separate identity domains (OQ-3a-2); retention and erasure (OQ-3b-2); the account-security policy (OQ-3c-1, OQ-3c-2).
- **INV-AFF-1** (affiliation discovery and ownership): a separate ARB act.

## 4. Security blocker carried forward

The live Google sign-in links or takes over any account with the same email, including import placeholders (`app/Http/Controllers/GoogleController.php:44`, recorded in XD-3c §5). Under §2 item 6 this is a **security blocker**, not an ordinary defect. A matching email never establishes identity or authorises linking.

## 5. Threats the tactical design must address

Cross-organisation correlation and inference; unauthorised resolution of references; disclosure through logs, events, APIs or error messages; mistaken merges; duplicates from independent imports; account takeover; boundaries between deletion, retention and suspension.

## 6. Ruling

**Accepted, 2026-11-09 (D-75).** Carried into tactical design: account linking and recognising two organisations' references as one Person are separate operations (a claim code from one organisation never reveals another's affiliation); the bar on resolving references is enforced technically across APIs, events, logs and error messages.
