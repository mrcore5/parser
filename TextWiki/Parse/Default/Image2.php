<?php

class Text_Wiki_Parse_Default_Image2 extends Text_Wiki_Parse {

	var $conf = array(
		'schemes' => 'http|https|ftp|gopher|news',
		'host_regexp' => '(?:[^.\s/"\'<\\\#delim#\ca-\cz]+\.)*[a-z](?:[-a-z0-9]*[a-z0-9])?\.?',
		'path_regexp' => '(?:/[^\s"<\\\#delim#\ca-\cz]*)?'
	);

	var $regex = '/(\[\[image\s+)(.+?)(\]\])/i';
	var $url = '';

	function Text_Wiki_Parse_Image(&$obj)
	{
		$default = $this->conf;
		parent::Text_Wiki_Parse($obj);

		// convert the list of recognized schemes to a regex OR,
		$schemes = $this->getConf('schemes', $default['schemes']);
		$this->url = str_replace( '#delim#', $this->wiki->delim,
			'#(?:' . (is_array($schemes) ? implode('|', $schemes) : $schemes) . ')://'
			. $this->getConf('host_regexp', $default['host_regexp'])
			. $this->getConf('path_regexp', $default['path_regexp']) .'#');
	}

	function process(&$matches)
	{
		$pos = strpos($matches[2], ' ');

		if ($pos === false) {
			$options = array(
				'src' => $matches[2],
				'attr' => array());
		} else {
			// everything after the space is attribute arguments
			$options = array(
				'src' => substr($matches[2], 0, $pos),
				'attr' => $this->getAttrs(substr($matches[2], $pos+1))
			);
			// check the scheme case of external link
			if (array_key_exists('link', $options['attr'])) {
				// external url ?
				if (($pos = strpos($options['attr']['link'], '://')) !== false) {
					if (!preg_match($this->url, $options['attr']['link'])) {
						return $matches[0];
					}
				#} elseif (($pos = strpos($options['attr']['link'], 'local:')) !== false) {
				} else {
					#mReschke Addition for link="local:/somepage"
				}
				#} elseif (in_array('Wikilink', $this->wiki->disable)) {
				#		return $matches[0]; // Wikilink disabled
				#}
			}
		}

		return $this->wiki->addToken($this->rule, $options);
	}
}
