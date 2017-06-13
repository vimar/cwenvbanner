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
    'description' => 'Adds a small Banner in both FE and BE and changes title tag to easWier distinct between ' .
        'different Typo3 installations (hopefully no more "Ups - I was on Live all the time...")',
    'category' => 'misc',
    'shy' => 1,
    'version' => '0.1.1',
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
        'conflicts' => array(),
        'suggests' => array(),
    ),
    'suggests' => array(),
    'autoload' => array(
            'psr-4' =>
                array(
                    'CarstenWindler\\Cwenvbanner\\' => 'Classes',
                ),
        ),
    'autoload-dev' => array(
            'psr-4' =>
                array(
                    'CarstenWindler\\Cwenvbanner\\Tests\\' => 'Tests',
                ),
        )
);
