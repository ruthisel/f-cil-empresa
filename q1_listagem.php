<?php
// arquivo: testes/contratos/q1_listagem.php

declare(strict_types=1);

// --- Config de conexão ---
$host = 'localhost';
$db   = 'tu_base';
$user = 'tu_usuario';
$pass = 'tu_password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);

  // --- Query segundo as relações e a regra de nomes de FK ---
  $sql = "
    SELECT
      b.nome              AS nome_banco,
      c.verba             AS verba,
      ct.codigo           AS codigo_contrato,
      ct.data_inclusao    AS data_inclusao,
      ct.valor            AS valor,
      ct.prazo            AS prazo
    FROM Tb_contrato ct
    INNER JOIN Tb_convenio_servico cs ON cs.codigo = ct.convenio_servico
    INNER JOIN Tb_convenio c          ON c.codigo  = cs.convenio
    INNER JOIN Tb_banco b             ON b.codigo  = c.banco
    ORDER BY b.nome, c.verba, ct.data_inclusao, ct.codigo
  ";

  $stmt = $pdo->query($sql);
  $rows = $stmt->fetchAll();

} catch (Throwable $e) {
  http_response_code(500);
  echo "<h1>Error de aplicação</h1>";
  echo "<pre>" . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</pre>";
  exit;
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8"/>
  <title>Listagem de Contratos</title>
  <style>
    body{font-family:system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; padding:24px;}
    table{border-collapse:collapse; width:100%;}
    th, td{border:1px solid #ddd; padding:8px; text-align:left;}
    th{background:#f5f5f5;}
    tr:nth-child(even){background:#fafafa;}
    code{background:#f6f8fa; padding:2px 4px; border-radius:4px;}
  </style>
</head>
<body>
  <h1>Relação de Contratos</h1>
  <table>
    <thead>
      <tr>
        <th>Nome do Banco</th>
        <th>Verba</th>
        <th>Código do Contrato</th>
        <th>Data de Inclusão</th>
        <th>Valor</th>
        <th>Prazo</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($rows)): ?>
        <tr><td colspan="6">Sem registros.</td></tr>
      <?php else: ?>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['nome_banco']) ?></td>
            <td><?= htmlspecialchars($r['verba']) ?></td>
            <td><?= htmlspecialchars((string)$r['codigo_contrato']) ?></td>
            <td><?= htmlspecialchars($r['data_inclusao']) ?></td>
            <td><?= htmlspecialchars(number_format((float)$r['valor'], 2, ',', '.')) ?></td>
            <td><?= htmlspecialchars((string)$r['prazo']) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
