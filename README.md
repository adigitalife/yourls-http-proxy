# HTTP Proxy for YOURLS
This plugin adds HTTP proxy support for YOURLS.  This is particularly useful when YOURLS is running behind a firewall (e.g. corporate intranet).

####Instructions:

1. Copy the 'case-insensitive' folder to user/plugins/.
2. Enter the proxy server in the following line of plugin.php and save:
    * `$proxy = 'http://PROXY_SERVER:PORT/';  // Define your proxy server here`
3. Activate the plugin in the YOURLS admin interface.

That's it.
