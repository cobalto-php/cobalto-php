<?php

class PerfilProgramaORM extends ActiveRecord\Model {

    static $table_name = "perfis_programas";

    static $belongs_to = array(
			array("perfil", "foreign_key" => "perfil_id", "class_name" => "PerfilORM"),
			array("programa", "foreign_key" => "programa_id", "class_name" => "ProgramaORM"),
			array("programa_pai", "foreign_key" => "programa_pai", "class_name" => "ProgramaORM")
		);

}

?>