SELECT senhaschamadas.id_senhaschamadas,
senhaschamadas.des_local,
senhaschamadas.log_chamada,
senhaschamadas.log_rechamado,
IF(senhaschamadas.id_senha IS NOT null, (SELECT senhas.num_sequencia FROM senhas WHERE senhas.id_senha=senhaschamadas.id_senha ),IF(senhaschamadas.id_chamadaolostech IS NOT null, (SELECT paciente.nome from paciente where paciente.id=(SELECT chamadaolostech.id_paciente FROM chamadaolostech WHERE chamadaolostech.id=senhaschamadas.id_chamadaolostech)), null)) as num_sequencia,
IF(senhaschamadas.id_senha IS NOT null, (SELECT servico.des_descricao FROM servico WHERE servico.id_servico = (SELECT senhas.id_servico from senhas WHERE senhas.id_senha=senhaschamadas.id_senha)),null) as des_descricao,
IF(senhaschamadas.id_senha IS NOT null, (SELECT servico.sigla FROM servico WHERE servico.id_servico = (SELECT senhas.id_servico from senhas WHERE senhas.id_senha=senhaschamadas.id_senha)),null) as sigla,
IF(senhaschamadas.id_senha IS NOT null, (SELECT CONCAT('#',servico.des_cor) FROM servico WHERE servico.id_servico = (SELECT senhas.id_servico from senhas WHERE senhas.id_senha=senhaschamadas.id_senha)),IF(senhaschamadas.id_senha IS null, (SELECT CONCAT('#',tipoestacao.cor) FROM tipoestacao WHERE tipoestacao.id=(SELECT estacoes.id_tipoestacao FROM estacoes WHERE estacoes.id=(SELECT chamadaolostech.id_estacoes FROM chamadaolostech WHERE chamadaolostech.id=senhaschamadas.id_chamadaolostech))), null)) as cor
FROM senhaschamadas
WHERE senhaschamadas.log_chamada=0
ORDER BY senhaschamadas.id_senhaschamadas ASC
LIMIT 1