<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Alunos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">

    <h2><?php echo isset($_GET['edit_id_aluno']) ? 'Editar Aluno' : 'Cadastrar Aluno'; ?></h2>
    <?php if (isset($error)) { echo '<div class="alert alert-danger">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>'; } ?>
    <form method="POST" action="">
        
        <?php if (isset($_GET['edit_id_aluno'])):  $row = $students->fetch_assoc(); ?>
            <input type="hidden" name="update_id_aluno" value="<?php echo htmlspecialchars($row['id_aluno'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php endif; ?>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="vc_aluno" class="form-label">Nome:</label>
                <input type="text" class="form-control" id="vc_aluno" name="vc_aluno" value="<?php echo isset($row['vc_aluno']) ? htmlspecialchars($row['vc_aluno'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="vc_usuario" class="form-label">Usuário:</label>
                <input type="text" class="form-control" id="vc_usuario" name="vc_usuario" value="<?php echo isset($row['vc_usuario']) ? htmlspecialchars($row['vc_usuario'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="dt_nascimento" class="form-label">Data de Nascimento:</label>
                <input type="date" class="form-control" id="dt_nascimento" name="dt_nascimento" value="<?php echo isset($row['dt_nascimento']) ? htmlspecialchars($row['dt_nascimento'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="boo_status" class="form-label">Status:</label>
                <select id="boo_status" name="boo_status" class="form-control" required>
                    <option value="1" <?php echo isset($row['boo_status']) && $row['boo_status'] == 1 ? 'selected' : ''; ?>>Ativo</option>
                    <option value="0" <?php echo isset($row['boo_status']) && $row['boo_status'] == 0 ? 'selected' : ''; ?>>Inativo</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo isset($_GET['edit_id_aluno']) ? 'Atualizar Aluno' : 'Cadastrar Aluno'; ?></button>
    </form>

    <h2 class="mt-5">Alunos Cadastrados</h2>
    
    <!-- Formulário para filtro e seleção de itens por página -->
    <form method="GET" action="" class="d-flex align-items-center mb-3">
        <input type="hidden" name="rota" value="cadastrar_aluno">

        <div class="me-3">
            <label for="search_name" class="form-label">Pesquisar por nome:</label>
            <input type="text" class="form-control" id="search_name" name="search_name" value="<?php echo htmlspecialchars($search_name, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="me-3">
            <label for="status_filter" class="form-label">Status:</label>
            <select id="status_filter" name="status_filter" class="form-control">
                <option value="">Todos</option>
                <option value="1" <?php echo $status_filter === "1" ? 'selected' : ''; ?>>Ativo</option>
                <option value="0" <?php echo $status_filter === "0" ? 'selected' : ''; ?>>Inativo</option>
            </select>
        </div>
        <div class="me-3">
        <button type="submit" style="margin-top: 31px;" class="btn btn-primary">Filtrar</button></div>

        <!-- Campo para selecionar a quantidade de itens por página -->
        <div class="ms-auto">
            <form method="GET" action="" class="d-inline-block">
                <input type="hidden" name="rota" value="cadastrar_aluno">
                <div class="d-flex align-items-center">
                    <select class="form-select" id="per_page" name="per_page" onchange="this.form.submit()">
                        <?php 
                            $per_page_options = [5, 10, 15, 20];
                            foreach ($per_page_options as $option): 
                        ?>
                            <option value="<?php echo $option; ?>" <?php echo $option == $per_page ? 'selected' : ''; ?>>
                                <?php echo $option; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </form>
       <!-- Div para informar a quantidade de alunos -->
    <div class="">
        Total de alunos cadastrados: <?php echo $total_rows; ?>
    </div> 
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th><a href="<?php echo ROOT_PATH; ?>?rota=cadastrar_aluno&order_by=vc_aluno&order_direction=<?php echo $order_by[1] == 'asc' ? 'desc' : 'asc'; ?>">Nome</a></th>
                <th><a href="<?php echo ROOT_PATH; ?>?rota=cadastrar_aluno&order_by=vc_usuario&order_direction=<?php echo $order_by[1] == 'asc' ? 'desc' : 'asc'; ?>">Usuário</a></th>
                <th>Data de Nascimento</th>
                <th>Status</th>
                <th>Ações</th>

            </tr>
        </thead>
        <tbody>
            <?php while($row = $students->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['vc_aluno'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['vc_usuario'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($row['dt_nascimento'])), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo $row['boo_status'] == 1 ? 'Ativo' : 'Inativo'; ?></td>
                    
                    <td>
                        <a href="<?php echo ROOT_PATH; ?>?rota=cadastrar_aluno&edit_id_aluno=<?php echo htmlspecialchars($row['id_aluno'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary">Editar</a>
                        <a href="<?php echo ROOT_PATH; ?>?rota=cadastrar_aluno&delete_id_aluno=<?php echo htmlspecialchars($row['id_aluno'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este aluno?')">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Paginação -->
    <?php
        $total_pages = ceil($total_rows / $per_page);
        if ($total_pages > 1):
    ?>
        <nav aria-label="Navegação de página">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="<?php echo ROOT_PATH; ?>?rota=cadastrar_aluno&page=<?php echo $i; ?>&per_page=<?php echo $per_page; ?>">
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
</body>
</html>
