Deliberate architecture violations, NEVER loaded or executed (files end in .php.fixture): input for
ArchitectureRatchetTest, which proves the ratchet (1) tolerates a listed legacy violation, (2) detects a new one,
(3) reports a fixed one still listed (the baseline must shrink), (4) detects a new violation hidden in a file that
already has listed ones. `baseline.json` is the fixture's own ratchet baseline.
