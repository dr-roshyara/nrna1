# SD follow-up 1 — routed findings, the decision-(1) application, and T10-16 (b)

*Lead DDD Architect, part 1 (review only). Items 2–4 of the Principal's commission after turn 5. Every element is at S0. The Professor's rejections are reported, not re-argued. Nothing here decides.*

## Baseline

| Item | Content |
|---|---|
| Date | 2026-10-29 |
| Run | new review (follow-up to SD1–SD4, commit `514068d6d`; sha256 as in `SHA256-SD-REVIEWS.txt`, all verified OK at the start of this run) |
| Theory and design baseline | Ch. 10 at `dca72c57f` (turn 5 settled; the decision-(1) application `f8f6bbb2f` settled with it): `10.02`, `10.03`, `10.04`, `10.05`, `TODO.md`; `10.04` also at `f8f6bbb2f` (L6, count line) and `87fbfa2ed` (for the diff) |
| Decisions | decision record at `382fa6d85`: T10-10 (1) (10:00:44Z); routing A, B, C, D (10:12:39Z); T10-16 (b) (10:46:12Z, **decided, not yet applied to Ch. 10**) |
| Standards | as SD5 |
| Scope | item 2: packages A and C (B waits for the G1 turn; D is kept for the stage-1 package). Item 3: SD4 §5's predictions against the application. Item 4: one line per affected finding on T10-16 (b). Excluded: the settlement file `review/SD5-PUBLISHED-LANGUAGE/SETTLEMENT.md` (a review folder; never opened). Where §10.5 says "weighed in the settlement", only the settled Ch. 10 text and the TODO are used |

## Item 2 — routed findings: resolved, rejected with a reason, or open

**Package A (into turn 5's settlement)**

| Routed finding | Disposition | Where (at `dca72c57f`) | Note |
|---|---|---|---|
| **A1** — rec. 2: one published answer carrying both values, so that "unaltered" is a duty attached to it | **Partly rejected with a reason; the rest left open.** *Rejected:* a joint answer cannot make **alteration at display** preventable, only **omission at delivery**, since "no producer's step can prevent a consumer from altering it". *Open:* the joint answer depends on gap G1 and on a join the book does not state (Q1 keyed (k, x, t, s, u), Q4 (k, s, g), per claim). Its form is "a question for after the G1 turn". Today the display duty is an **assumption on consumers, not a guarantee** | 10.05 PL-14 L261–271 | reported, not re-argued |
| — SD1-02 (core subdomain with no enforcing context) | **Left open, now stated in the book**: "no context's operation enforces row C, by §10.3.2's own definition of an enforcer" | 10.05 L263 | — |
| — SD3-03 (operation question for row C) | **Left open**, as A1 | 10.05 L263–270 | — |
| — SD4-04 (R17 draws D, a role, as an upstream participant) | **Left open, unchanged.** R17 still reads "D → U (informs)", and §10.5.3 calls the working context and pin "D's content" | 10.04 L69; 10.05 L280 | the role/participant point was not addressed |
| **A2** — rec. 3 with SD2-02, SD4-01: the record and its kind list as an explicit shared element (owner; the act that adds a kind) | **Resolved in the map and the language; owner put to the Principal and since decided.** PL-0 and R0 added (R0 typed "conforms", every area); the act that adds a kind recorded as book history (every kind added by the Principal's decision); owner as T10-16, now decided **(b)** | 10.05 PL-0 L58–68; 10.04 R0 L52, §10.4.4 L130; TODO T10-16 L21 | SD2-02's "two models in candidate 1" is resolved by T10-16 (b) **once applied** (item 4) |
| **A3** — rec. 5, SD4-06: R18 as an ACL owned by the receiving side | **Option as recommended rejected with a reason; the boundary left open.** Reason: the receiving sides (catalogue, norms) are not contexts under T10-10 (1), so the ACL "would have no owner of record". The book names an alternative, **(b) translation as acceptance** (product facts enter only as kinds whose acceptance rule is the translation), as "the option the book's own mechanism supports". Its kinds (O1: registration, link) are not admitted, and their owner depended on T10-16. **Neither is adopted; T10-14 open** | 10.04 L152–156; TODO T10-14 L19; 10.05 L328 | reported, not re-argued |

**Package C (into the Ch. 10 TODO; after the G1 turn, before part 2)**

| Routed finding | Disposition | Where |
|---|---|---|
| **SD3-01** — owner assignments against the "information to reject" test and R-a | **Left open, scheduled**: "after the G1 turn, before tactical design" | TODO T10-17 L22 |
| **SD2-01** — evidence for the own-language column (a glossary per candidate) | **Left open, scheduled**: "after the G1 turn" | TODO T10-18 L23 |
| **SD3-04** — the inventory lacks the applications' own rules | **Left open, scheduled**: "qualify, or inventory their rules" | TODO T10-19 L24 |
| **SD1-01** — classify the derivation rule | **Left open, scheduled**: "the G1 turn states the rule, the classification follows" | TODO T10-20 L25 |

**Package B** (SD4-02): waits for the G1 turn, as routed. For the record only: §10.5 now states it with the Governance Engineer's qualification ("Within one kernel such an entry is refused at append … the case arises on merged records or bypass") and names it "the G1 turn's question (routed finding B4)" (10.05 L235).

## Item 3 — the decision-(1) application (`f8f6bbb2f`, settled at `dca72c57f`) against SD4 §5

| SD4 §5 prediction | What the settled text does | Match |
|---|---|---|
| §10.3.6: T10-10 decided (1); options (2), (3) closed; (3) returns only via G1 | "Decided (T10-10 …)" with the question kept as the record (10.03 L300) | **matches** |
| Norms "not admitted today", T10-10 dropped from the reason | 10.02 banner L5; 10.03 L290, L298, L320; 10.04 L16–17 | **matches** |
| Catalogue: only route is kinds of its own; G10/G11 remain, T10-10 drops | status "not admitted today" (10.02 L5; 10.03 L321; 10.04 L17), **marked as derived by the Professor**, since the decision names standing and norms only | **matches, with one difference**: the text goes further than I predicted ("not admitted today", not just "doubtful and pending") and says the step is derived. **§10.4.4 L127 still reads "the catalogue doubtful and pending (G10, G11)"**, which is stale against L17 and 10.03 L321 |
| §10.4.4 row 2: content half answered "no"; only G11 remains | "G11: … (T10-10 decided (1): content outside the record does not count)" | **matches** |
| §10.4.4 rows 3 and 5: T10-10 removed | row 3 now "kinds of its own (G11) and G10"; R21 row "G11 and G1" | **matches** |
| R6: T10-10 removed; R6 becomes the expected shape for evidence | R6's condition is now "G1" only | **matches** |
| Decision record 09:36:01Z consequence line superseded on norms | stated in 10.03 L300 | **matches** |
| Count "16 of 21" probably unchanged | at `f8f6bbb2f`: still **16 of 21** (L6, L130). At `dca72c57f`: **17 of 22**, solely because turn 5 added R0 | **matches** |
| *(not predicted)* | §10.4.3 L100: "depends on T10-10 and G11" became "**and on G11**", a grammatical remnant ("That needs … and on G11") | **missed by SD4 §5**; editorial only |
| *(not predicted)* | R9, R10, R11, R16, R18's own condition cells: T10-10 removed (10.04 diff) | covered by my row-2 line in substance; not itemised |

## Item 4 — T10-16 (b) (the record and its kind list owned by no area): likely effect per affected finding

*One line each. Not a review of the application, which does not exist yet.*

- **SD1-03** (row 1 bundles CE\* semantics with record mechanics): **likely resolved at the context level.** The record-keeping rules leave standing. The **subdomain class of the new element** (core, supporting or generic) is then unstated in §10.1.
- **SD2-02** (candidate 1 holds two models): **likely resolved**: standing keeps "what counts", and the record's rules go to the element.
- **SD3-01** (owner by concept list, R-a inapplicable): **partly changed.** For the record's rules, an amending-act holder now exists (an authorised act outside every area), so R-a becomes applicable there. Elsewhere it is unchanged.
- **SD3-02** (row A mixes standing semantics with record integrity): **likely resolved**: laws 9a, 29, 30, the designation rule and G6 should move from row A to the element. Row A will need re-reading.
- **SD3-05** (carrier-change gate enforced by an act outside every candidate): **likely resolved**: the gate's home becomes the element, whose changes are acts outside every area.
- **SD4-01** (shared kernel undrawn; owner and adding act): **resolved by the decision**; the application should update R0's authority cell and PL-0.
- **SD4-02 / B** (a W-rule breach refuses every standing answer): **likely unchanged in substance.** (b) changes who may change a kind's clause, not the effect of (d3) on a merged record.
- **SD4-03** (R12, unowned inputs): **not affected.**
- **SD4-05** (admission decided by whoever extends standing's schema): **largely resolved**: "no area can admit a context". **The criteria** for the outside act that adds a kind remain unstated (the G1 turn's kinds are "proposed kinds", one question for the Principal).
- **SD4-06 / A3** (R18): **likely affected**: option (b), translation as acceptance, loses one stated obstacle, since the owner of O1's kinds is no longer standing by default. The kinds are still not admitted.
- **SD5-01** (record's language mixed into standing's): **resolved in direction by the decision**; it requires §10.5.2 to be split when applied.
- **SD5-05** (whose acceptance rule governs `designate`): **created by reading T10-10 (1) and T10-16 (b) together**; the application should settle it, or it comes back as one question.

## Files opened and commands run

As in SD5 §5 (one run). **Not opened:** the SD5 settlement, its reports or any other review-folder file, corpus sources, `00-access/`, `CONCEPT-REGISTER.tsv`, `THEORY-MAP.md`.
