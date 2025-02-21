import '../bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

/* document.addEventListener('DOMContentLoaded', function () {
    // Ajouter un écouteur d'événements pour le formulaire de déconnexion
    const logoutForm = document.querySelector('form[action="{{ path('app_logout') }}"]');
    if (logoutForm) {
        logoutForm.addEventListener('submit', function (event) {
            // Empêcher la soumission par défaut du formulaire
            event.preventDefault();

            // Trouver le bouton dropdown et fermer le menu
            const dropdownButton = document.getElementById('customDropdown');
            if (dropdownButton) {
                // Réinitialiser l'attribut aria-expanded pour fermer le menu
                dropdownButton.setAttribute('aria-expanded', 'false');
            }

            // Soumettre manuellement le formulaire de déconnexion après avoir manipulé le dropdown
            logoutForm.submit();
        });
    }

    // Vérifier si l'utilisateur est connecté ou non
    const isUserLoggedIn = {{ app.user ? 'true' : 'false' }};
    
    if (!isUserLoggedIn) {
        // L'utilisateur est déconnecté, fermer le dropdown
        const dropdownButton = document.getElementById('customDropdown');
        if (dropdownButton) {
            dropdownButton.setAttribute('aria-expanded', 'false');
        }
    }
}); */

console.log("app.js chargé avec succès !");

// Fonction pour afficher/masquer le mot de passe
function togglePasswordVisibility(event) {
    console.log("Icône cliquée !"); // Pour vérifier que l'événement est bien déclenché

    const passwordField = event.target.closest('.mb-4').querySelector('.password-field');
    const icon = event.target;

    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

// Attacher l'événement au bouton après le chargement du DOM
document.addEventListener('DOMContentLoaded', () => {
    console.log("DOM chargé, script en cours d'exécution...");

    const toggleButtons = document.querySelectorAll('.password-icon');

    if (toggleButtons.length === 0) {
        console.warn("⚠️ Aucun bouton de visibilité du mot de passe trouvé !");
    }

    toggleButtons.forEach(button => {
        button.addEventListener('click', togglePasswordVisibility);
    });
});





