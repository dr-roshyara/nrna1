#!/usr/bin/env bash
# PBDIGIT-324 (roadmap W2, decision W2-1 = C): run the Membership PostgreSQL suite on a throwaway PostgreSQL
# cluster. Plan: docs/plans/20261102-1719-membership-isolated-postgres-container-plan.md §3.5.
#
#   MEMBERSHIP_PG_IMAGE=docker.io/library/postgres:<major>@sha256:<digest> bash scripts/testing/membership-pg.sh [phpunit args]
#
# - The image must be pinned by digest (gate G-RUN fixes the version and digest).
# - The container listens on 127.0.0.1 only, on a random host port, with a random password for this run.
#   The password lives only in this process's environment: it is never printed, logged or written to a file,
#   and it is passed to podman by variable name, not on the command line.
# - After start, the cluster's system_identifier is captured; the PHPUnit bootstrap
#   (tests/bootstrap-membership-pg.php) refuses to run unless its own connection sees the same identifier and
#   the database membership_pg_isolated.
# - The container is removed on every exit, including failures.
# - Evidence (commit, image digest, server version, system identifier, suite output, exit code, SHA256SUMS) is
#   written to MEMBERSHIP_PG_EVIDENCE_DIR (default: a new temporary directory).
set -euo pipefail

IMAGE="${MEMBERSHIP_PG_IMAGE:-}"
if [ -z "$IMAGE" ]; then
    echo "membership-pg: REFUSED set MEMBERSHIP_PG_IMAGE to postgres:<major>@sha256:<digest>" >&2
    exit 1
fi
case "$IMAGE" in
    *@sha256:*) ;;
    *) echo "membership-pg: REFUSED the image must be pinned by digest (…@sha256:…)" >&2; exit 1 ;;
esac

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
cd "$ROOT"

command -v podman >/dev/null 2>&1 || { echo "membership-pg: REFUSED podman is not installed" >&2; exit 1; }
command -v openssl >/dev/null 2>&1 || { echo "membership-pg: REFUSED openssl is not installed" >&2; exit 1; }
if [ -e bootstrap/cache/config.php ]; then
    echo "membership-pg: REFUSED the Laravel configuration is cached (bootstrap/cache/config.php)" >&2
    exit 1
fi
if [ -n "${APP_CONFIG_CACHE+x}" ]; then
    echo "membership-pg: REFUSED APP_CONFIG_CACHE is set" >&2
    exit 1
fi

PG_DBNAME=membership_pg_isolated
PG_ROLE=membership_pg
NAME="pbd-membership-pg-$$-$RANDOM"
EVIDENCE="${MEMBERSHIP_PG_EVIDENCE_DIR:-$(mktemp -d)}"
mkdir -p "$EVIDENCE"

cleanup() {
    podman rm -f "$NAME" >/dev/null 2>&1 || true
}
trap cleanup EXIT

SECRET="$(openssl rand -hex 24)"

POSTGRES_PASSWORD="$SECRET" podman run -d --name "$NAME" \
    -e POSTGRES_DB="$PG_DBNAME" \
    -e POSTGRES_USER="$PG_ROLE" \
    -e POSTGRES_PASSWORD \
    -p 127.0.0.1::5432 \
    "$IMAGE" >/dev/null

ready=0
for _ in $(seq 1 60); do
    if podman exec "$NAME" pg_isready -h 127.0.0.1 -p 5432 -U "$PG_ROLE" -d "$PG_DBNAME" >/dev/null 2>&1; then
        ready=1
        break
    fi
    sleep 1
done
if [ "$ready" -ne 1 ]; then
    echo "membership-pg: REFUSED the cluster did not become ready within 60 s" >&2
    exit 1
fi

HOSTPORT="$(podman port "$NAME" 5432/tcp | head -n 1)"
PG_HOST="${HOSTPORT%:*}"
PG_PORT="${HOSTPORT##*:}"
if [ "$PG_HOST" != "127.0.0.1" ]; then
    echo "membership-pg: REFUSED the published port is not on 127.0.0.1" >&2
    exit 1
fi

SYSTEM_ID="$(podman exec "$NAME" psql -U "$PG_ROLE" -d "$PG_DBNAME" -Atc 'SELECT system_identifier FROM pg_control_system()')"

# PBDIGIT-345 task 0 (slice 2): one central and three organisation databases in the same throwaway cluster, for
# the multi-database tests. Fixed names; the PHP side accepts only these (Tests\Support\PlatformPgIsolatedDatabases).
PLATFORM_PG_DATABASES="platform_pg_central,platform_pg_org_a,platform_pg_org_b,platform_pg_org_c"
for db in ${PLATFORM_PG_DATABASES//,/ }; do
    podman exec "$NAME" psql -v ON_ERROR_STOP=1 -U "$PG_ROLE" -d "$PG_DBNAME" -qc "CREATE DATABASE $db" >/dev/null
done
SERVER_VERSION="$(podman exec "$NAME" psql -U "$PG_ROLE" -d "$PG_DBNAME" -Atc 'SHOW server_version')"
IMAGE_DIGEST="$(podman image inspect --format '{{.Digest}}' "$IMAGE")"

{
    echo "commit: $(git rev-parse HEAD)"
    echo "image: $IMAGE"
    echo "image_digest: $IMAGE_DIGEST"
    echo "server_version: $SERVER_VERSION"
    echo "system_identifier: $SYSTEM_ID"
    echo "platform_databases: $PLATFORM_PG_DATABASES"
    echo "host: $PG_HOST (port published at random)"
    echo "started: $(date -Iseconds)"
} > "$EVIDENCE/run.txt"

export MEMBERSHIP_PG_TEST_DB="$PG_DBNAME"
export MEMBERSHIP_PG_DB_HOST="$PG_HOST"
export MEMBERSHIP_PG_DB_PORT="$PG_PORT"
export MEMBERSHIP_PG_DB_USERNAME="$PG_ROLE"
export MEMBERSHIP_PG_DB_PASSWORD="$SECRET"
export MEMBERSHIP_PG_SYSTEM_ID="$SYSTEM_ID"
# PBDIGIT-345 task 12b (T-17): the cluster's own PostgreSQL client tools (same major version as the server) are reached
# with `podman exec -i <container> pg_dump|pg_restore`; a newer host pg_dump writes dumps an older server cannot restore
export MEMBERSHIP_PG_CONTAINER="$NAME"
export PLATFORM_PG_DATABASES

set +e
vendor/bin/phpunit -c phpunit.membership-pg.xml --do-not-cache-result "$@" > "$EVIDENCE/suite.txt" 2>&1
RC=$?
set -e
echo "$RC" > "$EVIDENCE/suite.exit"

if grep -qF -- "$SECRET" "$EVIDENCE/suite.txt" "$EVIDENCE/run.txt"; then
    echo "membership-pg: FAILED a credential appeared in the evidence; the evidence directory must be discarded" >&2
    exit 1
fi

(cd "$EVIDENCE" && sha256sum run.txt suite.txt suite.exit > SHA256SUMS)
echo "membership-pg: suite exit $RC; evidence in $EVIDENCE" >&2
exit "$RC"
