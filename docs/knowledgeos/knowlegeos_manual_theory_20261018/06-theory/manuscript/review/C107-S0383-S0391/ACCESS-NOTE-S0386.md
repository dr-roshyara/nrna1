# C107: S0386's allowed-list status is "changed" (stop (d), put to the Principal)

*2026-11-03. The Professor.*

- **Fact.** `CORPUS-ALLOWED.tsv` lists S0386 with sha256 `c5abdae5822a49ba1cb4f9128663135daece23078f16ddcaf0d2274224c4aeee` and disk status **`changed`** (one of two such rows in the list; the other is S2444). The file on disk has sha256 `95c6113986727b2071b3e3bd6ce5af6a7378726725d7b05613e43965783d2a74`, which is the version the Professor read and recorded in the READ-LOG (2026-11-03T04:20:54Z) and which reviewer WA read.
- **Not noticed at reading.** `close_source.py` reported "allowed-list PASS" for S0386; it checks listing, not the disk status. The Professor did not check the status column. WA found it (`STEP1-INVENTORY-A.md`, opening paragraph).
- **What differs is not known.** The recorded version is not accessible without git history, which the lane does not use. One visible sign on disk: a stray "aa" at L10.
- **Use in the book.** §8.101 (draft, `9879a379b`) cites S0386 in C4 (L15, L36, L42, L52, L67, L79–85, L105) and its lineage; nothing else in the book cites S0386.
- **Held:** C107's step 2 (both commissions) is not sent until the Principal rules.
