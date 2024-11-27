-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Sep 2024 pada 17.16
-- Versi server: 10.4.14-MariaDB
-- Versi PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restoran_1.0.0`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('PUBLISH','DRAFT') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PUBLISH',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `image`, `status`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `user_id`) VALUES
(1, 'MAKANAN', 'makanan', NULL, 'PUBLISH', '2024-09-01 15:13:33', '2024-09-01 15:13:33', NULL, 1, NULL, NULL, 1),
(2, 'MINUMAN', 'minuman', NULL, 'PUBLISH', '2024-09-01 15:13:50', '2024-09-01 15:13:50', NULL, 1, NULL, NULL, 1),
(3, 'SNACK', 'snack', NULL, 'PUBLISH', '2024-09-01 15:14:02', '2024-09-01 15:14:02', NULL, 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `category_product`
--

CREATE TABLE `category_product` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `category_product`
--

INSERT INTO `category_product` (`id`, `product_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 1, NULL, NULL),
(3, 3, 2, NULL, NULL),
(4, 4, 2, NULL, NULL),
(5, 5, 3, NULL, NULL),
(6, 6, 3, NULL, NULL),
(7, 7, 1, NULL, NULL),
(8, 8, 2, NULL, NULL),
(9, 9, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `chairs`
--

CREATE TABLE `chairs` (
  `id` int(10) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `no` int(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `chairs`
--

INSERT INTO `chairs` (`id`, `name`, `no`, `created_at`, `updated_at`) VALUES
(1, 'Meja 1 Orang A', 1, '2024-09-09 05:54:29', '2024-09-09 05:55:44'),
(2, 'Meja 2 Orang A', 3, '2024-09-09 05:54:42', '2024-09-16 07:37:24'),
(3, 'Meja 2 Orang B', 2, '2024-09-09 05:55:24', '2024-09-16 07:37:14'),
(4, 'Meja 5 Orang A', 4, '2024-09-16 07:36:41', '2024-09-16 07:37:33'),
(5, 'Meja 3 Orang A', 6, '2024-09-16 07:37:53', '2024-09-16 07:37:53'),
(6, 'Meja 4 Orang A', 7, '2024-09-16 07:38:07', '2024-09-16 07:38:07'),
(7, 'Meja 5 Orang B', 8, '2024-09-16 07:38:26', '2024-09-16 07:38:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `common_images`
--

CREATE TABLE `common_images` (
  `id` int(10) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_deleted` varchar(1) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `common_images`
--

INSERT INTO `common_images` (`id`, `image_url`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`, `created_by`, `user_id`) VALUES
(1, 'commonImage-covers/u6teYxAz5J1L0PgUf3GR8LSRzu8yZsWBW4FYN2Ke.jpeg', '2024-09-09 05:54:00', '2024-09-09 14:43:50', 'y', NULL, 4, 4),
(2, 'commonImage-covers/w1ZdpH6PXTIJdPD1IogkT6XpRBSUMZIMf4ujvj31.jpeg', '2024-09-09 05:54:07', '2024-09-16 07:36:03', 'y', NULL, 1, 4),
(3, 'commonImage-covers/wZohyZ7J4VdUv04YMSnvqWatAuuLLk6SIapFTibc.jpeg', '2024-09-16 07:35:51', '2024-09-16 07:35:51', 'y', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_bill` double(15,2) NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('SUBMIT','PROCESS','FINISH','CANCEL','SEND') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SUBMIT',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `evidence_of_transfer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chair_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL -- Menambahkan kolom customer_name
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Struktur dari tabel `order_product`
--

CREATE TABLE `order_product` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name_product` varchar(225) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_product` double(15,2) DEFAULT NULL,
  `buying_price` double(15,2) DEFAULT NULL,
  `buying_price_total` double(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double(15,2) UNSIGNED DEFAULT NULL,
  `stock` double(15,2) UNSIGNED DEFAULT NULL,
  `status` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `buying_price` double(15,2) UNSIGNED DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `code`, `title`, `slug`, `description`, `cover`, `price`, `stock`, `status`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `buying_price`, `user_id`) VALUES
(1, '00001', 'Sego Pecel Madiun', 'sego-pecel-madiun', 'Nasi Pecel Khas Madiun:\r\nNasi Pecel\r\nKerupuk', 'product-covers/SoxLU8NxoiCXwyqqdJNfGF7WYlkwolF1VvVGs26L.jpeg', 5000.00, 10.00, 'PUBLISH', '2024-09-09 05:56:52', '2024-09-09 05:56:52', NULL, 4, NULL, NULL, 2000.00, 4),
(2, '00002', 'Nasi Goreng Ayam', 'nasi-goreng-ayam', 'Nasi Goreng Ayam', 'product-covers/eBUVG2zGsuOoGHhs6DeUBZYcb6X0K0jrBTUt0WiH.jpeg', 10000.00, 10.00, 'PUBLISH', '2024-09-09 05:57:32', '2024-09-16 07:38:45', NULL, 4, NULL, NULL, 5000.00, 4),
(3, '00003', 'Es Teh Manis', 'es-teh-manis', 'Es Teh Manis', 'product-covers/xSH0vZmo5Emp4CfUbNeOsPeT6O6HE7dBZhTFsnsB.jpeg', 2000.00, 10.00, 'PUBLISH', '2024-09-09 05:58:00', '2024-09-16 07:39:31', NULL, 4, NULL, NULL, 1000.00, 4),
(4, '00004', 'Es Jeruk', 'es-jeruk', 'Es Jeruk', 'product-covers/FWgEhC6fjFB2OlMMwbfAtvuSxvRABCi2bxUhvTz5.jpeg', 5000.00, 8.00, 'PUBLISH', '2024-09-09 05:58:30', '2024-09-19 15:04:59', NULL, 4, NULL, NULL, 3000.00, 4),
(5, '00005', 'Kentang Goreng', 'kentang-goreng', 'Kentang Goreng', 'product-covers/4O11KV6DUOhoVeKo0ecLab9VZaFs98XXJrYordmW.jpeg', 6000.00, 6.00, 'PUBLISH', '2024-09-09 05:59:00', '2024-09-16 07:40:12', NULL, 4, NULL, NULL, 3000.00, 4),
(6, '00006', 'Roti Bakar 66', 'roti-bakar-66', 'Roti Bakar 66', 'product-covers/cgBkelRdyhZok1PjSbt0qgW4iSqEaRq946uArel6.jpeg', 10000.00, 5.00, 'PUBLISH', '2024-09-09 05:59:27', '2024-09-16 08:54:47', NULL, 4, NULL, NULL, 5000.00, 4),
(7, '00007', 'Nasi Gandul', 'nasi-gandul', 'Nasi Gandul', 'product-covers/StZdd53J7A50bNyaAxix1mmLDUU7vuSS2gn3oRUl.jpeg', 10000.00, 8.00, 'PUBLISH', '2024-09-16 07:39:16', '2024-09-19 15:04:59', NULL, 1, NULL, NULL, 5000.00, 1),
(8, '00008', 'Es cendol', 'es-cendol', 'Es cendol', 'product-covers/FL8iKBT0ZidljeRxKWNXjdXryQY9k3DCurkxdLqF.jpeg', 5000.00, 9.00, 'PUBLISH', '2024-09-16 07:39:58', '2024-09-16 08:54:47', NULL, 1, NULL, NULL, 3000.00, 1),
(9, '00009', 'Mendoan', 'mendoan', 'Mendoan', 'product-covers/Fc2sCflzWDO505jwN4aTfuOJvftafaKRXiXry3IO.jpeg', 3000.00, 7.00, 'PUBLISH', '2024-09-16 07:40:37', '2024-09-19 15:04:59', NULL, 1, NULL, NULL, 2000.00, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_stores`
--

CREATE TABLE `setting_stores` (
  `id` int(11) NOT NULL,
  `store` varchar(255) DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `user_id` int(11) NOT NULL,
  `logo_path_url` varchar(250) DEFAULT NULL,
  `role` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `setting_stores`
--

INSERT INTO `setting_stores` (`id`, `store`, `create_at`, `user_id`, `logo_path_url`, `role`) VALUES
(3, 'Atas Nama 		    : Resto Siap Saji 66\r\nNo ReKening		: 987*******432\r\nUpload Bukti Pembayaran.', NULL, 4, 'store-logo/oyXVNl0Dz5m5bUhEASXRNBpRjsSqC2wT1njiQIDj.jpeg', 'admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `roles` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_token` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `roles`, `avatar`, `status`, `api_token`, `username`) VALUES
(1, 'ADMIN', 'resto@indorobotika.com', '2023-07-05 14:17:29', '$2y$10$HZy5jyX/ROiZXox62N8E1uy.0C2qjG.FbOS0WPobicFqeiwFVguM.', NULL, '0000-00-00 00:00:00', '2024-09-19 15:13:55', 'ADMIN', 'avatars/rk4gmCzrg5JYZjOitz9gCrOgKT1CXbXHxlSe8uL1.webp', 'ACTIVE', NULL, 'admin@indorobotika.com');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indeks untuk tabel `category_product`
--
ALTER TABLE `category_product`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `chairs`
--
ALTER TABLE `chairs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `common_images`
--
ALTER TABLE `common_images`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `order_product`
--
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `books_slug_unique` (`slug`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indeks untuk tabel `setting_stores`
--
ALTER TABLE `setting_stores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_api_token_unique` (`api_token`),
  ADD KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `category_product`
--
ALTER TABLE `category_product`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `chairs`
--
ALTER TABLE `chairs`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `common_images`
--
ALTER TABLE `common_images`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `order_product`
--
ALTER TABLE `order_product`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `setting_stores`
--
ALTER TABLE `setting_stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
