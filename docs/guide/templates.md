# Markup templates

Helpers convert strings inside your normal PHP templates. `DjotView` instead reads `.djot` template files and convert the entire file to HTML.

## A standalone document

For Djot, add this action to `PagesController`:

```php
public function documentation(): void {
    $this->viewBuilder()
        ->setClassName('Markup.Djot')
        ->disableAutoLayout();
    $this->set('username', 'Alex');
}
```

Connect the action in `config/routes.php`, inside the application scope callback and before its `fallbacks()` call. The standard application names this callback argument `$builder`; use the name from your own routes file:

```php
$builder->connect('/documentation', ['controller' => 'Pages', 'action' => 'documentation']);
```

Create `templates/Pages/documentation.djot`:

```text
# Welcome, {{username}}!

This is *bold* and _italic_ text.
```

For `.crv` templates, use the [cakephp-carve](https://github.com/markup-carve/cakephp-carve) plugin.

Open `/documentation`. The response is an HTML fragment containing the converted document. Automatic layouts are unsupported: this view does not substitute CakePHP content blocks into markup layouts, so keep `disableAutoLayout()`. To retain your existing PHP layout and full document wrapper, use a helper in a PHP template.

## Variables and configuration

Placeholders use `{{name}}` with no spaces. Scalars and objects with `__toString()` are substituted before conversion. Arrays and other objects leave their placeholders unchanged. Substituted text can contain markup and will be parsed; safe mode filters rendered HTML, but does not make the value literal text.

The view reads the `Djot` configuration namespace, including a custom converter class. Explicit view options take precedence:

```php
$this->viewBuilder()->setOptions(['safeMode' => true, 'profile' => 'article']);
```

Use a helper when you need CakePHP PHP helpers, PHP layouts or literal output escaped with `h()`. Markup template files are read as text, so PHP inside them is not executed.
