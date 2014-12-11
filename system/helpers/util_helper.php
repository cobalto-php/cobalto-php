<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Este arquivo contem os metodos com algumas utilidades no framework
 *
 * @package helpers
 * @subpackage util
 */

/**
 * Retorna o pathBread (caminho de pão) do framework.<br>
 * Abaixo um exemplo de como usar nas views:
 * <code>
 * A variável $path_bread é populada pelo controller do programa
 * <?=path_bread($path_bread);?>
 * </code>
 * @param string $path Caminho de pão do programa a ser informado
 * @return string
 */
function path_bread($path) {
    $path_bread = '';
    if ($path != '') {
        $path_bread .= '<div class="breadCrumb module"><ul>';
        $path_bread .= '<li><a href="' . base_url() . 'dashboard"></a></li>';
        $breadCrumbs = explode(' / ', $path);
        foreach ($breadCrumbs as $bread) {
            $path_bread .= '<li>' . trim($bread) . '</li>';
        }
        $path_bread .= '</ul><div style="clear: both;"></div>';
        $path_bread .= '</div>';
        $path_bread .= '<div style="clear: both;"></div>';
    }
    return $path_bread;
}

/**
 * Exibe uma mensagem de erro.<br/>
 * Os métodos name.showMessageWarning(), name.hideMessageWarning()
 * e name.setMessageWarning("mensagem") estão disponíveis para controle
 * via javascript
 * @param string $name Nome, identificador da mensagem
 * @param string $message Texto da mensagem
 * @param boolean $closeContent FALSE para que o conteúdo seja exposto
 * @param boolean $info TRUE para exibir um bloco de informação em vez de um de erro
 * @return string
 */
function warning($name = 'warning', $message = '', $closeContent = true, $info = false) {
    if ($info || preg_match("/^info/", $name)) {
        $state = 'ui-state-highlight';
        $icon = 'ui-icon-info';
    } else {
        $state = 'ui-state-error';
        $icon = 'ui-icon-alert';
    }
    $warning = '<div id="' . $name . '" class="ui-widget" style="margin-bottom: 5px; display: none;">';
    $warning.= '	<div class="' . $state . ' ui-corner-all" style="padding: 1px;">';
    $warning.= '		<span class="ui-icon '.$icon.'" style="display: block; float: left; margin: 5px;"></span>';
    $warning.= '		<p style="margin: 6px 5px 5px 0px;">' . $message . '</p>';
    $warning.= '	</div>';
    $warning.= '</div>';
    $warning.= '<script type="text/javascript">' .
            "var $name = $('#$name');" .
            "$.extend($name, String.prototype);" .
            "$.extend($name, {toString:function(){return '$name';}});";
    if (!$closeContent) {
        $warning.= '$(document).ready(function(){' . $name . '.showMessageWarning();});';
    }
    $warning.= '</script>';
    return $warning;
}

/**
 * Coleta dados do usuário armazenados na sessão.<br/>
 * @return object Objeto contendo id, pessoa_id, login, senha, nome_pessoa, email, avatar_id, nome_gerado, nome_original, servidor_id, tema
 * @see getSessionCobalto()
 */
function getUsuarioSession() {
    $return = getSessionCobalto('usuario');
    if (is_null($return)) {
        $return = array(
            'id' => '0',
            'pessoa_id' => '0',
            'login' => false,
            'senha' => '',
            'nome_pessoa' => '',
            'email' => '',
            'avatar_id' => '',
            'nome_gerado' => '',
            'nome_original' => '',
            'servidor_id' => '',
            'tema' => 'redmond'
        );
    }

    return (object) $return;
}

/**
 * Coleta dados armazenados na sessão
 * @param string $name Nome do item a ser selecionado da sessão
 * @return mixed O item, ou FALSE caso não exista (@link Session::userdata), ou NULL caso não haja sessão
 */
function getSessionCobalto($name) {
    $CI = & get_instance();
    if (isset($CI->session)) {
        return json_decode($CI->session->userdata($name));
    } else {
        return NULL;
    }
}

/**
 * Consulta um parâmetro cadastrado nos parâmetros do Cobalto
 * @param type $nome
 * @return type
 */
function getParametro($nome) {
    $CI = & get_instance();
    $CI->db->select('valor');
    $CI->db->where('nome', $nome);
    $query = $CI->db->get('parametros');
    $query = @$query->row();
    return @$query->valor;
}

/**
 * @author Carlos Eduardo Alves
 * Loga a consulta SQL como mensagem de erro nos logs do CodeIgniter.<br>
 * Deve ser chamado antes da ação (get, insert, update, delete) para que funcione.<br>
 * Exemplo de uso:<br>
 * <code>
 *  $this->db->select('campo1, campo2');
 *  $this->db->from('tabela');
 *  logSQL();
 *  $resultado = $this->db->get()->result();
 * </code>
 * <code>
 *  $this->db->set('campo', $valor);
 *  $this->db->from('tabela');
 *  logSQL('insert');
 *  $resultado = $this->db->insert();
 * </code>
 * <code>
 *  $this->db->set('campo', $valor);
 *  logSQL('insert', 'tabela');
 *  $resultado = $this->db->insert('tabela');
 * </code>
 * @since 16/02/2011
 * @param string $type Tipos válidos: select, insert, update, delete
 * @param string $table Tabela de ação (ignorado no "select")
 * @param string $description Descrição do log (útil para identificação)
 */
function logSQL($type = "select", $table = '', $description = '') {
    $CI = & get_instance();
    if ($table == '') {
        if (!isset($CI->db->ar_from[0])) {
            log_message('error', "NO TABLE TO LOG QUERY!!!");
            return;
        } else {
            $table = $CI->db->ar_from[0];
        }
    }
    if ($description == '') {
        $description = $type;
    }
    switch (strtoupper($type)) {
        case 'SELECT':
            $sql = $CI->db->_compile_select();
            break;
        case 'INSERT':
            if (count($CI->db->ar_set) == 0) {
                log_message('error', "NO ITENS SET TO LOG QUERY!!!");
                return;
            }
            $sql = $CI->db->_insert($CI->db->_protect_identifiers($table, TRUE, NULL, FALSE), array_keys($CI->db->ar_set), array_values($CI->db->ar_set));
            break;
        case 'UPDATE':
            if (count($CI->db->ar_set) == 0) {
                log_message('error', "NO ITENS SET TO LOG QUERY!!!");
                return;
            }
            $sql = $CI->db->_update($CI->db->_protect_identifiers($table, TRUE, NULL, FALSE), $CI->db->ar_set, $CI->db->ar_where, $CI->db->ar_orderby, $CI->db->ar_limit);
            break;
        case 'DELETE':
            if (count($CI->db->ar_where) == 0 && count($CI->db->ar_wherein) == 0 && count($CI->db->ar_like) == 0) {
                log_message('error', "NO CLAUSES TO LOG QUERY!!!");
                return;
            }
            $sql = $CI->db->_delete($table, $CI->db->ar_where, $CI->db->ar_like, $CI->db->ar_limit);
            break;
        default:
            break;
    }
    logVar($sql, $type);
}

/**
 * @author Carlos Eduardo Alves
 * Loga a consulta SQL como mensagem de erro nos logs do CodeIgniter.<br>
 * Deve ser chamado após a ação (get, insert, update ou delete) para que funcione.<br>
 * Exemplo de uso:<br>
 * <code>
 *  $this->db->select('coluna1, coluna2');
 *  $this->db->from('tabela');
 *  $resultado = $this->db->get()->row();
 *  logLastSQL();
 * </code>
 * <code>
 *  $this->db->set('coluna', $valor);
 *  $this->db->from('tabela');
 *  $resultado = $this->db->insert();
 *  logLastSQL();
 * </code>
 * @since 15/02/2011
 * @param integer $reverse_index O índice reverso da SQL
 * @param string $description Uma descrição (opcional) para embutir no log
 * @see logSQL()
 */
function logLastSQL($reverse_index = 1, $description = "LAST SQL") {
    $CI = & get_instance();
    $index = count($CI->db->queries);
    if ($reverse_index <= $index) {
        $last = $CI->db->queries[$index - $reverse_index];
    } else {
        $last = "ERROR\nReverse_index ($reverse_index) is greather than queries index ($index)!\n"
                . "In logLastSQL(" . implode(", ", func_get_args()) . ")";
    }
    logVar($last, $description);
}

/**
 * @author Carlos Eduardo Alves
 * Loga um dump da variável passada, como mensagem de erro nos logs do CodeIgniter.<br>
 * Aceita variáveis de todos os tipos, incluindo arrays e objetos.<br>
 * Exemplo de uso:<br>
 * <code>
 *  logVar($minhaVariavel);
 * </code>
 * @since 15/02/2011
 * @param mixed $var A variável a ser logada, será exportada para um formato amigável caso não seja uma string
 * @param string $description Uma descrição (opcional) para embutir no log
 */
function logVar($var, $description = "VAR") {
    if (!is_string($var)) {
        $var = var_export($var, TRUE);
        $var = str_replace(" => \n", " => ", $var);
    }
    log_message('error', str_replace("\n", "\n    ", "$description:\n$var"));
}

/**
 * Retira os acentos da string passada
 * @param string $txt O texto com acentos
 * @return string O texto sem acentos
 */
function retira_acentos($txt) {
    $a = array(
        '/[ÂÀÁÄÃ]/u' => 'A',
        '/[âãàáä]/u' => 'a',
        '/[ÊÈÉË]/u' => 'E',
        '/[êèéë]/u' => 'e',
        '/[ÎÍÌÏ]/u' => 'I',
        '/[îíìï]/u' => 'i',
        '/[ÔÕÒÓÖ]/u' => 'O',
        '/[ôõòóö]/u' => 'o',
        '/[ÛÙÚÜ]/u' => 'U',
        '/[ûúùü]/u' => 'u',
        '/ç/u' => 'c',
        '/Ç/u' => 'C'
    );
    return preg_replace(array_keys($a), array_values($a), $txt);
}

/**
 * Carrega o cabeçalho padrão da página.<br/>
 * Exemplo de uso (no início da página):<br/>
 * <code>
 *  <?=headerView()?>
 * </code>
 * @return view A view de cabeçalho carregada
 */
function headerView() {
    return loadView("headerGlobalView");
}

/**
 * Carrega o rodapé padrão da página.<br/>
 * Exemplo de uso (no final da página):<br/>
 * <code>
 *  <?=footerView()?>
 * </code>
 * @return view A view de rodapé carregada
 */
function footerView() {
    return loadView("footerGlobalView");
}

/**
 * Carrega o arquivo javascript para para manipulação de uma view.
 */
function loadJavaScript($fileName){
	return '<script type="text/javascript" src="'.BASE_URL.$fileName.'?'.random_string().'"></script>';
}

/**
 * Carrega um trecho de uma view.
 * @param string $view O nome da view.
 *  Opcionalmente pode-se utilizar o caminho completo à view desejada
 *  (relativo à pasta "views" do projeto atual)
 * @return view A view solicitada carregada, ou uma página de erro caso esta não seja encontrada
 * @see headerView()
 * @see footerView()
 */
function loadView($view) {
    $base = APPPATH . "/views";
    $folder = "../../static/_views";

    if (file_exists("$base/$folder/$view" . EXT)) {
        $load = "$folder/$view";
    } else if (file_exists("$base/$view" . EXT)) {
        $load = "$view";
    } else {
        logVar("FAIL to load view \"$view\"");
        return show_error("FAIL to load view", 500);
    }

    if (isset($this) && isset($this->load)) {
        return $this->load->view("$load");
    } else {
        $CI = & get_instance();
        return $CI->load->view("$load");
    }
}

/**
 * Busca o novo código de matricula utilizando a api-cobalto para a integração entre gol x cobalto
 * @param integer $ano Ano que será utilizado para gerar nova matricula
 * @param integer $semestre Semestre que será utilizado para gerar nova matricula
 */
function getNovoCodigoMatricula($ano, $semestre){
	$url         = 'http://api-cobalto.ufpel.edu.br/academico/aluno/get_novo_cod_matricula';
	$api_key     = 'f14aa0b35ffdca55a5bb42985c508763c4e863a1';
	$format      = 'json';
	$parameters  = 'ano='.$ano.'&semestre='.$semestre.'&random='.random_string();
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url.'?api_key='.$api_key.'&format='.$format.'&'.$parameters);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($curl);
	curl_close($curl);

	$matricula = json_decode($result);
	if (isset($matricula->error)){
		return $matricula->error;
	}else{
		return $matricula->cod_matricula;
	}
}

/**
 * Monta o menu público do sistema
 */
function getMenuPublico()
{
	$menu = '';
	$idSetorMenuPortal = getParametro('ID_SETOR_MENU_PORTAL');

	$CI = & get_instance();

	$CI->db->select('e.id, e.nome');
	$CI->db->from('empresas as e');
	$CI->db->where('e.id', $idSetorMenuPortal);
	$CI->db->order_by('e.nome', 'asc');
	$empresas = $CI->db->get()->result();
	
	foreach ($empresas as $empresa) 
	{
		//$menu.= '<li><span>'.$empresa->nome.'</span><ul style="background: none !important;">';

		$CI->db->select('p.id, p.nome_perfil');
		$CI->db->from('perfis as p');
		$CI->db->join('empresas_perfis as ep', 'p.id = ep.perfil_id');
		$CI->db->where('ep.empresa_id', $empresa->id);
		$CI->db->order_by('p.nome_perfil', 'asc');
		$perfis = $CI->db->get()->result();
		
		foreach ($perfis as $perfil)
		{
			$menu.= '<li><span>'.$perfil->nome_perfil.'</span><ul style="background: none !important;">';
			
			$programasParent = _getProgramasMenuPublico($perfil->id, 0, $empresa->id);
			foreach ($programasParent as $programaParent)
			{
				$menu.= '<li class="closed"><span>';
				if($programaParent->link == '')
				{
					$menu.= $programaParent->nome_programa;
				}				
				else
				{
					if($programaParent->flg_link_externo == 'S')
					{
						$menu.= '<a href="'.$programaParent->link.'" target="_blank">'.$programaParent->nome_programa.'</a>';
					}
					else
					{
						$menu.= '<a href="'.base_url().$programaParent->link.'">'.$programaParent->nome_programa.'</a>';	
					}					
				}
				
				$menu.= '</span>';
				$programas = _getProgramasMenuPublico($perfil->id, $programaParent->programa_id, $empresa->id);
				if(count($programas) > 0)
				{
					$menu.='<ul style="background: none !important;">';
					foreach ($programas as $programa)
					{
						if($programa->flg_link_externo == 'S')
						{
							$menu.= '<li><a href="'.$programa->link.'" target="blank">'.$programa->nome_programa.'</a></li>';
						}
						else
						{
							$menu.= '<li><a href="'.base_url().$programa->link.'">'.$programa->nome_programa.'</a></li>';	
						}						
					}

					$menu.= '</ul></li>';	
				}
				
			}							
			$menu.= '</ul></li>';
		}
		$menu.= '</ul></li>';
	}
	return $menu;
}

/**
 * Busca os programas referente ao perfil e a empresa com acesso publico
 * @param integer ID do perfil que deseja ver os programas
 * @param integer ID do programa pai que deseja ver os programas
 * @param integer ID da empresa que deseja ver os programas
 */
function _getProgramasMenuPublico($perfilId, $programaPaiId=0, $empresaId)
{
	$CI = & get_instance();
	$CI->db->select('p.id as perfil, p.nome_perfil, pp.programa_id, pp.programa_pai, pp.ordem, pr.nome_programa, pr.link, pr.flg_link_externo');
	$CI->db->from('perfis as p');
	$CI->db->join('empresas_perfis as ep', 'p.id = ep.perfil_id');
	$CI->db->join('perfis_programas as pp', 'pp.perfil_id = p.id');
	$CI->db->join('programas as pr', 'pp.programa_id = pr.id');
	$CI->db->where('p.id', $perfilId);
	$CI->db->where('ep.empresa_id', $empresaId);
	$CI->db->where('pp.programa_pai', $programaPaiId);
	$CI->db->order_by('pr.nome_programa', 'asc');	
	return $CI->db->get()->result();	
}