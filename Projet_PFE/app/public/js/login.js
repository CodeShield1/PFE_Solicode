// Validation simple du formulaire
document.getElementById('loginForm')?.addEventListener('submit', function(e) {
    const email = document.getElementById('email')?.value;
    const password = document.getElementById('password')?.value;

    if (!email || !password) {
        alert('Veuillez remplir tous les champs !');
        e.preventDefault();
    }
});

// Toggle Password Visibility (L'3in)
document.querySelectorAll('.toggle-password').forEach(icon => {
    icon.addEventListener('click', function() {
        const passwordInput = this.parentElement.querySelector('input');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        }
    });
});