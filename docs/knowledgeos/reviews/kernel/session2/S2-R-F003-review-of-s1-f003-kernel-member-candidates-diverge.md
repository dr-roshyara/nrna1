# Session-2 Review — S1-F003

## 1 · Source artifact
`session1/S1-F003-kernel-member-candidates-diverge.md`

## 2 · What Session 1 claims
Two Kernel member-lists (A `20:32`, B `21:08`) written **36 minutes apart** diverge; their intersection is `{Evidence, Provenance}`. Read as evidence of instability in Kernel membership.

## 3 · Evidence classification
The two lists are **FACT** (as quoted). *"Divergence"* is **INFERENCE**. *"Instability"* is **INTERPRETATION**, and it is the contested layer.

## 4 · Question type
Session 1 treats both as **BOUNDARY (membership)**. `S2-F007` challenges precisely this: **A enumerates protected concerns, B enumerates pipeline stages** — so the two may answer *different* question types, making the comparison **ill-typed** and the intersection meaningless rather than small.

## 5 · DDD / architectural altitude
Nominally Kernel-altitude membership. Per `S2-F010`, four questions are answered under one heading — **membership · boundary · mechanism · semantics** — and that single conflation is upstream of `S2-F007`, `S2-F009` and `S2-F012`.

## 6 · Provenance assessment
Both lists are the same author within one 36-minute window. **PERSISTENCE / one working session**, not two positions. `S2-F001` additionally records that a *report* about these lists is not evidence *of* them.

## 7 · Zero-lens assessment
B's missing `Authority` is **UNMENTIONED**, not demonstrably **EXCLUDED**. `S2-F016` Part 2 supplies the decisive pattern from `S1-F005`: `Authority` runs **A ✔ → B ✘ → C ✔**. Instability predicts drift; a term absent once between two presences, with no rival in the gap, is better explained by B answering a different question.

## 8 · Contradiction test
Same subject, **different question type** (§4), same author, same 36-minute context. **NOT a contradiction.** Classification: **DIFFERENT QUESTION (probable) / UNRESOLVED.** ⚠ Session 1's instability reading remains live and is **not** withdrawn by me.

## 9 · Convergence test
None claimed. ⚠ `S2-F014` shows the *intersection* of A/B/C is not a stability result: C could only have shrunk it, and contains both members — so "unchanged" was near-guaranteed (`S2-F016` Part 1).

## 10 · What survives
**The two lists as data.** Their content, timestamps and non-identity are the artifact's durable contribution and nothing challenges them.

## 11 · What is challenged
*Instability* as the interpretation (`S2-F007`, `S2-F016`) · the intersection's informativeness (`S2-F016`) · the four-question conflation (`S2-F010`).

## 12 · Implementation relevance

### Kernel test
No capability is named. A member-list is a **proposal about what the Kernel contains**, and `S2-F024.1` shows the corpus has no bound criterion for deciding such lists. *Remove the lists:* nothing becomes impossible, because nothing was built on them.

### Decision
**DO NOT IMPLEMENT.**

### Engineering consequence
None. Implementing from a member list would be the exact failure `S1-F007` diagnoses — **membership by capability enumeration**, with no invariant named that the membership protects.

### What would be lost by not implementing
Nothing identifiable.

### Existing protection
Not applicable — there is no threat here, only an unresolved modelling question.

## 13 · Open questions
What question was each list written to answer? *(the discriminating test for `S2-F011` ↔ `S2-F013`, and it needs the corpus)* · Is `Authority`'s A✔B✘C✔ pattern drift or scope?

## 14 · Confidence
**High** on the lists as data and on `DO NOT IMPLEMENT`. **Low** on which interpretation of the divergence is right — deliberately so.

## 15 · Final Session-2 assessment
The most-cited Session-1 artifact and the one whose interpretation is least settled. Its data survives fully; its diagnosis has two live readings and I decline to choose. **Zero implementation consequence**, which is the appropriate outcome for a finding about competing proposals rather than about a required capability.
