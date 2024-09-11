<?php

namespace App\Controller\Admin;

use App\Entity\Appointment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AppointmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Appointment::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Rendez-vous')
            ->setEntityLabelInPlural('Rendez-vous')
            ->setPageTitle('edit', 'Déclarer le rendez-vous comme effectué')
            ->setHelp('edit', 'Si le rendez-vous à été annulé, vous pouvez simplement le supprimer')
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(20)
            ->setPaginatorRangeSize(4)
            ->hideNullValues()
            // ...
        ;
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id'),
            TextField::new('Name', 'Nom et Prénom'),
            DateField::new('date_apm', 'Jour du rendez-vous'),
            TextField::new('hour', 'Heure')
                ->formatValue(function($value, $entity){
                    return ($value . 'h');
                })
                ->setSortable(false),
            TextField::new('phone', 'Téléphone')
                ->setSortable(false),
            TextareaField::new('reason', 'Motif')
                ->setSortable(false),
            DateTimeField::new('submition_date', 'Date de demande')
                ->setSortable(false),
            BooleanField::new('is_done', 'Statut')
        ];

        // only is_done in edit form
        if($pageName == Crud::PAGE_EDIT)
        {
            $fields = [
                BooleanField::new('is_done', 'Statut'),
            ];
        }

        return $fields;
    }
    
}
