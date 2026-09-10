# MD-081 §03 — `EC`/`EC_t` Evolution Ledger

Built entirely from MD-078 §01 and MD-080 §02/`TP-2`'s own already-verified evidence — no new source
reading performed for this ledger. `EC₀`→`ECₙ` numbering is this phase's own bookkeeping convenience,
not a corpus-native sequence; the actual chronological order is given by the `Time` column.

| # | Time | Fields | Meaning/responsibility | Evidence | Relationship to prior state |
|---|---|---|---|---|---|
| `EC₀` | 2026-08-27 16:25:45, `step-023` §14 | `(Purpose,Requirements,EvidenceRules,UncertaintyLimits,ConflictRules,TemporalRules,AuthorityRules)` — 7-field | genuine birth: "what must be known, at what strength, from what authority, for what purpose" | `SOURCE-STATED`, direct primary-source read | — (origin) |
| `EC₁` | 2026-08-27, `step-025d` | `EC_G=(R_G,Γ_G)` — 2-field | same general contract notion, drastically compressed | `SOURCE-STATED` (verification lane's own prior "EC SIGNATURE DRIFT" audit) | `RELATED OBJECT`, `SAME_LINEAGE_AS` `EC₀` per the verification lane's own characterization; not independently re-verified this phase |
| `EC₂` | 2026-08-27, `step-025e` | `EC=(R,Γ,A,V)` — 4-field | contract with requirements, context, authority, version | `SOURCE-STATED` (same audit) | `SAME_LINEAGE_AS` `EC₁`, drift not explained |
| `EC₃` | 2026-08-27, `step-025` (later same day) | `(Requirements,…,Version)` — 9-field | richest of the `step-025` variants | `SOURCE-STATED` (same audit) | `SAME_LINEAGE_AS` `EC₂`, expansion |
| `EC₄` | 2026-08-27, `step-025f` | `EC=(R,Γ,A,S,T,D,X,V)` — 8-field, Provenance dropped | contract with scope/temporal/dependency/exception fields added, provenance removed | `SOURCE-STATED` (same audit) | `SAME_LINEAGE_AS` `EC₃`, one field genuinely removed — a real, disclosed regression, not merely growth |
| `EC₅` | 2026-09-01/02, math lane `[00-47]`/`[DEF-19]` (`T5`) | `EC_t=EC(S_t,G_t,Q_t,C_t)` — 4-field, time-subscripted | the canonical co-birth source's own contract, feeding `Req(EC_t)→Sat→Δ_t→Zero` | `SOURCE-STATED`; `RECONSTRUCTED`, not proven, lineage to `EC₀`–`EC₄` (MD-067's own citation check already confirmed no direct citation) | `RELATED OBJECT` to the `step-023`/`025` family — plausible independent re-derivation of the same idea, `UNWITNESSED` documentarily |
| `EC₆` | 2026-09-06, T21 Part I | `⟨Req,Rules,Scope,ER,TR,AR⟩` — 6-field, "exact structure will be refined later" | the anchor for `Det_r`/`EvalReq`'s own composition | `SOURCE-STATED` | `RELATED OBJECT` to `EC₅` and to the `step-023`/`025` family — no source states which, if any, it descends from |
| `EC₇` | 2026-09-06, T21 Part II Def 2.20 | same 6 fields as `EC₆`, glossed one line each | Part II's own promised refinement of Part I's `EC₆` | `SOURCE-STATED` | `SAME OBJECT` as `EC₆` — genuine, internal, same-rewrite refinement (the one clean, unambiguous `EC` transition in the whole ledger) |
| `EC₈` | `verification/DEFINITION-VERIFICATION-REGISTER.md` DV-27, self-dated audit | matches `EC₀` exactly (7-field) | a direct verbatim quote/re-statement of `EC₀`, not an independent formulation | `SOURCE-STATED` | `SAME OBJECT` as `EC₀` |
| `EC₉` | `research/knowledgeos-sim/kos/inquiry.py`, self-dated ~2026-09-02 | `(id,standard,requirements,attribution_policy)` — 4-field dataclass | an executable, working contract object; cites `DEF-20/21/22` — `EC₅`'s (`T5`'s) own citation IDs | `DIRECTLY EVIDENCED` (code) | `RELATED OBJECT` to `EC₅`, `RECONSTRUCTED` lineage — plausible deliberate `T5`-lineage implementation, no explicit citation of `EC₅`'s own text |

## Was `EC.Rules` an intentionally open parameter, or was its responsibility relocated?

**Intentionally open, not relocated — this ledger's own central finding.** `EC₆`/`EC₇`'s own `Rules`
field is the *only* field, across all nine ledger entries, that is explicitly source-disclosed as
deferred ("the exact structure will be refined later") and then never honored anywhere in the remaining
material this reconstruction has read (20 further T21 parts, plus the full cross-lane sweep in MD-080
and this phase's own §01 extension). None of the other eight `EC` variants' own richer fields
(`EvidenceRules`/`ConflictRules`/`AuthorityRules` in `EC₀`; `standard` in `EC₉`) is ever source-stated
as supplying `EC₆`/`EC₇`'s own missing `Rules` content — each variant's own richer fields are its own,
not a demonstrated donor to the anchor. The responsibility was never relocated because no source ever
states a relocation; it remains, on the corpus's own terms, exactly what it was disclosed to be: a
deliberately deferred, contract-specific parameter.
