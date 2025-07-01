<?php
session_start();

// Brute force protection
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt'] = time();
}

if ($_SESSION['login_attempts'] > 3 && (time() - $_SESSION['last_attempt']) < 300) {
    $error = 'Terlalu banyak percobaan. Silakan coba lagi setelah 5 menit';
    // Skip proses login
} else if (isset($_SESSION['login'])) {
    header('Location: admin.php');
    exit;
}

include 'koneksi.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $query = "SELECT id, username, password FROM admin WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            // Reset login attempts on success
            $_SESSION['login_attempts'] = 0;
            
            // Regenerate session ID
            session_regenerate_id(true);
            
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            
            header('Location: admin.php');
            exit;
        } else {
            $error = 'Username atau password salah';
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt'] = time();
        }
    } else {
        $error = 'Username atau password salah';
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt'] = time();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - Sistem Infaq Digital</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #1a3c40, #2a6b5b);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .login-container {
      width: 100%;
      max-width: 400px;
      padding: 0 20px;
    }
    
    .login-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 16px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
      overflow: hidden;
      transform: translateY(0);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .login-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }
    
    .logo-container {
      background: linear-gradient(135deg, #1a3c40, #2a6b5b);
      padding: 30px 0;
      text-align: center;
    }
    
    .logo {
      width: 80px;
      height: 80px;
      background: white;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .logo i {
      color: #2a6b5b;
      font-size: 40px;
    }
    
    .form-group {
      margin-bottom: 20px;
      position: relative;
    }
    
    .form-group i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #2a6b5b;
    }
    
    .form-control {
      width: 100%;
      padding: 12px 20px 12px 45px;
      border: 2px solid #e2e8f0;
      border-radius: 8px;
      font-size: 16px;
      transition: all 0.3s ease;
    }
    
    .form-control:focus {
      border-color: #2a6b5b;
      box-shadow: 0 0 0 3px rgba(42, 107, 91, 0.2);
      outline: none;
    }
    
    .btn-login {
      background: linear-gradient(135deg, #1a3c40, #2a6b5b);
      color: white;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      width: 100%;
    }
    
    .btn-login:hover {
      background: linear-gradient(135deg, #2a6b5b, #1a3c40);
      transform: translateY(-2px);
    }
    
    .error-message {
      background: #ffebee;
      color: #c62828;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
    }
    
    .error-message i {
      margin-right: 10px;
    }
    
    .copyright {
      text-align: center;
      color: rgba(255, 255, 255, 0.7);
      margin-top: 20px;
      font-size: 14px;
    }
    
    .eye-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #2a6b5b;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="logo-container">
        <div class="logo">
          <i class="fas fa-lock"></i>
        </div>
        <h1 class="text-white text-2xl font-bold mt-4">Admin Dashboard</h1>
        <p class="text-white opacity-80">Sistem Pencatatan Infaq Digital</p>
      </div>
      
      <div class="p-8">
        <?php if (!empty($error)): ?>
          <div class="error-message">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= $error ?></span>
          </div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
          <div class="form-group">
            <i class="fas fa-user"></i>
            <input 
              type="text" 
              name="username" 
              class="form-control" 
              placeholder="Username" 
              required
            >
          </div>
          
          <div class="form-group">
            <i class="fas fa-key"></i>
            <input 
              type="password" 
              name="password" 
              id="password" 
              class="form-control" 
              placeholder="Password" 
              required
            >
            <span class="eye-toggle" id="togglePassword">
              <i class="fas fa-eye"></i>
            </span>
          </div>
          
          <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt mr-2"></i> Masuk
          </button>
        </form>
      </div>
    </div>
    
    <div class="copyright">
      <p>&copy; <?= date('Y') ?> D3 Teknik Informatika 24</p>
      <p class="mt-1">ULBI</p>
    </div>
  </div>

  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    
    togglePassword.addEventListener('click', function() {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      
      this.querySelector('i').classList.toggle('fa-eye');
      this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelector('.login-card').style.opacity = '0';
      document.querySelector('.login-card').style.transform = 'translateY(20px)';
      
      setTimeout(() => {
        document.querySelector('.login-card').style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        document.querySelector('.login-card').style.opacity = '1';
        document.querySelector('.login-card').style.transform = 'translateY(0)';
      }, 100);
    });
  </script>
</body>
</html>