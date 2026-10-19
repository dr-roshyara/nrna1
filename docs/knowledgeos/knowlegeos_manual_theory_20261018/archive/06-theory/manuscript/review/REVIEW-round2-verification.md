# Independent verification of the round-2 corrections — report and dispositions

| Field | Value |
|---|---|
| Date | 2026-10-18 |
| Commission | Principal, "Independently Verify Round-2 Corrections Before Resuming Research" |
| Reviewer | a fresh, read-only session of the **same model family** as the author and the previous reviewer. **Not external expert validation** |
| Reviewer access (as instructed and as reported) | Ch. 0 (whole); Ch. 1 §1.6, §1.13–§1.14.5, §1.16–§1.17, change log; Ch. 2 §2.8.2, §2.9–§2.11, change log; glossary; `REVIEW-targeted-batch03-corrections.md`; register rows B3-; line ranges of `…0841`, `…0905`, `…0922`, `…1029`, `…1332`. **No corpus, no `.env`, no transcripts; no file changed** (≈ 3.9 min, 14 tool uses) |
| Independence limitation | the reviewer was also given the earlier review record, which contains the author's dispositions, so it may have anchored on them. Future targeted reviews should receive only the claims and the authorized evidence (Principal, 2026-10-18) |
| Reviewer's recommendation | **CORRECT** |
| Verdicts | A **DEFECTIVE** (ordinary; the core two-time correction is sound) · B **DEFECTIVE** (ordinary) · C **SOUND WITH MINOR ISSUES** (one consistency correction required) |
| Corrections applied | **none.** Per the commission, findings are returned and corrections wait for the Principal's direction |
| The round-2 corrections | remain **not yet accepted**. This review found defects in them, so the statement in `REVIEW-targeted-batch03-corrections.md` that they "have not been independently reviewed" is now superseded only in this sense: **reviewed, and found to need correction** |

## Verification of the findings by the author

Each finding was checked against the current text before classification (Ch. 1 L522–526, L625, L633, L640–646, L895, L911; glossary L22, L43–44; register B3-F2-01, B3-F3-07; V3 L317–319). **All checked findings hold.** One anchor slip by the reviewer: V3's "lapsed, was revoked, or was superseded" is at **L319**, not L317.

## Classification

### Confirmed defects (correction required; not yet applied)

| # | Area | Defect | Where |
|---|---|---|---|
| A1 | formula | *g* (and *a*) are bound existentially inside validly-made but used free in E(*g*, *t*). With two covering grants, or joint grants, the formula gives no answer | Ch. 1 L522–526 |
| A2 | formula | E covers **revocation only**, so suspension and supersession of *g* silently never end *c*; the name "ends-with-grant" suggests every ending | Ch. 1 L526; glossary L34 |
| A3 | formula | no **record-time** index: a grant later annulled as "issued in error" is still "valid at *t*ₐ" if the record is read as revocation from now on, so authority obtained in error persists (*survives*) or lasts until the annulment (*ends-with-grant*) | Ch. 1 L522; §1.13.2 (records editable) |
| B1 | enforcement | condition 3 ("*r* is mandatory, or the block is a confirmation request") excludes the authorized emergency freeze on a candidate rule that the round-2 conclusion admits | Ch. 1 L642; glossary L22 |
| B2 | enforcement | "advisory is the correct mode for any rule whose standing is short of adopted" contradicts the generalized conclusion | Ch. 1 L646 |
| B3 | enforcement | stale forms: "derivable from what the book already holds" (L625); Ch. 2 L625 and Ch. 1 L968 still bound enforcement by "standing"; register B3-F2-01 keeps "r effective; min()" | as listed |
| C1 | I-2 | the choice between (a) and (b) is declared reserved, but the body (L895, L911) and the glossary (L43–44) state (b) as settled | Ch. 1 L895, L911; glossary L43–44 |
| R1 | register | B3-F3-07's verification column still states the withdrawn bound validity(c) ⊆ validity(g) | register |

### Interpretive points the author accepts (to be written in as qualifications)

| # | Point | Author's position |
|---|---|---|
| A4 | AB L100 can be read **statically** (binding period ⊆ act window; the book's reading) or **dynamically** (the exception ends when the approver's authority ends, including by revocation, which would settle E as *ends-with-grant* for exceptions) | accepted; both readings to be stated; not decided |
| A5 | V3 L319: a rule stops being currently authoritative "because its authority lapsed, was revoked, or was superseded". If "lapsed" refers to the grant, this **conflicts with the normative premise** (products survive the end of their grant) | accepted as a **source-level counter-position** to the premise; to be recorded beside it |
| B-a | disjunct (a), "adopting *r* as mandatory authorizes blocking", merges binding strength with enforcement consequence, which Ch. 2 §2.9 keeps apart, and silently answers OQ-24 ("nobody has decided whether conflict blocks") | accepted: either a separate act setting hardness is required, or the question is marked open |
| B-fc | fail-closed "follows from the axiom itself" is too strong. The axiom shows only that an ungranted act confers no standing. **Preventing** the attempt needs INV-A3 (Track B) or a premise that only recorded grants count (cf. E4, authority that exists without a record) | accepted |
| C-c | a third reading of I-2 is missing: (c) reasons are a validity condition **for some kinds of act** only, e.g. changing an authoritative rule, where RM-3 (ruled, Track B) requires "explicit authorization and a recorded reason" | accepted |
| C-dec | the argument for (b), that it "keeps validity decidable from records", is weak: the **presence** of recorded reasons is equally decidable; only their adequacy is not | accepted; the recommendation for (b) loses that argument |
| C-term | terminology: "verifiable … with approver, reason" (Ch. 2 L674), "well-founded" (Ch. 1, glossary), "quality obligation" (register, review record); I-2's "visible" part assigned to neither side | accepted; align |

### Disputed points

None. No finding is rejected. **That every finding was accepted is itself a limitation:** the author and the reviewer share a model family.

### Unresolved questions (recorded with follow-ups)

| # | Question | Follow-up |
|---|---|---|
| U1 | E for each kind of claim **and each kind of grant ending** (revocation, suspension, supersession, annulment) | reserved decision 1 |
| U2 | annulment from the start vs revocation from now on; which record state "valid at *t*ₐ" is judged against | needs a record-time index or a stated gap; partly depends on grant records being version-controlled (§1.13.2) |
| U3 | meaning of "effects of an act" in P-E | reserved decision 2 |
| U4 | what breaching an invariant does to an act (I-1 and I-7 breaches are treated as defects elsewhere, Ch. 2 L650) | reserved decision 3 |
| U5 | computing current authority needs rule-to-grant linkage, which does not exist today (AM-5) | evidence gap, not a theory defect |

## Decisions reserved for the Principal or ARB (the reviewer decides none of them)

1. **Ending policy E**, per kind of claim and per kind of grant ending. Options:
   - *survives*: stable products, but authority obtained in error persists;
   - *ends-with-grant*: traceable authority, but fragile standing;
   - *per kind*: closest to the sources, but adds governance cost.
2. **Premise P-E**: adopt it, adopt it with "effects" narrowed to normative effects, or keep it conditional.
   - Adopting it means every gate, freeze, quota or automatic escalation, **including the session resolver**, needs a traceable authorizing act.
   - Keeping it conditional leaves §1.14.5 as advice, not theory.
3. **I-2 "reasoned"**: (a) a validity condition; (b) a quality obligation; or (c) a validity condition for some kinds of act (e.g. rule changes under RM-3). The choice also fixes what breaching any invariant means.
4. **Does adopting a rule as mandatory authorize blocking**, or does hardness need its own act (OQ-24)?

## Proposed corrections (not applied; await the Principal's direction)

- **A:**
  - bind *g* to "the grant cited by *a*'s record";
  - widen E to each kind of ending, or state its limited coverage;
  - add a record-time index, or name annulment as an open gap;
  - state AB's static and dynamic readings;
  - record V3 L319 against the premise;
  - fix the minor points (L518 referent; per-kind note on delegated grants; validity(c) for decisions; "two periods of a grant"; the I-9 citation at L536).
- **B:**
  - condition 3 to read "*r* is mandatory, a confirmation request, **or a valid act authorizes the block for that scope and period**";
  - disjunct (a) to require a hardness-setting act or mark OQ-24 open;
  - fail-closed to rest on INV-A3 or a stated recorded-grant premise;
  - update L625, L646, Ch. 2 L625, Ch. 1 L968, glossary L22 and L37, register B3-F2-01.
- **C:**
  - make L895, L911 and glossary L43–44 conditional on reading (b);
  - add reading (c) under RM-3;
  - drop the decidability argument;
  - add pointers from Ch. 0 (I-2, §0.5–§0.6);
  - align the terminology with Ch. 2 L674.
- **Consistency checks the corrections would require:**
  - dependent §1.16.2 L781;
  - the register rows B3-F2-01, B3-F3-07 and B3-F5-02;
  - change logs in Ch. 1 and Ch. 2;
  - a round-3 entry in the disposition record;
  - glossary rows 22, 34, 37, 43 and 44.
- **After correction:** a targeted re-review of the formula, the enforcement conditions and the glossary. The reviewer should receive the claims and the authorized evidence only, not the author's dispositions.

## Appendix — the reviewer's report

The unedited report is saved as `REVIEW-round2-reviewer-report.md`. Its structure: §1 scope observed, §2 passages, §3 verdicts with counterexamples (A: two concurrent grants; annulment from the start. B: automatic escalation to blocking via configuration; actor with unrecorded authority), §4 findings, §5 reserved decisions, §6 recommended corrections, §7 limitations (same model family; partial reading of sources and Ch. 2; original rulings unverified; failed counterexamples prove nothing), §8 recommendation CORRECT.


---

## Round 3 — corrections applied (2026-10-18, 16:13–16:20Z)

*Authorized by the Principal ("Apply the Verified Corrections and Prepare a Fresh Independent Review"). Baseline and checklist: `CORRECTION-ROUND3-CHECKLIST.md`.*

| Finding | Correction | Location |
|---|---|---|
| A1 | grants bound to **basis(a)**, the grants cited by the act's record (single or joint); validly-made requires joint coverage and the act inside each act window | Ch. 1 §1.14.1, formula block |
| A2 | E generalized to **E_P** with an **ending policy P** over all ending kinds (expiry, consumption, revocation, supersession, annulment, suspension), per kind of claim; reserved, no value assumed | same |
| A3 | **limitation L-RT** (record time) stated; annulment distinguished from revocation (retroactive vs from-now-on depends on L-RT) | same |
| A4 | AB L100 static and dynamic readings both stated; neither selected | same |
| A5 | V3 **L319** (verified anchor) recorded as a counter-position to the premise, with two readings | same |
| A-minor | referent of "revokes" fixed; premise limited to expiry and consumption; grant/claim difference per kind; validity(c) convention [tₐ, ∞) (Int); act window vs binding period; **I-9 misquotation withdrawn** (found in the baseline, not by the reviewer); AM-5 dependency stated | same; glossary "validity (period)" |
| B1 | condition 3 replaced: realization · authorization (any of three routes) · containment in the act's scope and period | Ch. 1 §1.14.5 |
| B2 | advisory mode is correct for any rule **for which no valid act authorizes a block** | same |
| B3 | classification: needs P-E; Ch. 2 §2.9 dependency restated; Ch. 1 §1.17.5 OQ-26 answer; §1.17.3 refusal bullet; glossary "enforcement consequence", "conformance gate", "authority gate" | as listed |
| B-a | adoption as mandatory authorizes blocking **only under reading (i)** of reserved decision 4; the book assumes neither | §1.14.5 |
| B-fc | fail-closed rests on INV-A3 (Track B) or premise P-R, not on the axiom alone | §1.14.5, §1.17.3 |
| U3 | "normative effects" delimited (permit, forbid, block, oblige, change standing); wider readings left to reserved decision 2 | §1.14.5; glossary "P-E" |
| C1 | validity statements made conditional on the reading; glossary "valid (act)", "well-founded (decision)" conditional | §1.17.2; glossary |
| C-c | reading (c) added (RM-3) | §1.17.2 |
| C-dec | decidability argument withdrawn; presence vs adequacy; **no recommendation** | §1.17.2 |
| C-term | I-2 split into explicit / reasoned / visible; "verifiable" and "quality obligation" defined; Ch. 0 pointer added | §1.17.2; Ch. 0 §0.6; glossary |
| R1 | B3-F3-07 verification replaced; superseded text kept in `reason` for audit. B3-F2-01 likewise; B3-F5-02 follow-up neutral | register |

**Mechanical checks after round 3:** register 0 findings (115 rows); anchors 0 of 321 out of range; coverage 0 uncovered lines on the five batch-3 files. **These are format checks.** Anchors written in round 3 were also spot-checked for content (AMS L284–286 and L200–206, V3 L319, AB L100 and L107, CF L36, RS L185–187, IA L173).

**Status of the round-3 corrections: not yet independently reviewed.**

**Review packet:** `06-theory/review-packets/RP-R3/PACKET.md`, sha256 `5788e33326594a0839389bef836033d48a1fb7150db1e9dff9e4e58fe1dfbc25`. Built by `tools/make_review_packet_r3.py` from the current manuscript. It contains the claims (with correction-history notes stripped), definitions and axioms, glossary rows and source excerpts. It contains **no** dispositions, rationale, earlier reviewer conclusions or expected answers. The reviewer may read only the packet and the seven named background sources. **Not yet run:** it waits for the Principal.
