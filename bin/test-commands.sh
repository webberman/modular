#!/bin/sh

set -e

# Check if file or directory exists. Exit if it doesn't.
examine() {
    if [ ! -f $1 ] && [ ! -d $1 ]; then
        echo "\n-- ERROR -- $1 could not be found!\n"
        exit 1
    fi
}

# Lint a PHP file for syntax errors. Exit on error.
lint() {
    # echo "\n -- MISSING -- Lint file $1"
    RESULT=$(php -l $1)
    if [ ! $? -eq 0 ] ; then
        echo "$RESULT" && exit 1
    fi
}

if [ ! -f ".env" ]; then
    echo 'APP_KEY=' > .env
    php artisan key:generate
fi

examine "app/Providers"
examine "app/Providers/RouteServiceProvider.php"
examine "resources"
examine "resources/lang"
examine "resources/views"
examine "resources/views/welcome.blade.php"
lint "resources/views/welcome.blade.php"
examine "routes"
examine "routes/api.php"
examine "routes/web.php"
lint "routes/api.php"
lint "routes/web.php"
examine "tests"

## --- Micro ---

# Controller
./vendor/bin/modular make:controller trade
examine "app/Http/Controllers/TradeController.php"
lint "app/Http/Controllers/TradeController.php"

# Feature
./vendor/bin/modular make:feature trade
examine "app/Features/TradeFeature.php"
lint "app/Features/TradeFeature.php"
examine "tests/Feature/TradeFeatureTest.php"
lint "tests/Feature/TradeFeatureTest.php"

# Job
./vendor/bin/modular make:job submitTradeRequest shipping
examine "app/Domains/Shipping/Jobs/SubmitTradeRequestJob.php"
lint "app/Domains/Shipping/Jobs/SubmitTradeRequestJob.php"
examine "tests/Unit/Domains/Shipping/Jobs/SubmitTradeRequestJobTest.php"
lint "tests/Unit/Domains/Shipping/Jobs/SubmitTradeRequestJobTest.php"

./vendor/bin/modular make:job sail boat --queue
examine "app/Domains/Boat/Jobs/SailJob.php"
lint "app/Domains/Boat/Jobs/SailJob.php"
examine "tests/Unit/Domains/Boat/Jobs/SailJobTest.php"
lint "tests/Unit/Domains/Boat/Jobs/SailJobTest.php"

# Model
./vendor/bin/modular make:model bridge
examine "app/Data/Bridge.php"
lint "app/Data/Bridge.php"

# Operation
./vendor/bin/modular make:operation spin
examine "app/Operations/SpinOperation.php"
lint "app/Operations/SpinOperation.php"
examine "tests/Unit/Operations/SpinOperationTest.php"
lint "tests/Unit/Operations/SpinOperationTest.php"

./vendor/bin/modular make:operation twist --queue
examine "app/Operations/TwistOperation.php"
lint "app/Operations/TwistOperation.php"
examine "tests/Unit/Operations/TwistOperationTest.php"
lint "tests/Unit/Operations/TwistOperationTest.php"

# Policy
./vendor/bin/modular make:policy fly
examine "app/Policies/FlyPolicy.php"
lint "app/Policies/FlyPolicy.php"

# Ensure nothing is breaking
./vendor/bin/modular list:features
./vendor/bin/modular list:services

# Run PHPUnit tests
./vendor/bin/phpunit

echo "\nMicro tests PASSED!\n"

## --- Monolith ---

# Controller
./vendor/bin/modular make:controller trade harbour
examine "app/Services/Harbour/Http/Controllers/TradeController.php"
lint "app/Services/Harbour/Http/Controllers/TradeController.php"

# Feature
./vendor/bin/modular make:feature trade harbour
examine "app/Services/Harbour/Features/TradeFeature.php"
lint "app/Services/Harbour/Features/TradeFeature.php"
examine "tests/Feature/Services/Harbour/TradeFeatureTest.php"
lint "tests/Feature/Services/Harbour/TradeFeatureTest.php"

## Operation
./vendor/bin/modular make:operation spin harbour
examine "app/Services/Harbour/Operations/SpinOperation.php"
lint "app/Services/Harbour/Operations/SpinOperation.php"
examine "tests/Unit/Services/Harbour/Operations/SpinOperationTest.php"
lint "tests/Unit/Services/Harbour/Operations/SpinOperationTest.php"

./vendor/bin/modular make:operation twist harbour --queue
examine "app/Services/Harbour/Operations/TwistOperation.php"
lint "app/Services/Harbour/Operations/TwistOperation.php"
examine "tests/Unit/Services/Harbour/Operations/TwistOperationTest.php"
lint "tests/Unit/Services/Harbour/Operations/TwistOperationTest.php"

# Ensure nothing is breaking
./vendor/bin/modular list:features
./vendor/bin/modular list:services

./vendor/bin/phpunit

## --- TEARDOWN ---

./vendor/bin/modular delete:feature trade
./vendor/bin/modular delete:job submitTradeRequest shipping
./vendor/bin/modular delete:job sail boat
./vendor/bin/modular delete:model bridge
./vendor/bin/modular delete:operation spin
./vendor/bin/modular delete:operation twist
./vendor/bin/modular delete:policy fly
rm app/Http/Controllers/TradeController.php

./vendor/bin/modular delete:feature trade harbour
./vendor/bin/modular delete:operation spin harbour
./vendor/bin/modular delete:operation twist harbour
rm app/Services/Harbour/Http/Controllers/TradeController.php

echo "\nPASSED!\n"

exit 0
