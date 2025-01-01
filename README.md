# vetEnfant-CDA
  Project Symfony 
  CLI
  
  - git clone lien du repo
  - npm icomposer install
  - npm run build
  - symfony serve -d

  <!-- Création de BDD, entité, controller et migration vers BDD-->
  - symfony console doctrine:database:create
  - symfony console make:entity
  - symfony console make:migration
  - symfony console doctrine:migrations:migrate
  - symfony console make:controller

 <!-- Création de fixtures -->
  - composer require --dev orm-fixtures
  - symfony console make:fixtures
  - symfony console doctrine:fixtures:load --append    

<!-- Création d'un -->
  - symfony console make:registration-form
  - no-reply@vetenfant.com
  - VetEnfant Mail Bot
  - composer require symfonycasts/verify-email-bundle
  - symfony console make:auth
  - 
  

 