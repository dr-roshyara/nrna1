# Registration — Implementation AUTHORIZED · Track 1 — PHP Adapter / Language Binding

**Registered by:** Governance, on the delivered PO/ARB act · 2026-08-18
**Grant:** `G-KOS-CONTRACT-IMPL-TRACK1` · **Assignment:** `S3-implementation-track1-php-adapter` · **START not performed.**

## 1 · What is authorized

Implementation of **Track 1 — the PHP Adapter / Language Binding**, realizing the accepted Architecture D:

```
PHP source → PHP Adapter / B3 extraction → L3 Fact Model
           → existing Python analytical capability → L4/L5 cohesion → LCOM4
```

**Scope:** PHP only · one PHP source file per analysis · the accepted L3 Fact Model · preserve qualifier kind · preserve determinability · preserve stable anonymous-class identity (OQ-1 declaration path) · implement the decided enum/trait/interface/nullsafe semantics · produce the declared conformance evidence.

## 2 · ⚠️ A supersession this act performs — recorded, not inferred

**The pipeline places the existing Python analytical capability at L4/L5 in *current* scope.** Three standing records say otherwise:

| Standing record | What it said |
|---|---|
| `IMPL-ARCH-AMD1` | *"PHP is the sole implementation language for the current implementation"* |
| Accepted design §11.1 | `connectedComponents()` **moves up to L5** — on the PHP side |
| Accepted design §11.2 · Future direction | `lcom4_collector.py` **out of current scope**; one neutral engine marked **FUTURE ONLY — NOT CURRENT SCOPE** |

**Where the acts conflict, the later act governs.** Governance records **which parts are superseded** rather than leaving the record silently inconsistent: **AMD1's sole-language clause is narrowed, and part of the future direction is brought into current scope.**

**AMD1's other clauses stand unchanged** — the L3 model and L4/L5 engine remain language-neutral boundaries, the binding stays separable from the model, and *"PHP is the first language binding, not the definition of the language-neutral cohesion model."*

*Governance did not resolve this on its own reading: the act was stated twice in one turn, the second time with its reasoning — "we should not rebuild the existing Python analysis scripts unless Track 1 proves they cannot consume the accepted L3 model." The intent is deliberate, so it is registered rather than blocked.*

## 3 · ⚠️ The reuse premise is untested — and the act itself makes it conditional

*"Reuse existing Python analysis scripts **where they already realize** the accepted L4/L5 capability"* · *"unless Track 1 **proves** they cannot consume the accepted L3 model."*

**Nobody has assessed the Python analysis against the nine decided rules.** The accepted design measured the **PHP** side and found **eight of nine require change**; the Python side has had **no equivalent assessment** — and it was itself the subject of a FAIL verification and nine divergence mechanisms, all at extraction rather than cohesion.

**First task registered accordingly:** *map the existing Python scripts and identify the exact L3 input contract they require — report the fit, do not assume it.*

## 4 · Prohibitions carried

⛔ change the accepted semantic rules · redefine the L3 Fact Model · change the conformance authority · **treat the existing PHP calculator as the specification** (Decision 1) · introduce Java or another language · **silently change expected evidence** (OQ-3's authorization is separate and unexercised) · retire the legacy PHP calculation.

**And one binding carried from design into implementation:** the pre-delivery gate — *no PHP source text, spelling, AST nodes, tokens or parser objects reachable from L4/L5* — **binds the implementation, not only the design.** PHP-specific parsing stays inside the adapter.

## 5 · Sequence and separation

1 implement the adapter · 2 produce L3 facts · 3 integrate with the existing Python analysis · 4 add/update conformance tests **as separately authorized** · 5 deliver for independent verification.

**Independent verification remains a separate actor and assignment.** The implementer does not verify, accept, or close its own work.

## 6 · Human action required

**START the Track 1 implementation** — the assignment is registered and handed off; the START is the PO/ARB's act and has not been performed.

**Traceability:** the PO/ARB act 2026-08-18 (twice, with reasoning) · accepted design `adc5c8e8` / acceptance `c6c4f984` · `IMPL-ARCH-AMD1` (superseded in part, §2) · future direction registration · Decisions 1 · 13.1 · 13.3 · 13.5 · 13.7 · OQ-1 · OQ-2 · OQ-3 · OPEN-1
