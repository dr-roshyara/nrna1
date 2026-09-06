r"""KR-ZOOM-01 — domain, generator, and the typed operations.

METHODOLOGICAL CONSTRAINTS ENFORCED IN CODE (spec §2, §7, §8):
  * Projection != Zero.  P_S marks dimensions EXCLUDED. Nothing in this module ever
    writes a Zero verdict from exclusion; Zero requires zero_test() -- an intervention.
  * Zoom is a LOOKUP into generated, auditable structure. It never invents relations.
    Every exposed relation carries the parent id that generated it.
  * Atomicity is resolution- AND traversal-relative, defined by "no admissible
    non-trivial refinement", never by data type.
  * The four traversal directions are TYPED REGIMES. Nothing here assumes they are
    orthogonal, inverse, or commuting -- that is measured, not built in.
"""
import random, hashlib
from dataclasses import dataclass, field, replace
from typing import Dict, List, Optional, Tuple

DTYPES = ("INTERNAL", "CONTEXT", "HISTORY", "CONSEQUENCE", "EVIDENCE", "TEMPORAL", "PROVENANCE")
DIRECTIONS = ("INWARD", "OUTWARD", "RETROSPECTIVE", "PROSPECTIVE")
# which relation kind each direction generates -- used to test Zoom-vs-decomposition (§10)
REL_KIND = {"INWARD": "STRUCTURAL", "OUTWARD": "CONTEXTUAL",
            "RETROSPECTIVE": "HISTORICAL", "PROSPECTIVE": "CONSEQUENTIAL"}

# Q reads ONLY these dtypes. The others are candidates for Zero -- and may become
# operative after a Zoom exposes INTERNAL/EVIDENCE material beneath them. That is H3's
# mechanism, and whether it actually fires is generated, not guaranteed.
Q_READS = ("INTERNAL", "EVIDENCE")
BUCKETS = ((-10**9, -6, "CRITICAL"), (-6, -2, "STRESS"), (-2, 2, "WATCH"), (2, 10**9, "OK"))

@dataclass(frozen=True)
class Dim:
    id: str
    dtype: str
    name: str
    value: int          # signed contribution
    weight: int         # 1..3

@dataclass(frozen=True)
class Node:
    """A generated epistemic node: it CARRIES a state and knows its typed children."""
    id: str
    label: str
    dims: Tuple[Dim, ...]
    children: Dict[str, Tuple[str, ...]] = field(default_factory=dict)   # direction -> node ids
    parent: Optional[str] = None
    depth: int = 0

# ------------------------------------------------------------------ generator
NAMES = {
 "INTERNAL":   ["cash_balance","receivables","payables","debt_obligations","cash_burn"],
 "CONTEXT":    ["business_unit","company","banking_relationship","regulatory_env","market_ctx"],
 "HISTORY":    ["prev_cash_position","revenue_decline","delayed_receivables","debt_drawdown","prior_decision"],
 "CONSEQUENCE":["default_risk","emergency_financing","supplier_impact","restructuring","downstream_liq"],
 "EVIDENCE":   ["bank_statement","ledger_entry","auditor_note","invoice","contract_clause"],
 "TEMPORAL":   ["as_of_date","reporting_lag","maturity_window","seasonality","observation_time"],
 "PROVENANCE": ["source_system","entered_by","confidence_tag","revision_id","import_batch"],
}

class World:
    """The generated refinement forest. Every relation is explicit and auditable."""
    def __init__(self, seed: int, n_roots: int, max_depth: int = 4):
        self.rng = random.Random(seed); self.nodes: Dict[str, Node] = {}
        self.max_depth = max_depth; self.seed = seed
        self.roots = [self._mk_root(i) for i in range(n_roots)]

    def _nid(self, *parts):
        return "n" + hashlib.md5(("|".join(map(str, parts)) + f"|{self.seed}").encode()).hexdigest()[:10]

    def _mk_dims(self, key, k_min=3, k_max=6):
        dims = []
        chosen = self.rng.sample(DTYPES, self.rng.randint(k_min, min(k_max, len(DTYPES))))
        for dt in chosen:
            nm = self.rng.choice(NAMES[dt])
            dims.append(Dim(id=self._nid(key, dt, nm), dtype=dt, name=nm,
                            value=self.rng.choice([-4,-3,-2,-1,1,2,3,4]),
                            weight=self.rng.randint(1, 3)))
        return tuple(dims)

    def _mk_root(self, i):
        # GENERATOR CONSTRAINT (declared in DESIGN.md §5, not post-hoc tuning):
        # a root must be OBSERVABLE under Q, else there is no value to zoom from.
        # Deeper nodes carry no such constraint -- UNDETERMINED there is informative.
        nid = self._nid("root", i)
        dims = self._mk_dims(("root", i))
        if not any(d.dtype in Q_READS for d in dims):
            dt = self.rng.choice(Q_READS); nm = self.rng.choice(NAMES[dt])
            dims = dims + (Dim(id=self._nid("root", i, "forced", dt, nm), dtype=dt, name=nm,
                               value=self.rng.choice([-4,-3,-2,-1,1,2,3,4]),
                               weight=self.rng.randint(1, 3)),)
        self.nodes[nid] = Node(id=nid, label=f"liquidity_event_{i}", dims=dims,
                               children={}, parent=None, depth=0)
        self._expand(nid)
        return nid

    def _expand(self, nid):
        """Generate typed children. CONTROL A/C shapes arise here: some directions
        terminate. Termination is GENERATED, not decided at zoom time."""
        node = self.nodes[nid]
        if node.depth >= self.max_depth: return
        ch = {}
        for d in DIRECTIONS:
            # each direction independently may terminate -- this is what makes
            # atomicity traversal-relative rather than a property of the value
            if self.rng.random() < (0.30 + 0.12 * node.depth): continue
            k = self.rng.randint(1, 2); ids = []
            for j in range(k):
                cid = self._nid(nid, d, j)
                self.nodes[cid] = Node(id=cid, label=f"{node.label}/{d.lower()}{j}",
                                       dims=self._mk_dims((nid, d, j)), children={},
                                       parent=nid, depth=node.depth + 1)
                ids.append(cid)
            ch[d] = tuple(ids)
        self.nodes[nid] = replace(node, children=ch)
        for d, ids in ch.items():
            for cid in ids: self._expand(cid)

# ------------------------------------------------------------------ state / focus / observe
@dataclass(frozen=True)
class State:
    """K_r -- a knowledge state assembled from one or more generated nodes."""
    node_ids: Tuple[str, ...]
    dims: Tuple[Dim, ...]
    resolution: int
    origin: Optional[str] = None      # the anchor this state was zoomed from

def state_of(world: World, nid: str, resolution: int = 0) -> State:
    n = world.nodes[nid]
    return State(node_ids=(nid,), dims=n.dims, resolution=resolution)

def focus(K: State, S: Tuple[str, ...]):
    """P_S(K). Returns (active, EXCLUDED). NOTHING here is called Zero (spec §2)."""
    active   = tuple(d for d in K.dims if d.dtype in S)
    excluded = tuple(d for d in K.dims if d.dtype not in S)
    return active, excluded

def observe(active: Tuple[Dim, ...]):
    """x = Obs_Q(P_S(K)).  Returns (value, anchor_dim_id_or_None).
    The anchor makes Zoom TRACEABLE: we always know which dimension produced x."""
    reading = [d for d in active if d.dtype in Q_READS]
    if not reading: return ("UNDETERMINED", None)
    score = sum(d.value * d.weight for d in reading)
    label = next(b[2] for b in BUCKETS if b[0] <= score < b[1])
    # deterministic anchor: largest |contribution|, ties broken by id
    anchor = max(reading, key=lambda d: (abs(d.value * d.weight), d.id))
    return (label, anchor.id)

# ------------------------------------------------------------------ typed elimination + Zero
def eliminate(K: State, dim_id: str) -> State:
    """E^-_{d}(K). Typed: removes the dimension and re-establishes the state's invariant
    (dims tuple stays sorted-by-id, no stale references)."""
    kept = tuple(d for d in K.dims if d.id != dim_id)
    return State(node_ids=K.node_ids, dims=kept, resolution=K.resolution, origin=K.origin)

def zero_test(K: State, dim_id: str, S: Tuple[str, ...]) -> dict:
    """Zero_r(d | Q, C).  An INTERVENTION test -- the only thing in this codebase
    permitted to produce a Zero verdict."""
    a0, _ = focus(K, S);              v0, _ = observe(a0)
    a1, _ = focus(eliminate(K, dim_id), S); v1, _ = observe(a1)
    return {"dimension": dim_id, "intervention_method": "typed_elimination_E_minus",
            "observable_before": v0, "observable_after": v1,
            "observable_equal": v0 == v1, "zero_at_current_resolution": v0 == v1}

# ------------------------------------------------------------------ zoom
def zoom(world: World, K: State, anchor_dim_id: Optional[str], direction: str,
         resolution: int) -> Optional[State]:
    """Z_{Q,tau}(x_r) -> K_{r+1}, or None if no admissible refinement exists.
    Looks up GENERATED children only. Never invents structure."""
    if anchor_dim_id is None: return None
    host = None
    for nid in K.node_ids:
        if any(d.id == anchor_dim_id for d in world.nodes[nid].dims): host = nid; break
    if host is None: return None
    kids = world.nodes[host].children.get(direction)
    if not kids: return None
    dims = tuple(d for cid in kids for d in world.nodes[cid].dims)
    if not dims: return None
    return State(node_ids=tuple(kids), dims=dims, resolution=resolution, origin=anchor_dim_id)

def nontrivial(K2: Optional[State]) -> bool:
    """H1's success criterion: genuinely structured, not mere metadata.
    Requires >=2 dimensions AND >=2 distinct dtypes."""
    return K2 is not None and len(K2.dims) >= 2 and len({d.dtype for d in K2.dims}) >= 2

def atomic(world: World, K: State, anchor: Optional[str], direction: str) -> bool:
    """Atomic(x | r, tau): no admissible NON-TRIVIAL refinement for this query and
    traversal. Never defined by data type (spec §7)."""
    return not nontrivial(zoom(world, K, anchor, direction, K.resolution + 1))

def exposed_kinds(direction: str) -> str:
    return REL_KIND[direction]
