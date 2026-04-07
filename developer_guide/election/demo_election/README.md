# Demo Election — Developer Guide

**Last updated:** 2026-04-07  
**Branch:** `multitenancy`

---

## Contents

| File | What it covers |
|------|---------------|
| [README.md](README.md) | This index — start here |
| [01-overview.md](01-overview.md) | Architecture, two modes, data isolation |
| [02-five-step-workflow.md](02-five-step-workflow.md) | Full voting flow (auth-based) |
| [03-public-demo.md](03-public-demo.md) | Anonymous "Try Demo" feature |
| [04-models-and-tables.md](04-models-and-tables.md) | Database schema for all demo tables |
| [05-services.md](05-services.md) | DemoElectionResolver, DemoElectionCreationService, VoterSlugService |
| [06-routes.md](06-routes.md) | Route map — auth vs public routes |
| [07-testing.md](07-testing.md) | Test suite overview and how to run |
| [DemoCodeController.md](DemoCodeController.md) | Code verification steps 1 & 2 |
| [AGREEMENT_SUBMISSION_FIX.md](AGREEMENT_SUBMISSION_FIX.md) | Fix history |
| [FIXES_SUMMARY.md](FIXES_SUMMARY.md) | Bug fix log |

---

## Quick Start

### Run a demo election as logged-in user

1. Register/login
2. Click **"Demo Versuchen"** on the dashboard (`/dashboard`)
3. Walk through 5 steps: Code → Agreement → Vote → Verify → Thank You

### Run a demo election as anonymous visitor

1. Go to the home page (`/`) — no login
2. Click **"Try Demo"** in the CTA section
3. You are redirected to `/public-demo/start`
4. Walk through the same 5 steps

---

## Key Concepts

**Two modes** — every demo election is either:
- `organisation_id = NULL` — platform-wide, visible to all
- `organisation_id = X` — scoped to a specific organisation

**Vote anonymity** — `demo_votes` table has no `user_id` column. Votes cannot be linked back to voters.

**Re-voting** — demo elections allow unlimited re-voting. Each new attempt gets a fresh voter slug.

**Public demo isolation** — anonymous visitors use `PublicDemoSession` (keyed by Laravel session ID). No user_id is ever required.
