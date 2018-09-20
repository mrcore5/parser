<?php namespace Mrcore\Parser;

class WikiPhp extends Mrcore
{

    /**
     * Parse this text_wiki and php $data into HTML
     * @return string
     */
    public function parse($data)
    {
        // Run data through PHP parser
        $parser = new Php();
        $data = $parser->parse($data);

        // Run evaled PHP through mrcore parser
        return parent::parse($data);
    }
}
