"""THE LENS/DOMAIN CATEGORY CHECK  [PROP]

Rule (as generalized by the reviewer):
  A lens may observe, transform or interpret a domain object only according to its
  declared contract; it must NOT become the domain object merely by appearing in an
  equation that names that object.

Statically checkable:  reject  DomainObject = f(Lens)  unless the lens has been
promoted by an independent architectural decision.
"""
import re, os, json

# derived from the corpus (occurrence counts in the brainstorming tree)
LENS_REGISTER = {
  "Zero":    1136+126, "Lord": 395+12, "Krishna": 196+2, "Yoni": 56,
  "Sārathi": 44+5, "Sarathi": 3, "Buddhi": 1, "Linga": 0,   # Linga: proposed, never "Lens"-suffixed
}
LENSES = [l for l in LENS_REGISTER]

# domain objects the theory declares (v1.2 / v1.1 vocabulary)
DOMAIN = ["KnowledgeOS", "Kernel", r"\\mathcal K", "K_t", "KnowledgeState",
          "EpistemicState", "KnowledgeAggregate", "Determination", "Evidence"]

LENS_ALT = "|".join(map(re.escape, LENSES))
DOM_ALT  = "|".join(DOMAIN)

# LHS is a domain object, RHS mentions a lens
EQ = re.compile(rf"(?P<lhs>{DOM_ALT})\s*(?:=|\\equiv|≡|:=)\s*(?P<rhs>[^\n$]{{0,140}})")

def scan(root):
    hits=[]
    for dp,_,fns in os.walk(root):
        for fn in fns:
            if not fn.endswith(".md"): continue
            p=os.path.join(dp,fn)
            try: t=open(p,encoding="utf-8",errors="replace").read()
            except Exception: continue
            for m in EQ.finditer(t):
                rhs=m.group("rhs")
                found=[l for l in LENSES if re.search(rf"\b{re.escape(l)}\b", rhs)]
                if found:
                    line=t[:m.start()].count("\n")+1
                    hits.append(dict(file=os.path.relpath(p,root), line=line,
                                     lhs=m.group("lhs"), rhs=rhs.strip()[:110],
                                     lenses=found))
    return hits
