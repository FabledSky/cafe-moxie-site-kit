# Cafe Moxie Site Kit Enterprise Upgrade

This package upgrades the original site kit into a lean storefront plugin for Twenty Twenty-Five that is still editable in WordPress and still driven by Secure Custom Fields.

## What changed

The original kit had strong raw ingredients but it was still closer to a styled proof of concept than a reusable storefront layer. The biggest changes in this upgrade are:

- the design system is now driven by real settings and CSS tokens rather than a few partially wired knobs
- the canonical six-page Cafe Moxie starter set (Home, About Cafe Moxie, Browse the Counter, How It Works, Who It’s For, Trust + FAQ) now follows the brand guide structure
- the homepage can showcase live Edge Tool content using a shortcode-backed featured tools section
- the Edge Tool archive is now filterable and presentable as a storefront rather than a basic card list
- the single Edge Tool template now exposes the public SCF field groups in a structured, product-page-like layout
- internal ops metadata stays hidden publicly

## SCF coverage

The single template is designed around the SCF groups in your export:

- Core Product Info
- Platform + Compatibility
- File Handling
- Usage + Workflow
- Security + Data Handling
- Commerce
- Media + Presentation

The public template intentionally excludes the Internal Ops Metadata group from frontend output.

## Settings you can control in wp-admin

### Storefront
- featured tools count
- archive items per page
- archive filters on or off

### Layout and motion
- brand mark width
- header height target
- section max width
- hero min height
- card image ratio
- glow intensity
- border radius
- button scale
- motion on or off
- Google Font loading on or off

### Brand media
- brand mark image URL
- home hero image URL
- home story image URL
- about story image URL

### Calls to action
- home primary and secondary CTAs
- about CTA
- footer line

### Header and footer architecture
- enable or disable plugin-managed header/footer routing
- choose a managed header/footer preset
- configure brand treatment, navigation source, and header CTA
- configure footer content areas, utility copy, and legal/meta behavior
- generate or refresh managed `wp_template_part` header/footer assets with marker-safe updates
- provision plugin-managed fallback `wp_navigation` assets for primary/footer shell links
- use a single shell bootstrap action to apply preset + starter pages + navigation + template parts + front-page assignment in one pass
- apply a deterministic **Cafe Moxie polished defaults** preset action to restore the intended shell after experiments

### Brand preset defaults layer
- preset defaults are applied from a real preset map (`cafe_moxie` and `neutral`) rather than only per-field fallback defaults
- first install seeds settings using the Cafe Moxie polished preset
- preset re-apply/reset actions use `preset_participation` metadata to control which keys are intentionally managed by presets
- the neutral path remains available for reusable non-Cafe deployments while sharing the same registry contract

### Presentation setup state panel (Overview + Setup)
- the plugin overview now includes a **Presentation setup state** table designed as the day-to-day setup console
- it reports explicit shell diagnostics for header template part, footer template part, primary navigation, footer navigation, logo/media state, front page assignment, starter page pack state, and storefront readiness
- navigation diagnostics identify whether shell nav is coming from theme menu mapping or plugin-managed fallback navigation
- includes a guarded write-through action to assign the Home page as the static front page using WordPress core options (`show_on_front`, `page_on_front`)
- links directly to relevant core screens (Reading settings, Pages, Navigation, Site Editor template parts, Customizer logo control) so canonical values stay in core

### Plugin logging (Task 28)
- a dedicated **Logs** admin tab now provides:
  - logging level selector (`Advanced` default, optional `Basic`)
  - recent log viewer with type/status/time-range filters
  - clear logs action (confirmed) and downloadable text export
  - AI-ready copy/export format (`timestamp + type + status + message + JSON context`)
- logs are stored in a bounded WordPress option ring buffer (max 500 entries) to avoid unbounded growth and keep storage deterministic/lightweight
- use centralized helpers in plugin code:
  - `cm_log_event( $type, $message, $context = [] )`
  - `cm_log_error( $message, $context = [] )`
  - `cm_log_action( $action, $status, $context = [] )`
- context is sanitized, JSON-serializable, and size-limited; sensitive keys (tokens/passwords/nonces/cookies/api keys) are redacted
- logging failure is non-fatal: if storage fails, logging silently returns without breaking plugin flows
- required instrumentation coverage includes:
  - settings save/update
  - preset apply/reset + polished setup actions
  - starter page generation
  - header/footer generation
  - composed page/template rendering paths
  - content module rendering
  - SCF normalization edge cases
  - media fallback decisions

### Color tokens
All primary brand tokens from the guide are exposed as overrideable settings.

## Public Edge Tool layout

Each tool page now answers the product-page questions in a stable order:

1. annoying task
2. who deals with this
3. what goes in
4. what comes out
5. local or compute
6. how it is priced
7. what still needs judgment
8. why it saves time
9. who else uses it

Then it continues into technical sections for compatibility, file handling, security, workflow, media, and extra notes.

## Built for future extension

The plugin includes reusable methods and filters so you can keep growing without replacing the whole system:

- `cafe_moxie_tool_data`
- `cafe_moxie_edge_tool_archive_query_args`

That gives you a clean path to extend behavior in a mu-plugin, child plugin, or future custom integration.

## AI-ready architecture groundwork (non-functional)

To support future AI-assisted editing safely, the plugin now keeps composed page behavior in structured registries before rendering:

- composed section metadata lives in array-based registries (labels, template identifiers, supported content primitives)
- page templates (including the six-page Cafe Moxie starter set) resolve from one shared template registry instead of a parallel starter architecture
- section markup is mapped by template key with token placeholders
- a dedicated context map resolves runtime values (brand name, CTA URL, feature counts, layout classes)
- rendering applies visibility rules and token replacement before final block markup output

This means a future AI feature can propose or validate section plans as data structures first, without directly writing raw PHP template strings. No model calls, keys, or AI admin UI are included in this baseline.

The deterministic pre-integration contract is documented in `docs/ai-architecture.md`, and the plugin exposes registry snapshots + plan models through internal architecture helpers so future `gpt-5-mini` integration can remain review-gated and deterministic.

## Suggested next steps in WordPress

1. Import the SCF JSON export.
2. Install this upgraded plugin.
3. Add a brand mark image and real hero imagery.
4. Populate at least 3 Edge Tool posts with hero images, pricing, workflow, and trust cues.
5. Mark the strongest tools as featured.
6. Refresh the starter pages.
7. Fine-tune the page layouts visually in the block editor.

## Visual expectation

This kit is intentionally plugin-light and block-theme-native, so it will not copy Elementor’s exact authoring model. What it does give you is a cleaner, more maintainable path to a similarly polished storefront feel using native patterns, CSS tokens, SCF data, and reusable templates instead of a page-builder dependency.

## Visual stability defaults policy (Task 29)

The Cafe Moxie preset now treats **single-column, stacked composition as the safe baseline** for starter/composed sections and major template surfaces.

Guardrail rules:

- Default section layouts should prefer `single_column` and stack earlier on tablet/mobile.
- Two-column/split modes are opt-in and should only be used where they stay balanced with real-world (imperfect) content.
- Heading and CTA defaults must be bounded to prevent clipping, crowding, and dominant text in narrow cards/panels.
- Media framing should degrade gracefully when missing, portrait, or undersized instead of forcing brittle side-by-side layouts.
- Managed header composition must remain readable with long labels, optional navigation, and missing logo assets.

## Task status verification policy

For task tracking in `agents.md`, architecture scaffolding alone is not considered “delivered.”

A task should only be marked baseline-delivered/advanced after visual QA passes on a **clean WordPress install** with:

- Twenty Twenty-Five as the active theme
- only this plugin active (plus required SCF data import)
- desktop, tablet, and mobile checks for header/footer, starter/composed pages, Edge Tool archive/single templates, and setup actions

Required evidence for any “baseline-delivered/advanced” status update:

1. Exact environment declaration (WordPress version, theme, active plugins).
2. Action path used (fresh install flow + plugin actions invoked).
3. Visual checks performed (desktop/tablet/mobile minimum).
4. Regression notes for header/footer, starter/composed pages, Edge Tool archive/single, and setup panel actions.
5. Explicit pass/fail statement.

Until that QA pass is documented with the evidence above, status should remain “structurally present” or “partially polished.”

## Content-module parity QA checklist (Task 21.7)

Before marking content-module architecture as polished, verify:

1. **Archive parity:** `archive-edge_tool.php` renders identical card metadata and empty-state behavior with/without filters.
2. **Single parity:** `single-edge_tool.php` renders all registered module sections and degrades gracefully when sparse SCF fields are missing.
3. **Starter-page parity:** generated starter pages keep composed section structure and CTA/action cluster spacing after refresh.
4. **Managed shell parity:** generated header/footer continue to work after preset re-apply and polished setup flow.
5. **Responsive parity:** viewport checks at 1440, 1280, 1024, 782, 640, and 390 show no overlap in nav/button/action clusters.

## Clean-install QA checklist (Task 26.6)

Use this checklist before marking the setup-console experience as polished:

1. **Plugin activation state**
   - Activate only Twenty Twenty-Five + Cafe Moxie Site Kit (plus required SCF import).
   - Open **Site System Kit → Overview + Setup** and verify the quick-actions + setup-state panels render without PHP notices.
2. **Preset application**
   - Click **Apply Cafe Moxie polished defaults**.
   - Confirm a success notice appears and key defaults (brand preset, CTA defaults, shell settings) persist after refresh.
3. **Page generation**
   - Click **Generate / Refresh Cafe Moxie Starter Set**.
   - Confirm six canonical starter pages exist and setup-state reporting distinguishes managed generated pages vs unmanaged/user-edited pages vs missing pages.
4. **Header/footer generation**
   - Click **Generate / Refresh Managed Header + Footer**.
   - Confirm `cm-site-kit-header` and `cm-site-kit-footer` template parts exist and ownership reporting distinguishes managed vs unmanaged entries.
5. **Front-page assignment**
   - Click **Assign Home as Front Page**.
   - Confirm `show_on_front=page` and `page_on_front` points to Home.
6. **Desktop/tablet/mobile visual checks**
   - At minimum check 1440, 1024, and 390 widths.
   - Validate managed header/footer, starter pages, and Edge Tool archive/single pages remain visually stable.
