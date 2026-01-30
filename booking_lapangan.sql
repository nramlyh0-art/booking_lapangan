-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Jan 2026 pada 10.05
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
(2, 2, 3, 'GLRY-4A890', '2026-01-29 20:55:53', '2026-01-30', '12:00:00', '14:00:00', 2, 270000, 'BUKTI-GLRY-4A890.jpg', 'Lunas'),
(3, 2, 3, 'GLRY-3FEB5', '2026-01-29 21:10:04', '2026-01-30', '10:00:00', '11:00:00', 1, 135000, 'BUKTI-GLRY-3FEB5.jpg', 'Lunas'),
(4, 2, 2, 'GLRY-B3013', '2026-01-29 21:25:13', '2026-01-30', '04:26:00', '05:26:00', 1, 50000, 'BUKTI-GLRY-B3013.jpg', 'Lunas'),
(5, 2, 2, 'GLRY-71612', '2026-01-29 22:50:53', '2026-01-30', '11:00:00', '12:00:00', 1, 50000, 'BUKTI-GLRY-71612.jpg', 'Lunas'),
(11, 2, 1, 'GLRY-20260130-2185', '2026-01-30 09:08:07', '2026-01-30', '11:00:00', '12:00:00', 1, 150000, 'BUKTI-GLRY-20260130-2185.jpg', 'Lunas'),
(12, 2, 3, 'GLRY-20260130-4A24', '2026-01-30 09:20:03', '2026-01-30', '23:00:00', '01:00:00', 2, 270000, 'BUKTI-GLRY-20260130-4A24.jpg', 'Lunas');

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
(2, 'nramlyh', 'amel', 'nramlyh0@gmail.com', '081389841710', '$2y$10$k8igVSudZ3v2ZdSRuBwN4OmCoAAtfIT98uCPaGyH7WqSMqAhcUNGy', 'user'),
(5, 'admincenter', 'admincenter', 'admincenter@gmail.com', '089509935256', '$2y$10$coMSkCblRt6up7P3jI6AXumzXvM7CSdnwJGf.85V1k5PsrUKVRMbO', 'admin');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT untuk tabel `booking`
--
ALTER TABLE `booking`
  MODIFY `id_booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
