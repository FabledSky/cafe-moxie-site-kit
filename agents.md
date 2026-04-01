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

* main → staging
* production → live

#### Deployment

* GitHub Actions via SFTP

#### Rules

* Never edit production directly
* All changes via PR
* Always test on staging



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

`
"Read agents.md and implement Task 1, Steps: 1, 2 and 3"
`
```
"Read agents.md and implement Task:  , Steps:  "
```
### Task 1
Goals
#### Implementation Steps (1, 2, 3, ...)
Break down into clean manageable tasks AI agent cand handle in a single prompt.
#### Definition of done / COnstraints / Files to modify / etc.

---
End of file.
