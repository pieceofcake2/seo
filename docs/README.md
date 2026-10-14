# SEO Documentation

pieceofcake2/seo is a toolkit for the search engine optimization of CakePHP 2 applications,
managed from admin screens:

* Easy yet powerful 301 redirect tools only loaded when a 404 would otherwise occur
* Highly configurable and intelligent 404 deep URL guessing utilizing Levenshtein distance and your `sitemap.xml`
* Highly configurable and customizable meta tags for any incoming URI
* Title tag overwrites based on URI
* Canonical links based on URI
* Custom status codes based on URI
* Scraper banning administration, complete with honeypot baiting for scrapers to ban themselves
* Google Analytics A/B testing management based on URIs

See [CHANGELOG.md](../CHANGELOG.md) for the version history.

- [Installation](#installation)
- [Configuration](#configuration)
- [Admin Screens](#admin-screens)
- [Redirects and Status Codes](#redirects-and-status-codes)
- [Meta Tags](#meta-tags)
- [Titles](#titles)
- [Canonical Links](#canonical-links)
- [DNS Prefetch](#dns-prefetch)
- [Blacklist](#blacklist)
- [A/B Testing](#ab-testing)
- [Shells](#shells)
- [Wiki Links](#wiki-links)

## Installation

Install the plugin with [Composer](https://getcomposer.org/):

```bash
composer require pieceofcake2/seo
```

The plugin has the `cakephp-plugin` type, so it is installed into your plugins directory
by `composer/installers`. Set `installer-paths` in your application's `composer.json` so it
is installed into the right place:

| Layout | `installer-paths` |
| --- | --- |
| Modern (`config/`, `src/`, `templates/`, `plugins/`) | `"plugins/{$name}/": ["type:cakephp-plugin"]` |
| Legacy (`app/Config/`, `app/Plugin/`) | `"app/Plugin/{$name}/": ["type:cakephp-plugin"]` |

Load the plugin in `config/bootstrap.php` (legacy layout: `app/Config/bootstrap.php`):

```php
CakePlugin::load('Seo');
```

Create the plugin's tables in your database:

```bash
bin/cake schema create --plugin Seo

# legacy layout
app/Console/cake schema create --plugin Seo
```

The SQL is also available in [`Config/sql/install.sql`](../Config/sql/install.sql).

## Configuration

Copy [`Config/seo.php.default`](../Config/seo.php.default) of the plugin to `config/seo.php`
(legacy layout: `app/Config/seo.php`) and adjust it:

```php
$config = [
    'Seo' => [
        'approverEmail' => 'nick@example.com',
        'replyEmail' => 'noreply@example.com',
        'emailConfig' => 'default', // config of your email, if false will use default CakeEmail()
        'parentDomain' => 'http://www.example.com',
        'aggressive' => true, // if false, log offenders for later review instead of autobanning
        'triggerCount' => 2,
        'timeBetweenTriggers' => 60 * 60 * 24, // seconds
        'honeyPot' => ['admin' => false, 'plugin' => 'seo', 'controller' => 'seo_blacklists', 'action' => 'honeypot'],
        'log' => true,
        'cacheEngine' => false, // optionally cache things to save on DB requests - eg: 'default'
        'searchTerms' => true, // turn on term finding
        'levenshtein' => [
            'active' => false,
            'threshold' => 5, // -1 to ALWAYS find the closest match
            'cost_add' => 1, // cost to add a character, higher the amount the less you can add to find a match
            'cost_change' => 1, // cost to change a character, higher the amount the less you can change to find a match
            'cost_delete' => 1, // cost to delete a character, higher the amount the less you can delete to find a match
            'source' => '/sitemap.xml', // URL to list of urls in a sitemap
        ],
        'abTesting' => [
            'category' => 'ABTest', // Category for your ABTesting in Google Analytics
            'scope' => 3, // Scope for your ABTesting in Google Analytics
            'slot' => 4, // Slot for your ABTesting in Google Analytics
            'legacy' => false, // Uses Legacy version of Google Analytics JS code pageTracker._setCustomVar(...)
            'session' => true, // will use sessions to store tests for users who've already seen them.
            'redmine' => false, // or the full URL if your redmine http://www.redmine-example.com/issues/
        ],
        'dnsPrefetch' => [
            // Default prefetches, starting with // or http:// or https://
            // '//fonts.googleapis.com',
        ],
    ],
];
```

The file is loaded with `Configure::load('seo')` the first time a setting is read, unless
the `Seo` settings are already configured.

## Admin Screens

The redirects, status codes, meta tags, titles, canonical links, blacklist and A/B tests are
managed under `/admin/seo/...`. Enable the `admin` routing prefix in `config/core.php` (legacy
layout: `app/Config/core.php`) and protect the admin screens like the rest of your admin
area (the plugin's controllers extend your `AppController`):

```php
Configure::write('Routing.prefixes', ['admin']);
```

## Redirects and Status Codes

Use the plugin's exception handler in `config/core.php` (legacy layout: `app/Config/core.php`).
When a request results in a 404, it looks for a matching status code, then for a matching
301 redirect, then (if `levenshtein.active` is enabled) for the closest URL of your sitemap:

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

### Add Redirects

`http://www.example.com/admin/seo/seo_redirects/`

### Add Status Codes

`http://www.example.com/admin/seo/seo_status_codes/`

NOTE: Special case status code 200 will return a minimum bandwidth noindex robots page, for
alternative URL killing (410 alternative).

## Meta Tags

Include the `Seo.Seo` helper in your `AppController.php`:

```php
public $helpers = ['Seo.Seo'];
```

Alter your layout to include the SEO meta tags in the head of your layout:

```php
<head>
    <!-- other head items -->
    <?= $this->Seo->metaTags() ?>
</head>
```

`metaTags()` also takes an array of default meta tags (`name => content`); the tags managed
in the admin screen take priority.

### Add Meta Tags

`http://www.example.com/admin/seo/seo_meta_tags`

## Titles

Include the `Seo.Seo` helper in your `AppController.php`:

```php
public $helpers = ['Seo.Seo'];
```

Alter your layout to include the SEO title in the head of your layout:

```php
<head>
    <!-- other head items -->
    <?= $this->Seo->title($this->fetch('title')) ?>
</head>
```

### Add Title Tags

`http://www.example.com/admin/seo/seo_titles`

## Canonical Links

Include the `Seo.Seo` helper in your `AppController.php`:

```php
public $helpers = ['Seo.Seo'];
```

Alter your layout to include the SEO canonical link in the head of your layout:

```php
<head>
    <!-- other head items -->
    <?= $this->Seo->canonical() ?>
</head>
```

### Add Canonical Links

`http://www.example.com/admin/seo/seo_canonicals`

## DNS Prefetch

`$this->Seo->dnsPrefetch()` outputs `<link rel="dns-prefetch">` tags for the hosts in the
`dnsPrefetch` setting and the hosts passed to it:

```php
<head>
    <?= $this->Seo->dnsPrefetch(['//maps.googleapis.com']) ?>
</head>
```

## Blacklist

Include the `Seo.BlackList` component in your `AppController.php`:

```php
public $components = ['Seo.BlackList'];
```

Start adding honeypot links in and around your application to bait malicious content grabbers:

```php
<?= $this->Seo->honeyPot() ?>
```

Update your `robots.txt` to exclude `/seo/` from being spidered. All legitimate spiders will
ignore the honeypot:

```txt
User-agent: *
Disallow: /seo/
```

### Add/Manage Banned IPs

`http://www.example.com/admin/seo/seo_blacklists`

## A/B Testing

Include the `Seo.Seo` helper and the `Seo.ABTest` component in your `AppController.php`:

```php
public $helpers = ['Seo.Seo'];
public $components = ['Seo.ABTest'];
```

In your Google Analytics code on your site, add the line like so:

```php
<script type="text/javascript">
    <!-- GA Items -->
    var pageTracker = _gat._getTracker('UA-SOMEKEY');
    <?= $this->Seo->getABTestJS() ?>
</script>
```

The generated code uses the custom variables of the classic Google Analytics tracking code
(`_gaq.push(['_setCustomVar', ...])`, or `pageTracker._setCustomVar(...)` with
`abTesting.legacy`).

In your `AppController.php`, to test if you're on a testable page and serve it, do something
like this:

```php
public function beforeFilter()
{
    $test = $this->ABTest->getTest();
    if ($test) {
        // Do things specific to this test
        $this->set('ABTest', $test);
        $this->view = $test['SeoABTest']['slug'];
    }

    parent::beforeFilter();
}
```

ProTip: For debugging in your controller before going live in GA, set the debug option to
true. This will return tests that aren't active yet:

```php
$test = $this->ABTest->getTest(['debug' => true]);
```

### Add A/B Tests

`http://www.example.com/admin/seo/seo_a_b_tests`

ProTip: By setting the A/B test to debug, it will return true in your controller, but you
won't be tracking the GA code.

## Shells

Search and add redirects from the command line:

```bash
bin/cake Seo.seo_redirects search <url or redirect>
bin/cake Seo.seo_redirects add <url> <redirect> [priority] [callback]

# for example
bin/cake Seo.seo_redirects add '/mybad/path*' '/my-cleaned-up-path' 50
bin/cake Seo.seo_redirects add '#/some-old-route-(.*)#i' '/new-route-$1' 10
```

Import the URLs used by the Levenshtein matching from `levenshtein.source`, or add one:

```bash
bin/cake Seo.seo_urls import
bin/cake Seo.seo_urls add <url> <priority>
```

In the legacy layout, use `app/Console/cake` instead of `bin/cake`.

## Wiki Links

The wiki of the original plugin by Nick Baker:

* <https://github.com/webtechnick/CakePHP-Seo-Plugin/wiki/Seo-Redirects>
* <https://github.com/webtechnick/CakePHP-Seo-Plugin/wiki/Seo-Meta-Tags>
* <https://github.com/webtechnick/CakePHP-Seo-Plugin/wiki/Seo-Title-Tags>
