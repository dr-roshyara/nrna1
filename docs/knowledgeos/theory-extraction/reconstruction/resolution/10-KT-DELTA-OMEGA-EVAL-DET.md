# Phase 10 — `K_t · Δ · Ω · Eval · Det` · Targeted Resolution

⚠️ **Zähleinheit:** nach der `C10`-Lehre wird nach **semantischer Rolle / Codomäne**
geclustert, nicht nach Schreibweise. Meine Rohzählung (Δ 307, Ω 235, Eval 80, Det 45
„Formen") ist eine **Schreibweisen**zählung und **nicht** als Familienzahl zu lesen.

## 1 · `K_t` — acht Tupelformen, vom Korpus selbst registriert

`A3W-state-transition-register` §1 (2026-08-29) führt sie bereits auf:

| | Form | Stell. | Status **in der eigenen Quelle** |
|---|---|---|---|
| K-a | `(𝒜,ℛ,ℰ,ℋ,𝒵,ℒ,𝒯,𝒢,𝒞,ℳ)` | **10** | ⚠️ *„**rejected by Q13's own review**"* — nie revidiert, aber **downstream als autoritativ zitiert** |
| K-b | `(𝒜,ℛ,ℰ,ℋ,𝒵,ℒ)` | 6 | operative Form des „frozen" Transitionsmodells |
| K-c | `(𝒫,𝒜,ℛ,ℰ,Σ,𝒞,𝒯,Π)` | 8 | Gegenvorschlag des Reviews |
| K-d | `(𝒜,ℛ,ℰ,𝒞,𝒯,Π)` | 6 | Gegenvorschlag |
| K-e | `(A,R,C,τ,Π)` | 5 | *„revised mathematical backbone"* — **unreconciled mit K-d im selben Review** |
| K-f | `(E,A,M,C,F,V,R)` | 7 | *„one of the most important formalizations so far"* — **zitiert die Q-Reihe nicht** |
| K-g | `𝒦=(I,S,O,E,P,A,M,U,R,D,X,L)` | **12** | *„the canonical architecture"* (Begriffsebene) |
| K-h | `𝒦=(E,S,T,O,P,R,Π,A)` | 8 | ⭐ **das Achtertupel** — `O` = **Beobachtungen** |

⭐ **Der schärfste Defekt ist protokolliert, nicht von mir gefunden:** K-a wurde vom
**eigenen Review verworfen**, nie revidiert — und **downstream (Q20 §3.1) trotzdem als
autoritativ zitiert**.

## 2 · `Δ` — von der Subtraktion zur Requirement-Menge

| Datum | Form | Rolle |
|---|---|---|
| 08-25 23:31 | `Δ_t = K_t^* − K̂_t` | ⚠️ **Subtraktion von Zuständen** |
| 08-25 23:45 | `Δ_t(p) = V_t^*(p) − V̂_t(p)` | Subtraktion von **Werten** |
| 08-26 10:23 | `Δ_t = Compare(Ô_t, Î_t)` | **Vergleichsfunktion** |
| 08-26 16:10 | `Δ_t(d) = Gap(X_t(d), I_t(d))` | **je Dimension** |
| 08-26 18:02 | `Δ_t = (Δ^K_t, Δ^U_t, Δ^D_t)` | **3-Tupel** |
| 09-02 | `Δ_t = Gap(K_t,EC_t) = {r ∈ Req(EC_t) : ¬Sat(K_t,r)}` | ⭐ **Requirement-Menge** (v1.0 `DEF-21`) |

⭐ Die **Subtraktionslesart wird später verworfen** (v1.0 §29) — dasselbe Verdikt, das
Phase A für `Zero_E` (Distanz) fand. **Ein Verwurf, zwei Objekte.**

## 3 · ⭐⭐ `Ω` — ein Homonym **in derselben Minute**

`20260824-122855`, **beide um 12:18**:

$$\Omega = \text{outcome space} \qquad\text{und}\qquad \Omega = \text{possible system states}$$

Das ist die maßtheoretische **Ergebnismenge** gegen den **Systemzustandsraum** — zwei
Standardbedeutungen, in **einem Dokument, zur selben Zeit**, unkommentiert. Später:
`Ω = Infinite Knowledge Space` (08-25), und die indizierte Familie `Ω_D` (Domäne/Semantik),
`Ω_E` (epistemischer Zustandsraum).

⚠️ Für jede spätere Wahrscheinlichkeitsaussage über `(Ω_K, 𝒜_K, P_K)` ist entscheidend,
**welches `Ω`** gemeint ist. Der Korpus trennt es nicht.

## 4 · `Eval` — und die Verschiebung der Stelligkeit

| Datum | Form | Stell. |
|---|---|---|
| 08-30 23:04 | `Eval(π,K,E,C,A) → V_π` | **5** |
| 09-02 10:13 | `Eval_c : (K_t,r,Γ_t) → EVal_c` | **3**, klassenindiziert |

⭐ `Eval` ist nach Phase 6 das **primäre Objekt**, `Sat_c` seine verlustbehaftete Projektion.

## 5 · `Det` — Konsum wechselt von Rohevidenz zu `EVal`

| Datum | Form | konsumiert |
|---|---|---|
| 09-02 08:54 | `Det(E_t,Q_t,C_t,S_t) = 𝒜_t` | **Rohevidenz**, 4-stellig |
| 09-02 10:53 | `Det(E,Q,Γ) → A` | Rohevidenz, 3-stellig |
| 09-02 16:03 | `Det(Q,E_t) = {H_1}` unique / `{H_1,H_2}` underdetermined / … | **fallweise**, Hypothesenmengen |
| 09-02 19:02 | `Det(EVal, Γ_t) → Determination` | ⭐ **`Eval`s Ausgabe**, 2-stellig |

$$\boxed{\text{Innerhalb von 10 Stunden hört } Det \text{ auf, Evidenz zu lesen, und liest stattdessen } EVal.}$$

⭐ Das ist ein **bezeugter Refinement-Schritt** — `Eval` schiebt sich zwischen Evidenz und
Determination. Genau die Sorte Übergang, die ich in den Phasen A–D nicht gesucht hatte.

## 6 · ⭐⭐⭐ Die Kette, die der Korpus selbst zieht

`20260902-101343_direct-answers-to-the-satc-closure-questions` (10:13):

$$Eval \rightarrow Sat \rightarrow \Delta^{sem} \rightarrow Zero$$

$$\boxed{\text{evaluation semantics} \rightarrow \text{closure semantics} \rightarrow \text{kernel}}$$

— eingeführt mit *„And **only after that** should we return to"*, also als **Reihenfolge**,
nicht als bloße Aufzählung.

## 7 · ⭐⭐⭐ Und der Mechanismus, der alles verbindet

`20260902-135228_agreement-with-structure-first-and-invariant-custody`:

> *„it explains **several apparently unrelated failures with one mathematical mechanism**:"*
> `Eval → Sat` · `Boundary → U` · `Boundary → Gap` · `Argument Field → Balanced` ·
> `Evidence → K` · potenziell `K → reduced kernel`
>
> *„The important point isn't merely that information is lost. It is that **the loss creates
> equivalence classes of states that the reduced representation can no longer
> distinguish**."*

⭐⭐⭐ **Jeder dieser Pfeile ist eine verlustbehaftete Projektion — und `ℛ_req` (Phase 9) ist
genau das Kriterium, das sagt, wann ein solcher Verlust zulässig ist:**

$$\text{zulässig} \iff \mathcal R_{req}(Q,\Gamma) \subseteq Dist(R)$$

Damit hängen Phase 6 (`Sat_c := value ∘ Eval_c`, *„loss concentrated exactly at `U`"*),
Phase 9 (`ℛ_req`-Adäquatheit) und dieser Befund an **einem** Mechanismus. Das HPA-Ruling
vom selben Tag nennt es *„a principle that unifies `Sat`, `Zero`, `Gap`, `Balanced`, `K`, and
**every other failure we have encountered**"*.

## 8 · Closure-Status nach §14

| Objekt | Identität | Typ | Abhängigkeit | Bemerkung |
|---|---|---|---|---|
| `K_t` | ⛔ **8 Tupel**, 5–12-stellig | ⛔ | ⛔ | K-a verworfen **und** weiterzitiert |
| `Δ` | ⚠️ Subtraktion **verworfen**, Requirement-Menge lebt | ✅ für `DEF-21` | ⛔ hängt an `Sat`, `Req`, `EC` | |
| `Ω` | ⛔ **Homonym in derselben Minute** | ⛔ | — | blockiert jede `(Ω,𝒜,P)`-Aussage |
| `Eval` | ⚠️ 5-stellig → 3-stellig | ✅ für `Eval_c` | ⛔ hängt an `Γ` | ⭐ **primäres Objekt** |
| `Det` | ⭐ **Refinement bezeugt** | ✅ für `Det(EVal,Γ)` | ⛔ hängt an `Eval`, `Γ` | |

## 9 · Closure decision

$$\boxed{\textbf{TEILWEISE GESCHLOSSEN — mit einer belegten Ordnung, die Phase E bestritten hatte.}}$$

* ⭐ `Eval → Det` : **REFINEMENT, bezeugt** (Konsumwechsel innerhalb von 10 h) — **geschlossen**.
* ⭐ `Eval → Sat → Δ^sem → Zero` : **ORDERED, vom Korpus gesetzt** — **geschlossen als Reihenfolge**.
* ⛔ `K_t` : **REMAINS OPEN** — acht Tupel, und eines ist verworfen-und-weiterzitiert.
* ⛔ `Ω` : **REMAINS OPEN** — das Homonym ist nie aufgelöst.
* ⚠️ `Δ` : **teilgeschlossen** — Subtraktion verworfen, Requirement-Menge typisiert.
