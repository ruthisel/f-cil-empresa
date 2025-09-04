-- arquivo: testes/contratos/q2_agrupado.sql

SELECT
  b.nome                AS nome_banco,   -- (g)
  c.verba               AS verba,        -- (g)
  MIN(ct.data_inclusao) AS data_inclusao_mais_antiga,
  MAX(ct.data_inclusao) AS data_inclusao_mais_nova,
  SUM(ct.valor)         AS soma_valor_contratos
FROM Tb_contrato ct
JOIN Tb_convenio_servico cs ON cs.codigo = ct.convenio_servico
JOIN Tb_convenio c          ON c.codigo  = cs.convenio
JOIN Tb_banco b             ON b.codigo  = c.banco
GROUP BY b.nome, c.verba
ORDER BY b.nome, c.verba;
