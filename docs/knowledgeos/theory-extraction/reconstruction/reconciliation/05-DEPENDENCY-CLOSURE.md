# Phase E — Abhängigkeitsschluss

## 0 · Was hier gemessen wurde — und was es *nicht* ist

Für 16 Kernobjekte wurde jede **Definitionsstelle** (`X := …`, `X : … → …`, `X ⟺ …`) in
firewall-freien, **fremden** Quellen extrahiert und geprüft, welche anderen Kernobjekte im
**Definiens** vorkommen. `$$…$$`-Displays wurden vorher geglättet.

⚠️ **Evidenzklasse `TYPE-CONSTRAINED`, nicht `PROVEN`.** Eine Kante heißt: *das Definiens
nennt das Symbol.* Sie beweist keine mathematische Notwendigkeit. Kanten mit hohem Gewicht
(`Zero→Δ` 80) sind belastbar, Kanten mit `n = 1` sind es nicht. Alle Schwellen sind angegeben.

**Definitionsstellen je Objekt (fremd):**

| | | | | | | | |
|---|---|---|---|---|---|---|---|
| `K_t` **1008** | `Zero` **526** | `δ` **231** | `EC` **216** | `Sat` **179** | `Ω` **177** | `Δ` **151** | `Req` 43 |
| `Det_r` 39 | `Γ` 36 | `Eval` 36 | `Qualify` 35 | `ℐ` 23 | `P_c` **5** | `𝒪_core` **2** | `≡_sem` **2** |

## 1 · ⭐⭐⭐ Die Kommissionskette existiert nicht — sie ist ein einziger Zyklus

Die Kommission nannte die Kette

$$K_t \rightarrow \Delta \rightarrow \Omega \rightarrow \delta \rightarrow Sat \rightarrow Eval \rightarrow Det$$

Gemessen ist **keines dieser Glieder azyklisch**. Bei jeder geprüften Kantenschwelle
(`n ≥ 3`, `≥ 5`, `≥ 10`) fallen **elf** Objekte in **eine einzige starke
Zusammenhangskomponente**:

$$\boxed{\{\,K_t,\ \Delta,\ \Omega,\ \delta,\ Sat,\ Eval,\ Det_r,\ Zero,\ EC,\ Req,\ \mathcal I\,\}}$$

**Gegenseitige Definitionen (Gewichte hin/zurück):**

| | | | | |
|---|---|---|---|---|
| `Zero ↔ Δ` **80/14** | `K_t ↔ Ω` 55/18 | `Zero ↔ EC` 41/6 | `δ ↔ K_t` 34/26 | `Zero ↔ K_t` 29/8 |
| `K_t ↔ EC` 18/10 | `K_t ↔ ℐ` 17/9 | `Δ ↔ Sat` 14/3 | `Sat ↔ Det_r` 13/4 | `Δ ↔ K_t` 6/5 |

⇒ **Kein Objekt dieser elf kann zuerst definiert werden.** Jede Reihenfolge setzt eines
seiner eigenen Argumente voraus.

## 2 · ⭐⭐⭐ Und der Korpus hat genau dieses Muster **selbst verboten**

`Question 20`, **2026-08-26 18:48** — über abgeleitete Größen, die in den Zustand
zurückgeschrieben werden:

> *"Therefore these are **derived outputs** of the state. If we put them back into the state,
> we create:*
> $$S_t \rightarrow G_t \rightarrow S_t$$
> *and **risk a circular definition**. The clean model is: …"*

Dazu die **Anti-Zirkularitätsregel** vom **2026-08-25** (Kernel-Spur, McGinn-Linse):
*"This should become a **major anti-circularity rule**"* — und `step-048` führt
*„circular definitions"* unter den Korrektheitsdefekten.

⭐ **Gemessen sind `K_t ↔ Δ` (6/5) und `K_t ↔ Zero` (29/8) exakt das Muster
`S_t → G_t → S_t`.** `Δ` und `Zero` sind abgeleitete Größen — und sie stehen im Definiens
von `K_t`.

⚠️ Ich stelle das fest. **Ich repariere es nicht und kanonisiere nichts.** Die Regel ist als
*"should become"* formuliert, also ein **Vorschlag, keine Ratifikation** — was den Befund
nicht schwächt, aber seine Rechtsnatur bestimmt.

## 3 · ⭐⭐⭐ Die Fundamente sind die am schlechtesten definierten Objekte

`Γ` hat im **gesamten** Korpus genau **eine** ausgehende Definitionskante (`Γ → EC`, `n=1`).
`𝒪_core`, `≡_sem` und `Qualify` haben **keine**. Sie sind **Senken**:

```
   ┌──────────────────────────────────────────────────────────┐
   │   K_t · Δ · Ω · δ · Sat · Eval · Det_r · Zero · EC ·      │
   │   Req · ℐ        ── eine einzige zyklische Komponente ──  │
   └───────────────────────────┬──────────────────────────────┘
                               │ stützt sich auf
                               ▼
        Γ            𝒪_core         ≡_sem        Qualify        P_c
   12 widersprüch-   2 Def.-       2 Def.-      4 lebende      5 Def.-
   liche Familien,   stellen,      stellen      Signaturen     stellen
   Regelsprache      NICHT                      (CR-5)
   UNDEFINIERT       EINGEFROREN
```

$$\boxed{\text{Der gesamte Aufbau ruht auf den fünf Objekten, die er am wenigsten definiert hat.}}$$

Das ist **keine Schwäche der Rekonstruktion, sondern ein struktureller Befund**: die
Vorwärtsplanung nennt genau diese als Entscheidungen (`OQ-1` Träger, `CR-5` `Qualify`,
`CR-4` `≡_sem`, `𝒪_core` nicht eingefroren) — und die Messung zeigt jetzt **warum sie
gaten**: sie sind die einzigen Knoten ohne eigene Voraussetzungen.

## 4 · Konsequenz für die Phasen A–D

| Phase | Befund | wird hier erklärt durch |
|---|---|---|
| **A** `Zero` | `Zero_strict` in **jedem** Test unerreichbar | `Zero ↔ Δ ↔ Sat` — Zero misst, was `Sat` liefert |
| **B** `Sat` | ungetypt geboren, 11 Codomains | `Sat`s Fundament `Γ` ist selbst untypisiert |
| **C** `δ` | **kein Commit-Fall**, `K₁ is K₀` | `Γ` ist Senke und **kein Bestandteil von `K`** |
| **D** `Γ` | zugleich Menge/Tupel/Funktion/Wert | als Senke wird `Γ` von **nichts** eingeschränkt |

⭐ Die vier Phasen fanden **vier Symptome derselben Struktur**: das Fundament ist unbestimmt,
und darüber definieren sich elf Objekte gegenseitig.

## 5 · Ergebnis

$$\textbf{Der Abhängigkeitsschluss ist NICHT wohlfundiert.}$$

* **Keine topologische Ordnung existiert** über den elf Kernobjekten.
* **Kein „erst X, dann Y"-Programm** kann aus dem Korpus abgeleitet werden — jede solche
  Reihenfolge wäre eine **Entscheidung**, keine Ableitung.
* Die einzigen wohlfundierten Startpunkte sind die **fünf Senken**, und alle fünf sind
  ausdrücklich **offen** (`OPEN BY COMMISSION` bzw. `NOT FROZEN`).

⚠️ Damit ist auch gesagt, was **nicht** folgt: der Zyklus macht die Theorie **nicht falsch**.
Wechselseitig rekursive Definitionen sind zulässig, wenn ein Fixpunkt-Argument sie stützt.
**Ein solches Argument ist im Korpus nicht gefunden worden** — das ist der Befund, nicht die
Zirkularität als solche.

## 6 · Offen

* Ein **Fixpunkt-/Fundierungsargument** für die elf Objekte: `NOT YET LOCATED`.
* Ob die Anti-Zirkularitätsregel je ratifiziert wurde: `NOT YET LOCATED` (Wortlaut *"should become"*).
* Kanten mit `n ≤ 2` sind **nicht belastbar** und hier bewusst nicht ausgewertet.
