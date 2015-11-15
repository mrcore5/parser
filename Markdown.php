<?php namespace Mrcore\Parser;

use cebe\markdown\GithubMarkdown;

class Markdown extends Parser
{

	/**
	 * Parse this github flavored markdown $data into HTML
	 * @return string
	 */
	public function parse($data)
	{
		$parser = new GithubMarkdown();
		return $parser->parse($data);
	}

}
