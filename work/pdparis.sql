-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 08 2021 г., 13:47
-- Версия сервера: 5.7.29
-- Версия PHP: 7.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `pdparis`
--

-- --------------------------------------------------------

--
-- Структура таблицы `aromas`
--

CREATE TABLE `aromas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ua` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `aromas`
--

INSERT INTO `aromas` (`id`, `name`, `name_ua`, `created_by_id`, `updated_by_id`, `created_at`, `updated_at`) VALUES
(1, 'морские океанические ароматы', 'морські океанічні аромати', 2, NULL, '2021-01-23 14:39:16', '2021-01-23 14:39:16'),
(2, 'цветочные ароматы', 'квіткові аромати', 2, NULL, '2021-01-23 14:39:32', '2021-01-23 14:39:32'),
(3, 'восточные ароматы', 'східні аромати', 2, 2, '2021-01-23 14:39:50', '2021-01-23 16:04:11'),
(4, 'древесные ароматы', 'деревні аромати', 2, 2, '2021-01-23 14:40:00', '2021-01-28 09:22:20'),
(6, 'какой-то новый аромат 1', 'якісь новій аромат 1', 2, 1, '2021-01-28 09:22:48', '2021-01-28 09:24:58');

-- --------------------------------------------------------

--
-- Структура таблицы `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `brands`
--

INSERT INTO `brands` (`id`, `name`, `created_by_id`, `updated_by_id`, `created_at`, `updated_at`) VALUES
(1, 'Lacoste', 2, NULL, '2021-01-23 14:37:17', '2021-01-23 14:37:17'),
(2, 'Shanel', 2, 2, '2021-01-23 14:37:26', '2021-01-23 14:37:34'),
(3, 'Nina Ricci', 2, 2, '2021-01-23 14:38:08', '2021-01-23 14:38:16'),
(4, 'Christian Dior', 2, 2, '2021-01-23 14:38:40', '2021-01-23 16:04:24');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ua` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `header_mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `header_desktop` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slider_show` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`, `name_ua`, `header_mobile`, `header_desktop`, `slider_show`, `created_by_id`, `updated_by_id`, `created_at`, `updated_at`) VALUES
(1, 'Женские парфюмы', 'Жіночі парфуми', NULL, NULL, 0, NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(2, 'Мужские парфюмы', 'Чоловічі парфуми', NULL, NULL, 0, NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(3, 'Антисептики', 'Антисептики', NULL, NULL, 0, NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(4, 'Автопарфюмы', 'Автопарфуми', NULL, NULL, 0, NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(5, 'Спреи для волос', 'Спреї для волосся', NULL, NULL, 0, NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(6, 'Годовой запас парфюма 500ml Женская парфюмерия', 'Річний запас парфуму 500ml Жіноча парфумерія', NULL, NULL, 0, NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(7, 'Годовой запас парфюма 500ml Мужская парфюмерия 1', 'Річний запас парфуму 500ml Чоловіча парфумерія 1', NULL, NULL, 0, NULL, 1, '2021-01-28 08:18:24', '2021-01-28 09:34:32');

-- --------------------------------------------------------

--
-- Структура таблицы `category_product`
--

CREATE TABLE `category_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `category_product`
--

INSERT INTO `category_product` (`id`, `category_id`, `product_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 1, NULL, NULL),
(3, 4, 1, NULL, NULL),
(4, 2, 2, NULL, NULL),
(5, 4, 2, NULL, NULL),
(12, 1, 6, NULL, NULL),
(13, 5, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2021_01_11_110639_add_role_to_users_table', 1),
(5, '2021_01_11_112605_create_categories_table', 1),
(6, '2021_01_12_130213_create_products_table', 1),
(7, '2021_01_12_132248_create_category_product_table', 1),
(8, '2021_01_14_092202_create_product_variants_table', 1),
(9, '2021_01_15_132135_create_notes_table', 1),
(10, '2021_01_15_132926_create_note_product_table', 1),
(11, '2021_01_19_162626_create_aromas_table', 1),
(12, '2021_01_20_090247_create_brands_table', 1),
(13, '2021_01_20_105217_note2_product', 1),
(14, '2021_01_20_110020_note3_product', 1),
(15, '2021_01_22_141452_create_settings_table', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `note2_product`
--

CREATE TABLE `note2_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `note_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `note2_product`
--

INSERT INTO `note2_product` (`id`, `note_id`, `product_id`, `created_at`, `updated_at`) VALUES
(1, 8, 1, NULL, NULL),
(2, 15, 1, NULL, NULL),
(3, 9, 2, NULL, NULL),
(4, 11, 2, NULL, NULL),
(11, 8, 6, NULL, NULL),
(12, 15, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `note3_product`
--

CREATE TABLE `note3_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `note_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `note3_product`
--

INSERT INTO `note3_product` (`id`, `note_id`, `product_id`, `created_at`, `updated_at`) VALUES
(1, 14, 1, NULL, NULL),
(2, 4, 2, NULL, NULL),
(9, 9, 6, NULL, NULL),
(10, 11, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `notes`
--

CREATE TABLE `notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name_ua` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ru` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `notes`
--

INSERT INTO `notes` (`id`, `name_ua`, `name_ru`, `created_by_id`, `updated_by_id`, `created_at`, `updated_at`) VALUES
(1, 'деревне', 'древесный', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(2, 'квітковий', 'цветочный', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(3, 'ароматичний', 'ароматический', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(4, 'цитрусовий', 'цитрусовый', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(5, 'свіжий пряний', 'свежий пряный', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(6, 'солодкий', 'сладкий', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(7, 'білоквітковий', 'белоцветочный', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(8, 'фруктовий', 'фруктовый', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(9, 'морський', 'морской', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(10, 'шкіряний', 'кожанный', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(11, 'зелений', 'зеленый', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(12, 'теплий пряний', 'теплый пряный', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(13, 'амбровий', 'амбровый', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(14, 'свіжий', 'свежий', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(15, 'медовий', 'медовый', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(16, 'трояндовий', 'розовый', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(17, 'какао', 'какао', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(18, 'мускусний', 'мускусный', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(19, 'бальзамічний', 'бальзамический', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(20, 'ванільний', 'ванильный', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(21, 'карамельний', 'карамельный', NULL, NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24');

-- --------------------------------------------------------

--
-- Структура таблицы `note_product`
--

CREATE TABLE `note_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `note_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `note_product`
--

INSERT INTO `note_product` (`id`, `note_id`, `product_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, NULL),
(2, 7, 1, NULL, NULL),
(3, 11, 2, NULL, NULL),
(4, 14, 2, NULL, NULL),
(11, 4, 6, NULL, NULL),
(12, 6, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `aroma_id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `vendor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `description_ua` text COLLATE utf8mb4_unicode_ci,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `aroma_id`, `brand_id`, `vendor`, `name`, `description`, `description_ua`, `img`, `created_by_id`, `updated_by_id`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'vendor 1', 'product 1', 'Lorem Ipsum - это текст-\"рыба\", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной \"рыбой\" для текстов на латинице с начала XVI века. В то время некий безымянный печатник создал большую коллекцию размеров и форм шрифтов,', 'Lorem Ipsum - це текст-\"риба\", що використовується в друкарстві та дизайні. Lorem Ipsum є, фактично, стандартною \"рибою\" аж з XVI сторіччя, коли невідомий друкар взяв шрифтову гранку та склав на ній підбірку зразків шрифтів', '1611836728-ywtz9ciL.jpg', 1, 2, '2021-01-28 09:40:10', '2021-01-28 10:25:28'),
(2, 3, 4, 'vendor 2', 'product 2', 'Lorem Ipsum - это текст-\"рыба\", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной \"рыбой\" для текстов на латинице с начала XVI века. В то время некий безымянный печатник создал большую коллекцию размеров и форм шрифтов,', 'Lorem Ipsum - це текст-\"риба\", що використовується в друкарстві та дизайні. Lorem Ipsum є, фактично, стандартною \"рибою\" аж з XVI сторіччя, коли невідомий друкар взяв шрифтову гранку та склав на ній підбірку зразків шрифтів. \"Риба\" не тільки', '1611834161-SN5Jd8pE.jpg', 2, NULL, '2021-01-28 09:42:41', '2021-01-28 09:42:41'),
(6, 3, 2, 'vendor 3', 'product 3', 'Многие думают, что Lorem Ipsum - взятый с потолка псевдо-латинский набор слов, но это не совсем так. Его корни уходят в один фрагмент классической латыни 45 года н.э., то есть более двух тысячелетий назад.', 'На відміну від поширеної думки Lorem Ipsum не є випадковим набором літер. Він походить з уривку класичної латинської літератури 45 року до н.е., тобто має більш як 2000-річну історію.', '1611836513-xjI12onA.jpg', 2, NULL, '2021-01-28 10:21:53', '2021-01-28 10:21:53');

-- --------------------------------------------------------

--
-- Структура таблицы `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` double(8,2) NOT NULL,
  `art` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_ua` int(11) NOT NULL,
  `price_ru` int(11) NOT NULL,
  `active_ua` tinyint(4) DEFAULT NULL,
  `active_ru` tinyint(4) DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `name`, `volume`, `art`, `price_ua`, `price_ru`, `active_ua`, `active_ru`, `created_by_id`, `updated_by_id`, `created_at`, `updated_at`) VALUES
(3, 2, 'ml', 15.00, '1234-11', 100, 200, 1, 1, NULL, NULL, NULL, NULL),
(6, 6, 'ml', 30.00, '1111-11', 500, 1100, 1, 0, 2, 2, '2021-01-28 10:21:53', '2021-01-28 10:21:53'),
(7, 6, 'ml', 50.00, '2222-22', 600, 1200, 0, 1, 2, 2, '2021-01-28 10:21:53', '2021-01-28 10:21:53'),
(11, 1, 'ml', 25.00, '1111-11', 500, 1100, 1, 0, 2, 2, '2021-01-28 10:25:28', '2021-01-28 10:25:28'),
(12, 1, 'ml', 35.00, '2222-22', 600, 1200, 0, 1, 2, 2, '2021-01-28 10:25:28', '2021-01-28 10:25:28'),
(13, 1, 'ml', 50.00, '3333-33', 800, 1600, 1, 1, 2, 2, '2021-01-28 10:25:28', '2021-01-28 10:25:28');

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `analytic_code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `header_mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `header_desktop` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slider_show` tinyint(3) UNSIGNED NOT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `analytic_code`, `header_mobile`, `header_desktop`, `slider_show`, `created_by_id`, `updated_by_id`, `created_at`, `updated_at`) VALUES
(1, 'Код аналитики', '1611833608-VTOa2dhs.jpg', '1611833608-dtQ3gm4Z.jpg', 1, NULL, 1, '2021-01-28 08:18:24', '2021-01-28 09:33:28');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `role`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Admin', 'kleo0707@mail.com', '2021-01-28 08:18:24', '$2y$10$5J3X28PV3zbKdMWSheiOheVSDP0D/4MI0u4EiwZAZ9VRjSnq76fz2', NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24'),
(2, 'admin', 'test', 'test@test.com', '2021-01-28 08:18:24', '$2y$10$/NUJzFJy3chJ4e6wgrtkbOvRIwQyw8gYneENzlHgAy5JEC1lsi/m.', NULL, '2021-01-28 08:18:24', '2021-01-28 08:18:24');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `aromas`
--
ALTER TABLE `aromas`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `category_product`
--
ALTER TABLE `category_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_product_category_id_foreign` (`category_id`),
  ADD KEY `category_product_product_id_foreign` (`product_id`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `note2_product`
--
ALTER TABLE `note2_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `note2_product_note_id_foreign` (`note_id`),
  ADD KEY `note2_product_product_id_foreign` (`product_id`);

--
-- Индексы таблицы `note3_product`
--
ALTER TABLE `note3_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `note3_product_note_id_foreign` (`note_id`),
  ADD KEY `note3_product_product_id_foreign` (`product_id`);

--
-- Индексы таблицы `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `note_product`
--
ALTER TABLE `note_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `note_product_note_id_foreign` (`note_id`),
  ADD KEY `note_product_product_id_foreign` (`product_id`);

--
-- Индексы таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `aromas`
--
ALTER TABLE `aromas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `category_product`
--
ALTER TABLE `category_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `note2_product`
--
ALTER TABLE `note2_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `note3_product`
--
ALTER TABLE `note3_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `notes`
--
ALTER TABLE `notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `note_product`
--
ALTER TABLE `note_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `category_product`
--
ALTER TABLE `category_product`
  ADD CONSTRAINT `category_product_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `category_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ограничения внешнего ключа таблицы `note2_product`
--
ALTER TABLE `note2_product`
  ADD CONSTRAINT `note2_product_note_id_foreign` FOREIGN KEY (`note_id`) REFERENCES `notes` (`id`),
  ADD CONSTRAINT `note2_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ограничения внешнего ключа таблицы `note3_product`
--
ALTER TABLE `note3_product`
  ADD CONSTRAINT `note3_product_note_id_foreign` FOREIGN KEY (`note_id`) REFERENCES `notes` (`id`),
  ADD CONSTRAINT `note3_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ограничения внешнего ключа таблицы `note_product`
--
ALTER TABLE `note_product`
  ADD CONSTRAINT `note_product_note_id_foreign` FOREIGN KEY (`note_id`) REFERENCES `notes` (`id`),
  ADD CONSTRAINT `note_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
