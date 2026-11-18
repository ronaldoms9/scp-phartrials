<?php
include_once "class/projeto.class.php";
$varProjeto = new Projeto(486);
$varProjeto->geraCheckList(486);
echo "<br>FIM";