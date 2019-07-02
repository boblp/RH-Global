<?php
session_start();
require("constants.php");
require("class.bd.php");
require("class.validate.php");
require("extra.functions.php");
$bd = new BD(_USER_,_PWD_,_BD_,_HOST_);

// Process everything before continue
if(isset($_POST["ppc"])) {
	require("post.processor.php");
}

// All right... Here it comes the page creation:
require("flow.controller.php");
?>