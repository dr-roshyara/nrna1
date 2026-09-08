# Adversarial Falsification (10 required self-attacks)

| # | Attack | Result |
|---|---|---|
| 1 | Did you import an unstated assumption? | Yes, disclosed: the input-carrier list `{Claim, Evidence References, Justification History}` is a constructed inference, explicitly labeled as such throughout (`04`, `06`, `07`) |
| 2 | Did you silently use another `kernel-reduction/` file? | No — checked directly; only `03`/`04` were read for B-side content |
| 3 | Did you infer missing semantics? | Only the labeled, disclosed input-mapping (attack 1); no precondition/postcondition/failure semantics were invented anywhere |
| 4 | Did you mistake lexical similarity for semantic identity? | No — `06` explicitly rates lexical correspondence "weak" and does not use it to support the conceptual/functional finding |
| 5 | Did you mistake an analytical mapping for a DDD command binding? | No — `06` explicitly states the opposite: "not established... a mapping constructed for this experiment, not a demonstrated native model mapping" |
| 6 | Did you use reconstructed provenance as direct provenance? | No — `08` keeps `RECONSTRUCTED PROVENANCE` (of the admitted files themselves) separate from `DIRECT EVIDENCE` (of their content) throughout |
| 7 | Did you silently modify a frozen source? | No — seq 0157, `04`, `03` all confirmed unmodified (`11`) |
| 8 | Did you convert an untestable condition into a testable one by inventing a contract? | No — `05` reports 5 of 7 preservation properties as genuinely untestable, not forced into a result |
| 9 | Did you infer invariant preservation without a source-stated invariant? | No — `05` explicitly notes P-3's only candidate invariant is itself an unestablished hypothesis, so no preservation claim was made |
| 10 | Did you accidentally generalize from Pair 1 to B/C1 generally? | No — `07`, `10` restrict every finding to this one pair explicitly |

## Self-correction surfaced by this audit, not by the original critique

The P-3 "many-to-many evidence-sharing stress test" mischaracterization (`00`, `03`) was found by this
study's own raw-source re-check, not flagged in advance by the authorization — recorded as a genuine,
independently-discovered correction.
