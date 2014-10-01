-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2014 at 06:16 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `intervensi`
--
CREATE DATABASE `intervensi` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `intervensi`;

-- --------------------------------------------------------

--
-- Table structure for table `data_penduduk`
--

CREATE TABLE IF NOT EXISTS `data_penduduk` (
  `NoKK` varchar(30) NOT NULL,
  `NamaKep` varchar(50) NOT NULL,
  `Alamat` varchar(30) NOT NULL,
  `RT` int(5) NOT NULL,
  `RW` int(5) NOT NULL,
  `Dusun` varchar(30) NOT NULL,
  `KodePos` varchar(10) NOT NULL,
  `NIK` varchar(30) NOT NULL,
  `NamaLengkap` varchar(60) NOT NULL,
  `JK` varchar(20) NOT NULL,
  `TempatLahir` varchar(40) NOT NULL,
  `TglLahir` date NOT NULL,
  `NoAkta` varchar(30) NOT NULL,
  `GolDarah` varchar(3) NOT NULL,
  `Agama` varchar(15) NOT NULL,
  `Pekerjaan` varchar(30) NOT NULL,
  `NamaIbu` varchar(50) NOT NULL,
  `NamaAyah` varchar(50) NOT NULL,
  `Status` varchar(25) NOT NULL,
  `Kelurahan` varchar(20) NOT NULL,
  PRIMARY KEY (`NIK`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `data_penduduk`
--

INSERT INTO `data_penduduk` (`NoKK`, `NamaKep`, `Alamat`, `RT`, `RW`, `Dusun`, `KodePos`, `NIK`, `NamaLengkap`, `JK`, `TempatLahir`, `TglLahir`, `NoAkta`, `GolDarah`, `Agama`, `Pekerjaan`, `NamaIbu`, `NamaAyah`, `Status`, `Kelurahan`) VALUES
('009090909', 'tes', 'tes', 9, 10, 'cimahi', '40132', '0909909', 'ratih', 'Perempuan', 'garut', '2013-11-04', '34343', 'A', 'Islam', 'ttt', 'trtrt', 'trtrt', 'Belum Menikah', 'Cibeber'),
('3277020811060392', 'RIZAL SULAEMAN', 'JL WARUNG CONTONG', 2, 9, '-', '40524', '3277000202020001', 'MUHAMMAD MAESA M JELIWANG', 'Laki-laki', 'CIMAHI', '2002-02-02', '-', 'O', 'Islam', '-', 'TITI SUGIYATI', 'RIZAL SULAEMAN', '-', 'SETIAMANAH'),
('327702088888888', 'RIZAL SULAEMAN', 'CIBEBER', 9, 8, 'CIBEBER', '877', '3277000202020012', 'TES', 'laki-laki', 'CIMAHI', '2002-02-02', '788888', 'O', 'Islam', 'MAHASISWA', 'NUNUNG', 'UTAR', 'Menikah', 'CIBEBER'),
('3277020811060235', 'T. SINAGA', 'JL. WARUNG CONTONG TIMUR NO. 1', 2, 9, 'KP KIHAPIT TIMUR', '40524', '3277000611760001 ', 'NOVIK HOTMAN ', 'Laki-laki', 'CIMAHI ', '2000-01-01', '41.949/1988  ', 'AB', 'KRISTEN ', 'KARYAWAN SWASTA ', 'T. SINAGA ', 'J. SITUMORANG ', 'BELUM KAWIN', 'SETIAMANAH '),
('3277020811060235 ', 'T. SINAGA ', 'JL. WARUNG CONTONG TIMUR NO. 1', 2, 9, '-', '40524 ', '3277000812740001 ', 'ARIFIN ', 'Laki-laki', 'CIMAHI ', '1976-06-11', ' 992/2006', 'O', 'KRISTEN ', 'KARYAWAN SWASTA ', 'T. SINAGA ', 'J. SITUMORANG ', 'BELUM KAWIN', 'SETIAMANAH '),
('3277012602080004 ', 'HASMAR YUSUF PANJAITAN', 'CIBEBER', 7, 8, 'CIBEBER', '40531 ', '3277002006070001 ', 'YEHEZKIEL ARDIAN PANJAITAN ', 'Laki-laki', 'CIMAHI ', '1974-08-12', '-', 'A', 'KRISTEN', 'BELUM/TIDAK BEKERJA ', 'TETTY SIHOMBING', 'HASMAR YUSUF PANJAITAN ', 'BELUM KAWIN', 'CIBEBER '),
('3277012910060894 ', 'SUTONO', 'KP HUJUNG KULON ', 8, 5, '-', '40533 ', '3277010101000001 ', 'H E R U ', 'Laki-laki', 'BANDUNG ', '2007-01-01', '-', 'B', 'ISLAM', 'PELAJAR/MAHASISWA ', 'JASIAH ', 'SUTONO', 'BELUM KAWIN', 'UTAMA '),
('3277011501100161', 'WARTISAH', 'KIHAPIT TIMUR', 5, 20, '-', '40532', '3277010101000002', 'FAHRIZAL MUHAMAD RIZKI', 'Laki-laki', 'CIMAHI', '2000-01-01', '-', 'A', 'ISLAM', 'BELUM/TIDAK BEKERJA', 'WARTISAH', ' HERI PARID JAENUDIN', 'BELUM KAWIN', 'LEUWIGAJAH'),
('3277012910061037 ', 'SURYO TRI HARTONO ', 'KP HUJUNG KIDUL ', 4, 7, '-', '40533 ', '3277010101000003', 'SYAUQI IQBAL FARABI SP ', 'Laki-laki', 'CIMAHI', '2000-01-01', '5358/2007', 'B', 'ISLAM', 'PELAJAR/MAHASISWA', 'YENI RUSMIYATI ', 'SURYO TRI HARTONO ', 'BELUM KAWIN', 'UTAMA'),
('3277010111060799 ', 'BUSIDIN', 'BLOK SUKAMAJU ', 2, 6, '-', '40534 ', '3277010101000004', 'DENDI SETIAWAN ', 'Laki-laki', 'PURWOKERTO', '2000-01-01', '-', 'O', 'ISLAM', 'PELAJAR/MAHASISWA', 'SITI ', 'BUSIDIN ', 'BELUM KAWIN', 'MELONG'),
('3277010410060681 ', 'AGUS SUBANDAR ', 'JL KEBON KOPI NO188 ', 4, 8, 'CIBEUREUM', '40535 ', '3277010101000042', 'REZA MELDIAN ', 'Laki-laki', 'BANDUNG ', '2000-01-01', '-', 'AB', 'ISLAM', 'PELAJAR/MAHASISWA', 'SEPTIKA WATI ', 'AGUS SUBANDAR ', 'BELUM KAWIN', 'CIBEUREUM'),
('3277011610061370 ', 'PAIJAN ', 'KIHAPIT TIMUR ', 10, 8, '-', '40532 ', '3277010101000045', 'RAMDAN ARIF FIRMANSYAH ', 'Laki-laki', 'BANDUNG', '2000-01-01', '-', 'O', 'ISLAM', 'PELAJAR/MAHASISWA', 'TUMI ARIYANTI ', 'PAIJAN ', 'BELUM KAWIN', 'LEUWIGAJAH'),
('3277010311062245', 'A. BADRUDIN', 'BLOK 17 NO 109 MELONG', 2, 15, '-', '40534', '3277010101000049', 'MUHAMAD IMAM HARMAEN', 'Laki-laki', 'BANDUNG', '2000-01-01', '3606/2005', 'A', 'ISLAM', 'BELUM/TIDAK BEKERJA', ' IMAS JULAEHA', 'A BADRUDIN', 'BELUM KAWIN', 'MELONG'),
('3277010811060311', 'RIAN NURYADIN', 'JL KEBON KOPI GG  H SAFEI II N', 3, 7, '-', '40534 ', '3277010101000052', 'AGUNG RAMDANI', 'Laki-laki', 'BANYUMAS', '2000-01-01', '-', 'A', 'ISLAM', 'BELUM/TIDAK BEKERJA', 'SUGINI', 'TASLAM', 'ISLAM', 'MELONG'),
('3277012205070020', 'TASLAM KOSMANA', 'BLOK HEGARMANAH NO  241 ', 1, 7, '-', '40533 ', '3277010101000058', 'FEBI RAMDHAN KOSMANA', 'Laki-laki', 'CIREBON', '2000-01-01', '-', 'O', 'ISLAM', 'BELUM/TIDAK BEKERJA', 'ROHIDAH', 'KOSMANA', 'ISLAM', 'UTAMA'),
('3277011001080002', 'BUBUN BUNYAMIN ', 'JL JOYODIKROMO HUJUNG KIDUL ', 6, 7, '-', '40534 ', '3277010101000059', 'RIZKI', 'Laki-laki', 'TASIKMALAYA', '2000-01-01', '-', 'B', 'ISLAM', 'BELUM/TIDAK BEKERJA', 'LILIS', 'BUBUN BUNYAMIN', 'ISLAM', 'MELONG'),
('3277012001100083', 'AMINAH', 'BLOK HEGARMANAH NO 314 CIBOGO ', 5, 6, '-', '40532 ', '3277010101000075', 'Y PAHMI PRATAMA', 'Laki-laki', 'CIMAHI', '2000-01-01', '-', 'AB', 'ISLAM', 'PELAJAR/MAHASISWA', 'AMINAH', 'YUDI NAUVAL', 'ISLAM', 'LEUWIGAJAH'),
('3277011001070048', 'EKO PATMONO ', 'RANCA BENTANG UTARA ', 3, 25, '-', '40535 ', '3277010101000078', 'MUHAMAD PADLAN', 'Laki-laki', 'G.HALU', '2000-01-01', '-', 'O', 'ISLAM', 'PELAJAR/MAHASISWA', 'DWI BUDI ROKHAYATI', 'EKO PATMONO', 'ISLAM', 'CIBEUREUM'),
('3277012204070468', 'AA KURNIA ', 'KP CIBODAS ', 5, 11, '-', '40533', '3277010101000081', 'MUKLIS AKBAR KURNIANSAH', 'Laki-laki', 'CIMAHI', '2000-01-01', '-', 'O', 'ISLAM', 'BELUM/TIDAK BEKERJA', 'MIMING', 'AA KURNIA', 'ISLAM', 'UTAMA'),
('3277012204070468', 'AA KURNIA ', 'KP CIBODAS ', 5, 11, '-', '40533', '3277010101000082', 'SAEPUL RAJIP', 'Laki-laki', 'CIMAHI', '2000-01-01', '-', 'O', 'ISLAM', 'BELUM/TIDAK BEKERJA', 'MIMING', 'AA KURNIA', 'ISLAM', 'UTAMA'),
('3277011110061311', 'RIAN NURYADIN', 'JL KEBON KOPI GG  H SAFEI II N', 3, 28, '-', '40535', '3277010101010001', 'ALDITIA', 'Laki-laki', 'BANDUNG', '2000-01-01', '1958/2008', 'B', 'ISLAM', 'BELUM/TIDAK BEKERJA', 'RIAWATI', 'RIAN N', 'ISLAM', 'CIBEUREUM'),
('3277012001100108', 'YUDI KEMAL ', 'JL ROROJONGGRANG UTARA I BLOK ', 4, 36, '-', '40534 ', '3277012001570004', 'MUHAMAD ADIFIO BAHI', 'Laki-laki', 'BANDUNG', '2000-01-01', '-', 'A', 'ISLAM', 'PELAJAR/MAHASISWA', 'FINY FITRIANY', 'YUDI KEMAL', 'ISLAM', 'MELONG');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE IF NOT EXISTS `kegiatan` (
  `KodeKegiatan` varchar(25) NOT NULL,
  `NamaKegiatan` varchar(300) NOT NULL,
  `JadwalAwal` date NOT NULL,
  `JadwalAkhir` date NOT NULL,
  `KodeInstansi` varchar(200) NOT NULL,
  `KodeKomponen` varchar(20) NOT NULL,
  `KodeSubKomponen` varchar(20) NOT NULL,
  `KodeDetailSubKomponen` varchar(20) NOT NULL,
  `Anggaran` bigint(15) NOT NULL,
  PRIMARY KEY (`KodeKegiatan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`KodeKegiatan`, `NamaKegiatan`, `JadwalAwal`, `JadwalAkhir`, `KodeInstansi`, `KodeKomponen`, `KodeSubKomponen`, `KodeDetailSubKomponen`, `Anggaran`) VALUES
('E-0112014010819', 'Dana Bantuan Bulan Februari', '2014-02-03', '2014-02-07', '16', 'E', 'E-04', 'E-011', 135000000),
('E-0112014010853', 'Dana Bantuan Bulan Januari', '2014-01-06', '2014-01-13', '16', 'E', 'E-04', 'E-011', 134000000),
('L-0122014010930', 'Perbaikan Jalan', '2014-01-01', '2014-01-08', '18', 'L', 'L-01', 'L-012', 25000000),
('L-0212014011433', 'Perbaikan Saluran Air', '2014-01-16', '2014-01-23', '18', 'L', 'L-02', 'L-021', 40000000),
('L-0342014010901', 'Perbaikan Jalan', '2014-01-10', '2014-01-16', '8', 'L', 'L-01', 'L-034', 10000000),
('L-0412014010833', 'Bedah Rumah Kelurahan Cibeber', '2014-02-10', '2014-02-14', '18', 'L', 'L-04', 'L-041', 150000000),
('L-0412014091734', 'Bantuan Pendidikan', '2014-09-14', '2014-09-19', '16', 'E', 'L-12', 'L-041', 300000000),
('S-0242014010806', 'Bedah Rumah Kelurahan Setiamanah', '2014-01-01', '2014-01-03', '18', 'L', 'L-04', 'L-041', 50000000);

-- --------------------------------------------------------

--
-- Table structure for table `m_instansi`
--

CREATE TABLE IF NOT EXISTS `m_instansi` (
  `KodeInstansi` varchar(200) NOT NULL,
  `Instansi` varchar(250) NOT NULL,
  PRIMARY KEY (`KodeInstansi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_instansi`
--

INSERT INTO `m_instansi` (`KodeInstansi`, `Instansi`) VALUES
('0', 'Administrator'),
('1', 'Sekretaris Daerah Kota Cimahi'),
('10', 'Kabag Administrasi Perekonomian Setda Kota Cimahi'),
('11', 'Sekretaris Bappeda Kota Cimahi'),
('12', 'Sekretaris BMPPKB Kota Cimahi'),
('13', 'Sekretaris Inspektorat Kota Cimahi'),
('14', 'Kepala KAPPDE Kota Cimahi'),
('15', 'Kepala BPS Kota Cimahi'),
('16', 'Kabid Ekonomi Bappeda Kota Cimahi'),
('17', 'Kabid Fisik Bappeda Kota Cimahi'),
('18', 'Kabid Sosial dan Budaya Bappeda Kota Cimahi'),
('19', 'Kabid Pemberdayaan Masyarakat BPMPPKB Kota Cimahi'),
('2', 'Asisten Perekonomian dan Pembangunan Setda Kota Cimahi'),
('20', 'Kasubid Sosial dan Budaya Bappeda Kota Cimahi'),
('21', 'Kasubid Program Bappeda Kota Cimahi'),
('22', 'Kasubid Pemerintahan Bappeda Kota Cimahi'),
('23', 'Kasubid Tata Ruang Bappeda Kota Cimahi'),
('24', 'Kasubid Indagpar Bappeda Kota Cimahi'),
('25', 'Kasubid Kopkumtan Bappeda Kota Cimahi'),
('3', 'Asisten Pemerintahan Setda Kota Cimahi'),
('4', 'Asisten Administrasi Umum Setda Kota Cimahi'),
('5', 'Kepala Bappeda Kota Cimahi'),
('6', 'Kepala BMPPKB Kota Cimahi'),
('7', 'Kepala Disnakertransos Kota Cimahi'),
('8', 'Kepala Dinas PU Kota Cimahi'),
('9', 'Kepala Dinas Kopindagtan Kota Cimahi');

-- --------------------------------------------------------

--
-- Table structure for table `m_kecamatan`
--

CREATE TABLE IF NOT EXISTS `m_kecamatan` (
  `kode_kecamatan` int(15) NOT NULL,
  `nama_kecamatan` varchar(30) NOT NULL,
  PRIMARY KEY (`kode_kecamatan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_kecamatan`
--

INSERT INTO `m_kecamatan` (`kode_kecamatan`, `nama_kecamatan`) VALUES
(327701, 'CIMAHISELATAN'),
(327702, 'CIMAHITENGAH'),
(327703, 'CIMAHIUTARA');

-- --------------------------------------------------------

--
-- Table structure for table `m_kelurahan`
--

CREATE TABLE IF NOT EXISTS `m_kelurahan` (
  `kode_kelurahan` int(15) NOT NULL,
  `Kelurahan` varchar(50) NOT NULL,
  PRIMARY KEY (`kode_kelurahan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_kelurahan`
--

INSERT INTO `m_kelurahan` (`kode_kelurahan`, `Kelurahan`) VALUES
(32770101, 'CIBEBER'),
(32770102, 'CIBEUREUM'),
(32770103, 'LEUWIGAJAH'),
(32770104, 'MELONG'),
(32770105, 'UTAMA'),
(32770201, 'BAROS'),
(32770202, 'CIGUGUR TENGAH'),
(32770203, 'CIMAHI'),
(32770204, 'KARANG MEKAR'),
(32770205, 'PADASUKA'),
(32770206, 'SETIAMANAH'),
(32770301, 'CIBABAT'),
(32770302, 'CIPAGERAN'),
(32770303, 'CITEUREUP'),
(32770304, 'PASIRKALIKI');

-- --------------------------------------------------------

--
-- Table structure for table `m_komponen`
--

CREATE TABLE IF NOT EXISTS `m_komponen` (
  `KodeKomponen` varchar(1) DEFAULT NULL,
  `Komponen` varchar(50) DEFAULT NULL,
  `Urut` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_komponen`
--

INSERT INTO `m_komponen` (`KodeKomponen`, `Komponen`, `Urut`) VALUES
('L', 'Infrastruktur', '1'),
('S', 'Sosial', '2'),
('E', 'Ekonomi ', '9');

-- --------------------------------------------------------

--
-- Table structure for table `m_komponen_sub`
--

CREATE TABLE IF NOT EXISTS `m_komponen_sub` (
  `KodeSubKomponen` varchar(4) DEFAULT NULL,
  `SubKomponen` varchar(29) DEFAULT NULL,
  `KodeKomponen` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_komponen_sub`
--

INSERT INTO `m_komponen_sub` (`KodeSubKomponen`, `SubKomponen`, `KodeKomponen`) VALUES
('E-02', 'Home Industry', 'E'),
('E-04', 'Human Resources Development', 'E'),
('E-99', 'Lain-Lain', 'E'),
('L-01', 'Road', 'L'),
('L-02', 'Drainage', 'L'),
('L-03', 'Bridge', 'L'),
('L-04', 'Housing', 'L'),
('L-05', 'Public Toilets', 'L'),
('L-06', 'Waste Disposal', 'L'),
('L-07', 'Water Supply', 'L'),
('L-08', 'Public Lightning', 'L'),
('L-09', 'Building School', 'L'),
('L-10', 'Irigasi', 'L'),
('L-11', 'Healthcare Facility', 'L'),
('L-12', 'Retail Trading Facility', 'L'),
('L-13', 'Waste Water Canal', 'L'),
('L-14', 'Tambatan Perahu', 'L'),
('L-99', 'Other In Infrastructure', 'L'),
('S-01', 'Santunan Sosial/Hibah', 'S'),
('S-02', 'Peningkatan SDM', 'S'),
('S-03', 'Scholarship', 'S'),
('S-04', 'Health', 'S'),
('S-99', 'Other in Social', 'S');

-- --------------------------------------------------------

--
-- Table structure for table `m_komponen_sub_detail`
--

CREATE TABLE IF NOT EXISTS `m_komponen_sub_detail` (
  `KodeDetailSubKomponen` varchar(5) DEFAULT NULL,
  `SubKomponenDetail` tinytext,
  `Satuan` varchar(50) DEFAULT NULL,
  `KodeSubKomponen` varchar(50) DEFAULT NULL,
  `TotalQuality` double DEFAULT NULL,
  `IDRTotalCost` double DEFAULT NULL,
  `USDTotalCost` double DEFAULT NULL,
  `IDBUSDShare` double DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_komponen_sub_detail`
--

INSERT INTO `m_komponen_sub_detail` (`KodeDetailSubKomponen`, `SubKomponenDetail`, `Satuan`, `KodeSubKomponen`, `TotalQuality`, `IDRTotalCost`, `USDTotalCost`, `IDBUSDShare`) VALUES
('E-011', 'Retail Trading', 'person', 'E-02', 55364, 99999990, 2330910, 9000),
('L-012', 'Concrete Road', 'meter', 'L-01', 1367318, 98683804560, 8971255, 6237775),
('L-014', 'Macadam Road', 'meter', 'L-01', 15369, 737690300, 67063, 47125),
('L-015', 'Telford Road', 'meter', 'L-01', 74321, 2714754900, 246796, 181263),
('L-016', 'Retaining Wall', 'meter', 'L-01', 65507, 7459092835, 678099, 511474),
('L-021', 'Drainage', 'meter', 'L-02', 642319, 36763542208, 3342140, 2383721),
('L-031', 'Wood Bridge', 'meter', 'L-03', 2556, 392354000, 35669, 24055),
('L-032', 'Iron Bridge', 'meter', 'L-03', 2675, 1407315500, 127938, 81050),
('L-033', 'Concrete Bridge', 'meter', 'L-03', 14499, 7528937018, 684449, 473763),
('L-034', 'Road Drains', 'meter', 'L-03', 25119, 7888795521, 717163, 475245),
('L-041', 'Housing', 'unit', 'L-04', 11810, 49844427591, 4531312, 2930908),
('L-051', 'Public Toilets', 'unit', 'L-05', 22298, 31222525479, 2838411, 1944948),
('L-052', 'Toilet', 'unit', 'L-05', 497, 1152010750, 104728, 67080),
('L-061', 'Waste Disposal', 'unit', 'L-06', 19732, 9120183900, 829108, 521173),
('L-071', 'Dug Well', 'unit', 'L-07', 698, 1661323950, 151029, 95726),
('L-072', 'Hand Pump', 'unit', 'L-07', 2793, 3706486600, 336953, 199104),
('L-073', 'Rain Collector', 'unit', 'L-07', 330, 876345100, 79668, 48618),
('L-074', 'Public Hydrant', 'unit', 'L-07', 6106, 4459408400, 405401, 278493),
('L-075', 'Water Pipeline', 'meter', 'L-07', 207419, 10189534850, 926321, 723440),
('L-076', 'Bone Capthing', 'unit', 'L-07', 1274, 585955100, 53269, 38097),
('L-077', 'Surface Water Collector', 'unit', 'L-07', 91, 554717200, 50429, 37274),
('L-081', 'Public Lighting', 'unit', 'L-08', 6825, 3660615100, 332783, 239371),
('L-091', 'Education Facilities', 'unit\r\nunit', 'L-09', 3294, 6991046300, 635550, 453112),
('L-101', 'Saluran Irigasi', 'meter', 'L-10', NULL, NULL, NULL, NULL),
('L-102', 'Waduk/Embung', 'unit', 'L-10', NULL, NULL, NULL, NULL),
('L-111', 'Healthcare Facilities', 'unit', 'L-11', 111942, 16870368050, 1533670, 1070039),
('L-121', 'Kiosk', 'unit', 'L-12', 473, 890250000, 80932, 40046),
('L-131', 'Waste Water Canal', 'meter', 'L-13', 99703, 6257660960, 568878, 382987),
('L-141', 'Tambatan Perahu', 'unit/meter', 'L-14', NULL, NULL, NULL, NULL),
('L-991', 'Other In Infrastructure', 'meter', 'S-99', 5205, 214000000, 19455, 12645),
('S-011', 'Pemberian Uang Tunai', 'orang', 'S-01', NULL, NULL, NULL, NULL),
('S-012', 'Bazar', 'person', 'S-01', 55342, 2234018200, 203093, NULL),
('S-021', 'Agriculture Training', 'person', 'S-02', 23404, 3175617500, 288693, NULL),
('S-022', 'Husbandry Training', 'person', 'S-02', 4732, 589036400, 53549, NULL),
('S-023', 'Vocational Training', 'person', 'S-02', 97355, 15034730464, 1366794, NULL),
('S-024', 'Pelatihan bidang Pendidikan', 'orang', 'S-02', NULL, NULL, NULL, NULL),
('S-025', 'Pelatihan bidang Kesehatan', 'orang', 'S-02', NULL, NULL, NULL, NULL),
('S-026', 'Pelatihan bidang Keselamatan', 'orang', 'S-02', NULL, NULL, NULL, NULL),
('S-027', 'Pelatihan Ketrampilan', 'orang', 'S-02', NULL, NULL, NULL, NULL),
('S-031', 'Scholarship', 'person', 'S-03', 64789, 13443684275, 1222153, NULL),
('S-041', 'Imunization', 'person', 'S-04', 78972, 4096457750, 372405, NULL),
('S-042', 'Mother and Child Health', 'person', 'S-04', 67152, 3614607600, 328601, NULL),
('S-043', 'Nutrition', 'person', 'S-04', 83262, 7368802500, 669891, NULL),
('S-991', 'Other in Social', 'person', 'S-99', 293, 44500000, 4045, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_kota`
--

CREATE TABLE IF NOT EXISTS `m_kota` (
  `kode_kota` int(100) NOT NULL,
  `kota` varchar(200) NOT NULL,
  PRIMARY KEY (`kode_kota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_kota`
--

INSERT INTO `m_kota` (`kode_kota`, `kota`) VALUES
(3277, 'KOTA CIMAHI');

-- --------------------------------------------------------

--
-- Table structure for table `m_provinsi`
--

CREATE TABLE IF NOT EXISTS `m_provinsi` (
  `kode_provinsi` int(100) NOT NULL,
  `provinsi` varchar(200) NOT NULL,
  PRIMARY KEY (`kode_provinsi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_provinsi`
--

INSERT INTO `m_provinsi` (`kode_provinsi`, `provinsi`) VALUES
(32, 'JAWA BARAT ');

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran`
--

CREATE TABLE IF NOT EXISTS `pendaftaran` (
  `IdPendaftaran` int(11) NOT NULL AUTO_INCREMENT,
  `NIK` varchar(30) NOT NULL,
  `NamaLengkap` varchar(200) NOT NULL,
  `Alamat` varchar(300) NOT NULL,
  `Status` varchar(30) NOT NULL,
  `Kelurahan` varchar(200) NOT NULL,
  `KodeKegiatan` varchar(25) NOT NULL,
  `Ket` varchar(300) NOT NULL,
  PRIMARY KEY (`IdPendaftaran`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `pendaftaran`
--

INSERT INTO `pendaftaran` (`IdPendaftaran`, `NIK`, `NamaLengkap`, `Alamat`, `Status`, `Kelurahan`, `KodeKegiatan`, `Ket`) VALUES
(18, '3277000202020012', 'RIZA FAIZO RAJA,AM', 'CIBEBER', 'Menikah', 'CIBEBER', 'L-0612013112650', 'A,A,A'),
(19, '3277000202020012', 'TES', 'CIBEBER aja', 'Menikah', 'CIBEBER', 'E-0112013112742', '-'),
(20, '0909909', 'ratih AJAJAJA', 'Jalan jalan', 'Mahasiswa', 'CIBEBER', 'L-0612013112650', 'belum selesai'),
(22, '3277000202020001', 'MUHAMMAD MAESA M JELIWANG', 'JL WARUNG CONTONG', 'Belum Menikah', 'SETIAMANAH', 'E-0112013112704', '-'),
(23, '3277000611760001 ', 'NOVIK HOTMAN ', 'JL. WARUNG CONTONG TIMUR NO. 1', 'BELUM KAWIN', 'SETIAMANAH ', 'E-0112013112704', '-'),
(24, '3277010101000001 ', 'H E R U ', 'KP HUJUNG KULON ', 'BELUM KAWIN', 'UTAMA ', 'E-0112013112704', '-'),
(25, '3277010101000003', 'SYAUQI IQBAL FARABI SP ', 'KP HUJUNG KIDUL ', 'BELUM KAWIN', 'UTAMA', 'E-0112013112704', '-'),
(26, '3277010101000002', 'FAHRIZAL MUHAMAD RIZKI', 'KIHAPIT TIMUR', 'BELUM KAWIN', 'LEUWIGAJAH', 'E-0112013112704', '-'),
(28, '0909909', 'ratih', 'cibeber aja', 'Belum Menikah', 'Cibeber', 'E-0112013112742', '-'),
(29, '3277010101000042', 'REZA MELDIAN', 'JL KEBON KOPI NO188', 'BELUM KAWIN', 'CIBEUREUM', 'E-0112013112742', '-'),
(30, '3277000202020001', 'MUHAMMAD MAESA M JELIWANG', 'JL WARUNG CONTONG', 'KAWIN', 'SETIAMANAH', 'S-0242014010806', '-'),
(31, '3277000611760001', 'NOVIK HOTMAN', 'JL. WARUNG CONTONG TIMUR NO. 1', 'BELUM KAWIN', 'SETIAMANAH', 'S-0242014010806', '-'),
(32, '3277000812740001', 'ARIFIN', 'JL. WARUNG CONTONG TIMUR NO. 1', 'BELUM KAWIN', 'SETIAMANAH', 'S-0242014010806', '-'),
(34, '3277000202020001', 'MUHAMMAD MAESA M JELIWANG', 'JL WARUNG CONTONG', '-', 'SETIAMANAH', 'E-0112014010819', '-'),
(35, '3277000812740001', 'ARIFIN', 'JL. WARUNG CONTONG TIMUR NO. 1', 'BELUM KAWIN', 'SETIAMANAH', 'E-0112014010819', '-'),
(38, '3277002006070001', 'YEHEZKIEL ARDIAN PANJAITAN', 'CIBEBER', 'BELUM KAWIN', 'CIBEBER', 'L-0412014010833', '-'),
(39, '3277010101000042', 'REZA MELDIAN', 'JL KEBON KOPI NO188', 'BELUM KAWIN', 'CIBEUREUM', 'E-0112014010853', '-'),
(41, '3277000202020001', 'MUHAMMAD MAESA M JELIWANG', 'JL WARUNG CONTONG', '-', 'SETIAMANAH', 'L-0212014011433', '-'),
(42, '3277000812740001', 'ARIFIN', 'JL. WARUNG CONTONG TIMUR NO. 1', 'BELUM KAWIN', 'SETIAMANAH', 'L-0212014011433', '-'),
(44, '0909909', 'ratih', 'tes', 'Belum Menikah', 'Cibeber', 'L-0122014011523', '-'),
(45, '009090909', 'iwan', 'cimahi', 'kawin', 'cibeber', 'L-0122014011523', 'ketua rt.10'),
(46, '0909909', 'ratih', 'tes', 'Belum Menikah', 'Cibeber', 'L-0412014010833', '-'),
(47, '3277000611760001', 'NOVIK HOTMAN', 'JL. WARUNG CONTONG TIMUR NO. 1', 'BELUM KAWIN', 'SETIAMANAH', 'E-0112014010819', 'Bantuan'),
(48, '123', 'Ratih', 'Cimahi', '-', '-', 'E-0112014010819', '-');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE IF NOT EXISTS `pengguna` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pengguna` varchar(100) NOT NULL,
  `password` varchar(30) NOT NULL,
  `KodeInstansi` varchar(100) NOT NULL,
  `level` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `pengguna`, `password`, `KodeInstansi`, `level`) VALUES
(1, 'admin', '123', '0', 'Admin'),
(4, 'KSBB', 'sosial1234', '18', 'Pengunjung'),
(6, 'KEB', 'ekonomi1234', '16', 'Pengunjung'),
(10, 'KabidPU', '1234', '8', 'Pengunjung');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
