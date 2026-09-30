# `IDENTIFIER-RENAME-01` — `H-Cn` → `RC-H-0n` *(2026-09-23)*

| | |
|---|---|
| **Authority** | ⭐ **L0-directed.** ⚠️ *Renaming governance identifiers is governance work; recorded as directed, not inferred — the distinction `B-15` turns on* |
| **Nature** | ⭐ **identifier disambiguation ONLY.** ⛔ *No meaning, scope, authority level or stop condition was altered* |
| **Trigger** | `B-17` — `H-C4` named two different things in one repository |

---

## The mapping

| Old | New | Meaning — ⭐ **unchanged** |
|---|---|---|
| `H-C1` | **`RC-H-01`** | Evidence binding: per-source admission control vs boundaries-only |
| `H-C2` | **`RC-H-02`** | Approve generation of the canonical manifest and its baseline commit |
| `H-C3` | **`RC-H-03`** | Adopt `RCI-014` fail-closed semantics; the `scope class` field |
| ⭐ `H-C4` | ⭐ **`RC-H-04`** | **Schedule the F0031–F0040 correction unit** |
| `H-C5` | **`RC-H-05`** | Accept/reject this document as `ARCH` Addendum B; the §2 authority hierarchy |
| `H-C6` | **`RC-H-06`** | Resolve `Q18` vs `Q61` (`E-7`) |
| `H-C7` | **`RC-H-07`** | Placement of `research_agent_contract.md` |

## Execution

| | |
|---|---|
| **Pattern** | `\bH-C([1-7])\b` → `RC-H-0\1` |
| **Files rewritten** | **11** · **62** occurrences |
| ⛔ **Excluded — worktree** | `.claude/worktrees/kos-v11-ddd/` **untouched**; its unrelated `H-C4` stands *(instruction 4, verified in `git status`)* |
| ⛔ **Excluded — append-only records** | `COMMIT-BOUNDARY-FINDING-01.md` (9) · `.claude/sessions/2026-09-23.md` (6) |

### ⭐ Why those two were excluded — this is a judgment, stated so it can be overruled

**They are factual records of the collision *as it existed*.** ⛔ **Renaming inside them would make them describe a state that never obtained** — a record saying *"`RC-H-04` is ambiguous"* would be **false**, because `RC-H-04` never was.

> ⭐ **The evidence keeps the old token; the live definitions carry the new one.** That is the same rule applied to the commit log and to the F0031 range: **preserve the witness, correct forward.**

⚠️ **15 occurrences of `H-Cn` therefore remain in scope, deliberately.** ⛔ *If instruction 3's "update all references consistently" was meant to include them, say so and I will — but I read instruction 8 as governing.*

## ⭐ Verification

| Check | Result |
|---|---|
| **`RC-H-04` definitions** | ⭐ **EXACTLY ONE** — `architecture/research-control-architecture.md:389` |
| **`RC-H-04` referring files** | 8, all consistent with that single definition |
| **Old `H-C[1-7]` in scope** | ⛔ **only the 2 excluded append-only records** |
| **Worktree** | ✅ **untouched** — its 4 `H-C4` references intact |
| **`RC-H-*` collisions elsewhere** | ⛔ **none** — the prefix was unused before this rename |

### ⚠️ A finding the rename check turned up

⛔ **The `H-C*` prefix space is crowded independently of this incident:** `H-CAT-1` occurs **302 times**, `H-CAT-2/3/4` also exist, and bare `H-C` appears ~600 times across **181 files**.

> ⭐ **`H-C` was never a safe prefix to mint into.** **`RC-H-` is unused**, which is why the new identifiers are unambiguous — ⚠️ *and it is a reason to check the prefix space before minting the next series.*

⛔ **Recorded as a fact.** *Whether a minting rule follows is a governance decision, not proposed here.*

---

*`IDENTIFIER-RENAME-01` · 7 identifiers · 11 files · 62 occurrences · 15 preserved in evidence · `RC-H-04` verified unique · worktree untouched · ⛔ no meaning changed, no history rewritten, nothing authorized by this record.*
