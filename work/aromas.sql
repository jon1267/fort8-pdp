-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 25 2021 г., 16:06
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
(4, 'древесные ароматы', 'деревні аромати', 2, NULL, '2021-01-23 14:40:00', '2021-01-23 14:40:00');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `aromas`
--
ALTER TABLE `aromas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `aromas`
--
ALTER TABLE `aromas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
