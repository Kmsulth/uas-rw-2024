<?php
// URL API
$url = 'https://lailyn.github.io/uas-rw-2024/youtube_videos_20.json';

// Mengambil data dari API
$json = file_get_contents($url);
$data = json_decode($json, true);

// Memeriksa apakah data berhasil diambil
if ($data === null) {
	die('Error: Eror.');
}

// Fungsi untuk memformat angka
function formatNumber($number) {
    if ($number >= 1000000) {
        return round($number / 1000000, 1) . 'jt'; // juta
    } elseif ($number >= 1000) {
        return round($number / 1000, 1) . 'rb'; // ribu
    } else {
        return $number;
    }
}

// Fungsi untuk menghitung tanggal relatif
function timeAgo($date) {
    $now = new DateTime();
    $uploadDate = new DateTime($date);
    $diff = $now->diff($uploadDate);
    
    if ($diff->y > 0) {
        return $diff->y . ' tahun lalu';
    } elseif ($diff->m > 0) {
        return $diff->m . ' bulan lalu';
    } elseif ($diff->d > 0) {
        return $diff->d . ' hari lalu';
    } elseif ($diff->h > 0) {
        return $diff->h . ' jam lalu';
    } elseif ($diff->i > 0) {
        return $diff->i . ' menit lalu';
    } else {
        return 'baru saja';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>UAS Rekayasa Web</title>
	<!-- This file has been cloned from https://github.com/lailyn/uas-rw-2024 -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
		integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
		crossorigin="anonymous"></script>
	<style>
		.modal-body p {
			margin: 0;
		}

		.comment-user {
			font-weight: bold;
		}

		.comment-date {
			font-size: .875rem;
			color: #6c757d;
		}

		.pointer-text {
			cursor: pointer;
			color: #007bff;
			text-decoration: underline;
		}

		.pointer-text:hover {
			color: #0056b3;
		}
	</style>
</head>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
	<div class="container-fluid">
		<a class="navbar-brand ms-5" href="#">
			<img src="https://logodownload.org/wp-content/uploads/2014/10/youtube-logo-9.png" alt="Bootstrap"
				width="100" height="24">
		</a>
		<form class="d-flex align-items-center justify-content-center" style="width: 700px;" role="search">
			<div class="input-group">
				<input type="search" class="form-control rounded-pill" placeholder="Telusuri" aria-label="Search"
					aria-describedby="search-addon">
				<span class="input-group-text border-0" id="search-addon">
					<i class="fas fa-search"></i>
				</span>
			</div>
		</form>

		<div class="d-flex align-items-center">

		</div>
	</div>
</nav>
<div class="container d-flex align-items-center justify-content-center full-height">
	<div class="d-grid gap-2 d-md-block">
		<button class="btn btn-secondary" type="button" id="filter-tutorial">Tutorial Coding</button>
		<button class="btn btn-secondary" type="button" id="filter-english">Pelajaran Bahasa Inggris</button>
		<button class="btn btn-secondary" type="button" id="filter-all">Tampilkan Semua</button>
	</div>
</div>
<div class="container mt-5">
	<div class="row">
		<?php foreach ($data as $index => $item) : ?>
		<div class="col-md-3 mb-4 card-item" data-category="<?php echo htmlspecialchars($item['category']); ?>">
			<div style="border: none; box-shadow: none;" class="card">
				<img src="<?php echo htmlspecialchars($item['thumbnail']); ?>" class="card-img-top" alt="...">
				<div class="card-body">
					<p style="display: none; " class="card-text">
						<?php echo htmlspecialchars($item['category']); ?>
					</p>
					<p style="font-weight: bold;" class="card-text">
						<?php echo htmlspecialchars($item['title']); ?>
					</p>
					<p style="margin-top: -15px;" class="card-text md-5">
						<?php echo htmlspecialchars($item['channel_name']); ?>
					</p>


					<div style="margin-top: -15px;" class="d-flex">
						<p class="card-text">
							<?php echo htmlspecialchars(formatNumber($item['views']) ); ?> Ditonton
						</p>
						<p style="margin-left:10px;" class="card-text md-5">
						 •  <?php echo htmlspecialchars(timeAgo($item['upload_date'])); ?>
						</p>
					</div>

					<p style="margin-top: -15px;" class="pointer-text" data-bs-toggle="modal" data-bs-target="#commentModal-<?php echo $index; ?>">
						Lihat Komentar
					</p>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>

<?php foreach ($data as $index => $item) : ?>
<!-- koding modal -->
<div class="modal fade" id="commentModal-<?php echo $index; ?>" tabindex="-1"
	aria-labelledby="commentModalLabel-<?php echo $index; ?>" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="commentModalLabel-<?php echo $index; ?>">Komentar untuk
					<?php echo htmlspecialchars($item['title']); ?>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<?php if (isset($item['comments']) && is_array($item['comments'])) : ?>
				<?php foreach ($item['comments'] as $comment) : ?>
				<div class="mb-3">
					<p class="comment-user">
						<?php echo htmlspecialchars($comment['user']); ?>
					</p>
					<p>
						<?php echo htmlspecialchars($comment['comment']); ?>
					</p>
					<p class="text-muted comment-date">
						<?php echo htmlspecialchars($comment['comment_date']); ?> | <span class="badge bg-primary">
							<?php echo htmlspecialchars($comment['likes']); ?> Likes
						</span>
					</p>
				</div>
				<?php endforeach; ?>
				<?php else : ?>
				<p>Tidak ada komentar.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php endforeach; ?>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		// Fungsi untuk menyaring card berdasarkan kategori
		function filterCards(category) {
			document.querySelectorAll('.card-item').forEach(function (card) {
				if (category === 'all' || card.getAttribute('data-category') === category) {
					card.style.display = 'block';
				} else {
					card.style.display = 'none';
				}
			});
		}

		// Event listener untuk tombol filter
		document.getElementById('filter-tutorial').addEventListener('click', function () {
			filterCards('Tutorial Coding');
		});

		document.getElementById('filter-english').addEventListener('click', function () {
			filterCards('Pelajaran Bahasa Inggris');
		});

		document.getElementById('filter-all').addEventListener('click', function () {
			filterCards('all');
		});

		// Menampilkan semua card saat halaman dimuat
		filterCards('all');
	});
</script>

<body>

</body>

</html>