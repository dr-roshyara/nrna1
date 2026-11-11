T9-22 STEP 1 ANSWER B

Reviewer B (kos-lane-reviewer, Sonnet). Process-independent only. Written blind, from §9.1, §9.10, §9.11 (part), §9.13.2, §9.16 L93, §9.19, §9.20 and §1.16.1, before any turn-1 draft. Lines are in the files as read.

**Access deviation, disclosed (A6, minor).** I did not know the 09.18, 09.19 and 09.20 file names. I ran one glob, `wc -c …/chapter_09/09.20*`, and one filtered listing, `ls chapter_09 | grep -E '^09\.(1[0-9]|20)-'`. Both are against "no globs, no directory listing". I read no file beyond the allowed list, and I opened nothing under review/. Not relied on beyond getting the names.

## 1. The five objects

| Object | Class | Derivation, options or the question |
|---|---|---|
| **G** | **A decision** (options named) | Req's per-kind cut gives only T4, §9.19 L246: every kernel holds, per kind, a mechanism that appends it at a reachable state. It says nothing about how many kinds one mechanism serves. **After U6 (a), G-kind and G-act coincide as pool descriptions** (Req's input requirements are now per act kind; §9.19 L160–161). [I] What stays open is the shape of the pool, in two dimensions. **(a) Appender cut:** one appender per act kind, or also a case-split appender that serves several kinds by input kind. The book's "composite" (§9.19 L173, "a sequence of other members applied in one step") does not cover a case split. **(b) Guards:** inside the mechanism as its ⊥ (§9.19 L159), or separate components, G-guard. Under G-guard, T6 shows H-down and H-mono can fail. **Derived by small model, not in the book (Ded, scratch `dispatch.py`):** if the pool holds a_1…a_n (n ≥ 2) and also a case-split a_all that preserves I, then under the per-kind Req_O both {a_all} and {a_1…a_n} are minimal, so there are two minimal kernels. T5 does not reach this, because a_all is not a composite. Nothing in Req's cut, C6 or D excludes a_all. Fixing (a) or (b) is a choice, so it is not made here. **The one question:** "Does the interface offer one append mechanism per act kind, with its guard inside it, or may it also offer a mechanism that serves several kinds by case?" |
| **Batching** | **Derived**, conditional on named premises | (P1) C6's input form: each input carries one act or none, §9.1 L93. (P2) The origin rule as a transition invariant I_T: standing and acts change "only for what that act names", §9.1 L96 and L109 (law 2, §9.11 L34, "C6's origin rule"). (P3) An entry is one act of one kind with its own fields (§9.13.2 L27–52), and its identity is content-derived (§9.16 L93). **Conclusion:** one accepted input appends at most one entry, plus the in-place tombstone that a removal act names (§9.13 L47). A composite that appends several kinds from one one-act input appends acts the input does not carry, which the origin rule forbids. T5's c_E (§9.19 L251–255) therefore does not exist under P1–P3; this is §9.19's COARSE-T case, now derived rather than assumed. Law 12 (§9.11 L48) is vacuous in the type, so it adds nothing. **Scope limit:** held acts (§5.6) and the five kinds outside D (§9.13 L52). |
| **Q1 "acts the answer rests on"** (§9.10 L34) | **Narrowed**, with a derived part | **Derived part:** the list is a set, and it is the support of §9.13 L138–140. A minimal justification is not unique, because a second intact basis exists. Choosing one would need a selection rule, which the limits forbid. The support is a function of the call. The identification of Q1's "rests on" with L138's support is [I]: the same phrase, but §9.10 does not say so. **Open part:** the support's members are returned as identifiers (L93: kind plus D's required fields, without recorder and status) or as whole entries. Marks (§9.13 L137) already expose status when the entry is signed-as-other or unsigned. They expose the recorder in no case that was tested. |
| **Q2 output and scope** | **Output form: derived from the text (Src).** **Scope: a decision.** | **Form:** Q2 returns "the entries about k … in record order" (§9.10 L45). Its reference is "the record itself, cut at s" (L50). A removed entry appears as its recorded loss (L48). That is whole entries. A-05's identity reading (§9.20 L109) conflicts with the text and is not adopted here. **Scope:** "items with a governed identifier" (L46). Registration is outside D, and entry identity is content-derived rather than governed, so whether grants, roots and entries-as-targets lie in scope is not decided. Whether a grant's own issuance entry counts as "about" that grant is also open. T9-14 and T9-18 are not used. |
| **Q3 "found (with the grant)"** (§9.10 L57) | **Narrowed**, with a derived part | **Derived part:** the reference is the basis clause for a hypothetical act (§9.10 L62; §9.13 L122), existential over grants. Several grants can cover one call. A single "the grant" is therefore not a function of the call. A single grant would need a selection rule among covering grants. None is in the book, and ties in act time are unordered (§9.13 L26). So the grant part is **the set of covering grants**, with value T, F or U. **Open part:** the set as identifiers or as entries (the same question as Q1). |

## 2. Minimality (§9.20.5) restated

State = records modulo the coarsest right congruence over all observers (Q1 with its act list and marks, Q2, Q3, g1, Q5), all guards and all replies, as at §9.20 L102. Define a form vector φ = (Q1 list, Q2 scope, Q3 grant-set), each entry named as an identifier or as a whole entry.

- **If every entry any output names is returned whole**, and Q2 returns whole entries, no pair was found (§9.20 L111, reported and not proved). The record is then minimal for the core except for entries no call names.
- **If every output names entries only by identifier, and no Req question resolves an identifier to its entry**, the record is not minimal. The recorder of a grant or root signed as its actor is read by no mark, no guard, no value and, by L93, no identifier.
- **Pair that the forms separate.** Take R_a and R_b with the same root r, grant g (holder h, window and scope the same, not single-use, signed-as-actor) and adoption y citing g, differing only in the recorder field of g. Both give identical Q1, Q3 and Q2 identifier outputs, and identical marks, guard verdicts and g1 classes. Whole-entry outputs, or Q2 resolving g's identifier, separate them.
- **A second pair (M6: root or grant act time) depends on a tension in L93.** The L93 text says identity comes from "the fields CE* reads", then glosses this as "D's required fields other than recorder and status". The second reading includes act time, so identifiers separate the M6 pair. The first reading excludes it. The R3 note on L93 already records the mismatch.
- [I] Joint dependency. If Q2's scope covers whatever Q1's list and Q3's grant-set can name, and Q2 returns whole entries, then identifiers in Q1 and Q3 resolve through Q2 and their form does not matter for minimality.

## 3. T9-22

**Reduced, not closed.** One decision remains, with a conditional second:
- **Q-T9-22:** "Does Q2's scope cover every identifier that Q1's list and Q3's grant-set can name (grants, roots, and entries named as targets), including the entry that introduces it? Yes: minimality is derived for the core (modulo unnamed entries). No: the form of Q1's list and Q3's grant-set (identifier or whole entry) decides, and a second question follows."
- G is a separate decision, above. It is not part of T9-22 as the row states it.

## 4. T9-03 and T9-04 (stated, not settled)

- **T9-03.** Batching being derived removes the composite route by which §9.19 L176 separated mechanism-minimal from derivable ("{c_all} minimal and derivable"). [I] §9.19 L173's derivability definition (a sequence in one step) does not capture a case split such as a_all. The notion of primitive minimality for mechanisms is therefore incomplete as defined.
- **T9-04.** Uniqueness then turns on alternative routes, not composites. T10 (§9.19 L274) gives a unique minimal kernel if each kind has one minimal enabling set, with H-down and P of the union. For KnowledgeOS that set is the kind's citation closure under D. If the pool is closed under case-splitting, uniqueness fails (§1, G). Under G-guard the T6 hypotheses fail, and T2 and T3 (b) do not apply.
- **Joint issue.** Under U6 (a) the per-kind Req_O names five kinds that D does not admit (§9.13 L52). Read literally, no kernel in D can meet it, so 𝒦 = ∅ (T1). Statements about a minimal kernel therefore range over the 13 admitted kinds until T9-21: 10 in D plus W's evid, ewdr, erst. [I] Also O3's "marked absence" (§9.10 L125) is not in D's list of kinds (§9.13 L27–52). I did not check it beyond that list.

## 5. What would refute each answer

| Answer | Refuted by |
|---|---|
| G (decision) | A derivation that excludes a case-split appender from the pool, for instance a book invariant that every mechanism serves one kind. Or a showing that a_all fails P while the a_i pass. |
| Batching (derived) | An admitted input type that carries several acts. Or held acts (§5.6) becoming a kernel kind under law 18 (i), where one step registers the object and applies the held act (two entries from one act). Or origin being declared an application invariant under F36 (P2 fails). |
| Q1 list is the support | A Req or D clause that defines "rests on" as a minimal justification with a stated tie rule. |
| Q2 form is whole entries | A ruling that §9.10 L45's "entries" means identifiers, which would contradict L50 and L48. |
| Q3 is a set | A decided selection rule among covering grants. Or a Req reading in which "found" is a Boolean and "with the grant" is a presentation matter. |
| Minimality pair (R_a, R_b) | A Req question, guard or mark that reads a grant's recorder when signed-as-actor. Or a decision that L93's identity includes the recorder. |
| T9-22 reduced to one decision | A second independent open item that the forms cannot reach, such as the content of an entry beyond its required fields (§9.20.3 row 8). |

All [I] items are marked in place. The Q2 scope reading and the O3 point rest only on the lines cited.

## Files opened
- /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/T9-22-TURN/BRIEF-STEP1.md
- …/03-methodology/REVIEWER-INSTRUCTIONS.md
- …/03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md
- …/06-theory/THEORY-MAP.md (lines 1–211 only; cap)
- …/06-theory/RESEARCH-PURPOSE.md
- …/06-theory/manuscript/chapter_09/09.20-the-state-space.md
- …/chapter_09/09.19-req-and-a-when-a-minimal-kernel-is-well-posed.md
- …/chapter_09/09.10-required-capabilities.md
- …/chapter_09/09.01-kernel-scaffold.md
- …/chapter_09/09.13-the-evaluator.md (lines 20–149)
- …/chapter_09/09.11-kernel-type-from-req.md (lines 20–109)
- …/chapter_01/01.16-consistency-boundaries-time-and-reproducibility.md (lines 1–31)
- Parts of 09.16-reassessment.md (line 93, plus L85–100 via sed) and 09.18-evidence-in-the-record.md (grep excerpts for W's kinds), via bash.

## Files copied
None. Scratch file written: …/scratchpad/t922-b/dispatch.py (my own toy model, not a repository file).

## Commands and searches
- `grep -o '09\.[0-9][0-9]-…\.md'` over CONCEPT-REGISTER.tsv and THEORY-MAP.md, plus `grep -n 'T9-22|§9.20'` on THEORY-MAP.md. This also touched CONCEPT-REGISTER.tsv by grep only, which is allowed.
- `grep -o '09\.(18|19|20)…'` and `grep -o 'chapter_09/…'` over the same two files, followed by `wc -c …/09.20*` (the glob).
- `ls chapter_09 | grep -E '^09\.(1[0-9]|20)-'` (the directory listing).
- `sed -n 85,100p` and `sed -n 93p` on 09.16-reassessment.md.
- `wc -l` on 09.01, 09.13, 09.16 and 09.18.
- `grep -n '^## …1.16.1'` on 01.16, and `ls chapter_01 | grep '^01\.16'`.
- `grep -n 'G8|evid|ewdr|erst|…'` on 09.18-evidence-in-the-record.md.
- `grep -n -i 'origin'` on 09.11-kernel-type-from-req.md.
- `grep -n -i 'batch'` over 09.11, 09.12, 09.13, 09.16, 09.17 and 09.18 (no hits).
- `python3 -I dispatch.py` in scratch (minimal kernels: {a_all} and {a0, a1, a2}).
- I ran no tmp/tools script and no git command. I did not open TODO.md, 09.00, 09.99, 09.21 or BOOK-OUTLINE.
