-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2025 at 05:05 AM
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
-- Database: `alumni`
--

-- --------------------------------------------------------

--
-- Table structure for table `alumni`
--

CREATE TABLE `alumni` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alumni_posts`
--

CREATE TABLE `alumni_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `content` text NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `event_date` timestamp NULL DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `max_registrations` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alumni_posts`
--

INSERT INTO `alumni_posts` (`id`, `user_id`, `is_archived`, `is_completed`, `content`, `title`, `description`, `event_date`, `location`, `max_registrations`, `image_path`, `created_at`, `updated_at`) VALUES
(3, 1, 0, 1, 'test', 'test', 'this a test for all shits', '2025-10-23 09:56:00', 'ortigas', NULL, 'alumni-posts/3WRjPWiIlHc392o1LdXPGlSx1jktUTjUxpBnLcgu.jpg', '2025-10-24 01:57:14', '2025-10-31 12:26:27'),
(4, 1, 0, 1, 'safasfas', 'sdgds', 'sdgdsgds', '2025-10-26 05:10:00', 'Quezon Cityfhfdhfd', NULL, 'alumni-posts/wXOxYwjqvTJLc9rsXTzJRiAJYlvEPui58KpDv1PN.png', '2025-10-24 02:09:34', '2025-10-31 12:26:27'),
(5, 1, 0, 1, 'dsgsdgsdgsdg', 'test eventsus', 'sdgsdgsdgsdg', '2025-10-28 05:14:00', 'Quezon City', 15, 'alumni-posts/lvYrBBvOtKzmrgpiy6L308JKZCqPK1CkdzMN7fsb.png', '2025-10-28 12:14:50', '2025-10-31 12:26:27'),
(6, 1, 0, 0, 'basta tangina yan', 'test tan', 'dsdsgds', '2025-11-13 04:31:00', 'makati', 100, 'alumni-posts/dak6t7a4SLpYAOXDukqSSyqN84HnX3e8Q6NLwAP7.png', '2025-11-07 11:30:59', '2025-11-07 11:36:05'),
(7, 1, 0, 0, 'this event details', 'this title', 'this description', '2025-12-23 23:23:00', 'Quezon City', 100, 'alumni-posts/KYZL0cZtnYhDY37XjMMw7NQYZrQTaOHmKSuVMo4m.png', '2025-11-09 11:45:47', '2025-11-09 11:45:47');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `alumni_post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('attending','not_attending','maybe') NOT NULL DEFAULT 'attending',
  `checked_in_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('alumni_cache_rensacuna11@gmail.com|127.0.0.1', 'i:1;', 1761489639),
('alumni_cache_rensacuna11@gmail.com|127.0.0.1:timer', 'i:1761489639;', 1761489639);

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
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `alumni_post_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `alumni_post_id`, `parent_id`, `content`, `created_at`, `updated_at`) VALUES
(10, 14, 6, NULL, 'dsgsdgsd mga kupal', '2025-11-07 11:50:00', '2025-11-07 11:50:00'),
(11, 11, 7, NULL, 'pakyu', '2025-11-09 11:57:20', '2025-11-09 11:57:20');

-- --------------------------------------------------------

--
-- Table structure for table `comment_likes`
--

CREATE TABLE `comment_likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `comment_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comment_likes`
--

INSERT INTO `comment_likes` (`id`, `created_at`, `updated_at`, `user_id`, `comment_id`) VALUES
(14, '2025-11-07 11:50:05', '2025-11-07 11:50:05', 14, 10),
(15, '2025-11-09 11:57:25', '2025-11-09 11:57:25', 11, 11);

-- --------------------------------------------------------

--
-- Table structure for table `comment_replies`
--

CREATE TABLE `comment_replies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `comment_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_requests`
--

CREATE TABLE `document_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `receipt_image` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Confirmed') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `user_id`, `amount`, `receipt_image`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 100000.00, 'donations/JkoZrlNa0HNEO43DvqOBXx2k7VxqTR1AFcZL8xtV.png', 'Confirmed', '2025-08-31 10:41:52', '2025-09-05 14:17:25'),
(2, 11, 2000.00, 'donations/5Q59mUPRanhf5eIYC2n1C374unZ6lvW2t3kXHWmo.png', 'Confirmed', '2025-11-05 20:48:44', '2025-11-05 20:54:01');

-- --------------------------------------------------------

--
-- Table structure for table `event_registrations`
--

CREATE TABLE `event_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `alumni_post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'registered',
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_registrations`
--

INSERT INTO `event_registrations` (`id`, `alumni_post_id`, `user_id`, `status`, `category`, `created_at`, `updated_at`) VALUES
(8, 4, 11, 'attended', 'Alumni', '2025-10-25 02:20:31', '2025-10-25 02:34:50');

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
-- Table structure for table `final_assessments`
--

CREATE TABLE `final_assessments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `training_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `passing_score` int(11) NOT NULL DEFAULT 70,
  `time_limit` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `final_assessments`
--

INSERT INTO `final_assessments` (`id`, `training_id`, `title`, `description`, `passing_score`, `time_limit`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 10, 'dsgdsg', 'gsdgsd', 50, NULL, 1, '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(4, 11, 'jfgjfgjfg', 'jfg', 70, NULL, 1, '2025-11-09 11:29:51', '2025-11-09 11:29:51');

-- --------------------------------------------------------

--
-- Table structure for table `final_assessment_choices`
--

CREATE TABLE `final_assessment_choices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `choice_letter` enum('A','B','C','D') NOT NULL,
  `choice_text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `final_assessment_choices`
--

INSERT INTO `final_assessment_choices` (`id`, `question_id`, `choice_letter`, `choice_text`, `created_at`, `updated_at`) VALUES
(17, 5, 'A', 'gdsgsd', '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(18, 5, 'B', 'gsdg', '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(19, 5, 'C', 'sdgsdg', '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(20, 5, 'D', 'dsgds', '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(21, 6, 'A', 'dsgsd', '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(22, 6, 'B', 'gsdg', '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(23, 6, 'C', 'gsdg', '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(24, 6, 'D', 'sdgds', '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(25, 7, 'A', 'fjgjfg', '2025-11-09 11:29:51', '2025-11-09 11:29:51'),
(26, 7, 'B', 'jfgjfg', '2025-11-09 11:29:51', '2025-11-09 11:29:51'),
(27, 7, 'C', 'jfg', '2025-11-09 11:29:51', '2025-11-09 11:29:51'),
(28, 7, 'D', 'gfjfgjfg', '2025-11-09 11:29:52', '2025-11-09 11:29:52');

-- --------------------------------------------------------

--
-- Table structure for table `final_assessment_questions`
--

CREATE TABLE `final_assessment_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `final_assessment_id` bigint(20) UNSIGNED NOT NULL,
  `question_text` text NOT NULL,
  `correct_answer` enum('A','B','C','D') NOT NULL,
  `points` int(11) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `final_assessment_questions`
--

INSERT INTO `final_assessment_questions` (`id`, `final_assessment_id`, `question_text`, `correct_answer`, `points`, `order`, `created_at`, `updated_at`) VALUES
(5, 3, 'dsgdsgsdg', 'A', 1, 1, '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(6, 3, 'dsgds', 'A', 1, 2, '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(7, 4, 'gfjfgjf', 'B', 1, 1, '2025-11-09 11:29:51', '2025-11-09 11:29:51');

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
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `alumni_post_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_03_072536_add_role_to_users_table', 1),
(5, '2025_08_03_072816_add_role_to_users_table', 1),
(6, '2025_08_07_104847_create_news_table', 1),
(7, '2025_08_07_111512_create_alumni_table', 1),
(8, '2025_08_07_125104_create_alumni_posts_table', 1),
(9, '2025_08_13_044941_create_resumes_table', 1),
(10, '2025_08_13_070000_create_comments_table', 1),
(11, '2025_08_13_094222_create_comment_replies_table', 1),
(12, '2025_08_13_094427_create_comment_likes_table', 1),
(13, '2025_08_13_104221_create_likes_table', 1),
(14, '2025_08_15_135850_create_messages_table', 1),
(15, '2025_08_27_120306_create_donations_table', 1),
(16, '2025_08_31_032613_rename_post_id_to_alumni_post_id_in_comments_table', 2),
(17, '2025_08_31_033256_add_missing_columns_to_comment_likes_table', 3),
(18, '2025_10_11_121158_add_title_description_to_alumni_posts_table', 4),
(19, '2025_10_23_191339_add_is_alumni_to_users_table', 5),
(20, '2025_10_24_182714_create_reviews_table', 6),
(21, '2025_10_28_045920_add_is_archived_to_alumni_posts_table', 7),
(22, '2025_10_28_051107_add_max_registrations_to_alumni_posts_table', 8),
(23, '2025_10_31_052345_add_is_completed_to_alumni_posts_table', 9),
(24, '2025_11_07_012433_add_assessment_type_to_training_files_table', 10),
(25, '2025_11_07_012435_create_quizzes_table', 11),
(26, '2025_11_07_012437_create_questions_table', 12),
(27, '2025_11_07_012439_create_question_choices_table', 13),
(28, '2025_11_07_012442_create_user_quiz_answers_table', 14),
(29, '2025_11_07_012522_create_user_quiz_attempts_table', 15),
(30, '2025_11_07_012444_create_final_assessments_table', 16),
(31, '2025_11_07_012446_create_final_assessment_questions_table', 17),
(32, '2025_11_07_012520_create_final_assessment_choices_table', 18),
(33, '2025_11_07_012448_create_user_final_assessment_answers_table', 19),
(34, '2025_11_07_012523_create_user_final_assessment_attempts_table', 20),
(35, '2025_11_07_020658_add_training_id_to_final_assessments_table', 21),
(36, '2025_11_07_030841_add_resume_fields_to_resumes_table', 22),
(37, '2025_11_07_034005_add_last_name_to_users_table', 23);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `image_path`, `created_at`, `updated_at`) VALUES
(1, 'dgsdg', 'sdgsdgsd', 'news_images/R7vugVNaFHbqljKq1gFYwgeZTYoxsxkWB6uP3pCd.jpg', '2025-10-24 02:07:10', '2025-10-24 02:07:10');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `related_model` varchar(255) DEFAULT NULL,
  `related_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `question_text` text NOT NULL,
  `correct_answer` enum('A','B','C','D') NOT NULL,
  `points` int(11) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `correct_answer`, `points`, `order`, `created_at`, `updated_at`) VALUES
(7, 4, 'dsgdsgd', 'A', 1, 1, '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(8, 4, 'dsgdsgsd', 'A', 1, 2, '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(9, 5, 'gfjfgjfg', 'B', 1, 1, '2025-11-09 11:29:51', '2025-11-09 11:29:51'),
(10, 5, 'gfjfgjfg', 'C', 1, 2, '2025-11-09 11:29:51', '2025-11-09 11:29:51');

-- --------------------------------------------------------

--
-- Table structure for table `question_choices`
--

CREATE TABLE `question_choices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `choice_letter` enum('A','B','C','D') NOT NULL,
  `choice_text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_choices`
--

INSERT INTO `question_choices` (`id`, `question_id`, `choice_letter`, `choice_text`, `created_at`, `updated_at`) VALUES
(25, 7, 'A', 'dgds', '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(26, 7, 'B', 'gsdgds', '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(27, 7, 'C', 'gsdg', '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(28, 7, 'D', 'dsgsd', '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(29, 8, 'A', 'dsgdsg', '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(30, 8, 'B', 'dgdsg', '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(31, 8, 'C', 'dsgsd', '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(32, 8, 'D', 'gsdg', '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(33, 9, 'A', 'jfgjfgj', '2025-11-09 11:29:51', '2025-11-09 11:29:51'),
(34, 9, 'B', 'gfjfgj', '2025-11-09 11:29:51', '2025-11-09 11:29:51'),
(35, 9, 'C', 'fgjfg', '2025-11-09 11:29:51', '2025-11-09 11:29:51'),
(36, 9, 'D', 'jfgj', '2025-11-09 11:29:51', '2025-11-09 11:29:51'),
(37, 10, 'A', 'gfjgf', '2025-11-09 11:29:51', '2025-11-09 11:29:51'),
(38, 10, 'B', 'jfgjfg', '2025-11-09 11:29:51', '2025-11-09 11:29:51'),
(39, 10, 'C', 'jfgjfg', '2025-11-09 11:29:51', '2025-11-09 11:29:51'),
(40, 10, 'D', 'fgjfgjfg', '2025-11-09 11:29:51', '2025-11-09 11:29:51');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `training_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `passing_score` int(11) NOT NULL DEFAULT 70,
  `time_limit` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `training_id`, `title`, `description`, `passing_score`, `time_limit`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 10, 'safasf', 'safasf', 20, NULL, 1, '2025-11-07 10:47:09', '2025-11-07 10:47:09'),
(5, 11, 'gfjfgjfg', 'jfgjfg', 70, NULL, 1, '2025-11-09 11:29:51', '2025-11-09 11:29:51');

-- --------------------------------------------------------

--
-- Table structure for table `resumes`
--

CREATE TABLE `resumes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `objective` text DEFAULT NULL,
  `educational_attainment` text DEFAULT NULL,
  `training_seminars` text DEFAULT NULL,
  `work_experience` text DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `resumes`
--

INSERT INTO `resumes` (`id`, `user_id`, `full_name`, `contact_number`, `email`, `objective`, `educational_attainment`, `training_seminars`, `work_experience`, `file_name`, `file_path`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'RESUME-UPDATED 2025.pdf', 'resumes/kXt76et5PwYDGnl1PwDUPjf1PmCAxr0yGRgI9fOK.pdf', '2025-08-31 10:19:25', '2025-08-31 10:19:25'),
(2, 11, 'rens ACUNA', '093048789976', 'rensacuna1@gmail.com', 'KUPAL SA TRABAHO MAGDMAG', 'BASTA TANGINA MO', 'SECRET BOLD YUN', 'WLA TAMABAUY', 'resume_2_1762485536.pdf', 'resumes/resume_2_1762485536.pdf', '2025-11-07 11:18:56', '2025-11-07 11:18:57'),
(3, 11, 'rens acuna', '098989898989', 'rensacuna1@gmail.com', 'fsafasf', 'asfas', 'safasfassa', 'fsafsa', 'resume_3_1762485729.pdf', 'resumes/resume_3_1762485729.pdf', '2025-11-07 11:22:08', '2025-11-07 11:22:09');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `alumni_post_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(10) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `alumni_post_id`, `rating`, `comment`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 11, 4, 4, 'fsasfa', 1, '2025-10-25 01:29:03', '2025-10-25 01:29:03');

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
('HVFW07xtbfIwPuCM2AlaWtJ8wYnnHPTHcmgmZNYq', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNHI1ZXowT0FNMDFMU0prcmNqbnpyd0dFTHlLWmtDbGVZNlJhbmNTZiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ub3RpZmljYXRpb25zL3VucmVhZC1jb3VudCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1762661143);

-- --------------------------------------------------------

--
-- Table structure for table `trainings`
--

CREATE TABLE `trainings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `certificate_path` varchar(255) DEFAULT NULL,
  `certificate` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `progress` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 CHECK (`progress` between 0 and 100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trainings`
--

INSERT INTO `trainings` (`id`, `title`, `description`, `certificate_path`, `certificate`, `created_at`, `updated_at`, `progress`) VALUES
(10, 'test', 'safasf', 'trainings/10/certificate/I1Z6I9nBOKPAMIRQJmhZpcedjMHBIr9noUDf8V7u.png', NULL, '2025-11-07 10:47:09', '2025-11-07 10:47:09', 0),
(11, 'gfjfg', 'jfgjfgjfg', 'trainings/11/certificate/mFWdXaq5zuxw589pZVv3N1aw4CwMsU4LX2MEUqm3.png', NULL, '2025-11-09 11:29:49', '2025-11-09 11:29:51', 0);

-- --------------------------------------------------------

--
-- Table structure for table `training_files`
--

CREATE TABLE `training_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `training_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `type` enum('module','certificate') DEFAULT 'module',
  `assessment_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training_files`
--

INSERT INTO `training_files` (`id`, `training_id`, `path`, `original_name`, `mime_type`, `type`, `assessment_type`, `created_at`, `updated_at`) VALUES
(8, 11, 'trainings/11/modules/On3SJw5KQp4eSp3lBV8S2hJjd2JTmwyV40tqMkKI.pdf', 'gawin 10.pdf', 'application/pdf', 'module', NULL, '2025-11-09 11:29:51', '2025-11-09 11:29:51');

-- --------------------------------------------------------

--
-- Table structure for table `training_reads`
--

CREATE TABLE `training_reads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `training_file_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training_reads`
--

INSERT INTO `training_reads` (`id`, `user_id`, `training_file_id`, `created_at`, `updated_at`) VALUES
(1, 11, 8, '2025-11-09 11:30:55', '2025-11-09 11:30:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `is_alumni` tinyint(1) NOT NULL DEFAULT 0,
  `middle_name` varchar(255) DEFAULT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `year_graduated` year(4) DEFAULT NULL,
  `program` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `profile_image_path` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

  INSERT INTO `users` (`id`, `name`, `last_name`, `email`, `email_verified_at`, `password`, `role`, `is_alumni`, `middle_name`, `suffix`, `year_graduated`, `program`, `gender`, `status`, `contact_number`, `address`, `profile_image_path`, `remember_token`, `created_at`, `updated_at`) VALUES
  (1, 'Admin User', NULL, 'admin@alumni.com', NULL, '$2y$12$Bq5m7Bvt7nPxFXN8xmYtqeV0mFzn7kxiUuqfen2nQljD8OH9GIOjq', 'admin', 0, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, '2025-08-31 10:02:03', '2025-08-31 10:02:03'),
  (2, 'System Administrator', NULL, 'system@alumni.com', NULL, '$2y$12$UtWECkdlyARg2/iATSXN1e4u9hVL/bo.5EDfDEYNk5k8w29t4bmPu', 'admin', 0, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, NULL, '2025-08-31 10:02:04', '2025-08-31 10:02:04'),
  (3, 'Test User', NULL, 'test@example.com', NULL, '$2y$12$A1peCz6e7IEH.I6S9yWpGu.D43vyDI6s0/ACOW1BLGyHhGHxDbgrm', 'user', 0, NULL, NULL, '2004', 'BSIT', NULL, 'ALUMNI', NULL, NULL, NULL, NULL, '2025-08-31 10:02:04', '2025-09-21 19:34:02'),
  (4, 'rens', NULL, 'wasieacuana@gmail.com', NULL, '$2y$12$VT6b/5BaBYrMPbLWBX0Ah.c7UiowSMBYGFC1erO2FA17NFZvZdmDa', 'user', 0, 'b', 's', '2004', 'BSCS', 'female', 'ALUMNI', '09105395990', 'one ynares village sitio paopwan bgry san salvador baras rizal', 'profiles/veQvHDp5xy5NcXeEg6rlVYfzyvWp9xPKldIPw9tt.png', NULL, '2025-08-31 10:05:52', '2025-10-09 00:17:40'),
  (10, 'assistant', NULL, 'rensacuna11@gmail.com', NULL, '$2y$12$Rr.IR6a1Ag.ZdNytz4n60Ofkvn7WPpAtuN/hweEiWL7XduhY9GxTa', 'assistant', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 01:32:03', '2025-10-09 01:32:03'),
  (11, 'rens', 'acuna', 'rensacuna1@gmail.com', NULL, '$2y$12$ERbMlQWoErX5xitXLIUz8uJ9rw3mVZBUqYreA5rffJujJhEZe8ltS', 'user', 1, 'b', 's', '2004', 'BSIT', 'Male', 'SIGNAL', '093048797256383', 'one ynares village sitio paopwan bgry san salvador baras rizal', 'profiles/FKTT7dpmIzXhPTbowSIl3hkJu766CMxyNM0TdPxM.png', NULL, '2025-10-24 02:17:19', '2025-11-07 11:44:29'),
  (14, 'rens', NULL, 'wasieacuna@gmail.com', NULL, '$2y$12$byiK2xQBgSlzPe1yTOw8luOCv4G2TAXmfUIM0bDwhFXjR7srxS7jK', 'assistant', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-07 11:47:51', '2025-11-07 11:47:51');

-- --------------------------------------------------------

--
-- Table structure for table `user_final_assessment_answers`
--

CREATE TABLE `user_final_assessment_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `final_assessment_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `selected_answer` enum('A','B','C','D') NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `points_earned` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_final_assessment_answers`
--

INSERT INTO `user_final_assessment_answers` (`id`, `user_id`, `final_assessment_id`, `question_id`, `selected_answer`, `is_correct`, `points_earned`, `created_at`, `updated_at`) VALUES
(5, 11, 3, 5, 'A', 1, 1, '2025-11-07 10:51:34', '2025-11-07 10:51:34'),
(6, 11, 3, 6, 'A', 1, 1, '2025-11-07 10:51:35', '2025-11-07 10:51:35');

-- --------------------------------------------------------

--
-- Table structure for table `user_final_assessment_attempts`
--

CREATE TABLE `user_final_assessment_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `final_assessment_id` bigint(20) UNSIGNED NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `total_points` int(11) NOT NULL DEFAULT 0,
  `percentage` int(11) NOT NULL DEFAULT 0,
  `passed` tinyint(1) NOT NULL DEFAULT 0,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_final_assessment_attempts`
--

INSERT INTO `user_final_assessment_attempts` (`id`, `user_id`, `final_assessment_id`, `score`, `total_points`, `percentage`, `passed`, `started_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(4, 11, 3, 2, 2, 100, 1, '2025-11-07 10:51:31', '2025-11-07 10:51:35', '2025-11-07 10:51:31', '2025-11-07 10:51:35');

-- --------------------------------------------------------

--
-- Table structure for table `user_module_progress`
--

CREATE TABLE `user_module_progress` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `training_file_id` bigint(20) UNSIGNED NOT NULL,
  `scroll_progress` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `time_spent` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `completion_percentage` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `last_accessed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_module_progress`
--

INSERT INTO `user_module_progress` (`id`, `user_id`, `training_file_id`, `scroll_progress`, `time_spent`, `completion_percentage`, `is_completed`, `last_accessed_at`, `created_at`, `updated_at`) VALUES
(9, 11, 8, 100, 60, 100, 1, '2025-11-09 11:33:36', '2025-11-09 11:31:01', '2025-11-09 11:33:36');

-- --------------------------------------------------------

--
-- Table structure for table `user_quiz_answers`
--

CREATE TABLE `user_quiz_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `selected_answer` enum('A','B','C','D') NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `points_earned` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_quiz_answers`
--

INSERT INTO `user_quiz_answers` (`id`, `user_id`, `quiz_id`, `question_id`, `selected_answer`, `is_correct`, `points_earned`, `created_at`, `updated_at`) VALUES
(11, 11, 4, 7, 'A', 1, 1, '2025-11-07 10:47:38', '2025-11-07 10:47:38'),
(12, 11, 4, 8, 'C', 0, 0, '2025-11-07 10:47:38', '2025-11-07 10:47:38');

-- --------------------------------------------------------

--
-- Table structure for table `user_quiz_attempts`
--

CREATE TABLE `user_quiz_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `total_points` int(11) NOT NULL DEFAULT 0,
  `percentage` int(11) NOT NULL DEFAULT 0,
  `passed` tinyint(1) NOT NULL DEFAULT 0,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_quiz_attempts`
--

INSERT INTO `user_quiz_attempts` (`id`, `user_id`, `quiz_id`, `score`, `total_points`, `percentage`, `passed`, `started_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(6, 11, 4, 1, 2, 50, 1, '2025-11-07 10:47:33', '2025-11-07 10:47:38', '2025-11-07 10:47:33', '2025-11-07 10:47:38'),
(7, 11, 5, 0, 2, 0, 0, '2025-11-09 11:30:38', NULL, '2025-11-09 11:30:38', '2025-11-09 11:30:38');

-- --------------------------------------------------------

--
-- Table structure for table `user_training_progress`
--

CREATE TABLE `user_training_progress` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `training_id` bigint(20) UNSIGNED NOT NULL,
  `progress` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_training_progress`
--

INSERT INTO `user_training_progress` (`id`, `user_id`, `training_id`, `progress`, `created_at`, `updated_at`) VALUES
(10, 11, 11, 100, '2025-11-09 11:30:55', '2025-11-09 11:30:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alumni`
--
ALTER TABLE `alumni`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alumni_posts`
--
ALTER TABLE `alumni_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumni_posts_user_id_foreign` (`user_id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`alumni_post_id`,`user_id`),
  ADD KEY `fk_attendance_user` (`user_id`);

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
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_user_id_foreign` (`user_id`),
  ADD KEY `comments_post_id_foreign` (`alumni_post_id`),
  ADD KEY `comments_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_likes_user_id_foreign` (`user_id`),
  ADD KEY `comment_likes_comment_id_foreign` (`comment_id`);

--
-- Indexes for table `comment_replies`
--
ALTER TABLE `comment_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_replies_comment_id_foreign` (`comment_id`),
  ADD KEY `comment_replies_user_id_foreign` (`user_id`);

--
-- Indexes for table `document_requests`
--
ALTER TABLE `document_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_document_requests_user` (`user_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_alumni_user` (`alumni_post_id`,`user_id`),
  ADD KEY `fk_event_registrations_user` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `final_assessments`
--
ALTER TABLE `final_assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `final_assessments_training_id_foreign` (`training_id`);

--
-- Indexes for table `final_assessment_choices`
--
ALTER TABLE `final_assessment_choices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `final_assessment_choices_question_id_foreign` (`question_id`);

--
-- Indexes for table `final_assessment_questions`
--
ALTER TABLE `final_assessment_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `final_assessment_questions_final_assessment_id_foreign` (`final_assessment_id`);

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
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `likes_user_id_foreign` (`user_id`),
  ADD KEY `likes_alumni_post_id_foreign` (`alumni_post_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_isread_index` (`user_id`,`is_read`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_quiz_id_foreign` (`quiz_id`);

--
-- Indexes for table `question_choices`
--
ALTER TABLE `question_choices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_choices_question_id_foreign` (`question_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizzes_training_id_foreign` (`training_id`);

--
-- Indexes for table `resumes`
--
ALTER TABLE `resumes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resumes_user_id_foreign` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_user_id_alumni_post_id_unique` (`user_id`,`alumni_post_id`),
  ADD KEY `reviews_alumni_post_id_foreign` (`alumni_post_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `trainings`
--
ALTER TABLE `trainings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `training_files`
--
ALTER TABLE `training_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_training_files_training` (`training_id`);

--
-- Indexes for table `training_reads`
--
ALTER TABLE `training_reads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_file` (`user_id`,`training_file_id`),
  ADD KEY `fk_training_reads_file` (`training_file_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_final_assessment_answers`
--
ALTER TABLE `user_final_assessment_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_final_assessment_answers_user_id_foreign` (`user_id`),
  ADD KEY `user_final_assessment_answers_final_assessment_id_foreign` (`final_assessment_id`),
  ADD KEY `user_final_assessment_answers_question_id_foreign` (`question_id`);

--
-- Indexes for table `user_final_assessment_attempts`
--
ALTER TABLE `user_final_assessment_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_final_assessment_attempts_user_id_foreign` (`user_id`),
  ADD KEY `user_final_assessment_attempts_final_assessment_id_foreign` (`final_assessment_id`);

--
-- Indexes for table `user_module_progress`
--
ALTER TABLE `user_module_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_module` (`user_id`,`training_file_id`),
  ADD KEY `fk_user_module_progress_file` (`training_file_id`);

--
-- Indexes for table `user_quiz_answers`
--
ALTER TABLE `user_quiz_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_quiz_answers_user_id_foreign` (`user_id`),
  ADD KEY `user_quiz_answers_quiz_id_foreign` (`quiz_id`),
  ADD KEY `user_quiz_answers_question_id_foreign` (`question_id`);

--
-- Indexes for table `user_quiz_attempts`
--
ALTER TABLE `user_quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_quiz_attempts_user_id_foreign` (`user_id`),
  ADD KEY `user_quiz_attempts_quiz_id_foreign` (`quiz_id`);

--
-- Indexes for table `user_training_progress`
--
ALTER TABLE `user_training_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_training` (`user_id`,`training_id`),
  ADD KEY `fk_user_training_progress_training` (`training_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alumni`
--
ALTER TABLE `alumni`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alumni_posts`
--
ALTER TABLE `alumni_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `comment_likes`
--
ALTER TABLE `comment_likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `comment_replies`
--
ALTER TABLE `comment_replies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_requests`
--
ALTER TABLE `document_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `event_registrations`
--
ALTER TABLE `event_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `final_assessments`
--
ALTER TABLE `final_assessments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `final_assessment_choices`
--
ALTER TABLE `final_assessment_choices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `final_assessment_questions`
--
ALTER TABLE `final_assessment_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `question_choices`
--
ALTER TABLE `question_choices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `resumes`
--
ALTER TABLE `resumes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `trainings`
--
ALTER TABLE `trainings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `training_files`
--
ALTER TABLE `training_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `training_reads`
--
ALTER TABLE `training_reads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_final_assessment_answers`
--
ALTER TABLE `user_final_assessment_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_final_assessment_attempts`
--
ALTER TABLE `user_final_assessment_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_module_progress`
--
ALTER TABLE `user_module_progress`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_quiz_answers`
--
ALTER TABLE `user_quiz_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_quiz_attempts`
--
ALTER TABLE `user_quiz_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_training_progress`
--
ALTER TABLE `user_training_progress`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alumni_posts`
--
ALTER TABLE `alumni_posts`
  ADD CONSTRAINT `alumni_posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `fk_attendance_alumni_post` FOREIGN KEY (`alumni_post_id`) REFERENCES `alumni_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_attendance_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`alumni_post_id`) REFERENCES `alumni_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD CONSTRAINT `comment_likes_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comment_replies`
--
ALTER TABLE `comment_replies`
  ADD CONSTRAINT `comment_replies_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `document_requests`
--
ALTER TABLE `document_requests`
  ADD CONSTRAINT `fk_document_requests_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD CONSTRAINT `fk_event_registrations_alumni_post` FOREIGN KEY (`alumni_post_id`) REFERENCES `alumni_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_event_registrations_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `final_assessments`
--
ALTER TABLE `final_assessments`
  ADD CONSTRAINT `final_assessments_training_id_foreign` FOREIGN KEY (`training_id`) REFERENCES `trainings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `final_assessment_choices`
--
ALTER TABLE `final_assessment_choices`
  ADD CONSTRAINT `final_assessment_choices_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `final_assessment_questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `final_assessment_questions`
--
ALTER TABLE `final_assessment_questions`
  ADD CONSTRAINT `final_assessment_questions_final_assessment_id_foreign` FOREIGN KEY (`final_assessment_id`) REFERENCES `final_assessments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_alumni_post_id_foreign` FOREIGN KEY (`alumni_post_id`) REFERENCES `alumni_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_choices`
--
ALTER TABLE `question_choices`
  ADD CONSTRAINT `question_choices_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_training_id_foreign` FOREIGN KEY (`training_id`) REFERENCES `trainings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `resumes`
--
ALTER TABLE `resumes`
  ADD CONSTRAINT `resumes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_alumni_post_id_foreign` FOREIGN KEY (`alumni_post_id`) REFERENCES `alumni_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `training_files`
--
ALTER TABLE `training_files`
  ADD CONSTRAINT `fk_training_files_training` FOREIGN KEY (`training_id`) REFERENCES `trainings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `training_reads`
--
ALTER TABLE `training_reads`
  ADD CONSTRAINT `fk_training_reads_file` FOREIGN KEY (`training_file_id`) REFERENCES `training_files` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_training_reads_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_final_assessment_answers`
--
ALTER TABLE `user_final_assessment_answers`
  ADD CONSTRAINT `user_final_assessment_answers_final_assessment_id_foreign` FOREIGN KEY (`final_assessment_id`) REFERENCES `final_assessments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_final_assessment_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `final_assessment_questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_final_assessment_answers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_final_assessment_attempts`
--
ALTER TABLE `user_final_assessment_attempts`
  ADD CONSTRAINT `user_final_assessment_attempts_final_assessment_id_foreign` FOREIGN KEY (`final_assessment_id`) REFERENCES `final_assessments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_final_assessment_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_module_progress`
--
ALTER TABLE `user_module_progress`
  ADD CONSTRAINT `fk_user_module_progress_file` FOREIGN KEY (`training_file_id`) REFERENCES `training_files` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_module_progress_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_quiz_answers`
--
ALTER TABLE `user_quiz_answers`
  ADD CONSTRAINT `user_quiz_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_quiz_answers_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_quiz_answers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_quiz_attempts`
--
ALTER TABLE `user_quiz_attempts`
  ADD CONSTRAINT `user_quiz_attempts_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_quiz_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_training_progress`
--
ALTER TABLE `user_training_progress`
  ADD CONSTRAINT `fk_user_training_progress_training` FOREIGN KEY (`training_id`) REFERENCES `trainings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_training_progress_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
