# ARCHITECTURE NOTES

This document records major design decisions to keep the architecture consistent.

---

## Multi-Tenant Design

Each organisation has isolated data using:

tenant_id

All queries must filter with:

where('tenant_id', auth()->user()->tenant_id)

This is a core rule of the Pulze Care App.

---

## Role System

Current roles include:

- admin
- manager
- carer
- viewer

Spatie Laravel Permission is used for role and permission management.

---

## Controller Structure

Backend controllers are stored under:

app/Http/Controllers/Backend/Admin

---

## View Structure

Backend admin views are stored under:

resources/views/backend/admin

---

## Routing

Main web routes are stored in:

routes/web.php

---

## UI Pattern

Most backend modules should follow:

- index page
- modal-based create/edit where applicable
- minimal UI disruption

Avoid unnecessary separate pages unless the feature truly requires them.

---

## Security Principles

- tenant isolation
- role-based access control
- validation on all forms
- preserve existing working code unless change is necessary

---

## Development Preference

For this project, changes should be:

- step-by-step
- minimal
- safe
- consultant-style

Every change should be easy for another developer to continue.

---

## Future Notes

Use this file to record major architectural decisions whenever a new module or pattern is introduced.

---

## Current Tenant Enforcement Pattern

Pulze currently uses a **manual tenant-enforcement pattern** in controllers rather than relying consistently on a global model scope.

Observed pattern in tenant-owned admin modules (example: StaffProfileController):

- Resolve tenant context using `ResolvesTenantContext`
- Use `tenantIdOrFail()` for current tenant resolution
- Filter index/list queries with `where('tenant_id', $tenantId)`
- Filter lookup/dropdown data by `tenant_id`
- Set `tenant_id` explicitly during create/store operations
- Protect show/edit/update/delete operations using `authorizeTenantRecord()`
- Protect restore/force-delete actions for soft-deleted records as well

### Important Note

`TenantScope.php` exists, but key models reviewed so far do not consistently apply it as a global scope.

This means tenant isolation currently depends heavily on disciplined controller implementation.

### Architectural Guidance

Until a future controlled refactor is planned, developers should follow the manual tenant-safe controller pattern consistently across all tenant-owned modules.

### Verification Status

The tenant-safe controller pattern has been verified in at least these core admin modules:

- StaffProfileController
- ServiceUserController

Observed consistency includes:

- tenant resolution via `tenantIdOrFail()`
- list filtering by `tenant_id`
- lookup filtering by `tenant_id`
- explicit tenant assignment on create/store
- record-level authorization via `authorizeTenantRecord()`
- protection of restore and force-delete actions

This increases confidence that Pulze currently uses a deliberate manual tenant-enforcement strategy across important modules.

---

## Routes Audit Summary

The `routes/web.php` audit confirms that Pulze is already a substantially wired application with the following active route layers:

- Public / Auth
- Super Admin
- Admin
- Frontend / Carer
- Account Settings
- Assignment-related authenticated API-style routes

### Confirmed Routed Domains

- Super Admin tenants and users
- Support mode
- Admin users
- Staff profiles and nested staff modules
- Service users
- Locations
- Risk assessments, controls, reviews, items, and types
- Care plans and nested sections/goals/interventions/reviews/signoffs
- Shift templates
- Rota periods
- Assignments
- Reports
- Tenant settings
- Frontend carer workflow

### Route Observations

- `service-users/{service_user}/profile` appears duplicated and should be cleaned later
- `rota-periods` uses both explicit routes and resource routes, which should be reviewed for overlap
- Some report routes are marked as stubs and may not represent fully completed features

### Architectural Conclusion

The project is much more mature than the initial documentation suggested.

Future task planning should be based on verified gaps, cleanup priorities, or module refinement rather than early assumptions.

Below is the **recommended content** for:

```text
docs/ARCHITECTURE_NOTES.md
```

You can copy it **directly** into that file.

This document is different from the blueprint.
The blueprint defines **what exists**.
Architecture notes explain **why things are built the way they are**.

---

# Pulze Architecture Notes

Version: 1.0
Status: Post-Audit Architecture Notes
Purpose: Explain architectural reasoning and design principles of the Pulze platform.

---

# 1. Purpose of This Document

This document records **architectural decisions and reasoning** behind the Pulze Care App.

While `AI_DEVELOPMENT_BLUEPRINT.md` describes **system structure**, this file explains:

• why certain patterns were chosen
• why some modules are designed the way they are
• architectural rules developers and AI must respect

The goal is to prevent **architecture drift** as the project grows.

---

# 2. Core Architecture Philosophy

Pulze is designed as a **multi-tenant care management SaaS** with the following principles:

### Principle 1 — Tenant Isolation First

Every operational record belongs to a **tenant organization**.

No tenant must ever see another tenant's data.

Key implementation patterns:

- `tenantIdOrFail()`
- `authorizeTenantRecord()`
- tenant scoped queries

This rule must never be bypassed.

---

### Principle 2 — Workspace Separation

Pulze operates with **three workspaces**:

| Workspace    | Purpose                   |
| ------------ | ------------------------- |
| Super Admin  | Platform management       |
| Tenant Admin | Organizational operations |
| Carer        | Daily care delivery       |

Each workspace has its own:

• navigation
• UI layout
• permissions
• workflows

Cross-workspace access must be intentional and controlled.

---

### Principle 3 — Draft → Publish Workflows

Several modules implement a **draft workflow**.

Examples:

• Care Plans
• Shift Rota
• Assignments

Draft states exist to prevent incomplete information from reaching carers.

Example lifecycle:

```text
Draft → Review → Publish → Active
```

Carers should generally consume **published operational data**, not drafts.

---

### Principle 4 — Quick Setup + Deep File

Several modules follow the pattern:

**Quick Setup → Deep File Management**

Examples:

| Module           | Quick Setup    | Deep File                |
| ---------------- | -------------- | ------------------------ |
| Service Users    | basic identity | clinical details         |
| Risk Assessments | initial record | risk items               |
| Care Plans       | draft setup    | structured care sections |

This pattern reflects **real care operations** where full information is not available immediately.

---

### Principle 5 — Template-Based Scheduling

The rota system uses **shift templates**.

Structure:

```text
Shift Template → Rota Period → Staff Assignment
```

Benefits:

• reduces repetitive configuration
• standardizes shift patterns
• enables easier reporting

Templates must always belong to **locations**.

---

### Principle 6 — Operational Evidence Capture

Assignments support verification via:

• GPS
• photo evidence
• notes
• signatures

This is important for:

• compliance
• accountability
• care verification

Evidence should always be traceable to:

```text
Assignment → Staff → Service User → Location
```

---

### Principle 7 — Analytics as a First-Class Feature

The Reports module is not an afterthought.

Pulze treats analytics as a **core layer** of the platform.

Reports aggregate data from:

• assignments
• staff activity
• service user care
• evidence uploads
• location workload

This enables management insight.

---

# 3. System Layer Model

Pulze follows a **three-layer architecture**.

### Platform Layer

Provides infrastructure services.

Examples:

• Authentication
• Tenant management
• Settings
• Support mode

These modules support the system itself.

---

### Operational Layer

Handles real-world care operations.

Examples:

• Staff Profiles
• Service Users
• Assignments
• Care Plans
• Risk Assessments
• Shift Rota

These modules represent the **core business domain**.

---

### Intelligence Layer

Provides operational insights.

Example:

• Reports

Reports should consume data from operational modules without tightly coupling to their internal logic.

---

# 4. Module Dependency Philosophy

Dependencies should always flow **downward**, never circular.

Example dependency chain:

```text
Tenant
 ├─ Locations
 │   ├─ Service Users
 │   │   ├─ Care Plans
 │   │   └─ Risk Assessments
 │   └─ Assignments
 │
 ├─ Staff Accounts
 │   └─ Staff Profiles
 │       └─ Assignments
 │
 └─ Reports
```

AI must avoid creating circular dependencies.

---

# 5. Route Structure Philosophy

Routes are grouped by **workspace and function**.

Example pattern:

```text
/backend/super-admin/*
/backend/admin/*
/carer/*
```

This ensures:

• clear access boundaries
• simpler permission control
• easier navigation

---

# 6. Controller Responsibility Guidelines

Controllers should remain **thin**.

They should primarily:

• validate requests
• resolve tenant context
• authorize records
• delegate to models or services

Controllers should not contain heavy business logic when avoidable.

---

# 7. Known Architectural Adjustments

The following fixes were identified during the audit.

### Type-safe ownership checks

Certain nested resource checks required integer casting:

```php
abort_unless((int)$child->parent_id === (int)$parent->id, 404);
```

This prevents issues caused by strict comparison of string vs integer IDs.

---

### File storage URL differences

Local Laravel environments and production hosting environments behaved differently for public disk URLs.

The profile photo URL accessor had to account for environment-specific storage paths.

Future file URL logic should be tested in both environments.

---

# 8. Known Module Stability Notes

### Highly Stable Modules

• Authentication
• User Management
• Tenant Management
• Locations
• Reports

---

### Stable but still being validated

• Staff Profiles
• Service Users
• Risk Assessments
• Care Plans
• Shift Rota

---

### Needs refinement

• Assignments deep-view functionality

---

# 9. Future Architecture Considerations

The following systems will likely be added later.

### Notifications

Examples:

• assignment alerts
• overdue alerts
• rota updates

---

### Shift Check-In / Check-Out

Needed to support:

• hours worked reports
• attendance tracking

---

### Audit Logging

Track:

• edits
• deletes
• status changes
• sign-offs

---

### Subscription / Billing

Required for SaaS scalability.

---

# 10. Architectural Rule for Future Development

When adding new functionality, developers and AI should ask:

1. Which workspace does this belong to?
2. Does this module already exist?
3. Does it depend on another module?
4. Does it require tenant scoping?
5. Should it support draft/publish workflow?
6. Does it affect reports?
7. Does documentation need updating?

---

# 11. Final Architectural Note

Pulze has moved beyond an early prototype.

The system now has:

• strong tenant isolation
• layered architecture
• operational care modules
• evidence tracking
• analytics reporting

Future work should focus on:

• stabilization
• testing
• controlled expansion

rather than uncontrolled feature addition.

---

# End of Architecture Notes

---

If you want, the **next document we should generate** is:

```
docs/HANDOFF.md
```

This is **very important** because it allows:

• another developer
• Saeed
• or AI

to immediately understand:

• what the system is
• what is complete
• what is unfinished
• what to work on next.
