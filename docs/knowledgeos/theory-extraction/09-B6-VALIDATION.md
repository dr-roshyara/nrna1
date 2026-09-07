# `B-6` Validation — and proof no conceptual proposal was applied

**2026-09-07.** Deliverables 1, 2, 4, 5.

---

## 1. `[APPLIED — MECHANICAL]` — `B-6`, and only `B-6`

**Rule added:** `04-SCHEMA-v2-AND-AMENDMENT.md` **§3b**.

$$\boxed{\mathtt{n/a} \neq \mathtt{never}}$$

**10 edits across 8 files:**

| # | record(s) | field | was | now | why |
|---|---|---|---|---|---|
| 1–8 | **all eight** | `latest governed decision` | `EMPTY` | **`never`** | applicable *(a concept can be decided)* · `governance/` searched · none found |
| 9 | `KOS-T-0011` `ℐ` | `latest implementation` | `n/a` | **`never`** | applicable *(a register can be implemented)* · searched in scope · none found |
| 10 | `KOS-T-0009` `Zero` | `selection` for `D-02`/`D-06` | `—` | **`n/a`** | **structurally inapplicable** — no body ⇒ no definition being selected |

### ⭐ And what B-6 deliberately did **not** change

| preserved | count | why |
|---|---:|---|
| `provenance = n/a` where `Implementation = none` | `K`, `Σ`, `Π`, `Zero`, `ℐ` | **correct** — with no body there is no warrant to describe |
| `selection = n/a` where `Implementation = none` | same | **correct** — no definition is being selected |
| `Implementation = none` as a **level** | 20 occurrences | a **level**, not an empty-field marker; **§3b clarifies it means *searched and not found*, not *not looked*** |

$$\boxed{\textbf{B-6 is as much about NOT converting a correct } \mathtt{n/a} \textbf{ as about fixing a wrong one.}}$$

## 2. Verification

```
grep -c 'EMPTY' elements/*.md        →  0 in all 8 records
grep -c 'n/a'   elements/*.md        →  preserved on every provenance/selection line
```

**Scope carried on every `never`:** each reads *"applicable · searched `<scope>` · no instance found."*
⚠️ **A `never` without a declared scope is inadmissible** (§3b) — it would assert absence from the
corpus on a search of part of it.

## 3. `[PROP — CONCEPTUAL]` and `[OPEN — CONCEPTUAL]` — recorded, **not applied**

Full register: [`08-CONCEPTUAL-PROPOSAL-REGISTER.md`](./08-CONCEPTUAL-PROPOSAL-REGISTER.md).

| | count | IDs |
|---|---:|---|
| **`[PROP — CONCEPTUAL]`** | **13** | `P-01` `P-02` `P-03` `P-04` `P-05` `P-07` `P-08` `P-09` `P-10` `P-11` `P-12` `P-13` `P-16` |
| **`[OPEN — CONCEPTUAL]`** | **4** | `P-06` `P-14` `P-15` `P-17` |
| crossing the 3MC boundary | 2 | `P-11` *(not sent)* · `P-12` *(escalated in `06`)* |

**All requested proposals are present:** per-definition kind (`P-01`) · per-definition status
(`P-02`) · `value-set` (`P-07`) · `register/interface` (`P-08`) · `structure` (`P-09`) ·
`co-obligation` (`P-10`) · `supersession` (`P-11`) · disposition 5 / malformed (`P-03`) ·
`selection: superseded` (`P-04`) — **plus every other conceptual item from the stress report:**
`modelled-as-placeholder` (`P-05`), record-vs-reading identity (`P-06`), `not-searched` (`P-13`),
comparison arms (`P-14`), `Latest` ownership (`P-15`), scope-exclusion formalization (`P-16`),
titled-but-unplaced decisions (`P-17`).

## 4. ⭐ Proof that no conceptual proposal was applied

**Each proposal, with the state that would exist had it been applied, and the actual state:**

| ID | if applied, the records would show | actual state | ✓ |
|---|---|---|:--:|
| `P-01` kind@definition | `kind:` on each `D-nn` | `kind:` **only at record level**; `Π`/`Zero` still marked **"unassignable / per definition"** in prose | ✅ **not applied** |
| `P-02` status@definition | per-`D-nn` status | `Zero`'s two `[RF]` readings **still described in prose**, record status `[CT]` | ✅ |
| `P-03` `malformed` | `Π` R7 disposition = 5 | **"🔴 no disposition fits"** | ✅ |
| `P-04` `selection: superseded` | `Σ` `D-05` `selection: superseded` | **"🔴 no value fits"** | ✅ |
| `P-05` `modelled-as-placeholder` | `ℐ` level = that | `ℐ` level = **`none`** | ✅ |
| `P-06` unit of extraction | a glyph/occurrence layer | **one flat record per concept** | ✅ |
| `P-07`–`P-09` new kinds | `kind: value-set` / `register` / `structure` **asserted** | all three marked **`[PROP]`** inline | ✅ |
| `P-10` `co-obligation` | `ℐ` `D-01`~`D-03` = `co-obligation` | **`unresolved_equivalence`** | ✅ |
| `P-11` `supersession` | `Σ`~`Σ₀` = `supersession` | **`unresolved_equivalence`** | ✅ |
| `P-12` `misattribution` | `≡_sem` `D-01`~`D-03` = `misattribution` | **`unresolved_equivalence` + protest** | ✅ |
| `P-13` `not-searched` | a third empty value in §3b | §3b defines **exactly two** | ✅ |
| `P-14` comparison arms | a distinguishing field | `Zero`'s four arms **described in prose only** | ✅ |
| `P-15` `Latest` ownership | per-reading `Latest` | `Π` keeps **four record-level fields + a warning** | ✅ |
| `P-16` scope exclusion in text | §"corpus scope" extended to implementation | **applied in practice, absent from the rule text** — recorded as the gap | ✅ **not written** |
| `P-17` titled-but-unplaced | a new field | `Σ` keeps a **prose note**; field reads `never` | ✅ |

$$\boxed{\textbf{17 proposals · 0 applied · } B\text{-}6 \textbf{ alone applied, and it is mechanical.}}$$

## 5. Hard constraints — verified

| constraint | state |
|---|:--:|
| 3MC · `dimension-registry.md` · MD-017 · MD-018 unmodified | ✅ **no write** |
| brainstorming corpus unmodified | ✅ |
| nothing adjudicated · nothing canonicalized | ✅ — **23 definitions still alive across 8 records** |
| no carrier · no kernel · `OQ-1` untouched | ✅ |
| no new concepts extracted · no stress pass rerun | ✅ |
| all existing evidence and history preserved | ✅ — **B-6 changed only field VALUES; no finding, protest or measurement was altered or removed** |

⚠️ **One honest note.** `KOS-T-0011`'s §Latest previously *argued for* B-6 (*"`n/a` and `never` are
different claims"*). That sentence now reads **"B-6 APPLIED here"** and states the same distinction as
the rule. **The argument is preserved; only its tense changed** — from a finding to a citation.

**Stopping here. Schema v3 design not begun.**
