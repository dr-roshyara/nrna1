# Reference Geography tactical design v3: companion assessment

| | |
|---|---|
| **Status** | PROPOSED. Companion to `2026-11-09-reference-geography-tactical-design-baseline-v3.md`. Not committed |
| **Labels** | As in v3 |

## 1. What changed from v2

| Area | v2 | v3 |
|---|---|---|
| Knowledge order | `recordedAt` "database clock at commit" | **Publication position** in one platform sequence (a timestamp is not commit order) |
| Logs | Two aggregates (country, global) + a cross-country transaction | **One `PublicationLog`**; country and global are partitions; a cross-country change is one position |
| Identity | "One identity per official place" | **Four entity types; namespace-scoped identifier rules I-1 and I-2; I-3, I-4; reconciliation and its correction** |
| Claims | Kinds only | **Slots** (subject plus qualifiers); supersession per slot and per valid interval; explicit withdrawal |
| Belief | "Latest entry per place and kind" | **Belief(slot, t, τ)** by highest position |
| Mandate chain | Ends at a constitution | **Legal basis as each jurisdiction provides** |
| D-87 | Listed as open | **Integrated:** CLDR slots per locale and form, release provenance, inheritance, presentation rule |
| Coverage selector | In Geography | **Moved to Organisation**; Geography offers browse and `PlaceLookup` only |
| Governance | Roles implicit | **Preparer, first, second approver, matter reviewer defined;** conflict after publication handled |

## 2. Review findings R-1 to R-9

| Finding | Status in v3 |
|---|---|
| R-1 authority by claim kind | Resolved, and generalised (no universal chain) |
| R-2 identity | Resolved as far as enforceable: I-1 to I-4 plus reconciliation. **Duplicate detection stays a governed judgement** |
| R-3 interval validation | Resolved: derived read set (§5) |
| R-4 belief | Resolved with slots |
| R-5 approval freeze | Resolved; publication-time governance rules |
| R-6 conflict scope | Resolved: D-84a test applied to the deciding person |
| R-7 `Authority` | Withdrawn; registry claims published through the log |
| R-8 context relations | Resolved; coverage moved to Organisation |
| R-9 events | Resolved: one `RevisionPublished` per position |

## 3. Scenario results

**Abbreviations:** PL = `PublicationLog`; Rev = `Revision`; Gov = governance (`StewardRoster` plus approval rules). **G** = guaranteed by mechanism; **H** = depends on human governance assessment.

### 3.1 The original 15 (adversarial review §4)

| # | Scenario | Expected | Enforcer | G/H |
|---|---|---|---|---|
| 1 | Duplicate identity | Prevented if the identifiers collide (I-1); otherwise found by I-4's search and repaired by reconcile | PL; Rev | G for identifiers, **H for the real-world judgement** |
| 2 | Code change, entity continues | New value in slot (subject, namespace) | PL (I-1, I-2) | G |
| 3 | Code-assigning source without territorial competence | Only identifier claims accepted from it | Rev (basis by kind) | G |
| 4 | Statistical grouping taken as containment | Different slot kind; never containment | Rev; read models | G |
| 5 | Large revision | One position; validated per partition | PL | G (duration [TU]) |
| 6 | Two revisions on one state | Second `Stale` | PL | G |
| 7 | Second steward appointed after approval | Second approval required | PL plus Gov | G |
| 8 | Conflict found between approval and publication | Approval void | Gov | **H** (finding the conflict) / G (effect) |
| 9 | Late correction of a past date | Belief by slot and interval; children reassigned | PL (§5) | G |
| 10 | Dispute and change on one claim concurrently | Serialised; the later one sees the earlier and may be `Stale` | PL | G |
| 11 | Cross-country change | One position | PL | G |
| 12 | Snapshot across partitions | One sequence | PL | G |
| 13 | Organisation area submitted as official | No territorial competence → rejected | Rev | G (basis) / H (assessment) |
| 14 | Street as a place | Classification only on territorial tiers | Rev | G |
| 15 | Objection reconsidered | New objection with new evidence | `Objection` | G |

### 3.2 The 15 new scenarios

| # | Initial facts | Action | Invariant at risk | Expected | Enforcer | G/H |
|---|---|---|---|---|---|---|
| N1 | HK display (en, standard) = CLDR release r1 | CLDR r2 changes the name | History reproducibility | New claim in the same slot; belief at τ before shows r1 | Rev; PL | G |
| N2 | User locale ne-NP; no ne-NP form | Display | D-87 | CLDR inheritance ne → root | Display-name read model | G (CLDR data) |
| N3 | HK has ISO, UN and CLDR names | Show details | Names not overwritten | Separate slots by source and language | Claim model | G |
| N4 | Entity with ISO alpha-2, alpha-3, M49 | Any change to one | No interference | Separate namespaces | Claim model | G |
| N5 | CS held by Czechoslovakia to 1993 | Serbia and Montenegro gets CS from about 2003 | I-1 | Allowed: disjoint intervals; two subjects | PL | G |
| N6 | Districts X and X′ found to be the same entity | Reconcile | I-3, history | B → A from that position; history shows both | Rev; PL | **H** (judgement) |
| N7 | Reconciliation wrong | Correct it | History | Separating revision; both acts kept | Rev; PL | H |
| N8 | Unit has a name and an identifier | Correct only the name | Supersession scope | Only the name slot changes | Claim model | G |
| N9 | Retroactive change of A's identifier, colliding with B's in the same namespace and interval | Publish | I-1 outside the edited unit | Read set includes the namespace value → rejected | PL (§5) | G |
| N10 | A Germany revision and a global registry revision in flight together | Publish both | Order, atomicity | Serialised; two positions; each atomic | PL | G |
| N11 | A historical query during a publication | Query | Snapshot | Reads ≤ `closedThrough`; never half-committed | PL | G (commit-order assumption [TU]) |
| N12 | Second steward appointed between approval and publication | Publish | D-84 | Second approval required | PL plus Gov | G |
| N13 | Conflict discovered between approval and publication | Publish | D-84a | Approval void; non-conflicted approver needed | Gov | H / G |
| N14 | Organisation browses Geography for coverage | Select | Ownership | Geography supplies places; **Organisation decides and stores coverage** | Context boundary | G |
| N15 | Consumer asks for (asOf, τ) | Query | One τ | One sequence position for all partitions | PL | G |

## 4. New issues found in v3

- **Commit-order assumption** (§6 of v3): it rests on assigning and committing positions under one lock. **[TU]** Validate against the actual database behaviour.
- **Two naming gaps:** display names for the level-1 regions and for territorial units are not covered by D-87 (**[OQ]** ×2).
- **Conflict discovered after publication:** handled by re-assessment (an architectural conclusion from D-84a); no new rule invented.

## 5. Enforceable versus judgement

| Guaranteed by mechanism | Depends on human governance |
|---|---|
| Identifier rules I-1, I-2; identity never reused (I-3); I-4 evidence present | **Whether two identities are the same real entity** |
| Claim basis matches its kind | Authenticity and applicability of instruments |
| Valid-time invariants over the derived read set | Whether a conflict exists (D-84a) |
| All-or-nothing visibility; one τ; refusal beyond `closedThrough` | Source precedence where no recorded rule exists (disputed, never chosen) |
| Governance checks at approval and publication | — |

## 6. Technical uncertainties

- Commit order under the publication lock.
- Lock contention.
- Pre-validation followed by incremental re-validation for large revisions.
- Performance of the namespace and interval queries.

## 7. Readiness

**Ready for the Principal DDD Architect's independent assessment.**

**Two [OQ] naming questions** (regions; territorial units) **are outside D-87 and should be asked separately.** They do not block the model, because names are slots; they block only the display of those entities.
