PROPOSED / NOT RATIFIED; materials only; not executed

# KC-1 external encoding: answer sheet (one per encoder)

Save as `responses/<encoder_id>.md`. **Section A is filled by the coordinator, never by the encoder, and is never shown to the encoder.** Section B is the encoder's reply, pasted **verbatim** (for a model: the complete final reply; for a human: the submitted text, unedited). `score.py` reads the first ```json block under "## B. Encoder response".

## A. Coordinator record

| field | value |
|---|---|
| encoder_id | (e.g. E07; assigned from the allocation list, PROTOCOL.md §6.3) |
| arm (from the allocation list; not shown to the encoder) | T3 / SH / PARSE / PARSE_B / GEN |
| packet code sent | (letter in the packet title) |
| sha256 of the sendable body sent | (must equal the `#sendable-body` line in FREEZE-MANIFEST.sha256) |
| freeze commit id | (git commit made by the human before any encoding) |
| identity class | human / model |
| model: provider, family, exact model id, version or snapshot date | (e.g. "provider X, family Y, model id Z, snapshot 2026-mm-dd"); must not be Claude-family |
| model: interface and settings | API or UI; temperature / top-p as used; system prompt = none; tools, browsing, retrieval and memory = off; fresh context |
| human: background | formal logic or set notation: none / some / professional |
| familiarity screen (PROTOCOL.md §6.2) | prior exposure to KnowledgeOS, PublicDigit, T1/T2/T3 or KC texts: yes / no; if yes, describe |
| cluster (correlation group) | model family, or human cohort (e.g. same lab / same briefing) |
| sent (UTC, ISO 8601) | |
| reply received (UTC, ISO 8601) | |
| human: time spent (minutes, self-reported) | |
| raw transcript file and sha256 | `raw/<encoder_id>.txt` (full transcript incl. the packet as sent) |
| replacement of | — or the encoder_id this sheet replaces (PROTOCOL.md §6.5) |
| deviations | anything that departed from PROTOCOL.md (e.g. a follow-up message, a timeout) |

## B. Encoder response

### B1. JSON object (verbatim)

```json
{"R6": [], "R16": [], "R80": [], "R21": [], "R11": [], "R79": [], "R15": [], "R9": [], "R17": [], "R1": [], "R14": []}
```

(The empty lists above only show the row ids in packet order; replace the whole block with the encoder's JSON, unedited. If the reply's JSON is malformed, paste it unchanged; the scorer marks the sheet invalid, PROTOCOL.md §6.5.)

### B2. Reason lines (verbatim, one per row, packet order)

```
R6:
R16:
R80:
R21:
R11:
R79:
R15:
R9:
R17:
R1:
R14:
```

### B3. Parts of the rows the encoder treated under rule 6 (verbatim)

```
R11: part "…" -> (letter), read / not read; …
R14:
R15:
R16:
R17:
R21:
```

### B4. Anything else the encoder wrote (verbatim)

```
```
