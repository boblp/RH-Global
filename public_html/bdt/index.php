<?php
require("framework.php");
include("main.top.php");
foreach($contentfile as $cfile) {
	if(!include($cfile)) {
		echo "<br />\nFailed including: ".$cfile;
	}
}
echo $trace;
include("main.bot.php");
?>