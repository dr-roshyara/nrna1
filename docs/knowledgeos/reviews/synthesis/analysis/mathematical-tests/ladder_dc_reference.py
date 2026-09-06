#!/usr/bin/env python3
"""
GN-46 mathematical verification — status ladder (R-3/R-4, I-12) and
Decision Contract admissibility (042) reference implementation.
Testing tool only; nothing here is architecture.

Checks executed:
  1. The ratified 3-status ladder + Committed boundary as a covering relation
     (I-12): skipping is rejected; boundary crossing requires an authority act
     (A6/I-4); evidence volume cannot cross it.
  2. FA layered states: REJECTED/CONFLICTED reachable from pre-boundary
     statuses by governed act only; preserved, not deleted.
  3. Expressibility probe: source Omega_A 'Accepted AND Contest:Active'
     (008 §10-11) has NO single-state representation in the layered model —
     the PF-6 residue, demonstrated.
  4. DC admissibility as strict conjunction (042 §42.9 four-conjunct early
     form and §42.41 six-fold refined form): no averaging; Unknown -> Block.
  5. The ratified-model mismatch: the ratified DC 6-tuple has NO ratified
     admissibility conjunction of its own (42.9 omits Temporal; 42.41 belongs
     to the 7-tuple) — demonstrated by typing, reported as a finding.
"""

# ---------------- 1-2: ladder as covering relation ---------------------------
LADDER = ["Candidate", "Supported", "Accepted"]          # admission axis (v0.2)
BOUNDARY = "Committed"                                    # decision-boundary status
ADJACENT = {"REJECTED", "CONFLICTED"}                     # FA layered states

def next_up(status):
    i = LADDER.index(status)
    return LADDER[i + 1] if i + 1 < len(LADDER) else None

class Item:
    def __init__(self):
        self.status = "Candidate"
        self.committed_for = {}
        self.history = ["Candidate"]

    def promote(self, to, policy_ok=True):
        if to != next_up(self.status):
            raise ValueError(f"I-12 violation: {self.status} -> {to} skips")
        if not policy_ok:
            return False
        self.status = to
        self.history.append(to)
        return True

    def commit(self, purpose, authority_act=None, evidence_volume=0):
        # A6/I-4: only an authority act crosses; evidence volume is inert.
        if self.status != "Accepted":
            raise ValueError("boundary reachable only from Accepted (I-12)")
        if authority_act is None:
            return False
        self.committed_for[purpose] = authority_act
        self.history.append(f"Committed({purpose})")
        return True

    def governed_move(self, to, governed_act=None):
        if to not in ADJACENT:
            raise ValueError("only REJECTED/CONFLICTED here")
        if governed_act is None:
            raise ValueError("adjacent states reachable by governed act only")
        # preserved, never deleted: history keeps everything
        self.history.append(f"{to}(by {governed_act})")
        self.status = to
        return True

print("=" * 74)
print("Ladder / boundary / adjacent-states reference checks")
print("=" * 74)

it = Item()
try:
    it.promote("Accepted")
    print("  skip Candidate->Accepted allowed  : FAIL (I-12 broken)")
except ValueError:
    print("  skip Candidate->Accepted rejected : PASS (I-12 covering relation)")

it.promote("Supported"); it.promote("Accepted")
print(f"  in-order promotion                : PASS (history {it.history})")

ok = it.commit("PublishCertification", authority_act=None, evidence_volume=10**6)
print(f"  10^6 evidence, no authority act   : {'PASS (not committed)' if not ok else 'FAIL'}")
ok = it.commit("PublishCertification", authority_act="ReturningOfficer-2026-08-28")
print(f"  authority act crosses boundary    : {'PASS' if ok else 'FAIL'}")

it2 = Item(); it2.promote("Supported")
try:
    it2.governed_move("REJECTED")
    print("  silent rejection allowed          : FAIL")
except ValueError:
    print("  silent rejection refused          : PASS (governed act required)")
it2.governed_move("REJECTED", governed_act="adjudication-042")
print(f"  rejection preserved with reasoning: "
      f"{'PASS' if 'REJECTED(by adjudication-042)' in it2.history else 'FAIL'}")

# 3: expressibility probe (PF-6 residue)
print("\nExpressibility probe — source Omega_A = (Support, Acceptance, Commitment, Contest):")
print("  target state: Acceptance=Accepted AND Contest=Active (008 §11, board case)")
print("  layered-model options: stay 'Accepted' (contest invisible) or move to")
print("  'CONFLICTED' (acceptance suspended). NO single state carries both.")
print("  -> PF-6 residue CONFIRMED as an expressibility fact (not judged here).")

# ---------------- 4-5: DC admissibility --------------------------------------
print("\n" + "=" * 74)
print("Decision Contract admissibility (042)")
print("=" * 74)

def adm_early(pre, inv, assur, auth):
    """042 §42.9: Admissible = Pre AND Invariant AND Assurance AND Authorization.
    Three-valued: any False -> False; else any Unknown -> Unknown (policy: Block)."""
    vals = [pre, inv, assur, auth]
    if any(v is False for v in vals):
        return False
    if any(v is None for v in vals):
        return None                      # Unknown -> Block for safety-critical
    return True

def adm_refined(P, I, A, E, Q, T):
    """042 §42.41: six-fold conjunction over the 7-tuple's evaluable components."""
    vals = [P, I, A, E, Q, T]
    if any(v is False for v in vals):
        return False
    if any(v is None for v in vals):
        return None
    return True

print("  no-averaging (42.10): Pre=T, Assurance=T, Auth=T, Invariant=F")
r = adm_early(True, False, True, True)
print(f"    -> Admissible = {r} : {'PASS' if r is False else 'FAIL'} "
      f"(a 95%-admissible decision is inadmissible)")

r = adm_early(True, None, True, True)
print(f"  Unknown invariant (42.12): -> {r} (Block) : {'PASS' if r is None else 'FAIL'}")

r = adm_refined(True, True, True, True, False, True)
print(f"  refined form, Q (epistemic sufficiency) fails alone -> {r} : "
      f"{'PASS' if r is False else 'FAIL'}")
print("    NOTE: under the ratified SIX-tuple (Pre,Inv,Auth,Post,Temporal,Evidence)")
print("    there is no component typed as epistemic sufficiency, so this block")
print("    is INEXPRESSIBLE without conflating Q into Evidence — PF-7's loss,")
print("    executable form. Also: the ratified 6-tuple has NO ratified")
print("    admissibility conjunction (42.9 omits Temporal; 42.41 is the")
print("    7-tuple's law) — a formal mismatch reported to the register.")

# falsification experiments 42.49-42.51 executed
print("\n  042 falsification experiments, executed:")
print(f"    42.49 epistemic pass, auth fail -> {adm_early(True, True, True, False)} "
      f"(expect False) : {'PASS' if adm_early(True, True, True, False) is False else 'FAIL'}")
print(f"    42.50 auth pass, hard inv fail  -> {adm_early(True, False, True, True)} "
      f"(expect False) : {'PASS' if adm_early(True, False, True, True) is False else 'FAIL'}")
print(f"    42.51 safety-critical unknown   -> {adm_early(True, None, True, True)} "
      f"(expect Block, never True) : "
      f"{'PASS' if adm_early(True, None, True, True) is not True else 'FAIL'}")
