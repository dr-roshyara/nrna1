# User Manual: Creating a Committee

This guide walks you through creating a new committee in your organisation. Committees are governance bodies that manage elections, members, and organisational activities at various levels.

---

## Prerequisites

- You must be logged in with an **Owner** or **Admin** role in the organisation
- Your organisation must already be created

---

## Step 1: Navigate to Committees

1. Log in to your account
2. From the organisation dashboard, click **Committees** in the navigation menu
3. You will see the Committees overview page showing existing committees grouped by level:
   - **Central Committee** — National-level governance
   - **Provincial Committees** — Regional governance structures
   - **District Committees** — District-level committees
   - **Ward Committees** — Local-level committees

4. Click the **Create Committee** button at the top right

> **URL:** `/organisations/{your-organisation}/committees/create`

---

## Step 2: Fill in Basic Information

### Committee Name (Required)
Enter a clear, descriptive name for your committee:
- **Good:** "Central Committee", "Bavaria Provincial Committee", "Youth Wing Munich"
- **Avoid:** Single words, abbreviations without context

### Committee Code (Required)
Enter a unique identifier code for the committee:
- **Examples:** `CC-001`, `BAV-PROV-01`, `YW-MUC-01`
- Codes must be unique within your organisation — the system validates this as you type
- A green checkmark appears when the code is available

> **Tip:** Use a naming convention like `{LEVEL}-{REGION}-{NUMBER}` to keep codes organised.

---

## Step 3: Select Geographic Scope (Optional)

If your committee operates in a specific geographic area:

1. Click the geographic selector (opens a hierarchical tree of regions)
2. Select the relevant area — for example:
   - **Provincial committee:** Select the province
   - **District committee:** Select the district
   - **Ward committee:** Select the ward
3. The committee type is **automatically derived** from the geographic selection:
   - No geography selected → **Central Committee**
   - Province selected → **Provincial Committee**
   - District selected → **District Committee**
   - Ward selected → **Ward Committee**

> **Note:** Wing committees (Youth, Women, Student) require selecting an explicit type during creation. Geographic committees (Province, District, Ward) are derived automatically from the location you select.

---

## Step 4: Create the Committee

1. Review your entries
2. Click **Create Committee**
3. On success, you are redirected to the committee dashboard

---

## What Happens Next

After creating a committee, you can:

- **View the committee dashboard** — See committee details, code, type, and formation date
- **Add members** — Assign organisation members to roles in the committee
- **Edit committee details** — Update name or status later if needed
- **View in committees list** — The new committee appears grouped by its level

---

## Committee Types Reference

| Type | Geography Required? | Description |
|------|-------------------|-------------|
| Central | No | National-level governance |
| Provincial | Yes (Province) | Regional governance |
| District | Yes (District) | District-level governance |
| Ward | Yes (Ward) | Local-level governance |
| Youth Wing | No | Youth-focused governance body |
| Women Wing | No | Women-focused governance body |
| Student Wing | No | Student-focused governance body |

---

## Troubleshooting

### "Committee code already exists"
Choose a different code. Codes must be unique within your organisation.

### Geographic selector shows no options
Your organisation may not have geographic data configured. Contact your system administrator.

### "You are not authorized"
You need an **Owner** or **Admin** role in the organisation. Contact the organisation owner to upgrade your role.

---

## Related Topics

- [Assigning Members to a Committee](assign-members.md)
- [Editing a Committee](edit-committee.md)
- [Committee Dashboard Overview](committee-dashboard.md)
