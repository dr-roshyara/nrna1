# `KOS-CONTRACT-NEUTRALITY-001` — V-3: `D-1` / `D-4` / `D-5` **ADOPTED** — architecture vocabulary only

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-09-04
**Recorded by:** Governance — `claude-code-session:e8f324f1-25ea-4281-a95a-483401312e3e`
*(recording the PO/ARB's adoption; Governance does not decide)*

> ✅ **ADOPTED, exactly as proposed.** `D-1`, `D-4` and `D-5` now have a decided
> representation/vocabulary. ⛔ **This establishes architecture vocabulary/determination
> only.** It does **not** authorize implementation, contract modification, fixture
> modification, or runtime code change, and it does **not** close V-3. `D-2`/`D-3` remain
> exactly as previously decided — untouched here. **V-3 remains OPEN** until all required
> determinations and acceptance conditions are satisfied.

---

## 1 · The decision (verbatim)

> *"Adopt D-1, D-4 and D-5 exactly as proposed for V-3. This adoption establishes the
> architecture vocabulary/determination only. It does not authorize implementation, contract
> modification, fixture modification, runtime changes, or closure of V-3. D-2 and D-3 remain
> untouched and V-3 remains open until all required determinations and acceptance conditions
> are satisfied."*

## 2 · What is now ADOPTED (was: proposed in `2026-09-04-...-V3-D1-D4-D5-proposal.md`)

| Item | Adopted vocabulary/determination |
|---|---|
| **`D-1` representation** | A new, closed-vocabulary L3 fact kind, **`IndeterminateBehaviourReference`** — carries no `targetMethodName` field (nothing legal to put there), fixed determinability `NotDeterminable`, mandatory-total in whatever it does carry (`INV-L3-5` preserved, not exempted). The rejected alternatives (optional/absent field; stated limitation) stay rejected for the reasons given in the proposal (§1.1) — that reasoning is adopted along with the conclusion, not just the label. |
| **`D-1` L4 consequence** | `EdgeRules` gains a verdict rule for `IndeterminateBehaviourReference` that always returns `exclude(NotDeterminable)` — mechanical, metric-neutral, no new L4 decision. |
| **`D-4` enumerated list** | The determination's §12 wording, adopted whole: `call_user_func`, `call_user_func_array`, matched only in the literal `[$this, 'name']` form; the named out-of-scope idioms remain a declared limitation, not a silent gap. |
| **`D-5` vocabulary** | A new `QualifierKind` case, **`ExplicitCallableDispatch`**, used for the `D-4`-scoped dispatch case on the existing `BehaviourReference` type (no new fact kind needed here — the target name is a literal, per the proposal §3). |

## 3 · What this adoption explicitly does NOT do

⛔ **No implementation authorized** — `IndeterminateBehaviourReference` is not created as a
class; `EdgeRules` is not modified; `QualifierKind::ExplicitCallableDispatch` is not added;
`PhpFactExtractor` is unchanged. ⛔ **No contract modification** — `expected.json` and its
`_variant_decisions_pinned` text are unchanged; the adopted vocabulary is **not yet
incorporated into the authoritative specification**. ⛔ **No fixture modification** — the ten
golden fixtures are unchanged; no new fixture is authorized. ⛔ **No runtime/application code
change** of any kind. ⛔ **No closure of V-3** — the assignment `S4-architecture-v3-determination`
remains as previously recorded (`HANDED_OFF`, its provenance untouched); this act closes no
lane. ⛔ **`D-2`/`D-3` untouched** — dynamic property access remains **out of current scope /
not applicable**, exactly as decided in the 2026-08-19 decisions-registration; nothing here
restates, narrows, or reopens that.

## 4 · Consequence for the artifact-update gate — unchanged, stated so it isn't assumed

Because this act does **not** modify the contract, the standing gate condition from the
decisions-registration is **unaffected**: *"dynamic-member expected evidence remains blocked
until [incorporated] into the authoritative specification."* Adopting a vocabulary is not
the same act as incorporating it into `expected.json` — that incorporation is itself a
**contract modification**, which this act explicitly does not authorize. The gate stays
closed until a separate, future act performs that incorporation.

## 5 · What "V-3 remains open" still requires

Consistent with the PO/ARB's own words, this act settles **vocabulary**, not **closure**.
Still outstanding, unaffected by this adoption:
- **Incorporating** the adopted vocabulary into the authoritative specification (a contract
  modification — separately authorized).
- **Implementing** the adopted representation (the L3 class, the `EdgeRules` rule, the
  `QualifierKind` case, the binding recognition logic) — separately authorized.
- **Verifying** any such implementation independently, and **accepting** it — the standard
  RED→GREEN→VERIFY→ACCEPT sequence this repository requires for any implementation slice,
  not shortcut by this vocabulary decision.
- `D-2`/`D-3`'s standing disposition (dynamic property access, out of scope) continues to
  apply and is not reconsidered by any of the above.

Only once those are satisfied — and any other acceptance condition Governance/PO-ARB
identifies at that time — does V-3 close. **This act does not predict or schedule that; it
records only what has been decided today.**

**Traceability:** proposal `2026-09-04-...-V3-D1-D4-D5-proposal.md` · grant
`G-KOS-CONTRACT-V3-TARGETED-CONTINUATION` (`...-V3-targeted-continuation-AUTHORIZATION.md`)
· decisions-registration `2026-08-19-...-v3-decisions-registration.md` (+Amendment 1,
+Restoration R1) · V-3 architecture determination `2026-08-19-...-V3-architecture-determination.md`
· `INV-L3-5` · `G-KOS-CONTRACT-ARTIFACT-UPDATE` (+amendments, unchanged) ·
`G-KOS-CONTRACT-V3-ARCH` (+`AMD1`, unchanged) · `G-1`/`G-3`.
