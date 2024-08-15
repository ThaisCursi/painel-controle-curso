<?php
include 'conexao.php';

class AlunoModel {
    private $conn;
    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'bd_fiap');
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getIdStudents($id_students) {
        $sql = "SELECT id_aluno, vc_aluno, vc_usuario, dt_nascimento, dt_inclusao, dt_ult_alteracao, boo_status FROM tb_aluno WHERE id_aluno=$id_students";
        return $this->conn->query($sql);
    }

    public function getTotalStudents($search_name, $status_filter) {
        $where_sql = $this->buildWhereClause($search_name, $status_filter);
        $sql = "SELECT COUNT(*) AS total FROM tb_aluno $where_sql";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc()['total'];
    }

    public function getFilteredStudents($search_name, $status_filter, $order_by, $order_direction, $offset=null, $per_page=null) {
        $where_sql = $this->buildWhereClause($search_name, $status_filter);
        $sql = "SELECT id_aluno, vc_aluno, vc_usuario, dt_nascimento, dt_inclusao, dt_ult_alteracao, boo_status 
                FROM tb_aluno 
                $where_sql 
                ORDER BY $order_by $order_direction";
        if (isset($offset) && isset($per_page)) {
            $sql .= " LIMIT $offset, $per_page";
        }
        return $this->conn->query($sql);
    }

   
    public function createStudent($vc_aluno, $vc_usuario, $dt_nascimento, $boo_status) {
        $query = "INSERT INTO tb_aluno (vc_aluno, vc_usuario, dt_nascimento, boo_status) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('sssi', $vc_aluno, $vc_usuario, $dt_nascimento, $boo_status);
        $stmt->execute();
    }

    public function checkUsuarioExists($vc_usuario) {
        $query = "SELECT COUNT(*) FROM tb_aluno WHERE vc_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $vc_usuario);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        return $count > 0;
    }
    

    public function updateStudent($id_aluno, $vc_aluno, $vc_usuario, $dt_nascimento, $boo_status) {
        $sql = "UPDATE tb_aluno SET vc_aluno='$vc_aluno', vc_usuario='$vc_usuario', dt_nascimento='$dt_nascimento', boo_status=$boo_status 
                WHERE id_aluno=$id_aluno";
        $this->conn->query($sql);
    }

    public function deleteStudent($id_aluno) {
        $sql = "DELETE FROM tb_aluno WHERE id_aluno = $id_aluno";
        $this->conn->query($sql);
    }

    private function buildWhereClause($search_name, $status_filter) {
        $where_clauses = [];
        if (!empty($search_name)) {
            $where_clauses[] = "vc_aluno LIKE '%$search_name%'";
        }
        if ($status_filter !== '') {
            $where_clauses[] = "boo_status = $status_filter";
        }

        return count($where_clauses) > 0 ? 'WHERE ' . implode(' AND ', $where_clauses) : '';
    }
}
