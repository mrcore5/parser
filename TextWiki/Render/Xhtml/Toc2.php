<?php

class Text_Wiki_Render_Xhtml_Toc2 extends Text_Wiki_Render
{
    public $conf = array(
        'css_list' => null,
        'css_item' => null,
        'title' => '<strong>Table of Contents</strong>',
        'div_id' => 'toc',
        'collapse' => false
    );

    public $min = 2;

    public function token($options)
    {
        // type, id, level, count, attr
        extract($options);

        switch ($type) {

        case 'list_start':

            $css = $this->getConf('css_list');
            $html = '';

            // collapse div within a table?
            if ($this->getConf('collapse')) {
                $html .= '<table border="0" cellspacing="0" cellpadding="0">';
                $html .= "<tr><td>\n";
            }

            // add the div, class, and id
            $html .= '<div'; //original
            if ($css) {
                $html .= " class=\"panel panel-default $css\"";
            }

            $div_id = $this->getConf('div_id');
            if ($div_id) {
                $html .= " id=\"$div_id\"";
            }

            // add the title, and done
            $html .= '>';
            $html .= $this->getConf('title');

            $html .= '<div class="panel-body">';
            return $html;
            break;

        case 'list_end':
            if ($this->getConf('collapse')) {
                return "\n</div>\n</td></tr></table></div>\n\n";
            } else {
                return "\n</div>\n</div>\n\n";
            }
            break;

        case 'item_start':
            $html = "\n\t<div";

            $css = $this->getConf('css_item');
            if ($css) {
                $html .= " class=\"$css\"";
            }

            $pad = ((($level - $this->min) + 1) * 17) + 10;
            $html .= " style=\"padding-left: {$pad}px;\">";
            #$html .= "<a href=\"#$id\">"; #Original
            #$html .= "<a href=\"#$id\" onclick=\"toggle_wiki_header('$id');\">"; #mReschke
            $html .= "<a href=\"#$id\" onclick=\"toggle_wiki_headers(false);\">"; #mReschke
            return $html;
            break;

        case 'item_end':
            return "</a></div><hr />";
            break;
        }
    }
}
