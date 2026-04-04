## 🔗 **Membership Dashboard URL**

The membership dashboard is accessible at:

```
/organisations/{organisation-slug}/membership
```

### **Examples:**

| Organisation Slug | Full URL |
|-------------------|----------|
| `namaste-nepal` | `/organisations/namaste-nepal/membership` |
| `green-earth-alliance` | `/organisations/green-earth-alliance/membership` |
| `berlin-business-association` | `/organisations/berlin-business-association/membership` |

---

## 📍 **How Users Access It**

### **1. From Organisation Show Page**
- Navigate to `/organisations/{slug}`
- Look for the **"Membership"** zone card (purple theme)
- Click **"Membership Dashboard"**

### **2. From Action Buttons** (Admins/Owners only)
- On organisation show page, find **Step 5: Manage Membership** card
- Click **"Manage Membership"**

### **3. Direct URL**
```
https://your-domain.com/organisations/{organisation-slug}/membership
```

---

## 🔐 **Access Control**

| Role | Can Access |
|------|------------|
| Owner | ✅ Yes |
| Admin | ✅ Yes |
| Commission | ✅ Yes |
| Member | ✅ Yes |
| Voter | ❌ No (no role in organisation) |
| Outsider | ❌ No (403 Forbidden) |
| Guest | ❌ Redirect to login |

---

## 🧪 **Test in Development**

```bash
# Example for local development
http://localhost:8000/organisations/namaste-nepal/membership
```

**Route name:** `organisations.membership.dashboard`

```php
// In your code, generate URL with:
route('organisations.membership.dashboard', $organisation->slug)
```

---

The dashboard is now the **central hub** for all membership management activities! 🚀