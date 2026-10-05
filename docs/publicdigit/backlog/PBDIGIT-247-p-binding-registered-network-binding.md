# PBDIGIT-247 — P-BINDING: bind a voter to a registered network (Security Policy)

**Type:** Feature — Security Policy (P-BINDING) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Origin:** PO decision IP-D1 = (b) bind (2026-10-05), from the Voter Management column request; it **supersedes Q3-D6** (P-BINDING was "candidate, inert, implementation deferred")
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Existing-story search (freeze rule):** `PBDIGIT-234` (`users.voting_ip` read but no column) and `PBDIGIT-222` (IP verification has no backend of its own; IP ≠ identity) are inputs to this story. Neither owns the binding policy end to end. This story owns it; 234 and 222 stay as the storage and UI-boundary findings it must resolve.

| | |
|---|---|
| **Status** | 🟡 `OPEN` — desired by the PO; **not authorised for implementation. Stop at RED / design.** |
| **Context** | **Security Policy, not Admission.** Evaluated only by the Security Verdict Authority at `forEntry()` / `forCast()` (Q3-D2). Never part of identity or email evidence (ID-D2) |
| **Today [S]** | Three inert copies: the `validate.voting.ip` middleware, `validateVotingIpWithResponse()` in `store()`, and the authority's `registeredIpHash` evidence. All read `users.voting_ip`, which does not exist (PBDIGIT-234). Legacy columns `voter_verifications.verified_ip` / device stay unused for enforcement |
| **Design questions (all open)** | **1** storage model: where the registered network lives (election-scoped, not `users`) · **2** registration / capture lifecycle: who captures it and when; the officer, or the voter's first verified access · **3** election scoping: one election's binding never applies to another · **4** lifecycle lock: the binding policy is part of the I9 security-policy surface; the captured value's mutability after ReadyForVoting · **5** Security Verdict Authority evaluation: evidence assembly, policy outcome, failure semantics · **6** `forEntry()` · **7** `forCast()` · **8** revocation / change of a registered network · **9** IPv4 / IPv6 policy (2B-1 is IPv4-only) · **10** tests, plus retirement of the three inert copies (with 2B-6) |
| **UI** | The Voter Management **IP Address** column, shown only while P-BINDING is active (COL-D1) |
| **Out of scope** | identity and email verification · `users.voting_ip` creation before the storage decision · P-DENSITY / whitelist · device binding (P-DEVICE) |

**Traceability:** Q3 proposal §0 (D6 superseded), §4 P-BINDING · identity proposal `docs/publicdigit/reviews/2026-10-05-identity-verification-lifecycle-and-email-proposal.md` §G · PBDIGIT-234 · PBDIGIT-222.
