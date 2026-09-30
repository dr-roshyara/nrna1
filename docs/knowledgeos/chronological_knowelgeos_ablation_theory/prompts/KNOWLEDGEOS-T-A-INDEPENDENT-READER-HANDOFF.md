# T-A — Independent-reader handoff (prepared; not commissioned)

| | |
|---|---|
| **Kind** | handoff for the human L0. ⚠ authority: generated. It contains **no** SELF result and must **not** be given to the reader |
| **Log** | F-LOG-0042 (bundle built and verified) |

## 1. Bundle
- Path (temporary session scratchpad — copy it somewhere durable): `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/c3901d37-f846-4ebe-b010-0da3929cbcb5/scratchpad/TA-READER-BUNDLE-01.tar`
- sha256: `bbdb8e7e6a7bb3c048913c301ebf2e825dcb1151660ff02f00abf132ba0f5ae1`
- Contents: `packet/` (PREREGISTRATION-r3-BLIND.md, README.md, aggregate_blind.py, PACKET.sha256) · `objects/` (the eight released objects) · `BUNDLE.sha256`
- If lost: it is rebuilt byte-identically from git (the frozen packet plus `git show` of the eight objects)

## 2. The eight released objects (sha256)

```text
b685f5991338f24df135a50bd7d004999d62401bdac10f9c0ef8241ecbbed7bc  objects/M-1_F0018_d61bf5e84.md
7205c52d8b13090abbb51b788d08516bd4b1e2c514c2b5ab8cdde79f87327530  objects/M-4_ES-006_1_d63202b8c.md
d23d8b53865c9c5bdde412274c3f09b3e34d1bf8ecf69beb7e2396f8b795af9c  objects/M-4_ES-006_2_aee484e9c.md
25fcd0048e45cc43b396e895f7646d403041172d9d9f62315e19bd2e5f5fffb0  objects/M-4_ES-006_3_da565a213.md
facb576d3637df66c2e464a5e54affe7666e39e15e38e99132e1e0a6d0b97bda  objects/M-4_ES-006_4_c71f7d689.md
170f344d6eb0efe52faa729fc28fcc06a19cd73ced0a63f3a8cbe6007c4065b2  objects/M-4_ES-006_5_8d1df4b1d.md
12287296507530a600724018d1d8177053f967fbfe61d596ed8130fb240b6e6c  objects/M-4_ES-006_6_43682264d.md
349b7d5d5e2df4ffea052c06c35e2c2d5503c22ffd79654946ad671d2f0c42a0  objects/M-4_ES-006_7_668cc7b22.md
```

## 3. Reader instructions
Give the reader `packet/README.md` as it is (sha256 `475c42b86bf3c66f87c6e03221fbaddb5b7e4e378c9ba48a25af42ee0b9bee37`).
- Verify each object's hash, then read it completely.
- Fill `operation` first (source-declared type, else the described action; actor and object are context only; never the effect; UNKNOWN if the kind cannot be determined without the effect).
- Then fill `effect`, then `classification` (the §3.1 definitions only).
- Split only where the source does. Record ambiguity. Ignore `expected_finding_matched`. Contact no other reader.

## 4. Independence declaration (the reader signs it)
> "I, [name / model + model id], declare that before reading this bundle I had no access to the KnowledgeOS project, its reports, its predictions, H-F2-1-R analyses, or any T-A result or ledger. I used only the files in TA-READER-BUNDLE-01 (sha256 bbdb8e7e…5ae1). I produced records.jsonl without consulting any other reader."

## 5. The human records
reader identity · model family or human identity · the signed declaration · date/time of commissioning · the bundle sha256 as verified by the reader.

## 6. On return
1. `sha256sum records.jsonl` → send **only the hash** to Claude first.
2. Then send the file.
3. Claude stores it in `analysis/t_a/ledgers/INDEPENDENT/`, verifies it against the hash, and seals it with its provenance before any content is inspected. Then Claude validates it, builds the disagreement table (AGREEMENT / DISAGREEMENT / INDEPENDENT-ONLY / SELF-ONLY / UNKNOWN; never averaged), aggregates each ledger separately, and reports one r3 outcome.

**Ineligible readers:** this Claude session; any subagent of it; the senior reviewer (who has seen the predictions and results).
