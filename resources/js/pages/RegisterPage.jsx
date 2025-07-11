import React from 'react';
import Header from '../components/Header';
import Footer from '../components/Footer';
import { Link } from 'react-router-dom'; // Import Link untuk navigasi

function RegisterPage() {
  const registerIllustration = 'assets/img/register_figure.png'; 

  return (
    <>
      <Header />
      {/* Menggunakan class yang sama dengan halaman login untuk latar belakang */}
      <main className="login-page-main">
        <div className="register-container">
          {/* Kartu Register Utama */}
          <div className="register-card">
            <div className="register-form">
              <h1>Daftar untuk menggunakan KMS</h1>
              
              <div className="input-group">
                <i className="fas fa-user"></i>
                <input type="text" placeholder="Nama Lengkap" />
              </div>

              <div className="input-group">
                <i className="fas fa-envelope"></i>
                <input type="email" placeholder="Email" />
              </div>

              <div className="input-group">
                <i className="fas fa-lock"></i>
                <input type="password" placeholder="Password" />
              </div>

              <div className="input-group">
                <i className="fas fa-lock"></i>
                <input type="password" placeholder="Konfirmasi Password" />
              </div>

              <button type="submit" className="btn-login">Daftar</button>
            </div>
            <div className="register-illustration">
              <img src={registerIllustration} alt="Register Illustration" />
            </div>
          </div>
           {/* Link kembali ke Login */}
           <div className="back-to-login-link">
            <p>Sudah punya akun?</p>
            <Link to="/login">Masuk di sini</Link>
          </div>
        </div>
      </main>
      <Footer />
    </>
  );
}

export default RegisterPage;