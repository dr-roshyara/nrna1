# `KOS-CONTRACT-NEUTRALITY-001` — V-3 incorporation decision brief

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-09-27
**Assignment:** lane `S5-architecture-pass1-evidence-reconciliation` (re-verified `RESOLVED ·
attribution MATCH · workflow_state ACTIVE · authorized_to_act TRUE`, 45 transitions,
unchanged since 2026-09-05)
**Performer, self-declared, not attestable (`INV-ATTR-2`/`G-2`):**
`claude-code-session:e8f324f1-25ea-4281-a95a-483401312e3e`

> ⛔ **This is a decision brief with a recommendation, not a decision.** It does not modify
> `expected.json`. It packages the one open question `2026-09-04-...-V3-D1-D4-D5-ADOPTED.md`
> §5 already named — incorporating adopted vocabulary into the authoritative specification —
> for PO/ARB to authorize, amend, or reject.

---

## 1 · What "incorporation" means here, concretely

`expected.json._variant_decisions_pinned.intra_class_calls` currently reads (verbatim,
unchanged since 2026-08-16):

> *"...EXCLUDED AS NOT DETERMINABLE: calls whose target cannot be resolved from the class in
> isolation — dynamic method names (`$this->$name()`), callable arrays
> (`call_user_func([$this,'m'])`), and any runtime-computed target; a real dependency may
> exist, but the analysis cannot determine the target reliably..."*

This states the **semantic** decision (such calls are excluded, and why) but says nothing
about **how the fact model must represent** an excluded-as-undeterminable site, nor does it
name the two enumerated dispatch functions or a representation vocabulary. That is exactly
the gap `D-1`/`D-4`/`D-5` closed at the architecture level (`...-V3-D1-D4-D5-ADOPTED.md`) —
and exactly what is **not yet** in this text.

**Incorporation, concretely, is a single contract amendment** adding to the pinned text (or
an adjacent pinned key):
1. `D-1`: computed-method-name sites must be recorded via a distinct, closed-vocabulary fact
   kind (`IndeterminateBehaviourReference`) carrying no target-name field and a fixed
   `NotDeterminable` determinability — never a sentinel, never source text.
2. `D-4`: the enumerated dispatch scope — `call_user_func`, `call_user_func_array`, matched
   only in literal `[$this, 'name']` form — as the sole in-scope library-dispatch case,
   with the named out-of-scope idioms stated as a declared limitation.
3. `D-5`: such an in-scope dispatch is recorded on the existing `BehaviourReference` type
   with the new `QualifierKind::ExplicitCallableDispatch` case.

## 2 · What incorporation would and would not unblock

Per the decisions-registration's own gate language: *"dynamic-member expected evidence
remains blocked until [incorporated] into the authoritative specification."* **Incorporation
is the specific act that lifts that block on evidence *production*.** It does **not** lift
the separate block on conformance *assertion* — that still requires an implementation,
independently verified and accepted, which incorporation alone does not provide.

## 3 · Options, with consequences

| Option | What it does | Consequence |
|---|---|---|
| **1 — Incorporate now, implement later (recommended)** | Authorize the contract-text amendment for all three items in one act; implementation, verification and acceptance remain separately authorized, exactly as this repo already sequenced the original `D-1` core rule and Decision 13.3 (contract text pinned first, code corrected and verified after) | Unblocks dynamic-member evidence *production*; leaves conformance-assertion blocked until implementation is separately authorized and verified. If implementation later surfaces a problem with the representation, a further contract amendment is cheap and already normal practice here (multiple `AMD` amendments exist on this commission alone). |
| **2 — Prototype first, incorporate after** | Authorize a bounded, throwaway implementation spike (no contract change) to empirically confirm the representation integrates through L3→L4 before committing contract text | Lower risk of pinning an unworkable representation — but the determination already established, by code-level analysis (not conjecture), that `L4` needs only a trivial always-exclude rule and `L5` is metric-neutral (§2.5–2.6 of the determination); the genuinely novel part (a third L3 fact kind alongside `BehaviourReference`/`StateAccess`) is structurally the same *kind* of change already made once. The extra step buys little given what's already known, at the cost of another authorization cycle. |
| **3 — Incorporate `D-4`/`D-5` only, defer `D-1`** | Split the amendment; library-dispatch scope/vocabulary go in now, the new fact kind for computed method names waits | Narrower, lower-risk text change — but leaves the harder half of `V-3` (computed method names, the more consequential of the two constructs per the determination's own severity ranking) exactly where it is, with no evidence this narrower step is actually needed rather than merely cautious. |
| **4 — Hold, incorporate nothing yet** | No text change | No forward progress; `V-3` stays open with vocabulary decided but unincorporated indefinitely. |

**Recommendation: Option 1.** The determination's own analysis already did the empirical
groundwork a prototype would re-derive (measured probe through the actual pipeline, §1.2 of
the determination); this repo's established sequence for every prior decision on this
commission is contract-text-first, implementation-second; and splitting `D-1` from `D-4`/`D-5`
(Option 3) has no evidentiary basis for the extra caution it buys.

## 4 · What this brief does not do

⛔ No `expected.json` change · no implementation · no fixture change · no independent
verification commissioned · no acceptance · no closure of `V-3` · `D-2`/`D-3` untouched.
**Next actor: PO/ARB** — authorize, amend, or reject; if authorized, a follow-on act records
the actual contract-text amendment (a Governance act, not an architecture one, per this
repo's own convention for pinning decided text), and implementation remains a further,
separate authorization after that.

**Traceability:** `2026-09-04-...-V3-D1-D4-D5-ADOPTED.md` (§5) ·
`2026-09-04-...-V3-D1-D4-D5-proposal.md` · `2026-08-19-...-v3-decisions-registration.md`
(+Amendment 1) · `2026-08-19-...-V3-architecture-determination.md` (§2.5–2.6, §12) ·
`scripts/observations/examples/lcom4/expected.json` (`_variant_decisions_pinned.intra_class_calls`,
unchanged since 2026-08-16) · `G-KOS-CONTRACT-V3-TARGETED-CONTINUATION`.
