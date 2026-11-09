I think you are right to pause here. **Before defining how committees receive authority from a Constitution, we need to establish the structural architecture of PublicDigit itself.**

You are asking a more fundamental question:

> What is PublicDigit as a platform, what is an Organisation inside it, how does a User belong to an Organisation, and what roles can that User have within that Organisation?

These are foundational platform rules. They should be defined before we design committee governance.

One important distinction: **PublicDigit's own platform rules and each organisation's Constitution are two different levels of authority.**

- **PublicDigit platform rules** define how the software works: users, organisations, authentication, organisation membership, roles, access and organisation switching.
- **An organisation's Constitution** defines how that particular organisation is governed: its bodies, committees, offices, powers and decision-making procedures.

We should not mix these two levels.

## What we have already decided

I checked the previous PublicDigit decisions and architecture notes. **Yes, we have already defined important parts of this structure. But we have not yet completed one single, ratified PublicDigit platform constitution covering the whole structure.**

The most important existing decisions are:

1. **Central identity:** A person is identified at platform level. Identity is distinct from an organisation's membership.
2. **Multiple organisations:** One person can be associated with several organisations.
3. **PublicDigit bootstrap organisation:** Every newly registered user is automatically associated with the default PublicDigit organisation as a platform-level onboarding arrangement. This is not the same as becoming a formal Member.
4. **Working Organisation:** The system should determine the organisation in which the user is working, rather than treating the default PublicDigit organisation as their working organisation.
5. **Organisation-specific roles:** A person's access or affiliation in an organisation is separate from their formal Membership.

The Working Organisation routing rule is particularly concrete and was approved as B1:

| Number of real organisations (excluding bootstrap PublicDigit) | Login behaviour |
|---:|---|
| 0 | Show Create or Join Organisation |
| 1 | Enter that organisation automatically |
| 2 or more | Ask the user to select an organisation |

The system must not guess when the user belongs to multiple organisations. Eingefügter Text

## What is not yet settled

There are some important distinctions we must preserve.

### 1. Central User Model versus Person

We have discussed a platform-wide identity, but we should not automatically assume that one `User` database record must represent both a human being and their login account.

Our newer Identity discovery distinguishes:

- **Person:** the real human being.
- **Account:** the credentials and login used to access PublicDigit.
- **OrganisationAffiliation:** the person's access and roles within a particular organisation.
- **Membership:** the person's formal membership relationship with an organisation.

The proposed Person/Account separation is still subject to architectural decisions. So I would not freeze the central User Model design yet.

### 2. Your organisation roles

You mentioned:

- User
- Guest
- Staff/Worker
- Observer
- Member
- ElectionMember

We have discussed most of these, but they are not all interchangeable roles.

| Term | What it should mean | Current understanding |
|---|---|---|
| User | Someone with platform access or an organisation-level user affiliation | Decided as distinct from formal Membership |
| Guest | A person with limited organisational access | Organisation affiliation concept |
| Worker/Staff | Someone working for the organisation | Organisation affiliation; exact rules need clarification |
| Observer | Someone permitted to observe | Needs clarification because it can also be election-specific |
| Member | A person formally admitted as a member | Owned by Membership, not simply an access role |
| ElectionMember | Someone participating in an election | Should be examined as an Election-specific relationship, not automatically an organisation role |

**An important rule:** Being a User of an organisation must not automatically make someone a formal Member of that organisation.

Likewise, being an Election participant must not automatically make someone a formal organisational Member.

### 3. PublicDigit has two different organisational concepts

We have also clarified that these must not be confused:

1. **PublicDigit bootstrap organisation:** An infrastructure organisation used during registration. Every registered user receives a User affiliation there, not formal Membership.
2. **PublicDigit operator organisation:** The ordinary organisation representing PublicDigit's own real organisational life, including its workers, members, guests and committees.

This separation was confirmed in the 2 November decision K7.

---

# My recommendation: Establish the PublicDigit Structural Architecture first

I would organise our foundational architecture into six areas:

| No. | Architectural area | Main question |
|---|---|---|
| 1 | Identity & Authentication | Who is the person, and how do they log in? |
| 2 | Organisation | What is an organisation, and how is it created and managed? |
| 3 | Organisation Affiliation | How does a person get access to an organisation, and what roles can they have? |
| 4 | Working Organisation | Which organisation is the user currently working in? |
| 5 | Organisation Structure | How are departments, branches, committees and organisational units represented? |
| 6 | Governance | How does the organisation make decisions under its Constitution or authorised rules? |

A simplified picture:

```text
                  PUBLICDIGIT PLATFORM
                          |
                 Identity & Authentication
                          |
                  Person / Account
                          |
               Organisation Affiliation
                          |
              +-----------+-----------+
              |                       |
         Organisation A         Organisation B
              |                       |
       Organisation Roles      Organisation Roles
              |                       |
       Organisation Units      Organisation Units
              |                       |
          Committees               Committees
              |                       |
          Governance               Governance
              |                       |
        Own Constitution       Own Constitution
```

The user works inside one selected **Working Organisation** at a time.

Each organisation has its own organisational structure and governance rules.

PublicDigit provides the platform infrastructure and common technical capabilities. It must not impose one universal committee or governance model on every organisation.

## How I suggest we proceed

We should not start with database tables, Laravel models or code.

First, we should define the business structure and agree on the terminology. Then we can derive the Bounded Contexts, ownership, relationships and eventually the technical architecture.

I suggest we start with **Decision 1: Identity and Account Architecture**.

One question:

**Should one human being have one central identity in PublicDigit, with one login account that can access multiple organisations, while each organisation maintains its own affiliation, roles and formal membership records?**

My recommendation is **yes**.

For example:

- Nab has one PublicDigit login.
- Nab is a User in the PublicDigit bootstrap organisation.
- Nab is a Member in Organisation A.
- Nab is a Worker in Organisation B.
- Nab is an Observer in Organisation C.
- Nab may participate in an election in Organisation A.

One person, one platform identity, potentially several organisation-specific relationships.

This is the foundation I would like us to settle before moving to Organisation Architecture.