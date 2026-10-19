# Theory Readiness Assessment: corpus-based theory extraction (2026-10-18)

**Recommendation: NO-GO** for corpus-based theory extraction.

**A recommendation is not an authorization.** Decisions reserved for the Principal remain with the Principal.

**Method.** I read governance and state records only:
- the governance log `chronological-read/P3B-GOVERNANCE-LOG.md` (the log, not corpus, batch input or hold-out material);
- the S-series state files;
- this programme's access record.

No corpus file was opened for this assessment.

**Labels:**
- **[FACT]** read at source this session;
- **[INT]** interpretation;
- **[REC]** recommendation.

---

## 1. Two separate tracks, with separate gates [FACT]

| Track | What it is | Governing record |
|---|---|---|
| **Measurement track (S5)** | labelling 1,975 items in 396 batches: the formal measurement that yields "S5 data" | G-LOG-0110…0145; `.claude/S-SERIES-TODO.md` |
| **Theory-research track (Session B)** | reading permitted corpus documents to derive theory hypotheses | G-LOG-0141 (+ extension), the un-numbered **G-LOG-01xx** "Principal Authorization for Session B" |

**Correction of a framing:** "0 of 1,975" counts **S5 measurement labels** ("Accepted S5 measurements: 0 of 1,975 labels (0 of 396 batches)", S-SERIES-TODO L54). **It is not a count of corpus documents read.** See §4 for what has actually been read.

## 2. Measurement-track gates

| Gate | Status | Evidence [FACT] |
|---|---|---|
| **Run-3** | **NOT LAUNCHED.** Approved at G2 only | G-LOG-0117 (per S-SERIES-TODO L15); every later entry lists "run-3 outside G-LOG-0117" as not authorized (G-LOG-0129…0140) |
| **Quiet period** | **NOT SCHEDULED** | S-series README §4: "As of 2026-10-17, no quiet period is in force". No later entry |
| **PREFLIGHT-OK** | **NOT RECORDED** | G-LOG-0140 audit finding (3): "no PREFLIGHT-OK is recorded; at audit time P2/P3 of the preflight would have failed". The README states the same. No later entry |
| **G3 (S5 production; with G4, G5)** | **NOT AUTHORIZED** | G-LOG-0140: "S5 production (G3–G5) is not authorized". Repeated as "not authorized" in G-LOG-0137…0140 |
| **Counted canary** | **NOT APPROVED, NOT RUN** | S-SERIES-TODO L188, L257–258: the human approval of the disclosed attempt re-registration plus the counted canary is open, and so is its run (OB0012 attempt 3, OB0114 attempt 2) |
| **S5 authorization** | **NOT GIVEN** | G-LOG-0142: "Not authorized: S5; any corpus read; any research run". No H-06 submission exists, so all pre-data deadlines are open |
| **Verdict criteria** | **NOT ADOPTED**, except V-2 (ii) | G-LOG-0140 (drafting only); G-LOG-0145 adopts V-2 (ii) with a proviso, and "the rest of v1.8" is not adopted |
| **H-19 scoreability** | **DEFERRED**; must be decided before any S5 data | G-LOG-0143 |
| **Theory content rule** | "No substantive theory content is written before S5b and the recovery charter" | S-series README §3 rule 4 |

**Consequence [INT]:** under the S-series rules, a **verdict-bearing theory** (ROBUST / NO COHERENT THEORY) is **not possible** before S5 data exists. The verdict stays **OPEN — INSUFFICIENT EVIDENCE**.

## 3. Theory-research (Session B) gate

**G-LOG-01xx §3** requires all four conditions below before research can begin. Until then: "No production filter run, count-only source-ID check or primary-source read is authorized."

| Condition | Status | Evidence [FACT] |
|---|---|---|
| 1. Independent D1 review PASS (or conditions satisfied) | **NOT DONE**: reviewer "*to be appointed*" | `knowledgeos-s-series-theory/safety-gate/D1-REVIEW-PACKET-AND-HUMAN-TEST-SHEET.md` (roles table) |
| 2. Fake-fixture test signed PASS by a separate human tester (non-root) | **NOT DONE** | same file. No signed result exists |
| 3. The Principal records the safety gate as passed | **NOT RECORDED** | no entry in the governance log (searched: "safety gate", "fake-fixture", "D1 review" only occur in G-LOG-01xx itself) |
| 4. Scope and logging confirmed | **PARTLY**: the extension defines the scope, and the logging procedure exists | G-LOG-0141 extension; the READ-LOG files |
| *Also:* D2 (the production filter run) | **PAUSED** (`D2_CLEARED = False`). The amendment proposals (G-LOG-0146) are **not** in the log | the D1 packet; `safety-gate/D2-AMENDMENT-PROPOSAL-*.md` |
| *Also:* earlier filter runs (2026-10-17) | **NOT ASSESSED.** Their outputs "must not be treated as trusted authorization" | G-LOG-01xx §6 |

**Result: the Session B gate is NOT PASSED.**

## 4. Conflicting status reports (reconciled)

| # | Conflict | Reconciliation |
|---|---|---|
| C-1 | Where does the governance log end? S-SERIES-TODO: G-LOG-0140. Committed HEAD (`925e58045`, 2026-10-17): a "G-LOG-0141" header. Working tree: G-LOG-0141 text, the **un-numbered G-LOG-01xx**, and **G-LOG-0142…0145, uncommitted** (`git diff`: +122 lines) | **The authorization text that governs Session B, and G-LOG-0142…0145, are uncommitted.** The repository's own integrity rule ("working tree == index == HEAD" before governance conclusions) is not met for this file. **Needs: commit, or a confirmation by the Principal / Session A.** The un-numbered "G-LOG-01xx" also needs a number |
| C-2 | "0 of 1,975 corpus items examined" | this counts **S5 labels**, not documents (§1) |
| C-3 | **Corpus reading in this programme (manual theory reconstruction)** | see §5 |

## 5. Disclosure: what this programme has actually read and passed on

**Corpus reads [FACT].**
- This programme's access record (`00-access/ACCESS-RECORD.md`) records an **in-session Principal grant** (2026-10-18: "you can read everything"). That grant is **not entered in the governance log**.
- It also records a **checkpoint at 12:15 UTC**: having found no passed safety gate, the lane **"STOPPED before opening S0011"**.
- **Afterwards, at the Principal's explicit in-session instructions, this session read corpus sources S0011–S0104**, plus S0001–S0010 earlier (103 indexed sources in all).

**Protection check (done now) [FACT].**
- **All 103 paths are on the protective allowed list** (`CORPUS-ALLOWED.tsv`, 2,734 paths; 0 not on it).
- So, as far as that list goes, **no sealed hold-out file was read**.
- Residual risk: the list comes from an **unreviewed** filter (G-LOG-01xx §6).

**Governance status of those reads [INT].**
- They rest on **in-session Principal instructions**, not on the G-LOG-01xx safety gate.
- G-LOG-01xx is scoped to the S-series workspace. Whether the manual-reconstruction lane falls under it is **not recorded**.
- **This is a governance-record gap for the Principal to resolve.** I do not decide it.

**Sub-agent exposure (disclosure; a breach of this lane's own rule).** The access record adopts G-LOG-01xx §5: sub-agents receive **no corpus content** unless the Principal approves specific material. Three sub-agents were affected:

| Sub-agent | What it received |
|---|---|
| ALM review-1 reviewer | instructed to spot-check, among others, `docs/knowledgeos/reviews/KOS-ARCH-BASELINE-003-bc7-domain-model-proposal.md` (corpus source **S0071**); also received documents quoting short corpus excerpts |
| ALM review-2 reviewer | research documents containing short corpus excerpts |
| Independent oracle agent | a specification citing corpus locations |

- **None of the material came from the sealed list.**
- **Consequence under G-LOG-0144:** these sub-agent sessions should be treated as **exposed**, i.e. not eligible to draft Kind P candidates or hold-out predictions.
- **Recorded here for the Principal.** No further action was taken.

## 6. What may proceed now, and what may not

| Allowed without new authorization [REC, within existing grants] | **Not** allowed |
|---|---|
| preparing the **extraction method** from governing and methodological documents only | any further corpus reading by any track until the gate question (§3, §5) is resolved by the Principal |
| formal work that uses no corpus content (e.g. the authority models, decision briefs) | treating any hypothesis as corpus-derived |
| gap lists; question lists; templates for read logs and claim–evidence matrices | S5, run-3, G3–G5, the counted canary, H-19 unseal or scoring |
| — | passing corpus material to sub-agents |

## 7. GO / NO-GO

| Question | Recommendation |
|---|---|
| Corpus-based **theory extraction** (S-series rules) | **NO-GO.** S5 has not started; verdict criteria are not adopted (except V-2); README rule 4 forbids substantive theory before S5b |
| Further **Session B corpus research** (G-LOG-01xx) | **NO-GO** until D1 PASS, the signed human test and the Principal's gate record, and until the uncommitted log entries are confirmed |
| **Extraction-method preparation** (no corpus) | **GO** within existing grants |

## 8. Decisions for the Principal (only these)

1. **The status of this programme's corpus reads** (S0001–S0104, made on in-session instructions while the safety gate was unrecorded):
   - **(a)** record them as authorized by your in-session grant (a governance-log entry, to be made by you or Session A); or
   - **(b)** treat them as outside the gate, and keep the results as research notes only.
2. **Commit or confirm the uncommitted governance-log entries** (G-LOG-01xx, G-LOG-0142…0145), and give G-LOG-01xx a number.
3. **Appoint the D1 reviewer and the human tester** for the safety gate, if Session B corpus research is to continue under G-LOG-01xx.
4. **Note the sub-agent exposure** (§5). Under G-LOG-0144 the affected sessions are not eligible for prediction roles.

**Recommended next step [REC]:**
- decisions 1–2 first: they settle what counts as evidence;
- then the D1 safety gate;
- method preparation can continue meanwhile.
