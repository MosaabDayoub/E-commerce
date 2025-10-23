-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2025 at 10:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `store`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(5, 1, '2025-09-29 12:24:15', '2025-09-29 12:24:15');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `color_id` bigint(20) UNSIGNED NOT NULL,
  `size_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `color_id`, `size_id`, `quantity`, `created_at`, `updated_at`) VALUES
(4, 5, 8, 5, 3, 3, '2025-09-29 12:24:15', '2025-09-29 12:24:15');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Pants', 'Garments covering the legs from the waist to the ankles, available in various styles, fabrics, and fits.', NULL, NULL),
(2, 'Dresses', 'One-piece garments consisting of a skirt and a bodice, ranging from casual everyday wear to formal gowns.', NULL, NULL),
(3, 'Shirts', 'Garments for the upper body with a collar, buttons down the front, and often with long or short sleeves.', NULL, NULL),
(4, 'Sweaters', 'Knitted garments designed to provide warmth, typically worn over other tops like shirts or t-shirts.', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Black', NULL, NULL),
(2, 'White', NULL, NULL),
(3, 'Red', NULL, NULL),
(4, 'Lavender', NULL, NULL),
(5, 'Blue', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `color_product`
--

CREATE TABLE `color_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `color_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `color_product`
--

INSERT INTO `color_product` (`id`, `color_id`, `product_id`, `price`, `created_at`, `updated_at`) VALUES
(4, 1, 5, NULL, NULL, NULL),
(5, 4, 5, 44.00, NULL, NULL),
(6, 3, 5, NULL, NULL, NULL),
(7, 5, 5, NULL, NULL, NULL),
(8, 5, 6, NULL, NULL, NULL),
(9, 2, 6, 54.00, NULL, NULL),
(10, 5, 7, NULL, NULL, NULL),
(11, 3, 7, 65.00, NULL, NULL),
(12, 1, 7, 0.00, NULL, NULL),
(13, 3, 7, NULL, NULL, NULL),
(14, 1, 7, 65.00, NULL, NULL),
(15, 4, 8, 75.00, NULL, NULL),
(16, 1, 9, NULL, NULL, NULL),
(17, 2, 9, NULL, NULL, NULL),
(18, 4, 9, 66.00, NULL, NULL),
(19, 3, 9, NULL, NULL, NULL),
(20, 2, 10, NULL, NULL, NULL),
(21, 1, 10, 75.00, NULL, NULL),
(22, 1, 11, NULL, NULL, NULL),
(23, 2, 11, NULL, NULL, NULL),
(24, 3, 11, 75.00, NULL, NULL),
(25, 4, 11, NULL, NULL, NULL),
(26, 5, 11, NULL, NULL, NULL),
(27, 5, 12, NULL, NULL, NULL),
(28, 4, 12, 61.00, NULL, NULL),
(29, 5, 13, NULL, NULL, NULL),
(30, 1, 14, NULL, NULL, NULL),
(31, 2, 14, NULL, NULL, NULL),
(32, 3, 14, 46.00, NULL, NULL),
(33, 1, 15, NULL, NULL, NULL),
(34, 1, 17, NULL, NULL, NULL),
(35, 2, 17, NULL, NULL, NULL),
(36, 1, 18, NULL, NULL, NULL),
(37, 2, 18, NULL, NULL, NULL),
(40, 3, 5, NULL, NULL, NULL),
(41, 1, 19, NULL, NULL, NULL),
(42, 1, 21, NULL, NULL, NULL),
(43, 2, 21, NULL, NULL, NULL),
(44, 3, 21, NULL, NULL, NULL),
(45, 2, 22, NULL, NULL, NULL),
(46, 4, 21, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(84, '0001_01_01_000000_create_users_table', 1),
(85, '0001_01_01_000001_create_cache_table', 1),
(86, '0001_01_01_000002_create_jobs_table', 1),
(87, '2025_09_03_110851_create_categories_table', 1),
(88, '2025_09_03_110932_create_products_table', 1),
(89, '2025_09_03_142339_create_personal_access_tokens_table', 1),
(90, '2025_09_23_075405_create_colors_table', 1),
(91, '2025_09_23_085956_create_sizes_table', 1),
(92, '2025_09_23_091653_create_carts_table', 1),
(93, '2025_09_23_095402_create_cart_items_table', 1),
(94, '2025_09_23_100625_create_orders_table', 1),
(95, '2025_09_23_102325_create_order_items_table', 1),
(96, '2025_09_23_111844_create_color_product_table', 1),
(97, '2025_09_23_111918_create_product_size_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `status` enum('pending','completed','approved','rejected','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 15.00, 'pending', '2025-09-29 11:50:15', '2025-09-29 11:50:15'),
(3, 1, 72.00, 'pending', '2025-09-29 12:24:00', '2025-09-29 12:24:00');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `color_id` bigint(20) UNSIGNED NOT NULL,
  `size_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `color_id`, `size_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(3, 3, 8, 5, 3, 3, 24.00, '2025-09-29 12:24:00', '2025-09-29 12:24:00');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `created_at`, `updated_at`, `category_id`) VALUES
(5, 'High-Waisted Wide-Leg Trousers', 'Fashionable pants with a high waistline and legs that are wide from the hip down. They are often made from flowing fabrics like crepe or wool blends, offering both comfort and a bold, elegant silhouette.', 22.00, NULL, NULL, 1),
(6, 'Classic Blue Denim Jeans (Straight Leg)', 'The essential wardrobe staple. These jeans are made from durable denim and feature a straight leg cut from the hip to the ankle, providing a timeless and universally flattering fit.', 16.00, NULL, NULL, 1),
(7, 'Midi Shirt Dress', 'A dress that borrows details from a classic shirt, such as a button-down front, collar, and cuffed sleeves. The midi length falls between the knee and ankle. It\'s a polished yet easy-to-wear option for various occasions.', 14.00, NULL, NULL, 2),
(8, 'Little Black Dress (LBD) - A-Line', 'A timeless, knee-length dress with an A-line silhouette that is fitted at the bodice and flares out at the waist. Its simple elegance makes it perfect for cocktail parties or dinners.', 24.00, NULL, NULL, 2),
(9, 'Floral Maxi Sundress', 'A long, flowy dress that extends to the ankles. Typically made from lightweight materials like cotton or chiffon, it features thin straps and a vibrant floral print, ideal for summer and warm weather.', 19.00, NULL, NULL, 2),
(10, 'Men\'s Formal Cotton Dress Shirt', 'A crisp, tailored shirt made from 100% cotton or a cotton blend. It features a pointed collar, a button placket, and barrel cuffs. Designed to be worn with a suit and tie for formal business settings.', 13.00, NULL, NULL, 3),
(11, 'Women\'s Silk-Blend Blouse with Bow Detail', 'An elegant blouse made from luxurious silk or a silk-blend fabric. It often features a delicate design, such as a tie-neck bow or ruffled sleeves, suitable for professional and semi-formal environments.', 22.00, NULL, NULL, 3),
(12, 'Classic Flannel Shirt', 'A warm, soft shirt made from brushed cotton flannel fabric. It\'s typically cut in a relaxed, comfortable fit and features a classic plaid or checkered pattern. Perfect for casual, cool-weather wear', 26.00, NULL, NULL, 3),
(13, 'Cashmere Crewneck Sweater', 'A luxurious sweater made from soft and warm cashmere wool. The crewneck (round neck) design is simple and versatile, making it a high-quality layering piece for both casual and smart-casual outfits.', 28.00, NULL, NULL, 4),
(14, 'Chunky Cable-Knit Sweater', 'A thick, heavy-knit sweater characterized by a raised, rope-like \"cable\" pattern. It\'s extremely warm and cozy, often associated with winter and holiday casual wear.', 25.00, NULL, NULL, 4),
(15, 'Cardigan (Open-Front Sweater)', 'A sweater that opens at the front and is fastened with buttons or a zip. It\'s an incredibly versatile piece that can be worn layered over anything from a t-shirt to a dress, making it a practical wardrobe essential.', 29.00, NULL, NULL, 4),
(16, 'Cashmere Crewneck Sweater2', 'A luxurious sweater made from soft and warm cashmere wool. The crewneck (round neck) design is simple and versatile, making it a high-quality layering piece for both casual and smart-casual outfits.', 34.00, '2025-09-29 12:02:51', '2025-09-29 12:02:51', 1),
(17, 'Cashmere Crewneck Sweater3', 'A luxurious sweater made from soft and warm cashmere wool. The crewneck (round neck) design is simple and versatile, making it a high-quality layering piece for both casual and smart-casual outfits.', 34.00, '2025-09-29 12:03:54', '2025-09-29 12:03:54', 1),
(18, 'Cashmere Crewneck Sweater4', 'A luxurious sweater made from soft and warm cashmere wool. The crewneck (round neck) design is simple and versatile, making it a high-quality layering piece for both casual and smart-casual outfits.', 34.00, '2025-09-29 12:04:08', '2025-09-29 12:04:08', 1),
(19, 'short shirt', 'A luxurious shirt made from cotton .', 40.00, '2025-09-30 05:04:31', '2025-09-30 05:04:31', 1),
(21, 'short dress', 'A luxurious dress.', 40.00, '2025-09-30 05:07:22', '2025-09-30 05:07:22', 4),
(22, 'dressss', 'A luxurious dressrrr.', 30.00, '2025-09-30 07:59:42', '2025-09-30 07:59:42', 4);

-- --------------------------------------------------------

--
-- Table structure for table `product_size`
--

CREATE TABLE `product_size` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `size_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_size`
--

INSERT INTO `product_size` (`id`, `size_id`, `product_id`, `created_at`, `updated_at`) VALUES
(4, 1, 5, NULL, NULL),
(5, 3, 5, NULL, NULL),
(10, 2, 8, NULL, NULL),
(11, 2, 8, NULL, NULL),
(12, 2, 9, NULL, NULL),
(13, 1, 10, NULL, NULL),
(14, 2, 10, NULL, NULL),
(15, 3, 10, NULL, NULL),
(16, 2, 11, NULL, NULL),
(17, 2, 12, NULL, NULL),
(18, 2, 12, NULL, NULL),
(19, 2, 13, NULL, NULL),
(20, 3, 13, NULL, NULL),
(21, 2, 14, NULL, NULL),
(22, 1, 15, NULL, NULL),
(23, 2, 15, NULL, NULL),
(24, 3, 15, NULL, NULL),
(25, 1, 18, NULL, NULL),
(26, 2, 18, NULL, NULL),
(27, 1, 19, NULL, NULL),
(28, 1, 21, NULL, NULL),
(30, 2, 22, NULL, NULL),
(31, 2, 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('hmcD572WoJn3SSnAuJ2eTpQs46BNPNvJkbbt3PNt', NULL, '127.0.0.1', 'PostmanRuntime/7.48.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZFhMYm9ZSWsxNkhHOFNCNlJGbExvQ0NXa2taaTV3WlZUcDY5QzdXUiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1759230119),
('mHKKrfTiwpdfjhh3nP1QIpJai0yMmrfehCh55Ge4', NULL, '127.0.0.1', 'PostmanRuntime/7.48.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM0dvOW1iYVRRekZCWGoxdHFqbWpFTGUyNzBoRnR2TnVscm9iSmJkMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1759159135),
('OBqAyqpUSFY7TSeUDb38YIjxAPoPcb14Lxql4CHM', NULL, '127.0.0.1', 'PostmanRuntime/7.48.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaFV1VDE4aVF5SjhhbmhRR251WXg0a1BIbG1kMWthT3Q5MGpKcnZtcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1759235505);

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'L', NULL, NULL),
(2, 'M', NULL, NULL),
(3, 'S', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'mohamad ahmad', 'mohamadahmad@example.com', NULL, '$2y$12$7d.XOK8QyLoTgydTN52yBemDdv.p9jqvi9nQi5d8guj21gPkPggfK', NULL, '2025-09-29 11:39:39', '2025-09-29 11:39:39'),
(2, 'aohamad ahmads', 'sohamadahmads@example.com', NULL, '$2y$12$cE/8S24CuqYmdvAe1V4.duXwNPB0JgFcS6UjRczKSCOK0K9bFjPXe', NULL, '2025-09-30 09:30:28', '2025-09-30 09:30:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`),
  ADD KEY `cart_items_color_id_foreign` (`color_id`),
  ADD KEY `cart_items_size_id_foreign` (`size_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `color_product`
--
ALTER TABLE `color_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `color_product_color_id_foreign` (`color_id`),
  ADD KEY `color_product_product_id_foreign` (`product_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_color_id_foreign` (`color_id`),
  ADD KEY `order_items_size_id_foreign` (`size_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_size`
--
ALTER TABLE `product_size`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_size_size_id_foreign` (`size_id`),
  ADD KEY `product_size_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sizes_name_unique` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `color_product`
--
ALTER TABLE `color_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `product_size`
--
ALTER TABLE `product_size`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `color_product`
--
ALTER TABLE `color_product`
  ADD CONSTRAINT `color_product_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `color_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_size`
--
ALTER TABLE `product_size`
  ADD CONSTRAINT `product_size_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_size_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
