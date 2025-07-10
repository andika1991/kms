import React from 'react';

function Footer() {
  const footerLogo = 'src/assets/img/logo_diskominfotik_lampung.png';

  return (
    <footer className="footer-container">
      <div className="footer-content">
        <div className="footer-info">
          <img src={footerLogo} alt="Logo Diskominfo Lampung Footer" className="footer-logo" />
          <p>
            <strong>Dinas Komunikasi, Informatika dan Statistik Provinsi Lampung</strong><br />
            Alamat : Jl. WR Monginsidi No.69 Bandar Lampung<br />
            Telepon : (0721) 481107<br />
            Facebook : www.facebook.com/diskominfo.lpg<br />
            Instagram : www.instagram.com/diskominfotiklampung
          </p>
        </div>
        <div className="footer-links">
          <h4>Navigasi</h4>
          <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/about">Tentang Kami</a></li>
            <li><a href="/kegiatan">Kegiatan</a></li>
            <li><a href="/dokumen">Dokumen</a></li>
            <li><a href="/kontak">Kontak</a></li>
          </ul>
        </div>
        <div className="footer-social">
          <h4>Ikuti Kami</h4>
          <a href="#"><i className="fab fa-facebook-f"></i></a>
          <a href="#"><i className="fab fa-instagram"></i></a>
          <a href="#"><i className="fab fa-youtube"></i></a>
        </div>
      </div>
    </footer>
  );
}

export default Footer;