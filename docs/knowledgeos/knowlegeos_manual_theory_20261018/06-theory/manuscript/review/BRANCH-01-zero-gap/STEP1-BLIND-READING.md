BRANCH-01 step 1: blind reading of the "Zero" / "Gap" theory

Scope: I read only the line ranges listed in the access record and opened nothing else. A few things the passages refer to fall outside those ranges: §25D.13/.28/.39/.40, the "must not become" section, the metrization verdict, GN-/I-/AF- records, and Part I.2. I mark those as unverifiable here. My own judgments are marked [I].

## 1. Meanings of "Zero", by date and source
- **M1, research lens and neutral reference state (S0208, 2026-08-22).** L23: "Zero is a research lens, not a kernel primitive" (labelled "absolute", L21). L512–520: "zero is a neutral reference state before transformations occur". In that state identity exists, but semantic content, evidence, authority and temporal state are unresolved, and lifecycle is "created". L522 calls this explicitly "a hypothesis, not an architectural decision".
- **M2, absence/boundary lens (S0721, 2026-08-26).** L49: "Zero is a lens applied to the knowledge state". L60: Z(K_t) = "what the Zero Lens reveals about the boundary of K_t". It depends on K_t alone and is descriptive.
- **M3, computable gap operator (S0896, 2026-08-27).** L53: Zero "must compare requirements against epistemic state". L105: "Zero cannot be computed from the goal alone". L364: "a structured discrepancy". It is normative: it measures against what is required.
- **S1296 (later synthesis)** records three referents for the name (L221–233). Unqualified Zero means M3, the "L2 gap operator". Z-KOS-001 is the "ratified meta-principle", which roughly matches M1. The "absence-lens Zero" (M2) is "historical only". S1296 dates both the meta-principle and the absence-lens to 2026-08-22.

## 2. Definitions, with line anchors
- S0208 L512–520: the neutral reference state.
- S0721 L194–198: UNKNOWN ≠ ABSENT. Three cases (L206–222): Present, Absent (investigated and found absent), Unknown (not established). "A conventional database often collapses B and C into NULL. KnowledgeOS must not" (L226–232).
- S0721 L240–250: State(d) ∈ {Known, Unknown, Unresolved, NotAssessed, NotApplicable, Absent}. L252–256 then says this "should not automatically become a single enum" because these are "different epistemic axes". L290: Zero points toward "orthogonal semantic properties, rather than a single status field".
- S0721 L298–325: known dimension with unknown value, versus unknown dimension (d_new ∉ D_t). Called "one of the deepest Zero distinctions" (L296).
- S0721 L453–493: NO_EVIDENCE (Evidence(X)=∅, which does not imply X=False) ≠ INVALID_EVIDENCE (Evidence ≠ ∅ but Validity=False).
- S0721 L501–533: NOT_ASSESSED is a "coverage gap"; LOW_CONFIDENCE is an "epistemic uncertainty".
- S0721 L541–563: NOT_APPLICABLE ≠ UNKNOWN. NotApplicable means "evaluated and determined to be outside the relevant domain".
- S0896 L68–69: R_G = {r_1…r_n}, the required knowledge conditions induced by a goal G. L116: Epistemic Contract EC_G = (R_G, Γ_G), where Γ_G gives the sufficiency rules.
- S0896 L155–172: Sat(K_t, r_i) takes values in 𝒮 = {Satisfied, PartiallySatisfied, Unknown, Insufficient, Conflicted, Stale, Invalid, Prohibited, NotApplicable}. That is nine values, introduced with "Initially I propose". L202: Sat ≠ Boolean.
- S0896 L214–223: Z_i = Status(K_t, r_i, EC_G), and Zero = {(r_i, Z_i) | r_i ∈ R_G}, called a "Zero vector".
- S0896 L247–285: **Missing** is a separate status for "an expected governed artifact/action". Unknown is Evidence(r)=∅.
- S0896 L340–358: Zero = R_G \ Satisfied(K, R_G), stated as "only a shorthand". The full form is Zero = {Requirement, Status, Evidence, Reason, Dependencies}. L368–386 adds a per-item record that includes RequiredAction and DecisionCriticality.
- S0896 L526–546: operational Zero Z_W = Distance(W_t, W*). Epistemic Zero Z_K = Distance(K_t, K*_EC). Called "fundamentally different".
- S0896 L602–628: Zero = (Z_world, Z_knowledge, Z_governance, Z_decision), "at the conceptual level". L639–658: this is not a single number (5 + 1 ≠ "Zero = 6").
- S1296 L32–34: the ratified signature is Zero(K, EC); the earlier Zero(K, G, EC) is kept as lineage. The output is a "StructuredGapSet". Scope is gap detection only (L39–40).

## 3. Mathematical structure
- **Sets:** R_G; the dimension set D_t; 𝒮 (nine values, plus Missing). One set difference (R_G \ Satisfied).
- **Functions:** Sat or Status, a map from (K_t, r, EC) to 𝒮. Zero is a map from (K, G, EC) to a set of pairs, which is a status map over R_G. Evidence(·), Validity(·), Assessment(·) and Applicable(·) appear only as informal predicates.
- **Vectors:** a positional status vector (L231–240). A four-component tuple of sub-Zeros (L602–608).
- **Distances:** Distance(W_t, W*) and Distance(K_t, K*_EC). No metric is defined, yet "Z_W > 0" and "Z_K > 0" are asserted (L569, L585).

## 4. Kinds of "nothing" or absence that the passages distinguish
- Absent (investigated, not there) vs Unknown (not established). S0721.
- No evidence vs invalid evidence. S0721.
- Not assessed (coverage gap) vs low confidence (assessed but conflicting). S0721.
- Not applicable vs unknown. S0721.
- Unknown value vs unknown dimension. S0721.
- Unknown (no evidence) vs Missing (an expected governed artifact that does not exist). S0896.
- The neutral "unresolved" origin state. S0208.
- Kinds of not-satisfied: Insufficient, Stale, Invalid, Conflicted, Prohibited. S0896.

## 5. Claims and how strongly they are worded
- **S0208:** "absolute", "must never define KnowledgeOS architecture", "Do not convert metaphor into architecture", "Reject … Knowledge = numerical value / Trust = positive minus negative evidence / Kernel calculates truth" (L210–238, L591–600). It also says "kernel preserves, does not calculate" (L596). Its one positive claim is labelled hypothesis (L522).
- **S0721:** "first architectural principle" (L35), "most important" (L192), "completely different" (L224), "KnowledgeOS must not" (L232), "deepest" (L296), "must remain separate" (L493), "entirely different" (L529).
- **S0896:** "insufficient" (L28), "crucial" (L103), "Initially I propose" (L158), "I recommend" (L277, L600), "computationally representable" (L243), "only a shorthand" (L349), "already implementable" (L388), "fundamentally different" (L548), "much stronger" (L630), "No." (L654).
- **S1296:** "Definition", "ratified", "canonical", "exactly when" (L186), "precisely" (L186), "never a number" (L166), "a metric may summarize Zero; it may never BE Zero" (L181). It quotes the source calling no-invented-gaps "one of the strongest governance properties we have discovered" (L198; the source line is outside my ranges).

## 6. Contradictions and tensions
1. **Lens versus operator** [I]. S0208 says Zero is a lens, not a kernel primitive, and forbids turning metaphor into architecture (L23, L29). Four days later S0721 calls Zero-derived points "architectural principle[s]". Five days later S0896 calls it "computationally representable" and "already implementable". S1296 then treats it as a ratified operator. This is exactly the lens-to-architecture move S0208 forbade. S1296 handles it by splitting the name into three referents. That is honest, but the gap operator is still what carries the name forward.
2. **Single enum versus orthogonal axes.** S0721 warns that the status list "should not automatically become a single enum" (L252–290). S0896's 𝒮 is a single nine-or-ten-value enum that mixes several axes: evidence existence, sufficiency, time (Stale), validity, governance (Prohibited, Missing) and applicability.
3. **The two status sets do not line up** [I]. S0721's Absent, NotAssessed, LowConfidence and Unresolved have no counterpart in 𝒮. S0896 L256–262 maps Evidence=∅ to Unknown. That merges "not assessed" with "assessed, nothing found", which is the very collapse S0721 L226–232 forbids.
4. **No status for "known to fail"** [I]. S0896 L189 itself lists "we know it is false" as one meaning of Sat=False. Yet 𝒮 has no "known not satisfied" value, for example a rollback test that ran and failed. Missing only covers governed artifacts.
5. **Unknown dimensions are excluded** [I]. S0721's "deepest" distinction is the unknown dimension (d_new ∉ D_t). S0896's operator compares only against a declared R_G, and S1296 §10 forbids reporting undeclared requirements ("no invented gaps"). So M3 by construction cannot represent M2's deepest case. S1296 does not note this loss within my ranges.
6. **Full status map versus gap set.** S0896 L221–223 defines Zero over all of R_G, Satisfied items included. L326–330 and L340 then define it as the not-satisfied subset. The set-difference shorthand would also report NotApplicable requirements as gaps.
7. **Distance versus no scalar.** S0896 defines Z_W and Z_K as Distance(…) and asserts "> 0" (L526–585), which presupposes a metric or an order. L634–658 then rejects a single number. The four-tuple (L602–608) is also not orthogonal to the per-requirement vector: r_7 GovernanceApprovalObtained already sits inside R_G, and Z_world is not epistemic even though L53 says Zero compares against epistemic state.
8. **Detection versus planning.** S1296 L39–40 and L208–210 say Zero is gap detection only and never plans. But S0896's own Zero item carries RequiredAction ("perform restore test", L381–382) and Z_decision (L626–628), which is planning or decision content inside Zero.
9. **"No scalar" versus "Zero".** The name invokes a numerical origin. S0208 rejects scalar readings. M3 nonetheless reintroduces distances. [I]

## 7. Where S1296 adds to or changes the sources
- **Changes the signature** to Zero(K, EC), dropping G (L32–33). It admits this is not proven minimal (OQ-1, L295–297). [I] The change is plausible because EC_G is already indexed by G.
- **Miscounts the statuses** [I, a definite error]. S1296 says four ratified arms "summarize nine source statuses" (L140). Its own table (L143–149) maps the "missing" arm to **Missing**, which is not one of the nine in 𝒮. Its table therefore covers ten source statuses. The §16 "completeness of the nine-status set" (L303) also leaves Missing out.
- **Adds interpretation not in the source.** "An absent approval is … a determinate institutional fact" (L128–129), and the "echo" of unknown ≠ absent ≠ false (L130–132, tagged [IN]). [I] This leaves a weakness unaddressed: telling Missing ("no approval exists") apart from Unknown ("no evidence of an approval") requires knowing that the approval does not exist, and neither document says how.
- **Strengthens a source claim.** The source says one missing approval "could be more blocking than five low-priority informational gaps" (S0896 L658). S1296 L170–171 turns this into "can block everything while five informational gaps block nothing".
- **Adds cross-chapter mappings** (L185–191): Conflicted "exactly when" there is (S⁺, S⁻); Stale tied to criterion H; Invalid "retroactively". None of this appears in S0896 within my ranges.
- **Adds governance context:** I-2, I-9 ("four-way"), AF-F-9, A6, OQ-4, PF-1/PF-5 (L157–164, L210–219). It discloses openly that the four-arm compression sits inside the text of invariant I-9. I could not verify any of these records.
- **Keeps operational Zero** as source content "promoted nowhere" (L214–219). This agrees with the source.
- **Dating does not match what I read** [I]. S1296 L229–230 dates the absence-lens to 2026-08-22. The absence-lens passages I read (S0721) are dated 2026-08-26. The 08-22 source I read (S0208) contains the neutral-reference sense, not UNKNOWN ≠ ABSENT. An earlier absence-lens text may exist outside my access, so this needs checking in step 2.

## 8. Weak or invalid points [I]
- **No empirical evidence.** Every distinction is argued from constructed Nexus examples. These show that the distinctions are coherent, not that they are needed or that the set is complete.
- **Undefined terms.** The status values have no definitions beyond prose: Unknown vs Unresolved (S0721), Insufficient vs PartiallySatisfied, and Prohibited as a "satisfaction" value are not separated. NotApplicable inside a goal-induced R_G is odd: why would a contract require something that does not apply?
- **Distance is never defined,** so "Z > 0" has no content as written.
- **"Zero ≠ simple subtraction"** (S0896 L49) is followed by a set-difference definition. It is self-labelled a shorthand, but it is still a subtraction.
- **Excess numbering.** The 5 + 1 = 6 argument shows the items are not commensurable. It does not justify the four-component split, whose components overlap.
- **Unverifiable ratification.** Calling the result "ratified" (S1296) rests on records I cannot see. Within my ranges, the sources themselves say "propose", "recommend" and "hypothesis".

## 9. What survives this reading [I]
- The pairwise absence distinctions in S0721 hold as conceptual distinctions.
- Sat ≠ Boolean holds.
- "Not a single scalar" holds.
- Zero as a requirement-relative structured gap set holds as a proposal: coherent, never tested, with an incomplete status set.
