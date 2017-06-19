<?php

function smarty_modifier_modifiertest($value, $params)
{
    return $value . ':' . $params[0];
}
