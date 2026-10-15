# Titles

The titles feature sets the `<title>` tag for the incoming URI. A title set for the URI in the
admin screen overwrites the title of your page.

## Setup

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

`title()` outputs the `<title>` tag with the title of the URI, or with the title passed to it
(here the title of your view) when the URI has none.

## Add Title Tags

Browse and manage the titles at `https://example.com/admin/seo/seo_titles`, and add one at
`https://example.com/admin/seo/seo_titles/add`. A title has the following fields (the
`seo_titles` table, with the URI in `seo_uris`):

**URI**
The incoming URI that you want to assign the title to, as a path relative to the domain
(`/about`). It must be the same as the request, including the query string; wildcards and
regular expressions are not supported for titles.

**Title**
The title for this URI.

A URI can have only one title.
