# Committee & Geography Context Developer Guide

## Overview

This guide explains how to create committees using the **Geography Context** and **Membership Context**. It covers:

1. **Committee Structure Types** - What they are and how to use them
2. **Committee Code** - Naming conventions and examples
3. **Geographic Reference** - How to use geography data when creating committees
4. **The Relationship** between Geography and Committee Management

---

## Part 1: Understanding Committee Types

### The Hierarchical Structure

Committees exist at **different organizational levels**:

```
┌─────────────────────────────────────┐
│  CENTRAL COMMITTEE                   │  National level
│  (Highest authority)                │
└──────────────┬──────────────────────┘
               │
    ┌──────────┴──────────┐
    ▼                     ▼
┌──────────────┐   ┌──────────────┐
│ PROVINCIAL   │   │ PROVINCIAL   │  Regional levels
│ COMMITTEE    │   │ COMMITTEE    │  (Multiple committees)
│ (Bavaria)    │   │ (Baden)      │
└──┬───────────┘   └───┬──────────┘
   │                   │
   ├─────┬─────┐       ├─────┬──────┐
   ▼     ▼     ▼       ▼     ▼      ▼
┌────┐┌────┐┌────┐ ┌────┐┌────┐┌────┐
│DIS-││DIS-││DIS-│ │DIS-││DIS-││DIS-│  District level
│TRCT││TRCT││TRCT│ │TRCT││TRCT││TRCT│
└─┬──┘└─┬──┘└─┬──┘ └─┬──┘└─┬──┘└─┬──┘
  │     │     │     │     │     │
 ▼▼▼   ▼▼▼   ▼▼▼   ▼▼▼   ▼▼▼   ▼▼▼
 WARD  WARD  WARD  WARD  WARD  WARD   Ward level
 CMTES CMTES CMTES CMTES CMTES CMTES
```

### Seven Committee Types

#### 1. **Central Committee**
- **Level**: National
- **Purpose**: Highest authority body
- **Geography**: National scope (no geography restriction)
- **Members**: Top leadership, decision-makers
- **Example**: "Namaste Nepal Central Committee"

#### 2. **Provincial Committee**
- **Level**: Regional (State/Province level)
- **Purpose**: Regional governance
- **Geography**: Tied to specific province/state
- **Members**: Provincial leaders
- **Example**: "Bavaria Provincial Committee"

#### 3. **District Committee**
- **Level**: District level
- **Purpose**: District-level governance
- **Geography**: Tied to specific district
- **Members**: District leaders and coordinators
- **Example**: "Munich District Committee"

#### 4. **Ward Committee**
- **Level**: Local (Municipality/Ward level)
- **Purpose**: Local governance and direct member engagement
- **Geography**: Tied to specific ward/municipality
- **Members**: Local activists and volunteers
- **Example**: "Altstadt-Lehel Ward Committee"

#### 5. **Youth Wing**
- **Level**: Can be at any geographic level
- **Purpose**: Engage younger members (typically 18-35)
- **Geography**: Can be national or regional
- **Members**: Young party members
- **Example**: "Namaste Nepal Youth Wing" or "Bavaria Youth Wing"

#### 6. **Women Wing**
- **Level**: Can be at any geographic level
- **Purpose**: Engage and support women members
- **Geography**: Can be national or regional
- **Members**: Women members
- **Example**: "Namaste Nepal Women Wing"

#### 7. **Student Wing**
- **Level**: Can be at any geographic level
- **Purpose**: Engage student members
- **Geography**: Can be national or regional
- **Members**: University and college students
- **Example**: "Namaste Nepal Student Wing"

---

## Part 2: Committee Code Naming Conventions

### What is a Committee Code?

A **committee code** is a **unique identifier** that helps you:
- Track committees across the system
- Differentiate between committees easily
- Create reports and statistics
- Manage multiple committees efficiently

### Naming Convention Examples

#### Pattern 1: **Type-Location-Number** (Recommended)

```
Structure: [TYPE]-[LOCATION]-[NUMBER]

Examples:
├── CENTRAL-NP-001          (Central Committee - Nepal)
├── PROV-BY-001             (Provincial - Bayern/Bavaria)
├── PROV-BW-001             (Provincial - Baden-Württemberg)
├── DIST-MUC-001            (District - Munich)
├── DIST-AUG-001            (District - Augsburg)
├── WARD-ALT-001            (Ward - Altstadt)
├── YOUTH-NP-001            (Youth - National)
├── WOMEN-BY-001            (Women - Bavaria)
└── STUDENT-MUC-001         (Student - Munich)
```

**Benefits**:
- Easy to identify type and location
- Consistent and scalable
- Good for sorting and filtering

#### Pattern 2: **Acronym-Based**

```
Structure: [ACRONYM]-[YEAR]-[NUMBER]

Examples:
├── CNC-2024-001            (Central Committee - 2024)
├── PBC-2024-001            (Provincial Committee - 2024)
├── DBC-2024-001            (District Committee - 2024)
├── WBC-2024-001            (Ward Committee - 2024)
├── YW-2024-001             (Youth Wing - 2024)
├── WW-2024-001             (Women Wing - 2024)
└── SW-2024-001             (Student Wing - 2024)
```

**Benefits**:
- Short and concise
- Works well in reports and documents
- Includes year for budget/reporting cycles

#### Pattern 3: **Hierarchical-Based**

```
Structure: [LEVEL].[REGION].[SEQUENCE]

Examples:
├── 1.0.0                   (Central - National - First)
├── 2.1.0                   (Provincial - Bayern - First)
├── 2.2.0                   (Provincial - Baden - First)
├── 3.1.1.0                 (District - Bayern.Munich - First)
├── 3.2.1.0                 (District - Baden.Stuttgart - First)
└── 4.1.1.1.0               (Ward - Bayern.Munich.Altstadt - First)
```

**Benefits**:
- Shows hierarchy immediately
- Useful for nested structures
- Database-friendly

### Choosing Your Code Format

| Pattern | Best For | Example |
|---------|----------|---------|
| **Type-Location-Number** | Human readability | `PROV-BY-001` |
| **Acronym-Based** | Short references | `PBC-2024-001` |
| **Hierarchical** | System organization | `2.1.0` |

**Recommendation**: Use **Pattern 1 (Type-Location-Number)** for best balance of readability and scalability.

---

## Part 3: Geographic Reference System

### Understanding Geographic Hierarchy in Nepal/Germany

The system uses a **multi-level geographic hierarchy**:

```
COUNTRY
  └─ REGION/PROVINCE
       └─ DISTRICT
            └─ MUNICIPALITY/WARD
                 └─ SUB-WARD (optional)
```

### Geographic Reference Format

The system uses **geographic codes** to identify locations:

```
Format: country.level.id

Examples for Nepal:
├── np.1.1                  (Nepal Level 1 - Province 1)
├── np.1.11                 (Nepal Level 1.1 - District within Province 1)
├── np.1.12.1               (Nepal Level 1.1.2 - Ward within District)
└── np.1.12.1.5             (Nepal Level 1.1.2.5 - Sub-ward)

Examples for Germany:
├── de.1.1                  (Germany Level 1 - Bayern)
├── de.1.2                  (Germany Level 1 - Baden-Württemberg)
├── de.1.1.1                (Germany Level 1.1 - Munich District)
└── de.1.1.1.4              (Germany Level 1.1.1.4 - Ward)
```

### Using Geographic References When Creating Committees

When creating a committee, you specify where it operates:

#### **Central Committee** (No geographic restriction)
```
Name:             Namaste Nepal Central Committee
Code:             CENTRAL-NP-001
Type:             Central
Geographic Ref:   (Leave empty - operates nationally)
```

#### **Provincial Committee** (Tied to one province)
```
Name:             Bavaria Provincial Committee
Code:             PROV-BY-001
Type:             Province
Geographic Ref:   de.1.1  (Bayern/Bavaria in Germany)
```

#### **District Committee** (Tied to one district)
```
Name:             Munich District Committee
Code:             DIST-MUC-001
Type:             District
Geographic Ref:   de.1.1.1  (Munich within Bayern)
```

#### **Ward Committee** (Tied to one ward)
```
Name:             Altstadt-Lehel Ward Committee
Code:             WARD-ALT-001
Type:             Ward
Geographic Ref:   de.1.1.1.4  (Specific ward in Munich)
```

#### **Youth Wing** (Can be national or regional)
```
Name:             Namaste Nepal Youth Wing
Code:             YOUTH-NP-001
Type:             Youth
Geographic Ref:   (Leave empty for national scope)
```

OR (Regional scope)
```
Name:             Bavaria Youth Wing
Code:             YOUTH-BY-001
Type:             Youth
Geographic Ref:   de.1.1  (Bavaria scope)
```

---

## Part 4: Creating Committees - Step by Step

### Step 1: Navigate to Committee Creation

1. Go to Organisation Dashboard: `http://localhost:8000/organisations/namaste-nepal-gmbh`
2. Scroll to **Administration** section
3. Click **Committee Management** card
4. Click **New** button OR Click "Manage" → then click **Create Committee**

### Step 2: Fill in Basic Information

**Committee Name**:
- Full, formal name of your committee
- Example: "Bavaria Provincial Committee"
- This is what appears in reports and member communications

**Committee Code**:
- Unique identifier following your naming convention
- Example: `PROV-BY-001`
- Once created, this should NOT be changed
- Must be unique within the organization

### Step 3: Select Committee Type

Choose the appropriate type:

```
┌─────────────────────────────────────────────────────────────┐
│ COMMITTEE TYPE SELECTOR                                     │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│ ○ Central Committee    → National-level governance           │
│ ○ Provincial Committee → State/Province-level governance     │
│ ○ District Committee   → District-level governance           │
│ ○ Ward Committee       → Local ward/municipality level       │
│ ○ Youth Wing           → Youth member engagement             │
│ ○ Women Wing           → Women member engagement             │
│ ○ Student Wing         → Student member engagement           │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

**Question to Ask Yourself**:
- "At what organizational level does this committee operate?"
- "Which geographic area does it serve?"
- "Who are the primary members?"

### Step 4: Define Geographic Scope (if applicable)

**NOT ALL COMMITTEES NEED GEOGRAPHIC REFERENCES!**

#### When to SKIP this field:
- ✅ Central Committee (operates nationally)
- ✅ National-level Youth/Women/Student Wings

#### When to FILL this field:
- ✅ Provincial Committee → Reference to province/state
- ✅ District Committee → Reference to district
- ✅ Ward Committee → Reference to ward/municipality
- ✅ Regional Youth/Women/Student Wings → Reference to region

### How to Find Geographic References

The system has pre-loaded geographic data. Here's how to find the right code:

#### For Nepal (Example):
```
np.1    → Province 1
np.1.12 → Morang District (in Province 1)
np.1.12.5 → Sundar Ward (in Morang)
```

#### For Germany (Example):
```
de.1.1    → Bayern (Bavaria)
de.1.1.1  → München (Munich District)
de.1.1.1.4 → Altstadt-Lehel Ward
```

**How to Look Up Codes**:
1. Reference the geographic context documentation
2. Ask system administrator for geographic code list
3. Check existing committees in the system
4. Use the geographic dropdown/search (if available)

### Step 5: Review and Submit

Before clicking "Create Committee":

- ✅ Committee Name is clear and official
- ✅ Committee Code is unique and follows convention
- ✅ Committee Type is appropriate
- ✅ Geographic Reference is correct (if applicable)

**Click "Create Committee"**

---

## Part 5: How Geography Context Works

### The Architecture

```
┌──────────────────────────────────────────────────────┐
│           GEOGRAPHY CONTEXT                          │
│                                                      │
│  Geographic Units (7,581 locations in Nepal/Germany)│
│  - Countries                                        │
│  - Provinces/States                                 │
│  - Districts                                        │
│  - Municipalities/Wards                             │
│  - Sub-wards (optional)                             │
└──────────────┬───────────────────────────────────────┘
               │
               │ Used by
               ▼
┌──────────────────────────────────────────────────────┐
│         MEMBERSHIP CONTEXT                           │
│                                                      │
│  Committees are mapped to Geographic Units          │
│  - Central Committee → National scope               │
│  - Provincial → Specific province                   │
│  - District → Specific district                     │
│  - Ward → Specific ward                             │
└──────────────────────────────────────────────────────┘
```

### Database Relationship

```sql
-- Simplified: How committees link to geography

CREATE TABLE committees (
    id UUID PRIMARY KEY,
    name VARCHAR(255),           -- "Bavaria Provincial Committee"
    code VARCHAR(100),           -- "PROV-BY-001"
    type VARCHAR(50),            -- "province"
    geo_unit_id BIGINT,          -- References geography context
    organisation_id UUID,
    ...
);

CREATE TABLE geo_administrative_units (
    id BIGINT PRIMARY KEY,
    code VARCHAR(50),            -- "de.1.1"
    name VARCHAR(255),           -- "Bayern"
    level INT,
    parent_id BIGINT,
    ...
);

-- When creating a Provincial Committee:
-- 1. Committee references geographical unit ID
-- 2. Committee inherits geographic context
-- 3. All assignments to this committee are within that geography
```

### Example: Creating a Provincial Committee

```
User Input:
├── Name: "Bavaria Provincial Committee"
├── Code: "PROV-BY-001"
├── Type: "Province"
└── Geographic Ref: "de.1.1"  (Bayern code)

System Processing:
1. Look up geographic unit with code "de.1.1"
2. Get geographic unit ID: 12345
3. Create Committee record:
   {
     id: "uuid-xxx",
     name: "Bavaria Provincial Committee",
     code: "PROV-BY-001",
     type: "province",
     geo_unit_id: 12345,
     geo_path: "de.1.1",
     organisation_id: "org-id"
   }

Result:
- Committee is now linked to Bavaria geographic unit
- All assignments to this committee are Bavaria-based
- Reports for this committee show Bavaria geographic context
- Sub-committees can be created under this committee
```

---

## Part 6: Common Scenarios

### Scenario 1: Creating National Structure

**Goal**: Set up committees at all levels for Nepal

```
Step 1: Create Central Committee
├── Name:            "Namaste Nepal Central Committee"
├── Code:            "CENTRAL-NP-001"
├── Type:            Central
└── Geographic Ref:  (Leave empty)

Step 2: Create Provincial Committees
├── Province 1       → Code: "PROV-P1-001", Geo: "np.1"
├── Province 2       → Code: "PROV-P2-001", Geo: "np.2"
├── Province 3       → Code: "PROV-P3-001", Geo: "np.3"
├── Province 4       → Code: "PROV-P4-001", Geo: "np.4"
├── Province 5       → Code: "PROV-P5-001", Geo: "np.5"
├── Province 6       → Code: "PROV-P6-001", Geo: "np.6"
└── Province 7       → Code: "PROV-P7-001", Geo: "np.7"

Step 3: Under each province, create District Committees
├── Province 1
│   ├── District 1   → Code: "DIST-P1D1-001", Geo: "np.1.1"
│   ├── District 2   → Code: "DIST-P1D2-001", Geo: "np.1.2"
│   └── District 3   → Code: "DIST-P1D3-001", Geo: "np.1.3"
└── (repeat for all provinces)

Step 4: Under each district, create Ward Committees
├── District 1
│   ├── Ward 1       → Code: "WARD-D1W1-001", Geo: "np.1.1.1"
│   ├── Ward 2       → Code: "WARD-D1W2-001", Geo: "np.1.1.2"
│   └── Ward 3       → Code: "WARD-D1W3-001", Geo: "np.1.1.3"
└── (repeat for all districts)

Step 5: Create Special Wings (Optional)
├── Youth            → Code: "YOUTH-NP-001", Geo: (empty)
├── Women            → Code: "WOMEN-NP-001", Geo: (empty)
└── Student          → Code: "STUDENT-NP-001", Geo: (empty)
```

### Scenario 2: Creating Regional Wings

**Goal**: Create regional Youth Wings under provinces

```
National Youth Wing
├── Code:     "YOUTH-NP-001"
├── Type:     Youth Wing
└── Geo Ref:  (Leave empty - National scope)

Bavaria Youth Wing
├── Code:     "YOUTH-BY-001"
├── Type:     Youth Wing
└── Geo Ref:  "de.1.1"  (Tied to Bavaria)

Munich Youth Wing
├── Code:     "YOUTH-MUC-001"
├── Type:     Youth Wing
└── Geo Ref:  "de.1.1.1"  (Tied to Munich)
```

### Scenario 3: Managing Large Geographic Area

**Goal**: Create district-level structure for state with 30 districts

```
Use hierarchical naming:
├── DIST-BY-001   (Munich)
├── DIST-BY-002   (Augsburg)
├── DIST-BY-003   (Passau)
├── DIST-BY-004   (Regensburg)
... (continues to 30)
└── DIST-BY-030

Each with corresponding geographic reference from the system:
├── DIST-BY-001   → Geo: "de.1.1.1"
├── DIST-BY-002   → Geo: "de.1.1.2"
├── DIST-BY-003   → Geo: "de.1.1.3"
... (etc)
```

---

## Part 7: Best Practices

### ✅ DO's

| Practice | Example |
|----------|---------|
| Use consistent naming | `PROV-BY-001`, `PROV-BW-001` |
| Document your codes | Keep a spreadsheet of all codes |
| Assign geography carefully | Match committee scope to geographic unit |
| Use the hierarchy | Central → Provincial → District → Ward |
| Plan before creating | Think through your structure first |
| Create codes systematically | Don't use random numbers |

### ❌ DON'Ts

| Anti-Pattern | Why It's Bad |
|--------------|-------------|
| Vague names | "Committee 1" is not helpful |
| Duplicate codes | Each code must be unique |
| Skip geography | Leads to confusion later |
| Create committees randomly | Breaks organizational structure |
| Change codes | Use original code throughout lifecycle |
| Mixed naming formats | Be consistent across organization |

---

## Part 8: Troubleshooting

### Issue: "Geographic Reference not found"

**Cause**: The geography code doesn't exist in the system

**Solution**:
1. Double-check the code format (e.g., `np.1.12`)
2. Verify the location exists in your country
3. Contact system administrator for valid geographic codes
4. Leave empty for national-scope committees

### Issue: "Can't find the right geographic code"

**Solution**:
1. Check existing committees in your organization
2. Ask your geographic data administrator
3. Reference the geographic context documentation
4. Use the geographic code lookup tool (if available)

### Issue: "Committee name too long or too short"

**Solution**:
- Minimum: 3 characters
- Maximum: 255 characters
- Aim for 30-60 characters for clarity
- Example: "Bavaria Provincial Committee" (29 chars - good!)

### Issue: "Code already exists"

**Cause**: The committee code is not unique

**Solution**:
1. Change the number suffix (001 → 002)
2. Modify the code to make it unique
3. Check if you're creating a duplicate committee

---

## Part 9: Reference Data

### Geographic Code Examples

#### Nepal
```
PROVINCES:
np.1    Province 1
np.2    Province 2
np.3    Province 3
np.4    Province 4
np.5    Province 5
np.6    Province 6
np.7    Province 7

EXAMPLE DISTRICTS (Province 3):
np.3.1    Kathmandu District
np.3.2    Bhaktapur District
np.3.3    Lalitpur District
np.3.4    Kavre District
np.3.5    Sindhupalchok District

EXAMPLE WARDS (Kathmandu):
np.3.1.1  Ward 1, Kathmandu
np.3.1.2  Ward 2, Kathmandu
np.3.1.3  Ward 3, Kathmandu
... (up to Ward 32)
```

#### Germany
```
STATES:
de.1.1    Bayern (Bavaria)
de.1.2    Baden-Württemberg
de.1.3    Nordrhein-Westfalen
de.1.4    Hessen
de.1.5    Rheinland-Pfalz
de.1.6    Schleswig-Holstein
de.1.7    Niedersachsen
de.1.8    Sachsen
de.1.9    Brandenburg
de.1.10   Thüringen
de.1.11   Sachsen-Anhalt
de.1.12   Mecklenburg-Vorpommern
de.1.13   Hamburg
de.1.14   Bremen
de.1.15   Berlin
de.1.16   Saarland

EXAMPLE DISTRICTS (Bayern):
de.1.1.1  München (Munich)
de.1.1.2  Augsburg
de.1.1.3  Ingolstadt
de.1.1.4  Landshut
```

### Committee Code Reference

```
NAMING PATTERNS:

National Level:
CENTRAL-NP-001       Central Committee - National

Regional Level:
PROV-BY-001          Provincial Committee - Bavaria
PROV-BW-001          Provincial Committee - Baden-Württemberg

District Level:
DIST-MUC-001         District Committee - Munich
DIST-AUG-001         District Committee - Augsburg

Ward Level:
WARD-ALT-001         Ward Committee - Altstadt-Lehel

Special Wings:
YOUTH-NP-001         Youth Wing - National
WOMEN-BY-001         Women Wing - Bavaria
STUDENT-MUC-001      Student Wing - Munich
```

---

## Summary Table

| Aspect | Details |
|--------|---------|
| **What is Committee Code?** | Unique identifier for each committee |
| **Format** | Type-Location-Number (e.g., `PROV-BY-001`) |
| **What is Geographic Reference?** | Location code linking committee to geographic area |
| **Format** | Country.Level.ID (e.g., `de.1.1.1` for Munich) |
| **When to use Geography?** | When committee operates in specific geographic area |
| **When to skip Geography?** | For national-level committees |
| **Committee Types** | Central, Provincial, District, Ward, Youth, Women, Student |
| **Geographic Levels** | Country → Region → District → Municipality → Ward |

---

## Quick Start Checklist

When creating a committee:

- [ ] Decide committee type (Central, Provincial, District, Ward, or Wing)
- [ ] Create unique, consistent code (e.g., `PROV-BY-001`)
- [ ] Give committee clear, formal name
- [ ] If geographic: Find correct geographic code (e.g., `de.1.1`)
- [ ] If national/wing: Leave geographic reference empty
- [ ] Review all information is correct
- [ ] Click "Create Committee"
- [ ] Document the committee code in your records

---

## Questions?

For more information on:
- **Geography Context implementation**: See `architecture/geography_contexts/`
- **Committee management code**: See `app/Contexts/Membership/Domain/Committee/`
- **Creating committees UI**: See the tutorial at `/tutorial`

---

**Last Updated**: May 4, 2026  
**Author**: Development Team  
**Version**: 1.0

