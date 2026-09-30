# v1-2-sim-three-valued-zero-ambiguity

**Scope(s):** OBJECT · **Row count:** 11 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `E1`, `Zero_kleene`, `Zero_strict`, `Zero_weak` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0058`, scope `OBJECT`: E1's central finding: under three-valued class-indexed Sat, 'Zero iff Delta_t=empty' names three non-equivalent predicates (Zero_strict, Zero_weak, Zero_kleene) that disagree on the most favourable case -- a fully determined, corroborated state is strict=false, weak=true, Kleene=U -- because the three universally-open classes (governance, temporal, operational) supply every U, so Zero cannot be evaluated until they are closed (Step 261's meta-gate reappearing independently). Zero_strict is unreachable in every case tested across the whole v1.2 lane.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2398 §"The single most consequential change is three-valued Sat: it makes v1.1's sentence "Zero <=> Delta_t = empty" ambiguous. That one sentence names three different predicates, and they disagree on the most important case."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2400 §"Gap is no longer a set. It is a partition: GapPartition(K, Req) = (violated, undetermined, satisfied) ... Zero_strict(g) <=> violated=empty AND undetermined=empty ... Zero_kleene(g) in {top,bot,U}"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2410. Candidate lifecycle: **ACTIVE**. Evidence: no retraction/supersession/contradiction evidence recorded; the ACTIVE classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S2398, S2401 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2400 |
| type_signature | PRESENT | S2400 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | PRESENT | S2400 |
| semantics | PRESENT | S2402 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2398, S2398, S2398, S2401, S2402 |
| open_questions | PRESENT | S2402 |

## Rationale
States the executive finding of the v1.2 run: three-valued class-indexed Sat makes v1.1's single Zero definition name three disagreeing predicates. [S2398]

Identifies the one thing v1.2 made strictly worse: downgrading E_t!=K_t to a research distinction leaves the CE-1 contradiction 'homeless' -- real and reproducible but no longer attached to a firmly-asserted claim. [S2401]

## Assumption register
| Statement | Stated | Source | Anchor |
|---|---|---|---|
| governance requires an explicit authority; none was supplied | EXPLICIT | S2400 | declared assumptions table |
| delta (operation semantics) is undefined per Step 290 open status | EXPLICIT | S2400 | declared assumptions table |

## All rows (source_id order)
- [S2398] types=['EXPERIMENTAL-RESULT', 'ANALYSIS'] scope=THEORY-LEVEL — "States the executive finding of the v1.2 run: three-valued class-indexed Sat makes v1.1's single Zero definition name three disagreeing predicates." (anchor: "The single most consequential change is three-valued Sat: it makes v1.1's sentence "Zero <=> Delta_t = empty" ambiguous. That one sentence names three different predicates, and they disagree on the most important case.")
- [S2398] types=['EXPERIMENTAL-RESULT'] scope=OBJECT — "Details result 1 (E1): the fully determined/corroborated case gives strict=false, weak=true, Kleene=U, and the U-producing classes are exactly the three the theory itself marks open, so Zero's evaluability is gated by the theory's own unresolved items." (anchor: "A state where everything the agent could determine is determined and corroborated is not Zero (strict), is Zero (weak), and undetermined (Kleene). The three undetermined requirements are governance, temporal and operational -- exactly the three classes v1.2 itself marks open.")
- [S2398] types=['EXPERIMENTAL-RESULT', 'CORRECTION'] scope=OBJECT — "Details result 4 (E3): CE-1 factivity and CE-3 revision both persist under v1.2 unrepaired, but three-valued Sat improves diagnostic resolution (splitting one undifferentiated gap into 1 violated + 6 undetermined)." (anchor: "Both v1.1 failures persist -- and v1.2 makes one of them better reported. ... v1.2 splits into 1 violated (rs) + 6 undetermined. The failure is identical; the report is strictly more informative.")
- [S2400] types=['FORMALIZATION'] scope=OBJECT — "Formalizes the consequence of three-valued Sat: Gap becomes a three-way partition (violated/undetermined/satisfied), producing three distinct candidate Zero definitions (strict, weak, Kleene) from the single prior equation." (anchor: "Gap is no longer a set. It is a partition: GapPartition(K, Req) = (violated, undetermined, satisfied) ... Zero_strict(g) <=> violated=empty AND undetermined=empty ... Zero_kleene(g) in {top,bot,U}")
- [S2400] types=['ASSUMPTION'] scope=OBJECT — "Declares the assumptions underlying the v1.2 run and their sensitivity: no governance authority supplied (keeps Zero_strict unreachable), lambda fixed to (status,value), delta left undefined per Step 290." (anchor: "governance requires an explicit authority; none was supplied | supplying one moves rg from U to top/bot and would make Zero_strict reachable ... delta is undefined (Step 290 open) | defining it moves ro out of U")
- [S2401] types=['EXPERIMENTAL-RESULT'] scope=OBJECT — "Registers TG12-1 (CRITICAL): Zero's three-way ambiguity under three-valued Sat, requiring a normative (not experimental) decision." (anchor: "TG12-1 | Zero <=> Delta=empty is ambiguous under three-valued Sat -- it names 3 predicates that disagree | G1 mathematical | NORMATIVE decision required | CRITICAL")
- [S2401] types=['ANALYSIS', 'LIMITATION'] scope=OBJECT — "Identifies the one thing v1.2 made strictly worse: downgrading E_t!=K_t to a research distinction leaves the CE-1 contradiction 'homeless' -- real and reproducible but no longer attached to a firmly-asserted claim." (anchor: "E_t != K_t was a correction in v1.1 and is a research distinction in v1.2. ... v1.2 has no ratified statement that the contradiction contradicts. The failure is now homeless: real, reproducible, and attached to a claim the theory no longer asserts firmly.")
- [S2402] types=['EXPERIMENTAL-RESULT'] scope=OBJECT — "Establishes that Zero_strict is unreachable in every constructed case because three requirement classes are permanently U by construction." (anchor: "Zero_strict was unreachable in every case tested ... because governance, temporal and operational requirements are permanently U. Under the strict reading, no state this experiment could construct is ever Zero.")
- [S2402] types=['RESTATEMENT'] scope=THEORY-LEVEL — "Restates the productive-weakening thesis as the closing verdict of the v1.2 lane." (anchor: "The weakening was scientifically productive: v1.2 repaired no failure but exposed a defect v1.1's phrasing had concealed, and improved the diagnosis of one it inherited.")
- [S2402] types=['FUTURE-RESEARCH'] scope=METHODOLOGICAL — "Prescribes the Zero Closure Decision Experiment (three arms, one per reading) as next, with governance authority and temporal semantics supplied as prerequisites -- a plan later superseded by the Sat_c-first sequencing correction in this same batch." (anchor: "The Zero Closure Decision Experiment. Three arms, one per reading ... supply a governance authority and a temporal semantics for the test harness -- E1 shows those two classes alone are responsible for Zero_strict never being reachable.")
- [S2410] types=['CORRECTION'] scope=OBJECT — "Walks back an earlier stronger claim ('Zero is THE closure predicate of the whole theory') to the weaker, more defensible 'Zero functions as a candidate closure predicate in the tested model; canonical status remains OPEN'." (anchor: ""Zero is the closure predicate of the whole theory." That is too strong given the evidence currently available. ... In the tested v1.2 model, Zero functions as the candidate closure predicate ... Whether Zero is canonically the theory's closure construct remains OPEN.")

## Notes for P3
(none beyond what is noted above)
