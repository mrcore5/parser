<?php

class Text_Wiki_Render_Xhtml_Textbox extends Text_Wiki_Render {

	var $conf = array(
		'css'	  => null, // class for <pre>
		'css_outer' => null, // class for outer div
		'css_header'  => null, // class for header span
		#'css_html' => null, // class for HTML <code>
		#'css_filename' => null // class for optional filename <div>
	);

	function token($options)
	{
		$text = $options['text'];
		$attr = $options['attr'];
		@$height = strtolower($attr['height']);
		@$title = strtolower($attr['title']);

		$css	  = $this->formatConf(' class="%s"', 'css');
		$css_outer = $this->formatConf(' class="%s"', 'css_outer');
		$css_header  = $this->formatConf(' class="%s"', 'css_header');
		#$css_html = $this->formatConf(' class="%s"', 'css_html');
		#$css_filename = $this->formatConf(' class="%s"', 'css_filename');

		$text = $this->textEncode($text);
		if ($title == '') $title = 'Text Snippet';
		if ($height == '') $height = 200;
		$text = "<div$css_outer><span$css_header>$title</span><textarea$css style='height:${height}px;'disabled='disabled'>$text</textarea></div>";
		#html>\n<div class='textbox_outer'><span class='textbox_header'>$2</span><br /><textarea class='textbox' style='height:$1px;'>\n", $wikiData);

		return "\n$text\n\n";
	}
}
