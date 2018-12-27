<?php

function smarty_block_rot13($content, bool &$repeat)
{
    if (!$repeat) {
        return str_rot13($content);
    }
    return null;
}
