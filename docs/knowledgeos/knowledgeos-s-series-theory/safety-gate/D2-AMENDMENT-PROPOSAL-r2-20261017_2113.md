# D2 amendment proposal, revision 2 (for Principal review)

| Field | Value |
|---|---|
| Prepared by | Session B, 2026-10-17 21:13 (clock) |
| Supersedes | `D2-AMENDMENT-PROPOSAL-G-LOG-0146-20261017_2109.md` (kept unchanged as history) |
| Status | **DRAFT FOR HUMAN REVIEW.** Nothing is written to the governance log. Preparing this revision is not approval, and it authorizes nothing. |
| Checks performed | read-only: the governance-log numbers, a reproducible fingerprint, cross-references, and G-LOG-0141 / 0143 / 0144. No protected material, filter or check was run. |

## 1. Problems corrected from r1

1. **Identifiers.** r1 used both "G-LOG-0146" and "G-LOG-0146-A", which was ambiguous. Now:
   - **G-LOG-0146** is the original authorization;
   - **G-LOG-0147** is this amendment.

   The original text and its "01xx" heading stay untouched.
2. **Fingerprint.** r1 gave a line-based hash. It is now **text-based** and reproducible however the log shifts (Appendix B).
3. **Source scope.** Sources 4 and 5 (gap theory, v1.1) were named by **topic**, not uniquely by ID. They are now **reserved and blocked** until a separate written act names their source IDs. Only three sources are in scope.
4. **The "already-read" set** was undefined in r1. It is now fixed as an **over-inclusive list**, so that the check cannot miss something merely because the read log is imperfect.
5. **Detection claims.** The statement of what the check **cannot** detect is extended: it does not cover influence through Session A's summaries and handoff documents.
6. **G-LOG-0141's broader scope.** G-LOG-0141 and its extension permit reading all discovery files and families. r1 did not say how that relates to the new limits. Now stated: **the narrower rules govern.**
7. **"Studies" (G-LOG-0141 item 7).** r1 did not say whether the count-only check is a "study". It is now classified as an **access-control safety check**, and the Principal is asked to confirm this.
8. **Logging** now includes **purpose**, as G-LOG-0141 item 3 requires.
9. **"Added to the withheld set."** r1 implied someone would act on result (a). Filter r4 already withholds any family that quotes a hold-out ID, so no human action is needed.

## 2. Decision note (business language)

**What you are deciding.** You have already decided that only I may read research documents, through a reviewed safety filter, and only after the safety gate passes. This amendment fixes the remaining details so that nobody can misread the permission later. **It grants nothing new.** It only narrows and clarifies.

**The numbering, in plain words.**
- Your original permission becomes **G-LOG-0146**. Its text stays exactly where and as it is, still headed "G-LOG-01xx".
- This clarification becomes **G-LOG-0147**. It is added at the end of the log and tells readers: "the entry headed G-LOG-01xx is G-LOG-0146."
- An older entry, G-LOG-0143, already refers to "the unnumbered entry". That now means G-LOG-0146.

**The rules.**

| # | Rule | What it means in practice |
|---|---|---|
| 1 | **Three sources, now** | I may read only three named documents (Appendix A), and each only if the approved filter allows it |
| 2 | **Two sources on hold** | Two further documents (the gap theory and the v1.1 derivation) are known only by topic, not by a unique ID. They stay **blocked** until you name their IDs in writing |
| 3 | **Narrower rules win** | Your earlier, broader permission (G-LOG-0141 and its extension) does not reopen anything. Reading is governed only by G-LOG-0146 and G-LOG-0147 |
| 4 | **Who does what** | I run the approved check and read. An independent reviewer reviews the filter. A separate human runs and signs the test. **You alone** decide the gate and rule on the earlier runs |
| 5 | **The earlier runs** | Assessed by the count-only check, but **only when you separately authorize it** after the gate. Then you rule. Until then, the old results are untrusted |
| 6 | **Stop rule** | If either yes/no answer from the check says "yes", I stop all research reading at once, report to you, and do nothing further on it without your written permission |
| 7 | **Logging** | Every check and every reading is recorded: what, when, why, under which permission, and with which filter version |
| 8 | **Not the gate** | Approving this amendment does **not** pass the safety gate, and permits no reading |

**What the count-only check can and cannot tell you.**
- **It reports:** (a) a count of allowed summaries that quote a protected document's ID; (b) yes/no: is any of them a summary I already read? (c) yes/no: does any document I already read quote such an ID?
- **It can:** catch protected documents **referred to by their ID** in what I read.
- **It cannot catch:**
  - paraphrased or edited references, or references without the ID;
  - influence carried through Session A's summaries or handoff notes;
  - anything outside the "already-read" list.
- **So a clean result is reassuring, not proof** that protected material never reached me or never influenced a finding.

| Result | Meaning | Your decision |
|---|---|---|
| (b) and (c) are "no" | No ID-level sign of overlap in what I read | Close the question, or ask for more assurance |
| (a) > 0, while (b) and (c) are "no" | Some summaries I never read quote protected IDs. The filter already withholds them | As above |
| (b) or (c) is "yes" | Possible overlap | I have stopped. You decide what happens next |

## 3. Paste-ready amendment (for the governance log, only after explicit approval)

> ## G-LOG-0147 — Human act: completion of the Session B corpus authorization (designated G-LOG-0146): numbering, scope, roles, method for the earlier-run assessment, stop rule, logging
>
> | Field | Value |
> |---|---|
> | Decider | the human project owner (Principal). Wording drafted by Session B at the Principal's request; approved by the Principal on [date and time] |
> | Designation | The entry headed "# G-LOG-01xx — Principal Authorization for Session B: S-Series Corpus Research" is designated **G-LOG-0146**. Its text is not edited. Text fingerprint: sha256 = `db22416969a637671090c1531ac87d1230a3a7afe8b12d92d2da47a3e9643dc9`, computed as in Appendix B. The reference in G-LOG-0143 to "the unnumbered 'G-LOG-01xx' entry" means G-LOG-0146. |
> | Answers | D2: the clarification of G-LOG-0141 item 7, as set out in G-LOG-0146 §2. This entry completes G-LOG-0146 §3.4, §4, §6 and §7. |
> | 1. Governing scope | Session B's corpus reading is governed by G-LOG-0146 and this entry only. The broader reading scope of G-LOG-0141 item 1 and its extension is **not** a basis for any reading. |
> | 2. Permitted sources | Only the three sources of Appendix A, rows 1–3, each only if the approved filter marks it permitted. Rows 4–5 are **reserved and blocked** until a separate written act of the Principal names their source IDs. Any other source requires a separate written act. |
> | 3. Roles | **Operator** of the approved filter and of the count-only check: Session B. **Filter reviewer:** an independent reviewer who is not the author. **Human tester:** a separate person who runs and signs the fake-fixture test as non-root. **Gate decision, and ruling on the earlier runs:** the Principal only. |
> | 4. Earlier runs: method only | Method: the count-only source-ID check. Outputs: (a) the number of allowed families quoting a hold-out source ID; (b) yes/no, any family in the already-read set among them; (c) yes/no, any document in the already-read set quoting one. Nothing else is output. **Already-read set:** every item ever entered in `READ-LOG.tsv` (including items logged but not opened, and partial reads), together with the Session A artifacts listed in `SOURCE-REGISTER.md` that Session B has read. **This entry does not authorize running the check.** Running it requires a separate act of the Principal after the gate passes (consistent with G-LOG-0143). The Principal rules on the result. Until the ruling, the outputs of the earlier runs are untrusted. |
> | 5. Classification | The count-only check is an access-control safety check, not a "study" within G-LOG-0141 item 7. *[The Principal confirms or strikes this row.]* |
> | 6. Stop rule | If (b) or (c) is "yes", Session B stops all corpus research immediately and reports to the Principal. There is no further investigation of the overlap without the Principal's written authorization. |
> | 7. Known limits | The check detects quoted hold-out source IDs only. It does not detect paraphrased or ID-less references, or influence through Session A summaries or handoffs. **A clean result is not proof** of no access or no influence. |
> | 8. Logging | Every authorized check and every permitted reading is appended to `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/READ-LOG.tsv`. Each entry records: date and time; purpose; the authorizing entries (G-LOG-0146, G-LOG-0147, and the run-specific act); the approved filter version and its sha256; the operator; output counts (for checks) or source ID and full or partial read (for readings). |
> | 9. Not the gate | This entry authorizes **no** reading, filter run, count-only check, filter change, unsealing, or use or identification of hold-out material. The safety gate remains BLOCKED until the conditions of G-LOG-0146 §3 are met and the Principal records it as passed. |

### Appendix A: sources

| # | Source | Status under G-LOG-0147 | Note |
|---|---|---|---|
| 1 | **S2373**: KnowledgeOS theory v1.0 | permitted, if the filter allows it | ID unique in the source registry. That it *is* theory v1.0 is source-reported via Session A's family, to be confirmed on reading |
| 2 | **S2763**: theory v2 rewrite (core operations) | permitted, if the filter allows it | same note |
| 3 | **S2871**: the "Adequacy ≠ Realization" witness | permitted, if the filter allows it | same note |
| 4 | the primary source of the gap theory v1.0 | **reserved; BLOCKED** | ID not known to Session B without a further read. Needs a written act naming the ID |
| 5 | the primary source of the v1.1 derivation | **reserved; BLOCKED** | same |

### Appendix B: reproducible fingerprint of G-LOG-0146

The command, run from `docs/knowledgeos/chronological-read/`:

```
sed -n '/^# G-LOG-01xx — Principal Authorization for Session B/,/F-Series integration\.$/p' P3B-GOVERNANCE-LOG.md | sha256sum
```

| Item | Value (21:13:11) |
|---|---|
| Lines matched | 74 |
| Heading occurrences | 1 |
| sha256 | `db22416969a637671090c1531ac87d1230a3a7afe8b12d92d2da47a3e9643dc9` |
| Next free numbers | 0146 and 0147 are unused; the highest number in use is 0145 |

## 4. Consistency check (read-only)

| Requirement | Source | Status | Evidence |
|---|---|---|---|
| The Professor alone may read; other sessions stay under O1 | G-LOG-0141 items 1 and 6; G-LOG-0146 §1, §5 | ✔ consistent | Rule 3 names Session B only. No other actor gains permission. |
| A script-side filter; the reader never sees the sealed list | G-LOG-0141 item 2; G-LOG-0146 §2 | ✔ | Rows 3 and 4: operator plus filter; outputs are counts and yes/no only |
| Logging with hash, time **and purpose** | G-LOG-0141 item 3 | ✔ (fixed in r2) | Row 8 |
| Findings are observations only | G-LOG-0141 item 4 | ✔ unaffected | not touched |
| The Professor has no neutral roles (measurement, audit, linkage judging, predictions) | G-LOG-0141 item 5; G-LOG-0144 (3) | ✔ | The amendment gives Session B no such role. The operator role is an access-control check |
| Sealed lists, hold-out files, studies and commits are forbidden | G-LOG-0141 item 7 | **⚠ needs confirmation** | "Sealed lists": clarified by G-LOG-0146 §2 (in-memory use only). **"Studies":** row 5 asks the Principal to confirm that the check is not a study |
| G-LOG-0141's broad scope (all discovery files and families) vs the new limits | G-LOG-0141 extension; G-LOG-0146 §4 | **⚠ resolved only if row 1 is approved** | Row 1 states that the narrower rules govern. Without it, the two entries could be read as conflicting |
| The earlier-run assessment only under its own separate act | G-LOG-0143 precondition (2) | ✔ (fixed in r1/r2) | Row 4: method only; execution needs a separate act |
| G-LOG-0143's other preconditions (the reviewer's session id; the design of containment controls) | G-LOG-0143 | ✔ not affected | not touched; still need their own acts |
| The pre-reading-writer audit list includes this workspace | G-LOG-0144 (2) | ✔ noted | Affects the choice of reviewers (see §6), not this amendment |
| D1 review, signed human test and Principal gate before any reading | G-LOG-0146 §3 | ✔ | Row 9; the gate is BLOCKED |
| No new permission is created | the task brief | ✔ | Every row narrows or clarifies. The "Not authorized" list is explicit |
| No ambiguous responsibility | the task brief | ✔ | Row 3 names each role; the Principal holds both final decisions |
| The stop rule allows no further investigation | the task brief | ✔ | Row 6 |
| A clean result is not presented as proof | the task brief | ✔ | Row 7 and the decision note |

## 5. What remains blocked, and the next separate authorizations

**Blocked:** all corpus reading, all filter runs, the count-only check, sources 4 and 5, and the gate itself.

**Separate acts needed, in order:**
1. Approval of this wording, then entry of G-LOG-0147, by the Principal.
2. Appointment of the D1 reviewer and the human tester.
3. After a passing review and a signed test, **the Principal's gate act**.
4. **A separate act** authorizing the count-only check (the earlier-run assessment).
5. The Principal's ruling on its result.
6. Optionally, **an act naming the source IDs** for rows 4 and 5.

## 6. Recommendation to the Principal

**Recommendation: approve r2, provided you decide two points first.**
1. **Row 5 ("studies").** Confirm that the count-only check is an access-control safety check and not a "study" under G-LOG-0141 item 7. If you do not confirm it, strike row 5. The check would then need its own clarification before it could ever run.
2. **Row 1 (governing scope).** Confirm that G-LOG-0141's broader reading scope is no longer a basis for reading. This is the main point that prevents contradictory readings later.

**No other unresolved issue prevents approval.**

**Practical notes:**
- The governance log currently has uncommitted changes. Committing it is your separate decision.
- Under G-LOG-0144, a reviewer who accesses this workspace becomes ineligible to draft predictions or Kind-P candidates later. Consider this when appointing reviewers.
