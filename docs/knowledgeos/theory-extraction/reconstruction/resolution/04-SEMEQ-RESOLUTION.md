# Phase 4 — `≡_sem` · Targeted Resolution

## A · Problem präzisiert

Nicht *„`≡_sem` ist unaufgelöst"*, sondern: **Ist der Slot `≡_sem` leer, umstritten, oder
gefüllt-aber-falsch-benannt?**

## B/C · Primärquellen, Geburt → Gegenwart

| | Quelle | Aussage |
|---|---|---|
| **08-30 19:24** | `step_261_equality-and-identity` §261.25 | `𝔎 = (K, =_str, **≡_sem**, ≈_obs, SameId, ≡_H, ≡_P)` — **sieben getrennte Relationsslots** |
| 08-30 | §261.21 | Kandidat `≡_sem := (∀O ∈ 𝒪_K)`, mit eigenem Vorbehalt |
| **08-30 22:xx** | §261.23 | *„But do not overclaim"* — **sechs** Gründe, warum `≡_K` nicht die finale Gleichheit ist |
| **09-01** | `REFINED-STEP-290`, `N-1A` | ⭐ **DISTINCT ist ETABLIERT** |
| **09-02** | `CLOSURE-4` | `K₁ ≡_sem^{Q,Γ,𝒪} K₂` ⟺ Determinationen stimmen überein — **ausführbar**, mit Tester |
| **09-02** | Review-Korrektur | ⭐ *„I would **not call it general semantic equivalence**… closer to `K₁ ≈_{Q,Γ,𝒪} K₂` = **contextual observational equivalence**"* |

## D · ⭐⭐⭐ Die Auflösung: **ein leerer Slot, ein falsch benannter Nachbar**

`CLOSURE-4`s Definition quantifiziert über **Determinationen bei ausgewählten Queries** —
das ist per Konstruktion **beobachtungsbasiert**. Die Review-Korrektur benennt es:

> *„because two representations can produce the same answers for the **selected queries**
> while **differing in other observations**"*

$$\text{CLOSURE-4} \;=\; \approx_{Q,\Gamma,\mathcal O} \;\neq\; \equiv_{sem}$$

⇒ Die drei „Lesarten" aus Phase C′ sind **keine drei konkurrierenden Definitionen**:

| | war | ist |
|---|---|---|
| A Kandidat `(∀O ∈ 𝒪_K)` | „Lesart 1" | **selbst-etikettierter Kandidat**, nie ratifiziert |
| B Tupelposition | „Lesart 2" | ⭐ **der Slot selbst — und er ist LEER** |
| C `CLOSURE-4` | „Lesart 3" | ⭐ **ein anderes Objekt** (`≈_obs`), falsch benannt |

`CR-4`s Formulierung *„the corpus contains its own repair"* ist damit **bestätigt und
zugleich präzisiert**: die Reparatur ist eine **Umbenennung**, und sie füllt **`≈_obs`**,
nicht `≡_sem`.

## E · Gegenbelege (§5 Schritt E) — zwei unabhängige Reviews

| Quelle | Aussage |
|---|---|
| `…182009_review-as-senior-mathematician` §8 | *„CLOSURE-4 — the semantic equivalence claim is **too strong**"* |
| `…182010_review-consolidation-as-senior-statistician` §7 | *„CLOSURE-4 semantic equivalence **is not closed**… the claim that CLOSURE-4 is **fully ratified is not supported by the evidence**"* |

⇒ Der Umbenennungsbefund ist **nicht meine Konstruktion**, sondern von drei Quellen getragen.

## F · Die Abhängigkeitsschließung — vom Korpus selbst benannt

§261.23 nennt **sechs** Bedingungen, unter denen `≡_K` finale Gleichheit wäre:

| | Bedingung | Status |
|---|---|---|
| 1 | Beobachtungsmenge `𝒪_K` geschlossen | ⛔ offen |
| 2 | **Operationsregister geschlossen** | ⛔ offen — ⭐ **das ist Phase 3** |
| 3 | Provenienzplatzierung geklärt | ⛔ |
| 4 | Assertionssemantik geklärt | ⛔ |
| 5 | Temporalsemantik geklärt | ⛔ |
| 6 | Identitätssemantik geklärt | ⛔ |

**0 von 6 erfüllt** — der Stand ist in `REFINED-STEP-290` ausdrücklich bestätigt.

⚠️ **Disambiguierung, die ich fast falsch gemacht hätte:** `𝒪_K` ist eine **Beobachtungs**menge
(*„required definition ABSENT → a closed **observation set** `O_K`"*; `≈ := observational
relation over O_K`), **nicht** das Operationsuniversum aus Phase 3. Die 19 Operationen von
272A schließen `𝒪_K` **nicht**. Bedingungen 1 und 2 sind **zwei verschiedene** Blocker.

## G · Definierbar vs. auswertbar — die entscheidende Trennung

Reviewer B, `20260831-233234`:

> *„The candidate definition is **syntactically/formally expressible without a closed `O_K`**,
> but its **evaluation and architectural validation are blocked** until `O_K` is closed."*

Und die geboxte Feststellung aus `REFINED-STEP-290`:

> **The corpus HAS distinguished `≡` and `≈`. It has NOT filled `≡`. Choosing whether to
> collapse them requires authority — but not yet, because the candidate is defined over an
> `𝒪_K` that is not closed.**

## H · ⭐ Und der Korpus formuliert die DDD-Konsequenz selbst

§261.24, **2026-08-30**:

> *„We should **not** create one generic concept called `Equality` and use it everywhere.
> Instead, **bounded contexts may legitimately require different relations**."*

⭐⭐⭐ Das ist **dieselbe Einsicht, die ich in Phase 2 für `Γ` formuliert habe** — nur ist sie
hier **Primärquelle** und sieben Tage älter. Relationenvielfalt über Kontextgrenzen ist im
Korpus **normativ vorgesehen**, nicht bloß ein Mangel an Identitätsevidenz.

⇒ Meine „keine Merges"-Urteile der Phasen A–D erhalten damit eine **positive Grundlage**
statt einer bloß negativen: nicht *„Identität unbezeugt"*, sondern *„Trennung vorgeschrieben"*.

## I · Closure-Status nach §14

| | Kriterium | `≡_sem` |
|---|---|---|
| 1 | Objektidentität | ✅ **ein Slot**, von `≈_obs` nachweislich verschieden (`N-1A`) |
| 2–6 | Domäne/Codomäne/Stelligkeit/Typen | ⛔ **leer** — der Slot ist unbesetzt |
| 7 | Abhängigkeiten geschlossen | ⛔ **0 von 6** (§261.23) |
| 8 | Invarianten | ⚠️ nur die Nicht-Austauschbarkeit mit `≈` |
| 9 | keine unaufgelöste Alternative | ✅ ⭐ **aufgelöst** — die vermeintliche Alternative ist `≈_{Q,Γ,𝒪}` |
| 10 | keine Zirkularität | ✅ |

$$\equiv_{sem}:\quad \textsf{SLOT DECLARED} \wedge \textsf{EMPTY} \wedge \textsf{NOT TYPE-CLOSED} \wedge \textsf{DEPENDENCY-BLOCKED (0/6)} \wedge \textsf{DISTINCT FROM } \approx_{obs} \textbf{ (PROVEN)}$$

## J · Closure decision

$$\boxed{\textbf{REMAINS OPEN} — \text{aber die } \textit{Vielfalt} \text{ ist aufgelöst.}}$$

**Fortschritt gegenüber Phase C′:** aus *„drei konkurrierende Lesarten"* wird
**ein deklarierter, leerer Slot**, ein **nachweislich verschiedener Nachbar** und eine
**bestätigte Umbenennung**. Der Blocker ist nicht Uneinigkeit, sondern **sechs benannte
Vorbedingungen, von denen keine erfüllt ist**.

⛔ **Nicht von mir zu füllen:** `N-1′` weist die Autorität ausdrücklich `ARB` zu, und der
Kandidat ist bis zum Abschluss von `𝒪_K` nicht einmal **auswertbar**.
