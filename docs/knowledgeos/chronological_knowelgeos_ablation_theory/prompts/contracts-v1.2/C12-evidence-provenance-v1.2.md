# C12 — Evidence and provenance · v1.2 (delta)

**Base:** `contracts-v1.1/C12-evidence-provenance.md`, sha256 `f8d10b51ad8a998e02027f22ccac78079622273f046f2bb51d296ae60c1ffdb4`.

## Changes
1. **Chain.**
```
F-ID → manifest row (path, sha256)
     → page (READ-LOG, page sha256)
     → PAGE-DIGESTS            [frozen at READ-COMPLETE]
     → unit (UNITS, text sha256)
     → inventory item          [frozen at CONTENT-EXTRACTED]
     → independent L1 audit (when due)
     → contribution            [frozen at RECONSTRUCTED]
     → analysis record         [frozen at ANALYZED]
     → research record         [frozen at RESEARCHED]
     → checkpoint snapshot     [POPULATIONS / HYPOTHESES sha256 in STATE.jsonl]
```
   Every link is resolvable mechanically, and every freeze sits in a hash-chained event.
2. **No orphans at Level 3.** A research record without a Level-1 or Level-2 reference is refused (C10 v1.2 §2).
3. **Research quotes are never evidence for anything**, including their own record. Only Level-1 quotes count.
4. **Processing order vs historical time.** "No look-ahead" is a processing-order property, enforced by the reader and the evidence gates. Historical time is recorded as evidence (C09 v1.2 §1) and is never inferred (HDR-2).
