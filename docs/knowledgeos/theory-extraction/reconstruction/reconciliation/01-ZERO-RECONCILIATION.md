# Phase A — `Zero` Reconciliation

> **Reconciliation reduces historical forms into evidence-supported object identities;
> it does not erase historical differences.**

## ⚠️ 0 · Zurückgezogene Zahl

Mein Bericht nannte *„~440 Zero-Formen"*. **Das war eine Hochrechnung aus drei Teilblöcken,
keine Messung.** Gemessen über den gesamten Fahrplan (Firewall aus, **eigene Artefakte
ausgeschlossen**):

| | |
|---|---|
| distinkte Formen in **fremden** Quellen | **176** |
| nur in **eigenen** Artefakten | 23 |
| gesamt | 199 |

**Überschätzung um Faktor 2,5 — dreizehnter Messfehler.** Alte Zahl bleibt nach §16 vermerkt.
Verteilung: **40 im August, 136 im September** ⇒ **77 % der Formen entstehen nach v1.2.**

## 1 · Cluster nach **Codomain-Typ** (nicht nach Syntax)

| Klasse | n | erstmals | Beispiel |
|---|---|---|---|
| **A Boolean / 0-1** | **62** | **2026-09-01** | `Zero(K_t, I_Q, EC) = 0 \| 1` |
| **I prosaisch/semantisch** | 47 | 2026-08-24 | `Zero(K) = invariant structural core`; `= EpistemicBoundaryAnalysis(K)` |
| ⚠️ **H abgeschnitten** | **32** | — | **MESSARTEFAKT** — Regex fing `Zero(…) =` ohne RHS |
| **B Leerheit `Δ=∅`** | 11 | **2026-09-02** | `Zero(K_t,EC_t) ⟺ Δ_t = ∅` |
| **F Komposition** | 7 | 2026-09-03 | `Zero(S) = f(Zero(x₁),…,Zero(xₙ))` |
| **E Distanz** | 6 | 2026-08-29 | `Zero(K,K*,EC) = EpistemicDistance` |
| **C Menge/Tupel** | 5 | **2026-08-26** | `Zero(K_t,U_t,X_t,I_t) = (d_E,d_U,d_D)` — **4 Arg., Tripel** |
| **D Mengendifferenz** | 3 | **2026-08-26** | `Zero(K_t) = Ω ∖ Represented(K_t)` |
| **G Projektion** | 3 | 2026-09-04 | `Zero_i(K_t) ⟺ Π_i(K_t) = 0_{O_i}` |

⇒ Nach Abzug der 32 Artefakte: **144 echte Formen in 8 Klassen, davon 7 mit mathematisch
unvereinbarer Codomain.**

$$\text{Boolean} \neq \text{Leerheitsprädikat} \neq \text{Tupel} \neq \text{Mengendifferenz} \neq \text{Distanz} \neq \text{Kompositionsfunktion} \neq \text{Projektion}$$

⭐ **Die älteste mathematische Form ist `D` (08-26, `Ω ∖ Represented`), nicht `B` (`Δ=∅`,
09-02).** Die häufigste Klasse `A` (Boolean, 35 %) beginnt **erst am 09-01**.

## 2 · ⭐⭐⭐ Die `Zero_strict` / `Zero_weak` / Kleene-Familie — mit Exekutionsbefund

`theory-v1.2-simulation`:
$$Zero_{strict}(g) \iff violated = \varnothing \wedge undetermined = \varnothing$$
$$Zero_{weak}(g) \iff violated = \varnothing$$
plus **Kleene-`Zero`** als dritte Spalte der Ergebnistabelle.

> *"**`Zero_strict` was unreachable in every case tested** — because governance, temporal and
> [operational]"* · *"E1 shows those two classes alone are responsible for **`Zero_strict`
> never being reachable**."*

⭐⭐⭐ **Das koppelt `Zero` direkt an die blockierten `Sat_c`-Klassen** (`G-38`): die strengste
Zero-Form ist **ausführungsseitig unerreichbar**, und zwar aus genau denselben Gründen.
**Ein Exekutionsbefund, keine Definition.**

## 3 · §13 Adversariale Identitätsprüfung

| Suche | Ergebnis |
|---|---|
| **explizite Identitätsaussage** zwischen zwei Zero-Formen | ⭐ **KEINE.** Die Treffer sind Kritik (*"Zero is mathematically equivalent to any philosophical concept"*) oder betreffen andere Objekte |
| **explizite Trennung** | mehrere: *"Zero must not become a kernel article"*, *"must not become too powerful"*, *"a scalar must not become the knowledge it represents"* |
| **Mehrzahlaussagen** | *"at least **four Zero boundaries**"* — ⚠️ das sind die **`Z1–Z4`-Grenzen**, nicht vier Zero-Objekte. Meine erste Lesart war falsch |

*(Positivkontrolle bestanden: dasselbe Muster findet `Zero is a research lens`, `Zero lens`
u. a. — die zuvor leeren `ugrep`-Läufe waren Unicode-Komplexitätsartefakte, **vierzehnter
Messfehler**.)*

## 4 · Reconciliation-Ergebnis

| Cluster | Status | Begründung |
|---|---|---|
| `Zero_A` Meta-Prinzip (ratifiziert `Z-KOS-001`) | **DISTINCT** | ausdrücklich *"not a lifecycle state, not an algorithm"*, *"outside the kernel"* |
| `Zero_D` `Ω ∖ Represented` | **DISTINCT** | Codomain ist eine Ω-Teilmenge |
| `Zero_C` Tupel `(d_E,d_U,d_D)` | **DISTINCT** | 4-stellig, Tripel-Codomain |
| `Zero_E` `EpistemicDistance` | **DISTINCT + SPÄTER VERWORFEN** | v1.0 §29 verwirft die Subtraktionsidee |
| `Zero_B` `Δ = ∅` | **DISTINCT** | Prädikat über Requirement-Menge |
| `Zero_strict/weak/Kleene` | **DISTINCT (3)** | über `GapPartition`-Blöcken definiert |
| `Zero_A_bool` 0/1 | **DISTINCT** | Boolean-Codomain |
| `Zero_F` Komposition | **DISTINCT, POST-V1.2** | Funktion über Zero-Werten |
| `Zero_G` Projektion | **DISTINCT, POST-V1.2** | `Π_i`-basiert |
| `Zero_X(K,XC)` Rewrite-Schema | **DISTINCT, POST-V1.2** | parametrisiert über Kontrakttypen |

$$\boxed{\textbf{KEINE Merges.} \text{ Für keine Paarung existiert positive Identitätsevidenz.}}$$

**Status aller Paarungen: `IDENTITY UNWITNESSED`** — bei `Zero_A ↔ Zero_B` sogar
**`DISTINCT OBJECTS` mit Gegenbeleg** (`Z-KOS-001` schließt die spätere Form aus).

## 5 · v1.2-Zuordnung

**`PRE-V1.2`**: `Zero_A` (08-22), `Zero_C`/`Zero_D` (08-26), `Zero_E` (08-29)
**`V1.2`**: `Zero_B` (`DEF-22`, 09-02), `Zero_strict/weak/Kleene` (v1.2-Simulation)
**`POST-V1.2`**: `Zero_F`, `Zero_G`, `Zero_X`-Schema, Boolean-Klasse ab 09-01 (teilweise)

⚠️ **Keine dieser Zuordnungen impliziert `ratified in v1.2`** — nur `Zero_A` trägt überhaupt
eine Ratifikation, und die betrifft das Meta-Prinzip.

## 6 · Offen

* Die **32 abgeschnittenen Formen** (Klasse H) sind unaufgelöst — mein Muster, nicht der Korpus.
* Die **47 prosaischen Formen** (Klasse I) sind semantisch, nicht formal — Typprüfung steht aus.
* **Warum die Boolean-Klasse erst am 09-01 beginnt** und dann 35 % ausmacht, ist ungeklärt.
