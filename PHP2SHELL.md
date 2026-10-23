# PHP2Shell: what we did

## The problem

PHP2Shell is a bug in PHP itself, not in the pages of this app. The two bugs are CVE-2026-109618 and CVE-2026-109619.

A visitor does not need a password. On a weak PHP build, PHP can be tricked into running commands on the server. On Apache with the PHP module, this can happen before `index.php` starts. That is why changing only the app cannot fully fix it.

The same bug affects `ppa-otp` and `ppa-api-otp`. Both are CodeIgniter 3.1.4. They are safe only when the server PHP is safe.

## Safe PHP versions

Use one of these, or a newer release in the same line:

- PHP 8.5.12 or newer
- PHP 8.4.27 or newer
- PHP 8.3.36 or newer
- PHP 8.2.35 or newer

PHP 8.1 and older do not get this fix from the PHP project. Move those servers to a version in the list above.

After the update, restart Apache so every PHP worker loads the new build.

## What this computer already had

Apache's main PHP module is already PHP 8.5.12, which includes the fix. `ppa-otp` uses that module.

`ppa-api-otp` did not. A WAMP alias file (`C:\wamp64\alias\ppa-uams.conf`) sent it to PHP 7.4.33. PHP 7.4 is too old to get this fix, and upload-progress tracking was still on. That is the unsafe setup.

The memory cap is still 128 MB. Leave that cap on. Turning memory limits off is only a temporary idea for servers that cannot update yet, and it lets one visit use all of the computer's memory.

## What we changed

`ppa-api-otp` was taken off PHP 7.4 in two places:

- Its `.htaccess` tells Apache to run PHP files with the patched PHP module, not the old FastCGI PHP.
- The WAMP alias file no longer assigns that folder to PHP 7.4. That part applies the next time Apache is restarted.

PHP 7.4 is still used by the UAMS folders. Upload-progress tracking was turned off in that PHP 7.4 config. Those folders are still not safe until they move to a patched PHP.

## What we changed in the apps

1. **The site stays off on an unsafe PHP.**  
   `index.php` loads `application/config/php_runtime_check.php` first. If PHP is not in the safe list, the site returns "This site is off until PHP is updated to a safe release." This does not stop an attack that happens before the file runs. It does stop the app from opening on a server that was not updated.

2. **Upload-progress tracking is turned off in `.htaccess`.**  
   PHP asked for this setting to be applied by Apache before PHP reads the request. `ini_set()` inside the app is too late. This blocks one known path. It is not a full fix.

3. **Old PHP settings are no longer set on current PHP.**  
   CodeIgniter was still setting `mbstring.internal_encoding` and `iconv.internal_encoding` on every visit. Those settings are out of date and still raise warnings. Hiding the warning does not help. They are now set only on very old PHP, where they are still valid.

4. **Development mode no longer prints PHP deprecation notices.**  
   CodeIgniter 3 still runs on the patched PHP, but PHP 8 prints a lot of old-style warnings. Those warnings were hiding the real page. Real errors are still shown.

## What we did not change

We did not turn off the PHP memory cap. This server can run the patched PHP, so that temporary step is not needed.

Some backup and restore tools in `ppa-api-otp` still raise the memory cap after the page starts. That happens too late to fix PHP2Shell. Those tools were left as they are so backups keep working.

## What to do on the public server

1. Check the PHP version on the live server. Do not assume it matches this computer.
2. Install a safe version from the list above.
3. Restart Apache.
4. Open the site. It should load as usual.
5. If you see "This site is off until PHP is updated to a safe release," the live PHP is still too old.

There is no setting that replaces the PHP update.
