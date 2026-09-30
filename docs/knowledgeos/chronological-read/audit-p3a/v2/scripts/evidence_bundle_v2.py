#!/usr/bin/env python3
"""EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH.

P3A-V2 evidence-bundle builder (§7 of the repair specification). Replaces v1's
`row_brief()`, which kept only 6 fields (source_id, anchor, types, statement,
type_signature, explicit_date) and silently dropped dependencies[], lineage_
claims[], invariants[], assumptions[], completeness, missing[], and file-level
provenance from every row shown to a P3a reviewer.

Principle (per spec): "Evidence reduction is allowed only when it is lossless
with respect to the decision being made." version_ref/experiment{}/review_flag
remain excluded from the default bundle (no protocol rule ties them to a
relationship decision; they stay in the full ledger, one lookup away).
"""


def row_bundle_v2(row, file_meta):
    """file_meta: {provenance, status, path, best_historical_date} for this row's
    source_id, looked up from 02-FILES.jsonl by the caller."""
    return {
        "source_id": row["source_id"],
        "anchor": row.get("anchor"),
        "statement": row.get("statement"),
        "labels": row.get("labels"),
        "types": row.get("types"),
        "type_signature": row.get("type_signature"),
        "dependencies": row.get("dependencies") or [],
        "lineage_claims": row.get("lineage_claims") or [],
        "invariants": row.get("invariants") or [],
        "assumptions": row.get("assumptions") or [],
        "explicit_date": row.get("explicit_date"),
        "provenance": file_meta.get("provenance"),
        "source_role": file_meta.get("status"),
        "source_path": file_meta.get("path"),
    }


def candidate_bundle_v2(pair_record, family_rows_a, family_rows_b, file_by_sid):
    """Build the full v2 evidence bundle for one candidate pair, given the raw
    signal record from derive_reconciliation_v2.py's output plus each side's
    full row list (from _derived.json[families]) and a source_id->file lookup."""
    def meta(sid):
        f = file_by_sid.get(sid, {})
        return {"provenance": f.get("provenance"), "status": f.get("status"),
                "path": f.get("path"), "best_historical_date": f.get("best_historical_date")}

    return {
        "label_a": pair_record["label_a"], "label_b": pair_record["label_b"],
        "mechanisms": pair_record["mechanisms"],
        "best_confidence": pair_record["best_confidence"],
        "relationship_signal_origin": [
            {"mechanism": s["mechanism"], "confidence": s["confidence"],
             "source_id": s.get("source_id"),
             "lineage_kind": s.get("lineage_kind"),
             "lineage_quote": s.get("lineage_quote"),
             "dependency_value": s.get("dependency_value"),
             "matched_verbs": s.get("matched_verbs"),
             "note": s.get("note")}
            for s in pair_record["signals"]
        ],
        "a_rows": [row_bundle_v2(r, meta(r["source_id"])) for r in family_rows_a],
        "b_rows": [row_bundle_v2(r, meta(r["source_id"])) for r in family_rows_b],
        "a_row_count": len(family_rows_a), "b_row_count": len(family_rows_b),
        "provenance_summary": {
            "a_has_primary": any(meta(r["source_id"])["provenance"] == "PRIMARY" for r in family_rows_a),
            "a_has_secondary_synthesis": any(meta(r["source_id"])["provenance"] == "SECONDARY-SYNTHESIS" for r in family_rows_a),
            "b_has_primary": any(meta(r["source_id"])["provenance"] == "PRIMARY" for r in family_rows_b),
            "b_has_secondary_synthesis": any(meta(r["source_id"])["provenance"] == "SECONDARY-SYNTHESIS" for r in family_rows_b),
        },
    }
