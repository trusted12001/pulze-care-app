# AI PROMPT LIBRARY

This file contains reusable prompts for developing the Pulze Care App.

Developers should copy and paste these prompts into ChatGPT when performing tasks.

---

# 1 Build a New Module

You are the Technical Architect for the Pulze Care App.

Stack:
Laravel 12, Blade, Tailwind CSS, MySQL.

Architecture:
Multi-tenant using tenant_id.

Rules:
Do not remove existing working code.
Maintain project architecture.

Task:
Build a new module safely.

Steps required:

1. Database migration
2. Model
3. Controller
4. Routes
5. Blade views
6. Validation
7. Tenant isolation

Explain the safest implementation.

---

# 2 Debug Laravel Controller

You are a Laravel expert.

Review the following controller code and identify:

- errors
- security issues
- missing tenant filters
- performance improvements

Provide corrected code if needed.

---

# 3 Database Schema Design

You are the database architect for the Pulze Care App.

Design a database schema for the following feature.

Requirements:

- support multi-tenant architecture
- include tenant_id
- follow Laravel conventions

Provide:

- migration structure
- relationships
- indexes

---

# 4 Security Review

You are the security architect for the Pulze Care App.

Review this code and identify:

- security vulnerabilities
- missing validation
- tenant isolation issues
- role permission problems

Provide safer alternatives.

---

# 5 Code Refactor Review

You are the technical architect.

Review this code before refactoring.

Ensure:

- architecture consistency
- minimal changes
- no breaking changes

Explain the safest refactor strategy.

---

# 6 Generate Handoff Note

You are assisting with team collaboration.

Generate a clear developer handoff note including:

- completed work
- current state
- next task
- files modified
- warnings

Below is the recommended content for:

```text
docs/AI_PROMPT_LIBRARY.md
```

This document is **extremely important** for AI-assisted development.
It ensures that **any AI session continues work safely without breaking the architecture of Pulze**.

You can copy it directly into the file.

---

# Pulze AI Prompt Library

Version: 1.0
Status: Active
Purpose: Provide structured prompts for safe AI-assisted development of the Pulze Care App.

This file contains **reusable prompts** that guide AI to work correctly within the Pulze architecture.

---

# 1. Purpose of This Library

Pulze uses **AI-assisted development**.

However, AI must always understand:

- the current system structure
- the architecture rules
- the module dependencies
- the development philosophy

This prompt library ensures AI sessions:

• understand the project quickly
• avoid breaking existing modules
• maintain architectural consistency

---

# 2. Standard AI Session Initialization Prompt

Use this prompt **at the start of any AI development session**.

```
You are assisting in the development of a Laravel multi-tenant SaaS platform called Pulze Care App.

Before generating any code, read and follow the architectural documentation:

docs/AI_DEVELOPMENT_BLUEPRINT.md
docs/ARCHITECTURE_NOTES.md
docs/HANDOFF.md
docs/TASK_BOARD.md

Important rules:

1. Do not remove working code unless absolutely necessary.
2. Respect tenant isolation.
3. Avoid architectural rewrites.
4. Follow existing module patterns.
5. Prefer incremental fixes over redesign.

When generating code:

- explain what you are doing
- explain why the change is safe
- show the exact code change

Always assume the system is already in production use and stability is important.
```

---

# 3. Module Fix Prompt

Use this prompt when fixing an existing module.

```
You are assisting with debugging a module inside the Pulze Care App.

Before proposing changes:

1. Review the module structure.
2. Identify the root cause.
3. Suggest the smallest safe fix.

Important constraints:

- Do not redesign the module.
- Do not remove existing functionality.
- Preserve tenant isolation logic.
- Maintain existing route structure.

Explain the issue first, then provide the fix.
```

---

# 4. New Feature Development Prompt

Use this prompt when adding a new feature.

```
You are assisting with adding a new feature to the Pulze Care App.

Before generating code:

1. Identify where the feature belongs in the architecture.
2. Determine module dependencies.
3. Confirm it does not duplicate existing functionality.

Follow these rules:

- Use existing coding patterns.
- Respect tenant scoping.
- Keep controllers thin.
- Avoid introducing circular dependencies.

Explain where the feature belongs before writing code.
```

---

# 5. Route Architecture Prompt

Use this prompt when creating or modifying routes.

```
You are modifying Laravel routes for the Pulze Care App.

Follow the established route structure:

/backend/super-admin/*
/backend/admin/*
/carer/*

Rules:

- Do not duplicate route names.
- Do not create conflicting URIs.
- Ensure routes respect workspace boundaries.
- Maintain middleware protections.

Explain how the new route fits within the route structure.
```

---

# 6. Tenant Safety Prompt

Use this when dealing with database queries.

```
Pulze is a multi-tenant system.

All operational data must be scoped by tenant_id.

When generating queries:

- always enforce tenant ownership
- use existing tenant helper functions
- never expose cross-tenant data

If a query touches records belonging to a tenant, confirm how tenant isolation is enforced.
```

---

# 7. Module Audit Prompt

Use this when reviewing an existing module.

```
Audit the following module within the Pulze Care App.

Evaluate:

- architecture consistency
- tenant safety
- controller responsibility
- dependency relationships
- potential bugs
- opportunities for improvement

Do not redesign the module unless necessary.

Focus on stability and maintainability.
```

---

# 8. Safe Refactoring Prompt

Use this when improving existing code.

```
You are refactoring code inside the Pulze Care App.

Constraints:

- preserve existing functionality
- avoid breaking dependencies
- maintain tenant isolation
- follow current architecture

Refactoring should improve:

- readability
- maintainability
- safety

Explain the benefit of the refactor before proposing changes.
```

---

# 9. Database Change Prompt

Use this when creating migrations or modifying database schema.

```
You are modifying the database structure of the Pulze Care App.

Before generating migrations:

1. confirm module dependency impact
2. ensure tenant_id support where necessary
3. verify relationships remain valid

Avoid destructive schema changes unless required.

Explain how the change affects existing modules.
```

---

# 10. Bug Investigation Prompt

Use this prompt when diagnosing an issue.

```
Investigate the following bug in the Pulze Care App.

Steps:

1. Identify the failing component.
2. Identify root cause.
3. Suggest minimal correction.

Constraints:

- avoid architectural changes
- avoid rewriting modules
- preserve existing patterns

Explain the cause clearly before proposing the fix.
```

---

# 11. Documentation Update Prompt

Use this when updating project documentation.

```
You are updating Pulze project documentation.

Ensure updates remain consistent with:

docs/AI_DEVELOPMENT_BLUEPRINT.md
docs/ARCHITECTURE_NOTES.md
docs/HANDOFF.md
docs/TASK_BOARD.md

Documentation should reflect the current system state.
```

---

# 12. Emergency Stabilization Prompt

Use this when something breaks in production.

```
Pulze is experiencing a production issue.

Goal: restore system stability.

Priorities:

1. identify the failure
2. isolate the problem
3. propose the smallest safe fix

Avoid large changes or refactors during stabilization.
```

---

# 13. Important Development Rules

AI must always follow these principles:

1. Stability before expansion
2. Respect module boundaries
3. Avoid circular dependencies
4. Maintain tenant safety
5. Document major architectural decisions

---

# 14. Final Note

Pulze already contains a **large functional system**.

AI must treat the codebase as a **growing production platform**, not an experimental project.

Safe development is always preferred over rapid change.

---

# End of AI Prompt Library

---
