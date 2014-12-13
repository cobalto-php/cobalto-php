#!/bin/bash
# migrate.sh

## Inicio
cd system
vendor/bin/phinx rollback -e development
cd ..