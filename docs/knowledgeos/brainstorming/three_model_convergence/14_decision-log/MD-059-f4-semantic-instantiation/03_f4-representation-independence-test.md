# MD-059 §03 — Representation-Independence Attack on `Obs_F4`/`Sat_F4`

## The eight required tests (§6 of the authorizing prompt)

Applied to `Obs_F4(K_t) := (r ↦ Sat(K_t,r))_{r∈R_t}`:

| Test | Result | Reasoning |
|---|---|---|
| 1. Renaming | **INVARIANT** | `Sat` takes `K_t` as an opaque argument; renaming internal fields (under any single fixed structure) does not change what `Sat` returns |
| 2. Reordering | **INVARIANT** | same reasoning — order of internal fields is not visible to `Sat` |
| 3. Equivalent regrouping | **INVARIANT, conditionally** | regrouping fields into sub-tuples that preserve the same information is invisible to `Sat` *by the same opacity argument*, provided `Sat` genuinely only consumes `K_t` as a whole |
| 4. Splitting one representation into multiple components | **UNDECIDABLE FROM CURRENT CORPUS** | this is exactly the situation §01 already found: the corpus offers 9+ *mutually unreconciled* `K_t` structures (UE-1), not two *alternative encodings of one agreed object* — there is no corpus-given pair "same `K_t`, two encodings" to test `Sat`'s invariance against; only competing, never-equated proposals |
| 5. Merging components | **UNDECIDABLE FROM CURRENT CORPUS** | same reason as 4 |
| 6. State encoding changes | **UNDECIDABLE FROM CURRENT CORPUS** | same reason as 4 |
| 7. Syntactic reformulation preserving stated behaviour | **INVARIANT, by the opacity argument, IF "stated behaviour" is itself `Sat`'s own output** — but this is close to circular (see below) | flagged, not asserted as a clean result |
| 8. Addition/removal of purely internal representation details | **INVARIANT** | by construction, if `Sat` never reads internal details in the first place |

## Correction, disclosed (a modelling assumption this section originally made, not corpus-established)

The first pass of this section argued tests 1/2/3/8 pass because `Sat(K_t,r)` is "a black-box
predicate over `K_t` as a whole." **The primary-source check (`01a`) shows this is not corpus-
established** — M0132 states directly that `Sat`'s own eventual computation is *expected* to inspect
`K_t`'s internal components (*"the semantics of each component are still open. Therefore `Sat(K_t,r)`
cannot be fully defined yet"* — a statement that only makes sense if `Sat` is meant to read those
components, not treat `K_t` as opaque). **Treating `Sat` as a total black box was this reconstruction's
own modelling choice, not a corpus fact — flagged and corrected here rather than left standing
silently.**

## The precise, disclosed finding (revised)

Tests 1/2/3/8 pass **only under the disclosed assumption that `Sat` is evaluated as a black box** —
a genuine, non-circular argument *conditional on that assumption*, of the same shape as MD-058's
Proposition P1, but **not itself corpus-established**, unlike P1 (which reused F3's own already-
established `Reach` construction). This is the *strongest available* positive finding this phase
produces, but its status is downgraded from "MATHEMATICALLY DERIVED" to **HYPOTHESIS, conditional on
a disclosed, non-corpus-sourced assumption about how `Sat` will eventually be defined.**

**Tests 4/5/6 cannot be run at all** — not because `Obs_F4`/`Sat_F4` fail them, but because the corpus
supplies no *two agreed encodings of the same `K_t` object* to test invariance against. The 9+ `K_t`
variants (UE-1) are not alternative representations of one agreed semantic object — they are
*competing, unreconciled proposals*, and testing "does `Sat` give the same answer under two
representations of the same `K_t`" presupposes exactly the kind of agreement UE-1 says does not exist.
**This is a sharper, more precise finding than a flat "representation-dependent" or "representation-
independent" verdict — it is `UNDECIDABLE FROM CURRENT CORPUS`, and precisely because there is no
pair of representations to test, not because a test was run and returned an ambiguous answer.**

**Test 7's near-circularity, flagged explicitly**: calling a reformulation "behaviour-preserving"
already presupposes a notion of behavioural sameness — using `Sat`'s own output as that notion would
make the test vacuously pass by definition. **Not claimed as a genuine result; recorded as a method
risk, not a finding.**
