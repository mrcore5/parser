<?php namespace Mrcore\Parser;

Class Php extends Parser
{

	/**
	 * Parse this php $data into HTML
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

		return $data;
	}

}
