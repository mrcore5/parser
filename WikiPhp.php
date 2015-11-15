<?php namespace Mrcore\Parser;

Class WikiPhp extends WikiHtml
{

	/**
	 * Parse this text_wiki and php $data into HTML
	 * @return string
	 */
	public function parse($data)
	{
		$data = preg_replace('"^<\?php"i', '', $data);
		$data = preg_replace('"^<\?"i', '', $data);
		ob_start();
		eval($data);
		$data = ob_get_contents();
		ob_end_clean();

		// Run the evaled php through our wiki parser
		return parent::parse($data);
	}

}
