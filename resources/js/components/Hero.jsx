import React from 'react';

function Hero() {
  const heroLogoSrc = 'src/assets/img/logo_diskominfotik_lampung.png';

  return (
    <div className="hero-wrapper">
      <section className="hero-content-container">
        <div className="hero-content">
          <div className="hero-text">
            <h1>Knowledge Management System</h1>
            <p>Dinas Komunikasi Informatika dan Statistik Provinsi Lampung</p>
          </div>
          <div className="hero-logo">
            <img src={heroLogoSrc} alt="Logo Diskominfo Lampung" />
          </div>
        </div>
      </section>
      <div className="hero-line-pattern"></div>
    </div>
  );
}

export default Hero;