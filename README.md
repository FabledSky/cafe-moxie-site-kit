# cafe-moxie-site-kit

GitHub source repo for the Cafe Moxie WordPress site kit.

This repo is structured for a lean WordPress workflow:
- `plugin/` contains the live plugin code deployed to WordPress
- `scf-json/` contains versioned Secure Custom Fields exports
- `docs/` contains implementation and deployment notes
- `.github/workflows/` contains GitHub Actions for production deploys and release ZIP packaging

## Current production target
- Live site: `https://moxie.fabledsky.com`
- Server: `rna.fabledsky.com` (`23.94.44.32`)
- Shell user: `defaultShell25`
- Current production plugin path:
  `/var/www/clients/client0/web25/web/wp-content/plugins/cafe-moxie-site-kit-enterprise-ready-fixed`

## Recommended branch model
- `main` → live production deploys
- feature branches / PRs → Codex or manual changes

## Repo layout
```text
cafe-moxie-site-kit/
├── .github/
│   ├── workflows/
│   └── ISSUE_TEMPLATE/
├── docs/
├── plugin/
├── scf-json/
└── scripts/
```

## How Codex should work on this repo
Ask Codex to make changes in branches and PRs, not directly on production. Good examples:
- Refactor the edge tool archive renderer
- Improve accessibility in the single edge tool template
- Add new SCF-backed product sections
- Harden plugin rendering against malformed SCF field values

## GitHub secrets to add
Set these repository secrets before enabling deploy workflows.

### Production deploy
- `PLUGIN_REMOTE_PATH_PRODUCTION`

### Production SFTP
- `SFTP_HOST_PRODUCTION`
- `SFTP_PORT_PRODUCTION`
- `SFTP_USERNAME_PRODUCTION`
- `SFTP_PASSWORD_PRODUCTION`


## Suggested GitHub variables
Use GitHub repository variables for non-secret defaults if you prefer:
- `LIVE_SITE_URL`
- `SERVER_IP`

## First-time setup
1. Create the GitHub repo named `cafe-moxie-site-kit`.
2. Upload this repo bundle or push it from local Git.
3. Add the GitHub secrets listed above.
4. Set `PLUGIN_REMOTE_PATH_PRODUCTION` to the live plugin directory.
5. Push to `main` to deploy live.

## Presentation setup console (plugin admin)
- Use **Site System Kit → Overview + Setup** as the default presentation-control console.
- Start with the **Quick actions (first-run + refresh)** card for one-click operational actions:
  - Apply Cafe Moxie polished defaults
  - Generate / Refresh Cafe Moxie Starter Set
  - Generate / Refresh Managed Header + Footer
  - Assign Home as Front Page
  - Preview Site
- The **Presentation setup state** panel reports readiness for:
  - front page assignment
  - plugin-managed generated starter pages vs unmanaged/user-edited vs missing
  - plugin-managed template parts vs unmanaged/user-edited vs missing
  - primary navigation assignment
  - logo/media readiness
  - Edge Tool archive/storefront readiness
- Where WordPress owns canonical values, the panel links directly to core screens (Reading settings, menus, Site Editor, Customizer) instead of creating duplicate plugin-only state.

## Packaging a manual ZIP
Run:
```bash
bash scripts/package-plugin.sh
```
That will create a release ZIP in `dist/`.

## Notes
- The plugin code in `plugin/` is currently aligned to the live folder name already in use on the server.
- The repo name is cleaner than the current remote plugin directory. You can migrate the plugin slug later once production is stable.
