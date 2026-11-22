SET FOREIGN_KEY_CHECKS = 0;
CREATE TABLE `nguoidung` (
  `MaND` char(10) NOT NULL,
  `TenDangNhap` varchar(50) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `HoTen` varchar(100) NOT NULL,
  `VaiTro` enum('LeTan','BacSi','ThuNgan','ChuPhongKham','QuanTriVien') NOT NULL,
  `SoDienThoai` varchar(15) DEFAULT NULL,
  `TrangThai` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`MaND`),
  UNIQUE KEY `TenDangNhap` (`TenDangNhap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `nguoidung` (`MaND`, `TenDangNhap`, `MatKhau`, `HoTen`, `VaiTro`, `SoDienThoai`, `TrangThai`) VALUES
('ND001', 'admin', '123456', 'Quản Trị Viên', 'QuanTriVien', '0901000001', 1),
('ND002', 'letan', '123456', 'Nguyễn Thị Thúy', 'LeTan', '0901000002', 1),
('ND003', 'bacsi', '123456', 'BS. Trần Văn Nguyên', 'BacSi', '0901000003', 1),
('ND004', 'thungan', '123456', 'Lê Thị Thu Ngân', 'ThuNgan', '0901000004', 1),

CREATE TABLE `benhnhan` (
  `MaBN` char(10) NOT NULL,
  `HoTenBN` varchar(100) NOT NULL,
  `NgaySinh` date NOT NULL,
  `GioiTinh` enum('Nam','Nữ','Khác') NOT NULL,
  `SoDienThoai` varchar(15) NOT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `TienSuBenh` text DEFAULT NULL,
  PRIMARY KEY (`MaBN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `benhnhan` (`MaBN`, `HoTenBN`, `NgaySinh`, `GioiTinh`, `SoDienThoai`, `DiaChi`, `TienSuBenh`) VALUES
('BN001', 'Phạm Văn An', '1995-04-12', 'Nam', '0912345678', 'Quận 8, TP.HCM', 'Không dị ứng thuốc'),
('BN002', 'Lê Thị Bích', '2001-09-25', 'Nữ', '0987654321', 'Quận 5, TP.HCM', 'Tiền sử viêm dạ dày');


CREATE TABLE `phongkham` (
  `MaPK` char(10) NOT NULL,
  `TenPK` varchar(100) NOT NULL,
  `MoTa` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`MaPK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `phongkham` (`MaPK`, `TenPK`, `MoTa`) VALUES
('PK01', 'Phòng khám Nội tổng quát 1', 'Tầng 1 - Khám bệnh nội khoa'),
('PK02', 'Phòng khám Tai - Mũi - Họng', 'Tầng 1 - Chuyên khoa TMH');


CREATE TABLE `dichvu` (
  `MaDV` char(10) NOT NULL,
  `TenDV` varchar(100) NOT NULL,
  `DonGiaDV` decimal(12,2) NOT NULL DEFAULT 0.00,
  `TrangThai` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`MaDV`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `dichvu` (`MaDV`, `TenDV`, `DonGiaDV`, `TrangThai`) VALUES
('DV01', 'Khám Nội tổng quát', 150000.00, 1),
('DV02', 'Khám Tai - Mũi - Họng', 120000.00, 1);


CREATE TABLE `thuoc` (
  `MaThuoc` char(10) NOT NULL,
  `TenThuoc` varchar(150) NOT NULL,
  `DonViTinh` varchar(30) NOT NULL,
  `DonGiaThuoc` decimal(12,2) NOT NULL DEFAULT 0.00,
  `SoLuongTon` int NOT NULL DEFAULT 0,
  `HanSuDung` date NOT NULL,
  `CachDung` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`MaThuoc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `thuoc` (`MaThuoc`, `TenThuoc`, `DonViTinh`, `DonGiaThuoc`, `SoLuongTon`, `HanSuDung`, `CachDung`) VALUES
('TH001', 'Paracetamol 500mg', 'Viên', 2000.00, 500, '2027-12-31', 'Theo chỉ định của bác sĩ'),
('TH002', 'Amoxicillin 500mg', 'Viên', 5000.00, 300, '2027-06-30', 'Theo chỉ định của bác sĩ'),
('TH003', 'Vitamin C 500mg', 'Viên', 1500.00, 400, '2028-01-15', 'Theo chỉ định của bác sĩ');


CREATE TABLE `luotkham` (
  `MaLK` char(10) NOT NULL,
  `NgayKham` date NOT NULL,
  `SoThuTu` int NOT NULL,
  `TrieuChung` text DEFAULT NULL,
  `ChanDoan` text DEFAULT NULL,
  `GiaKham` decimal(12,2) NOT NULL DEFAULT 0.00,
  `TrangThaiLK` enum('ChoKham','ChoThanhToan','HoanTat') NOT NULL DEFAULT 'ChoKham',
  `MaBN` char(10) NOT NULL,
  `MaPK` char(10) NOT NULL,
  `MaDV` char(10) NOT NULL,
  `MaBacSi` char(10) DEFAULT NULL,
  PRIMARY KEY (`MaLK`),
  UNIQUE KEY `MaPK` (`MaPK`,`NgayKham`,`SoThuTu`),
  KEY `MaBN` (`MaBN`),
  KEY `MaDV` (`MaDV`),
  KEY `MaBacSi` (`MaBacSi`),
  CONSTRAINT `luotkham_ibfk_1` FOREIGN KEY (`MaBN`) REFERENCES `benhnhan` (`MaBN`),
  CONSTRAINT `luotkham_ibfk_2` FOREIGN KEY (`MaPK`) REFERENCES `phongkham` (`MaPK`),
  CONSTRAINT `luotkham_ibfk_3` FOREIGN KEY (`MaDV`) REFERENCES `dichvu` (`MaDV`),
  CONSTRAINT `luotkham_ibfk_4` FOREIGN KEY (`MaBacSi`) REFERENCES `nguoidung` (`MaND`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `luotkham` (`MaLK`, `NgayKham`, `SoThuTu`, `TrieuChung`, `ChanDoan`, `GiaKham`, `TrangThaiLK`, `MaBN`, `MaPK`, `MaDV`, `MaBacSi`) VALUES
('LK001', '2026-10-26', 1, 'Triệu chứng mẫu', 'Chẩn đoán mẫu', 150000.00, 'ChoThanhToan', 'BN001', 'PK01', 'DV01', 'ND003'),
('LK002', '2026-10-26', 2, NULL, NULL, 150000.00, 'ChoKham', 'BN002', 'PK01', 'DV01', NULL);


CREATE TABLE `chitiet_donthuoc` (
  `MaLK` char(10) NOT NULL,
  `MaThuoc` char(10) NOT NULL,
  `SoLuongKe` int NOT NULL,
  `LieuDung` varchar(255) NOT NULL,
  `DonGiaBan` decimal(12,2) NOT NULL,
  PRIMARY KEY (`MaLK`,`MaThuoc`),
  KEY `MaThuoc` (`MaThuoc`),
  CONSTRAINT `chitiet_donthuoc_ibfk_1` FOREIGN KEY (`MaLK`) REFERENCES `luotkham` (`MaLK`),
  CONSTRAINT `chitiet_donthuoc_ibfk_2` FOREIGN KEY (`MaThuoc`) REFERENCES `thuoc` (`MaThuoc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `chitiet_donthuoc` (`MaLK`, `MaThuoc`, `SoLuongKe`, `LieuDung`, `DonGiaBan`) VALUES
('LK001', 'TH003', 10, 'Dữ liệu mẫu để thử phần mềm', 1500.00);


CREATE TABLE `hoadon` (
  `MaHD` char(10) NOT NULL,
  `NgayLap` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `TienKham` decimal(12,2) NOT NULL DEFAULT 0.00,
  `TienThuoc` decimal(12,2) NOT NULL DEFAULT 0.00,
  `TongTien` decimal(12,2) NOT NULL DEFAULT 0.00,
  `TrangThaiTT` enum('ChuaThanhToan','DaThanhToan') NOT NULL DEFAULT 'ChuaThanhToan',
  `MaLK` char(10) NOT NULL,
  `MaThuNgan` char(10) NOT NULL,
  PRIMARY KEY (`MaHD`),
  UNIQUE KEY `MaLK` (`MaLK`),
  KEY `MaThuNgan` (`MaThuNgan`),
  CONSTRAINT `hoadon_ibfk_1` FOREIGN KEY (`MaLK`) REFERENCES `luotkham` (`MaLK`),
  CONSTRAINT `hoadon_ibfk_2` FOREIGN KEY (`MaThuNgan`) REFERENCES `nguoidung` (`MaND`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `hoadon` (`MaHD`, `NgayLap`, `TienKham`, `TienThuoc`, `TongTien`, `TrangThaiTT`, `MaLK`, `MaThuNgan`) VALUES
('HD001', '2026-10-26 09:00:00', 150000.00, 15000.00, 165000.00, 'ChuaThanhToan', 'LK001', 'ND004');

SET FOREIGN_KEY_CHECKS = 1;
