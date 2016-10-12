<?php

class Text_Wiki_Render_Xhtml_Tt2 extends Text_Wiki_Render
{
    public $conf = array(
        'css' => null,
        'use_code' => false,
    );

    public function token($options)
    {
        $useCode = $this->getConf('use_code');
        if ($options['type'] == 'start') {
            $css = $this->formatConf('class="%s"', 'css');
            if ($useCode) {
                return "<code $css>";
            } else {
                return "<tt $css>";
            }
        }

        if ($options['type'] == 'end') {
            if ($useCode) {
                return '</code>';
            } else {
                return '</tt>';
            }
        }
    }
}
