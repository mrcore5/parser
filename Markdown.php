<?php namespace Mrcore\Parser;

use cebe\markdown\GithubMarkdown;
use cebe\markdown\block\TableTrait;
#use Mrcore\Parser\Markdown\Inline\FileTrait;
use Mrcore\Parser\Markdown\Block\FileTrait;
use Mrcore\Parser\Markdown\Inline\EmojiTrait;

class Markdown extends GithubMarkdown
{
	// Existing trait overrides
	use TableTrait;

	// New mrcore traints
	use EmojiTrait;
	use FileTrait;

	public function __construct()
	{
		$this->html5 = true;
	}

	/**
	 * render a table block
	 */
	protected function renderTable($block)
	{
		return str_replace(
			"<table>",
			"<table class='table table-condensed table-bordered table-striped table-hover dataTable no-footer'>",
			parent::renderTable($block)
		);
	}

}
