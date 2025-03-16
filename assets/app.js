import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

// Code principal de ton application ici
console.log("App.js chargé");

/* console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉'); */


// Cette fonction charge les informations de l'utilisateur via l'API

/* console.log("app.js chargé avec succès !");

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
} */

// Attacher l'événement au bouton après le chargement du DOM
/* document.addEventListener('DOMContentLoaded', () => {
    console.log("DOM chargé, script en cours d'exécution...");

    const toggleButtons = document.querySelectorAll('.password-icon');

    if (toggleButtons.length === 0) {
        console.warn("⚠️ Aucun bouton de visibilité du mot de passe trouvé !");
    }

    toggleButtons.forEach(button => {
        button.addEventListener('click', togglePasswordVisibility);
    });
}); */

document.addEventListener("DOMContentLoaded", function () {
    function changeImage(event, src) {
        const mainImage = document.getElementById('mainImage');
        const thumbnails = document.querySelectorAll('.thumbnail');

        if (mainImage) {
            mainImage.src = src;
            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            event.target.classList.add('active');
        }
    }

    // Rendre la fonction accessible globalement
    window.changeImage = changeImage;
});













