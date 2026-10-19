# Phase 3: competing authority interpretations, compared inside ALM (2026-10-18)

Each interpretation is expressed as a choice of **which layer carries the word "valid"**. All of them are computable in `alm.py`.

| # | Interpretation | ALM formalization |
|---|---|---|
| **I1** | Acts establish authority; records are evidence | "valid" := V (Layer V). Records feed only R |
| **I2** | Records are necessary for operational effect | O requires `e ∈ H≤k` (Layer O). This is **compatible with I1**: it adds a layer and does not redefine V |
| **I3** | Records establish authority (strong C1-a) | "valid" := `Valid_{H≤∞}` with `≺` derived from **record positions**, so an unrecorded act is never valid and its validity time is its recording time |
| **L** | Layered = I1 + I2 (+ R) | V for validity, R for recognition, O for effect. **ALM as specified** |

## Assessment

| Criterion | I1 alone | I3 (records establish) | L (layered: recommended candidate) |
|---|---|---|---|
| **Source compatibility** | Supports S0067 L51, S0092 L35, S0095 L141, ES-001.2, ARCH-INV-1, R-86. **Cannot express** S0071 I-2 (activation only when recorded) | Supports S0071 I-2 and v3.1 §10.2 ("entered by a **recorded** human act"). **Contradicts** S0092 L35 ("the act **did occur**", unrecorded), S0095 L141 (transcribing a "given" act ≠ inventing it), S0067 L51, and ARCH-INV-1 "records authority; never grants it" | Supports all of the above. I-2 and §10.2 map to O. The act-based sources map to V |
| **Formal consistency** | consistent (Theorem 1) | consistent, but the order comes from positions. `test_order_from_positions_breaks_soundness` shows it then **disagrees with act-time reality**; it is a different theory, not an inconsistent one | consistent: Proposition 8 (layers never refer to later-layer verdicts) |
| **Operational implementability** | V needs the *complete* act history, which no system has. Not operable alone | fully operable on the store | R and O are operable on the store. V is a *specification target* that R approximates (Theorem 3) |
| **Historical reproducibility** | not reproducible from records | reproducible per prefix **only if prefixes are retained**; today they are not (C-3) | the same as I3 for R and O. V is fixed but unobservable. **Needs store retention (ADR-C6)** |
| **Late-recorded events** | handled correctly (valid from act time) | work done between giving and recording an act is invalid. S0103 F-7 would become "unauthorized work"; **the corpus calls it an anomaly, not unauthorized** | V: valid. R: changes when the record arrives (Theorem 4). O: no effect until recorded. **This matches the corpus's three-way treatment** |
| **Back-dated events** | n/a (V ignores records) | back-dating would change validity, so I3 *needs* the no-back-dating rule to be meaningful | no-back-dating keeps R honest. V is unaffected |
| **Failure modes** | "valid but nobody can know" | "invalid although properly given"; anachronism when recording is late | V ≠ R disagreement while records are incomplete; non-monotone recognition (Theorem 4) |
| **Ambiguous cases** | an act given orally and never recorded | the same act counts as never given | an explicit three-way answer: V valid · R UNDETERMINED · O not effective |
| **Consequences for architecture** | none that can be implemented | the BC-7 store becomes *constitutive*, so its missing provenance (C-3) becomes a validity defect | BC-7 is *evidential* for R and *constitutive* for O. Both ARCH-INV-1 and I-2 are satisfied as currently stated |

## Result [Ded]

- **The three interpretations are not mutually exclusive.** I1 and I2 are compatible and together form L. I3 is the only one that **conflicts** with sources, namely with S0092, S0095, S0067 L51 and ARCH-INV-1.
- **I3 differs from L in exactly one place:** the act-time validity of acts that were given but recorded late. It is *source-compatible* only with v3.1 §10.2, whose scope is T3 and whose rule model is STAGED.
- **Recommendation (research, not a decision):** L is the strongest model supported by the evidence.

**What L leaves to policy, and therefore to the Principal:**
- **λ:** when absence of a record may yield NOT_AUTHORIZED rather than UNDETERMINED (Theorem 6: no sound absence-based negative without it);
- **κ:** whether endings of authority cascade to decisions already made (evidence favours `persist`);
- **ι:** the interval convention (repository precedent: closed);
- **U:** a uniqueness invariant per subject with "later prevails" (T2-01);
- **the A0 tie-break rule:** which of two same-instant acts comes first. `strict_semantics_resolves_mutual_revocation_by_A0_order` shows **this content can decide outcomes**. No source rules it;
- **the C1 naming question itself:** L *dissolves* C1 into the layers V and O. Whether the book should *say* "valid" for V (I1 wording) or reserve "valid" for O (I3-like wording) is a terminology and policy choice, still reserved.

## Where I3 is not refuted

If the organisation **decides** that only recorded acts count, for reasons of auditability, that is a legitimate policy. Its cost is visible: properly given acts recorded late become invalid in the interval before recording (S0103's work would be invalid). The evidence shows the organisation currently does **not** behave that way: it calls such cases anomalies, does not invalidate them, and transcribes the act late. **That is a description of practice, not a ruling.**
