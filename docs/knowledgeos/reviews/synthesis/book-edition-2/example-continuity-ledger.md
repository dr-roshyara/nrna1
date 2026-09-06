# Running-Example Continuity Ledger (BA-ED2-12 control 4 · machine-checkable · created 2026-08-29)

**Element × stage × status × evidence-count for the election-certification example. Updated per
chapter; checked at each chapter gate. "—" = the chapter carries no example stage (permitted; the
ledger row still records it).**

| Chapter | Stage | Elements advanced | Statuses at stage end | Evidence counts |
|---|---|---|---|---|
| III.2 | 1 | G, IdealState, EC (r₁–r₅ derived) | frame recorded | — |
| III.5 | 2 | evidence for r₂/r₃ | r₂: 1 independent item (41→1 collapse); r₃: 2 supporting + 1 preserved counter (e⁻) | r₂:1 · r₃:2+1⁻ |
| III.4 | 3 | full Zero vector | r₁ Satisfied · r₂ Insufficient · r₃ Conflicted · r₄ Missing · r₅ Stale | as above |
| III.3 | 4 | K_t snapshot (one instance per primitive) | consistent with stage 3 | as above |
| III.6 | 5 | ladder journeys | R: Candidate→Supported→(stall r₃)→Accepted · R′: Contested→Rejected (separate wire chain) · boundary: Committed(R, Publish) pending | — |
| III.7 | 6 | DC(publish) filled; Auth act; action | all Pre/Inv/Auth/Post/Temporal/Evidence True (r₄ closed; r₅ RE-ATTESTED — closes stage-3 Stale); Committed(R, PublishCertification); K_{t+1} | r₅ re-attested |
| III.8 | 7 | policy vN→vN+1 (certification policy) | content→Accepted-as-proposition→DC-authorized in-force vN+1; in-flight under vN via Temporal clause | — |
| III.9 | (pass) | custody conflict under three kernel lenses | no state change | — |
| III.10 | (close) | full-chain annotation by invariant | consistent with all prior cells | — |
| II.1 | — | no example stage (method chapter) | — | — |
| II.2 | — | no example stage (method chapter) | — | — |
| II.3 | — | no example stage (method chapter) | — | — |
| II.4 | — | no example stage (historical chapter) | — | — |
