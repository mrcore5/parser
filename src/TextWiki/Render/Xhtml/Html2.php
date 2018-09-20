<?php

class Text_Wiki_Render_Xhtml_Html2 extends Text_Wiki_Render
{
    public function token($options)
    {
        return $options['text'];
    }
}
