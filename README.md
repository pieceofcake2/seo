# Search Engine Optimization CakePHP Plugin

[![GitHub License](https://img.shields.io/github/license/pieceofcake2/seo?label=License)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/pieceofcake2/seo?label=Packagist)](https://packagist.org/packages/pieceofcake2/seo)
![PHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/seo/php?logo=php&logoColor=%23FFFFFF&label=PHP&labelColor=%23777BB4&color=%23FFFFFF)
![CakePHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/seo/pieceofcake2/cakephp?logo=cakephp&logoColor=%23FFFFFF&label=CakePHP&labelColor=%23D33C43&color=%23FFFFFF)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%205-brightgreen?label=PHPStan)](https://github.com/pieceofcake2/seo/actions/workflows/CI.yml)
[![CI](https://img.shields.io/github/actions/workflow/status/pieceofcake2/seo/CI.yml?label=CI)](https://github.com/pieceofcake2/seo/actions/workflows/CI.yml)
[![Codecov](https://img.shields.io/codecov/c/gh/pieceofcake2/seo?label=Coverage)](https://codecov.io/gh/pieceofcake2/seo)

pieceofcake2/seo is a toolkit for the search engine optimization of CakePHP 2 applications:
301 redirects and status codes for URIs that would otherwise be 404s, 404 URL guessing from
your sitemap, meta tags, titles and canonical links per URI, scraper banning with honeypot
links, and Google Analytics A/B testing, all managed from admin screens. Originally developed
by Nick Baker.

See [CHANGELOG.md](CHANGELOG.md) for the version history.

## Documentation

- Website: https://pieceofcake2.github.io/seo/ (日本語: https://pieceofcake2.github.io/seo/ja/)
- Markdown: [docs/README.md](docs/README.md) (日本語: [docs/ja/README.md](docs/ja/README.md)) (for AI tools and offline reading)

The Markdown documentation is also included in the Composer package, in the plugin's directory: `plugins/Seo/docs/README.md` (the location depends on your `installer-paths`). Install the package with `--dev` if you only need it for development.

## Requirements

* PHP 8.0+
* pieceofcake2/cakephp 2.12+

## Installation

```bash
composer require pieceofcake2/seo
```

Then load the plugin, create its tables and configure it.
See [Installation](docs/README.md#installation) and [Configuration](docs/README.md#configuration).

## Contributing

See the [contribution guidelines](https://github.com/pieceofcake2/.github/blob/master/CONTRIBUTING.md).

## Origin

This package is a maintained fork of
[webtechnick/CakePHP-Seo-Plugin](https://github.com/webtechnick/CakePHP-Seo-Plugin)
(`webtechnick/cakephp-seo-plugin` on Packagist, last upstream version v6.1.0), originally by Nick
Baker, Alan Blount and Matt Reishus. It is maintained for CakePHP 2 on modern PHP by
[pieceofcake2](https://github.com/pieceofcake2). Changes from the original are listed in
[CHANGELOG.md](https://github.com/pieceofcake2/seo/blob/6.x/CHANGELOG.md). The
documentation includes the pages of the original plugin's
[wiki](https://github.com/webtechnick/CakePHP-Seo-Plugin/wiki).

## License

MIT License. See [LICENSE](LICENSE).
