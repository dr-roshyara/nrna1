# MD-049 §3 — MinKer on Equivalence Classes (Phase 5), DDD Analysis (Phase 6)

## Phase 5 — does the existing framework permit `MinKer` to operate over semantic equivalence
classes rather than syntactically unique Kernel definitions?

**Yes, at the level of the framework's own design — this part does not need to be demonstrated by
this study, because the source material already demonstrates it.** `MinKer_/≡sem = {[K]_≡sem \|
K∈MinKer(𝔠_KOS)}` (MD-045 `01_...md`, file 8 of the chain) is explicitly typed as a **set of distinct
semantic equivalence classes**, not a set of syntactically-unique implementations — this was the
chain's own correction of its earlier, overclaimed "uniqueness" bullet (MD-044 `02_...md`). The
framework's own mathematics already treats "same semantic class, different implementation" as the
normal, expected case, not an obstacle.

**What is NOT possible with existing material is populating any equivalence class with a real
candidate**, because doing so requires the `≡_sem` relation to actually be *evaluated* on a real pair
— and Phase 4 (`02_...md`) just demonstrated directly that this evaluation cannot currently be
performed for any of the three pre-registered pairs. **The obstruction is not in `MinKer`'s own
design — it is entirely in the missing instantiation of `Tr_K`/`Obs`/`Beh_𝔠` for any real candidate.**
This is a materially different, and more precise, finding than "MinKer cannot handle multiple
definitions" — it can, by design; it simply has nothing to evaluate them with yet.

## Phase 6 — DDD analysis, with the `Challenge` finding as the required negative control

For each apparent point of contact found across this MD-044–049 arc, classified per the required
scheme:

| Apparent contact | Classification | Basis |
|---|---|---|
| F3's `DetectGap`-derivable/`Qualify`-needed finding vs. F3 itself (already known) | N/A — same candidate | — |
| "Challenge" (MinKer's 13th name) vs. the other vocabulary's own domain-object list (MD-047) | **GENUINELY DIFFERENT CONCEPT (confirmed homonym)** | Verb/capability role vs. noun/domain-event role, checked directly against every instance — the required negative control this phase must use |
| "K_t" (MinKer chain's generic notation) vs. F1's own ratified `K_t` object (this phase, `02_...md`) | **GENUINELY DIFFERENT CONCEPT (confirmed homonym)** | Generic epistemic-state-evolution symbol vs. a specific, governance-ratified 8-primitive tuple identity — same pattern as `Challenge`, found independently by this phase's own Step 0 check |
| F1 vs. F3 vs. F5 (the three pre-registered pairs) | **UNRESOLVED (insufficiently specified to classify further)** | Not even reachable as "different representation" or "different granularity" — those classifications presuppose a shared behavioral description to compare, which does not exist |

**The `Challenge` homonym, used exactly as the required negative control**: it demonstrates precisely
the failure mode this whole arc has been guarding against — a shared surface word mistaken for shared
meaning. Both confirmed homonyms found in this arc (`Challenge`, `K_t`) followed the identical
diagnostic pattern: a word search finds a hit, direct inspection of every instance shows the two uses
occupy different grammatical/ontological roles, and no source anywhere asserts they are the same
thing. **No apparent contact found anywhere in MD-044–049 has survived this check as a genuine
identity or even a structural correspondence** — every one resolves to either a confirmed homonym or
an insufficiently-specified pair.
