<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */

require 'bootstrap.php';

use App\DomainCounter;
use App\Exceptions\DieException;

$app = new DomainCounter();

try {
    # Run counter with arg1 as filename
    $app->run( strval( $argv[1] ) );
} catch ( DieException $e ) {
    echo $e->getMessage() . PHP_EOL;
    exit(1);
}