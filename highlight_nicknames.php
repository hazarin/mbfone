<?php
function highlight_nicknames(string $text): string
{
    $pattern = '/(^|\s)(@[[:alpha:]][[:alnum:]]+)([\s|\n|\r])/m';
    $replacement = '$1<b>$2</b>$3';

    return preg_replace($pattern, $replacement, $text);
}
