<?php

/**
 * Classe repsonsável por manipular as tabelas referente a pessoas
 * @package gerenciador
 * @subpackage pessoa
 * @author rsantos
 */
class PessoaModel extends Model {

	/**
	 * Insere a pessoa no sistema
	 * @param array $pessoa $_POST enviado pelo formulário do sistema
	 * @return boolean retorna true caso sucesso e false caso contrário
	 * @deprecated
	 */
    function inserir($pessoa) {
        $retErro = $this->validaPessoa($pessoa);
        if ($retErro) {
            return false;
        }

        $dados = array('nome' => $pessoa['txtNome'],
            'cpf' => $pessoa['txtCpf'],
            'senha' => $pessoa['txtSenha'],
            'dt_cadastro' => "now()");
        $this->db->insert('pessoas', $dados);
		$this->ajax->addAjaxData('pessoa', $this->getPessoa($this->db->insert_id('pessoas', 'id')));
        return true;
    }

	/**
	 * Altera a pessoa no sistema
	 * @param array $pessoa $_POST enviado pelo formulário do sistema
	 * @return boolean retorna true caso sucesso e false caso contrário
	 * @deprecated
	 */
    function alterar($pessoa) {
        $retErro = $this->validaPessoa($pessoa);
        if ($retErro) {
            return false;
        }

        $this->db->trans_start();

        $gruposUsuario = explode(",", $pessoa['txtGrupos']);

        $dados = array('nome' => $pessoa['txtNome'],
            'dt_cadastro' => "now()");
        $this->db->where('id', $pessoa['txtCodigo']);
        $this->db->update('pessoas', $dados);
        $this->ajax->addAjaxData('pessoa', $this->getPessoa($pessoa['txtCodigo']));

        $this->db->where('pessoa_id', $pessoa['txtCodigo']);
        $this->db->delete('pessoas_grupos');

        for ($i = 0; $i < count($gruposUsuario); $i++) {
            $dados = array('pessoa_id' => $pessoa['txtCodigo'],
                'grupo_id' => $gruposUsuario[$i]);
            $this->db->insert('pessoas_grupos', $dados);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->validate->addError('tab', lang('registroNaoGravado'));
            return FALSE;
        }

        return TRUE;
    }

	/**
	 * Exclui a pessoa do sistema
	 * @param integer $id ID referente ao campo id da tabela pessoas
	 * @deprecated
	 */
    function excluir($id) {
        $this->db->where('id', $id);
        $this->db->delete('pessoas');
    }

	/**
	 * Lista as pessoas para exibir na grid. OBS: ESTE MÉTODO NÃO É MAIS UTILIZADO
	 * @param string $nomePessoa
	 * @param string $cpf
	 * @param integer $start
	 * @param integer $limit
	 * @deprecated
	 */
    function getPessoas($nomePessoa = '', $cpf = '', $start, $limit) {
        $this->db->select('count(*) as quant');
        $this->db->like('nome', $nomePessoa);
        $this->db->like('cpf', $cpf);
        $total = $this->db->get('pessoas')->row();
        $dados['total'] = $total->quant;

        $this->db->select('id, nome as nome, cpf, dt_cadastro');
        $this->db->like('nome', $nomePessoa);
        $this->db->like('cpf', $cpf);
        $this->db->orderby('nome', 'asc');
        $dados['results'] = $this->db->get('pessoas', $limit, $start)->result();
        return $dados;
    }

	/**
	 * Busca todas as pessoas do sistema para exibir numa combo. OBS: ISSO É UMA INSANIDADE, NUNCA USE ESTE MÉTODO... NÃO SEI NEM PORQUE ELE EXISTE
	 * @return result contendo os campos id e nome das pessoas
	 * @deprecated
	 */
    function getPessoasCombo() {
        $this->db->select('id, nome as nome', false);
        $this->db->orderby('nome', 'asc');
        return $this->db->get('pessoas')->result_array();
    }

	/**
	 * Busca uma pessoa através do ID da pessoa
	 * @param integer $id ID pessoa do sistema referente ao campo ID da tabela public.pessoas
	 * @return row contendo as informações da tabela public.pessoas
	 */
    function getPessoa($id) {
        $this->db->select('id, nome, sexo, dt_cadastro, dt_nascimento, email, rg, cpf');
        $this->db->where('id', $id);
        return $this->db->get('pessoas')->row();
    }

	/**
	 * Busca pessoa pelo RG
	 * @param string $rg rg da pessoa que deseja buscar no sistema
	 * @return row contendo as informações da tabela public.pessoas 
	 */
    function getPessoaByRg($rg) {
        $this->db->where('rg', $rg);
        return $this->db->get('pessoas')->row();
    }

	/**
	 * Busca pessoa pelo CPF
	 * @param string $cpf cpf com todos os caracteres inclusive (. e -) ex: 004.102.290-40
	 * @return row contendo as informações da tabela public.pessoas
	 */
    function getPessoaByCpf($cpf) {
        $this->db->where('cpf', str_ireplace('-', '', str_ireplace('.', '', $cpf)));
        return $this->db->get('pessoas')->row();
    }

	/**
	 * Busca todos os grupos de acesso vinculados a pessoa
	 * @param integer $idPessoa ID da pessoa referente ao campo pessoa_id da tabela public.pessoas_grupos
	 * @return result contendo todos os grupos vinculados a pessoas utilizando a tabela public.pessoas_grupos
	 */
    function getGruposPessoa($idPessoa) {
        $this->db->where('pessoa_id', $idPessoa);
        return $this->db->get('pessoas_grupos')->result();
    }

	/**
	 * Valida as informações para incluir o usuário
	 * @param array $data $_POST dados enviados pelo formulário da view do sistema
	 * @return boolean retorna true caso exista erro e false caso contrário
	 */
    function validaPessoa($data) {
        $this->validate->setData($data);
        $this->validate->validateField('txtNome', array('required'), lang('pessoaNomeRequerido'));
        return $this->validate->existsErrors();
    }

}
