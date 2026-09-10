# Phase 6 — `Sat` · Targeted Resolution

## A · ⚠️ Zwanzigster Messfehler — eine **ausgelassene Suchdimension**

Phase B prüfte **Identitätsaussagen** und **Trennungsaussagen** und meldete: keine Identität,
viele Trennung. Beides bleibt richtig. Aber §10 fragt nach etwas Drittem:

> *„which **transformations between them are explicitly witnessed**?"*

**Übergangsaussagen sind weder Identität noch Trennung** — und ich hatte nicht danach
gesucht. Die gezielte Suche findet **61 Kandidaten**. Der Befund *„keine Identität"* war
korrekt, die daraus gezogene Lesart *„die Formen stehen unverbunden nebeneinander"* war
**falsch**.

## B · ⭐⭐⭐ Die bezeugten Übergänge

### ① `Eval_c → Sat_c` — eine **verlustbehaftete Projektion**

`G-evaluation-semantics-experiment` (2026-09-02 10:59):

$$Sat_c \;:=\; value \circ Eval_c$$

> *„`Sat_c := value ∘ Eval_c` is a **lossy projection**, and **the loss is concentrated
> exactly at `U`**"*
> *„the projection defining `Sat` **destroys** the distinction Rule 3 …"*
> *„**`Eval_c` is the primary object; `Sat_c` is a projection of it**"* `[MODEL]`

Und die Kommissionierung desselben Experiments (10:22):

> *„This should **replace** `Sat_c` as the primary research object."*
> *„`Sat_c` may be **retained as a candidate projection/name**, but **MUST NOT be promoted**."*
> *„…avoids **prematurely freezing** the three-valued `Sat` interpretation."*

⭐ **Das kehrt die Hierarchie um, die ich angenommen hatte.** `Sat_c` ist nicht das
Grundobjekt, sondern ein **abgeleiteter, verlustbehafteter Schatten** von `Eval_c` — und
`A6`s neun nach `U` kollabierende Situationen (Phase B) sind **genau dieser Verlust**,
unabhängig gemessen.

### ② zweiwertig → dreiwertig — **DERIVED**, mit Grund

| Quelle | Aussage |
|---|---|
| `A-executive-result` 09-02 09:25 | *„SUPPORTED **only under three-valued Sat** — **two-valued `Sat` collapses absence into negation**"* |
| `C-status-classification` 09-02 09:26 | *„`Zero`-as-boundary **requires** three-valued `Sat`"* — Status **`DERIVED`**, Beleg **E5** |
| `D-gap-register-and-comparison` 09-02 09:27 | *„Absence vs negation: v1.1 **collapsed** — `¬Sat` covers both · v1.2 **separated**: `U` vs `⊥`"* → **„v1.2 strictly better"** |

$$Sat_{2} \;\xrightarrow{\;\textbf{REFINEMENT (DERIVED, E5)}\;}\; Sat_{3}$$

⭐ Das ist **die** v1.2-Verfeinerung aus Phase 1 — hier mit ihrem **mathematischen Grund**:
`Zero`-als-Grenze ist unter zweiwertigem `Sat` nicht darstellbar.

### ③ Der Nicht-Kollaps ist ein **Gegenbeispiel-Registereintrag**

`A9-counterexample-register` (2026-08-29): *„Boolean-collapse counterexample behind
**`Sat ≠ Boolean`** (25D.5)"* — die Nicht-Kollaps-Regel `I-9` (Phase B) hat also ein
**registriertes Gegenbeispiel**, nicht nur eine Norm.

## C · Die Formen nach §10, mit Übergängen

| | Form | Dom → Cod | Stell. | Kontext | Übergang | v1.2 |
|---|---|---|---|---|---|---|
| **S0** | `Sat(K,r_i)` „degree/status" | ⛔ **ungetypt** | 2 | Geburt 08-27 | — | vor |
| **S1** | `Status(r_i) ∈ {Satisfied,Unsatisfied,Unknown,Conflicted,NotApplicable}` | 5-wertig | 1 | daneben, unverbunden | ⛔ **nie mit `Sat` verknüpft** | vor |
| **S2** | `Sat ⊨ True/False` | 2-wertig | 2 | step-025 | ⭐ **① Ausgangspunkt von ②** | vor (**v1.1**) |
| **S3** | `Sat(K_t,r) ∈ {0,1}` | 2-wertig | 2 | Theory v1.0 | dito | vor |
| **S4** | `Sat : 𝒦 × ℛ → {⊤,⊥,U}` über 8 Klassen | 3-wertig | 2 | v1.2-Simulation | ⭐ **② Ziel, `DERIVED`** | ✅ **MITGLIED** |
| **S5** | `Sat_c : 𝒦 × ℛ_c × Γ → V_Sat` | 3-wertig | 3 | KR-SIM | ⭐ **① Projektion von `Eval_c`** | Mitglied (klassenindiziert) |
| **S6** | `𝒮_sat = {S,U,P,C}` | 4-wertig | 2 | part-03 §3.14 | ⛔ unbezeugt | nach |
| **S7** | `𝕊_sat` 5-wertig | 5-wertig | 3 | part-05 | ⛔ unbezeugt | nach |
| **S8** | `π_EC` / `χ_EC → {0,1}` | 2-wertig | 1 | part-03 / part-05 | Projektion **zurück** ins Binäre | nach |
| **SH** | Sanskrit `Sat` | — | — | Advaita | Homonym | außerhalb |

⭐ **Die Übergänge sind genau dort bezeugt, wo v1.2 liegt** (S2/S3 → S4, `Eval_c` → S5) —
und **genau dort unbezeugt, wo der Rewrite liegt** (S6, S7). Der 09-06-Rewrite führt vier-
und fünfwertige Mengen ein, **ohne einen Übergang von S4 zu behaupten oder zu begründen**.

## D · Der Widerspruch aus Phase B, neu eingeordnet

part-03 (`U` = unsatisfied/unknown **verschmolzen**) gegen part-05 (`Unknown ≠ Unsatisfied`)
bleibt bestehen. Neu ist die **Schwere**: part-03s Verschmelzung macht **rückgängig**, was
`E5` als `DERIVED` etabliert hatte — *„two-valued `Sat` collapses absence into negation"*.

$$\text{part-03 wiederholt den Fehler, dessen Behebung v1.2 „strictly better" machte.}$$

⚠️ Festgestellt, nicht adjudiziert.

## E · Ausführbarkeit (§15 getrennt halten)

| Dimension | Stand |
|---|---|
| `defined` | ✅ S4 in v1.2 |
| `implemented` | ✅ Simulation existiert |
| `tested` | ✅ 13 Experimente |
| `independently validated` | ⚠️ **`A6` widerlegt die Hinlänglichkeit** von `{⊤,⊥,U}` |
| `governance-reviewed` | ⚠️ v1.2 nennt `Sat` selbst **SEMANTICALLY INCOHERENT** |
| `selected` / `ratified` | ⛔ keiner |

**3 von 8 `Sat_c`-Klassen ausführbar**, fünf mit benannten Blockern (`⪰` · `Contr` · kein
Evaluator · keine Temporalsemantik · `δ`).

## F · Closure-Status nach §14

| | | `Sat` |
|---|---|---|
| 1 | Identität | ⚠️ **verbessert** — S2/S3→S4 und `Eval_c`→S5 sind **bezeugte Übergänge**; S6/S7 nicht |
| 2–3 | Domäne/Codomäne | ✅ für S4/S5 · ⛔ für S0 |
| 4 | Stelligkeit | ⚠️ 1 · 2 · 3 |
| 7 | Abhängigkeiten | ⛔ hängt an `Eval_c`, `Γ`, `ℛ_c`, `EC` |
| 8 | Invarianten | ✅ **`I-9`** Nicht-Kollaps, **mit registriertem Gegenbeispiel** (25D.5) |
| 9 | unaufgelöste Alternative | ⛔ **S6 gegen S7**, unbezeugt und widersprüchlich |
| 10 | Zirkularität | ⚠️ `Sat ↔ Δ`, `Sat ↔ Det_r` — Phase 11 |

$$Sat:\quad \textsf{DEFINED} \wedge \textsf{TYPE-CLOSED für S4/S5} \wedge \textsf{NOT DEPENDENCY-CLOSED} \wedge \textsf{PROJECTION OF } Eval_c \textsf{ (bezeugt, verlustbehaftet)}$$

## G · Closure decision

$$\boxed{\textbf{TEILWEISE GESCHLOSSEN.}}$$

* ⭐ **`S2/S3 → S4` : `REFINEMENT`, `DERIVED`** — geschlossen, mit Grund und Experiment.
* ⭐ **`Eval_c → S5` : `LOSSY PROJECTION`** — geschlossen, mit quantifiziertem Verlust (`U`).
* ⛔ **`S4 → S6/S7`** : **REMAINS OPEN** — der Rewrite behauptet keinen Übergang.
* ⛔ **`S0 ↔ S1`** : die Typlücke vom 08-27 bleibt **unbezeugt**.
* ⛔ **`S6 ↔ S7`** : `DISTINCT WITH CONTRADICTION`, unverändert.

⭐ **Und die Hierarchiekorrektur:** `Eval_c` ist das primäre Objekt, `Sat_c` seine Projektion
und *„MUST NOT be promoted"*. Jede künftige `Sat`-Arbeit hat bei `Eval_c` anzusetzen.
