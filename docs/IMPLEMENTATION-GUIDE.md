# Cafe Moxie Site Kit Enterprise Upgrade

This package upgrades the original site kit into a lean storefront plugin for Twenty Twenty-Five that is still editable in WordPress and still driven by Secure Custom Fields.

## What changed

The original kit had strong raw ingredients but it was still closer to a styled proof of concept than a reusable storefront layer. The biggest changes in this upgrade are:

- the design system is now driven by real settings and CSS tokens rather than a few partially wired knobs
- the Home and About starter pages now follow the brand guide structure much more closely
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
