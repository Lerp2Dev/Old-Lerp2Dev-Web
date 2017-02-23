SET NAMES 'utf8';

-- Database: `lerp2dev_db` --
-- Table `announcements` --
CREATE TABLE `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pos` int(11) NOT NULL,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `announcements` (`id`, `pos`, `data`) VALUES
(1, 1, 'a:1:{s:2:\"es\";s:704:\"<b><u>Descarga Terraria 1.3.0.8</u></b> <i>(usando Bc.vc)</i>: <a href=\"http://bc.vc/wvItEH\">[Mediafire]</a> <a href=\"http://bc.vc/gbUrqT\">[Mega]</a> <a href=\"http://bc.vc/vukz97\">[Dropbox]</a>; <font size=\"1\"><i>(por descarga directa)</i>: <a href=\"http://www.mediafire.com/download/5qlr6yq9x5p216z/Terraria+1.3.0.8.rar\">[Mediafire]</a> <a href=\"https://mega.nz/#!GtgCAZyB!ZtDs-e1yprjiVRHX2pj7RPHopn9w-2HDcK7K0kBIgUU\">[Mega]</a> <a href=\"https://www.dropbox.com/s/nx4lniq4kxy7nlw/Terraria%201.3.0.8.rar?dl=1\">[Dropbox]</a> </font><font size=\"2\"><a href=\"https://www.virustotal.com/es/file/c843411e713949559dcf8f8f58576251b23b285bd49a37f20764e0b33605c998/analysis/1435877353/\">{Virustotal.com}</a></font>\";}');

-- Table `banned_profiles` --
CREATE TABLE `banned_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banned_user_id` int(11) NOT NULL,
  `banned_ip` text NOT NULL,
  `ban_type` tinyint(1) NOT NULL,
  `restriction_type` tinyint(1) NOT NULL,
  `ban_time` int(11) NOT NULL,
  `ban_duration` int(11) NOT NULL,
  `perma_ban` int(11) NOT NULL,
  `reason_msg` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table `links` --
CREATE TABLE `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bot_link` text NOT NULL,
  `reg_link` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table `projects` --
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

INSERT INTO `projects` (`id`, `data`) VALUES
(1, 'a:1:{s:2:\"es\";a:10:{s:4:\"type\";s:1:\"2\";s:5:\"thumb\";s:35:\"http://lerp2dev.com/images/logo.png\";s:4:\"name\";s:8:\"Lerp2Dev\";s:4:\"desc\";s:221:\"Este es el proyecto principal de la página web, con este proyecto se pretende albergar todos los proyectos que el equipo vaya desarrollando a lo largo del tiempo. Servirá como \"portfolio\" de nuestro grupo de desarrollo.\";s:5:\"avers\";s:2:\"#3\";s:4:\"vers\";a:4:{i:0;a:2:{s:9:\"vers_name\";s:16:\"#00 (25-06-2015)\";s:9:\"vers_note\";s:40:\"Actualización inicial de la pagina web.\";}i:1;a:2:{s:9:\"vers_name\";s:16:\"#01 (30-11-2015)\";s:9:\"vers_note\";s:83:\"Después de un stand-by de un par de meses, se añadió: \n\n- Un sistema de idiomas.\";}i:2;a:2:{s:9:\"vers_name\";s:16:\"#02 (20-03-2015)\";s:9:\"vers_note\";s:425:\"Esta actualización está llena de nuevos contenidos: \n\n- Index mejorada (un slider de anuncios en la pagina inicial, se han eliminado los anuncios). \n- Un sistema de administración (para una mejor gestión de usuarios y proyectos) \n- Un anuncio que lleva hasta aquí (desaparecerá en las próximas versiones). \n- MySQL integrado a la mayoría de las funciones de la web. (Proyectos y usuarios) \n- Un buscador de proyectos.\";}i:3;a:2:{s:9:\"vers_name\";s:17:\"Futuras versiones\";s:9:\"vers_note\";s:354:\"- Se creará un blog para anunciar nuevas actualizaciones. \n- Se creará un sistema de usuarios avanzado, con perfiles. \n- Se abrirán los registros de nuevo con el propósito de que podáis disfrutar de todas las funciones como usuarios. \n- Un slider con publicidad de adsense para los no registrados. \n- Se eliminará el anuncio que lleva esta página.\";}}s:6:\"author\";i:1;s:13:\"creation_date\";i:1435183200;s:12:\"publish_date\";i:1458793716;s:10:\"other_data\";a:3:{s:10:\"odata_text\";s:30:\"Líneas de código limpio: ???\";s:6:\"images\";a:1:{i:0;s:35:\"http://lerp2dev.com/images/logo.png\";}s:4:\"link\";s:20:\"http://sykoland.com/\";}}}'),
(2, 'a:1:{s:2:\"es\";a:10:{s:4:\"type\";s:1:\"1\";s:5:\"thumb\";s:78:\"https://www.dropbox.com/s/1ght13o38r4qnoi/Ballistic%20Physics%20Thumb.png?dl=1\";s:4:\"name\";s:17:\"Ballistic Physics\";s:4:\"desc\";s:67:\"Con este asset podrás dibujar las trayectorias de los proyectiles.\";s:5:\"avers\";s:5:\"0.0.0\";s:4:\"vers\";a:2:{i:0;a:2:{s:9:\"vers_name\";s:14:\"Versión 0.0.0\";s:9:\"vers_note\";s:16:\"Versión inicial\";}i:1;a:2:{s:9:\"vers_name\";s:17:\"Versiones futuras\";s:9:\"vers_note\";s:18:\"- Soporte para 2D.\";}}s:6:\"author\";i:1;s:13:\"creation_date\";i:1434405600;s:12:\"publish_date\";i:1462623248;s:10:\"other_data\";a:4:{s:10:\"odata_text\";s:74:\"Peso: 70KB, Líneas puras de código creadas por el autor: 269, Precio: 5$\";s:6:\"images\";a:2:{i:0;s:47:\"http://lerp2dev.com/images/thumbs/ballistic.png\";i:1;s:78:\"https://www.dropbox.com/s/1ght13o38r4qnoi/Ballistic%20Physics%20Thumb.png?dl=1\";}s:6:\"videos\";a:2:{i:0;s:11:\"wG22uIqod9c\";i:1;s:11:\"_Y9mVc4CitU\";}s:4:\"link\";s:16:\"http://null.com/\";}}}'),
(3, 'a:1:{s:2:\"es\";a:10:{s:4:\"type\";s:1:\"0\";s:5:\"thumb\";s:42:\"http://lerp2dev.com/images/thumbs/maze.png\";s:4:\"name\";s:13:\"Maze Screamer\";s:4:\"desc\";s:72:\"Maze Screamer es un juego de miedo el cual deberás encontrar la salida.\";s:5:\"avers\";s:5:\"0.0.1\";s:4:\"vers\";a:3:{i:0;a:2:{s:9:\"vers_name\";s:14:\"Versión 0.0.0\";s:9:\"vers_note\";s:16:\"Versión inicial\";}i:1;a:2:{s:9:\"vers_name\";s:14:\"Versión 0.0.1\";s:9:\"vers_note\";s:102:\"- Añadidas texturas a las paredes y al suelo \n- Ahora el ángulo de la linterna es de 50 en vez de 30\";}i:2;a:2:{s:9:\"vers_name\";s:17:\"Futuras versiones\";s:9:\"vers_note\";s:298:\"- Los laberintos se podrán sedear \n- Menú de pausa, gestión del laberinto (tamaño, seed, etc) y de guardado \n- Música por WWW \n- Enemigos & Screamer e items (armas, municiones, antidotos, pociones, fuentes de luz y pilas, llaves, etc) \n- Puertas y cofres/llaves \n- Barra de vida - Varios pisos\";}}s:6:\"author\";i:1;s:13:\"creation_date\";i:1435183200;s:12:\"publish_date\";i:1458793844;s:10:\"other_data\";a:4:{s:10:\"odata_text\";s:63:\"Peso: 309KB, Líneas puras de código creadas por el autor: 329\";s:6:\"images\";a:1:{i:0;s:42:\"http://lerp2dev.com/images/thumbs/maze.png\";}s:4:\"link\";s:59:\"https://www.dropbox.com/s/b0t9d0nghk4dvt2/Maze.unity3d?dl=1\";s:8:\"controls\";a:2:{s:4:\"keys\";a:2:{i:0;s:0:\"\";i:1;s:0:\"\";}s:7:\"actions\";a:2:{i:0;s:7:\"Moverse\";i:1;s:14:\"Menú de pausa\";}}}}}'),
(4, 'a:1:{s:2:\"es\";a:10:{s:4:\"type\";s:1:\"0\";s:5:\"thumb\";s:43:\"http://lerp2dev.com/images/thumbs/hords.png\";s:4:\"name\";s:10:\"Hords Game\";s:4:\"desc\";s:66:\"Hords Game es un juego por hordas en el que tienes que sobrevivir.\";s:5:\"avers\";s:3:\"0.1\";s:4:\"vers\";a:3:{i:0;a:2:{s:9:\"vers_name\";s:14:\"Versión 0.0.0\";s:9:\"vers_note\";s:17:\"Versión inicial.\";}i:1;a:2:{s:9:\"vers_name\";s:12:\"Versión 0.1\";s:9:\"vers_note\";s:381:\"- Arreglos algunos bugs visuales y no visuales. \n- Se han añadido 5 armas: un hacha, una pistola, una metralleta, una escopeta y un lanzacohetes (para cambiar de arma usa las teclas 1, 2, 3, 4, 5 y 6 o la rueda del ratón) \n- Se han mejorado las AIs de los enemigos y además de que el sistema de oleadas ahora está mejor organizado haciendo que sea mucho más fácil superarlas.\";}i:2;a:2:{s:9:\"vers_name\";s:17:\"Futuras versiones\";s:9:\"vers_note\";s:316:\"- Barra de vida en los enemigos \n- Previsualización del daño hecho \n- Arreglado algunos bugs visuales de las armas, limpieza de recursos, código y optimización del inspector de variables del editor (algo no ivsible a nivel de usuario) que haya que la creación de armas y su configuración sea mucho más rápida\";}}s:6:\"author\";i:1;s:13:\"creation_date\";i:1435269600;s:12:\"publish_date\";i:1458794044;s:10:\"other_data\";a:4:{s:10:\"odata_text\";s:62:\"Peso: 13MB, Líneas puras de código creadas por el autor: 360\";s:6:\"images\";a:1:{i:0;s:43:\"http://lerp2dev.com/images/thumbs/hords.png\";}s:4:\"link\";s:67:\"https://www.dropbox.com/s/zejxz8618bagglx/Hords%20Game.unity3d?dl=1\";s:8:\"controls\";a:2:{s:4:\"keys\";a:3:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";}s:7:\"actions\";a:3:{i:0;s:7:\"Moverse\";i:1;s:6:\"Saltar\";i:2;s:14:\"Menú de pausa\";}}}}}'),
(5, 'a:1:{s:2:\"es\";a:10:{s:4:\"type\";s:1:\"1\";s:5:\"thumb\";s:47:\"http://lerp2dev.com/images/thumbs/crosshair.png\";s:4:\"name\";s:18:\"Advanced Crosshair\";s:4:\"desc\";s:3:\"???\";s:5:\"avers\";s:5:\"0.0.0\";s:4:\"vers\";a:1:{i:0;a:2:{s:9:\"vers_name\";s:14:\"Versión 0.0.0\";s:9:\"vers_note\";s:17:\"Versión inicial.\";}}s:6:\"author\";i:1;s:13:\"creation_date\";i:1451516400;s:12:\"publish_date\";i:1458794014;s:10:\"other_data\";a:4:{s:10:\"odata_text\";s:3:\"???\";s:6:\"images\";a:1:{i:0;s:47:\"http://lerp2dev.com/images/thumbs/crosshair.png\";}s:6:\"videos\";a:1:{i:0;s:11:\"_R_qwoK49nw\";}s:4:\"link\";s:16:\"http://null.com/\";}}}'),
(6, 'a:1:{s:2:\"es\";a:10:{s:4:\"type\";s:1:\"0\";s:5:\"thumb\";s:56:\"https://i.gyazo.com/7ac4d816702d1c5737217465045a923f.png\";s:4:\"name\";s:19:\"Bitcoin Mining Game\";s:4:\"desc\";s:197:\"BTM es un juego basado en el minado de Bitcoins, obtén el mayor número de Hashes posibles para obtener Bitcoins las cuales te servirán para obtener máquinas que saquen más Hashes por segundo. \";s:5:\"avers\";s:5:\"0.0.0\";s:4:\"vers\";a:2:{i:0;a:2:{s:9:\"vers_name\";s:14:\"Versión 0.0.0\";s:9:\"vers_note\";s:17:\"Versión inicial.\";}i:1;a:2:{s:9:\"vers_name\";s:17:\"Futuras versiones\";s:9:\"vers_note\";s:106:\"- Pantalla completa mejorada\n- Logros/Mejoras/...\n- Más realidad\n- Más interacción con la web\n- Idiomas\";}}s:6:\"author\";s:1:\"1\";s:13:\"creation_date\";i:1436220000;s:12:\"publish_date\";i:1459125159;s:10:\"other_data\";a:4:{s:10:\"odata_text\";s:64:\"Peso: 1,7MB, Líneas puras de código creadas por el autor: 1050\";s:6:\"images\";a:1:{i:0;s:56:\"https://i.gyazo.com/7ac4d816702d1c5737217465045a923f.png\";}s:4:\"link\";s:71:\"https://www.dropbox.com/s/kyjbzjznwzx65o0/Bitcoin%20Mining.unity3d?dl=1\";s:8:\"controls\";a:2:{s:4:\"keys\";a:1:{i:0;s:0:\"\";}s:7:\"actions\";a:1:{i:0;s:16:\"Obtener Bitcoins\";}}}}}'),
(7, 'a:1:{s:2:\"es\";a:10:{s:4:\"type\";s:1:\"0\";s:5:\"thumb\";s:28:\"http://1.ii.gl/8T3sTEqhm.jpg\";s:4:\"name\";s:10:\"PewPewPew!\";s:4:\"desc\";s:107:\"Este es un Shooter multijugador en vista de pájaro, juega con tus amigos en este divertido y simple juego.\";s:5:\"avers\";s:8:\"1.5-beta\";s:4:\"vers\";a:1:{i:0;a:2:{s:9:\"vers_name\";s:8:\"1.5-beta\";s:9:\"vers_note\";s:507:\"-Agregada visión nocturna al rifle de francotirador.\n-Eliminado el indicador de daño a distancia, hasta encontrar una manera de optimizarlo.\n-Agregados efectos especiales básicos free; blur y vignetting.\n-Arreglado el sistema de interpolación de sincronización en las balas y el jugador.\n-Agregado modo TeamDeathMatch.\n-Agregados detalles visuales, como nombre en un compañero de equipo y contorno coloreado en el mismo.\n-Agregado detalles gráficos en la GUI.\n--Entre otras cosas mínimas, de nuevo--\";}}s:6:\"author\";s:1:\"3\";s:13:\"creation_date\";i:1416006000;s:12:\"publish_date\";i:1459543631;s:10:\"other_data\";a:4:{s:10:\"odata_text\";s:3:\"???\";s:6:\"images\";a:1:{i:0;s:28:\"http://1.ii.gl/8T3sTEqhm.jpg\";}s:4:\"link\";s:64:\"https://www.dropbox.com/s/8hu09l0aloyvc4v/PewPewPew.unity3d?dl=1\";s:8:\"controls\";a:2:{s:4:\"keys\";a:8:{i:0;s:6:\"letras\";i:1;s:1:\"T\";i:2;s:1:\"F\";i:3;s:1:\"E\";i:4;s:3:\"izq\";i:5;s:3:\"der\";i:6;s:6:\"escape\";i:7;s:7:\"espacio\";}s:7:\"actions\";a:8:{i:0;s:10:\"Movimiento\";i:1;s:4:\"Chat\";i:2;s:9:\"Linterna \";i:3;s:14:\"Recoger objeto\";i:4;s:8:\"Disparar\";i:5;s:7:\"Apuntar\";i:6;s:14:\"Menú de pausa\";i:7;s:6:\"Saltar\";}}}}}'),
(8, 'a:1:{s:2:\"es\";a:10:{s:4:\"type\";s:1:\"1\";s:5:\"thumb\";s:71:\"https://www.dropbox.com/s/s0ybke4v9lpifl6/Wiki%20Asset%20Thumb.png?dl=1\";s:4:\"name\";s:23:\"Snippet Library Manager\";s:4:\"desc\";s:287:\"Con este asset podrás ver la wiki de Unity en el editor, ver los scripts (snippets) compartidos por el equipo, así como sus proyectos gratuitos y los no gratuitos; también podrás ver una lista de repositorios que el equipo dejará a tu libre disposición (si tienes alguno dínoslo).\";s:5:\"avers\";s:5:\"0.0.0\";s:4:\"vers\";a:2:{i:0;a:2:{s:9:\"vers_name\";s:5:\"0.0.0\";s:9:\"vers_note\";s:16:\"Versión inicial\";}i:1;a:2:{s:9:\"vers_name\";s:17:\"Futuras versiones\";s:9:\"vers_note\";s:256:\"- Sistema de enlaces mejorado\n- Mostrar imágenes y tablas\n- Buscador más eficiente (que muestre el número de resultados encontrados, etc)\n- Acabar los demás apartados en desarrollo\n- Una mejor interfaz para los códigos (poder copiar trozos de código)\";}}s:6:\"author\";s:1:\"1\";s:13:\"creation_date\";i:1458774000;s:12:\"publish_date\";i:1462623291;s:10:\"other_data\";a:4:{s:10:\"odata_text\";s:78:\"Peso: 162KB, Líneas puras de código creadas por el autor: 1306; Precio: Free\";s:6:\"images\";a:1:{i:0;s:71:\"https://www.dropbox.com/s/s0ybke4v9lpifl6/Wiki%20Asset%20Thumb.png?dl=1\";}s:6:\"videos\";a:1:{i:0;s:11:\"xwKHxPXBaZo\";}s:4:\"link\";s:16:\"http://null.com/\";}}}');

-- Table `ranks` --
CREATE TABLE `ranks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` mediumtext NOT NULL,
  `caption` text NOT NULL,
  `special` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `ranks` (`id`, `name`, `caption`, `special`) VALUES
(1, 'User', '', '0'),
(2, 'Moderator', '', '0'),
(3, 'Admin', '', '0');

-- Table `settings` --
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`id`, `name`, `data`) VALUES
(1, 'notepad', 'Hay que empezar a generar contenido gente!, voy a ver que puedo hacer para terminar algo, con que ganarnos un nombre o mas publico, hay que quitar las groserias de la pagina, para que pueda hacerla publica.\nY hay que traducir al ingles.\nNadie ha leido esto, verdad?... (30/04/2016)\n-Fenex'),
(3, 'announcer_settings', 'a:3:{s:16:\"stop_onmouseover\";s:7:\"checked\";s:13:\"random_change\";s:7:\"checked\";s:10:\"open_blank\";s:7:\"checked\";}'),
(5, 'project_settings', 'a:1:{s:11:\"max_entries\";s:2:\"10\";}'),
(6, 'register_settings', 'a:1:{s:14:\"reg_access_opt\";s:1:\"3\";}');

-- Table `users` --
CREATE TABLE `users` (
  `id` bigint(7) NOT NULL AUTO_INCREMENT,
  `username` mediumtext NOT NULL,
  `password` mediumtext NOT NULL,
  `ip` mediumtext NOT NULL,
  `user_agent` mediumtext NOT NULL,
  `reg_time` int(11) NOT NULL,
  `started_conn_time` int(11) NOT NULL,
  `last_activity` int(11) NOT NULL,
  `online_time` int(11) NOT NULL,
  `real_name` mediumtext NOT NULL,
  `email` mediumtext NOT NULL,
  `specialties` mediumtext NOT NULL,
  `code` mediumtext NOT NULL,
  `activation` longtext NOT NULL,
  `prem_days` int(11) NOT NULL,
  `ref_id` int(11) NOT NULL,
  `coins` bigint(20) NOT NULL,
  `exp` bigint(20) NOT NULL,
  `lvl` bigint(20) NOT NULL,
  `avatar` mediumtext NOT NULL,
  `gender` tinyint(4) NOT NULL DEFAULT '0',
  `birthdate` mediumtext NOT NULL,
  `location` mediumtext NOT NULL,
  `rank_id` tinyint(4) NOT NULL,
  `ban_time` int(11) NOT NULL,
  `ban_duration` int(11) NOT NULL,
  `ban_reason` mediumtext NOT NULL,
  `rank` mediumtext NOT NULL,
  `rank_duration` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  FULLTEXT KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `username`, `password`, `ip`, `user_agent`, `reg_time`, `started_conn_time`, `last_activity`, `online_time`, `real_name`, `email`, `specialties`, `code`, `activation`, `prem_days`, `ref_id`, `coins`, `exp`, `lvl`, `avatar`, `gender`, `birthdate`, `location`, `rank_id`, `ban_time`, `ban_duration`, `ban_reason`, `rank`, `rank_duration`) VALUES
(1, 'Ikillnukes', '3d0771e2546a1c422fe1b33fc25da81c', '::1', '', 1449529538, 1462646198, 1462646606, 122, '', 'alvaro.rg.98@gmail.com', '', '8791B6B7-6685-43C5-38DE-60550EF5356F', '', 0, 0, 0, 0, 0, 'http://localhost/lerp2dev/images/avatars/ikillnukes.jpg', '0', '', '', '3', 0, 0, '', '', 0),
(2, 'gajosu', '6185a2a0200c96664933988835a21651', '186.3.192.111', '', 1458508810, 0, 1458514877, 0, '', 'gajosu15@gmail.com', '', '7BD96A07-BAD1-ECC1-1778-FB92C3ACA635', '', 0, 0, 0, 0, 0, '', '0', '', '', '3', 0, 0, '', '', 0),
(3, 'sanfenex', '5699dbbe5e7e90b6918b01913eb6c6c8', '189.202.71.103', '', 1458953562, 1462055410, 1462055456, 246, '', 'fenexdeveloper@gmail.com', '', '56BEECDF-2E1D-93E3-9986-C2DDE84B9D09', '', 0, 0, 0, 0, 0, '', '0', '', '', '3', 0, 0, '', '', 0),
(4, 'Nico_dev', '15d5afd6ee53a3b0b619e16a772479c6', '181.44.119.171', '', 1458953888, 0, 1458957096, 0, '', 'sonreaper@gmail.com', '', 'D11F861D-9B2C-13B2-162D-E8EF485708D7', '', 0, 0, 0, 0, 0, '', '0', '', '', '3', 0, 0, '', '', 0);

-- Table `visitors` --
CREATE TABLE `visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` mediumtext NOT NULL,
  `user_agent` mediumtext NOT NULL,
  `reg_time` int(11) NOT NULL,
  `last_activity` int(11) NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14245 DEFAULT CHARSET=utf8;