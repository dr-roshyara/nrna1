# D2: written clarification request to the rule owner

| Field | Value |
|---|---|
| To | The owner of the rule in **G-LOG-0141 item 7** ("What stays forbidden: measurement data, ledgers, **sealed lists**, hold-out files, Run-3, studies and commits"), routed by the Principal |
| From | Session B (Professor), S-Series theory lane |
| Date | 2026-10-17 19:29 (clock) |
| Status | **REQUEST.** No answer is assumed. Until a written answer is recorded, nothing in this request is acted on. |

## 1. The conflict to resolve

Two items of G-LOG-0141 (Principal-recorded, governance log l.2220, with its extension at l.2230) appear to pull in different directions:
- **Item 2** authorizes a **script-side filter**. That filter can only work by consulting the seal (the hold-out file list and the label list) inside the script.
- **Item 7** forbids "sealed lists".

The Principal's decision D2 (2026-10-17) pauses every use of sealed lists in scripts until this is clarified.

## 2. Disclosure

On 2026-10-17, before D2 was paused, Session B ran two filters that consulted the sealed lists **inside the script**:
- `corpus-access/discovery_filter.py`;
- `corpus-access/corpus_filter.py`.

Nothing sealed was displayed or written. Only allowed paths and counts were output. Session B understood this use as authorized by item 2. **Whether that use was permitted is part of the question below.**

## 3. Questions (please answer each in writing)

| # | Question | Options |
|---|---|---|
| Q1 | Does item 7 forbid a script from **reading** the sealed hold-out file list and label list in memory, when nothing sealed is printed, stored or returned? | (a) Forbidden · (b) Permitted under conditions (please state them) |
| Q2 | If (b): which conditions apply? Examples: integrity verification via the pinned hashes; counts and yes/no outputs only; a reviewed script; a named operator; a log entry per run. | free text |
| Q3 | Were Session B's runs of 2026-10-17 (§2) within the rule? | (a) Yes · (b) No: remediation required (please state it) |
| Q4 | May the **count-only source-ID check** (1752 report §4.2) be run after the safety review passes? It outputs: the number of allowed families quoting a hold-out source ID; yes/no for the 6 families already opened; yes/no for the 4 documents already read. | (a) Yes · (b) No · (c) Only by someone other than Session B |
| Q5 | If in-script use is forbidden: which alternative safe mechanism is acceptable, if any? Examples: a pre-computed allowed list produced by the seal custodian; reading only material the custodian has cleared; no corpus reading at all. | free text |

## 4. Consequences of each answer (for the decision, not a recommendation of an answer)

| Answer | Consequence |
|---|---|
| Q1 (a) and Q5 = custodian-produced allowed list | Session B never touches the seal. The custodian runs the reviewed filter and hands over an allowed list. **The safest separation of duties.** |
| Q1 (a) and Q5 = none | No corpus reading is possible. The theory stays unconnected to KnowledgeOS evidence, and Gate D cannot be reached. |
| Q1 (b) | The reviewed, patched filter may run under the stated conditions. Gate D becomes reachable once the D1 review passes. |
| Q3 (b) | The listed remediation is carried out before anything else. Earlier allowed lists are treated as untrusted. |

## 5. What Session B does meanwhile

- No filter runs and no use of sealed lists.
- No primary-source reads.
- D1 work continues on **fake fixtures only**: the specification, the patched code and the test suite.
