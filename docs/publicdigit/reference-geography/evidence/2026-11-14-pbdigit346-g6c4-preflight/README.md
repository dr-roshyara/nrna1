# Evidence: G-6c-4 preflight (reconciling the G-6c-3 review), 2026-11-14

| File | Shows |
|---|---|
| `c4-01-context-check-after.txt` | the project-state reminder is silent in the real repository: it now checks the ROUTED context file (`app/` changed → `CONTEXT-publicdigit.md`, which is current). Before the fix it checked only `CONTEXT.md` and warned falsely |
| `c4-02-context-check-stale-routed.txt` | **the safeguard still fires** when the routed file is stale (simulated in a scratch repository) |
| `c4-03-a10-injection-points/` | A10 with three explicit injection points: (1) an AFTER INSERT trigger on the roster entry (written, then fails); (2) the first notice write; (3) before commit, after the hook observed the finding and the roster entry inside the transaction. **Nothing survives any of them; redelivery then applies the merge.** 1 test, 9 assertions |

**The developer-guide reminder** (`86ffdf783`) was re-checked in the G-6c-3 preflight: it recognises shell-written and committed files and still fires for a real gap (`../2026-11-14-pbdigit346-g6c3-preflight/c3-02-guide-check-real-gap.txt`).
