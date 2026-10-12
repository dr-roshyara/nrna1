\echo '-- 1. identity and server'
SELECT current_database(), current_user, session_user;
SELECT version();
SHOW server_version_num;
\echo '-- 2. role flags'
SELECT rolname, rolsuper, rolinherit, rolcreatedb, rolcreaterole, rolreplication, rolbypassrls, rolcanlogin, rolconnlimit
  FROM pg_roles WHERE rolname = current_user;
\echo '-- 3. role memberships (expected: none)'
SELECT r.rolname AS member_of FROM pg_auth_members m JOIN pg_roles r ON r.oid = m.roleid
  WHERE m.member = (SELECT oid FROM pg_roles WHERE rolname = current_user);
\echo '-- 4. database owner and ACL'
SELECT datname, pg_get_userbyid(datdba) AS owner, datacl FROM pg_database WHERE datname = current_database();
\echo '-- 5. CONNECT privilege of this role on every database that allows connections (privilege level only; no connection attempted)'
SELECT datname, has_database_privilege(current_user, datname, 'CONNECT') AS can_connect
  FROM pg_database WHERE datallowconn ORDER BY datname;
\echo '-- 6. public schema owner and CREATE privilege'
SELECT nspname, pg_get_userbyid(nspowner) AS owner, has_schema_privilege(current_user, 'public', 'CREATE') AS can_create
  FROM pg_namespace WHERE nspname = 'public';
\echo '-- 7. plpgsql available and usable'
SELECT lanname, has_language_privilege(current_user, 'plpgsql', 'USAGE') AS can_use FROM pg_language WHERE lanname = 'plpgsql';
\echo '-- 8. existing user tables (expected: none in a fresh database)'
SELECT count(*) AS user_tables FROM pg_tables WHERE schemaname NOT IN ('pg_catalog','information_schema');
\echo '-- 9. pg_hba rules (needs superuser or pg_read_all_settings; may be refused)'
SELECT line_number, type, database, user_name, address, auth_method FROM pg_hba_file_rules ORDER BY line_number;
