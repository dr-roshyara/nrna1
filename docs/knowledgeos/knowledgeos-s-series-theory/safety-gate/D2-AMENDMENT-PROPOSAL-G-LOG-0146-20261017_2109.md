# D2: finalizing the sealed-list authorization (for Principal approval)

| Field | Value |
|---|---|
| Prepared by | Session B, 2026-10-17 21:09 (clock) |
| Status | **PROPOSAL. Nothing has been written to the governance log.** It is entered only after the Principal explicitly approves the final wording. |
| What it amends | the unnumbered entry "G-LOG-01xx — Principal Authorization for Session B: S-Series Corpus Research" (the governance log, currently from l.2233) |
| Proposed number | **G-LOG-0146**: verified as the next free number (the highest number in the log is G-LOG-0145; checked read-only at 21:09) |

---

## Part 1. Decision note (business language)

### What you are deciding

In October you authorized me, and only me, to read KnowledgeOS research documents through a safety filter. The filter keeps the protected "hold-out" documents out of my reach. That authorization leaves four practical details open. This amendment closes them **without changing what you already decided**.

### The rules, and what each means in practice

1. **The authorization gets a permanent number (G-LOG-0146).**
   *In practice:* everyone can cite it, and every log entry about a check or a reading can point to it. An existing entry (G-LOG-0143) already refers to it as "the unnumbered entry", so the amendment records both names.
2. **Only five named sources may be read, and only if the approved filter permits each one.**
   *In practice:* I read only the five documents the theory most depends on (listed in the appendix). If the filter blocks one, I do not read it. Anything beyond these five needs your separate written authorization.
3. **Who does what.**
   - **I (Session B)** operate the approved check and do the permitted reading.
   - **An independent reviewer**, not me, reviews the filter.
   - **A separate human tester** runs and signs the filter test.
   - **You** decide whether the safety gate has passed, and you rule on the earlier runs.

   No one can approve their own work.
4. **A clear stop rule.** If the check gives any sign that something I have already read might overlap with protected material, I stop all research reading immediately and report to you. I do not investigate further unless you authorize it in writing.
5. **Everything is logged.** Every check and every reading session is recorded: what was done, when, under which authorization, and with which approved filter version. That way we can always reconstruct what happened later.
6. **The two earlier runs.** On 17 October I ran two earlier filter scripts before this rule was clarified. They are assessed by the count-only check, run **only when you separately authorize it**, after the safety gate has passed. You then rule on the result. Until then, their outputs are not trusted.

### What the count-only check can and cannot tell you

**What it reports:** one number and two yes/no answers.
- **(a)** How many of the allowed research summaries quote a protected document's ID.
- **(b)** Yes/no: is any summary I **already read** among them?
- **(c)** Yes/no: does any document I **already read** quote such an ID?

| Result | What it means | What you decide |
|---|---|---|
| (b) = no and (c) = no | No sign that what I read referred to protected documents **by ID** | Close the earlier-run question, or ask for more assurance |
| (a) > 0, (b) = no, (c) = no | Some summaries I have **not** read quote protected IDs | Those summaries are added to the withheld set; then as above |
| (b) or (c) = yes | **Possible overlap** with protected material | I stop. You decide remediation, which may include setting aside affected findings and an independent assessment |

**What it cannot establish:**
- It looks for protected **IDs** only. It would miss a protected document that is paraphrased, edited and moved, or referred to without its ID (residual risk R-1 in the filter specification).
- A clean result is **reassuring, not proof** that no overlap exists.
- It says nothing about whether the theory is correct.

### What this amendment does NOT do

- It does **not** authorize any reading, any filter run or the count-only check.
- It does **not** mean the safety gate has passed. The gate stays **BLOCKED** until:
  1. the independent filter review passes;
  2. the human tester signs the test;
  3. you record the gate as passed.

  After that, the count-only check also needs its own separate authorization (see below).

### Ambiguities and risks you should know about

| # | Item | Why it matters | Suggested handling |
|---|---|---|---|
| R1 | **G-LOG-0143 requires the earlier-run assessment to happen "only under its own separate act".** | My earlier draft would have authorized the assessment inside this amendment. That would conflict with G-LOG-0143. | This proposal **defines the method only**. Running it needs a separate act after the gate (rule 6). |
| R2 | **Numbering out of position.** The authorization sits between G-LOG-0141 and G-LOG-0142, but receives number 0146. | Readers may be confused by the order. | The amendment cites the entry by heading **and** by a fingerprint of its text. The original text is not moved or edited. |
| R3 | **The governance log has uncommitted changes.** The authorization and entries 0142–0145 are not yet in version control. | Line numbers can shift, and the "permanent" record is not yet permanent in git. | The amendment cites heading + fingerprint, not line numbers. Committing the log is your separate decision. |
| R4 | **The count-only check covers IDs only** (see above). | A clean result is not a guarantee. | The amendment states this limit, so that no one treats it as proof. |
| R5 | **G-LOG-0144** bars any session that has accessed this research workspace from drafting Kind-P candidates or hold-out predictions. | An external reviewer given access to the workspace would become ineligible for those roles. | Consider it when appointing reviewers. It does not affect this amendment. |
| R6 | **Five sources are named by ID, but two are named by topic only** (the gap-theory and v1.1 sources). Their exact files are identified only once the approved filter runs. | If a topic maps to several files, scope creep is possible. | The amendment limits each topic to **its primary source**. Any additional file needs your written authorization. |

---

## Part 2. Paste-ready amendment (technical; for the governance log after approval)

> ## G-LOG-0146 — Human act: D2 completion — numbering, scope, roles, stop rule and logging for the Session B corpus authorization
>
> | Field | Value |
> |---|---|
> | Decider | the human project owner (Principal), 2026-10-17. Wording drafted by Session B at the Principal's request; approved by the Principal on [date/time] |
> | Amends | the unnumbered entry headed "G-LOG-01xx — Principal Authorization for Session B: S-Series Corpus Research" (text fingerprint: sha256 of its 74 lines, from its heading to its last paragraph, = `db22416969a637671090c1531ac87d1230a3a7afe8b12d92d2da47a3e9643dc9`, computed 2026-10-17 21:09). That entry is hereby numbered **G-LOG-0146-A**. References to it as "the unnumbered G-LOG-01xx entry" (e.g. G-LOG-0143) mean G-LOG-0146-A. Its text is not edited. |
> | Answers | D2 (clarification of G-LOG-0141 item 7); completes G-LOG-0146-A §3.4, §4, §6 and §7 |
> | 1. Scope | Reading is limited to the five primary sources in Appendix A, each **only if the approved filter marks it permitted**. A topic-named source means its primary source only. Any other source requires a separate written act of the Principal. |
> | 2. Roles | **Operator** of the approved filter and of the count-only check: Session B. **Filter reviewer:** an independent reviewer who is not the author (D1). **Human tester:** a separate person who runs and signs the fake-fixture test as non-root (D1). **Gate decision and the ruling on the earlier runs:** the Principal only. |
> | 3. Earlier runs (G-LOG-0146-A §6) | The method of assessment is the count-only source-ID check (outputs (a) count; (b) yes/no on already-read families; (c) yes/no on already-read documents; nothing else is output). **This entry does not authorize running it.** Running it requires a separate Principal act after the gate passes, consistent with G-LOG-0143 ("each item only under its own separate act"). The Principal rules on the result. Until the ruling, the outputs of the earlier runs remain untrusted. |
> | 4. Stop rule | If (b) or (c) is "yes", Session B stops all corpus research immediately and reports to the Principal. No further investigation of the overlap without the Principal's written authorization. |
> | 5. Known limit | The check detects quoted hold-out source IDs only; a clean result is not proof of no overlap (filter SPEC residual risk R-1). |
> | 6. Logging | Every authorized check and every permitted reading is appended to `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/READ-LOG.tsv` with: date and time; the authorizing entry (G-LOG-0146 and the run-specific act); the approved filter version and sha256; operator; permitted output counts (checks) or source ID and full/partial read (readings). |
> | 7. Separation from the gate | This entry authorizes **no** reading, **no** filter run and **no** count-only check. The safety gate remains BLOCKED until the conditions of G-LOG-0146-A §3 are met and the Principal records it as passed. |
> | Not authorized | any corpus or source read; any filter run; the count-only check; any change to the filter; unseal; any use or identification of hold-out material |

### Appendix A: the five sources (scope of rule 1)

| # | Source | Purpose in the theory |
|---|---|---|
| 1 | **S2373**: KnowledgeOS theory v1.0 (definitions, axioms, theorems) | verify AX-1…AX-7 and the Zero theorem |
| 2 | **S2763**: theory v2 rewrite | verify the five core operations ASSERT, LINK, REVISE, RETRACT, ISOLATE |
| 3 | **S2871**: the "Adequacy ≠ Realization" witness (T-7) | verify the witness data and design |
| 4 | **The primary source of the gap theory v1.0** (the companion to S2373; its file is identified by the approved filter) | verify the Gap, Zero and contract-relative order definitions |
| 5 | **The primary source of the v1.1 derivation** (behavioural semantic equivalence; its file is identified by the approved filter) | verify the definition of behavioural equivalence |

### Appendix B: reference values (as of 21:09)

| Item | Value |
|---|---|
| Next free log number (verified) | G-LOG-0146 (the highest existing is G-LOG-0145) |
| Fingerprint of the amended entry | `db22416969a637671090c1531ac87d1230a3a7afe8b12d92d2da47a3e9643dc9` |
| Filter r4 (unreviewed; it may change after D1) | `kos_filter_v2.py` `4b60a02b0ad8ee03f37c05075632a7f661b4331f411af0b9afa7def6bd372dff` |
| Read log (current) | `corpus-access/READ-LOG.tsv` `1fc01e1058a5fe9ae9229f39f5e97d5471c76bb7a7b6b745f564de1c868d1101` |

### Ambiguities in the technical text

- **"G-LOG-0146-A" numbering:** an alternative is to give the authorization number 0146 and this amendment number 0147. I recommend the **single-number** form shown, so that the authorization and its completion are cited together. **This is your choice.**
- **The fingerprint** is valid only while the amended entry's text is unchanged. If the log is reformatted before entry, the fingerprint must be recomputed.
- **The approval date and time** field is left blank for you.
