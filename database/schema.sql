-- GymProgress schema
-- Desenvolvimento e testes: banco padrão automatizado
CREATE DATABASE IF NOT EXISTS gymprogress_teste
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE gymprogress_teste;

-- GymProgress | Schema inicial de desenvolvimento | MySQL 8.4.3
-- Modelagem das 12 tabelas APROVADA para testes.
-- Validado pelo usuario no phpMyAdmin (gymprogress_teste): criacao das 12 tabelas,
-- 57 restricoes, uma unica sessao ativa, FK de fichas e exclusao em cascata.
-- NAO e validacao integral das regras de negocio (dependencias do backend).
-- AMBIENTE ATUAL: apenas o banco gymprogress_teste (desenvolvimento e testes).
-- IMPORTANTE: selecione manualmente gymprogress_teste no phpMyAdmin.
-- Confirme com SELECT DATABASE() antes de executar o schema.
-- Use em base vazia; nao execute novamente em base populada esperando migracoes.
-- Este arquivo NAO cria/seleciona banco, nem apaga dados existentes.
-- NAO crie gymprogress (base definitiva) antes de concluir todos os testes.
-- CREATE TABLE IF NOT EXISTS NAO atualiza tabelas existentes.
-- Datas DATETIME devem ser fornecidas em UTC pelo backend.

CREATE TABLE IF NOT EXISTS usuarios (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  senha_hash VARCHAR(255) NOT NULL,
  timezone VARCHAR(64) NOT NULL DEFAULT 'America/Sao_Paulo',
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_usuarios_email (email),
  CONSTRAINT ck_usuario_nome CHECK (CHAR_LENGTH(TRIM(nome)) > 0)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS recuperacao_senhas (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id BIGINT UNSIGNED NOT NULL,
  token_hash CHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  expira_em DATETIME NOT NULL,
  utilizado_em DATETIME NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_rec_token (token_hash),
  KEY idx_rec_usuario_expira (usuario_id, expira_em),
  CONSTRAINT fk_rec_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON UPDATE RESTRICT ON DELETE CASCADE,
  CONSTRAINT ck_rec_datas CHECK (expira_em > criado_em AND (utilizado_em IS NULL OR utilizado_em >= criado_em))
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS fichas (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id BIGINT UNSIGNED NOT NULL,
  nome VARCHAR(120) NOT NULL,
  descricao TEXT NULL,
  grupo_muscular VARCHAR(80) NULL,
  status ENUM('ativa','arquivada') NOT NULL DEFAULT 'ativa',
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_ficha_usuario (id, usuario_id), -- permite FK de sessao associada ao mesmo usuario da ficha
  KEY idx_fichas_usuario_status (usuario_id, status),
  CONSTRAINT fk_fichas_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT ck_ficha_nome CHECK (CHAR_LENGTH(TRIM(nome)) > 0)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS exercicios (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id BIGINT UNSIGNED NULL,  -- NULL = catalogo geral
  nome VARCHAR(150) NOT NULL,
  grupo_muscular ENUM('Peito','Costas','Pernas','Ombros','Bracos','Abdomen','Outros') NOT NULL,
  descricao TEXT NULL,
  imagem_url VARCHAR(500) NULL,
  video_url VARCHAR(500) NULL,
  status ENUM('ativo','arquivado') NOT NULL DEFAULT 'ativo',
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_exercicios_catalogo (usuario_id, status, grupo_muscular),
  KEY idx_exercicios_nome (nome),
  CONSTRAINT fk_exercicios_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT ck_exercicio_nome CHECK (CHAR_LENGTH(TRIM(nome)) > 0)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS ficha_exercicios (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  ficha_id BIGINT UNSIGNED NOT NULL,
  exercicio_id BIGINT UNSIGNED NOT NULL,
  ordem INT UNSIGNED NOT NULL,
  series_planejadas SMALLINT UNSIGNED NOT NULL,
  reps_min SMALLINT UNSIGNED NOT NULL,
  reps_max SMALLINT UNSIGNED NOT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_ficha_exercicio (ficha_id, exercicio_id),
  KEY idx_ficha_exercicios_ordem (ficha_id, ordem),
  KEY idx_ficha_exercicios_exercicio (exercicio_id),
  CONSTRAINT fk_fe_ficha FOREIGN KEY (ficha_id) REFERENCES fichas(id)
    ON UPDATE RESTRICT ON DELETE CASCADE,
  CONSTRAINT fk_fe_exercicio FOREIGN KEY (exercicio_id) REFERENCES exercicios(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT ck_fe_series CHECK (series_planejadas > 0),
  CONSTRAINT ck_fe_reps CHECK (reps_min > 0 AND reps_max >= reps_min),
  CONSTRAINT ck_fe_ordem CHECK (ordem > 0)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sessoes (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id BIGINT UNSIGNED NOT NULL,
  ficha_id BIGINT UNSIGNED NOT NULL,
  nome_ficha_snapshot VARCHAR(120) NOT NULL,
  status ENUM('em_andamento','finalizada') NOT NULL DEFAULT 'em_andamento',
  iniciada_em DATETIME NOT NULL,
  finalizada_em DATETIME NULL,
  duracao_segundos INT UNSIGNED NULL,
  observacoes TEXT NULL,
  -- MySQL: UNIQUE permite varios NULL, mas apenas um usuario_ativo_id preenchido.
  usuario_ativo_id BIGINT UNSIGNED GENERATED ALWAYS AS (
    CASE WHEN status = 'em_andamento' THEN usuario_id ELSE NULL END
  ) STORED,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_sessao_ativa_usuario (usuario_ativo_id),
  KEY idx_sessoes_usuario_status_data (usuario_id, status, iniciada_em),
  KEY idx_sessoes_ficha_usuario (ficha_id, usuario_id),
  CONSTRAINT fk_sessoes_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_sessoes_ficha_proprietario FOREIGN KEY (ficha_id, usuario_id)
    REFERENCES fichas(id, usuario_id) ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT ck_sessoes_tempos CHECK (
    (status = 'em_andamento' AND finalizada_em IS NULL AND duracao_segundos IS NULL)
    OR (status = 'finalizada' AND finalizada_em IS NOT NULL AND duracao_segundos IS NOT NULL
        AND finalizada_em >= iniciada_em)
  )
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sessao_exercicios (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  sessao_id BIGINT UNSIGNED NOT NULL,
  exercicio_id BIGINT UNSIGNED NOT NULL,
  nome_exercicio_snapshot VARCHAR(150) NOT NULL,
  grupo_muscular_snapshot VARCHAR(80) NOT NULL,
  ordem INT UNSIGNED NOT NULL,
  series_planejadas_snapshot SMALLINT UNSIGNED NOT NULL,
  reps_min_snapshot SMALLINT UNSIGNED NOT NULL,
  reps_max_snapshot SMALLINT UNSIGNED NOT NULL,
  status ENUM('pendente','parcialmente_realizado','concluido','concluido_antecipadamente') NOT NULL DEFAULT 'pendente',
  concluido_em DATETIME NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_se_sessao_ordem (sessao_id, ordem),
  KEY idx_se_exercicio (exercicio_id),
  CONSTRAINT fk_se_sessao FOREIGN KEY (sessao_id) REFERENCES sessoes(id)
    ON UPDATE RESTRICT ON DELETE CASCADE,
  CONSTRAINT fk_se_exercicio FOREIGN KEY (exercicio_id) REFERENCES exercicios(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT ck_se_prescricao CHECK (series_planejadas_snapshot > 0
    AND reps_min_snapshot > 0 AND reps_max_snapshot >= reps_min_snapshot AND ordem > 0)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS series_realizadas (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  sessao_exercicio_id BIGINT UNSIGNED NOT NULL,
  numero_serie SMALLINT UNSIGNED NOT NULL,
  origem ENUM('planejada','extra') NOT NULL DEFAULT 'planejada',
  repeticoes SMALLINT UNSIGNED NULL,
  carga_kg DECIMAL(7,2) NULL,
  concluida BOOLEAN NOT NULL DEFAULT FALSE,
  concluida_em DATETIME NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_serie_numero (sessao_exercicio_id, numero_serie),
  CONSTRAINT fk_series_sessao_exercicio FOREIGN KEY (sessao_exercicio_id)
    REFERENCES sessao_exercicios(id) ON UPDATE RESTRICT ON DELETE CASCADE,
  CONSTRAINT ck_serie_numero CHECK (numero_serie > 0),
  CONSTRAINT ck_serie_reps CHECK (repeticoes IS NULL OR repeticoes > 0),
  CONSTRAINT ck_serie_carga CHECK (carga_kg IS NULL OR carga_kg >= 0),
  CONSTRAINT ck_serie_concluida CHECK (concluida IN (0,1) AND
    (concluida = 0 OR (repeticoes IS NOT NULL AND repeticoes > 0)))
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS historico_alteracoes (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  sessao_id BIGINT UNSIGNED NOT NULL,
  usuario_id BIGINT UNSIGNED NOT NULL,
  tipo_operacao VARCHAR(60) NOT NULL,
  dados_anteriores JSON NOT NULL,
  dados_novos JSON NOT NULL,
  alterado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_ha_sessao_data (sessao_id, alterado_em),
  KEY idx_ha_usuario (usuario_id),
  CONSTRAINT fk_ha_sessao FOREIGN KEY (sessao_id) REFERENCES sessoes(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_ha_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS metas (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id BIGINT UNSIGNED NOT NULL,
  exercicio_id BIGINT UNSIGNED NULL,
  tipo ENUM('carga','frequencia_semanal','sessoes_acumuladas') NOT NULL,
  titulo VARCHAR(150) NOT NULL,
  valor_alvo DECIMAL(10,2) NOT NULL,
  inicio_em DATE NOT NULL,
  prazo_em DATE NULL,
  status_base ENUM('ativa','arquivada') NOT NULL DEFAULT 'ativa',
  atingida_em DATETIME NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_metas_usuario_status_prazo (usuario_id, status_base, prazo_em),
  KEY idx_metas_exercicio (exercicio_id),
  CONSTRAINT fk_metas_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_metas_exercicio FOREIGN KEY (exercicio_id) REFERENCES exercicios(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT ck_metas_alvo CHECK (valor_alvo > 0),
  CONSTRAINT ck_metas_tipo CHECK (
    (tipo = 'carga' AND exercicio_id IS NOT NULL)
    OR (tipo IN ('frequencia_semanal','sessoes_acumuladas') AND exercicio_id IS NULL
        AND valor_alvo = FLOOR(valor_alvo))
  ),
  CONSTRAINT ck_metas_prazo CHECK (prazo_em IS NULL OR prazo_em >= inicio_em),
  CONSTRAINT ck_metas_titulo CHECK (CHAR_LENGTH(TRIM(titulo)) > 0)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS conquistas (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  codigo VARCHAR(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  nome VARCHAR(150) NOT NULL,
  descricao TEXT NULL,
  tipo ENUM('recorde_pessoal','recorde_semanal','frequencia','marco_sessoes') NOT NULL,
  parametros JSON NULL,
  ativo BOOLEAN NOT NULL DEFAULT TRUE,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_conquistas_codigo (codigo),
  CONSTRAINT ck_conquistas_ativo CHECK (ativo IN (0,1))
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS usuario_conquistas (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id BIGINT UNSIGNED NOT NULL,
  conquista_id BIGINT UNSIGNED NOT NULL,
  exercicio_id BIGINT UNSIGNED NULL,
  periodo_inicio DATE NULL,
  periodo_fim DATE NULL,
  chave_ocorrencia VARCHAR(180) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  valor_atingido DECIMAL(10,2) NULL,
  conquistada_em DATETIME NOT NULL,
  valida BOOLEAN NOT NULL DEFAULT TRUE,
  aviso_visualizado_em DATETIME NULL,
  aviso_dispensado_em DATETIME NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_uc_usuario_ocorrencia (usuario_id, chave_ocorrencia),
  KEY idx_uc_usuario_validade (usuario_id, valida),
  KEY idx_uc_conquista (conquista_id),
  KEY idx_uc_exercicio (exercicio_id),
  CONSTRAINT fk_uc_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_uc_conquista FOREIGN KEY (conquista_id) REFERENCES conquistas(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_uc_exercicio FOREIGN KEY (exercicio_id) REFERENCES exercicios(id)
    ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT ck_uc_valida CHECK (valida IN (0,1)),
  CONSTRAINT ck_uc_periodo CHECK (periodo_inicio IS NULL OR periodo_fim IS NULL OR periodo_fim >= periodo_inicio)
) ENGINE=InnoDB;

-- O SQL acima implementa ESTRUTURA de tabelas, NAO as regras transacionais a seguir:
-- 1) Apenas sessao em andamento pode ser descartada, apos confirmacao e verificacao de dono.
-- 2) Sessao finalizada deve manter ao menos uma serie concluida; verificar sob lock/transacao.
-- 3) Correcao historica: alteracoes + auditoria + recalculo de indicadores/metas/conquistas.
-- 4) Nao permitir fichas/exercicios privados de outros usuarios; validar propriedades.
-- 5) Duracao deve ser recalculada apos corrigir horarios, sem arredondamento incorreto.
-- 6) Atualizar situacao de exercicio de acordo com series realmente concluidas.
-- 7) Evitar repeticao de notificacao de conquista depois de recalculo.
-- 8) Recomenda-se conta de app sem permissao de DROP/TRUNCATE, e rotinas de teste.
-- NOTA: nenhuma query SQL foi executada neste ambiente.
