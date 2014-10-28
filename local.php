<?php

$debug     = FALSE;

date_default_timezone_set('America/Los_Angeles');

$locations = array(
    array(
        'address_name'  => '', // descriptive name of the address
        'latitude'      => '', // eg: 47.6354714
        'longitude'     => '', // eg: -122.3703784
        'language'      => 'en-US',
        'email_address' => '', // eg: itsnicehere@notebleu.com
        'history_file'  => '' // eg: path/to/file (file must be writable by PHP)
    ) // repeat as necessary
);
