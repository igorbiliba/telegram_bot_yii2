<?php
echo exec('cd .. && git pull origin master');
echo "<br />";
echo exec('cd .. && php yii migrate --interactive=0');
