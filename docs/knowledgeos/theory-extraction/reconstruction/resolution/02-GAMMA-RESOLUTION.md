# Phase 2 — `Γ` · Targeted Resolution

## 1 · ⛔ WIDERRUF 4 — `Γ` ist **keine Senke**. Phase E hat ein Artefakt gemessen.

§6 der Kommission stellte genau die richtige Frage: *„Sind die Definitionskanten nur durch
Erkennungs-/Parsinggrenzen verborgen?"*

**Ja.** Extrahiert man die Bezeichner **aus Γs eigenen Definiens** (fremde Quellen, firewall-frei):

| Bezeichner im Definiens | n | erstmals |
|---|---|---|
| `Committed` · `Uncommitted` · `Contested` | 15 | 2026-08-30 |
| `Assertion` · `GovCtx` / `GovernanceContext` | 8 | 2026-08-30 11:30 |
| `Cmd` · `Qry` · `Evt` · `Obs` | 8 | 2026-08-28 11:36 |
| `Commands_A` · `Queries_A` · `Events_A` · `Observations_A` | 8 | 2026-08-28 11:36 |
| `AuthorityValid` · `EvidenceComplete` · `RuleSatisfied` | 6 | 2026-08-29 10:59 |
| `C_Q` · `E_t` · `𝒟_t` · `factivity policy` | 11 | 2026-09-02 |
| `Domain` · `Participant` · `Time` · `Purpose` · `Constraints` · `Vocabulary` · `Authority` | 4+ | 2026-09-06 |

$$\boxed{\Gamma \text{ hat eine reiche ausgehende Abhängigkeitsstruktur — in ein Vokabular, das meine 16-Knoten-Menge nicht enthielt.}}$$

⚠️ **Neunzehnter Messfehler, und er trägt eine Schlagzeile.** Phase E schloss: *„Der gesamte
Aufbau ruht auf den fünf Objekten, die er am wenigsten definiert hat."* Dieser Satz war ein
**Artefakt der Knotenwahl**, nicht ein Befund über den Korpus. Ich hatte die Kanten korrekt
als `TYPE-CONSTRAINED` markiert — aber die **Scope-Beschränkung der Knotenmenge** nicht als
Fehlerquelle benannt. Eine Nullmessung misst ein Muster; **eine Senke misst eine Knotenmenge.**

## 2 · ⭐⭐⭐ Der Korpus **benutzt** eine legitime Fixpunktdefinition — und sie heißt `Γ`

Aus der Levesque/Lakemeyer-Extraktion (2026-09-02 18:42), autoepistemische Logik:

> *A set `Γ` is a **stable expansion** of `A` iff*
> $$\Gamma = \{\gamma \mid \gamma \text{ basic and } A \cup \{K\beta \mid \beta \in \Gamma\} \cup \{\neg K\beta \mid \beta \notin \Gamma\} \models_{FOL} \gamma\}$$

**`Γ` steht auf beiden Seiten.** Das ist Moores stabile Expansion — ein **Lehrbuchobjekt**,
mathematisch einwandfrei, weil es als **Fixpunkt** verstanden wird.

⭐ **Konsequenz für §7:** In Phase E hatte ich notiert, ein Fundierungs-/Fixpunktargument sei
`NOT YET LOCATED`. Das war zu eng. Der Korpus **kennt und verwendet** Fixpunktdefinitionen
korrekt. Selbstreferenz ist in seiner eigenen Praxis **kein Defekt**. Damit ist die
Vorentscheidung, die Phase E nahelegte, aufgehoben — Phase 11 muss den 11er-Zyklus
**ergebnisoffen** prüfen.

⚠️ Zugleich: dies ist ein Fixpunkt **für stabile Expansionen**, **nicht** für den 11er-Zyklus.
Es beweist die Zulässigkeit der *Form*, nicht die Fundiertheit *jenes* Zyklus.

## 3 · Die vierzehn `Γ`-Familien nach §12

| | Bounded Context | semantische Rolle | Typ | Domäne → Codomäne | Quelle |
|---|---|---|---|---|---|
| **Γ_set** | Modellierung | Guard-Menge | Menge | `{g₁…g_n}` | 08-24 12:28 |
| **Γ_G** | Evidenzkontrakt | *rules determining sufficiency* | Komponente von `EC_G=(R_G,Γ_G)` | — | 08-27 18:31 |
| **Γ_CC** | Integration | **Kontextkontrakt** | 4-Tupel | `(Commands_A,Queries_A,Events_A,Observations_A)` | 08-28 11:36 |
| **Γ_inv** | Verifikation | Invariantenmenge | Menge | `{AuthorityValid,EvidenceComplete,RuleSatisfied}` | 08-29 10:59 |
| **Γ_gov** | **Governance** | Statusfunktion | **Funktion** | `Assertion × GovCtx → {Uncommitted,Committed,Rejected,Contested,Superseded,Retired}` | 08-30 11:30 |
| **Γ_val** | Governance | ⚠️ **ein Wert** | Konstante | `Γ = Committed` | 08-30 19:40 |
| **Γ_SRT** | Gītā-Linse | Zustand | 3-wertig | `Γ_t ∈ {S,R,T}` | 09-01 01:57 |
| **Γ_A2** | Maßtheorie | Abbildung | Funktion | `𝒦 → 𝒜` | 09-02 00:27 |
| **Γ_fact** | Attribution | faktive Attribution | Funktion | `(E,Q,C,EC) → K` | 09-02 09:12 |
| **Γ_attr** | Attribution | Attribution mit Policy | Funktion, 3-stellig | `(𝒟_t,E_t,\text{factivity policy}) → K_t^{attrib}` | 09-02 13:29 |
| **Γ_bg** | Erkenntnistheorie | Hintergrund | prosaisch | `BackgroundContext` | 09-02 18:31 |
| ⭐ **Γ_logic** | **Logik (Literatur)** | **Prämissenmenge / stabile Expansion** | **Fixpunkt-Menge** | `{γ \| … ⊨_FOL γ}` | **09-02 18:42** |
| **Γ_tup** | Auswertung | Auswertungskontext | **offenes** Tupel | `(𝒮,𝒞,𝒯,𝒫,…)` | 09-02 18:38 |
| ⭐ **Γ_7** | Rewrite | Kontext | **geschlossenes 7-Tupel** | `⟨Domain,Participant,Time,Purpose,Constraints,Vocabulary,Authority⟩` | **09-06 00:23** |

⚠️ **`Γ_7` ist gehedgt:** part-01 schreibt *„a context **may contain**"*, nicht *„is"*.
Auch das geschlossene Tupel ist damit **permissiv, nicht definitorisch**.

## 4 · ⭐ Die DDD-Lesart — deshalb dürfen sie **nicht** verschmelzen

Die vierzehn Familien liegen in **mindestens acht verschiedenen Bounded Contexts**:
Governance · Integration · Evidenzkontrakt · Verifikation · Attribution · Maßtheorie ·
Logik (importiert) · Auswertung.

$$\boxed{\text{Das ist keine mathematische Inkonsistenz, sondern eine } \textbf{Ubiquitous-Language-Verletzung}.}$$

Ein Glyph trägt acht Verantwortlichkeiten über acht Kontextgrenzen. Die Reparatur ist
**Namensgebung**, nicht Vereinheitlichung — und sie ist ein **Governance-Registrierungsakt**
(die Vorwärtsplanung führt ihn als `H1`), keine mathematische Ableitung.

## 5 · Identitätsrelationen (§12)

| Paarung | Relation | Evidenz |
|---|---|---|
| `Γ_gov ↔ Γ_val` | ⚠️ **TYPE ERROR** — Funktion vs. eigener Wert | direkt vergleichbar |
| `Γ_CC ↔ Γ_7` | **DISTINCT** — 4-Tupel (Nachrichtenarten) vs. 7-Tupel (Situationsmerkmale) | disjunkte Komponenten |
| `Γ_tup ↔ Γ_7` | ⭐ **REFINEMENT-KANDIDAT** — `(𝒮,𝒞,𝒯,𝒫,…)` könnte durch `⟨…,Authority⟩` geschlossen werden; **beide hedgen**, keine Quelle stellt die Beziehung her | `IDENTITY UNWITNESSED` |
| `Γ_fact ↔ Γ_attr` | **REFINEMENT-KANDIDAT** — `Γ_attr` fügt *factivity policy* hinzu; erschienen 4 h auseinander, **ohne Zitat** | `IDENTITY UNWITNESSED` |
| `Γ_logic ↔ alle` | ⭐ **HOMONYM (Literaturimport)** — Standardkonvention `Γ ⊢ φ` | **DISTINCT OBJECTS PROVEN** |
| `Γ_SRT ↔ alle` | **HOMONYM** — Guṇa-Trias, andere Linse | `DISTINCT` |
| `Γ ↔ ρ_A` | ⛔ `DEPENDENCY-BLOCKED` über undefiniertes `φ : R→P` | unverändert |

## 6 · Closure-Status nach §14

| Kriterium | `Γ` |
|---|---|
| 1 Objektidentität | ⛔ **14 Familien, 8 Kontexte** |
| 2 Domäne | ⚠️ je Familie definiert |
| 3 Codomäne | ⚠️ je Familie definiert |
| 4 Stelligkeit | ⚠️ 0 (Konstante) · 1 · 2 · 3 · 4 · 7 |
| 5–6 Argument-/Resultattypen | ⚠️ je Familie |
| 7 Abhängigkeiten rekursiv geschlossen | **NOT YET ASSESSED** — das Subvokabular (`Assertion`, `GovCtx`, …) ist noch nicht traversiert |
| 8 Invarianten explizit | ⛔ keine gefunden |
| 9 keine unaufgelöste Alternative | ⛔ zwei Refinement-Kandidaten offen |
| 10 keine unaufgelöste Zirkularität | ✅ **für `Γ_logic`** (Fixpunkt) · sonst nicht anwendbar |

$$\Gamma:\quad \textsf{DEFINED (vielfach)} \;\wedge\; \textsf{NOT TYPE-CLOSED} \;\wedge\; \textsf{DEPENDENCY: NOT YET ASSESSED}$$

## 7 · Antwort auf §6

> **Ist `Γ` ein fundamentales primitives Objekt?** — **Nein.** Es hat reiche ausgehende Kanten.
> **Sind die Kanten durch Parsinggrenzen verborgen?** — ⭐ **Ja, das war der Fall.**
> **Ist `Γ` historisch spät eingeführt?** — **Nein.** `Γ = {g₁…g_n}` steht am **2026-08-24**,
> zwei Tage vor `δ`s Geburt und drei vor `Sat`.

## 8 · Offen nach Phase 2

* Das **Subvokabular** (`Assertion`, `GovCtx`, `Cmd/Qry/Evt/Obs`, `AuthorityValid`, …) ist
  noch nicht traversiert — es entscheidet Kriterium 7.
* Zwei **Refinement-Kandidaten** (`Γ_tup→Γ_7`, `Γ_fact→Γ_attr`) sind unbezeugt.
* Die Auslassung in `(𝒮,𝒞,𝒯,𝒫,…)` bleibt offen.
* `φ : R→P` bleibt ⛔ gesperrt.
