<?php namespace Mrcore\Parser;

use Layout;

class Text extends Parser
{

    /**
     * Parse this text $data into HTML
     * @return string
     */
    public function parse($data)
    {
        if (Layout::modeIs('raw')) {
            return $data;
        } else {
            return "<pre class='plaintext'>".htmlentities($data)."</pre>";
        }
    }
}
