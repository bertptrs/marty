<?php

function smarty_compiler_echo()
{
    return <<<EOD
<?php
echo 'Yes, compiler functions work!' . PHP_EOL;
?>
EOD;
}
