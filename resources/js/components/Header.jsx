import React from 'react';
// Import NavLink untuk navigasi
import { NavLink } from 'react-router-dom';

function Header() {
  const logoSrc = '/src/assets/img/KMS_Diskominfotik.png';

  return (
    <header className="header-container">
      <div className="header-content">
        <div className="header-logo-left">
          <img src={logoSrc} alt="Logo KMS Diskominfo" />
        </div>
        <nav className="main-nav">
          <ul>
            <li><NavLink to="/" className={({ isActive }) => (isActive ? 'active' : '')}>Beranda</NavLink></li>
            <li><NavLink to="/about" className={({ isActive }) => (isActive ? 'active' : '')}>Tentang Kami</NavLink></li>
            <li><NavLink to="/kegiatan" className={({ isActive }) => (isActive ? 'active' : '')}>Kegiatan</NavLink></li>
            <li><NavLink to="/dokumen" className={({ isActive }) => (isActive ? 'active' : '')}>Dokumen</NavLink></li>
          </ul>
        </nav>
        <NavLink to="/login" className="btn-masuk">Masuk</NavLink>
      </div>
    </header>
  );
}

export default Header;