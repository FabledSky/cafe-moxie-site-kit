# AGENTS.md — Cafe Moxie Site Kit (Source of Truth)

This document defines how humans and AI agents (Codex, ChatGPT, etc.) should understand, modify, and extend the **Cafe Moxie Site Kit** WordPress plugin.

It is the **single operational contract** for development.

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

#### Deployment

* GitHub Actions via SFTP

#### Rules

* Never edit production directly
* All changes via PR
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



### 10. What Agents SHOULD Do

* Refactor safely
* Improve normalization
* Improve rendering clarity
* Add reusable helpers
* Expand templates based on SCF



### 11. What Agents MUST NOT Do

* Break SCF compatibility
* Introduce plugin dependencies
* Add unnecessary abstraction
* Replace WordPress core behavior



### 12. Testing Rules

Before merging:

* Load homepage
* Load archive page
* Load single tool page
* Test empty fields
* Test partial data



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


## Tasks for Codex Agent

Add tasks below. Codex agent is to read agents.md (this file) and follow instructions like:

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

End of file.

If you want, I can also turn this into a **fully merged replacement `agents.md`** instead of just the task section.
