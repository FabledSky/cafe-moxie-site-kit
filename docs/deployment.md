# Deployment notes

## Strategy
This repo uses GitHub Actions with SFTP deployment.

- `main` branch deploys to staging
- `production` branch deploys to production
- release workflow packages the `plugin/` directory as a ZIP artifact

## Production path
`/var/www/clients/client0/web25/web/wp-content/plugins/cafe-moxie-site-kit-enterprise-ready-fixed`

## Recommended staging path
Create a separate plugin directory on the same server, for example:
`/var/www/clients/client0/web25/web-staging/wp-content/plugins/cafe-moxie-site-kit-enterprise-ready-fixed`

If staging is the same WordPress install, use a staging site or different plugin path only if that server architecture supports it. Best practice is a separate staging WordPress install.

## Required GitHub secrets
- `SFTP_HOST_STAGING`
- `SFTP_PORT_STAGING`
- `SFTP_USERNAME_STAGING`
- `SFTP_PASSWORD_STAGING`
- `PLUGIN_REMOTE_PATH_STAGING`
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
