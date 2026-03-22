# Documentação de Boas Práticas do Front-End

---

## 1. Estrutura do projeto

```
index.php                  → Layout base (não alterar)
partials/sidebar.php       → Sidebar do sistema (não alterar)
partials/auth-layout.php   → Layout compartilhado das telas de auth (não alterar)
pages/                     → Páginas do sistema (cada dev trabalha aqui)
pages/auth/                → Telas de autenticação (login, cadastro, etc.)
assets/css/reset.css       → Reset CSS (não alterar)
assets/css/variables.css   → Design system / variáveis (não alterar sem consenso)
assets/css/global.css      → Estilos globais — já importa reset.css e variables.css (não alterar sem necessidade)
assets/css/layout.css      → Layout base (não alterar)
assets/css/pages/          → CSS específico de cada página interna (cada dev trabalha aqui)
assets/css/auth/auth.css   → Layout base auth (não alterar)
assets/css/auth/components.css → Componentes reutilizáveis de formulário auth (não alterar)
assets/css/auth/            → CSS específico de cada tela auth (cada dev trabalha aqui)
assets/js/app.js           → JS global (não alterar sem necessidade)
assets/js/pages/           → JS específico de cada página interna (cada dev trabalha aqui)
assets/js/auth/            → JS específico de cada tela auth (cada dev trabalha aqui)
assets/icon/               → Ícones SVG do sistema
assets/images/             → Imagens do sistema
```

---

## 2. Como trabalhar em uma página

- Code o HTML apenas dentro do arquivo da sua página em `pages/`
- Não altere `sidebar.php`
- Não altere `index.php`
- Não altere `layout.css` nem `global.css`
- Não altere as rotas
- Sua área de trabalho é: `pages/suapagina.php` + `assets/css/pages/suapagina.css`

### Telas de autenticação (auth)

- As telas auth usam um layout compartilhado: `partials/auth-layout.php`
- Cada tela auth fica em `pages/auth/` e usa `ob_start()` / `ob_get_clean()` para injetar o formulário no layout
- Inclua no `<head>`: `global.css`, `auth/auth.css`, `auth/components.css` e o CSS específico da tela
- Reutilize os componentes de `components.css` (`.form-group`, `.btn-primary`, `.input-password-wrapper`, etc.)
- Use `pages/auth/cadastro.php` como referência de implementação
- Sua área de trabalho é: `pages/auth/suatela.php` + `assets/css/auth/suatela.css` + `assets/js/auth/suatela.js`

---

## 3. Organização do CSS

- Cada página interna tem seu próprio arquivo CSS em `assets/css/pages/`
- Cada tela auth tem seu próprio arquivo CSS em `assets/css/auth/`
- Estilos específicos da sua tela vão no CSS da sua página
- Nunca coloque estilos de uma página no CSS global
- O CSS global é apenas para layout base e design system

---

## 4. Uso do design system

Sempre use as variáveis CSS definidas em `variables.css`:

```css
/* Cores */
var(--color-primary)         /* #8B0836 — cor principal */
var(--color-background)      /* #F5F5F5 — fundo da área de conteúdo */
var(--color-sidebar-bg)      /* #FFFFFF — fundo da sidebar */
var(--color-text-strong)     /* #030303 — texto principal */
var(--color-text-secondary)  /* #6B6B6B — texto secundário */

/* Tipografia */
var(--font-family)           /* Outfit, sans-serif */
var(--font-size-menu)        /* 16px */

/* Espaçamentos */
var(--spacing-xs)            /* 8px */
var(--spacing-sm)            /* 16px */
var(--spacing-md)            /* 24px */
var(--spacing-lg)            /* 40px */

/* Bordas */
var(--border-radius)         /* 12px */

/* Auth */
var(--auth-bg)               /* #EAEAEC — fundo das telas auth */
var(--auth-padding)          /* 20px */
var(--auth-gap)              /* 20px */
var(--auth-radius)           /* 30px — bordas da imagem e container */
var(--auth-form-bg)          /* #FFFFFF — fundo do container do formulário */
var(--auth-form-max-width)   /* 800px — largura máx do container direito */
```

- Use essas variáveis em vez de valores fixos
- Não invente novas cores ou fontes sem necessidade

---

## 5. Quando algo pode ir para o global

**Pode ir para o global quando:**
- For usado em mais de uma página
- Fizer parte do layout base
- Fizer parte do design system

**Caso contrário, fica no CSS da página.**

---

## 6. Regras de HTML

- Use HTML semântico (`section`, `article`, `nav`, `header`, `main`, etc.)
- Nomes de classes claros e descritivos
- Não use nomes genéricos como `box`, `container2`, `teste`, `div1`
- Exemplos bons: `task-list`, `member-card`, `filter-bar`

---

## 7. JavaScript

- Use JS somente se a página realmente precisar
- JS específico da página vai em `assets/js/pages/suapagina.js`
- Não coloque scripts de página no `app.js` global
- O carregamento do JS da página já é automático pelo `index.php`

---

## 8. Regra final

> Se é específico da tela, fica na tela.
> Só vai para o global se virar padrão do sistema.
