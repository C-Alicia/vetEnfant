<?php

namespace App\Controller\Admin;

use App\Entity\User;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use PhpParser\Node\Expr\Cast\Double;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setPageTitle(Crud::PAGE_INDEX, 'Liste des utilisateurs')
        ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un utilisateur')
        ->setPageTitle(Crud::PAGE_NEW, 'Créer un utilisateur')
        ->setPageTitle(Crud::PAGE_DETAIL, 'Consulter un utilisateur');
    }

    public function configureActions(Actions $actions): Actions{
        return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
            return $action->setIcon('fa-solid fa-plus pe-1')->setLabel('Ajouter utilisateur');
        })
        ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
            return $action->setIcon('fas fa-trash text-danger')->setLabel('Supprimer utilisateur');
        })
        ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
            return $action->setIcon('fas fa-edit text-warning')->setLabel('Editer utilisateur');
        })
        ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
            return $action->setIcon('fas fa-eye text-info')->setLabel('Consulter utilisateur');
        })
        ->update(Crud::PAGE_DETAIL, Action::DELETE, function (Action $action) {
            return $action->setLabel('Supprimer compétence');
        })
        ->update(Crud::PAGE_DETAIL, Action::EDIT, function (Action $action) {
            return $action->setIcon('fas fa-edit text-light')->setLabel('Editer utilisateur');
        })
        ->update(Crud::PAGE_DETAIL, Action::INDEX, function (Action $action) {
            return $action->setIcon('fa-solid fa-arrow-left text-light')->setLabel('Retour');
        })
        ->reorder(Crud::PAGE_DETAIL, [Action::INDEX, Action::EDIT, Action::DELETE]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->hideOnDetail()
                ->hideOnIndex(),           
            TextField::new('username'),         
            TextField::new('email'),
            TextField::new('lastName'),
            TextField::new('firstName'),
            TextField::new('phoneNumber'),           
            TextField::new('description'),
            TextField::new('profileImage'),
            ArrayField::new('roles'),         
            BooleanField::new('isActive'),
            BooleanField::new('isRole'),        
        ];
    }
}
