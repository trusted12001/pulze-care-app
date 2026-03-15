# Pulze Project Rules

Version: 1.0  
Purpose: Protect the architectural integrity, stability, and long-term maintainability of the Pulze Care App.

This document defines **non-negotiable rules** for development of the Pulze platform.

All developers and AI assistants must follow these rules.

---

# 1. Protect Tenant Isolation

Pulze is a **multi-tenant system**.

Rules:

- Every operational record must belong to a tenant.
- All database queries must respect tenant scoping.
- Controllers must validate tenant ownership.
- Cross-tenant data access must never occur.

If a feature risks exposing cross-tenant data, it must be redesigned.

---

# 2. Do Not Remove Working Code Unnecessarily

Working modules must not be rewritten or removed without strong justification.

Before modifying code:

- confirm the purpose of the existing implementation
- verify whether it is already in production use
- prefer minimal changes over rewrites

Pulze favors **incremental improvement over redesign**.

---

# 3. Respect Existing Module Architecture

Modules already implemented include:

- Authentication
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
- Settings

New features should **extend these modules**, not duplicate them.

---

# 4. Avoid Circular Dependencies

Modules should follow the dependency hierarchy.

Example:

Tenant  
→ Locations  
→ Service Users  
→ Care Plans / Risk Assessments

Staff Accounts  
→ Staff Profiles  
→ Assignments

Shift Templates  
→ Shift Rota

Circular dependencies must never be introduced.

---

# 5. Keep Controllers Thin

Controllers should:

- validate requests
- enforce tenant ownership
- delegate work to models or services
- return responses

Controllers should **not contain large business logic blocks**.

---

# 6. Preserve Route Structure

Routes must follow the workspace pattern.

Examples:

/backend/super-admin/_  
/backend/admin/_  
/carer/\*

Rules:

- route names must remain unique
- URIs must not conflict
- middleware protections must remain intact

---

# 7. Maintain Draft vs Published Workflows

Some modules use **draft workflows**.

Examples:

- Care Plans
- Shift Rota
- Assignments

Draft data should not be visible to carers or operational users until published.

---

# 8. Follow the Existing UI Flow

Pulze modules follow a consistent pattern:

Create  
Edit  
View  
Soft Delete  
Open (deep file view)

New modules should follow the same interaction model.

---

# 9. Respect File Storage Structure

Uploaded files include:

- staff profile photos
- documents
- evidence files

Files must:

- store in the correct disk
- generate valid public URLs
- remain accessible across environments

Always test file access in both local and production environments.

---

# 10. Stability Before Feature Expansion

Before building new major features:

- stabilize existing modules
- test workflows
- fix known issues

Pulze is evolving into a **production SaaS platform**, not an experimental project.

---

# 11. Always Update Documentation

Whenever significant architectural changes occur, update:

docs/AI_DEVELOPMENT_BLUEPRINT.md  
docs/ARCHITECTURE_NOTES.md  
docs/HANDOFF.md  
docs/TASK_BOARD.md

Documentation must reflect the **current state of the system**.

---

# 12. AI Collaboration Rule

When using AI for development, always load project context first.

Recommended initialization:

Read:

docs/AI_MEMORY.md  
docs/PULZE_MASTER_PROMPT.md

This ensures AI understands the Pulze architecture before generating code.

---

# Final Rule

Pulze must remain:

- stable
- secure
- tenant-safe
- maintainable

Every change must protect the **long-term integrity of the system**.
