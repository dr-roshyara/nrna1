# Phase B — `Sat` Reconciliation

## ⛔ 0 · FIREWALL-VORFALL (zuerst, weil er die Evidenzbasis betrifft)

Eine meiner Suchen lief mit Scope `docs/` **ohne** `--exclude-dir=three_model_convergence`.
Vier Dateien aus dem permanent gesperrten Verzeichnis (`MD-074`, `MD-078`, `MD-083`, `MD-087`)
haben Trefferzeilen angezeigt.

**Behandlung:** die Zeilen sind **quarantäniert**. Nichts daraus ist zitiert, paraphrasiert
oder als Evidenz verwendet; **keine Aussage dieses Registers stützt sich darauf.** Jede
Aussage unten ist unabhängig aus firewall-freien Quellen belegt. Alle Folgesuchen tragen den
Ausschluss.

⚠️ **Ursache: Scope-Disziplin, nicht Werkzeug.** Der Ausschluss muss in der Suche stehen,
nicht in meiner Absicht.

## 1 · Provenance zuerst — 46 von 122 Quellen sind kein Korpus

Bei `Zero` habe ich Provenance über Pfadmuster geschätzt. Das trägt hier nicht:

| Klasse | Dateien | Bedeutung |
|---|---|---|
| **KORPUS** (vor Programmbeginn) | **76** | Evidenz |
| **KORPUS, zeitgleich** (`what_is_knowlegeos_theory/`, 09-09) | **10** | Evidenz, aber **während** der Rekonstruktion entstanden |
| **MEIN OUTPUT** | **24** | ⛔ keine Evidenz |
| **UNRECORDABLE** (untracked) | **12** | fremde Audits, Provenance nicht wiederherstellbar |

⚠️ **Fünfzehnter Messfehler.** Mein erster Provenance-Lauf nutzte `git log --diff-filter=A`
**ohne Rename-Verfolgung** und hätte **10 Korpusdateien als meinen eigenen Output**
ausgewiesen — sie kamen durch einen reinen Umbenennungs-Commit herein. Mit `--follow` korrigiert.

⚠️ Die frühere Angabe *„~336 Sat-Formen"* war wie bei `Zero` eine Hochrechnung. **Gemessen:
175 distinkte Formen mit Argumenten in fremden Quellen** (+11 nur in eigenen). Faktor ~1,8.

## 2 · ⭐⭐⭐ `Sat` wird **ungetypt** geboren — und die Codomain daneben ist fünfwertig

`step-023`, **2026-08-27 16:25**, §10:

> `Sat(K,r_i)` — *"as the **degree/status** to which knowledge K satisfies requirement r_i"*

**Keine Signatur, keine Codomain.** Eine Zeile davor, §9, steht eine *andere*, benannte Menge:

$$Status(r_i) \in \{Satisfied,\ Unsatisfied,\ Unknown,\ Conflicted,\ NotApplicable\}$$

**Die Verbindung zwischen `Sat` und `Status` wird nie hergestellt.** Genau hier entsteht die
gesamte spätere Vielfalt: nicht durch Uneinigkeit, sondern durch eine **nie geschlossene Typlücke**.

⭐ Und im selben Dokument, direkt über `Status`, wird die binäre Lesart **am Geburtstag
zurückgewiesen**:

> ```text
> requirement_met = true/false
> ```
> *"because `Unknown` and `Conflicted` matter."*

## 3 · Die Codomain-Familien, chronologisch

| | Codomain | ⋕ | erstmals | Quelle |
|---|---|---|---|---|
| **S0** | **ungetypt** ("degree/status") | — | **2026-08-27 16:25** | step-023 §10 |
| **S1** | `{Satisfied,Unsatisfied,Unknown,Conflicted,NotApplicable}` | **5** | 2026-08-27 16:25 | step-023 §9 (als `Status`) |
| **S2** | `⊨ True / False` | 2 | 2026-08-27 16:26 | step-025 |
| **S3** | `{0,1}` | 2 | **2026-09-02 08:23** | Theory v1.0 |
| **S4** | `Sat : 𝒦 × ℛ → {⊤,⊥,U}` | 3 | 2026-09-02 09:25 | v1.2-Simulation |
| **S5** | `Sat_c : 𝒦 × ℛ_c × Γ → V_Sat = {⊤,⊥,U}` | 3 | 2026-09-02 | KR-SIM |
| **S9** | `Sat_c := value ∘ Eval_c` (Projektion einer Evaluation) | — | 2026-09-02 10:22 | Experiment G |
| **S6** | `𝒮_sat = {S,U,P,C}`, `Sat : 𝕂 × Req → 𝒮_sat` | **4** | **2026-09-06** | **part-03 §3.14** |
| **S7** | `𝕊_sat = {Satisfied,Partial,Unsatisfied,Unknown,Conflicted}` | **5** | **2026-09-06** | **part-05** |
| **S8** | `π_EC : 𝒮_sat → {0,1}` · `χ_EC : 𝕊_sat → {0,1}` | 2 | 2026-09-06 | part-03 / part-05 |
| **SH** | Sanskrit `Sat` / `Mithya` / `Asat` | — | 2026-08-26 | Advaita-Linse |

**Aritäten gemessen:** 1-stellig **13** · 2-stellig **536** · 3-stellig **57** — drei Aritäten.
**Subskriptfamilien: 16+** — `Sat_c` 476 · `Sat_content` 48 · `Sat_consistency` 34 ·
`Sat_new` 22 · `Sat_op` 18 · **`Sat_F4` 18** *(F4-Spur — ⛔ kein Brückenschlag)*.

## 4 · ⭐⭐⭐ Der Widerspruch **innerhalb desselben Rewrites, am selben Tag**

**part-03 §3.14** verschmilzt zwei Werte:

> `𝒮_sat = {S,U,P,C}` wobei **`U` = unsatisfied/​unknown**

**part-05** verbietet genau diese Verschmelzung:

$$Unknown \neq Unsatisfied$$

Beide vom **2026-09-06**, beide im 23-teiligen Rewrite, **keine zitiert die andere**. `part-06`
übernimmt anschließend `𝕊_sat` (5-wertig) und lässt `𝒮_sat` (4-wertig) **wortlos fallen** —
eine Gabelung innerhalb eines einzigen Werks.

⭐ Und `Unknown ≠ Unsatisfied` ist **älter als `Sat`**: `step-013`, **2026-08-27 15:37** —
48 Minuten vor der Geburt des Symbols (die Verifikationsspur schreibt selbst „nine files and 48 minutes later"). Es steht fünfmal in vier Dokumenten.

## 5 · ⭐ Der Boolean-Kollaps geschieht **dreimal gegen ein stehendes Verbot**

Normativ im Definition-Register:

> **`I-9`** — *"Zero's non-satisfaction typology **must not collapse to Boolean**"* · `READ (025d)`
> *· normative content intact*

Trotzdem: **v1.0** `Sat(K_t,r) = 0|1` · **part-03** `Sat(K,r)=0` und `π_EC → {0,1}` ·
**part-05** `χ_EC → {0,1}`.

⚠️ Das ist **kein Merge und keine Reparatur** — ich stelle nur fest, dass drei Reduktionen
gegen eine ausdrücklich als *normative content intact* geführte Nicht-Kollaps-Regel laufen.
Die Adjudikation gehört der Governance.

## 6 · ⭐⭐⭐ Die dreiwertige Codomain ist **experimentell widerlegt** — und das trifft `Zero`

Die v1.2-Simulation notiert die Streitlage selbst: *"value set **contested**: `{⊤,⊥}` oder
`{⊤,⊥,U}`"*. `Experiment G · A6` entscheidet sie negativ:

> **A6 — is `EVal = {⊤,⊥,U}` sufficient? No. `[EXP]` — refutes N2**
> `T` 1 Situation · `F` 1 · `C` 1 · `UNDEFINED` 1 · **`U` neun**

Die neun: `DELTA_UNDEFINED` · `NO_EVALUATOR` · `NO_ORDERING` · `NO_TEMPORAL_SEMANTICS` ·
`INSUFFICIENT_PROVENANCE` · `UNOBSERVED` · `UNDERDETERMINED` · `NON_TERMINATING` ·
**`CONTRADICTORY_INPUT`** — über `[OPEN]`, `[PROP]` **und** `[NEG]` hinweg.

$$\boxed{\text{Und: } \texttt{Zero\_reasoned} \text{ liest } U \text{ als schließend.}}$$

> *"a contradiction becomes theory-blocked `U`, which `Zero_reasoned` treats as closing.
> **A system holding `p` and `¬p` would be declared 'nothing more for the agent to do'.**"* `[NEG]`

⭐ **Das ist die Kopplung zwischen Phase A und Phase B.** Der Codomain-Defekt von `Sat`
propagiert in die `Zero`-Familie und kehrt dort das Ergebnis um. Beide Phasen mussten
getrennt gemessen werden, um das sichtbar zu machen.

## 7 · §13 Adversariale Identitätsprüfung

| Suche | Ergebnis |
|---|---|
| **Identitätsaussage** zwischen zwei `Sat`-Formen | ⭐ **KEINE** |
| **Trennungsaussagen** | **viele, und normativ** |

* `Unknown ≠ Unsatisfied` — step-013 (08-27 15:37), ×5 in 4 Dokumenten, zuletzt part-05
* `I-9` — Nicht-Kollaps zu Boolean
* step-023 — *"because Unknown and Conflicted matter"*
* part-03 — *"the satisfaction semantics **must be contract-specific**"*
* part-06 — *"The same evidence can produce **different satisfaction states** under different contracts"*
* part-21 — *"Constraint unsatisfiability **must not be confused with** proposition falsity"*

**Homonym:** `Sat`/`Mithya`/`Asat` ist **korpuseigen bereits adjudiziert** —
`STEP-TRACE-B2`: *"HEURISTIC analogy … **NOT RELEVANT to formal model**"*. Kein offener Punkt,
nur eine Namenskollision (`EKS-43`).

## 8 · Unabhängige Übereinstimmung aus der zeitgleichen Korpusspur

`what_is_knowlegeos_theory/20260909-2213` (**nicht mein Artefakt**):

> *"The existence and role of `Sat` are present in the historical corpus, but a fully
> operational, corpus-native definition of `Sat(K_t,r)` **has not yet been recovered**.
> The later `Sat*` construction is therefore a **candidate construction**."*

Diese Spur kommt unabhängig zum selben konservativen Ergebnis.

## 9 · Reconciliation-Ergebnis

| Paarung | Status |
|---|---|
| **S6 `{S,U,P,C}` ↔ S7 `{Satisfied,Partial,Unsatisfied,Unknown,Conflicted}`** | ⭐ **DISTINCT WITH CONTRADICTION** — S7 verbietet ausdrücklich S6s Verschmelzung |
| S1 ↔ S7 | **DISTINCT** — beide 5-wertig, aber `NotApplicable` ↔ `Partial` getauscht |
| S3 `{0,1}` ↔ alles Mehrwertige | **DISTINCT**, Reduktion gegen `I-9` |
| S4 ↔ S5 (`V_Sat`) | **IDENTITY UNWITNESSED** — gleiche Wertemenge, aber S5 ist klassenindiziert und 3-stellig |
| S0 ↔ alles | **UNTYPED** — kann mit nichts identifiziert werden |
| S8 `π_EC` ↔ `χ_EC` | **IDENTITY UNWITNESSED** — gleiche Rolle, **verschiedene Domäne** (4- vs 5-wertig) |
| SH ↔ alles Formale | **HOMONYM**, korpuseigen adjudiziert |

$$\boxed{\textbf{KEINE Merges.}}$$

## 10 · v1.2-Zuordnung

**PRE-V1.2**: S0, S1, S2 · **V1.2**: S3, S4, S5, S9 · **POST-V1.2**: S6, S7, S8 (Rewrite 09-06)
**Außerhalb**: SH

⚠️ Keine Ratifikation für irgendeine `Sat`-Form gefunden.

## 11 · Offen

* Die **`Status`↔`Sat`-Typlücke von 08-27** ist bis heute nicht geschlossen — `OPEN BY COMMISSION`.
* **16+ Subskriptfamilien** sind nur gezählt, nicht typisiert.
* `Sat_F4` (18) liegt jenseits der F3/F4-Grenze — **`FIREWALL-LIMITED`, kein Brückenschlag**.
* Die **12 `UNRECORDABLE`** Quellen tragen Sat-Formen, die ich nicht provenienzieren kann.
