<?php

// Uncomment this line if you must temporarily take down your site for maintenance
// require __DIR__ . '/.maintenance.phtml';

function id($a) { return $a; }
id( require_once __DIR__ . '/../app/bootstrap.php' )->application->run();
