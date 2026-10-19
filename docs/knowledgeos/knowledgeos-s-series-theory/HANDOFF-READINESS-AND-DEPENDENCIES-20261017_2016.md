# Handoff readiness (D1, D6) and dependency list

| Field | Value |
|---|---|
| Author | Session B, 2026-10-17 20:16 (clock) |
| Status | Preparation only. **No appointment, review, signed test or gate decision has happened.** |
| D2 | **Answered by the Principal**: governance-log entry headed "G-LOG-01xx — Principal Authorization for Session B" (PROD log, from l.2233; its number is not yet assigned). Session B is authorized exclusively, effective only after the safety gate. The open details are listed in §3 below. |

## 1. D1 filter r4: reviewer handoff checklist

**Packet checks done by Session B at 20:14:28** (facts, not review):

| Check | Result |
|---|---|
| The packet names **r4** and marks it **not yet reviewed** | ✔ |
| Full SHA-256 hashes match the files on disk (SPEC `e24a8bf2…c232`, code `4b60a02b…2dff`, tests `f3a44639…0aa1`) | ✔ |
| Expected test count **49**; developer pre-check run as non-root (uid 1891886374): 49 OK, 0 skipped | ✔ |
| Reviewer and human tester are separate roles; neither is Session B | ✔ (both still *to be appointed*) |
| The human test must run as non-root | ✔ (Part D, step 3) |
| The 49 tests are presented as developer evidence only, not proof of safety | ✔ |

**The reviewer must cover** (from the Principal's instruction; each item is listed in packet Part B or in SPEC):
1. **Specification first:** derive expected behaviour and own tests before reading the code.
2. **Review history r1–r4:**
   - r1 FAIL, 9 fail-open;
   - r2 PASS WITH CONDITIONS;
   - r3 FAIL from a fresh reviewer, 7 fail-open;
   - r4 unreviewed.

   Treat this history as leads only.
3. **Defect tests must fail on the old implementation.**
   - **Not satisfied today:** the old scripts load the real seal at import, so they **must not be run**.
   - *Option for the reviewer:* re-implement the old matching logic in a fake-seal harness, or extract its pure functions, and show that each defect test fails on it. This is the **reviewer's task**. Session B must not do it, because Session B is the author.
4. **Residual risks:**
   - R-1 edited-and-moved copies;
   - R-2 homoglyphs;
   - R-3 and R-6 ID formats, separators, base64;
   - R-4 case-insensitive filesystems;
   - R-5 over-withholding;
   - R-7 TOCTOU on parent directories;
   - R-8 the CJK label threshold.
5. **Specific areas:**
   - symlinks (files and directories);
   - encodings (%xx, HTML, JSON `\u`, quoted-printable, nested);
   - Unicode homoglyphs and invisible fillers;
   - identifier formats, including hyphenated prefixes;
   - path canonicalization (NFC, `..`, absolute, backslash);
   - unreadable files and directories;
   - traceback leakage;
   - output overwrite and injection;
   - integrity against the pins;
   - the over-withholding thresholds.
6. **Production adapter:** specify what it must verify, using **only the resolver's documented interface**:
   - the hash conventions for I-1 and I-2;
   - which seal fields hold paths;
   - the source of the pins.
7. **Verdict:** PASS / PASS WITH CONDITIONS / FAIL, as a numbered list. If tests are added or changed, the hashes and the count are re-issued before the human run.

**Statements the packet must not be read as making:** that the filter is complete, or that it can be proven safe against all encodings. SPEC carries this caveat explicitly.

**Open fields for the Principal:**

| Field | Requirement | Value |
|---|---|---|
| D1 reviewer | did not write the filter; a human engineer, or another model family as a supplement | — |
| Human tester | a different person from the reviewer; non-root account; signs Part D | — |
| Review deadline and record location | — | — |

**Human-test readiness:** **READY to run once the reviewer approves the final file set.** The tester must use the reviewer-approved hashes and count, not necessarily the r4 values.

## 2. D6 external review: readiness checklist

| Item | Status |
|---|---|
| Two-stage protocol (claims first, then arguments) | ✔ `external-review/README-EXTERNAL-REVIEW.md` |
| Stage-1 claims: core + O-5 + O-6 | ✔ `STAGE1-CLAIMS-core.md` |
| Stage-1 claims for results produced **after** the packet: O5-12 (1951), D10-1 (1951), O-10a (2016) | ✘ **not yet added.** An addendum will be prepared **after** O-10a's internal review, so that the claims are stated as reviewed |
| Stage-2 document list | ✔. To add: the 1951 report, the 2016 report |
| D4 correction proposal with r2 amendments, and the manuscript hash `28d8bba2…a0` | ✔ |
| The open disagreement U-1 to adjudicate | ✔ |
| Literature list to verify (about 11 items) | ✔ |
| Statement that the review cannot establish fit to KnowledgeOS | ✔ |
| Preferred reviewer (human mathematician or theoretical computer scientist; AI only as a supplement) | ✔ stated; **not appointed** |
| A reviewer response format | ✔ |

**Open fields for the Principal:** the reviewer's name and qualifications; how the packet is delivered (the reviewer needs read access to the listed workspace files only, with **no** corpus-access or safety-gate folders); the due date.

## 3. Dependency list

| # | Item | Who can do it | Blocked by |
|---|---|---|---|
| 1 | Assign the G-LOG number to the D2 entry; close its open details: who assesses the earlier runs, the scope (the five sources), the count-only operator | **Principal** | — |
| 2 | Appoint the D1 reviewer and the human tester | **Principal** | — |
| 3 | D1 review: spec-first, add tests, old-code failure harness, verdict | **D1 reviewer** | 2 |
| 4 | Fix the reviewer's findings; re-issue hashes and count | Session B (author) | 3 |
| 5 | Signed non-root fake-fixture test | **Human tester** | 3, 4 |
| 6 | Record the gate as passed | **Principal** | 1, 3, 5 |
| 7 | Count-only check; assessment of the earlier runs | Session B, as the named operator | 6 |
| 8 | Read the five primary sources; check the assumptions; O-3 | Session B | 6, 7 |
| 9 | Appoint and brief the D6 reviewer | **Principal** | — |
| 10 | External review, adjudication of U-1, literature checks | **External reviewer** | 9 |
| 11 | Integrate the review; D4 revision; v0.5 approval | Session B, then **Principal** | 10 |
| 12 | Stage-1 addendum (O5-12, D10-1, O-10a) | Session B | O-10a internal review |
| 13 | O-10b…e, O-1′, O-6a–d, E3′ finite case, concurrent writers, O5-12 extensions, E2 with ≥ 5 states, R4(b) second implementation | Session B (synthetic only) | — |
| 14 | Define 𝔇_crit (D9) and the recorded outcomes and retention (D10) | **Stakeholders**, via the Principal | — |
| 15 | Map theory → requirements → bounded contexts | Session B proposes; **Principal** decides | 8, 10, 14 |
