# SEO Documentation

pieceofcake2/seo is a toolkit for the search engine optimization of CakePHP 2 applications,
managed from admin screens:

* Easy yet powerful [301 redirect tools](redirects.md) only loaded when a 404 would otherwise occur
* Highly configurable and intelligent 404 deep [URL guessing](redirects.md#url-guessing) utilizing Levenshtein distance and your `sitemap.xml`
* Highly configurable and customizable [meta tags](meta-tags.md) for any incoming URI
* [Title tag](titles.md) overwrites based on URI
* [Canonical links](#canonical-links) based on URI
* Custom [status codes](redirects.md#add-status-codes) based on URI
* [Scraper banning](#blacklist) administration, complete with honeypot baiting for scrapers to ban themselves
* Google Analytics [A/B testing](#ab-testing) management based on URIs

See [CHANGELOG.md](../CHANGELOG.md) for the version history.

- [Installation](#installation)
- [Configuration](#configuration)
- [Admin Screens](#admin-screens)
- [Redirects and Status Codes](redirects.md)
- [Meta Tags](meta-tags.md)
- [Titles](titles.md)
- [Canonical Links](#canonical-links)
- [DNS Prefetch](#dns-prefetch)
- [Blacklist](#blacklist)
- [A/B Testing](#ab-testing)

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

Redirects, status codes, meta tags, titles, canonical links and A/B tests are attached to URIs
(the `seo_uris` table), which are managed at `https://example.com/admin/seo/seo_uris`. A URI is
created when you save one of them with a new URI. Regular expression URIs must be approved
there before they are used (see [Advanced Redirects](redirects.md#advanced-redirects)).

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

`https://example.com/admin/seo/seo_canonicals`

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

`https://example.com/admin/seo/seo_blacklists`

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
/**
 * Serve the view of the A/B test of this page, if there is one
 *
 * @return void
 */
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
$this->set('ABTest', $test);
```

### Add A/B Tests

`https://example.com/admin/seo/seo_a_b_tests`

ProTip: By setting the A/B test to debug, it will return true in your controller, but you
won't be tracking the GA code.

## Origin

This package is a maintained fork of
[webtechnick/CakePHP-Seo-Plugin](https://github.com/webtechnick/CakePHP-Seo-Plugin)
(`webtechnick/cakephp-seo-plugin` on Packagist, last upstream version v6.1.0), originally by Nick
Baker, Alan Blount and Matt Reishus. It is maintained for CakePHP 2 on modern PHP by
[pieceofcake2](https://github.com/pieceofcake2). Changes from the original are listed in
[CHANGELOG.md](https://github.com/pieceofcake2/seo/blob/6.x/CHANGELOG.md). The pages
[Redirects and Status Codes](redirects.md), [Meta Tags](meta-tags.md) and [Titles](titles.md)
are based on the [wiki](https://github.com/webtechnick/CakePHP-Seo-Plugin/wiki) of the original
plugin.
