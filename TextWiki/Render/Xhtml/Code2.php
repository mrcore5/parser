<?php

class Text_Wiki_Render_Xhtml_Code2 extends Text_Wiki_Render {

	var $conf = array(
		'css'	  => null, // class for <pre>
		'css_code' => null, // class for generic <code>
		'css_title' => null // class for optional filename <div>
	);

	function token($options)
	{
		$text = $options['text'];
		$attr = $options['attr'];
		$type = strtolower($attr['type']);
		$type = isset($attr['type']) ? strtolower($attr['type']) : 'text';
		$title = isset($attr['title']) ? $attr['title'] : '';
		$css = $this->formatConf(' class="%s"', 'css');
		#$cssCode = $this->formatConf(' class="language-'.$type.' %s"', 'css_code');
		$cssCode = $this->formatConf(' class="%s language-'.$type.'"', 'css_code');
		$cssTitle = $this->formatConf(' class="%s"', 'css_title');

		$text = str_replace("\t", "	", $text);
		$text = $this->textEncode($text);

		// I use prism.js to syntax highlighting, which is client side Javascript
		// Just add class='langunage-xyz' to enable prism.js
		if ($title) $title = "<code $cssTitle>$title</code>";
		$text = "<div>
			$title
			<pre$css><code$cssCode>$text</code></pre>
		</div>";
		return "\n$text\n\n";
	}
}
