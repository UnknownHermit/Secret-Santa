<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/classes/SecretSanta.class.php");
$santa = new SecretSanta;
if(isset($_POST["action"]) && !empty($_POST["action"])){
    $status = $santa->processApiRequest($_POST["action"]);
    $output = json_encode($status);
    print $output;
}
?>