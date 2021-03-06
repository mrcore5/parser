<?php

/**
*
* Parse for URLS in the source text.
*
* @category Text
*
* @package Text_Wiki
*
* @author Paul M. Jones <pmjones@php.net>
*
* @license LGPL
*
* @version $Id: Url.php,v 1.3 2005/02/23 17:38:29 pmjones Exp $
*
*/

/**
*
* Parse for URLS in the source text.
*
* Various URL markings are supported: inline (the URL by itself),
* numbered or footnote reference (where the URL is enclosed in square
* brackets), and named reference (where the URL is enclosed in square
* brackets and has a name included inside the brackets).  E.g.:
*
* inline    -- http://example.com
* numbered  -- [http://example.com]
* described -- [http://example.com Example Description]
*
* When rendering a URL token, this will convert URLs pointing to a .gif,
* .jpg, or .png image into an inline <img /> tag (for the 'xhtml'
* format).
*
* Token options are:
*
* 'type' => ['inline'|'footnote'|'descr'] the type of URL
*
* 'href' => the URL link href portion
*
* 'text' => the displayed text of the URL link
*
* @category Text
*
* @package Text_Wiki
*
* @author Paul M. Jones <pmjones@php.net>
*
*/

class Text_Wiki_Parse_Url extends Text_Wiki_Parse
{


    /**
    *
    * Keeps a running count of numbered-reference URLs.
    *
    * @access public
    *
    * @var int
    *
    */

    public $footnoteCount = 0;


    /**
    *
    * URL schemes recognized by this rule.
    *
    * @access public
    *
    * @var array
    *
    */
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


    /**
    *
    * Constructor.
    *
    * We override the constructor so we can comment the regex nicely.
    *
    * @access public
    *
    */

    public function Text_Wiki_Parse_Url(&$obj)
    {
        parent::Text_Wiki_Parse($obj);

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


    /**
    *
    * Find three different kinds of URLs in the source text.
    *
    * @access public
    *
    */

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


    /**
    *
    * Process inline URLs.
    *
    * @param array &$matches
    *
    * @param array $matches An array of matches from the parse() method
    * as generated by preg_replace_callback.  $matches[0] is the full
    * matched string, $matches[1] is the first matched pattern,
    * $matches[2] is the second matched pattern, and so on.
    *
    * @return string The processed text replacement.
    *
    */

    public function process(&$matches)
    {
        // set options
        $options = array(
            'type' => 'inline',
            'href' => $matches[2],
            'text' => $matches[2]
        );

        // tokenize
        return $matches[1] . $this->wiki->addToken($this->rule, $options) . $matches[5];
    }


    /**
    *
    * Process numbered (footnote) URLs.
    *
    * Token options are:
    * @param array &$matches
    *
    * @param array $matches An array of matches from the parse() method
    * as generated by preg_replace_callback.  $matches[0] is the full
    * matched string, $matches[1] is the first matched pattern,
    * $matches[2] is the second matched pattern, and so on.
    *
    * @return string The processed text replacement.
    *
    */

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


    /**
    *
    * Process described-reference (named-reference) URLs.
    *
    * Token options are:
    *     'type' => ['inline'|'footnote'|'descr'] the type of URL
    *     'href' => the URL link href portion
    *     'text' => the displayed text of the URL link
    *
    * @param array &$matches
    *
    * @param array $matches An array of matches from the parse() method
    * as generated by preg_replace_callback.  $matches[0] is the full
    * matched string, $matches[1] is the first matched pattern,
    * $matches[2] is the second matched pattern, and so on.
    *
    * @return string The processed text replacement.
    *
    */

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
