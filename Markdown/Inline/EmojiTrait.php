<?php namespace Mrcore\Parser\Markdown\Inline;

/**
 * Adds emoji inline elements
 */
trait EmojiTrait
{
    /**
     * Parses the emoji feature.
     * @marker :
     */
    protected function parseEmoji($markdown)
    {
        if (preg_match('/^:(.+?):/', $markdown, $matches)) {
            return [
                [
                    'emoji',
                    $this->parseInline(strtolower($matches[1]))
                ],
                strlen($matches[0])
            ];
        }
        if (strlen($markdown) > 1) {
            return [['text', $markdown[0] . $markdown[1]], 2];
        } else {
            return [['text', $markdown[0]], 2];
        }
    }

    /**
     * Renders an emoji icon
     */
    protected function renderEmoji($block)
    {
        $icon = $this->renderAbsy($block[1]);
        if (substr($icon, 0, 1) == 'h') {
            $icon = substr($icon, 1).'_h';
        }
        return '<img src="/assets/images/smileys/icon_'.$icon.'.png" alt="'.$icon.'">';
        #return '<del>' . $this->renderAbsy($block[1]) . '</del>';
    }
}
