# Deployment notes

## Strategy
This repo uses GitHub Actions with SFTP deployment.

- `main` branch deploys to production
- release workflow packages the `plugin/` directory as a ZIP artifact

## Production path
`/var/www/clients/client0/web25/web/wp-content/plugins/cafe-moxie-site-kit-enterprise-ready-fixed`


## Required GitHub secrets
- `SFTP_HOST_PRODUCTION`
- `SFTP_PORT_PRODUCTION`
- `SFTP_USERNAME_PRODUCTION`
- `SFTP_PASSWORD_PRODUCTION`
- `PLUGIN_REMOTE_PATH_PRODUCTION`

## Deployment behavior
Each deploy workflow:
1. Checks out the repo
2. Builds a temporary plugin bundle from `plugin/`
3. Uploads the files over SFTP
4. Replaces remote plugin files in place

## WordPress post-deploy checklist
After deploy:
1. Clear any server and site caches
2. Visit the homepage and `/edge-tools/`
3. Confirm a sample single Edge Tool page
4. Re-save permalinks if template behavior changes
5. If SCF fields changed, re-import or sync the JSON definitions
