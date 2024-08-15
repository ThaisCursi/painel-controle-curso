<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Matrículas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2><?php echo isset($_GET['edit_id_matricula']) ? 'Editar Matrícula' : 'Cadastrar Matrícula'; ?></h2>
    <?php if (isset($error)) { echo '<div class="alert alert-danger">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>'; } ?>
    <form method="POST" action="">
        <?php if (isset($_GET['edit_id_matricula'])):  ($row = $matriculas->fetch_assoc())?>
            <input type="hidden" name="update_id_matricula" value="<?php echo htmlspecialchars($row['id_matricula'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php endif; ?>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="id_aluno" class="form-label">Aluno:</label>
                <select class="form-select" id="id_aluno" name="id_aluno" required>
                    <option value="">Selecione um aluno</option>
                    <?php while ($row = $alunos_result->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['id_aluno'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo isset($row['id_aluno']) && $row['id_aluno'] == $selected_id_aluno ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['vc_aluno'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="id_turma" class="form-label">Turma:</label>
                <select class="form-select" id="id_turma" name="id_turma" required>
                    <option value="">Selecione uma turma</option>
                    <?php while ($row = $turmas_result->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['id_turma'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo isset($row['id_turma']) && $row['id_turma'] == $selected_id_turma ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['vc_turma'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="boo_status" class="form-label">Status:</label>
                <select id="boo_status" name="boo_status" class="form-select" required>
                    <option value="1" <?php echo isset($row['boo_status']) && $row['boo_status'] == 1 ? 'selected' : ''; ?>>Ativo</option>
                    <option value="0" <?php echo isset($row['boo_status']) && $row['boo_status'] == 0 ? 'selected' : ''; ?>>Inativo</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo isset($_GET['edit_id_matricula']) ? 'Atualizar Matrícula' : 'Cadastrar Matrícula'; ?></button>
    </form>

    <h2 class="mt-5">Lista de Matrículas</h2>

    <!-- Formulário para filtro e seleção de itens por página -->
    <form method="GET" action="" class="d-flex align-items-center mb-3">
        <input type="hidden" name="rota" value="fazer_matricula">

        <div class="me-3">
            <label for="search_name" class="form-label">Buscar por Aluno:</label>
            <input type="text" class="form-control" id="search_name" name="search_name" value="<?php echo htmlspecialchars($search_name, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="me-3">
            <label for="status_filter" class="form-label">Status:</label>
            <select id="status_filter" name="status_filter" class="form-select">
                <option value="">Todos</option>
                <option value="1" <?php echo $status_filter === "1" ? 'selected' : ''; ?>>Ativo</option>
                <option value="0" <?php echo $status_filter === "0" ? 'selected' : ''; ?>>Inativo</option>
            </select>
        </div>
        <div class="me-3">
            <button type="submit" style="margin-top: 31px;" class="btn btn-primary">Filtrar</button>
        </div>

        <!-- Campo para selecionar a quantidade de itens por página -->
        <div class="ms-auto">
            <form method="GET" action="" class="d-inline-block">
                <input type="hidden" name="rota" value="fazer_matricula">
                <div class="d-flex align-items-center">
                    <select class="form-select" id="per_page" name="per_page" onchange="this.form.submit()">
                        <?php foreach ([5, 10, 15] as $option): ?>
                            <option value="<?php echo $option; ?>" <?php echo $option == $per_page ? 'selected' : ''; ?>>
                                <?php echo $option; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </form>

    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th><a href="<?php echo ROOT_PATH; ?>?rota=fazer_matricula&?order_by=id_matricula&order_direction=<?php echo $order_by[1] == 'asc' ? 'desc' : 'asc'; ?>">ID Matrícula</a></th>
                <th>Aluno</th>
                <th>Turma</th>
                <th>Data da Matrícula</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($matriculas->num_rows > 0): ?>
                <?php while ($row = $matriculas->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_matricula'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['vc_aluno'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['vc_turma'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo date('d/m/Y H:i:s', strtotime($row['dt_matricula'])); ?></td>
                        <td><?php echo $row['boo_status'] == 1 ? 'Ativo' : 'Inativo'; ?></td>
                        <td>
                            <a href="<?php echo ROOT_PATH; ?>?rota=fazer_matricula&edit_id_matricula=<?php echo htmlspecialchars($row['id_matricula'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="<?php echo ROOT_PATH; ?>?rota=fazer_matricula&delete_id_matricula=<?php echo htmlspecialchars($row['id_matricula'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta matrícula?')">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Nenhuma matrícula encontrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Paginação -->
    <!-- Paginação -->
    <?php
        $total_pages = ceil($total_rows / $per_page);
        if ($total_pages > 1):
    ?>
        <nav aria-label="Navegação de página">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="<?php echo ROOT_PATH; ?>?rota=fazer_matricula&page=<?php echo $i; ?>&per_page=<?php echo $per_page; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="<?php echo ROOT_PATH; ?>" class="btn btn-secondary">Voltar</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
