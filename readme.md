# IndieAuth #
**Contributors:** [indieweb](https://profiles.wordpress.org/indieweb/), [pfefferle](https://profiles.wordpress.org/pfefferle/), [dshanske](https://profiles.wordpress.org/dshanske/)  
**Tags:** IndieAuth, IndieWeb, IndieWebCamp, login  
**Requires at least:** 4.9.9  
**Requires PHP:** 5.6  
**Tested up to:** 6.3  
**Stable tag:** trunk  
**License:** MIT  
**License URI:** http://opensource.org/licenses/MIT  
**Donate link:** https://opencollective.com/indieweb  

IndieAuth is a way to allow users to use their own domain to sign into other websites and services. 

## Description ##

The plugin allows WordPress to use a remote IndieAuth endpoint. This can be used to act as an authentication mechanism for WordPress and its REST API, as well as an identity mechanism for other sites. It uses the URL from the profile page to identify the blog user or your author url. We recommend your site be served over https to use this. We recommend you use the IndieAuth plugin, which makes your site an IndieAuth endpoint without any external options

You can also install this plugin to enable web sign-in for your site using your domain.

## Installation ##

1. Upload the `indieauth-remote` directory to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Set your endpoints.
4. That's it

## Frequently Asked Questions ##

### What is IndieAuth? ###

[IndieAuth](https://indieauth.net) is a way for doing Web sign-in, where you use your own homepage or author post URL( usually /author/authorname ) to sign in to other places. It is built on top of OAuth 2.0, which is used by many websites.

### Why IndieAuth? ###

IndieAuth is an extension to OAuth. If you are a developer, you have probably used OAuth to get access to APIs. As a user, if you have given an application access to your account on a service, you probably used OAuth. One advantage of IndieAuth is how easily it allows everyone's website to be their own OAuth Server without needing applications to register with each site.

### How is IndieAuth different from OAuth? ###

IndieAuth was built on top of OAuth 2.0 and differs in that users and clients are represented by URLs.  Clients can verify the identity of a user and obtain an OAuth 2.0 Bearer token that can be used to access user resources.

You can read the [specification](https://indieauth.spec.indieweb.org/) for implementation details.

### How is Web Sign In different from OpenID? ###

The goals of OpenID and Web Sign In are similar. Both encourage you to sign in to a website using your own domain name. However, OpenID has failed to gain wide adoption. Web sign-in prompts a user to enter a URL to sign on. Upon submission, it tries to discover the URL's authorization endpoint, and authenticate to that. If none is found, it falls back on other options.

This plugin only supports searching an external site for an authorization endpoint, allowing you to log into one site with the credentials of another site. This functionality may be split off in future into its own plugin.

### What is IndieAuth.com? ###

[Indieauth.com](https://indieauth.com) is an implementation of the IndieAuth Protocol. It has been deprecaed in favor of other solutions. 

### Does this require each user to have their own unique domain name? ###

No. When you provide the URL of the WordPress site and authenticate to WordPress, it will return the URL of your author profile as your unique URL. Only one user may use the URL of the site itself.
This setting is set in the plugin settings page, or if there is only a single user, it will default to them.

### How do I authenticate myself via Indieauth other than my domain? ###

That, as mentioned, depends on the server. Some may have a username/password combination, some other techniques. By adding Indieauth support, you can log into sites simply by providing your URL, but you still need to prove yourself to the IndieAuth server.

### How secure is this? ###

We recommend your site uses HTTPS to ensure your credentials are not sent in cleartext. 

### What is a token endpoint? ###

Once you have proven your identity, the token endpoint issues a token, which applications can use to authenticate as you to your site.

### How do I incorporate this into my plugin? ###

The WordPress function, `get_current_user_id` works to retrieve the current user ID if logged in via IndieAuth. The plugin offers the following functions to assist you in using IndieAuth for your service. We suggest you check on activation for the IndieAuth plugin by asking `if ( class_exists( 'IndieAuth_Plugin') )`

* `indieauth_get_scopes()` - Retrieves an array of scopes for the auth request.
* `indieauth_check_scope( $scope )` - Checks if the provided scope is in the current available scopes
* `indieauth_get_response()` - Returns the entire IndieAuth token response
* `indieauth_get_client_id()` - Returns the client ID
* `indieauth_get_me()` - Return the me property for the current session.

If any of these return null, the value was not set, and IndieAuth is not being used. Scopes and user permissions are not enforced by the IndieAuth plugin and must be enforced by whatever is using them. The plugin does contain a list of permission descriptions to display when authorizing, but this is solely to aid the user in understanding what the scope is for.

The scope description can be customized with the filter `indieauth_scope_description( $description, $scope )`

### I keep getting the response that my request is Unauthorized ###

Many server configurations will not pass bearer tokens. The plugin attempts to work with this as best possible, but there may be cases we have not encountered. The first step is to try running the diagnostic script linked to in the settings page. It will tell you whether tokens can be passed.

Temporarily enable [WP_DEBUG](https://codex.wordpress.org/Debugging_in_WordPress) which will surface some errors in your logs.

If your Micropub client includes an `Authorization` HTTP request header but you still get an HTTP 401 response with body `missing access token`, your server may be stripping the `Authorization` header. If you're on Apache, [try adding this line to your `.htaccess` file](https://github.com/indieweb/wordpress-micropub/issues/56#issuecomment-299202820):

    SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1

If you are not running the latest version of WordPress, [try this line](https://github.com/georgestephanis/application-passwords/wiki/Basic-Authorization-Header----Missing). It is added automatically as of 5.6:

    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

You can also try:

    CGIPassAuth On

If that doesn't work either, you may need to ask your hosting provider to whitelist the `Authorization` header for your account. If they refuse, you can [pass it through Apache with an alternate name](https://github.com/indieweb/wordpress-micropub/issues/56#issuecomment-299569822). The plugin searches for the header in REDIRECT_HTTP_AUTHORIZATION, as some FastCGI implementations store the header in this location.

### I get an error that parameter redirect_uri is missing but I see it in the URL ###

Some hosting providers filter this out using mod_security. For one user, they needed [Rule 340162](https://wiki.atomicorp.com/wiki/index.php/WAF_340162) whitelisted as it detects the use of a URL as an argument.

## Changelog ##

Project and support maintained on github at [indieweb/wordpress-indieauth-remote](https://github.com/indieweb/wordpress-indieauth-remote). This project was extracted from [indieweb/wordpress-indieauth](https://github.com/indieweb/wordpress-indieauth) which contains a full IndieAuth endpoint built into your site and is the fully maintained solution.

### 1.0.0 ###

* initial version forked from 
