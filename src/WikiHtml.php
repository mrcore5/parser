<?php namespace Mrcore\Parser;

class WikiHtml extends Mrcore
{

    /**
     * Parse this text_wiki and html $data into HTML
     * @return string
     */
    public function parse($data)
    {
        $this->disabledRules[] = 'Html';
        return parent::parse($data);
    }
}
