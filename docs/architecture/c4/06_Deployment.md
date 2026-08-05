# Deployment View

**Diagram:** [`plantuml/Deployment.puml`](plantuml/Deployment.puml)
**Derived from:** container view + routes/console.php scheduling + Blueprint §19 performance assumptions

## Explanation
Single-node modular-monolith deployment: Browser → Nginx → PHP-FPM (Laravel) with Supervisor/cron running the queue worker and the scheduler (`outbox:process` every minute; `inbox:redrive` planned with PB-003). Data tier: PostgreSQL (business + messaging tables) and Redis.

## Deployment assumptions (explicit, to be confirmed by operations)
1. **Single node suffices** at documented volumes (Blueprint §19: challenges are exceptional legal events; loop latency target is minutes-by-design).
2. **The relay is cron-scheduled polling, not a broker.** Introducing a queue/broker for events is a Blueprint v1.1 + Security Gate decision — never an ops convenience.
3. **Redis** presence is assumed from Laravel convention; tests run array drivers.
4. Windows/XAMPP is the development environment; the diagram shows the production shape (Linux/Nginx/PHP-FPM) by convention — no frozen deployment document exists yet (flagged as a gap in README).
5. The transactional outbox table MUST share the database with aggregate tables — ADR-T1 atomicity is a same-database guarantee.
