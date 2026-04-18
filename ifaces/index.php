<?php

require_once('../core/requetes.php');
require_once('../moteur/dbconfig.php');

global $bdd;

header('Location: ../ifaces/ventes.php?numero='.filter_visibles(points_ventes($bdd))[0]['id']);
