# Phase 3 — `𝒪_core` · Targeted Resolution

## A · Problem präzisiert

Nicht *„`𝒪_core` ist offen"*, sondern **drei trennbare Ansprüche**:

| | Anspruch | |
|---|---|---|
| **A1** | Ist das Operationsinventar **aufgezählt**? | |
| **A2** | Ist es **minimal** bewiesen? | |
| **A3** | Ist es **gegen die ratifizierten 8 Primitive** enumeriert? | |

Meine bisherige Formulierung (*„`𝒪` nie enumeriert"*) hat A1 mit A3 vermengt.

## B/C · Primärquelle und Geburt

**`Step 272A — Derivation of the Core Operation Universe`**, `20260830-224242`, 1430 Zeilen.
`Status: DERIVED — FOUNDATIONAL BASIS` · `Authority: HPA` · *„Prerequisite to `K` minimality"*.

Der Anlass steht im ersten Absatz und ist ein **Typbefund**:

> *"the previous treatment of `O_core` was **too broad**: `Assert` is a **state transition**,
> `Query` is an **observation**, `Authorize` is a **governance predicate**, and `Serialize`
> is a **representation function**. **They cannot simply be treated as one homogeneous
> algebra.**"*

## D · **A1 ist ERFÜLLT** — das Inventar existiert, getypt

$$O_{sem} \;=\; O_S \cup O_E \cup O_O \cup O_H \cup O_G$$

| Klasse | Operationen | n |
|---|---|---|
| `O_S` semantische Zustandsoperationen | `Assert · Retract · Supersede · Infer · Merge` | 5 |
| `O_E` Evidenz/epistemisch | `Support · Refute · **Qualify** · Assess · DetectContradiction · Resolve` | 6 |
| `O_O` Beobachtung/Äquivalenz | `Query · Compare · Identity · Equal` | 4 |
| `O_H` historisch | `Replay · Trace` | 2 |
| `O_G` Governance | `Authorize · Validate` | 2 |
| | **gesamt** | **19** |

⭐ **`Qualify` (Phase 5) ist hier verortet** — als eine von sechs Evidenzoperationen.
⭐ Ausgeschlossen wurden `Serialize`, `Save`, `Load` — *„an important correction to the
earlier formulation"*.

## E · **A2 ist AUSDRÜCKLICH VERTAGT — nicht fehlend**

§272A.19, wörtlich:

> *"this step does **not yet freeze** every operation as irreducibly primitive. Instead
> `O_sem^{candidate}` is established first. **Primitive minimality is a subsequent proof
> obligation.**"*

Mit konkreten Reduktionsverdachten: `Merge` evtl. über Assertion + Relationsaufbau,
`Resolve` evtl. `Assess + Decide + Update`.

⭐ Das ist **kein Defekt, sondern eine deklarierte Beweisschuld** — mit sechs
Falsifikationsbedingungen (`F272A-1` fehlende Pflichtoperation · `-2` redundante ·
`-3` **Kategorienfehler** · `-4` verborgene Abhängigkeit · `-5` Governance-Kontamination ·
`-6` historische Kontamination).

## F · Die Rücknahme — in den Worten der Quelle

§272A, Zeile 1428:

> *"the earlier claim that `O_core` was already **'closed' was too strong**: the corpus had
> **reconstructed the operation inventory**, but had **not yet completed the minimality and
> category-separation proof**."*

⇒ Die Rücknahme des `CLOSED`-Anspruchs (Step 277) ist **inhaltlich begründet und im Text
ausgeführt** — nicht bloß durch einen Autoritätsstempel behauptet. Das schwächt meinen
Phase-C′-Befund `UNVERIFIED AUTHORITY` erheblich: die *Begründung* trägt sich selbst; nur
die *Form* des Aktes bleibt offen.

⚠️ **Korrektur an mir selbst:** ich hatte geschrieben, die Rücknahme *„ruht auf einem
ungeprüften Autoritätsanspruch"*. Richtig ist: sie ruht auf einem **1430-zeiligen
Ableitungsdokument**; strittig ist allein, ob `Authority: HPA` sie zu einem *Akt* macht.
**Argument und Akt sind zwei Dimensionen** — ich hatte sie zusammengezogen.

## G · **A3 bleibt offen — und ist eine Brücke, die ich nicht bauen darf**

`review-overall-verdict` (08-31) zeichnet die Lage:

```
       RATIFIED CANON          RESEARCH / VERIFICATION
          K_t                       K = (𝒜,ℛ)
      8 primitives             Σ, Q_t, ℐ, 𝒪, δ, …
          └─────────── X ─────────┘
                    MISSING CANONICAL BRIDGE
```

⭐ **`𝒪` gehört nicht zu den acht Primitiven — es steht auf der anderen Seite.** Meine
frühere Lesart *„nie gegen die 8 enumeriert"* beschreibt also **keine unvollständige
Aufzählung, sondern eine fehlende Spurenbrücke**.

⛔ Genau diese Brücke zu schlagen ist mir untersagt. ⇒ `A3` = **`OPEN BY COMMISSION`**,
zugleich `GENUINE CORPUS GAP` (niemand hat sie gebaut).

## H · Resolution-Corridor ab 09-02 — kein Fortschritt

`20260902-182007_gap-closure-strategy` (`[ADVISORY]`, `Authority: HPA Supervisory`),
Statustabelle:

> **`O_core` — 🔴 OPEN — *"Canonical operations"***

⇒ Der Corridor bestätigt die Offenheit, löst sie nicht.

## I · Closure-Status nach §14

| | Kriterium | `𝒪_core` |
|---|---|---|
| 1 | Objektidentität | ✅ ein Objekt: `O_sem^{candidate}` |
| 2 | Domäne | ✅ Operationen über Wissenszuständen |
| 3 | Codomäne | ⚠️ **fünf verschiedene**, je Klasse |
| 4 | Stelligkeit | — (Menge, keine Funktion) |
| 5–6 | Argument-/Resultattypen | ⚠️ je Klasse |
| 7 | Abhängigkeiten | ✅ §272A.20 gibt eine Abhängigkeitsstruktur an |
| 8 | Invarianten explizit | ⚠️ statt Invarianten: **6 Falsifikationsbedingungen** |
| 9 | keine unaufgelöste Alternative | ⛔ `Merge`, `Resolve` möglicherweise abgeleitet |
| 10 | keine Zirkularität | ✅ |

$$\mathcal O_{core}:\quad \textsf{ENUMERATED} \wedge \textsf{TYPED (5 Klassen)} \wedge \textsf{NOT MINIMALITY-PROVEN} \wedge \textsf{NOT CATEGORY-SEPARATION-PROVEN} \wedge \textsf{NOT BRIDGED}$$

## J · Closure decision

$$\boxed{\textbf{REMAINS OPEN} — \text{aber aus drei benannten, getrennten Gründen.}}$$

| | war | ist |
|---|---|---|
| A1 Aufzählung | *„nie enumeriert"* | ⭐ **ERFÜLLT** — 19 Operationen, 5 Klassen, 08-30 |
| A2 Minimalität | „fehlt" | ⭐ **ausdrücklich vertagte Beweisschuld**, mit Falsifikationsdesign |
| A3 Brücke zum Kanon | vermengt mit A1 | ⛔ **`OPEN BY COMMISSION` + `GENUINE CORPUS GAP`** |

⚠️ **Und: `𝒪_core` ist keine Senke.** Sein Definiens nennt `Assert`, `Query`, `Authorize`,
`Merge`, `Resolve`, `Assess` … — dieselbe Scope-Beschränkung wie bei `Γ`. Der Phase-E-Befund
ist auch hier hinfällig.
