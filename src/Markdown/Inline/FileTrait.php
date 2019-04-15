<?php namespace Mrcore\Parser\Markdown\Inline;

/**
 * Adds file manager inline elements
 */
trait FileTrait
{
    /**
     * Parses the file feature.
     * @marker <
     */
    protected function parseFile($markdown)
    {
        if (preg_match('/^<file>/', $markdown, $matches)) {
            dd($matches);
            return [
                [
                    'emoji',
                    $this->parseInline(strtolower($matches[1]))
                ],
                strlen($matches[0])
            ];
        }
        return [['text', $markdown[0] . $markdown[1]], 2];
    }

    /**
     * Renders a file manager
     */
    protected function renderFile($block)
    {
        dd('x');
        $icon = $this->renderAbsy($block[1]);
        if (substr($icon, 0, 1) == 'h') {
            $icon = substr($icon, 1).'_h';
        }
        return '<img src="/assets/images/smileys/icon_'.$icon.'.png" alt="'.$icon.'">';
        #return '<del>' . $this->renderAbsy($block[1]) . '</del>';
    }
}
