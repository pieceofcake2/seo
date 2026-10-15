# メタタグ

メタタグ機能は、リクエスト URI に応じて `<meta>` タグを設定します。すべてのページに共通のメタタグを
設定するのにも、サイトの任意のページのメタデータを細かく調整するのにも使えます。

<a id="setup"></a>

## セットアップ

`AppController.php` で `Seo.Seo` ヘルパーを読み込みます。

```php
public $helpers = ['Seo.Seo'];
```

レイアウトを変更して、レイアウトの head に SEO のメタタグを含めます。

```php
<head>
    <!-- other head items -->
    <?= $this->Seo->metaTags() ?>
</head>
```

`metaTags()` には、デフォルトのメタタグの配列 (`name => content`) を、たとえばビュー変数から渡すことも
できます。管理画面で管理するタグは、同じ名前のデフォルトより優先されます。

```php
<?= $this->Seo->metaTags([
    'description' => 'The default description of the page',
    'keywords' => 'cakephp, seo',
]) ?>
```

<a id="add-meta-tags"></a>

## メタタグの追加

メタタグの一覧と管理は `https://example.com/admin/seo/seo_meta_tags` で、追加は
`https://example.com/admin/seo/seo_meta_tags/add` で行います。メタタグには次のフィールドがあります
(`seo_meta_tags` テーブル、URI は `seo_uris`)。

**URI**
メタタグを割り当てたいリクエスト URI です。ドメインからの相対パス (`/about`)、ワイルドカード付きの
パス (`/products*`)、または `#` で始まり `#` で終わる正規表現を指定します。

**Name**
メタタグの名前です。例:

- `description`
- `keywords`
- `author`
- `Content-Type` (**Is Http Equiv** と一緒に)

**Content**
メタタグの内容です。例:

- ページの説明
- `keyword1, keyword2, keyword3`
- `Nick Baker`
- `text/html; charset=UTF-8`

**Is Http Equiv**
チェックを入れると、名前は `name` 属性ではなく `http-equiv` 属性として出力されます。

1 つの URI に複数のメタタグを設定できます。

<a id="matching-uris"></a>

## URI の照合

`metaTags()` は、まずリクエスト (パスとクエリー文字列) と同じ URI のメタタグを探します。
なければ、リクエストに一致するすべてのワイルドカードと正規表現の URI のメタタグを使います。

- ワイルドカードの URI は、リクエストが `*` より前の部分を含む場合に一致します。
- 正規表現の URI は、式がリクエストに一致する場合に一致します。
  [リダイレクト](redirects.md#advanced-redirects) と同じく、先に承認しておく必要があります。

`cacheEngine` 設定 ([設定](README.md#configuration) をご覧ください) を使うと、ワイルドカードと
正規表現の URI の一覧がそのキャッシュ設定でキャッシュされます。そのため、新しい URI はキャッシュが
期限切れになるかクリアされた後に使われます。
