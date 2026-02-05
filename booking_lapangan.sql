-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Feb 2026 pada 19.33
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booking_lapangan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin') DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `email`, `password`, `role`) VALUES
(4, 'admin_glory', 'adminglory@gmail.com', '$2y$10$PnExqGFHX0AzhIRj6AXV8eM0wKnNFXd3N3qr6.Zsveu8UdSvr0uEi', 'admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `booking`
--

CREATE TABLE `booking` (
  `id_booking` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_lapangan` int(11) NOT NULL,
  `kode_booking` varchar(20) NOT NULL,
  `tgl_booking` datetime DEFAULT current_timestamp(),
  `tgl_main` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `durasi` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Menunggu Validasi','Lunas','Dibatalkan') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `booking`
--

INSERT INTO `booking` (`id_booking`, `id_user`, `id_lapangan`, `kode_booking`, `tgl_booking`, `tgl_main`, `jam_mulai`, `jam_selesai`, `durasi`, `total_harga`, `bukti_bayar`, `status`) VALUES
(14, 2, 3, 'GLRY-20260205-7B76', '2026-02-05 17:24:43', '2026-02-06', '09:00:00', '11:00:00', 2, 270000, 'BUKTI-GLRY-20260205-7B76.jpg', 'Lunas'),
(15, 2, 2, 'GLRY-20260205-035A', '2026-02-05 17:43:05', '2026-02-06', '12:00:00', '13:00:00', 1, 50000, 'BUKTI-GLRY-20260205-035A.jpg', 'Lunas'),
(16, 2, 1, 'GLRY-20260205-EF2A', '2026-02-05 17:44:36', '2026-02-06', '20:00:00', '21:00:00', 1, 150000, 'BUKTI-GLRY-20260205-EF2A.jpg', 'Lunas'),
(17, 2, 2, 'GLRY-20260205-3B54', '2026-02-05 18:01:26', '2026-02-06', '13:00:00', '14:00:00', 1, 50000, 'BUKTI-GLRY-20260205-3B54.jpg', 'Lunas'),
(18, 2, 3, 'GLRY-20260205-F27F', '2026-02-05 18:05:26', '2026-02-06', '21:00:00', '22:00:00', 1, 135000, 'BUKTI-GLRY-20260205-F27F.jpg', 'Lunas'),
(19, 2, 2, 'GLRY-20260205-BA06', '2026-02-05 18:53:35', '2026-02-06', '22:00:00', '23:00:00', 1, 50000, 'BUKTI_19_1770314632.jpg', 'Lunas'),
(20, 2, 3, 'GLRY-20260205-51C7', '2026-02-05 19:05:39', '2026-02-06', '03:00:00', '04:00:00', 1, 135000, 'BUKTI_20_1770315128.jpg', 'Lunas'),
(21, 2, 1, 'GLRY-20260205-8725', '2026-02-05 19:13:36', '2026-02-06', '22:00:00', '23:00:00', 1, 150000, NULL, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lapangan`
--

CREATE TABLE `lapangan` (
  `id_lapangan` int(11) NOT NULL,
  `nama_lapangan` varchar(50) NOT NULL,
  `harga_per_jam` int(11) NOT NULL,
  `foto_lapangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `lapangan`
--

INSERT INTO `lapangan` (`id_lapangan`, `nama_lapangan`, `harga_per_jam`, `foto_lapangan`) VALUES
(1, 'Vinyl Court (Indoor)', 150000, 'futsal-vinyl.jpg'),
(2, 'Synthetic Grass (Indoor)', 50000, 'badminton-synthesis.jpg'),
(3, 'Futsal Interlock (Indoor)', 135000, 'futsal-interlock.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama_lengkap`, `username`, `email`, `no_hp`, `password`, `role`) VALUES
(2, 'nramlyh', 'amel', 'nramlyh0@gmail.com', '081389841710', '$2y$10$k8igVSudZ3v2ZdSRuBwN4OmCoAAtfIT98uCPaGyH7WqSMqAhcUNGy', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id_booking`),
  ADD UNIQUE KEY `kode_booking` (`kode_booking`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_lapangan` (`id_lapangan`);

--
-- Indeks untuk tabel `lapangan`
--
ALTER TABLE `lapangan`
  ADD PRIMARY KEY (`id_lapangan`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `booking`
--
ALTER TABLE `booking`
  MODIFY `id_booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `lapangan`
--
ALTER TABLE `lapangan`
  MODIFY `id_lapangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`id_lapangan`) REFERENCES `lapangan` (`id_lapangan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
