<?php namespace Mrcore\Parser\Markdown\Block;

/**
 * Adds file manager inline elements
 */
trait FileTrait
{
    /**
     * identify a line as the beginning of a code block.
     */
    protected function identifyFile($line)
    {
        // indentation >= 4 or one tab is code
        return ($l = $line[0]) === '<' && strncmp($line, '<file', 5) === 0;
    }

    /**
     * Consume lines for a code block element
     */
    protected function consumeFile($lines, $current)
    {
        // Consume until </file>
        $line = rtrim($lines[$current]);
        $fence = "</file>";
        $block = [
            'file',
            'path' => null,
            'filter' => null,
        ];

        // Get file options, ex: <file path="option" filter="option"></file>
        #if (preg_match('"title=(.*);"Ui', $params, $matches)) $title = $matches[1];
        $options = [];
        if (preg_match_all('/(path|filter)=("[^"]*")/i', $line, $matches)) {
            for ($i = 0; $i < count($matches[1]); $i++) {
                $block[$matches[1][$i]] = $matches[2][$i];
            }
        }

        $content = [];
        if (!str_contains($line, $fence)) {
            for ($i = $current + 1, $count = count($lines); $i < $count; $i++) {
                if (rtrim($line = $lines[$i]) !== $fence || rtrim($line = $lines[$i]) !== $line) {
                    $content[] = $line;
                } else {
                    break;
                }
            }
        } else {
            $i = $current;
        }

        $block['content'] = implode("\n", $content);
        return [$block, $i];
    }

    /**
     * Renders a code block
     */
    protected function renderFile($block)
    {
        #dd($block);
        #return "Showing file manager for ".$block['path']." and filter ".$block['filter'];
        $path = $block['path'];
        $params = '';
        return '<div class="fm" data-path="'.$path.'" data-params="'.$params.'">
            <i class="icon-spinner icon-spin blue"></i>
            Loading File Manager ...
        </div>';

        #$class = isset($block['language']) ? ' class="language-' . $block['language'] . '"' : '';
        #return "<pre><code$class>" . htmlspecialchars($block['content'] . "\n", ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8') . "</code></pre>\n";
    }
}
