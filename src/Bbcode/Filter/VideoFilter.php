<?php

namespace Markup\Bbcode\Filter;

use Decoda\Decoda;
use Decoda\Filter\AbstractFilter;
use MediaEmbed\MediaEmbed;

class VideoFilter extends AbstractFilter {

	/**
	 * Regex pattern.
	 */
	public const VIDEO_PATTERN = '/^[-_a-z0-9]+$/is';
	public const SIZE_PATTERN = '/^(?:small|medium|large)$/i';

	/**
	 * @var \MediaEmbed\MediaEmbed
	 */
	protected $MediaEmbed;

	/**
	 * Supported tags.
	 *
	 * @var array
	 */
	protected $_tags = [
		'video' => [
			'template' => 'video',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_NONE,
			'contentPattern' => self::VIDEO_PATTERN,
			'attributes' => [
				'default' => self::ALPHA,
				'size' => self::SIZE_PATTERN,
			],
		],
	];

	/**
	 * Custom build the HTML for videos.
	 *
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function parse(array $tag, $content) {
		$provider = isset($tag['attributes']['default']) ? $tag['attributes']['default'] : $tag['tag'];
		//$size = mb_strtolower(isset($tag['attributes']['size']) ? $tag['attributes']['size'] : 'medium');

		preg_match('/^\[video\s*=\s*([a-z0-9_-]+)\]$/i', $tag['text'], $matches);
		if (!$matches) {
			return $tag['text'] . $content . '[/' . $tag['tag'] . ']';
		}

		$provider = $matches[1];
		$result = $this->transform($provider, $content);
		if (!$result) {
			return $tag['text'] . $content . '[/' . $tag['tag'] . ']';
		}

		return $result;
	}

	/**
	 * @param string $provider
	 * @param string $id
	 *
	 * @return string|null
	 */
	protected function transform($provider, $id) {
		// timestamp?
		if (strpos($id, ',') !== false) {
			[$id, $t] = explode(',', $id, 2);
		}
		if (!empty($t)) {
			// with timestamps we cannot use the embed mode...
			//TODO
		}

		if (!isset($this->MediaEmbed)) {
			$this->MediaEmbed = new MediaEmbed();
		}
		$MediaObject = $this->MediaEmbed->parseId($id, $provider);
		if (!$MediaObject) {
			return null;
		}

		$MediaObject->setAttribute('width', '100%');

		return $MediaObject->getEmbedCode();
	}

}
