# FIELD-INVENTORY: agent reachability of the R7 verifier path (builder A, read-only)

Scope: every key, enum, format, regex and cross-field rule that the R7 verifier path reads or requires on an
agent-authored artifact, or on an agent tool call, checked against the agent's I(run) text. I(run) is the rev3
contract, the R7 addendum, the plan and the slice/view. The dispatch prompt is agent-visible but outside I(run);
it is cited where it matters.

**Legend.** Code (all under `scripts/`): VR7 = `p3b_s5_r7_verify.py`, U = `p3b_s5_r7_universe.py`,
W = `p3b_s5_r7_witness.py`, E = `p3b_s5_r7_evidence.py`, C = `p3b_s5_r7_reconstruction.py`, V = `p3b_s5_verify.py`
(the historical gates, which also run on rev7 and are composed into U/E/R at V:1064-1085), r5 = `p3b_s5_r5.py`,
r6 = `p3b_s5_r6.py`, cm = `p3b_s5_common.py`, pr = `p3b_s5_prepare.py`, orch = `p3b_s5_r7_orchestrate.py`.
Contract: rev3 = `prompts/20260925_1204_p3b-agent-contract-r2.md`, R7 = `prompts/20260926_2400_p3b-agent-contract-r7-addendum.md`.
Artifacts: OBJ = `objects.jsonl` (the object record; the assembly copies it unchanged), REG = `register.jsonl`,
GAP = `p1-gap.jsonl` (assembled as `p1-gap-capture.jsonl`), FRR = `file-reading-records.jsonl`, CE = `claim-evidence.json`,
LINT = `S3-LINT.json`, CALLS = the agent's tool calls as witnessed. **[NEW]** = not named in DIAG-A or PKG-REVIEW-D.

**Status.** SPECIFIED = name plus value domain or format stated in I(run). NAME-ONLY = the name is stated but the value,
format or equality rule is not. CONTRADICTORY = I(run) states something the code rejects, or two I(run) texts
conflict. ABSENT = not in I(run). **Remedy** applies to non-SPECIFIED rows only. **v2** = package v2, `audit-p3b/20260930_S5-CANARY-EVALUATION-DECISION.md` §2 R1–R5.

## Inventory

| # | artifact | key / rule | code file:line | contract status + line | remedy | covered by v2? |
|---|---|---|---|---|---|---|
| 1 | OBJ | Exact key set: every rev3 §E `object_record` key is required (except `checklist_examined`) and no other key is allowed. Shapes: `[`→list, `{`→dict, `bool`→bool | V:478, 491-501 | SPECIFIED rev3:251-293 | — | — |
| 2 | OBJ, REG, GAP | `run_id` = `<B>-R7`; `batch_id` = B (G-07) | V:511-513, 955, 990 | SPECIFIED R7:126-130 (prompt orch:123-126) | — | — |
| 3 | OBJ, REG, GAP | **[NEW]** `contract_sha256` must equal the manifest entry's value, which is the **rev3** contract sha. OBJ `input_manifest_sha256` must equal the **slice's** `input_manifest_sha256` | V:511-513, 955, 990; slice check V:367-370 | CONTRADICTORY / NAME-ONLY. rev3:103 and :1311 name the fields without a value source. R7:122 binds "`contract.sha256` = sha of this addendum", which invites the addendum sha. R7:537-539 defines a different `input_manifest_sha256` (the RunInputManifest hash). The slice carries both correct values | EXPOSE ("copy both from the slice fields of the same name") or DERIVE (the assembly stamps them) | no |
| 4 | OBJ, REG, GAP | `model_id` ∈ {`claude-opus-5-5`, `claude-opus-5-5[1m]`}; `generation_parameters` key present | V:111, 126-127, 514-515, 956, 991; GAP via exact key set V:986 | SPECIFIED rev3:92-93, 288-289 | — | — |
| 5 | OBJ | `record_status`=PROPOSED, `proposed_by`=AI-AGENT, `evidence_presentation`=V1-PLUS-ROWS; no `status`/`STATUS`/`corpus_status` key | V:516-522 | SPECIFIED rev3:285-287, :90, :76-77. rev3:1316 also allows `ROW-BUNDLE-V2`; §B:90 governs | — | — |
| 6 | OBJ | `hub` is the slice's `hub` (bool identity) | V:502-503 | SPECIFIED rev3:263 | — | — |
| 7 | OBJ | `checklist_examined` is the full 1..23 iff the slice has `in_checklist`; otherwise empty or absent | V:504-509 | SPECIFIED rev3:91, 292 | — | — |
| 8 | OBJ | `escalations[]`: objects with keys {field, reason, detail} plus optional `hub_record`; `reason` in the closed enum | V:524-531 | SPECIFIED rev3:159-167, 275 | — | — |
| 9 | OBJ | A null `type_status`/`mathematical_status` needs an escalation whose `field` is exactly that field name, with reason SCHEMA-LIMITATION, plus a SCHEMA-LIMITATION register record (G-09) | V:535-545 | SPECIFIED rev3:88-89, 271-272 | — | — |
| 10 | OBJ | `escalations[*].field` that carries an S-id must be exactly `timeline[<S>].<order\|date_applies_to_file\|change_vs_previous>`, with S an own timeline point (else R7-U) | U:38, 306-326 | SPECIFIED R7:311, 317 | — | — |
| 11 | OBJ | LOAD only on hub labels. On a hub, each hit-bearing dimension needs `field`=`absences.<dim>`, reason LOAD, and `hub_record` = the slice's hub record (object, or a JSON string of it) | V:538-540, 781-791, 1123-1131 | SPECIFIED rev3:54-60 | — | — |
| 12 | OBJ | `type_status`, `mathematical_status`, `primary_layer`, `secondary_roles[]` and `tier` enums; `tier` = slice tier | V:542-553 | SPECIFIED rev3:109-135, 228-231, 261 | — | — |
| 13 | OBJ | `dependency_edges[].kind` enum | V:554-556 | SPECIFIED rev3:136-143 | — | — |
| 14 | OBJ | `dependency_edges[].edge_class` ∈ {R1-STRUCTURAL, R2-EVIDENCED}. R2 needs `quote` and `source_id`; R1's source must be a plan row source | r5:352-364 via C:186-189 | ABSENT (0 hits in rev3/R7; R7:70 says only "edge classes") | EXPOSE, or DERIVE (R2 iff a quote is present; R1 otherwise) | yes (R2, D list) |
| 15 | OBJ | `pair_breakdown` = Counter(`relationship\|basis`) over the slice's `p3a_pairs`; `tier_causing_pair_ids` ⊆ the slice's pair ids | V:557-562 | SPECIFIED rev3:262, 270, 1342 | — | — |
| 16 | OBJ | Timeline points are objects with exactly 7 keys. `source_id` is a required, unique S-id. `change_vs_previous` is required and in the enum. `states.epistemic_class` ∈ SOURCE\|INFERENCE. `order`/`date_applies_to_file` are in their enums (null only with a rule-(b) escalation). F1 applies | V:564-581; U:227-248 | SPECIFIED rev3:178-198, 264, 78-79; R7:239, 242 | — | — |
| 17 | OBJ | `births`: exactly the 5 kinds. Each value fullmatches a §D pattern (`ESTABLISHED-<KIND>-BIRTH[...]` with the kind upper-cased). G-08: PRIMARY per the slice's `source_meta`; `ESCALATED[G-08: …]` must name that provenance. ESTABLISHED needs a CONFIRMED point. BIRTH-UNRESOLVED-MTIME-ONLY has its own conditions | V:135-139, 584-603 | SPECIFIED rev3:144-151, 84-85, 616, 1147-1149 | — | — |
| 18 | OBJ | **[NEW]** **Every** S-id token anywhere in `births.<kind>` becomes a claim `birth:<kind>:<S>` that needs a BIRTH fact (WHOLE-FILE unless the value starts BIRTH-UNRESOLVED-). That includes S-ids inside a MOVED quote, an `ESCALATED[TIMESTAMP-ANOMALY: reason]`, and the non-PRIMARY source named by `ESCALATED[G-08: sole basis S#### …]`. S2 also requires every such token to be a plan row source | U:340-343; E:158-177; r5:452-455 | NAME-ONLY. The registry row (R7:276-277) and the claim grammar (R7:334) give the path type, but not that escalation reasons and quotes are scanned token by token | REDESIGN (enumerate only `birth_basis()`; no claim for ESCALATED forms) or EXPOSE | no |
| 19 | OBJ | Each `status_basis.<field>` is an object with a truthy `anti_projection` and an `epistemic_class` that is not a layer-B/C class | V:604-608 | SPECIFIED rev3:281, 1345, 1347 | — | — |
| 20 | OBJ | S-ids in `status_basis.<field>.sources[*]` and in `hindsight_dependency[*]` must be ⊆ the sources of the verifier-enumerated claims ("verified cited sources") | U:306-322 | NAME-ONLY R7:300, 302 (term undefined); rev3:281 asks for sources of the status | EXPOSE (define it) | yes (R2, D list / F8) |
| 21 | OBJ | **[NEW]** In `status_basis`, `sources` must be a **list**: a string value lands on an untyped path, because the `<text>` placeholder excludes `sources`. Free-text fields (`what_says_this`, …) that carry an S-id must be **scalar strings**: a list or object under them is untyped. Both are R7-U | U:104-110, 289-303 | NAME-ONLY. Implied only by the registry path grammar R7:300-301; rev3:281 gives no types for the text fields | EXPOSE | no |
| 22 | OBJ | **[NEW]** Closed-world typing: any S-id-bearing string at a path without a registry row is R7-U. rev3 fields that naturally carry S-ids have no row: `timeline[*].states.step` (INFERENCE step, rev3:1163), `timeline[*].date_basis`, `absences.<dim>.supplied_by.anchor`, `census_reading_disagreements[*].anchor`/`stage1_terms`, extra keys in d4/d5 entries, and `supplied_by.{source_id,…}` under a non-FOUND resolution | U:155-218, 289-303 | SPECIFIED as a rule (R7:227-237, 255, registry R7:270-312). The fields to keep S-id-free are left implicit: a latent trap | EXPOSE (list the S-id-free fields), or REDESIGN (META rows) | no |
| 23 | OBJ | **[NEW]** `stage2_dispositions[*].hit_key` must copy the stage-1 key exactly (G-04 key equality, V:698-700, 751-753). Some ledger anchors contain an S-id token: 17 of 143,789 anchors in `P3B-DISCOVERY-SEARCH.jsonl`, in the LABEL-HITS of 2 non-hub S5 manifest labels (counts only). `hit_key` has no registry row, so such a disposition is an **unavoidable** R7-U | U:155-175, 198-218 | CONTRADICTORY rev3:278 ("EXACTLY the stage-1 hit's key") vs R7:227-237, 255 (default-deny) | REDESIGN (registry row `stage2_dispositions[*].hit_key` META "copied key, no force") | no |
| 24 | OBJ | `semantic_evidence` has exactly the keys {d4, d5}; entries need an S-id `source_id` and a `quote`. Tier Z constants; ROW-0/3/4 equal the slice's mechanical value; ROW-1/2 need d4/d5; CONTESTED needs a PRIMARY d4 | V:626-651; U:282-285 | SPECIFIED rev3:71-75, 269 | — | — |
| 25 | OBJ | CONTESTED + ROW-1 + non-empty `contradicted_by` ⇒ ∃ a d4 entry for a contradicting source | C:108-111 | SPECIFIED R7:404 (the code is weaker; see Reverse R-3) | — | — |
| 26 | OBJ | `stage2_dispositions[]`: exact keys (+`offsets`). A raw `hit_key` is an int; `term_index` is an int. `by_dimension` keys = the DIMENSION set, with values in the enum. One entry per distinct expected hit key; empty on a hub | V:688-753 | SPECIFIED rev3:278, 168-177 | — | — |
| 27 | OBJ | Decided binary: method BINARY-DECIDED with every dimension FALSE-HIT, or NOT-CONSUMED-ESCALATED with every dimension ESCALATED plus a CONTRACT-DEVIATION naming the file; never read; NOT-CONSUMED record; not a claim source | V:715-723; E:222-256 | SPECIFIED R7:639-651 | — | — |
| 28 | OBJ | **[NEW]** An incompletely read, non-decided stage-2 hit file passes only with method **NOT-CONSUMED-ESCALATED**: method WHOLE-FILE with every dimension ESCALATED fails READ-COVERAGE (V:881-882, 895-899). A hit file never read at step 1/7 fails G-04 whatever is escalated (V:852-857) | V:715-723, 852-857, 869-899 | CONTRADICTORY. rev3 §D:174-177 ("binding") omits that method value. rev3:17-20 calls a CONTRACT-DEVIATION escalation "an acceptable outcome". R7 introduces the method only for decided binaries (R7:647) and fail-closed only for undecided binaries (R7:651) | EXPOSE (the method and the "at least one page" condition), or REDESIGN G-04 to accept a named CONTRACT-DEVIATION | no |
| 29 | OBJ | **[NEW]** STAGE-2A-2B dispositions need scanner log entries and F3 `offsets`. Under R7 the scanner is not an allowed tool (W8), and the witnessed read log has no `tool` entries, so every STAGE-2A-2B disposition on a short term fails G-04 | V:724-753; W:273-288 | CONTRADICTORY rev3:39-41, 51-52, 80-81, 175 (Stage 2A/2B offered) vs R7:490-500 (reader-only Bash) | EXPOSE ("not available under R7; read short-term files whole") or REMOVE the method from the R7 enum | no |
| 30 | OBJ | `absences`: keys {resolution, negative_label, population_basis, supplied_by, reason} (+`relative_timing`). `resolution` enum. `population_basis` = `DISCOVERY-POPULATION (HS-…)`. `negative_label` = the stage-1 record. Dimensions = the DIMENSION set. FOUND conditions: supplied_by source/anchor/quote, PRIMARY, a FOUND hit, a step-1/7 read, not NEGATIVE-CENSUS, D-23 `relative_timing`. GU conditions; disagreement → ESCALATED | V:755-815 | SPECIFIED rev3:70, 153-158, 277, 611-612, 1268 | — | — |
| 31 | OBJ | GENUINELY-UNDEFINED: every file of the label's reading runs is WHOLE-FILE (except conforming FALSE-HIT decided files), and no witnessed ABSENCE fact with `finding` DEFINES exists for that dimension | E:259-269 | SPECIFIED R7:288, 650 | — | — |
| 32 | OBJ | D-23 Track-B rules (births, FOUND, edges, status_basis, CONTESTED) | V:817-833 | SPECIFIED rev3:86-87, 1251-1268 | — | — |
| 33 | OBJ | Hub read discipline (no step-7 read; step-10 limits) and GAP `NOT-FOUND-LOAD-ESCALATED` on hub dimensions only | V:835-863 | SPECIFIED rev3:54-60 | — | — |
| 34 | OBJ | READ-COVERAGE: complete witnessed pages for FOUND, non-UNRESOLVED births, change points and non-ESCALATED dispositions; OMQ-14 row sources complete or named CONTRACT-DEVIATION | V:867-908 | SPECIFIED rev3:9-23 | — | — |
| 35 | OBJ | Every non-WHOLE-FILE record (NOT-CONSUMED included) needs a CONTRACT-DEVIATION escalation whose `detail` contains the S-id, unless the file is a conforming FALSE-HIT decided file | E:208-219 | SPECIFIED rev3:17-18; R7:631, 650 | — | — |
| 36 | OBJ | Object-level quotes are anchored on bytes witnessed by a reading run of the label (for a synthesis: the unit runs): `timeline.states.quote`, FOUND `supplied_by.quote`, edge, d4/d5 and census quotes | E:181-205, 64-106 | SPECIFIED R7:275-299, 618-629 | — | — |
| 37 | OBJ | G-12 in the quote-stripped object: the rs_id pattern, the POINTER regex (incl. `register (OBSERVATION\|GAP\|…)`, `rs_id`), no `research_time` key, and no register statement of ≥ 40 characters inside a non-quote string | V:108-109, 610-621 | NAME-ONLY for the phrase list (rev3:1347 prose; the regex is not stated). SPECIFIED for rs_id and `research_time` | EXPOSE (the adjudicated phrase list) | yes (R2(b)) |
| 38 | OBJ | S3 layer-A pointers on the **whole** object body, quotes included (index 0 only): POINTER7 (`rs[-_ ]?id`, `research[- ]register`, `cf. register`, `register (record\|id\|entry)`) plus rsid, including `<B>#<n>` | C:34-35, 165-168 | NAME-ONLY. Not stated; the regex differs from row 37's | EXPOSE (one adjudicated regex) | yes (R2(b), D list) |
| 39 | OBJ, REG, GAP | G-01: no F-id outside quotes; S-ids ∈ 02-FILES; no FIREWALL-LIMITED file as evidence. AUDIT: cited S-ids ⊆ slice ∪ witnessed reads | V:622-624, 1001-1030 | SPECIFIED rev3:1338, 100 | — | — |
| 40 | OBJ, REG, GAP | **[NEW]** S3 S-id range matcher (after NFKC, dash folding and `\u` decoding): dashes, `..`, `to/through/thru/until/till/bis/up to`, `between\|from S#### and\|to S####`, abbreviated endpoints. Ordinary timeline prose such as "moves from S#### to S####" is a range. If the span covers a hold-out id, it is also a QUARANTINE hit and, in the Write input, a W8 SEAL stop | C:155-164; cm:166-207; W:265-270 | NAME-ONLY. rev3:100-103 forbids ranges, gives two dash/dot examples, and does not state the `from…to` / word forms | EXPOSE (state the forbidden phrasings) | partly (R2 only if the annex adds the matcher) |
| 41 | OBJ, REG, GAP, CALLS | **[NEW]** QUARANTINE/SEAL: no hold-out label name (exact token match) and no hold-out S-id (explicit or range-spanned) in any record, and in **any tool-call input** (W8 SEAL STOP). Hold-out names are sealed, and the reader does not mask them in delivered pages (no masking code in `p3b_read_source.py`). A verbatim quote of a discovery page that mentions a hold-out label therefore trips SEAL | V:117, 1032-1042; C:169-172; W:265-270; cm:159-163, 210-217 | ABSENT as an agent-checkable rule. R7:505 states the SEAL, but the agent cannot know the identifiers. Magnitude is unknown without corpus access | REDESIGN (mask hold-out names at the reader, or another seal-preserving design) | no |
| 42 | OBJ | `timeline_summary` keys ⊆ {first_<kind>, later_support, later_refinement, contradicted_by, rejected_by, current_lifecycle}. later_*/rejected_by are lists of unique S-ids. `contradicted_by[]` is exactly {source_id, contradicts}, both S-ids, unique | U:58-59, 249-275 | SPECIFIED rev3:265; R7:243-244, 395 | — | — |
| 43 | OBJ | Summary relations: membership; later_support only RESTATES; later_refinement only EXTENDS/NARROWS/CHANGES-*; CONTRADICTS ⇒ in contradicted_by targeting the predecessor; RETRACTS ⇒ in rejected_by; first_<kind> S-ids = births S-ids; contradicted_by members are distinct points | C:63-107 | SPECIFIED R7:371-380, 388, 401 | — | — |
| 44 | OBJ | **[NEW]** later_* lateness needs ESTABLISHED precedence, computed from **02-FILES** `best_historical_date_basis`, `order_evidence` and `mtime_block`, plus `date_applies_to_file` CONFIRMED. The slice's `source_meta` has **no `order_evidence`** (pr:55-56, 434), and 02-FILES is outside I(run). 167 of the 1,167 EXPLICIT files have `order_evidence` exactly SOURCE_ID, so they are undated for the verifier but look dated to the agent | C:39-59, 96-101; files_meta from V:310 | ABSENT (input). The rule itself is stated: R7:379, 408; rev3:1206-1213 | DERIVE (put each source's dated position in the plan or slice), or REMOVE the agent-side lateness assertion (the verifier filters) | no |
| 45 | OBJ | **[NEW]** `superseded_by_sources[]` is exactly {source_id (S-id), position}, unique. `position` must equal the derived A.10 file-level value: the ISO date string `YYYY-MM-DD` (`to_date(...).isoformat()`, cm:305-313) or `UNDATED`. It depends on the same missing `order_evidence` | U:276-281; C:39-51, 129-132 | NAME-ONLY R7:285, 417; rev3:1165-1167 ("dated position or UNDATED"; no string format; input incomplete) | DERIVE (the verifier computes `position`; the agent writes `source_id` only), as v2's principle "attest nothing a tool can derive" asks | no (principle only) |
| 46 | OBJ | `current_lifecycle` contains "SUPERSEDED" ⇔ `superseded_by_sources` is non-empty | C:125-128 | SPECIFIED R7:418 | — | — |
| 47 | OBJ, REG | S1 NO path: escalation reason OTHER with a `detail` matching `^VERDICT-EVIDENCE-CONFLICT: RP<digits>`, plus a register record with `topics` ∋ VERDICT-EVIDENCE-CONFLICT naming the exact pair token | r6:270-271, 280-287 | NAME-ONLY rev3:694-695, 843; R7:892 | EXPOSE | yes (R2, D list) |
| 48 | FRR | Exactly one record per permitted file of the run (the plan's run files, decided binaries included), keyed by `source_id` | E:125-129 | NAME-ONLY. R7:643 implies a record for decided files; the PLAN lists the files; neither "exactly one" nor the key name is stated | EXPOSE | yes (R2) |
| 49 | FRR | `reading_state` ∈ {WHOLE-FILE, READ-PARTIAL, READ-FAILED, NOT-CONSUMED} | E:133-135; r5:40 | NAME-ONLY. R7:616 and :643 name two of the four values | EXPOSE | yes (R2, D list) |
| 50 | FRR | WHOLE-FILE ⇒ complete witnessed coverage in the owning run | E:136-137 | SPECIFIED R7:616 | — | — |
| 51 | FRR | `ack_tokens`: the sorted list = one `ACK-<12 hex>` per byte page 1..n, with no repeats | E:138-141 | ABSENT (field). R7:429 and :630 give the value only | REMOVE (W3 checks the trailer; coverage is witnessed) | yes (R1(a)) |
| 52 | FRR | `facts[]`: `fact_id` present and unique per record | E:142-144 | NAME-ONLY R7:246 | EXPOSE | yes (R2) |
| 53 | FRR | Fact `kind` ∈ the 9 fact kinds; each kind's required field names | E:24-26, 146-152 | SPECIFIED R7:319-328 | — | — |
| 54 | FRR | Truthy-only fact fields without a value domain: TIMELINE `change_candidate`, TWO-CONCEPT `concepts`, OMQ14 `type` | E:151 | NAME-ONLY R7:320, 325, 328 | EXPOSE (domains) or REMOVE (no claim reads them) | yes (R2, D list) |
| 55 | FRR | Fact `quote` is anchored inside pages witnessed by the owning run (exact bytes or N-WS) | E:85-106, 153-154 | SPECIFIED R7:618-629 | — | — |
| 56 | FRR | BIRTH `birth_kind` = the lower-case births key | U:342; E:170 | NAME-ONLY R7:321 (value implied only by the claim id, R7:334) | EXPOSE | yes (R2, D list) |
| 57 | FRR | ABSENCE `dimension` = the dimension and `finding` = DEFINES, for `absence:<dim>` and stage-2 FOUND claims | U:355, 362; E:170 | NAME-ONLY. R7:322 gives the domain; "FOUND needs DEFINES" is only implicit (R7:288) | EXPOSE | yes (R2, D list) |
| 58 | FRR | DEPENDENCY `target_label` = the edge's `target_label` | U:366; E:170 | NAME-ONLY R7:323, 340 | EXPOSE | yes (R2, D list) |
| 59 | FRR | CONTRADICTION `contradicts_source` = the contradicted_by target (contra claims; d4 claims have no field constraint) | U:347, 370; E:170 | SPECIFIED R7:402 ("`contradicts_source` = the target") | — | — |
| 60 | FRR | LIFECYCLE `lifecycle` = "SUPERSEDED" | U:351; E:170 | NAME-ONLY R7:326 | EXPOSE | yes (R2, D list) |
| 61 | FRR | CENSUS `dimension` = the census entry's `dimension` | U:374; E:170 | NAME-ONLY R7:327 | EXPOSE | yes (R2, D list) |
| 62 | FRR | The fact must belong to a reading run (UNIT/SINGLE) of the label. Whole claims need a WHOLE-FILE state and complete coverage in that run | E:162-175; VR7:170-172 | SPECIFIED R7:260, 402-403, 616 | — | — |
| 63 | FRR | `pair_evidence_checks[{pair_id, supports, quote}]` for every (P3a pair touching the label, required file cited in its `what_says_this`/`basis`). `supports` ∈ {YES, NO, NOT-DETERMINABLE}. A YES/NO quote must occur in the file bytes (whitespace-collapsed). No duplicate (pair_id, source_id) | r6:257-279; C:137-146 | ABSENT (0 hits in rev3; R7:245 "(pair_id) for pair checks"; R7:892 "S1 carried over") | EXPOSE | yes (R2) |
| 64 | CE | JSON object `{claim_id: [{run, source_id, fact_id}, …]}` in the final run's directory. A missing file silently becomes `{}` | VR7:193; E:158-177 | ABSENT (shape). R7:130 and :247 give names only | REMOVE (the verifier searches the facts itself) | yes (R1(c)) |
| 65 | CE | Claim ids exactly as enumerated (R7:332-343). The stage-2 id embeds `json.dumps(hit_key)` (ASCII `\u` escapes) | U:331-376 | NAME-ONLY (`json()` undefined, R7:339) | REMOVE (with row 64) | yes (R1(c)) |
| 66 | CE | A ref's `run` must be the fact-owning reading run (UNIT for DECOMPOSED); the synthesis id fails. **[NEW detail]** the dispatch prompt itself says "every claim-evidence ref use YOUR run id" (orch:126) | E:166-168; VR7:171 | CONTRADICTORY R7:130 ("the dispatched run id"); the prompt reinforces it | REMOVE (with row 64) | yes (R1(c)) |
| 67 | LINT | `{result: "PASS", records_sha256: sha256(canonical JSON of [object] + register + p1-gap of the label)}`. It cannot be computed without Bash | VR7:205-207 | ABSENT (R7:92, 143, 492 name the file only) | REMOVE (the verifier runs S3, C:155-172) | yes (R1(b)) |
| 68 | FRR, CE, LINT | **[NEW]** Parse robustness: a malformed FRR line, a CE/LINT that is not a JSON object, a non-object record or fact, or an unhashable `fact_id` **raises** in the verifier. `main` catches only Refused/S5Error/OSError, so there is no report instead of BATCH-FAIL | VR7:55-66, 184, 193, 205; E:117-121, 127, 142; V:1188 | ABSENT (rev3:95 gives a format for OBJ/REG/GAP only); against R7:122's "FAILS, never raises" | REDESIGN (fail-closed parse with an R7-E code) | partly (R3: CE only, if R1(c) is not adopted) |
| 69 | REG | Required core keys (all §E register keys except profile*, `gap_status`, `proposed_topic_definitions`) and shapes | V:922-937 | SPECIFIED rev3:294-319 | — | — |
| 70 | REG, GAP | `rs_id` = `<B>-R7:<B>:<n>`, `gap_id` = `<B>-R7:<B>:G<n>`, unique; n range 1000·L+k (assembly) | V:926-930, 978-979, 988-989, 998-999 | SPECIFIED R7:127-130 | — | — |
| 71 | REG | `kind` (§13.2) with its `output_layer`; `lifecycle_stage`/`lens`/`output_layer` enums; `epistemic_class` §11.1; `origin`=P3B; `evidence_kind`; provenance; no STATUS | V:938-959 | SPECIFIED rev3:216-247, 300-312, 588-603, 795-806 | — | — |
| 72 | REG | **[NEW]** `scale` must be OBJECT | V:943-946 | CONTRADICTORY. rev3 §D:241-243 (binding) says OBJECT; rev3:886 lists OBJECT \| CROSS-OBJECT \| CORPUS, and rev3:817 prescribes `scale: CORPUS`. Likewise `origin` P3B (rev3:312) vs "P3B \| EXTERNAL-LANE" (rev3:889) | EXPOSE ("batch register records: OBJECT scale, origin P3B") | no |
| 73 | REG | GAP `gap_status` enum. HYPOTHESIS/STRUCTURE-CANDIDATE at TEST-DEFINED with all 7 profile keys | V:960-967 | SPECIFIED rev3:222-227, 319 | — | — |
| 74 | REG | **[NEW]** TEST-DEFINED ⇒ `test_plan_sha256` is 64 hex characters. The agent cannot compute a sha256 under W8, and the verifier checks only the format. So the pre-registered outcome kinds (HYPOTHESIS/STRUCTURE-CANDIDATE, rev3:570) pass only with a fabricated value | V:962-971 | CONTRADICTORY. rev3:1030 ("hashes as `test_plan_sha256`") vs R7:490-500 (no Bash) | DERIVE (a tool hashes the test-plan fields at assembly; the verifier recomputes) | no (same class as R1(b), not listed) |
| 75 | REG | `PROPOSED:` topics need definitions; `historical_anchor` and `research_time` are truthy | V:972-977 | SPECIFIED rev3:318, 845, 1347 | — | — |
| 76 | GAP | Exactly the 13 keys; `found_via` enum; no STAGE-2 on a hub; provenance | V:981-997 | SPECIFIED rev3:321-335 | — | — |
| 77 | GAP / CALLS | File name `p1-gap.jsonl`. A Write to rev3's `p1-gap-capture.jsonl` is outside the permitted paths (W8) | U:55-56, 696-698; W:319-330 | CONTRADICTORY. rev3:98-100 ("p1-gap-capture.jsonl … Write nothing else") vs R7:138, 492, superseded only by implication | EXPOSE ("replaces rev3 §C") | partly (an annex of file shapes would carry it) |
| 78 | CALLS | Reader: the exact canonical command; bound run/batch/label; source ∈ perm; no call on decided files; no reader call in a synthesis run | W:276-282, 345-357; U:701-704 | SPECIFIED R7:482-485, 490, 640 | — | — |
| 79 | CALLS | Refused, malformed (e.g. page > n) and denied reader calls are stop conditions (R7-W) | W:358-370 | SPECIFIED R7:430 | — | — |
| 80 | CALLS | Read only the exact I(run) paths, or the agent's own announced persisted outputs; displayed content = the frozen bytes | W:289-318, 82-92 | SPECIFIED R7:491, 539-576 | — | — |
| 81 | CALLS | **[NEW]** A Read past EOF displays no numbered lines, so `content_match` is false: a W8 R7-W failure | W:82-92, 296-298 | ABSENT in rev3/R7; stated only in the prompt (orch:205-206, `STOP_PAGING`) | EXPOSE, or REDESIGN (treat a past-EOF notice as a non-violating empty read) | no |
| 82 | CALLS | Write only to permitted run paths. Edit/MultiEdit/Grep/Glob/other Bash/web are forbidden. The final bytes must equal the last witnessed Write (W5) | W:319-341 | SPECIFIED R7:492, 497-501 | — | (R5 adds a prompt line) |
| 83 | CALLS | **[NEW]** rev3's scratch directory `/tmp/p3b-s5-scratch/<batch>/`: any write there is a W8 violation | W:319-330 | CONTRADICTORY rev3:48 vs R7:492, 501 | EXPOSE ("rev3 A.4 does not apply under R7") | no |
| 84 | CALLS | **[NEW]** SINGLE run: no reader call may complete after the run's last permitted Write of **any** file. The code takes the time of the last Write of any permitted path, not only the object Write | W:409-413, 432-433 | ABSENT. R7:668-676 orders only units and synthesis, and defines "produced" as "the last permitted object Write" | EXPOSE ("no reader call after your last Write"), or REMOVE for SINGLE (W5's last-Write equality already binds the bytes) | no |
| 85 | CALLS | At least one non-handback tool call (W1-ZERO-TOOL/DECLINED); the final run must Write | W:209-212, 434-435 | SPECIFIED R7:444-445 | — | — |

Derived values the agent is asked to produce are rows 3, 18, 20, 44, 45, 51, 65, 67 and 74. In each, the value is
computable by a tool and partly or wholly impossible for the agent under W8. READ-LOG.jsonl (the reader writes it;
W6 reconciliation, E:273-284), the WITNESS/INPUT-MANIFEST/PROVENANCE files, the plan, the manifest and the EMPTY
objects are orchestrator- or tool-authored and are excluded.

## Reverse direction (I(run) rules the code never checks, or checks differently)

| # | Contract rule | What the code does |
|---|---|---|
| R-1 | R7:335 claim id `summary:<field>:<S>` | Never enumerated (U:331-376). Summary entries are evaluated only through membership/class rules (C:85-95) and the referenced point's timeline claim |
| R-2 | R7:372 "`rejected_by` entries require WHOLE-FILE (retraction force)" | Enforced only through the referenced point's class (U:338-339). A non-RETRACTS point in `rejected_by` on a non-WHOLE-FILE source with a CONTRACT-DEVIATION escalation passes, which contradicts T76 (R7:802) |
| R-3 | R7:404 "if CONTESTED via D4 on it, `semantic_evidence.d4` has an entry for `source_id`" (per relation) | Only checked under `semantic_status_rule` ROW-1, and only that **some** relation's source is in d4 (C:108-111) |
| R-4 | R7:413 "an agent-declared precedence value that differs from the computed one is R7-R" (T45) | No declared-precedence field exists. An extra key in a `contradicted_by` entry fails R7-U validation (U:268-269), never R7-R |
| R-5 | R7:419 `end(p)` and the UNORDERED marking per A.10 | Not computed by the verifier |
| R-6 | R7:592 "reads its SLICE-VIEW, not the raw SLICE, at most 25 lines per Read" | Unchecked. The raw SLICE is in I(run), and W8 admits any Read of it (W:291-301) |
| R-7 | rev3:95 output files are canonical JSON lines (sorted keys, compact separators) | Unchecked (lenient `json.loads`, V:224-234). The assembly re-serializes |
| R-8 | rev3:291 `analysis_date` YYYY-MM-DD; rev3:307 `research_time` ISO datetime | Presence/truthiness only (V:491-497, 976) |
| R-9 | R7:247 uniqueness key "the claim id in claim-evidence" (a duplicate fails, no last-wins, R7:251) | `json.load` keeps the last duplicate key silently (VR7:62-66), so it is never detected (moot if R1(c) removes CE) |
| R-10 | R7:892 S1 "quotes in bytes" vs R7:618-622 "for every fact quote … inside Wr(S)" | Pair-check quotes are matched anywhere in the whole file, whitespace-collapsed (r6:147-148, 279), not anchored on witnessed pages |
| R-11 | rev3 §11.2 minimum evidence (CLOSED needs a type-signature row; INCONSISTENT quotes two statements; ESTABLISHED states that no earlier row matches) | Not script-checked (audit, §21) |
| R-12 | rev3:61-64 step-10 cap of 900,000 bytes and label-local step-10 reads | Alerts only (V:910-916, 1057-1061) |
| R-13 | R7:303 META row rule "a CONTRACT-DEVIATION escalation must name the unread file" | `meta_violations` ignores that rule text (U:319-326). It is enforced elsewhere (E:208-219), so it is effectively checked |

## New vs reviews A/D

**Named by DIAG-A §3 or PKG-REVIEW-D's "Unreachable keys" table and confirmed:** `ack_tokens` (51);
`pair_evidence_checks` with its scale (63); `records_sha256` (67); `edge_class` (14); the `reading_state` values (49);
the CE shape (64); the `json(hit_key)` serializer (65); "verified cited sources" (20); `birth_kind`, `finding: DEFINES`,
`target_label` (56-58); the S1 NO path (47); LIFECYCLE `SUPERSEDED` (60); CENSUS `dimension` (61); the truthy-only fields (54);
the synthesis claim `run` (66); the divergent POINTER regexes (37/38); `summary:` (R-1); the `p1-gap` file-name conflict (77);
the silent `or {}` (VR7:193, :205).

**Named by the reviews and NOT confirmed (or confirmed only in part):**
1. D: "CONTRADICTION `contradicts_source == <T>` … the equality rule is unstated." R7:402 states it explicitly for the contradicted_by relation ("`contradicts_source` = the target"). For d4 claims the code imposes no field equality at all (U:370). Only the CENSUS half of that D row stands.
2. D finding 3 and the package's R2(b) speak of "three divergent POINTER regexes" and cite `p3b_s5_r5.py:462`. That regex sits in `r5.s3_lint`, which the R7 path never calls: R7 uses `C.s3_violations`, C:155-172. Only two POINTER regexes are live on the R7 path (V:108-109 for G-12, C:34-35 for S3).
3. DIAG-A F2 (failures track per-run file counts) and all string counts come from the verify reports and run outputs. They were not re-derived here; the outputs were not opened.

**New in this sweep** (not in DIAG-A, PKG-REVIEW-D or package v2):
- 3: `contract_sha256`/`input_manifest_sha256` have no stated value source, and R7 uses both names for other hashes
- 18: birth claims are created for every S-id token in a births value (incl. ESCALATED reasons, the G-08 source, MOVED quotes)
- 21: `status_basis.sources` must be a list; text fields must be scalars
- 22: closed-world typing makes S-ids illegal in rev3 free-text fields (states.step, date_basis, anchors, stage1_terms)
- 23: the S-id-bearing `hit_key` is an unavoidable R7-U (17 anchors; 2 non-hub S5 labels)
- 28: the NOT-CONSUMED-ESCALATED method is the only passing disposition for an incompletely read hit file; CONTRACT-DEVIATION is not "acceptable" for unread hit files
- 29: STAGE-2A-2B cannot pass under W8
- 40: the breadth of the range matcher (`from S#### to S####` prose), which escalates to SEAL when the span covers a hold-out id
- 41: hold-out label names in verbatim quotes are agent-unknowable, yet trigger QUARANTINE and a W8 SEAL stop
- 44/45: `order_evidence` is missing from `source_meta`, so precedence and `position` cannot be derived by the agent; `position` has an unstated `YYYY-MM-DD` format
- 66 (detail): the dispatch prompt itself instructs the wrong claim `run`
- 68: malformed agent JSON crashes the verifier instead of failing
- 72: the contradictions over `scale` and `origin`
- 74: `test_plan_sha256` cannot be computed under W8 (it affects the pre-registered outcome kinds)
- 81: a Read past EOF is a W8 failure, stated only in the prompt
- 83: rev3's scratch directory is a W8 violation
- 84: SINGLE read-after-last-Write is a W5 failure that R7 §7 does not state
- Reverse R-2 to R-10

## Method & limits

**How the sweep was done.**
1. **Call graph.** `p3b_s5_verify.verify` (rev7 branch, V:418-436) calls `verify_r7` (VR7:69-215), and every
   historical gate after it (V:462-1061) is also on the path, composed at V:1064-1085. From `verify_r7` I followed
   every call into U, W, E and C, and from there into r5/r6/cm.
2. **Function sets reached:**
   - U: contract load, typing, validation, meta, claims, binary decisions, plan, namespace, revision, slice view, input manifest, permitted writes, canonical reader.
   - W: witness, `_classify`, `_reader_event`, `_stages`, digests, monotonicity.
   - E: all functions.
   - C: all functions except `contradiction_precedence` (not called).
   - r5: `is_binary`, `byte_pages`, `ack_token`, `byte_page_record`, `edge_class_violations`, `s2_violations`. `empty_label_violations` is reached only for orchestrator-written EMPTY objects.
   - r6: `slice_required`, `plan_derive`, `plan_violations`, `run_owner`, `s1_violations_v6`, `_in_text`, `empty_violations_v6` (EMPTY only).
   - cm: `_range_norm`, `SID_RANGE`, `SID_BETWEEN`, `quarantine_hits`, `to_date`.
3. **Static extraction.** An AST script (`extract_keys.py` in this scratchpad) listed every `.get("…")`, `["…"]` and
   string-literal comparison inside those functions: 1,015 occurrences (`keys.tsv`). I then read each function and
   assigned every literal that touches an agent artifact or tool call to a row. Literals on manifest, plan, slice,
   witness, transcript and READ-LOG data were excluded as orchestrator-, tool- or harness-derived. Regexes
   (SID, SID_RANGE/BETWEEN, POINTER, POINTER7, rsid, RUN7, canonical reader, BIND/ORCH/HDR/TRL, CATN) were read in full.
4. **Contract check.** Every key was grepped in rev3 and R7 (occurrence counts), and the cited lines were read.
   The plan (`plan_derive_v7` fields) and the slice fields (pr:55-56, 419-434; `r7_slice_text` only rewrites the run id)
   were checked as sources of values. The prompt (orch:230-262) was read because it is agent-visible.
5. **Data probes, counts only.** 02-FILES (`order_evidence` distribution), `P3B-DISCOVERY-SEARCH.jsonl` (ledger anchors
   with an S-id token), the hub list (no S-ids in hub records) and the manifest (`contract_sha256` = rev3 sha). No S-id or label was printed.

**Why the sweep should be complete for verifier-read keys.** Every R7 failure string is produced inside the functions
listed above, and each was read end to end. The literal extraction cross-checks that no `.get`/subscript on an agent
record was skipped. All 1,015 extracted literals fall into a row, the excluded classes, or report/bookkeeping keys.

**What could still be missed:**
- **Dynamic keys.** Some keys are built at runtime: `f"first_{k}"`, `f"timeline[{sid}].{fld}"`, the registry rows parsed from R7's table (U:76-110), and the `ENUMS`/`SCHEMA` tables imported from pr:88-158. I covered them by reading those tables, not by AST.
- **Orchestrator checks outside the verifier** that also fail a batch: the assembly refusals (R7:143; n range, foreign label, duplicate-key lines) and freeze-final. They are agent-reachable but not on the verifier path; rows 70 and 77 cover the visible ones.
- **Harness-dependent W8 behaviour (unconfirmed).** `_content_match` compares the displayed lines byte for byte (W:82-92). If the harness Read truncates lines above about 2,000 characters, any longer I(run) line fails W8. 192 of the 396 rev7 plans are a single line longer than 2,000 characters (up to 5,616), and SLICE-VIEW lines may reach 8,000 (U:52). The canary batch whose plan line is 2,124 characters shows no `content_match` failure, so either the harness did not truncate or the plan was not read. Deciding this needs the witness (an authorized session).
- **Magnitude, row 41.** How often discovery pages mention hold-out label names needs corpus access, which I did not have.
- **Row 23.** The affected-label count assumes that slice LABEL-HITS equal the discovery-search LABEL-HITS (the slices were not opened).
- **Semantics, not reachability.** Whether an agent *could* infer a NAME-ONLY value is a judgment. The classification is conservative: a value counts as SPECIFIED only when the value or format is written in I(run).
