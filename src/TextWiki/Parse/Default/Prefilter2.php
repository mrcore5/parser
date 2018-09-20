<?php

class Text_Wiki_Parse_Default_Prefilter2 extends Text_Wiki_Parse
{
    public function parse()
    {
        // convert DOS line endings
        $this->wiki->source = str_replace("\r\n", "\n",
            $this->wiki->source);

        // convert Macintosh line endings
        $this->wiki->source = str_replace("\r", "\n",
            $this->wiki->source);

        // mreschke commented out...I don't want to allow \ multiline
        // concat lines ending in a backslash
        #$this->wiki->source = str_replace("\\\n", "",
        #    $this->wiki->source);

        // convert tabs to four-spaces
        $this->wiki->source = str_replace("\t", "    ",
            $this->wiki->source);

        // add extra newlines at the top and end; this
        // seems to help many rules.
        $this->wiki->source = "\n" . $this->wiki->source . "\n\n";

        $this->wiki->source = str_replace("\n----\n", "\n\n----\n\n",
                                          $this->wiki->source);
        $this->wiki->source = preg_replace("/\n(\\+{1,6})(.*)\n/m",
                                           "\n\n\\1 \\2\n\n",
                                           $this->wiki->source);

        // finally, compress all instances of 3 or more newlines
        // down to two newlines.
        $find = "/\n{3,}/m";
        $replace = "\n\n";
        $this->wiki->source = preg_replace($find, $replace,
            $this->wiki->source);
    }
}
