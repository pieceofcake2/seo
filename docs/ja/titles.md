# タイトル

タイトル機能は、リクエスト URI に応じて `<title>` タグを設定します。管理画面でその URI に設定した
タイトルは、ページのタイトルを上書きします。

<a id="setup"></a>

## セットアップ

`AppController.php` で `Seo.Seo` ヘルパーを読み込みます。

```php
public $helpers = ['Seo.Seo'];
```

レイアウトを変更して、レイアウトの head に SEO のタイトルを含めます。

```php
<head>
    <!-- other head items -->
    <?= $this->Seo->title($this->fetch('title')) ?>
</head>
```

`title()` は、URI のタイトルで `<title>` タグを出力します。URI にタイトルがない場合は、引数で渡した
タイトル (この例ではビューのタイトル) で出力します。

<a id="add-title-tags"></a>

## タイトルタグの追加

タイトルの一覧と管理は `https://example.com/admin/seo/seo_titles` で、追加は
`https://example.com/admin/seo/seo_titles/add` で行います。タイトルには次のフィールドがあります
(`seo_titles` テーブル、URI は `seo_uris`)。

**URI**
タイトルを割り当てたいリクエスト URI で、ドメインからの相対パス (`/about`) で指定します。
クエリ文字列も含めてリクエストと同じである必要があります。タイトルではワイルドカードと正規表現は
使えません。

**Title**
この URI のタイトルです。

1 つの URI に設定できるタイトルは 1 つだけです。
