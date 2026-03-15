# Pulze Development Task Board

Version: 1.0
Status: Post-System Audit Roadmap
Purpose: Track completed work, work in progress, and future development priorities for the Pulze Care App.

---

# 1. Task Board Philosophy

The task board divides work into four categories:

| Status       | Meaning                                   |
| ------------ | ----------------------------------------- |
| Completed    | Stable and trusted modules                |
| Testing      | Implemented but still under validation    |
| Fix Required | Known issues requiring correction         |
| Planned      | Features scheduled for future development |

This prevents **scope confusion and architectural drift**.

---

# 2. Completed & Stable Modules

These modules are considered **production-ready**.

## Authentication & Account Access

Features:

- Login
- Logout
- Forgot Password
- Reset Password
- Change Password

Status:
Complete — Tested — Trusted

---

## User Management

Features:

- Create User
- Edit User
- Assign Role
- Soft Delete User
- Activate / Deactivate User
- View User Profile

Status:
Complete — Tested — Trusted

---

## Tenant Management

Features:

- Create Tenant
- Edit Tenant
- View Tenant
- Soft Delete Tenant
- Tenant Status
- Support Mode Access

Status:
Complete — Tested — Trusted

Future improvement:

- Address fields could be structured (postcode, address line etc.)

---

## Location Setup

Features:

- Add Location
- Edit Location
- Soft Delete
- Location Type
- Address Structure
- Geofencing Settings

Status:
Complete — Tested — Trusted

Dependency:

Location is required for:

- Service Users
- Shift Templates
- Assignments

---

## Settings

Features:

- Company Logo Upload
- Office Address Update

Status:
Complete — Tested — Trusted

Future expansion possible.

---

## Reports

Features:

- Dashboard analytics
- Quick reports
- Graphical insights

Status:
Complete — Developer-tested — Trusted

Future expansion possible.

---

# 3. Implemented but Under Validation

These modules are **fully implemented but still undergoing operational testing**.

---

## Staff Profiles

Status:

Complete — Testing ongoing — Trusted so far

Features include:

- Staff Profile Creation
- Employment Details
- Compliance Data
- DBS Information
- Right to Work
- Line Manager
- Profile Photo
- Contracts
- Training
- Qualifications
- Occupational Health
- Immunization
- Emergency Contacts
- Driving Details
- Disciplinary Records
- Documents
- Print Profile

Reason testing continues:

Large data structure and multiple sections.

---

## Service Users

Status:

Complete — Testing ongoing — Trusted so far

Features:

Quick Setup:

- Name
- DOB
- Gender
- Contact
- Address
- Placement Type
- Room
- Admission Date
- Diet
- Allergies

Deep File Tabs:

- Identity & Inclusion
- Communication
- Clinical Flags
- Risk
- Safeguarding
- Consent
- GP & Pharmacy
- Preferences

Additional:

- Print Profile

---

## Risk Assessments

Status:

Complete — Developer-tested — Trusted so far

Features:

- Create Assessment
- Edit
- Soft Delete
- Filter
- Deep Assessment Builder
- Risk Scoring
- Risk Types
- PDF Export

Risk types include:

- Absconding
- Aggression
- Behavioral Distress
- Choking
- Fire Safety
- Medication Risk
- Pressure Ulcer
- Safeguarding
- Neglect

---

## Care Plans

Status:

Complete — Developer-tested — Trusted so far

Features:

- Create Care Plan
- Draft / Publish
- Edit
- Soft Delete
- Versioning
- Deep Sections
- Digital Sign-Off
- Print PDF

Sections include:

- Health Goals
- Nutrition
- Medication
- Mobility
- Communication
- Personal Care
- Emotional Well-being
- Preferences
- Reviews

---

## Shift Templates

Status:

Complete — Developer-tested — Trusted so far

Features:

- Create Template
- Edit
- Duplicate
- Deactivate
- Soft Delete

Fields include:

- Location
- Shift Name
- Role
- Start Time
- End Time
- Break
- Head Count
- Skills

---

## Shift Rota

Status:

Complete — Developer-tested — Trusted so far

Features:

- Create Rota Period
- Assign Staff
- Generate Shifts
- Publish Rota
- Edit Assignments
- Soft Delete

Dependency chain:

Location → Shift Template → Rota

---

# 4. Modules Requiring Fix

## Assignments

Status:

Functional but requires refinement.

Features:

- Create Assignment
- Edit
- Soft Delete
- Evidence options
- GPS requirement
- Photo requirement
- Signature requirement
- Recurring tasks

Issue:

The **Open / Dive Deeper action throws a TypeError**.

Priority:

Medium.

Fix needed before production reliance.

---

# 5. Planned System Enhancements

These features are planned but not yet implemented.

---

## Shift Attendance Tracking

Needed for:

- Check-in
- Check-out
- Hours worked
- Overtime calculations

Required for future reporting.

---

## Notifications System

Examples:

- Assignment alerts
- Missed tasks
- Rota updates
- Care plan changes

---

## Audit Logging

Track:

- edits
- deletes
- status changes
- document uploads
- sign-offs

Important for regulatory compliance.

---

## SaaS Platform Features

Future platform expansion:

- Tenant subscription billing
- Tenant lifecycle management
- Usage analytics
- Advanced tenant settings

---

# 6. Current Development Priorities

Recommended order:

Priority 1
Fix Assignment module deep view error.

Priority 2
Continue operational testing of:

- Staff Profiles
- Service Users
- Care Plans
- Risk Assessments
- Rota system

Priority 3
Add shift attendance tracking.

Priority 4
Add notification system.

Priority 5
Add audit logging.

---

# 7. Important Development Rule

Before implementing new features, always check:

```
docs/AI_DEVELOPMENT_BLUEPRINT.md
docs/ARCHITECTURE_NOTES.md
docs/HANDOFF.md
docs/TASK_BOARD.md
```

These documents define the **current system architecture and priorities**.

---

# End of Task Board

---

If you want, the **next document we should generate** (very powerful for AI collaboration) is:

```
docs/AI_PROMPT_LIBRARY.md
```

That file will contain **ready-made prompts** for AI to safely work on Pulze without breaking architecture.
