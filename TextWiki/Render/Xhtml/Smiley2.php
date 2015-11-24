<?php

class Text_Wiki_Render_Xhtml_Smiley2 extends Text_Wiki_Render {

	var $conf = array(
		'prefix' => 'images/smileys/icon_',
		'extension' => '.png',
		'css' => null
	);

	function token($options)
	{
		// Use laravel asset() function to get full path
		$imageFile = asset($this->getConf('prefix') . $options['name'] . $this->getConf('extension'));
		return '<img src="'.$this->textEncode($imageFile).'" alt="'.$options['desc'].'"'.$this->formatConf(' class="%s"', 'css') . ' />';
	}
}
