<?php

// vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
/**
 * Code rule end renderer for Xhtml
 *
 * PHP versions 4 and 5
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     Paul M. Jones <pmjones@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Code.php,v 1.13 2010/10/27 mReschke Exp $
 * @link       http://pear.php.net/package/Text_Wiki
 */

/**
 * This class renders code blocks in XHTML.
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     Paul M. Jones <pmjones@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Text_Wiki
 */
class Text_Wiki_Render_Xhtml_Code extends Text_Wiki_Render {

    var $conf = array(
        'css'      => null,
        'css_title' => null,
    );

    /**
    *
    * Renders a token into text matching the requested format.
    *
    * @access public
    *
    * @param array $options The "options" portion of the token (second
    * element).
    *
    * @return string The text rendered from the token options.
    *
    */

    function token($options)
    {
        $text = $options['text'];
        $attr = $options['attr'];
        $type = isset($attr['type']) ? strtolower($attr['type']) : 'text';
        $title = isset($attr['title']) ? $attr['title'] : '';

        // Not used anymore
        #$linenum = isset($attr['linenum']) ? $attr['linenum'] : false;
        #$height = isset($attr['height']) ? $attr['height'] : null;

        // Get configs
        $css = $this->formatConf('class="%s"', 'css');
        $css_title = $this->formatConf('class="%s"', 'title');

        #if ($height) $height = "style='height:${height}px !important; overflow:auto;'";
        if ($title) $title = "<code>$title</code>";
        $text = "<div $css>
            $title
            <pre class='language-$type'><code class='language-$type'>$text</code></pre>
        </div>";

       return "\n$text\n\n";
    }
}
