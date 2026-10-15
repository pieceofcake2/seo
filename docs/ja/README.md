# SEO ドキュメント

pieceofcake2/seo は、CakePHP 2 アプリケーションの検索エンジン最適化 (SEO) のためのツールキットで、
管理画面から管理します。

* 404 になる場合にだけ読み込まれる、手軽で強力な [301 リダイレクトツール](redirects.md)
* レーベンシュタイン距離と `sitemap.xml` を使う、細かく設定できる賢い 404 の [URL 推測](redirects.md#url-guessing)
* 任意のリクエスト URI に対して、細かく設定・カスタマイズできる [メタタグ](meta-tags.md)
* URI に応じた [タイトルタグ](titles.md) の上書き
* URI に応じた [canonical リンク](#canonical-links)
* URI に応じた独自の [ステータスコード](redirects.md#add-status-codes)
* スクレイパーが自分自身を禁止させるハニーポットを備えた [スクレイパー禁止](#blacklist) の管理
* URI に応じた Google Analytics の [A/B テスト](#ab-testing) の管理

バージョン履歴は [CHANGELOG.md](../../CHANGELOG.md) をご覧ください。

- [インストール](#installation)
- [設定](#configuration)
- [管理画面](#admin-screens)
- [リダイレクトとステータスコード](redirects.md)
- [メタタグ](meta-tags.md)
- [タイトル](titles.md)
- [canonical リンク](#canonical-links)
- [DNS プリフェッチ](#dns-prefetch)
- [ブラックリスト](#blacklist)
- [A/B テスト](#ab-testing)

<a id="installation"></a>

## インストール

[Composer](https://getcomposer.org/) でプラグインをインストールします。

```bash
composer require pieceofcake2/seo
```

このプラグインのタイプは `cakephp-plugin` なので、`composer/installers` によってプラグインディレクトリに
インストールされます。正しい場所にインストールされるように、アプリケーションの `composer.json` で
`installer-paths` を設定してください。

| レイアウト | `installer-paths` |
| --- | --- |
| モダン (`config/`, `src/`, `templates/`, `plugins/`) | `"plugins/{$name}/": ["type:cakephp-plugin"]` |
| レガシー (`app/Config/`, `app/Plugin/`) | `"app/Plugin/{$name}/": ["type:cakephp-plugin"]` |

`config/bootstrap.php` (レガシーレイアウトでは `app/Config/bootstrap.php`) でプラグインを読み込みます。

```php
CakePlugin::load('Seo');
```

データベースにプラグインのテーブルを作成します。

```bash
bin/cake schema create --plugin Seo

# legacy layout
app/Console/cake schema create --plugin Seo
```

SQL は [`Config/sql/install.sql`](../../Config/sql/install.sql) にもあります。

<a id="configuration"></a>

## 設定

プラグインの [`Config/seo.php.default`](../../Config/seo.php.default) を `config/seo.php`
(レガシーレイアウトでは `app/Config/seo.php`) にコピーして、調整します。

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

このファイルは、`Seo` の設定がまだ構成されていなければ、設定が初めて読み込まれるときに
`Configure::load('seo')` で読み込まれます。

<a id="admin-screens"></a>

## 管理画面

リダイレクト、ステータスコード、メタタグ、タイトル、canonical リンク、ブラックリスト、A/B テストは
`/admin/seo/...` で管理します。`config/core.php` (レガシーレイアウトでは `app/Config/core.php`) で
`admin` ルーティングプレフィックスを有効にし、管理画面をほかの管理エリアと同じように保護してください
(プラグインのコントローラーはアプリケーションの `AppController` を継承します)。

```php
Configure::write('Routing.prefixes', ['admin']);
```

リダイレクト、ステータスコード、メタタグ、タイトル、canonical リンク、A/B テストは URI
(`seo_uris` テーブル) に結び付けられ、URI は `https://example.com/admin/seo/seo_uris` で管理します。
URI は、新しい URI でこれらのいずれかを保存したときに作成されます。正規表現の URI は、使われる前に
そこで承認する必要があります ([高度なリダイレクト](redirects.md#advanced-redirects) をご覧ください)。

<a id="canonical-links"></a>

## canonical リンク

`AppController.php` で `Seo.Seo` ヘルパーを読み込みます。

```php
public $helpers = ['Seo.Seo'];
```

レイアウトを変更して、レイアウトの head に SEO の canonical リンクを含めます。

```php
<head>
    <!-- other head items -->
    <?= $this->Seo->canonical() ?>
</head>
```

<a id="add-canonical-links"></a>

### canonical リンクの追加

`https://example.com/admin/seo/seo_canonicals`

<a id="dns-prefetch"></a>

## DNS プリフェッチ

`$this->Seo->dnsPrefetch()` は、`dnsPrefetch` 設定のホストと引数で渡したホストについて
`<link rel="dns-prefetch">` タグを出力します。

```php
<head>
    <?= $this->Seo->dnsPrefetch(['//maps.googleapis.com']) ?>
</head>
```

<a id="blacklist"></a>

## ブラックリスト

`AppController.php` で `Seo.BlackList` コンポーネントを読み込みます。

```php
public $components = ['Seo.BlackList'];
```

悪意のあるコンテンツ収集ツールをおびき寄せるために、アプリケーションの中やその周辺にハニーポットのリンクを
追加していきます。

```php
<?= $this->Seo->honeyPot() ?>
```

`robots.txt` を更新して、`/seo/` をクロールの対象から外します。正当なクローラーはすべてハニーポットを
無視します。

```txt
User-agent: *
Disallow: /seo/
```

<a id="addmanage-banned-ips"></a>

### 禁止 IP の追加・管理

`https://example.com/admin/seo/seo_blacklists`

<a id="ab-testing"></a>

## A/B テスト

`AppController.php` で `Seo.Seo` ヘルパーと `Seo.ABTest` コンポーネントを読み込みます。

```php
public $helpers = ['Seo.Seo'];
public $components = ['Seo.ABTest'];
```

サイトの Google Analytics のコードに、次のように行を追加します。

```php
<script type="text/javascript">
    <!-- GA Items -->
    var pageTracker = _gat._getTracker('UA-SOMEKEY');
    <?= $this->Seo->getABTestJS() ?>
</script>
```

生成されるコードは、従来の Google Analytics トラッキングコードのカスタム変数
(`_gaq.push(['_setCustomVar', ...])`、`abTesting.legacy` の場合は `pageTracker._setCustomVar(...)`)
を使います。

テスト対象のページかどうかを確かめてそのページを表示するには、`AppController.php` で
次のようにします。

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

ヒント: GA で公開する前にコントローラーでデバッグするには、debug オプションを true にします。
まだ有効になっていないテストも返されます。

```php
$test = $this->ABTest->getTest(['debug' => true]);
$this->set('ABTest', $test);
```

<a id="add-ab-tests"></a>

### A/B テストの追加

`https://example.com/admin/seo/seo_a_b_tests`

ヒント: A/B テストを debug に設定すると、コントローラーでは true が返されますが、GA のコードは
トラッキングされません。

<a id="origin"></a>

## 由来

このパッケージは
[webtechnick/CakePHP-Seo-Plugin](https://github.com/webtechnick/CakePHP-Seo-Plugin)
(Packagist では `webtechnick/cakephp-seo-plugin`、アップストリームの最終バージョンは v6.1.0)
のメンテナンスされたフォークで、元は Nick Baker、Alan Blount、Matt Reishus によって作られました。
[pieceofcake2](https://github.com/pieceofcake2) が、モダンな PHP 上の CakePHP 2 向けにメンテナンスしています。
オリジナルからの変更点は [CHANGELOG.md](https://github.com/pieceofcake2/seo/blob/6.x/CHANGELOG.md)
にあります。[リダイレクトとステータスコード](redirects.md)、[メタタグ](meta-tags.md)、
[タイトル](titles.md) の各ページは、オリジナルのプラグインの
[wiki](https://github.com/webtechnick/CakePHP-Seo-Plugin/wiki) をもとにしています。
