Below is the updated content for `docs/AI_DEVELOPMENT_BLUEPRINT.md`.

---

# Pulze AI Development Blueprint

## 1. Project identity

**Project name:** Pulze Care App
**Project type:** Multi-tenant SaaS care management platform
**Primary stack:** Laravel
**Architecture style:** Tenant-scoped monolith with role-based workspaces
**Core roles:** Super Admin, Tenant Admin, Carer
**Primary goal:** Enable care providers to manage staff, service users, care planning, risk, assignments, rota, evidence, and reporting within isolated tenant environments.

---

## 2. Current architectural truth

Pulze is no longer at concept stage. It has a substantial implemented backend and tenant-admin workspace. AI must treat the current codebase as an existing product, not a greenfield app.

### Implemented architectural foundations

- Multi-tenant SaaS structure is in place
- Tenant isolation is handled manually in controllers
- `tenantIdOrFail()` is the standard tenant resolution pattern
- `authorizeTenantRecord()` is the standard tenant record protection pattern
- Spatie roles and permissions are in use
- Separate role layers exist:
    - Super Admin workspace
    - Tenant Admin workspace
    - Carer workspace

- Support Mode exists for Super Admin entering Tenant Admin workspace
- Carer workspace exists as a separate mobile-first interface

### Verified route state

Route audit confirmed:

- no duplicate route names
- no duplicate method + URI routes
- no repeated controller actions
- no obvious route grouping issues

Conclusion: route layer is stable enough; future work should focus on controlled refinement, not destructive reorganization.

---

## 3. Platform layer model

Pulze should be understood in 3 layers.

### A. Platform / shared subsystem layer

Cross-cutting capabilities that support the app:

- Authentication & Account Access
- Role & Permission Management
- Tenant Resolution / Tenant Isolation
- Support Mode
- File Storage / Uploads
- Evidence handling
- Reporting foundation
- Future: notifications, audit logs, billing, GPS enforcement

### B. Operational business modules

Modules used by tenant admins and carers:

- User Management
- Tenant Management
- Locations
- Staff Profiles
- Service Users
- Risk Assessments
- Care Plans
- Shift Templates
- Shift Rota
- Assignments
- Reports
- Tenant Settings

### C. Intelligence / analytics layer

- Reports Overview
- Assignment analytics
- Staff performance analytics
- Service user activity analytics
- Location workload analytics
- Evidence analytics
- Future: hours and shift logs

---

## 4. Workspace model

### 4.1 Super Admin workspace

Purpose:

- manage platform-level users
- manage tenants
- enter support mode
- oversee platform operations

Current reality:

- only some Super Admin dashboard cards are implemented
- fully developed modules in owner/super-admin space are mainly:
    - Manage Users
    - Manage Tenants

- other cards are placeholder or not yet wired

### 4.2 Tenant Admin workspace

Purpose:

- run day-to-day tenant operations
- manage staff and service users
- create care structures
- manage assignments and rota
- view reports
- manage tenant branding/settings

This is currently the most developed workspace.

### 4.3 Carer workspace

Purpose:

- mobile-first daily care delivery interface
- separate layout from admin
- should consume published care and operational tasks
- future support mode into carer workspace is planned, but not yet implemented

---

## 5. Tenant model

Pulze uses a closed-account, admin-provisioned SaaS model.

### Provisioning flow

1. Super Admin creates tenant
2. Super Admin creates initial tenant admin user
3. Tenant Admin creates internal users
4. Users do not self-register
5. Users are expected to change password after first login

### Tenant principles

- every tenant-owned operational record must be tied to `tenant_id`
- all tenant-bound queries must be scoped
- all tenant-bound controllers must enforce tenant access
- no tenant should see another tenant's data

### Tenant lifecycle policy

Current state:

- soft delete behavior impact is not fully defined

Recommended future policy:

- **Active:** tenant users can access normally
- **Inactive/Suspended:** tenant users cannot log in or operate
- **Soft Deleted:** tenant hidden from normal operations, no tenant access, data retained for audit/recovery

AI should not implement cascading destructive deletes for tenant shutdown without explicit instruction.

---

## 6. Current module status map

## 6.1 Shared subsystems

### Authentication & Account Access

Status: Complete, tested, trusted

Features:

- Login
- Logout
- Forgot Password
- Reset Password
- Change Password

Notes:

- no public registration
- internally provisioned accounts only

---

## 6.2 Business modules

### User Management

Status: Complete, tested, trusted

Features:

- Create User
- Edit User
- Assign Role
- Soft Delete User
- Activate/Deactivate User
- View User Profile

---

### Tenant Management

Status: Complete, tested, trusted

Features:

- Create Tenant
- View Tenants
- View Tenant Details
- Edit Tenant
- Soft Delete Tenant
- Activate/Deactivate Tenant
- Enter Support Mode

Note:
Support Mode should be treated as a shared subsystem, not just a tenant-module feature.

---

### Locations / Location Management

Status: Complete, tested, trusted

Features:

- Create Location
- Edit Location
- View Location
- Soft Delete Location
- Active/Inactive status
- Location type
- Geofencing radius
- Address fields
- Contact info
- Longitude / Latitude

Dependencies:

- Tenant context

Downstream dependencies:

- Staff Profiles
- Service Users
- Shift Templates
- Assignments
- Reports
- Future GPS enforcement

---

### Staff Profile Management

Status: Complete, testing ongoing, trusted so far

Purpose:
Operational HR/compliance layer for staff beyond basic user account identity.

Dependencies:

- User Accounts
- Locations

Core features:

- Create Staff Profile from existing staff account
- Edit Staff Profile
- View profile summary
- Soft delete
- Upload profile photo
- Print staff profile

Submodules:

- Contracts
- Payroll
- Bank Accounts
- Registrations
- Employment Checks
- Visa / Right to Work
- Training
- Supervisions
- Qualifications
- Occupational Health
- Immunisations
- Leave
- Availability
- Emergency Contacts
- Equality Data
- Adjustments
- Driving
- Disciplinary
- Documents

Important note:
Several controllers needed integer casting in parent-child ownership checks:

```php
abort_unless((int) $child->foreign_id === (int) $parent->id, 404);
```

---

### Service User Management

Status: Complete, developer-tested, trusted so far

Purpose:
Care-recipient profile and operational care identity.

Dependencies:

- Locations

Core features:

- Add Service User
- Edit
- Soft Delete
- View Overview
- Open Service User File
- Print as PDF

Design pattern:

- quick registration first
- deep enrichment later

Deeper sections include:

- Identity and Inclusion
- Communication
- Clinical Flags
- Baseline
- Risk and Safeguarding
- Legal and Consent
- Preferences
- GP and Pharmacy
- Tags

Downstream dependencies:

- Risk Assessments
- Care Plans
- Assignments
- Reports

---

### Risk Assessment Management

Status: Complete, developer-tested, trusted so far

Dependencies:

- Service Users

Core features:

- Create Assessment
- Edit
- Soft Delete
- Filter by Title
- Filter by Service User
- Filter by Status
- Open Full Assessment
- Print to PDF

Design pattern:

- quick setup first
- detailed structured file later

Sections / risk categories include:

- Absconding / Missing
- Aggressiveness
- Behavioural Distress
- Choking / Dysphagia
- Controlled Substances
- Distress
- Epilepsy / Seizure
- Falls
- Fire Safety
- Medication Safety
- Pressure Ulcer
- Safeguarding Risk
- Neglect

Risk item structure includes:

- risk type
- risk owner
- context/description
- likelihood
- severity
- auto score
- risk band
- status

---

### Care Plan Management

Status: Complete, developer-tested, trusted so far

Dependencies:

- Service Users
- likely informed by Risk Assessments

Core features:

- Create Care Plan
- Edit
- Soft Delete
- Filter by Title
- Filter by Service User
- Filter by Status
- Open Full Care Plan
- Print to PDF

Lifecycle:

- Save Draft
- Publish
- Version awareness
- Review tracking
- Sign-off

Important rule:
Carers should only consume published care plans, not incomplete drafts.

Sections include:

- Overview
- Identity and Inclusion
- Health
- Nutrition
- Medication
- Mobility
- Communication
- Personal Care
- Emotional Well-being
- Improved Mobility
- Risk Assessment
- Preferences
- Review
- Sign-off

---

### Shift Template Management

Status: Complete, developer-tested, trusted so far

Dependencies:

- Locations

Purpose:
Reusable staffing pattern layer beneath rota.

Features:

- Create Shift Template
- Edit
- Soft Delete
- Activate/Deactivate
- Duplicate

Fields:

- Location
- Shift Name
- Role
- Start Time
- End Time
- Break
- Head Count
- Skills
- Notes

Downstream dependency:

- Shift Rota

---

### Shift Rota Management

Status: Complete, developer-tested, trusted so far

Dependencies:

- Locations
- Shift Templates
- Staff Profiles

Core features:

- Create Rota Period
- Edit
- Soft Delete
- View Overview
- Open Rota Builder
- Generate Shifts
- Publish Rota

Lifecycle:

1. Create rota period
2. Assign staff to rows
3. Generate shifts
4. Publish rota

Important rule:
Draft rota should not be visible to carers until published.

---

### Assignments

Status: Complete, developer-tested, needs refinement before trusted

Dependencies:

- Staff Profiles
- Service Users
- optionally Locations

Core features:

- Create Assignment
- Edit
- Soft Delete
- Open / Dive deeper

Fields / controls include:

- Title
- Type
- Start Window
- End Window
- Due Date
- Priority
- Assigned Staff
- Optional Location
- Optional Service User
- Description
- Requirements
    - GPS required
    - Photo evidence
    - Signature

- Recurring frequency

Known issue:

- Open / dive deeper currently throws a TypeError
- module should be treated as not yet fully hardened

---

### Reports

Status: Complete, developer-tested, trusted

Dependencies:

- Assignments
- Staff
- Service Users
- Locations
- Evidence
- future shift logs

Subreports:

- Overview dashboard
- Assignments Report
- Staff Performance Report
- Service User Activity Report
- Location Workload Report
- Evidence Report
- Hours & Shift Logs (future)

Important note:
Reports is a cross-module intelligence layer, not a standalone isolated CRUD module.

---

### Tenant Settings

Status: Complete, developer-tested, trusted

Features:

- Logo Upload
- Office Address Update

Purpose:

- tenant branding
- document identity
- print output header

Keep intentionally minimal for now.

---

## 6.3 Placeholder / not fully built items

These exist conceptually or visually but are not yet treated as fully implemented:

- several Super Admin dashboard cards
- Hours & Shift Logs detailed report
- some insights/statistics elements
- some knowledge-base style items
- broader audit logging
- subscription/billing
- fuller platform settings
- carer support mode
- complete shift hours integration
- possibly handovers, nudges, and other carer workflow items if not yet fully built/wired

AI must not assume these are production-ready unless explicitly verified.

---

## 7. Module dependency chain

Use this model when extending the platform.

```text
Tenant
 ├── Settings
 ├── Users
 │    └── Staff Profiles
 │         ├── Shift Rota
 │         ├── Assignments
 │         └── Reports
 ├── Locations
 │    ├── Staff Profiles
 │    ├── Service Users
 │    ├── Shift Templates
 │    ├── Assignments
 │    └── Reports
 ├── Service Users
 │    ├── Risk Assessments
 │    ├── Care Plans
 │    ├── Assignments
 │    └── Reports
 ├── Shift Templates
 │    └── Shift Rota
 ├── Assignments
 │    ├── Evidence
 │    └── Reports
 └── Reports
```

---

## 8. AI development rules

AI must follow these rules when generating or modifying Pulze code.

### 8.1 Never rebuild what already exists

Before generating new code, AI must first ask:

- does this module already exist?
- is it complete?
- is it partial?
- is it placeholder only?
- is the requested work a refinement rather than a greenfield build?

### 8.2 Respect tenant isolation

AI must not generate tenant-owned modules without:

- tenant resolution
- tenant authorization
- tenant-safe queries
- tenant-safe related model handling

Preferred controller patterns:

- `tenantIdOrFail()`
- `authorizeTenantRecord()`

### 8.3 Respect workspace boundaries

AI must distinguish between:

- Super Admin workspace
- Tenant Admin workspace
- Carer workspace

Do not casually mix routes, views, or flows across these layers.

### 8.4 Preserve existing code unless necessary

Pulze development preference:

- do not remove existing code unless necessary
- prefer precise updates over broad rewrites
- keep design continuity
- avoid breaking live tenant usage

### 8.5 Prefer completion and hardening over uncontrolled expansion

If a module is almost complete but has a known flaw, AI should usually recommend:

- audit current implementation
- refine
- test
- then extend

### 8.6 Document architectural decisions

Any major new subsystem should be reflected in:

- architecture notes
- task board
- handoff
- prompt library
- test prompts

---

## 9. Current known issues and caution points

### High-confidence known issue

- Assignments open/deep view throws TypeError

### Recently fixed pattern

- nested parent-child strict comparisons causing false 404 due to `"15" === 15`
- use integer casting in ownership checks

### Recently fixed environment-specific issue

- profile photo rendering required environment-aware URL generation
- current deployment did not behave like default Laravel public disk URL pattern
- AI should be careful with file URL assumptions in this project

### Unfinalized policy

- exact tenant soft delete / suspension access behavior

---

## 10. Recommended next priority order

### Priority 1 — Hardening

- fix Assignments deep/open path
- review evidence flow
- continue testing Staff Profiles
- continue testing Service Users, Risk, Care Plans, Rota in live tenant usage

### Priority 2 — Shift execution and hours

- shift check-in
- shift check-out
- hours worked calculation
- reports integration for hours and logs

### Priority 3 — Carer operational maturity

- audit carer workspace modules
- confirm what is complete vs placeholder
- later plan Super Admin support mode into carer workspace

### Priority 4 — Audit / compliance maturity

- audit trail
- action logging
- who changed what
- who verified evidence
- support session logging

### Priority 5 — SaaS/platform maturity

- subscriptions / billing
- tenant lifecycle enforcement
- advanced platform settings
- notification system

---

## 11. AI prompt posture for future work

When AI receives a Pulze development request, it should think in this order:

1. Which workspace is this for?
2. Is this a shared subsystem or a business module?
3. Does the module already exist?
4. What does it depend on?
5. Does it require tenant-bound logic?
6. Is it safe to implement now, or should it be audited first?
7. What documentation must be updated after the change?

---

## 12. Definition of terms for this project

### Platform / System

The whole Pulze Care App.

### Module

A major business capability area with its own screens, logic, routes, and workflow.
Examples:

- Staff Profiles
- Service Users
- Assignments
- Care Plans

### Feature

A capability within a module.
Examples:

- Upload profile photo
- Publish care plan
- Duplicate shift template

### Function / Action

A specific user action.
Examples:

- Create
- Edit
- Delete
- Publish
- Upload

### Shared subsystem

A cross-cutting capability used across modules.
Examples:

- Authentication
- Tenant Isolation
- Support Mode
- File Storage
- Reporting foundation

---

## 13. Final blueprint conclusion

Pulze is no longer a concept-only system. It is an actively structured multi-tenant care operations platform with:

- strong tenant-admin operational depth
- a stable route foundation
- strong core care modules
- an unusually mature reports layer
- a separate carer workspace
- support mode already in place for tenant-admin support

The immediate focus should not be broad rebuilding.
The immediate focus should be:

- refine
- harden
- test
- document
- then extend carefully

---
