# Migration Consolidation Algorithm

## Purpose
Consolidate legacy (old) migrations with new consolidated migrations to ensure database schema consistency and eliminate duplicate/missing columns.

---

## High-Level Algorithm

```
FOR EACH TABLE IN CRITICAL_TABLES:
    1. ANALYZE_OLD_MIGRATIONS(table_name)
    2. ANALYZE_NEW_MIGRATIONS(table_name)
    3. COMPARE_SCHEMAS(old_columns, new_columns)
    4. UPDATE_DATABASE(missing_columns)
    5. UPDATE_MIGRATION_FILE(missing_columns, remove_duplicates)
    6. VERIFY_CONSISTENCY(table_name)
END FOR
```

---

## Detailed Pseudocode

### PHASE 1: DISCOVERY & ANALYSIS

```
ALGORITHM AnalyzeOldMigrations(table_name)
INPUT: table_name (string)
OUTPUT: old_columns (list), migration_timeline (list)

BEGIN
    // Step 1: Locate all old migration files
    old_migration_dir = "./database/migrations_backup_20260301_093159/"
    old_migration_files = FIND_FILES(old_migration_dir, "*" + table_name + "*")

    // Step 2: Extract columns from each migration
    old_columns = EMPTY_LIST()
    migration_timeline = EMPTY_LIST()

    FOR EACH migration_file IN old_migration_files:
        date = EXTRACT_DATE_FROM_FILENAME(migration_file)
        class_name = EXTRACT_CLASS_NAME(migration_file)
        columns_added = PARSE_MIGRATION_UP_METHOD(migration_file)

        FOR EACH column IN columns_added:
            IF column NOT IN old_columns:
                old_columns.ADD(column)
            END IF
        END FOR

        migration_timeline.ADD({
            date: date,
            file: migration_file,
            columns: columns_added,
            class_name: class_name
        })
    END FOR

    // Step 3: Sort by date to show evolution
    migration_timeline.SORT_BY(date)

    RETURN {
        old_columns: old_columns,
        migration_timeline: migration_timeline,
        count: LENGTH(old_columns)
    }
END


ALGORITHM AnalyzeNewMigrations(table_name)
INPUT: table_name (string)
OUTPUT: new_columns (list), new_migration_file (string)

BEGIN
    // Step 1: Locate new consolidated migration
    new_migration_dir = "./database/migrations/"
    new_migration_files = FIND_FILES(new_migration_dir, "*create*" + table_name + "*.php")

    IF LENGTH(new_migration_files) == 0:
        new_migration_files = FIND_FILES(new_migration_dir, "*" + table_name + "*.php")
    END IF

    // Step 2: Parse the main creation migration
    new_migration_file = new_migration_files[0]  // Most recent/main one

    // Step 3: Extract columns from Schema::create() or Schema::table()
    new_columns = PARSE_MIGRATION_COLUMNS(new_migration_file)

    // Step 4: Check for duplicates within the migration
    duplicates = FIND_DUPLICATE_COLUMNS(new_columns)

    RETURN {
        new_columns: new_columns,
        new_migration_file: new_migration_file,
        duplicates: duplicates,
        count: LENGTH(new_columns)
    }
END
```

### PHASE 2: COMPARISON & ANALYSIS

```
ALGORITHM CompareSchemas(old_result, new_result)
INPUT: old_result (dict), new_result (dict)
OUTPUT: comparison_report (dict)

BEGIN
    old_columns = old_result.old_columns
    new_columns = new_result.new_columns

    // Step 1: Find missing columns
    missing_columns = EMPTY_LIST()
    FOR EACH column IN old_columns:
        IF column NOT IN new_columns:
            missing_columns.ADD(column)
            LOG "Missing: " + column.name + " (" + column.type + ")"
        END IF
    END FOR

    // Step 2: Find extra/deprecated columns
    extra_columns = EMPTY_LIST()
    FOR EACH column IN new_columns:
        IF column NOT IN old_columns AND NOT is_timestamp_field(column):
            extra_columns.ADD(column)
            LOG "Extra: " + column.name
        END IF
    END FOR

    // Step 3: Identify conflicts
    conflicts = EMPTY_LIST()
    FOR EACH column IN new_columns:
        // Check if column defined multiple times with different types
        count = COUNT_OCCURRENCES(new_columns, column.name)
        IF count > 1:
            // Get all definitions
            definitions = GET_ALL_DEFINITIONS(new_columns, column.name)
            conflicts.ADD({
                column_name: column.name,
                definitions: definitions,
                severity: "HIGH" IF definitions_differ(definitions) ELSE "LOW"
            })
            LOG "Conflict: " + column.name + " defined " + count + " times"
        END IF
    END FOR

    // Step 4: Check database reality
    database_columns = GET_DATABASE_COLUMNS(table_name)

    db_missing = EMPTY_LIST()
    FOR EACH column IN new_columns:
        IF column NOT IN database_columns:
            db_missing.ADD(column)
            LOG "⚠️ Database Missing: " + column.name
        END IF
    END FOR

    RETURN {
        missing_columns: missing_columns,
        extra_columns: extra_columns,
        conflicts: conflicts,
        database_missing: db_missing,
        status: DETERMINE_STATUS(missing_columns, conflicts)
    }
END


ALGORITHM DetermineStatus(missing_columns, conflicts)
INPUT: missing_columns (list), conflicts (list)
OUTPUT: status (string)

BEGIN
    IF LENGTH(missing_columns) > 0 OR LENGTH(conflicts) > 0:
        RETURN "NEEDS_CONSOLIDATION"
    ELSE IF LENGTH(database_missing) > 0:
        RETURN "DATABASE_OUT_OF_SYNC"
    ELSE:
        RETURN "CONSOLIDATED"
    END IF
END
```

### PHASE 3: DATABASE UPDATE

```
ALGORITHM UpdateDatabase(table_name, missing_columns)
INPUT: table_name (string), missing_columns (list)
OUTPUT: update_log (dict)

BEGIN
    update_log = EMPTY_DICT()
    added_count = 0
    dropped_count = 0

    LOG "--- Starting Database Schema Updates ---"

    // Step 1: Add missing columns
    FOR EACH column IN missing_columns:
        IF NOT ColumnExistsInDatabase(table_name, column.name):
            TRY:
                SQL = BUILD_ALTER_ADD_COLUMN(table_name, column)
                EXECUTE_SQL(SQL)
                update_log[column.name] = "ADDED"
                added_count++
                LOG "✅ Added: " + column.name + " (" + column.type + ")"
            CATCH error:
                update_log[column.name] = "FAILED: " + error.message
                LOG "❌ Failed to add: " + column.name + " - " + error.message
            END TRY
        ELSE:
            update_log[column.name] = "ALREADY_EXISTS"
        END IF
    END FOR

    // Step 2: Drop deprecated columns (e.g., voting_time_minutes)
    deprecated_columns = FIND_DEPRECATED_COLUMNS(table_name)

    FOR EACH deprecated IN deprecated_columns:
        IF ColumnExistsInDatabase(table_name, deprecated.name):
            TRY:
                SQL = BUILD_ALTER_DROP_COLUMN(table_name, deprecated)
                EXECUTE_SQL(SQL)
                update_log[deprecated.name] = "DROPPED"
                dropped_count++
                LOG "✅ Dropped: " + deprecated.name + " (deprecated)"
            CATCH error:
                update_log[deprecated.name] = "DROP_FAILED: " + error.message
                LOG "❌ Failed to drop: " + deprecated.name + " - " + error.message
            END TRY
        END IF
    END FOR

    RETURN {
        update_log: update_log,
        added: added_count,
        dropped: dropped_count,
        total_changes: added_count + dropped_count
    }
END


ALGORITHM FindDeprecatedColumns(table_name)
INPUT: table_name (string)
OUTPUT: deprecated (list)

BEGIN
    deprecated = EMPTY_LIST()

    // Common deprecation patterns
    patterns = [
        { old: "voting_time_minutes", new: "voting_time_in_minutes", reason: "Renamed for clarity" },
        { old: "organization_id", new: "organisation_id", reason: "British spelling standardization" },
        // Add more patterns as needed
    ]

    FOR EACH pattern IN patterns:
        IF table_name == "codes" OR table_name == "demo_codes":
            IF ColumnExistsInDatabase(table_name, pattern.old):
                IF ColumnExistsInDatabase(table_name, pattern.new):
                    deprecated.ADD({
                        name: pattern.old,
                        reason: pattern.reason,
                        replacement: pattern.new
                    })
                END IF
            END IF
        END IF
    END FOR

    RETURN deprecated
END
```

### PHASE 4: MIGRATION FILE UPDATE

```
ALGORITHM UpdateMigrationFile(migration_file, missing_columns, duplicates, deprecated)
INPUT: migration_file (string), missing_columns (list), duplicates (list), deprecated (list)
OUTPUT: status (string)

BEGIN
    LOG "--- Updating Migration File ---"

    // Step 1: Read migration content
    file_content = READ_FILE(migration_file)
    schema_create_block = EXTRACT_SCHEMA_CREATE_BLOCK(file_content)

    // Step 2: Remove duplicate column definitions
    FOR EACH duplicate IN duplicates:
        LOG "Removing duplicate: " + duplicate.column_name

        // Keep the FIRST definition (usually most recent type)
        definitions = GET_ALL_DEFINITIONS_IN_FILE(schema_create_block, duplicate.column_name)

        // Keep primary definition
        primary_definition = definitions[0]

        // Remove others
        FOR i = 1 TO LENGTH(definitions)-1:
            LOG "  Removing definition " + (i+1) + " of " + duplicate.column_name
            schema_create_block = REMOVE_LINE(schema_create_block, definitions[i].line_number)
        END FOR
    END FOR

    // Step 3: Remove deprecated columns
    FOR EACH deprecated_col IN deprecated:
        LOG "Removing deprecated: " + deprecated_col.name
        schema_create_block = REMOVE_COLUMN_DEFINITION(schema_create_block, deprecated_col.name)
    END FOR

    // Step 4: Add missing columns in appropriate sections
    FOR EACH missing IN missing_columns:
        insertion_point = FIND_INSERTION_POINT(schema_create_block, missing)
        column_definition = BUILD_COLUMN_DEFINITION(missing)

        LOG "Adding missing: " + missing.name + " at " + insertion_point
        schema_create_block = INSERT_COLUMN_DEFINITION(schema_create_block, insertion_point, column_definition)
    END FOR

    // Step 5: Organize columns into logical sections
    organized_schema = ORGANIZE_BY_SECTION(schema_create_block)
    // Sections: Primary Keys → Codes → Code State → Voting State → Agreements → Verification → Validation → Session → Metadata → Timestamps

    // Step 6: Write back to file
    updated_content = REBUILD_MIGRATION_FILE(file_content, organized_schema)

    TRY:
        WRITE_FILE(migration_file, updated_content)
        LOG "✅ Migration file updated successfully"
        RETURN "SUCCESS"
    CATCH error:
        LOG "❌ Failed to update migration file: " + error.message
        RETURN "FAILED"
    END TRY
END


ALGORITHM OrganizeBySection(schema_block)
INPUT: schema_block (string)
OUTPUT: organized_schema (string)

BEGIN
    // Define section order
    sections = [
        "Primary & Foreign Keys",
        "Code System",
        "Code State Tracking - Code1",
        "Code State Tracking - Code2",
        "Code State Tracking - Code3",
        "Code State Tracking - Code4",
        "Voting State",
        "Voter Agreement",
        "Vote Verification",
        "Validation",
        "Session & Timing",
        "Metadata"
    ]

    // Extract all columns
    columns = EXTRACT_ALL_COLUMNS(schema_block)

    // Assign to sections
    organized = EMPTY_DICT()
    FOR EACH section IN sections:
        organized[section] = EMPTY_LIST()
    END FOR

    FOR EACH column IN columns:
        section = CATEGORIZE_COLUMN(column.name)
        organized[section].ADD(column)
    END FOR

    // Rebuild schema with sections
    rebuilt = ""
    FOR EACH section IN sections:
        IF LENGTH(organized[section]) > 0:
            rebuilt += "// " + section + "\n"
            FOR EACH column IN organized[section]:
                rebuilt += "  " + column.definition + "\n"
            END FOR
            rebuilt += "\n"
        END IF
    END FOR

    RETURN rebuilt
END


ALGORITHM CategorizeColumn(column_name)
INPUT: column_name (string)
OUTPUT: section (string)

BEGIN
    SWITCH column_name:
        CASE matches "^(id|election_id|user_id|organisation_id)$":
            RETURN "Primary & Foreign Keys"
        CASE matches "^code[1-4]$":
            RETURN "Code System"
        CASE matches "^(is_code1|code1_|has_used_code1|has_code1)":
            RETURN "Code State Tracking - Code1"
        CASE matches "^(is_code2|code2_|has_used_code2|has_code2)":
            RETURN "Code State Tracking - Code2"
        CASE matches "^(is_code3|code3_)":
            RETURN "Code State Tracking - Code3"
        CASE matches "^(is_code4|code4_)":
            RETURN "Code State Tracking - Code4"
        CASE matches "^(can_vote|has_voted|vote_submitted|voting_started|vote_completed)":
            RETURN "Voting State"
        CASE matches "^has_agreed":
            RETURN "Voter Agreement"
        CASE matches "^(vote_show|vote_last|code_for)":
            RETURN "Vote Verification"
        CASE matches "^is_codemodel":
            RETURN "Validation"
        CASE matches "^(session_name|client_ip|voting_time|expires)":
            RETURN "Session & Timing"
        CASE matches "^metadata":
            RETURN "Metadata"
        DEFAULT:
            RETURN "Metadata"  // Fallback
    END SWITCH
END
```

### PHASE 5: VERIFICATION

```
ALGORITHM VerifyConsistency(table_name)
INPUT: table_name (string)
OUTPUT: verification_report (dict)

BEGIN
    LOG "--- Verifying Consolidation ---"

    // Step 1: Get expected columns from migration
    expected_columns = PARSE_MIGRATION(table_name)

    // Step 2: Get actual database columns
    actual_columns = GET_DATABASE_COLUMNS(table_name)

    // Step 3: Compare
    missing = ARRAY_DIFF(expected_columns, actual_columns)
    extra = ARRAY_DIFF(actual_columns, expected_columns)

    // Step 4: Check for duplicates in database
    duplicates_in_db = CHECK_DUPLICATE_COLUMNS(actual_columns)

    // Step 5: Validate column types match
    type_mismatches = EMPTY_LIST()
    FOR EACH column IN expected_columns:
        expected_type = column.type
        actual_type = GET_COLUMN_TYPE(table_name, column.name)

        IF expected_type != actual_type:
            type_mismatches.ADD({
                column: column.name,
                expected: expected_type,
                actual: actual_type
            })
            LOG "⚠️ Type mismatch: " + column.name
        END IF
    END FOR

    // Step 6: Generate report
    is_consolidated = LENGTH(missing) == 0 AND LENGTH(duplicates_in_db) == 0 AND LENGTH(type_mismatches) == 0

    status = is_consolidated ? "✅ CONSOLIDATED" : "❌ ISSUES_FOUND"

    RETURN {
        table_name: table_name,
        status: status,
        expected_count: LENGTH(expected_columns),
        actual_count: LENGTH(actual_columns),
        missing_columns: missing,
        extra_columns: extra,
        duplicates: duplicates_in_db,
        type_mismatches: type_mismatches,
        timestamp: CURRENT_TIMESTAMP()
    }
END
```

---

## Implementation Flow

```
MAIN_ALGORITHM ConsolidateAllMigrations()
BEGIN
    critical_tables = [
        "organisations",
        "users",
        "elections",
        "codes",
        "demo_codes",
        "votes",
        "results",
        "voter_slugs",
        "voter_slug_steps",
        "user_organisation_roles"
    ]

    consolidation_report = EMPTY_LIST()

    FOR EACH table IN critical_tables:
        LOG "\n" + "="*60
        LOG "CONSOLIDATING: " + table
        LOG "="*60

        // Phase 1: Analysis
        old_result = AnalyzeOldMigrations(table)
        new_result = AnalyzeNewMigrations(table)

        LOG "Old migrations found: " + LENGTH(old_result.migration_timeline)
        LOG "Old columns identified: " + old_result.count
        LOG "New migration file: " + new_result.new_migration_file
        LOG "New columns defined: " + new_result.count

        // Phase 2: Comparison
        comparison = CompareSchemas(old_result, new_result)

        IF comparison.status == "CONSOLIDATED":
            LOG "✅ Table already consolidated"
            consolidation_report.ADD({table: table, status: "ALREADY_CONSOLIDATED"})
            CONTINUE
        END IF

        LOG "Missing columns: " + LENGTH(comparison.missing_columns)
        FOR EACH col IN comparison.missing_columns:
            LOG "  - " + col.name + " (" + col.type + ")"
        END FOR

        IF LENGTH(comparison.conflicts) > 0:
            LOG "Duplicate definitions found: " + LENGTH(comparison.conflicts)
            FOR EACH conflict IN comparison.conflicts:
                LOG "  - " + conflict.column_name + " defined " + LENGTH(conflict.definitions) + " times"
            END FOR
        END IF

        // Phase 3: Database Update
        db_update = UpdateDatabase(table, comparison.missing_columns)
        LOG "Database changes: " + db_update.total_changes + " (" + db_update.added + " added, " + db_update.dropped + " dropped)"

        // Phase 4: Migration File Update
        migration_status = UpdateMigrationFile(
            new_result.new_migration_file,
            comparison.missing_columns,
            comparison.conflicts,
            comparison.extra_columns
        )
        LOG "Migration file: " + migration_status

        // Phase 5: Verification
        verification = VerifyConsistency(table)
        consolidation_report.ADD({
            table: table,
            status: verification.status,
            verification: verification
        })

        IF verification.status == "✅ CONSOLIDATED":
            LOG "✅ SUCCESS: " + table + " consolidation complete"
        ELSE:
            LOG "❌ ISSUES: " + table + " has remaining issues"
            FOR EACH issue IN verification.missing_columns + verification.extra_columns:
                LOG "  - " + issue
            END FOR
        END IF
    END FOR

    // Generate final report
    WRITE_CONSOLIDATION_REPORT(consolidation_report)
    RETURN consolidation_report
END
```

---

## Data Structures

```
COLUMN_DEFINITION = {
    name: STRING,
    type: STRING,  // boolean, string, integer, timestamp, json, etc.
    nullable: BOOLEAN,
    default: ANY,
    length: INTEGER (optional),
    line_number: INTEGER,
    source_migration: STRING
}

MIGRATION_ENTRY = {
    date: DATETIME,
    file: STRING,
    class_name: STRING,
    columns: LIST<COLUMN_DEFINITION>
}

CONFLICT_ENTRY = {
    column_name: STRING,
    definitions: LIST<COLUMN_DEFINITION>,
    severity: ENUM["LOW", "HIGH"]
}

COMPARISON_RESULT = {
    missing_columns: LIST<COLUMN_DEFINITION>,
    extra_columns: LIST<COLUMN_DEFINITION>,
    conflicts: LIST<CONFLICT_ENTRY>,
    database_missing: LIST<COLUMN_DEFINITION>,
    status: ENUM["CONSOLIDATED", "NEEDS_CONSOLIDATION", "DATABASE_OUT_OF_SYNC"]
}
```

---

## Key Decision Points

```
IF migration_consolidation_needed(table):

    DECISION 1: Column Type Conflict
    QUESTION: If a column is defined as BOTH timestamp AND dateTime?
    ANSWER: Keep the FIRST definition (earlier in file), remove duplicates

    DECISION 2: Deprecated Columns
    QUESTION: Should old columns like voting_time_minutes be removed?
    ANSWER: YES - if a replacement exists (voting_time_in_minutes)

    DECISION 3: Default Values
    QUESTION: If old migration has default(0) but new has default(false)?
    ANSWER: Use new default (more semantically correct), ensure backward compatibility

    DECISION 4: Null vs NotNull
    QUESTION: If column changes from nullable to required?
    ANSWER: Keep as nullable in migration, add data migration separately

    DECISION 5: Missing Foreign Keys
    QUESTION: Should missing foreign key constraints be added?
    ANSWER: YES - add in migration file, execute as separate migration if DB already exists

END IF
```

---

## Troubleshooting Logic

```
ALGORITHM HandleMigrationErrors()
BEGIN
    // Error 1: Column Already Exists
    IF error contains "Column already exists":
        SKIP_ADD_COLUMN()
        LOG "Column already exists in database"
    END IF

    // Error 2: Foreign Key Constraint Failed
    IF error contains "Cannot add or update a child row":
        LOG "Foreign key constraint failed"
        LOG "Solution: Add missing parent record or use ->nullable()"
    END IF

    // Error 3: Type Incompatibility
    IF error contains "Incompatible column type":
        LOG "Column type mismatch between expected and actual"
        LOG "Solution: Manual column type conversion may be needed"
    END IF

    // Error 4: Duplicate Column Definition
    IF error contains "Duplicate column":
        LOG "Column defined multiple times in migration"
        LOG "Solution: Remove duplicate definition"
    END IF
END
```

---

## Success Metrics

```
CONSOLIDATION_SUCCESS_CRITERIA = {

    // Database State
    all_expected_columns_exist: BOOLEAN,
    no_duplicate_columns: BOOLEAN,
    correct_column_types: BOOLEAN,
    correct_defaults: BOOLEAN,
    correct_nullable: BOOLEAN,

    // Migration File State
    migration_file_updated: BOOLEAN,
    migration_file_readable: BOOLEAN,
    migration_file_executable: BOOLEAN,

    // Consistency
    migration_matches_database: BOOLEAN,
    migration_matches_old_migrations: BOOLEAN,
    all_columns_documented: BOOLEAN,

    // Performance
    indexes_present: BOOLEAN,
    foreign_keys_present: BOOLEAN,

    // Overall Status
    consolidation_complete: BOOLEAN = (
        all_expected_columns_exist AND
        no_duplicate_columns AND
        migration_matches_database AND
        migration_file_updated
    )
}
```

---

## Example: Codes Table Consolidation

```
// Step 1: Discovery
old_migrations = [
    "2021_07_22_195040_create_codes_table.php",
    "2022_01_03_182522_add_more_columns_to_codes_table.php",
    ...
    "2026_02_19_190930_add_organisation_id_to_codes_table.php"
]
old_columns_found = 39 columns

new_migration = "2026_03_01_000007_create_codes_table.php"
new_columns_defined = 31 columns (missing 8)

// Step 2: Comparison
missing = [vote_submitted_at, has_agreed_to_vote, ...]
conflicts = [{column: vote_last_seen, definitions: [timestamp, dateTime]}, ...]
deprecated = [voting_time_minutes]

// Step 3: Database Update
ALTER TABLE codes ADD COLUMN vote_submitted_at TIMESTAMP NULL;
...
ALTER TABLE codes DROP COLUMN voting_time_minutes;

// Step 4: Migration Update
- Add 8 missing columns
- Remove 2 duplicate definitions (vote_last_seen, voting_started_at)
- Remove 1 deprecated column (voting_time_minutes)
- Reorganize into logical sections

// Step 5: Verification
Database columns: 41 ✅
Migration columns: 41 ✅
Status: CONSOLIDATED ✅
```

---

## Application to Other Tables

This algorithm can be applied to:
- ✅ organisations
- ✅ user_organisation_roles
- ✅ codes
- ✅ demo_codes
- ⏭️ elections
- ⏭️ votes
- ⏭️ results
- ⏭️ voter_slugs
- ⏭️ users
- ⏭️ posts/demo_posts

---

## Notes & Best Practices

1. **Always backup** the database before running migrations
2. **Test in development first** before production
3. **Keep old migrations** for reference and history
4. **Document changes** in migration comments
5. **Verify column order** matches logical groupings
6. **Check for circular dependencies** in foreign keys
7. **Validate data types** match usage in code
8. **Update models** if column names change
9. **Run verification** after each table consolidation
10. **Commit migrations** to version control with detailed messages
