# `20260911-1508` — bootstrap session: the corpus purpose and the record model

| | |
|---|---|
| **ID** | `S-01` |
| **Stage** | `10-capture` |
| **Kind** | session |
| **Provenance** | `DERIVED` — the record is written by the agent |
| **Claim layer** | `OBSERVATION` — the agent reports what was said |
| **Governance** | `NONE-RECORDED` |
| **Status vector** | `DERIVED · OBSERVATION · WITNESSED · N/A · ACTIVE · NONE-RECORDED` |
| **Scope of this session** | **in:** what this station is for, and whether its records can tell a participant's statement from the agent's synthesis. **out:** all theory content |
| **Anchor** | the exchange of 2026-09-11, quoted verbatim in §1 |

> **What this session was trying to answer.** *What is this station for, and can its records keep
> source material distinguishable from agent material?*

**Tagging used in this record.** The session *file* is the agent's — `DERIVED`. The *statements
inside it* are not. Each block below carries its own `provenance · claim_layer` (§C4.1).

**A `PRIMARY` block is never edited, tidied, summarised in place, or reclassified** (§C4.3).

---

## 1. What was said

### 1.1 — the purpose correction

> **`PRIMARY · UTTERANCE`** — participant, 2026-09-11

> "your job in not construct theory from old corpus but to create corpos so that we can construct the
> theory form corups we build now."

*Verbatim, including its spelling. Not tidied — the quote is its own anchor (§C9).*

> **`DERIVED · OBSERVATION`** — agent

> The statement names a source ("old corpus"), a target ("corups we build now"), and an ordering
> between them ("not … but"). It is a statement *about the station*, not about the theory.

> **`DERIVED · INTERPRETATION`** — agent

> Read against the machinery as it stood, this removed the ground the three `PENDING` vocabulary
> pointers stood on: they pointed outside the station, and the sentence says the work is inside it.

### 1.2 — the approval, with constraints

> **`PRIMARY · UTTERANCE`** — participant, 2026-09-11

> "Approve A1, A2 and A4.
>
> Approve A3 conceptually, but implement it carefully:
>
> 1. `claim_layer` is a corpus-record classification, not a KnowledgeOS theory vocabulary and must not
>    be presented as a newly established theoretical ontology.
> 2. Keep provenance and epistemic role conceptually separate. Do not collapse 'who/what produced this
>    record' with 'what epistemic role the record plays'.
> 3. Preserve the distinction between: source/human utterance, agent observation, agent
>    interpretation, hypothesis, finding, governance decision.
> 4. Do not model governance decision as merely the next epistemic level after finding. Governance
>    status is orthogonal to epistemic status.
> 5. A source utterance must remain identifiable as source material even when it expresses a
>    hypothesis, proposal, interpretation or other content.
> 6. Agent observations and interpretations must never be silently classified as source evidence.
> 7. Repeated utterances may be captured repeatedly for provenance, but repetition must never be
>    counted as independent corroboration.
>
> Before beginning substantive capture, amend the DMS and run one bootstrap capture test using a real
> current research exchange.
>
> The purpose of this station is now explicitly:
>
> 'Build the provenance-preserving research corpus from the research we conduct now, so that
> KnowledgeOS theory can subsequently be constructed from that corpus.'
>
> Do not reconstruct the old theory from the historical corpus as the primary mission.
>
> Do not create new theoretical concepts merely to repair the DMS. If an ambiguity remains, record it
> as an open methodological question."

*Verbatim. The seven numbered constraints are the source of §C4.1–§C4.4 and §C4.3 respectively; the
mapping is recorded in §2.*

> **`DERIVED · OBSERVATION`** — agent

> Three of the seven constraints name a *separation* (2, 4, 6); two name an *immutability* (5, 6);
> one names a *counting* rule (7); one scopes the new classification (1). Every one of them is a rule
> about **not collapsing two things**, which is why the amendment took the shape of separate fields
> rather than a richer single field.

---

## 2. What was decided

| # | decision | act | → record |
|---|---|---|---|
| 1 | **A1** — `C10`'s borrowed-pointer register withdrawn; the three `PENDING` rows removed | `adopted` | `00-CONVENTIONS.md` §C10 |
| 2 | **A2** — `C6` `PRIMARY` redefined: *"not produced by the agent"*, not *"not produced here"* | `adopted` | `00-CONVENTIONS.md` §C6 |
| 3 | **A3** — `claim_layer` added as an axis separate from `provenance`; coherence rules `§C4.2`; utterance immutability `§C4.3`; governance orthogonality `§C4.4` | `adopted` | `00-CONVENTIONS.md` §C4 |
| 4 | **A4** — repetition is never corroboration, recorded **at intake** | `adopted` | `10-capture/_STAGE.md` |
| 5 | The field `origin` (`EVIDENCE` · `DERIVED-PROPOSAL`) **withdrawn** as redundant with `provenance` + `claim_layer` | `NONE-RECORDED` — **taken by the agent as an implementation consequence of A3, and reported for confirmation**; it was not in the approved set | `00-CONVENTIONS.md` §C4 |

**Which constraint produced which rule:**

| participant's constraint | where it now lives |
|---|---|
| 1 — classification, not theory | §C4.1 *"machinery, not theory"* + §C10 test |
| 2 — provenance ≠ epistemic role | §C4.1 — two fields, neither derived from the other |
| 3 — six distinctions preserved | §C4.1 values + §C4.4 (the sixth is its own axis) |
| 4 — governance is orthogonal | §C4.4 + `60-governance/_STAGE.md` |
| 5 — an utterance stays source material | §C4.3 — immutability, cite-never-reclassify |
| 6 — agent material never source evidence | §C4.2 incoherent pairings |
| 7 — repetition is not corroboration | `10-capture/_STAGE.md` *"Repetition is not corroboration"* |

*The `act` column is the **governance** axis (§C4.4) — which acts were recorded, not how strong the
decision is. These are acts on **this station's own machinery**, licensed by the participant's
approval; they are not acts on a theory claim and carry no status at `60-governance/`.*

---

## 3. What was left open

| # | question | → record |
|---|---|---|
| 1 | Is "source form" (spoken utterance vs ingested written source) a separate axis from epistemic role, or does `UTTERANCE` cover both? | `OQ-01` |

---

## 4. What was NOT discussed

- **No theory content.** Nothing about KnowledgeOS, its objects, its mathematics or its concepts was
  discussed in this session. The corpus contains no theory statement yet.
- **The governance axis was not exercised on a claim.** No theory claim has reached
  `60-governance/`, so every record in this session carries `governance: NONE-RECORDED`. The acts in
  §2 attach to *machinery decisions*, not to claims. **Orthogonality is therefore demonstrated
  structurally — separate field, never a `claim_layer` value — and not yet empirically, on a claim.**
  *Recorded as an absence so that an untested rule does not read as a tested one.*
- **Stages 20, 30, 40, 50, 70 were not exercised at all.** No form, no pair, no membership, no
  validation, no theory section exists yet.
- **The historical corpus was not consulted.** Consistent with §1.1.

---

## 5. Corrections to earlier records

None. This is the first capture record in this root.
