<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - MEGALOC</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="auth-wrapper">
        <!-- Image Side -->
        <div class="auth-image">
            <div class="image-overlay">
                <h1>MEGALOC</h1>
                <p>Rejoignez-nous et commencez à louer votre matériel dès aujourd'hui.</p>
            </div>
        </div>

        <!-- Form Side -->
        <div class="auth-form-container">
            <div class="form-box">
                <div class="form-header">
                    <div class="logo">
                        <span class="mega">MEGA</span><span class="loc">LOC</span>
                    </div>
                    <h2>Inscription</h2>
                    <p>Créez votre compte gratuitement</p>
                </div>

                <form action="index.php?url=register" method="POST" id="registerForm">
                    <div class="input-group">
                        <label>Nom complet</label>
                        <div class="field">
                            <i class="fas fa-user"></i>
                            <input type="text" name="name" placeholder="Votre nom complet" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Email</label>
                        <div class="field">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" placeholder="votre@email.com" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Téléphone</label>
                        <div class="field">
                            <i class="fas fa-phone"></i>
                            <input type="text" name="phone" placeholder="06 00 00 00 00" required>
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

                    <button type="submit" class="btn-login">S'inscrire</button>
                </form>

                <div class="form-footer">
                    <p>Déjà un compte ? <a href="index.php?url=login">Se connecter</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="js/login.js"></script>
</body>
</html>