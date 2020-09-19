<?php

namespace Markup\Bbcode;

use Cake\Core\InstanceConfigTrait;
use Decoda\Decoda;
use Decoda\Filter\BlockFilter;
use Decoda\Filter\CodeFilter;
use Decoda\Filter\DefaultFilter;
use Decoda\Filter\EmailFilter;
use Decoda\Filter\ImageFilter;
use Decoda\Filter\ListFilter;
use Decoda\Filter\TableFilter;
use Decoda\Filter\TextFilter;
use Decoda\Filter\UrlFilter;
use Decoda\Filter\VideoFilter;
use Decoda\Hook\CensorHook;
use Decoda\Hook\ClickableHook;
use Markup\Bbcode\Filter\VideoFilter as MediaEmbedVideoFilter;
use MediaEmbed\MediaEmbed;

class DecodaBbcode implements BbcodeInterface {

	use InstanceConfigTrait;

	/**
	 * @var \Decoda\Decoda|null
	 */
	protected $converter;

	/**
	 * @var array
	 */
	protected $_defaultConfig = [
	];

	/**
	 * @param array $config
	 */
	public function __construct(array $config = []) {
		$this->setConfig($config);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	protected function _prepare($string) {
		return $string;
	}

	/**
	 * @param string $text
	 * @param array $options
	 *
	 * @return string
	 */
	public function convert(string $text, array $options = []): string {
		$options += ['escape' => true];
		if ($options['escape']) {
			$text = (string)h($text);
		}

		$converter = $this->converter($text);

		return $converter->parse();
	}

	/**
	 * @param string $text
	 * @return \Decoda\Decoda
	 */
	protected function converter(string $text): Decoda {
		$options = [
			'escapeHtml' => false,
		];

		$this->converter = new Decoda($text, $options);
		// For now lets use basic defaults
		$this->converter->addFilter(new DefaultFilter());
		$this->converter->addFilter(new EmailFilter());
		$this->converter->addFilter(new ImageFilter());
		$this->converter->addFilter(new UrlFilter());
		$this->converter->addFilter(new TextFilter());
		$this->converter->addFilter(new BlockFilter());
		$this->converter->addFilter(new CodeFilter());
		$this->converter->addFilter(new ListFilter());
		$this->converter->addFilter(new TableFilter());
		if (class_exists(MediaEmbed::class)) {
			$this->converter->addFilter(new MediaEmbedVideoFilter());
		} else {
			$this->converter->addFilter(new VideoFilter());
		}

		$this->converter->addHook(new CensorHook());
		$this->converter->addHook(new ClickableHook());

		return $this->converter;
	}

}
