<?php

class Text_Wiki_Parse_Default_Heading2 extends Text_Wiki_Parse
{
    public $regex = '/^(\+{1,6}) (.*)/m';
    public $conf = array(
        'id_prefix' => 'toc'
    );

    public function process(&$matches)
    {
        // keep a running count for header IDs.  we use this later
        // when constructing TOC entries, etc.
        static $number;
        if (!isset($number)) {
            $number = 0;
        }

        $prefix = htmlspecialchars($this->getConf('id_prefix'));
        $level = strlen($matches[1]);
        $text = $matches[2];
        $id = $prefix.$number; //toc0

        $start = $this->wiki->addToken(
            $this->rule,
            array(
                'type' => 'start',
                'level' => $level,
                'text' => $text,
                'number' => $number,
                'id' => $id
            )
        );

        $end = $this->wiki->addToken(
            $this->rule,
            array(
                'type' => 'end',
                'level' => $level,
                'text' => $text,
                'number' => $number,
                'id' => $id
            )
        );
        $number ++;

        return $start . $matches[2] . $end . "\n";
    }
}
