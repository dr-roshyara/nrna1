# Decision Registration — OQ-2 · OQ-4 · the conformance method · future language extension

**Registered by:** Governance, on the delivered PO/ARB ruling · 2026-08-18

---

## ✅ OQ-2 — Analysis scope · **DECIDED: SINGLE PHP FILE**

> **"The analysed scope is one PHP source file."**

**Rationale, as given:** it preserves the accepted *"analysed in isolation"* boundary and **avoids introducing cross-file namespace, import, identity, autoloading or repository-wide dependency semantics that have not been decided by the contract.**

**This unblocks OQ-1.** Identity must be *"stable and unique within the analysed scope"* — that scope now has an extent, so Architecture can select the identity rule against a defined boundary. *(The proposal's own note is discharged: the pinned limitation implied the file, and implication has now become a ruling.)*

## ✅ OQ-4 — PHP runtime dependency · **NOT APPLICABLE TO CURRENT SCOPE; DEFERRED**

> **"There is no current Python implementation for which a PHP runtime dependency must be decided."**
> **"When a future non-PHP binding is commissioned, its extraction/runtime architecture will be decided as part of that binding's own governed architecture work."**

**Governance note:** this is the disposition Governance asked for and it is the honest one — **the question is not answered, its premise is gone**, and the ruling says so rather than recording a false "no". The question returns intact with the premise, inside the future binding's own work item.

## ✅ CONFORMANCE METHOD — **ADOPTED: declared-specification conformance, three levels**

| Level | Conformance requirement |
|---|---|
| **L3 Fact** | actual extracted node/unit facts and relationship inputs **must match the declared expected facts** |
| **L4 Semantic** | the resulting cohesion graph **must match the declared expected graph — node set + edge set** |
| **L5 Metric** | the calculated LCOM4 **must match the declared expected metric** |

> **"Final metric equality alone is insufficient."**
> ⭐ **"Implementation agreement is diagnostic only and is not a conformance authority."**
> **"The normative authority is the declared contract + expected evidence."**

**This is the answer to the question the language-scope decision opened.** With one implementation there is no differential comparison — and rather than weakening the evidence, the ruling introduces **a third authority above both implementations**: the declared specification. *Agreement between implementations can no longer certify anything; it is demoted to a diagnostic signal.* This implements Decision 13.7 rather than replacing it, and it retires the failure mode the estate measured twice — `N-2` (a fixture population blind in the same place as both implementations) and `O-2` (a probe that observes only the final number cannot notice it failed).

## ✅ FUTURE LANGUAGE EXTENSION — **RECORDED**

A future Java/Python/C# binding is **a separate governed work item**; it must produce **the same L3 Fact Model** and conform to **the same L4/L5 semantics**. ⛔ **"The current PHP implementation does not need a second implementation language merely to preserve the former differential experiment."**

## 📌 The work item's identity has changed — registered, because it changes what "done" means

> **"We should now stop calling this a 'two-language neutrality experiment' for the current phase. It has evolved into a language-neutral model with independently validated language bindings, with PHP as the first binding."**

And the claim it now supports, precisely bounded:

- **Question A — can we calculate LCOM4 correctly?** *Answerable now, with PHP only, against declared evidence.*
- **Question B — is the model genuinely language-neutral?** ⚠️ **Not fully demonstrable empirically with only PHP.** It becomes demonstrable when a second binding produces the same L3 facts. **Current scope proves the PHP binding conforms to the language-neutral model; it does not prove the model is neutral.**

*Governance records this distinction because the work item's name still says "contract neutrality", and a reader in six months would otherwise take the current phase's completion as having settled Question B.*

## ⬜ Recommended but NOT included in this ruling — returned to the PO/ARB

The earlier passage recommends recording a **long-term target**: *the LCOM4 engine SHALL be language-neutral with **one authoritative implementation**; **Python is the planned authoritative implementation** of the L4/L5 cohesion engine; the existing PHP calculator remains temporarily as a reference and **may be retired only after independent verification demonstrates equivalence** under the accepted evidence model.*

**The operative ruling does not contain it**, and Governance does not infer it — the two passages are compatible in time but only one was issued as the act. **Register it as a future architectural direction, or leave it unrecorded?**

## Boundaries

⛔ **No implementation authorization is granted by this act.** ⛔ No artifact modified. ⛔ Architecture D, the qualifier rules and the decided silences are untouched. ⛔ The existing Python collector is not retired (out of current scope ≠ deleted).

**Traceability:** the PO/ARB ruling 2026-08-18 · language-scope registration · implementation architecture `d2f2e5a4` (`OQ-1`…`OQ-4`) · Decisions 13.1/13.3/13.5/13.7 · breadth report `17e4f066` (`N-2`) · `O-1`/`O-2` · founding grant `G-KOS-CONTRACT-EXP`
