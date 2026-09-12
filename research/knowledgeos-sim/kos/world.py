"""§4 SIMULATION WORLD — latent reality the agent CANNOT read.

Oracle independence (§24): world truth is used ONLY by the evaluator, never by
any agent transition.  Any agent code touching `World.truth` is a smuggling bug;
the audit in kos/audits.py checks for it.
"""
import random
from dataclasses import dataclass, field

@dataclass
class Channel:
    """An acquisition mechanism.  A property with NO channel is UNOBSERVABLE."""
    id: str
    prop: str
    reliability: float = 1.0     # P(report matches truth)
    ambiguous: bool = False      # returns a token that admits >1 interpretation
    source: str = "sourceA"
    available: bool = True

@dataclass
class World:
    truth: dict = field(default_factory=dict)     # LATENT — evaluator only
    channels: list = field(default_factory=list)
    t: int = 0
    _log: list = field(default_factory=list)

    def channels_for(self, prop):
        return [c for c in self.channels if c.prop == prop and c.available]

    def observable(self, prop):
        return bool(self.channels_for(prop))

    def query(self, channel, rng):
        """The ONLY way information leaves the world."""
        if not channel.available:
            return None
        v = self.truth.get(channel.prop)
        if channel.ambiguous:
            token = AMBIGUOUS.get(channel.prop, "ambiguous")
            self._log.append((self.t, channel.id, token))
            return dict(token=token, prop=channel.prop, source=channel.source,
                        ambiguous=True, t=self.t)
        if rng.random() > channel.reliability:                # noisy / misleading
            alts = [x for x in DOMAINS.get(channel.prop, [v]) if x != v]
            v = rng.choice(alts) if alts else v
        self._log.append((self.t, channel.id, v))
        return dict(token=v, prop=channel.prop, source=channel.source,
                    ambiguous=False, t=self.t)

    def advance(self, changes=None):
        self.t += 1
        if changes: self.truth.update(changes)

DOMAINS = {
    "os":        ["RHEL9.8", "RHEL8.6", "Ubuntu22.04"],
    "port":      [8081, 8082],
    "reachable": [True, False],
    "ram_gb":    [16, 31, 64],
    "connectivity": ["direct", "proxy"],
}
AMBIGUOUS = {"status": "available"}     # 'available' -> reachable? | licensed? | up?
