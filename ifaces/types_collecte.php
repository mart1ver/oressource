<?php

/*
  Oressource
  Copyright (C) 2014-2017  Martin Vert and Oressource devellopers

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as
  published by the Free Software Foundation, either version 3 of the
  License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once '../core/composants.php';

echo page_config3([
  'h1' => 'Gestion de la typologie des collectes',
  'heading' => "Gérez ici les différents types de collectes",
  'text' => "Permet de différencier les collectes en fonction de leur origine (apport volontaire, collecte à domicile, collecte en pied d'immeuble...).",
  'url' => 'type_collecte',
  'functData' => 'types_collectes'
], function () { return is_allowed_gestion(); });
