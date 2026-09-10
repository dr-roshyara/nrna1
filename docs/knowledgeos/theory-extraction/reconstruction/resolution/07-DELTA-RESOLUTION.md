# Phase 7 — `δ` · Targeted Resolution

## A · Die Quelle, die Phase C nicht ausgewertet hatte

`verification/spec/A3W-state-transition-register.md` (**2026-08-29**, `EXTRACTED`, 136 Zeilen)
ist die vollständigste `δ`-Aufnahme des Korpus — ein Register über Q13/Q14/Q15/Q20,
step-031 und Steps 189/201–205. Es sagt von sich: *„Everything below records corpus claims
with locations. **Nothing reconciled.**"*

⚠️ Phase C hat 63 Signaturen **gezählt**, aber diese Spur nicht gelesen. Die Zählung war
richtig; die **Deutung als „achtzehn unverbundene Familien" war es nicht.**

## B · ⭐⭐⭐ Die Partialität **hat einen Body**: `δ defined ⟺ Pre`

Der „frozen" Kern (Q15-revised), das entwickeltste `δ`:

$$\delta : \mathcal K \times \mathcal E \rightharpoonup \mathcal K \qquad\text{explizit partiell}$$
$$\boxed{\delta \text{ defined} \iff Pre}$$

mit `Pre/Post`-Prädikaten, **8 Ereignistypen**, 7 Kommandotypen, 5 Schichten (*„only
Commitment changes `K`"*), und der Replay-Rekursion

$$Replay(K_0,\,H_t \Vert e_t) \;=\; \delta\big(Replay(K_0,H_t),\, e_t\big)$$

sowie acht Theoremen `T1`–`T8`, darunter **`T7` `K_{t+1}=δ(K_t,e_t)`** und
**`T8` `K_t = Replay(K_0,H_t)`**.

⭐ **Partialität ist hier keine Unbestimmtheit, sondern durch ein Vorbedingungsprädikat
geregelt.** Mein Phase-C-Urteil `IDENTITY UNWITNESSED` für „`⇀` vs. `∪{⊥}`" bleibt richtig,
aber die `⇀`-Seite ist **spezifiziert**, nicht bloß notiert.

⭐ Ebenfalls dort: **`T5` — *„Zero evaluates, does not constitute, Knowledge"***. Eine
`Zero`-Aussage aus der `δ`-Spur, die Phase A nicht hatte.

## C · ⭐⭐⭐ §11 beantwortet: `δ` und `τ` sind **disjunkte Entwicklungen**

| | `δ` (Q15-frozen) | `τ` (Step 204) |
|---|---|---|
| Signatur | `𝒦 × ℰ ⇀ 𝒦` | `S × C → S` |
| Totalität | **partiell**, `⟺ Pre` | **total** laut Signatur |
| zweites Argument | **Event** | ⭐ **Context** |
| Zusatzstruktur | `Pre/Post`, Replay | **Transition-Kontrakt** `T_τ=(Pre,Input,Authority,Policy,Effect,Post,Invariant,Lineage)` |
| Querbezug | — | ⭐ *„**never references `δ`**"* |

Das Register urteilt selbst:

> ⚑OBS *„the **'frozen' transition model and the transition algebra are disjoint
> developments with different signatures**"* — Q13/Q14/Q15/Q20/step-031 behandeln
> **nirgends** Idempotenz, Kommutativität, Kompensation oder Halbordnung; Step 204 tut es.

Und der Totalitätskonflikt ist **benannt und klassifiziert**:

> *„Genuine conflicts (stand): totality — **`τ` total vs `δ`/`ℛ` partial** — τ's *„valid only
> if Pre"* prose **effectively partializes it, but the signature says total**: **`C-026`-class
> defect**"*

$$\boxed{\text{Nicht dieselbe Funktion verschieden beschrieben, sondern } \textbf{zwei Entwicklungen} \text{ — plus ein Signatur-Prosa-Widerspruch in } \tau.}$$

⭐ **Bedingte Verfeinerung existiert:** *„A refinement mapping exists **if** τ's context `C` is
read as carrying the event"* — also `REFINEMENT (CONDITIONAL)`, mit ausgesprochener Bedingung.

## D · ⭐⭐ Widerspruch zu meinem Phase-C-Befund über `δ⁻¹` — **verschärft, nicht widerrufen**

Phase C fand: SPEC-DET (09-02) postuliert `∃δ⁻¹`, das *„appends a counter-record"* und damit
Invariante 1 widerspricht. **Neu:** Step 204 (08-30) hatte Reversibilität schon **abgelehnt**:

> *„reversibility **mostly denied** (`Rollback ≠ Reversal`; compensation `Payment→Refund`,
> **`Refund ≠ Payment⁻¹`**)"*

⇒ SPEC-DETs `δ⁻¹` steht **nicht nur intern** im Widerspruch, sondern auch gegen einen drei
Tage älteren, ausdrücklichen Negativbefund derselben Estate. **Festgestellt, nicht adjudiziert.**

## E · Weitere vom Register selbst protokollierte Defekte (⚑OBS, nicht meine)

| | Defekt |
|---|---|
| ① | Transitionsregeln emittieren 6-Tupel mit `𝒵_t, ℒ_t` — **gegen `T5` derselben Sektion** |
| ② | Rollback-Regel als schlichte Gleichheit `= K_{τ_target}`, während die eigene Prosa **andere Provenienz** verlangt |
| ③ | `𝒮_strat`, `Authorized` **undefiniert** |
| ④ | **`Pre`-Argumentreihenfolge zwischen §2.4 und §5 vertauscht** |
| ⑤ | Q20: `S_{t+1}=δ(S_t,e_t,P_t)` gegen Signatur `δ:𝒮×ℰ⇀𝒮` — **Stelligkeit 3 in der Anwendung, 2 in der Signatur, beide Hälften, unaufgelöst** |
| ⑥ | Kompositionsgesetz **zweimal nicht-äquivalent in einem Absatz**: `Post_{τ1} ⇒ Pre_{τ2}` gegen `Composable ⟺ Post(τ1) ⊇ Pre(τ2)` — *„run in opposite directions"* |

⭐ ⑤ ist genau die Stelligkeitsspreizung, die Phase C als getrennte „Familien" gezählt hatte.
**Der Korpus führt sie als *einen* unaufgelösten Konflikt** (`C-026`) — wieder ein besseres
Analyseraster als meines.

## F · Eine normative Trennung, die ich nicht hatte

> Step 204: drei Transitionsarten — **epistemisch / Governance / operativ** — die
> *„**must not be collapsed into one workflow**"*.

⇒ Ein Teil meiner „18 Familien" ist **vorgeschriebene** Vielfalt, kein Wildwuchs (dieselbe
Figur wie §261.24 bei `≡` und wie die Bounded Contexts bei `Γ`).

## G · ⭐⭐⭐ Querkorrektur zu Phase 3: `O` ≠ `𝒪`

Das Register führt als `K-h` das **Achtertupel** `𝒦 = (E,S,T,O,P,R,Π,A)` (049 §49.30).
Die Quelle löst die Buchstaben auf:

| | | | |
|---|---|---|---|
| `E` entities | `S` states | `T` temporal/event structure | ⭐ **`O` observations** |
| `P` propositions | `R` typed relations | `Π` policies | `A` actions |

$$\boxed{\text{Im Achtertupel steht } O = \textbf{Beobachtungen} \text{, nicht Operationen.}}$$

⇒ Phase 3s Befund *„`𝒪` gehört nicht zu den acht Primitiven"* ist **bestätigt und geschärft**:
es ist eine **Glyph-Kollision**, keine bloße Spurentrennung.

⭐ **Und eine neue Spur für Phase 4:** `𝒪_K`, die *„closed **observation set**"*, die `≡_sem`
blockiert, könnte dasselbe `O` sein. **Identitätskandidat — nicht verschmolzen**, weil kein
Beleg die beiden verknüpft.

⚠️ **Statuskonflikt vermerkt:** `49.30` nennt es *„Candidate mathematical kernel"*, `A3W`
führt `K-h` als *„ratified layer"* (Verweis `Part R D-R11`). Zwei Statusansprüche für
dasselbe Tupel — `NOT YET ASSESSED`.

## H · Closure-Status nach §14

| | | `δ` |
|---|---|---|
| 1 | Identität | ⛔ `δ` (Q15) und `τ` (204) **disjunkt**; `C-026`-Stelligkeitskonflikt offen |
| 2–3 | Domäne/Codomäne | ✅ **für Q15-`δ`**: `𝒦 × ℰ ⇀ 𝒦` |
| 4 | Stelligkeit | ⛔ **`C-026`** — 2 gegen 3, unaufgelöst |
| 5–6 | Typen | ✅ 8 Ereignistypen, 7 Kommandotypen |
| 7 | Abhängigkeiten | ⛔ **kein Commit-Fall** (`Γ` nicht in `K`) |
| 8 | Invarianten | ⭐ ✅ **`Pre/Post` + `T1`–`T8` + Replay-Rekursion** |
| 9 | unaufgelöste Alternative | ⛔ `τ` · `ℛ` · `Evolve` · `Transition` |
| 10 | Zirkularität | ⚠️ `δ ↔ K_t` — Phase 11 |

$$\delta:\quad \textsf{TYPE-CLOSED (Q15-Variante)} \wedge \textsf{INVARIANT-BEARING} \wedge \textsf{NOT IDENTITY-CLOSED} \wedge \textsf{NOT DEPENDENCY-CLOSED}$$

## I · Closure decision

$$\boxed{\textbf{TEILWEISE GESCHLOSSEN — und deutlich weiter als Phase C annahm.}}$$

| §11-Frage | Antwort |
|---|---|
| same object? | ⛔ **nein** für `δ`/`τ` — *„never references δ"*, disjunkte Entwicklungen |
| refinement? | ⭐ **ja, bedingt** — *„if τ's context `C` is read as carrying the event"* |
| replacement? | ⚠️ Q15-original wurde vom eigenen Review **superseded** (`Operation ≠ Event ≠ Transition`) |
| incompatible candidate? | ✅ `ℛ(K_t,E_t,C_t,M_t,V_t,T_t)` — 6-stellig, eigene Codomäne |
| homonym? | ✅ `δ_S` (Skalar), `δ_J` (Label), `δ` als Entscheidung |
| contextual specialization? | ✅ **vorgeschrieben** — drei Transitionsarten, *„must not be collapsed"* |
| **total vs. partiell — dieselbe Funktion?** | ⭐⭐⭐ **NEIN.** `δ` ist partiell **mit Body** (`⟺ Pre`); `τ` ist total **laut Signatur**, aber die eigene Prosa partialisiert es — ein benannter **`C-026`-Defekt** |
