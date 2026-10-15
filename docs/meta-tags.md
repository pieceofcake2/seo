# Meta Tags

The meta tags feature sets `<meta>` tags for the incoming URI. Use it to set global meta tags
for every page as well as to fine-tune the meta data of any page of your site.

## Setup

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

`metaTags()` also takes an array of default meta tags (`name => content`), for example from
your view variables. The tags managed in the admin screen take priority over the defaults with
the same name:

```php
<?= $this->Seo->metaTags([
    'description' => 'The default description of the page',
    'keywords' => 'cakephp, seo',
]) ?>
```

## Add Meta Tags

Browse and manage the meta tags at `https://example.com/admin/seo/seo_meta_tags`, and add one at
`https://example.com/admin/seo/seo_meta_tags/add`. A meta tag has the following fields (the
`seo_meta_tags` table, with the URI in `seo_uris`):

**URI**
The incoming URI that you want to assign the meta tag to: a path relative to the domain
(`/about`), a path with a wildcard (`/products*`) or a regular expression that starts and ends
with `#`.

**Name**
The name of the meta tag, for example:

- `description`
- `keywords`
- `author`
- `Content-Type` (with **Is Http Equiv**)

**Content**
The content of the meta tag, for example:

- a description of the page
- `keyword1, keyword2, keyword3`
- `Nick Baker`
- `text/html; charset=UTF-8`

**Is Http Equiv**
If checked, the name is output as the `http-equiv` attribute instead of the `name` attribute.

A URI can have several meta tags.

## Matching URIs

`metaTags()` first looks for the meta tags of the URI that is the same as the request (path and
query string). If there are none, it uses the meta tags of all wildcard and regular expression
URIs that match the request:

- A wildcard URI matches when the request contains the part before the `*`.
- A regular expression URI matches when the expression matches the request. Like for
  [redirects](redirects.md#advanced-redirects), it must be approved first.

With the `cacheEngine` setting (see [Configuration](README.md#configuration)), the list of
wildcard and regular expression URIs is cached with that cache configuration, so a new URI is
used after the cache expires or is cleared.
