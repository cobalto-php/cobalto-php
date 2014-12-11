cobalto-php
===========

Projeto cobalto-php

== Instalação do ambiente de desenvolvimento

=== Servidor Web:
sudo apt-get install apache2 php5 libapache2-mod-php5 #instala aplicativos
sudo a2enmod rewrite expires headers php5 userdir #habilita módulos
sudo nano /etc/apache2/mods-enabled/php5.conf #mudar “p3” para “p” no começo
sudo nano /etc/apache2/mods-enabled/php5.conf #comentar de “<IfModule mod_userdir.c>” a “</IfModule>”
sudo nano /etc/apache2/sites-enabled/000-default #configurar “AllowOverride All” para /var/www
sudo service apache2 restart #reinicia o serviço web após configurações

=== Banco de dados:
#instala aplicativos:
sudo apt-get install postgresql php5-pgsql pgadmin3
#configura senha do usuário postgres:
echo "ALTER USER postgres WITH PASSWORD 'cophp';" | sudo su postgres -c "psql"
