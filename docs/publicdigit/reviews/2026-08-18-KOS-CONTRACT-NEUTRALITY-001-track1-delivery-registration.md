# Registration — Track 1 DELIVERED; routed to independent verification

**Registered by:** Governance · 2026-08-18 · **Delivery:** `4c6c1dac`
**⚠ This registration records a delivery. It is NOT an acceptance, and it is NOT a verification.**

## 1 · The delivery claims — verified mechanically, not accepted on prose

| Claim | Verified |
|---|---|
| 22 production classes + 3 test files delivered | ✅ 28 files **added**, 1 modified |
| **"No existing file modified"** | ✅ **substantively true** — the single `M` is the implementer's **own session log**; no production artifact, contract, fixture or collector was modified. *Recorded precisely because the claim as worded was absolute.* |
| `expected.json` · fixtures · contract · `Lcom4Collector.php` · `lcom4_collector.py` untouched | ✅ **none appear in the commit** |
| **Gate 3 — L4/L5 unreachable from PHP specifics** | ✅ **`Domain/` and `Application/` contain ZERO references** to `token_get_all`, `T_*` tokens, any parser, `rawLexeme` or `Provenance` |
| `token_get_all` confined to the binding | ✅ **exactly one production file** — `Infrastructure/Php/PhpFactExtractor.php` |
| The neutral-layer suite runs with no PHP input | ✅ `CohesionSemanticsTest.php`'s only match is **its own opening `<?php` tag** — it constructs L3 facts by hand and never invokes the extractor |
| **Conformance guard honoured** | ✅ **no expected-evidence file created** — *"honoured by producing nothing, not by producing something careful"* |

## 2 · ⭐ Gate 3 is now executable, not asserted

The accepted design's pre-delivery gate asked whether L4/L5 *could* be implemented without PHP specifics. **The delivery converts that from an argument into a running test:** a 19-test suite constructs facts by hand and exercises the cohesion layers **with no PHP source, tokens, AST or provenance in the process**. As the implementer puts it: *if a cohesion rule ever needed a spelling, that file could not be written.* **Governance confirms the property independently by static reference count, above.**

## 3 · The single divergence is the predicted one

Dual-run diagnostic: **10/10 identical on existing units**, with **one divergence — `trait-user.php` gains a unit** — which is exactly what **13.5** (trait = analysed unit) requires and what the accepted design flagged as **OQ-3**. **Predicted in design, measured in implementation, `expected.json` untouched.** ✅ Divergence by design, not regression.

## 4 · Three corrections the implementer recorded against itself

Registered because self-correction under measurement is evidence of method, not noise:

- **a heredoc's `$this->b()` interpolates `$this->b`; the trailing `()` is literal text** — the implementer's own test expectation was wrong and was corrected **to the language's truth, not the assumption**. *This is also precisely why the AST reference reported 2 where the regex scanner fabricated an edge and reported 1.*
- **an alias only denotes the analysed unit when it actually resolves to it.**
- **one real extractor defect** — skipping a method body must not alter brace depth; the first version silently lost every method after the first. ***"The same class of bug as NEW-2."***

## 5 · What was deliberately NOT done

⛔ **Declared expected evidence not produced** — it requires `G-KOS-CONTRACT-ARTIFACT-UPDATE`, still **unexercised**; the conformance test layer is blocked on it, correctly. ⛔ No self-verification · no self-acceptance · **the lane was not closed by the implementer** · legacy calculator not retired · no existing artifact modified.

**Classified gaps carried forward, not hidden:** `O-EKS-1` (deliberately untouched) · attributes in every grammatical position · BOM/non-UTF-8 · `goto` · PHP 8.4/8.5 syntax · the provenance store (boundary right, store not built) · a second binding.

## 6 · Next actor — independent verification

The Track-1 grant's sequence ends *"deliver for independent verification"*, and **independent verification remains a separate actor and assignment**. **No verification assignment exists yet**, and its grant scope — what the verifier must attempt to falsify — is a **PO/ARB decision**, not Governance's to invent.

**Recommended scope, for the PO/ARB to issue or amend:** re-derive Gate 3 independently rather than accept the static count · re-run the suites and confirm the 55/105 figures · **attempt to falsify the three corrections**, especially the heredoc token claim · confirm the decided semantics against 13.3/13.5 case by case · confirm the single divergence is the OQ-3 one and nothing more · confirm no artifact modified (hashes) · **confirm no expected evidence was generated or used as an oracle** · classify Observed/Declared/Inferred/Unknown · **the implementer must not verify.**

**Traceability:** delivery `4c6c1dac` · delivery record `2026-08-18-…-track1-delivery.md` · `G-KOS-CONTRACT-IMPL-TRACK1` + AMD1 · accepted design §21 (the gate) · OQ-3 · 13.3/13.5 · `G-KOS-CONTRACT-ARTIFACT-UPDATE` (unexercised)
