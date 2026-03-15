# Pulze – Read Me First

This project uses **structured AI-assisted development**.  
Before making any changes, follow these steps.

---

## Step 1 — Understand the System

Read these documents in order:

1. docs/AI_MEMORY.md
2. docs/AI_DEVELOPMENT_BLUEPRINT.md
3. docs/HANDOFF.md
4. docs/TASK_BOARD.md

These files explain:

- what Pulze is
- how the architecture works
- what modules exist
- what work is currently planned

---

## Step 2 — Identify the Current Task

From `docs/TASK_BOARD.md`, determine:

- what is already completed
- what is under testing
- what needs fixing
- what is planned next

Choose **one task only** to work on.

---

## Step 3 — Work in Small Safe Changes

Before changing code:

- understand the module involved
- identify dependencies
- respect tenant isolation
- avoid rewriting working modules

Prefer **small, incremental improvements**.

---

## Step 4 — Use AI in Consultant Mode

When using AI tools:

- load project context first
- explain the problem clearly
- request minimal safe fixes
- avoid large architectural changes

AI should behave like a **technical consultant**, not a code generator.

---

## Step 5 — Test the Workflow

After making changes, test:

- Create
- Edit
- View
- Delete (soft delete)
- Cross-module interactions

Always verify **tenant isolation**.

---

## Step 6 — Update Documentation

If your work changes system behavior or project priorities, update:

- docs/HANDOFF.md
- docs/TASK_BOARD.md

Documentation must reflect the **current state of the system**.

---

## Guiding Principle

Pulze is a **production SaaS platform**.

Always prioritize:

- stability
- tenant safety
- maintainability
- incremental improvement
