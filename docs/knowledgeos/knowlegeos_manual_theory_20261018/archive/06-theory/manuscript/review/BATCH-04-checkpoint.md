# Batch 4 checkpoint (2026-10-18)

*Authorized by the Principal ("the five-file batch is authorized, start after the review", 17:09Z). Method: `03-methodology/M-04-method-on-one-page.md` under M-04 v1.0. Authorized background only; no corpus material.*

## What the five sources contributed to the theory

| Source | Main contribution | Where |
|---|---|---|
| PC policy catalogue | E6 split (undecidable vs time-out vs unreadable); only proven-consistent opens a hard gate; **override** as a property, by recorded act only; context assembly filters, never elevates | Ch. 2 §2.8.8, §2.9; Ch. 1 §1.14.5, §1.17.5 |
| DR decision register | **what a question waits for before closure** (nothing, a prior decision, work, evidence); **closure itself is an act**; questions form a dependency graph; **answered ≠ closed** | Ch. 1 §1.4 |
| DE domain events | check and effect in one atomic change, which applies to authority checks too; kinds of event, audit ≠ domain event; **retention for as long as a pinned claim may be checked** (permanence needs a further policy assumption); an approval reference must come from its human author, not from the relying mechanism; an event may not enforce an invariant (EVT-1); absence of a record is not a fact (second domain) | Ch. 1 §1.16; Ch. 2 §2.8.8, §2.9 |
| AG artifact gap analysis | "governed state-and-analysis system" as a summary of the platform; relevance ≠ authority; **inferred relationships can leak** (the visibility bound is necessary, not sufficient) | Ch. 0 §0.2; Ch. 1 §1.6, §1.7 |
| SI script inventory | **enforcement-mode census** (only ARCH-INV-1 is "compiled", meaning one runtime guard on one path; read in the code, not tested); a ruled, recorded absence as a determinate E1; **silent fail-open** incidents and the fail-closed meta-rule (verified); one owner vs several mechanisms (duplication vs defence in depth) | Ch. 1 §1.9, §1.12, §1.16.1; Ch. 2 §2.8.8 |

## Changes to baselines

- **Chapter 1:** 13 revisions (R1-1 … R1-13), each with the five elements in `BASELINE-REVISIONS.md`. One was a **contradiction repair**: §1.17.5 claimed the book said nothing about read access, while §1.7 did.
- **Chapter 0:** R0-1.
- **After the Principal's checkpoint review:** R1-3a, R1-6a, R1-11a.
- **After the independent review (below):** nine Chapter 1 corrections and eight Chapter 2 corrections (R2-1 … R2-8), all derivation or traceability corrections, plus two preserved alternatives.
- **No foundational claim** of Chapter 1 was overturned by the batch; the revisions refine or add.

## Remaining material issues (tracked; none blocks the next batch)

1. **§1.14.1 formal model:** the defects F-1 to F-12 of the targeted review. A repair is proposed for this checkpoint; batch 4 supplied two inputs (recorded causation links; a recorded, guaranteed order of acts).
2. **Reserved decisions 1–6** and alternatives C1–C13: unchanged; none became blocking.
3. **Open questions from the sources:**
   - **OQ-25**, read access: now well specified by three sources;
   - **OQ-28**, partial support: already in code, absent from the governed vocabulary;
   - **OQ-24**, conflict blocking: made concrete;
   - **OQ-37 / OQ-38**: undefined or conflicting items in the sources.
4. **Platform facts relevant to the Principal (not theory work):**
   - the fail-closed meta-rule covers only some gates;
   - about 45 mechanisms have no declared owner;
   - two verdict vocabularies have no declared authoritative owner;
   - grant records are outside version control.

## Independent review of this batch

**Reviewer:** a fresh read-only session of the same model family, so it may share the lead's blind spots; not an external expert. It read the five sources in full, every book passage citing them, and four platform files. It could not read V3, IM, IA, AB, GA, RS, DR-AB or TR. It changed no file.

**Verdict: DEFECTIVE**, with 25 findings (4 high, 13 medium, 8 low or low–medium). The verdict rests on three overstatements, all now corrected:
- the SI census presented as the realization ladder, which §2.9 had withdrawn;
- "compiled / verified" for what is one runtime guard on one path;
- "E3–E6" against the book's own licensing rule.

**How the findings were handled.** The reviewer's code claims were re-checked in the code first (`Assessment.php` L38–42; `design-check.sh` L42–46; DR's OQ-1 count; V3 REV 1 at 11:36).

| Disposition | Findings | Where recorded |
|---|---|---|
| **accepted and corrected** | F1–F9, F11–F21, F23 | `BASELINE-REVISIONS.md`: R1-3b, R1-5a, R1-6b, R1-7a, R1-8a, R1-9a, R1-11b, R1-12a, R1-13a; R2-1 … R2-8 |
| **accepted, already corrected** | F10 (retention), and F5 in part | R1-6a, R1-3a (Principal's checkpoint review) |
| **accepted, already present** | F22(b), F22(c): "no covering grant is not a denial"; separation of duties as a fact about records | Ch. 1 §1.17.3 (from IA); PC repeats it; register B4-R-F22bc |
| **accepted (a)**, integrated | F22(a): pin the version of the deciding logic | R1-6b |
| **deferred (optional)** | F24: AG's deferral list and its internal tension | register B4-R-F24 |
| **explained, no contradiction** | F25: PC (10:34) and DR (10:40) still read V3 §3 as a "five names" failure, but both were written **before** V3 REV 1 (11:36), which withdrew it. The sources read the earlier V3; the book reads the revision | register B4-R-F25 |
| **rejected** | none | — |

**Two alternatives preserved, not decided:**
- **NOT_APPLICABLE** (F9): analysis inapplicable vs rules proven disjoint. AI recommendation: report the second as proven consistent by disjointness;
- **approval references** (F11): DE's machine-checkable rule vs the book's recorded-authorship rule. A trade-off, kept as two concepts.

**One open classification:** whether a ruled, recorded absence (F21) is a ninth kind of nothing or a recorded justification of E1. AI recommendation: the latter.

**Answers to the Principal's five questions** (the reviewer's reasoning, checked by the lead):
1. **Closure.** DR's four categories are **prerequisites**, not closers; the closing step is always an act. Prerequisites combine by conjunction and disjunction. Two closure rules: a decision closes by a valid act; a factual question by evidence that meets its kind's standard, recorded by an act. The roots block 7 questions, not 11 (§1.4).
2. **Pinning and retention.** Pinning requires retention only **for as long as the pinning claim must stay verifiable**. Permanence needs an extra assumption (indefinite challengeability), which a legal deletion duty, such as this product's voter-anonymity constraints, would contradict (§1.16.6, R1-6a).
3. **Who enforces an invariant if an event may not.** The aggregate checks before the event exists; the application service commits; publication follows the commit. Atomicity holds only within one transaction boundary. For conditions spanning aggregates, only pinning, detection or prohibition remain. While grant records sit outside version control, an authority check and its effect cannot share one atomic change. A repeated check closes the gap only if it is atomic with the effect (Ch. 2 §2.9, R2-7).
4. **"Elevation".** PC defines it per item. An assembly can still elevate by **inference** (a combination discloses a restricted relationship), **ranking** (first looks governing), **stripped or wrong labels**, and **existence signals** (counts, "withheld" markers, E8). So "filters, never elevates" is the **necessary** bound of §1.7, not a sufficient one. *Recorded as a finding for the §1.17.5 / OQ-25 work:* elevation should mean any increase in reachable inference or apparent standing, not only in item membership. Not yet written into the chapter; it is a definition on the may-read hypothesis, which stays open.
5. **What "compiled" and "verified" establish for K-17.** Only that an `Assessment` object cannot carry a reserved verdict: a runtime guard on one path, read in the code, not executed. No compile-time guarantee, and not the general invariant (§1.9, R1-11b).

**Material effect on the theory.** No foundational claim changed. One identification that had been withdrawn returned unnoticed (realization = enforcement) and is removed again. One property was misnamed (evaluability → required consistency guarantee). Two derivations were overstated (OQ-24 recasting; override independence). No reserved decision was touched.

**Look-back result.** The batch-4 additions were integrated faster than they were checked against the book's own earlier distinctions. Three of the four high findings were contradictions with the book itself, not with the sources. That is why the independent challenge of derivations, not only of source fidelity, earns its place.

**Batch 4 is closed.** Nothing found blocks the next batch. Still awaiting the Principal: the §1.14.1 formal repair (proposed, not authorized); reserved decisions 1–6; alternatives C1–C13; the next batch.
