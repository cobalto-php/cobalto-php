<?php

class Aluno extends Controller {

	public function __construct(){
	   parent::__construct();
	}

    public function index(){
        $data["path_bread"] = ProgramaORM::pathBread($_SERVER["REQUEST_URI"]);
        $this->load->view('alunoFiltroView', $data);
    }

}