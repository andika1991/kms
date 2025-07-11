import React from 'react';
import { Link } from 'react-router-dom';
import Header from '../components/Header';
import Footer from '../components/Footer'; 

function LoginPage() {
  // Path ke gambar
  const loginIllustration = 'assets/img/login_figure.png'; 

  return (
    <>
      <Header />
      <main className="login-page-main">
        <div className="login-container">
          {/* Kartu Login Utama */}
          <div className="login-card">
            <div className="login-illustration">
              <img src={loginIllustration} alt="Login Illustration" />
            </div>
            <div className="login-form">
              <h1>Selamat Datang</h1>
              <div className="input-group">
                <i className="fas fa-user"></i>
                <input type="email" placeholder="Email" />
              </div>
              <div className="input-group">
                <i className="fas fa-lock"></i>
                <input type="password" placeholder="Password" />
              </div>
              <button type="submit" className="btn-login">Masuk</button>
            </div>
          </div>

          {/* Link Daftar */}
          <div className="register-link">
            <p>Belum punya akun?</p>
            <a href="/register" className="btn-register">Daftar</a>
          </div>
        </div>
      </main>
      <Footer />
    </>
  );
}

export default LoginPage;