/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : db_permintaan

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 22/12/2024 23:40:57
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for barang
-- ----------------------------
DROP TABLE IF EXISTS `barang`;
CREATE TABLE `barang`  (
  `id_barang` int NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `harga` decimal(10, 2) NOT NULL,
  `stok` int NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `kategori_barang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `satuan` enum('pcs','box','dus') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_barang`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for departement
-- ----------------------------
DROP TABLE IF EXISTS `departement`;
CREATE TABLE `departement`  (
  `id_departement` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_dept_head` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_departement` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_dept_head` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_departement`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dept_head
-- ----------------------------
DROP TABLE IF EXISTS `dept_head`;
CREATE TABLE `dept_head`  (
  `id_dept_head` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_dept_head` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_dept_head`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for detail_permintaan
-- ----------------------------
DROP TABLE IF EXISTS `detail_permintaan`;
CREATE TABLE `detail_permintaan`  (
  `id_detail_permintaan` int NOT NULL AUTO_INCREMENT,
  `id_permintaan` int NOT NULL,
  `id_barang` int NOT NULL,
  `jumlah` int NULL DEFAULT NULL,
  PRIMARY KEY (`id_detail_permintaan`) USING BTREE,
  INDEX `departemen_id`(`id_permintaan` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 65 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for email_reset_logs
-- ----------------------------
DROP TABLE IF EXISTS `email_reset_logs`;
CREATE TABLE `email_reset_logs`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token_expiry` datetime NOT NULL,
  `sent_at` datetime NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('sent','used','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'sent',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC, `token` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for inventori_barang
-- ----------------------------
DROP TABLE IF EXISTS `inventori_barang`;
CREATE TABLE `inventori_barang`  (
  `id_inventori_barang` int NOT NULL AUTO_INCREMENT,
  `id_barang` int NULL DEFAULT NULL,
  `id_user` int NULL DEFAULT NULL,
  `jumlah` int NOT NULL,
  `tanggal` datetime NULL DEFAULT NULL,
  `status` enum('masuk','keluar') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `bukti` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_inventori_barang`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 59 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for log_permintaan
-- ----------------------------
DROP TABLE IF EXISTS `log_permintaan`;
CREATE TABLE `log_permintaan`  (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_permintaan` int NOT NULL,
  `id_user` int NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `tanggal_log` datetime NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_log`) USING BTREE,
  INDEX `departemen_id`(`id_permintaan` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 132 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for pengajuan
-- ----------------------------
DROP TABLE IF EXISTS `pengajuan`;
CREATE TABLE `pengajuan`  (
  `id_pengajuan` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_permintaan` int NOT NULL,
  PRIMARY KEY (`id_pengajuan`) USING BTREE,
  INDEX `departemen_id`(`id_user` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 53 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for penjadwalan
-- ----------------------------
DROP TABLE IF EXISTS `penjadwalan`;
CREATE TABLE `penjadwalan`  (
  `id_penjadwalan` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_permintaan` int NULL DEFAULT NULL,
  `tanggal` datetime NULL DEFAULT NULL,
  `Keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_penjadwalan`) USING BTREE,
  INDEX `departemen_id`(`id_user` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for permintaan
-- ----------------------------
DROP TABLE IF EXISTS `permintaan`;
CREATE TABLE `permintaan`  (
  `id_permintaan` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `status` enum('Menunggu Diterima','Diterima HRGA','Ditolak HRGA','Diterima PUD/Purchasing','Ditolak PUD/Purchasing','Dijadwalkan HRGA','Sudah Diterima Departement','Batas') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'Menunggu Diterima',
  `tanggal_permintaan` datetime NULL DEFAULT NULL,
  `tanggal_diterima` datetime NULL DEFAULT NULL,
  `bukti_terima` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_permintaan`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 69 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama_user` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `level` enum('1','2','3','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_active` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `id_departement` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_user`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for web
-- ----------------------------
DROP TABLE IF EXISTS `web`;
CREATE TABLE `web`  (
  `id_web` int NOT NULL AUTO_INCREMENT,
  `logo` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nama` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `author` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `nohp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_web`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
