<?php
// Atualiza apenas o usuário autenticado. Aceita multipart/form-data para que a
// foto e os campos textuais sejam enviados juntos, sempre protegidos por CSRF.
require __DIR__ . '/_bootstrap.php';
exigirMetodo('POST');
exigirCsrf();

if (!manterSessaoAtiva()){
    responderErro('sessao-expirada', [], 403);
}

$usuarioSessao = $_SESSION['usuario'];
$tipo = (string) ($usuarioSessao['tipo'] ?? '');
$cfg = tipoValido($tipo);
$id = (int) ($usuarioSessao['id'] ?? 0);
$atual = buscarUsuario($pdo, $id, $tipo);

if (!$atual){
    responderErro('sessao-expirada', [], 403);
}

$dados = $_POST;
$erros = validarAtualizacaoPerfil($dados, $cfg);
if ($erros){
    responderErro(reset($erros), $erros);
}

$fotoAnterior = $atual['foto'] ?? null;
$novaFoto = $fotoAnterior;
$arquivoNovo = null;

try {
    if (($dados['remover_foto'] ?? '') === '1'){
        $novaFoto = null;
    }

    if (isset($_FILES['foto']) && ($_FILES['foto']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE){
        $arquivo = $_FILES['foto'];
        if (($arquivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK){
            responderErro('foto-envio', ['foto' => 'foto-envio']);
        }
        if ((int) ($arquivo['size'] ?? 0) > 5 * 1024 * 1024){
            responderErro('foto-tamanho', ['foto' => 'foto-tamanho']);
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($arquivo['tmp_name']);
        $extensoes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (!isset($extensoes[$mime])){
            responderErro('foto-formato', ['foto' => 'foto-formato']);
        }

        $diretorio = dirname(__DIR__, 2) . '/View/uploads/avatars';
        if (!is_dir($diretorio) && !mkdir($diretorio, 0755, true) && !is_dir($diretorio)){
            throw new RuntimeException('Não foi possível criar o diretório de avatares.');
        }

        $nomeArquivo = $tipo . '-' . $id . '-' . bin2hex(random_bytes(8)) . '.' . $extensoes[$mime];
        $arquivoNovo = $diretorio . '/' . $nomeArquivo;
        if (!move_uploaded_file($arquivo['tmp_name'], $arquivoNovo)){
            throw new RuntimeException('Não foi possível salvar o avatar.');
        }
        $novaFoto = '../uploads/avatars/' . $nomeArquivo;
    }

    if (!atualizarPerfil($pdo, $id, $tipo, $dados, $novaFoto)){
        if ($arquivoNovo && is_file($arquivoNovo)) unlink($arquivoNovo);
        responderErro('email-em-uso', ['email' => 'email-em-uso']);
    }

    // Só remove a imagem anterior depois que o UPDATE terminou com sucesso.
    if ($fotoAnterior && $fotoAnterior !== $novaFoto && str_starts_with($fotoAnterior, '../uploads/avatars/')){
        $caminhoAnterior = dirname(__DIR__, 2) . '/View/uploads/avatars/' . basename($fotoAnterior);
        if (is_file($caminhoAnterior)) unlink($caminhoAnterior);
    }

    $_SESSION['usuario']['nome'] = trim((string) $dados['nome']);
    $_SESSION['usuario']['email'] = normalizarEmail($dados['email']);

    responder([
        'ok' => true,
        'mensagem' => 'Perfil atualizado com sucesso.',
        'redirect' => '../../View/pages/' . ($tipo === 'profissional' ? 'perfil-profissional.php' : 'perfil.php'),
    ]);
} catch (Throwable $e){
    if ($arquivoNovo && is_file($arquivoNovo)) unlink($arquivoNovo);
    responderServidor($e);
}
