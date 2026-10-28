# Window C35: settlement

*2026-10-28. Reviewer W (Opus). Report: `REVIEW-REPORT.md`, saved verbatim (sha256 in `SHA256-REPORT.txt`: `18a62f7a…7c0dc`). Draft `47b5e0c6f`; brief `d9751afc8`. Changed theory reviewed: `756b31612` (the C34 settlement, model v20). Rulebook v1.4.*

"V (Professor)" marks evidence the Professor re-checked. "R" marks evidence accepted on the reviewer's reading.

## Findings and dispositions

| W-nn | Sev. | Professor's check | Disposition | Where applied |
|---|---|---|---|---|
| W-01 | high | **V (Professor):** code read. The owner of a new store is the mover (`store_owner`), and the guard requires the mover to be the old store's owner, so by induction the owner is constant along a chain | Accepted. The limit is stated: per-store and per-record ownership coincide until owner transfer is modelled. Check 32 (c) relabelled (a label only; output otherwise unchanged; checks 1–31 identical to v19). **No new model version.** Whether the Principal's rule intends cross-application moves after a transfer is a protocol question for T9-11, not decided here | §9.17 R3 (c); `ce_model.py` docstring and label; T9-11 |
| W-02 | high | R (probe P4 as reported); premise already stated in v20 | Accepted. (c′) is qualified: detection holds "if the mover declares its own application". §8.28 C2 is restated: S0190 is a direct estate instance of that premise failing | §9.17 R3 (c), (c′); §8.28 C2 |
| W-03 | high | **V (Professor):** S0186 L14 ("the system historically called PKS / EKS") | Accepted. C1 gains the EKS/PKS-independence premise and "a litmus test is not a placement" (CON-0105). Routed to T0-03b | §8.28 C1; Ch. 0 TODO T0-03b |
| W-04 | high | R | **Accepted as a reasoned exclusion.** The candidates are listed for T0-03b and not assessed in a window section: assessing them means applying the three boundary questions, which is a theory turn and needs the Principal (consolidation B-10) | §8.28 C1; T0-03b |
| W-05 | high | **V (Professor):** S0188 L630 ("rather than … ephemeral conversation state") | Accepted. C8 now reads as two senses of "durable". "A statement against the record" is INVALID as stated | §8.28 C8 |
| W-06 | normal | **V (Professor):** §1.13 L54–63 (AMS 08-16; the recount of 142 grants, 0/144) | Accepted. C4's population paragraph is restated: the figures are a stale relay of AMS, and the record is the same one, later | §8.28 C4 |
| W-07 | normal | R | Accepted. The narrowing claim is attributed to the source. The lane-binding liveness question goes to CON-0151 / F32 | §8.28 C3 |
| W-08 | normal | **V (Professor):** §8.27 L45 | Accepted. The sentence is removed | §8.27 C2 |
| W-09 | normal | R | Accepted. Three occurrences from two relays, one line | §8.28 C3 |
| W-10 | normal | R | Accepted. Identity: "the estate reports none". Acceptance: "none reported in the sources read" | §8.28 C2 |

**Coverage items applied:**
- S0190's mutation-owner rule (ownership passes only at START), as estate evidence of a transfer mechanism for a different owner (T9-11, with the homonym noted);
- S0188's unnamed "second mechanism" (C5);
- S0189's single demonstrated run (C6);
- the two senses of "KnowledgeOS" in S0189 (T0-03b).

**Recorded only:** S0187's Decision "core of the kernel" vs "above the kernel" (L291–305, L637) and the S0191 matrix tick against "idle since 5 August". They are for T0-03b.

**Claim verdicts (reviewer):**

| Claim | Verdict |
|---|---|
| C1, C2, C5, C8 | PROVISIONAL, restated |
| C3, C6, C7 | VALIDATED |
| C4 | VALIDATED within scope; its population paragraph INVALID as stated, restated |
| v20 owner check | VALIDATED within scope (one owner per record; declared application; presence-only attestation) |
| (c′) quotation and T9-10 met | VALIDATED |

## What this settlement establishes, and what it does not

- **Established (V):**
  - v20's owner is constant along a chain (code);
  - v20's checks 1–31 give output identical to v19 after the relabel (`diff`);
  - S0186 names "PKS / EKS" as one historical system (L14);
  - S0188 defines "durable" against conversation state (L630).
- **Not established:**
  - whether PKS and EKS are one system;
  - any kernel candidate's placement;
  - an owner check that does not rest on a declared application.

## R3

- **§8.28 C1, C2:** the inbound lines in §8.29 (L21, L52) and §8.30 (L19, L48, L53) cite the distinctions that remain ("the kernel owns the mechanism…"; "no gate reads identity"). They stay true.
- **§9.17 (c′):** inbound lines in §9.13.2 and the map stay true; the restriction narrows, it does not reverse.

## Checks after settlement

| Check | Result |
|---|---|
| Quote check | §8.28 9/0; §9.17 7/0 |
| Model v20 | all assertions pass; checks 1–31 identical to v19 |

## Measures

| Measure | Value |
|---|---|
| Findings | 10: 0 critical, 5 high, 5 normal; all accepted (W-04 as a reasoned exclusion) |
| On the previous change (`756b31612`) | 3 (W-01, W-02, W-08); on §8.28, 7 |
| Professor, reading | 132 s by start/stop records (bookkeeping; understates) |
| Reviewer W | step 1: 157.6 s; steps 2–3: 564.6 s (harness) |
| Elapsed window time | from the first commit `47b5e0c6f` (2026-10-28T02:34:06Z) to this settlement's commit (see the session log) |
