r"""KR-ZOOM-02 — INQUIRY-ZOOM on the Nexus liquidity/egress domain.

THE CORRECTION THIS EXPERIMENT ENCODES (research owner, 2026-09-04):

    Zoom(K_t, Q, A) -> K_t^Q      is a VIEW over K_t. Context is PRESERVED.
    NOT  Zoom(D, d_i)             = delete everything except the anchor.

    ┌──────────────────────────────────────────────────────────┐
    │   Inquiry focus  !=  Knowledge boundary                    │
    │   The anchor says WHAT WE ARE INVESTIGATING,               │
    │   not WHAT WE ARE ALLOWED TO SEE.                          │
    └──────────────────────────────────────────────────────────┘

Two movements are supported, and the experiment measures both:
    FOCUS      K_t -> K_t^Q          attention narrows to the inquiry
    EXPANSION  K_t^Q -> K_t^{Q,+}    the working set WIDENS when evidence points elsewhere
"""
import random
from dataclasses import dataclass
from typing import Dict, List, Tuple, Optional

DIMENSIONS = ("repositories","users","storage","network","cpu_ram","backups","security",
              "traffic","ci_cd","config","monitoring","external_api","scheduled_jobs","auth")

# candidate explanatory factors per dimension (the investigation space H_Q)
FACTORS = {
 "repositories":["docker_pulls","maven_artifacts","npm_packages","proxy_cache_miss"],
 "users":["new_team_onboarded","service_account_loop","bulk_export"],
 "storage":["blob_rebalance","gc_rewrite","tiering_move"],
 "network":["egress_spike","peering_change","nat_gateway_route"],
 "cpu_ram":["gc_pressure","cache_evictions"],
 "backups":["full_backup_daily","offsite_replication","retention_change"],
 "security":["vuln_scanner_sweep","cert_rotation"],
 "traffic":["client_retry_storm","health_check_flood"],
 "ci_cd":["pipeline_config_change","cache_disabled","matrix_expansion","nightly_rebuild"],
 "config":["ttl_set_to_zero","proxy_disabled","mirror_removed"],
 "monitoring":["metric_scrape_interval","trace_sampling_100pct"],
 "external_api":["third_party_sync","webhook_replay"],
 "scheduled_jobs":["cron_overlap","reindex_job"],
 "auth":["token_refresh_storm","sso_probe"],
}

@dataclass(frozen=True)
class Link:
    """One evidence link: 'factor A in dim A is explained by factor B in dim B'."""
    src_dim: str; src_factor: str; dst_dim: str; dst_factor: str

@dataclass(frozen=True)
class Case:
    anchor_dim: str                    # where the anomaly is OBSERVED  (e.g. network)
    anchor_factor: str                 # the anomaly                    (e.g. egress_spike)
    chain: Tuple[Link, ...]            # ground-truth causal chain, anomaly -> root cause
    root_dim: str; root_factor: str    # the TRUE cause
    decoys: Tuple[Link, ...]           # plausible-but-wrong links, same shape as real ones
    dims_present: Tuple[str, ...]

def generate(seed: int, n: int, max_chain: int = 4) -> List[Case]:
    """Ground truth is GENERATED and auditable. The chain may cross dimensions -- how often
    it does is a GENERATOR PROPERTY, reported as such, never presented as a discovery."""
    rng = random.Random(seed); out = []
    for _ in range(n):
        a_dim = "network"; a_fac = "egress_spike"
        L = rng.randint(1, max_chain)
        chain, cur_d, cur_f = [], a_dim, a_fac
        for _ in range(L):
            nd = rng.choice([d for d in DIMENSIONS if d != cur_d]) if rng.random() < 0.75 else cur_d
            nf = rng.choice(FACTORS[nd])
            chain.append(Link(cur_d, cur_f, nd, nf)); cur_d, cur_f = nd, nf
        # decoys: wrong links hanging off dimensions the investigator will meet
        decoys = []
        for _ in range(rng.randint(3, 8)):
            sd = rng.choice(DIMENSIONS); dd = rng.choice([d for d in DIMENSIONS if d != sd])
            decoys.append(Link(sd, rng.choice(FACTORS[sd]), dd, rng.choice(FACTORS[dd])))
        out.append(Case(a_dim, a_fac, tuple(chain), cur_d, cur_f, tuple(decoys), DIMENSIONS))
    return out

# ------------------------------------------------------------------ the two operators
def _links_from(c: Case, dim: str, factor: str) -> List[Link]:
    """Evidence available at (dim, factor): the true link plus same-shaped decoys."""
    real = [l for l in c.chain if l.src_dim == dim and l.src_factor == factor]
    fake = [l for l in c.decoys if l.src_dim == dim and l.src_factor == factor]
    return real + fake

def investigate(c: Case, mode: str, budget: int, order_seed: int) -> dict:
    """mode = 'RESTRICTION'  -- the KR-ZOOM-01 operator: the anchor dimension IS the boundary.
       mode = 'INQUIRY'      -- the corrected operator: working set EXPANDS on evidence,
                               context K_t is never discarded.
    Both spend the SAME probe budget and use the SAME exploration RNG: a paired design."""
    rng = random.Random(order_seed)
    working = {c.anchor_dim}                       # K_t^Q -- attention, not deletion
    frontier = [(c.anchor_dim, c.anchor_factor)]
    probes = 0; expansions = 0; visited = set()
    while frontier and probes < budget:
        rng.shuffle(frontier)                      # exploration ORDER (Z5 varies this)
        d, f = frontier.pop()
        if (d, f) in visited: continue
        visited.add((d, f))
        if d == c.root_dim and f == c.root_factor:
            return {"found": True, "probes": probes, "expansions": expansions,
                    "working_set": sorted(working), "mode": mode}
        probes += 1
        for l in _links_from(c, d, f):
            if l.dst_dim not in working:
                if mode == "RESTRICTION":
                    continue                       # <-- the boundary. cause is unreachable.
                working.add(l.dst_dim); expansions += 1
            frontier.append((l.dst_dim, l.dst_factor))
    return {"found": False, "probes": probes, "expansions": expansions,
            "working_set": sorted(working), "mode": mode}

# ------------------------------------------------------------------ Zero, with Q FIXED
def zero_for_inquiry(c: Case, dim: str, reachable_working: set) -> bool:
    """Zero_Q(d): does removing dimension d change what the inquiry can determine?
    Q = Cause(egress_spike) is held FIXED. Only the evidence state changes."""
    if dim not in reachable_working: return True          # outside the view -> no effect yet
    return not any(l.dst_dim == dim or l.src_dim == dim for l in c.chain)
