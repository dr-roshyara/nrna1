# Phase 11 — Der „11er-Zyklus" · Reaudit nach §7

## 0 · ⛔ WIDERRUF 5 — es gibt **keinen** elfgliedrigen Zyklus

Phase E maß Kanten als *„das Definiens **nennt** das Symbol"* — über den **ganzen Fließtext**.
Beschränkt man dieselbe Messung auf **echte mathematische Definitionsumgebungen**
(`$$…$$`-Displays, linksseitig das definierte Objekt):

| Messung | Kanten | Zyklus |
|---|---|---|
| **Phase E** (Fließtext) | ~500 | **ein Zyklus mit 11 Objekten** |
| **streng** (nur Display-Math), `n ≥ 1` | 355 | ein Zyklus mit **7** |
| **streng**, `n ≥ 2` | | ein Zyklus mit **6** |
| ⭐ **streng, `n ≥ 3`** | | ⭐ **zwei Zyklen: `K_t ↔ δ` (2) und `Δ ↔ Det ↔ Sat ↔ Zero` (4)** |

$$\boxed{\text{Der 11er-Zyklus war überwiegend ein Artefakt von Prosa- und Erklärungsverweisen.}}$$

Und `Γ`, `EC`, `Ω`, `Eval`, `Req` liegen in der strengen Messung **in keinem Zyklus**.

## 1 · §7 A/B — waren es echte Definiens-Abhängigkeiten?

**Nein, überwiegend nicht.** Die Differenz zwischen 11 und 2+4 Objekten ist genau der Anteil
aus erklärenden Verweisen, Beispielen, Querverweisen und Validierungsbezügen.

⚠️ Ich hatte die Kanten korrekt als `TYPE-CONSTRAINED` markiert — aber daraus eine
**strukturelle Schlussfolgerung** gezogen (*„nicht wohlfundiert"*), die diese Evidenzklasse
nicht trägt. **Die Klassifikation war richtig, ihre Verwendung nicht.**

## 2 · ⭐⭐⭐ §7 C — Zyklus 1 `Δ ↔ Zero`: **eine Gleichung, zweimal gerichtet gelesen**

| Richtung | Beleg |
|---|---|
| `Zero → Δ` | `Zero : (K_t,I_t) → Δ_t` · `Zero(S_t) = Δ_t` (08-26 18:47/18:48) |
| `Δ → Zero` | `Δ = Zero(K_t,I,EC_G)` (08-27 18:24) |

Das ist **dieselbe Aussage**, einmal als `Zero(…) = Δ`, einmal als `Δ = Zero(…)` notiert.
Meine gerichtete Kantenextraktion hat **eine Gleichung in zwei Gegenkanten verwandelt**.

$$\boxed{\textsf{FALSE GRAPH EDGE}}$$

## 3 · ⭐⭐⭐ §7 C — Zyklus 2 `Sat ↔ Det`: ein **Homonym**, kein Kreis

| Richtung | Beleg | Objekt |
|---|---|---|
| `Det → Sat` | `Det(K,p,EC,Γ) ⟺ ∀r ∈ Req_p(EC,Γ): Sat(K,r) = Satisfied` (part-03) | **`Det` über einer Proposition `p`** — Prädikat |
| `Sat → Det` | `Sat(K,r,Γ) = Det_r(EvalReq(K,r,EC,Γ), EC)` (part-06) | ⭐ **`Det_r` über einem Requirement `r`** — Funktion nach `𝕊_sat` |

**`Det` und `Det_r` sind verschiedene Objekte** — verschiedene Argumente (Proposition vs.
Requirement), verschiedene Codomänen (Wahrheitswert vs. `𝕊_sat`), verschiedene Ebenen. Mein
Knotenmuster `Det(?![a-z])` hat beide zu **einem** Knoten verschmolzen.

$$\boxed{\textsf{SEPARATE OBJECT FAMILIES}}$$

## 4 · ⭐⭐⭐ Und `K_t ↔ δ`: Träger gegen Element

`δ : 𝒦 × ℰ ⇀ 𝒦` — `δ`s Definiens nennt den **Träger** `𝒦`.
`K_{t+1} = δ(K_t, e_t)` — `K_t`s „Definiens" nennt `δ`, ist aber gar keine Definition von
`K_t`, sondern die **Erzeugung eines Elements**.

Mein Knotenmuster fasste `𝒦`, `𝕂` und `K_t` zu **einem** Knoten zusammen. Damit wurde der
Standardaufbau eines dynamischen Systems zu einem Kreis gemacht.

$$\boxed{\textsf{FALSE GRAPH EDGE} — \text{Träger } \mathcal K \text{ gegen Element } K_t}$$

## 5 · ⭐⭐⭐ Die Kette ist **azyklisch** — und sie ist die des Korpus

Setzt man die vier Belege richtig zusammen:

$$EvalReq \;\rightarrow\; Det_r \;\rightarrow\; Sat \;\rightarrow\; \underbrace{\{\,\Delta \;\equiv\; Zero\,\}}_{\text{eine Gleichung}} \;\rightarrow\; Det_{\text{prop}}$$

mit `Det(Q) ⟺ Δ_Q = ∅` als Abschluss. **Kein Kreis.**

Und sie deckt sich mit der Reihenfolge, die der Korpus selbst gesetzt hat (Phase 10):

$$Eval \rightarrow Sat \rightarrow \Delta^{sem} \rightarrow Zero \qquad\qquad \text{evaluation semantics} \rightarrow \text{closure semantics} \rightarrow \text{kernel}$$

## 6 · §7 D — liegen die Objekte auf derselben semantischen Ebene?

**Nein — und v1.2 hat das konstitutionell festgeschrieben.** Die Vierschichtentrennung
(Phase 1, Zug ①) verteilt sie:

| Schicht | Objekte |
|---|---|
| Philosophisch/konzeptuell | Linsen, `Zero` als Meta-Prinzip |
| **Epistemisch-mathematisch** | `Δ`, `Sat`, `≡`, `≈`, `Eval`, `Det_r` |
| **DDD** | `KnowledgeIdentity`, `KnowledgeAggregate` |
| Architektur | Kernel, Compiler, Projektionen |

⇒ Kanten, die Schichten kreuzen, sind **keine Definitionsabhängigkeiten**, sondern
**Bezugnahmen** — und v1.2 verbietet die Ableitung über Schichtgrenzen ausdrücklich.

## 7 · §7 E — gibt es Regeln zur gegenseitigen Definition?

**Ja, zwei, und sie ziehen in verschiedene Richtungen:**

| | Regel | Rechtsnatur |
|---|---|---|
| ⭐ **pro** | `Γ` als **stabile Expansion** (Moore/autoepistemische Logik) — `Γ` steht auf beiden Seiten, **korrekt als Fixpunkt** | Lehrbuchobjekt, **im Korpus verwendet** |
| **contra** | *„This should become a major **anti-circularity rule**"* (08-25) · `Question 20`: `S_t → G_t → S_t` *„risk a circular definition"* (08-26) | ⚠️ *„should become"* — **Vorschlag, nicht Ratifikation** |

⇒ Selbstreferenz ist in der Praxis des Korpus **zulässig, wenn ein Fixpunkt sie trägt**.
Die Anti-Zirkularitätsregel zielt auf ein **spezifisches Muster** (abgeleitete Größen in den
Zustand zurückschreiben), nicht auf jede wechselseitige Bezugnahme.

## 8 · §7 F — gibt es eine Schichtung, die den Schein erklärt?

**Ja, drei zusammenwirkende:**
1. die **Vierschichtentrennung** von v1.2,
2. die **Reihenfolge** `evaluation → closure → kernel` des Korpus,
3. die **Träger/Element**-Unterscheidung (`𝒦` gegen `K_t`), die ich verletzt hatte.

## 9 · Verdikt nach §7

| Kandidat | Urteil |
|---|---|
| `CIRCULAR DEFINITION` | ⛔ **nein** |
| `MUTUAL DEFINITION` | ⛔ nein — kein Paar bleibt nach Disambiguierung übrig |
| ⭐ **`FALSE GRAPH EDGE`** | ✅ **`Δ ↔ Zero`** (eine Gleichung) · **`K_t ↔ δ`** (Träger/Element) |
| ⭐ **`SEPARATE OBJECT FAMILIES`** | ✅ **`Det` gegen `Det_r`** |

$$\boxed{\textbf{Die Theorie ist an dieser Stelle NICHT zirkulär. Mein Graph war es.}}$$

## 10 · Was damit **hinfällig** wird

| Phase-E-Aussage | Status |
|---|---|
| *„elf Objekte in einer starken Zusammenhangskomponente"* | ⛔ **widerrufen** |
| *„keine topologische Ordnung existiert"* | ⛔ **widerrufen** — die Ordnung existiert und steht im Korpus |
| *„der Aufbau ruht auf den fünf am wenigsten definierten Objekten"* | ⛔ **widerrufen** (Phase 2, Scope-Artefakt) |
| *„ein Fixpunktargument ist `NOT YET LOCATED`"* | ⛔ **widerrufen** — `Γ` als stabile Expansion |
| *„der Abhängigkeitsschluss ist nicht wohlfundiert"* | ⛔ **widerrufen für die geprüften Objekte** |

⚠️ **Was bestehen bleibt:** `Γ` und `EC` sind in der strengen Messung **Senken innerhalb
dieser Knotenmenge** — aber Phase 2 hat gezeigt, dass `Γ` außerhalb dieser Menge sehr wohl
Kanten hat. **Senkenaussagen sind nur relativ zur Knotenmenge gültig**, und das ist jetzt
zweimal gemessen worden.

## 11 · Offen

* Die **strenge Messung erfasst nur `$$…$$`-Displays.** Definitionen in Fließtext oder
  Tabellen fehlen — die Zahlen sind eine **untere Schranke** für echte Kanten.
* `Det` gegen `Det_r` ist **disambiguiert, aber nicht adjudiziert** — beide leben weiter.
* Ob die Anti-Zirkularitätsregel je ratifiziert wurde: weiterhin `NOT YET LOCATED`.
