# Markup Plugin for CakePHP
[![Build Status](https://api.travis-ci.org/dereuromark/cakephp-markup.svg)](https://travis-ci.org/dereuromark/cakephp-markup)
[![Coverage Status](https://coveralls.io/repos/dereuromark/cakephp-markup/badge.svg)](https://coveralls.io/r/dereuromark/cakephp-markup)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/dereuromark/cakephp-markup/license)](https://packagist.org/packages/dereuromark/cakephp-markup)
[![Total Downloads](https://poser.pugx.org/dereuromark/cakephp-markup/d/total.svg)](https://packagist.org/packages/dereuromark/cakephp-markup)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2--R-yellow.svg)](https://github.com/php-fig-rectified/fig-rectified-standards)

A CakePHP 3.x plugin to
- easily use code syntax highlighters.

## Setup
```
composer require dereuromark/cakephp-markup
```
and
```
bin/cake plugin load Markup
```

## Usage

## Helper Usage

```php
// You must load the helper before
$this->loadHelper('Markup.Highlighter', $optionalConfigArray);

// In our ctp file we can now link to the hashed version
echo $this->Markup->highlight($text);
```

## License
MIT
