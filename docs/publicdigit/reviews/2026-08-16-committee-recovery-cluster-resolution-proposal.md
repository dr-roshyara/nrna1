# Committee-Recovery Cluster — Resolution Proposal (`EM-GOV-064`/`065`/`066` PREPARED)

**Type:** Governance cluster resolution (Session 2, commissioned 2026-08-16) · **Basis:** adopted rules + the verified `EM-BRQ-001` findings only.
**⛔ ALL THREE RULES ARE PREPARED, NOT ADOPTED — each is NEW POLICY requiring separate adoption, exactly as the commission requires. Not touched, per the commission's own exclusions: Model A's design · the configured denominator (`EM-GOV-057`) · the representative threshold (`EM-GOV-037`) · any new acceptance model · Architecture.**

## 0 · A vocabulary reading, stated first because `EM-GOV-030` protects it

**The commission's items 1 and 3 say *"representative unavailability"* and *"Representation Votes"*. The verified gaps are COMMITTEE-side** (`EM-GOV-056`'s unavailability→vacancy collapse; a cast **Committee Vote** across mid-gate replacement) — **and the representative side already holds its answers in adopted text** (`EM-GOV-042` defines representative unavailability; `044`/`045` settle post-freeze resignation and the configured vote). **Read throughout as: Committee member · Committee Vote.** One line overrules this reading if a representative-side question was intended — but then the answer is largely *already adopted*.

**The PO's carried-forward constraint, honoured as the ordering of this document:** *the unavailability/vacancy distinction must be resolved before the onset of Inoperative can be made deterministic, because the onset determines when the Committee-restoration clock begins and therefore how much recovery time remains.*

---

## 1 · `EM-GOV-064` (PREPARED) — Committee-side states: vacancy is an EVENT, never an inference

> *"A Committee seat becomes vacant only upon a recorded vacancy event: the member's resignation (with reason recorded), the member's death or permanently established incapacity, or the member's loss of eligibility or independence under the applicable Election Rules. Temporary unavailability is not a vacancy, does not trigger seat-filling, and is recorded as an event when it occurs. A member's temporary unavailability means no Committee Vote is expressed by that member for the affected decision; the denominator and threshold are unchanged."*

**What this consumes rather than invents:** it is the Committee-side analogue of adopted `042`/`044`/`045` — the same five-state discipline, transplanted; the arithmetic consequence is already adopted (`057`: *"with 2 available, 2 accepting PASSES"*). **What is genuinely new:** the closed LIST of vacancy events, and the negative rule that absence alone never converts into a vacancy. **Why event-based, not time-based:** a time-based conversion would be the deemed-by-time shape again — weaker here (its consequence is replacement, not termination), but the record's discipline is facts-not-inference, and every listed event is a recordable fact. **Amendment consequence:** `EM-GOV-056`'s phrase *"becomes unavailable"* is conformed to *"a vacancy event occurs"* — that phrase was the verified defect (N-2/`056` collapse), and this is its repair at source.

## 2 · `EM-GOV-065` (PREPARED) — "unable to function" is ARITHMETIC; the onset of Inoperative is a recorded event, and nobody declares it

> *"The Election Committee is unable to function when the number of non-vacant seats is less than the number of Committee Votes required by the applicable threshold. Election Inoperative begins at the recorded vacancy event that causes this condition, without any declaration or determination by any actor. The election's inability to be restored is evidenced solely by expiry of the Committee-restoration period; no earlier finding that restoration 'cannot occur' exists. The halted-election recovery clock's pause and the Committee-restoration clock's start are computed from the recorded onset event."*

**Derivation, step by step:** ① with `064` in place, *vacant* is a recorded fact, so *non-vacant seats < required* is computable from the record alone — **this is the Option-C move the `EM-OPEN-109` analysis validated: a question about recorded facts needs no discretionary determiner, and the classifier-independence problem never arises because there is no classifier.** ② `058`'s second trigger — *"cannot restore"* — is a world-fact no rule can observe; binding it to restoration-period expiry converts it into a governed, recorded event — **the same conversion `EM-OPEN-047`/`EM-GOV-063` already made for the halted clock.** ③ V-BRQ-1's sharpening is discharged: **the paused halted-clock remainder becomes computable, because the onset is a recorded moment** (`060`'s non-retroactivity now has a fact to run from). **Worked example (S-06 re-derived):** 3 constituted, threshold 2; two seats become vacant by recorded events → non-vacant 1 < 2 → **Inoperative at the second vacancy event** → halted clock pauses with its remainder as of that moment; restoration clock starts → filled in time: resume; not filled: cancelled (`058`). **Temporary absence of two members (no vacancy events): NOT Inoperative — the election is halted at the gate and the halted clock governs**, which is exactly the S-05/S-06 boundary the cluster needed.

## 3 · `EM-GOV-066` (PREPARED) — a validly cast Committee Vote stands; a seat expresses one position per decision

> *"A Committee Vote validly cast remains cast and recorded notwithstanding the member's subsequent unavailability, resignation, or the filling of the seat. A replacement member does not re-cast, alter, or supplement the seat's already-expressed position for that decision; a seat expresses at most one position per acceptance decision. A replacement member participates fully in decisions on which the seat has not yet expressed a position."*

**Two readings existed (stands vs lapses/re-cast); the recommendation is *stands*, for three grounds in force:** ① **version-binding** — acts validly performed remain valid; ② **`EM-GOV-005`** — the cast vote is history, and history is never reset; ③ ⚠️ **the manipulation test decides it: under the lapse reading, inducing a member's resignation ERASES their already-cast vote — reshaping the record to escape an unwelcome position, the `EM-OPEN-022` Reading A shape.** The stands-reading makes that manoeuvre worthless. **Boundary honoured:** *recovery restores exercise, not shape* — the seat (shape) persists; the replacement gains the seat's future exercise, never its spent one. `056`'s *"does not alter any decision already made"* is thereby sharpened to cover expressed positions, not narrowed.

## 4 · What the cluster does NOT resolve — stated so silence cannot be read as an answer

⛔ **The gate's missing time-bound (`EM-OPEN-053`) stands** — `065` bounds Committee restoration, not Committee indecision. ⛔ **The external authority's identity (`066`/`094`) stands.** ⛔ **The pre-Chief recovery initiator (NEW ④) is NOT touched — the separate commission the PO ordered.** ⛔ **`EM-OPEN-110`'s naming stands.**

## 5 · Adoption line, if the PO so decides

> *"I adopt EM-GOV-064, EM-GOV-065 and EM-GOV-066 as prepared, including the conforming amendment to EM-GOV-056's vacancy wording."*

**After adoption: re-run S-05, S-06, S-07, S-08, S-09 (the affected scenarios only) — both passes' protocol — then the final Model A verdict.**

**Traceability.** Commission (this thread, verbatim constraints honoured) · `EM-BRQ-001` comparison N-①–③ · `EM-GOV-005`/`030`/`042`/`044`/`045`/`056`/`057`/`058`/`060`/`061`/`062`/`063` · `EM-OPEN-047`/`109` analyses · Reading A · A-3.
