# Upgrading

The `cake6` development branch targets CakePHP 6. Check [composer.json](https://github.com/dereuromark/cakephp-markup/blob/cake6/composer.json) for supported versions before updating an older application. The `master` branch remains available for CakePHP 5.

The CommonMark adapter uses the current `convert()` API when available, retaining the legacy path for older supported CommonMark versions. Configured Markdown and BBCode conversion options now apply; per-call options still take precedence. Review any existing `escape => false` settings before upgrading: they now allow raw HTML, which should only be enabled for trusted content.

Unknown Djot or Carve profile names now throw `InvalidArgumentException` instead of silently using unrestricted features. An empty string is also invalid; use `null` for unrestricted markup. Correct misspelled profile names to `full`, `article`, `comment` or `minimal`.

The JavaScript highlighter now honors a per-call `tabToSpaces` override. Optional converter libraries remain application dependencies; follow [Installation](../guide/installation.md) for each format you use.

For document views, explicitly disable automatic layouts. Markup layouts do not receive the CakePHP content block. Keep normal PHP layouts by using helpers in PHP templates instead.

## CakePHP 6 extension contracts

Converter and helper subclasses use `$defaultConfig` and `$config` for inherited configuration. Custom Djot and Carve view subclasses use `$ext` for the template extension and override `evaluate()` instead of `_evaluate()`. The existing conversion, variable substitution and safe-mode behavior is retained.
