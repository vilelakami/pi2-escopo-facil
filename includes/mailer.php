<?php

function cabecalhosEmail(): array
{
    $from = $_ENV['MAIL_FROM'] ?? 'no-reply@escopofacil.local';
    $fromName = $_ENV['MAIL_FROM_NAME'] ?? 'Escopo Facil';

    return [
        'From: ' . sprintf('%s <%s>', $fromName, $from),
        'Reply-To: ' . $from,
        'Content-Type: text/plain; charset=UTF-8',
        'X-Mailer: PHP/' . phpversion(),
    ];
}

function enviarEmailRedefinicaoSenha(string $destinatario, string $nome, string $url): bool
{
    $subject = 'Redefinicao de senha - Escopo Facil';

    $message = "Ola, {$nome}.\n\n";
    $message .= "Recebemos uma solicitacao para redefinir sua senha no Escopo Facil.\n\n";
    $message .= "Acesse o link abaixo para criar uma nova senha:\n";
    $message .= $url . "\n\n";
    $message .= "Este link expira em 1 hora. Se voce nao solicitou a redefinicao, ignore este email.\n";

    // Usa mail() nativo; em XAMPP/local a URL de debug cobre a falta de SMTP.
    return mail($destinatario, $subject, $message, implode("\r\n", cabecalhosEmail()));
}

function enviarEmailConfirmacaoCadastro(string $destinatario, string $nome, string $url): bool
{
    $subject = 'Confirme seu email - Escopo Facil';

    $message = "Ola, {$nome}.\n\n";
    $message .= "Sua conta no Escopo Facil foi criada.\n\n";
    $message .= "Confirme seu email acessando o link abaixo:\n";
    $message .= $url . "\n\n";
    $message .= "Este link expira em 24 horas. Se voce nao criou esta conta, ignore este email.\n";

    return mail($destinatario, $subject, $message, implode("\r\n", cabecalhosEmail()));
}
