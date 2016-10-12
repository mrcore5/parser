<?php

class Text_Wiki_Parse_Default_Textbox extends Text_Wiki_Parse
{

    /*var $regex = '/^(\<textbox( .+)?\>)\n(.+)\n(\<\/textbox\>)(\s|$)/Umsi';*/
    #var $regex = ';^<textbox(\s[^>]*)? >((?:(?R)|.*?)*)\n</textbox>(\s|$);msi';
    public $regex = ';\n<textbox(\s[^>]*)?>\n?((?:[^<]*(?R)?.*?)*?)</textbox>;msi'; #BEAUTIFUL!!

    public function process(&$matches)
    {
        // are there additional attribute arguments?
        $args = trim($matches[1]);

        if ($args == '') {
            $options = array(
                'text' => $matches[2],
                'attr' => array('type' => '')
            );
        } else {
            // get the attributes...
            $attr = $this->getAttrs($args);

            // ... and make sure we have a 'type'
            if (! isset($attr['type'])) {
                $attr['type'] = '';
            }

            // retain the options
            $options = array(
                'text' => $matches[2],
                'attr' => $attr
            );
        }

        return $this->wiki->addToken($this->rule, $options) . @$matches[3];
    }
}
