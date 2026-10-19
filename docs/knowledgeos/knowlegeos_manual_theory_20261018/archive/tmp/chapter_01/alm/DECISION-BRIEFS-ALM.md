# Decision briefs: policy parameters left open by the layered model (2026-10-18)

These are prepared, not requested. Each brief is a choice that **evidence and mathematics cannot make**. The research recommends; the Principal decides. Nothing here is adopted.

---

## Brief P-λ · When may "no record" mean "not authorized"?

**Business question:** if our records show no permission for something that happened, may we say it was **unauthorized**, or only that we **cannot tell**?

**Why research cannot settle it:** late recording of genuinely given acts is permitted in practice (S0095, S0103). Mathematically, while late recording is unbounded, any "not authorized" verdict based only on a missing record can be wrong (Theorem 6, checked over 2,611 bounded cases).

| Option | Meaning | Consequence |
|---|---|---|
| **λ = ∞ (open)** | absence never proves anything | **fully honest, never decisive.** Only a positive record of revocation, a bar or a refusal yields "not authorized". Matches r1.1's "cannot determine, never 'not authorized'" |
| **λ = a fixed window** (e.g. 7 days) | after the window, missing records count as absence | **decisive, but defeasible.** A late record after the window *changes the verdict*, which must be reported as a recognition change, never back-dated (Theorem 4) |
| **λ = 0 (closed)** | the records are always presumed complete | maximally decisive. It contradicts current practice (late transcription is legitimate) |

**Recommendation:** λ = ∞ as the default. Introduce a finite λ only together with a duty to record within that window. **Evidence status:** no source defines λ.

---

## Brief P-κ · Do decisions survive the end of the authority that made them?

**Business question:** if someone's mandate ends or is revoked, do the decisions they validly made while in office still stand?

| Option | Consequence | Evidence |
|---|---|---|
| **persist** | decisions stand until explicitly ended by a new act | [Repo] ADR-001 (ACCEPTED, implemented): "historical decisions maintain their legitimacy at decision time"; Stress Test 8: prior decisions "remain as historical fact"; AuthorityDiscovery Conflict 4 |
| **cascade** | every decision ends automatically with the mandate | no source found. It would mass-end decisions on any change of role |

**Recommendation:** `persist`, with **explicit annulment acts** for exceptions (R-77: "explicit act, never inference"). **Evidence status:** supported only by product-side precedent; no KnowledgeOS ruling exists.

---

## Brief P-A0 · Two conflicting acts at the same moment: which wins?

**Business question:** two authorized people revoke each other's authority at the same moment. Who prevails?

**Why it matters:** without a tie-break rule the outcome is **not unique**; two equally consistent answers exist (`test_same_instant_semantics_not_unique`). With a tie-break rule, the rule itself decides the case.

| Option | Consequence |
|---|---|
| the order of giving (who acted first) wins | unique. It needs a reliable order of giving, which the records do not hold (they keep sequence of recording, not of giving) |
| a higher-ranking authority wins (A-2 hierarchy) | unique if ranks differ; still needs a rule for equal rank |
| **neither** (both are suspended, escalated to the PO/ARB) | unique and conservative. It adds a third outcome, "suspended pending ruling" |

**Recommendation:** rank first; for equal rank, **escalate**, so that neither act takes effect until a ruling. This is the most consistent with A-1.3's "never infer authority". **Evidence status:** no source; there is no observed case.

---

## Brief P-C1-wording · What does the book call "valid"?

**Background:** the research finds that the C1 dispute dissolves into layers:
- the act's **validity** (fixed by acts);
- its **recognition** (fixed by records as they stood);
- its **operational effect** (requires the record).

The remaining question is **vocabulary only**.

| Option | Wording |
|---|---|
| **W1** | "valid" = given by someone with authority at the time (the act layer). "Effective" = valid **and** recorded |
| **W2** | "valid" = recorded and authorized (the effect layer). The act layer gets a different word, e.g. "properly given" |

**Recommendation:** W1. It matches ARCH-INV-1, "the mechanism records authority; it never grants it", ES-001.2, and the corpus's own treatment of late-recorded acts as anomalies rather than invalid acts. W2 is coherent, but it would call properly given acts invalid until transcribed.

**This brief is the C1 decision, reduced to wording; it stays reserved.**
