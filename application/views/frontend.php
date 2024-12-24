<section id="hero" class="hero section dark-background" style="background-image: url('<?= base_url('assets/') ?>/img/hero.jpeg'); background-size: cover; background-position: center;">

    <div id="hero-carousel" data-bs-interval="5000" class="container carousel carousel-fade" data-bs-ride="carousel">

        <!-- Slide 1 -->
        <div class="carousel-item active">
            <div class="carousel-container">
                <h2 class="animate__animated animate__fadeInDown">Welcome to <span>SUGIN</span></h2>
                <p class="animate__animated animate__fadeInUp">Selamat Datang Di Pusat Permintaan Barang ATK & Consumable <br> <strong>PT.SUGIURA INDONESIA</strong></p>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
            <div class="carousel-container">
                <h2 class="animate__animated animate__fadeInDown">Tentang PT. SUGIURA INDONESIA</h2>
                <p class="animate__animated animate__fadeInUp">Perusahaan kami mulai beroperasi pada tahun 2013 sebagai anak perusahaan dari Sugiura Seisakusho Corporation dan merupakan perusahaan yang memproduksi dan menjual produk fasterner otomotif (Nut & Bolt)</p>
            </div>
        </div>

        <!-- Navigation -->
        <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
        </a>
        <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
        </a>

    </div>

    <!-- Wave Effect -->
    <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28 " preserveAspectRatio="none">
        <defs>
            <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
        </defs>
        <g class="wave1">
            <use xlink:href="#wave-path" x="50" y="3"></use>
        </g>
        <g class="wave2">
            <use xlink:href="#wave-path" x="50" y="0"></use>
        </g>
        <g class="wave3">
            <use xlink:href="#wave-path" x="50" y="9"></use>
        </g>
    </svg>
</section>
<script>
    $(document).ready(function () {
        // Ketika carousel berganti slide
        $('#hero-carousel').on('slid.bs.carousel', function (e) {
            // Ambil index slide yang aktif
            var activeIndex = $(e.relatedTarget).index();
            console.log("Slide aktif sekarang adalah: " + activeIndex);

            // Jalankan aksi tertentu saat slide berubah
            // Contoh: ubah teks di luar carousel
            $('#current-slide-info').text("Slide aktif: " + (activeIndex + 1));
        });
    });
</script>