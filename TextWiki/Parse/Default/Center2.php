<?php

class Text_Wiki_Parse_Default_Center2 extends Text_Wiki_Parse
{
    public $regex = '/\n\= (.*?)\n/';

    public function process(&$matches)
    {
        $start = $this->wiki->addToken(
            $this->rule,
            array('type' => 'start')
        );

        $end = $this->wiki->addToken(
            $this->rule,
            array('type' => 'end')
        );

        #return "\n" . $start . $matches[1] . $end . "\n";
        #mReschke removed last \n because it created a double space below any centered text!
        return "\n" . $start . $matches[1] . $end;
    }
}
