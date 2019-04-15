<?php

class Text_Wiki_Render_Xhtml_Center2 extends Text_Wiki_Render
{
    public $conf = array(
        'css' => null
    );

    public function token($options)
    {
        if ($options['type'] == 'start') {
            $css = $this->getConf('css');
            if ($css) {
                return "<div class=\"$css\">";
            } else {
                return '<div style="text-align: center;">';
            }
        }

        if ($options['type'] == 'end') {
            return '</div>';
        }
    }
}
