<?php

class Text_Wiki_Render_Xhtml_Table2 extends Text_Wiki_Render
{
    public $conf = array(
        'css_table' => null,
        'css_table_simple' => null,
        'css_caption' => null,
        'css_tr' => null,
        'css_th' => null,
        'css_td' => null
    );

    public function token($options)
    {
        static $closedHeader;
        static $foundHeader;
        static $tableNumber;

        // make nice variable names (type, attr, span)
        $span = $rowspan = 1;
        extract($options);

        // free format
        $format = isset($format) ? ' '. $format : '';

        $pad = '    ';

        switch ($type) {

        case 'table_start':
            $css = $this->formatConf(' class="%s"', 'css_table');
            $rnd = rand(1, 999);
            return "\n\n<table$css$format id='datatable_".$rnd."'><thead>\n";
            break;

        case 'table_start2':
            $css = $this->formatConf(' class="%s"', 'css_table_simple');
            return "\n\n<table$css$format><thead>\n";
            break;

        case 'table_end':
            if (!$closedHeader) {
                return "</thead></table>\n\n";
            } else {
                $closedHeader = false;
                $return = "</tbody></table>";
                return $return;
            }
            break;

        case 'caption_start':
            $css = $this->formatConf(' class="%s"', 'css_caption');
            return "<caption$css$format>\n";
            break;

        case 'caption_end':
            return "</caption>\n";
            break;

        case 'row_start':
            $css = $this->formatConf(' class="%s"', 'css_tr');
            return "$pad<tr$css$format>\n";
            break;

        case 'row_end':
            if ($foundHeader) {
                $foundHeader = false;
                $closedHeader = true;
                return "$pad</tr></thead><tbody>\n";
            } else {
                return "$pad</tr>\n";
            }
            break;

        case 'cell_start':

            // base html
            $html = $pad . $pad;

            // is this a TH or TD cell?
            if ($attr == 'header') {
                // start a header cell
                $foundHeader = true;
                $css = $this->formatConf(' class="%s"', 'css_th');
                $html .= "<th$css";
            } else {
                // start a normal cell
                $css = $this->formatConf(' class="%s"', 'css_td');
                $html .= "<td$css";
            }

            // add the column span
            if ($span > 1) {
                $html .= " colspan=\"$span\"";
            }

            // add the row span
            if ($rowspan > 1) {
                $html .= " rowspan=\"$rowspan\"";
            }

            // add alignment
            if ($attr != 'header' && $attr != '') {
                $html .= " style=\"text-align: $attr;\"";
            }

            // done!
            $html .= "$format>";
            return $html;
            break;

        case 'cell_end':
            if ($attr == 'header') {
                return "</th>\n";
            } else {
                return "</td>\n";
            }
            break;

        default:
            return '';

        }
    }
}
