#!/bin/bash
# migrate.sh


## Inicio
cd system
vendor/bin/phinx migrate -e development
cd ..