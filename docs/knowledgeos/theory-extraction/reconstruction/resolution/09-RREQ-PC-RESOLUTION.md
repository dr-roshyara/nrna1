# Phase 9 — `ℛ_req` / `P_c` und die Pipeline · Targeted Resolution

## A · ⚠️ Die Kommissionskette ist wieder nicht die des Korpus

§13 nennt `Requirement → P_c → Eval → Sat → Accept`. Der Korpus führt (`182007`, Statuszeile):

$$E \rightarrow Rep \rightarrow Reason \rightarrow Eval \rightarrow Det$$

und `P_c` steht in einer **anderen** Kette (`closure-01`, 2026-08-27 12:56):

$$X \rightarrow Artifact \rightarrow O_s \rightarrow I \rightarrow \mathbf{P_c} \rightarrow \Sigma \rightarrow A \rightarrow K_t$$

⭐ **`P_c` und `ℛ_req` liegen nicht auf derselben Kette.** `P_c` ist eine Station der
**Beobachtungs-Pipeline** (Artefakt → Beobachtung → Interpretation → Kandidatenproposition →
epistemische Bewertung → Assertion → Wissenszustand). `ℛ_req` ist ein **Adäquatheitskriterium
für Repräsentationen**. Sie zu einer Kette zu verbinden wäre meine Konstruktion, nicht die
des Korpus. **Nicht getan.**

## B · `ℛ_req` — vollständig typisiert

**part-01** (2026-09-06), das die Definition am saubersten setzt:

$$\mathcal R_{\mathrm{req}}(Q,\Gamma) \;=\; \text{die Menge der Distinktionen, die nötig sind, um } Q \text{ unter } \Gamma \text{ zu beantworten}$$

$$\boxed{Adequate(R,Q,\Gamma) \iff \mathcal R_{\mathrm{req}}(Q,\Gamma) \subseteq Dist(R)}$$

— *„This is **one of the central principles of the theory**."*

**`SPEC-RREQ`** (2026-09-02) liefert die Erhaltungsbedingung über einer Kodierung `E : S → 𝒦`:

$$\forall s_1,s_2 \in S:\quad s_1 \nsim_d s_2 \;\Longrightarrow\; E(s_1) \neq E(s_2)$$

| | |
|---|---|
| **Typ** | `ℛ_req : Query × Context → 𝒫(Distinctions)` |
| **Distinktion `d`** | eine **Äquivalenzrelation `∼_d`** auf dem Zustandsraum `S` |
| **Adäquatheit** | Teilmengenrelation — **entscheidbar, sobald beide Seiten endlich sind** |

⭐ Das ist das **am besten typisierte Objekt** aller neun bisher untersuchten.

## C · ⛔ Aber: der Ratifikationsanspruch steht **im Dateinamen, nicht im Dokument**

Der `06-AUTHORITY-CLAIMS-AUDIT` der Verifikationsspur (Primärquelle, nicht meins):

> *„**a document declaring itself ratified is not a governance act.** Before any construct's
> status is upgraded, the act must be locatable."*

$$\boxed{\textbf{Der Governance-Register enthält keinen Akt nach 2026-08-24 und keine Erwähnung irgendeiner 09-02-Spezifikation.}}$$

| Anspruch | tatsächlicher Stempel | in `governance/`? |
|---|---|:--:|
| **`SPEC-RREQ-2026-V1-RATIFIED`** | ⭐ **`[PROPOSED RATIFICATION]`** | **NEIN** |
| `SPEC-DET-2026-v1` | `[RATIFIED]`, *„Authority: HPA Supervisory / Architecture Board"* | **NEIN** |
| `GAP CLOSURE STRATEGY` | enthält `[RATIFIED]` | **NEIN** |
| `DECISION-01` | `[DECIDED]`, *„taken by governance, not by this lane"* | **NEIN** |
| **`R1`** Faktivität | `DECIDED — R1 (governance, 2026-09-02)` | **NEIN** |
| `DECISION-02` | `[DECISION REQUIRED]` | n/a — **korrekt offen** |

⭐⭐⭐ **Der Dateiname behauptet mehr als das Dokument.** `…-V1-RATIFIED` gegen
`[PROPOSED RATIFICATION]` — eine exakt prüfbare Diskrepanz.

⚠️ **Berichtigung meiner Phase 1.** Ich hatte `R1` als **`ACT, WELL-EVIDENCED`** geführt. Der
Audit zeigt: es ist **nicht registriert**. Beide Aussagen sind wahr und gehören in
**verschiedene Dimensionen** — das *Verfahren* ist vorbildlich dokumentiert, der *Akt* ist
nicht verzeichnet. Richtige Klasse: **`ACT ASSERTED, PROCEDURE EXEMPLARY, NOT REGISTERED`**.

⚠️ Und die Grenze, die der Audit selbst zieht und die ich übernehme:

> *„**Does NOT establish that any decision was not taken.** The user acts as HPA/governance…"*

## D · Die zwei unadjudizierten Renderings

| Quelle | `Loss_req` | Typ | Adäquat wenn |
|---|---|---|---|
| `…182008` RREQ-6 | `ℛ_req ∩ Collapsed(π)` | **Menge** | `= ∅` |
| `…182016` L217 | `Σ wᵢ · 𝕀(Collapse)` | **gewichteter Skalar** | `== 0` |

`025d` **verbietet** einen Skalar (Unvergleichbarkeit). Der Konflikt ist **spurintern**;
die Spur hält fest: *„**Not adjudicated; both branches preserved.**"* Die eigentliche Frage
lautet *„may a **priority weighting** be stipulated?"* — eine **Entscheidung**, keine Ableitung.

## E · Abstammung und die einzige Identität

* **`G-05`:** gegenüber der 08-27-Menge `ℛ(P)` **null Zitate** ⇒ **`DISTINCT by type`**
  (requirements vs. distinctions), konzeptuelle Abstammung **`UNWITNESSED`**.
* **`ℛ_req`-Adäquatheit ≡ `Expressive(F,ℐ)`** — Kontrapositionen unter `ℐ ↔ ℛ_req`,
  `F ↔ E`; zwei Spuren, 32 Stunden, kein Zitat.
  ⛔ **`[INF]` — die Quelle verbietet die Aufwertung.** Status bleibt
  **`IDENTITY CANDIDATE`**, wie §13 es verlangt, **nicht `proven theorem`**.

## F · `P_c` — unverändert das einzige eindeutige Objekt

`P_c` = **Candidate Proposition**, eine Station zwischen semantischer Interpretation `I` und
epistemischer Bewertung `Σ`. 5 Definitionsstellen, **eine** Lesart, kein Homonym, keine
konkurrierende Signatur, nie Gegenstand einer Kanonisierungsdebatte.

⚠️ **Aber:** ein Eingangs-/Ausgangstyp ist **nirgends angegeben** — `P_c` ist als
*Stationsname* definiert, nicht als Funktion. `LOCATION DEFINED · TYPE UNWITNESSED`.

## G · Closure-Status nach §14

| | | `ℛ_req` | `P_c` |
|---|---|---|---|
| 1 | Identität | ✅ **ein Objekt** | ✅ |
| 2 | Domäne | ✅ `Query × Context` | ⛔ |
| 3 | Codomäne | ✅ `𝒫(Distinctions)` | ⛔ |
| 4 | Stelligkeit | ✅ **2** | — |
| 5–6 | Typen | ✅ `d` = Äquivalenzrelation | ⛔ |
| 7 | Abhängigkeiten | ⚠️ `Q`, `Γ`, `Dist(R)`, `E` | ⚠️ `I`, `Σ` |
| 8 | Invarianten | ✅ **Erhaltungsbedingung** explizit | ⛔ |
| 9 | Alternative | ⛔ **zwei `Loss_req`-Renderings** | ✅ keine |
| 10 | Zirkularität | ✅ | ✅ |

$$\mathcal R_{req}:\ \textsf{TYPE-CLOSED} \wedge \textsf{INVARIANT-BEARING} \wedge \textsf{NOT DEPENDENCY-CLOSED} \wedge \textsf{NOT RATIFIED}$$
$$P_c:\ \textsf{IDENTITY-CLOSED} \wedge \textsf{NOT TYPE-CLOSED} \wedge \textsf{LOCATION-DEFINED ONLY}$$

## H · Closure decision

$$\boxed{\mathcal R_{req}: \textbf{TYPE-CLOSED, sonst OFFEN} \qquad P_c: \textbf{EINDEUTIG, aber UNGETYPT}}$$

⭐ **`ℛ_req` ist das am weitesten geschlossene Objekt der gesamten Untersuchung** — vollständig
typisiert, mit expliziter Invariante und einer entscheidbaren Adäquatheitsrelation. Was ihm
fehlt, ist **kein mathematischer Inhalt**, sondern: eine **Adjudikation** zwischen zwei
Verlustmaßen und ein **registrierter** Ratifikationsakt.

⚠️ Und es ist damit der Gegenbeleg zu jeder pauschalen Aussage, im Korpus sei nichts
geschlossen: **hier ist die Mathematik fertig und nur die Governance offen** — die exakte
Umkehrung von `Qualify`, wo die Governance wartet, weil die Mathematik nicht wohlgestellt ist.
