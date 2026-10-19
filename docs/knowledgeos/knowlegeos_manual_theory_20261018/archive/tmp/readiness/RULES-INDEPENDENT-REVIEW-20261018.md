# Independent review of the methodology rules (03-methodology) — 2026-10-18

| Field | Value |
|---|---|
| Subject | all rule-bearing files in `03-methodology/`, plus `tmp/readiness/RULE-REVIEW-DECISION-BRIEF-20261018.md` and `00-access/ACCESS-RECORD.md` |
| Method | three separate read-only reviews: the main session's own reading; Reviewer A (operational rules and internal logic); Reviewer B (governance, authority, versioning, access). The two reviewers worked independently of each other. Findings below are consolidated; the two blocking facts on corpus access and chapter edits were re-checked directly (file times and the chronological read log) |
| Standing | **advisory research document, AI-generated.** It recommends; it decides nothing, approves nothing and changes no rule, access or authorization. Decisions stay with the Principal |
| Changes made | none to any reviewed file. This document only |

## Overall verdict

**The rules in `03-methodology/` cannot all be followed at once right now.** The root cause is the agreement "i agree the rules" (`PRINCIPAL-DECISION-RECORD-RULES-20261018.md:3`). It approved the original method rules AR-1…AR-12 and the later instructions L-0a…L-15 together, "as in force". But the brief it approved (`tmp/readiness/RULE-REVIEW-DECISION-BRIEF-20261018.md:175–183`) itself lists nine open conflicts between exactly those rules (CF-1…CF-9). Those conflicts are now conflicts inside the approved rule set, and nothing says which rule wins. All three reviews reached this independently.

The hashes in `METHOD-VERSIONS.md` all match the files that still exist, so nothing was changed after it was registered.

## Blocking: real actions are affected

**1. Corpus reading restarted without the approval the rules require.** Checked directly.
- The brief says: "approving the rules (A) does **not** open the corpus" (`brief:260`). It asks for decision D-C1 to be written down by the Principal, including acceptance of the remaining hold-out risk (`brief:257`).
- L-15, approved as in force, says: do not resume "until … access is verified" (`brief:146`).
- The approved step 1 still says "the main corpus is still locked… I work only on permitted background documents" (`03-methodology/M-04-v1.0-approved-baseline.md:17`).
- Four minutes after the agreement, the researcher read "you have your own rules" as lifting the gate. The same record had just warned "do not interpret a recommendation as an authorization" (`PRINCIPAL-DECISION-RECORD-RULES-20261018.md:27`).
- The chronological read log shows S0008 was opened at **21:45:00Z** (`tmp/corpus-reading/READ-LOG-CHRONO-20261018.tsv:25`).

**2. Chapters 0–2 were edited while the chapter freeze was still in force.** Checked directly.
- L-6 says "do not … edit existing chapters" and L-9 says "do not modify the manuscript". Both are approved as in force.
- The decision about writing into the chapters (D-R5) is explicitly "not acted on until confirmed" (`PRINCIPAL-DECISION-RECORD-RULES-20261018.md:21`).
- Even so, CH-02 changed at 21:45:50Z, CH-00 at 21:48:01Z and CH-01 at 21:49:35Z (file modification times, UTC).
- The addendum's "the manuscript directory … is authorized" does not say whether it lifts L-6 and L-9.

**3. There is no precedence rule.** The Principal named the whole folder as "your rules" (`PRINCIPAL-DECISION-RECORD-RULES-20261018.md:37`), but its files have very different standing:
- M-00 says "the binding text are in M-01 … M-02 … M-03" (`M-00:7`), yet M-03 calls that method "PROPOSED. Not adopted".
- The trial protocol and the efficiency review are set aside or are proposals, but they still contain checklists that could be followed.
- No file says which document governs when two of them disagree.

## Major contradictions

| # | Rule A | vs | Rule B |
|---|---|---|---|
| 4 | Step 7: check across all chapters after **every file** (`baseline:68`) | | L-3: "no repeated full-book scans" (`working-practice:52`). The working practice already applies the narrower check "in proportion" (`:85`), yet the pending change E-3 that would allow it is still PROPOSED (`METHOD-VERSIONS.md:17`) |
| 5 | Step 6: the register records source, label, how it was checked, and where it sits in the book (`baseline:49`) | | "Light record": idea, source, what was done, open question (`one-page:14`; `working-practice:30`). The label, the check and the book location are all dropped |
| 6 | A separate adjudicator settles disagreements (`baseline:80`); M-03: "the adjudicator must not be me" | | "The lead investigates the disagreement" (`one-page:75`) |
| 7 | Independence: the reviewer reads the sources *before* seeing my notes (`baseline:80`) | | The reviewer "gets the claims and the evidence" (`working-practice:37`). Those claims are the lead's notes, so the reviewer is not blind |
| 8 | Review cadence: every 5 files, plus a blind sample every 100 corpus files (`baseline:73,80`) | | Re-review "only after a foundational change" (`working-practice:40`) or "only after a material change" (`one-page:76`): two different triggers, both overriding the fixed cadence |
| 9 | AR-11: no reviewer receives corpus material without separate approval; "no corpus content to sub-agents" (`PRINCIPAL-DECISION-RECORD-RULES-20261018.md:50`) | | The AI reviewer and the ChatGPT chapter review get "the evidence" and whole chapters (`06-theory/review-packets/CH-01-chatgpt-review-brief.md`). Since batch C-1 the chapters contain corpus-derived text, so the ChatGPT review would send corpus content to an outside service with no access record |
| 10 | Four incompatible label vocabularies: says / verified / deduced / hypothesis (rule 1, `baseline:89`) · [EV] [I] [H] [?] [NEW] (M-00) · Src/Ind/Int/Ded/Hyp (A-1, PROPOSED but already used in the manuscript) · established / disproved / conditional / hypothesis / unresolved (one-page) | | The register uses 16 status values, none of them the six dispositions of step 6a (`M-04-efficiency-review-20261018.md` §3) |

**11. The working practice and the one-page method are amendments in practice.** Both say "not an amendment". But they:
- drop obligations of the approved method: the full register, the three-way source/register/book check of 6a, the cross-chapter check of step 7, the "continue, correct or pause" decision, and the 100-file blind re-read;
- add new obligations nobody approved: "valid alternatives" as step 8, and seven obligations per claim.

Their register status, "in use", is not one of the four statuses (PROPOSED, APPROVED, REJECTED, SUPERSEDED) that A-1(h) allows. This is the path for changing the method without approval that the register was meant to close.

**12. The record is read selectively.** The four words were taken as approving the baseline (D-R1), but not D-R2…D-R10 from the same decision sheet. If the agreement covers L-3 and L-5, it also quietly approves E-3, which entry 11 says it does not cover.

**13. The approved baseline contains stale facts.** "Corpus locked", "audit of Ch. 0–2 running now" (the audit is finished: `06-theory/manuscript/chapter_02/AUDIT-ch00-02.md`, `AUDIT-DISPOSITION-ch00-02.md`) and "three chapters written" are still in the approved text, and step 1 is now being cited as the condition that *unlocks* the corpus.

## Minor

- **Undefined thresholds:** "material", "foundational" (defined only in A-1(f), which is not approved), "sensible points", "in proportion", "meaningful idea", "important claim", and "restricted corpus" vs "main corpus" vs "locked".
- **Register already runs on unapproved rules:** `METHOD-VERSIONS.md` operates under A-1(h)'s rules (Principal decides only, append-only) while A-1 is still PROPOSED.
- **Wrong verification command:** `METHOD-VERSIONS.md:35` says use `diff -u`, but the stored diff only reproduces with `diff -U1000`.
- **Unverifiable hashes:** the earlier file versions behind entries 4, 7 and 9 were overwritten, so those hashes cannot be checked.
- **One person controls the whole record:** the researcher reconstructs, hashes, registers, interprets the Principal and self-certifies "not an amendment", in an untracked, editable workspace.
- **Stale checklists:** the efficiency review §7 and the trial protocol still require line-coverage checks and non-propositional line lists, which L-3 dropped.
- **Unclear owner of the ChatGPT review:** it is called a ChatGPT review in one place (`working-practice:38`) and "the Principal's chapter review (ChatGPT)" in another (`one-page:74`), so it is unclear whose act it is and what it may receive.
- **Decision-record header out of date:** it says "Recorded … in `tmp/`. Repository writes are still frozen" (`PRINCIPAL-DECISION-RECORD-RULES-20261018.md:8`) but the file now lives in `03-methodology/`.

## Suggested improvements, in order

1. **Pause corpus integration at S0008 and stop sending chapters to ChatGPT** until the Principal answers D-C1 in their own words: range, exclusions, accepted hold-out risk. Mark the C-1 chapter additions PROPOSED until D-R5 is decided.
2. **Add a precedence clause** to `METHOD-VERSIONS.md`:
   - (1) recorded Principal acts, where a later act overrides an earlier one only if it says so;
   - (2) M-04 v1.0;
   - (3) working practice and one-page method, as interpretation only;
   - (4) everything else informative.

   Stamp M-00…M-03, the trial protocol and the efficiency review "not rules".
3. **Replace the three overlapping rule documents** (working practice, one-page method, M-00) with a single "rules in force" page. Pass it as a numbered amendment (v1.1) with a hash and an explicit Principal decision. Each line either keeps a v1.0 obligation or names the change to it. That one approval settles E-3, the register weight (D-R4) and the cadence (D-R8).
4. **Write one controlled vocabulary** per dimension (comparison, disposition, epistemic mark, claim status) with a mapping table, and say which fields are mandatory per register row.
5. **Write one definition of independence and one disagreement rule:**
   - independence means blind to the lead's claims and dispositions, with model and session separation stated;
   - for corpus material, decide D-R7 (a named reviewer with their own access record), use an external adjudicator, and limit the AI reviewer to background material;
   - until then, mark corpus-derived text "not independently reviewed".
6. **Fix the cadence** in one table: five-file book review, 100-file blind sample, and an ad-hoc re-review using one defined trigger, A-1(f)'s "foundational" test, approved on its own.
7. **Approve an editorial v1.0.1** that updates step 1's condition and the stale status lines.
8. **Make the record trustworthy:** put the workspace under version control (D-R9), keep a snapshot per register entry, and fix the `diff` command.

Several of these are decisions only the Principal can make (D-C1, D-R4, D-R5, D-R7, D-R8, D-R9, E-3). They should be asked for item by item, not read out of a short agreement.

## Limits of this review

- It reviews the rules and the records, not the correctness of the theory in the chapters.
- File modification times show *when* chapters changed, not *what* changed; the content of the C-1 additions was not reviewed here.
- The reviewers are AI sessions of the same model family; their agreement is not independent confirmation in the strong sense the method itself warns about (rule 2).
