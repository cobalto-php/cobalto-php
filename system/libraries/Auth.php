<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth {

    private $ci;

    public function __construct() {
        $this->ci = &get_instance();
    }

    function check_logged($classe, $metodo) {
        $this->CI = & get_instance();

        $this->CI->db->where('classe', $classe);
        $this->CI->db->where('metodo', $metodo);
        $metodos = $this->CI->db->get('sys_metodos')->result();

        if (count($metodos) == 0) {
            $this->CI->db->set('classe', $classe);
            $this->CI->db->set('metodo', $metodo);
            $this->CI->db->set('apelido', $classe . '/' . $metodo);
            $this->CI->db->set('privado', 1);
            $this->CI->db->insert('sys_metodos');

            if (IS_AJAX) {
                $this->CI->ajax->addAjaxData('error', array("message" => lang('semPermissaoAcessarMetodo')));
                $this->CI->ajax->returnAjax();
                exit;
            } else {
                redirect(base_url() . $classe . '/' . $metodo, 'refresh');
            }
        } else {
            if ($metodos[0]->privado == 0) {
                return true;
            } else {
                $id_usuario = getUsuarioSession()->id;
                $id_sys_metodo = $metodos[0]->id;

                if ($id_usuario) {
                    $sql = 'select sys_metodo_id
                                from sys_permissoes
                                where usuario_id = ' . $id_usuario . '
                                and sys_metodo_id = ' . $id_sys_metodo . '
                                union
                            select sys_metodo_id
                                from usuarios as u
                                join usuarios_grupos_acessos as uga
                                    on u.id = uga.usuario_id
                                join grupos_acessos_programas_permissoes as gapp
                                    on uga.grupo_acesso_id = gapp.grupo_acesso_id
                                where u.id = ' . $id_usuario . '
                                and gapp.sys_metodo_id = ' . $id_sys_metodo;
                    $permissoes = $this->CI->db->query($sql)->result();

                    if (count($permissoes) == 0) {
                        if (IS_AJAX) {
                            $this->CI->ajax->addAjaxData('error', array("message" => lang('semPermissaoAcessarMetodo')));
                            $this->CI->ajax->addAjaxData('success', false);
                            $this->CI->ajax->returnAjax();
                            exit;
                        } else {
                            show_error('<h1 style="float: left; margin: 0px 10px 10px 0px;">ACESSO NEGADO</h1><br /><h2 style="margin: 0px 10px 10px 0px;">' . lang('semPermissaoAcessarMetodo') . '</h2>');
                        }
                    } else {
                        return true;
                    }
                } else {
                    redirect(base_url(), 'refresh');
                }
                return true;
            }
        }
    }

    /**
     * Método auxiliar para autenticar entradas em menu.
     * Não faz parte do plugin como um todo.
     */
    function check_menu($classe, $metodo) {
        $this->CI = & get_instance();
        $sql = "SELECT SQL_CACHE
                    count(sys_permissoes.id) as found
                    FROM
                    sys_permissoes
                    INNER JOIN sys_metodos
                    ON sys_metodos.id = sys_permissoes.id_metodo
                    WHERE id_usuario = '" . $this->ci->session->userdata('id_usuario') . "'
                    AND classe = '" . $classe . "'
                    AND metodo = '" . $metodo . "'";
        $query = $this->CI->db->query($sql);
        $result = $query->result();
        return $result[0]->found;
    }
    
    
    /**
     * Verifica se o usuário logado tem permissão para acessar uma determinada turma.
     */
    function check_permission_turma($turmaId){
    	$pessoaId = getUsuarioSession()->pessoa_id;
    	$this->CI = &get_instance();
    	$sql = "SELECT t.id " .
    			"from turmas as t " .
    			"join public.turmas_professores as tp on tp.turma_id = t.id and tp.flg_deletado = 'N' " .
    			"join public.funcionarios as f on f.id = tp.professor_id and f.flg_deletado = 'N' and f.pessoa_id = $pessoaId " .
    			"where t.id = $turmaId ";
		$result = $this->CI->db->query($sql)->row();
	
		if (!isset($result->id))
			show_error('<h1 style="float: left; margin: 0px 10px 10px 0px;">ACESSO NEGADO</h1><br /><h2 style="margin: 0px 10px 10px 0px;">' . lang('semPermissaoAcessarTurma') . '</h2>');

		return TRUE;
    }
    
	function check_permission_editar_turma($turmaId){
		$pessoaId = getUsuarioSession()->pessoa_id;
    	$this->CI = &get_instance();
    	$sql = "SELECT t.id " .
    			"from turmas as t " .
    			"join public.turmas_professores as tp on tp.turma_id = t.id and tp.flg_deletado = 'N' " .
    			"join public.funcionarios as f on f.id = tp.professor_id and f.flg_deletado = 'N' and f.pessoa_id = $pessoaId " .    			
    			"where t.id = $turmaId and t.flg_encerrada = 'N' and t.flg_deletado = 'N'";
		$result = $this->CI->db->query($sql)->row();
	
		if (!isset($result->id))
			show_error('<h1 style="float: left; margin: 0px 10px 10px 0px;">ACESSO NEGADO</h1><br /><h2 style="margin: 0px 10px 10px 0px;">' . lang('semPermissaoAcessarTurma') . '</h2>');

		return TRUE;
    }
    
    /**
     * Verifica se o usuário logado tem permissão para acessar determinada avaliação
     */
    function check_permission_avaliacao($avaliacoesId){
    	$pessoaId = getUsuarioSession()->pessoa_id;
    	$this->CI = &get_instance();
    	
    	foreach($avaliacoesId as $avalId){
    		$sql = "SELECT av.id " .
    				"from avaliacoes as av " .
    				"join public.turmas_professores as tp on tp.turma_id = av.turma_id and tp.flg_deletado = 'N' " .
    				"join public.funcionarios as f on f.id = tp.professor_id and f.flg_deletado = 'N' and f.pessoa_id = $pessoaId " .
    				"where av.id = $avalId ";
			$result = $this->CI->db->query($sql)->row();
	
			if (!isset($result->id))
				show_error('<h1 style="float: left; margin: 0px 10px 10px 0px;">ACESSO NEGADO</h1><br /><h2 style="margin: 0px 10px 10px 0px;">' . lang('semPermissaoAcessarTurma') . '</h2>');
    	}
		return TRUE;
	}

    /**
     * Verifica se o usuário logado tem permissão para editar o cadastro de uma determinada pessoa
     */
    function check_permission_editar_pessoa($pessoaId){

    	$pessoaId_logado = getUsuarioSession()->pessoa_id;
    	$idGrupoAcessoAdministrador = getParametro("ID_GRUPO_ACESSO_ADMINISTRADOR_COBALTO");
    	
    	$this->CI = &get_instance();
    	$sql = "select p.id, p.nome, * from usuarios_grupos_acessos as uga " .
    			"join grupos_acessos as gu on gu.id = uga.grupo_acesso_id and uga.grupo_acesso_id = $idGrupoAcessoAdministrador " .
    			"join usuarios as u on u.id = uga.usuario_id " .
    			"join pessoas as p on p.id = u.pessoa_id and p.id = $pessoaId_logado";
		$result = $this->CI->db->query($sql)->row();
		if (isset($result->id))
			return TRUE;
		
		$this->CI = &get_instance();
    	$sql = "select p.id from pessoas as p " .
    			"where p.id not in (select pessoa_id from alunos) " .
    			"and p.id not in (select pessoa_id from servidores) " .
    			"and p.id = $pessoaId";
    	$result = $this->CI->db->query($sql)->row();
		if (isset($result->id))
			return TRUE;

		return FALSE;
    }

}
