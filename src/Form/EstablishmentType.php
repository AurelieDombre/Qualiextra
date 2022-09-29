<?php

namespace App\Form;

use App\Entity\Establishment;
use App\Entity\Style;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstablishmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,  [
                'label' => 'Nom de l\'établissement *'
            ])
            ->add('address', TextType::class,  [
                'label' => 'Adresse *'
            ])
            ->add('phone', TextType::class,  [
                'label' => 'Téléphone'
            ])
            ->add('email', TextType::class)
            ->add('website', TextType::class,  [
                'label' => 'Site web'
            ])
            ->add('openingHour', TextType::class,  [
                'label' => 'Horaires d\'ouverture'
            ])
            ->add('openingDay', TextType::class,  [
                'label' => 'Jours d\'ouverture'
            ])
            ->add('description', TextareaType::class,  [
                'label' => 'Description *'
            ])
            ->add('picture', UrlType::class, [
                'help' => 'Url de l\'image',
                'label' => 'Photo de l\'établissement'
            ])
            ->add('price',  NumberType::class,  [
                'label' => 'Prix moyen proposé'
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Equipement(s)',
                'choice_label' => 'name',
                'class' => Tag::class,
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ])
            ->add('user', EntityType::class, [
                'label' => 'Nom du gérant *',
                'choice_label' => 'lastname',
                'class' => User::class,
                'multiple' => false,
                'expanded' => false,
                'required' => true,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('u')
                    ->andWhere('u.roles LIKE :roles')
                    ->setParameter('roles', '%PRO%')
                    ->orderBy('u.lastname', 'ASC');
                }
            ])
            ->add('style', EntityType::class, [
                'label' => 'Style *',
                'choice_label' => 'name',
                'class' => Style::class,
                'multiple' => false,
                'expanded' => true,
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Establishment::class,
        ]);
    }
}
