<!-- views/layouts/header.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Painel de Controle Curso'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="bg-primary text-white text-center py-3">
        <div class="container">
            <h1><?php echo $headerTitle ?? 'Painel de Controle Curso'; ?></h1>
        </div>
    </header>

    <!-- Main content container -->
    <div class="container mt-5">
