# Results-blind mechanical comparison: canonical schema and rules (frozen BEFORE any independent result exists)

| | |
|---|---|
| Scope | Gate 2 (`SPEC-G2-r2.md`, rule r2-7) and Gate 2.5 (`SPEC-G25-r1.md`, pre-registration §7 = r2-7) |
| Status | written 2026-09-26, before any non-Claude result exists. The comparator holds **no expected values, no model preference and no interpretation** |
| Class | tooling (SELF). It does not decide reproduction; it produces the mechanical differences that the pre-registered r2-7 outcome decision consumes |

## 1. Pipeline

```
reference results.json  ──canon_<gate>_ref.py──►  ref.canon.json ┐
                                                                 ├─ compare_mech.py ─► DIFF.json
independent results.json ──adapter (see §4)───►  ind.canon.json ┘
```

## 2. Canonical form

A JSON object `{"gate": "G2"|"G25", "entries": {KEY: {"c": CRIT, "v": VALUE, "parent": KEY|null}}}`.

- **KEY** = `|`-joined path. The grammar per gate is in §5.
- **CRIT** ∈ {C-1, C-2, C-3, C-4, C-5, C-6}, per r2-7.
- **VALUE types:** int (counts and lengths), class string (§3), bool (existential answers and E0/EB), string (a lattice element or a declared token), or a sorted list (a bar u; a set of sets = a sorted list of sorted lists).
- **parent:** a dependent entry (a witness length, a scenario t_a/t_p field) is compared only if its parent entry is EXACT; otherwise it is `NOT-COMPARED(parent)`.

## 3. Frozen vocabulary normalization (the only value transformation allowed)

- Class strings: uppercase, with `-` and space replaced by `_`. Allowed after normalization: `HOLDS`, `FAILS`, `VACUOUS`, `NOT_APPLICABLE`, `NOT_REPRESENTABLE`.
- Existential answers → bool: `REACHABLE`/`true` → true; `NOT_REACHABLE`/`false` → false. A witness list → true; null → false (Gate 2 reference encoding only).
- Anything else stays as it is. It is **never coerced**, so an out-of-vocabulary value surfaces as STRUCTURAL-MISMATCH.

## 4. Independent adapter constraints (fixed now)

The adapter for the independent result is written only after the independent `results.json` is sealed, because its layout is unknown now. To keep it non-adaptive:

1. It may only **relocate** fields into canonical keys and apply §3. It must not compute, infer, default or repair any value.
2. Where a canonical key has no source field, it is **omitted**, which yields MISSING. Unmappable source fields are listed in the adapter output as `unmapped`, which yields EXTRA.
3. It is written reading the independent file's **structure only**. It is committed and hashed **before** `compare_mech.py` runs on it.
4. `compare_mech.py` and the reference canonicalizers are not edited after this freeze. A defect found later is recorded, and a new versioned comparator is frozen before any rerun; the frozen one is never patched in place.

## 5. Key grammar

- **G2:**
  - `C-1|M|I|{states,reachable,reachable_N}` and `C-1|M|I|applicability` (`APPLICABLE`/`NOT_APPLICABLE`);
  - `C-2|M|I|P` → class; `C-6|M|I|P|len` → int (parent C-2);
  - `C-3|M|chain3|S` → bool or token;
  - `C-4|M|I|-AX|P` → class;
  - `C-5|M|I|minimal|P` → set of sets; `C-5|M|I|redundant` → sorted list.
- **G25:**
  - `C-1|M|I|{states,reachable,reachable_ad1}`;
  - `C-2|M|I|P` → class or bool (existential items); `C-6|M|I|P|len` → int or null (parent C-2);
  - `C-3|M|chain3|REP` → bool; `C-3|M|chain3|Tn` → bool; `C-3|M|chain3|Tn|len` → int; `C-3|M|chain3|Tn|{t_a,t_p}|{e,u,E0,EB}` (parent `C-3|M|chain3|Tn`);
  - `C-4|M|I|-AX|P`; `C-5|M|I|minimal|P`; `C-5|M|I|redundant`.

## 6. Mechanical categories (compare_mech.py)

| Category | Condition |
|---|---|
| EXACT | key on both sides, values equal after canonical form |
| MISSING | key in reference only |
| EXTRA | key in independent only (including `unmapped`) |
| STRUCTURAL-MISMATCH | both present, the value **types** differ (e.g. int vs null, class vs bool, list vs scalar), or a value is outside §3 |
| NUMERIC-MISMATCH | both int, unequal (counts, lengths) |
| SEMANTIC-MISMATCH | both of the same non-int type, unequal (a class, a bool, a lattice element, a set) |
| NOT-COMPARED(parent) | the parent entry is not EXACT |

**Annotation (not a category):** for a class SEMANTIC-MISMATCH with both values in TRUE = {HOLDS, VACUOUS}, the flag `both_true` is set. That distinction is pre-registered in r2-7 / F-LOG-0064. The **outcome** (REPRODUCED / UNDER A DIFFERENT READING / NOT REPRODUCED) is decided afterwards under r2-7 together with C-7 (the declared readings). The comparator does not decide it.

## 7. Order of use (after the independent seal)

1. Verify the bundle and result hashes.
2. Commit the adapter with its hash.
3. Run the comparator.
4. Classify the outcome under r2-7.
5. Only then inspect truth values, keeping mathematical reproduction, model-property result, interpretation and corpus implication separate.
