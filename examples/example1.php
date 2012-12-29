<?php
/*
 * This file is part of TreeBuilder.
 *
 * (c) 2012 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

include_once '../vendor/autoload.php';

use TreeBuilder\Factory;

$tree = Factory::builder();

$tree
    ->leaf('identity')
        ->key('value', 'ksey1')
        ->value('identity')
    ->end()
;

var_dump($tree('myvalue'));


