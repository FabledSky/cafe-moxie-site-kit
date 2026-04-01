# Repo workflow

## Daily development loop
1. Create a feature branch
2. Ask Codex to work against that branch / PR
3. Review code in GitHub
4. Merge to `main`
5. Validate on live

## Good PR sizes
Prefer focused PRs:
- one rendering fix
- one new template section
- one SCF schema change
- one design system refinement

## SCF discipline
Whenever SCF changes:
1. Export the latest SCF JSON
2. Save it into `scf-json/`
3. Commit it in the same PR as the rendering/template updates

## Content vs code
Use Git for:
- plugin PHP
- templates
- patterns
- deployment workflows
- SCF exports

Use WordPress admin for:
- pages
- Edge Tool entries
- media
- menus
- plugin settings
