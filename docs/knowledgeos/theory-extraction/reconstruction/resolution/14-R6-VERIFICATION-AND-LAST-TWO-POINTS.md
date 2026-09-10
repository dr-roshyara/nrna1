# Phase 14 — `R6` unabhängig nachgerechnet · und die letzten zwei forschbaren Punkte

## 1 · ⭐⭐⭐ `R6` — der minimale Schnitt, **unabhängig reproduziert und verschärft**

Step 289 legt seine Rechnung offen: `step-289/exec/t289_bootstrap_v2.py` mit gespeicherter
Ausgabe. Der Skriptkopf formuliert dabei genau die Disziplin, die ich in Phase E verletzt hatte:

> *„Edges are **DEFINITIONAL or OPERATIONAL only**: **„A and B are mentioned together" is NOT
> an edge**"* · *„The cut set is **computed by exhaustive search, NOT asserted**"* ·
> *„SCOPE: the graph is the AUDITED EDGE LIST. **A different edge list gives a different cut.**"*

### Stufe 1 — Reproduktion

```
python3 t289_bootstrap_v2.py | diff OUT-t289_bootstrap_v2.txt -   →   ✅ byte-identisch
```

⚠️ Das beweist nur, dass das Skript seine eigene Ausgabe erzeugt. **Keine Verifikation.**

### Stufe 2 — echte unabhängige Nachrechnung

Ich habe die **Kantenliste aus der Quelle geparst** (nicht importiert, nicht abgeschrieben)
und Zyklensuche sowie minimalen Feedback-Vertex-Set **selbst implementiert**:

| | Step 289 | **meine unabhängige Rechnung** | |
|---|---|---|---|
| Knoten | 27 | **27** | ✅ |
| Kanten | 42 | **42** | ✅ |
| Elementarzyklen | 4 | **4** | ✅ |
| ↳ | `approx→equiv→approx` · `congruence→equiv→congruence` · `approx→equiv→bindings→approx` · `congruence→equiv→delta→congruence` | **identisch** | ✅ |
| minimaler Cut | Größe 1, eindeutig, `{equiv}` | **Größe 1, eindeutig, `{equiv}`** | ✅ |
| Suchraum | **12** „resolvable" Knoten | ⭐ **alle 27 Knoten** | **stärker** |

$$\boxed{\{\equiv\} \text{ ist der eindeutige minimale Feedback-Vertex-Set über der \textbf{gesamten} Knotenmenge, nicht nur über der auflösbaren Teilmenge.}}$$

⭐ Das ist eine **Verschärfung** des fremden Ergebnisses: Step 289 suchte in einem
eingeschränkten Raum und fand `{equiv}`; die Suche über alle 27 Knoten findet **keine
kleinere und keine andere** Lösung.

⭐⭐ Und es ist das **erste Mal in diesem Programm**, dass ich ein fremdes Ergebnis
unabhängig nachgerechnet, bestätigt **und** verstärkt habe — statt es zu zitieren.

⚠️ **Der Scope-Vorbehalt der Quelle gilt unverändert und für mich genauso:** eine andere
Kantenliste ergibt einen anderen Cut. Das Ergebnis ist relativ zu **dieser auditierten
Kantenliste** — dieselbe Relativität, die meine eigenen Zyklus- und Senkenbefunde dreimal
eingeholt hat.

## 2 · Offener Punkt 7 — die Auslassung in `Γ = (𝒮,𝒞,𝒯,𝒫,…)`

**Zensus über alle firewall-freien Primärquellen: null Treffer.** Keine Quelle löst die
Auslassung auf oder nennt die vollständige Komponentenliste.

$$\Rightarrow \textsf{GENUINE CORPUS GAP} \text{ — bestätigt nach Zensus, nicht bloß vermutet.}$$

⚠️ Das **geschlossene** `Γ_7 = ⟨Domain, Participant, Time, Purpose, Constraints, Vocabulary,
Authority⟩` (part-01) ist **keine** Auflösung: es ist eine **andere** Familie, mit einem
eigenen Hedge (*„a context **may** contain"*), und keine Quelle stellt die Beziehung her.

## 3 · Offener Punkt 5 — die `Status ↔ Sat`-Typlücke

**Nicht geschlossen — und `Status` selbst ist gedriftet.**

| Datum | Form | Werte |
|---|---|---|
| 08-27 16:25 | `Status(r_i) ∈ {…}` | `Satisfied · Unsatisfied · Unknown · Conflicted · NotApplicable` |
| 08-27 18:31 | `Status(r_1)=Satisfied`, `Status(r_3)=Unknown` | Verwendung in der Zero-Algebra |
| **09-06** | ⭐ **`Status(r,Γ)`** — part-09 | ⭐ **`Status(r,Γ) = Supported`** |

⭐ Zwei unbemerkte Änderungen über zehn Tage:
1. **`Status` gewinnt einen `Γ`-Parameter** — von 1-stellig auf 2-stellig;
2. **der Wert `Supported`** taucht auf und steht **nicht** in der Ursprungsmenge.

⇒ Die Lücke zwischen `Sat` und `Status` ist **nicht nur ungeschlossen** — die andere Seite
hat sich inzwischen **selbst verändert**, ohne dass eine Quelle beides in Beziehung setzt.

$$\Rightarrow \textsf{OPEN BY COMMISSION} \;+\; \textsf{DRIFT UNREMARKED}$$

## 4 · Stand der dreizehn Punkte nach diesem Durchgang

| | Punkt | Stand |
|---|---|---|
| 1 | Fixpunkt/Fundierung | ⭐ **GESCHLOSSEN** |
| 9 | Baseline-Divergenz A/B | ⭐ **GESCHLOSSEN** |
| 3 · 6 · 10 | `𝒪`-Enumeration · `CR-3` · `Authority: HPA` | ⭐ **GESCHÄRFT** |
| **7** | Auslassung in `Γ` | ⭐ **`GENUINE CORPUS GAP` nach Zensus bestätigt** |
| **5** | `Status ↔ Sat` | ⭐ **geschärft — Gegenseite selbst gedriftet** |
| **4** | `𝒪_K`-Abschluss | ⭐ **NARROWED** — 30 gemessene Kandidaten; *„membership is a **declaration**"* |
| 2 · 11 | Träger · Prioritätsgewichtung | **Entscheidungen** — nicht forschbar |
| 8 | `φ : R→P` | ⛱ **gesperrt** |
| 12 | `Sat_F4` | ⛱ `FIREWALL-LIMITED` |
| 13 | 12 `UNRECORDABLE` | **Provenance nicht wiederherstellbar** |

$$\boxed{\textbf{2 geschlossen · 5 geschärft/verengt · 3 Entscheidungen · 3 unauflösbar (gesperrt / firewall / Provenance)}}$$

⭐ **Kein Punkt bleibt „einfach offen".** Jeder trägt jetzt entweder ein Ergebnis, eine
Verengung, oder einen **benannten Grund seiner Unauflösbarkeit** — genau die Terminalbedingung
aus §18.

## 5 · Verbleibende zulässige Forschungsakte (nicht Teil der 13)

| | Akt | Aufwand |
|---|---|---|
| `R1` | `Det` gegen `Det_r` durchgängig disambiguieren | klein |
| `R2` | strenge Kantenmessung auf Fließtext/Tabellen ausweiten | groß |
| `R3` | `Γ`s Subvokabular (`Assertion`, `GovCtx`, `Cmd/Qry/Evt/Obs`, …) traversieren | mittel |
| `R5` | `M_t`/`R_t`-Drift und `K-a` datieren und melden | klein |
| ~~`R4`~~ · ~~`R6`~~ | zurückgezogen · ⭐ **erledigt** | — |
