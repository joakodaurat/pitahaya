/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : hombres

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-08-14 13:12:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for aplicacion
-- ----------------------------
DROP TABLE IF EXISTS `aplicacion`;
CREATE TABLE `aplicacion` (
  `app_id` int(11) NOT NULL AUTO_INCREMENT,
  `app_nombre` varchar(45) NOT NULL,
  `app_baja` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`app_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of aplicacion
-- ----------------------------
INSERT INTO `aplicacion` VALUES ('5', 'Estadio 7', '0');

-- ----------------------------
-- Table structure for area
-- ----------------------------
DROP TABLE IF EXISTS `area`;
CREATE TABLE `area` (
  `area_id` int(11) NOT NULL AUTO_INCREMENT,
  `area_nombre` varchar(45) DEFAULT NULL,
  `area_baja` tinyint(4) NOT NULL,
  PRIMARY KEY (`area_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of area
-- ----------------------------
INSERT INTO `area` VALUES ('1', 'Ventas', '0');
INSERT INTO `area` VALUES ('5', 'Sistemas', '0');

-- ----------------------------
-- Table structure for banco
-- ----------------------------
DROP TABLE IF EXISTS `banco`;
CREATE TABLE `banco` (
  `banco_id` int(2) NOT NULL,
  `banco_nombre` varchar(256) NOT NULL,
  `banco_baja` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`banco_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of banco
-- ----------------------------
INSERT INTO `banco` VALUES ('1', 'Frances', '0');
INSERT INTO `banco` VALUES ('2', 'Galicia', '0');
INSERT INTO `banco` VALUES ('3', 'HSBC', '0');
INSERT INTO `banco` VALUES ('4', 'ICBC', '0');
INSERT INTO `banco` VALUES ('5', 'Itau', '0');
INSERT INTO `banco` VALUES ('6', 'Macro', '0');
INSERT INTO `banco` VALUES ('7', 'Nacion', '0');
INSERT INTO `banco` VALUES ('8', 'Provincia', '0');
INSERT INTO `banco` VALUES ('9', 'Santander', '0');
INSERT INTO `banco` VALUES ('10', 'Supervielle', '0');
INSERT INTO `banco` VALUES ('11', 'Comafi', '0');
INSERT INTO `banco` VALUES ('12', 'Credicoop', '0');

-- ----------------------------
-- Table structure for boleto
-- ----------------------------
DROP TABLE IF EXISTS `boleto`;
CREATE TABLE `boleto` (
  `boleto_id` int(11) NOT NULL AUTO_INCREMENT,
  `boleto_cliente_id` int(11) NOT NULL,
  `boleto_banco_id` int(11) NOT NULL,
  `boleto_emision_fh` date DEFAULT NULL,
  `boleto_vencimiento_fh` date NOT NULL,
  `boleto_cobro_fh` date DEFAULT NULL,
  `boleto_numero` varchar(256) NOT NULL,
  `boleto_monto_pesos` float(10,2) NOT NULL,
  `boleto_monto_reales` float(11,2) DEFAULT NULL,
  `boleto_nfe` varchar(256) DEFAULT NULL,
  `boleto_multa` float(11,2) DEFAULT NULL,
  `boleto_interes` float(11,2) DEFAULT NULL,
  `boleto_descuento` float(11,2) DEFAULT NULL,
  `boleto_monto_total` float(11,2) DEFAULT NULL COMMENT 'monto+interes+multa-descuento',
  `boleto_estado` int(1) NOT NULL,
  `boleto_baja` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`boleto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of boleto
-- ----------------------------

-- ----------------------------
-- Table structure for boleto_estado
-- ----------------------------
DROP TABLE IF EXISTS `boleto_estado`;
CREATE TABLE `boleto_estado` (
  `vestado_id` int(11) NOT NULL AUTO_INCREMENT,
  `vestado_descripcion` varchar(128) NOT NULL,
  `vestado_baja` int(1) NOT NULL,
  PRIMARY KEY (`vestado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of boleto_estado
-- ----------------------------
INSERT INTO `boleto_estado` VALUES ('1', 'Pendiente de cobro', '0');
INSERT INTO `boleto_estado` VALUES ('2', 'Rechazado', '0');
INSERT INTO `boleto_estado` VALUES ('3', 'Cobrado', '0');

-- ----------------------------
-- Table structure for caja
-- ----------------------------
DROP TABLE IF EXISTS `caja`;
CREATE TABLE `caja` (
  `caja_id` int(11) NOT NULL AUTO_INCREMENT,
  `caja_fh_inicio` datetime NOT NULL,
  `caja_fh_cierre` datetime DEFAULT NULL,
  `caja_monto_inicio` float(9,2) NOT NULL,
  `caja_monto_cierre` float(9,2) DEFAULT NULL,
  `caja_usua_inicio` int(11) NOT NULL,
  `caja_usua_cierre` int(11) DEFAULT NULL,
  `caja_estado` int(1) NOT NULL,
  `caja_cobros_ft` float(11,2) DEFAULT NULL,
  `caja_pagos_ft` float(11,2) DEFAULT NULL,
  `caja_gastos_ft` float(11,2) DEFAULT NULL,
  `caja_cant_ventas` int(11) DEFAULT NULL,
  `caja_total_ventas` float(11,2) DEFAULT NULL,
  `caja_cant_cambios` int(11) DEFAULT NULL,
  `caja_cant_articulos` int(11) DEFAULT NULL,
  PRIMARY KEY (`caja_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of caja
-- ----------------------------
INSERT INTO `caja` VALUES ('3', '2019-08-08 16:52:47', '2019-08-08 16:52:51', '0.00', '1000.00', '1', '1', '2', null, null, null, null, null, null, null);
INSERT INTO `caja` VALUES ('8', '2019-08-09 17:01:17', '2019-08-12 14:30:14', '1000.00', null, '2', '2', '2', null, null, null, null, null, null, null);
INSERT INTO `caja` VALUES ('9', '2019-08-12 14:30:26', '2019-08-12 12:13:52', '1500.00', '1600.00', '2', '2', '2', null, null, null, null, null, null, null);
INSERT INTO `caja` VALUES ('10', '2019-08-12 17:13:29', '2019-08-12 17:17:19', '1230.00', null, '2', '2', '2', null, null, null, null, null, null, null);
INSERT INTO `caja` VALUES ('11', '2019-08-14 18:30:55', '2019-08-14 17:26:38', '1600.00', '1.00', '1', '1', '2', '0.00', '0.00', '0.00', '0', '0.00', '0', '0');
INSERT INTO `caja` VALUES ('12', '2019-08-14 17:26:56', '2019-08-14 18:06:58', '1000.00', '3000.00', '1', '1', '2', '3611.00', '0.00', '500.00', '3', '6111.00', '1', '4');
INSERT INTO `caja` VALUES ('13', '2019-08-14 18:11:22', null, '2000.00', null, '1', null, '1', null, null, null, null, null, null, null);

-- ----------------------------
-- Table structure for caja_estado
-- ----------------------------
DROP TABLE IF EXISTS `caja_estado`;
CREATE TABLE `caja_estado` (
  `ce_id` int(11) NOT NULL AUTO_INCREMENT,
  `ce_nombre` varchar(256) NOT NULL,
  `ce_baja` int(1) DEFAULT NULL,
  PRIMARY KEY (`ce_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of caja_estado
-- ----------------------------
INSERT INTO `caja_estado` VALUES ('1', 'Abierta', '0');
INSERT INTO `caja_estado` VALUES ('2', 'Cerrada', '0');

-- ----------------------------
-- Table structure for categoria
-- ----------------------------
DROP TABLE IF EXISTS `categoria`;
CREATE TABLE `categoria` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_nombre` varchar(128) NOT NULL,
  `cat_baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of categoria
-- ----------------------------
INSERT INTO `categoria` VALUES ('1', 'Remera', '0');
INSERT INTO `categoria` VALUES ('2', 'Pantalon', '0');
INSERT INTO `categoria` VALUES ('3', 'Zapatillas', '0');
INSERT INTO `categoria` VALUES ('4', 'Cinturones', '0');
INSERT INTO `categoria` VALUES ('5', 'Cinturon', '0');
INSERT INTO `categoria` VALUES ('6', 'Medias', '0');
INSERT INTO `categoria` VALUES ('7', 'Ropa interior', '0');
INSERT INTO `categoria` VALUES ('8', 'Pullover', '0');
INSERT INTO `categoria` VALUES ('11', '', '0');
INSERT INTO `categoria` VALUES ('12', 'Botas', '0');
INSERT INTO `categoria` VALUES ('13', 'Accesorios', '0');
INSERT INTO `categoria` VALUES ('14', '56456', '0');
INSERT INTO `categoria` VALUES ('15', '', '0');
INSERT INTO `categoria` VALUES ('16', 'asd', '0');
INSERT INTO `categoria` VALUES ('17', 'yerba', '0');

-- ----------------------------
-- Table structure for cheque
-- ----------------------------
DROP TABLE IF EXISTS `cheque`;
CREATE TABLE `cheque` (
  `cheque_id` int(11) NOT NULL AUTO_INCREMENT,
  `cheque_banco_id` int(11) NOT NULL,
  `cheque_numero` varchar(256) NOT NULL,
  `cheque_monto` float(10,2) NOT NULL,
  `cheque_cobro_fh` date NOT NULL,
  `cheque_cliente_id` int(11) NOT NULL,
  `cheque_estado` int(2) NOT NULL,
  `cheque_baja` int(1) NOT NULL DEFAULT '0',
  `cheque_proveedor_id` int(11) DEFAULT NULL,
  `cheque_proveedor_fh` date DEFAULT NULL,
  `cheque_emision_fh` date DEFAULT NULL,
  `cheque_titular` varchar(256) DEFAULT NULL,
  `cheque_ingreso_fh` date DEFAULT NULL,
  PRIMARY KEY (`cheque_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cheque
-- ----------------------------
INSERT INTO `cheque` VALUES ('1', '1', '1', '10.00', '2019-05-31', '1', '3', '0', '1', null, '2019-05-28', 'Nino', '2019-05-31');
INSERT INTO `cheque` VALUES ('2', '7', '2', '10.00', '2019-05-31', '1', '6', '0', null, null, '2019-05-27', 'Hugo', '2019-05-31');
INSERT INTO `cheque` VALUES ('3', '6', '333', '10.00', '2019-05-31', '1', '1', '0', null, null, '2019-05-28', 'Nino', '2019-05-31');

-- ----------------------------
-- Table structure for cheque_estado
-- ----------------------------
DROP TABLE IF EXISTS `cheque_estado`;
CREATE TABLE `cheque_estado` (
  `vestado_id` int(11) NOT NULL AUTO_INCREMENT,
  `vestado_descripcion` varchar(128) NOT NULL,
  `vestado_baja` int(1) NOT NULL,
  PRIMARY KEY (`vestado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cheque_estado
-- ----------------------------
INSERT INTO `cheque_estado` VALUES ('1', 'Pendiente de cobro', '0');
INSERT INTO `cheque_estado` VALUES ('2', 'Vencido', '0');
INSERT INTO `cheque_estado` VALUES ('3', 'Entregado a proveedores', '0');
INSERT INTO `cheque_estado` VALUES ('4', 'Sin fondos', '0');

-- ----------------------------
-- Table structure for cheque_propio
-- ----------------------------
DROP TABLE IF EXISTS `cheque_propio`;
CREATE TABLE `cheque_propio` (
  `cheque_id` int(11) NOT NULL AUTO_INCREMENT,
  `cheque_banco_id` int(11) NOT NULL,
  `cheque_numero` varchar(256) NOT NULL,
  `cheque_monto` float(10,2) NOT NULL,
  `cheque_vencimiento_fh` date NOT NULL,
  `cheque_proveedor_id` int(11) NOT NULL,
  `cheque_estado` int(2) NOT NULL,
  `cheque_baja` int(1) NOT NULL DEFAULT '0',
  `cheque_emitido_fh` date DEFAULT NULL,
  `cheque_titular` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`cheque_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cheque_propio
-- ----------------------------

-- ----------------------------
-- Table structure for cliente
-- ----------------------------
DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
  `cliente_id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_nombre` varchar(256) NOT NULL,
  `cliente_email` varchar(128) DEFAULT NULL,
  `cliente_tel1` varchar(64) DEFAULT NULL,
  `cliente_trabajo` varchar(64) DEFAULT NULL,
  `cliente_dni` varchar(64) DEFAULT NULL,
  `cliente_observacion` text,
  `cliente_direccion` varchar(256) DEFAULT NULL,
  `cliente_fh_alta` datetime NOT NULL,
  `cliente_baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cliente_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cliente
-- ----------------------------
INSERT INTO `cliente` VALUES ('1', 'Nino', '', '', null, '', '', '', '2019-05-31 20:37:01', '0');
INSERT INTO `cliente` VALUES ('2', 'Gonzalo', '', '', null, '448484', '', '', '2019-07-24 15:48:50', '0');
INSERT INTO `cliente` VALUES ('3', 'Hector tito el bambino', '', '', null, '111111', '', '', '2019-07-24 15:51:46', '0');
INSERT INTO `cliente` VALUES ('9999', 'Anonimo', null, null, null, null, null, null, '0000-00-00 00:00:00', '0');

-- ----------------------------
-- Table structure for cliente_cuenta_corriente
-- ----------------------------
DROP TABLE IF EXISTS `cliente_cuenta_corriente`;
CREATE TABLE `cliente_cuenta_corriente` (
  `ccte_id` int(11) NOT NULL AUTO_INCREMENT,
  `ccte_cliente_id` int(11) NOT NULL,
  `ccte_fh` datetime NOT NULL,
  `ccte_operacion_tipo` int(11) NOT NULL,
  `ccte_operacion_id` int(11) NOT NULL,
  `ccte_importe` float(11,2) NOT NULL,
  `ccte_saldo_actual` float(11,2) NOT NULL,
  PRIMARY KEY (`ccte_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cliente_cuenta_corriente
-- ----------------------------
INSERT INTO `cliente_cuenta_corriente` VALUES ('1', '1', '2019-05-31 20:37:38', '1', '1', '100.00', '-100.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('2', '1', '2019-05-31 00:00:00', '3', '1', '0.00', '-100.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('3', '1', '2019-05-31 00:00:00', '3', '2', '100.00', '0.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('4', '1', '2019-05-31 00:00:00', '3', '3', '0.00', '0.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('5', '1', '2019-05-31 00:00:00', '3', '4', '0.00', '0.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('6', '1', '2019-06-03 11:33:17', '1', '2', '100.00', '-100.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('7', '1', '2019-06-03 11:42:44', '8', '2', '100.00', '0.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('8', '1', '2019-06-03 11:45:16', '1', '3', '100.00', '-100.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('9', '1', '2019-06-03 00:00:00', '3', '5', '100.00', '0.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('10', '1', '2019-06-03 11:50:51', '1', '4', '100.00', '-100.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('11', '0', '2019-07-23 16:02:08', '1', '5', '0.00', '-100.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('12', '0', '2019-07-23 16:04:14', '1', '6', '0.00', '-100.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('13', '0', '2019-07-23 16:07:41', '1', '7', '0.00', '-100.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('14', '0', '2019-07-23 16:09:59', '1', '8', '0.00', '-100.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('15', '0', '2019-07-23 16:21:44', '1', '9', '0.00', '-100.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('16', '0', '2019-07-23 16:28:16', '1', '10', '0.00', '-100.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('17', '0', '2019-07-23 16:28:22', '1', '11', '0.00', '-100.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('18', '0', '2019-07-23 16:28:39', '1', '12', '0.00', '-100.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('19', '0', '2019-07-23 16:29:29', '1', '13', '0.00', '-100.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('20', '0', '2019-07-23 16:37:04', '1', '16', '0.00', '-100.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('21', '3', '2019-07-24 18:21:35', '3', '6', '2430.00', '2430.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('22', '9999', '2019-07-31 16:08:40', '1', '74', '2700.00', '-2700.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('23', '9999', '2019-07-31 18:04:44', '1', '75', '555.00', '-3255.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('24', '9999', '2019-08-01 14:56:35', '1', '76', '900.00', '-4155.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('25', '9999', '2019-08-05 19:19:15', '1', '1', '555.50', '-4710.50');
INSERT INTO `cliente_cuenta_corriente` VALUES ('26', '9999', '2019-08-05 19:22:45', '1', '1', '1800.00', '-6510.50');
INSERT INTO `cliente_cuenta_corriente` VALUES ('27', '9999', '2019-08-05 19:32:09', '1', '2', '30000.00', '-36510.50');
INSERT INTO `cliente_cuenta_corriente` VALUES ('28', '9999', '2019-08-05 19:32:31', '5', '1', '2350.00', '-34160.50');
INSERT INTO `cliente_cuenta_corriente` VALUES ('29', '9999', '2019-08-05 19:36:35', '1', '3', '37500.00', '-71660.50');
INSERT INTO `cliente_cuenta_corriente` VALUES ('30', '9999', '2019-08-05 19:36:58', '5', '2', '26600.00', '-45060.50');
INSERT INTO `cliente_cuenta_corriente` VALUES ('31', '9999', '2019-08-05 19:45:19', '5', '3', '2200.00', '-42860.50');
INSERT INTO `cliente_cuenta_corriente` VALUES ('32', '9999', '2019-08-05 19:45:19', '5', '4', '2200.00', '-40660.50');
INSERT INTO `cliente_cuenta_corriente` VALUES ('33', '9999', '2019-08-05 19:47:31', '5', '5', '1944.50', '-38716.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('34', '9999', '2019-08-05 19:47:58', '5', '6', '900.00', '-37816.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('35', '9999', '2019-08-08 18:52:17', '1', '4', '555.00', '-38371.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('36', '9999', '2019-08-09 15:54:36', '1', '5', '900.00', '-39271.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('37', '9999', '2019-08-09 16:04:44', '1', '6', '3333.00', '-42604.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('38', '9999', '2019-08-09 16:05:49', '1', '7', '150.00', '-42754.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('39', '9999', '2019-08-09 17:03:24', '1', '8', '555.00', '-43309.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('40', '9999', '2019-08-12 14:15:46', '1', '9', '1999.00', '-45308.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('41', '9999', '2019-08-12 17:15:40', '1', '10', '555.00', '-45863.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('42', '9999', '2019-08-14 17:29:14', '1', '11', '1111.00', '-46974.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('43', '9999', '2019-08-14 17:29:48', '1', '12', '2500.00', '-49474.00');
INSERT INTO `cliente_cuenta_corriente` VALUES ('44', '9999', '2019-08-14 17:29:58', '1', '13', '2500.00', '-51974.00');

-- ----------------------------
-- Table structure for cobro_cliente
-- ----------------------------
DROP TABLE IF EXISTS `cobro_cliente`;
CREATE TABLE `cobro_cliente` (
  `cobro_id` int(11) NOT NULL AUTO_INCREMENT,
  `cobro_fh` datetime NOT NULL,
  `cobro_cliente_id` int(11) NOT NULL,
  `cobro_monto_total` float(11,2) NOT NULL,
  `cobro_usuario_id` int(11) NOT NULL,
  `cobro_forma_pago` int(2) NOT NULL,
  `cobro_cheque_id` int(11) DEFAULT NULL,
  `cobro_bono_id` int(11) DEFAULT NULL,
  `cobro_observacion` varchar(512) DEFAULT NULL,
  `cobro_baja_fh` datetime DEFAULT NULL,
  `cobro_transferencia_id` int(11) DEFAULT NULL,
  `cobro_deposito_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`cobro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cobro_cliente
-- ----------------------------
INSERT INTO `cobro_cliente` VALUES ('1', '2019-05-31 00:00:00', '1', '10.00', '6', '6', '1', null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('2', '2019-05-31 00:00:00', '1', '100.00', '6', '1', null, null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('3', '2019-05-31 00:00:00', '1', '10.00', '6', '6', '2', null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('4', '2019-05-31 00:00:00', '1', '10.00', '6', '6', '3', null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('5', '2019-06-03 00:00:00', '1', '100.00', '6', '1', null, null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('6', '2019-07-24 18:21:50', '3', '2430.00', '2', '1', null, null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('7', '2019-07-31 16:08:58', '9999', '2700.00', '2', '1', null, null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('8', '2019-08-01 14:56:21', '9999', '555.00', '2', '3', null, null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('9', '2019-08-01 14:56:46', '9999', '0.00', '2', '2', null, null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('10', '2019-08-05 19:23:09', '9999', '1800.00', '2', '1', null, null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('11', '2019-08-05 19:24:18', '9999', '0.00', '2', '2', null, null, 'Pago por devoluciÃ³n de mercaderÃ­a.', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('12', '2019-08-05 19:32:25', '9999', '30000.00', '2', '1', null, null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('13', '2019-08-05 19:36:51', '9999', '37500.00', '2', '1', null, null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('14', '2019-08-09 18:52:26', '9999', '555.00', '2', '1', null, null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('15', '2019-08-09 15:54:48', '9999', '900.00', '2', '2', null, null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('16', '2019-08-09 16:05:31', '9999', '3333.00', '2', '2', null, null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('17', '2019-08-09 16:06:02', '9999', '15000.00', '2', '3', null, null, '123', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('18', '2019-08-09 16:07:13', '9999', '0.00', '2', '4', null, null, 'Pago por devoluciÃ³n de mercaderÃ­a.', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('19', '2019-08-09 16:08:30', '9999', '0.00', '2', '3', null, null, 'Pago por devoluciÃ³n de mercaderÃ­a.', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('20', '2019-08-09 16:13:44', '9999', '3800.00', '2', '3', null, null, 'Pago por devoluciÃ³n de mercaderÃ­a.', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('21', '2019-08-09 17:03:29', '9999', '555.00', '2', '1', null, null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('22', '2019-08-12 19:15:51', '9999', '1999.00', '2', '1', null, null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('23', '2019-08-14 17:26:37', '9999', '1.00', '1', '1', null, null, 'ConciliaciÃ³n de caja', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('24', '2019-08-14 17:29:32', '9999', '1111.00', '1', '1', null, null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('25', '2019-08-14 17:29:52', '9999', '2500.00', '1', '1', null, null, '', null, null, null);
INSERT INTO `cobro_cliente` VALUES ('26', '2019-08-14 17:30:03', '9999', '2500.00', '1', '3', null, null, '', null, null, null);

-- ----------------------------
-- Table structure for color
-- ----------------------------
DROP TABLE IF EXISTS `color`;
CREATE TABLE `color` (
  `color_id` int(11) NOT NULL AUTO_INCREMENT,
  `color_nombre` varchar(256) NOT NULL,
  `color_codigo` varchar(256) DEFAULT NULL,
  `color_baja` int(1) NOT NULL,
  PRIMARY KEY (`color_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of color
-- ----------------------------
INSERT INTO `color` VALUES ('1', 'Blanco', 'FFFFFF', '0');
INSERT INTO `color` VALUES ('2', 'Negro', '000000', '0');

-- ----------------------------
-- Table structure for compra
-- ----------------------------
DROP TABLE IF EXISTS `compra`;
CREATE TABLE `compra` (
  `compra_id` int(11) NOT NULL AUTO_INCREMENT,
  `compra_fh` datetime NOT NULL,
  `compra_prov_id` int(11) NOT NULL,
  `compra_monto_total` float(11,2) NOT NULL,
  `compra_usuario_id` int(11) NOT NULL,
  `compra_observacion` text,
  `compra_tipo_comprobante` varchar(256) DEFAULT NULL,
  `compra_nro_comprobante` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`compra_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of compra
-- ----------------------------
INSERT INTO `compra` VALUES ('1', '2019-05-31 00:00:00', '1', '1000.00', '6', '', 'BOL', '1');
INSERT INTO `compra` VALUES ('2', '2019-07-17 00:00:00', '1', '6803.00', '2', '', 'BOL', '99998756929');
INSERT INTO `compra` VALUES ('3', '2019-07-17 00:00:00', '1', '91000.00', '2', '', '', '');
INSERT INTO `compra` VALUES ('4', '2019-07-17 00:00:00', '1', '26000.00', '2', '', '', '');
INSERT INTO `compra` VALUES ('5', '2019-07-17 00:00:00', '1', '34400.00', '2', '', '', '');
INSERT INTO `compra` VALUES ('6', '2019-07-17 00:00:00', '1', '130000.00', '2', '', '', '');
INSERT INTO `compra` VALUES ('7', '2019-07-17 00:00:00', '1', '351500.00', '2', '', '', '');
INSERT INTO `compra` VALUES ('8', '2019-07-17 00:00:00', '1', '175000.00', '2', '', '', '');
INSERT INTO `compra` VALUES ('9', '2019-07-17 00:00:00', '2', '6250.00', '2', '', '', '');
INSERT INTO `compra` VALUES ('10', '2019-07-18 13:58:44', '1', '45100.00', '2', '', '', '');
INSERT INTO `compra` VALUES ('11', '2019-07-22 17:17:48', '1', '900.00', '2', '', '', '');
INSERT INTO `compra` VALUES ('12', '2019-08-05 19:21:14', '1', '1000.00', '2', '', 'BOL', '123');
INSERT INTO `compra` VALUES ('13', '2019-08-05 19:21:15', '1', '1000.00', '2', '', 'BOL', '123');
INSERT INTO `compra` VALUES ('14', '2019-08-05 19:31:41', '1', '30000.00', '2', '', '', '');
INSERT INTO `compra` VALUES ('15', '2019-08-05 19:36:02', '2', '16000.00', '2', '', '', '');
INSERT INTO `compra` VALUES ('16', '2019-08-09 15:55:23', '2', '12000.00', '2', '', '', '');
INSERT INTO `compra` VALUES ('17', '2019-08-09 16:18:12', '1', '1230.00', '2', '', '', '');

-- ----------------------------
-- Table structure for compra_concepto
-- ----------------------------
DROP TABLE IF EXISTS `compra_concepto`;
CREATE TABLE `compra_concepto` (
  `cc_id` int(11) NOT NULL AUTO_INCREMENT,
  `cc_compra_id` int(11) NOT NULL,
  `cc_tipo` int(11) NOT NULL,
  `cc_observacion` varchar(245) DEFAULT NULL,
  `cc_fh` datetime DEFAULT NULL,
  `cc_monto` float(11,2) DEFAULT NULL,
  PRIMARY KEY (`cc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of compra_concepto
-- ----------------------------
INSERT INTO `compra_concepto` VALUES ('1', '15', '1', '', '2019-08-08 19:25:48', '1500.00');

-- ----------------------------
-- Table structure for compra_concepto_tipo
-- ----------------------------
DROP TABLE IF EXISTS `compra_concepto_tipo`;
CREATE TABLE `compra_concepto_tipo` (
  `cc_tipo_id` int(11) NOT NULL AUTO_INCREMENT,
  `cc_tipo_nombre` varchar(90) NOT NULL,
  `cc_tipo_baja` int(1) NOT NULL,
  PRIMARY KEY (`cc_tipo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of compra_concepto_tipo
-- ----------------------------
INSERT INTO `compra_concepto_tipo` VALUES ('1', 'Costo descarga', '0');
INSERT INTO `compra_concepto_tipo` VALUES ('2', 'Entrega de dinero a transportista', '0');
INSERT INTO `compra_concepto_tipo` VALUES ('3', 'Costo Flete', '0');
INSERT INTO `compra_concepto_tipo` VALUES ('4', 'Observacion', '0');
INSERT INTO `compra_concepto_tipo` VALUES ('5', 'Nota de Credito', '0');
INSERT INTO `compra_concepto_tipo` VALUES ('6', 'Nota de Debito', '0');
INSERT INTO `compra_concepto_tipo` VALUES ('7', 'Impuesto', '0');

-- ----------------------------
-- Table structure for compra_detalle
-- ----------------------------
DROP TABLE IF EXISTS `compra_detalle`;
CREATE TABLE `compra_detalle` (
  `detalle_id` int(11) NOT NULL AUTO_INCREMENT,
  `detalle_compra_id` int(11) NOT NULL,
  `detalle_prod_id` int(11) NOT NULL,
  `detalle_prod_cant` int(11) NOT NULL,
  `detalle_prod_precio_u` float(11,2) NOT NULL,
  `detalle_prod_talle` int(11) DEFAULT NULL,
  `detalle_prod_color` int(11) DEFAULT NULL,
  PRIMARY KEY (`detalle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of compra_detalle
-- ----------------------------
INSERT INTO `compra_detalle` VALUES ('1', '1', '33', '100', '10.00', '0', null);
INSERT INTO `compra_detalle` VALUES ('2', '2', '1', '1', '500.00', null, null);
INSERT INTO `compra_detalle` VALUES ('3', '2', '1', '3', '501.00', null, null);
INSERT INTO `compra_detalle` VALUES ('4', '2', '1', '10', '480.00', null, null);
INSERT INTO `compra_detalle` VALUES ('5', '3', '1', '10', '100.00', '2', '1');
INSERT INTO `compra_detalle` VALUES ('6', '3', '2', '20', '200.00', '1', '1');
INSERT INTO `compra_detalle` VALUES ('7', '3', '1', '30', '300.00', '2', '1');
INSERT INTO `compra_detalle` VALUES ('8', '3', '2', '40', '400.00', '1', '2');
INSERT INTO `compra_detalle` VALUES ('9', '3', '1', '50', '500.00', '2', '2');
INSERT INTO `compra_detalle` VALUES ('10', '3', '1', '60', '600.00', '1', '2');
INSERT INTO `compra_detalle` VALUES ('11', '4', '1', '10', '900.00', '1', '1');
INSERT INTO `compra_detalle` VALUES ('12', '4', '1', '20', '850.00', '1', '2');
INSERT INTO `compra_detalle` VALUES ('13', '5', '1', '100', '200.00', '1', '1');
INSERT INTO `compra_detalle` VALUES ('14', '5', '2', '80', '180.00', '2', '2');
INSERT INTO `compra_detalle` VALUES ('15', '6', '1', '100', '500.00', '1', '1');
INSERT INTO `compra_detalle` VALUES ('16', '6', '2', '200', '400.00', '2', '2');
INSERT INTO `compra_detalle` VALUES ('17', '7', '1', '100', '200.00', '1', '1');
INSERT INTO `compra_detalle` VALUES ('18', '7', '1', '500', '300.00', '1', '2');
INSERT INTO `compra_detalle` VALUES ('19', '7', '1', '400', '305.00', '2', '1');
INSERT INTO `compra_detalle` VALUES ('20', '7', '1', '100', '595.00', '2', '2');
INSERT INTO `compra_detalle` VALUES ('21', '8', '2', '500', '350.00', '1', '1');
INSERT INTO `compra_detalle` VALUES ('22', '9', '2', '15', '150.00', '1', '1');
INSERT INTO `compra_detalle` VALUES ('23', '9', '2', '20', '200.00', '1', '1');
INSERT INTO `compra_detalle` VALUES ('24', '10', '1', '11', '1100.00', '2', '2');
INSERT INTO `compra_detalle` VALUES ('25', '10', '2', '22', '1500.00', '1', '2');
INSERT INTO `compra_detalle` VALUES ('26', '11', '4', '1', '900.00', '1', '1');
INSERT INTO `compra_detalle` VALUES ('27', '12', '28', '10', '100.00', '1', '1');
INSERT INTO `compra_detalle` VALUES ('28', '13', '28', '10', '100.00', '1', '1');
INSERT INTO `compra_detalle` VALUES ('29', '14', '29', '20', '1500.00', '2', '2');
INSERT INTO `compra_detalle` VALUES ('30', '15', '29', '10', '1600.00', '2', '2');
INSERT INTO `compra_detalle` VALUES ('31', '16', '1', '10', '1200.00', '1', '2');
INSERT INTO `compra_detalle` VALUES ('32', '17', '3', '10', '123.00', '1', '2');

-- ----------------------------
-- Table structure for compra_estado
-- ----------------------------
DROP TABLE IF EXISTS `compra_estado`;
CREATE TABLE `compra_estado` (
  `cestado_id` int(11) NOT NULL AUTO_INCREMENT,
  `cestado_descripcion` varchar(128) NOT NULL,
  `cestado_baja` int(1) NOT NULL,
  PRIMARY KEY (`cestado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of compra_estado
-- ----------------------------
INSERT INTO `compra_estado` VALUES ('1', 'Pendiente', '0');
INSERT INTO `compra_estado` VALUES ('2', 'Saldada', '0');
INSERT INTO `compra_estado` VALUES ('3', 'Cancelada', '0');
INSERT INTO `compra_estado` VALUES ('4', 'Despachada', '0');
INSERT INTO `compra_estado` VALUES ('5', 'Sin stock', '0');
INSERT INTO `compra_estado` VALUES ('6', 'Archivada', '0');

-- ----------------------------
-- Table structure for cuenta_corriente_operacion
-- ----------------------------
DROP TABLE IF EXISTS `cuenta_corriente_operacion`;
CREATE TABLE `cuenta_corriente_operacion` (
  `ccop_id` int(11) NOT NULL AUTO_INCREMENT,
  `ccop_nombre` varchar(128) NOT NULL,
  `ccop_tabla` varchar(128) NOT NULL,
  `ccop_baja` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ccop_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cuenta_corriente_operacion
-- ----------------------------
INSERT INTO `cuenta_corriente_operacion` VALUES ('1', 'Venta', 'venta', '0');
INSERT INTO `cuenta_corriente_operacion` VALUES ('2', 'Compra', 'compra', '0');
INSERT INTO `cuenta_corriente_operacion` VALUES ('3', 'Cobro', 'cobro_cliente', '0');
INSERT INTO `cuenta_corriente_operacion` VALUES ('4', 'Pago', 'pago_proveedor', '0');
INSERT INTO `cuenta_corriente_operacion` VALUES ('5', 'Nota credito', 'nota_credito', '0');
INSERT INTO `cuenta_corriente_operacion` VALUES ('6', 'Nota debito', 'nota_debito', '0');
INSERT INTO `cuenta_corriente_operacion` VALUES ('7', 'Facturacion', 'facturacion', '0');
INSERT INTO `cuenta_corriente_operacion` VALUES ('8', 'Anulacion', 'venta', '0');
INSERT INTO `cuenta_corriente_operacion` VALUES ('9', 'Entrega', 'transportista', '0');
INSERT INTO `cuenta_corriente_operacion` VALUES ('10', 'Costo Flete', 'transportista', '0');

-- ----------------------------
-- Table structure for deposito_bancario
-- ----------------------------
DROP TABLE IF EXISTS `deposito_bancario`;
CREATE TABLE `deposito_bancario` (
  `d_id` int(11) NOT NULL AUTO_INCREMENT,
  `d_proveedor_id` int(11) DEFAULT NULL,
  `d_transportista_id` int(11) DEFAULT NULL,
  `d_despachante_id` int(11) DEFAULT NULL,
  `d_fh` datetime DEFAULT NULL,
  `d_monto` float(11,2) DEFAULT NULL,
  `d_banco_id` int(11) DEFAULT NULL,
  `d_comprobante` varchar(256) DEFAULT NULL,
  `d_factura` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`d_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of deposito_bancario
-- ----------------------------

-- ----------------------------
-- Table structure for deposito_bancario_terceros
-- ----------------------------
DROP TABLE IF EXISTS `deposito_bancario_terceros`;
CREATE TABLE `deposito_bancario_terceros` (
  `d_id` int(11) NOT NULL AUTO_INCREMENT,
  `d_cliente_id` int(11) DEFAULT NULL,
  `d_fh` datetime DEFAULT NULL,
  `d_monto` float(11,2) DEFAULT NULL,
  `d_banco_id` int(11) DEFAULT NULL,
  `d_comprobante` varchar(256) DEFAULT NULL,
  `d_factura` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`d_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of deposito_bancario_terceros
-- ----------------------------

-- ----------------------------
-- Table structure for devolucion
-- ----------------------------
DROP TABLE IF EXISTS `devolucion`;
CREATE TABLE `devolucion` (
  `d_id` int(11) NOT NULL AUTO_INCREMENT,
  `d_venta_id` int(11) NOT NULL,
  `d_usr_id` int(11) NOT NULL,
  `d_fh` datetime NOT NULL,
  `d_diferencia` float(11,2) NOT NULL,
  `d_pago_id` int(11) DEFAULT NULL,
  `d_nota_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`d_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of devolucion
-- ----------------------------
INSERT INTO `devolucion` VALUES ('1', '1', '2', '2019-08-05 19:24:17', '-405.50', null, null);
INSERT INTO `devolucion` VALUES ('2', '2', '2', '2019-08-05 19:33:00', '2350.00', null, null);
INSERT INTO `devolucion` VALUES ('3', '3', '2', '2019-08-05 19:37:37', '26600.00', null, null);
INSERT INTO `devolucion` VALUES ('4', '3', '2', '2019-08-05 19:45:28', '2200.00', null, null);
INSERT INTO `devolucion` VALUES ('5', '3', '2', '2019-08-05 19:47:13', '2200.00', null, null);
INSERT INTO `devolucion` VALUES ('6', '2', '2', '2019-08-05 19:47:38', '1944.50', null, null);
INSERT INTO `devolucion` VALUES ('7', '1', '2', '2019-08-05 19:48:37', '900.00', null, null);
INSERT INTO `devolucion` VALUES ('8', '7', '2', '2019-08-09 16:07:13', '-2627.50', '18', null);
INSERT INTO `devolucion` VALUES ('9', '5', '2', '2019-08-09 16:08:30', '-900.00', '19', null);
INSERT INTO `devolucion` VALUES ('10', '2', '2', '2019-08-09 16:13:44', '-3800.00', '20', null);
INSERT INTO `devolucion` VALUES ('11', '13', '1', '2019-08-14 18:04:47', '0.00', null, null);

-- ----------------------------
-- Table structure for devolucion_detalle
-- ----------------------------
DROP TABLE IF EXISTS `devolucion_detalle`;
CREATE TABLE `devolucion_detalle` (
  `detalle_id` int(11) NOT NULL AUTO_INCREMENT,
  `detalle_devolucion_id` int(11) NOT NULL,
  `detalle_ps_id` int(11) DEFAULT NULL,
  `detalle_vd_id` int(11) DEFAULT NULL,
  `detalle_prod_cant` int(11) NOT NULL,
  PRIMARY KEY (`detalle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of devolucion_detalle
-- ----------------------------
INSERT INTO `devolucion_detalle` VALUES ('1', '1', '26', '1', '1');
INSERT INTO `devolucion_detalle` VALUES ('2', '1', '27', '1', '0');
INSERT INTO `devolucion_detalle` VALUES ('3', '1', '0', null, '1');
INSERT INTO `devolucion_detalle` VALUES ('4', '2', '28', '2', '1');
INSERT INTO `devolucion_detalle` VALUES ('5', '2', '0', null, '1');
INSERT INTO `devolucion_detalle` VALUES ('6', '3', '28', '3', '9');
INSERT INTO `devolucion_detalle` VALUES ('7', '3', '29', '3', '2');
INSERT INTO `devolucion_detalle` VALUES ('8', '3', '0', null, '1');
INSERT INTO `devolucion_detalle` VALUES ('9', '4', '29', '3', '1');
INSERT INTO `devolucion_detalle` VALUES ('10', '5', '29', '3', '1');
INSERT INTO `devolucion_detalle` VALUES ('11', '6', '28', '2', '1');
INSERT INTO `devolucion_detalle` VALUES ('12', '7', '26', '1', '10');
INSERT INTO `devolucion_detalle` VALUES ('13', '7', '27', '1', '2');
INSERT INTO `devolucion_detalle` VALUES ('14', '7', '24', null, '1');
INSERT INTO `devolucion_detalle` VALUES ('15', '8', '26', '7', '1');
INSERT INTO `devolucion_detalle` VALUES ('16', '8', '17', null, '5');
INSERT INTO `devolucion_detalle` VALUES ('17', '9', '15', '5', '1');
INSERT INTO `devolucion_detalle` VALUES ('18', '9', '15', null, '2');
INSERT INTO `devolucion_detalle` VALUES ('19', '10', '28', '2', '1');
INSERT INTO `devolucion_detalle` VALUES ('20', '10', '15', null, '7');
INSERT INTO `devolucion_detalle` VALUES ('21', '11', '28', '13', '1');
INSERT INTO `devolucion_detalle` VALUES ('22', '11', '28', null, '1');

-- ----------------------------
-- Table structure for estado
-- ----------------------------
DROP TABLE IF EXISTS `estado`;
CREATE TABLE `estado` (
  `estado_id` int(2) NOT NULL AUTO_INCREMENT,
  `estado_nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`estado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of estado
-- ----------------------------
INSERT INTO `estado` VALUES ('1', 'Abierto');
INSERT INTO `estado` VALUES ('2', 'Pendiente');
INSERT INTO `estado` VALUES ('3', 'Cerrado y solucionado');
INSERT INTO `estado` VALUES ('4', 'Cerrado sin solucion');

-- ----------------------------
-- Table structure for forma_pago
-- ----------------------------
DROP TABLE IF EXISTS `forma_pago`;
CREATE TABLE `forma_pago` (
  `fp_id` int(11) NOT NULL AUTO_INCREMENT,
  `fp_desc` varchar(45) NOT NULL,
  `fp_baja` int(1) NOT NULL,
  PRIMARY KEY (`fp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of forma_pago
-- ----------------------------
INSERT INTO `forma_pago` VALUES ('1', 'Efectivo', '0');
INSERT INTO `forma_pago` VALUES ('2', 'MercadoPago', '0');
INSERT INTO `forma_pago` VALUES ('3', 'Tarjeta credito', '0');
INSERT INTO `forma_pago` VALUES ('4', 'Tarjeta debito', '0');

-- ----------------------------
-- Table structure for gasto
-- ----------------------------
DROP TABLE IF EXISTS `gasto`;
CREATE TABLE `gasto` (
  `gasto_id` int(11) NOT NULL AUTO_INCREMENT,
  `gasto_fh` datetime NOT NULL,
  `gasto_observacion` varchar(255) NOT NULL,
  `gasto_concepto` int(11) NOT NULL,
  `gasto_monto_total` float(11,2) NOT NULL,
  `gasto_usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`gasto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of gasto
-- ----------------------------
INSERT INTO `gasto` VALUES ('1', '2019-07-29 15:31:04', 'asd', '0', '500.00', '2');
INSERT INTO `gasto` VALUES ('2', '2019-08-09 16:00:49', 'EDELAP', '4', '1900.00', '2');
INSERT INTO `gasto` VALUES ('3', '2019-08-09 16:04:04', '12', '2', '12.00', '2');
INSERT INTO `gasto` VALUES ('4', '2019-08-09 16:04:12', 'Luz', '4', '250.00', '2');
INSERT INTO `gasto` VALUES ('5', '2019-08-12 20:04:27', '', '2', '150.00', '2');
INSERT INTO `gasto` VALUES ('6', '2019-08-14 16:33:25', '12', '2', '123.00', '1');
INSERT INTO `gasto` VALUES ('7', '2019-08-14 17:28:26', 'obs!', '1', '500.00', '1');
INSERT INTO `gasto` VALUES ('8', '2019-08-14 18:06:58', 'ConciliaciÃ³n de caja', '5', '111.00', '1');

-- ----------------------------
-- Table structure for log_ingreso
-- ----------------------------
DROP TABLE IF EXISTS `log_ingreso`;
CREATE TABLE `log_ingreso` (
  `loging_id` int(11) NOT NULL AUTO_INCREMENT,
  `loging_usua_id` int(11) NOT NULL,
  `loging_app_id` int(11) NOT NULL,
  `loging_fecha` datetime NOT NULL,
  PRIMARY KEY (`loging_id`),
  KEY `fk_log_ingreso_aplicacion1` (`loging_app_id`) USING BTREE,
  KEY `fk_log_ingreso_usuario1` (`loging_usua_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of log_ingreso
-- ----------------------------

-- ----------------------------
-- Table structure for marca
-- ----------------------------
DROP TABLE IF EXISTS `marca`;
CREATE TABLE `marca` (
  `marca_id` int(11) NOT NULL AUTO_INCREMENT,
  `marca_nombre` varchar(128) NOT NULL,
  `marca_baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`marca_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of marca
-- ----------------------------
INSERT INTO `marca` VALUES ('1', 'Adidas', '0');
INSERT INTO `marca` VALUES ('2', 'Nike', '0');
INSERT INTO `marca` VALUES ('3', 'Umbro', '0');
INSERT INTO `marca` VALUES ('4', 'jhjklh', '0');
INSERT INTO `marca` VALUES ('5', 'Mistral', '0');
INSERT INTO `marca` VALUES ('6', 'taragui', '0');

-- ----------------------------
-- Table structure for modulo
-- ----------------------------
DROP TABLE IF EXISTS `modulo`;
CREATE TABLE `modulo` (
  `mod_id` int(11) NOT NULL AUTO_INCREMENT,
  `mod_app_id` int(11) NOT NULL,
  `mod_nombre` varchar(45) NOT NULL,
  `mod_baja` tinyint(4) NOT NULL,
  `mod_color` varchar(20) NOT NULL,
  `mod_intro` varchar(45) DEFAULT NULL,
  `mod_icono` varchar(40) NOT NULL,
  `mod_index_modpag_id` int(11) NOT NULL,
  PRIMARY KEY (`mod_id`),
  KEY `fk_aplicacion_app_id1` (`mod_app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of modulo
-- ----------------------------
INSERT INTO `modulo` VALUES ('1', '5', 'Inicio', '0', 'primary', 'Inicio', 'fa-home', '10');
INSERT INTO `modulo` VALUES ('2', '5', 'Ventas Moviles', '1', 'primary', 'GestiÃ³n de ventas moviles', 'fa-tags', '41');
INSERT INTO `modulo` VALUES ('3', '5', 'Contable', '0', 'primary', 'GestiÃ³n contable', 'fa-line-chart', '120');
INSERT INTO `modulo` VALUES ('4', '5', 'Stock', '0', 'primary', 'Productos, compras y proveedores', 'fa-dropbox', '70');
INSERT INTO `modulo` VALUES ('5', '5', 'Seguridad', '0', 'primary', 'Seguridad y permisos', 'fa-unlock-alt', '100');
INSERT INTO `modulo` VALUES ('6', '5', 'Ventas', '0', 'primary', 'GestiÃ³n de ventas', 'fa-shopping-cart', '106');

-- ----------------------------
-- Table structure for modulo_paginas
-- ----------------------------
DROP TABLE IF EXISTS `modulo_paginas`;
CREATE TABLE `modulo_paginas` (
  `modpag_id` int(11) NOT NULL AUTO_INCREMENT,
  `modpag_mod_id` int(11) NOT NULL,
  `modpag_scriptname` varchar(60) NOT NULL,
  PRIMARY KEY (`modpag_id`),
  KEY `fk_modulo_modpag_mod_id1` (`modpag_mod_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of modulo_paginas
-- ----------------------------
INSERT INTO `modulo_paginas` VALUES ('10', '1', 'home/home.php');
INSERT INTO `modulo_paginas` VALUES ('11', '1', 'home/denegado.php');
INSERT INTO `modulo_paginas` VALUES ('21', '3', 'clientes/cartera.php');
INSERT INTO `modulo_paginas` VALUES ('22', '3', 'clientes/cuenta_corriente.php');
INSERT INTO `modulo_paginas` VALUES ('40', '2', 'ventas_moviles/cargar_venta.php');
INSERT INTO `modulo_paginas` VALUES ('41', '2', 'ventas_moviles/movil.php');
INSERT INTO `modulo_paginas` VALUES ('70', '4', 'productos/index.php');
INSERT INTO `modulo_paginas` VALUES ('72', '4', 'productos/categorias.php');
INSERT INTO `modulo_paginas` VALUES ('73', '3', 'proveedores/cartera.php');
INSERT INTO `modulo_paginas` VALUES ('74', '4', 'productos/tipos.php');
INSERT INTO `modulo_paginas` VALUES ('75', '4', 'compras/listado.php');
INSERT INTO `modulo_paginas` VALUES ('76', '4', 'compras/index.php');
INSERT INTO `modulo_paginas` VALUES ('100', '5', 'seguridad/index.php');
INSERT INTO `modulo_paginas` VALUES ('101', '5', 'seguridad/usuarios.php');
INSERT INTO `modulo_paginas` VALUES ('102', '5', 'seguridad/roles.php');
INSERT INTO `modulo_paginas` VALUES ('103', '5', 'seguridad/permisos.php');
INSERT INTO `modulo_paginas` VALUES ('104', '5', 'seguridad/modulos.php');
INSERT INTO `modulo_paginas` VALUES ('105', '5', 'seguridad/areas.php');
INSERT INTO `modulo_paginas` VALUES ('106', '6', 'gestion_ventas/index.php');
INSERT INTO `modulo_paginas` VALUES ('107', '6', 'gestion_ventas/pendientes.php');
INSERT INTO `modulo_paginas` VALUES ('108', '6', 'gestion_ventas/saldadas.php');
INSERT INTO `modulo_paginas` VALUES ('115', '3', 'notas/index.php');
INSERT INTO `modulo_paginas` VALUES ('116', '3', 'notas/listado.php');
INSERT INTO `modulo_paginas` VALUES ('117', '3', 'pagos_proveedores/index.php');
INSERT INTO `modulo_paginas` VALUES ('118', '3', 'pagos_proveedores/listado.php');
INSERT INTO `modulo_paginas` VALUES ('120', '3', 'contable/index.php');
INSERT INTO `modulo_paginas` VALUES ('124', '3', 'gastos/listado.php');
INSERT INTO `modulo_paginas` VALUES ('125', '4', 'productos/productos.php');
INSERT INTO `modulo_paginas` VALUES ('126', '3', 'proveedores/cuenta_corriente.php');
INSERT INTO `modulo_paginas` VALUES ('130', '3', 'caja/index.php');
INSERT INTO `modulo_paginas` VALUES ('131', '4', 'productos/marca.php');
INSERT INTO `modulo_paginas` VALUES ('132', '4', 'productos/talles_colores.php');

-- ----------------------------
-- Table structure for modulo_tablas
-- ----------------------------
DROP TABLE IF EXISTS `modulo_tablas`;
CREATE TABLE `modulo_tablas` (
  `modtab_id` int(11) NOT NULL AUTO_INCREMENT,
  `modtab_ddt_id` int(11) NOT NULL,
  `modtab_mod_id` int(11) NOT NULL,
  PRIMARY KEY (`modtab_id`),
  KEY `fk_modulo_modtab_mod_id1` (`modtab_mod_id`) USING BTREE,
  KEY `fk_diccionario_datos_tablas_modtab_ddt_id1` (`modtab_ddt_id`) USING BTREE,
  CONSTRAINT `modulo_tablas_ibfk_1` FOREIGN KEY (`modtab_mod_id`) REFERENCES `modulo` (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of modulo_tablas
-- ----------------------------

-- ----------------------------
-- Table structure for notas
-- ----------------------------
DROP TABLE IF EXISTS `notas`;
CREATE TABLE `notas` (
  `nota_id` int(11) NOT NULL AUTO_INCREMENT,
  `nota_cliente_id` int(11) NOT NULL,
  `nota_prov_id` int(11) DEFAULT NULL,
  `nota_tipo` varchar(2) NOT NULL,
  `nota_monto` float(11,2) NOT NULL,
  `nota_fh` datetime DEFAULT NULL,
  `nota_alta_fh` datetime DEFAULT NULL,
  `nota_observacion` varchar(256) DEFAULT NULL,
  `nota_ccop_tipo` int(11) DEFAULT NULL,
  `nota_ccop_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`nota_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of notas
-- ----------------------------
INSERT INTO `notas` VALUES ('1', '9999', null, 'NC', '2350.00', '2019-08-05 19:32:31', '2019-08-05 19:33:00', 'Nota por devoluciÃ³n de mercaderÃ­a.', null, null);
INSERT INTO `notas` VALUES ('2', '9999', null, 'NC', '26600.00', '2019-08-05 19:36:58', '2019-08-05 19:37:38', 'Nota por devoluciÃ³n de mercaderÃ­a.', null, null);
INSERT INTO `notas` VALUES ('3', '9999', null, 'NC', '2200.00', '2019-08-05 19:45:19', '2019-08-05 19:45:28', 'Nota por devoluciÃ³n de mercaderÃ­a.', null, null);
INSERT INTO `notas` VALUES ('4', '9999', null, 'NC', '2200.00', '2019-08-05 19:45:19', '2019-08-05 19:47:13', 'Nota por devoluciÃ³n de mercaderÃ­a.', null, null);
INSERT INTO `notas` VALUES ('5', '9999', null, 'NC', '1944.50', '2019-08-05 19:47:31', '2019-08-05 19:47:38', 'Nota por devoluciÃ³n de mercaderÃ­a.', null, null);
INSERT INTO `notas` VALUES ('6', '9999', null, 'NC', '900.00', '2019-08-05 19:47:58', '2019-08-05 19:48:37', 'Nota por devoluciÃ³n de mercaderÃ­a.', null, null);

-- ----------------------------
-- Table structure for pago
-- ----------------------------
DROP TABLE IF EXISTS `pago`;
CREATE TABLE `pago` (
  `pago_id` int(11) NOT NULL AUTO_INCREMENT,
  `pago_fh` datetime NOT NULL,
  `pago_cliente_id` int(11) NOT NULL,
  `pago_monto_total` float(11,2) NOT NULL,
  `pago_usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`pago_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of pago
-- ----------------------------

-- ----------------------------
-- Table structure for pago_detalle
-- ----------------------------
DROP TABLE IF EXISTS `pago_detalle`;
CREATE TABLE `pago_detalle` (
  `detalle_id` int(11) NOT NULL AUTO_INCREMENT,
  `detalle_pago_id` int(11) NOT NULL,
  `detalle_ccop_tipo` int(11) NOT NULL,
  `detalle_ccop_id` int(11) NOT NULL,
  `detalle_monto` float(11,2) NOT NULL,
  PRIMARY KEY (`detalle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of pago_detalle
-- ----------------------------

-- ----------------------------
-- Table structure for pago_proveedor
-- ----------------------------
DROP TABLE IF EXISTS `pago_proveedor`;
CREATE TABLE `pago_proveedor` (
  `pago_id` int(11) NOT NULL AUTO_INCREMENT,
  `pago_fh` datetime NOT NULL,
  `pago_prov_id` int(11) NOT NULL,
  `pago_compra_id` int(11) DEFAULT NULL,
  `pago_monto_total` float(11,2) NOT NULL,
  `pago_usuario_id` int(11) NOT NULL,
  `pago_forma_pago` int(2) NOT NULL,
  `pago_observacion` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`pago_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of pago_proveedor
-- ----------------------------
INSERT INTO `pago_proveedor` VALUES ('1', '2019-05-28 00:00:00', '1', null, '100.00', '6', '2', '');
INSERT INTO `pago_proveedor` VALUES ('2', '2019-05-31 00:00:00', '1', null, '10.20', '6', '2', '');
INSERT INTO `pago_proveedor` VALUES ('3', '2019-05-31 00:00:00', '1', null, '10.00', '6', '6', '');
INSERT INTO `pago_proveedor` VALUES ('4', '2019-08-09 00:00:00', '2', '16', '2500.00', '2', '3', '');
INSERT INTO `pago_proveedor` VALUES ('5', '2019-08-12 21:00:00', '2', '16', '9500.00', '2', '1', '');
INSERT INTO `pago_proveedor` VALUES ('6', '2019-08-14 00:00:00', '1', null, '500.00', '1', '1', '');

-- ----------------------------
-- Table structure for permiso
-- ----------------------------
DROP TABLE IF EXISTS `permiso`;
CREATE TABLE `permiso` (
  `permiso_id` int(11) NOT NULL AUTO_INCREMENT,
  `permiso_rol_id` int(11) NOT NULL,
  `permiso_mod_id` int(11) NOT NULL,
  `permiso_tipoacc_id` int(11) NOT NULL,
  PRIMARY KEY (`permiso_id`),
  KEY `fk_rol_has_modulo_modulo1` (`permiso_mod_id`) USING BTREE,
  KEY `fk_rol_has_modulo_rol1` (`permiso_rol_id`) USING BTREE,
  KEY `fk_permiso_tipo_acceso1` (`permiso_tipoacc_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of permiso
-- ----------------------------
INSERT INTO `permiso` VALUES ('1', '1', '1', '4');
INSERT INTO `permiso` VALUES ('2', '1', '2', '4');
INSERT INTO `permiso` VALUES ('3', '1', '3', '4');
INSERT INTO `permiso` VALUES ('4', '1', '4', '4');
INSERT INTO `permiso` VALUES ('5', '1', '5', '4');
INSERT INTO `permiso` VALUES ('6', '1', '6', '4');
INSERT INTO `permiso` VALUES ('7', '1', '7', '4');
INSERT INTO `permiso` VALUES ('8', '1', '8', '4');
INSERT INTO `permiso` VALUES ('9', '1', '9', '4');
INSERT INTO `permiso` VALUES ('10', '2', '1', '1');
INSERT INTO `permiso` VALUES ('11', '2', '2', '1');
INSERT INTO `permiso` VALUES ('12', '2', '3', '1');
INSERT INTO `permiso` VALUES ('13', '2', '4', '1');
INSERT INTO `permiso` VALUES ('14', '2', '5', '0');
INSERT INTO `permiso` VALUES ('15', '2', '7', '1');
INSERT INTO `permiso` VALUES ('16', '2', '8', '1');
INSERT INTO `permiso` VALUES ('17', '2', '9', '1');
INSERT INTO `permiso` VALUES ('18', '3', '1', '2');
INSERT INTO `permiso` VALUES ('19', '3', '3', '2');
INSERT INTO `permiso` VALUES ('20', '3', '4', '2');
INSERT INTO `permiso` VALUES ('21', '3', '5', '2');
INSERT INTO `permiso` VALUES ('22', '3', '6', '2');
INSERT INTO `permiso` VALUES ('23', '3', '7', '2');
INSERT INTO `permiso` VALUES ('24', '3', '8', '2');
INSERT INTO `permiso` VALUES ('25', '3', '9', '0');
INSERT INTO `permiso` VALUES ('26', '4', '1', '3');
INSERT INTO `permiso` VALUES ('27', '4', '3', '3');
INSERT INTO `permiso` VALUES ('28', '4', '4', '3');
INSERT INTO `permiso` VALUES ('29', '4', '5', '3');
INSERT INTO `permiso` VALUES ('30', '4', '6', '3');
INSERT INTO `permiso` VALUES ('31', '4', '7', '3');
INSERT INTO `permiso` VALUES ('32', '4', '8', '3');
INSERT INTO `permiso` VALUES ('33', '4', '9', '3');
INSERT INTO `permiso` VALUES ('34', '1', '10', '4');
INSERT INTO `permiso` VALUES ('35', '2', '10', '0');
INSERT INTO `permiso` VALUES ('36', '3', '10', '2');
INSERT INTO `permiso` VALUES ('37', '4', '10', '3');
INSERT INTO `permiso` VALUES ('38', '3', '2', '1');
INSERT INTO `permiso` VALUES ('39', '4', '2', '1');
INSERT INTO `permiso` VALUES ('40', '1', '11', '4');
INSERT INTO `permiso` VALUES ('41', '2', '11', '1');
INSERT INTO `permiso` VALUES ('42', '3', '11', '2');
INSERT INTO `permiso` VALUES ('43', '4', '11', '3');
INSERT INTO `permiso` VALUES ('44', '1', '12', '4');
INSERT INTO `permiso` VALUES ('45', '2', '12', '0');
INSERT INTO `permiso` VALUES ('46', '3', '12', '2');
INSERT INTO `permiso` VALUES ('47', '4', '12', '3');
INSERT INTO `permiso` VALUES ('48', '1', '13', '4');
INSERT INTO `permiso` VALUES ('49', '2', '13', '1');
INSERT INTO `permiso` VALUES ('50', '3', '13', '2');
INSERT INTO `permiso` VALUES ('51', '4', '13', '3');
INSERT INTO `permiso` VALUES ('52', '1', '14', '4');

-- ----------------------------
-- Table structure for producto
-- ----------------------------
DROP TABLE IF EXISTS `producto`;
CREATE TABLE `producto` (
  `prod_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_nombre` varchar(255) NOT NULL,
  `prod_codigo` bigint(64) NOT NULL COMMENT '3 CAT + 3 MARCA + 6 MODELO',
  `prod_marca_id` int(11) NOT NULL,
  `prod_cat_id` int(11) NOT NULL,
  `prod_baja` tinyint(1) NOT NULL DEFAULT '0',
  `prod_precio` float(11,2) NOT NULL,
  PRIMARY KEY (`prod_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of producto
-- ----------------------------
INSERT INTO `producto` VALUES ('1', 'Seleccion Argentina Copa america', '1155599859', '1', '1', '0', '555.50');
INSERT INTO `producto` VALUES ('2', 'Seleccion Brasil Copa America', '229959431', '2', '2', '0', '900.00');
INSERT INTO `producto` VALUES ('3', 'Air temporada 2019', '2147483647', '1', '1', '0', '1999.80');
INSERT INTO `producto` VALUES ('4', 'Largo con cinturon', '999998', '2', '2', '0', '1400.00');
INSERT INTO `producto` VALUES ('5', 'Largo con rayas temporada 2019', '2147483647', '2', '2', '0', '1600.00');
INSERT INTO `producto` VALUES ('6', 'Guantes de arquero ASD 0019', '2147483647', '3', '13', '0', '800.00');
INSERT INTO `producto` VALUES ('7', '555', '9223372036854775807', '4', '14', '0', '120.00');
INSERT INTO `producto` VALUES ('8', 'Classic AJ 220', '2619123123221', '3', '3', '0', '3260.00');
INSERT INTO `producto` VALUES ('9', '5 agujeros, una hebilla, un sentimiento', '5648912213121', '2', '5', '0', '360.00');
INSERT INTO `producto` VALUES ('10', 'Classic 192 Invierno 2019', '6491561992632', '1', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('11', 'Classic 192 Invierno 2019', '6491561992632', '1', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('12', 'Classic 192 Invierno 2019', '6491561992632', '1', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('13', 'Classic 192 Invierno 2019', '6491561992632', '1', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('14', 'Classic 192 Invierno 2019', '6491561992632', '1', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('15', 'Classic 192 Invierno 2019', '6491561992632', '1', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('16', 'Classic 192 Invierno 2019', '6491561992632', '1', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('17', 'Classic 192 Invierno 2019', '6491561992632', '1', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('18', 'Classic 192 Invierno 2019', '6491561992632', '1', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('19', 'Classic 192 Invierno 2019', '6491561992632', '1', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('20', 'Classic 192 Invierno 2019', '6491561992632', '1', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('21', 'Classic 192 Invierno 2019', '6491561992632', '1', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('22', 'Classic 192 Invierno 2019', '6491561992632', '1', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('23', 'Classic 192 Invierno 2019', '6491561992632', '1', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('24', 'Classic 192 Invierno 2019', '6491561992632', '1', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('25', 'Classic 192 Invierno 2019', '6491561992632', '1', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('26', 'Cinto con agujeritos  y hebilla 199', '19902292906506169', '5', '4', '0', '290.00');
INSERT INTO `producto` VALUES ('27', 'AB Nw 49 Con suela', '98498489489894849', '3', '3', '0', '3600.00');
INSERT INTO `producto` VALUES ('28', 'BCP 1 kilo', '7790387013153', '6', '17', '0', '150.00');
INSERT INTO `producto` VALUES ('29', 'zapas 1', '654654645', '2', '1', '0', '2500.00');

-- ----------------------------
-- Table structure for producto_stock
-- ----------------------------
DROP TABLE IF EXISTS `producto_stock`;
CREATE TABLE `producto_stock` (
  `ps_id` int(11) NOT NULL AUTO_INCREMENT,
  `ps_compra_id` int(11) DEFAULT NULL,
  `ps_producto_id` int(11) NOT NULL,
  `ps_costo_u` float(11,2) NOT NULL,
  `ps_cantidad` int(11) NOT NULL,
  `ps_talle_id` int(11) DEFAULT NULL,
  `ps_color_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ps_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of producto_stock
-- ----------------------------
INSERT INTO `producto_stock` VALUES ('14', '6', '1', '500.00', '0', '1', '1');
INSERT INTO `producto_stock` VALUES ('15', '6', '2', '400.00', '182', '2', '2');
INSERT INTO `producto_stock` VALUES ('16', '7', '1', '200.00', '0', '1', '1');
INSERT INTO `producto_stock` VALUES ('17', '7', '1', '300.00', '484', '1', '2');
INSERT INTO `producto_stock` VALUES ('18', '7', '1', '305.00', '397', '2', '1');
INSERT INTO `producto_stock` VALUES ('19', '7', '1', '595.00', '92', '2', '2');
INSERT INTO `producto_stock` VALUES ('20', '8', '2', '350.00', '500', '1', '1');
INSERT INTO `producto_stock` VALUES ('21', '9', '2', '150.00', '15', '1', '1');
INSERT INTO `producto_stock` VALUES ('22', '9', '2', '200.00', '20', '1', '1');
INSERT INTO `producto_stock` VALUES ('23', '10', '1', '1100.00', '11', '2', '2');
INSERT INTO `producto_stock` VALUES ('24', '10', '2', '1500.00', '12', '1', '2');
INSERT INTO `producto_stock` VALUES ('25', '11', '4', '900.00', '0', '1', '1');
INSERT INTO `producto_stock` VALUES ('26', '12', '28', '100.00', '10', '1', '1');
INSERT INTO `producto_stock` VALUES ('27', '13', '28', '100.00', '2', '1', '1');
INSERT INTO `producto_stock` VALUES ('28', '14', '29', '1500.00', '9', '2', '2');
INSERT INTO `producto_stock` VALUES ('29', '15', '29', '1600.00', '8', '2', '2');
INSERT INTO `producto_stock` VALUES ('30', '16', '1', '1200.00', '10', '1', '2');
INSERT INTO `producto_stock` VALUES ('31', '17', '3', '123.00', '9', '1', '2');

-- ----------------------------
-- Table structure for proveedor
-- ----------------------------
DROP TABLE IF EXISTS `proveedor`;
CREATE TABLE `proveedor` (
  `prov_id` int(11) NOT NULL AUTO_INCREMENT,
  `prov_nombre` varchar(256) NOT NULL,
  `prov_email` varchar(256) DEFAULT NULL,
  `prov_tel1` varchar(128) DEFAULT NULL,
  `prov_dni` varchar(128) DEFAULT NULL,
  `prov_observacion` text,
  `prov_direccion` varchar(256) DEFAULT NULL,
  `prov_fh_alta` datetime NOT NULL,
  `prov_baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`prov_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of proveedor
-- ----------------------------
INSERT INTO `proveedor` VALUES ('1', 'EKSA', '', '', '', '', '', '2019-05-31 20:35:15', '0');
INSERT INTO `proveedor` VALUES ('2', 'Proveedor de prueba', '', '', '', '', '', '2019-07-17 18:44:37', '0');

-- ----------------------------
-- Table structure for proveedor_cuenta_corriente
-- ----------------------------
DROP TABLE IF EXISTS `proveedor_cuenta_corriente`;
CREATE TABLE `proveedor_cuenta_corriente` (
  `ccte_id` int(11) NOT NULL AUTO_INCREMENT,
  `ccte_proveedor_id` int(11) NOT NULL,
  `ccte_fh` datetime NOT NULL,
  `ccte_operacion_tipo` int(11) NOT NULL,
  `ccte_operacion_id` int(11) NOT NULL,
  `ccte_importe` float(11,2) NOT NULL,
  `ccte_saldo_actual` float(11,2) NOT NULL,
  PRIMARY KEY (`ccte_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of proveedor_cuenta_corriente
-- ----------------------------
INSERT INTO `proveedor_cuenta_corriente` VALUES ('1', '1', '2019-05-31 00:00:00', '2', '1', '1000.00', '-1000.00');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('2', '1', '2019-05-28 00:00:00', '4', '1', '100.00', '-900.00');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('3', '1', '2019-05-31 00:00:00', '4', '2', '10.20', '-889.80');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('4', '1', '2019-05-31 00:00:00', '4', '3', '10.00', '-879.80');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('5', '1', '2019-07-17 00:00:00', '2', '2', '6803.00', '-7682.80');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('6', '1', '2019-07-17 00:00:00', '2', '3', '91000.00', '-98682.80');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('7', '1', '2019-07-17 00:00:00', '2', '4', '26000.00', '-124682.80');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('8', '1', '2019-07-17 00:00:00', '2', '5', '34400.00', '-159082.80');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('9', '1', '2019-07-17 00:00:00', '2', '6', '130000.00', '-289082.81');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('10', '1', '2019-07-17 00:00:00', '2', '7', '351500.00', '-640582.81');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('11', '1', '2019-07-17 00:00:00', '2', '8', '175000.00', '-815582.81');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('12', '2', '2019-07-17 00:00:00', '2', '9', '6250.00', '-6250.00');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('13', '1', '2019-07-18 00:00:00', '2', '10', '45100.00', '-860682.81');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('14', '1', '2019-07-22 00:00:00', '2', '11', '900.00', '-861582.81');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('15', '1', '2019-08-05 00:00:00', '2', '12', '1000.00', '-862582.81');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('16', '1', '2019-08-05 00:00:00', '2', '13', '1000.00', '-863582.81');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('17', '1', '2019-08-05 00:00:00', '2', '14', '30000.00', '-893582.81');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('18', '2', '2019-08-05 00:00:00', '2', '15', '16000.00', '-22250.00');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('19', '2', '2019-08-09 00:00:00', '2', '16', '12000.00', '-34250.00');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('20', '2', '2019-08-09 00:00:00', '4', '4', '2500.00', '-31750.00');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('21', '2', '2019-08-09 00:00:00', '4', '5', '9500.00', '-22250.00');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('22', '1', '2019-08-09 00:00:00', '2', '17', '1230.00', '-894812.81');
INSERT INTO `proveedor_cuenta_corriente` VALUES ('23', '1', '2019-08-14 00:00:00', '4', '6', '500.00', '-894312.81');

-- ----------------------------
-- Table structure for puesto
-- ----------------------------
DROP TABLE IF EXISTS `puesto`;
CREATE TABLE `puesto` (
  `puesto_id` int(11) NOT NULL AUTO_INCREMENT,
  `puesto_nombre` varchar(256) NOT NULL,
  PRIMARY KEY (`puesto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of puesto
-- ----------------------------
INSERT INTO `puesto` VALUES ('1', 'La Plata');
INSERT INTO `puesto` VALUES ('2', 'Junin');
INSERT INTO `puesto` VALUES ('3', 'Capital');
INSERT INTO `puesto` VALUES ('4', 'MDQ');
INSERT INTO `puesto` VALUES ('5', 'Brasil');

-- ----------------------------
-- Table structure for rol
-- ----------------------------
DROP TABLE IF EXISTS `rol`;
CREATE TABLE `rol` (
  `rol_id` int(11) NOT NULL AUTO_INCREMENT,
  `rol_nombre` varchar(45) NOT NULL,
  `rol_baja` tinyint(4) NOT NULL,
  PRIMARY KEY (`rol_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rol
-- ----------------------------
INSERT INTO `rol` VALUES ('1', 'Administrador', '0');
INSERT INTO `rol` VALUES ('2', 'Usuario Basic', '0');
INSERT INTO `rol` VALUES ('3', 'Usuario Plus', '0');
INSERT INTO `rol` VALUES ('4', 'Usuario Premium', '0');

-- ----------------------------
-- Table structure for talle
-- ----------------------------
DROP TABLE IF EXISTS `talle`;
CREATE TABLE `talle` (
  `talle_id` int(11) NOT NULL AUTO_INCREMENT,
  `talle_nombre` varchar(256) NOT NULL,
  `talle_baja` int(1) NOT NULL,
  PRIMARY KEY (`talle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of talle
-- ----------------------------
INSERT INTO `talle` VALUES ('1', 'S', '0');
INSERT INTO `talle` VALUES ('2', 'M', '0');

-- ----------------------------
-- Table structure for tipo_acceso
-- ----------------------------
DROP TABLE IF EXISTS `tipo_acceso`;
CREATE TABLE `tipo_acceso` (
  `tipoacc_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipoacc_nombre` varchar(45) DEFAULT NULL,
  `tipoacc_baja` tinyint(4) NOT NULL,
  PRIMARY KEY (`tipoacc_id`),
  UNIQUE KEY `tipoacc_nombre_UNIQUE` (`tipoacc_nombre`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tipo_acceso
-- ----------------------------
INSERT INTO `tipo_acceso` VALUES ('1', 'Acceso', '0');
INSERT INTO `tipo_acceso` VALUES ('2', 'Medio', '0');
INSERT INTO `tipo_acceso` VALUES ('3', 'Alto', '0');
INSERT INTO `tipo_acceso` VALUES ('4', 'Total', '0');

-- ----------------------------
-- Table structure for tipo_color
-- ----------------------------
DROP TABLE IF EXISTS `tipo_color`;
CREATE TABLE `tipo_color` (
  `color_id` int(11) NOT NULL,
  `color_nombre` varchar(256) DEFAULT NULL,
  `color_baja` int(1) DEFAULT NULL,
  PRIMARY KEY (`color_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tipo_color
-- ----------------------------

-- ----------------------------
-- Table structure for tipo_gasto
-- ----------------------------
DROP TABLE IF EXISTS `tipo_gasto`;
CREATE TABLE `tipo_gasto` (
  `tg_id` int(11) NOT NULL AUTO_INCREMENT,
  `tg_nombre` varchar(256) DEFAULT NULL,
  `tg_baja` int(1) DEFAULT NULL,
  PRIMARY KEY (`tg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tipo_gasto
-- ----------------------------
INSERT INTO `tipo_gasto` VALUES ('1', 'Sueldos', '0');
INSERT INTO `tipo_gasto` VALUES ('2', 'Gasto', '0');
INSERT INTO `tipo_gasto` VALUES ('3', 'Retiro', '0');
INSERT INTO `tipo_gasto` VALUES ('4', 'Servicios', '0');
INSERT INTO `tipo_gasto` VALUES ('5', 'Varios', '0');

-- ----------------------------
-- Table structure for tipo_talle
-- ----------------------------
DROP TABLE IF EXISTS `tipo_talle`;
CREATE TABLE `tipo_talle` (
  `talle_id` int(11) NOT NULL,
  `talle_nombre` varchar(10) NOT NULL,
  `talle_baja` int(1) DEFAULT NULL,
  PRIMARY KEY (`talle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tipo_talle
-- ----------------------------

-- ----------------------------
-- Table structure for transferencia_bancaria
-- ----------------------------
DROP TABLE IF EXISTS `transferencia_bancaria`;
CREATE TABLE `transferencia_bancaria` (
  `t_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_proveedor_id` int(11) DEFAULT NULL,
  `t_transportista_id` int(11) DEFAULT NULL,
  `t_despachante_id` int(11) DEFAULT NULL,
  `t_fh` datetime DEFAULT NULL,
  `t_monto` float(11,2) DEFAULT NULL,
  `t_banco_emisor_id` int(11) DEFAULT NULL,
  `t_banco_receptor_id` int(11) DEFAULT NULL,
  `t_comprobante` varchar(256) DEFAULT NULL,
  `t_factura` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`t_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of transferencia_bancaria
-- ----------------------------

-- ----------------------------
-- Table structure for transferencia_bancaria_terceros
-- ----------------------------
DROP TABLE IF EXISTS `transferencia_bancaria_terceros`;
CREATE TABLE `transferencia_bancaria_terceros` (
  `t_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_cliente_id` int(11) DEFAULT NULL,
  `t_fh` datetime DEFAULT NULL,
  `t_monto` float(11,2) DEFAULT NULL,
  `t_banco_emisor_id` int(11) DEFAULT NULL,
  `t_banco_receptor_id` int(11) DEFAULT NULL,
  `t_comprobante` varchar(256) DEFAULT NULL,
  `t_factura` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`t_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of transferencia_bancaria_terceros
-- ----------------------------

-- ----------------------------
-- Table structure for usuario
-- ----------------------------
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `usua_id` int(11) NOT NULL AUTO_INCREMENT,
  `usua_usrid` varchar(50) NOT NULL,
  `usua_nombre` varchar(100) DEFAULT NULL,
  `usua_pwd` varchar(32) NOT NULL,
  `usua_email` varchar(100) NOT NULL,
  `usua_tel1` varchar(45) DEFAULT NULL,
  `usua_tel2` varchar(45) DEFAULT NULL,
  `usua_baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`usua_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of usuario
-- ----------------------------
INSERT INTO `usuario` VALUES ('1', 'admin', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '-', '-', '-', '0');
INSERT INTO `usuario` VALUES ('2', 'ralis', 'Renzo Alis', 'e10adc3949ba59abbe56e057f20f883e', 'renzoalis@gmail.com', null, null, '0');
INSERT INTO `usuario` VALUES ('3', 'ggomez', 'Gaston Gomez', 'e10adc3949ba59abbe56e057f20f883e', 'gastongomeza@gmail.com', null, null, '0');
INSERT INTO `usuario` VALUES ('4', 'tablet', 'Usuario Basic', 'e10adc3949ba59abbe56e057f20f883e', '', null, null, '0');
INSERT INTO `usuario` VALUES ('6', 'cajero', 'cajero', 'e10adc3949ba59abbe56e057f20f883e', '', null, null, '0');

-- ----------------------------
-- Table structure for usuario_area
-- ----------------------------
DROP TABLE IF EXISTS `usuario_area`;
CREATE TABLE `usuario_area` (
  `usarea_id` int(11) NOT NULL AUTO_INCREMENT,
  `usarea_usua_id` int(11) NOT NULL,
  `usarea_area_id` int(11) NOT NULL,
  PRIMARY KEY (`usarea_id`),
  KEY `usuario_area_ibfk_1` (`usarea_area_id`) USING BTREE,
  KEY `usuario_area_ibfk_2` (`usarea_usua_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of usuario_area
-- ----------------------------
INSERT INTO `usuario_area` VALUES ('1', '1', '5');
INSERT INTO `usuario_area` VALUES ('2', '2', '5');
INSERT INTO `usuario_area` VALUES ('3', '3', '5');
INSERT INTO `usuario_area` VALUES ('4', '4', '1');
INSERT INTO `usuario_area` VALUES ('5', '5', '1');
INSERT INTO `usuario_area` VALUES ('6', '6', '1');

-- ----------------------------
-- Table structure for usuario_rol
-- ----------------------------
DROP TABLE IF EXISTS `usuario_rol`;
CREATE TABLE `usuario_rol` (
  `usrrol_id` int(11) NOT NULL AUTO_INCREMENT,
  `usrrol_usua_id` int(11) NOT NULL,
  `usrrol_rol_id` int(11) NOT NULL,
  `usrrol_app_id` int(11) NOT NULL,
  PRIMARY KEY (`usrrol_id`),
  KEY `fk_usuario_has_rol_rol1` (`usrrol_rol_id`) USING BTREE,
  KEY `fk_usuario_has_rol_usuario1` (`usrrol_usua_id`) USING BTREE,
  KEY `fk_usuario_rol_aplicacion1` (`usrrol_app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of usuario_rol
-- ----------------------------
INSERT INTO `usuario_rol` VALUES ('1', '1', '1', '5');
INSERT INTO `usuario_rol` VALUES ('2', '2', '1', '5');
INSERT INTO `usuario_rol` VALUES ('3', '3', '1', '5');
INSERT INTO `usuario_rol` VALUES ('4', '4', '2', '5');
INSERT INTO `usuario_rol` VALUES ('5', '5', '3', '5');
INSERT INTO `usuario_rol` VALUES ('6', '6', '4', '5');

-- ----------------------------
-- Table structure for venta
-- ----------------------------
DROP TABLE IF EXISTS `venta`;
CREATE TABLE `venta` (
  `venta_id` int(11) NOT NULL AUTO_INCREMENT,
  `venta_fh` datetime NOT NULL,
  `venta_cliente_id` int(11) NOT NULL,
  `venta_forma_pago_id` int(2) NOT NULL,
  `venta_estado_id` int(2) NOT NULL,
  `venta_monto_total` float(11,2) NOT NULL,
  `venta_cant_cuotas` int(2) NOT NULL,
  `venta_usuario_id` int(11) NOT NULL,
  `venta_monto_contado` float(11,2) NOT NULL DEFAULT '0.00',
  `venta_observacion` text,
  `venta_baja_fh` datetime DEFAULT NULL,
  `venta_archivada_fh` datetime DEFAULT NULL,
  `venta_despachada_fh` datetime DEFAULT NULL,
  `venta_descuento_porc` float(5,2) DEFAULT NULL,
  `venta_descuento_monto` float(11,2) DEFAULT NULL,
  PRIMARY KEY (`venta_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of venta
-- ----------------------------
INSERT INTO `venta` VALUES ('1', '2019-08-05 19:22:45', '9999', '1', '2', '1800.00', '0', '2', '0.00', '', null, null, null, null, null);
INSERT INTO `venta` VALUES ('2', '2019-08-05 19:32:08', '9999', '1', '2', '30000.00', '0', '2', '0.00', '', null, null, null, null, null);
INSERT INTO `venta` VALUES ('3', '2019-08-05 19:36:35', '9999', '1', '2', '37500.00', '0', '2', '0.00', '', null, null, null, null, null);
INSERT INTO `venta` VALUES ('4', '2019-08-08 18:52:17', '9999', '1', '2', '555.00', '0', '2', '0.00', '', null, null, null, null, null);
INSERT INTO `venta` VALUES ('5', '2019-08-09 15:54:35', '9999', '2', '2', '900.00', '0', '2', '0.00', '', null, null, null, null, null);
INSERT INTO `venta` VALUES ('6', '2019-08-09 16:04:44', '9999', '2', '2', '3333.00', '0', '2', '0.00', '', null, null, null, null, null);
INSERT INTO `venta` VALUES ('7', '2019-08-09 16:05:48', '9999', '3', '2', '150.00', '0', '2', '0.00', '', null, null, null, null, null);
INSERT INTO `venta` VALUES ('8', '2019-08-09 17:03:23', '9999', '1', '2', '555.00', '0', '2', '0.00', '', null, null, null, null, null);
INSERT INTO `venta` VALUES ('9', '2019-08-12 14:15:45', '9999', '1', '2', '1999.00', '0', '2', '0.00', '', null, null, null, null, null);
INSERT INTO `venta` VALUES ('10', '2019-08-12 17:15:40', '9999', '0', '1', '555.00', '0', '2', '0.00', '', null, null, null, null, null);
INSERT INTO `venta` VALUES ('11', '2019-08-14 17:29:13', '9999', '1', '2', '1111.00', '0', '1', '0.00', '', null, null, null, null, null);
INSERT INTO `venta` VALUES ('12', '2019-08-14 17:29:48', '9999', '1', '2', '2500.00', '0', '1', '0.00', '', null, null, null, null, null);
INSERT INTO `venta` VALUES ('13', '2019-08-14 17:29:57', '9999', '3', '2', '2500.00', '0', '1', '0.00', '', null, null, null, null, null);

-- ----------------------------
-- Table structure for venta_concepto
-- ----------------------------
DROP TABLE IF EXISTS `venta_concepto`;
CREATE TABLE `venta_concepto` (
  `vc_id` int(11) NOT NULL AUTO_INCREMENT,
  `vc_venta_id` int(11) NOT NULL,
  `vc_tipo` int(11) NOT NULL,
  `vc_observacion` varchar(256) DEFAULT NULL,
  `vc_fh` datetime DEFAULT NULL,
  `vc_monto` int(11) DEFAULT NULL,
  `vc_op_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`vc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of venta_concepto
-- ----------------------------

-- ----------------------------
-- Table structure for venta_concepto_tipo
-- ----------------------------
DROP TABLE IF EXISTS `venta_concepto_tipo`;
CREATE TABLE `venta_concepto_tipo` (
  `vc_tipo_id` int(11) NOT NULL AUTO_INCREMENT,
  `vc_tipo_nombre` varchar(90) NOT NULL,
  `vc_tipo_baja` int(1) NOT NULL,
  PRIMARY KEY (`vc_tipo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of venta_concepto_tipo
-- ----------------------------
INSERT INTO `venta_concepto_tipo` VALUES ('1', 'Nota de Credito', '0');
INSERT INTO `venta_concepto_tipo` VALUES ('2', 'Nota de Debito', '0');
INSERT INTO `venta_concepto_tipo` VALUES ('3', 'Observacion', '0');
INSERT INTO `venta_concepto_tipo` VALUES ('4', 'Facturacion', '0');

-- ----------------------------
-- Table structure for venta_detalle
-- ----------------------------
DROP TABLE IF EXISTS `venta_detalle`;
CREATE TABLE `venta_detalle` (
  `detalle_id` int(11) NOT NULL AUTO_INCREMENT,
  `detalle_venta_id` int(11) NOT NULL,
  `detalle_prod_id` int(11) NOT NULL,
  `detalle_prod_cant` int(11) NOT NULL,
  `detalle_prod_precio_u` float(11,2) NOT NULL,
  `detalle_prod_costo_u` float(11,2) DEFAULT NULL,
  `detalle_prod_precio_u_original` float(11,2) DEFAULT NULL,
  `detalle_prod_talle_id` int(11) DEFAULT NULL,
  `detalle_prod_color_id` int(11) DEFAULT NULL,
  `detalle_cant_devueltos` int(11) DEFAULT NULL,
  PRIMARY KEY (`detalle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of venta_detalle
-- ----------------------------
INSERT INTO `venta_detalle` VALUES ('1', '1', '28', '12', '150.00', null, null, '1', '1', '12');
INSERT INTO `venta_detalle` VALUES ('2', '2', '29', '12', '2500.00', null, null, '2', '2', '3');
INSERT INTO `venta_detalle` VALUES ('3', '3', '29', '15', '2500.00', null, null, '2', '2', '13');
INSERT INTO `venta_detalle` VALUES ('4', '4', '1', '1', '555.50', null, null, '2', '2', null);
INSERT INTO `venta_detalle` VALUES ('5', '5', '2', '1', '900.00', null, null, '2', '2', '1');
INSERT INTO `venta_detalle` VALUES ('6', '6', '1', '6', '555.50', null, null, '1', '2', null);
INSERT INTO `venta_detalle` VALUES ('7', '7', '28', '1', '150.00', null, null, '1', '1', '1');
INSERT INTO `venta_detalle` VALUES ('8', '8', '1', '1', '555.50', null, null, '2', '2', null);
INSERT INTO `venta_detalle` VALUES ('9', '9', '3', '1', '1999.80', null, null, '1', '2', null);
INSERT INTO `venta_detalle` VALUES ('10', '10', '1', '1', '555.50', null, null, '2', '2', null);
INSERT INTO `venta_detalle` VALUES ('11', '11', '1', '2', '555.50', null, null, '1', '2', null);
INSERT INTO `venta_detalle` VALUES ('12', '12', '29', '1', '2500.00', null, null, '2', '2', null);
INSERT INTO `venta_detalle` VALUES ('13', '13', '29', '1', '2500.00', null, null, '2', '2', '1');

-- ----------------------------
-- Table structure for venta_detalle_stock
-- ----------------------------
DROP TABLE IF EXISTS `venta_detalle_stock`;
CREATE TABLE `venta_detalle_stock` (
  `vds_id` int(11) NOT NULL AUTO_INCREMENT,
  `vds_venta_detalle_id` int(11) DEFAULT NULL,
  `vds_prodstock_id` int(11) DEFAULT NULL,
  `vds_prod_cant` int(11) NOT NULL,
  `vds_cant_devuelta` int(11) DEFAULT NULL,
  PRIMARY KEY (`vds_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of venta_detalle_stock
-- ----------------------------
INSERT INTO `venta_detalle_stock` VALUES ('1', '1', '26', '10', '10');
INSERT INTO `venta_detalle_stock` VALUES ('2', '1', '27', '2', '2');
INSERT INTO `venta_detalle_stock` VALUES ('3', '2', '28', '12', '3');
INSERT INTO `venta_detalle_stock` VALUES ('4', '3', '28', '9', '9');
INSERT INTO `venta_detalle_stock` VALUES ('5', '3', '29', '6', '4');
INSERT INTO `venta_detalle_stock` VALUES ('6', '4', '19', '1', null);
INSERT INTO `venta_detalle_stock` VALUES ('7', '5', '15', '1', '1');
INSERT INTO `venta_detalle_stock` VALUES ('8', '6', '17', '6', null);
INSERT INTO `venta_detalle_stock` VALUES ('9', '7', '26', '1', '1');
INSERT INTO `venta_detalle_stock` VALUES ('10', '8', '19', '1', null);
INSERT INTO `venta_detalle_stock` VALUES ('11', '9', '31', '1', null);
INSERT INTO `venta_detalle_stock` VALUES ('12', '10', '19', '1', null);
INSERT INTO `venta_detalle_stock` VALUES ('13', '11', '17', '2', null);
INSERT INTO `venta_detalle_stock` VALUES ('14', '12', '28', '1', null);
INSERT INTO `venta_detalle_stock` VALUES ('15', '13', '28', '1', '1');

-- ----------------------------
-- Table structure for venta_estado
-- ----------------------------
DROP TABLE IF EXISTS `venta_estado`;
CREATE TABLE `venta_estado` (
  `vestado_id` int(11) NOT NULL AUTO_INCREMENT,
  `vestado_descripcion` varchar(128) NOT NULL,
  `vestado_baja` int(1) NOT NULL,
  PRIMARY KEY (`vestado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of venta_estado
-- ----------------------------
INSERT INTO `venta_estado` VALUES ('1', 'Pendiente', '0');
INSERT INTO `venta_estado` VALUES ('2', 'Saldada', '0');
INSERT INTO `venta_estado` VALUES ('3', 'Cancelada', '0');
INSERT INTO `venta_estado` VALUES ('4', 'Despachada', '0');
INSERT INTO `venta_estado` VALUES ('5', 'Sin stock', '0');
INSERT INTO `venta_estado` VALUES ('6', 'Archivada', '0');

-- ----------------------------
-- Table structure for venta_forma_pago
-- ----------------------------
DROP TABLE IF EXISTS `venta_forma_pago`;
CREATE TABLE `venta_forma_pago` (
  `vf_id` int(11) NOT NULL AUTO_INCREMENT,
  `vf_desc` varchar(45) NOT NULL,
  `vf_baja` int(1) NOT NULL,
  PRIMARY KEY (`vf_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of venta_forma_pago
-- ----------------------------
INSERT INTO `venta_forma_pago` VALUES ('1', 'Efectivo', '0');
INSERT INTO `venta_forma_pago` VALUES ('2', 'MercadoPago', '0');
INSERT INTO `venta_forma_pago` VALUES ('3', 'Tarjeta credito', '0');
INSERT INTO `venta_forma_pago` VALUES ('4', 'Tarjeta debito', '0');
INSERT INTO `venta_forma_pago` VALUES ('5', 'Bono', '0');

-- ----------------------------
-- View structure for view_usuario_login
-- ----------------------------
DROP VIEW IF EXISTS `view_usuario_login`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER  VIEW `view_usuario_login` AS (select `usuario_rol`.`usrrol_id` AS `usrrol_id`,`usuario`.`usua_id` AS `usua_id`,`usuario`.`usua_usrid` AS `usua_usrid`,`usuario`.`usua_nombre` AS `usua_nombre`,`usuario`.`usua_pwd` AS `usua_pwd`,`usuario`.`usua_email` AS `usua_email`,`rol`.`rol_nombre` AS `rol_nombre`,`aplicacion`.`app_id` AS `app_id`,`aplicacion`.`app_nombre` AS `app_nombre`,`permiso`.`permiso_id` AS `permiso_id`,`tipo_acceso`.`tipoacc_id` AS `tipoacc_id`,`tipo_acceso`.`tipoacc_nombre` AS `tipoacc_nombre`,`modulo`.`mod_id` AS `mod_id`,`modulo`.`mod_nombre` AS `mod_nombre`,`modulo`.`mod_baja` AS `mod_baja`,`modulo_paginas`.`modpag_id` AS `modpag_id`,`modulo_paginas`.`modpag_scriptname` AS `modpag_scriptname` from ((((((((`usuario_rol` join `usuario` on((`usuario`.`usua_id` = `usuario_rol`.`usrrol_usua_id`))) join `rol` on((`rol`.`rol_id` = `usuario_rol`.`usrrol_rol_id`))) join `aplicacion` on((`usuario_rol`.`usrrol_app_id` = `aplicacion`.`app_id`))) join `permiso` on((`permiso`.`permiso_rol_id` = `usuario_rol`.`usrrol_rol_id`))) join `tipo_acceso` on((`tipo_acceso`.`tipoacc_id` = `permiso`.`permiso_tipoacc_id`))) join `modulo` on(((`modulo`.`mod_id` = `permiso`.`permiso_mod_id`) and (`modulo`.`mod_app_id` = `usuario_rol`.`usrrol_app_id`)))) join `modulo_paginas` on((`modulo_paginas`.`modpag_mod_id` = `modulo`.`mod_id`))) left join `modulo_tablas` on((`modulo_tablas`.`modtab_mod_id` = `modulo`.`mod_id`))) where ((`usuario`.`usua_baja` = 0) and (`aplicacion`.`app_baja` = 0))) ;
