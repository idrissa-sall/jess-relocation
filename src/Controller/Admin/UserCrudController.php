<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if($entityInstance instanceof User)
        {
            $entityInstance->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

            $entityInstance->setPassword(
                $this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword())
            );
        }

        parent::persistEntity($entityManager, $entityInstance);
    }


    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if($entityInstance instanceof User)
        {
            $entityInstance->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

            if(method_exists($entityInstance, 'setPassword'))
            {
                $newPassword = trim($entityInstance->getPassword());
            
                if(!empty($newPassword))
                {
                    $entityInstance->setPassword(
                        $this->passwordHasher->hashPassword($entityInstance, $newPassword)
                    );
                }
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Administrateur')
            ->setEntityLabelInPlural('Administrateurs')
            ->setPageTitle('index', 'Gestion des administrateurs')
            ->setDefaultSort(['id' => 'ASC'])
            ->setPaginatorPageSize(20)
            ->setPaginatorRangeSize(4)
            // ->hideNullValues()
            // ...
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('name', 'pseudo'),
            EmailField::new('email', 'Email')
                ->setSortable(false),
            TextField::new('password', 'Mot de passe')
                ->setFormType(PasswordType::class)
        ];

        if($pageName == Crud::PAGE_EDIT)
        {
            $fields = [
                IdField::new('id')
                    ->hideOnForm(),
                TextField::new('name', 'pseudo'),
                EmailField::new('email', 'Email')
                    ->setSortable(false),
                TextField::new('password', 'Nouveau mot de passe')
                    ->setHelp("Si vous souhaitez conserver l'ancien mot de passe, merci de le saisir, sinon saisir le nouveau mot de passe")
                    ->setFormType(PasswordType::class)
            ];
        }

        return $fields;
    }
    
}
