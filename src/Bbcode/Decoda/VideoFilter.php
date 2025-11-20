<?php

namespace Markup\Bbcode\Decoda;

use Decoda\Decoda;
use Decoda\Filter\AbstractFilter;
use MediaEmbed\MediaEmbed;

class VideoFilter extends AbstractFilter {

	/**
	 * Regex pattern.
	 *
	 * @var string
	 */
	public const VIDEO_PATTERN = '/^[-_a-z0-9]+$/is';

	/**
	 * @var string
	 */
	public const SIZE_PATTERN = '/^(?:small|medium|large)$/i';

	/**
	 * @var \MediaEmbed\MediaEmbed
	 */
	protected $MediaEmbed;

	/**
	 * Supported tags.
	 *
	 * @var array<string, mixed>
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
	 * @param array<string, mixed> $tag
	 * @param string $content
	 * @return string
	 */
	public function parse(array $tag, $content) {
		$provider = $tag['attributes']['default'] ?? $tag['tag'];
		//$size = mb_strtolower(isset($tag['attributes']['size']) ? $tag['attributes']['size'] : 'medium');

		if (preg_match('/^\[video\s*=\s*([a-z0-9_-]+)\]$/i', $tag['text'], $matches) !== 1) {
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
		$t = null;
		if (strpos($id, ',') !== false) {
			[$id, $t] = explode(',', $id, 2);
		}
		if ($t) {
			// with timestamps we cannot use the embed mode, as providers don't support deep linking via embed URLs
			// This feature would require implementing direct video URLs instead of embeds
		}

		if ($this->MediaEmbed === null) {
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
