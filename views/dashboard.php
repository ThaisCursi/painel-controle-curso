
<style>
        .center-content {
            min-height: 100vh; 
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: -webkit-center;
        }
    </style>
<body>
    <div class="col center-content container mt-5">
        <div class="col ext-center mt-4">
            <div class="col-md-5 d-flex justify-content-center mb-3">
                <a href="<?php echo ROOT_PATH; ?>?rota=cadastrar_aluno" class="btn btn-primary w-100">Cadastrar Aluno</a>
            </div>
            <div class="col-md-5 d-flex justify-content-center mb-3">
                <a href="<?php echo ROOT_PATH; ?>?rota=cadastrar_turma" class="btn btn-secondary w-100">Cadastrar Turma</a>
            </div>
            <div class="col-md-5 d-flex justify-content-center mb-3">
                <a href="<?php echo ROOT_PATH; ?>?rota=fazer_matricula" class="btn btn-success w-100">Fazer Matrícula</a>
            </div>
            <div class="col-md-5 d-flex justify-content-center mb-3">
                <form method="post" class="w-100" action="?rota=logout">
                    <button type="submit" class="btn btn-danger w-100" >Logout</button>
                </form>
            </div>
        </div>


    </div>

