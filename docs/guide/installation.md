# Installation

From your application's root directory:

Before installing, allow development dependencies in your application's root `composer.json` with `"minimum-stability": "dev"` and `"prefer-stable": true`.

```sh
composer require dereuromark/cakephp-markup:dev-cake6
bin/cake plugin load Markup
```

Composer checks supported PHP and CakePHP versions against [composer.json](https://github.com/dereuromark/cakephp-markup/blob/cake6/composer.json). The plugin does not require a database table or plugin routes.

## Choose a converter

Install only the libraries for the formats you use. For the [beginner Markdown example](./getting-started.md):

```sh
composer require league/commonmark:^2
```

For BBCode:

```sh
composer require mjohnson/decoda:^6.12
```

For Djot:

```sh
composer require php-collective/djot:^0.1.30
```

For Carve:

```sh
composer require markup-carve/carve-php:^0.1.1
```

Native PHP highlighting and the JavaScript-highlighter wrapper need no additional PHP package. The JavaScript wrapper still needs your chosen browser library and its CSS; see [Code highlighting](./highlighting.md).

BBCode uses the media-embed video filter when its package is installed. To enable that integration:

```sh
composer require dereuromark/media-embed:^1
```

Without it, Decoda's standard video filter is used. Older media-embed versions conflict with this plugin branch.
