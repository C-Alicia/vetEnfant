import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

/* function showAllProducts() {
    // Vous pouvez utiliser AJAX ici pour charger les produits dynamiquement.
    fetch('/path/to/api/products')  // Remplacez par une route qui renvoie tous les produits.
        .then(response => response.json())
        .then(data => {
            const allProductsDiv = document.getElementById('allProducts');
            let productList = '<ul>';
            data.forEach(product => {
                productList += `<li><strong>${product.name}</strong> - Créé le : ${new Date(product.createdAt).toLocaleDateString()}</li>`;
            });
            productList += '</ul>';
            allProductsDiv.innerHTML = productList;
            allProductsDiv.style.display = 'block';
            document.getElementById('showAllProducts').style.display = 'none';
        })
        .catch(error => console.error('Error fetching products:', error));
} */

document.addEventListener('DOMContentLoaded', function () {
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
});




