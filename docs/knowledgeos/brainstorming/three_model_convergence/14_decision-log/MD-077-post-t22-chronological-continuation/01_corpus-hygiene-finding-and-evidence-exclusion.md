# MD-077 §01 — Corpus-Hygiene Finding: 14 Self-Referential Files (`EKS-31`, Occurrences 3–16)

## The 14 files

All 14 sit inside the post-T22 (mtime `>= 2026-09-06 10:00`) population of
`mathematical_ideas_that_can_be_implemented/`, all with non-standard, generic, or malformed filenames
(several without a `.md` extension, several truncated to their own first sentence — a filesystem
pathology consistent with an accidental save rather than deliberate corpus authorship):

| File | Lines | Opens with |
|---|---:|---|
| `document1.md` | 572 | "I performed this as a completion audit of the historical F4/Model-B semantic backbone... I excluded `three_model_convergence/`..." |
| `document2.md` | 581 | near-duplicate of `document1.md`, same structure/verdict |
| `document3.md` | 703 | "Targeted Completion Audit — Historical F4/Model-B Semantic Backbone" |
| `document4.md` | 550 | "Yes. I searched the recent F4/Model-B reconstruction work in the conversation... especially MD-059, MD-060 and MD-061" |
| `document5.md` | 229 | Q&A-style, sharpens document4's "seven gaps" |
| `document6.md` | 621 | "Targeted Completion Audit," decisive answer "None of A–D is exactly correct" |
| `documents7.md` | 520 | "I have now reconstructed the requested boundary narrowly from the corpus, including... MD-061, MD-062, and the completed MD-063 reconstruction" |
| `handover_verdict` | 334 | "I treated MD-058 and MD-059 as the frozen handover boundary..." |
| `historical_source.md` | 843 | "I performed this as a historical-source audit, not as a continuation of MD-059 theory construction" |
| `Untitled-17.md` | 797 | "Yes. I searched the historical conversation/library material... including the newly completed MD-061 material" |
| `# Historical Evidence-Recovery Report.md` | 850 | "I treated the attached document as the research commission... I therefore searched the historical KnowledgeOS corpus" |
| `# F4 Handover Audit — post-MD-059.md` | 590 | "I treated MD-058 and MD-059 as the frozen handover boundary and did not use MD-059's same-day external re-analysis as independent corroboration" |
| `I conducted the targeted evidence-extrac` (no ext.) | 1271 | "I conducted the targeted evidence-extraction pass against the available KnowledgeOS corpus, including... MD-023/054/057/058..." |
| `I treated the uploaded file as the gover` (no ext.) | 939 | "I treated the uploaded file as the governing research instruction and performed the requested historical evidence-recovery pass... I excluded `three_model_convergence/`" |

All 14 filesystem-timestamped **2026-09-09 13:xx–16:47**, i.e. inside this same reconstruction's own
MD-058→MD-063 execution window (MD-058 written 13:28–13:31; MD-059 13:45–13:53; MD-060 15:30–15:34;
MD-061 15:44–15:47; MD-062 16:39–16:41; MD-063 17:10–17:12, all same calendar day). Several files
explicitly name MD-058/059/060/061/062/063 by number and describe excluding
`three_model_convergence/` from their own search — i.e. they are describing **this reconstruction's
own governed decision-log directory as an exclusion zone**, which no independently-authored primary
corpus document from before this reconstruction began could possibly do.

## Classification

**These are not primary corpus theory documents.** They are first-person, AI-generated audit/
meta-commentary produced **during this reconstruction's own MD-058–063 phase work**, saved into the
primary-evidence corpus directory (`mathematical_ideas_that_can_be_implemented/`) instead of the
governed decision-log (`14_decision-log/`) — apparently by a parallel or intermediate process during
that same working window. Their content substantially concerns MD-061's own `Sat*(K_t,r)` construction
(an explicitly non-corpus-native "Gate C — CONDITIONAL CANDIDATE," already distinguished by this
reconstruction's own object-identity discipline from the T21 canonical `Sat(K,r,Γ)=Det_r(EvalReq(...),
EC)` formula this current mission investigates) and pre-existing MD-058–063-era findings, not new
corpus-native theory content.

## This is not a new problem — it is `EKS-31`, occurrences 3 through 16

`EKS-31` was first filed in MD-059 ("a same-day, MD-058-consuming external file was found saved into
the primary corpus directory with no provenance marker — a corpus-hygiene risk for future timestamp-
thread sweeps") and extended once already in MD-060 ("a second same-day, MD-059-consuming external
file was found... EKS-31 extended, occurrence 2"). **These 14 files are the identical phenomenon**:
same-day, same-directory, MD-05x-consuming files with no provenance marker, discovered here for the
first time at bulk scale (14 files, not 1–2) because this mission's own post-T22 mtime-based population
scan is the first to systematically enumerate this directory's full contents by timestamp rather than
by filename pattern. Per this reconstruction's own standing discipline (never file a second ticket for
an already-tracked problem class), **no new backlog ticket is filed** — this finding extends `EKS-31`'s
own known scope and severity, recorded here forward, `EKS-31`'s own original text unedited.

## Evidence-exclusion rule applied

Per the mission's own governing principle ("the source establishes lineage; never infer ancestry from
chronology alone") and this reconstruction's own repeated precedent for excluding non-primary material
(MD-072 excluded `three_model_convergence/`'s own scaffolding from its census as "circular, not corpus
archaeology"; every phase from MD-052 onward has kept admissibility and content strictly separate),
**all 14 files are excluded from the post-T22 evidence base for the current mission's central §8
question.** Two independent, sufficient reasons: (1) they are not corpus-native — they are this
reconstruction's own recycled commentary about its own earlier work, not evidence of "what the theory
itself did next"; (2) even read at face value, their subject matter is predominantly the earlier,
already-distinguished `Sat*`/MD-061 branch, not the T21 `Sat(K,r,Γ)` formula this mission tracks —
conflating the two would violate this reconstruction's own standing object-identity discipline (never
merge objects merely because of shared vocabulary).

## Verification that this finding does not contaminate any frozen artifact

Checked directly (`grep`, this session): none of `MD-067`'s Theory Evolution Graph, `MD-068`'s three
registries, or `MD-069`'s `TheoryState` timeline contain any of these 14 files' own distinctive
vocabulary (`Accept_r`, `Req_Σ`, `Gate C — CONDITIONAL`, the `L0/L1/L2` layering discipline,
"Requirement Interpretation / Evaluation Boundary," "component-membership") — **zero hits**. MD-067's
own 876-file queue-driven traversal did not ingest this content; no frozen `TheoryState` entry rests on
it. No correction to any frozen artifact is required.
