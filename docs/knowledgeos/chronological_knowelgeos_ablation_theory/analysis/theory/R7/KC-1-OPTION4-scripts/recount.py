"""KC-1 option 4 (Add): transcription check by the executor (values copied by hand from the sources below;
not an independent recount). No FZ encoding is used. Sources (quoted in KC-1-OPTION4-AMENDMENT-PROPOSAL.md r3 §1-§2):
  item sets      T3 r1.9 §1a lines 48-49 (CB1, CB23); C1 spans both under S only (rule 2a, reading S)
  E3-REL lists   E3 r1.3 line 111-113 (S, S'/S''), +R24 under D12 (b)
  K1c parse      KC-CONVENTIONS-PROPOSAL.md §2.4 item 4 (guard rows: asserted part of the statement)
Rule: guard rows (R11, R14-R17, R21) qualify iff their strict K1c asserted part meets CB1 and CB23.
Non-guard rows of E3-REL are classified row by row in the proposal §2 under the human's uniform test
(F-LOG-0316); that classification (every non-guard row qualifies) is transcribed, not derived here: the default
True in q.get(r, True) encodes it.
R22 and R24 follow the human's interpretations (F-LOG-0317); --alt prints the rejected alternatives."""
CB1 = {13, 14, 15, 16, 17, 18, 23, 24, 27}
CB23 = set(range(2, 13)) | {19, 20, 21}
E3REL = {"S": ["R2","R3","R5","R6","R8","R9","R10","R14","R15","R16","R19","R21","R22"],
         "S'": ["R2","R3","R9","R14","R15","R16","R21","R22"]}
E3REL["S''"] = list(E3REL["S'"])
K1C = {"R11": {3, 4, 6}, "R14": set(), "R15": {20}, "R16": set(), "R17": set(), "R21": {15}}
def side(items): return ("CB1" if items & CB1 else "") + ("+" if items & CB1 and items & CB23 else "") + ("CB23" if items & CB23 else "") or "none"
print("guard-row asserted parts (K1c):")
for r, it in K1C.items(): print(f"  {r}: {sorted(it)} -> {side(it)}; both sides: {bool(it & CB1 and it & CB23)}")
import sys
cases = [("selected: R22 yes, R24 yes", {})]
if "--alt" in sys.argv:
    cases += [("R22 yes, R24 no", {"R24": False}), ("R22 no, R24 yes", {"R22": False}), ("R22 no, R24 no", {"R22": False, "R24": False})]
for label, q in cases:
  print("==", label)
  for d12 in ("a", "b"):
    for rd, rows in E3REL.items():
        rows = rows + (["R24"] if d12 == "b" else [])
        keep = [r for r in rows if (r in K1C and K1C[r] & CB1 and K1C[r] & CB23) or (r not in K1C and q.get(r, True))]
        drop = [r for r in rows if r not in keep]
        print(f"D12 ({d12}) {rd:3}: E3-REL {len(rows):2}  asserted-content {len(keep):2} {keep}  excluded {drop}")
