# AI Architecture Contract (Deterministic, Pre-Integration)

This document defines how future AI-assisted site editing can plug into the Cafe Moxie Site Kit **without** introducing non-deterministic or unsafe write paths.

## Current scope

The current baseline is architecture-only:

- no model calls
- no SDK integration
- no key management
- no autonomous apply flow

The plugin only exposes structured registries, plan models, and safe-action boundaries.

## Structured plan models

Future AI proposals should be represented as typed plan objects:

1. design token plans
2. layout setting plans
3. header/footer plans
4. page template plans
5. section instance plans
6. content module render plans
7. safe mutation/apply actions

These are represented in plugin code by `CafeMoxieSiteKit::ai_architecture_contract()` and helper snapshot methods.

## Deterministic execution flow

Any future integration should keep this fixed order:

1. inspect current site/plugin state
2. serialize structured context from registries + current settings
3. generate a proposed plan diff
4. require explicit human review/approval
5. apply through existing safe generator/update paths only
6. record what changed

## Registry exposure requirements

The architecture requires that all proposal-relevant registries expose:

- human-readable labels
- constraints (`type`, `min`, `max`, `allowed_values`)
- supported fields
- example payload shapes

The plugin now provides structured snapshots for:

- settings registry
- page template registry
- composed section registry
- content module registry

## Safe mutation boundaries

Allowed apply actions are constrained to existing deterministic paths, including:

- settings updates through sanitize/validation
- composed page generation from registered templates/sections
- generated page create/update paths
- managed header/footer generation paths
- guarded front-page assignment using WordPress core options

Explicitly out of scope:

- arbitrary PHP/code mutation
- raw SQL/direct DB writes
- freeform page-builder style edits outside registries

## Integration seam for future `gpt-5-mini`

When model integration is added later, the model-facing adapter should:

- call snapshot methods to build prompt/context payloads
- request a plan object conforming to the contract
- run server-side validation against registry constraints
- present a human-readable diff in admin UI
- invoke safe actions only after approval

This keeps the model as a **proposal engine**, not a direct mutation runtime.
