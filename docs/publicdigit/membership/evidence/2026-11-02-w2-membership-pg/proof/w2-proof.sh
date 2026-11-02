#!/usr/bin/env bash
# PBDIGIT-324 step 6, AC-3: fail-closed proof against a live throwaway cluster (scratch, not committed).
# Usage: bash w2-proof.sh <worktree> <image@digest> <outdir>
set -euo pipefail
WT="$1"; IMAGE="$2"; OUT="$3"
mkdir -p "$OUT"
cd "$WT"
NAME="pbd-membership-pg-proof-$$"
cleanup() { podman rm -f "$NAME" >/dev/null 2>&1 || true; }
trap cleanup EXIT
SECRET="$(openssl rand -hex 24)"
POSTGRES_PASSWORD="$SECRET" podman run -d --name "$NAME" \
    -e POSTGRES_DB=membership_pg_isolated -e POSTGRES_USER=membership_pg -e POSTGRES_PASSWORD \
    -p 127.0.0.1::5432 "$IMAGE" >/dev/null
for _ in $(seq 1 60); do
    podman exec "$NAME" pg_isready -h 127.0.0.1 -p 5432 -U membership_pg -d membership_pg_isolated >/dev/null 2>&1 && break
    sleep 1
done
HOSTPORT="$(podman port "$NAME" 5432/tcp | head -n 1)"
HOST="${HOSTPORT%:*}"; PORT="${HOSTPORT##*:}"
SYSID="$(podman exec "$NAME" psql -U membership_pg -d membership_pg_isolated -Atc 'SELECT system_identifier FROM pg_control_system()')"
echo "host=$HOST sysid=$SYSID" > "$OUT/proof-cluster.txt"

probe() { # label, name, sysid
    local label="$1" dbname="$2" sid="$3" rc
    set +e
    env -i PATH="$PATH" MEMBERSHIP_PG_TEST_DB="$dbname" MEMBERSHIP_PG_DB_HOST="$HOST" MEMBERSHIP_PG_DB_PORT="$PORT" \
        MEMBERSHIP_PG_DB_USERNAME=membership_pg MEMBERSHIP_PG_DB_PASSWORD="$SECRET" MEMBERSHIP_PG_SYSTEM_ID="$sid" \
        php tests/bootstrap-membership-pg.php > "$OUT/proof-$label.txt" 2>&1
    rc=$?
    set -e
    echo "$rc" > "$OUT/proof-$label.exit"
}
probe wrong-system-id membership_pg_isolated "${SYSID}9"
probe wrong-name pbdigit274_isolated_test "$SYSID"
probe correct membership_pg_isolated "$SYSID"

if grep -rqF -- "$SECRET" "$OUT"; then
    echo "LEAK" > "$OUT/LEAK"
fi
