# `P-75` — Provenance Audit: `step_167`/`168` → `GN-46`

**2026-09-09 · Lane T.** After [`92` `P-74`](./92-P74-IS-STEP-161-A-FOURTH-CONTRACT-NOTION.md).
**Method:** Chronological Thread Discovery Protocol *(`MEMORY.md`, adopted 2026-09-09)* — **census
first, reconstruct second.**

# 1. Executive verdict

$$\boxed{\mathbf{[DEPENDENT].} \textbf{ Direction: } \mathbf{step\_16x \longrightarrow GN\text{-}46.} \textbf{ Ladder position: } \mathbf{DIRECTLY\ CITED\ +\ EXPLICITLY\ DEPENDENT.}}$$

⛔ **Not coincidence. Not independence. Not replication.** ⭐⭐⭐ The `GN-46` lane holds a **per-step
verification register** — `STEP-TO-THEORY-TRACEABILITY.md`, 266 lines — with **a row for every step
`161`–`170`**, each carrying the step's opening line *(so it was read)*, a band label, **a verification
verdict**, and **findings with IDs**.

⭐⭐⭐ **And it corrects `P-74` directly:**

$$\boxed{\begin{array}{c}\mathbf{VK\text{-}3:\ \textit{"SIX INTERNAL TUPLE CONTRADICTIONS INSIDE ONE FILE. §161.3–161.19 define the same objects differently from §161.55"}}\\[4pt] \textbf{⚠️ } P\text{-}74 \textbf{ read } \mathbf{161.3}\textbf{ AND } \mathbf{161.55} \textbf{ and } \mathbf{did\ not\ notice\ they\ contradict.}\end{array}}$$

$$|K| = 11 \textbf{ — unchanged, } \mathbf{[REC]}. \textbf{ ⛔ No model merged, nothing promoted, no admission status changed.}$$

# 2. §1 Census — run before any thread claim

**Census A — the `step_16x` chronological index**, `20260829-011300` → `014500`, **16 files**:

`011309` **161** semantic-contract reconstruction *(1 807)* · ⚠️ `011356` **159-duplicate** *(1 218)* ·
`011552` **162** invariant/boundary *(1 456)* · `011730` **163** context map *(1 383)* · `012048` **164**
domain events *(1 746)* · `012222` **165** aggregate boundaries *(1 782)* · `012701` **166** process
managers *(1 861)* · `012838` **167** formal verification obligations *(1 612)* · `013134` **168** the
verification lattice *(1 872)* · `013327` **169** falsification *(1 313)* · `013526` **170** end-to-end
proof chain *(1 654)* · `013701` **171** authority-responsibility matrix · `013920` **172**
separation-of-duties experiment · `014058` **173** bounded-context discovery experiment · `014253`
**174** context-map experiment · `014420` **175** *"the state does not know its past"* experiment.

⭐⭐ **`P-74` reported the thread as `161 → 168`. The census shows `161 → 175`+** — through falsification,
a proof chain and **four experiments.** ⛔ **`P-74`'s `thread_end` was correctly marked *not
established*, and it is now extended, not corrected.**

⚠️⭐⭐⭐ **§8 trap caught by the census:** `step_159_..._duplicate` sits **inside** the sequence at
position 2. ⛔ **A naive forward-walk would have counted it as development.** ⭐ **This is why the census
precedes the reconstruction.**

**Census B — `GN-46`'s artifacts:** `verification/V0-theory-corpus-map.md` · `spec/00-INDEX` ·
`spec/A1`–`A4`, `A10`, `A3X` · `spec/K0` · `plan/04`, `plan/07`, `plan/11-theory-to-architecture-traceability`.
⭐ **Timestamp coverage: 9% *(`P-73`)* — so the index analogue governs here, not chronology.**

# 3. §12 Information-flow test — direct citation, verbatim

`STEP-TO-THEORY-TRACEABILITY.md`, control `step` = **256** occurrences in 266 lines. Rows `161`–`170`:

| step | `GN-46` verdict |
|---|---|
| **161** | ⭐⭐⭐ `VERIFIED · ` **`DEF CONTRADICTORY (VK-3: six tuple definitions contradicted WITHIN ONE …)`** |
| **162** | `VERIFIED · NOT_EXECUTED` — ⚠️ *"mints `I-01…I-20` = **step-048's range**"* |
| **163** | `VERIFIED · CONCEPTUAL-ONLY · P1–P10 minted` — ⚠️ *"**third** status vocabulary; duplicate §"* |
| **164** | `VERIFIED · NOT_EXECUTED` — *"the band's **only** back-reference to a registry"* |
| **165** | `VERIFIED · CONCEPTUAL-ONLY` — ⭐ *"aggregate table **self-labelled 'hypotheses, not architecture'**"* |
| **166** | `VERIFIED · NOT_EXECUTED` — *"`AgentSession ≠ BusinessProcess` RETAINED"* |
| ⭐⭐⭐ **167** | `VERIFIED · ` **`DERIV INVALID — TV-F-076`** — *"§167.32 `I(S;H) < H(H)` **is FALSE** when …"* |
| **168** | `VERIFIED` — *"§168.57 multi-dimensional state `⟨Operational, Epistemic, Governance, Authoriz…⟩`"* |
| ⭐⭐ **169** | `VERIFIED · ` **`TEST EXECUTED_PARTIAL — GENUINE FALSIFICATION`** *(attacks 5 of 8 laws)* |
| **170** | `VERIFIED · CONCEPTUAL-ONLY` — *"stipulates `D_t` = Determination"* |

$$\boxed{\textbf{⭐⭐ Every step in the band was } \mathbf{read,\ verified\ and\ adjudicated\ individually.} \textbf{ ⛔ } \mathbf{No\ inference\ from\ date\ or\ vocabulary\ was\ needed\ or\ used.}}$$

⭐ **Supporting artifacts:** `STEP-VERIFY-159-185.md` *(a verification document for the band)* and
`TV-F-071-078-steps-158-185-findings.md`. ⇒ **The lane verified steps 158–185 systematically.**

# 4. ⭐⭐⭐ Two findings that matter more than the verdict

**4.1 `VK-3` — and it is a direct correction of `P-74`.**
> *"**SIX INTERNAL TUPLE CONTRADICTIONS INSIDE ONE FILE.** §161.3–161.19 define the same objects
> differently from §161.55"* … *"**SUPERSEDED-without-crosswalk by 162.**"*

⚠️⭐⭐⭐ **`P-74` read `161.3`'s `Definition` and `161.55`'s eight signatures — both — and characterized
the file as a clean *"signature layer."*** ⛔ **It is internally contradictory by the estate's own
verification, and superseded by `step_162` with no crosswalk.**

$$\boxed{\textbf{⭐⭐ } P\text{-}74 \textbf{ is } \mathbf{[QUALIFIED]}\textbf{: the signature layer is not merely unconstructed — } \mathbf{it\ is\ inconsistent.}}$$

⭐ **Seventeenth miss, and different in kind from the previous sixteen:** ⛔ **not *"did not find the
file"* but *"read both contradicting sections and did not compare them."*** ⚠️ **That is a reading
failure, not a search failure, and the protocol's §1 census did not prevent it — only the estate's own
register caught it.**

**4.2 `TV-F-076` — the estate's own witness for the forward-read method.** Its title:
> ⭐⭐⭐ ***"A genuine mathematical error at 167.32, correctly repaired eight steps later without anyone
> noticing."***

⭐⭐ **A real error, self-repaired ~eight steps downstream, and the repair went unnoticed until a
systematic forward pass found it.** ⛔ **`P-74` stopped at `168` — one step past the error and seven
short of the repair.**

# 5. §4 Same-object test

⛔ **`step_167`/`168` and `GN-46` do NOT target the same proposition.** `step_167` mints **verification
obligations** for the KnowledgeOS architecture; `GN-46` **verifies the corpus that contains them**, `167`
included — and **found `167.32` false.**

$$\boxed{\textbf{⭐⭐ } \mathbf{GN\text{-}46\ is\ META\ to\ the\ step\ band,\ not\ parallel\ to\ it.} \textbf{ ⛔ So } \mathbf{REPLICATION\ is\ not\ the\ relation}\textbf{, and neither is independence.}}$$

# 6. §5 Independence classification

| candidate | verdict |
|---|---|
| `[SAME ARTIFACT/LINEAGE]` | ⛔ **no** — different lanes, different mandates |
| ⭐⭐⭐ **`[DEPENDENT]`** | ⭐ **ESTABLISHED** — per-step rows, opening lines quoted, verdicts and finding IDs |
| `[INDEPENDENT RESEARCH — PLAUSIBLE]` | ⛔ **REFUTED** by the register |
| `[INDEPENDENCE UNRECORDABLE]` | ⛔ **not applicable** — the flow is recorded |
| `[COINCIDENTAL SIMILARITY]` | ⛔ **REFUTED** — `P-74`'s hypothesis, and it was wrong |
| `[UNDETERMINED]` | ⛔ **no** |

⭐ **`P-74` offered *"coincidence"* as one of two branches and warned against the shared word and date.
⭐⭐ The evidence chose the other branch — and it did so on citation, exactly as the commission required.**

# 7. §6 DDD and admission consequence

⭐⭐ **`GN-46`'s artifacts do not inherit the step band's status — they *assign* it.** ⇒ **No admission
status changes for `GN-46`; and the step band's status is now known to be *verified with findings*, not
merely *found*.** ⛔ **No model merged. No result promoted into KnowledgeOS theory.**
⚠️⭐ **And `step_161`'s own `[REC]`-grade *"freeze the first semantic contract layer"* now reads
differently:** the estate verified that layer and recorded it **contradictory** — ⛔ **which is not the
same as rejecting it, and the register does not reject it.**

# 8. Effect on the current programme

⭐⭐⭐ **`P-72`'s picture is unchanged but better evidenced:** `step_161` does not discharge step 1, and
now not only for the type reason *(`P-74`)* but because **its signature layer is internally
inconsistent** *(`VK-3`)* and **superseded without a crosswalk** *(`step_162`)*.
⭐⭐ **And `EKS-25` grows heavier:** the stopped programme's reach is far larger than its index shows —
⭐⭐⭐ **`TV-F` findings run to `TV-F-088`, 88 distinct IDs, while `spec/00-INDEX` records
`TV-F-001…019`.** ⇒ **69 findings the index does not mention.**

# 9. §11 Provenance record

| field | value |
|---|---|
| `thread_id` | `step-16x band` → `GN-46 verification` |
| `thread_start` · `thread_end` | `20260829-011309` (`161`) · ⚠️ **not established — `175`+ still on topic** |
| `files_examined` | ⭐ **4** — `STEP-TO-THEORY-TRACEABILITY`, `STEP-VERIFY-159-185` *(grep-level)*, `TV-F-071-078` *(title)*, `step_161` *(carried)* |
| `files_not_examined` | ⛔ **`162`–`175` in full; the 88-finding set; `V0`; `plan/*`** |
| `timestamp_coverage` | step band **100%**; `GN-46` lane **9%** ⇒ index analogue used |
| `discovery_signal` | `P-74`'s title/date resemblance — ⭐ **a discovery signal only, and it was wrong about coincidence** |
| `topic_identity` | ⛔ **different** — obligations vs verification of obligations |
| `provenance_relationship` · `information_flow` | ⭐⭐⭐ **`DIRECTLY CITED` · `EXPLICITLY DEPENDENT` · `step_16x → GN-46`** |
| `key_claims` | `VK-3` · `TV-F-076` · `169`'s genuine falsification of 5 of 8 laws · `162`'s `I-01…I-20` range collision · `163`'s *"third status vocabulary"* |
| `corrections` | ⭐⭐ **`VK-3` corrects `P-74`**; `TV-F-076` corrects `step_167` |
| `final_disposition` | ⭐ band **VERIFIED with findings**; ⚠️ programme **STOPPED pending supervision** *(`EKS-25`)* |
| `admissibility` · `epistemic_status` | **CHARACTERIZED** for the register; ⛔ **`FOUND`** for the 88 findings |

# 10. Status register
**`[EMP]`** ⭐⭐⭐ the ten per-step rows verbatim · `VK-3`'s six contradictions and *"superseded-without-
crosswalk by 162"* · `TV-F-076`'s title · `169`'s genuine falsification · **88 distinct `TV-F` IDs vs the
index's 19** · the 16-file census incl. the interleaved duplicate · control `step` = 256.
**`[DERIVED]`** ⭐⭐⭐ `[DEPENDENT]`, `step_16x → GN-46` · `GN-46` is **meta** to the band, so replication
is not the relation · `P-74`'s thread end extended to `175`+.
**`[QUALIFIED]`** ⭐⭐ **`P-74`'s *"clean signature layer"*** — inconsistent by `VK-3`.
**`[REFUTED]`** ⭐⭐ `[COINCIDENTAL SIMILARITY]` · `[INDEPENDENT RESEARCH — PLAUSIBLE]`.
**`FOUND`** ⭐ `STEP-VERIFY-159-185` · `TV-F-071-078` · the 88-finding set · `V0-theory-corpus-map`.
**`[OPEN]`** ⭐⭐ `η` / step 1 · what the other ~69 findings say · all carried opens · ⛔ **well-
foundedness / terminality / acyclicity — DEFERRED.**
**Governance:** `|K| = 11` **`[REC]`** — unchanged, unfrozen.

### 11. ONE NEXT UNRESOLVED QUESTION

$$\boxed{\textbf{Do any of the } \mathbf{88\ TV\text{-}F\ findings} \textbf{ bear on the } \mathbf{eleven\ persistence\ cells}\textbf{ — and has this lane's kernel already been verified without its knowledge?}}$$

⭐⭐⭐ **This follows directly from the evidence found, not from a roadmap.** The `GN-46` lane verified
steps `158`–`185` per-step, found a genuine mathematical error, a genuine falsification of 5 of 8 laws,
six internal contradictions in one file, and a range collision — ⭐ **and it produced 88 findings while
its own index records 19.** ⛔ **If any finding touches identity retention, supersession, warrant or
transition records, then the kernel has an external verification verdict it has never been shown.**
⚠️ **And the check must be by finding content, ⛔ never by the finding IDs' numeric range or the word
*"persistence"*** — the two things that would make an unrelated finding look like a verdict on `K1`–`K11`.

```
[DEPENDENT]. DIRECTION step_16x -> GN-46. LADDER POSITION: DIRECTLY CITED + EXPLICITLY DEPENDENT.

Not coincidence, not independence, not replication. The GN-46 lane holds a per-step verification register
— STEP-TO-THEORY-TRACEABILITY.md, 266 lines, control "step" = 256 — with a row for every step 161 to 170,
each quoting the step's opening line, carrying a band label, a verification verdict and findings with
IDs. No inference from date or vocabulary was needed or used.

CENSUS FIRST, AS THE PROTOCOL REQUIRES, AND IT PAID TWICE. The step band runs 161 to 175 and beyond —
through falsification, an end-to-end proof chain and four experiments — not 161 to 168 as P-74 reported;
P-74 had correctly marked its thread_end "not established", so this extends rather than corrects it. And
step_159_duplicate sits INSIDE the sequence at position two: a naive forward-walk would have counted it as
development. That is why the census precedes the reconstruction.

TWO FINDINGS MATTER MORE THAN THE VERDICT. VK-3 reads: "SIX INTERNAL TUPLE CONTRADICTIONS INSIDE ONE
FILE. §161.3-161.19 define the same objects differently from §161.55", and "SUPERSEDED-without-crosswalk
by 162". P-74 read 161.3's Definition AND 161.55's eight signatures — both — and characterized the file as
a clean signature layer. It is internally contradictory by the estate's own verification. P-74 is
QUALIFIED: the layer is not merely unconstructed, it is inconsistent. Seventeenth miss, and different in
kind: not "did not find the file" but "read both contradicting sections and did not compare them" — a
reading failure, not a search failure, which the census could not have prevented.

And TV-F-076's title is the estate's own witness for the forward-read method: "A genuine mathematical
error at 167.32, correctly repaired eight steps later without anyone noticing." P-74 stopped at 168 — one
step past the error and seven short of the repair.

SAME-OBJECT TEST: step_167/168 and GN-46 do not target the same proposition. step_167 mints verification
obligations; GN-46 verifies the corpus containing them, 167 included, and found 167.32 FALSE. GN-46 is
META to the band, not parallel — so replication is not the relation, and neither is independence.

INDEPENDENCE CLASSIFICATION: [DEPENDENT] established; [COINCIDENTAL SIMILARITY] and [INDEPENDENT RESEARCH
— PLAUSIBLE] both REFUTED. P-74 offered coincidence as one of two branches and warned against the shared
word and date; the evidence chose the other branch, on citation, exactly as required.

DDD CONSEQUENCE: GN-46's artifacts do not inherit the band's status, they assign it — so no admission
status changes, and the band is now known to be verified-with-findings rather than merely found. No model
merged, nothing promoted.

EFFECT ON THE PROGRAMME: P-72's picture is unchanged but better evidenced — step_161 does not discharge
step 1, now also because its signature layer is internally inconsistent and superseded without a
crosswalk. And EKS-25 grows heavier: TV-F findings run to TV-F-088, 88 distinct IDs, while the
programme's own index records TV-F-001 to 019 — 69 findings the index does not mention.

|K| = 11 UNCHANGED, [REC], UNFROZEN. No model merged, nothing promoted, no admission status changed.

ONE NEXT UNRESOLVED QUESTION: Do any of the 88 TV-F findings bear on the eleven persistence cells — and
  has this lane's kernel already been verified without its knowledge? The GN-46 lane verified steps 158
  to 185 per-step, found a genuine mathematical error, a genuine falsification of five of eight laws, six
  internal contradictions in one file and a range collision, and produced 88 findings while its index
  records 19. If any finding touches identity retention, supersession, warrant or transition records, the
  kernel has an external verification verdict it has never been shown. And the check must be by finding
  content, never by the IDs' numeric range or the word "persistence" — the two things that would make an
  unrelated finding look like a verdict on K1 to K11.

NO three_model_convergence INSPECTION — excluded by path from every command, unread.
```
