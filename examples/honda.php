<?php

require __DIR__ . '/vendor/autoload.php';

try {
    $data = new rallisf1\PhpSaracakisPricelists\Parser(__DIR__ . '\PRHONDA', 0);
} catch (Exception $e) {
    echo 'Error: '. $e->getMessage();
}

echo json_encode($data);