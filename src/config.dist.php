<?php

//Config
define('AC_API_DOMAIN', 'xxxxxxxxxxxx'); //URL de la API de Active Campaign
define('AC_API_TOKEN', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'); //Token de la API de Active Campaign
define('AC_LOG_API_CALLS', false); //Guardar log de los llamadas a la API

//Usuarios
$userFields = [
  "tratamiento" => 40,
  "dni"       => 42,
  "provincia" => 7,
];

$tags = getAllTags(true);

$lists = getAllLists(true);

//Cuentas
$accountFields = [
  "ciudad" => 4,
  "pais" => 7,
  "telefono" => 11,
];
