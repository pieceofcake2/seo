# リダイレクトとステータスコード

リダイレクトツールは、本来 404 Not Found になるリクエストに対して 301 リダイレクトを送ります。
主に SEO のために使いますが、ユーザーを誤った URL から正しい URL へ案内するためにも使えます。
リクエスト URI に対するルールを定義します。ルールはシンプルな URI、ワイルドカード、正規表現の
いずれかで、それぞれに優先度と有効フラグがあります。ステータスコードも同じ仕組みで、URI に対して
410 Gone など任意のステータスコードを返します。

ルールが読み込まれるのは、本来 404 になる場合 (または自分で `catch404()` を呼んだ場合。
[セットアップ](#setup) をご覧ください) だけです。アプリケーションがエラーなく応答する URI のルールは
使われません。ページが表示されるなら、プラグインは読み込まれもしません。このツールは、検索エンジンと
ユーザーにとって適切で役に立つ方法でエラーを扱うように設計されています。

リクエストが 404 になると、プラグインは次の順に確認します。

1. 有効な [ステータスコード](#add-status-codes) (優先度順)
2. 有効な [リダイレクト](#add-redirects) (優先度順)
3. [URL 推測](#url-guessing) が有効な場合、サイトマップの中で最も近い URL

何も一致しなければ、通常の 404 ページが表示されます。

<a id="setup"></a>

## セットアップ

`config/core.php` (レガシーレイアウトでは `app/Config/core.php`) でプラグインの例外ハンドラーを使います。

```php
Configure::write('Exception', [
    'handler' => 'SeoExceptionHandler::handle',
    'renderer' => 'ExceptionRenderer',
    'log' => true,
]);
```

`config/bootstrap.php` (レガシーレイアウトでは `app/Config/bootstrap.php`) で、プラグインを読み込んだ後に
ハンドラーのクラスを読み込みます。

```php
require_once CakePlugin::path('Seo') . 'Lib' . DS . 'Error' . DS . 'SeoAppError.php';
```

自分のコードから、たとえば未知の URL を自分で処理するコントローラーから、ステータスコードとリダイレクトを
実行するには `catch404()` を呼びます。

```php
$seoAppError = new SeoAppError();
$seoAppError->catch404();
```

URI はリクエストの `REQUEST_URI` と比較されるので、クエリ文字列も含みます。

<a id="add-redirects"></a>

## リダイレクトの追加

リダイレクトの一覧と管理は `https://example.com/admin/seo/seo_redirects` で、追加は
`https://example.com/admin/seo/seo_redirects/add` で行います。リダイレクトには次のフィールドがあります
(`seo_redirects` テーブル、URI は `seo_uris`)。

**URI**
301 でリダイレクトさせたいリクエスト URI です。ドメインからの相対パス (`/old-page`)、
ワイルドカード付きのパス (`/old-section*`)、または `#` で始まり `#` で終わる正規表現
([高度なリダイレクト](#advanced-redirects) をご覧ください) を指定します。

**Redirect**
一致した URI を 301 でリダイレクトする先の URL で、できればドメインからの相対パスにします。
絶対 URL、パス、または正規表現の URI の置換パターン (`/guides/$1`) を指定できます。
[コールバック](#callback-redirects) を使う場合は、リダイレクト先のどこかに `{callback}` を入れます。
これはコールバックの戻り値に置き換えられます。

**Priority**
ルールの優先度で、デフォルトは `100` です。複数のルールが一致した場合は、優先度の数値が
**小さい** ルールが優先されます。たとえば、

- `/test.asp` から `/first-link` へ、優先度 1
- `/test.asp` から `/other-link` へ、優先度 2

の場合、`/test.asp` へのリクエストは `/other-link` ではなく `/first-link` にリダイレクトされます。

**Is Active**
ルールを有効または無効にします。有効なルールだけが使われます。

**Is Nocache** (編集画面のみ)
ブラウザーがリダイレクトをキャッシュしないように、301 と一緒に `Cache-Control` と `Expires` ヘッダーを
送ります。後で変更するかもしれないリダイレクトに使います。

**Callback**
呼び出すメソッドを `Model::method` (または `Plugin.Model::method`) の形で指定します。
リクエストされた URI が渡され、その戻り値でリダイレクト先の `{callback}` が置き換えられます。
メソッドが `false` を返すと、リダイレクトは取り消されます。`Model::` のないメソッド名は
`SeoRedirect` モデルで呼び出されます。

リダイレクト先がリクエストと同じルールは、リダイレクトループを避けるためにスキップされます。
`log` 設定がオンの場合、リダイレクトと検出されたループは `seo_redirects` タイプでログに記録されます。

<a id="types-of-redirects"></a>

## リダイレクトの種類

リダイレクトには、基本と高度の 2 種類があります。

<a id="basic-redirects"></a>

### 基本的なリダイレクト

基本的なリダイレクトは、一対一または多対一のリダイレクトです。書くのも理解するのも簡単です。
URI とリダイレクト先は、ドメインからの相対パスにしてください。

**一対一**

- URI: `/library`
- Redirect: `/guides/common-questions`
- 結果: `https://example.com/library` は `https://example.com/guides/common-questions` にリダイレクトされます

比較では大文字と小文字を区別せず、リクエスト全体が一致する必要があります。

**多対一、ワイルドカードを使う場合**

- URI: `/website*`
- Redirect: `/`
- 結果: `https://example.com/website`、`https://example.com/websites-are-fun`、
  `https://example.com/website/param/really/long/url` は `https://example.com/` にリダイレクトされます

URI の末尾の `*` は、指定した部分に続く任意の文字列に一致します。このルールは `/website` で始まる
すべてのリクエストに一致します。

<a id="advanced-redirects"></a>

### 高度なリダイレクト

高度なリダイレクトは、正規表現でリクエストを照合し、リダイレクト先は
[`preg_replace()`](https://www.php.net/manual/en/function.preg-replace.php) の置換パターンになります。
はるかに柔軟ですが、読み書きは難しくなります。多対多のリダイレクトができるのは、高度なリダイレクトだけです。

正規表現の URI は `#` で始まり `#` で終わる (その後に `i` などの修飾子を続けてもかまいません)
必要があります。そうでなければ基本的な URI として扱われます。

正規表現のルールは強力ですが、リダイレクトループなどの思わぬ事態を引き起こすこともあります。
注意して使ってください。

正規表現の URI は未承認として保存され (URI の管理画面で **Is Approved** にチェックを入れた場合を除く)、
承認されるまでそのルールは使われません。プラグインは `approverEmail` に通知メールを送ります
(送信元は `replyEmail`、メール設定は `emailConfig`。[設定](README.md#configuration) をご覧ください)。
`https://example.com/admin/seo/seo_uris` で URI を編集し、**Is Approved** にチェックを入れて
承認してください。[シェル](#shells) で追加したリダイレクトはすぐに承認されます。

**多対多**

- URI: `#(.*)\?from\=sb\-tracked\:(.*)#i`
- Redirect: `$1`
- 結果: `?from=sb-tracked:...` で終わる URI は、そのクエリ文字列を除いた同じ URL にリダイレクトされます。

式の末尾の `i` によって、大文字と小文字が区別されなくなります。URI の中の括弧の組は、左から順に番号が
付けられ、リダイレクト先で `$1`、`$2` などとして使えます。この例では `$1` は `?` より前の部分、
`$2` は `tracked:` の後の値です。

- URI: `#^/article/([0-9a-zA-Z]*)#`
- Redirect: `/guides/$1`
- 結果: URI の残りの部分を保ったまま、`/article/` を `/guides/` の別名にします。

<a id="callback-redirects"></a>

## コールバックによるリダイレクト

コールバックを使うと、`.htaccess` のリダイレクトではできないことができます。特に、404 を表示する代わりに
データベースから正しい URL を探せます。

たとえば、記事の URL が `https://example.com/article/123-slug` のような形で、id が `123`、
スラッグが `slug` だとします。id 123 の壊れたリンクを正しいスラッグの URL にリダイレクトするには、
記事ごとにルールを書く代わりに、コールバックを使ったルールを 1 つ書けば済みます。

- URI: `#^/article/([0-9]+)(.*)#i`
- Redirect: `/article/$1-{callback}`
- Callback: `Article::callbackFindSlugById`

`Article` モデルにコールバックを書きます。

```php
/**
 * Returns the slug of the requested article, for the SEO redirect.
 *
 * @param string $request the requested URI
 * @return string|false the slug, or false to cancel the redirect
 */
public function callbackFindSlugById($request)
{
    if (!preg_match('#^/article/([0-9]+)#', $request, $matches)) {
        return false;
    }

    $slug = $this->field('slug', ['id' => $matches[1]]);

    return $slug ?: false;
}
```

このルールは、記事への壊れたリンクを正しい `id-slug` の URL にリダイレクトします。コールバックが
`false` を返すと (たとえば存在しない id の場合)、リダイレクトは取り消され、404 ページが表示されます。

<a id="add-status-codes"></a>

## ステータスコードの追加

ステータスコードの一覧と管理は `https://example.com/admin/seo/seo_status_codes` で、追加は
`https://example.com/admin/seo/seo_status_codes/add` で行います。ステータスコードには、URI
([リダイレクトの URI](#types-of-redirects) と同じように照合されます)、ステータスコード、優先度
(小さいものが先)、有効フラグがあります。一致したリクエストには、ステータスコードとその理由フレーズ
(たとえば `410 Gone`) だけが返されます。

注意: 特別なケースとして、ステータスコード 200 は、代替 URL を消すための (410 の代わりとなる)
帯域を最小限に抑えた noindex の robots ページを返します。

<a id="url-guessing"></a>

## URL 推測

`levenshtein` 設定 ([設定](README.md#configuration) をご覧ください) を使うと、ステータスコードにも
リダイレクトにも処理されない 404 は、
[レーベンシュタイン距離](https://www.php.net/manual/en/function.levenshtein.php) でサイトマップの中から
最も近い URL に 301 でリダイレクトされます。

- `active`: URL 推測を有効にします。
- `source`: サイトマップです。`webroot` の中のパス (`/sitemap.xml`) または URL を指定します。
  その URL は、最初に必要になったとき、または [`seo_urls` シェル](#shells) で `seo_urls` テーブルに
  インポートされます。
- `cost_add`、`cost_change`、`cost_delete`: 文字の追加、変更、削除のコストです。コストが高いほど、
  一致を見つけるためにその変更が許される量が少なくなります。
- `threshold`: URL はサイトマップでの優先度の順に確認され、しきい値より近い最初の URL で検索が
  止まります。そうでなければ最も近い URL が使われます。`-1` を指定すると常にすべての URL を確認し、
  最も近いものを見つけます。

`log` 設定がオンの場合、これらのリダイレクトは `seo_levenshtein` タイプでログに記録されます。

<a id="shells"></a>

## シェル

`Seo.seo_redirects` シェルでリダイレクトを管理します。

```bash
# Quickly search for an existing redirect
bin/cake Seo.seo_redirects search <url or redirect>

# Add a new redirect, priority 100 by default
bin/cake Seo.seo_redirects add <url> <redirect> [priority] [callback]

# Delete redirects or URIs by id
bin/cake Seo.seo_redirects delete <id> [<id> ...]
bin/cake Seo.seo_redirects delete_uri <id> [<id> ...]
```

例:

```bash
bin/cake Seo.seo_redirects add '/mybad/path*' '/my-cleaned-up-path' 50
bin/cake Seo.seo_redirects add '/myother-bad/path*' '/my-cleaned-up-path' 60
bin/cake Seo.seo_redirects add '/my*' '/my-failover-path' 10
bin/cake Seo.seo_redirects add '#/some-old-route-(.*)#i' '/new-route-$1' 10
bin/cake Seo.seo_redirects add '#/(admin|moderator)/(.*)#i' '/$2?old-prefix=$1' 10
```

URI は `/` または `#` で始まり、リダイレクト先は `/` または `http` で始まる (または `{callback}` を含む)
必要があります。URI に既存のリダイレクトがある場合は変更されません。管理画面で編集してください。

[URL 推測](#url-guessing) で使う URL を `levenshtein.source` からインポートする
(`levenshtein.active` がオンの場合のみ。インポートはすべての URL を置き換えます) か、1 件追加します。

```bash
bin/cake Seo.seo_urls import
bin/cake Seo.seo_urls add <url> <priority>
```

レガシーレイアウトでは、`bin/cake` の代わりに `app/Console/cake` を使います。

<a id="resources"></a>

## 参考資料

正規表現についてさらに学ぶには:

- [Regular Expressions Tutorial](https://www.regular-expressions.info/tutorial.html)
- [Regular Expressions - User Guide](https://www.zytrax.com/tech/web/regex.htm)
- PHP マニュアルの [PCRE pattern syntax](https://www.php.net/manual/en/reference.pcre.pattern.syntax.php)

正規表現は [regex101](https://regex101.com/) でテストできます (PCRE を選んでください)。
