<?php

class Text_Wiki_Parse_Default_Table2 extends Text_Wiki_Parse
{
    public $regex = '/\n((\|\|).*)(\n)(?!(\|\|))/Us';

    public function process(&$matches)
    {
        // our eventual return value
        $return = '';

        // the number of columns in the table
        $num_cols = 0;

        // the number of rows in the table
        $num_rows = 0;

        // rows are separated by newlines in the matched text
        $rows = explode("\n", $matches[1]);

        // loop through each row
        $first_row = true;
        $datatables = true;
        foreach ($rows as $row) {
            if ($first_row) {
                if (preg_match("'<simpletable>'", $row)) {
                    $row = preg_replace("'<simpletable>'", "", $row);
                    $datatables = false;
                }
                $first_row = false;
            }

            // increase the row count
            $num_rows ++;

            // start a new row
            $return .= $this->wiki->addToken(
                $this->rule,
                array('type' => 'row_start')
            );

            // cells are separated by double-pipes
            $cell = explode("||", $row);

            // get the number of cells (columns) in this row
            $last = count($cell) - 1;

            // is this more than the current column count?
            // (we decrease by 1 because we never use cell zero)
            if ($last - 1 > $num_cols) {
                // increase the column count
                $num_cols = $last - 1;
            }

            // by default, cells span only one column (their own)
            $span = 1;

            // ignore cell zero, and ignore the "last" cell; cell zero
            // is before the first double-pipe, and the "last" cell is
            // after the last double-pipe. both are always empty.
            for ($i = 1; $i < $last; $i ++) {

                // if there is no content at all, then it's an instance
                // of two sets of || next to each other, indicating a
                // span.
                if ($cell[$i] == '') {

                    // add to the span and loop to the next cell
                    $span += 1;
                    continue;
                } else {

                    // this cell has content.

                    // find any special "attr"ibute cell markers
                    if (substr($cell[$i], 0, 2) == '> ') {
                        // right-align
                        $attr = 'right';
                        $cell[$i] = substr($cell[$i], 2);
                    } elseif (substr($cell[$i], 0, 2) == '= ') {
                        // center-align
                        $attr = 'center';
                        $cell[$i] = substr($cell[$i], 2);
                    } elseif (substr($cell[$i], 0, 2) == '< ') {
                        // left-align
                        $attr = 'left';
                        $cell[$i] = substr($cell[$i], 2);
                    } elseif (substr($cell[$i], 0, 2) == '~ ') {
                        $attr = 'header';
                        $cell[$i] = substr($cell[$i], 2);
                    } else {
                        $attr = null;
                    }

                    // start a new cell...
                    $return .= $this->wiki->addToken(
                        $this->rule,
                        array(
                            'type' => 'cell_start',
                            'attr' => $attr,
                            'span' => $span
                        )
                    );

                    // ...add the content...
                    $return .= trim($cell[$i]);

                    // ...and end the cell.
                    $return .= $this->wiki->addToken(
                        $this->rule,
                        array(
                            'type' => 'cell_end',
                            'attr' => $attr,
                            'span' => $span
                        )
                    );

                    // reset the span.
                    $span = 1;
                }
            }

            // end the row
            $return .= $this->wiki->addToken(
                $this->rule,
                array('type' => 'row_end')
            );
        }

        // wrap the return value in start and end tokens
        $start_string = 'table_start';
        if (!$datatables) {
            $start_string = 'table_start2';
        }
        $return =
            $this->wiki->addToken(
                $this->rule,
                array(
                    'type' => $start_string,
                    'rows' => $num_rows,
                    'cols' => $num_cols
                )
            )
            . $return .
            $this->wiki->addToken(
                $this->rule,
                array(
                    'type' => 'table_end'
                )
            );

        // we're done!
        return "\n$return\n\n";
    }
}
