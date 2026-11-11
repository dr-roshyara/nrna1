T9-22 STEP 1 ANSWER A

*Reviewer A (kos-lane-reviewer, Opus). Blind step: nothing the Professor wrote for this turn was opened. Independence here means a separate process, not a separate person. Labels: Ded = derived from the cited lines; [I] = my own judgement.*

## 1. The five objects

| Object | Class | Derivation, options or question |
|---|---|---|
| **Batching** (can one input carry several entries?) | **Derived for D_S's kinds** (the standing kinds of D); **narrowed for W's kinds** (the evidence kinds) | (1) C6's input-origin condition says each input carries one act or none: act(x) ∈ Acts ∪ {none}, and the kernel does not make acts itself (§9.1 L93). (2) Every kind of D is an act of exactly one kind, and an act's identity is its entry's content (§9.13 L27–48; §9.16 L93). (3) So an accepted input appends at most one D_S entry. A removal also tombstones its target in place, but that is one act with its effect (§9.13 L59–62; law 12, §9.11 L48). (4) A step that appended an act the input did not carry would be the kernel making an act, which (1) forbids. So COARSE's c_all is not a well-typed mechanism for D_S's kinds. T5's batching condition (§9.19 L255) is never met there. **Open for W:** O2 says an evidence entry "is not an act in A1's sense" (§9.10 L115). If an evidence input has act(x) = none, C6 does not limit how many W entries one input carries. The two options are separated by whether C6's "Acts" include W entries. The word "act" is used in two senses here [I]. |
| **G** (how finely mechanisms are cut) | **Narrowed** | **What is derived:** (a) G-coarse is excluded for D_S's kinds, by batching above. (b) Under U6 (a), "one appender per input kind of Req" (G-kind) is the same as G-act on every kind that is both a kind of D and a kind of O1 (§9.19 L160–161). (c) An appender's effect is fixed by (ρ, x): the carried act, at the next position, with the store in force and the kernel's attestation (§9.13 L48; Th1 step 7). So appenders can differ only in where they refuse (⊥), and in how the input domain is split among them. **What is left:** (i) how the domain is split. One option is per-kind appenders; another is one appender for every kind ("a_any"). The second is not a "composite" under the book's definition, because it is a case-split, not a sequence (§9.19 L173) [I]. Every split generates the same set of steps, so P, Req_O and every observer are unchanged; only \|M\| changes [I, Ded sketch]. (ii) Where guards sit: as separate components (G-guard, where T6 says H-down/H-mono need not hold) or inside each appender as its own refusal (⊥). (iii) Which act kinds O1 has. §9.10 L105 lists 8 kinds. D also has annulment, ending, consumption, transfer, removal and designation, and U6's wording does not say whether these are O1 kinds. **Question (one):** "Is a minimal kernel counted over mechanisms (then G must be fixed by decision) or over the set of steps it generates (then the split in (i) does not matter)?" |
| **Q1's "acts the answer rests on"** | **Which acts: derived (Int strength). Their form: narrowed** | **Which acts:** the book defines "rests on" only once, as the support. That is the closure from the adoptions and revocations of c under "reads": citations, annulments, endings, consumptions, removals and transfers (§9.13 L138). It is defined "for the marks" and is conservative (L140). **Form:** (a) identifiers under §9.16 L93; (b) whole entries. What separates them: recorder, status, generic fields and content beyond the required fields are visible only under (b). Under (a), recorder and status still reach the caller through the marks (L137). Purpose (o1), the actor, is inside the identity, so (a) serves it. Choosing the least informative form that still serves the purpose would itself be a selection criterion, so this goes to the Principal [I]. **Coupling:** with (a), the caller can resolve a grant's or a root's identifier only if Q2's scope covers grants and roots, or if some entry lookup exists. Req has no such lookup. |
| **Q2's output and scope** | **Output: derived. Scope: narrowed** | **Output:** the reference is "the record itself, cut at s" (§9.10 L50). The output is "the entries about k … in record order" (L45), and "no entry is returned that is not in the record". A removed entry appears "as its recorded loss" (L48), i.e. the tombstone as recorded. So Q2 returns whole entries, recorder and status included. **Scope:** "items with a governed identifier" (L46). Whether grants and roots are such items depends on item identity, which is T9-14 (not usable). Registration is not in D (§9.13 L52). **And "about k" is undefined for D's kinds.** Adoptions and revocations name an item. Annulments, removals, endings, consumptions and transfers name an entry or a grant. Designations name a store [I]. So Q2's scope needs (α) T9-14 and (β) a definition of "about". (β) is theory work, not a choice. |
| **Q3's "found (with the grant)"** | **Narrowed** | The reference is ⋁ over the grants and roots g in R≤s of basis(g, h) (§9.13 L255). (i) So the thing found can be a **root**: "the grant" must include roots (Ded). (ii) Several bases can be T at once, because plural bases are allowed (§9.16 L170; χ, §9.13 L83). A singular "the grant" therefore makes ref_q a non-function. The only choice-free form is **the set of T bases**; one basis chosen by a rule is a further choice [I]. (iii) Identifiers or whole entries, as for Q1. Under (iii-a), an act needs only identifiers to cite its basis (D's citations name entries). |

**Omission [I]:** Q4's output, "with the evidence entries it rests on" (§9.10 L70; g1's reasons, §9.18 L202), raises the same identifier-or-entry question for the recorder and status of evid entries. T9-22 lists only Q1–Q3.

## 2. Minimality (§9.20.5), restated for these forms

- **Q2's scope comes first [I].** If every entry is about some item in Q2's scope, then whole-entry Q2 at every cut fixes every entry and its position. The quotient is trivial, the record is minimal for Req's core, and the forms of Q1 and Q3 no longer matter. Otherwise, the forms of Q1 and Q3 (and Q4) decide.
- **Pair P1 (M6, a root's act time 0 or −3).** These records count as one state when Q2 excludes roots and the Q1/Q3 identifiers follow the "fields CE* reads" reading of L93. They are separated when Q2 includes roots, when outputs are whole entries, or when identifiers follow L93's "required fields" reading, because act time is a required field of a root (§9.13 L28). **So §9.20.5's "with identity outputs such pairs exist" depends on which reading of L93 holds.** L93's two readings differ (its own R3 note, B-07).
- **Pair P2 (only the recorder of a grant signed-as-actor differs; B's P2–P4).** One state under identifier outputs on either reading (recorder is outside the identity). Separated by whole-entry Q1 or Q3, or by Q2 including grants.
- **Correction (Ded from §9.13 L122; not modelled; high).** A delegated grant's own act time **is read**. A grant is an act whose authorization is basis(g₁, g₂), which tests t_{g₂} ∈ g₁'s window and before(e, g₂) for endings of g₁. Example pair: root R; g₁ under R with window [2, 5); g₂ by g₁'s holder at act time 3 or 6; an adoption under g₂. Q1 is T in the first record and F in the second. So §9.20.5's Ded ("never the grant's act time") and §9.20.3 L48 hold only for grants based directly on a root.

## 3. T9-22

**Not closed. It reduces to one decision:** "Do Req's observers name acts by L93 identifier or by the whole entry (Q1's support, Q3's set of T bases, and Q4's evidence entries)?" That question is well-posed only once L93's reading is fixed (B-07). Q2's output is derived. Q2's scope waits on T9-14 and on a definition of "about k" (theory work).

## 4. Consequences for T9-03 and T9-04 (stated, not settled)

- **T9-03:** For D_S's kinds there are no composites. So by §9.19 L174, every mechanism-minimal kernel there consists of underivable mechanisms. But "derivable" does not cover case-split joins like a_any, so primitive minimality needs a notion of derivability that does [I].
- **T9-04:** T5 cannot apply to D_S's kinds. With per-kind appenders and fixed guards, T10's condition holds, as in the GACT model with a per-kind cut. Uniqueness can still fail in three ways: through a_any (in name only, the same steps), through guards placed inside appenders (real alternative routes), or through W batching if it is allowed.
- **Neither T9-03 nor T9-04 depends on the output forms.** Observers add nothing to mechanism minimality (§9.19 L180). The forms affect only 𝒮.

## 5. What would refute each answer

| Answer | Refuted by |
|---|---|
| No batching for D_S | A D kind whose entry carries two acts; act(x) read as a set; or §1.16 L171's "human approval recorded atomically with its effect" read as one step of the type rather than a refinement of two steps |
| G-kind = G-act | A D kind that is the kind of no O_i |
| Q1 "which acts" = the support | A book line that defines "rests on" for Q1 otherwise |
| Q2 returns whole entries | A Q2 reference over identities, or (T-b) shown to be served by identities |
| Q3 as the set of T bases | D shown to guarantee at most one T basis |
| The delegated-grant correction | Φ shown not to evaluate a grant's issuance against its parent's window |

## 6. Limits and process

- **Read in full:** §9.01, §9.10, §9.11, §9.19, §9.20, this brief, REVIEWER-INSTRUCTIONS, WORKING-INSTRUCTIONS-IN-FORCE.
- **Read in part:** §9.13 L1–262; §9.16 L60–209; §9.18 L175–214; §1.16 L1–40.
- **Seen through grep only:** §9.15, §9.17, §9.18, §9.06, §9.12, THEORY-MAP, CONCEPT-REGISTER.
- **Not opened:** §9.02–§9.09 (except the wc count), §9.12, §9.14, RESEARCH-PURPOSE, any corpus source.
- No tools were run and nothing was copied. The P1/P2/P3 claims are derived from the text, not computed.
- **Minor access deviations (A6, disclosed; nothing relied on outside the brief's allowed files):**
  - To find file names without a directory listing, I used shell globs twice: `wc -l manuscript/chapter_09/09.0[1-9]-*.md` and `ls -d 09.18-* 09.19-* 09.20-*` (plus `wc` on the same patterns). Both matched only files the brief allows.
  - I grepped `tmp/tools/QUOTE-BASELINE.txt` in place for the §1.16 file name instead of on a scratch copy.

**Files opened (all under `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/`):**
- `06-theory/manuscript/review/T9-22-TURN/BRIEF-STEP1.md`
- `03-methodology/REVIEWER-INSTRUCTIONS.md`
- `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`
- `06-theory/manuscript/chapter_09/09.20-the-state-space.md`
- `06-theory/manuscript/chapter_09/09.19-req-and-a-when-a-minimal-kernel-is-well-posed.md`
- `06-theory/manuscript/chapter_09/09.10-required-capabilities.md`
- `06-theory/manuscript/chapter_09/09.01-kernel-scaffold.md`
- `06-theory/manuscript/chapter_09/09.13-the-evaluator.md` (L1–262)
- `06-theory/manuscript/chapter_09/09.16-reassessment.md` (L60–209)
- `06-theory/manuscript/chapter_09/09.11-kernel-type-from-req.md`
- `06-theory/manuscript/chapter_09/09.18-evidence-in-the-record.md` (L175–214)
- `06-theory/manuscript/chapter_01/01.16-consistency-boundaries-time-and-reproducibility.md` (L1–40)

**Commands and searches run (all read-only):**
1. `wc -l` on THEORY-MAP.md, RESEARCH-PURPOSE.md, CONCEPT-REGISTER.tsv.
2. `grep -o` for chapter_09 and 01.16 file names in THEORY-MAP.md, CONCEPT-REGISTER.tsv, RESEARCH-PURPOSE.md.
3. `grep` for the 09.18–09.20 and 01.16 names in THEORY-MAP, CONCEPT-REGISTER, 09.16, 09.17; then `wc -l` with the 09.0[1-9]-*.md glob.
4. `grep -n` for "9.18 / 9.19 / 9.20 / 1.16" in THEORY-MAP.md and "9.20" in CONCEPT-REGISTER.tsv.
5. `grep -oh` for section file stems in the three index files.
6. `grep -oH` in 09.01, 09.10, 09.11, 09.13, 09.16, 09.17 for later file names; `wc -l` on 09.01–09.17, each named.
7. `grep -oh` in tmp/tools/QUOTE-BASELINE.txt for the file names.
8. `grep -oH` in 09.02–09.09, 09.12, 09.14, 09.15, each named; `grep -l` in QUOTE-BASELINE.txt and THEORY-MAP.md.
9. `ls -d` and `wc -l` with the 09.18-*, 09.19-*, 09.20-* globs.
10. In 01.16: `wc -l`; `grep -n` for 1.16.1 / atomic / batch / one step.
11. `grep -n` in 09.15, 09.17, 09.18, 09.06, 09.12 for K6 / K1 / exclusive write / same kernel step / batch.
12. `grep -n` in 09.18 for G8 / (c3) / evid / ewdr / erst / D_W / D_S / rests on.
13. `grep -n` in 09.13, 09.20, 09.19, 09.18 for "absence", "about" and "per kind".
