# AGENTS.md — Cafe Moxie Site Kit (Source of Truth)

This document defines how humans and AI agents (Codex, ChatGPT, etc.) should understand, modify, and extend the **Cafe Moxie Site Kit** WordPress plugin.

It is the **single operational contract** for development.

---

## Table of Contents
<!-- BEGIN AUTO TOC -->
* [Overview & Background](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#overview--background)
  * [1. System Overview](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#1-system-overview)
    * [What This Is](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#what-this-is)
    * [Core Principle](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#core-principle)
    * [Product Direction (Global + Reusable)](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#product-direction-global--reusable)
  * [2. Architectural Model](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#2-architectural-model)
    * [Data Layer (SCF)](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#data-layer-scf)
    * [Application Layer (Plugin)](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#application-layer-plugin)
    * [Presentation Layer (Templates)](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#presentation-layer-templates)
  * [3. Key Design Philosophy](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#3-key-design-philosophy)
    * [3.1 No Plugin Bloat](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#31-no-plugin-bloat)
    * [3.2 Deterministic Rendering](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#32-deterministic-rendering)
    * [3.3 Schema-First Development](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#33-schema-first-development)
  * [4. Critical Functions](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#4-critical-functions)
    * [flatten_repeater_items()](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#flattenrepeateritems)
    * [tool_data()](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#tooldata)
    * [render_tool_card()](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#rendertoolcard)
  * [5. SCF Data Rules](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#5-scf-data-rules)
  * [6. Frontend System](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#6-frontend-system)
    * [UI Goals](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#ui-goals)
    * [Components](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#components)
    * [Rendering Approach](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#rendering-approach)
  * [7. Git + Deployment Workflow](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#7-git--deployment-workflow)
    * [Branches](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#branches)
    * [Deployment](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#deployment)
    * [Rules](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#rules)
  * [8. Repository Structure](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#8-repository-structure)
  * [9. Coding Standards](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#9-coding-standards)
    * [PHP](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#php)
    * [Naming](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#naming)
    * [Output](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#output)
  * [9.1 Settings Taxonomy Contract](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#91-settings-taxonomy-contract)
  * [9.2 Native Plugin UX Contract](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#92-native-plugin-ux-contract)
  * [10. What Agents SHOULD Do](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#10-what-agents-should-do)
  * [11. What Agents MUST NOT Do](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#11-what-agents-must-not-do)
  * [11.1 Explicit Anti-Patterns](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#111-explicit-anti-patterns)
  * [12. Testing Rules](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#12-testing-rules)
  * [12.1 Regression Checklist (Required for UI/architecture touching PRs)](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#121-regression-checklist-required-for-uiarchitecture-touching-prs)
  * [12.2 PR Change Disclosure Rule](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#122-pr-change-disclosure-rule)
  * [13. Future Extensions](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#13-future-extensions)
  * [14. Mental Model for Agents](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#14-mental-model-for-agents)
  * [15. Final Rule](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#15-final-rule)
* [Codex Agent Instructions User Prompt](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#codex-agent-instructions-user-prompt)
* [Tasks for Codex Agent](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#tasks-for-codex-agent)
  * [Task 1](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-1)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc)
  * [Task 2](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-2)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-1)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-1)
  * [Task 3](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-3)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-2)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-2)
  * [Task 4](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-4)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-3)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-3)
  * [Task 5](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-5)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-4)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-4)
  * [Task 6](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-6)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-5)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-5)
  * [Task 7](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-7)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-6)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-6)
  * [Task 8](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-8)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-7)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-7)
  * [Task 9](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-9)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-8)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-8)
  * [Task 10](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-10)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-9)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-9)
  * [Task 11](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-11)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-10)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-10)
  * [Task 12](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-12)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-11)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-11)
* [Concise repo review summary](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#concise-repo-review-summary)
  * [Task 13](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-13)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-12)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-12)
    * [Status update (2026-04-01)](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#status-update-2026-04-01)
  * [Task 14](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-14)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-13)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-13)
    * [Status update (2026-04-01)](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#status-update-2026-04-01-1)
  * [Task 15](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-15)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-14)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-14)
    * [Status update (2026-04-01)](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#status-update-2026-04-01-2)
  * [Task 16](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-16)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-15)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-15)
    * [Status update (2026-04-01)](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#status-update-2026-04-01-3)
  * [Task 17](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-17)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-16)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-16)
    * [Status update (2026-04-01)](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#status-update-2026-04-01-4)
  * [Task 18](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-18)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-17)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-17)
    * [Status update (2026-04-01)](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#status-update-2026-04-01-5)
  * [Task 19](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-19)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-18)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-18)
    * [Status update (2026-04-01)](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#status-update-2026-04-01-6)
  * [Task 20](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-20)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-19)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-19)
    * [Status update (2026-04-01)](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#status-update-2026-04-01-7)
* [Concise repo review summary](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#concise-repo-review-summary-1)
  * [Task 21](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-21)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-20)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-20)
    * [Task 21 audit results (2026-04-01)](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-21-audit-results-2026-04-01)
    * [Task 21 file-by-file code-path audit map (2026-04-01)](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-21-file-by-file-code-path-audit-map-2026-04-01)
  * [Task 21 follow-up punch list (narrow PR-sized items)](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-21-follow-up-punch-list-narrow-pr-sized-items)
    * [Task 21.1 — True preset defaults map](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-211--true-preset-defaults-map)
    * [Task 21.2 — Header crowding + responsive QA fix](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-212--header-crowding--responsive-qa-fix)
    * [Task 21.3 — Starter pack expansion to canonical six pages](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-213--starter-pack-expansion-to-canonical-six-pages)
    * [Task 21.4 — Replace generic section placeholders](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-214--replace-generic-section-placeholders)
    * [Task 21.5 — Orientation-aware media frames](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-215--orientation-aware-media-frames)
    * [Task 21.6 — One-click polished setup action](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-216--one-click-polished-setup-action)
    * [Task 21.7 — Content-module parity QA checklist](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-217--content-module-parity-qa-checklist)
  * [Mandatory verification rule for future task status updates](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#mandatory-verification-rule-for-future-task-status-updates)
  * [Task 22](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-22)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-21)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-21)
  * [Task 23](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-23)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-22)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-22)
  * [Task 24](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-24)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-23)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-23)
  * [Task 25](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-25)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-24)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-24)
  * [Task 26](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-26)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-25)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-25)
  * [Task 27](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#task-27)
    * [Implementation Steps](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#implementation-steps-26)
    * [Definition of done / Constraints / Files to modify / etc.](https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#definition-of-done--constraints--files-to-modify--etc-26)
  * [Task 28](#task-28)
  * [Task 29](#task-29)
  * [Task 30](#task-30)
  * [Task 31](#task-31)
<!-- END AUTO TOC -->
---
## Overview & Background

### 1. System Overview

#### What This Is

The Cafe Moxie Site Kit is a **custom WordPress plugin** that:

* Defines the **Cafe Moxie storefront system**
* Renders **Edge Tool products** using SCF (Secure Custom Fields)
* Implements a **brand-driven UI layer** on top of Twenty Twenty-Five
* Avoids dependency on page builders (Elementor, etc.)

#### Core Principle

> WordPress = CMS
> Plugin = System + Rendering Layer

#### Product Direction (Global + Reusable)

Cafe Moxie remains the default preset, but the plugin direction is now **globally reusable**:

* Core behavior should be brand-agnostic wherever practical.
* Cafe Moxie copy/styles should be implemented as defaults/presets, not hard requirements.
* New architecture decisions should improve portability to other storefront/content models without removing Edge Tool support.



### 2. Architectural Model

#### Data Layer (SCF)

* Source of truth for all product data
* Defined in `/scf-json/`
* Includes:

  * `edge_tool` post type
  * field groups
  * taxonomies

SCF defines structured data like:

* Core product info
* Workflow
* Compatibility
* File handling
* Security
* Commerce
* Media

Agents MUST treat SCF schema as **contractual**.

#### Application Layer (Plugin)

Located in:

```
/plugin/
```

Responsibilities:

* Data normalization
* Rendering
* Shortcodes
* Templates

#### Presentation Layer (Templates)

Located in:

```
/plugin/templates/
```

Includes:

* archive-edge_tool.php
* single-edge_tool.php
* partials (if added later)



### 3. Key Design Philosophy

#### 3.1 No Plugin Bloat

DO NOT:

* Add page builders
* Add heavy UI frameworks
* Add dependency-heavy libraries

Prefer:

* Native PHP
* Minimal JS
* SCF-driven rendering

#### 3.2 Deterministic Rendering

All UI must be:

* Data-driven
* Predictable
* Testable

#### 3.3 Schema-First Development

Always:

1. Understand SCF field structure
2. Normalize data
3. Render clean UI

Never:

* Hardcode assumptions about field shapes



### 4. Critical Functions

#### flatten_repeater_items()

Purpose:
Normalize SCF repeater fields into flat arrays.

Rules:

* Must accept mixed types
* Must not throw TypeErrors
* Must handle nested arrays
* Must return strings only

Failure here breaks:

* archive pages
* homepage
* featured tools

#### tool_data()

Purpose:
Aggregate all SCF data into a normalized structure.

Rules:

* Never return raw SCF structures
* Always sanitize output

#### render_tool_card()

Purpose:
Render product cards consistently.

Rules:

* Must not assume fields exist
* Must gracefully degrade



### 5. SCF Data Rules

SCF fields can return:

* string
* array
* nested array
* null

Agents MUST:

* Type-check all values
* Never call string functions on arrays
* Always sanitize

Example risk:

```
trim(array) → fatal error
```



### 6. Frontend System

#### UI Goals

* “Neon industrial counter” aesthetic
* High readability
* Minimal noise

#### Components

* Tool cards
* Filters
* Hero sections
* CTAs

#### Rendering Approach

* PHP templates
* Inline logic kept minimal
* Prefer helper functions



### 7. Git + Deployment Workflow

#### Branches

* main → live
* No staging branch is part of the normal deployment model

#### Deployment

* GitHub Actions via SFTP
* Production-only deploy flow from `main`

#### Rules

* Never edit production directly
* All changes via PR
* Do not introduce staging-only assumptions in docs, workflows, or release instructions unless explicitly requested later
* Always test on a live-safe verification checklist before and after deploy



### 8. Repository Structure

```
/plugin/
/scf-json/
/.github/workflows/
/docs/
/scripts/
```



### 9. Coding Standards

#### PHP

* Defensive programming
* Strict type checks
* No assumptions about input

#### Naming

* snake_case for functions
* consistent prefixes

#### Output

* Always escaped
* Always validated

### 9.1 Settings Taxonomy Contract

For maintainability across brands, plugin settings are grouped into four distinct responsibilities:

* **global design tokens** (colors, spacing primitives, motion, typography scale)
* **component defaults** (cards, grids, reusable UI framing)
* **page template defaults** (section layouts, density, starter/composed page behavior)
* **storefront defaults** (catalog behavior, CTA copy, storefront-specific media)

Agents should keep these groups conceptually separate, even when values remain stored in the same WordPress option.

### 9.2 Native Plugin UX Contract

For this project, **native plugin UX** means:

* All major controls are managed through standard WordPress admin settings screens.
* Labels, descriptions, defaults, and validation are clear enough for non-technical users.
* Layout/template generation is guided by settings and predictable actions, not hidden code-only toggles.
* Generated content should remain editable in the block editor after creation.
* Admin interactions should be lightweight, accessible, and consistent with core WordPress patterns.

Native plugin UX explicitly does **not** mean:

* Drag-and-drop page builders
* Custom visual editors that duplicate WordPress core editing behavior
* Heavy client-side admin frameworks where native settings UI is sufficient



### 10. What Agents SHOULD Do

* Refactor safely
* Improve normalization
* Improve rendering clarity
* Add reusable helpers
* Expand templates based on SCF
* Prefer visually stable defaults over ambitious split layouts: single-column first, calm heading scale, and graceful media/header fallback behavior.
* Treat the `## Table of Contents` block as generated content between `<!-- BEGIN AUTO TOC -->` and `<!-- END AUTO TOC -->`.
* Re-run `python3 scripts/update_agents_toc.py` whenever headings or task titles are added, renamed, or removed.
* Do not hand-edit links inside the auto-generated TOC marker block.



### 11. What Agents MUST NOT Do

* Break SCF compatibility
* Introduce plugin dependencies
* Add unnecessary abstraction
* Replace WordPress core behavior
* Hardcode rigid layout assumptions that fail with variable content
* Add destructive overwrite flows that can silently remove user edits

### 11.1 Explicit Anti-Patterns

Do not introduce these patterns unless a human explicitly overrides this contract:

* Page builder dependencies (Elementor-style runtime coupling)
* Plugin bloat (large UI/runtime libraries without clear necessity)
* Hardcoded layout assumptions (fixed grids/markup that cannot degrade gracefully)
* Destructive overwrite behavior for generated pages/templates without clear opt-in and recovery path



### 12. Testing Rules

Before merging:

* Load homepage
* Load about page
* Load archive page
* Load single tool page
* Test empty fields
* Test partial data

### 12.1 Regression Checklist (Required for UI/architecture touching PRs)

Use this checklist whenever changes could affect rendering, data normalization, template composition, settings UX, or deployment behavior.

Keep it practical: mark each item **pass / fail / not-applicable** in the PR, and include short notes for any fail/NA items.

1. **Homepage rendering**
   * Loads without PHP warnings/fatals.
   * Key sections render with expected spacing and readable text.
2. **About page rendering**
   * Pattern/composed sections load in intended order.
   * Layout degrades cleanly at narrower viewport widths.
3. **Edge Tools archive**
   * Tool cards render with complete data.
   * Filters/search controls (if present) still function.
4. **Single Edge Tool page**
   * Core metadata, media, and CTA blocks render.
   * Missing optional fields do not break layout.
5. **Empty SCF states**
   * Pages with sparse/empty SCF values still render gracefully.
   * No array-to-string/type errors from normalization helpers.
6. **Missing image states**
   * Cards/sections without images fall back cleanly (no broken UI chrome).
7. **Long text states**
   * Long headlines/paragraphs/lists wrap without overlap or clipping.
8. **Production deploy workflow integrity**
   * `.github/workflows/deploy-production.yml` still targets production from `main`.
   * No staging-only assumptions introduced in docs or workflow logic.

### 12.2 PR Change Disclosure Rule

All PRs that touch plugin behavior/templates/workflows must include:

* **Affected files**: grouped by area (templates, core plugin logic, workflows/docs).
* **Expected visible changes**: short human-readable description of what should look/behave differently.
* **Regression checklist results**: the required checklist above with pass/fail/NA annotations.



### 13. Future Extensions

Allowed:

* More templates
* Better filtering
* Performance optimization
* UI refinement

Not allowed:

* Turning into SaaS dashboard
* Overengineering



### 14. Mental Model for Agents

Think of this system as:

"A structured data renderer for tools, not a website builder"



### 15. Final Rule

If unsure:

* Default to simpler code
* Default to safer type handling
* Default to respecting SCF schema

---
---
## Codex Agent Instructions User Prompt
```
You are working on the Cafe Moxie Site Kit WordPress plugin.

Your source of truth is:
- agents.md (STRICT contract)
- existing plugin codebase

Your task is to COMPLETE Task [XX] exactly as defined in agents.md.

---

## Critical Instructions

1. Read Task [XX] in agents.md fully before making any changes.
2. Do NOT assume the task is already complete, even if partial implementation exists.
3. Treat this task as incomplete until ALL implementation steps and definition-of-done criteria are satisfied.
4. Do NOT modify or rewrite other tasks.
5. Do NOT introduce:
   - page builders
   - heavy frameworks
   - unnecessary abstraction
6. Keep everything:
   - WordPress-native
   - lean
   - deterministic
   - maintainable

---

## Execution Requirements

You MUST:

1. Identify all relevant files for this task.
2. Audit current implementation against:
   - Implementation Steps
   - Definition of Done

3. Explicitly list:
   - what is already implemented
   - what is missing or incorrect

4. Then implement ONLY what is missing.

5. Do not break:
   - SCF schema contract
   - existing rendering behavior
   - existing settings unless required

---

## Output Requirements

Return your response in this exact structure:

### 1. Audit Summary
- What exists
- What is missing
- What is incorrect

### 2. Implementation Plan
- Bullet list of what you will change/add

### 3. Code Changes
- Provide full updated code for any modified files
- Do not include unrelated files
- Keep changes minimal and precise

### 4. Verification Checklist
Confirm:

- [ ] All Implementation Steps are satisfied
- [ ] Definition of Done is satisfied
- [ ] No regressions introduced
- [ ] Works on clean install (Twenty Twenty-Five + plugin only)

---

## Scope Control

- Do NOT start another task
- Do NOT “improve” unrelated systems
- Do NOT refactor beyond what Task [XX] requires

---

Now proceed with Task [XX].
```

## Tasks for Codex Agent

Add tasks below. Codex agent is to read agents.md (this file) and follow instructions like:

Task maintenance rule:

* Keep this task list current as architecture and workflow evolve.
* When a task becomes obsolete or completed by a durable baseline change, update or remove it in the same PR that introduces that change.
* Treat this section as operational planning, not historical archive.

### Task 1

Goals

Make the repository and plugin workflow production-only and remove all staging assumptions so the repo matches the actual deployment model.

#### Implementation Steps

1. Update all repo documentation to reflect that `main` is the live deployment branch.
2. Remove staging language from `README.md`, `docs/deployment.md`, `docs/repo-workflow.md`, and `agents.md`.
3. Remove or deprecate any staging workflow files under `.github/workflows/`.
4. Ensure the remaining GitHub Actions deploy workflow is production-only and deploys from `main`.
5. Standardize documentation around the live plugin path currently in use on the server.

#### Definition of done / Constraints / Files to modify / etc.

* Done when the repo no longer references staging as the normal workflow.
* Do not change the current production plugin directory path yet unless explicitly asked.
* Files likely to modify:

  * `README.md`
  * `docs/deployment.md`
  * `docs/repo-workflow.md`
  * `agents.md`
  * `.github/workflows/*`

---

### Task 2

Goals

Refactor the plugin from a Cafe Moxie–specific site kit into a more globally reusable standalone “site system” plugin while preserving Cafe Moxie as the default preset.

#### Implementation Steps

1. Audit all hardcoded Cafe Moxie assumptions in plugin copy, settings labels, starter pages, and template rendering.
2. Separate **core plugin system behavior** from **Cafe Moxie default branding/content**.
3. Introduce the concept of a **brand preset** or **default theme profile**, with Cafe Moxie as the default built-in preset.
4. Ensure the plugin can be reused on other WordPress installations without requiring a Cafe Moxie storefront use case.
5. Preserve backwards compatibility for the existing Cafe Moxie site.

#### Definition of done / Constraints / Files to modify / etc.

* Done when the plugin can function as a generic design/system plugin, with Cafe Moxie as the preloaded opinionated default.
* Do not remove Cafe Moxie defaults.
* Prefer settings-driven defaults over hardcoded copy.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * `plugin/patterns/*.php`
  * `plugin/templates/*.php`
  * `README.md`
  * `agents.md`

---

### Task 3

Goals

Create a more native, intuitive plugin admin UX so a non-technical user can control layouts, styling, and generated pages without editing PHP.

#### Implementation Steps

1. Audit the current settings page and identify missing controls for layout, template behavior, and page generation.
2. Add settings sections for:

   * layout behavior
   * page sections
   * template defaults
   * responsive behavior
   * card/grid density
3. Reorganize the admin settings page into clear sections with stronger labels and descriptions.
4. Add guardrails and sensible defaults so users can make changes safely.
5. Keep the admin UI lean and WordPress-native.

#### Definition of done / Constraints / Files to modify / etc.

* Done when a user can manage major visual and structural decisions from the plugin settings screen.
* Do not add page builders or third-party admin frameworks.
* Stay inside native WordPress settings UI patterns.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`

---

### Task 4

Goals

Replace rigid two-column starter page structures with a more flexible layout system that avoids overlapping elements, squished text, and brittle compositions.

#### Implementation Steps 

1. Audit all uses of fixed grid classes such as `cm-grid-2` and `cm-grid-3`.
2. Introduce layout modes that can be selected per section or per generated template:

   * single-column
   * balanced two-column
   * media-left / media-right split
   * stacked-on-tablet
   * full-width content band
3. Make the homepage and about page patterns more responsive and less dependent on fixed columns.
4. Ensure long text content and narrow screens degrade gracefully.
5. Add max-width and content-width controls where needed.

#### Definition of done / Constraints / Files to modify / etc.

* Done when homepage/about layouts are visually stable across desktop and tablet widths and no longer feel cramped by default.
* Prefer reusable helper classes and helper functions over one-off fixes.
* Files likely to modify:

  * `plugin/patterns/home.php`
  * `plugin/patterns/about.php`
  * `plugin/cafe-moxie-site-kit.php`

---

### Task 5

Goals

Introduce a template composition system so users can generate additional pages using the same logic as the starter pages, without hardcoded one-off patterns.

#### Implementation Steps 

1. Design a lightweight page template system based on reusable sections rather than rigid full-page patterns.
2. Define a library of sections such as:

   * hero
   * story split
   * feature grid
   * CTA band
   * trust section
   * product feed
   * content section
3. Build helper functions that assemble pages from these sections.
4. Add admin controls to generate pages from selected section combinations.
5. Ensure generated pages remain editable in WordPress after creation.

#### Definition of done / Constraints / Files to modify / etc.

* Done when a user can generate additional pages from reusable building blocks rather than only using Home/About.
* Do not build a drag-and-drop page builder.
* Keep the implementation simple, PHP-driven, and WordPress-native.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * `plugin/patterns/*.php`
  * possibly add `plugin/includes/` helpers if needed

---

### Task 6

Goals

Create a formal separation between global design tokens and per-template layout settings so the plugin scales better to future websites.

#### Implementation Steps 

1. Audit current settings and separate them into:

   * global design tokens
   * component defaults
   * page template defaults
   * storefront defaults
2. Refactor settings storage or access patterns if needed so responsibilities are clearer.
3. Ensure token usage remains centralized and consistent.
4. Prevent layout logic from being tightly coupled to Cafe Moxie storefront assumptions.

#### Definition of done / Constraints / Files to modify / etc.

* Done when token settings and page structure settings are clearly distinct and easier for future reuse.
* Keep existing option storage stable unless a migration is justified.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * `agents.md`

---

### Task 7

Goals

Harden all frontend rendering and CSS behavior to prevent overlapping, overflow, broken media framing, and poor typography rhythm.

#### Implementation Steps 

1. Audit spacing, line lengths, image sizing, card widths, and responsive breakpoints.
2. Add stronger safeguards for:

   * text overflow
   * long headings
   * narrow screens
   * missing images
   * embedded media
   * variable SCF content length
3. Normalize vertical rhythm and spacing across starter pages, archive cards, and single templates.
4. Ensure buttons, badges, chips, stat bands, and media frames wrap cleanly.

#### Definition of done / Constraints / Files to modify / etc.

* Done when the system handles real-world messy content more gracefully.
* Do not add heavy CSS frameworks.
* Keep styles lean and local to the plugin.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * `plugin/templates/archive-edge_tool.php`
  * `plugin/templates/single-edge_tool.php`
  * `plugin/patterns/*.php`

---

### Task 8

Goals

Create a stronger storefront/content abstraction so Edge Tools remain a supported default, but the plugin can evolve to support other post types and content systems later.

#### Implementation Steps 

1. Audit where `edge_tool` is assumed directly in rendering logic, shortcodes, templates, and filters.
2. Separate generic content rendering helpers from Edge Tool–specific helpers.
3. Define what parts of the plugin are storefront-core versus Edge Tool module behavior.
4. Keep Edge Tool fully supported, but structure the code so future post types can be added cleanly.

#### Definition of done / Constraints / Files to modify / etc.

* Done when the plugin architecture is less tightly coupled to one product type.
* Do not remove current Edge Tool behavior.
* Prefer incremental refactors over wholesale rewrites.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * `plugin/templates/archive-edge_tool.php`
  * `plugin/templates/single-edge_tool.php`

---

### Task 9

Goals

Improve starter page generation so users can regenerate or extend pages safely without accidentally overwriting useful edits.

#### Implementation Steps 

1. Audit the current “Create / Refresh Starter Pages” behavior.
2. Add safer generation behavior such as:

   * only create if missing
   * optional overwrite mode
   * revision-friendly regeneration
   * generated page markers or metadata
3. Make page generation more transparent in admin.
4. Support future additional starter pages beyond Home/About.

#### Definition of done / Constraints / Files to modify / etc.

* Done when starter pages are safer to use and easier to extend.
* Do not silently destroy user-edited page content.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`

---

### Task 10

Goals

Expand `agents.md` itself into a stronger long-term source of truth for Codex and other AI coding agents.

#### Implementation Steps 

1. Update `agents.md` to reflect production-only deployment.
2. Add explicit guidance about the plugin’s new global/reusable direction.
3. Add a section that defines what “native plugin UX” means for this project.
4. Add explicit anti-patterns:

   * no page builders
   * no plugin bloat
   * no hardcoded layout assumptions
   * no destructive overwrite flows
5. Keep this tasks section current as the architecture evolves.

#### Definition of done / Constraints / Files to modify / etc.

* Done when `agents.md` accurately reflects the real repo, deploy model, and future direction.
* `agents.md` should remain the first file an AI agent reads before editing anything.
* Files likely to modify:

  * `agents.md`

---

### Task 11

Goals

Lay the non-functional groundwork for future AI-assisted site editing without implementing the actual OpenAI editing flow yet.

Status note (2026-04-01 baseline):

* Initial groundwork is now in place by moving composed-page sections toward structured registries/templates in `plugin/cafe-moxie-site-kit.php`.
* Future work should build on those registries (section metadata + tokenized markup context) instead of re-introducing large inline section switches.

#### Implementation Steps 

1. Identify where structured page/template data should eventually live if AI is added later.
2. Ensure templates and settings can be represented in structured arrays/configs rather than only inline PHP markup.
3. Add comments or internal architecture notes where future AI-generated structured output would connect.
4. Do not add API keys, model calls, or UI for AI yet.

#### Definition of done / Constraints / Files to modify / etc.

* Done when the plugin is easier to extend later with AI-driven edits.
* Explicitly do not implement the AI feature yet.
* Avoid speculative overengineering.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * `agents.md`
  * possibly `docs/IMPLEMENTATION-GUIDE.md`

---

### Task 12

Goals

Establish a regression checklist so Codex can make UI and architecture changes without repeatedly breaking the live site.

#### Implementation Steps 

1. Add a concrete regression checklist to `agents.md` or a referenced doc.
2. Include checks for:

   * homepage rendering
   * about page rendering
   * edge tools archive
   * single edge tool pages
   * empty SCF states
   * missing image states
   * long text states
   * production deploy workflow integrity
3. Require agents to describe affected files and expected visible changes in PRs.

#### Definition of done / Constraints / Files to modify / etc.

* Done when future PRs have a more reliable definition of “safe to merge.”
* Keep the checklist practical and short enough that agents will actually follow it.
* Files likely to modify:

  * `agents.md`
  * `.github/pull_request_template.md`

---
## Concise repo review summary

The repo already has the right first-generation primitives for this direction: a single plugin owns tokens and inline CSS, there is an early brand-preset layer, starter-page safety markers exist, `composed_sections()` / `composed_page_templates()` provide an initial modular page-composition seam, `content_modules()` introduces a first content abstraction seam, and the SCF normalization helpers are defensively written. The main gaps are that most of the system still lives in one file, `settings_groups()` is conceptual but not yet driving the admin UX, the settings screen is still one long manual form, Home/About remain largely hardcoded Cafe Moxie patterns, header/footer are not plugin-managed yet, width/padding/spacing controls are only partial, `footer_copy` is exposed without a real footer architecture behind it, and `edge_tool` is still the only true rendering module despite the abstraction seam. The next task layer should therefore formalize registry-driven visual settings, plugin-managed header/footer/template parts, modular section/page composition, stronger module-based SCF rendering, a plugin-first site setup console, and a deterministic architecture that can later support `gpt-5-mini` without reworking the plugin core.

### Task 13

Goals

Create a registry-driven visual system settings architecture so the plugin can become the primary control layer for site aesthetics and layout behavior rather than a collection of manually wired settings.

#### Implementation Steps

1. Audit all current settings, defaults, sanitization, body classes, and CSS token generation in `plugin/cafe-moxie-site-kit.php`.
2. Replace the current manually rendered settings organization with a formal settings registry that defines, per field:

   * key
   * label
   * description
   * group / tab placement
   * control type
   * allowed values
   * sanitization rule
   * default value
   * preset participation
   * CSS variable / class output mapping
3. Expand the settings registry so it can express near-complete visual control for the plugin-managed site layer, including:

   * global visual tokens
   * component framing defaults
   * layout defaults
   * section spacing behavior
   * typography scaling behavior
   * media framing behavior
   * CTA/button treatment
   * archive/grid presentation defaults
   * template part presentation defaults
4. Ensure Cafe Moxie remains the default polished preset, while neutral/reusable variants consume the same registry instead of branching into separate hardcoded behavior.
5. Refactor visual output so CSS token generation and helper-class generation read from this single settings source of truth.

#### Definition of done / Constraints / Files to modify / etc.

* Done when major visual and layout behavior is controlled through a structured settings architecture instead of ad hoc field handling.
* Do not introduce a Customizer-like duplicate system or a heavy admin framework.
* Keep the current option storage stable unless a migration is clearly justified.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * possibly add `plugin/includes/settings-registry.php`
  * possibly add `plugin/includes/style-system.php`
  * `agents.md`

#### Status update (2026-04-01)

Baseline delivered: plugin visual/admin settings now run through a central settings registry that drives defaults, sanitization, admin field rendering, body-class output mapping, and CSS variable emission from one source of truth. Follow-up iterations should focus on extracting the registry/style system into dedicated include files and expanding template-part controls.

---

### Task 14

Goals

Reorganize the plugin admin into a stronger WordPress-native information architecture with clear tabs, panels, summaries, and action areas so the plugin can function as the main aesthetic control console for the site.

#### Implementation Steps

1. Design a native admin IA that groups plugin controls into clear top-level areas such as:

   * overview / setup state
   * brand + presets
   * layout + spacing
   * header + footer
   * pages + templates
   * content modules
   * storefront behavior
2. Refactor the current long scrolling settings page into WordPress-native tabs and/or grouped admin panels using the settings registry as the source of truth.
3. Add a top-level summary panel that surfaces current state for key plugin-managed concerns such as:

   * active preset
   * starter page state
   * composed page defaults
   * header/footer status
   * front-page setup status
   * content module readiness
4. Separate standard save actions from generation / refresh / assignment actions so high-impact operations are easier to understand and harder to trigger accidentally.
5. Improve labels, descriptions, help text, and notices so a non-technical site owner can understand what each setting controls without editing PHP.

#### Definition of done / Constraints / Files to modify / etc.

* Done when the plugin admin is substantially easier to navigate and feels like the primary control center for the site system.
* Use standard WordPress admin UI patterns only.
* Do not introduce a SPA, page builder shell, or dependency-heavy admin framework.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * possibly add `plugin/includes/admin-ui.php`
  * `agents.md`

#### Status update (2026-04-01)

Baseline delivered: the plugin admin now uses a WordPress-native tabbed IA (overview/setup, brand/presets, layout/spacing, header/footer, pages/templates, content modules, storefront behavior), includes a top summary panel for key setup states, and separates normal setting saves from generation/refresh actions.

---

### Task 15

Goals

Add plugin-managed header and footer architecture so site-wide top/bottom presentation can be controlled through the plugin UI while remaining WordPress-native and compatible with a base theme such as Twenty Twenty-Five.

Status note (2026-04-01 baseline):

* `header_height` and `footer_copy` settings exist, but the plugin does not currently own or generate a true site header/footer system.

#### Implementation Steps

1. Audit how the current theme supplies header/footer structure and define a plugin-first strategy that remains block-theme-native.
2. Introduce plugin-managed header and footer presets using native WordPress structures where appropriate, such as:

   * `wp_template_part`
   * `wp_navigation`
   * plugin-generated block markup with generated markers
3. Add plugin UI controls for header/footer management, including items such as:

   * enable plugin-managed header/footer
   * preset selection
   * brand mark treatment
   * utility copy
   * CTA/link controls
   * navigation source
   * footer content areas
   * legal/meta line behavior
4. Reuse the existing safe generation principles from starter pages so header/footer creation and refresh flows are:

   * opt-in
   * revision-friendly where possible
   * marker-based
   * non-destructive to manually customized theme parts
5. Ensure plugin-managed header/footer output consumes the same visual settings/tokens as the rest of the system.

#### Definition of done / Constraints / Files to modify / etc.

* Done when a user can manage header/footer presentation from the plugin UI without needing to hand-edit theme template parts for normal use.
* Stay native to WordPress block theme concepts.
* Do not add a custom visual builder or silently overwrite existing user-customized template parts.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * possibly add `plugin/includes/template-parts.php`
  * possibly add `plugin/template-parts/*`
  * `docs/IMPLEMENTATION-GUIDE.md`
  * `agents.md`

#### Status update (2026-04-01)

Baseline delivered: plugin-managed header/footer controls now exist in the Site Kit settings UI, managed template parts can be generated/refreshed through a dedicated action, generated header/footer parts are marker-protected to avoid overwriting unmanaged user parts, and core `header`/`footer` template-part block rendering can be routed to managed parts when the feature is enabled.

---

### Task 16

Goals

Expand dashboard-controlled width, padding, spacing, density, and layout controls so the plugin can drive site rhythm and layout behavior without requiring custom CSS edits.

#### Implementation Steps

1. Add bounded visual controls for layout rhythm and width management, including settings such as:

   * outer wrapper max width
   * long-form content width
   * full-width band width
   * section vertical spacing
   * panel/card padding
   * grid gap
   * archive/single template rail widths
   * header/footer padding
   * hero spacing
   * breakpoint-related layout modes
2. Map these controls into CSS variables and helper classes so they affect all plugin-managed surfaces consistently.
3. Apply the new controls across all relevant site layers, including:

   * starter pages
   * composed pages
   * archive templates
   * single templates
   * header/footer presets when added
4. Provide sane preset behavior for compact / comfortable / airy density models so the user gets meaningful changes without needing dozens of micro-adjustments.
5. Keep validation strict enough that extreme values cannot easily break layout integrity.

#### Definition of done / Constraints / Files to modify / etc.

* Done when width and spacing decisions can be meaningfully tuned from the dashboard across the main plugin-rendered surfaces.
* Do not add per-block or per-section micro-controls that drift toward Elementor-style builder behavior.
* Favor bounded options, scales, and presets over unconstrained visual tweaking.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * `plugin/patterns/*.php`
  * `plugin/templates/*.php`
  * `agents.md`

#### Status update (2026-04-01)

Baseline delivered: bounded dashboard controls were added for wrapper gutter, grid/panel rhythm, archive/single rail widths, header/footer padding, hero spacing, and responsive breakpoint mode. A new site-density preset (compact/comfortable/airy) now scales spacing defaults across plugin-managed surfaces via CSS variables, while existing per-surface density controls remain available for bounded fine-tuning.

---

### Task 17

Goals

Promote the current composed-page foundation into a true modular template/page/section composition architecture so Home, About, and future pages all derive from the same reusable section system.

Status note (2026-04-01 baseline):

* `composed_sections()`, `composed_page_templates()`, and generated-page markers already exist. This task should evolve that baseline into the main composition path rather than creating a second parallel system.

#### Implementation Steps

1. Audit the current relationship between:

   * `plugin/patterns/home.php`
   * `plugin/patterns/about.php`
   * `composed_sections()`
   * `composed_page_templates()`
   * generated page flows
2. Convert hardcoded starter-page sections into reusable section definitions with structured metadata such as:

   * label
   * purpose
   * default copy
   * supported layout modes
   * media requirements
   * visibility rules
   * token placeholders
   * applicable page types
3. Define a stronger page-template registry for common page types so new pages can be composed from ordered section sets rather than one-off pattern files.
4. Ensure Home/About either become:

   * thin wrappers around the shared section registry, or
   * generated outputs from that registry

   but not a competing architecture
5. Extend safe generation logic so section-based pages can be created, refreshed, and audited using markers/hashes without silently discarding user edits.

#### Definition of done / Constraints / Files to modify / etc.

* Done when the plugin has one primary modular composition architecture for starter pages and future generated pages.
* Generated pages must remain editable in the block editor after creation.
* Do not build a drag-and-drop builder or a proprietary visual page editor.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * `plugin/patterns/*.php`
  * possibly add `plugin/includes/composition.php`
  * possibly add `plugin/includes/sections.php`
  * `docs/IMPLEMENTATION-GUIDE.md`
  * `agents.md`

#### Status update (2026-04-01)

Baseline advanced: Home/About starter patterns now route through the same composition renderer used by generated pages, a shared page-template registry now includes starter and generated template definitions, section entries now carry richer metadata (purpose, layout support, media requirements, visibility rules, token placeholders, applicable page types), and composition rendering now enforces section visibility rules before tokenized block output.

---

### Task 18

Goals

Strengthen the SCF/custom post type rendering architecture so Edge Tool remains the default module, but the plugin can support additional structured content modules later without duplicating entire template stacks.

Status note (2026-04-01 baseline):

* `content_modules()`, `template_include()`, and `edge_tool_data()` already provide an initial seam. Build on those rather than replacing them with a parallel framework.

#### Implementation Steps

1. Formalize each content module as a configuration-driven definition that can declare:

   * post type
   * labels
   * archive filters
   * field map
   * normalization callbacks
   * derived values
   * archive-card schema
   * single-page section order
   * empty-state copy
2. Split generic SCF/meta normalization helpers from Edge Tool–specific field mapping so reusable logic is clearly separated from module logic.
3. Refactor archive and single rendering toward modular section renderers rather than a single large inline assembly path.
4. Ensure module configs can define which sections are rendered and in what order, while maintaining graceful fallback behavior for sparse/missing SCF data.
5. Preserve current Edge Tool behavior as the default working module and keep public output parity as close as practical during refactor.

#### Definition of done / Constraints / Files to modify / etc.

* Done when a future CPT/SCF-backed module could be added cleanly without copying the full Edge Tool implementation.
* Do not remove Edge Tool support or introduce a large abstraction framework.
* Keep rendering deterministic, schema-aware, and defensive against malformed field values.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * `plugin/templates/archive-edge_tool.php`
  * `plugin/templates/single-edge_tool.php`
  * possibly add `plugin/includes/modules/*.php`
  * possibly add `plugin/includes/renderers.php`
  * `agents.md`

#### Status update (2026-04-01)

Baseline advanced: module definitions are now configuration-rich (field map, normalization callbacks, derived values, card schema, section order, empty-state copy), template resolution now iterates all registered content modules, archive query/filter helpers now accept module keys, and Edge Tool single/archive templates now consume module-configured section/filter/empty-state behavior while preserving Edge Tool as the default module path.

---

### Task 19

Goals

Make the plugin the main presentation-control layer for the site so common visual/system setup tasks can be handled here instead of requiring the user to hunt across multiple WordPress settings screens.

#### Implementation Steps

1. Audit which site presentation responsibilities still require leaving the plugin screen, such as:

   * front page assignment
   * key page presence/state
   * header/footer assignment
   * navigation readiness
   * logo/media readiness
   * archive/storefront readiness
2. Add a site setup / presentation state panel that reports current status and offers safe actions for plugin-relevant setup tasks.
3. Where WordPress core already owns the canonical value, use guarded write-through controls and status views rather than creating duplicate shadow options.
4. Add health checks and notices when theme/core settings drift away from plugin expectations in ways that degrade the intended visual system.
5. Provide direct links to the exact core screens that still matter, but make the plugin UI the default operating console for routine aesthetic/system setup.

#### Definition of done / Constraints / Files to modify / etc.

* Done when the plugin meaningfully minimizes the need to visit unrelated settings screens for normal site presentation management.
* Do not hide, remove, or hard-disable core WordPress settings screens.
* Do not create shadow state for values WordPress already owns unless there is a very strong reason and a clear sync strategy.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * `docs/IMPLEMENTATION-GUIDE.md`
  * `README.md`
  * `agents.md`

#### Status update (2026-04-01)

Baseline advanced: the plugin Overview + Setup tab now includes a presentation setup state panel that reports front-page assignment, starter page presence, managed header/footer readiness, navigation assignment, logo/media readiness, and Edge Tool archive/storefront readiness. The panel includes guarded write-through front-page assignment for WordPress core options and links directly to the relevant core admin screens instead of duplicating core-owned state.

---

### Task 20

Goals

Define the deterministic architecture required for future AI-assisted site editing with `gpt-5-mini`, without implementing any live model integration yet.

#### Implementation Steps

1. Define structured site-plan objects that a future AI layer could safely read/write, including models for:

   * design token plans
   * layout setting plans
   * header/footer plans
   * page template plans
   * section instance plans
   * content module render plans
   * safe mutation/apply actions
2. Ensure the settings registry, section registry, and content-module registry expose human-readable labels, constraints, supported fields, and example structures suitable for structured AI prompting later.
3. Design a deterministic future flow such as:

   * inspect current site/plugin state
   * serialize structured context
   * generate a proposed diff/plan
   * require human review/approval
   * apply only through existing safe generator/update paths
   * record what changed
4. Add internal architecture notes, interfaces, or docs showing where future `gpt-5-mini` calls would plug in.
5. Explicitly constrain future AI scope so it proposes controlled settings/template/content-structure changes instead of arbitrary code mutations or builder-style freeform page edits.

#### Definition of done / Constraints / Files to modify / etc.

* Done when the plugin core is architecturally ready for a future `gpt-5-mini` integration without requiring a major redesign.
* Do not add model calls, SDKs, API keys, secret handling, or production AI UI yet.
* Avoid speculative overengineering; keep the system deterministic first.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * `docs/IMPLEMENTATION-GUIDE.md`
  * possibly add `docs/ai-architecture.md`
  * `agents.md`

#### Status update (2026-04-01)

Task 20 baseline implemented: deterministic AI-architecture contract scaffolding is now in place without any live model integration. The plugin now exposes structured plan-model metadata, registry snapshots (settings/templates/sections/content modules), and explicit safe-action boundaries for future `gpt-5-mini` proposal workflows. Supporting documentation was added in `docs/ai-architecture.md` and linked from `docs/IMPLEMENTATION-GUIDE.md`.

---
## Concise repo review summary

The updated repo has a real baseline for Tasks 13–20, but it is not yet the polished out-of-box Cafe Moxie system we discussed. The strongest foundations are now present: a central `settings_registry()`, tabbed admin UI, managed template-part generation, shared page/section registries, richer Edge Tool module metadata, and deterministic AI-contract scaffolding. The main remaining gaps are that `brand_presets()` still does not apply a true Cafe Moxie default map despite `preset_participation` metadata existing, `starter_page_definitions()` still only generates Home/About, `page_template_registry()` still leans generic, several generated sections remain placeholder/generic instead of following `cade-moxie-brand-guide.md`, starter-page images still render as raw `wp:image` blocks rather than adaptive media frames, and `generated_header_markup()` still packs brand/nav/CTA too tightly, which matches the crowding/overlap problem you noticed. The next task layer should therefore focus on verified closure of Tasks 13–20, a real Cafe Moxie polished preset, non-crowded header/button/action layouts, a canonical 6-page Cafe Moxie starter pack, orientation-aware media/layout behavior, and a one-click polished setup flow.

### Task 21

Goals

Audit Tasks 13–20 against the actual shipped code and convert “baseline exists” into “verified, polished, production-ready” so the task list reflects what is truly done versus what is only structurally present.

#### Implementation Steps

1. Perform a file-by-file audit of the current implementation for Tasks 13–20, mapping each task’s stated outcome to the actual code paths now present in:

   * `plugin/cafe-moxie-site-kit.php`
   * `plugin/patterns/*.php`
   * `plugin/templates/*.php`
   * `docs/IMPLEMENTATION-GUIDE.md`
   * `docs/ai-architecture.md`
2. For each Task 13–20 status update, classify the current state as one of:

   * structurally present
   * partially polished
   * fully verified
     and record where the implementation still falls short of the original intent.
3. Explicitly verify the following known gap areas rather than assuming the existing status notes are sufficient:

   * preset system completeness versus mere metadata presence
   * admin IA polish versus tabs-only delivery
   * header/footer usability versus simple generation support
   * starter/composed page quality versus generic placeholder content
   * responsive/media quality versus basic CSS coverage
   * plugin-first setup versus isolated actions spread across tabs
4. Add a follow-up punch list in `agents.md` only for gaps that are still materially open after code review. Keep each follow-up task narrow enough to be completed in a single prompt/PR.
5. Add a mandatory verification rule for future task status updates: do not mark a task “baseline delivered” or “advanced” until it has passed visual QA on a clean WordPress install using Twenty Twenty-Five and only this plugin.

#### Definition of done / Constraints / Files to modify / etc.

* Done when Tasks 13–20 are truthfully reclassified and any still-open work is explicitly tracked without vague “mostly done” language.
* Do not treat scaffolding alone as full completion.
* Do not delete the historical task intent; clarify completion state accurately.
* Files likely to modify:

  * `agents.md`
  * `docs/IMPLEMENTATION-GUIDE.md`


#### Task 21 audit results (2026-04-01)

Classification scale used:

- **Structurally present**: architecture/scaffolding exists, but polish or end-to-end quality is not yet validated.
- **Partially polished**: user-facing behavior exists and is usable, but still has notable quality or completeness gaps.
- **Fully verified**: shipped behavior + visual QA on a clean Twenty Twenty-Five install with only this plugin has passed.

| Task | Current classification | What is implemented | Material gaps still open |
|---|---|---|---|
| 13 (settings architecture) | **Partially polished** | Central `settings_registry()` drives defaults/sanitization/admin rendering + CSS/class output hooks. | Preset system is still metadata-heavy; no true Cafe Moxie preset map applies holistic defaults yet. |
| 14 (admin IA) | **Partially polished** | Native tabbed IA + summary panel + separated action areas exist. | IA is functional but not yet fully polished for non-technical first-run clarity and guided setup flow. |
| 15 (header/footer system) | **Partially polished** | Managed template-part generation/routing and marker-protected updates exist. | Generated header composition still has crowding risk (brand/nav/CTA density) and needs visual QA hardening. |
| 16 (layout/spacing controls) | **Partially polished** | Density, width, spacing, rail and breakpoint controls are wired to CSS variables/classes. | Responsive/media behavior is broadly covered but not yet validated as production-grade across starter/composed outputs. |
| 17 (modular composition architecture) | **Partially polished** | Shared section/template registry path is in place and Home/About route through composition rendering. | Starter/composed content quality still includes generic placeholders and limited polished page-template depth. |
| 18 (content module architecture) | **Structurally present** | Config-driven module definitions + template resolution seams + Edge Tool defaults exist. | Edge Tool is strong, but generalized multi-module renderer ergonomics and parity QA remain unverified. |
| 19 (plugin-first presentation control) | **Partially polished** | Setup state panel + guarded front-page write-through + core-screen links are implemented. | Setup remains distributed across tabs/actions; one-click polished setup path is not yet present. |
| 20 (AI deterministic contract) | **Structurally present** | Plan models, registry snapshots, safe-action boundaries, and architecture docs exist. | Contract is ready as scaffolding, but intentionally unverified for real visual outcomes until future integrations and QA loops. |

#### Task 21 file-by-file code-path audit map (2026-04-01)

`plugin/cafe-moxie-site-kit.php`

- **Task 13 (settings architecture):** `settings_registry()`, `defaults()`, and `sanitize_settings()` provide centralized setting metadata/defaults/sanitization and output mappings, while `brand_presets()` remains light metadata instead of a full preset-default map.
- **Task 14 (admin IA):** tabbed settings IA is wired through `settings_tabs()`, `settings_page()`, and `render_settings_tabs()`, with grouped fields and action panels.
- **Task 15 (header/footer system):** managed template-part generation flows through `generate_template_parts()`, `upsert_template_part()`, `generated_header_markup()`, and `generated_footer_markup()`.
- **Task 16 (layout/spacing controls):** registry-provided token values are emitted via frontend style/class output paths and layout mode helpers (`layout_mode_choices()`, density/breakpoint/layout settings in `settings_registry()`).
- **Task 17 (modular composition architecture):** shared composition path is provided by `page_template_registry()`, `section_registry()`, `compose_page_content()`, `resolve_template_sections()`, and starter generation hooks.
- **Task 18 (content module architecture):** module-level configuration and render seams are driven by `content_module_registry()`, `archive_query()`, `archive_filters()`, and module-aware template resolution helpers.
- **Task 19 (plugin-first setup control):** overview/setup state and write-through controls are wired in `render_setup_state_panel()` and setup action handlers.
- **Task 20 (AI deterministic contract):** deterministic plan scaffolding is exposed by `ai_plan_models()`, `ai_registry_snapshot()`, and safe-action guardrails.

`plugin/patterns/*.php`

- Home/About patterns route to composition rendering through `compose_page_content()` and `template_key` assignment, confirming starter pages are now composition-driven and not hand-maintained parallel templates.

`plugin/templates/*.php`

- `archive-edge_tool.php` consumes module-aware archive query/filter/headline helpers and empty-state configuration.
- `single-edge_tool.php` consumes normalized tool data and module-defined section ordering/fallback behavior.
- These templates verify module seams are real, but also confirm parity/polish must be validated with explicit visual QA rather than inferred from scaffolding.

`docs/IMPLEMENTATION-GUIDE.md`

- Documents plugin-first setup flow, composition architecture, module hooks, and status verification policy, but does not replace runtime visual QA evidence requirements.

`docs/ai-architecture.md`

- Documents plan models, registry snapshots, and deterministic apply boundaries for future AI-assisted workflows; no live model execution path is shipped (by design).

### Task 21 follow-up punch list (narrow PR-sized items)

#### Task 21.1 — True preset defaults map
Implement a real preset-default layer so `brand_preset` applies complete Cafe Moxie vs neutral default bundles (not just field-level defaults + participation metadata).

#### Task 21.2 — Header crowding + responsive QA fix
Refine generated header markup/layout tokens to prevent brand/nav/CTA overlap, with explicit tablet/mobile behavior validation.

#### Task 21.3 — Starter pack expansion to canonical six pages
Extend starter generation beyond Home/About to a polished six-page baseline using the shared section/template registry.

#### Task 21.4 — Replace generic section placeholders
Audit section templates and replace intentionally generic placeholder copy/blocks with brand-guide-aligned production starter content.

#### Task 21.5 — Orientation-aware media frames
Upgrade starter/composed media handling from raw image blocks to adaptive frame/layout behavior for portrait/landscape assets.

#### Task 21.6 — One-click polished setup action
Add a guarded “polished setup” action that orchestrates starter pages, front-page assignment, and managed header/footer generation in one deterministic flow.

#### Task 21.7 — Content-module parity QA checklist
Add a concrete verification checklist for module-driven archive/single rendering parity and sparse-data fallbacks before calling module architecture “advanced”.

### Mandatory verification rule for future task status updates

Effective immediately, no task may be marked **“baseline delivered”**, **“advanced”**, or equivalent completion language unless it has passed **visual QA on a clean WordPress install using Twenty Twenty-Five with only this plugin active**.

Required minimum evidence for status updates:

1. Exact environment declaration (WordPress version, theme = Twenty Twenty-Five, active plugins list).
2. Action path used (fresh install steps + plugin actions invoked).
3. Visual checks performed (desktop + tablet + mobile at minimum).
4. Regression notes covering header/footer, starter/composed pages, Edge Tool archive/single, and setup panel actions.
5. Explicit pass/fail statement; if any critical item fails, task remains “structurally present” or “partially polished,” not “delivered.”

---

---

### Task 22

Goals

Implement a real Cafe Moxie polished preset/default profile so the plugin loads into an elegant, sleek, on-brand state without the user having to manually tune settings.

Status note (2026-04-01 baseline):

* The settings registry exists, but the current `brand_preset` system does not yet apply a true Cafe Moxie default map across all relevant visual/layout/header/footer/page settings.
* Implementation update (2026-04-01): preset-default maps now drive first install + explicit reapply flows, `preset_participation` metadata controls preset-managed keys, and the settings UI includes a direct “Apply Cafe Moxie polished defaults” action.

#### Implementation Steps

1. Introduce a true preset-defaults layer keyed by brand preset, rather than relying only on field-level registry defaults. At minimum, add:

   * a Cafe Moxie polished preset
   * a neutral reusable preset
2. Make the Cafe Moxie preset own explicit default values for the settings that most affect first impression and crowding, including at minimum:

   * `enable_managed_header_footer` = `1`
   * `header_footer_preset` = `counter`
   * `header_brand_treatment` = `logo_and_name`
   * `header_nav_source` = `primary_navigation`
   * `header_cta_label` = `Browse the Counter`
   * `header_cta_url` = `/edge-tools/`
   * `home_primary_cta` = `Browse the Counter`
   * `home_secondary_cta` = `See What Runs Local`
   * `about_primary_cta` = `Browse the Counter`
   * `footer_copy` = `Tools for people who actually do the work.`
   * `footer_utility_copy` = `Pull up a chair. The tools are ready.`
   * `footer_content_primary` = `Own it when you want ownership. Run it when you just need the task done.`
   * `footer_content_secondary` = `Buy once for local tools. Pay per task for compute-backed workflows.`
   * `logo_width` = `240`
   * `header_height` = `88`
   * `header_vertical_padding` = `12`
   * `footer_vertical_padding` = `24`
   * `section_max_width` = `1240`
   * `outer_wrapper_gutter` = `28`
   * `hero_min_height` = `620`
   * `hero_section_spacing` = `36`
   * `border_radius` = `16`
   * `button_scale` = `0.96`
   * `mobile_heading_scale` = `0.94`
   * `card_image_ratio` = `4:3`
   * `grid_gap` = `24`
   * `panel_padding` = `26`
   * `content_max_width` = `720`
   * `content_band_max_width` = `1160`
   * `archive_rail_max_width` = `1280`
   * `single_rail_max_width` = `1120`
   * `responsive_breakpoint_mode` = `early_stack`
   * `page_section_density` = `comfortable`
   * `site_density_preset` = `comfortable`
   * `archive_columns` = `3`
   * `tablet_columns` = `2`
   * `home_hero_layout` = `media_right_split`
   * `home_story_layout` = `media_right_split`
   * `home_trust_layout` = `stacked_on_tablet`
   * `home_featured_layout` = `stacked_on_tablet`
   * `about_intro_layout` = `media_right_split`
   * `about_calibrate_layout` = `stacked_on_tablet`
3. Ensure preset application is deterministic in three places:

   * first install / first option creation
   * explicit “reset/apply preset” action from the plugin UI
   * future AI-proposed preset changes
4. Make `preset_participation` metadata actually control which fields are reset/reapplied by a preset, instead of leaving that metadata unused.
5. Add a visible plugin action such as “Apply Cafe Moxie polished defaults” so a site owner can safely restore the intended default shell after experiments.

#### Definition of done / Constraints / Files to modify / etc.

* Done when a fresh install defaults to a polished Cafe Moxie experience and the preset system is real rather than nominal.
* Do not remove the neutral preset path.
* Do not introduce hidden state or duplicate option stores.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * possibly add `plugin/includes/presets.php`
  * `docs/IMPLEMENTATION-GUIDE.md`
  * `agents.md`

---

### Task 23

Goals

Eliminate crowding/overlap in the default front-end shell by refining header/footer structure, action-cluster spacing, and button wrapping so the default Cafe Moxie presentation feels composed instead of cramped.

Status note (2026-04-01 baseline):

* Managed header/footer generation exists, but the generated header structure is still too compact and currently packs brand, navigation, and CTA into a crowd-prone cluster.

#### Implementation Steps

1. Audit all plugin-generated button/action clusters that currently risk crowding, including:

   * `generated_header_markup()`
   * `generated_footer_markup()`
   * composed page hero/button clusters
   * archive filter/action clusters
   * overview/generation action buttons in admin
2. Refactor the managed header markup into explicit structural regions instead of one broad flex cluster. At minimum provide:

   * left region = brand mark / site title
   * center region = navigation
   * right region = CTA / utility actions
3. Add explicit CSS/layout rules for all action clusters so they:

   * wrap cleanly
   * preserve readable tap targets
   * maintain consistent gap spacing
   * avoid logo/nav/button collisions
   * stack earlier on medium viewports when needed
4. Add plugin-owned classes for button groups/action clusters instead of relying on whatever spacing the active theme happens to apply to core block buttons.
5. Verify the default Cafe Moxie shell at these viewport widths at minimum:

   * `1440px`
   * `1280px`
   * `1024px`
   * `782px`
   * `640px`
   * `390px`
     and treat any overlap/crowding as a failure.
6. Apply the same anti-crowding rules to the generated footer and key composed-page CTA areas so the polished default experience is consistent across the whole site shell.

#### Definition of done / Constraints / Files to modify / etc.

* Done when the default header/footer/CTA layouts no longer crowd, collide, or wrap awkwardly in a clean install.
* Do not solve this by adding a heavy responsive builder or per-section manual controls.
* Keep the layout deterministic and theme-independent enough to behave well on Twenty Twenty-Five.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * `plugin/patterns/*.php`
  * `plugin/templates/*.php`
  * `agents.md`

---

### Task 24

Goals

Create the canonical Cafe Moxie starter page pack and make it the default generated content set, using `cade-moxie-brand-guide.md` as the source of truth instead of generic placeholder copy.

Status note (2026-04-01 baseline):

* The composition framework exists, but `starter_page_definitions()` still only generates Home/About and several composed sections/templates remain generic or placeholder-oriented rather than fully Cafe Moxie specific.

#### Implementation Steps

1. Expand the starter-page system from a minimal Home/About pair into a canonical Cafe Moxie starter pack with six default pages:

   * `home` → `Home`
   * `about` → `About Cafe Moxie`
   * `browse-the-counter` → `Browse the Counter`
   * `how-it-works` → `How It Works`
   * `who-its-for` → `Who It’s For`
   * `trust-faq` → `Trust + FAQ`
2. Use `cade-moxie-brand-guide.md` as the canonical content source for these pages. Do not use generic “site system” language where the brand guide already gives concrete Cafe Moxie copy.
3. Map each generated page to an explicit section plan and content source, for example:

   * Home → use the homepage structure from brand guide section 17 exactly
   * About Cafe Moxie → pull from sections 1–7 and 9–16
   * Browse the Counter → introduce the counter metaphor, category names from section 18, and CTA into the Edge Tool archive
   * How It Works → explain Buy Once / Pay Per Task / Hybrid and Compute Credits using sections 15–16
   * Who It’s For → use the audience definition, traits, and emotional truth from section 3
   * Trust + FAQ → use trust messages, commerce rules, and product-page clarity rules from sections 6, 19, and 20
4. Replace placeholder section text in the current composed section markup with real Cafe Moxie copy where the default preset is Cafe Moxie.
5. Keep generic/non-Cafe-Moxie templates available for reusable mode, but make the Cafe Moxie starter generator default to this canonical six-page pack.
6. Add one explicit generation action for this pack, such as “Generate / Refresh Cafe Moxie Starter Set,” and ensure pages remain block-editor editable after generation.
7. Keep generation marker-based and revision-safe:

   * create if missing
   * overwrite only plugin-managed generated pages when explicitly allowed
   * never silently replace unmanaged user-authored pages

#### Definition of done / Constraints / Files to modify / etc.

* Done when a clean install can generate a coherent, brand-correct Cafe Moxie page shell without placeholder copy.
* Do not invent fake testimonials, fake client logos, fake metrics, or unsupported claims.
* Do not create fake product inventory; use real Edge Tool content where available and graceful placeholders where not.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * `plugin/patterns/*.php`
  * possibly add `plugin/includes/cafe-moxie-page-pack.php`
  * `cade-moxie-brand-guide.md`
  * `docs/IMPLEMENTATION-GUIDE.md`
  * `agents.md`

---

### Task 25

Goals

Add orientation-aware media framing and responsive composition rules so starter pages, composed sections, and module templates look elegant regardless of whether the image is portrait, landscape, square, tiny, or missing.

Status note (2026-04-01 baseline):

* Tool cards use a ratio frame, but composed starter-page media still renders as raw `wp:image` output, which is why portrait/small images can look awkward or under-filled in split sections.

#### Implementation Steps

1. Audit all plugin-managed media rendering surfaces, including:

   * composed starter-page media
   * generated page templates
   * header/footer brand images
   * Edge Tool hero/gallery/before-after media
   * archive cards
2. Replace raw starter-page/media block output with a shared media-frame layer that can choose a presentation mode based on image metadata and section context.
3. Introduce a deterministic media metadata resolver that can expose, where available:

   * attachment ID
   * width
   * height
   * aspect ratio
   * orientation class (`portrait`, `landscape`, `square`, `unknown`)
4. Support both local WordPress media and URL fallback:

   * when a WordPress attachment is available, use attachment metadata
   * when only a URL is available, use safe fallbacks and default frame behavior rather than leaving the layout visually inconsistent
5. Define bounded frame/layout modes for major contexts, such as:

   * hero-wide
   * split-standard
   * split-tall
   * square-card
   * portrait-focus
   * logo/signage
6. Add responsive rules so portrait or undersized imagery in split layouts does not leave a tiny image floating in a tall blank column. Acceptable strategies include:

   * taller cropped media frames
   * automatic earlier stacking for that section
   * switching to media-top treatment at narrower widths
7. Ensure missing or weak media falls back to polished Cafe Moxie signage-style placeholders rather than generic blank boxes.

#### Definition of done / Constraints / Files to modify / etc.

* Done when the plugin’s default pages and templates remain visually balanced across mixed image aspect ratios without manual CSS edits.
* Do not add heavy JavaScript layout measurement, drag-and-drop crop tools, or builder-style media controls.
* Keep the behavior deterministic, lightweight, and WordPress-native.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * `plugin/patterns/*.php`
  * `plugin/templates/*.php`
  * possibly add `plugin/includes/media-system.php`
  * `agents.md`

---

### Task 26

Goals

Make the plugin feel like the primary polished setup console for Cafe Moxie by adding an enterprise-quality, one-click setup flow and more refined admin panel organization without turning the plugin into a builder.

#### Implementation Steps

1. Refine the current tabbed admin UI into grouped panels/cards inside each tab instead of rendering each tab as one flat settings table.
2. Add a first-run / quick-actions area with the high-value actions a site owner actually needs, at minimum:

   * Apply Cafe Moxie polished defaults
   * Generate / Refresh Cafe Moxie Starter Set
   * Generate / Refresh Managed Header + Footer
   * Assign Home as Front Page
   * Preview Site
3. Add setup-state reporting that distinguishes between:

   * plugin-managed generated pages
   * plugin-managed template parts
   * unmanaged or user-edited content
   * missing assets / navigation / logo
4. Make the Overview + Setup experience deterministic enough that a clean install can reach a polished Cafe Moxie shell from this plugin screen with minimal trips to core screens.
5. Add “recommended for Cafe Moxie” guidance within relevant panels so the user understands which controls matter most for the polished default experience.
6. Add a clean-install QA checklist for the whole experience, covering:

   * plugin activation state
   * preset application
   * page generation
   * header/footer generation
   * front-page assignment
   * desktop/tablet/mobile visual checks
7. Keep all of this WordPress-native and maintainable. The goal is a better control console, not a wizard, SPA, or page builder.

#### Definition of done / Constraints / Files to modify / etc.

* Done when the plugin can serve as the main operational surface for standing up a polished Cafe Moxie site shell.
* Do not add hidden state machines, proprietary builders, or dependency-heavy admin frameworks.
* Preserve access to core WordPress screens even if the plugin becomes the preferred operating console.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * possibly add `plugin/includes/admin-ui.php`
  * `README.md`
  * `docs/IMPLEMENTATION-GUIDE.md`
  * `agents.md`

---

### Task 27

Goals

Automatically maintain the `agents.md` Table of Contents so it stays in sync whenever sections or tasks are added, renamed, or removed.

#### Implementation Steps

1. Add a lightweight script under `scripts/` that reads `agents.md`, finds all markdown headers, and regenerates the Table of Contents block at the top of the file.
2. Generate full GitHub edit-page anchor links in this exact format:

   * `https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#overview--background`
3. Support the heading levels currently used in `agents.md`, including at minimum:

   * `##`
   * `###`
   * `####`
4. Match GitHub anchor behavior closely enough for the current document structure, including:

   * lowercase conversion
   * spaces to hyphens
   * removal of punctuation where appropriate
   * preservation of numeric headings like `9.1` → `#91-settings-taxonomy-contract`
5. Detect and replace only the Table of Contents section, without rewriting the rest of the file.
6. Add a clear marker block around the generated TOC, for example:

   * `<!-- BEGIN AUTO TOC -->`
   * `<!-- END AUTO TOC -->`
7. Add an npm script, shell alias, or documented command so Codex can run the TOC generator in one step before finishing a PR.
8. Update `agents.md` instructions so future agents know:

   * the TOC is generated
   * they must re-run the generator whenever headings/tasks change
   * they should not hand-edit links inside the generated TOC block
9. Optionally add a CI check or pre-commit validation that fails if headings changed but the generated TOC block was not updated.

#### Definition of done / Constraints / Files to modify / etc.

* Done when `agents.md` has a stable auto-generated TOC workflow that future agents can maintain with one command.
* Keep the implementation lightweight and repo-native.
* Do not add a heavy documentation toolchain just for TOC generation.
* Files likely to modify:

  * `agents.md`
  * `scripts/*`
  * optionally `package.json`
  * optionally `.github/workflows/*`
 
 ---
Here is **Task 28**, written in the exact same structure and style as your `agents.md`, optimized for Codex execution and aligned with your system philosophy:

---

### Task 28

Goals

Implement a comprehensive, WordPress-native logging system with an admin “Logs” tab so plugin behavior, actions, errors, and failures can be easily diagnosed and shared for debugging.

The logging system should make it possible to paste logs into Codex/ChatGPT and receive actionable fixes.

#### Implementation Steps

1. Introduce a centralized logging utility within the plugin, for example:

   * `cm_log_event( $type, $message, $context = [] )`
   * `cm_log_error( $message, $context = [] )`
   * `cm_log_action( $action, $status, $context = [] )`

   Where:

   * `$type` includes: `info`, `warning`, `error`, `action`
   * `$status` includes: `success`, `failure`
   * `$context` is a structured array (never raw objects)

2. Store logs in a lightweight, WordPress-native way. Acceptable approaches:

   * custom database table (preferred for scalability), OR
   * bounded option-based storage (ring buffer style)

   Requirements:

   * logs must be capped (avoid unbounded growth)
   * logs must be queryable by type/time
   * logs must not degrade site performance

3. Add a new top-level tab in the plugin admin UI:

   * `Logs`

   This tab should include:

   * log level selector (Basic vs Advanced)
   * log viewer (recent entries)
   * filters:

     * type (error/warning/info/action)
     * success/failure
     * time range
   * clear logs action (with confirmation)
   * export logs (copyable or downloadable)

4. Implement logging levels:

   * **Basic logging**

     * critical errors
     * failed actions
     * major system events (page generation, header/footer generation)

   * **Advanced logging (default)**

     * all actions triggered through plugin UI
     * generation flows (pages, templates, presets)
     * SCF normalization issues or unexpected values
     * template rendering warnings (missing data, fallbacks triggered)
     * media handling decisions (fallbacks, orientation handling)
     * preset application events
     * admin actions (save settings, generate pages, etc.)

5. Instrument key plugin systems so they log meaningful events, including:

   * starter page generation
   * header/footer generation
   * settings save/update
   * preset application/reset
   * template composition rendering
   * content module rendering (Edge Tool and future modules)
   * SCF normalization edge cases
   * media fallback behavior
   * any caught PHP warnings/errors within plugin scope

6. Add structured context to logs wherever possible, such as:

   * post ID
   * page slug
   * template key
   * module key
   * setting key
   * action name

   Context must be:

   * JSON-serializable
   * sanitized
   * size-limited

7. Ensure logging is safe and non-disruptive:

   * logging must never cause fatal errors
   * logging must fail silently if storage fails
   * avoid logging sensitive data (no secrets, no raw user input without sanitization)

8. Add a copy/export format optimized for AI debugging, such as:

   * clean, structured text block
   * grouped by timestamp
   * includes type + status + message + context

   Example format:

   ```
   [2026-04-01 14:32:10] ERROR | action: generate_pages | status: failure
   Message: Missing section definition for template "home"
   Context: { "template": "home", "section": "hero" }
   ```

9. Integrate the logging system into the admin Overview/Setup experience:

   * show recent critical errors
   * surface “last failure” summaries
   * link directly to full Logs tab

10. Document logging usage in `agents.md` and/or `docs/IMPLEMENTATION-GUIDE.md` so future agents:

* know how to log events
* know what must be logged
* do not introduce silent failures

#### Definition of done / Constraints / Files to modify / etc.

* Done when plugin actions and failures can be diagnosed entirely from the Logs tab without needing server-level access.

* Default logging level must be **Advanced**.

* Logging must be lightweight and not introduce noticeable performance overhead.

* Do not introduce external logging services or dependencies.

* Do not log sensitive/private data.

* Keep everything WordPress-native and maintainable.

* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * possibly add `plugin/includes/logger.php`
  * possibly add `plugin/includes/admin-logs.php`
  * `docs/IMPLEMENTATION-GUIDE.md`
  * `agents.md`

---

This one is **very high leverage** — once implemented, you’ll be able to:

> copy logs → paste into ChatGPT → get precise fixes

If you want, I can also:

* generate the **exact Codex prompt for Task 28**
* or design the **log schema + DB table structure** before implementation (recommended for performance)
---
### Task 29

Goals

Perform a visual quality and layout stabilization pass so the plugin’s default Cafe Moxie output is presentable, readable, and polished out of the box.

The current system has many of the right architectural pieces, but the default front-end output still suffers from brittle layout choices, oversized text, clipped headings, awkward card alignment, unreliable media/header behavior, and an overuse of two-column defaults that make the site feel broken even when the design language itself is promising.

This task is to fix the default presentation quality layer.

#### Implementation Steps

1. Audit the current default Cafe Moxie front-end output across all plugin-managed surfaces, including at minimum:

   * homepage hero
   * homepage composed sections
   * starter/default generated pages
   * managed header
   * managed footer
   * cards / panel groupings
   * archive listing surfaces
   * common button/action clusters
   * media/image blocks

2. Treat the current default two-column/split-layout behavior as a design-risk pattern, not a safe default. Update the plugin so the default Cafe Moxie page/layout behavior becomes **single-column first** unless a section explicitly proves it can support a split layout without clipping, crowding, or weak balance.

3. Change all Cafe Moxie default layout settings that currently encourage brittle split compositions so the polished default experience prefers:

   * single-column
   * stacked sections
   * full-width content bands
   * media-top or media-below treatment
   * earlier stacking on tablet/mobile

   rather than side-by-side text/media by default.

4. Audit all typography scales used in plugin-managed surfaces and reduce brittle oversized heading behavior. At minimum:

   * reduce default heading sizes where they cause clipping or awkward wrapping
   * improve heading line-height and max-width behavior
   * prevent oversized text from dominating narrow cards/panels
   * ensure long headings wrap cleanly without being cut off
   * introduce safer responsive typography scaling where needed

5. Audit panel/card/section alignment and fix visual instability caused by mismatched heights, inconsistent padding, weak internal spacing, and brittle layout assumptions.

6. Add stronger guardrails for front-end text rendering, including:

   * long-title handling
   * long CTA label handling
   * badge/chip wrapping
   * paragraph width control
   * section intro width limits
   * prevention of headline clipping inside cards or narrow columns

7. Improve plugin-managed media behavior wherever current output feels broken or unfinished, including:

   * weak image placement
   * tiny images in oversized columns
   * inconsistent media framing
   * poor fallback behavior when media is missing or undersized

   Prefer stable, elegant media framing over ambitious split-layout compositions.

8. Audit the managed header implementation and fix any layout or behavior issues that make it feel unreliable, crowded, or visually broken. At minimum verify:

   * brand/title area sizing
   * nav spacing
   * CTA spacing
   * wrapping behavior
   * mobile behavior
   * dynamic title/logo handling
   * graceful degradation when assets/content are missing

9. Add safer default constraints so user-configurable values cannot easily produce bad-looking output, especially for:

   * heading scale
   * button scale
   * panel padding
   * section density
   * image framing
   * split-layout usage
   * content width

10. Add a dedicated visual QA pass for the Cafe Moxie default preset and treat the following as failures:

   * clipped titles
   * unreadable or oversized headings
   * broken/wonky split layouts
   * tiny media floating in large empty columns
   * uneven or awkward card alignment
   * crowded button groups
   * header/footer crowding
   * obviously unfinished or placeholder-looking default output

11. Update the default generated Cafe Moxie experience so it aims for:

   * elegant
   * readable
   * calm
   * stacked
   * balanced
   * polished
   * resilient under imperfect content

   rather than “visually ambitious but brittle.”

12. Document any new layout-safety rules in `agents.md` and/or `docs/IMPLEMENTATION-GUIDE.md` so future agents understand that visual stability and polished defaults take priority over showing off multi-column composition.

#### Definition of done / Constraints / Files to modify / etc.

* Done when a fresh Cafe Moxie default install looks presentable and polished without the user needing to fix text scale, broken split layouts, or awkward card/media behavior manually.
* The default Cafe Moxie layout direction must be **single-column first**.
* Do not introduce a page builder, visual editor, or heavy CSS/JS framework.
* Do not solve the issue by hiding content or making everything tiny.
* Prefer stable defaults and bounded layout decisions over clever but brittle visual compositions.
* If a two-column layout cannot be made consistently good, it should not be the default.
* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * `plugin/patterns/*.php`
  * `plugin/templates/*.php`
  * possibly `plugin/includes/style-system.php`
  * `docs/IMPLEMENTATION-GUIDE.md`
  * `agents.md`
 
### Review
Here’s the practical rundown after reviewing the current repo as it stands through Task 29:

The plugin is no longer missing architecture. It has a real settings registry, composed page system, starter-page generator, managed template parts, presets, logs, and a much better baseline than the early versions. The problem now is not “missing framework.” The problem is that the **out-of-box experience is still not truly plugin-owned**.

What still feels incomplete in practical terms:

* The plugin still does **not fully own the site shell**. Header/footer behavior still depends too much on theme/menu/template-part state.
* “Polished setup” gets partway there, but it still does **not fully provision a finished Cafe Moxie front-end** without extra theme/admin cleanup.
* The visual system is stronger, but the **default presentation still feels too narrow, too conservative, and too dependent on fallbacks/placeholders**.
* The page generator is now broader, but the **starter experience still does not feel like a complete branded site pack** with shell, nav, assets, and final calibrated defaults all working together.

So the next critical work should stop being abstract cleanup and focus on the two most practical missing layers:

1. **true plugin-first shell ownership**
2. **true polished out-of-box content/media/layout baseline**

---

### Task 30

Goals

Make the plugin the true owner of the default site shell so a fresh install no longer depends on manual theme settings work for header/footer/navigation/logo/site presentation readiness.

Right now, the plugin can generate parts of the shell, but the out-of-box experience still feels partially theme-managed. This task is to close that gap and make the plugin-first shell actually reliable.

#### Implementation Steps

1. Refactor the managed header/footer system so it no longer depends on manual theme-level setup to feel complete. The plugin should provision and manage the default shell state itself wherever WordPress allows.

2. Introduce a plugin-owned shell bootstrap flow that can provision all core shell dependencies required for a finished Cafe Moxie presentation, including at minimum:

   * managed header template part
   * managed footer template part
   * primary navigation content
   * footer navigation content (if used)
   * front-page assignment
   * default shell settings/preset state

3. Stop relying on theme menu-location state as the primary dependency for managed navigation output if that dependency makes the shell fragile. Replace or supplement it with a plugin-managed fallback path that can still render a working navigation shell when theme menu assignment is missing or incomplete.

4. Add a plugin-managed navigation provisioning routine that creates and/or syncs a default Cafe Moxie navigation structure, including at minimum links for:

   * Home
   * About Cafe Moxie
   * Browse the Counter
   * How It Works
   * Who It’s For
   * Trust + FAQ

5. Ensure the polished setup flow becomes a true shell bootstrap flow, not just a partial preset/page generator. After running setup on a clean install, the plugin should be able to leave the site in a state where:

   * header renders
   * footer renders
   * nav renders
   * front page is assigned
   * starter pages exist
   * shell looks intentionally configured

6. Add explicit shell-readiness diagnostics to the plugin UI so the user can see whether the following are ready, broken, or using fallback behavior:

   * header template part
   * footer template part
   * primary navigation
   * footer navigation
   * logo/brand asset state
   * front page assignment
   * starter page pack state

7. Improve header/footer generation so repeated generation/setup runs are deterministic and safe:

   * create if missing
   * update if plugin-managed
   * avoid silently replacing unmanaged user-edited shell content
   * clearly log what was created, updated, skipped, or missing

8. Ensure the Logs tab records shell-bootstrap failures and fallback behavior in a way that makes shell issues easy to diagnose.

9. Update the plugin Overview/Setup experience so the main quick action becomes a true “stand up the site shell” action, not just a collection of loosely related generation tools.

#### Definition of done / Constraints / Files to modify / etc.

* Done when a clean install no longer requires the user to visit theme settings just to get a working Cafe Moxie header/footer/navigation shell.

* The plugin must become the preferred shell-control layer for the site.

* Do not hard-disable theme functionality, but do eliminate theme-setup dependence for the default plugin-managed experience.

* Do not introduce a page builder, site builder, or heavy admin framework.

* Keep behavior deterministic, revision-safe, and WordPress-native.

* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * possibly add `plugin/includes/site-shell.php`
  * `docs/IMPLEMENTATION-GUIDE.md`
  * `agents.md`

---

### Task 31

Goals

Ship a truly polished Cafe Moxie out-of-box baseline by bundling a complete starter presentation layer: wider calibrated defaults, calmer typography, non-placeholder starter content treatment, and plugin-owned fallback media/assets so the generated site looks finished immediately.

Right now, the system is structurally capable, but too much of the first-run experience still feels narrow, placeholder-heavy, or visually provisional. This task is to make the default generated site feel complete and presentable without manual intervention.

#### Implementation Steps

1. Calibrate the Cafe Moxie default visual baseline for a wider, more composed presentation. Update polished preset defaults so the default generated site no longer feels overly narrow or compressed.

2. Revisit and tune the specific default values that most affect first impression, including at minimum:

   * section max width
   * content width
   * content band width
   * outer gutter
   * panel padding
   * grid gap
   * heading scale
   * hero min height
   * button scale
   * mobile heading scale
   * page density
   * archive/default card column behavior

3. Replace visually weak starter-page fallback states with plugin-owned branded fallback presentation. Do not let the default generated pages feel unfinished because the user has not yet uploaded assets.

4. Add a Cafe Moxie starter asset/fallback layer for generated pages and managed shell surfaces, including appropriate plugin-owned fallback treatments for:

   * hero/signage media
   * story/about media
   * header brand mark area
   * empty visual panels
   * category/CTA surfaces

5. Make the default generated pages read as intentionally designed even when real images are not present. Placeholder states should feel branded, calm, and acceptable in production, not like temporary editor scaffolding.

6. Audit all generated page copy/layout pairings and remove any remaining sections that feel generic, under-filled, or structurally awkward in the default Cafe Moxie preset.

7. Ensure the six-page Cafe Moxie starter pack works as one coherent site set, not just six individually generated pages. At minimum verify consistency across:

   * shell language
   * CTA language
   * page width behavior
   * section spacing rhythm
   * card treatment
   * fallback media treatment
   * archive hand-off language

8. Add stronger default constraints so user-facing visual controls start from a polished range rather than a technically valid but unattractive range.

9. Add a dedicated “Cafe Moxie polished baseline” QA pass to the plugin workflow and treat the following as failures on a clean install:

   * starter pages still look placeholder-like
   * pages feel too narrow by default
   * shell and pages do not visually feel like one system
   * missing images make the site feel broken
   * default generated content feels incomplete or generic

10. Ensure polished setup applies this full baseline automatically so a clean install yields a finished-looking branded starter site, not just a technically generated one.

#### Definition of done / Constraints / Files to modify / etc.

* Done when a clean install using the Cafe Moxie preset produces a site that feels intentionally designed and presentable even before the user customizes images or text.

* The default site should feel wider, calmer, more complete, and less placeholder-driven.

* Do not introduce fake testimonials, fake client logos, fake product metrics, or unsupported claims.

* Do not solve the issue by simply shrinking everything or hiding missing media.

* Prefer elegant fallback design and calibrated defaults over brittle complexity.

* Files likely to modify:

  * `plugin/cafe-moxie-site-kit.php`
  * `plugin/patterns/*.php`
  * `plugin/templates/*.php`
  * possibly add `plugin/assets/*`
  * `cade-moxie-brand-guide.md`
  * `docs/IMPLEMENTATION-GUIDE.md`
  * `agents.md`

---

These are the two tasks I’d do next because they attack the actual remaining pain:

* **Task 30** fixes “why do I still need theme settings and why doesn’t the shell just work?”
* **Task 31** fixes “why does the generated site still feel narrow / unfinished / placeholder-ish?”

That is the clearest path from “30 tasks in and still fiddling” to “activate plugin, run setup, and the site actually looks done.”

---
End of file.
