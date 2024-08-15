<?php
include 'conexao.php';

class TurmaModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'bd_fiap');
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getTurma($id_turma) {
        $sql = "SELECT * FROM tb_turma WHERE id_turma = $id_turma";
        return $this->conn->query($sql);
    }

    public function getTotalTurmas($search_name, $status_filter) {
        $where_sql = $this->buildWhereClause($search_name, $status_filter);
        $sql = "SELECT COUNT(*) AS total FROM tb_turma $where_sql";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc()['total'];
    }

    public function getFilteredTurmas($search_name, $status_filter, $order_by, $order_direction, $offset=null, $per_page=null) {
        $where_sql = $this->buildWhereClause($search_name, $status_filter);
        $sql = "SELECT t.id_turma, t.vc_turma, t.vc_descricao,t.id_tipo_turma, tt.vc_tipo, t.boo_status
                FROM tb_turma as t
                JOIN  tb_tipo_turma  AS tt on (tt.id_tipo_turma = t.id_tipo_turma)
                $where_sql ORDER BY $order_by $order_direction";

        if (isset($offset) && isset($per_page)) {
            $sql .= " LIMIT $offset, $per_page";
        }
        return $this->conn->query($sql);
    }

    public function getTipo() {
        $sql = "SELECT DISTINCT id_tipo_turma, vc_tipo FROM tb_tipo_turma";
        $result = $this->conn->query($sql);
        
        $tipos = [];
        if ($result) {

            $icount = 0;
            while ($row = $result->fetch_assoc()) {
                $tipos[$icount]['id_tipo_turma'] = $row['id_tipo_turma'];
                $tipos[$icount]['vc_tipo'] = $row['vc_tipo'];
                $icount++;
            }
        }
        return $tipos;
    }

    public function createTurma($vc_turma, $vc_descricao, $id_tipo_turma, $boo_status) {
        $sql = "INSERT INTO tb_turma (vc_turma, vc_descricao, id_tipo_turma, boo_status) 
                VALUES ('$vc_turma', '$vc_descricao', '$id_tipo_turma', $boo_status)";
        $this->conn->query($sql);
    }

    public function updateTurma($id_turma, $vc_turma, $vc_descricao, $id_tipo_turma, $boo_status) {
        $sql = "UPDATE tb_turma SET vc_turma='$vc_turma', vc_descricao='$vc_descricao', id_tipo_turma='$id_tipo_turma', boo_status=$boo_status 
                WHERE id_turma=$id_turma";
        $this->conn->query($sql);
    }

    public function deleteTurma($id_turma) { 
        $sql = "DELETE FROM tb_turma WHERE id_turma = $id_turma";
        $this->conn->query($sql);
    }

    private function buildWhereClause($search_name, $status_filter) {
        $where_clauses = [];
        if (!empty($search_name)) {
            $where_clauses[] = "vc_turma LIKE '%$search_name%'";
        }
        if ($status_filter !== '') {
            $where_clauses[] = "boo_status = $status_filter";
        }
        return count($where_clauses) > 0 ? 'WHERE ' . implode(' AND ', $where_clauses) : '';
    }
}
