<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "cwenvbanner".
 *
 * Auto generated 25-06-2014 09:13
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Environment Banner',
	'description' => 'Adds a small Banner in both FE and BE and changes title tag to easier distinct between different Typo3 installations (hopefully no more "Ups - I was on Live all the time...")',
	'category' => 'misc',
	'shy' => 1,
	'version' => '0.0.3',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Carsten Windler',
	'author_email' => 'carsten@carstenwindler.de',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '7.5.0-7.99.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
	'autoload' =>
		array(
			'psr-4' =>
				array(
					'Carstenwindler\\Cwenvbanner\\' => 'Classes',
				),
		),
	'autoload-dev' =>
		array(
			'psr-4' =>
				array(
					'Carstenwindler\\Cwenvbanner\\Tests\\' => 'Tests',
				),
		),
	'_md5_values_when_last_written' => 'a:16:{s:9:"ChangeLog";s:4:"3a31";s:24:"class.tx_cwenvbanner.php";s:4:"0d1a";s:22:"class.ux_typo3logo.php";s:4:"43e7";s:16:"ext_autoload.php";s:4:"b6d0";s:21:"ext_conf_template.txt";s:4:"42a7";s:12:"ext_icon.gif";s:4:"f84b";s:17:"ext_localconf.php";s:4:"acbf";s:23:"ext_localconf_php53.php";s:4:"ccff";s:14:"ext_tables.php";s:4:"00e1";s:20:"ext_tables_php53.php";s:4:"8ba1";s:13:"locallang.xml";s:4:"0453";s:9:"README.md";s:4:"59e9";s:27:"Classes/Xclass/LogoView.php";s:4:"9e2c";s:19:"doc/wizard_form.dat";s:4:"8988";s:20:"doc/wizard_form.html";s:4:"0ef4";s:33:"tests/tx_cwenvbanner_testcase.php";s:4:"ca3e";}',
);

?>