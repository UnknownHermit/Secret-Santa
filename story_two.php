<?php
set_time_limit(10);
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/classes/SecretSanta.class.php");
$santa = new SecretSanta;
$santa->populateFromCsv();
$santa->allocateSantasNotRelated();
$table = $santa->getAllocationsTable();
$subtitle = "Story 2";
require_once("views/view_santas.html");
?>