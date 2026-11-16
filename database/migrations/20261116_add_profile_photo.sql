-- GymProgress | migração não destrutiva | executar UMA VEZ no gymprogress_teste
-- Confirme o banco selecionado antes; não executar no banco definitivo.
USE gymprogress_teste;
ALTER TABLE usuarios ADD COLUMN foto_perfil VARCHAR(80) DEFAULT NULL AFTER timezone;
