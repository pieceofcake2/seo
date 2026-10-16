# Redirects and Status Codes

The redirect tool sends a 301 redirect for a request that would otherwise end in a 404 Not
Found. It is used mainly for SEO, but also to send your users from a bad URL to a good one.
You define rules for incoming URIs: a simple URI, a wildcard or a regular expression, each with
a priority and an active flag. Status codes work the same way and answer a URI with a status
code of your choice, such as 410 Gone.

The rules are only loaded when a 404 would otherwise occur (or when you call `catch404()`
yourself, see [Setup](#setup)). A rule for a URI that your application answers without an error
is never used: if the page renders, the plugin is not even loaded. The tool is designed to
handle errors in a sane and helpful way for search engines and your users.

When a request results in a 404, the plugin checks, in this order:

1. the active [status codes](#add-status-codes), ordered by priority;
2. the active [redirects](#add-redirects), ordered by priority;
3. the closest URL of your sitemap, if [URL guessing](#url-guessing) is enabled.

If nothing matches, the usual 404 page is rendered.

## Setup

Use the plugin's exception handler in `config/core.php` (legacy layout: `app/Config/core.php`):

```php
Configure::write('Exception', [
    'handler' => 'SeoExceptionHandler::handle',
    'renderer' => 'ExceptionRenderer',
    'log' => true,
]);
```

Load the handler class in `config/bootstrap.php` (legacy layout: `app/Config/bootstrap.php`),
after loading the plugin:

```php
require_once CakePlugin::path('Seo') . 'Lib' . DS . 'Error' . DS . 'SeoAppError.php';
```

To run the status codes and redirects from your own code, for example in a controller that
handles unknown URLs itself, call `catch404()`:

```php
$seoAppError = new SeoAppError();
$seoAppError->catch404();
```

URIs are compared with the `REQUEST_URI` of the request, so they include the query string.

## Add Redirects

Browse and manage the redirects at `https://example.com/admin/seo/seo_redirects`, and add one at
`https://example.com/admin/seo/seo_redirects/add`. A redirect has the following fields
(the `seo_redirects` table, with the URI in `seo_uris`):

**URI**
The incoming URI that you want to redirect away from with a 301: a path relative to the domain
(`/old-page`), a path with a wildcard (`/old-section*`) or a regular expression that starts and
ends with `#` (see [Advanced Redirects](#advanced-redirects)).

**Redirect**
The URL to redirect a matching URI to with a 301, preferably relative to the domain. It can be
an absolute URL, a path, or a replacement pattern of a regular expression URI (`/guides/$1`).
When you use a [callback](#callback-redirects), put `{callback}` somewhere in the redirect: it is
replaced with the return value of the callback.

**Priority**
The priority of the rule, `100` by default. When several rules match, the rule with the
**lower** priority number wins. For example, with

- `/test.asp` to `/first-link` with priority 1
- `/test.asp` to `/other-link` with priority 2

a request for `/test.asp` is redirected to `/first-link`, not `/other-link`.

**Is Active**
Turns the rule on or off. Only active rules are used.

**Is Nocache** (edit screen only)
Sends `Cache-Control` and `Expires` headers with the 301 so that browsers don't cache the
redirect. Use it for redirects you may change later.

**Callback**
A method to call, as `Model::method` (or `Plugin.Model::method`). The requested URI is passed to
it, and its return value replaces `{callback}` in the redirect. If the method returns `false`,
the redirect is cancelled. A method name without `Model::` is called on the `SeoRedirect` model.

A rule whose redirect is the same as the request is skipped to avoid a redirect loop. With the
`log` setting on, redirects and detected loops are logged with the `seo_redirects` type.

## Types of Redirects

There are two types of redirects: basic and advanced.

### Basic Redirects

Basic redirects are one-to-one or many-to-one redirects. They are simple to write and to
understand. The URI and the redirect should be relative to the domain.

**One to one**

- URI: `/library`
- Redirect: `/guides/common-questions`
- Result: `https://example.com/library` redirects to `https://example.com/guides/common-questions`

The comparison is case-insensitive and must match the whole request.

**Many to one, with a wildcard**

- URI: `/website*`
- Redirect: `/`
- Result: `https://example.com/website`, `https://example.com/websites-are-fun` and
  `https://example.com/website/param/really/long/url` redirect to `https://example.com/`

A `*` at the end of the URI matches anything after the given part: the rule matches every
request that starts with `/website`.

### Advanced Redirects

Advanced redirects use a regular expression to match the request, and the redirect is a
replacement pattern for [`preg_replace()`](https://www.php.net/manual/en/function.preg-replace.php).
This is far more flexible, but harder to read and write. Advanced redirects are the only
redirects that can do many-to-many redirects.

A regular expression URI must start and end with `#` (optionally followed by modifiers such as
`i`), otherwise it is treated as a basic URI.

Regular expression rules are powerful, but they can also cause redirect loops and other
surprises. Proceed with caution.

A regular expression URI is saved as not approved (unless **Is Approved** is checked in the URIs
admin screen), and its rules are not used until it is approved. The plugin sends a notification email to `approverEmail` (from
`replyEmail`, with the `emailConfig` email configuration, see
[Configuration](README.md#configuration)). The email shows the URI, the redirect and the
priority, and links to `/admin/seo/seo_uris/approve/<URI id>`, which approves the URI. The link
starts with `parentDomain`, or with the full base URL of the application (`App.fullBaseUrl`)
when `parentDomain` is not set. You can also approve the URI at
`https://example.com/admin/seo/seo_uris` by editing it and checking **Is Approved**. Redirects
added with the [shell](#shells) are approved right away.

**Many to many**

- URI: `#(.*)\?from\=sb\-tracked\:(.*)#i`
- Redirect: `$1`
- Result: any URI that ends with `?from=sb-tracked:...` is redirected to the same URL without
  that query string.

The `i` at the end of the expression makes it case-insensitive. Each set of parentheses in the
URI is available in the redirect as `$1`, `$2` and so on, numbered from the left: here `$1` is
the part before the `?` and `$2` the value after `tracked:`.

- URI: `#^/article/([0-9a-zA-Z]*)#`
- Redirect: `/guides/$1`
- Result: an alias of `/article/` to `/guides/` that keeps the rest of the URI.

## Callback Redirects

A callback can do things that a `.htaccess` redirect can't, most notably look up the correct URL
in your database instead of showing a 404.

For example, your article URLs look like `https://example.com/article/123-slug`, with the id
`123` and the slug `slug`. To redirect any broken link with the id 123 to the URL with the
correct slug, you can write one rule with a callback instead of a rule per article:

- URI: `#^/article/([0-9]+)(.*)#i`
- Redirect: `/article/$1-{callback}`
- Callback: `Article::callbackFindSlugById`

Write the callback in your `Article` model:

```php
/**
 * Returns the slug of the requested article, for the SEO redirect.
 *
 * @param string $request the requested URI
 * @return string|false the slug, or false to cancel the redirect
 */
public function callbackFindSlugById($request)
{
    if (!preg_match('#^/article/([0-9]+)#', $request, $matches)) {
        return false;
    }

    $slug = $this->field('slug', ['id' => $matches[1]]);

    return $slug ?: false;
}
```

This rule redirects any broken article link to the correct `id-slug` URL. If the callback
returns `false`, for example for an unknown id, the redirect is cancelled and the 404 page is
shown.

## Add Status Codes

Browse and manage the status codes at `https://example.com/admin/seo/seo_status_codes`, and add
one at `https://example.com/admin/seo/seo_status_codes/add`. A status code has a URI (matched
like the [redirect URIs](#types-of-redirects)), a status code, a priority (lower first) and an
active flag. A matching request is answered with the status code and its reason phrase, for
example `410 Gone`, and nothing else.

NOTE: Special case status code 200 will return a minimum bandwidth noindex robots page, for
alternative URL killing (410 alternative).

## URL Guessing

With the `levenshtein` setting (see [Configuration](README.md#configuration)), a 404 that no
status code or redirect handles is redirected with a 301 to the closest URL of your sitemap, by
[Levenshtein distance](https://www.php.net/manual/en/function.levenshtein.php):

- `active`: turns URL guessing on.
- `source`: the sitemap, a path in your `webroot` (`/sitemap.xml`) or a URL. Its URLs are
  imported into the `seo_urls` table the first time they are needed, or with the
  [`seo_urls` shell](#shells).
- `cost_add`, `cost_change`, `cost_delete`: the cost of adding, changing and deleting a
  character. The higher a cost, the less of that change is accepted to find a match.
- `threshold`: the URLs are checked in the order of their priority in the sitemap, and the
  search stops at the first URL closer than the threshold; otherwise the closest URL is used.
  `-1` always checks all URLs and finds the closest one.

With the `log` setting on, these redirects are logged with the `seo_levenshtein` type.

## Shells

Manage your redirects with the `Seo.seo_redirects` shell:

```bash
# Quickly search for an existing redirect
bin/cake Seo.seo_redirects search <url or redirect>

# Add a new redirect, priority 100 by default
bin/cake Seo.seo_redirects add <url> <redirect> [priority] [callback]

# Delete redirects or URIs by id
bin/cake Seo.seo_redirects delete <id> [<id> ...]
bin/cake Seo.seo_redirects delete_uri <id> [<id> ...]
```

For example:

```bash
bin/cake Seo.seo_redirects add '/mybad/path*' '/my-cleaned-up-path' 50
bin/cake Seo.seo_redirects add '/myother-bad/path*' '/my-cleaned-up-path' 60
bin/cake Seo.seo_redirects add '/my*' '/my-failover-path' 10
bin/cake Seo.seo_redirects add '#/some-old-route-(.*)#i' '/new-route-$1' 10
bin/cake Seo.seo_redirects add '#/(admin|moderator)/(.*)#i' '/$2?old-prefix=$1' 10
```

The URI must start with `/` or `#`, and the redirect with `/` or `http` (or contain
`{callback}`). An existing redirect for the URI is not changed; edit it in the admin screen.

Import the URLs used by [URL guessing](#url-guessing) from `levenshtein.source` (only when
`levenshtein.active` is on; the import replaces all URLs), or add one:

```bash
bin/cake Seo.seo_urls import
bin/cake Seo.seo_urls add <url> <priority>
```

In the legacy layout, use `app/Console/cake` instead of `bin/cake`.

## Resources

Learn more about regular expressions:

- [Regular Expressions Tutorial](https://www.regular-expressions.info/tutorial.html)
- [Regular Expressions - User Guide](https://www.zytrax.com/tech/web/regex.htm)
- [PCRE pattern syntax](https://www.php.net/manual/en/reference.pcre.pattern.syntax.php) in the PHP manual

Test your regular expressions with [regex101](https://regex101.com/) (choose the PCRE flavor).
