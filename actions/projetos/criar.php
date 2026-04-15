<?php
// RESPONSABILIDADE: ponto de entrada para a action de criar projeto (POST)
// Arquivo chamado diretamente pelo formulário: action="actions/projetos/criar.php"
//
// O que deve acontecer aqui, nesta ordem:
//   1. session_start()
//   2. require config.php
//   3. require ProjetoController.php
//   4. verificar se $_SESSION['usuario_id'] existe — se não, redireciona para login
//   5. verificar se $_SERVER['REQUEST_METHOD'] === 'POST' — se não, redireciona para projetos
//   6. chamar ProjetoController::criar()
