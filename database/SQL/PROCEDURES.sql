# -------------------------------------------------------------------------------------------------------------------------
# PROCEDURES PARA A TABELA USUÁRIOS
# -------------------------------------------------------------------------------------------------------------------------

-- PROCEDURE PARA ADICIONAR UM NOVO USUÁRIO

DELIMITER $$
CREATE PROCEDURE add_usuario(IN cpf CHAR(11), IN nome VARCHAR(50), IN sobrenome VARCHAR(100), IN email VARCHAR(100), IN senha VARCHAR(100), IN curl VARCHAR(100), IN img MEDIUMBLOB)
BEGIN
	INSERT INTO usuarios (cpf, nome, sobrenome, email, senha, curl, img_perfil) VALUES (cpf, nome, sobrenome, email, senha, curl, img);
    CALL add_notificacao('Boas vindas!', CONCAT('Seja bem vindo ', nome, ' , por aqui você poderá solicitar e verificar suas reservas em nosso hotel'), cpf);
END
$$ DELIMITER ;

-- PROCEDURE QUE IRÁ VERIFICAR SE UM EMAIL JÁ FOI CADASTRADO

DELIMITER $$
CREATE PROCEDURE usuario_email_existe(IN email_user VARCHAR(100))
BEGIN
	SELECT COUNT(*) AS existe FROM usuarios WHERE email = email_user LIMIT 1;
END
$$ DELIMITER ;

-- PROCEDURE QUE IRÁ VERIFICAR SE UM CPF JÁ FOI CADASTRADO

DELIMITER $$
CREATE PROCEDURE usuario_cpf_existe(IN cpf_user CHAR(11))
BEGIN
	SELECT COUNT(*) AS existe FROM usuarios WHERE cpf = cpf_user LIMIT 1;
END
$$ DELIMITER ;

-- PROCEDURE QUE IRÁ VERIFICAR SE UM CURL JÁ FOI CADASTRADO

DELIMITER $$
CREATE PROCEDURE usuario_curl_existe(IN curl_user VARCHAR(100))
BEGIN
	SELECT COUNT(*) AS existe FROM usuarios WHERE curl = curl_user LIMIT 1;
END
$$ DELIMITER ;

-- PROCEDURE QUE IRÁ EDITAR A SENHA DO USUÁRIO

DELIMITER $$
CREATE PROCEDURE edit_usuario_senha(IN cpf_user CHAR(11), IN senha_user VARCHAR(100))
BEGIN
	UPDATE usuarios SET senha = senha_user WHERE cpf = cpf_user LIMIT 1;
END
$$ DELIMITER ;

-- PROCEDURE QUE IRÁ ATIVAR A CONTA DO USUÁRIO

DELIMITER $$
CREATE PROCEDURE edit_usuario_ativa(IN curl_user VARCHAR(100))
BEGIN
	UPDATE usuarios SET ativo = TRUE WHERE curl = curl_user LIMIT 1;
END
$$ DELIMITER ;

-- PROCEDURE QUE IRÁ ALTERAR A IMGEM DE UM USUÁRIO

DELIMITER $$
CREATE PROCEDURE edit_usuario_img(IN cpf_user CHAR(11), IN img MEDIUMBLOB)
BEGIN
	UPDATE usuarios SET img_perfil = img WHERE cpf = cpf_user LIMIT 1;
END
$$ DELIMITER ;

-- PROCEDURE QUE IRÁ EDITAR UM USUÁRIO

DELIMITER $$
CREATE PROCEDURE edit_usuario(IN cpf_antigo CHAR(11), IN cpf_novo CHAR(11), IN nome VARCHAR(50), IN sobrenome VARCHAR(100), IN email VARCHAR(100), IN status_user ENUM('B', 'D'), IN acesso ENUM('A', 'C'), IN ativo_conta BOOL)
BEGIN
	UPDATE usuarios SET cpf = cpf_novo, nome = nome, sobrenome = sobrenome, email = email, acesso = acesso, status_user = status_user, ativo = ativo_conta WHERE cpf = cpf_antigo LIMIT 1;
END
$$ DELIMITER ;

-- PROCEDURE QUE IRÁ DELETAR O USUÁRIO

DELIMITER $$
CREATE PROCEDURE del_usuario(IN cpf_user CHAR(11))
BEGIN
	UPDATE usuarios SET deleted_at = NOW() WHERE cpf = cpf_user LIMIT 1;
END
$$ DELIMITER ;

-- PROCEDURE QUE IRÁ RECUPERAR UM USUÁRIO DELETADO

DELIMITER $$
CREATE PROCEDURE rec_usuario(IN cpf_user CHAR(11))
BEGIN
	UPDATE usuarios SET deleted_at = NULL WHERE cpf = cpf_user LIMIT 1;
END
$$ DELIMITER ;

# -------------------------------------------------------------------------------------------------------------------------
# PROCEDURES PARA A TABELA NOTIFICAÇÕES
# -------------------------------------------------------------------------------------------------------------------------

-- PROCEDURE PARA ADICIONAR UMA NOVA NOTIFICAÇÃO

DELIMITER $$
CREATE PROCEDURE add_notificacao(IN titulo VARCHAR(100), IN mensagem MEDIUMTEXT, IN cpf_usuario CHAR(11))
BEGIN
	INSERT INTO notificacoes (titulo, mensagem, cpf_usuario) VALUES (titulo, mensagem, cpf_usuario);
END
$$ DELIMITER ;

-- PROCEDURE PARA EDITAR A VISUALIZAÇÃO DA NOTIFICAÇÃO

DELIMITER $$
CREATE PROCEDURE edit_notificacao_visualizacao(IN id_not INT UNSIGNED, IN cpf CHAR(11), IN vis BOOL)
BEGIN
	UPDATE notificacoes SET visualizado = vis WHERE id_notificacao = id_not AND cpf_usuario = cpf LIMIT 1;
END
$$ DELIMITER ;

-- PROCEDURE PARA DELETAR A NOTIFICAÇÃO

DELIMITER $$
CREATE PROCEDURE del_notificacao(IN id_not INT UNSIGNED)
BEGIN
	DELETE FROM notificacoes WHERE id_notificacao = id_not LIMIT 1;
END
$$ DELIMITER ;

# -------------------------------------------------------------------------------------------------------------------------
# PROCEDURES PARA A TABELA QUARTOS
# -------------------------------------------------------------------------------------------------------------------------

-- PROCEDURE PARA ADICIONAR UM NOVO QUARTO

DELIMITER $$
CREATE PROCEDURE add_quarto(IN numero_quarto SMALLINT UNSIGNED, IN andar TINYINT UNSIGNED, IN preco_hora DECIMAL(10, 2) UNSIGNED, IN id_tipo_quarto INT UNSIGNED)
BEGIN
	INSERT INTO quartos (numero_quarto, andar, preco_hora, id_tipo_quarto) VALUES (numero_quarto, andar, preco_hora, id_tipo_quarto);
END
$$ DELIMITER ;

-- PROCEDURE QUE IRÁ VERIFICAR SE UM NÚMERO DE QUARTO JÁ FOI CADASTRADO

DELIMITER $$
CREATE PROCEDURE quarto_numero_existe(IN numero SMALLINT UNSIGNED)
BEGIN
	SELECT COUNT(*) AS existe FROM quartos WHERE numero_quarto = numero LIMIT 1;
END
$$ DELIMITER ;

-- PROCEDURE PARA EDITAR UM QUARTO

DELIMITER $$
CREATE PROCEDURE edit_quarto(IN numero_quarto_atual SMALLINT UNSIGNED, IN numero_quarto_editado SMALLINT UNSIGNED, IN andar TINYINT UNSIGNED, IN preco_hora DECIMAL(10, 2) UNSIGNED, IN status_q ENUM('D', 'I'), IN id_tipo_quarto INT UNSIGNED)
BEGIN
	UPDATE quartos SET numero_quarto = numero_quarto_editado, andar = andar, preco_hora = preco_hora, status_quarto = status_q, id_tipo_quarto = id_tipo_quarto WHERE numero_quarto = numero_quarto_atual LIMIT 1;
END
$$ DELIMITER ;

-- PROCEDURE PARA DELETAR UM QUARTO

DELIMITER $$
CREATE PROCEDURE del_quarto(IN numero SMALLINT UNSIGNED)
BEGIN
	DELETE FROM quartos WHERE numero_quarto = numero LIMIT 1;
END
$$ DELIMITER ;

# -------------------------------------------------------------------------------------------------------------------------
# PROCEDURES PARA AS RESERVAS
# -------------------------------------------------------------------------------------------------------------------------

-- PROCEDURE PARA ADICIONAR UMA NOVA RESERVA

DELIMITER $$
CREATE PROCEDURE add_reserva(IN preco_hora DECIMAL(10, 2) UNSIGNED, IN data_reserva TIMESTAMP, IN data_encerrar TIMESTAMP, IN numero_quarto SMALLINT UNSIGNED, IN cpf CHAR(11))
BEGIN
	INSERT INTO reservas (preco_hora, data_reserva, data_encerrar, numero_quarto, cpf_usuario) VALUES (preco_hora, data_reserva, data_encerrar, numero_quarto, cpf);
END
$$ DELIMITER ;

-- PROCEDURE PARA EDITAR UMA NOVA RESERVA

DELIMITER $$
CREATE PROCEDURE edit_reserva(id SMALLINT UNSIGNED, IN preco DECIMAL(10, 2) UNSIGNED, IN data_res TIMESTAMP, IN data_enc TIMESTAMP, IN status_r ENUM('R', 'P', 'C'), IN numero SMALLINT UNSIGNED, IN cpf CHAR(11))
BEGIN
	UPDATE reservas SET preco_hora = preco, data_reserva = data_res, data_encerrar = data_enc, status_reserva = status_r, numero_quarto = numero, cpf_usuario = cpf WHERE id_reserva = id LIMIT 1;
END
$$ DELIMITER ;

-- PROCEDURE PARA EDITAR O STATUS DE UMA RESERVA

DELIMITER $$
CREATE PROCEDURE edit_status_reserva(IN id SMALLINT UNSIGNED, IN status_r ENUM('R', 'P', 'C'))
BEGIN
	UPDATE reservas SET status_reserva = status_r WHERE id_reserva = id LIMIT 1;
END
$$ DELIMITER ;

-- PROCEDURE QUE BUSCA O TOTAL A PAGAR DE UMA RESERVA

DELIMITER $$
CREATE PROCEDURE total_reserva(IN id SMALLINT UNSIGNED)
BEGIN
	SELECT (GREATEST(0, timestampdiff(HOUR, data_reserva, LEAST(data_encerrar, CURRENT_TIMESTAMP))) * preco_hora) AS total FROM reservas WHERE id_reserva = id LIMIT 1;
END
$$ DELIMITER ;

-- PROCEDURE QUE IRÁ FINALIZAR UMA RESERVA

DELIMITER $$
CREATE PROCEDURE finaliza_reserva(IN id SMALLINT UNSIGNED)
BEGIN
	DECLARE total DECIMAL(10, 2) DEFAULT 0;
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
    END;

	START TRANSACTION;
		UPDATE reservas SET data_encerrar = CURRENT_TIMESTAMP WHERE id_reserva = id LIMIT 1;
    
		SELECT (GREATEST(0, timestampdiff(HOUR, data_reserva, data_encerrar)) * preco_hora) INTO total FROM reservas WHERE id_reserva = id LIMIT 1;
		
        IF total > 0 THEN
			CALL edit_status_reserva(id, 'P');
		ELSE
			CALL edit_status_reserva(id, 'C');
		END IF;
    COMMIT;
END
$$ DELIMITER ;

# -------------------------------------------------------------------------------------------------------------------------
# PROCEDURES PARA OS PEDIDOS DE RESERVAS
# -------------------------------------------------------------------------------------------------------------------------

-- PROCEDURE PARA ADICIONAR UM NOVO PEDIDO DE RESERVA

DELIMITER $$
CREATE PROCEDURE add_pedido_reserva(IN preco_hora DECIMAL(10, 2) UNSIGNED, IN data_reserva TIMESTAMP, IN data_encerrar TIMESTAMP, IN numero_quarto SMALLINT UNSIGNED, IN cpf CHAR(11))
BEGIN
	INSERT INTO pedidos_reserva (preco_hora, data_reserva, data_encerrar, numero_quarto, cpf_usuario) VALUES (preco_hora, data_reserva, data_encerrar, numero_quarto, cpf);
END
$$ DELIMITER ;

-- PROCEDURE QUE IRÁ VERIFICAR SE UM USUÁRIO JÁ FEZ UMA SOLICITAÇÃO PARA UM QUARTO

DELIMITER $$
CREATE PROCEDURE pedido_reserva_existe(IN numero SMALLINT UNSIGNED, IN cpf CHAR(11))
BEGIN
	SELECT COUNT(*) AS existe FROM pedidos_reserva WHERE numero_quarto = numero AND cpf_usuario = cpf AND status_pedido = 'P' LIMIT 1;
END
$$ DELIMITER ;

-- PROCEDURE QUE IRÁ DELETAR UMA SOLICITAÇÃO DE RESERVA

DELIMITER $$
CREATE PROCEDURE del_pedido_reserva(IN id SMALLINT UNSIGNED)
BEGIN
	DELETE FROM pedidos_reserva WHERE id_pedido_reserva = id LIMIT 1;
END
$$ DELIMITER ;

-- PROCEDURE QUE FINALIZA O PEDIDO DA RESERVA

DELIMITER $$
CREATE PROCEDURE finaliza_pedido_reserva(IN id SMALLINT UNSIGNED, IN status_p ENUM('P', 'N', 'A'))
BEGIN
    -- BUSCANDO OS DADOS DO PEDIDO
    
    DECLARE cpf CHAR(11);
    DECLARE numero SMALLINT UNSIGNED;
    DECLARE data_res TIMESTAMP;
    DECLARE data_enc TIMESTAMP;
    DECLARE preco DECIMAL(7, 2) UNSIGNED;
    
    SELECT cpf_usuario INTO cpf FROM pedidos_reserva WHERE id_pedido_reserva = id LIMIT 1;
    SELECT numero_quarto INTO numero FROM pedidos_reserva WHERE id_pedido_reserva = id LIMIT 1;
    SELECT data_reserva INTO data_res FROM pedidos_reserva WHERE id_pedido_reserva = id LIMIT 1;
    SELECT data_encerrar INTO data_enc FROM pedidos_reserva WHERE id_pedido_reserva = id LIMIT 1;
    SELECT preco_hora INTO preco FROM pedidos_reserva WHERE id_pedido_reserva = id LIMIT 1;
    
    -- ATUALIZANDO STATUS DO PEDIDO
    
    UPDATE pedidos_reserva SET status_pedido = status_p WHERE id_pedido_reserva = id LIMIT 1;

	IF status_p != 'A' THEN
		IF status_p = 'N' THEN
			CALL add_notificacao('Seu pedido de reserva foi negado!', CONCAT('Seu pedido de reserva para o quarto ', numero ,' foi negado por algum administrador!, Talvéz seja bem provável que a data que você quis reservar já estive-se reservada para outro cliente!'), cpf);
		END IF;
	ELSE
		CALL add_reserva(preco, data_res, data_enc, numero, cpf);
		CALL add_notificacao('Seu pedido de reserva foi aceito!', CONCAT('Seu pedido de reserva para o quarto ', numero ,' foi aceito por algum administrador!, Por favor pedimos que você compareça ao nosso hotel no dia e horario marcado na reserva, caso contrário sua reserva será cancelada!'), cpf);
    END IF;
END
$$ DELIMITER ;

# -------------------------------------------------------------------------------------------------------------------------
# PROCEDURES PARA OS PAGAMENTOS
# -------------------------------------------------------------------------------------------------------------------------

-- PROCEDURE QUE IRÁ ADICIONAR UM PAGAMENTO DE UMA RESERVA

DELIMITER $$
CREATE PROCEDURE add_pagamento(IN pay_id VARCHAR(50), IN valor DECIMAL(10, 2) UNSIGNED, IN status_p VARCHAR(50), IN id_res SMALLINT UNSIGNED)
BEGIN
    INSERT INTO pagamentos (payment_id, valor_total, status_pagamento, id_reserva) VALUES (pay_id, valor, status_p, id_res);
END
$$ DELIMITER ;

-- PROCEDURE QUE IRÁ EDITAR O STATUS DE UM PAGAMENTO DE UMA RESERVA

DELIMITER $$
CREATE PROCEDURE edit_status_pagamento(IN pay_id VARCHAR(50), IN status_p VARCHAR(50))
BEGIN
    UPDATE pagamentos SET status_pagamento = status_p WHERE payment_id = pay_id LIMIT 1;
END
$$ DELIMITER ;