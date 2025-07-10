import React from 'react';
import Header from '../components/Header'; // Gunakan Header yang sama
import Footer from '../components/Footer'; // Gunakan Footer yang sama

function AboutPage() {
  const kantorDinasImg = 'assets/img/visi_misi.png'; // Path dari folder public

  return (
    <>
      <Header />
      <main>
        {/* Judul Halaman */}
        <section className="page-title-section">
          <div className="container">
            <h1>Tentang Kami</h1>
            <div className="intro-card">
              <p>Dinas Komunikasi, Informatika dan Statistik Pemerintah Provinsi Lampung merupakan penyelenggara urusan pemerintahan dan mempunyai tugas di bidang komunikasi dan informatika, statistik dan persandian...</p>
            </div>
          </div>
        </section>

        {/* Visi & Misi */}
        <section className="container">
          <div className="card card-visi-misi">
            <div className="visi-misi-img">
              <img src={kantorDinasImg} alt="Kantor Dinas Kominfotik" />
            </div>
            <div className="visi-misi-text">
              <h2>Visi</h2>
              <p>Terwujudnya Pelayanan Informasi dan Komunikasi untuk Mendorong Pembangunan Daerah Menuju Lampung Maju dan Sejahtera.</p>
              <h2 className="mt-4">Misi</h2>
              <ol>
                <li>Meningkatkan daya saing dan jangkauan Teknologi Informasi dan Komunikasi...</li>
                <li>Meningkatkan Kompetensi Sumber Daya Manusia aparatur dan masyarakat...</li>
                <li>Meningkatkan Kualitas Layanan Komunikasi dan Informatika...</li>
              </ol>
            </div>
          </div>
        </section>

        {/* Tugas & Fungsi */}
        <section className="container tugas-fungsi-grid">
          <div className="card">
            <h2>Tugas</h2>
            <p>Berdasarkan Peraturan Gubernur Nomor 59 Tahun 2021... mempunyai tugas membantu Gubernur melaksanakan urusan pemerintahan...</p>
          </div>
          <div className="card">
            <h2>Fungsi</h2>
            <p>Berdasarkan Peraturan Gubernur Nomor 59 Tahun 2021... menyelenggarakan fungsi: a. perumusan kebijakan...</p>
          </div>
        </section>

        {/* Bidang */}
        <section className="bidang-section">
          <div className="container">
            <h2>BIDANG</h2>
            <div className="bidang-grid">
              <div className="bidang-item">
                <i className="fas fa-bullhorn"></i>
                <p>Pengelolaan dan Layanan Informasi Publik</p>
              </div>
              <div className="bidang-item">
                <i className="fas fa-shield-halved"></i>
                <p>Persandian dan Keamanan Informasi</p>
              </div>
              <div className="bidang-item">
                <i className="fas fa-globe"></i>
                <p>Pengelolaan Komunikasi Publik</p>
              </div>
              <div className="bidang-item">
                <i className="fas fa-cloud"></i>
                <p>Tata Kelola Pemerintahan Elektronik</p>
              </div>
              <div className="bidang-item">
                <i className="fas fa-laptop-code"></i>
                <p>Teknologi Informasi dan Komunikasi</p>
              </div>
            </div>
          </div>
        </section>
        
        {/* Struktur Organisasi */}
        <section className="container">
          <div className="card">
            <h2>Struktur Organisasi</h2>
            <ol>
              <li>Peraturan Gubernur No 63 Tahun 2021...</li>
              <li>Sekretariat;</li>
              <li>Bidang Pengelolaan dan Layanan Informasi Publik;</li>
            </ol>
            <a href="#" className="btn-primary">Lihat</a>
          </div>
        </section>
      </main>
      <Footer />
    </>
  );
}

export default AboutPage;