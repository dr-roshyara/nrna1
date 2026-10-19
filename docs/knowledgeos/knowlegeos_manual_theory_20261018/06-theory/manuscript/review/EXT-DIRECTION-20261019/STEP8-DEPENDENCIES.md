# Step 8 (dependent-chapters check) on §5.4 C7 and §3.10 C8

*2026-10-19, after the extraction review was settled. Rulebook v1.3 §2 step 8. Only the relevant dependencies were checked.*

## §5.4 C7

| Dependency | Status | Definitions kept? | Result contradicted? | Omission found → fix |
|---|---|---|---|---|
| CON-0080, gap operator (5.4.2) | PROVISIONAL → **unverified** | yes | no | C7 did not say CON-0080 is unverified → added |
| 5.4.2 status set (≥ 11 values) | source description | yes | no | — |
| C1: Unknown must be split (E2–E6; Ch. 2 §2.8.8) | Ded, reviewed in BRANCH-01, no VALIDATED verdict → **unverified** | yes | no | a binary Sat repeats the Unknown collapse that C1 rejects → added |
| C2: decision needs (status, criticality) | Ded, reviewed in BRANCH-01 (wording revised), no VALIDATED verdict → **unverified** | yes | no | inclusion's status loss does not affect *proceed*, only the remedy → stated |
| C3 / CON-0081, the status set mixes kinds | **INVALID** (the concept) | — | C7 compared G1–G10 with C3 as if it were a clean axis | reworded: the status set as the axis, with the caveat; "orthogonal" was too strong, since three gap kinds overlap three statuses |
| Rulebook §4 (Ext cannot win automatically) | rule | yes | no | — |

## §3.10 C8

| Dependency | Status | Definitions kept? | Result contradicted? | Omission found → fix |
|---|---|---|---|---|
| CON-0098, senses S1/S2/S3 | PROVISIONAL, not yet reviewed → **unverified** | **no**: C8 paraphrased Ω as "possible epistemic states", which reads as S3. EXT-029 L1253–1256 says "possible complete interpretations/models of the world" | — | corrected; Ω is world models, not S3 |
| §3.10 C1 (S1 objects; S2/S3 models) | PROVISIONAL | yes | no | — |
| Open question "are S2 and S3 one concept?" | open | — | no | — |

## Effect
- Five fixes made before any further review: three in C7, two in C8.
- No dependency was contradicted.
- The strength scan shows no new strong words in C7 or C8.
- The two claims stay PROVISIONAL. **Not re-reviewed:** the reviewer had both sections in its scope, and step 8 changed wording and scope only. If the Principal wants the fixes reviewed, give the reviewer this list.
