# Search Engine Optimization CakePHP Plugin

[![GitHub License](https://img.shields.io/github/license/pieceofcake2/seo?label=License)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/pieceofcake2/seo?label=Packagist)](https://packagist.org/packages/pieceofcake2/seo)
![PHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/seo/php?logo=php&logoColor=%23FFFFFF&label=PHP&labelColor=%23777BB4&color=%23FFFFFF)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%205-brightgreen?label=PHPStan)](https://github.com/pieceofcake2/seo/actions/workflows/CI.yml)
![CakePHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/seo/pieceofcake2/cakephp?logo=cakephp&logoColor=%23FFFFFF&label=CakePHP&labelColor=%23D33C43&color=%23FFFFFF)
[![CI](https://img.shields.io/github/actions/workflow/status/pieceofcake2/seo/CI.yml?label=CI)](https://github.com/pieceofcake2/seo/actions/workflows/CI.yml)
[![Codecov](https://img.shields.io/codecov/c/gh/pieceofcake2/seo?label=Coverage)](https://codecov.io/gh/pieceofcake2/seo)

pieceofcake2/seo is a toolkit for the search engine optimization of CakePHP 2 applications:
301 redirects and status codes for URIs that would otherwise be 404s, 404 URL guessing from
your sitemap, meta tags, titles and canonical links per URI, scraper banning with honeypot
links, and Google Analytics A/B testing, all managed from admin screens. Originally developed
by Nick Baker.

See [CHANGELOG.md](CHANGELOG.md) for the version history.

## Requirements

* PHP 8.0+
* pieceofcake2/cakephp 2.12+

## Installation

```bash
composer require pieceofcake2/seo
```

Then load the plugin, create its tables and configure it.
See [Installation](docs/README.md#installation) and [Configuration](docs/README.md#configuration).

## Documentation

Documentation: https://pieceofcake2.github.io/seo/

## Contributing

See the [contribution guidelines](https://github.com/pieceofcake2/.github/blob/master/CONTRIBUTING.md).

## License

MIT License. See [LICENSE](LICENSE).
