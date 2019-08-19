# Compolomus Pagination

[![License](https://poser.pugx.org/compolomus/Pagination/license)](https://packagist.org/packages/compolomus/Pagination)

[![Build Status](https://scrutinizer-ci.com/g/Compolomus/Pagination/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Compolomus/Pagination/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Compolomus/Pagination/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Compolomus/Pagination/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Compolomus/Pagination/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Compolomus/Pagination/?branch=master)
[![Code Climate](https://codeclimate.com/github/Compolomus/Pagination/badges/gpa.svg)](https://codeclimate.com/github/Compolomus/Pagination)
[![Downloads](https://poser.pugx.org/compolomus/Pagination/downloads)](https://packagist.org/packages/compolomus/Pagination)

## Установка:

composer require compolomus/pagination

## Применение:

```php

use Compolomus\Pagination\Pagination;

require __DIR__ . '/vendor/autoload.php';

$page = $_GET['page'] ?? 1;

$items = range(1, 200);

echo count($items);

#1

$nav = new Pagination((int) $page, 10, count($items), 7);

for ($i = $nav->getOffset(); $i < $nav->getEnd(); $i++) {
    echo '<div>' . $items[$i] . '</div>';
}

#2

foreach (new LimitIterator(new ArrayIterator($items), $nav->getOffset(), $nav->getLimit()) as $item) {
    echo '<div>' . $item . '</div>';
}

#3

$count = 200; // select count(*) from table

$select = range(100, 1000, 100); // select * from table limit $nav->getLimit() offset $nav->getOffset()

/*
while($row = fetch($select)) {
// ...
}
*/

echo '<pre>' . print_r($nav->get(), true) . '</pre>';

/*
    Array
    (
        [1] => first
        [2] => ...
        [3] => 3
        [4] => 4
        [5] => 5
        [6] => 6
        [7] => 7
        [8] => 8
        [9] => 9
        [10] => current
        [11] => 11
        [12] => 12
        [13] => 13
        [14] => 14
        [15] => 15
        [16] => 16
        [17] => 17
        [19] => ...
        [20] => last
    )
*/

```
