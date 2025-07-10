import React from 'react';

function MainContent() {
  const pengetahuan1 = 'src/assets/img/pengetahuan_1.png';
  const pengetahuan2 = 'src/assets/img/pengetahuan_2.png';
  const pengetahuan3 = 'src/assets/img/pengetahuan_3.png';
  const pengetahuan4 = 'src/assets/img/pengetahuan_4.png';

  return (
    <main className="main-container">
      <div className="main-layout">
        <aside className="sidebar-card">
          <h2>Bidang</h2>
          <ul className="bidang-list">
            <li><a href="#"><i className="fas fa-building-user"></i> Sekretariat</a></li>
            <li><a href="#"><i className="fas fa-network-wired"></i> PLIP</a></li>
            <li><a href="#"><i className="fas fa-bullhorn"></i> PKP</a></li>
            <li><a href="#"><i className="fas fa-laptop-code"></i> TIK</a></li>
            <li><a href="#"><i className="fas fa-chart-simple"></i> SanStik</a></li>
            <li><a href="#"><i className="fas fa-sitemap"></i> UPTD</a></li>
          </ul>
        </aside>
        <section className="content-card">
          <h2>Pengetahuan</h2>
          <div className="pengetahuan-grid">
            <div className="knowledge-item">
              <img src={pengetahuan1} alt="Gambar Matriks Renstra" />
              <h3>Matriks Renstra Dinas Kominfotik Provinsi Lampung Tahun 2015-2019</h3>
              <a href="#" className="download-link">Download</a>
            </div>
            <div className="knowledge-item">
              <img src={pengetahuan2} alt="Gambar PK Dinas" />
              <h3>PK Dinas Kominfotik Provinsi Lampung Tahun 2017 Download</h3>
              <a href="#" className="download-link">Download</a>
            </div>
            <div className="knowledge-item">
              <img src={pengetahuan3} alt="Gambar Matriks Renstra" />
              <h3>Matriks Renstra Dinas Kominfotik Provinsi Lampung Tahun 2015-2019</h3>
              <a href="#" className="download-link">Download</a>
            </div>
            <div className="knowledge-item">
              <img src={pengetahuan4} alt="Gambar PK Dinas" />
              <h3>PK Dinas Kominfotik Provinsi Lampung Tahun 2017 Download</h3>
              <a href="#" className="download-link">Download</a>
            </div>
          </div>
        </section>
      </div>
    </main>
  );
}

export default MainContent;