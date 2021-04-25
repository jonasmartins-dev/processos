CREATE DATABASE IF NOT EXISTS `processos`;
USE `processos`;

CREATE TABLE IF NOT EXISTS `advogados` (
  `id_advogado` int(100) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) DEFAULT NULL,
  `oab` varchar(100) DEFAULT NULL,
  `cpf` varchar(100) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `perfil` enum('Advogado','Gestor') DEFAULT 'Advogado',
  `criacao_dt` timestamp NULL DEFAULT current_timestamp(),
  `atualizacao_dt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_advogado`)
) ENGINE=InnoDB;

INSERT INTO `advogados` (`id_advogado`, `nome`, `oab`, `cpf`, `email`, `senha`, `perfil`)
VALUES
	(1, 'Gestor', 'Gestor', '02129316522', 'gestor@gestor.com', 'f7ff3c8ea53e68bd308e6deed6a01a98c7886308', 'Gestor');

CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int(100) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) DEFAULT NULL,
  `cpf` varchar(100) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `nascimento_dt` date DEFAULT NULL,
  `criacao_dt` timestamp NULL DEFAULT current_timestamp(),
  `atualizacao_dt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `processos` (
  `id_processo` int(100) NOT NULL AUTO_INCREMENT,
  `num_proc` int(100) DEFAULT NULL,
  `area` enum('Administrativo','Civil','Consumidor','Criminal','Eleitoral','Trabalhista','Previdenciário') NOT NULL,
  `advogado_id` int(100) DEFAULT NULL,
  `cliente_id` int(100) DEFAULT NULL,
  `criacao_dt` timestamp NULL DEFAULT current_timestamp(),
  `atualizacao_dt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_processo`),
  KEY `FK_advogado` (`advogado_id`),
  KEY `FK_cliente` (`cliente_id`),
  KEY `num_proc` (`num_proc`),
  CONSTRAINT `FK_advogado` FOREIGN KEY (`advogado_id`) REFERENCES `advogados` (`id_advogado`),
  CONSTRAINT `FK_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id_cliente`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `movimentacoes` (
  `id_movimentacao` int(100) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(200) DEFAULT NULL,
  `processo_num` int(100) DEFAULT NULL,
  `criacao_dt` timestamp NULL DEFAULT current_timestamp(),
  `atualizacao_dt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_movimentacao`),
  KEY `FK_processo` (`processo_num`),
  CONSTRAINT `FK_processo` FOREIGN KEY (`processo_num`) REFERENCES `processos` (`num_proc`)
) ENGINE=InnoDB;