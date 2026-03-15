# Pulze AI Memory

This file contains the essential context for AI sessions working on the Pulze Care App.

Whenever an AI assistant works on this repository, it must assume the following system context.

---

# Project Identity

Project: Pulze Care App

Type: Multi-tenant SaaS care management platform.

Target users:

- Care homes
- Supported living services
- Domiciliary care providers
- Healthcare organizations

Purpose:

Manage staff, service users, care plans, risk assessments, assignments, shift rota, and operational reporting.

---

# Technology Stack

Primary Framework:
Laravel

Supporting Technologies:

- Blade templates
- MySQL database
- Spatie roles & permissions
- File storage for documents and evidence
- JavaScript UI interactions

Architecture type:

Laravel monolith with tenant-aware architecture.

---

# Multi-Tenant Model

Pulze is a multi-tenant system.

Rules:

- Every operational record belongs to a tenant.
- All queries must be tenant scoped.
- Controllers must verify tenant ownership.
- Cross-tenant access must never occur.

Common helpers used:

tenantIdOrFail()

authorizeTenantRecord()

---

# Workspaces

Pulze contains three workspaces:

Super Admin  
Tenant Admin  
Carer

Each workspace has its own:

- routes
- permissions
- layouts
- workflows

---

# Core Modules

The following modules exist in the system.

Authentication  
User Management  
Tenant Management  
Locations  
Staff Profiles  
Service Users  
Risk Assessments  
Care Plans  
Shift Templates  
Shift Rota  
Assignments  
Reports  
Settings

Some modules are fully stable. Others are still undergoing operational testing.

---

# Known Issues

Assignments module:

The **Open / Dive Deeper action throws a TypeError** and needs correction.

---

# Module Dependency Structure

Tenant
├ Locations
│ ├ Service Users
│ │ ├ Care Plans
│ │ └ Risk Assessments
│ └ Assignments
│
├ Staff Accounts
│ └ Staff Profiles
│ └ Assignments
│
├ Shift Templates
│ └ Shift Rota
│
└ Reports

Circular dependencies must be avoided.

---

# Development Philosophy

Pulze follows these rules:

1. Stability before expansion
2. Tenant safety must never be compromised
3. Avoid architectural rewrites
4. Prefer incremental improvements
5. Do not remove working code unnecessarily

---

# Testing Philosophy

Whenever modules are modified, test:

Create  
Edit  
Delete  
Cross-module interactions  
Tenant isolation

Testing must reflect real-world care workflows.

---

# AI Behaviour Expectations

When assisting with Pulze:

- behave like a senior system architect
- protect the architecture
- explain reasoning before generating code
- propose minimal safe changes
- maintain long-term maintainability

Pulze must be treated as a **production-grade SaaS system**.

```

---

# 3. Why This File Is Powerful

Now when you start an AI conversation you just say:

```

Read docs/AI_MEMORY.md before answering.

```

And the AI instantly understands:

• what Pulze is
• how the architecture works
• the modules
• the development philosophy

You don’t need to paste huge prompts again.

---

# 4. Even Better (Advanced Trick)

You can combine it with your **master prompt** like this:

Start any Pulze AI session with:

```

Read docs/AI_MEMORY.md and docs/PULZE_MASTER_PROMPT.md before answering.

```

Now the AI loads:

• architecture
• philosophy
• modules
• testing
• rules

in seconds.

---

# 5. What You Just Built

You now have **a complete AI-assisted development framework**:

| File                             | Purpose               |
| -------------------------------- | --------------------- |
| AI_MEMORY.md                     | AI system memory      |
| PULZE_MASTER_PROMPT.md           | AI boot prompt        |
| AI_DEVELOPMENT_BLUEPRINT.md      | System structure      |
| ARCHITECTURE_NOTES.md            | Design reasoning      |
| HANDOFF.md                       | Developer onboarding  |
| TASK_BOARD.md                    | Development roadmap   |
| AI_PROMPT_LIBRARY.md             | Safe prompts          |
| AI_TECHNICAL_ARCHITECT_PROMPT.md | AI architect behavior |
| WORKFLOW_TEST_PROMPT.md          | Testing system        |

This is **enterprise-grade AI engineering workflow**.
```
