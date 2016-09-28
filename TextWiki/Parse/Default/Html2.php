<?php

class Text_Wiki_Parse_Default_Html2 extends Text_Wiki_Parse
{

    #var $regex = '/^\<html\>\n(.+)\n\<\/html\>(\s|$)/Umsi'; #Original, just be on own line
    public $regex = '/<html\>(.+)\<\/html\>(\s|$)/Umsi'; #mReschke 2012-10-29, made inline

    public function process(&$matches)
    {
        $options = array('text' => $matches[1]);
        return $this->wiki->addToken($this->rule, $options) . $matches[2];
    }
}
