<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReviewCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Review::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Avis')
            ->setEntityLabelInPlural('Avis')
            ->setPageTitle('edit', 'Approuver ou non un avis')
            ->setHelp('edit', 'Vous pouvez supprimer les avis que vous ne souhaitez pas garder')
            ->setPageTitle('index', 'Gestion des avis')
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(20)
            ->setPaginatorRangeSize(4)
            // ->hideNullValues()
            // ...
        ;
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $fields = array(
            // IdField::new('id'),
            TextField::new('pseudo', 'Nom et Prénom'),
            ImageField::new('profil_picture', 'Profile')
                ->setBasePath('./assets/uploads/reviewers_pictures')
                ->setUploadDir('public/assets/uploads/reviewers_pictures')
                ->setSortable(false),
            TextareaField::new('message', 'Message')
                ->setSortable(false),
            DateTimeField::new('submition_date', 'Date de soumission'),
            BooleanField::new('isValid', 'Statut')
        );

        if($pageName == Crud::PAGE_EDIT)
        {
            $fields = [
                BooleanField::new('isValid', 'Statut'),
            ];
        }

        return $fields;
    }
    
}
