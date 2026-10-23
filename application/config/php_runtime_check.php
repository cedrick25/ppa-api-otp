<?php
/**
 * Stop the app when PHP is still in a PHP2Shell-affected release.
 * Safe releases: 8.2.35+, 8.3.36+, 8.4.27+, 8.5.12+, and anything newer.
 * This file is not a full fix. On a weak PHP build the attack can happen
 * before this file runs, so the server PHP must be updated and Apache restarted.
 */
if (!ppa_php_runtime_is_safe(PHP_VERSION)) {
	if (!headers_sent()) {
		http_response_code(503);
		header('Content-Type: text/plain; charset=UTF-8');
		header('Retry-After: 3600');
	}
	echo "This site is off until PHP is updated to a safe release.\n";
	exit(1);
}

function ppa_php_runtime_is_safe($version)
{
	if (version_compare($version, '8.2.35', '>=') && version_compare($version, '8.3.0', '<')) {
		return true;
	}
	if (version_compare($version, '8.3.36', '>=') && version_compare($version, '8.4.0', '<')) {
		return true;
	}
	if (version_compare($version, '8.4.27', '>=') && version_compare($version, '8.5.0', '<')) {
		return true;
	}
	if (version_compare($version, '8.5.12', '>=')) {
		return true;
	}
	return false;
}
