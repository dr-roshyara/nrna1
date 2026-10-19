# Window C16: step 8 (dependent chapters), strength scan, book search; §1.12 integration checks

*2026-10-19. Sources: S0087, S0088, S0089, S0090 (in order, full); S0091 (topic-full in C9, checked against §8.4 and §9.5). New: Ch. 8 §8.9 (C1–C9), register CON-0150–0152. Changed: Ch. 6 §6.7 C1 (one bullet, S0090 L57). Step 2 (integration) of Ch. 1 §1.12 under Rulebook §4, entry 52a.*

## Book search (top hits read)
| Query | Hits read | Relation |
|---|---|---|
| "absence" | CON-0120 / §8.4 C3; §1.16.8; §6.4 C2; §0.2; CON-0059 | §8.4 C3 same subject (C3 extends it); §6.4 C2 same family; the rest different |
| meaning: "absence of a record is not absence; exhaustive search" | CON-0120; §8.4; CON-0059; CON-0083; CON-0081 | §8.4 (C3, C4, C5) is the home: S0091's own account of the incidents S0089/S0090 verify |
| meaning: "content assessed independently of producer; accountability needs a declared producer" | CON-0122, CON-0131, CON-0005, CON-0121, CON-0030 | none the same; CON-0131 (subject-blind check) related; home taken as §1.12 and PT-001 |
| "provenance" | §1.2 (CON-0016); §1.14.4 | different (provenance of a claim, not accountability of a work product) |
| "recusal", "non-registration", "shared boundary", "observation point" | nothing found by this search | — |

## Step 8: dependencies of §8.9
| Relies on | Status | Check |
|---|---|---|
| §8.4 C3 (CON-0120 VALIDATED), C4, C5 | reviewed C9 | C3 and C5 extend; nothing changed in §8.4 |
| §8.6 seven factors (CON-0125 PROVISIONAL) | **unverified** | C2's Ded uses "authorship of the protected object" only |
| §1.12 (integrated in this window) | see below | C1 extends 1.12-2 |
| §6.4 C3; §6.5 C2 roles law; §6.6 C8 direction law (Int) | PROVISIONAL / **unverified** | C4, C9, C7 extend; no result changed |
| §9.6.6a K3 (Principal's act) | act | C7 reads S0087 as an instance of the shape, Hyp |
| §5.6 what an act covers | reviewed | C8 extends |
| §8.8 C2 | PROVISIONAL | C6 instance |
| §6.7 C1 (changed here) | reviewed C15 | the added bullet is later evidence, labelled as such |

## Strength scan (§8.9)
9 strong words in the book's own text; all scoped or inside a stated Int/Ded; "a correction holds only where the reader reads" restated before review. Quotes 35/35 after correcting S0088 anchors (a combined listing had offset them by 44 lines) and S0089 L91.

## §1.12 step 2 (integration), formatting checks (`tmp/tools/integration_check.py`)
- revision tags in the prose: **0**; INVALID markers outside "what does not hold": **0**;
- claim numbers kept: **yes** (1.12-1 … 1.12-4);
- length: 1081 → 1233 words (diagnostic: the growth is the further evidence added to 1.12-2);
- quotes 2/2 (S0095 L141, S0090 L233).
- Old text: `01.12-OLD-before-integration.md` in this folder. Change-log row: `chapter_01/01.99-change-log.md`, last row.

**Old → new, by claim (for the reviewer):**
| No. | Old head | New head | What changed |
|---|---|---|---|
| 1.12-1 | The idea (Src, DR-AB L9) | A report of an act is not the act (Src, DR-AB L9) | head states the claim; one sentence restating the quote's point |
| 1.12-2 | Refinement of §1.2 (Ded) | A record does not make the act happen: verifiable is not valid (Ded, from §1.2 and 1.12-1) | further evidence added: S0095 L141 (the act's occurrence had to come from outside the record), S0090 L233–234 (content vs accountability); marked supporting, status unchanged, with what it does not show |
| 1.12-3 | Generalization (pilot 2, file 5; Ch. 2 §2.8.7) | Every self-report is a claim about an event, to be evidenced (Ded; with Ch. 2 §2.8.7) | revision tag removed; the step from 1.12-2 made explicit; 1.12-4 named as its evidence; the separate "**Ded.**" sentence folded into the head label |
| 1.12-4 | Evidence from the platform: silent fail-open (Src, observed; SI …; added R1-12) | The platform's enforcement failed silently: declaration diverged from behaviour (Src, observed; SI …) | revision tag removed; the paragraph split into reading and cure; incident 3's parenthesis moved into its own sentence |
| header | form-rewrite header (C15), lineage "added at the rewrite" | sources and status line | revision history moved to the change log |
| 1.12.99 | "Nothing is stated as withdrawn …; corrections in the change log" | "No claim of this section has been refuted." | same content, without the revision pointer |
