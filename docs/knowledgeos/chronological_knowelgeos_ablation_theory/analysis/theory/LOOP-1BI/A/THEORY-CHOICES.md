# THEORY-CHOICES (r3)

SCHEMA-r3 gives every coding question an answer, or UNKNOWN. Four questions remain that are **theoretical, not coding, questions**. Answering any of them would decide part of what H-D and H-B disagree about. r3 does not answer them. Instead, it records the distinguishing information as tags, so that each alternative below can be computed from the coded data at analysis time. The analysis must state which alternative it uses, and should report results under each alternative.

---

## TC-1 (G1): Does an instrument's self-performance count as an act?

This choice is **inherited from r2, not made by r3**. r2's R-A3 already makes one choice: a register row that self-performs one of five listed norm-making operations is ACT_OCCURRED. That choice was flagged as building H-D's view into the observations. r3 restricts R-A3 to its literal text (closed list, register rows only, performative in the span) and does not extend it. It tags every act with `act.basis`.

| Alternative | What counts as an act | Consequence for what the data can show |
|---|---|---|
| **1a. r2 as written (r3 default coding)** | REPORTED + SELF_PERFORMED | Norm-making acts are observed wherever a register row performs a listed operation. Every such act is a register act by construction, so "norms change through acts" is partly true by definition for register rows. That part cannot count as evidence for H-D over H-B. |
| **1b. Reported acts only** | `basis = REPORTED` | The data show only acts that some text reports as having happened. The register's own rulings stop being act observations, so norm-making acts become rare. This **under-counts** what both hypotheses agree exists. It weakens tests of H-D's "norms change only through acts", because many norm changes then have no observed act. |
| **1c. Extended self-performance** | REPORTED + SELF_PERFORMED + SELF_PERFORMED_OUTSIDE_RA3 | Classifications, recommendations, applications, approvals, retirements and matrix verdicts all become acts. Act counts rise most for governance-text operations. This extends the flagged H-D-favouring convention further. |

All three are computable from `act.basis` and `act.operation`. r3's coded `act.state` follows 1a only because that is the unrevised r2 text.

---

## TC-2 (G2): Are behaviours named in an adopted instrument substantive norms the record carries?

Example: a promotion matrix adopted by a register ruling recommends that a named behaviour be promoted. Is the record then carrying a norm on that **behaviour** (e.g. REQUIRED: reuse before create), or only a verdict on the **meta-act** (ELIGIBLE: promote)?

r3 codes by literal attachment (R-N1): the verdict is coded on *promote*. The behaviour is MENTIONED (D11) and goes in `about_target`. r3 does **not** decide whether adoption of the instrument incorporates the mentioned behaviour as an in-force substantive norm.

| Alternative | Consequence |
|---|---|
| **2a. No incorporation.** Only stated norms count. | The data show the record carrying norms **about governance meta-acts** wherever a behaviour is handled by promotion or adoption. They show substantive norms only where a span states them. This looks closer to H-D's "the governed work is outside what the record needs to contain". The cause is partly that the substantive text lives in other documents, which this rule does not follow. |
| **2b. Incorporation by reference.** An adopted instrument's positive verdicts count as substantive norms on the mentioned behaviour. | The data show the record carrying norms on governed work, which looks closer to H-B. This requires an inferred deontic value that no span states (REQUIRED? PERMITTED?), so those observations would be INFERENCE throughout. |

Both are computable. 2b = records with `norm.deontic ∈ {ELIGIBLE}` and `basis = VERDICT`, joined to the adopting act's record.

---

## TC-3 (G4): Does a norm stated by a non-effective (PREPARED) act have force?

r3 codes the act as ACT_ATTEMPTED / PREPARED. It codes the stated content (e.g. AUTHORIZED) as a NORM, with `stated_by` carrying the PREPARED marker. It does not code **force**.

| Alternative | Consequence |
|---|---|
| **3a. Force follows the making act's status.** PREPARED norms are not in force until a later act gives them effect. | The data can test whether work conforms to *effective* norms only. A prepared authorization followed by work would count as non-conformity. |
| **3b. Force follows stated content.** | Prepared and adopted norms are treated alike. Any difference between them, the thing H-D predicts matters, cannot appear in the analysis. |
| **3c. Force undecided; report separately.** | The cost is smaller cells. The difference between prepared and adopted norms is shown, not assumed. |

Choosing 3a in the schema would build H-D's second bullet ("force depends on the status of the act that made it") into the observations. Choosing 3b would rule it out by definition.

---

## TC-4 (G6): Do performative-only norms count as norm observations?

r2's R-A3 second sentence requires a NORM on every self-performing row. When a row's only normative words are the performative itself ("ANNOTATE is adopted", "adopts R-81 through R-85"), r3 codes STATUS_CONSTRAINT with `norm.basis = PERFORMATIVE_ONLY`.

| Alternative | Consequence |
|---|---|
| **4a. Count them.** | Every such act generates a co-located norm. Act–norm co-occurrence is then partly definitional, and cannot be used as evidence for H-D's "norms change only through acts". |
| **4b. Exclude them.** | Norm counts contain only norms with independent textual content. Status changes that are stated only through a performative do not appear as norms, though they still appear as acts. |

Both are computable from `norm.basis`.

---

No other gap required a theoretical choice. G3 (priority) and G5 (REQUIRED vs STATUS_CONSTRAINT) are closed on observational and lexical grounds; see RATIONALE.md.
