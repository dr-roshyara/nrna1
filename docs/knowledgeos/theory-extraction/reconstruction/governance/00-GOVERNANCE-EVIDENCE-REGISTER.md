# Governance Evidence Register

> **Verbindliche Korrektur des Evidenzmodells (Kommission §1):**
> $$\boxed{\text{Mention count} \neq \text{governance coverage} \neq \text{mathematical evidence}}$$
> Keine Dimension wird aus einer anderen abgeleitet. Jede Kennzahl nennt ihre Population.

**Population:** `reviews/synthesis/analysis/governance-notes.md` — **85 GN-Sektionen**,
`GN-19` (2026-08-28) → `GN-96` (2026-09-02). 96 GN-Ids estate-weit zitiert; `GN-04`…`GN-18`
**ohne Sektion** (`NOT YET LOCATED`); 14 Sektionen ohne Datum.

---

## A. Die drei Kennzahlen, getrennt (Kommission §11)

| Kennzahl | Wert | Art |
|---|---|---|
| Vorkommen des Wortes `RATIFIED` | **33** | **lexikalisch** |
| davon buchbezogen (`book … ratified`) | **7** | **gegenstandsbezogen** |
| `NOT RATIFIED` / `no ratification` | **8** | negative Governance-Evidenz |
| **positive Ratifikationen eines der sieben Theorieobjekte** | **0** | **Entscheidungskennzahl** |

$$\boxed{\text{Positive Ratifikationen von Theorieobjekten in 85 Governance-Akten: } \mathbf{0}}$$

---

## B. Satzbezogene Messung — Objekt UND Akt-Verb im **selben Satz**

| Objekt | Mention | **Subject** | Ratif | Reject | Select | Decision | Recomm | Review | **NEG** |
|---|---|---|---|---|---|---|---|---|---|
| **`𝒪_core`** | 12 | **1** (`GN-78`) | **2 — beide Verneinungen** | 0 | 0 | 2 | 0 | 0 | **3** |
| `Zero` | 8 | 0 | **0** (3 Treffer, **alle Falsch-Positive**) | 0 | 0 | 4 | 1 | 0 | 1 |
| `δ` | 2 | 0 | 0 | 2 | 0 | 0 | 1 | 1 | 0 |
| `Γ` | 1 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 |
| **`Sat`** | **0** | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 |
| **`≡sem`** | **0** | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 |
| **`ℛ_req`** | **0** | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 |

**Kontrollbelege** — die einzigen zwei Sätze mit `𝒪_core` + `RATIFIED`:
> *"**𝒪_core is NOT frozen and NOT ratified** — the lane's own latest word: 'must NOT be frozen
> as-is…'"* · *"| **NO** | | Is the operation registry ratified?"*

⇒ **Status für `Sat`, `≡sem`, `ℛ_req`: `GOVERNANCE-UNBEFASST`** — weder ungovernt noch
undefiniert; **sie waren nie Gegenstand eines Akts.**

---

## C. ⚠️ Messfehler-Kette (Kommission §9) — alle drei Messungen bleiben stehen

| # | Messung | Ergebnis | Fehlerursache | Status |
|---|---|---|---|---|
| **1** | Sektions-Ko-Okkurrenz, `awk` | `𝒪_core 10 · Zero 8 · δ 4 · Γ 1` | `\|`-Escape-Fehler in `awk`; case-sensitiv | **überholt** |
| **2** | Sektions-Ko-Okkurrenz + Akt-Verben | `𝒪_core Ratif **11**` | ⛔ **unzulässig** — leitet „Ratification" aus *Verb irgendwo im Abschnitt* ab; verletzt §1. Zudem fängt `\bZero\b` case-insensitiv „**ZERO** AUTHORITATIVE ACTS" | **verworfen** |
| **3** | **satzbezogen**, mit Falsch-Positiv-Kontrolle | `𝒪_core Ratif 2, beide negativ; alle anderen 0` | — | **gültig** |

⭐ **Restfehler in Messung 3, offen dokumentiert:** mein `Zero`-Ausschluss
`(?!AUTHORITATIVE|entries)` fängt *"zero CRITICAL/HIGH"* nicht; die drei `Zero`-Ratif-Treffer
sind durch Einzelinspektion als Falsch-Positive ausgeschieden, **nicht** durch das Muster.

---

## D. Governance-Wahrheit vs. mathematischer Status (Kommission §5)

| Objekt | **Governance truth** | **Mathematical status** |
|---|---|---|
| `𝒪_core` | **NOT RATIFIED** (`GN-84`), Instrument **entworfen, unsigniert** (`GN-78`) | `DEFINED` (5 Operationen, je numerierte Definition + Nicht-Kollaps-Klausel, part-04) · **`COMPETING`** (≥3 Mitgliedslisten, 6 rivalisierende Registries) |
| `δ` | **kein Akt** | `TYPE-CLOSED` (`𝕂×𝒪×Ctx→𝕂∪{⊥}`) · Semantik **`PARTIAL`** |
| `Zero` | **kein Akt zum Objekt** | `DEFINED` (Def 5.6) · `Zero ⟺ Complete` **definitional**, nicht bewiesen |
| `Γ₁` / `Γ₂` | **kein Akt** | **`COMPETING`** — Identität unbewiesen, `NOT SAME OBJECT` |
| `Sat` | **GOVERNANCE-UNBEFASST** | `DEFINED` (5-wertig, part-05) · Dependency-Closure **offen** |
| `≡sem` | **GOVERNANCE-UNBEFASST** | **`COMPETING DEFINITIONS`** (3 Rivalen, `G-40`) |
| `ℛ_req` | **GOVERNANCE-UNBEFASST** | `DEFINED` (`ℛ_req(Q,Γ) ⊆ 𝒟`) · von zwei Reviews als *closed* akzeptiert |

$$\text{RATIFIED} = \text{NO} \;\not\Rightarrow\; \text{MATHEMATICALLY FALSE} \qquad \text{RECOMMENDED} = \text{YES} \;\not\Rightarrow\; \text{PROVEN}$$

---

## E. `GN-78` — der einzige objektbezogene Akt (Kommission §4)

**Gegenstand:** das Operation Registry. **Handlungstyp:** Instrument **entworfen**, *"unsigned,
creates no authority"*. **Entscheidung:** keine. **Auswahl:** keine — *"No candidate was chosen,
named, or ranked by this lane."* **Ratifikation:** keine — *"a ratification cannot presently be
signed on evidence."*

**Behandlung:** ⭐ **rein governance-seitig.** Die drei Hindernisse sind Governance-/Evidenz-
Hindernisse (nicht governierte Kandidatenlisten · ein **nie ausgeführtes** Kriterium · ein
Kandidat, der sich selbst als nicht einfrierbar erklärt) — **keine mathematische Widerlegung.**

⇒ **Aus `Subject` folgt NICHT `adopted`.**

---

## F. `GN-96` — Terminal-/Negativevidenz (Kommission §3)

**Behauptung:** *"Nothing ratified · nothing adjudicated · no option recommended · no signature
manufactured."* **Zahlen:** GN-Ledger **83 Einträge, head GN-94**; formelle Lane **9 Dateien,
letzter Akt 2026-08-24**; **~1.161** klassifizierte Dateien: **A = 0** · B = 4 · C ≈ 20 ·
D ≈ 8 · E ≈ 45 · F = 6.

**Was „exhausted" heißt:** ⭐ **erschöpft ist die *Intake-Prüfung der angekommenen Artefakte
gegen die zehn Blocker `B-01…B-10`*** — *"The newly arrived material did **not** lift any of
B-01…B-10 through an authoritative governance act."*

**Was dadurch abgeschlossen ist:** dass **kein** autoritativer Akt zu den Blockern existiert.
**Was außerhalb bleibt:** ⚠️ **alles Mathematische.**

$$\boxed{\text{Governance-lane exhaustion} \;\neq\; \text{theory exhaustion.}}$$

*"**The research progressed; the constitutional gate remains closed.**"* — die Lane trennt
beides selbst.
