# Upgrading

This branch targets CakePHP 5. Check [composer.json](https://github.com/dereuromark/cakephp-markup/blob/master/composer.json) for supported versions before updating an older application. CakePHP 6 support is not declared.

The CommonMark adapter uses the current `convert()` API when available, retaining the legacy path for older supported CommonMark versions. Configured Markdown and BBCode conversion options now apply; per-call options still take precedence.

Unknown Djot or Carve profile names now throw `InvalidArgumentException` instead of silently using unrestricted features. Correct misspelled profile names to `full`, `article`, `comment` or `minimal`.

The JavaScript highlighter now honors a per-call `tabToSpaces` override. Optional converter libraries remain application dependencies; follow [Installation](../guide/installation.md) for each format you use.

For document views, explicitly disable automatic layouts unless you supply a layout with the corresponding `.djot` or `.crv` extension. Keep normal PHP layouts by using helpers in PHP templates instead.
