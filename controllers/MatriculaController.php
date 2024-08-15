<?php
require_once 'models/MatriculaModel.php';
require_once 'models/AlunoModel.php';
require_once 'models/TurmaModel.php';

class MatriculaController {
    private $model;
    private $alunoModel;
    private $turmaModel;

    public function __construct() {
        $this->model = new MatriculaModel();
        $this->alunoModel = new AlunoModel();
        $this->turmaModel = new TurmaModel();
    }

    public function index($error = null) {
        $editMode = false; 
        if (isset($_GET['edit_id_matricula'])) {
            $editMode = true;
            $id_matricula = $this->model->no_xss($_GET['edit_id_matricula'],'integer');
            
            $matricula = $this->model->getById($id_matricula);
            $selected_id_aluno = $matricula[0]['id_aluno']; 
            $selected_id_turma = $matricula[0]['id_turma']; 
        } else {
            $selected_id_aluno = ''; 
            $selected_id_turma = ''; 
        }

        $order_by = $this->getOrderBy();
        $per_page = $this->getPerPage();
        $page = $this->getPage();
        $offset = ($page - 1) * $per_page;
        $search_name = isset($_GET['search_name']) ? $this->model->no_xss($_GET['search_name']) : '';
        $status_filter = isset($_GET['status_filter']) ? $this->model->no_xss($_GET['status_filter']) : '';

        $total_rows = $this->model->getTotalMatriculas($search_name, $status_filter);
        $matriculas = $this->model->getFilteredMatriculas($search_name, $status_filter, $order_by[0], $order_by[1], $offset, $per_page);

        // Pega alunos e turmas para o formulário
        $alunos_result = $this->alunoModel->getFilteredStudents($search_name, $status_filter, 'id_aluno', $order_by[1], $offset);
        $turmas_result = $this->turmaModel->getFilteredTurmas($search_name, $status_filter, 'id_turma', $order_by[1], $offset);

        require './views/fazer_matricula.php';
    }

    public function store() {
        $id_aluno = intval($this->model->no_xss($_POST['id_aluno']));
        $id_turma = intval($this->model->no_xss($_POST['id_turma']));
        $boo_status = intval($this->model->no_xss($_POST['boo_status']));

        // Verificar se a matrícula já existe
        if ($this->model->checkMatriculaExists($id_aluno, $id_turma)) {
            $error = "O aluno já está matriculado nesta turma.";
            $this->index($error);
            return;
        }

        $this->model->createMatricula($id_aluno, $id_turma, $boo_status);

        header('Location: ?rota=fazer_matricula');
        exit();
    }

    public function update() {
        $update_id_matricula = intval($this->model->no_xss($_POST['update_id_matricula'],'integer'));
        $id_aluno = intval($this->model->no_xss($_POST['id_aluno'],'integer'));
        $id_turma = intval($this->model->no_xss($_POST['id_turma'],'integer'));
        $boo_status = intval($this->model->no_xss($_POST['boo_status'],'integer'));

        $this->model->updateMatricula($update_id_matricula, $id_aluno, $id_turma, $boo_status);

        header('Location: ?rota=fazer_matricula');
        exit();
    }

    public function delete() {
        $delete_id_matricula = intval($this->model->no_xss($_GET['delete_id_matricula'],'integer'));
        $this->model->deleteMatricula($delete_id_matricula);

        header('Location: ?rota=fazer_matricula');
        exit();
    }

    private function getOrderBy() {
        $allowed_columns = ['id_matricula', 'id_aluno', 'id_turma', 'dt_matricula', 'boo_status'];
        $allowed_directions = ['asc', 'desc'];

        $column = isset($_GET['order_by']) && in_array($_GET['order_by'], $allowed_columns) ? $_GET['order_by'] : 'id_matricula';
        $direction = isset($_GET['order_direction']) && in_array($_GET['order_direction'], $allowed_directions) ? $_GET['order_direction'] : 'asc';

        return [$column, $direction];
    }

    private function getPerPage() {
        $default_per_page = 5;
        $per_page_options = [5, 10, 15];

        return isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $per_page_options) ? (int)$_GET['per_page'] : $default_per_page;
    }

    private function getPage() {
        return isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    }
}
