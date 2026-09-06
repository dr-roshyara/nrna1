# ARCHITECTURE FINDINGS REGISTER (Book-Driven Verification — GN-43)

**Standing rule: findings here are DISCOVERED, CLASSIFIED, EVIDENCED, and REPORTED — never
resolved without a separate explicit governance act. This register is OUTSIDE the book's
authority. Relationship to the PF register (`book-edition-2/production-log.md`): PF entries are
production/compression findings discovered while sourcing; AF-F entries are architecture-flaw
candidates discovered by verification passes. PF-1/5/6/7/8/9 are all AF-3-class (compression loss)
and are cross-referenced, not duplicated.**

**Taxonomy:** AF-1 contradiction · AF-2 undefined dependency · AF-3 compression loss · AF-4
boundary violation · AF-5 layer leakage · AF-6 authority leakage · AF-7 evidence-grade inflation ·
AF-8 missing transition semantics · AF-9 circularity · AF-10 implementation impossibility.

**Standing classifications from material already on record (opened 2026-08-28):**

| ID | Class | Finding | Trace | Status |
|---|---|---|---|---|
| AF-F-1 | AF-2 | η essential to EC, construction not established | OQ-1; III.2 §9; 025d §25D.39 | OPEN (governed as OQ-1) |
| AF-F-2 | AF-3 | PF-1/5/6/7/8/9 compression family | production-log | REPORTED, pending disposition |
| AF-F-3 | AF-8 | Ladder transition calculus absent (what moves P between statuses) | III.6 §9 | OPEN — research required |
| AF-F-4 | AF-8 | Policy-version transition semantics absent (in-flight, re-evaluation, grace) | III.8 §7 | OPEN — research required |
| AF-F-5 | AF-8 | Action/execution semantics beyond boundary | OQ-4; III.7 §9 | OPEN (governed as OQ-4) |
| AF-F-6 | AF-10 | L2 formal objects have no L3/L4 realization path demonstrated | 3C CF-015; every chapter's L4 section | REPORTED (known profile, not new) |

*(Hostile-pass and cross-chapter findings are appended below as they are reported.)*

---

# HOSTILE-PASS REPORT (independent reviewing agent, GN-43 commission · recorded verbatim 2026-08-28)

**Reviewer: fresh-context independent agent (produced no book content; read-only; ~70 tool uses;
extensive source spot-verification). Commission: hostile pass over Edition-2 III.1–III.8 +
cross-chapter chain test + systemic analysis under the HPA's compression criterion. NOTHING in
this report is resolved; candidates AF-F-7…AF-F-23 await governance classification
(false positive / explanatory compression / book issue / architecture issue / research requirement).**

## Hostile-pass findings (verbatim)

AF-F-7 · AF-1 contradiction · BLOCKING · III.1 §3 — the [E]-graded concept-level grade census
("one TESTED-within-scope, one COMPOSITION, one SPECIFIED, the rest READ") misreports the ratified
table: Evidence = TESTED (EXP-01 + CSV) and Ω = "ruling"; "the rest READ" is false for Evidence.
A status-fidelity failure (downgrade-by-omission) in the chapter that calibrates the reader's
trust. Trace: v0.1 §1 rows; v0.2 §1 carry-forward.

AF-F-8 · AF-7 grade inflation · SIGNIFICANT · III.1 — the five-layer stack taught at bare
fact-[FA] while its ratified source (FA-1 §1) grades it "[IN, from 3C + archaeology]"; the book
carries every other grade scrupulously and this one nowhere. Governance question: does GN-31
ratification of an [IN]-graded framing license teaching it as bare [FA]?

AF-F-9 · AF-3 compression (beyond PF-1) · SIGNIFICANT · III.4 §§8–9 — the four-vs-nine compression
sits INSIDE ratified invariant I-9's own text ("Zero's four-way non-satisfaction typology…",
grade READ from 025d — whose set is nine-valued); III.4 §9's citation elides exactly the word
"four-way" (D-2-class paraphrase inside a load-bearing citation), and §8's "nothing anywhere
records the source contained exactly four statuses" is undercut by I-9's own ratified wording.

AF-F-10 · AF-1 · MINOR · III.1 §8 row 4 — "L4 realizes L3/L2" vs ratified FA-1 "L4 realizes
fragments of L3/L5"; also strains the chapter's own §5.

AF-F-11 · AF-3 (unregistered member) · MINOR · III.7 §2 — the ratified Proposal row carries
(K_t, Z_t, G); its cited source 025g signs the selector with a fourth argument C (context),
dropped between source and ratified row (predates PF-8's scope); the sentence "is what the
ratified Proposal carries" is locally false as to the argument list.

AF-F-12 · AF-2 · MINOR · III.3 §1 / III.7 §9 — the world-state symbol family (X_t, S_t, W_t) has
no naming-register entry though the loop interface depends on the identification; the book polices
exactly this hazard for Zero/K_t/Ω/Ω_A and not here.

AF-F-13 · AF-2 · SIGNIFICANT · III.4 §10 ↔ III.5 §2 — the Requirement↔Proposition map (r_i ↔ P)
is defined nowhere: the evidence tuple is proposition-indexed, Zero items requirement-indexed;
025d evaluates Sat(K_t, r) leaving the typing implicit; III.3 §15 instantiates it with no rule.
Not covered by AF-F-1 or any FA-6 entry.

AF-F-14 · AF-9 circularity · SIGNIFICANT · III.8 §§2/6/9 — the stratification loop has no base
case and no stated epistemic-status precondition for the content→force crossing: (i) if Accepted
is prerequisite, the FIRST in-force policy could not have been admitted (genesis circularity);
(ii) if not, un-admitted content can acquire force (boundary anomaly); (iii) I-11 governs only
CHANGES, and 121.47 presupposes a predecessor. Genuine architecture issue independent of
compression; distinct from AF-F-4.

AF-F-15 · AF-8/AF-9 · SIGNIFICANT · III.6 §10 — "governed resolution" of CONFLICTED is consumed
but never typed, and it operates UPSTREAM of admission (adjudication precedes Determination in
Journeys 1 and 3), inverting the taught Evidence→Admission→Decision chain; its relation to I-4 is
unexamined. D-FA-1's recorded coarseness covers state interaction, not this. Distinct from AF-F-3.

AF-F-16 · AF-1 vs ratified allocation · MINOR — OQ ownership labels contradict ratified BA-5 §2:
OQ-1 (BA-5: III.4) claimed by III.2; OQ-12 (BA-5: IV.1) claimed by III.5. Surfacing obligations
met; ownership assertions are unratified reallocations.

AF-F-17 · AF-1 between authorities · OBSERVATION — v0.2 §7's non-claims text says "operator
selection open (OQ-11)" while FA-6 assigns the operator question OQ-3 and OQ-11 to
EKS↔KnowledgeOS; unqualified "OQ-11" now has two ratified referents; the book silently uses FA-6
numbering throughout.

AF-F-18 · AF-1 (illustration layer) · MINOR — three cell-level inconsistencies in the running
example: (i) r₃ "exactly two evidence items" (III.5 §10) vs the anomaly e⁻ against r₃ (III.5 §9);
(ii) the wire chain's role shifts unreconciled across III.5/III.3/III.6 (support for r₂ →
Supports(wireSummary, R) → source of rival R′); (iii) r₅ Stale at stage 3 yet stage 6's DC passes
"current per criterion H" with no narrated re-attestation — a silent transition of exactly the
kind the model outlaws.

AF-F-19 · AF-1 (illustration vs teaching) · MINOR · III.2 §10 — the worked IdealState's clauses
are world-state-shaped, the very drift §6 teaches as the recorded flaw; only the wrapper gestures
at the knowledge side.

AF-F-20 · AF-7 · MINOR · III.1 §4 — "forbidden flow made structurally impossible" rides at fact
strength; the RA is PROPOSED·NON-AUTHORITATIVE and untested against the articles (3C tested the
repository against v0.2); a proposed design's impossibility claim is READ-grade intention, not a
property.

AF-F-21 · AF-2/AF-6-adjacent · OBSERVATION · III.8 §2 — BC_Governance block asserts "deliberately
leaves its internal composition to L5 practice" (uncited intent claim) and supplies the first
definition of a ratified name — the closest Part III comes to the GN-42 explain/expand line.

AF-F-22 · AF-7-adjacent · OBSERVATION · III.5 §8 — the ratified Evidence row's basis "TESTED |
EXP-01 + CSV" vs GN-27's CSV grading; the book quietly corrects the ratified basis line without
noting that it does so.

AF-F-23 · D-2-class slip · OBSERVATION · III.8 §2 — quotation marks enclose a paraphrase of the
ratified Governance row ("with its own" vs "(own …)").

## Cross-chapter consistency results (verbatim summary)
All interfaces CONSISTENT except: Zero.Evidence ↔ III.5 tuple UNDERSPECIFIED (AF-F-13; and three
of five interlock statuses exist only in the source set, feeding Cluster 1); III.8 loop base
case/crossing precondition open (AF-F-14); CONFLICTED adjudication upstream of admission
(AF-F-15); grades consistent everywhere except the III.1 census (AF-F-7); running example
r-numbering/status progression monotone and I-12-conformant with three cell-level defects
(AF-F-18) and one standard-placement defect (AF-F-19); OQ numbering consistent with FA-6, two
ownership labels inconsistent with BA-5 (AF-F-16), one authority-internal label collision
(AF-F-17). DC.Evidence slot: requirement-vs-satisfier conflated in the illustration only.
Stage numbering coherent in narrative time (exposition observation).

## Dissolved candidates (10)
Article 6.5 (verified clause 5) · Zero-item 5-field/6-field forms (both source-verbatim) ·
III.5 §7 matrix (matches verdict exactly) · 008 quotes (all verified) · 042 quotes (all verified) ·
invariant-level census 2/8/2 (correct; the CONCEPT census is the finding) · stage numbering
(narrative-time coherent) · III.3 §4–11 block layout (by design) · "Zero must not become [the
selector]" title (literally verified) · all remaining source quotes (025f, 049, EXP-01, 050, 121)
verified — "source quotations accurate to a degree unusual in my experience of such material."

## Systemic analysis (verbatim conclusions)
The findings are NOT independent: three systemic issues + one book-side production pattern.
**Cluster 1 — POSSIBLE SEMANTIC INFORMATION LOSS** (PF-1/5a/5b/6/7/8/9 + AF-F-9 + AF-F-11), each
tested against the HPA criterion: PF-1 **(b) defect candidate** for Stale/Insufficient/Prohibited
(the III.4↔III.5 interlock runs on source-only statuses; Articles 11.2, I-10/I-11-adjacent), (a)
for the non-gap statuses · PF-5a **(b) narrowly** — Γ's re-homing into AcceptancePolicy is
plausible but unrecorded (+ (e) define Γ↔ρ_A) · PF-5b **(a) deliberate** (OQ-4 holds the ground) ·
PF-6 **(a) by ratified decision (D-FA-1)** with the Accepted∧Contested-Active expressibility
residue as (e) · PF-7 **(b) defect candidate naming I-3** (no conjunct asks "is knowledge
sufficient?"; the source's own refinement judged Q necessary) · PF-8 mostly (a), one (b)-sliver
(no ratified structure requires a decision to retain "why" — Article-10 answerability asymmetry) ·
PF-9 **(b) — the strongest**: no ratified rule for conflicting in-force authorities; the
escalation law and AI-non-override have no ratified objects; I-11's operability under stress
undefined · AF-F-9 **(b) by construction** (inside a ratified invariant's text) · AF-F-11 (a).
**Cluster 2 — MISSING SEMANTICS** (AF-F-1/3/4/5 + new 13/14/15 + the equivalence pair): one
systemic issue — "the corpus formalized states, structures, and boundaries, and never formalized
the transformations and identities between them"; (c)/(e), not compression.
**Cluster 3 — REALIZATION GAP** (AF-F-6): known profile, no new member, no violation found.
**Book-side pattern (d)**: AF-F-7/8/10/16/17/18/19/20/21/22/23 + AF-F-9's elision half — all in
SYNOPTIC or ILLUSTRATIVE material; per-concept definition blocks, quotations, and grade-carriage
found essentially flawless; the mechanical gates verify per-chapter claims but nothing verifies
cross-artifact summaries or example cell-continuity — that asymmetry is the systemic exposition
issue.
**Hypothesis verdict:** the compression hypothesis correctly explains Cluster 1 only (and the book
revealed two members the production log missed); it does not explain Clusters 2–3 or the book-side
pattern.

## Overall verdict (verbatim)
**CHAIN COHERENT WITH FINDINGS.** Every interface signature matches its ratified form verbatim;
grades consistent across chapters (one census exception); the example's progression monotone and
I-12-conformant; source quotations exceptionally accurate; no contradiction inside the taught
architecture, no boundary crossed, no authority leak — the chapters' own honesty apparatus is what
exposed most findings. Not clean: one BLOCKING census error (AF-F-7), a grade-carriage gap on the
stack itself (AF-F-8), a compression member inside a ratified invariant (AF-F-9), two new
missing-semantics members at the loop's foundations (AF-F-14, AF-F-15), one undefined
cross-interface typing (AF-F-13), and a cluster of minor synoptic/example defects — all must reach
governance as findings; none severs the chain.

---

# GN-45 DISPOSITIONS APPLIED (2026-08-28)

| Findings | Disposition |
|---|---|
| AF-F-7, AF-F-9(elision half), AF-F-10, AF-F-16, AF-F-18(i–iii), AF-F-19, AF-F-20, AF-F-21, AF-F-22, AF-F-23 | **BOOK — bounded corrections APPLIED** under GN-45.1 (per-chapter ledger in each claims.md); the underlying architectural halves (AF-F-9's I-9 compression) remain findings |
| AF-F-8, AF-F-17 | **DEFERRED — governance questions**, explicitly not corrected (may an [IN]-graded ratified framing be taught at [FA]? · v0.2-internal OQ-11 label collision) |
| Compression family: PF-1, PF-5a, PF-5b, PF-6, PF-7, PF-8, PF-9, AF-F-9, AF-F-11 | **ROUTED to formal architecture-disposition analysis** (GN-45.2); GN-46 math audit performs the formal half (§17); disposition = future governance act |
| AF-F-13, AF-F-14, AF-F-15 (+ equivalence pair: PF-3's ~, frame-equivalence) | **REGISTERED as candidate formal gaps / research questions** (GN-45.3); in GN-46 audit scope |
| AF-F-12 | folded into the GN-45.1 correction stream's residue: a register entry for the world-state symbol family is BOOK-side work assigned to the apparatus (glossary/notation) phase — recorded, not yet executed |
| III.9 | **PROCEED** (GN-45.4) — must teach kernel-relevant findings, resolve none |

---

# GN-46/GN-47 CROSS-REFERENCE (2026-08-28)
The mathematical verification report (`mathematical-verification-report.md`, MV-F-1…22) formalizes
AF-F-13/14/15 (as MV-F-9/-8/-10, with explicitly non-authoritative option sketches), corroborates
AF-F-9 (MV-F-2: "four-way" is invalid as a description of its READ source), adds two compression
members (MV-F-3/-4: status-set-closure drift; EC's source-final tuple), delivers the load-bearing/
benign split for the whole compression family (report §15/§17), finds NO CRITICAL/HIGH issue, and
records one positive: binary Zero(K,EC) is directly source-attested — R-2's basis stronger than
recorded. Dispositions remain pending governance.

---

## AF-F-24 · producer-suite finding at the GN-51 gate (2026-08-29)
Class D-4/AF-F-18 family (mechanical terminology gate + false conformance claim) · MINOR ·
III.2 chapter.md lines ~301/312 (the GN-50-era genesis expansion): two "Lord" tokens inside Part
III (BA-3/BA-5 forbid the token outside Part I/IV.4), AND III.2 claims.md asserts 'no "Lord"' —
false. Root cause: the expansion pass post-dated that chapter's conformance check (the same
gates-don't-recheck-after-edits asymmetry the hostile pass identified). DISCOVERED → CLASSIFIED →
REPORTED; correction awaits the gate's authorization; not fixed silently.

---

## AF-F-30 · Delivery-artifact integrity (RED HOLD trace result · 2026-08-29)
**Trace performed under the supervisory hold — no rewrite, no chapter modification.**
⟦E⟧ **The authoritative II.1 file is INTACT**: md5 428959f12c6f6560270eadd118b776c6 · 187 lines /
2,021 words · valid UTF-8 · all 16 reviewer-cited corruption fragments checked — every one present
CLEAN in the file (three matched only across line breaks — wrap artifacts of single-line grep, not
defects) · zero corruption signatures ("archithis", "TESTEversus", "nothinhitecture", …) exist in
the file. **All eight BA-ED2-12 controls inspected THIS file** (grep/wc against the on-disk path)
— the verification's confidence was about the file and stands.
⟦E⟧ **Where the gap entered — two distinct layers, both at the delivery boundary, neither in the
file:** (a) **producer rendition lapse (owned):** the chat delivery presented an editorially
CONDENSED rendition of the chapter (sections reflowed, some prose compressed) while labeling it
"the chapter itself" — a fidelity lapse against the very rule being exercised; (b) **downstream
transport corruption (cause UNKNOWN beyond the producer boundary):** the text the external
reviewer received shows mid-word truncations/concatenations characteristic of a copy/wrap/render
pipeline; the producer cannot inspect that pipeline and does not speculate.
**Classification:** production-control finding (delivery boundary), NOT a chapter defect and NOT
a verification failure. **Consequences:** BA-ED2-13 drafted PROVISIONAL below; delivery protocol
changed immediately (see GN-56); the chapter file untouched.

## BA-ED2-13 (PROVISIONAL — proposed, NOT binding until ruled)
"The artifact subjected to final verification must be the artifact delivered for review, or any
transformation between verification artifact and delivery artifact must be mechanically
demonstrated lossless." Practical producer rule proposed with it: review deliveries are VERBATIM
FILE CONTENT (machine-emitted, e.g. cat) accompanied by the file's md5 and word/line counts —
never a re-typed or condensed rendition labeled as the artifact; reviewers reading through
copy/paste channels verify the received text against the stated md5-anchored counts, or read the
file directly.

---

# DDD CONTEXT-SEPARATION REVIEW (GN-58 · independent reviewer · recorded 2026-08-29)
**Verdict: SEPARATION SOUND WITH FINDINGS.** No boundary violation constructed; no invariant
co-owned or accidentally orphaned; every canonical-flow crossing one-directional; A6 assessed
"exemplary — a textbook invariant correctly re-typed as a context-boundary rule by governed
repair"; the historical kernel-era god-object critique STRUCTURALLY ANSWERED point by point
(6/6, one partial residue folded into R1); K_t/Zero/Knower god-object candidates all DISSOLVED;
book fidelity FAITHFUL (separations taught at exactly ratified strength).

## DDD-F register (recommendations only; nothing resolved)
| ID | Class · Sev | Finding | Disposition rec. |
|---|---|---|---|
| DDD-F-1 | unadjudicated context inventory · **SIGNIFICANT (non-duplicative)** | v0.1 §6's six BC candidates carry the explicit obligation "CANDIDATES ONLY (3B/3C must confirm)" — and no phase ever confirmed, refuted, or re-registered it (grep-verified); FA-6 omits it. A lapsed obligation with no owner. | register (FA-6-successor entry / governance one-liner); natural adjudicator = the RA v1.1 intake conformance pass |
| DDD-F-2 | BC_Governance named-not-bounded · SIGNIFICANT (coupled) | membership of adjudication, escalation, genesis, commitment-chain undetermined; catch-all PRECONDITION present (not yet a god-object; today the inverse — too thin) | fold into the PF-9 architectural-candidate disposition as its DDD face |
| DDD-F-3 | untyped crossings = missing governed TRANSLATIONS · MINOR (duplicates MV-F-6/AF-F-13 in substance) | the source itself prescribes the repair shape: 049 §49.14 "the translation itself should be governed" | classificatory note on the existing candidates |
| DDD-F-4 | language leaks · MINOR | "Policy" (≥4 senses, incl. the silently-open question whether a decision policy is I-11-in-force), "Authority" (act vs ⪰_C precedence), "Conflict" (3 senses) — no FA-4 rows | register — extend FA-4 by ruling (D-FA-3/6 pattern) |
| DDD-F-5 | aggregate gap / unregistered compression member · MINOR | 008 §15's AssertionAccepted domain event (payload incl. PolicyVersion, Authority, DecisionBasis) did not survive ratification though I-11/A6/Arts.10–11 presuppose its payload; PF-8 covered only the decision-side sibling | register as compression-family member; feeds R1 |
| DDD-F-6 | unowned transformation · OBS | SourceObs→SemanticObs interpretation has no named operator/owner (the critique's Interpretation Context unheeded) | name as an R1 member |
| DDD-F-7 | invariant-taxonomy · OBS | three de-facto invariant kinds (context-owned / boundary-crossing / cross-cutting) never named; 042's OwnerContext rule source-level only | fold into future governance-algebra/invariant-taxonomy ratification |
| DDD-F-8 | book-fidelity · OBS | Part III nowhere mentions the candidate context map's open status — defensible at current grade; one-line mention warranted only if DDD-F-1 is registered | conditional book one-liner |

Full report in the session record. RA v1.1 unread beyond status (lane respected).

---

# NEW FINDINGS FROM THE OPERATION-REGISTRY DERIVATION (registered GN-84, 2026-08-31)
Discovered · classified · evidenced · reported. **NOT resolved** (GN-43: the book lane may not
resolve architectural findings). Both were elevated by explicit HPA direction so they cannot be
absorbed into operation-set work or lost in the step-280/281/282 history.

## AF-F-31 · `Reject` — an internal tension inside the RATIFIED surface (SIGNIFICANT)
⟦E⟧ The ratified FA-1/D-FA-1 requires a reachable `REJECTED` state. The executed derivation finds
`Reject` in the **core of all six** minimal registries — it is the only operation reaching that
state — while its specification **violates I-12 and Article 8**. `Split` violates I-12 also,
excluding one registry; `LinkEvidence`, likewise core, is recorded UNRECONCILED against the
executed algebra. `Reject` additionally carries **four incompatible readings** across the corpus.
⟦INT⟧ This is **not** a defect of the candidate operation set. A state the ratified architecture
requires is reachable only by an operation whose specification breaches two ratified constraints —
i.e. a tension *within* the ratified surface, surfaced by execution rather than by argument.
**Kind: ratified-architecture tension. Owning lane: architecture/governance. Standing as its own
issue — explicitly NOT to be solved while defining operations.** Blocks: the typing of `Reject` is
prior decision **P-1**, on which the consistency of every candidate registry depends.

## AF-F-32 · A6 crossed by a corpus-stated `Commit` rule (SIGNIFICANT)
⟦E⟧ Executed: A6 holds for all 55 pool members under 44 evidence units with no authority act — and
**step 025a-2 §36's `Commit` rule (five conjuncts, no authority conjunct) executed → `*** CROSSED
A6 ***`.** ⟦INT⟧ Two reasons this outranks its apparent size: the ratified A6 asymmetry (an
authority act crosses the boundary; evidence never does) is one of the architecture's
load-bearing rules, and the record's **previous** A6 witness was withdrawn as a tautology
(`evidence_volume` never read), whereas this witness is **capable of failing and did fail**.
**Kind: ratified-invariant conformance finding. Owning lane: architecture/governance. Registered
separately by HPA direction so it is not folded into the operation registry.**

## AF-F-33 · Prior "14-forced/18-upper" operation result is PROPOSED, not DERIVED (MEDIUM)
⟦E⟧ `oderive.py`, the executable behind that widely-cited bound, contains a **hand-authored
law→operation table and no closure computation**. Related: 272A's `D_mandatory` is a **bijection
onto its own operation list**, making the necessity test over it a tautology; step 277's
`R_mandatory`, which its criterion quantifies over, **is never enumerated anywhere**.
⟦INT⟧ The bound may not be cited as mathematical evidence. **Kind: evidence-grade correction.**

## AF-F-34 · Two further "computes-over-a-hand-table" artifacts, unregistered (SIGNIFICANT)
⟦E⟧ AF-F-33 registered `oderive.py`'s hand-authored `FORCES` dict. Independent extraction (GN-92)
finds **two more of the same class, behind two more widely-cited results**: (i)
`canonical-construction/exec/bandtest.py:17–28` — the executable behind **"D-1 IS K-INVARIANT"** —
computes its 16-subset invariance over a **hand-authored `NEEDS` dict** mapping each operation to
the K-components it requires; (ii) `step-281/exec/test_repair_selection.py:29–34` — behind the
`281x` **"mandatory operation set"** — contains a loop that **cannot fire**: `joint = False` is set
unconditionally *inside* the loop, so `needs_pair` is provably always empty, and the M3 conclusion
at `:39` is a `print()`. ⟦INT⟧ Same defect class as AF-F-33: a script runs, and the proposition it
appears to establish is authored rather than computed. **Kind: evidence-grade correction. Neither
result may be cited as computational evidence.** Owning lane: architecture/theory.

## AF-F-35 · The operation universe was never bounded (SIGNIFICANT — supersedes the framing of AC-1)
⟦E⟧ **48 distinct enumerations** located (26 claiming universes · 12 sub-enumerations/typed families
· 10 pre-canonical sets); **intersection EMPTY, executed** (*"NO TWO ENUMERATIONS AGREE"*); union
**≥100 verified** against the 57-name working set; **0 operations and 0 occurrences of "operation"**
in v0.2 and FA-1…FA-9 (independently reproduced). Nine mutually incompatible pairs identified with
exact contradiction points, incl. **one physical 276 file containing both `O_core is structurally
closed` and its own withdrawal**, both sections carrying `Status: COMPLETED` + `Authority: HPA`;
272A placing `Authorize`/`Validate` **inside** the core and simultaneously `REQUIRED EXTERNAL`; the
set contracting **19→17→6** with `Σ_min ≥ 4` proven against the 6; and `Transform`/`Add`/`Revise`
marked *CORPUS ESTABLISHES* at steps 249/250 then **vanishing** from 277/272A with no supersession
statement. ⟦INT⟧ AC-1's failure is **not** "a table was missed" — the universe has **never been
bounded**, and no governance act exists because **there is no governed text about operations for an
act to attach to**. **Kind: precondition finding. P-11 is prior to P-1 and to every minimality
claim.** Not resolved.

## AF-F-36 · Step order and file order disagree (MEDIUM)
⟦E⟧ Timestamps invert the step numbering: 250 (18:35:19) precedes 249 (18:35:48); 260 (19:19:56)
precedes 259 (19:20:58); and **272A/272B (22:42/22:50) follow 274 (21:43), 276 (21:59), 277 (22:01)
and 278 (22:21)**. 272A states it of itself: *"our later work jumped over that derivation."*
⟦INT⟧ The artifact called foundational was written after the work depending on it — so "later step
number" is not evidence of supersession anywhere in this range, and any dependency argument keyed to
step order is unsound. **Kind: provenance/chronology correction.**

## AF-F-37 · `Q_t` binds THREE incompatible objects, and no register covers it (SIGNIFICANT)
⟦E⟧ Verified verbatim in three sources: **025a-1 §6** — `K_t = (A_t, Q_t, E_t, C_t, P_t)`, *"`Q_t` =
**assessments**"*, a component **inside** `K_t`; **253 §1019** — `K_t=(X_t,R_t,Q_t,H_t,…)`, *"`Q_t` =
**epistemic qualifications**"*, again **inside**; **281.4** — *"`Q_t ⊆ P` … records propositions that
have actually been queried/evaluated"*, **outside** `K`, with `step-282/06` confirming *"`Q` is not a
component."* ⟦INT⟧ Three bindings of one symbol, two of them inside the state and one outside it.
**The naming-collision register does not cover it** — TG-15 covers `Ω`, TG-07 `Authority`, TG-16
`E`/`V`; **none covers `Q_t`.** Earlier accounts described a twofold collision; it is threefold.
**Kind: terminology/canonical-object collision, unregistered.** Owning lane: architecture.

## AF-F-38 · Σ minimality is CONTRADICTED in the corpus, and the reconciliation omits the refutation (SIGNIFICANT)
⟦E⟧ `DECISION-SIGMA-EPISTEMIC-STATUS` §5 (2026-08-30): *"**I proposed a four-state set
(Unknown/Supported/Refuted/Conflicted). That is one too many.** … **Σ = {Unknown, Supported,
Refuted} — three states, minimal, each irreducible**"*, and §6 marks the 4-state set *"**REFUTED as
minimal**."* Against `consolidation/03` §A2 + step 272B §272A.13, same date: *"**A2 · Four distinct
valuations — ✅ MINIMALITY CONFIRMED** … `|Σ₀| ≥ 4`."* ⟦INT⟧ `handoff/03` asserts *"no
contradictions"* — **but its reconciliation table does not include `DECISION-SIGMA` §5**, and its
"three" correction concerns the *direction trichotomy* of `(dir,str)`, **not** the three-*state*
minimality argument. **The `Σ_min ≥ 4` result the 272B chain rests on has an unreconciled
same-date refutation.** ⟦E⟧ Also: **≥12 non-identical Σ structures** enumerated as a lower bound, and
step 283 still names `Σ = (dir, str)` *after* the `(D,S)` reconciliation. **Kind: formal
contradiction, unreconciled.** Not resolved.

## AF-F-39 · `Policy` inside vs outside `K` — a contradiction reaching the AUTHORIZED surface (SIGNIFICANT)
⟦E⟧ **v0.2 §3 (AUTHORIZED, GN-19)**: *"a policy is knowledge content **AT REST inside `K_t`**"*, with
R-1's `Policy-as-content ∈ K_t`. Against **`step-282/07` (executed)**: *"`GovernancePolicy ∉ K` |
**YES** — `P` is a `Policy` object, never an `Assertion`"*, and the executed line *"`P ∉ 𝒜` → policy
is NOT knowledge content."* A third placement: `FORMAL-SYSTEM-RECONSTRUCTION-AUDIT-245-NEW` — *"245
puts π inside `K₀`."* ⟦INT⟧ Unlike most items in this family, one side is **on the authorized
surface** — so this is not a corpus-internal disagreement but an executed result standing against
v0.2's own wording. **Kind: architecture-vs-execution contradiction. Not resolved; neither side
adopted.** Owning lane: architecture/governance.

## AF-F-40 · Σ/`Q_t` independence holds only "by mutual absence" — the strong form is NOT ESTABLISHED (MEDIUM, sharpens AC-3)
⟦E⟧ Tested against the model code: `class K` has exactly two fields (`A`, `R`); `delta`, `Replay`,
`StructuralValid`, `SemanticallyValid`, `Lineage` **never call `Sigma()`**; `Q` has **no
representation in `kosmodel.py` at all** (`ReplayQ` is test-local). ⟦INT⟧ The code therefore
demonstrates **independence by mutual absence, not non-interference between two present things** —
so AC-3's PASS supports only the weak form. Independently corroborated: `Σ₀`'s `Conflict` is unary
while contradiction is relational — *"two assertions in an explicit `contradicts` edge both read
`Supported` under `Σ₀`"* (TG-10). **Kind: evidence-scope correction.** The six counterexample
constructions remain unrun (GN-87).

## AF-F-41 · Identification hazards: 272B's internal numbering, and a production `grantId` collision (MEDIUM)
⟦E⟧ (i) `20260830-225058_step_272b_…md` carries internal section numbering **`272A.1 … 272A.25`
throughout** — the filename says 272B, the content says 272A. ⟦INT⟧ Any citation of "272A §272A.n"
is ambiguous between two files; combined with AF-F-36's inverted chronology, step-number citation in
this range is unsafe without a path. (ii) A **`grantId` collision has already occurred in
production** (authority lane). **Kind: provenance/citation hazard + one operational fact.**

## AF-F-42 · `Assertion` is not a ratified primitive — the two lanes' state objects share no base object (SIGNIFICANT)
⟦E⟧ The governed `K_t` is *"state over the 8 primitives {Entity, State, Event, Observation,
Proposition, Relation, Policy, Action}"* — **`Assertion` is not among them.** The only governed
occurrences of the word are non-construct (I-8 *"No architectural assertion without evidence"*;
FA-3 *"refuse premature assertion"*). The verification lane's `K=(𝒜,ℛ)` is built **on** `Assertion`
and carries **neither `Policy` nor `Action`**. ⟦INT⟧ The two lanes' knowledge-state objects do not
merely differ in arity — **they share no base object**, and nothing in the record binds them.
Corpus-side symbol counts show the same split: `𝒜` 19 / `ℛ` 12 in the derivation corpus against
`𝒜` 451 / `ℛ` 618 in the audit layer — the symbols live mostly in artifacts *discussing* the
candidate. **Kind: cross-lane object mismatch, prior to any mapping. Not resolved.**

## AF-F-43 · Lineage's implementation is a Type-3 analogy, mis-reported as implementation; and the test figure is still cited wrongly (SIGNIFICANT)
⟦E⟧ `GovernanceLineageGraph` and 12 further named classes exist (32 production + 35 test files) — but
in `App\Contexts\Membership\Domain\Committee\Constitutional\Lineage`, **the election platform's
committee-governance context**. Under the corpus's own taxonomy this is **Type 3 — analogy**, and
step 267 §267.5 states Type 3 *"must not be reported as implementation"* — **then reports it as
`IMPLEMENTED + 47 tests`.** Corroborated: *"the implementation implements `L`, NOT `K`."*
⟦E⟧ The figure: independent count gives **4 test methods / 5 assertions** in the only test file;
G-35 recorded *"a `--filter=Lineage` name-match over 18 unrelated classes; 4 exercise the provenance
graph; weight ≈12× overstated across 14 artifacts"*; `consolidation/11` owned it (*"It is 4 tests…
I never executed it"*) — **and `handoff/05` still cites "47 tests" today.** ⟦INT⟧ Two distinct
defects: a **correspondence-type misreport** and a **live uncorrected figure**. **Kind: empirical
scope + evidence-grade correction.**

## AF-F-44 · `handoff/05` reports Identity and Equality as "zero remaining blocker" against two open blocking findings (MEDIUM)
⟦E⟧ `handoff/05` lane totals: *"Zero remaining blocker 4 / 25 — **Identity, Equality**, Lineage,
Orphan."* Against: **TG-06 OPEN, BLOCKING** (*"`id` hashes a mutable field"*, with the executed
re-key) and **IE-3** (*"`K₁ = K₂` has no truth value in the theory as it stands"*), plus MV-F-22
(*"K_t state equality — nowhere defined"*). ⟦INT⟧ **No artifact reconciles these.** The registry's
"clear in every lane" count is therefore not usable as a readiness figure. **Kind: register
inconsistency.** Not resolved.
