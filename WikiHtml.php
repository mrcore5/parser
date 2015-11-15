<?php namespace Mrcore\Parser;

Class WikiHtml extends Wiki
{

	/**
	 * Parse this text_wiki and html $data into HTML
	 * @return string
	 */
	public function parse($data)
	{
		#$this->disabledRules = array('Html', 'Newline', 'Paragraph');
		$this->disabledRules = array('Html');
		return parent::parse($data);
	}

}
