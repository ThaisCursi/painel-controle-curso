<?php
require_once 'models/AlunoModel.php';

class AlunoController {
    private $model;

    public function __construct() {
        $this->model = new AlunoModel();
    }

    public function index($error = null) {   
        $order_by = $this->getOrderBy();
        $per_page = $this->getPerPage();
        $page = $this->getPage();
        $offset = ($page - 1) * $per_page;
        $search_name = isset($_GET['search_name']) ? $_GET['search_name'] : '';
        $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';

        $total_rows = $this->model->getTotalStudents($search_name, $status_filter);
        $students = $this->model->getFilteredStudents($search_name, $status_filter, $order_by[0], $order_by[1], $offset, $per_page);

        require './views/cadastrar_aluno.php';
    }

    public function store() {
        $vc_aluno = $_POST['vc_aluno'];
        $vc_usuario = $_POST['vc_usuario'];
        $dt_nascimento = $_POST['dt_nascimento'];
        $boo_status = $_POST['boo_status'];
    
        // Valida se a data de nascimento é no futuro
        if (strtotime($dt_nascimento) > time()) {
            $error = "A data de nascimento não pode ser no futuro.";
            $this->index($error);
            return;
        }
    
        // Valida se o usuário já existe
        if ($this->model->checkUsuarioExists($vc_usuario)) {
            $error = "O nome de usuário já está em uso.";
            $this->index($error);
            return;
        }
    
        // Cria o aluno se todas as validações forem passadas
        $this->model->createStudent($vc_aluno, $vc_usuario, $dt_nascimento, $boo_status);
    
        header('Location: ?rota=cadastrar_aluno');
        exit();
    }

    public function update() {
        $update_id_aluno = $_POST['update_id_aluno'];
        $vc_aluno = $_POST['vc_aluno'];
        $vc_usuario = $_POST['vc_usuario'];
        $dt_nascimento = $_POST['dt_nascimento'];
        $boo_status = $_POST['boo_status'];

        if (strtotime($dt_nascimento) > time()) {
            $error = "A data de nascimento não pode ser no futuro.";
        } else {
            $this->model->updateStudent($update_id_aluno, $vc_aluno, $vc_usuario, $dt_nascimento, $boo_status);
        }

        header('Location: ?rota=cadastrar_aluno');
        exit();
    }

    public function delete() {
        $delete_id_aluno = $_GET['delete_id_aluno'];
        $this->model->deleteStudent($delete_id_aluno);

        header('Location: ?rota=cadastrar_aluno');
        exit();
    }

    private function getOrderBy() {
        $allowed_columns = ['id_aluno', 'vc_aluno', 'vc_usuario', 'dt_nascimento', 'dt_inclusao', 'dt_ult_alteracao', 'boo_status'];
        $allowed_directions = ['asc', 'desc'];

        $column = isset($_GET['order_by']) && in_array($_GET['order_by'], $allowed_columns) ? $_GET['order_by'] : 'vc_aluno';
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
