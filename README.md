# Search Engine Optimization CakePHP Plugin

[![GitHub License](https://img.shields.io/github/license/pieceofcake2/seo?label=License)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/pieceofcake2/seo?label=Packagist)](https://packagist.org/packages/pieceofcake2/seo)
![PHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/seo/php?logo=php&logoColor=%23FFFFFF&label=PHP&labelColor=%23777BB4&color=%23FFFFFF)
![CakePHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/seo/pieceofcake2/cakephp?logo=cakephp&logoColor=%23FFFFFF&label=CakePHP&labelColor=%23D33C43&color=%23FFFFFF)
[![CI](https://img.shields.io/github/actions/workflow/status/pieceofcake2/seo/CI.yml?label=CI)](https://github.com/pieceofcake2/seo/actions/workflows/CI.yml)
[![Codecov](https://img.shields.io/codecov/c/gh/pieceofcake2/seo?label=Coverage)](https://codecov.io/gh/pieceofcake2/seo)

__This is forked for CakePHP2.__

## Features

Complete tool for all your CakePHP Search Engine Optimization needs

* Easy yet powerful 301 redirect tools only loaded when a 404 would otherwise occur
* Highly configurable and intelligent 404 deep url guessing utilizing levenshtein's distance and your sitemap.xml
* Highly configurable and customizable Meta Tags for any incoming URI
* Title tag overwrites based on URI
* Custom Status Codes based on URI
* Scrapper Banning administration, complete with honeyPot baiting for scrappers to ban themselves.
* Google Analytics AB Testing Management based on URIs

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history.

## Install

Clone the repository into your `app/Plugin/Seo` directory:

```bash
composer require pieceofcake2/seo
```

Run the schema into your database:

```bash
cake schema create --plugin Seo
```

## Setup

Create the file `app/Config/seo.php` with the following configurations like so:

```php
$config = array(
    'Seo' => array(
        'approverEmail' => 'nick@example.com',
        'replyEmail' => 'noreply@example.com',
        'emailConfig' => 'default', //config of your email, if false will use default CakeEmail()
        'parentDomain' => 'http://www.example.com',
        'aggressive' => true, //if false, log affenders for later review instead of autobanning
        'triggerCount' => 2,
        'timeBetweenTriggers' => 60 * 60 * 24, //seconds
        'honeyPot' => array('admin' => false, 'plugin' => 'seo', 'controller' => 'seo_blacklists', 'action' => 'honeypot'),
        'log' => true,
        'cacheEngine' => false, // optionally cache things to save on DB requests - eg: 'default'
        'searchTerms' => true, //turn on term finding
        'levenshtein' => array(
            'active' => false,
            'threshold' => 5, //-1 to ALWAYS find the closest match
            'cost_add' => 1, //cost to add a character, higher the amount the less you can add to find a match
            'cost_change' => 1, //cost to change a character, higher the amount the less you can change to find a match
            'cost_delete' => 1, //cost to delete a character, higher the ammount the less you can delete to find a match
            'source' => '/sitemap.xml' //URL to list of urls in a sitemap
        ),
        'abTesting' => array(
            'category' => 'ABTest', //Category for your ABTesting in Google Analytics
            'scope' => 3, //Scope for your ABTesting in Google Analytics
            'slot' => 4, //Slot for your ABTesting in Google Analytics
            'legacy' => false, //Uses Legacy verion of Google Analytics JS code pageTracker._setCustomVar(...)
            'session' => true, //will use sessions to store tests for users who've already seen them.
            'redmine' => false, //or the full URL if your redmine http://www.redmine-example.com/issues/
        )
    )
);
```

## SEO Redirect/Status Code Quick Start
update file `app/Config/core.php` with the following:

```php
Configure::write('Exception', array(
    'handler' => 'SeoExceptionHandler::handle',
    'renderer' => 'ExceptionRenderer',
    'log' => true
));
```

update file `app/Config/bootstrap.php` with the following:

```php
require_once(APP . 'Plugin' . DS . 'Seo' . DS . 'Lib' . DS . 'Error' . DS . 'SeoAppError.php');
```

### Add Redirects

`http://www.example.com/admin/seo/seo_redirects/`

### Add Status Codes

`http://www.example.com/admin/seo/seo_status_codes/`

NOTE: Special case Status Code 200 will return minimum bandwidth noindex robots page, for alternative url killing (410 alternative)


## SEO Meta Tags Quick Start

Include the `Seo.Seo` Helper to your `AppController.php`:

```php
var $helpers = array('Seo.Seo');
```

Alter your layout to include the Seo Meta Tags in the head of your layout

```php
<head>
    <!-- other head items -->
    <?php echo $this->Seo->metaTags(); ?>
</head>
```

### Add Meta Tags

`http://www.example.com/admin/seo/seo_meta_tags`


## SEO Titles Quick Start

Include the `Seo.Seo` Helper to your `AppController.php`:

```php
var $helpers = array('Seo.Seo');
```

Alter your layout to include the Seo Title in the head of your layout

```php
<head>
    <!-- other head items -->
    <?php echo $this->Seo->title($title_for_layout); ?>
</head>
```

### Add Title Tags

`http://www.example.com/admin/seo/seo_titles`


## SEO Canonical Quick Start

Include the `Seo.Seo` Helper to your `AppController.php`:

```php
var $helpers = array('Seo.Seo');
```

Alter your layout to include the Seo Canonical in the head of your layout

```php
<head>
    <!-- other head items -->
    <?php echo $this->Seo->canonical(); ?>
</head>
```

### Add Canonical Links

`http://www.example.com/admin/seo/seo_canonicals`

## SEO BlackList Quick Start

Include The `Seo.BlackList` Component in your `AppController.php`:

```php
var $components = array('Seo.BlackList');
```

Start adding honeypot links in and around your application to bait malicious content grabbers

```php
<?php echo $this->Seo->honeyPot(); ?>
```

Update your `robots.txt` to exclude `/seo/` from being spidered.  All legitimate spiders will ignore the honeyPot

```txt
User-agent: *
Disallow: /seo/
```

### Add/Manage Banned IPs

`http://www.example.com/admin/seo/seo_blacklists`


## SEO AB Testing Quick Start

Include the `Seo.Seo` Helper and the `Seo.ABTest` Component to your `AppController.php`:

```php
var $helpers = array('Seo.Seo');
var $components = array('Seo.ABTest');
```

In your GA code on your site add the line like so:

```php
<script type="text/javascript">
    <!-- GA Items -->
    var pageTracker = _gat._getTracker('UA-SOMEKEY');
    <?php echo $this->Seo->getABTestJS(); ?>
</script>
```

In your `AppController.php`, to test if you're on a testable page and serve it do something like this:

```php
public function beforeFilter(){
    if($test = $this->ABTest->getTest()){
        //Do things specific to this test
        $this->set('ABTest', $test);
        $this->view = $test['SeoABTest']['slug'];
    }
    return parent::beforeFilter();
}
```

ProTip: For debuging in your controller before going live in GA set the debug flag to true, this will return tests that aren't active yet.

```php
$test = $this->SeoABTest->getTest(array('debug' => true));
```

### Add AB Tests

`http://www.example.com/admin/seo/seo_a_b_tests`

ProTip: By setting the ABTest to debug, it will return true in your controller, but you won't be tracking the GA code.


# Wiki Links
  * <https://github.com/webtechnick/CakePHP-Seo-Plugin/wiki/Seo-Redirects>
  * <https://github.com/webtechnick/CakePHP-Seo-Plugin/wiki/Seo-Meta-Tags>
  * <https://github.com/webtechnick/CakePHP-Seo-Plugin/wiki/Seo-Title-Tags>
