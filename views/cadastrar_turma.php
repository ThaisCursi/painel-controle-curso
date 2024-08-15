<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $editMode ? 'Editar Turma' : 'Cadastrar Turma'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2><?php echo isset($editMode) ? 'Editar Turma' : 'Cadastrar Turma'; ?></h2>
    <form method="POST" action="?rota=cadastrar_turma&action=<?php echo $editMode ? 'update' : 'store'; ?>">
        <?php if ($editMode):  $row = $turmas->fetch_assoc();?>
            <input type="hidden" name="update_id_turma" value="<?php echo htmlspecialchars($row['id_turma']); ?>">
        <?php endif; ?>
        <div class="mb-3">
            <label for="vc_turma" class="form-label">Nome da Turma:</label>
            <input type="text" class="form-control" id="vc_turma" name="vc_turma" value="<?php echo htmlspecialchars($row['vc_turma'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="vc_tipo" class="form-label">Tipo:</label>
            <select class="form-select" id="vc_tipo" name="vc_tipo" required>
                <?php foreach ($tipos as $tipo): ?>
                    <option value="<?php echo htmlspecialchars($tipo['id_tipo_turma']); ?>" <?php echo ($tipo['vc_tipo'] === ($row['vc_tipo'] ?? '')) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($tipo['vc_tipo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="vc_descricao" class="form-label">Descrição:</label>
            <textarea class="form-control" id="vc_descricao" name="vc_descricao" required><?php echo htmlspecialchars($row['vc_descricao'] ?? ''); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status:</label>
            <select class="form-select" id="status" name="boo_status" required>
                <option value="1" <?php echo ($row['boo_status'] ?? '') == 1 ? 'selected' : ''; ?>>Ativo</option>
                <option value="0" <?php echo ($row['boo_status'] ?? '') == 0 ? 'selected' : ''; ?>>Inativo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $editMode ? 'Atualizar' : 'Cadastrar'; ?></button>
    </form>

    <hr>

    <h2>Lista de Turmas</h2>
    <table class="table table-striped">

        <thead>
            <tr>      

                <th><a href="<?php echo ROOT_PATH; ?>?rota=cadastrar_turma&order_by=id_turma&order_direction=<?php echo $order_by[1]  === 'asc' ? 'desc' : 'asc'; ?>">ID</a></th>
                <th><a href="<?php echo ROOT_PATH; ?>?rota=cadastrar_turma&order_by=vc_turma&order_direction=<?php echo $order_by[1]  === 'asc' ? 'desc' : 'asc'; ?>">Nome da Turma</a></th>
                <th><a href="<?php echo ROOT_PATH; ?>?rota=cadastrar_turma&order_by=vc_descricao&order_direction=<?php echo $order_by[1]  === 'asc' ? 'desc' : 'asc'; ?>">Descrição</a></th>
                <th><a href="<?php echo ROOT_PATH; ?>?rota=cadastrar_turma&order_by=vc_tipo&order_direction=<?php echo $order_by[1]  === 'asc' ? 'desc' : 'asc'; ?>">Tipo</a></th>
                <th><a href="<?php echo ROOT_PATH; ?>?rota=cadastrar_turma&order_by=boo_status&order_direction=<?php echo $order_by[1]  === 'asc' ? 'desc' : 'asc'; ?>">Status</a></th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($turmas)): ?>
                <?php foreach ($turmas as $turma):  ?>
                    <tr>
                        <td><?php echo htmlspecialchars($turma['id_turma']); ?></td>
                        <td><?php echo htmlspecialchars($turma['vc_turma']); ?></td>
                        <td><?php echo htmlspecialchars($turma['vc_descricao']); ?></td>
                        <td><?php echo htmlspecialchars($turma['vc_tipo']); ?></td>
                        <td><?php echo $turma['boo_status'] == 1 ? 'Ativo' : 'Inativo'; ?></td>
                        <td>
                            <a href="<?php echo ROOT_PATH; ?>?rota=cadastrar_turma&id_edit_turma=<?php echo htmlspecialchars($turma['id_turma']); ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="<?php echo ROOT_PATH; ?>?rota=cadastrar_turma&delete_id_turma=<?php echo htmlspecialchars($turma['id_turma']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta turma?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nenhuma turma cadastrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="mt-4">
        <a href="<?php echo ROOT_PATH; ?>" class="btn btn-secondary">Voltar</a>
    </div>
</div>
</body>
</html>
