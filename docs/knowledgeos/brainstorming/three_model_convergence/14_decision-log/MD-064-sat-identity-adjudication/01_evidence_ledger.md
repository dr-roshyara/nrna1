# MD-064 §01 — Q1: Identifier/Reference Identity — Exhaustive Ledger

Searched: M0043 (full re-scan for `Sat`), M0125 (full document, both occurrences of `Sat`), M0126
(diffed against M0125 in the relevant region — byte-identical), M0048 (already characterized, MD-061:
a same-day review of M0043, itself reuses `Sat(K,r)` with M0043's own signature), M0127.

| Test | Result |
|---|---|
| Does M0125 explicitly reference M0043? | **NO** — no citation, filename, timestamp, or "the document" phrase points to M0043 anywhere near either `Sat` occurrence (contrast M0048, which explicitly says *"the document already defines..."* — MD-061's own finding — M0125 has no equivalent phrase) |
| Is "`Sat`" defined earlier in M0125 itself? | **NO** — `grep` confirms exactly two occurrences in the whole file (§3.1 line 240; §8.5 line 904); neither is a definition, both presuppose the reader already knows what "`Sat`" means |
| Is it introduced as inherited terminology? | **Plausible, not confirmed** — used without local definition, consistent with inheriting a prior meaning, but equally consistent with informal reuse of the ordinary English word "satisfy" |
| Is it introduced as a new construct? | **NO** — no `[DEF]` tag, no boxed formula, no formal introduction anywhere in M0125 |
| Is it used with the same arguments as M0043's `Sat(K_t,r)`? | **NO** — M0125 never once writes `Sat(...)` as an applied function; both occurrences are informal (a table-cell label, and a prose phrase "outside `Sat`") |
| Is it used with the same codomain? | **NOT STATED** — M0125 never gives `Sat` a codomain |
| Is it used over the same objects (`K_t`, `r`)? | **NOT STATED** — neither `K_t` nor `r` appears adjacent to either `Sat` occurrence |
| Is it connected to `K_t`, `r`, `EC_t`, `Req`, `Δ_t`, or `Adequate`? | **NOT DIRECTLY** — `§3.1`'s own table (`02`) sits in a section discussing a theory-wide "projection" meta-principle that draws examples from *both* the `K_t`/`Δ_t` apparatus (Part 2) and the `Contr`/`Zero` apparatus (Part 1), so the table's own *setting* touches `K_t`-native material elsewhere in the same table (the "`K` insufficiency `\| E_t→K_t \|`" row), without ever placing `Sat` specifically next to a `K_t`/`r` argument pair |

**No identical spelling is treated as identity anywhere in this ledger** — every row above is stated
as a fact about what is or is not present in the text, not as an inference about meaning.
