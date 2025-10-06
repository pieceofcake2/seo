# Changelog

## 6.2.2
- Updated to PHPUnit 9 compatibility
- Converted all `App::import` to `App::uses` throughout codebase
- Replaced `EmailComponent` with `CakeEmail` in tests
- Fixed deprecated PHPUnit mock syntax (`expectOnce`/`expectNever` → `expects($this->once())`/`expects($this->never())`)
- Fixed model `save()` return values to properly return boolean
- Fixed `SeoHoneypotVisit::add()` to use `create()` for multiple saves
- Updated `Config/seo.php.default` to modern array syntax
- All tests passing (66 tests, 132 assertions)

## 6.2.1
- Cleanup and bug fix, no more duplicate errors
- New is_nocache boolean for SeoRedirects

## 6.2.0
- Added SeoABTesting
- Update your Config/seo.php file

## 6.1.0
- Added special case 200 Status Code to return noindex for easier and low bandwidth url killing than 410

## 6.0.0
- Updated for CakePHP 2.0

## 5.1.0
- New SeoUrls Shell to run sitemap levenshtein import on-demand

## 5.0.0
- New Levenshtein Distance formula to best guess the appropriate 301 based off the 404 request
- This only happens if it's active in the config (default false), and no 301 redirect rules would catch it

## 4.5.1
- Fixed a bug where wildcard uri's would match anywhere in the url instead of from the base
- /user* would match /users/login as well as /admin/users/login. That is not the desired result

## 4.5.0
- New Seo Canonical gives an SEO the ability to Canonical link any url much like the Seo Title tool

## 4.4.0
- New Seo Status Codes gives an SEO the ability to 410, or any other error code based on URI

## 4.3.0
- SeoHelper::metaTags now takes in an associative array of default meta tags to use (seo tags have priority)

## 4.2.x
- Bug fixes and updates
- Update config file

## 4.0.0
- Blacklist added

## 1.0.0
- Initial Release
