#!/bin/bash

echo Starting Queue app server...!

exitfn () {
    trap SIGINT
    echo; echo 'Cleaning reamining processes...'
    killall php
    echo; echo 'Done!'
    exit
}

trap "exitfn" INT            # Set up SIGINT trap to call function


php artisan websocket:serve &
php artisan serve

