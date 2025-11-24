-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 24 Nov 2025 pada 14.37
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
-- Database: `artikel_projek`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `genre` enum('Sepak Bola','Basket','Bulu Tangkis','Tenis','Voli','Renang','Atletik','Tinju','MotoGP','Lainnya') NOT NULL DEFAULT 'Lainnya'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `articles`
--

INSERT INTO `articles` (`id`, `title`, `slug`, `content`, `image`, `author_id`, `created_at`, `updated_at`, `genre`) VALUES
(11, 'Sepak Bola: Olahraga dengan Penggemar Terbesar di Dunia', 'sepak-bola-olahraga-dengan-penggemar-terbesar-di-dunia', '<p><strong>Sepak bola</strong> menjadi olahraga paling populer karena aturan yang sederhana dan dapat dimainkan oleh siapa saja. Pertandingan 11 lawan 11 ini menekankan kerja sama tim, teknik umpan, dan strategi permainan. Klub-klub besar seperti Barcelona, Manchester United, dan Bayern Munich terus melahirkan pemain kelas dunia yang menginspirasi generasi muda. Tidak hanya menghibur, sepak bola juga menyatukan berbagai budaya lewat kompetisi internasional seperti Piala Dunia.</p>', 'img/img_692311de598d5.jpeg', 1, '2025-11-23 20:53:34', '2025-11-23 20:53:34', 'Sepak Bola'),
(12, 'Basket: Permainan Cepat yang Menuntut Kelincahan', 'basket-permainan-cepat-yang-menuntut-kelincahan', '<p>Basket dikenal sebagai olahraga dengan tempo cepat dan intensitas tinggi. Setiap pemain harus memiliki kemampuan dribel, passing, dan shooting yang baik. Liga-liga besar seperti NBA sangat berpengaruh dalam perkembangan basket global. Dengan permainan yang dinamis, basket tidak hanya meningkatkan kebugaran tubuh, tetapi juga kemampuan koordinasi dan kerja sama tim.</p>', 'img/img_692311fdbc649.jpg', 1, '2025-11-23 20:54:05', '2025-11-23 20:54:05', 'Basket'),
(13, 'Bulu Tangkis: Kecepatan dan Ketepatan dalam Satu Lapangan', 'bulu-tangkis-kecepatan-dan-ketepatan-dalam-satu-lapangan', '<p><span style=\"text-decoration: underline;\"><em><strong>Bulu tangkis </strong></em></span>merupakan olahraga yang membutuhkan refleks cepat, ketahanan fisik, dan strategi serangan. Indonesia dikenal sebagai salah satu negara terkuat dengan banyak atlet berprestasi dunia. Dalam permainan tunggal maupun ganda, pemain dituntut untuk mengontrol shuttlecock sambil menjaga ritme permainan. Latihan rutin dapat meningkatkan daya tahan jantung serta kelincahan tubuh.</p>', 'img/img_692312d5e39fe.jpeg', 1, '2025-11-23 20:57:41', '2025-11-23 20:57:41', 'Bulu Tangkis'),
(14, 'Bola Voli: Perpaduan Teknik, Kerja Tim, dan Konsistensi', 'bola-voli-perpaduan-teknik-kerja-tim-dan-konsistensi', '<p>Voli adalah olahraga beregu yang mengandalkan kekompakan serta komunikasi antar pemain. Setiap posisi memiliki tugas penting, mulai dari setter, libero, hingga spiker. Permainan yang terdiri dari rally panjang membuat voli sangat seru untuk ditonton. Selain meningkatkan kekuatan otot, voli juga melatih konsentrasi serta kemampuan membaca arah bola.</p>', 'img/img_6923132a30d63.jpg', 1, '2025-11-23 20:59:06', '2025-11-23 20:59:06', 'Voli'),
(15, 'Tinju: Olahraga Fisik dan Mental yang Paling Menantang', 'tinju-olahraga-fisik-dan-mental-yang-paling-menantang', '<p>Tinju bukan hanya tentang pukulan, tetapi juga strategi, ketahanan, dan kontrol emosi. Petinju harus memadukan kekuatan, kelincahan, serta teknik bertahan untuk meraih kemenangan. Latihan tinju efektif meningkatkan stamina dan kekuatan otot tubuh secara keseluruhan. Di balik pertandingan yang keras, tinju mengajarkan disiplin dan fokus diri.</p>', 'img/img_6923137045e9a.jpeg', 1, '2025-11-23 21:00:16', '2025-11-23 21:00:16', 'Tinju'),
(17, 'Peran Penting Kiper dalam Pertandingan Sepak Bola', 'peran-penting-kiper-dalam-pertandingan-sepak-bola', '<p>Kiper bukan hanya penjaga gawang, melainkan juga pengatur lini pertahanan. Respons cepat, refleks kuat, dan kemampuan membaca arah tembakan menjadi kunci sukses. Di sepak bola modern, kiper juga dituntut mahir mengoper bola untuk membangun serangan.</p>', 'img/img_692314f59ed7b.jpg', 1, '2025-11-23 21:06:45', '2025-11-23 21:06:45', 'Sepak Bola'),
(18, 'Perkembangan Tembakan 3 Poin dalam Basket Modern', 'perkembangan-tembakan-3-poin-dalam-basket-modern', '<p>Dalam beberapa tahun terakhir, tembakan 3 poin menjadi senjata utama tim basket dunia. Strategi ini membuka ruang serangan lebih lebar, memaksa pertahanan bekerja ekstra. Pemain yang mahir melepaskan tembakan jarak jauh menjadi aset berharga di setiap tim.</p>', 'img/img_6923153556bc5.jpg', 1, '2025-11-23 21:07:49', '2025-11-23 21:07:49', 'Basket'),
(19, 'Pentingnya Footwork dalam Basket', 'pentingnya-footwork-dalam-basket', '<p>Footwork yang baik membantu pemain melakukan drive, pivot, hingga mencari ruang tembakan. Latihan footwork meningkatkan keseimbangan, kecepatan, dan efektivitas serangan. Inilah dasar yang harus dikuasai oleh pemain pemula maupun profesional.</p>', 'img/img_6923154c8b344.jpeg', 1, '2025-11-23 21:08:12', '2025-11-23 21:08:12', 'Basket'),
(20, 'Teknik Smash dalam Bulu Tangkis', 'teknik-smash-dalam-bulu-tangkis', '<p>Smash adalah pukulan keras yang mengarah tajam ke bawah untuk mematikan permainan lawan. Teknik ini membutuhkan kekuatan lengan, timing tepat, dan posisi tubuh yang stabil. Smash yang efektif dapat mengubah jalannya pertandingan dalam sekejap.</p>', 'img/img_69231561ac68f.webp', 1, '2025-11-23 21:08:33', '2025-11-23 21:08:33', 'Bulu Tangkis'),
(21, 'Strategi Bertahan dalam Permainan Ganda Bulu Tangkis', 'strategi-bertahan-dalam-permainan-ganda-bulu-tangkis', '<p>Permainan ganda membutuhkan koordinasi tinggi. Formasi bertahan depan-belakang atau samping-samping harus dipilih sesuai situasi. Kunci utamanya adalah komunikasi yang baik serta kemampuan mengembalikan serangan cepat lawan.</p>', 'img/img_692315c50c1d3.jpeg', 1, '2025-11-23 21:10:13', '2025-11-23 21:10:13', 'Bulu Tangkis'),
(22, 'Latihan Dasar untuk Pemain Bola Voli Pemula', 'latihan-dasar-untuk-pemain-bola-voli-pemula', '<p>Pemain pemula perlu menguasai passing, servis, dan blocking. Latihan rutin seperti wall passing atau servis target dapat meningkatkan kontrol bola. Dengan dasar teknik yang baik, pemain dapat berkembang lebih cepat di lapangan.</p>', 'img/img_692315dbf2119.jpeg', 1, '2025-11-23 21:10:35', '2025-11-23 21:10:35', 'Voli'),
(23, 'Peran Setter dalam Tim Bola Voli', 'peran-setter-dalam-tim-bola-voli', '<p>Setter adalah &ldquo;otak&rdquo; permainan yang menentukan arah serangan. Mereka harus cepat mengambil keputusan dan menilai posisi rekan serta lawan. Setter yang handal mampu mengubah ritme pertandingan dan menciptakan peluang poin.</p>', 'img/img_692315f2035a8.jpeg', 1, '2025-11-23 21:10:58', '2025-11-23 21:10:58', 'Voli'),
(24, 'Teknik Dodge dan Footwork dalam Tinju', 'teknik-dodge-dan-footwork-dalam-tinju', '<p>Selain menyerang, petinju harus mahir menghindari pukulan. Teknik dodge dan footwork memungkinkan petinju bergerak lincah, menjaga jarak, dan mencari celah serangan. Ini adalah kombinasi yang membuat tinju menjadi seni bertarung yang elegan.</p>', 'img/img_6923162d12fcc.jpeg', 1, '2025-11-23 21:11:57', '2025-11-23 21:11:57', 'Tinju'),
(25, 'Renang: Olahraga Lengkap untuk Kesehatan Tubuh', 'renang-olahraga-lengkap-untuk-kesehatan-tubuh', '<p>Renang melatih hampir semua otot tubuh, meningkatkan kapasitas paru-paru, dan cocok untuk semua usia. Gaya renang seperti bebas, dada, punggung, dan kupu-kupu memiliki manfaat tersendiri dalam meningkatkan kekuatan dan teknik pernapasan.</p>', 'img/img_6923164d3306f.jpg', 1, '2025-11-23 21:12:29', '2025-11-23 21:12:29', 'Renang'),
(26, 'Peran Gelandang dalam Mengatur Tempo Permainan', 'peran-gelandang-dalam-mengatur-tempo-permainan', '<p><strong>Gelandang </strong><span style=\"text-decoration: underline;\"><em>adalah jantung permainan sepak bola. Mereka harus mampu mengatur ritme, mendistribusikan bola, dan mendukung pertahanan sekaligus serangan. Pemain dengan visi bermain tinggi dapat mengubah jalannya pertandingan hanya dengan satu umpan terobosan.</em></span></p>', 'img/img_69231671cbc2c.jpeg', 1, '2025-11-23 21:13:05', '2025-11-23 22:55:21', 'Sepak Bola'),
(27, 'Evolusi Formasi Sepak Bola dari Masa ke Masa', 'evolusi-formasi-sepak-bola-dari-masa-ke-masa', '<p data-start=\"879\" data-end=\"1134\">Dari formasi klasik 2-3-5 hingga sistem modern seperti 4-3-3 atau 3-4-2-1, strategi sepak bola terus berkembang. Pelatih kini lebih fleksibel, menyesuaikan formasi dengan karakter pemain. Evolusi ini membuat permainan semakin dinamis dan sulit diprediksi.</p>', 'img/img_6923168a7f53b.jpeg', 1, '2025-11-23 21:13:30', '2025-11-23 21:13:30', 'Sepak Bola'),
(28, 'Servis Kencang: Senjata Utama Pemain Tenis Modern', 'servis-kencang-senjata-utama-pemain-tenis-modern', '<p>Dalam tenis modern, servis menjadi senjata mematikan. Kecepatan bola bisa melampaui 200 km/jam, membuat lawan kesulitan melakukan return. Selain kekuatan, akurasi penempatan bola juga menjadi faktor penting dalam servis yang efektif.</p>', 'img/img_692317496b2f8.webp', 1, '2025-11-23 21:16:41', '2025-11-23 21:16:41', 'Tenis'),
(29, 'MotoGP: Adu Kecepatan dan Ketepatan di Sirkuit Dunia', 'motogp-adu-kecepatan-dan-ketepatan-di-sirkuit-dunia', '<p>MotoGP adalah ajang balap motor paling bergengsi dengan kecepatan yang dapat mencapai lebih dari 350 km/jam. Para pembalap harus menguasai teknik menikung ekstrem, manajemen ban, dan fokus tinggi selama puluhan lap. Setiap balapan menyajikan drama dan aksi saling salip yang menegangkan bagi para penonton.</p>', 'img/img_6923178798dfd.webp', 1, '2025-11-23 21:17:43', '2025-11-23 21:17:43', 'MotoGP'),
(30, 'Sprint 100 Meter: Pertarungan Kecepatan dalam Detik', 'sprint-100-meter-pertarungan-kecepatan-dalam-detik', '<p>Nomor lari 100 meter sering disebut sebagai pertandingan tercepat di dunia. Sprinter harus memiliki start yang eksplosif, stride efisien, serta fokus penuh selama beberapa detik. Pemenangnya sering dianggap sebagai manusia tercepat di planet ini.</p>', 'img/img_692318699a7be.webp', 1, '2025-11-23 21:21:29', '2025-11-23 21:21:29', 'Atletik'),
(31, 'Padel: Olahraga Raket yang Sedang Naik Daun', 'padel-olahraga-raket-yang-sedang-naik-daun', '<p data-start=\"166\" data-end=\"415\">Padel adalah olahraga raket yang menggabungkan unsur tenis dan squash. Lapangannya lebih kecil, dikelilingi dinding kaca yang bisa dimanfaatkan untuk memantulkan bola. Permainannya cepat, seru, dan mudah dipelajari sehingga populer di banyak negara.</p>', 'img/img_692318b864fbd.jpeg', 1, '2025-11-23 21:22:48', '2025-11-23 21:22:48', 'Lainnya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_approved` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `comments`
--

INSERT INTO `comments` (`id`, `article_id`, `user_id`, `content`, `created_at`, `is_approved`) VALUES
(6, 27, 1, 'STY', '2025-11-23 22:42:32', 1),
(9, 27, 4, 'i love you sty', '2025-11-23 23:42:40', 1),
(10, 21, 4, 'jagoanku itu', '2025-11-23 23:42:56', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `likes`
--

INSERT INTO `likes` (`id`, `article_id`, `user_id`, `created_at`) VALUES
(87, 27, 1, '2025-11-23 23:33:35'),
(88, 26, 1, '2025-11-23 23:33:36'),
(89, 11, 1, '2025-11-23 23:34:44'),
(90, 27, 4, '2025-11-23 23:42:29'),
(91, 21, 4, '2025-11-23 23:42:51'),
(92, 31, 1, '2025-11-24 16:20:13'),
(93, 30, 1, '2025-11-24 16:20:14'),
(94, 29, 1, '2025-11-24 16:20:15'),
(97, 28, 1, '2025-11-24 16:20:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'Mufid Dhamarjati Kusuma', 'starsaquarios@gmail.com', '$2y$10$OJkjj16h6.ogERswZcGbZOi9qGSo8h9YonjsS7MuUyyxh3J7.Yb6S', 'admin', '2025-11-20 23:21:31'),
(2, 'Damar', 'mfddmr@gmail.com', '$2y$10$nkLyKohTMFGv8/aQSSjszOxjRwYoo3kJAqsQFlnoKvN51CsSUq7PK', 'admin', '2025-11-22 22:04:09'),
(3, 'jati', 'as@gmail.com', '$2y$10$cPd37/Zvcj8OPg/DeLrfIuYuRrQoPVagSghuvWBjbA.d1WXJq9GB.', 'user', '2025-11-23 04:50:29'),
(4, 'Kusuma', 'h@gmail.com', '$2y$10$vtQk8cvtV5SV4VBzGSkJeuKyK28P3EQ.2BaFQa2AdKtGctrBenWrG', 'user', '2025-11-23 23:41:38');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `author_id` (`author_id`);

--
-- Indeks untuk tabel `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_article` (`article_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indeks untuk tabel `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_article_user` (`article_id`,`user_id`),
  ADD KEY `idx_article` (`article_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
