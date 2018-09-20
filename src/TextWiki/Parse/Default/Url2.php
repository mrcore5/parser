<?php

class Text_Wiki_Parse_Default_Url2 extends Text_Wiki_Parse
{
    public $footnoteCount = 0;

    #mReschke 2010-10-27 added local: option for absolute and relative local urls
    # like [local:/search|goto search page]
    public $conf = array(
        'schemes' => array(
            'http://',
            'https://',
            'ftp://',
            'gopher://',
            'news://',
            'mailto:',
            'local:'
        )
    );

    public function __construct(&$obj)
    {
        parent::__construct($obj);

        // convert the list of recognized schemes to a regex-safe string,
        // where the pattern delim is a slash
        $tmp = array();
        $list = $this->getConf('schemes', array());
        foreach ($list as $val) {
            $tmp[] = preg_quote($val, '/');
        }
        $schemes = implode('|', $tmp);

        #mReschke
        // build the regex
        #$this->regex =
        #    "($schemes)" . // allowed schemes
        #    "(" . // start pattern
        #    "[^ \\/\"\'{$this->wiki->delim}]*\\/" . // no spaces, backslashes, slashes, double-quotes, single quotes, or delimiters;
        #    ")*" . // end pattern
        #    "[^ \\t\\n\\/\"\'{$this->wiki->delim}]*" .
        #    "[A-Za-z0-9\\/?=&~_]";

        #mReschke Removed the space trim, so things like [http://site.com/some file with space|click] works
        // build the regex
        $this->regex =
            "($schemes)" . // allowed schemes
            "(" . // start pattern
            "[^ \\/\"\'{$this->wiki->delim}]*\\/" . // no spaces, backslashes, slashes, double-quotes, single quotes, or delimiters;
            ")*" . // end pattern
            "[^ \\t\\n\\/\"\'{$this->wiki->delim}]*" .
            "[A-Za-z0-9\\/?=&~_]";
    }

    public function parse()
    {
        // -------------------------------------------------------------
        //
        // Described-reference (named) URLs.
        //

        // the regular expression for this kind of URL
        #mReschke (changed url to name separator to a | instead of a space)
        #So old was [http://site.com/file%20with%20space.html clickme]
        #New is [http://site.com/file with space.html|clickme]
        $tmp_regex = '/\[(' . $this->regex . ') ([^\]]+)\]/';
        #$tmp_regex = '/\[(' . $this->regex . ')\|([^\]]+)\]/';
        #Not work because a free link like just http://site.com/some file with space.html
        #would link all other words until newline

        // use a custom callback processing method to generate
        // the replacement text for matches.
        $this->wiki->source = preg_replace_callback(
            $tmp_regex,
            array(&$this, 'processDescr'),
            $this->wiki->source
        );


        // -------------------------------------------------------------
        //
        // Numbered-reference (footnote-style) URLs.
        //

        // the regular expression for this kind of URL
        #$tmp_regex = '/\[(' . $this->regex . ')\]/U';
        $tmp_regex = '/\[\[(' . $this->regex . ')\]\]/U'; #mReschke, removed the extra [ and ] and add them later as <sup> in the render url.php

        // use a custom callback processing method to generate
        // the replacement text for matches.
        $this->wiki->source = preg_replace_callback(
            $tmp_regex,
            array(&$this, 'processFootnote'),
            $this->wiki->source
        );


        // -------------------------------------------------------------
        //
        // Normal inline URLs.
        //

        // the regular expression for this kind of URL

        $tmp_regex = '/(^|[^A-Za-z])(' . $this->regex . ')(.*?)/';

        // use the standard callback for inline URLs
        $this->wiki->source = preg_replace_callback(
            $tmp_regex,
            array(&$this, 'process'),
            $this->wiki->source
        );
    }

    public function process(&$matches)
    {
        // set options
        $options = array(
            'type' => 'inline',
            'href' => $matches[2],
            'text' => $matches[2]
        );

        $x = '';
        if (isset($matches[5])) {
            $x = $matches[5];
        }

        // tokenize
        return $matches[1] . $this->wiki->addToken($this->rule, $options) . $x;
    }

    public function processFootnote(&$matches)
    {
        // keep a running count for footnotes
        $this->footnoteCount++;

        // set options
        $options = array(
            'type' => 'footnote',
            'href' => $matches[1],
            'text' => $this->footnoteCount
        );

        // tokenize
        return $this->wiki->addToken($this->rule, $options);
    }

    public function processDescr(&$matches)
    {
        // set options
        $options = array(
            'type' => 'descr',
            'href' => $matches[1],
            'text' => $matches[4]
        );

        // tokenize
        return $this->wiki->addToken($this->rule, $options);
    }
}
