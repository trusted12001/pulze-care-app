# WORKFLOW TEST PROMPT

Use this prompt when starting a new ChatGPT session for Pulze.

---

You are the Technical Architect for the Pulze Care App.

Follow the rules in the project blueprint.

Project:
Pulze Care App

Stack:

- Laravel 12
- Blade
- Tailwind CSS
- MySQL
- Spatie Laravel Permission

Architecture:

- Multi-tenant using tenant_id
- Backend controllers under app/Http/Controllers/Backend/Admin
- Views under resources/views/backend/admin
- Routes in routes/web.php
- Modal CRUD preferred where already used

Rules:

- Do not remove working code unless clearly necessary.
- Preserve existing UI structure unless explicitly asked to redesign.
- Enforce tenant_id consistently.
- Keep changes minimal, targeted, and safe.
- Respect role-based access control.
- Make the response handoff-friendly.

Current context:

- Collaboration docs have been created
- Team workflow is set up
- Current next task is Tenant Profile Page

Please respond in this format:

1. Current understanding
2. Safest next step
3. Files involved
4. Exact implementation plan
5. Risks / checks
6. Handoff note

Task:
Guide the safe implementation of the Tenant Profile Page for Pulze Care App.

# Pulze Workflow Test Prompt

Version: 1.0
Status: Active
Purpose: Provide structured prompts and procedures for testing Pulze modules and workflows safely.

---

# 1. Purpose of This Document

Pulze contains **many interconnected operational modules**.

Testing must ensure:

- modules work independently
- module dependencies behave correctly
- workflows follow real-world care operations
- tenant isolation remains intact

This document provides **AI prompts and test procedures** to verify module behavior.

---

# 2. AI Testing Session Prompt

Use this prompt whenever AI is asked to test a Pulze workflow.

```
You are testing the Pulze Care App.

Before testing any module:

1. Understand the module's role in the system.
2. Identify module dependencies.
3. Identify the expected workflow.

Test the module in the following order:

- Creation
- Editing
- Viewing
- Deleting (soft delete)
- Cross-module interaction

Verify:

• tenant isolation
• data consistency
• UI flow
• dependency integrity

Report any potential architectural risks or bugs discovered.
```

---

# 3. Authentication Workflow Test

Test steps:

1. Login with valid credentials.
2. Verify redirect to correct dashboard.
3. Logout and confirm session termination.
4. Test forgot password workflow.
5. Reset password successfully.
6. Change password while logged in.

Expected result:

Authentication should work across all workspaces without leaking session data.

---

# 4. Tenant Creation Workflow

Test steps:

1. Login as Super Admin.
2. Create new tenant.
3. Confirm tenant appears in tenant list.
4. Edit tenant details.
5. Activate / deactivate tenant.
6. Soft delete tenant.

Verification:

- Tenant must receive unique `tenant_id`.
- Tenant users must be scoped correctly.

---

# 5. User Management Workflow

Test steps:

1. Create a new user under a tenant.
2. Assign role.
3. Edit user details.
4. Deactivate user.
5. Soft delete user.

Verification:

- user belongs to correct tenant
- roles function correctly
- login works for active users

---

# 6. Location Setup Workflow

Test steps:

1. Create new location.
2. Add address details.
3. Add geofence settings.
4. Edit location.
5. Soft delete location.

Verification:

Locations must be selectable in:

- Service Users
- Assignments
- Shift Templates

---

# 7. Staff Profile Workflow

Test steps:

1. Create staff account.
2. Create staff profile.
3. Add employment details.
4. Upload profile photo.
5. Add training and compliance records.
6. Print staff profile.

Verification:

- staff profile linked to staff account
- compliance fields save correctly
- documents upload properly

---

# 8. Service User Workflow

Test steps:

1. Add service user (quick setup).
2. Open service user profile.
3. Add deeper information (clinical flags, communication etc.).
4. Edit service user.
5. Print service user profile.

Verification:

Service users must link correctly to:

- location
- care plans
- risk assessments
- assignments

---

# 9. Risk Assessment Workflow

Test steps:

1. Create new risk assessment.
2. Select service user.
3. Add risk items.
4. Score risk levels.
5. Save assessment.
6. Export PDF.

Verification:

- risk score calculation works
- service user association is correct
- editing works without data loss

---

# 10. Care Plan Workflow

Test steps:

1. Create new care plan.
2. Save as draft.
3. Add care sections.
4. Publish care plan.
5. Sign off digitally.
6. Print PDF.

Verification:

Carers must only see **published care plans**.

---

# 11. Shift Template Workflow

Test steps:

1. Create shift template.
2. Assign location.
3. Define shift times.
4. Define headcount.
5. Duplicate template.
6. Deactivate template.

Verification:

Templates must appear in **Shift Rota module**.

---

# 12. Shift Rota Workflow

Test steps:

1. Create rota period.
2. Select location.
3. Assign staff to shifts.
4. Generate shifts.
5. Publish rota.

Verification:

Staff assignments must match shift templates.

---

# 13. Assignment Workflow

Test steps:

1. Create assignment.
2. Assign staff.
3. Set evidence requirements.
4. Edit assignment.
5. Open assignment details.

Known issue:

The **Open action currently throws a TypeError** and requires fixing.

---

# 14. Reports Workflow

Test steps:

1. Open dashboard reports.
2. Verify statistics display correctly.
3. Navigate to quick reports.
4. Check graphical data accuracy.

Verification:

Reports must reflect:

- staff data
- assignments
- operational activity

---

# 15. Tenant Isolation Test

Critical security test.

Steps:

1. Login as Tenant A.
2. Create records.
3. Login as Tenant B.
4. Attempt to access Tenant A records.

Expected result:

Access must be denied.

---

# 16. File Upload Test

Modules to test:

- staff profile photos
- documents
- evidence uploads

Verification:

- files store correctly
- file URLs resolve properly
- production storage works

---

# 17. Performance Testing

Verify:

- dashboard loads efficiently
- rota generation handles large periods
- reports render quickly

Large datasets should not break the system.

---

# 18. Regression Testing Rule

Whenever a module is modified, re-test:

- Create
- Edit
- Delete
- Cross-module interactions

This prevents hidden breakage.

---

# 19. Final Testing Principle

Pulze must always behave like a **care operations platform**, not just a data management system.

Testing must reflect **real-world care workflows**.

---

# End of Workflow Test Prompt

---

You now have a **complete AI-assisted development system for Pulze**, including:

- Development blueprint
- Architecture documentation
- Developer handoff guide
- Task board roadmap
- AI prompt library
- Technical architect prompt
- Workflow testing prompt

This is **exactly the type of documentation framework used by serious SaaS engineering teams**.
