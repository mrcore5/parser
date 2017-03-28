<?php namespace Mrcore\Parser;

use cebe\markdown\GithubMarkdown;
use cebe\markdown\block\TableTrait;
#use Mrcore\Parser\Markdown\Inline\FileTrait;
use Mrcore\Parser\Markdown\Block\FileTrait;
use Mrcore\Parser\Markdown\Inline\EmojiTrait;

class Markdown extends GithubMarkdown
{
    // Existing trait overrides
    use TableTrait;

    // New mrcore traints
    use EmojiTrait;
    use FileTrait;

    public function __construct()
    {
        $this->html5 = true;
    }

    /**
     * render a table block
     */
    protected function renderTable($block)
    {
        // Calling return parent::renderTable($block); didn't return the proper results for some reason
        // So this is basically a copy of cebe/markdown/block/TableTrait.php wiht added cable class for bootstrap
        // I did NOT add dataTable class

        $content = '';
        $this->_tableCellAlign = $block['cols'];
        $content .= "<thead>\n";
        $first = true;
        foreach($block['rows'] as $row) {
            $this->_tableCellTag = $first ? 'th' : 'td';
            $align = empty($this->_tableCellAlign[$this->_tableCellCount]) ? '' : ' align="' . $this->_tableCellAlign[$this->_tableCellCount++] . '"';
            $tds = "<$this->_tableCellTag$align>" . trim($this->renderAbsy($this->parseInline($row))) . "</$this->_tableCellTag>"; // TODO move this to the consume step
            $content .= "<tr>$tds</tr>\n";
            if ($first) {
                $content .= "</thead>\n<tbody>\n";
            }
            $first = false;
            $this->_tableCellCount = 0;
        }
        return "<table class='table table-condensed table-bordered table-striped table-hover'>\n$content</tbody>\n</table>\n";
    }
}
