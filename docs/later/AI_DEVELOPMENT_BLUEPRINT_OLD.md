# AI DEVELOPMENT BLUEPRINT

## Project

Pulze Care App

## Purpose

Pulze Care is a multi-tenant healthcare management platform designed to support care providers in managing staff, service users, care planning, risk management, rota operations, assignments, attendance, reporting, and tenant-level administration across multiple organisations.

The platform includes both Super Admin and Tenant Admin areas, with structured separation between platform-level management and organisation-level operations.

---

# Technology Stack

Backend:
Laravel 12

Frontend:
Blade + Tailwind CSS

Database:
MySQL

Authentication:
Laravel Authentication

Roles & Permissions:
Spatie Laravel Permission

Version Control:
GitHub

---

# System Architecture

Pulze follows a **multi-tenant SaaS-style architecture**.

Key architecture layers include:

- Super Admin layer
- Tenant Admin layer
- Frontend / Carer-facing layer

Each organisation is treated as a **tenant**.

Tenant context is supported through:

- tenants table
- tenant_id on users
- Tenant model
- TenantSetting model
- TenantScope
- ResolvesTenantContext concern

All tenant-aware records must maintain proper tenant isolation.

---

# Code Structure

Controllers

app/Http/Controllers/Backend/Admin  
app/Http/Controllers/Backend/SuperAdmin  
app/Http/Controllers/Frontend

Views

resources/views/backend/admin

Routes

routes/web.php

Models

app/Models

---

# Development Rules

1. Never remove working code unless absolutely necessary.
2. Maintain tenant isolation consistently.
3. Preserve existing UI structure unless redesign is required.
4. Keep updates minimal and targeted.
5. Document architecture changes in ARCHITECTURE_NOTES.md.
6. Prefer consultant-style, step-by-step changes.

---

# Current Modules

### Platform / Core

- Authentication
- Roles and permissions
- Super Admin tenant management
- Super Admin user management
- Support mode
- Tenant settings

### Staff / HR

- Staff profiles
- Contracts
- Registrations
- Employment checks
- Visas
- Payroll
- Bank accounts
- Emergency contacts
- Qualifications
- Training records
- Leave records
- Leave entitlement
- Occupational health clearance
- Driving licence
- Immunisation
- Equality data
- Disciplinary records
- Availability preferences
- Supervision / appraisal
- Staff adjustments

### Service User Management

- Service users
- Service user photos
- Locations

### Care Planning

- Care plans
- Sections
- Goals
- Interventions
- Reviews
- Versions
- Signoffs
- Print support

### Risk Management

- Risk types
- Risk assessments
- Risk controls
- Risk reviews
- Risk assessment profiles

### Rota / Operations

- Shift templates
- Rota periods
- Shifts
- Shift assignments
- Attendance
- Assignments
- Assignment evidence
- Assignment notifications
- Assignment signoffs
- Assignment links
- Assignment events
- Timesheets

### Frontend / Carer Area

- Carer dashboard/controllers
- Carer resident views
- Carer rota
- Attendance
- Handovers area

---

# Current Documentation Goal

The current goal is to align project documentation with the actual implemented architecture so that collaboration between developers remains accurate and reliable.

---

# Known Issues / Verification Points

- Confirm which business tables enforce tenant_id
- Confirm which models use TenantScope
- Review route coverage for all major controllers
- Review which modules are fully completed vs partially implemented
- Improve mobile responsiveness where required
- Refine permission matrix across roles

---

# Team Workflow

When a developer stops work:

1. Push code to GitHub
2. Update HANDOFF.md
3. Update TASK_BOARD.md

Next developer should:

1. Pull repository
2. Read HANDOFF.md
3. Continue from the next verified task listed

---

# AI Prompt Template

Developers should start ChatGPT sessions using this prompt:

"You are the Technical Architect for the Pulze Care App.

Stack:
Laravel 12, Blade, Tailwind CSS, MySQL.

Architecture:
Multi-tenant SaaS-style architecture with Super Admin, Tenant Admin, and Frontend layers.

Rules:
Do not remove working code.
Preserve existing architecture.
Make minimal safe changes.

Current goal:
Work only from verified project state and guide the safest next implementation."
