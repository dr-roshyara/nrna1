# Phase C′ — Die vier verbleibenden Objekte: `𝒪_core` · `≡_sem` · `ℛ_req` · `P_c`

Phase E hat diese vier (mit `Γ`) als **Senken** des Abhängigkeitsgraphen gemessen. Hier ihre
eigene Reconciliation.

## 1 · `𝒪_core` — **zweimal geschlossen, einmal zurückgenommen, und die Rücknahme ist ungedeckt**

**2 Definitionsstellen im gesamten fremden Korpus** — das niedrigste Ergebnis aller 16
gemessenen Objekte, gleichauf mit `≡_sem`.

| | Akt |
|---|---|
| **Step 277** | `𝒪_core` — *"Classification: **CLOSED**"* |
| **Step 272A** (2026-08-30 22:42) | **retracts** Step 277s CLOSED-Klassifikation; etabliert `𝒪_core → Requirements(K) → Candidate(K) → Minimality(K) → Σ` |
| **Reviewer A** (08-31) | *"`𝒪_core` **cannot honestly be declared closed yet**"* · *"the current `𝒪_core` research is **premature as a canonicalization exercise**"* |
| **D285-7** | ⚠️ *"**`𝒪` never enumerated against the ratified 8 primitives** — new finding, and the **real reason `𝒪_core` cannot close**"* |

⭐⭐⭐ **Die Rücknahme trägt sich selbst nicht.** Step 272A führt `Authority: HPA` im
Frontmatter und enthält **null** Rulings im Text; `governance-notes.md` steht bei `GN-73`
mit **0** Einträgen zu `𝒪_core`, gegen einen Bestandsstandard von **132/132 `humanActRef`**.
Die Spur formuliert die Konsequenz selbst:

> *"is the `Authority: HPA` on Step 272A an actual authority act? If yes → record it (GN-74).
> **If no → Steps 272A–278 are `HYPOTHESIS`.**"*

⚠️ Damit ruht die **einzige** Rücknahme eines CLOSED-Anspruchs auf einem **ungeprüften
Autoritätsanspruch** — formgleich mit der Defektklasse *fabricated authority*, die ich für
v1.3 benannt hatte. **Ich stelle die Formgleichheit fest und behaupte keine Absicht.**

**Status:** `NOT FROZEN` · CLOSED-Anspruch **bestritten** · Rücknahme **`UNVERIFIED AUTHORITY`**

## 2 · `≡_sem` — definiert, falsch benannt, und über einer **unabgeschlossenen Menge**

| Lesart | Form | Quelle |
|---|---|---|
| **A** Kandidat | `≡_sem := (∀O ∈ 𝒪_K)` | `261.21` |
| **B** Tupelposition | `𝔎 = (K, =_str, ≡_sem, ≈_obs, SameId, ≡_H, ≡_P)` — **eine von sieben** | `261.25` |
| **C** kontextuell | *"`≡sem` is defined contextually"* | Ratifikations-Assessment |

Der Multiplizitätsregister des Korpus urteilt selbst:

> *"`≡_sem` unresolved"* → **`B` — defined, mis-named; **the corpus contains its own repair**"

Das ist exakt `CR-4` der Vorwärtsplanung (*„das billigste — der Korpus enthält seine eigene
Reparatur"*).

**Der Ratifikationsversuch `N-1′`** (Autorität **ARB**) scheitert nicht an Uneinigkeit,
sondern an etwas Strukturellem:

> **Evidence unavailable:** ⚠️ *"a closed `𝒪_K` — so the candidate's **extension** cannot be
> computed"* · **Why research cannot decide it:** *"the candidate is **defined over an
> unclosed set**, so research **cannot even determine what would be ratified**"*

⭐ Zugleich: **`G-67` ist ZURÜCKGEZOGEN** und durch `N-1′` ersetzt — *"non-critical,
downstream of `N-4`, and not evaluable yet"*.

**Status:** `DEFINED ELSEWHERE` + `DEPENDENCY-BLOCKED` über `𝒪_K` — **nicht** `UNDEFINED`.
Und: *"the closure experiment explicitly left **kernel minimality blocked** because `≡_sem`
is undefined"* — die Sperre wirkt weiter, obwohl die Prämisse *„undefiniert"* zu eng ist.

## 3 · `ℛ_req` — zwei Renderings, und ⭐ **die einzige positive Identität der ganzen Phase**

**Definition:** `ℛ_req = {d_1,…,d_k}`, eine Menge von **Distinktionen**, mit einer
Erhaltungsbedingung über einer Kodierung `E : S → 𝒦`:

$$\forall s_1,s_2 \in S:\quad s_1 \nsim_d s_2 \ \Longrightarrow\ E(s_1) \neq E(s_2)$$

**Abstammung (`G-05`):** gegenüber der 08-27-Menge `ℛ(P)` **null Zitate**.
⇒ **`DISTINCT by type`** (requirements vs. distinctions), **konzeptuelle Abstammung
`UNWITNESSED`**.

**⭐⭐ Zwei Renderings *innerhalb einer Spur*, an einem Tag:**

| | Form | Typ | Adäquatheit |
|---|---|---|---|
| `20260902-182008` RREQ-6 | `Loss_req = ℛ_req ∩ Collapsed(π)` | **Menge** | `= ∅` |
| `20260902-182016` L217 | `Σ wᵢ · 𝕀(Collapse)` | **gewichteter Skalar** | `== 0` |

Und `025d` **verbietet** einen Skalar. Der Konflikt ist **spurintern**, und die Spur hält
fest: *"**Not adjudicated; both branches preserved.**"* Die eigentliche offene Frage ist
*„may a priority weighting be stipulated?"*

### ⭐⭐⭐ Die einzige positive Identität

$$\underbrace{\forall s_1,s_2:\ F(s_1)=F(s_2) \Rightarrow I(s_1)=I(s_2)}_{Expressive(F,\mathcal I),\ \text{Spur 1, 09-01}} \quad\Longleftrightarrow\quad \underbrace{\forall s_1,s_2:\ I(s_1)\neq I(s_2) \Rightarrow F(s_1)\neq F(s_2)}_{\mathcal R_{req}\text{-Adäquatheit, Spur 2, 09-02}}$$

**Kontrapositionen** — dieselbe Bedingung unter `ℐ ↔ ℛ_req` und `F ↔ E`. Zwei Spuren, **32
Stunden**, **keine zitiert die andere**.

⚠️ **Und die Quelle selbst verweigert die Aufwertung:**

> *"⚠️ **Convergence is evidence, never proof.** … **Nothing here is upgraded on convergence
> alone.**"* — sie stempelt den Befund `[INF]`.

$$\boxed{\textbf{IDENTIFICATION WITNESSED — UPGRADE FORBIDDEN BY THE SOURCE.}}$$

⭐ Das ist der **einzige** Merge-Kandidat der gesamten RECONCILE-Phase, und er wird **nicht
von mir**, sondern **von seinem eigenen Autor** gesperrt. Ich übernehme diese Sperre.

## 4 · `P_c` — das einzige Objekt mit **einem** stabilen Sinn

`P_c` = **Candidate Proposition**, eine Station der Beobachtungs-Pipeline (2026-08-27 12:56):

$$X \rightarrow Artifact \rightarrow O_s \rightarrow I \rightarrow P_c \rightarrow \Sigma \rightarrow A \rightarrow K_t$$

**5 Definitionsstellen, eine Lesart, keine konkurrierende Signatur, kein Homonym.**

⭐ Unter allen 16 gemessenen Objekten ist `P_c` **das einzige ohne Vielfalt**. Es ist auch
das einzige, das nirgends als offen, blockiert oder strittig geführt wird — und
bezeichnenderweise **das einzige, das nie in eine Kanonisierungsdebatte geriet**.

## 5 · Ergebnis

| Objekt | Status | Merge? |
|---|---|---|
| `𝒪_core` | `NOT FROZEN`; CLOSED bestritten; Rücknahme `UNVERIFIED AUTHORITY` | ⛔ nein |
| `≡_sem` | `DEFINED ELSEWHERE` + `DEPENDENCY-BLOCKED` über `𝒪_K`; `G-67` zurückgezogen | ⛔ nein |
| `ℛ_req` | zwei unadjudizierte Renderings; **eine `[INF]`-Identität mit `Expressive(F,ℐ)`** | ⛔ **von der Quelle gesperrt** |
| `P_c` | **STABIL, EINDEUTIG** | — nichts zu verschmelzen |

$$\boxed{\textbf{Weiterhin keine Merges — jetzt auch dort, wo eine Identität nachgewiesen ist.}}$$
