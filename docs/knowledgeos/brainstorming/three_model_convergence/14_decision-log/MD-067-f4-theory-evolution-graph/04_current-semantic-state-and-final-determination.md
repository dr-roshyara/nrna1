# MD-067 §04 — Current F4 Semantic State, Corrections, Final Determination

## Output 6: Current F4 semantic state, per object

Using exactly the required vocabulary (ESTABLISHED / CORPUS-SUPPORTED / PROPOSED / CONSTRUCTED /
REJECTED / SUPERSEDED / UNRESOLVED / GOVERNANCE-OPEN). "ESTABLISHED" is reserved for claims with both
a corpus-native proof/experiment AND a corpus-native ratification event; nothing in this graph meets
both bars simultaneously, so ESTABLISHED is not used below — this is itself a finding, not an omission.

| Object | Status | Basis |
|---|---|---|
| `K_t` | UNRESOLVED | type never fixed; competing decompositions never reconciled; `K_t→A_t` rename decided (`[01-56]`) but propagation never shown |
| `EC_t` | CORPUS-SUPPORTED | two non-identical structured definitions (`[00-47]` 4-field, `[05-36]` 6-field) coexist unreconciled |
| `Req(EC_t)` / `r` | CORPUS-SUPPORTED | consistently typed across the canonical source and the decisive rewrite |
| `standard` | UNRESOLVED | named as a field of `r` since `[00-51]`; body never given anywhere in 876 files |
| `App` | PROPOSED, effectively ABANDONED | introduced `[00-55]`, never re-engaged, not reintroduced by the decisive `[05-41]` pipeline |
| `Sat(K,r,Γ)` | CORPUS-SUPPORTED, DEFINED — **not GOVERNANCE-RATIFIED** | `[05-41]` Def 6.18, two proved theorems resting on it; no adversarial review or ratification event found anywhere after it |
| `Eval_c` / `Eval` | CORPUS-SUPPORTED, DEFINED | `[05-41]` §6.15, feeds `Sat` directly; earlier candidate factor-lists (Standing/Boundary/Reliability/etc.) superseded, not adopted |
| `Evidence` | USED, not independently DEFINED | free parameter throughout; closest typed treatment is `E_p` inside `[05-38]`'s `Eval` output |
| `Reason` | CORPUS-SUPPORTED as a field, not independently DEFINED | present in `[02-06]`, `[05-37]`, `[05-38]` (`J_p`), never itself typed |
| `Provenance` | USED, not independently DEFINED | field throughout, never typed on its own |
| `Context` (`Γ_t`) | USED, not independently DEFINED | ambient parameter of nearly every function; never itself formally typed |
| `Condition` | USED in one branch only | Contr/FDE `Boundary` tuple; absent from the decisive `[05-41]` pipeline |
| `Determination` | CORPUS-SUPPORTED, DEFINED, PROVED | `[05-38]` Def 3.5, `[05-41]` Def 6.2, `[THM 3.1]`/`[THM 6.1]`/`[THM 21.8]` all proved |
| `Decision` | CORPUS-SUPPORTED, kept deliberately separate from Determination | `[THM 16.38]` proved (Determination ⇏ unique Decision); Decision's own structure never independently formalized |
| `Δ_t` (Sat-gap sense) | CORPUS-SUPPORTED, DEFINED, PROVED — **not GOVERNANCE-RATIFIED** | `[05-40]` Def 5.2, `[THM 5.1]`/`[THM 5.2]` proved |
| `Δ_t` (transition-residue sense) | UNRELATED_HOMONYM, unreconciled | `[04-27]`/`[04-28]`, never touches the Sat-gap sense |

## Output 10: Correction to MD-057–066's own conclusions (their text unedited, per standing discipline)

**MD-066's Final Determination ("B — FOUND BUT INCOMPLETE") is superseded in weight by this graph's
own findings, though MD-066's own text is not edited and its determination was reasonable given the
narrower, diagnostic-triage evidence base it actually used.** MD-066 read 10 files (its own disclosed,
bounded scope) and correctly reported that no frozen, `EC_t`-consuming `Sat(K_t,r)` body existed in
what it had read. This graph's full, unfiltered, 876-file chronological traversal finds that **such a
body does exist**, four days later, in a source MD-066 never reached (`[05-41]`, 2026-09-06) —
`Sat(K,r,Γ)=Det_r(EvalReq(K,r,EC,Γ),EC)` — with two proved theorems, a full worked example, and eight
further domain instantiations. This is a genuine correction of weight, not merely additional detail:
the specific gap MD-066 identified as the "smallest next research action" ("check whether M0140's
typed pipeline was later extended to consume `EC_t`... in files dated after 2026-09-02 18:00") is
**answered** — yes, by `[05-41]`, though via a *different* lineage (the Theory-00-21 rewrite) than
M0140's own pipeline, which this graph confirms was never itself extended to consume `EC_t` (see the
dependency graph in `03_...md` — `App`/M0140's pipeline stages do not reappear in `[05-41]`'s own
apparatus).

**MD-062's central finding (purpose-relativity/`EC_t` structurally absent from `Sat*`) remains correct
for the specific `Sat*` construction MD-061/062 examined — it is not overturned by this graph, since
`[05-41]`'s `Sat` is a different, later, independently-derived object that DOES consume `EC_t`
(directly, as an argument of both `Eval` and `Det_r`).** MD-062's finding and this graph's finding are
compatible: the corpus contains both a `Sat` that fails to consume `EC_t` (MD-061's construction) and
a later `Sat` that does (`[05-41]`) — recorded side by side, not merged.

**MD-063/064's "boundary partially reconstructed" / "identity unresolved" findings are unaffected in
substance** — they concerned M0125's own informal "Sat" and its relationship to M0043's formal
`Sat(K_t,r)`, a narrower question than this graph addresses. This graph does not newly bear on whether
M0125's informal usage equals M0043's formal object.

## Output 11: Final A/B/C/D determination for the originally-alleged "missing boundary"

### **B — FOUND BUT INCOMPLETE, materially strengthened toward A, with an explicit governance
qualification withholding full A.**

A concrete, computable, corpus-native, twice-proved definition of `Sat(K,r,Γ)` that consumes `EC_t`
exists (`[05-41]`). This is materially stronger than any prior MD's own finding in this reconstruction.
**Full A ("FOUND AND DEFINED") is withheld for one precise reason**: this corpus's own consistently-
demonstrated discipline is that a formal claim of this significance receives adversarial review before
being treated as settled (illustrated repeatedly throughout this very graph — see the contradiction
table in `03_...md`, rows 1–11, every one of which was contradicted or refuted within the same or next
research session). `[05-41]`'s `Sat` definition received **no such review anywhere in the remaining
~114 traversed positions**. Its own sibling material (`[05-59]`, the Gita cross-check) reports a
corroboration-failure for the *kernel* proposal built on top of this apparatus (4/14 overlap) — a
signal that the broader Theory-00-21 rewrite's claims have not held up well under the one independent
check it did receive, even though that check did not directly test `Sat` itself. Treating an unreviewed
definition as equivalent to the corpus's many reviewed-and-survived results would overstate what the
evidence shows.

## Output 12: Exact remaining blocker(s), and the smallest next dependency for the main goal

1. **No adversarial review of `[05-41]`'s `Sat(K,r,Γ)=Det_r(EvalReq(K,r,EC,Γ),EC)` exists in the
   corpus as traversed.** The single smallest next research action this graph can name precisely:
   locate (if it exists, in material outside this traversal's scope — e.g. `phase_measure_theory/`,
   never entered per the user's own scope decision) or construct (as an explicitly-labeled, separately
   authorized `CONSTRUCTED REQUIREMENT`, never silently) an independent critical review of Parts I–VI
   of the Theory-00-21 rewrite, mirroring the discipline every other major claim in this corpus
   received.
2. **`standard` (the field of `r`) remains genuinely undefined** — named since `[00-51]`, never given
   a body anywhere in 876 files, including `[05-41]`'s own decisive treatment.
3. **`App` (Applicability) was abandoned, not resolved** — introduced `[00-55]`, never reintroduced,
   including by the very pipeline (`[05-41]`) that supersedes the branch it came from. Whether the
   Theory-00-21 rewrite's `EvalReq` step implicitly subsumes `App`'s role, or genuinely drops it, is
   itself an open question this graph surfaces but does not resolve.
4. **Two genuinely distinct `EC_t` structures coexist** (`[00-47]`'s 4-field vs. `[05-36]`'s 6-field)
   with no reconciling document found.
5. **The `Δ_t` transition-residue/Sat-gap homonym and the `ℛ_req` Required-Distinction-Universe/
   Req(EC_t) homonym both remain unresolved** — the latter already tracked as `EKS-41`.

## MD-067 STATUS: COMPLETE. HARD STOP per the user's own explicit instruction.

No further phase automatically opened. No canonical theory decided. No `Sat_new` constructed. No `K_t`
selected. No `≡_sem` resolved. No F3↔F4 work performed. `theory-extraction/` and Lane T's own K3/Ω/
T-K1/T-K2 material untouched throughout (confirmed: the traversal's scope was `mathematical_ideas_
that_can_be_implemented/` plus the already-committed `three_model_convergence/14_decision-log/`
artifacts that predate the queue snapshot — no `theory-extraction/` path was read).
