<?php

class Text_Wiki_Render_Xhtml_Heading2 extends Text_Wiki_Render {

	var $conf = array(
		'css_h1' => null,
		'css_h2' => null,
		'css_h3' => null,
		'css_h4' => null,
		'css_h5' => null,
		'css_h6' => null
	);

	function token($options)
	{
		// Extract type, level, text, number, id from $options array
		extract($options);

		// Set header collapsed state
		$mode = 'default';
		$collapsed = false;
		if (!is_null(Input::get('collapseall')) || !is_null(Input::get('collapse'))) {
			$collapsed = true;
		} elseif (!is_null(Input::get('expandall')) || !is_null(Input::get('expand'))) {
			$collapsed = false;
		} elseif (!is_null(Input::get('raw'))) {
			$mode = 'raw';
			$collapsed = false;
		} else {
			if (stristr($text, "<->")) {
				// Force collapse if <-> is in header text
				$collapsed = true;
			}
		}

		// Recognize <pagebreak> and <endheader in header text
		$pagebreak = stristr($text, "<pagebreak>") ? ' style="page-break-before: always;"' : '';
		$endheader = stristr($text, "<endheader>");

		// Collapse/expand HTML
		$collapseHtml = '';
		if ($mode != 'raw') {
			$collapseHtml = '<span class="header_toggle"><a href="javascript:toggle_wiki_header(\''.$id.'\')" id="'.$id.'__link">['.($collapsed ? '+' : '-').']</a></span>';
			$collapseHtml .= '<span class="header_collapse"><a href="javascript:toggle_wiki_headers(true);">[--]</a></span>';
			$collapseHtml .= '<span class="header_expand"><a href="javascript:toggle_wiki_headers(false);">[++]</a></span>';
		}

		// Determine header level opening and closing divs
		if ($level == 1) {
			$div = "<div><div><div><div><div>";
			$divend = "</div></div></div></div></div></div>";
		} elseif ($level == 2) {
			$div = "<div><div><div><div>";
			$divend = "</div></div></div></div></div>";
		} elseif ($level == 3) {
			$div = "<div><div><div>";
			$divend = "</div></div></div></div>";
		} elseif ($level == 4) {
			$div = "<div><div>";
			$divend = "</div></div></div>";
		} elseif ($level == 5) {
			$div = "<div>";
			$divend = "</div></div>";
		} elseif ($level == 6) {
			$div = "";
			$divend = "</div>";
		}

		// Bootstrap color headers
		$color = '';
		if ($level == 2) {
			$color = ' text-success';
		} elseif ($level == 3) {
			$color = ' text-danger';
		} elseif ($level == 4) {
			$color = ' text-info';
		} elseif ($level == 5) {
			$color = ' text-warning';
		}

		switch($type) {
			case 'start':
				if ($endheader) return $divend;
				$css = $this->formatConf(' class="%s'.$color.'"', "css_h$level");
				return "$divend<h$level$css id='$id'$pagebreak>";

			case 'end':
				if ($endheader) return "$div<div>";
				return "$collapseHtml</h$level><div class='header${level}_content table-bordered' id='{$id}__content' style='".($collapsed == true ? 'display: none; ' : 'display: block')."'>$div";
		}
	}
}
