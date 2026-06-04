<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MEGALOC</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="auth-wrapper">
        <!-- Image Side -->
        <div class="auth-image">
            <div class="image-overlay">
                <h1>MEGALOC</h1>
                <p>Louez votre équipement de construction en quelques clics.</p>
            </div>
        </div>

        <!-- Form Side -->
        <div class="auth-form-container">
            <div class="form-box">
                <div class="form-header">
                    <div class="logo">
                        <span class="mega">MEGA</span><span class="loc">LOC</span>
                    </div>
                    <h2>Connexion</h2>
                    <p>Heureux de vous revoir !</p>
                </div>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?url=login" method="POST" id="loginForm">
                    <div class="input-group">
                        <label>Email</label>
                        <div class="field">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" id="email" placeholder="votre@email.com" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Mot de passe</label>
                        <div class="field">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" id="password" placeholder="••••••••" required>
                            <i class="fas fa-eye toggle-password"></i>
                        </div>
                    </div>

                    <div class="form-options">
                        <label><input type="checkbox"> Se souvenir de moi</label>
                        <a href="#">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="btn-login">Se connecter</button>
                </form>

                <div class="form-footer">
                    <p>Vous n'avez pas de compte ? <a href="index.php?url=register">S'inscrire</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="js/login.js"></script>
</body>
</html>