<?php
include 'conexao.php';

class MatriculaModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'bd_fiap');
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getOrderBy() {
        $allowed_columns = ['id_matricula', 'id_aluno', 'id_turma', 'dt_matricula', 'boo_status'];
        $allowed_directions = ['asc', 'desc'];

        $column = isset($_GET['order_by']) && in_array($_GET['order_by'], $allowed_columns) ? $_GET['order_by'] : 'id_matricula';
        $direction = isset($_GET['order_direction']) && in_array($_GET['order_direction'], $allowed_directions) ? $_GET['order_direction'] : 'asc';

        return [$column, $direction];
    }

    public function getPaginationParams() {
        $default_per_page = 5;
        $per_page_options = [5, 10, 15];

        $per_page = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $per_page_options) ? (int)$_GET['per_page'] : $default_per_page;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = $page < 1 ? 1 : $page;
        $offset = ($page - 1) * $per_page;

        return [$per_page, $page, $offset];
    }

    public function getTotalMatriculas($search_name, $status_filter) {
        $search_name = $this->conn->real_escape_string($search_name);
        $status_filter = $this->conn->real_escape_string($status_filter);

        $where_sql = $this->buildWhereClause($search_name, $status_filter);

        $sql = "SELECT COUNT(*) AS total FROM tb_matricula m
                JOIN tb_aluno a ON m.id_aluno = a.id_aluno
                JOIN tb_turma t ON m.id_turma = t.id_turma
                $where_sql";

        $result = $this->conn->query($sql);
        return $result->fetch_assoc()['total'];
    }

    public function getFilteredMatriculas($search_name, $status_filter, $order_by, $order_direction, $offset, $per_page) {
        $where_sql = $this->buildWhereClause($search_name, $status_filter);

        $sql = "SELECT m.id_matricula, m.id_aluno, m.id_turma, m.dt_matricula, m.boo_status, a.vc_aluno, t.vc_turma 
                FROM tb_matricula m
                JOIN tb_aluno a ON m.id_aluno = a.id_aluno
                JOIN tb_turma t ON m.id_turma = t.id_turma
                $where_sql 
                ORDER BY $order_by $order_direction 
                LIMIT $offset, $per_page";

        return $this->conn->query($sql);
    }

    public function getFilteredStudents() {
        $sql = "SELECT id_aluno, vc_aluno FROM tb_aluno";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTurmas() {
        $sql = "SELECT id_turma, vc_turma FROM tb_turma";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id_matricula) {
        $id_matricula = (int)$id_matricula;
        $sql = "SELECT id_aluno, id_turma, id_matricula FROM tb_matricula WHERE id_matricula =".$id_matricula;
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteMatricula($id_matricula) {
        $id_matricula = (int)$id_matricula;
        $sql = "DELETE FROM tb_matricula WHERE id_matricula = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die('Prepare failed: ' . $this->conn->error);
        }
        $stmt->bind_param('i', $id_matricula);
        return $stmt->execute();
    }


    public function updateMatricula($id_matricula, $id_aluno, $id_turma, $boo_status) {
        // Sanitiza os dados para evitar SQL Injection
        $id_matricula = intval($id_matricula);
        $id_aluno = intval($id_aluno);
        $id_turma = intval($id_turma);
        $boo_status = intval($boo_status);

        $query = "UPDATE tb_matricula SET id_aluno = ?, id_turma = ?, boo_status = ? WHERE id_matricula = ?";
        
        if ($stmt = $this->conn->prepare($query)) {
            // Vincula os parâmetros
            $stmt->bind_param("iiii", $id_aluno, $id_turma, $boo_status, $id_matricula);

            $success = $stmt->execute();

            $stmt->close();

            return $success;
        } else {
            throw new Exception("Falha ao preparar a query: " . $this->conn->error);
        }
    }

    public function createMatricula($id_aluno, $id_turma, $boo_status) {
        $id_aluno = (int)$id_aluno;
        $id_turma = (int)$id_turma;
        $boo_status = (int)$boo_status;
        $sql = "INSERT INTO tb_matricula (id_aluno, id_turma, boo_status) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die('Prepare failed: ' . $this->conn->error);
        }
        $stmt->bind_param('iii', $id_aluno, $id_turma, $boo_status);
        return $stmt->execute();
    }

    public function checkMatriculaExists($id_aluno, $id_turma) {
        $id_aluno = (int)$id_aluno;
        $id_turma = (int)$id_turma;
        $check_sql = "SELECT COUNT(*) AS count FROM tb_matricula WHERE id_aluno = ? AND id_turma = ?";
        $stmt = $this->conn->prepare($check_sql);
        if (!$stmt) {
            die('Prepare failed: ' . $this->conn->error);
        }
        $stmt->bind_param('ii', $id_aluno, $id_turma);
        $stmt->execute();
        $result = $stmt->get_result();
        $check_row = $result->fetch_assoc();
        return $check_row['count'] > 0;
    }

    private function buildWhereClause($search_name, $status_filter) {
        $where_clauses = [];
        if (!empty($search_name)) {
            $where_clauses[] = "a.vc_aluno LIKE '%$search_name%'";
        }
        if ($status_filter !== '') {
            $where_clauses[] = "m.boo_status = $status_filter";
        }
        return count($where_clauses) > 0 ? 'WHERE ' . implode(' AND ', $where_clauses) : '';
    }

	// Executa mesma rotina da flag FILTER_SANITIZE_STRING
	private function filter_string_polyfill(string $string) {
		if (!empty($string)) {
			$string = preg_replace('/\x00|<[^>]*>?/', '', (string) $string);
			$string = str_replace(["'", '"'], ['&#39;', '&#34;'], $string);
		}

		return $string;
	}
    /* Função que tira caracteres ilegais, capazes de ocasionar cross-cript*/
	public function no_xss($string, $type = "") {
		if (!empty($string)) {
			if (!empty($type)) {
				if (strcmp($type, "email") == 0) {
					$string = filter_var($string, FILTER_SANITIZE_EMAIL);
				} else if (strcmp($type, "url") == 0) {
					$string = filter_var($string, FILTER_SANITIZE_URL);
				} else if (strcmp($type, "float") == 0) {
					$string = filter_var($string, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
				} else if (strcmp($type, "integer") == 0) {
					$string = filter_var($string, FILTER_SANITIZE_NUMBER_INT);
				} else if (strcmp($type, "html") == 0) {
					$string = filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
				} else if (strcmp($type, "ip") == 0) {
					$string = filter_var($string, FILTER_VALIDATE_IP);
				} else {
					if (version_compare(PHP_VERSION, '8.1.0', '>=')) {
						$string = self::filter_string_polyfill($string);
					} else {
						$string = filter_var($string, FILTER_SANITIZE_STRING);
					}
				}
				$string = filter_var($string, FILTER_SANITIZE_ADD_SLASHES);
			} else {
				if (version_compare(PHP_VERSION, '8.1.0', '>=')) {
					$string = self::filter_string_polyfill($string);
				} else {
					$string = filter_var($string, FILTER_SANITIZE_STRING);
				}
				$string = filter_var($string, FILTER_SANITIZE_ADD_SLASHES);
			}
		}
		return $string;
	}
}
?>
