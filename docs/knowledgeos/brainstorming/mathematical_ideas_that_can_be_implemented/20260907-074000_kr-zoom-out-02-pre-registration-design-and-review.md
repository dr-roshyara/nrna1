**# \`KR-ZOOM-OUT-02\` — \*\*PRE-REGISTRATION DESIGN\*\***

**\*\*Status:\*\*** \`[DESIGN]\` · **\*\*NOT pre-registered · NOT authorized · NOT run.\*\***

**\*\*Date:\*\*** 2026-09-05 · Theory v1.2 FROZEN · kernel NOT SELECTED · no Zoom-out definition exists

**\*\*Predecessors:\*\*** \`KR-ZOOM-OUT-01\` (**\*\*failed as primary confirmatory / diagnostically informative\*\***)

· \`KR-ZOOM-OUT-02-SELECTION-AUDIT-2026-09.md\`

\> This artifact is the **\*\*design of a pre-registration\*\***, per the owner's instruction that the next

\> step be a pre-registration design rather than execution. **\*\*It ratifies nothing.\*\***

**---**

**## 1. The protocol this run must obey**

$$\boxed{\\;\text{Design} \to \text{Pre-register} \to \text{Calibration} \to \text{Freeze} \to \text{Analysis}\\;}$$

**\*\*Frozen 2026-09-05\*\*** (theory doc 10 §7a). \*\*No analysis-dependent choice of estimand or

population.\*\*

**### 1.1 Calibration is a \*\*separate executable\*\*, gated**

\`[REC]\` \`calibrate.py\` runs alone, writes \`data/calibration.json\`, and **\*\*exits\*\***. Its output is

inspected and the gate decided **\*\*before \`analyse.py\` exists in a runnable state\*\***.

\*\*\`KR-ZOOM-OUT-01\`'s gate failure (0.294 / 0.265 vs 0.30–0.80) was invisible until every outcome

had been seen\*\*, because calibration and analysis ran in one pass. That is a **\*\*process defect\*\*** and

this is its repair.

**---**

**## 2. ⚠️ The population correction — \*\*stratify, do not filter\*\***

$$\mathcal P = \mathcal P\_{\text{det}} \\;\cup\\; \mathcal P\_{\neg\text{det}}$$

\`Determine\` reached ∈ {yes, no} is a **\*\*declared stratification variable\*\***, **\*\*never\*\*** a population

filter.

\| | filtering | **\*\*stratifying\*\*** |

\|---|---|---|

\| can hide the stratum that explains the result | **\*\*yes — and it did\*\*** | no |

\| permits a post-hoc population switch | yes | **\*\*no\*\*** |

\| \`M1\`-as-artifact detectable | only by audit | **\*\*immediately\*\*** |

\> **### The failure this repairs**

\> \`KR-ZOOM-OUT-01\` reported \`M1\` = 0.634 as the single winner. **\*\*\`M1\` ≡ the no-determination rate\*\***

\> (0.6333), to three decimals. The null stratum became the "winner". \*\*Reporting stratum sizes as a

\> first-class quantity makes that impossible.\*\*

**### 2.1 Why conditioning is defensible here, and why we still don't do it**

The audit established that $D$ is an **\*\*ancestor\*\*** of the outcome ($\mathrm{zoom\\\_out}$ consumes it),

not a collider; the structural backdoor via evidence-graph size is empirically negligible

(10.755 vs 11.052); and the \`M0\` inversion **\*\*survives stratification unchanged\*\*** (0.4945 → 0.4977).

**\*\*So filtering would probably not have been fatal. Stratifying is still better\*\***, because it

removes the *\*possibility\** of the error rather than arguing it away — and because

**\*\*root depth, detail count and coverage were never stratified.\*\***

**---**

**## 3. Every estimand is labelled \*\*contract-relative\*\***

\`[REC]\` \`FR-004\` **\*\*candidate\*\***: $\mathrm{Determine} = \mathrm{Determine}(K, Q, C, E\_C, S, R)$.

$\mathcal P\_{\text{det}}$ is a property of **\*\*(domain × contract)\*\***, not of the domain. \*\*No estimand

computed on it may be reported as "what Zoom-out does"\*\* — only as \*"what Zoom-out does under this

contract, at this standard, in this stratum."\*

\`[REC]\` **\*\*Report the stratum sizes under at least two epistemic standards $S$\*\*** (e.g.

$\tau\_m = 1$ and $\tau\_m = 2$). If the strata move materially, \`FR-004\` gains a seventh instance —

**\*\*declared now, so it is a prediction rather than a discovery.\*\***

**---**

**## 4. Estimands — to be proposed with directions, then frozen**

\| | within stratum | direction |

\|---|---|---|

\| **\*\*A1\*\*** | $\mathcal P\_{\text{det}}$ | class distribution over \`M0/M1/M2/M3a/M3b/MX\` |

\| **\*\*A2\*\*** | $\mathcal P\_{\neg\text{det}}$ | the same distribution, **\*\*reported in full, never dropped\*\*** |

\| **\*\*A3\*\*** | both | **\*\*stratum sizes\*\***, as a first-class reported quantity |

\| **\*\*A4\*\*** | $\mathcal P\_{\text{det}}$ | $P(\text{determination GAINED at } Q\_{\text{broad}})$ — **\*\*directed\*\***, vs the null round-trip |

\| **\*\*A5\*\*** | $\mathcal P\_{\text{det}}$ | $P(\mathrm{Avail} \text{ lost})$ — the \`M0\` rate, **\*\*reported first\*\*** |

**\*\*The primary must be nominated and frozen before the run.\*\*** Recommendation: **\*\*\`A5\`\*\***, because

\`M0\` — *\*zoom-out destroying answerability\** — is the only candidate that both replicated in

\`KR-ZOOM-OUT-01\` and survived the selection audit.

\`[REC]\` **\*\*\`A4\` is NOT recommended as primary.\*\*** Its conditional value (0.182 / 0.185) was seen

after the fact; nominating it now would be adopting a diagnostic because it cleared a floor.

**\*\*It should be a pre-registered secondary and earn its place on fresh data.\*\***

**---**

**## 5. Controls carried forward**

\`O-A\` null round-trip (four equalities: \*\*Structural · Observable · Contract-semantic ·

Historical/provenance\*\*, separate) · \`O-B\` determined nothing · \`O-C\` determined a decoy ·

\`O-D\` $n$-cycle fidelity · \`O-E\` paired seeds · **\*\*\`O-F\` degeneracy pre-check, run before the run.\*\***

\`[REC]\` **\*\*New: \`O-G\` — stratum-artifact check.\*\*** For every classification rate, test whether it

equals a stratum size to within 0.005. \*\*If it does, it is reported as a stratum artifact, not a

finding.\*\* This is the \`M1\` failure, institutionalised.

**---**

**## 6. What must be decided before freezing**

1\. The **\*\*primary estimand\*\*** (recommendation: \`A5\`).

2\. The **\*\*effect floor\*\*** (recommendation: carry 0.10 / 0.05 forward unchanged).

3\. The **\*\*single-winner threshold\*\*** and whether it applies **\*\*within stratum\*\*** (recommendation: yes,

   within $\mathcal P\_{\text{det}}$ only, with \`MX\` first-class).

4\. The **\*\*second epistemic standard\*\*** $S'$ for the \`FR-004\` prediction of §3.

5\. Whether root depth, detail count and coverage join **\*\*evidence-graph size\*\*** as declared strata.

**## 7. What this experiment still cannot do**

**\*\*Cannot\*\*** add a definition of Zoom-out · promote \`FR-004\`, \`P1\`–\`P3\`, or 7b · touch Theory v1.2 or

the kernel · settle \`OQ-1\` or \`DECISION-02\`.

$$\boxed{\text{Observed phenotype} \\;\neq\\; \text{definition} \\;\neq\\; \text{Theory amendment}}$$
#
This design is **substantially ready**, but I would **not freeze it yet**. There are two methodological points I would resolve before Claude turns the five §6 decisions into a pre-registration.

### 1. A5 as primary is defensible — but the justification needs tightening

The text says:

> M0 is the only candidate that both replicated in KR-ZOOM-OUT-01 and survived the selection audit.

The first half is fine. The second needs care.

The selection audit showed that the **M0 inversion survived the tested stratification**, but the original run had a failed calibration gate. Therefore I would not describe M0 as having "survived the selection audit" in a way that sounds like the scientific effect itself was validated.

Better:

> **A5 is recommended as primary because M0 was the most stable diagnostic phenotype in KR-ZOOM-OUT-01 and its crude rate was essentially unchanged under the tested stratification. This does not constitute confirmatory evidence because the KR-ZOOM-OUT-01 calibration gate failed.**

That preserves the rationale without laundering the diagnostic into evidence.

---

### 2. Don't automatically add root depth/detail/coverage as strata

This is the more important decision.

The current design says §6 must decide whether:

* evidence-graph size,
* root depth,
* detail count,
* coverage

become declared strata.

I would **not require all four to become strata simply because they were not tested previously**.

Instead, have Claude pre-register them as **candidate structural covariates**, and require a deterministic rule for which ones become stratification variables.

Otherwise we risk creating a new version of the same problem:

$$
\text{many possible strata}
\rightarrow
\text{inspect results}
\rightarrow
\text{find informative stratum}
$$

The clean approach is:

$$
\boxed{
\text{Stratification variables are fixed before data generation}
}
$$

and their inclusion must have a design-based justification, not outcome-based usefulness.

Evidence-graph size is already defensible because it was explicitly examined in the audit. Root depth/detail/coverage need either the same treatment or a clear reason for inclusion.

---

## One more distinction I would make explicit

A1–A5 currently mix **classification**, **population description**, and **causal/effect estimands**.

That's okay operationally, but label them accordingly.

For example:

* **A1/A2:** descriptive classification distributions;
* **A3:** population composition;
* **A4:** directed determination-gain estimand;
* **A5:** answerability-loss estimand.

That makes it impossible to accidentally interpret A3 or A1 as evidence of a Zoom-out effect.

---

## I like the O-G control

Keep it.

But I would phrase its conclusion slightly more precisely:

$$
|P(M_i)-P(\mathcal P_s)|\le .005
$$

doesn't necessarily prove that the classification is an artifact. It identifies a **stratum-size coincidence requiring artifact classification**, according to the pre-registered rule.

So:

> "reported as a stratum artifact under O-G"

is preferable to:

> "is an artifact."

The former is a protocol classification; the latter is a causal explanation.

---

# My recommendation for §6

I would have Claude resolve it as:

1. **Primary:** A5.
2. **Effect floor:** 0.10 material / 0.05 borderline.
3. **Winner threshold:** 0.60, applied within \(\mathcal P_{\rm det}\), MX first-class.
4. **Second standard:** choose and freeze \(S'\) **before calibration/execution**.
5. **Structural strata:** freeze the complete set before execution; do not allow selection after seeing outcomes. If root depth/detail/coverage are included, they are declared independently of their eventual usefulness.

Then the protocol becomes genuinely frozen.

### One thing I would explicitly add

For A5, define its direction unambiguously:

$$
A5 =
P\left(
\neg Avail(Q_{\rm broad},K_{post})
\mid
Determine_{\rm preZO}(Q_{\rm broad})=1
\right).
$$

That prevents "M0 rate" from accidentally becoming a vague label for several different loss phenomena.

And similarly A4 should explicitly distinguish:

$$
Determine_{\rm preZO}=1
$$

from

$$
Determine_{\rm postZO}=1.
$$

---

## Overall verdict

**[REVIEW] READY FOR DECISION, NOT YET READY TO FREEZE**

I would send Claude these corrections, let it resolve the five decisions, and then review the resulting **actual pre-registration** once more before authorization.

Most importantly, we're now doing the right thing scientifically:

$$
\boxed{
\text{ZOOM-OUT-01 result}
\rightarrow
\text{design lesson}
\rightarrow
\text{new pre-registration}
}
$$

rather than:

$$
\text{ZOOM-OUT-01 diagnostic}
\rightarrow
\text{preferred result}
\rightarrow
\text{post-hoc theory}.
$$

And **Theory v1.2, FR-004, P1–P3, and the kernel remain untouched.**

#
All six corrections applied. KR-ZOOM-OUT-02-DECISIONS-2026-09.md — [REVIEW] READY FOR DECISION, NOT YET READY TO FREEZE. All five still open.

The one where my own recommendation was the anti-pattern

You caught it exactly. I proposed adding root depth and detail count "because they were not stratified" — which builds a menu of candidate strata from which an informative one could later be selected. That's the same failure in a new costume. Withdrawn.

Replaced with a deterministic, design-based rule fixed before data generation:

▎ Include X iff X is a plausible common cause of both determination and the outcome, per the generator's declared causal structure, and X is not on the causal path.

Applying it from structure alone, not from any result:

┌─────────────────────┬──────────────────────────┬─────────────────────┬────────────────────────────────────────────────────────────────────────┐
│                     │  causes determination?   │   causes outcome?   │                                verdict                                 │
├─────────────────────┼──────────────────────────┼─────────────────────┼────────────────────────────────────────────────────────────────────────┤
│ evidence-graph size │ yes                      │ yes                 │ include — confounder, and already audited                              │
├─────────────────────┼──────────────────────────┼─────────────────────┼────────────────────────────────────────────────────────────────────────┤
│ root depth          │ yes                      │ yes                 │ include — confounder by the rule                                       │
├─────────────────────┼──────────────────────────┼─────────────────────┼────────────────────────────────────────────────────────────────────────┤
│ detail count        │ no — drawn independently │ yes                 │ exclude as confounder — a precision covariate, not needed for validity │
├─────────────────────┼──────────────────────────┼─────────────────────┼────────────────────────────────────────────────────────────────────────┤
│ coverage            │ no                       │ it IS the mechanism │ exclude — mediator                                                     │
└─────────────────────┴──────────────────────────┴─────────────────────┴────────────────────────────────────────────────────────────────────────┘

The phrasing correction, taken

"M0 survived the selection audit" is withdrawn — it laundered a diagnostic into evidence. Replaced with your wording: most stable phenotype, crude rate essentially unchanged under the tested stratification, and not confirmatory because the calibration gate failed.

Same for O-G: "reported as a stratum artifact under O-G" — a protocol classification, not "is an artifact", which would be a causal claim.

A decision I had glossed, now with real content

Your precise A5 definition exposed it: A5 is a proportion, but the 0.10 floor is defined oansfer. Three options are laid out; I recommend 2-c — keep A5 exactly as you defined itwith a declared θ₀, and pre-register the true-cause-vs-decoy contrast alongside so the floor still has something to act on.

Both effect estimands now carry preZO / postZO explicitly, retiring "M0 rate" as a vague label. And A1/A2/A3 are labelled descriptive classification and population composition so they can't be read as effects.

Governance layer recorded

Theory doc 10 §7a now carries your full lifecycle and both safeguards — as research-governance, explicitly not as theory claims:

$$\text{Observed diagnostic} \not\Rightarrow \text{new primary estimand} \qquad \text{Observed convergence} \not\Rightarrow \text{theory promotion}$$

On Decision 3, worth saying plainly: with M0 ≈ 0.47 the likely maximum, we should expect no winner at 0.60 — and that's the honest result, not a failure. Lowering the threshold now would be the post-hoc move
itself.

Nothing authorized. Theory v1.2 frozen, kernel untouched, FR-004 a candidate, Zoom-out und
\--
**# \`KR-ZOOM-OUT-02\` — the five open decisions, worked**

**\*\*Status:\*\*** **\*\*\`[REVIEW]\` READY FOR DECISION, NOT YET READY TO FREEZE\*\***

$$\boxed{\text{recommended design} \\;\neq\\; \text{ratified pre-registration} \\;\neq\\; \text{executed experiment}}$$

\> **### ⚠️ \*\*All five decisions remain GENUINELY OPEN.\*\* Nothing here is ratified. \*\*"\`A5\` is primary" is a RECOMMENDATION\*\*, and must not become a ratified choice by repetition.**

**---**

**## 0. Estimand TYPES — labelled, so they cannot be misread as effects**

\| | estimand | **\*\*type\*\*** |

\|---|---|---|

\| \`A1\` | class distribution over \`M0/M1/M2/M3a/M3b/MX\` in $\mathcal P\_{\text{det}}$ | **\*\*descriptive classification\*\*** |

\| \`A2\` | the same in $\mathcal P\_{\neg\text{det}}$ | **\*\*descriptive classification\*\*** |

\| \`A3\` | stratum sizes | **\*\*population composition\*\*** |

\| \`A4\` | determination **\*\*gained\*\*** at $Q\_{\text{broad}}$ | **\*\*directed effect estimand\*\*** |

\| \`A5\` | **\*\*answerability loss\*\*** at $Q\_{\text{broad}}$ | **\*\*effect estimand\*\*** |

\> \`[REC]\` **\*\*\`A1\`, \`A2\` and \`A3\` are descriptions, not evidence of a Zoom-out effect.\*\*** Labelling

\> them prevents a distribution or a population count from being read as a finding.

**### The two effect estimands, defined unambiguously**

$$A\_5 \\;=\\; P\\!\left(\neg\\,\mathrm{Avail}\\!\left(Q\_{\text{broad}}, K\_{\text{post}}\right) \\;\middle|\\; \mathrm{Determine}\_{\text{preZO}}\\!\left(Q\_{\text{broad}}\right) = 1\right)$$

$$A\_4 \\;=\\; P\\!\left(\mathrm{Determine}\_{\text{postZO}}\\!\left(Q\_{\text{broad}}\right) = 1 \\;\middle|\\; \mathrm{Determine}\_{\text{preZO}}\\!\left(Q\_{\text{broad}}\right) = 0\right) \\;-\\; \left[\text{same, null round-trip}\right]$$

\`[REC]\` **\*\*\`preZO\` and \`postZO\` are written explicitly in every formula.\*\*** *\*"M0 rate"\** was a vague

label covering several different loss phenomena; it is retired in favour of the expression above.

**---**

**## Decision 1 — the primary estimand**

**\*\*\`[REC]\` \`A5\` primary.\*\*** Rationale, stated at the strength the evidence supports:

\> \*\*\`A5\` is recommended as primary because \`M0\` was the most stable diagnostic phenotype in

\> \`KR-ZOOM-OUT-01\`, and its crude rate was essentially unchanged under the tested stratification

\> (0.4945 → 0.4977; 0.4475 → 0.4476). This does NOT constitute confirmatory evidence, because

\> \`KR-ZOOM-OUT-01\`'s calibration gate failed.\*\*

⚠️ **\*\*Correction to my own earlier wording.\*\*** I wrote that \`M0\` *\*"survived the selection audit"\**.

That reads as though the scientific effect was validated. **\*\*It was not.\*\*** The audit tested one

structural proxy against a run whose gate had failed. **\*\*The phrasing above replaces it.\*\***

\> **\*\*\`[REC]\` \`A4\` remains NOT recommended as primary.\*\*** Its conditional value (0.182/0.185) was seen

\> after the fact. **\*\*Pre-registered secondary, earning its interpretation on fresh data.\*\***

**---**

**## Decision 2 — the effect floor · ⚠️ \*\*this decision has real content\*\***

\`A5\` as defined is a **\*\*proportion\*\***, not a difference. \*\*The 0.10 floor is defined on a

difference, so it does not transfer.\*\* Three options:

\| | | |

\|---|---|---|

\| **\*\*2-a\*\*** | declare a reference $\theta\_0$: $H:\\,A\_5 > \theta\_0$ | simple; but $\theta\_0$ is arbitrary — nothing in the domain fixes it |

\| **\*\*2-b\*\*** | make \`A5\` a contrast: loss given **\*\*true cause\*\*** vs given **\*\*decoy\*\*** (\`O-C\` supplies the comparator) | the 0.10 floor transfers unchanged; asks the sharper question *\*does loss depend on* **\*\*what\*\*** *was determined?\** |

\| **\*\*2-c\*\*** | keep \`A5\` a proportion **\*\*and\*\*** carry \`2-b\` as a declared secondary | both, at the cost of two frozen quantities |

**\*\*\`[REC]\` \`2-c\`.\*\*** \`A5\` stays exactly as the owner defined it, with $\theta\_0$ declared before the

run; the contrast is pre-registered alongside so the floor still has something to act on.

**\*\*Borderline/material bands 0.10 / 0.05 carry over unchanged for the contrast.\*\***

The floor has now held under pressure **\*\*twice\*\*** — \`KR-ZOOM-03\` (0.058), \`KR-ZOOM-OUT-01\` (0.067) —

and was not moved either time. \*\*Changing it after two runs failed to clear it would be the

clearest possible breach of the safeguard it exists to enforce.\*\*

**---**

**## Decision 3 — the single-winner threshold**

**\*\*\`[REC]\` 0.60, within $\mathcal P\_{\text{det}}$ only, \`MX\` first-class.\*\***

**### Expect NO winner — and that is a legitimate result**

\`KR-ZOOM-OUT-01\`'s conditional distribution: \`M0\` ≈ 0.47 · \`M3b\` ≈ 0.26 · \`MX\` ≈ 0.16.

**\*\*Nothing approaches 0.60.\*\*** Lowering the threshold *\*after\** seeing that 0.47 is the likely maximum

would be precisely the post-hoc move the programme forbids. \*\*Keep 0.60 and accept "no single

meaning describes Zoom-out" as the honest and probable outcome.\*\*

**---**

**## Decision 4 — the second epistemic standard $S'$**

**\*\*\`[REC]\` $S' = (\tau\_s = 1,\ \tau\_m = 2)$\*\***, uniqueness unchanged. \*\*Chosen and frozen BEFORE

calibration and execution.\*\*

⚠️ *\*"Stricter margin ⟹ fewer determined cases"\** is near-certain by construction and tests nothing.

**\*\*The declared prediction is therefore:\*\***

$$\left|\\,P\\!\left(M\_0 \mid \mathcal P\_{\text{det}}, S\right) - P\\!\left(M\_0 \mid \mathcal P\_{\text{det}}, S'\right)\right| \\;\ge\\; 0.10$$

The classification distribution **\*\*within\*\*** the determined stratum shifts materially when **\*\*only\*\***

the epistemic standard changes. **\*\*Not forced\*\*** — one could equally expect the survivors of a

stricter margin to behave the same way afterwards.

**---**

**## Decision 5 — structural strata · ⚠️ \*\*my earlier recommendation was the anti-pattern\*\***

I proposed adding root depth and detail count *\*"because they were not stratified"\**. \*\*That is

outcome-blind in the wrong way\*\*: it creates a menu of candidate strata from which an informative

one could later be selected. **\*\*Withdrawn.\*\***

**### \`[REC]\` A deterministic, design-based inclusion rule, fixed \*\*before data generation\*\***

$$\boxed{\text{Include } X \text{ as a stratification variable} \iff X \text{ is a plausible COMMON CAUSE of both determination and the outcome, per the generator's declared causal structure, and } X \text{ is not on the causal path.}}$$

Applying the rule to the four candidates — **\*\*from the generator's structure, not from any result\*\***:

\| candidate | causes determination? | causes the outcome? | verdict |

\|---|---|---|---|

\| **\*\*evidence-graph size\*\*** | yes | yes (via root identity → detail claims) | **\*\*INCLUDE\*\*** — confounder; also the one variable already examined in the audit |

\| **\*\*root depth\*\*** | yes (position in the graph) | yes (root identity fixes which detail claims exist) | **\*\*INCLUDE\*\*** — confounder by the rule |

\| **\*\*detail count\*\*** | **\*\*no\*\*** — drawn independently of the evidence graph | yes | **\*\*EXCLUDE as a confounder.\*\*** A cause of the outcome only is a **\*\*precision covariate\*\***, not required for validity. May be reported as precision **\*\*only if declared now\*\***. |

\| **\*\*coverage\*\*** | no | yes — **\*\*and it IS the mechanism\*\*** | **\*\*EXCLUDE — MEDIATOR\*\*** |

**### Why coverage must not be a stratum**

$$\text{determination} \longrightarrow \text{coverage} \longrightarrow M\_0$$

$$\text{covered} \Rightarrow \text{consolidated (}M2\text{)} \qquad \text{uncovered} \Rightarrow \text{retracted (}M0\text{)}$$

\> **\*\*Stratifying on coverage would block the very effect being measured.\*\*** It lies *\*on\** the causal

\> path. Conditioning on a mediator would drive the \`M0\` contrast toward zero **\*\*by construction\*\*** —

\> and the result would look like a clean negative.

\`[REC]\` \*\*The complete stratum set is frozen before data generation, and cells with $n < 60$ cause

the stratifier to be dropped BEFORE the run, never after.\*\*

**---**

**## Control \`O-G\` — phrasing corrected**

$$\left|P(M\_i) - P(\mathcal P\_s)\right| \le 0.005 \\;\Longrightarrow\\; \textbf{reported as a stratum artifact under } O\text{-}G$$

⚠️ **\*\*Not\*\*** *\*"is an artifact."\** The coincidence **\*\*triggers a protocol classification\*\***; it does not

establish a causal explanation. \`[REC]\` The distinction is kept in the results wording.

**---**

**## Summary — all still OPEN**

\| # | decision | recommendation |

\|---|---|---|

\| 1 | primary estimand | **\*\*\`A5\`\*\***, with the rationale stated at diagnostic strength only |

\| 2 | effect floor | **\*\*\`2-c\`\*\*** — $\theta\_0$ for the proportion **\*\*+\*\*** the true-cause/decoy contrast at 0.10/0.05 |

\| 3 | winner threshold | 0.60 within $\mathcal P\_{\text{det}}$; **\*\*expect no winner\*\*** |

\| 4 | second standard $S'$ | $\tau\_m = 2$, frozen before calibration, with a non-obvious prediction |

\| 5 | structural strata | **\*\*a deterministic common-cause rule\*\*** → graph size + root depth; detail count precision-only; **\*\*coverage EXCLUDED as mediator\*\*** |

**\*\*Theory v1.2 FROZEN · Kernel UNTOUCHED · \`FR-004\` CANDIDATE · \`P1\`–\`P3\` UNTOUCHED · Zoom-out UNDEFINED · \`KR-ZOOM-OUT-02\` NOT AUTHORIZED.\*\***
#
This is now a **serious pre-registration design**, but I would **not freeze it yet**. There are two genuine blockers and one important refinement.

## 1. The biggest blocker: the `n < 60` rule is still data-dependent

The artifact says:

> cells with \(n<60\) cause the stratifier to be dropped **before the run**

But the cell sizes are not known until data generation.

So this creates:

$$
\text{Generate data}
\rightarrow
\text{inspect cell sizes}
\rightarrow
\text{drop stratifier}
\rightarrow
\text{analyze}.
$$

Even though analysis hasn't run, the **design has now depended on generated data**.

That violates the spirit—and arguably the explicit wording—of:

$$
\boxed{\text{No analysis-dependent choice of population or estimand}.}
$$

### Fix

Either:

**A. Never drop a declared stratifier.**

Small cells are reported with uncertainty/limitations.

Or:

**B. Determine expected cell occupancy analytically from the generator before data generation**, and freeze the decision from that.

I strongly prefer **A** for this experiment.

> Once a stratifier is declared, it remains a stratifier. No cell-size-triggered deletion after generation.

That's cleaner.

---

# 2. The effect-floor problem is not completely solved yet

Claude correctly discovered that A5 is a proportion:

$$
A_5=
P(\neg Avail(Q_b,K_{post})
\mid Determine_{preZO}(Q_b)=1).
$$

Therefore the previous:

$$
0.10/0.05
$$

difference floor cannot simply be attached to A5.

Claude's 2-c is reasonable:

$$
A5 + \text{true-cause vs decoy contrast}.
$$

But there is still one unresolved item:

> **What exactly is \(\theta_0\)?**

The text says:

> "with a declared \(\theta_0\)"

but §6 hasn't actually selected it.

So Decision 2 is **not ready to freeze**.

And I would resist inventing \(\theta_0\) simply to complete the table.

### My preference

Keep A5 as the descriptive primary quantity, but make the **causal/effect contrast** the quantity to which the 0.10/0.05 floor applies:

$$
\Delta_{loss}
=
P(Loss\mid TrueCause,Det_{pre}=1)
-
P(Loss\mid Decoy,Det_{pre}=1).
$$

Then:

$$
|\Delta_{loss}|\ge .10
$$

= material,

$$
.05\le|\Delta_{loss}|<.10
$$

= borderline.

That gives the existing floor a non-arbitrary target.

A5 itself can remain:

> "What proportion of previously determined states lose answerability?"

No arbitrary \(\theta_0\) is then required for the primary scientific interpretation.

---

# 3. There is a subtle issue with Decision 4

This is the most interesting one.

Claude proposes:

$$
S=(\tau_s=1,\tau_m=1)
$$

versus

$$
S'=(\tau_s=1,\tau_m=2).
$$

And predicts:

$$
|P(M0\mid P_{det},S)-P(M0\mid P_{det},S')|\ge .10.
$$

That's a legitimate **contract-sensitivity experiment**.

But it must be explicit that:

$$
\mathcal P_{det,S}
\neq
\mathcal P_{det,S'}
$$

in general.

A stricter determination standard changes **who belongs to the determined stratum**.

Therefore the comparison is not simply:

> "same population under two standards."

It is:

> **"How does the composition of the pre-Zoom-out determined population change when the determination contract changes, and how does the subsequent Zoom-out phenotype differ?"**

That's actually more interesting—but it needs to be stated exactly that way.

Otherwise someone could incorrectly interpret the result as a pure causal effect of \(S\).

---

# What I would approve

The overall architecture is excellent:

$$
\boxed{
\text{Design}
\rightarrow
\text{Pre-register}
\rightarrow
\text{Calibration}
\rightarrow
\text{Freeze}
\rightarrow
\text{Analysis}
}
$$

with:

$$
\mathcal P=
\mathcal P_{det}\cup\mathcal P_{\neg det}
$$

and:

$$
\boxed{\text{stratify, don't filter}}
$$

That should stay.

The A1/A2/A3 separation is also exactly right:

$$
\text{description}\neq\text{effect}.
$$

And O-G is worth keeping.

---

## My revised decision table

| Decision                       | My verdict                                                                    |
| ------------------------------ | ----------------------------------------------------------------------------- |
| **1. A5 primary**              | ✅ Acceptable                                                                  |
| **2. 2-c**                     | ⚠️ Accept framework, but resolve \(\theta_0\) / contrast before freeze        |
| **3. 0.60 / MX first-class**   | ✅ Accept                                                                      |
| **4. \(S'=(1,2)\)**            | ✅ Accept, but explicitly frame as changing the determined-stratum composition |
| **5. graph size + root depth** | ✅ Accept, **remove post-generation \(n<60\) dropping**                        |

So I would send Claude **one mandatory correction** and two wording/formalization corrections:

### Mandatory

Remove:

> "cells with \(n<60\) cause the stratifier to be dropped BEFORE the run."

Replace with:

> **All declared stratifiers remain fixed once pre-registered. No stratifier may be dropped after data generation. Sparse cells are reported as sparse and do not trigger redesign.**

### Formal

Make the floor apply to:

$$
\Delta_{loss}
=
P(Loss\mid TrueCause)-P(Loss\mid Decoy)
$$

rather than silently attaching it to the raw A5 proportion.

### Interpretive

State explicitly:

$$
S\rightarrow\mathcal P_{det,S}
$$

so changing \(S\) changes the composition of the determined stratum. The S/S′ comparison is therefore a **contract-relative stratified analysis**, not a pure intervention on an otherwise identical population.

---

### Bottom line

**[REVIEW] Almost ready to freeze.**

I would **not authorize execution yet**.

Fix the \(n<60\) rule first. Resolve the exact Decision-2 floor target. Clarify the changing-stratum interpretation under \(S'\).

After those are frozen, I think `KR-ZOOM-OUT-02` will be methodologically strong enough to authorize.

And importantly, this still leaves:

$$
\boxed{\text{Theory v1.2 FROZEN}}
$$

$$
\boxed{\text{Zoom-out UNDEFINED}}
$$

$$
\boxed{\text{Kernel UNTOUCHED}}
$$

exactly where they should be.
#
All three applied. Still [REVIEW] — not frozen, not authorized, not run.

① Mandatory — the n < 60 rule is withdrawn

You're right, and my wording was incoherent, not merely risky: it said a stratifier would be dropped for sparse cells "before the run" — but cell sizes aren't known until after data generation. As written it created

$$\text{generate} \to \text{inspect cell sizes} \to \text{drop stratifier} \to \text{analyse}$$

The design would have depended on generated data even with no analysis run. Replaced with your Option A:

▎ All declared stratifiers remain fixed once pre-registered. No stratifier may be dropped after data generation. Sparse cells are reported as sparse and do not trigger redesign.

② Formal — Decision 2 resolved, and no θ₀ invented

You were right to resist inventing one to complete the table. Two roles, both declared:

┌────────┬────────────────────────────────────────┬──────────────────────────────────────────────────────┐
│        │                quantity                │                         role                         │
├────────┼────────────────────────────────────────┼──────────────────────────────────────────────────────┤
│ A5     │ P(¬Avail(Q_b, K_post) | Det_preZO = 1) │ primary reported quantity — no floor, no θ₀          │
├────────┼────────────────────────────────────────┼──────────────────────────────────────────────────────┤
│ Δ_loss │ P(Loss | TrueCause) − P(Loss | Decoy)  │ primary adjudicated effect — the floor attaches here │
└────────┴────────────────────────────────────────┴──────────────────────────────────────────────────────┘

The existing 0.10 / 0.05 floor now has a non-arbitrary target, and A5 keeps its plain scientific meaning: what proportion of previously-determined states lose answerability?

③ Interpretive — S → P_det,S

Stated explicitly, and the prediction decomposed so the two components are separable:

(i) total shift across P_det,S vs P_det,S' — confounds composition with phenotype.

(ii) phenotype component on the intersection P_det,S ∩ P_det,S', where membership is held constant.

┌───────────────────────┬────────────────────────────────────────────────────────────────┐
│                       │                            reading                             │
├───────────────────────┼────────────────────────────────────────────────────────────────┤
│ (i) large, (ii) ≈ 0   │ pure composition — different cases, same behaviour             │
├───────────────────────┼────────────────────────────────────────────────────────────────┤
│ (i) large, (ii) large │ the standard changes the phenotype itself — the stronger claim │
├───────────────────────┼────────────────────────────────────────────────────────────────┤
│ (i) ≈ 0               │ no contract sensitivity at this margin                         │
└───────────────────────┴────────────────────────────────────────────────────────────────┘

Both declared now, so neither can be selected after the fact — and the result can no longer be misread as a causal effect of S on a fixed population.

The S/S' comparison is labelled throughout as a contract-relative stratified analysis, which is also the seventh-instance test for FR-004.

Theory v1.2 frozen, Zoom-out undefined, kernel untouched, FR-004 a candidate. Ready for your review of the corrected protocol.
#
Yes. **The corrected protocol is now methodologically ready for freeze**, subject to one final governance action: freeze it before any execution or inspection of generated outcomes.

### Review verdict

**Status: READY TO FREEZE — no substantive blocker remains.**

The three corrections close the issues I previously identified:

1. **Stratifier integrity — resolved**

   * Declared stratifiers are now immutable after pre-registration.
   * Sparse cells cannot cause redesign.
   * This preserves the intended separation:
     **pre-registration → generation → analysis**, without data-dependent model selection.

2. **A5 / Δ_loss — resolved**

   * A5 remains a scientifically interpretable descriptive quantity:

     $$
     A5=P(\neg Avail(Q_b,K_{post})\mid Det_{preZO}=1).
     $$
   * No arbitrary \(\theta_0\) is introduced.
   * The existing \(0.10/0.05\) adjudication floor attaches to the genuine comparative effect:

     $$
     \Delta_{loss}
     =
     P(Loss\mid TrueCause)
     -
     P(Loss\mid Decoy).
     $$
   * This is considerably cleaner than forcing a threshold onto an absolute proportion.

3. **S vs. S′ — resolved**

   * You correctly changed the interpretation from a supposed intervention on a fixed population to:
     **contract-relative stratified analysis**.
   * Separating:

     $$
     P(M0\mid P_{det,S})
     \quad\text{from}\quad
     P(M0\mid P_{det,S}\cap P_{det,S'})
     $$

     is important.
   * It allows composition effects and phenotype effects to be distinguished rather than conflated.

### One thing I would explicitly preserve

The protocol should retain the distinction:

> **A declared quantity can be descriptive without being an adjudicated effect.**

So I would regard the roles as:

| Quantity                   | Status                                     |
| -------------------------- | ------------------------------------------ |
| A1/A2                      | descriptive stratum distributions          |
| A3                         | population composition                     |
| A4                         | directed exploratory/secondary effect      |
| **A5**                     | **primary descriptive phenotype**          |
| **Δ_loss**                 | **primary adjudicated comparative effect** |
| S/S′ intersection analysis | contract-sensitivity diagnostic            |

That prevents the protocol from accidentally turning every measured quantity into a causal claim.

### The important methodological achievement

This protocol now contains a much stronger separation of:

$$
\boxed{
\text{Population}
\neq
\text{Stratification}
\neq
\text{Contract}
\neq
\text{Phenotype}
\neq
\text{Effect}
}
$$

That matters because the previous Zoom-out experiment demonstrated exactly how easily these layers can collapse into one another.

And importantly, **you are not repairing the failed KR-ZOOM-OUT-01 by changing its result**. You are designing a new experiment whose estimands and population handling are fixed independently of its future outcome.

### Governance disposition

I would now mark:

**KR-ZOOM-OUT-02**

> `[READY FOR FREEZE]`

Then the sequence should be:

$$
\boxed{
\text{Freeze}
\rightarrow
\text{Calibration}
\rightarrow
\text{Gate}
\rightarrow
\text{Execution}
\rightarrow
\text{Analysis}
}
$$

with the calibration executable still having authority to **stop the experiment**, not silently modify it.

And after freeze:

* **Theory v1.2:** unchanged
* **Kernel:** untouched
* **Zoom-out definition:** still open
* **FR-004:** candidate only
* **No new primary estimand**
* **No post-hoc stratifier removal**
* **No threshold adjustment**
* **No post-hoc winner selection**
* **No promotion from convergence to theory**

So yes: **I would approve the corrected protocol for freeze.** The next action is not another conceptual redesign; it is to **freeze the exact protocol and then run its independent calibration gate**.
#
