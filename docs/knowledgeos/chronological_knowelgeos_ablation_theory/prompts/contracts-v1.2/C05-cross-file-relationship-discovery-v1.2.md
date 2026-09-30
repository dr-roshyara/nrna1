# C05 — Cross-file relationship discovery · v1.2 (delta)

**Base:** `contracts-v1.1/C05-cross-file-relationship-discovery.md`, sha256 `ad0f9c57d2958f673a105df4e6e2dd07b248d019e1f2ea641b9baa6f7acd9495`.

## Changes
1. **Candidate ids** are `FXC-<this>-<other>-<sha1(key)[:12]>` with the key carried separately (F-19; no truncation collisions).
2. **Every candidate carries `this_identical_to_s` and `other_identical_to_s`** (F-14, RN-08). Cross-file counts at checkpoints use the populations of C09 v1.2 §1.
3. **"Earlier" means earlier in processing order** (F-12). This is a hindsight guard for the analyst, not a statement about historical time (HDR-2).
4. **Other-side quotes** come from the other file's **Level-1** ledger only (inventory quotes, source definitions and contribution anchors). Research quotes never count.
5. **Similarity is a signal (RN-09).** Similarity, clustering and embedding results never decide RELATED, SAME or HOMONYM. They may open a candidate for inspection, and the disposition's `basis` must state what was inspected.
