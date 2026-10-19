# Proposed filter patch: DO NOT RUN

| Field | Value |
|---|---|
| Status | **PROPOSAL ONLY.** Nothing has been applied or executed. The scripts `discovery_filter.py` and `corpus_filter.py` are unchanged. |
| Author | Session B. As the author of both scripts, Session B **cannot** be their safety reviewer (D1). |
| Blocked by | **D2 (paused):** both scripts use the sealed lists inside the script, through `discovery_resolver()` and `load_seal()`. While D2 is paused, neither the current nor the patched scripts may be run. The count-only check also needs the sealed lists. **So the safety gate cannot pass until D2 is clarified by the rule owner.** |
| Disclosure | Session B's earlier runs (2026-10-17, under G-LOG-0141 item 2 and its extension) used the sealed lists inside the script, with nothing displayed. Whether that use was permitted is the very question D2 has paused. It is reported here so the rule owner can rule on it. |

## 1. Defects (from reading Session B's own code; no execution)

| # | Script | Defect | Failure direction |
|---|---|---|---|
| F-1 | corpus_filter | `label_hash_ok` is printed but not enforced | **fail-open** |
| F-2 | corpus_filter | `l in text`: case-sensitive exact substring | fail-open (missed variants) |
| F-3 | corpus_filter | the stem rule tests slug *equality* only | fail-open |
| F-4 | corpus_filter | `os.listdir`: the subdirectories of 20-FAMILIES are not scanned | fail-open (not listed) or silent omission |
| F-5 | both | PROD `REPO` vs W-worktree `FAMILIES`/`SCRIPTS`: mixed roots | inconsistent results |
| F-6 | corpus_filter | no source-ID quotation check | **fail-open** (the 1752 channel) |
| F-7 | corpus_filter | sealed exclusion by `source_id` only; no sha or path check | fail-open (copies) |
| F-8 | corpus_filter | relative output paths | misplaced outputs |
| **F-9 (new)** | discovery_filter | if `res.sealed` is empty (the seal state reads UNSEALED), every file is ALLOWED; the state is printed but does not stop the run | **fail-open** |
| **N-1 (`norm()`)** | discovery_filter | `lstrip("./")` strips **every** leading `.` and `/` character: `.claude/x` → `claude/x`, `../x` → `x` | false matches (over-withholding) **and** collisions of out-of-repo paths with in-repo paths |
| **F-10 (new)** | discovery_filter | edited-and-moved copies of sealed files (new sha **and** new path) pass | **fail-open**; cannot be closed by sha or path alone |

## 2. Proposed changes (pseudocode-level; for the reviewer to re-derive)

```python
# N-1: exact, root-anchored normalization
def norm(p):
    p = os.path.normpath(str(p or ""))
    return p[2:] if p.startswith("./") else p          # remove only a literal "./" prefix

def rel_in_repo(p):
    rel = os.path.relpath(os.path.realpath(p), os.path.realpath(REPO))
    if rel.startswith(".."):
        raise SystemExit("FAIL-CLOSED: path outside REPO")  # never compare out-of-repo paths
    return norm(rel)

# F-1, F-9: fail closed on any integrity condition
if not label_hash_ok: sys.exit(2)
if not res.sealed:    sys.exit(2)              # an UNSEALED state must stop, not allow everything

# F-2, F-3: normalized matching on both sides
canon = lambda s: re.sub(r"[^a-z0-9]+", "-", s.casefold()).strip("-")
withhold = any(canon(l) in canon(text) for l in labels) or any(canon(l) in canon(stem) for l in labels)

# F-4: os.walk(FAMILIES), recursive; report the number of subdirectories scanned
# F-5: a single declared ROOT for registry, families, corpus and scripts; print the ROOT and its git HEAD
# F-6: withhold a family if its text contains any sealed source_id, matched as a whole token
#      (\bS\d{4}\b-style; the exact pattern to be fixed by the reviewer)
# F-7: sealed exclusion by sid OR sha OR normalized path (as in discovery_filter)
# F-8: absolute output paths under corpus-access/
# F-10: mitigation only: near-duplicate check of each candidate against the sealed files' text
#       (e.g. MinHash shingles, inside the script, count-only output). Needs a D2 ruling, because it reads
#       sealed content inside the script.
```

**Over-withholding is the accepted failure direction.** Every change above moves a fail-open path to a fail-closed one.

## 3. Acceptance conditions for the safety review (proposed)

1. The reviewer re-derives the expected behaviour from the specification, not from this patch.
2. Synthetic test fixtures with **fake** seals and labels (never the real ones) cover:
   - each defect;
   - case variants;
   - nested directories;
   - `../` and `.hidden` paths;
   - an unsealed state;
   - a bad hash;
   - a source-ID quotation;
   - an edited-and-moved copy.
3. Both scripts fail closed on every integrity condition.
4. A written review record names the reviewer, the scope, the outcome and the open conditions.
5. Only then, **and only once D2 permits use of sealed lists inside a script**, does the count-only check run (1752 report §4.2: counts and yes/no answers only).
