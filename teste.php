<?php

$diaSemana = array();
$diaSemana[0]='Dom';
$diaSemana[1]='Seg';
$diaSemana[2]='Ter';
$diaSemana[3]='Qua';
$diaSemana[4]='Qui';
$diaSemana[5]='Sex';
$diaSemana[6]='Sab';

//var_dump(date('w'));
echo $diaSemana[date('w')];

?>