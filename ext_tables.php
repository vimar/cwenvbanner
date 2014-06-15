<?php

// Help poor people still have to use php 5.2 (including myself at the moment :-(
// this "feature" will be removed pretty soon, though
if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
	require_once t3lib_extMgm::extPath('cwenvbanner') . "ext_tables_php53.php";
}
