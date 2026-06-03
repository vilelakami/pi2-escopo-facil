# Guia completo do projeto Escopo Facil

Este documento explica o projeto Escopo Facil de ponta a ponta, usando uma linguagem mais simples e voltada para quem esta aprendendo programacao.

A ideia aqui nao e decorar tudo. A ideia e entender o caminho que uma informacao percorre dentro do sistema: ela sai da tela, passa por uma action, entra em um controller, chega em um model, conversa com o banco de dados e depois volta para o usuario por meio de um redirecionamento ou atualizacao da tela.

---

## 1. O que e o Escopo Facil

O Escopo Facil e um sistema web para organizar projetos, membros e tarefas.

Na pratica, ele permite que uma pessoa:

- crie uma conta;
- confirme o email;
- faca login;
- crie projetos;
- adicione membros a projetos;
- veja os projetos em que participa;
- crie, edite, mova e exclua tarefas;
- atualize seus dados pessoais;
- troque a senha;
- recupere a senha por um link enviado por email.

O projeto usa PHP no backend, MySQL como banco de dados e JavaScript no frontend para algumas interacoes da tela, principalmente no kanban de tarefas.

---

## 2. Tecnologias usadas

As principais tecnologias do projeto sao:

- PHP: linguagem do backend.
- MySQL: banco de dados relacional.
- PDO: forma segura do PHP conversar com o banco.
- HTML/CSS: estrutura e visual das telas.
- JavaScript: interacoes no navegador.
- XAMPP: ambiente local com Apache, PHP e MySQL.

O projeto nao usa um framework grande como Laravel. Isso e bom para estudo, porque deixa mais claro o que cada arquivo faz.

---

## 3. Ideia principal da arquitetura

O projeto segue um fluxo parecido com este:

```text
Pagina HTML/PHP
    -> formulario ou fetch
    -> action
    -> controller
    -> model
    -> banco de dados
    -> redirect ou resposta
```

Em outras palavras:

1. O usuario interage com uma tela.
2. A tela envia dados para uma action.
3. A action prepara o ambiente e chama o controller.
4. O controller valida as regras do sistema.
5. O model executa comandos no banco.
6. O sistema redireciona o usuario para uma pagina.

Esse padrao aparece em varias partes do projeto.

Exemplo de fluxo para criar uma tarefa:

```text
pages/tarefas/index.php
    -> assets/js/pages/tarefas.js
    -> actions/tarefas/criar.php
    -> controllers/TarefaController.php
    -> models/Tarefa.php
    -> tabela tarefas no MySQL
```

---

## 4. Estrutura de pastas

Esta e a funcao geral de cada pasta importante:

```text
actions/
controllers/
models/
includes/
pages/
partials/
assets/
docs/
scripts/
```

### actions

A pasta `actions` guarda os arquivos que recebem requisicoes de formularios ou chamadas `fetch`.

Uma action normalmente:

- carrega arquivos necessarios com `require_once`;
- confere se o metodo e `POST`;
- chama um controller ou executa um fluxo simples;
- redireciona o usuario.

Exemplo:

```php
<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controllers/TarefaController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/?page=tarefas');
    exit;
}

(new TarefaController())->criar();
```

Esse arquivo nao deveria ter muita regra de negocio. Ele funciona como uma porta de entrada.

### controllers

A pasta `controllers` guarda as classes que controlam a regra do sistema.

Um controller decide coisas como:

- os campos obrigatorios foram preenchidos?
- o usuario esta logado?
- o usuario pode mexer nesse projeto?
- o status da tarefa e valido?
- para onde redirecionar se algo der errado?

Exemplo simplificado:

```php
if ($titulo === '') {
    $this->redirecionar($projetoId, 'titulo-vazio');
}

if (!ProjetoMembro::jaEMembro($projetoId, $usuarioId)) {
    $this->redirecionar($projetoId, 'nao-autorizado');
}
```

O controller nao deve montar SQL diretamente. Quem conversa com o banco e o model.

### models

A pasta `models` guarda as classes que conversam com o banco de dados.

Um model normalmente possui metodos como:

- `criar`;
- `buscarPorId`;
- `listar`;
- `atualizar`;
- `deletar`.

Exemplo simplificado de model:

```php
$pdo = getConnection();
$stmt = $pdo->prepare("
    SELECT *
    FROM tarefas
    WHERE projeto_id = :projeto_id
");
$stmt->execute(['projeto_id' => $projetoId]);
return $stmt->fetchAll();
```

Repare que o SQL usa `:projeto_id`. Isso e um parametro preparado. Ele ajuda a evitar SQL Injection, que e quando alguem tenta enviar comandos maliciosos para o banco por meio de campos do sistema.

### includes

A pasta `includes` guarda arquivos auxiliares reutilizados por varias partes do projeto.

Exemplos:

- `includes/db.php`: ajuda a obter a conexao com o banco.
- `includes/session.php`: funcoes relacionadas ao usuario logado.
- `includes/auth_guard.php`: protege paginas que precisam de login.
- `includes/mailer.php`: funcoes para envio de email.

Esses arquivos sao como ferramentas compartilhadas.

### pages

A pasta `pages` guarda as telas do sistema.

Exemplos:

- `pages/auth/login.php`;
- `pages/auth/cadastro.php`;
- `pages/projetos/index.php`;
- `pages/tarefas/index.php`;
- `pages/configuracao.php`.

Esses arquivos exibem HTML e PHP juntos. Eles devem buscar dados necessarios para montar a tela, mas a regra principal deve ficar nos controllers e models.

### partials

A pasta `partials` guarda partes de tela reutilizaveis, como sidebar e layouts.

Isso evita repetir o mesmo HTML em varias paginas.

### assets

A pasta `assets` guarda arquivos estaticos:

- CSS;
- JavaScript;
- imagens;
- icones.

O backend nao executa esses arquivos diretamente. Eles sao enviados para o navegador.

### docs

A pasta `docs` guarda documentacao do projeto.

Este arquivo, por exemplo, fica aqui para ajudar novos integrantes a entenderem o sistema.

### scripts

A pasta `scripts` guarda comandos auxiliares. Um exemplo e o script de migracao para adicionar confirmacao de email em bancos ja existentes.

---

## 5. Arquivo config.php

O `config.php` e um dos arquivos mais importantes do projeto.

Ele carrega configuracoes como:

- URL base do projeto;
- variaveis do arquivo `.env`;
- funcao `getConnection()`;
- funcao `appUrl()`.

A funcao `getConnection()` cria uma conexao PDO com o MySQL.

Exemplo conceitual:

```php
$pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
```

Isso significa:

- `PDO`: objeto usado para conversar com o banco;
- `ERRMODE_EXCEPTION`: se der erro no banco, o PHP lanca uma excecao;
- `FETCH_ASSOC`: os resultados vem como array associativo.

Exemplo de array associativo:

```php
[
    'id' => 1,
    'nome' => 'Maria',
    'email' => 'maria@email.com'
]
```

A funcao `appUrl()` ajuda a montar links completos. Ela e importante para email, porque um email precisa de uma URL absoluta, como:

```text
http://localhost/pi2-escopo-facil/index.php?page=confirmar-email&token=...
```

---

## 6. Arquivo .env

O `.env` guarda configuracoes que podem mudar de maquina para maquina.

Exemplo:

```env
DB_HOST=localhost
DB_NAME=escopo_facil
DB_USER=root
DB_PASS=
```

Essas informacoes nao devem ficar espalhadas pelo codigo, porque:

- no computador de uma pessoa o usuario do banco pode ser `root`;
- em uma hospedagem, pode ser outro usuario;
- a senha pode mudar;
- a URL do sistema muda quando sair do localhost.

O `.env.example` serve como modelo seguro para outros integrantes criarem seu proprio `.env`.

---

## 7. Banco de dados

O banco principal se chama `escopo_facil`.

As tabelas principais sao:

```text
usuarios
projetos
projeto_membros
tarefas
tokens_redefinicao
tokens_confirmacao_email
```

### usuarios

Guarda os dados de quem usa o sistema.

Campos importantes:

- `id`: identificador unico do usuario.
- `nome`: nome do usuario.
- `email`: email unico.
- `senha`: senha com hash.
- `email_verificado`: indica se o email foi confirmado.
- `cargo`: papel profissional do usuario.
- `avatar`: caminho da imagem de perfil.

Importante: a senha nao deve ser salva pura no banco.

Errado:

```text
senha = 12345678
```

Certo:

```text
senha = $2y$10$...
```

Esse valor e gerado por:

```php
password_hash($senha, PASSWORD_BCRYPT)
```

Para conferir a senha no login, usamos:

```php
password_verify($senhaDigitada, $usuario['senha'])
```

### projetos

Guarda os projetos criados.

Campos importantes:

- `id`: identificador do projeto.
- `titulo`: nome do projeto.
- `descricao`: texto explicando o projeto.
- `criado_por`: usuario que criou o projeto.

O campo `criado_por` e uma chave estrangeira para `usuarios.id`.

Isso significa: um projeto pertence a um usuario criador.

### projeto_membros

Liga usuarios e projetos.

Essa tabela existe porque um projeto pode ter varios usuarios, e um usuario pode participar de varios projetos.

Esse tipo de relacao e chamado de muitos-para-muitos.

Exemplo:

```text
Projeto 1 tem Usuario 1, Usuario 2 e Usuario 3
Usuario 2 participa do Projeto 1 e Projeto 5
```

Campos importantes:

- `projeto_id`;
- `usuario_id`;
- `role`.

O campo `role` pode ser:

- `admin`;
- `membro`.

### tarefas

Guarda as tarefas do kanban.

Campos importantes:

- `projeto_id`: projeto ao qual a tarefa pertence.
- `titulo`: nome da tarefa.
- `descricao`: detalhes.
- `prioridade`: 1 baixa, 2 media, 3 alta.
- `status`: 1 a fazer, 2 em andamento, 3 concluido.
- `prazo`: data limite.
- `criado_por`: usuario que criou a tarefa.

O kanban usa o campo `status` para saber em qual coluna a tarefa aparece.

### tokens_redefinicao

Guarda links temporarios para recuperacao de senha.

Campos importantes:

- `usuario_id`: dono do token.
- `token`: codigo aleatorio.
- `expira_em`: data/hora em que o token deixa de valer.
- `usado`: indica se o link ja foi usado.

### tokens_confirmacao_email

Guarda links temporarios para confirmacao de cadastro.

O funcionamento e parecido com recuperacao de senha, mas o objetivo e confirmar que o email pertence ao usuario.

---

## 8. O que e CRUD

CRUD e uma sigla muito usada em sistemas.

Ela significa:

```text
C = Create  = Criar
R = Read    = Ler/Listar
U = Update  = Atualizar
D = Delete  = Deletar
```

No projeto, temos CRUD principalmente para:

- projetos;
- tarefas;
- membros, em parte;
- dados do usuario, em parte.

### CRUD de tarefas

O CRUD de tarefas segue este caminho:

```text
Criar:
formulario/fetch -> actions/tarefas/criar.php -> TarefaController::criar() -> Tarefa::criar()

Listar:
pages/tarefas/index.php -> Tarefa::listarPorProjeto()

Editar:
formulario/fetch -> actions/tarefas/editar.php -> TarefaController::editar() -> Tarefa::atualizar()

Deletar:
formulario/fetch -> actions/tarefas/deletar.php -> TarefaController::deletar() -> Tarefa::deletar()
```

Exemplo de criacao no model:

```php
$stmt = $pdo->prepare("
    INSERT INTO tarefas (projeto_id, titulo, descricao, prioridade, status, prazo, criado_por)
    VALUES (:projeto_id, :titulo, :descricao, :prioridade, :status, :prazo, :criado_por)
");
```

O `INSERT` cria uma linha nova na tabela.

Exemplo de atualizacao:

```php
$stmt = $pdo->prepare("
    UPDATE tarefas
    SET titulo = :titulo,
        descricao = :descricao,
        prioridade = :prioridade,
        status = :status,
        prazo = :prazo
    WHERE id = :id
");
```

O `UPDATE` altera uma linha existente.

Exemplo de exclusao:

```php
$stmt = $pdo->prepare("DELETE FROM tarefas WHERE id = :id");
```

O `DELETE` remove uma linha.

---

## 9. Fluxo de cadastro

O cadastro com confirmacao de email funciona assim:

```text
Usuario preenche cadastro
    -> actions/auth/cadastro.php
    -> Usuario::criar()
    -> TokenConfirmacaoEmail::criar()
    -> enviarEmailConfirmacaoCadastro()
    -> redireciona para login
```

No cadastro, o sistema valida:

- nome obrigatorio;
- email obrigatorio;
- email em formato valido;
- cargo valido;
- senha com pelo menos 8 caracteres;
- confirmacao de senha igual;
- aceite dos termos;
- email ainda nao cadastrado.

Depois disso, o usuario e criado com:

```php
email_verificado = 0
```

Isso significa que a conta existe, mas ainda nao pode fazer login.

Em seguida, o sistema gera um token:

```php
$token = bin2hex(random_bytes(32));
```

Essa linha cria um codigo aleatorio grande, dificil de adivinhar.

Depois o sistema monta uma URL:

```php
$confirmacaoUrl = appUrl('/index.php?page=confirmar-email&token=' . urlencode($token));
```

Quando o usuario clica no link, o sistema valida o token e confirma o email.

---

## 10. Fluxo de confirmacao de email

O link de confirmacao chama:

```text
index.php?page=confirmar-email&token=...
```

O `index.php` encaminha esse fluxo para:

```text
actions/auth/confirmar-email.php
```

A action faz:

1. le o token da URL;
2. valida se o token existe;
3. verifica se nao expirou;
4. verifica se ainda nao foi usado;
5. marca o email do usuario como confirmado;
6. marca o token como usado;
7. redireciona para login com mensagem de sucesso.

Trecho importante:

```php
Usuario::confirmarEmail((int) $tokenRow['usuario_id']);
TokenConfirmacaoEmail::marcarUsado((int) $tokenRow['id']);
```

Isso evita que o mesmo link seja usado varias vezes.

---

## 11. Fluxo de login

O login funciona assim:

```text
Usuario informa email e senha
    -> actions/auth/login.php
    -> Usuario::buscarPorEmail()
    -> password_verify()
    -> autenticarUsuario()
    -> dashboard
```

O sistema valida:

- email preenchido;
- senha preenchida;
- email com formato valido;
- usuario existe;
- senha bate com o hash salvo;
- email ja foi confirmado.

Quando tudo esta certo, o sistema chama:

```php
autenticarUsuario((int) $usuario['id']);
```

Essa funcao salva o ID do usuario na sessao.

A sessao e como uma memoria temporaria do navegador no servidor. Ela permite que o sistema lembre que aquele usuario ja fez login.

---

## 12. Sessao e usuario logado

O arquivo `includes/session.php` concentra funcoes de sessao.

Exemplo conceitual:

```php
function usuarioLogado(): ?int
{
    return $_SESSION['usuario_id'] ?? null;
}
```

Se existir `$_SESSION['usuario_id']`, significa que alguem esta logado.

Outra funcao importante:

```php
function estaLogado(): bool
{
    return usuarioLogado() !== null;
}
```

Ela transforma a pergunta "existe usuario logado?" em `true` ou `false`.

---

## 13. Protecao de paginas com auth_guard

Algumas paginas nao podem ser acessadas sem login.

Para isso existe:

```php
require_once __DIR__ . '/../../includes/auth_guard.php';
```

O `auth_guard.php` verifica se o usuario esta logado. Se nao estiver, redireciona para login.

Fluxo:

```text
Usuario tenta abrir /?page=tarefas
    -> auth_guard verifica sessao
    -> se nao estiver logado, volta para login
```

Isso impede acesso direto a paginas internas.

---

## 14. Fluxo de logout

O logout chama:

```text
actions/auth/logout.php
```

Ele limpa a sessao do usuario e redireciona para login.

Em termos simples:

```text
antes: navegador esta associado ao usuario 3
depois: navegador nao esta associado a nenhum usuario
```

---

## 15. Recuperacao de senha

A recuperacao de senha usa token por email.

Fluxo:

```text
Usuario informa email
    -> actions/auth/esqueci-senha.php
    -> Usuario::buscarPorEmail()
    -> TokenRedefinicao::criar()
    -> enviarEmailRedefinicaoSenha()
    -> usuario abre link
    -> actions/auth/redefinir-senha.php
    -> Usuario::alterarSenha()
```

O sistema nao revela se o email existe.

Isso e importante por seguranca.

Exemplo:

```php
$usuario = Usuario::buscarPorEmail($email);
$params = 'sucesso=1';

if ($usuario) {
    // cria token e envia email
}
```

Mesmo se o usuario nao existir, a tela mostra uma mensagem generica de sucesso. Assim uma pessoa mal-intencionada nao consegue testar varios emails para descobrir quem tem conta.

---

## 16. Configuracao de perfil

A tela `pages/configuracao.php` permite:

- atualizar nome;
- atualizar email;
- atualizar cargo;
- alterar senha.

Atualizar dados pessoais:

```text
pages/configuracao.php
    -> actions/usuario/atualizar.php
    -> Usuario::atualizar()
```

Alterar senha:

```text
pages/configuracao.php
    -> actions/usuario/alterar-senha.php
    -> Usuario::buscarPorId()
    -> password_verify()
    -> Usuario::alterarSenha()
```

Para trocar senha, o sistema primeiro confere se a senha atual esta correta. Isso evita que alguem altere a senha de uma conta aberta sem saber a senha antiga.

---

## 17. Projetos

Projetos sao o centro do sistema.

Um projeto possui:

- titulo;
- descricao;
- criador;
- membros;
- tarefas.

O model `Projeto.php` cuida das operacoes no banco.

O controller `ProjetoController.php` cuida das regras para criar, editar e deletar.

O caminho geral e:

```text
actions/projetos/criar.php
    -> ProjetoController::criar()
    -> Projeto::criar()
```

Quando um projeto e criado, normalmente o criador tambem precisa entrar como membro administrador do projeto. Isso permite que ele gerencie membros e tarefas.

---

## 18. Membros de projeto

Os membros ficam na tabela `projeto_membros`.

Essa tabela responde perguntas como:

- o usuario participa deste projeto?
- ele e admin ou membro comum?
- ele pode remover outro membro?
- ele pode alterar permissao?

O model `ProjetoMembro.php` tem metodos para lidar com isso.

Um exemplo importante e verificar se o usuario ja e membro:

```php
ProjetoMembro::jaEMembro($projetoId, $usuarioId)
```

Essa verificacao aparece no fluxo de tarefas para impedir que um usuario mexa em tarefas de um projeto do qual ele nao participa.

---

## 19. Tarefas e kanban

A tela de tarefas funciona como um kanban.

As colunas principais sao:

```text
1 = A Fazer
2 = Em Andamento
3 = Concluido
```

No banco, isso fica no campo:

```text
tarefas.status
```

Quando a tela carrega, o PHP busca as tarefas do projeto:

```php
$tarefas = Tarefa::listarPorProjeto($projetoId);
```

Depois a pagina monta os cards de acordo com o status.

O JavaScript em `assets/js/pages/tarefas.js` cuida das interacoes da tela, como:

- abrir modal;
- enviar formulario;
- mover card;
- chamar actions por `fetch`;
- atualizar a tela depois de criar, editar ou excluir.

Exemplo de chamada `fetch`:

```js
fetch(BASE_URL + '/actions/tarefas/criar.php', {
  method: 'POST',
  body: new FormData(form)
})
```

Isso envia os dados do formulario para o PHP sem precisar que o navegador carregue uma pagina totalmente nova.

---

## 20. Por que usamos redirect depois de POST

O projeto segue o padrao:

```text
Model -> Controller -> Action POST -> redirect
```

Depois de criar, editar ou deletar algo, o sistema redireciona.

Isso evita um problema comum: se o usuario apertar F5 depois de enviar um formulario, o navegador pode tentar enviar o mesmo POST de novo.

Com redirect, o fluxo fica assim:

```text
POST cria tarefa
    -> sistema salva no banco
    -> sistema redireciona para GET /?page=tarefas
```

Assim o usuario recarrega uma pagina comum, nao o envio do formulario.

Esse padrao e conhecido como PRG:

```text
Post -> Redirect -> Get
```

---

## 21. Como o index.php organiza as paginas

O `index.php` funciona como entrada principal do sistema.

Ele le o parametro `page` da URL.

Exemplo:

```text
index.php?page=login
index.php?page=dashboard
index.php?page=projetos
index.php?page=tarefas
```

Dependendo do valor de `page`, ele inclui uma pagina diferente.

Exemplo conceitual:

```php
if ($page === 'login') {
    require 'pages/auth/login.php';
}
```

Esse tipo de estrutura ajuda a manter uma URL unica de entrada e organizar as telas internas.

---

## 22. Frontend: CSS, JS e telas

O frontend e dividido em:

- arquivos PHP dentro de `pages`;
- CSS dentro de `assets/css`;
- JS dentro de `assets/js`;
- imagens e icones dentro de `assets`.

O PHP monta o HTML inicial. O CSS deixa bonito. O JavaScript adiciona comportamento.

Exemplo:

```text
pages/tarefas/index.php
    monta a estrutura da tela

assets/css/pages/tarefas.css
    define o visual da tela

assets/js/pages/tarefas.js
    controla interacoes do kanban
```

Essa separacao ajuda o projeto a nao virar um unico arquivo gigante.

---

## 23. Seguranca basica usada no projeto

O projeto ja usa algumas boas praticas importantes.

### Senhas com hash

O sistema usa:

```php
password_hash()
password_verify()
```

Isso e essencial para nao salvar senha pura.

### SQL preparado

O sistema usa:

```php
$pdo->prepare(...)
$stmt->execute([...])
```

Isso reduz risco de SQL Injection.

### Verificacao de login

Paginas internas usam `auth_guard.php`.

### Verificacao de permissao

Antes de mexer em tarefa, o sistema verifica se o usuario faz parte do projeto.

### Tokens com expiracao

Links de redefinicao de senha e confirmacao de email expiram.

Isso reduz risco se alguem encontrar um link antigo.

---

## 24. Como ler um fluxo no projeto

Quando voce quiser entender uma funcionalidade, procure nesta ordem:

1. Qual tela o usuario esta usando?
2. Para qual action o formulario envia?
3. Qual controller a action chama?
4. Qual model conversa com o banco?
5. Qual tabela e alterada?
6. Para onde o usuario e redirecionado?

Exemplo: criar tarefa.

```text
1. Tela:
   pages/tarefas/index.php

2. JavaScript:
   assets/js/pages/tarefas.js

3. Action:
   actions/tarefas/criar.php

4. Controller:
   controllers/TarefaController.php

5. Model:
   models/Tarefa.php

6. Banco:
   tabela tarefas
```

Esse metodo de leitura funciona para quase tudo no projeto.

---

## 25. Como testar localmente

Para testar localmente:

1. Abrir Apache e MySQL no XAMPP.
2. Criar o banco `escopo_facil`.
3. Importar `database/schema.sql`.
4. Configurar `.env`.
5. Acessar o projeto pelo navegador.

Exemplo de URL local:

```text
http://localhost/pi2-escopo-facil
```

Fluxos importantes para testar:

- criar conta;
- confirmar email;
- fazer login;
- criar projeto;
- abrir tarefas do projeto;
- criar tarefa;
- editar tarefa;
- mover tarefa no kanban;
- excluir tarefa;
- atualizar perfil;
- alterar senha;
- recuperar senha.

---

## 26. Sobre envio de email em ambiente local

Em localhost, o envio real de email pode nao funcionar sem configurar SMTP.

Por isso o projeto usa variaveis de debug, como:

```env
MAIL_DEBUG_TOKEN_URL=true
MAIL_DEBUG_CONFIRMATION_URL=true
```

Quando isso esta ativo, o sistema mostra o link de teste na tela.

Em uma hospedagem real, o ideal e configurar envio de email de verdade e desligar esses debugs:

```env
MAIL_DEBUG_TOKEN_URL=false
MAIL_DEBUG_CONFIRMATION_URL=false
```

---

## 27. O que muda quando hospedar

Ao sair do localhost, sera necessario ajustar:

- `BASE_URL` ou configuracao equivalente da URL do sistema;
- dados reais do banco no `.env`;
- envio de email;
- permissao de arquivos/pastas, se necessario;
- importacao do banco no servidor;
- protecao do `.env` para nao ficar publico.

Tambem e importante garantir que o servidor esteja usando uma versao compativel do PHP.

---

## 28. Boas praticas para continuar o projeto

Algumas dicas para manter o codigo saudavel:

- nao colocar regra importante direto no HTML;
- nao escrever SQL dentro de paginas, se puder colocar no model;
- validar dados no backend mesmo que o frontend tambem valide;
- usar nomes simples e claros;
- fazer commits com mensagens profissionais;
- testar login, cadastro e CRUD depois de mexer em banco;
- nao enviar `.env` real para o repositorio;
- evitar arquivos duplicados ou prototipos misturados com telas oficiais.

---

## 29. Resumo mental do projeto

Se fosse para resumir o Escopo Facil em uma frase tecnica:

```text
E um sistema PHP com MySQL que gerencia usuarios, projetos, membros e tarefas, usando models para banco, controllers para regras, actions para receber requisicoes e pages para exibir telas.
```

Se fosse para resumir como estudante:

```text
O usuario clica na tela, o PHP valida, o model salva ou busca no banco, e o sistema mostra o resultado de volta.
```

Esse e o coracao do projeto.

