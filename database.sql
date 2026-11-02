-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.7 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for ql_phongkham_trasanh
CREATE DATABASE IF NOT EXISTS `ql_phongkham_trasanh` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ql_phongkham_trasanh`;

-- Dumping structure for table ql_phongkham_trasanh.benhnhan
CREATE TABLE IF NOT EXISTS `benhnhan` (
  `MaBN` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `HoTenBN` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NgaySinh` date NOT NULL,
  `GioiTinh` enum('Nam','Nữ','Khác') COLLATE utf8mb4_unicode_ci NOT NULL,
  `SoDienThoai` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DiaChi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TienSuBenh` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`MaBN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ql_phongkham_trasanh.benhnhan: ~2 rows (approximately)
INSERT INTO `benhnhan` (`MaBN`, `HoTenBN`, `NgaySinh`, `GioiTinh`, `SoDienThoai`, `DiaChi`, `TienSuBenh`) VALUES
	('BN001', 'Phạm Văn An', '1995-04-12', 'Nam', '0912345678', 'Quận 8, TP.HCM', 'Không dị ứng thuốc'),
	('BN002', 'Lê Thị Bích', '2001-09-25', 'Nữ', '0987654321', 'Quận 5, TP.HCM', 'Tiền sử viêm dạ dày');

-- Dumping structure for table ql_phongkham_trasanh.chitiet_donthuoc
CREATE TABLE IF NOT EXISTS `chitiet_donthuoc` (
  `MaLK` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MaThuoc` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `SoLuongKe` int NOT NULL,
  `LieuDung` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DonGiaBan` decimal(12,2) NOT NULL,
  PRIMARY KEY (`MaLK`,`MaThuoc`),
  KEY `MaThuoc` (`MaThuoc`),
  CONSTRAINT `chitiet_donthuoc_ibfk_1` FOREIGN KEY (`MaLK`) REFERENCES `luotkham` (`MaLK`),
  CONSTRAINT `chitiet_donthuoc_ibfk_2` FOREIGN KEY (`MaThuoc`) REFERENCES `thuoc` (`MaThuoc`),
  CONSTRAINT `chitiet_donthuoc_chk_1` CHECK ((`SoLuongKe` > 0)),
  CONSTRAINT `chitiet_donthuoc_chk_2` CHECK ((`DonGiaBan` >= 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ql_phongkham_trasanh.chitiet_donthuoc: ~1 rows (approximately)
INSERT INTO `chitiet_donthuoc` (`MaLK`, `MaThuoc`, `SoLuongKe`, `LieuDung`, `DonGiaBan`) VALUES
	('LK001', 'TH003', 10, 'Dữ liệu mẫu để thử phần mềm', 1500.00);

-- Dumping structure for table ql_phongkham_trasanh.dichvu
CREATE TABLE IF NOT EXISTS `dichvu` (
  `MaDV` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TenDV` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DonGiaDV` decimal(12,2) NOT NULL DEFAULT '0.00',
  `TrangThai` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`MaDV`),
  CONSTRAINT `dichvu_chk_1` CHECK ((`DonGiaDV` >= 0)),
  CONSTRAINT `dichvu_chk_2` CHECK ((`TrangThai` in (0,1)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ql_phongkham_trasanh.dichvu: ~2 rows (approximately)
INSERT INTO `dichvu` (`MaDV`, `TenDV`, `DonGiaDV`, `TrangThai`) VALUES
	('DV01', 'Khám Nội tổng quát', 150000.00, 1),
	('DV02', 'Khám Tai - Mũi - Họng', 120000.00, 1);

-- Dumping structure for table ql_phongkham_trasanh.hoadon
CREATE TABLE IF NOT EXISTS `hoadon` (
  `MaHD` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NgayLap` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `TienKham` decimal(12,2) NOT NULL DEFAULT '0.00',
  `TienThuoc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `TongTien` decimal(12,2) NOT NULL DEFAULT '0.00',
  `TrangThaiTT` enum('ChuaThanhToan','DaThanhToan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ChuaThanhToan',
  `MaLK` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MaThuNgan` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`MaHD`),
  UNIQUE KEY `MaLK` (`MaLK`),
  KEY `MaThuNgan` (`MaThuNgan`),
  CONSTRAINT `hoadon_ibfk_1` FOREIGN KEY (`MaLK`) REFERENCES `luotkham` (`MaLK`),
  CONSTRAINT `hoadon_ibfk_2` FOREIGN KEY (`MaThuNgan`) REFERENCES `nguoidung` (`MaND`),
  CONSTRAINT `hoadon_chk_1` CHECK ((`TienKham` >= 0)),
  CONSTRAINT `hoadon_chk_2` CHECK ((`TienThuoc` >= 0)),
  CONSTRAINT `hoadon_chk_3` CHECK ((`TongTien` = (`TienKham` + `TienThuoc`)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ql_phongkham_trasanh.hoadon: ~1 rows (approximately)
INSERT INTO `hoadon` (`MaHD`, `NgayLap`, `TienKham`, `TienThuoc`, `TongTien`, `TrangThaiTT`, `MaLK`, `MaThuNgan`) VALUES
	('HD001', '2026-10-26 09:00:00', 150000.00, 15000.00, 165000.00, 'ChuaThanhToan', 'LK001', 'ND004');

-- Dumping structure for table ql_phongkham_trasanh.luotkham
CREATE TABLE IF NOT EXISTS `luotkham` (
  `MaLK` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NgayKham` date NOT NULL,
  `SoThuTu` int NOT NULL,
  `TrieuChung` text COLLATE utf8mb4_unicode_ci,
  `ChanDoan` text COLLATE utf8mb4_unicode_ci,
  `GiaKham` decimal(12,2) NOT NULL DEFAULT '0.00',
  `TrangThaiLK` enum('ChoKham','ChoThanhToan','HoanTat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ChoKham',
  `MaBN` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MaPK` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MaDV` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MaBacSi` char(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`MaLK`),
  UNIQUE KEY `MaPK` (`MaPK`,`NgayKham`,`SoThuTu`),
  KEY `MaBN` (`MaBN`),
  KEY `MaDV` (`MaDV`),
  KEY `MaBacSi` (`MaBacSi`),
  CONSTRAINT `luotkham_ibfk_1` FOREIGN KEY (`MaBN`) REFERENCES `benhnhan` (`MaBN`),
  CONSTRAINT `luotkham_ibfk_2` FOREIGN KEY (`MaPK`) REFERENCES `phongkham` (`MaPK`),
  CONSTRAINT `luotkham_ibfk_3` FOREIGN KEY (`MaDV`) REFERENCES `dichvu` (`MaDV`),
  CONSTRAINT `luotkham_ibfk_4` FOREIGN KEY (`MaBacSi`) REFERENCES `nguoidung` (`MaND`),
  CONSTRAINT `luotkham_chk_1` CHECK ((`SoThuTu` > 0)),
  CONSTRAINT `luotkham_chk_2` CHECK ((`GiaKham` >= 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ql_phongkham_trasanh.luotkham: ~2 rows (approximately)
INSERT INTO `luotkham` (`MaLK`, `NgayKham`, `SoThuTu`, `TrieuChung`, `ChanDoan`, `GiaKham`, `TrangThaiLK`, `MaBN`, `MaPK`, `MaDV`, `MaBacSi`) VALUES
	('LK001', '2026-10-26', 1, 'Triệu chứng mẫu', 'Chẩn đoán mẫu', 150000.00, 'ChoThanhToan', 'BN001', 'PK01', 'DV01', 'ND003'),
	('LK002', '2026-10-26', 2, NULL, NULL, 150000.00, 'ChoKham', 'BN002', 'PK01', 'DV01', NULL);

-- Dumping structure for table ql_phongkham_trasanh.nguoidung
CREATE TABLE IF NOT EXISTS `nguoidung` (
  `MaND` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TenDangNhap` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MatKhau` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `HoTen` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `VaiTro` enum('LeTan','BacSi','ThuNgan','ChuPhongKham','QuanTriVien') COLLATE utf8mb4_unicode_ci NOT NULL,
  `SoDienThoai` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TrangThai` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`MaND`),
  UNIQUE KEY `TenDangNhap` (`TenDangNhap`),
  CONSTRAINT `nguoidung_chk_1` CHECK ((`TrangThai` in (0,1)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ql_phongkham_trasanh.nguoidung: ~5 rows (approximately)
INSERT INTO `nguoidung` (`MaND`, `TenDangNhap`, `MatKhau`, `HoTen`, `VaiTro`, `SoDienThoai`, `TrangThai`) VALUES
	('ND001', 'admin', '$2b$12$lhhD.niVXnx1D5z.3zJoh.curRjCcH0hDAu9S7T9clNPW47pd7Poq', 'Quản Trị Viên', 'QuanTriVien', '0901000001', 1),
	('ND002', 'letan', '$2b$12$lhhD.niVXnx1D5z.3zJoh.curRjCcH0hDAu9S7T9clNPW47pd7Poq', 'Nguyễn Thị Lễ Tân', 'LeTan', '0901000002', 1),
	('ND003', 'bacsi', '$2b$12$lhhD.niVXnx1D5z.3zJoh.curRjCcH0hDAu9S7T9clNPW47pd7Poq', 'BS. Trần Văn Khám', 'BacSi', '0901000003', 1),
	('ND004', 'thungan', '$2b$12$lhhD.niVXnx1D5z.3zJoh.curRjCcH0hDAu9S7T9clNPW47pd7Poq', 'Lê Thị Thu Ngân', 'ThuNgan', '0901000004', 1),
	('ND005', 'chupk', '$2b$12$lhhD.niVXnx1D5z.3zJoh.curRjCcH0hDAu9S7T9clNPW47pd7Poq', 'Phan Thái Nguyên', 'ChuPhongKham', '0901000005', 1);

-- Dumping structure for table ql_phongkham_trasanh.phongkham
CREATE TABLE IF NOT EXISTS `phongkham` (
  `MaPK` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TenPK` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MoTa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`MaPK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ql_phongkham_trasanh.phongkham: ~2 rows (approximately)
INSERT INTO `phongkham` (`MaPK`, `TenPK`, `MoTa`) VALUES
	('PK01', 'Phòng khám Nội tổng quát 1', 'Tầng 1 - Khám bệnh nội khoa'),
	('PK02', 'Phòng khám Tai - Mũi - Họng', 'Tầng 1 - Chuyên khoa TMH');

-- Dumping structure for table ql_phongkham_trasanh.thuoc
CREATE TABLE IF NOT EXISTS `thuoc` (
  `MaThuoc` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TenThuoc` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DonViTinh` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DonGiaThuoc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `SoLuongTon` int NOT NULL DEFAULT '0',
  `HanSuDung` date NOT NULL,
  `CachDung` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`MaThuoc`),
  CONSTRAINT `thuoc_chk_1` CHECK ((`DonGiaThuoc` >= 0)),
  CONSTRAINT `thuoc_chk_2` CHECK ((`SoLuongTon` >= 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ql_phongkham_trasanh.thuoc: ~3 rows (approximately)
INSERT INTO `thuoc` (`MaThuoc`, `TenThuoc`, `DonViTinh`, `DonGiaThuoc`, `SoLuongTon`, `HanSuDung`, `CachDung`) VALUES
	('TH001', 'Paracetamol 500mg', 'Viên', 2000.00, 500, '2027-12-31', 'Theo chỉ định của bác sĩ'),
	('TH002', 'Amoxicillin 500mg', 'Viên', 5000.00, 300, '2027-06-30', 'Theo chỉ định của bác sĩ'),
	('TH003', 'Vitamin C 500mg', 'Viên', 1500.00, 400, '2028-01-15', 'Theo chỉ định của bác sĩ');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
