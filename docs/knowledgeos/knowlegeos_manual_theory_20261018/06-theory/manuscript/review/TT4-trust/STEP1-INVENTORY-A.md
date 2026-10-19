*Reviewer A's (Opus) step-1 inventory, theory turn 4, verbatim (saved by the Professor, 2026-10-19).*

# STEP 1 INVENTORY — TT4 (reviewer A)

Blind step, topic review. I read three sources whole: S0913 (1806 lines), S0973 (2069 lines) and S1500 (317 lines; read in two parts because of size). I read nothing under W/06-theory/ and not RESEARCH-PURPOSE.md. Line numbers are counted separately for each file. Judgments marked [I] are mine. My independence is process independence: I am a separate reader, not a different person.

## S0913 — Step 25U, multi-agent knowledge, conflict, consensus, trust (ranked)
1. **Trust depends on context and time, and is not one score per agent.** The source rejects `Trust(Agent)=0.9` and replaces it with `Trust(a,d,c,t)`: agent, domain, context, time [S0913 L120-159]. The party doing the trusting is left implicit, so this is the system's own trust [I].
2. **Trust is not authority, and trust never substitutes for authority.** Expertise ≠ Authority and Trust ≠ Authority [L163-199]. "Trust never substitutes for authority" is boxed as an architectural invariant [L1381-1403]. Test E covers it [L1568-1582]. [I] Only one direction is argued (trust does not imply authority). Whether authority implies trust is never discussed.
3. **Trust changes how evidence is weighed. It creates neither evidence nor truth** [L1407-1430]. A trust score does not select what is true, because a higher-trust agent may be repeating the lower-trust agent's source [L564-597]. [I] This is the source's working definition of what trust does: it acts as a weight on evidence.
4. **A trust assessment is a derived knowledge object.** It carries evidence, evaluation period, domain, method, uncertainty and revision history, "so trust does not become magic metadata" [L756-804]. [I] This is the strongest statement in the corpus that trust is derivable.
5. **Reliability is not trust.** The example is an architect who is trustworthy but whose recollection of an IP address is unreliable [L203-225]. Here "trust" means sincerity or integrity, and reliability means accuracy for one question type [I]. **Internal merge:** the worked example of a *trust* assessment in §25U.23 is a *ReliabilityAssessment* (a false-positive rate) [L766-779]. So the source joins back together, at L766, the two concepts it separated at L203.
6. **Competence(a,q) per question type** is called "much more meaningful than a global trust score" [L679-700]. Reputation, measured as calibration, accuracy or error rate, feeds `Reliability(a,q,t)` and must be evidence-based and versioned [L704-732]. [I] That gives three near-synonyms (Trust(a,d,c,t), Reliability(a,q,t), Competence(a,q)) with no stated relation between them.
7. **Reputation can feed back on itself.** Trust must not reinforce itself recursively without independent validation [L736-752]. [I] This is a key argument that trust cannot be computed entirely inside the system: it needs a validation source from outside.
8. **Agent independence is not information independence.** Corroboration is counted in independent information, not in agents [L229-282, L1507-1518]. Consensus is taken over independent evidence clusters [L874-892].
9. **Agent ≠ Evidence.** Agents produce evidence, and this is recorded as `ProducedBy(E)` provenance [L40-74].
10. **The Actor tuple** is `(ActorID, Type, Capabilities, AuthorityScopes, Provenance)` [L1290-1317]. Capability ≠ authority [L1321-1347]. Epistemic permissions such as CanObserve, CanAssert and CanApprove are separate privileges [L1351-1377].
11. **TrustPolicy is domain-specific, not infrastructure** [L1256-1286]. The quorum `k` is "a domain policy, not a universal epistemic law" [L896-928].
12. **Trust is placed as a cross-cutting dimension** (Time, Context, Provenance, Authority, Trust, Uncertainty), not as a stage in the main chain [L1736-1741].
13. **Likelihood-ratio example.** LR₁ = 0.95/0.05 = 19, LR₂ = 15, product 285 "if independent". The source warns that the product must not be used when both depend on a common source [L1459-1503].
14. **Trust is not Byzantine consensus.** Evidence quality ≠ node count [L932-988].

## S0973 — Step 73, identity, trust, cryptographic provenance (ranked)
1. **Trust is derived, not a primitive.** `TrustAssessment(a) = f(Identity, Authenticity, Integrity, Authority, EpistemicSupport, Context, Time)` [S0973 L996-1014]. f is never defined, so this is a sketch [I]. The source rejects a single `trusted=true` flag [L1018-1047]. It also rejects one universal Trust aggregate or TrustObject as a god object [L1051-1077, L1107-1135].
2. **The kernel update adds Identity, not Trust.** The kernel becomes Artifact, Type, Identity, Provenance, Event, Invariant, Transformation, Policy; State is demoted to "operational projection" [L1703-1771]. The stated reason is "trustworthy provenance requires attribution" [L1727]. PKI and HSM stay in infrastructure. Trust is carried only as "TrustMetadata" on transitions [L1935-1950].
3. **Six concepts are kept apart:** identity, authenticity, integrity, authority, trust, provenance [L71-99]. Each of the following is separated from truth by an example: identity [L133-165], integrity [L235-256], authenticity in the signed-false-claim case [L261-297], and cryptography in general, "Authenticity is orthogonal to truth" [L898-953]. Also "Who said this?" ≠ "Why should we believe this?" [L1837-1855].
4. **Trust is relational:** `Trust(x,y,C,t)`, meaning how much confidence x places in y for context C at time t [L355-375]. [I] This conflicts with S0913's `Trust(a,d,c,t)`. Here the party that trusts is explicit and domain is folded into context: a homonym and an arity change.
5. **Trust is not transitive.** Trust(A,B) and Trust(B,C) do not give Trust(A,C) [L1390-1467]. [I] What the source shows is a rejected inference, not a model. Small model I checked by hand: let Trust(x,y,C) = High iff x's own validated record on y in C is good. A may have no record of C, so Trust(A,C) is Unknown and transitivity fails. The source does not discuss the standard alternative: referral trust, which propagates with weakening (discounting).
6. **Reliability is not trust.** Reliability = correct / evaluated outcomes, estimated from data. Trust "may additionally incorporate authority, security, provenance, policy, historical behavior" [L427-457]. 99% accuracy with a compromised identity does not give 99% trust [L461-482]. [I] Taken with S0913 item 5, non-implication is claimed in both directions, but only by stipulated examples. The frequency estimator is undefined at 0/0 and has no prior. Choosing a prior is a policy act.
7. **Cryptographic trust depends on key management.** The verification result holds "subject to the key-management assumptions" [L538]. After key compromise, signatures are technically valid [L542-596]. [I] This is the irreducible remainder: cryptography moves trust onto keys and roots. It does not remove trust.
8. **Identity has a lifecycle and time.** States are Active, Suspended, Revoked, Expired. A signature made before revocation can still be historically authentic, which requires IdentityValidityAtTime. "Trust must potentially also be time-indexed" [L600-698]. [I] There are two omissions. First, the signer's claimed signing time cannot be trusted after compromise (backdating), so this needs independent timestamping. Second, compromise time ≠ revocation time.
9. **Authority is a typed capability** `(Subject, Action, Resource, Scope, Validity)` [L1195-1216]. Delegation can only narrow scope: granting S1 ∪ S2 when holding only S1 is a violation [L1139-1190]. Least privilege [L1374-1386]. An AI agent with unrestricted rights is a governance failure [L1356-1370]. Trust in one domain does not transfer into authority in another [L407-423].
10. **Authority inside or outside trust is an internal tension.** In the layer model, Authority is placed "separately" [L986-992]. Yet Authority is an input to TrustAssessment [L1003-1011]. And "Trustworthy provenance = Identity + Authenticity + Integrity + Epistemic provenance + Context + Authority" [L1867-1902]. [I] Authority is a matter of permission. Making it part of whether provenance can be trusted mixes two categories, and it is in tension with S0913's Trust ≠ Authority.
11. **Trust-aware evidence assessment** is the tuple (Authenticity, Integrity, SourceReliability, ContextFit, TemporalValidity, Independence) [L1559-1577]. AuthenticEvidence ≠ ReliableEvidence, and ReliableSource ≠ CorrectEveryTime [L1609-1633].
12. **The trust graph G_T is kept separate** from the knowledge, provenance, dependency and authority graphs, and is not merged into them [L1471-1531]. [I] If trust is derived (item 1), G_T is a derived view, not stored data. The source does not say which it is.
13. **Tamper evidence, not blockchain** [L856-893]. Verifiable epistemic provenance = epistemic provenance plus cryptographic provenance [L1637-1661]. SemanticTrustBoundary: nothing becomes "TrustedKnowledgeState" just because an AI produced it [L1775-1797, L1801-1833]. [I] Here "trusted" means accepted knowledge, which is yet another meaning of the word.
14. **Source defects.** The Merkle formula `H_root = H(H_1..H_n)` [L838-852] is a flat hash list, not a tree. As written it does not give the membership check it promises. PASS and FAIL change meaning: Experiments 15 and 21 report "FAIL" [L1134, L1528] where the expectation was met, whereas elsewhere PASS means the expectation was met. All 23 experiments are expectations the author stamped PASS or FAIL. Nothing was executed (my reading; S1500 does not cover Step 73).

## S1500 — STEP-VERIFY 025h–025z, a verifier's report on S0913 and its batch (ranked)
1. **Nothing in the batch was executed.** Every "PASS" is the author's assertion [S1500 L21, L316]. That covers all 8 tests in S0913.
2. **Beta-reliability was dropped.** 025u re-derives an earlier authority family (A1–A15) without citing it and drops its Beta-reliability formalisation: "reliability here is qualitative" [L223]. [I] There is an earlier formal treatment of reliability that I have not read (reported, not shown).
3. **Two central arguments are confirmed VALID:** the rejection of weighted voting, and independent convergence over agent agreement [L225]. The reputation feedback loop is "a genuine failure mode with no proposed damper" [L225]. [I] That slightly overstates: S0913 L752 does state a damping *principle* (independent validation), though no mechanism.
4. **The likelihood-ratio product needs independence given H *and* given ¬H** (025n §18). 025u §45 "inherits" the error [L124, L222]. **Checked:** E₁ and E₂ independent given H with P = 0.9 and 0.6, and identical given ¬H with P = 0.1, give a product of 54 against a true joint LR of 5.4 (python). 19×15 = 285 is correct. [I] S0913 L1485 actually says only "if independent". That is vague rather than the H-only condition. Read as unconditional independence it would also be wrong.
5. **Keystones of 025u:** Trust ≠ Authority, Trust ≠ Truth, AgentCount ≠ EvidenceCount, and "Trust modifies epistemic weight" [L229]. Gap: whether one authoritative cluster beats k independent weak ones is undecided [L230].
6. **025y (not read; reported):** Integrity ≠ Authenticity ≠ Authority ≠ Truth, no blockchain, scalar trust score rejected, no threat model ("insider with key access defeats hash chains") [L276-285]. [I] Step 73 repeats this ground two hours later without citing it.
7. **Related keystones** from the same batch: "Evidence has no intrinsic universal weight", the six-way split Support/Reliability/Authority/Probability/Confidence/Independence [L131], Generation ≠ Validation ≠ Authority [L68], Replication ≠ Corroboration [L266], and HumanApproval treated as evidence, with HumanApproval ⇏ Risk=0 [L298].
8. **No numbered invariants, and symbol "A" collides** (agent, assertion, action) [L310]. This bears on the Trust(A,…) notation.

## Cross-source table (my analysis)
| Point | S0913 | S0973 | S1500 |
|---|---|---|---|
| Trust's arity | (a,d,c,t), truster implicit | (x,y,C,t), relational | repeats S0913 |
| Bearer of trust | agent | agent, artifact, key, knowledge state | — |
| Authority vs trust | separate, invariant | separate (layer 5) but also an input to f | confirms separate |
| Trust derivable? | derived knowledge object | f(...) sketch; not in kernel | — |
| Reliability | qualitative, versioned | frequency ratio | Beta model dropped |
| Remainder not derived | independent validation (L752) | key management (L538, L560) | insider key threat (via 025y) |

**Meanings of "trust" in these sources [I]:** (a) sincerity of an agent [S0913 L223]; (b) context-relative weight [S0913 L134]; (c) a relying party's overall confidence [S0973 L367]; (d) an assessment object [S0913 L790]; (e) cryptographic or key assurance [S0973 L557]; (f) accepted knowledge status [S0973 L1794]; (g) a boolean flag (rejected). Each use needs a qualifier.

**Trivial case [I].** Every trust law in these sources says what must never happen: no substitution for authority, no creation of truth, no transitivity, no self-reinforcement. A system that trusts nothing satisfies all of them. The only "must-do" content is in S0913 L704-732 (reputation from validated outcomes), S0973 L446-448 (the reliability ratio) and S0913 L1421 (trust adjusts weight).

## State lineage
- S0913 and S0973 are timestamped 2026-08-28 at 10:09 and 12:09, two hours apart. Both entered git in the same commit, f24edf316 (2026-08-28 13:46, git author: the Principal). By the corpus description both are AI-session brainstorming.
- They share an idiom: boxed "≠" laws and stamped experiments. S0973 does not cite S0913 (grep finds no 25U or 25N reference), yet it re-derives contextual trust, reliability ≠ trust, and no transfer of trust into authority.
- **So S0913 and S0973 count as one line of support, not two.**
- S1500 is a verifier agent's report (mandate 20260829_1956, committed 70fee73c8 on 2026-09-06). It is derived from S0913 and is a critique, not independent support. It points to an earlier, third line that I have not read (A1–A15 with Beta-reliability).

## Sketch: definition of trust
Trust_P(x, y, q, c, t) is the warrant that relying party x, under policy P, holds for treating y's statements of type q in context c at time t as evidence of a given strength. It is a pair (w, B):
- w = g_P(R(y,q,c,≤t), Auth(y,t), Integ, Indep);
- R is the validated outcome record, Auth is the authenticity or identity status at time t, Integ is the integrity of the item, and Indep is its independence;
- B is the set of evidence that justifies w.

Authority is excluded by definition: it governs what y may *do*, not how much y's statements count. The relation is non-transitive and not symmetric. It can change in either direction over time, through revocation or new outcomes.

## Sketch: independence test
1. Construct two states S₁ and S₂ that are identical in evidence, provenance, identity and key status, validated reliability history, policy and time, but in which Trust differs.
2. If no such pair can exist, trust is derivable, because g_P is a function of the other components.
3. **The candidate counterexample is a difference in anchors:** different accepted root keys, reliability priors, or the validator taken as ground truth (S0913 L752, S0973 L538). These cannot be derived from evidence without a regress.
4. So trust is derivable **iff anchor-acceptance is counted inside Policy.** In that case the open question moves: is accepting an anchor a policy act, under authority, or a separate primitive (a trust anchor or base assumption)?
5. Undecided on these sources.

