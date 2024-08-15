<?php
require_once 'models/TurmaModel.php';

class TurmaController {
    private $model;

    public function __construct() {
        $this->model = new TurmaModel();
    }

    public function index() {

        $editMode = false; 
    
        if (isset($_GET['id_edit_turma'])) {
            $editMode = true;
        }

        $order_by = $this->getOrderBy();
        $per_page = $this->getPerPage();
        $page = $this->getPage();
        $offset = ($page - 1) * $per_page;
        $search_name = isset($_GET['search_name']) ? $_GET['search_name'] : '';
        $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';
        $tipos = $this->model->getTipo();


        $total_rows = $this->model->getTotalTurmas($search_name, $status_filter);
        $turmas = $this->model->getFilteredTurmas($search_name, $status_filter, $order_by[0], $order_by[1], $offset, $per_page);
        require './views/cadastrar_turma.php';
    }

    public function store() {
        $vc_turma = $_POST['vc_turma'];
        $vc_descricao = $_POST['vc_descricao'];
        $vc_tipo = $_POST['vc_tipo'];
        $boo_status = $_POST['boo_status'];

        $this->model->createTurma($vc_turma, $vc_descricao, $vc_tipo, $boo_status);

        header('Location: ?rota=cadastrar_turma');
        exit();
    }

    public function update() {
        $update_id_turma = $_POST['update_id_turma'];
        $vc_turma = $_POST['vc_turma'];
        $vc_descricao = $_POST['vc_descricao'];
        $vc_tipo = $_POST['vc_tipo'];
        $boo_status = $_POST['boo_status'];

        $this->model->updateTurma($update_id_turma, $vc_turma, $vc_descricao, $vc_tipo, $boo_status);

        header('Location: ?rota=cadastrar_turma');
        exit();
    }

    public function delete() { 
        $delete_id_turma = $_GET['delete_id_turma'];
        $this->model->deleteTurma($delete_id_turma);

        header('Location: ?rota=cadastrar_turma');
        exit();
    }

    private function getOrderBy() {
        $allowed_columns = ['id_turma', 'vc_turma', 'vc_descricao', 'vc_tipo', 'boo_status'];
        $allowed_directions = ['asc', 'desc'];

        $column = isset($_GET['order_by']) && in_array($_GET['order_by'], $allowed_columns) ? $_GET['order_by'] : 'vc_turma';
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
