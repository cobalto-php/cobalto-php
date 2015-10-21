<?php

class ProgramaORM extends ActiveRecord\Model {

    static $table_name = "programas";

    static $has_many = array(
			array("perfis_programas", "foreign_key" => "programa_id", "class_name" => "PerfilProgramaORM")
		);

    public static function pathBread($uri){
    	$url = explode('/', $uri);
    	$programa = self::getProgramaByURL($url);
    	if(!is_null($programa)){
    		return self::getPathBreadByProgramaId($programa->id);
    	}else{
    		return ucwords(str_replace("/", " / ", $uri));
    	}
    }

    public function getPathBreadByProgramaId($programa_id){
    	$joins = "join programas as app on app.id = perfis_programas.programa_id
    			  left join programas as pai on pai.id = perfis_programas.programa_pai
    			  left join perfis on perfis.id = perfis_programas.perfil_id";

		$conditions = array();
        \ActiveRecord\Utils::add_condition($conditions, array("perfis_programas.programa_id = ?", $programa_id));
        \ActiveRecord\Utils::add_condition($conditions, array("perfis.flg_ativo = ?", "S"));
        $perfilPrograma = PerfilProgramaORM::find(
									        		[
									        			"conditions" => $conditions,
									        			"joins" => $joins,
									        			"select" => "perfis.nome_perfil, pai.nome_programa as nome_programa_pai, app.nome_programa"
									        		]
									        	);
        $nome_perfil = (empty($perfilPrograma->nome_perfil) ? "" : $perfilPrograma->nome_perfil." / ");
        $nome_programa_pai = (empty($perfilPrograma->nome_programa_pai) ? "" : $perfilPrograma->nome_programa_pai." / ");

        return $nome_perfil.$nome_programa_pai.$perfilPrograma->nome_programa;
    }

    private function getProgramaByURL($url, $start=0, $finish=0){
    	$link = "";
    	for ($i = $start; $i < count($url) - $finish; $i++) {
            if ($link == "") {
                $link = $url[$i];
            } else {
                $link.= "/" . $url[$i];
            }
        }

        if(!empty($link)){
			$programa = ProgramaORM::find_by_link($link);
        }

        if(empty($programa)){
        	if($start != count($url)){
        		$finish++;
	        	if(count($url) < $finish){
	        		$start++;
	        		$finish = 0;
	        	}
	        	return self::getProgramaByURL($url, $start, $finish);
        	}else{
        		return NULL;
        	}
        }else{
        	return $programa;
        }
    }

}

?>