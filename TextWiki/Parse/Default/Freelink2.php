<?php

class Text_Wiki_Parse_Default_Freelink2 extends Text_Wiki_Parse
{
    public $conf = array(
        'utf-8' => false
    );

    public function __construct(&$obj)
    {
        parent::__construct($obj);
        if ($this->getConf('utf-8')) {
            $any = '\p{L}';
        } else {
            $any = '';
        }
        $this->regex =
            '/' .                                                   // START regex
            "\\(\\(" .                                               // double open-parens
            "(" .                                                   // START freelink page patter
            "[-A-Za-z0-9 _+\\/.,;:!?'\"\\[\\]\\{\\}&".$any."\xc0-\xff]+" . // 1 or more of just about any character
            ")" .                                                   // END  freelink page pattern
            "(" .                                                   // START display-name
            "\|" .                                                   // a pipe to start the display name
            "[-A-Za-z0-9 _+\\/.,;:!?'\"\\[\\]\\{\\}&".$any."\xc0-\xff]+" . // 1 or more of just about any character
            ")?" .                                                   // END display-name pattern 0 or 1
            "(" .                                                   // START pattern for named anchors
            "\#" .                                                   // a hash mark
            "[A-Za-z]" .                                           // 1 alpha
            "[-A-Za-z0-9_:.]*" .                                   // 0 or more alpha, digit, underscore
            ")?" .                                                   // END named anchors pattern 0 or 1
            "()\\)\\)" .                                           // double close-parens
            '/'.($this->getConf('utf-8') ? 'u' : '');              // END regex
    }

    public function process(&$matches)
    {
        // use nice variable names
        $page = $matches[1];
        $text = $matches[2];
        $anchor = $matches[3];

        // is the page given a new text appearance?
        if (trim($text) == '') {
            // no
            $text = $page;
        } else {
            // yes, strip the leading | character
            $text = substr($text, 1);
        }

        // set the options
        $options = array(
            'page'   => $page,
            'text'   => $text,
            'anchor' => $anchor
        );

        // return a token placeholder
        return $this->wiki->addToken($this->rule, $options);
    }
}
