# graph-tools

![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/smoren/graph-tools)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Smoren/graph-tools-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Smoren/graph-tools-php/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/Smoren/graph-tools-php/badge.svg?branch=master)](https://coveralls.io/github/Smoren/graph-tools-php?branch=master)
![Build and test](https://github.com/Smoren/graph-tools-php/actions/workflows/test_master.yml/badge.svg)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Tools for working with graphs

### How to install to your project
```
composer require smoren/graph-tools
```

### Unit testing
```
composer install
composer test-init
composer test
```

### Usage

#### Working with preloaded graph repository

##### Basic graph

```php
use Smoren\GraphTools\Models\Edge;
use Smoren\GraphTools\Models\Vertex;
use Smoren\GraphTools\Traverse\Traverse;
use Smoren\GraphTools\Traverse\TraverseDirect;
use Smoren\GraphTools\Traverse\TraverseReverse;
use Smoren\GraphTools\Filters\TransparentTraverseFilter;
use Smoren\GraphTools\Store\PreloadedGraphRepository;
use Smoren\GraphTools\Structs\FilterConfig;

$vertexes = [
    new Vertex(1, 1, null), // id, type, extra data
    new Vertex(2, 1, null),
    new Vertex(3, 1, null),
];
$connections = [
    new Edge(1, 1, 1, 2), // id, type, from id, to id
    new Edge(2, 1, 2, 3),
];

// Creating repository
$repo = new PreloadedGraphRepository($vertexes, $connections);

// Creating direct traverse model
$traverse = new TraverseDirect($repo);
$contexts = $traverse->generate(
    $repo->getVertexById(1),
    new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS])
);

// Let's go traverse
$vertexIds = [];
foreach($contexts as $context) {
    $vertexIds[] = $context->getVertex()->getId();
}
print_r($vertexIds); // [1, 2, 3]

// Creating reverse traverse model
$traverse = new TraverseReverse($repo);
$contexts = $traverse->generate(
    $repo->getVertexById(3),
    new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS])
);

// Let's go traverse
$vertexIds = [];
foreach($contexts as $context) {
    $vertexIds[] = $context->getVertex()->getId();
}
print_r($vertexIds); // [3, 2, 1]

$traverse = new Traverse($repo);

// Creating non-directed traverse model
$contexts = $traverse->generate(
    $repo->getVertexById(2),
    new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS])
);

// Let's go traverse
$vertexIds = [];
$loopsCount = 0;
foreach($contexts as $context) {
    if($context->isLoop()) {
        $contexts->send(Traverse::STOP_BRANCH);
        ++$loopsCount;
    } else {
        $vertexIds[] = $context->getVertex()->getId();
    }
}
print_r($vertexIds); // [2, 3, 1]
var_dump($loopsCount); // 2

// Creating non-directed traverse model with loop prevent control
$contexts = $traverse->generate(
    $repo->getVertexById(2),
    new TransparentTraverseFilter([FilterConfig::PREVENT_LOOP_PASS, FilterConfig::PREVENT_LOOP_HANDLE])
);

// Let's go traverse
$vertexIds = [];
foreach($contexts as $context) {
    $vertexIds[] = $context->getVertex()->getId();
}
print_r($vertexIds); // [2, 3, 1]
```

Look for more examples in [tests](https://github.com/Smoren/graph-tools-php/tree/master/tests/unit).
