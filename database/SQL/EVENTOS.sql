SET GLOBAL event_scheduler = ON;

-- EVENTO QUE FINALIZA RESERVAS QUE PASSARAM OU CHEGARAM NA DATA PREVISTA PARA ENCERRAMENTO

DELIMITER $$
CREATE EVENT finaliza_reservas ON SCHEDULE EVERY 1 MINUTE 
STARTS CURRENT_TIMESTAMP ON COMPLETION PRESERVE DO
BEGIN
	UPDATE reservas SET status_reserva = 'P' WHERE CURRENT_TIMESTAMP >= data_encerrar AND status_reserva = 'R' AND TIMESTAMPDIFF(HOUR, data_reserva, data_encerrar) > 0;
    UPDATE reservas SET status_reserva = 'C' WHERE CURRENT_TIMESTAMP >= data_encerrar AND status_reserva = 'R' AND TIMESTAMPDIFF(HOUR, data_reserva, data_encerrar) <= 0;
END
$$ DELIMITER ;