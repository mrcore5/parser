<?php

class Text_Wiki_Parse_Default_Code2 extends Text_Wiki_Parse {

	// Original textwiki
	/*var $regex = ';^<code(\s[^>]*)?>(.*?)\n</code>(\s|$);msi';*/

	#Wow, took this from http://pear.php.net/bugs/bug.php?id=11649&edit=12&patch=code.patch&revision=latest
	#Bug here: http://pear.php.net/bugs/bug.php?id=11649
	#Awesome!! This is what I have been looking for for a long time
	#Its not the php.ini pcre.backtrack_limit or recursion limit that are low
	#Its the regex. Finally I can have HUGE code snippets
	#And I noticed this fix also has the \n so <code>\n must be on its own line
	#Perfect!!
	#mReschke 2010-10-28
	var $regex = ';<code(\s[^>]*)?>\n?((?:[^<]*(?R)?.*?)*?)</code>;msi';

	function process(&$matches)
	{
		// are there additional attribute arguments?
		$args = trim($matches[1]);

		if ($args == '') {
			$options = array(
				'text' => $matches[2],
				'attr' => array('type' => '')
			);
		} else {
			// get the attributes...
			$attr = $this->getAttrs($args);

			// ... and make sure we have a 'type'
			if (! isset($attr['type'])) {
				$attr['type'] = '';
			}

			// retain the options
			$options = array(
				'text' => $matches[2],
				'attr' => $attr
			);
		}

		// With original var $regex, matches[3] was a \n...with mine, its nothing and I don't want a \n
		#return $this->wiki->addToken($this->rule, $options) . $matches[3];
		return $this->wiki->addToken($this->rule, $options);
	}
}
