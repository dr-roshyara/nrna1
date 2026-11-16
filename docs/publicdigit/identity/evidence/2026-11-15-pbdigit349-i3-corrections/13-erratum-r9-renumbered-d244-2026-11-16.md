# Erratum: the R-9 decision number (2026-11-16)

**What is affected:** `R-9-erratum-2026-11-15.md` says the decision "was recorded as **D-241**". That file stays **unchanged**; its SHA-256 in `SHA256SUMS` is unchanged.

**What happened:** while I-3 was parked, Session A recorded **D-241 … D-243** (Membership Q-6 … Q-8, commit `eaeecc16a`) in the canonical register. When the Principal instructed "lets first merge all work" (2026-11-16), `election-flow-updated` was merged into `identity/pbdigit-349-i3` and the number was re-verified, as planned. The canonical register ended at D-243, so the R-9 entry became **D-244** (section §11.142).

**Unchanged:**
- the decision: R-9 Option B. I-1 … I-3 are authorised for development and test only; **I-4 is not authorised**; no production rollout;
- its effective date: from the Principal's approval, **2026-11-15 19:08 CET** (option I);
- the D-200 gates, and the throttle values staying unratified.

**Not implied:** a merge is not I-3 acceptance.
