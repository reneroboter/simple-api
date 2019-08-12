#!/usr/bin/env bash

echo "Start ..."

echo "Drop database ..."
/app/bin/console doctrine:database:drop --force

echo "Create database ..."
/app/bin/console doctrine:database:create

echo "Run migrations ..:"
/app/bin/console doctrine:migrations:migrate --no-interaction

echo "Load fixtures ..."
/app/bin/console doctrine:fixtures:load --no-interaction

echo "Finish ..."
