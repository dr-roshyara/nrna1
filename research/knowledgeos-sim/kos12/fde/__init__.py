"""KR-CONTR-FDE-2026-09 — experimental representation-comparison harness.

EXPERIMENTAL PACKAGE.  Not a KnowledgeOS domain package.  Nothing here is a primitive.

The spec was written in Java records; this repository has no Java.  Production domain is
PHP under app/ ; the research lane is Python under research/knowledgeos-sim/ .  This
package therefore lives inside the EXISTING research structure rather than inventing a
parallel architecture, and Java records map to frozen dataclasses.

Central object, per the E12/E13 results:

    Evaluation Representation  =  Status/Polarity  x  Typed Boundary/Reason

FDE's two-channel Standing is ONE CANDIDATE for the first factor -- not the model.
"""
EXPERIMENT_ID = "KR-CONTR-FDE-2026-09"
