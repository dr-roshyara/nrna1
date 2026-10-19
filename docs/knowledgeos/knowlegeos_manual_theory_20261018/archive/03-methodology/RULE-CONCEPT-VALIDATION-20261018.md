# Rule CV-1: every concept gets a concept ID and a validation status

| Field | Value |
|---|---|
| Status | **APPROVED** by the Principal, 2026-10-18, with two statuses (in-session, verbatim): "approve CV-1 with two statuses: validated and invalid" |
| Register | `METHOD-VERSIONS.md`, entry 21 (earlier PROPOSED versions: entries 19–20); §2a added under entry 22 |
| Purpose | when the Professor reads a source and understands a concept, it also **validates** the concept, gives it a **concept ID**, and marks it **validated** or **invalid** |
| Relation to M-04 v1.0 | makes step 4 ("check that it is true, not just that it is written") visible **per concept**. It removes no obligation of M-04 v1.0 |

## 1. What counts as a concept

A **concept** is a named idea the theory uses: a term together with its meaning (e.g. *authority*, *current authority*, *standing*). Statements *about* concepts (claims, arguments, findings) stay in the idea register as ideas; they are linked to the concepts they use.

## 2. When it applies

After reading each source, for every concept the source introduces, defines, changes or relies on:
1. **Already registered?** Search the concept register by term and meaning. If the concept exists, use its ID; add the new source and update its status if the new evidence changes it (section 5). Do not create a second ID for the same concept.
2. **Not registered?** Create a new concept ID.
3. **Same word, different meaning?** That is a different concept: give it its own ID and record the clash.

## 2a. Several concepts for the same thing

*Principal, 2026-10-18 (verbatim): "if there are multiple concept for the same thing, each concept should be recorded in the corresponding chapter."*

When sources give **different concepts of the same thing** (e.g. two different definitions or models of "authority"):
1. **Each concept gets its own ID.** They are not merged, and one is not silently chosen over the others (one-page method, step 8: keep valid alternatives).
2. **Each is recorded in its corresponding chapter:** the chapter whose subject the concept belongs to. Its `book_location` points there, and that chapter explains it. If several belong to the same chapter, they are all explained there, side by side.
3. **Each is validated on its own** (section 4). One can be VALIDATED while another is INVALID.
4. **They are linked.** Each row names the others in `same_subject_as`, so a reader of one chapter can find the alternatives in another.
5. **They are told apart by name.** Each gets a qualified term (e.g. "authority (grant model)" and "authority (role model)"), so the same bare word never points to two concepts.

## 3. The concept ID

- Format **`CON-NNNN`** (e.g. `CON-0001`), numbered in order of creation, never reused, never renumbered.
- The prefix `CON-` is chosen because `C-n`, `KC-n` and `CE-n` are already in use in this project.
- The ID is shown in the book where the concept is defined, e.g. *current authority* `[CON-0012 · validated]` or `[CON-0013 · invalid]`.

## 4. The validation status (exactly one per concept)

| Status | Meaning | Required record |
|---|---|---|
| **VALIDATED** | checked, and all four checks below pass | what was checked and the result |
| **INVALID** | not validated, for any reason | the **reason**, as one of: *refuted* (checked and wrong or incoherent) · *partly valid* (some checks pass, some fail) · *not verifiable* (cannot be checked with the authorized evidence) · *not yet checked*; plus the evidence, and what would settle it |

The reason is kept in the `basis` field so that, as rule 3 of M-04 requires ("unknown is an answer"), "wrong", "uncheckable" and "not looked at" stay distinguishable inside the single status INVALID.

**VALIDATED requires all four checks:**
1. **Source fidelity:** the source really says it (lines cited; quotes machine-checked).
2. **Definition:** the meaning is clear enough to tell what falls under the concept and what does not.
3. **Consistency:** it does not contradict an established concept, axiom or result in the book, or the contradiction is resolved and recorded.
4. **Support for its kind** (step 4 of M-04):
   - logical or mathematical: derivation re-checked, at least one counterexample attempt recorded;
   - about software: checked against code, configuration or records, with the coverage stated;
   - about decisions or rules: the governing record found, its standing (proposed, exploratory, adopted, superseded) stated;
   - empirical: observations with their population and period.

A concept that passes 1 but not 4 is **INVALID** (reason: partly valid): source fidelity shows the source says it, not that it is true.

## 4a. Not fully valid: mark it, record a TODO, continue

*Principal, 2026-10-18 (verbatim): "if the concept and ideas are not valid compeletely, we still continue by marking it as invalid and put it into the todos also."*

- **The work never stops on an invalid concept or idea.** The Professor marks it and moves on to the next concept or source.
- **Not completely valid counts as INVALID.** A concept or idea that passes only some of the four checks is marked INVALID; the parts that hold and the parts that fail are stated in `basis`.
- **Every INVALID concept or idea goes into the TODOs.** It is entered in the `TODO.md` of the chapter where it is used, with:
  - its concept ID (or idea ID);
  - the status INVALID and its reason;
  - what is missing or wrong;
  - what would settle it.
- **The TODO entry is closed** only when the status changes to VALIDATED (section 5), and the closing reason is recorded.
- This applies to **ideas** as well as concepts: an idea-register row whose verification fails, fully or in part, is marked INVALID in its `verification` column and also gets a TODO entry.

## 5. Status changes

- A status changes only for a reason: new evidence, a new derivation, a demonstrated contradiction, or a review finding.
- Each change is **appended** to the concept's history (previous status, new status, date, reason, evidence). Nothing is overwritten.
- An INVALID concept stays in the register. If it was rejected, replaced or materially revised, it also gets an entry in `06-theory/CONCEPT-EVOLUTION.md`.

## 6. The concept register

One table, one row per concept, with these fields:

| Field | Content |
|---|---|
| `concept_id` | `CON-NNNN` |
| `term` | the name |
| `meaning` | one-sentence meaning, plus a pointer to the defining passage in the book (the book holds the full definition) |
| `sources` | S-IDs (or background file paths) with line ranges |
| `idea_ids` | links to the idea-register rows that state or use it |
| `book_location` | chapter file and section |
| `same_subject_as` | IDs of other concepts of the same thing (section 2a), or empty |
| `status` | `VALIDATED` · `INVALID` |
| `basis` | what was checked, by which kind of check, and its coverage; for INVALID, the reason (refuted · partly valid · not verifiable · not yet checked) |
| `checked_on` | date of the last status decision |
| `reviewed` | `self` · `independent` (by an AR-11 reviewer, with the review file) |
| `follow_up` | open question or `TODO.md` pointer |
| `history` | appended status changes |

## 7. In the book

- An INVALID concept is never written as established fact. The text says what is open, consistent with rule 1 of M-04.
- Undefined or disputed concepts also go into the chapter's `TODO.md` (D-C1 condition 5), with their concept ID.

## 8. Review

- The Professor's validation is a **self-check**, marked `reviewed: self`.
- At each five-file review, the independent reviewer (AR-11 standing approval) re-checks the concepts marked VALIDATED or INVALID in that group. Its result is written in the review folder (ML-2) and recorded as `reviewed: independent`.

### 8a. Second attempt by the independent reviewer for INVALID concepts and ideas

*Principal, 2026-10-18 (verbatim): "Independent reviewer tries to validate the concept if not valid but if the invedpent reviewer still fails , we continue but keep the concept/idea as invalid marking it with its id."*

1. **Every concept or idea the Professor marked INVALID** is given to the independent reviewer at the next five-file review, with the authorized evidence for it, and the reviewer **tries to validate it** using the four checks of section 4.
2. **If the reviewer validates it:** the status becomes VALIDATED, with `reviewed: independent` and the reviewer's basis recorded; the change is appended to the history (section 5) and its TODO entry is closed with that reason.
3. **If the reviewer also fails:** the work **continues**. The concept or idea **stays INVALID under its ID** (`CON-NNNN` or the idea ID), with `reviewed: independent` and the reviewer's reason recorded. Its TODO entry stays open.
4. The reviewer's attempt is written in the review folder (ML-2: the chapter's `review/`, or `06-theory/manuscript/review/` for all chapters).
5. A concept or idea that has failed both attempts is reopened only for a reason in section 5 (new evidence, a new derivation, a demonstrated contradiction, a review finding), not routinely at every review.

## Decided

- CV-1 approved (2026-10-18).
- Two statuses: VALIDATED and INVALID; the reason for INVALID is kept in `basis`.

## Still open (not decided by the approval)

1. ~~Where the register lives.~~ **Decided 2026-10-18** (register entry 23): the concept register is **`06-theory/CONCEPT-REGISTER.tsv`**, as an **exception to L-9** (Principal: "use 06-theory/CONCEPT-REGISTER.tsv as exception to L-9"). The exception covers this one file only. The idea register (`06-theory/IDEA-REGISTER.tsv`) stays frozen under L-9 pending D-R6, so the INVALID marking of idea rows in §4a is recorded, until D-R6, in the chapter `TODO.md` entry only.
2. **Existing chapters.** Either the concepts already in Chapters 0–2 get IDs in a one-off back-fill, or they get IDs only when a later source touches them (the "already done is an exception" pattern of ML-1).
