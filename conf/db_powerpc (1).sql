-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19/05/2025 às 14:44
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

CREATE DATABASE IF NOT EXISTS `db_powerpc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_powerpc`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
 /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
 /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 /*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_powerpc`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `data_pedido` datetime NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `status` enum('pendente','processando','enviado','entregue','cancelado') NOT NULL DEFAULT 'pendente',
  `endereco_entrega` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_itens`
--

CREATE TABLE `pedido_itens` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `estoque` int(11) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `categoria` varchar(50) NOT NULL,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `email` varchar(110) NOT NULL,
  `senha` varchar(200) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `data_nasci` date NOT NULL,
  `endereco` varchar(45) NOT NULL,
  `tipo` enum('admin','usuario') DEFAULT 'usuario',
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimo_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `cpf`, `email`, `senha`, `telefone`, `data_nasci`, `endereco`, `tipo`, `data_criacao`, `ultimo_login`) VALUES
(15, 'jik', '9999999', 'fdfj@jfdf', '', '9876543', '2000-12-11', 'qnp 15', 'usuario', '2025-05-17 19:33:34', NULL),
(21, 'Felipe', '888888888', 'sfdfdd@wee', '$2y$10$RDBbbak3ezEiPr7q8e.R5u6eDhkfvpHSm3cRqmAZ8miwb/huXab42', '6199999998', '2000-12-12', 'qnp 20', 'usuario', '2025-05-17 19:33:34', NULL),
(22, 'yure', '123.456.123-99', 'lkj@email.com', '$2y$10$pbx8VhnE0c44dpPdzGv8UOkc77mz3IVcv3W9BfgXMCkdMBmojH3Kq', '61994306868', '2000-12-12', 'CEILANDIA SUL (CEILANDIA)', 'usuario', '2025-05-17 19:33:34', NULL),
(28, 'BEATRIZ KAREN ROCHA RODRIGUES', '66666666', 'assdd@defef', '', '61994306868', '2000-12-12', 'Df', 'usuario', '2025-05-17 19:33:34', NULL),
(30, 'BEATRIZ KAREN ROCHA RODRIGUES', '098.098.099-12', 'asdf@mail.com', '$2y$10$39BrFPBRZQyZ9NocaNFwcOHkfJ3TmKSkBBoiwrR4ZY.F.hFS5Ak.G', '61994306868', '2000-12-12', 'qnp 20', 'admin', '2025-05-17 19:33:34', NULL),
(31, 'yure', '11209876590', 'yure123@email.com', '', '1234', '2005-12-11', 'qnp 30', 'usuario', '2025-05-17 19:33:34', NULL),
(32, 'BEATRIZ KAREN ROCHA RODRIGUES', '09809809912', 'karentris1113@gmail.com', '$2y$10$2BmCuvXKHSXzTr.HFXs7DO2oEGw865BATYlX6aP1zAhzAnDAD702.', '61994306868', '2000-12-11', 'Df', 'usuario', '2025-05-17 19:33:34', NULL),
(33, 'Admin PowerPC', '123.456.789-00', 'admin@powerpc.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(11) 99999-9999', '1990-01-01', 'Endereço Admin', 'admin', '2025-05-17 19:33:34', NULL);

--
-- Índices para tabelas despejadas
--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `cpf_UNIQUE` (`cpf`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD CONSTRAINT `pedido_itens_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `pedido_itens_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

-- --------------------------------------------------------
-- Inserção de administrador
-- --------------------------------------------------------

INSERT INTO `administradores` (`nome`, `email`, `senha`, `nivel_acesso`, `criado_em`, `atualizado_em`)
VALUES ('Admin Master', 'adminmaster@powerpc.com', '$2y$10$KbmiNRn/N9vlWdXGusPWAu1Evaz8J3bmtFqa8XU3H0zcbgfzTMoFC', 'superadmin', NOW(), NULL);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
 /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
 /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
