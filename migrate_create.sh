#!/bin/bash
# migrate.sh

## Inicio

if [ $# -eq 1 ]
then
	cd system
	vendor/bin/phinx create $1
	cd ..
else
	if [ $# -gt 1 ]
	then
		echo "============================================="
		echo "Erro: é preciso informar apenas um parametro."
		echo "============================================="
	else
		echo "========================================="
		echo "Erro: é preciso passar o nome da migrate."
		echo "========================================="
	fi
fi
