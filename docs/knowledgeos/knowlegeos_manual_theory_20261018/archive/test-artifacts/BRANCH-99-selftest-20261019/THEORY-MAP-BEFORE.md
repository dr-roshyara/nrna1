# Theory map: KnowledgeOS theory as it stands

*Read before every source; updated after every source. It replaces the goal file as the working picture. Built 2026-10-19 from Chapters 0–5 and 8, after S0027. Labels: **Ax** axiom or stipulation · **Res** derived result · **Src** stated by the corpus · **Open** unresolved. `[CON-nnnn ✓/✗]` = validated / invalid.*

## 0. The problem (Ch. 0 §0.1)

- **Core property (Int):** knowledge must be **valid at the moment it is used**, by a person or an AI, and the user must be able to **see** whether it is.
- Loss, drift, staleness and "model amnesia" (re-deriving what is already known) are failures of this one property.
- Status: the problem is well evidenced. That KnowledgeOS solves it is a hypothesis; **nothing has been validated in use.**

## 1. What exists (Ch. 0 §0.3, Ch. 3)

- **Claim:** the unit of assertion. It is a unit of granularity, not a supertype (§1.15).
- **Dimensions:** a claim sits in a space of dimensions: nature · representation `[CON-0002 ✓]` · governance status `[CON-0003 ✗]` (mixes lifecycle and standing) · canon scope · evidence status · operational state `[CON-0006 ✗]`.
  - **Constraint set S:** tacit knowledge carries no status or scope; skill is tacit only.
  - **Res:** kind must not be one of the nature values, or the element formula counts nature twice (MM-1).
- **Knowledge ≠ artifact** (I-1) `[CON-0010 ✗]`.
  - **Res:** the deletion test shows that knowledge and artifact are **distinct**, not that knowledge **survives** deletion. A single carrier means no survival.
- **Archetypes** (nine candidate kinds) and **three ontology layers**: documentation, platform (absent), product (partial).
- **Relations per type, not one hierarchy** `[CON-0064 ✓]`: authority · production · realization · containment (plus references, which is cyclic, and constrains).
  - **Res:** a relation's reachability is a partial order exactly when its edges have no cycle; reachability ≠ the relation, for non-transitive relations.
  - **Res:** a typed union loses nothing; an unlabelled closure loses the type; a total extension adds false order. Impact questions need union reachability.
- **What executes is undecided: fork F11** (three senses) `[CON-0066 ✗]`. "Knowledge does not execute" stays circular (SEQ-0012 not reduced).

## 2. Authority (Ch. 1, Ch. 2 §2.8)

- **Ax A1 (§1.6):** only a **recorded act** by an authorized human changes standing, within the grant's **scope** and **period**: authority = act × scope × time.
- **Provenance ≠ standing (§1.2):** provenance is an immutable fact; standing is a judgment changed only by an act. Evidence grade ≠ standing (§1.3).
- **A record is not the act (§1.12).** The residual trust assumption is stated, not hidden.
- **authorized(x)** is defined recursively over grants, ending at root acts. It is **three-valued**: T / F / U ("cannot determine"). Not found ≠ unauthorized (§1.14.1, §1.17.3).
- **Mechanisms record, never grant** (ARCH-INV-1).
  - **P-8:** being executable, searchable, indexed or used by AI confers no authority.
  - **(P-E, conditional):** no mechanism may produce the normative effect of an act without an authorizing act (§1.14.5).
- **Visibility:** vis(derived) ⊆ ∩ vis(sources) (§1.7). Nothing yet governs **who may read** (§1.17.5).
- **Time:** an act is valid at the time it is made; meaning-bearing references must pin a version; a result is relative to its inputs (§1.16).
- **Res (§1.16.7):** a global invariant can be **prevented** only through a single serialization point. Otherwise it can only be **detected**.
- **Roles:** humans decide, governance records, architecture recommends. Independence here is **process** independence, not personal (RC-30, RC-65a).

## 3. Rules and conflict (Ch. 2)

- **Rule** = subject + applicability predicate + normative effect + scope + validity + authority + exceptions (RS adds binding strength, evidence, supersession).
- **Conflict:** some state exists where both rules apply and their effects cannot both be met. The output is the **region** where this holds.
  - Most apparent contradictions are not conflicts.
  - Priority numbers are rejected; precedence is allowed only among alternatives competing for one output.
- **Res (sound partitioning):** a rule's partition key must contain the projection of its region: key(r) ⊇ π(Region(r)).
- **AI proposes · analysis proves · humans resolve.** A proven result carries no confidence score.
- **Scope** means three things: subject, grant, evaluation. Containment: exception ⊆ subject scope ⊆ grant scope.
  - **SC-2:** unspecified ≠ all. Applicability is three-valued (Kleene), plus "not applicable".
  - The **kinds of nothing** are E1–E8, plus two provisional candidates E9 (invalid evidence) and E10 (unknown dimension).
- **Binding strength ≠ enforcement.** Rules with meaning and rules with enforcement are different sets, and here they sit in different components (§2.10, qualified).
- **Exception** = a separately authorized deviation within a scope and validity period.
  - **Res:** effective = adopted ∧ t ∈ validity, so a deferred rule cannot bind `[CON-0063 ✓]`.

## 4. How knowledge changes (Ch. 0 §0.4, Ch. 1 §1.6, Ch. 4)

- **Three moves:** derivation (new claim plus an edge to its source) · representation transition · standing transition by act. Supersession is a link, never an edit.
- **Two engines:** governance acts (granted) and evidence (earned). No act decrees evidence; evidence alone does not effect an act (RC-37a).
- **From observation to pattern:**
  - an evidence entry separates fact, interpretation and authority `[CON-0068 ✓]`;
  - patterns are derived from entries, never written into them `[CON-0069 ✓]`;
  - **a count needs its class fixed in advance** `[CON-0070 ✓]`;
  - **evidenced ≠ measured** (no baseline; the rival explanation is instruction-following);
  - recurrence must be **independent**: no shared actor, instructions, reviewer or work item. The corpus's own counts (R-6 n=3) fail this; the proposing instance must not count.
- **An obligation shrinks to its monitor's coverage** `[CON-0071 ✓]`.
- **The loop back** (evidence → platform change) is thin: about 3 informal passes; the formal count depends on how "formal" is defined.

## 4a. Boundaries: platform and product (Ch. 6)

- **Method · binding · evidence** `[CON-0073 ✗]` (not exhaustive: no ambiguous class).
- **The binding is a parameter** `[CON-0072 ✓]`: an artifact written inside one product is *M*(β_PD).
- **Res:** from one product the split (*M*, β) is not identifiable **unless the interface is declared**. A second product helps only if its binding differs. This grounds condition (3) of the component rule `[CON-0076 ✓]`.
- SD-1 admits several rule/binding splits; choosing one is part of the ARB's question (F10).
- **Res: portability is a lattice** {0,1}² (binding-free × case-law-free) plus "not domain-free" `[CON-0075 ✓]`, not a four-tier ladder `[CON-0074 ✗]`.
- **Kernel admission:** tier 1 is necessary, not sufficient `[CON-0057 ✓]`.
- If case law lives in product spaces, the platform's evidence lies across a boundary; but the corpus keeps evidence in **three or more** homes, so its placement rule is not followed (T6-02, T6-03).
- The four orders of §3.8 are **not exhaustive**: "guides" and "hosts" lie outside them.

## 5. Decisions (Ch. 5)

- The platform **prepares**; humans **decide**. Decision-readiness is `[CON-0012 ✗]`: there is no governing record for it.
- **Decision package** `[CON-0048 ✓]` · **gating order** `[CON-0049 ✓]`.
- **Presupposing evidence:** evidence that assumes the answer does not count, unless it is robust under every candidate answer `[CON-0050 ✗, qualified]`.
- **Gap kinds by closure** (modelling · ruling · use) `[CON-0053 ✗]`: the classification is inconsistent across sources.
- **Deciding in advance** `[CON-0077 ✓ as a design]`: a pre-specified decision rule, the decision-side twin of the fixed counting class (CON-0070). The register's own record falls short (same-day revisions).
- Gates by risk `[CON-0078 ✗]` (families do not cover the gates used) · three **sequential** questions, not orthogonal `[CON-0079 ✗]`.
- **Res (scope of the n ≥ 2 rule):** it governs claims of **reuse across contexts**; local automation in its own context makes none. This reconciles S0024/S0025 with S0029.
- Activation in the register came from acts and incidents; **no** criterion fired independently. Incidents are a third kind (T5-07).

## 5a. Gaps: what a decision lacks (Ch. 5 §5.4, from BRANCH-01; reviewed, provisional)

- "Zero" names three concepts: neutral origin · absence lens · **gap operator** `[CON-0080 provisional]`.
- The gap operator types each requirement relative to a contract (at least 11 statuses, including Failed and Missing, plus criticality). It detects; it does not plan.
- **Res:** the decision needs (status, criticality) pairs; a summary that drops criticality cannot decide (a count of unsatisfied **blocking** requirements can).
- The status set mixes kinds `[CON-0081 ✗]`. "Missing" asserts absence (E1) where only "not found" (E3) may be known.
- Its Unknown merges E2–E5, against Ch. 2's rule; it must be split before validation.
- Kinds of nothing come from the **absence lens**, not the operator: candidates **E9** `[CON-0082 provisional]` and **E10** `[CON-0083 provisional]`. E10 lies outside the operator by design.
- Ch. 0 Q3 is partly answered. **"Unsatisfiable" remains unformalized.**

## 6. Validation (Ch. 8)

- Falsification by projection into other products `[CON-0047 ✓]` · the reach register `[CON-0058 ✓]` · scaffolding contamination `[CON-0060 ✓]` · domain-free ≠ portable `[CON-0059 ✓]`.
- **Res:** one failure refutes a sufficiency claim, but does not license new structure.
- A single product **masks** failures, such as deployment and external authority.

## 7. Open parallel lines (forks)

| # | Fork | Kind | Where |
|---|---|---|---|
| F1 | Mission: PublicDigit-specific vs product-neutral | factual (sources disagree) + normative (sponsor ratifies) | Ch. 0 §0.2 |
| F2 | External authority: inside the act model (recognition act; grant citing an external act) vs outside it | factual / definitional | Ch. 1 §1.11, T1-15 |
| F3 | Genesis: architectural gap vs governance gap | factual | Ch. 1, T1-17 |
| F4 | Does the record **constitute** authority or **represent** it? (C1-a / C1-b) | normative (reserved) | Ch. 1 §1.13.1 |
| F5 | I-2 "reasoned": a validity condition or a quality obligation? | normative (reserved) | Ch. 1 §1.17.2 |
| F6 | Governance status: one dimension, or standing and lifecycle split apart (PM-1)? | definitional | Ch. 3 §3.1 |
| F7 | Kind: its own axis or a nature value (MM-1)? | definitional | Ch. 0 §0.3 |
| F8 | Knowledge spaces: nested or adjacent (H-3)? | factual | Ch. 3 §3.3, §3.8 |
| F9 | Is authority part of the knowledge theory, or the governance around it? | definitional (largest) | Ch. 0 §0.6, §0.8 |
| F11 | What "execute" means: (A) executing things are capabilities · (B) executable artifacts stay artifacts · (C) capability is a port, adapters execute | definitional | Ch. 3 §3.8, Ch. 1 §1.5 |
| F10 | SD-1's rule part ("consume only a certified release"): platform rule or not? (the either/or form of OQ-S1 is dissolved, §6.1 C3) | normative (ARB) | Ch. 6 §6.1 |

## 8. Weak points

- **Nothing has been validated in use.** The loop back is thin.
- I-8 (no second maintaining population) risks being unfalsifiable.
- Undefined: "runtime adapter" (SEQ-0001); the maturity levels L0–L4 (SEQ-0002).
- A capability is stipulated to be "that which executes" (SEQ-0012, partly reduced).
- 42 of 71 registered concepts are INVALID. Many of them are source vocabulary rather than the theory's building blocks (to be pruned under RK-2).

## 9. Position

- Last source read **in order**: **S0030**; next: **S0031**.
- **Counts (from `close_source.py --position`):** read in order (full/skim): **30** · consulted in branches only: **4** (S0208, S0721, S0896, S1296; BRANCH-01) · scans: **1**.
- Chapters: 0–3 written · 4 (§4.1) · 5 (§5.1–5.3) · 6 (§6.1) · 8 (§8.1) · 7 planned.
x
