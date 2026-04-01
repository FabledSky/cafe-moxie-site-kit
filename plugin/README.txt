Cafe Moxie Site Kit v2.0.0

Use with:
- Twenty Twenty-Five active
- Secure Custom Fields active
- Edge Tool post type and taxonomies imported from your SCF JSON export

What this version does
- Applies the Cafe Moxie brand system with color token overrides, layout controls, motion toggles, and optional Google Font loading
- Uses the brand guide copy structure for starter Home and About pages
- Creates editable starter pages without locking you out of the block editor
- Renders Featured Tools on the homepage from the Edge Tool custom post type
- Adds a filterable Edge Tool archive with search, taxonomy filters, and local / compute mode filters
- Renders a full single Edge Tool template from Secure Custom Fields, including media, file handling, compatibility, workflow, pricing, trust cues, and security details
- Keeps internal ops metadata hidden on the public storefront

Install
1. Activate Twenty Twenty-Five.
2. Import your Secure Custom Fields JSON so Edge Tool, taxonomies, and field groups are registered.
3. Upload and activate this plugin ZIP.
4. Go to Cafe Moxie in wp-admin.
5. Set brand media, CTA destinations, and any design token overrides.
6. Click Create / Refresh Starter Pages.
7. Set Home as the homepage in Settings > Reading if needed.
8. Edit the created pages in the block editor whenever you want tighter control over layout or copy.

Notes
- The brand mark image URL is used inside the starter pages and storefront templates. Set the global Site Logo in WordPress separately if you want it in the theme header.
- If you self-host fonts for privacy or performance, disable Google Font loading in the plugin settings.
- Featured tools are pulled from Edge Tool posts where the SCF field `featured_tool` is enabled. If none are marked featured, the plugin falls back to recent tools.
