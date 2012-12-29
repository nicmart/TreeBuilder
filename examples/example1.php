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
    ->leaf('element', 'author')
        ->key('value', 'author_name')
        ->value('element', 'name')
    ->end()
    ->leaf('element', 'date')
        ->value(function(\DateTime $dateTime) { return $dateTime->format('d-m-Y'); })
        ->key('value', 'born in')
    ->end()
    ->leaf()
        ->key('value', 'short description')
        ->value(function($book) {
                $date = $book['date'];
                return sprintf('"%s": a book by %s written in %s',
                    $book['title'],
                    $book['author']['name'],
                    $book['date']->format('Y')
                );
            })
    ->end()
;

$book = array(
    'author' => array(
        'name' => 'Nicolò Martini',
        'birth year' => 1983,
    ),
    'date' => new DateTime,
    'title' => 'The new TreeBuilder Library',
    'tags' => array('php', 'programming', 'libraries'),
    'related' => array(
        array('title' => 'Related Book 1'),
        array('title' => 'Related Book 2'),
        array('title' => 'Related Book 3'),
    )
);

$book2 = array(
    'author' => array(
        'name' => 'Alessandro Manzoni',
        'birth year' => 1785,
    ),
    'date' => new DateTime('1827-01-01'),
    'title' => 'I promessi sposi',
    'tags' => array('romanzo', 'manzoni', 'romanzo storico'),
    'related' => array(
        array('title' => 'Related Book 1'),
        array('title' => 'Related Book 2'),
        array('title' => 'Related Book 3'),
    )
);

var_dump($tree($book));
var_dump($tree($book2));


