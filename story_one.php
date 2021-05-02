<?php
set_time_limit(10);
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/classes/SecretSanta.class.php");
$santa = new SecretSanta;
$santa->populateFromCsv();
$santa->allocateSantas();
$table = $santa->getAllocationsTable();
$subtitle = "Story 1";
require_once($_SERVER["DOCUMENT_ROOT"]."/views/view_santas.html");
?>