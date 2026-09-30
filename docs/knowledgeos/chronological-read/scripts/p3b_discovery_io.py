#!/usr/bin/env python3
"""P3b discovery-population I/O — the seal-aware CONTENT reader required by the H-19 addendum v1.5 §4 (G-LOG-0016).

`discovery_resolver()` returns a ContentResolver (from the S3 script, G-LOG-0014: manifest-bound, sha256-verified,
fail closed) that additionally refuses every hold-out file (HF) while `P3B-HOLDOUT-SEAL.json` has `state: SEALED`.
Every stage-2 / S4 / S5 reader of CONTENT bytes must obtain them through this function; S4/S5 script reviews assert
this. Residual risk: the base ContentResolver (S3 script, committed and not modified) can still be called directly and
knows nothing of the seal. The committed S3 script is
not modified; its own run (which predates the seal) is unaffected.
"""
import importlib.util
import json
import os

_HERE = os.path.dirname(os.path.abspath(__file__))
_spec = importlib.util.spec_from_file_location("p3b_s3_mechanical_prep", os.path.join(_HERE, "p3b_s3_mechanical_prep.py"))
s3 = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(s3)

SEAL = "P3B-HOLDOUT-SEAL.json"
HOLDOUT_FILE_SHA = "46cdabe271768a9db7ea06967eaa6d3963c001f11680e2e0d896153f9e2d8a9d"    # G-LOG-0016


class SealedContentResolver(s3.ContentResolver):
    """ContentResolver that refuses sealed source_ids."""

    def __init__(self, manifest_rows, sealed_source_ids):
        super().__init__(manifest_rows)
        self.sealed = frozenset(sealed_source_ids)

    def read_many(self, source_ids):
        source_ids = list(source_ids)
        blocked = sorted(s for s in source_ids if s in self.sealed)
        if blocked:
            raise s3.IdentityError(f"sealed hold-out file(s) requested while SEALED: {blocked[:5]} (H-19 addendum §4)")
        return super().read_many(source_ids)


def load_seal(path=None):
    path = path or os.path.join(s3.CR, SEAL)
    with open(path, encoding="utf-8") as f:
        return json.load(f)


def discovery_resolver(seal_path=None, manifest_path=None):
    """The pinned manifest (G-LOG-0013) plus the seal. Refuses HF while SEALED; after the unseal it is an ordinary
    ContentResolver over the whole census."""
    base = s3.ContentResolver.from_manifest(manifest_path)
    seal = load_seal(seal_path)
    if seal.get("state") not in ("SEALED", "UNSEALED"):
        raise s3.IdentityError(f"invalid seal state {seal.get('state')!r}")
    if s3.sha256_bytes(json.dumps(sorted(seal.get("holdout_files", []))).encode()) != HOLDOUT_FILE_SHA:
        raise s3.IdentityError("seal hold-out file list differs from the approved hash (G-LOG-0016)")
    sealed = seal["holdout_files"] if seal["state"] == "SEALED" else []
    return SealedContentResolver(list(base.rows.values()), sealed)
