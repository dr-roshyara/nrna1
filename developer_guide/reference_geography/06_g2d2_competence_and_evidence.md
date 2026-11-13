# 06: G-2d-2, territorial competence and the evidence basis (PBDIGIT-346)

**Design:** v3.11 §5 ("D-82: territorial claims need territorial competence"; "Evidence citing mandates and rules"); **D-82**; **D-227** (assessed at the instrument's issue/adoption date; disputes visible, never a refusal by themselves). Evidence: `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g2d2/`.

## In business terms

- **An official change to the map rests on an instrument** (an act, a decree, a gazette notice) from an authority with **territorial competence**.
- **That competence is checked on the day the instrument was issued or adopted.** A 1990 act stays valid even if its authority was abolished in 1995.
- **The mandates and precedence rules it cites must also have been in force that day.**
- **If someone disputes the authority relied on,** the dispute is shown. It does not on its own make the change invalid, and PublicDigit does not decide it.

## Code

| Element | Role |
|---|---|
| `EvidenceVersion(id, fingerprint, issuer, issuedOn, mandates, precedenceRules)` | an instrument records issuer **and** issue date together, and any mandates or rules it cites (which require the date). Non-instrument evidence carries none |
| `ValidDate::nextDay()` | pure calendar arithmetic (leap years included), for one-day read intervals |
| `SlotHistory::claimAt()` | the claim in force on a date (to find the relied-upon authority claim) |
| `RevisionValidator::validate($claims, $reader, $evidence)` | new rules `D-82`, `D-82_ISSUER_UNKNOWN`, `D-82_BASIS`, `EVIDENCE_MANDATE`, `EVIDENCE_PRECEDENCE` |
| `ValidationResult::$disputedReliances` | relied-upon authority claims whose dispute state is "disputed": **visible, in the digest, never a violation** |
| `Revision::validateWith()` | now passes the revision's evidence |

**Territorial kinds** (v3.11 §3 basis column "Territorial"): EXISTENCE, CONTAINMENT, CLASSIFICATION, BOUNDARY, SUCCESSION, PROFILE_*.

**Recorded reads, over [issue date, next day):**
- (COMPETENCE, issuer, territorial);
- (MANDATE, m) and (PRECEDENCE, r);
- the (DISPUTE, relied claim) of each.

## D-82_BASIS (a direct reading of D-82, flagged for review)

**A territorial claim must rest on an instrument**, since without an issuer no competence can be checked. **v3.11's own exceptions are kept:**
- a BOUNDARY from a cartographic source attributed as such (§3);
- EXISTENCE established by a duplicate-search assessment (I-4);
- withdrawals on a correction (D-228).

## Testing

`tests/Unit/Contexts/ReferenceGeography/Domain/CompetenceAndEvidenceTest.php` (10 tests), plus `tests/Support/ReferenceGeography/CompetentInstrument.php` (a competent test issuer).

**Fixture corrections** (recorded in the evidence; no rule relaxed):
- **The older validator and revision tests** had territorial claims on issuer-less instruments, so the new D-82 rule refused them. They now use the competent test instrument.
- **One dispute test** built two atlases with different competence-claim ids; that claim now has a fixed id.
- **The purity guard read the words "issue date (" in a message as a call to `date()`;** the message was reworded.

**Traceability:** PBDIGIT-346 G-2d-2 · v3.11 §3, §5 · D-82, D-227, D-228 · I-4.
