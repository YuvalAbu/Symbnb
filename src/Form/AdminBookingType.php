<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;

class AdminBookingType extends ApplicationType
{

    public function __construct(FrenchToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', 
                TextType::class,
                $this->getConfiguration("Date d'arrivée", "La date à laquelle vous comptez arriver")   
            )
            ->add('endDate', 
                TextType::class,
                $this->getConfiguration("Date de départ", "La date à laquelle vous comptez quiitez les lieux")   
            )
            ->add('comment')
            ->add('booker', 
                EntityType::class, [
                    'class' => User::class,
                    'choice_label' => function($user) {
                        return $user->getFirstName() . " " . strtoupper($user->getLastName());
                    }
                ]
            )
            ->add('annonce', 
                EntityType::class, [
                    'class' => Annonce::class,
                    'choice_label' => "title"
                ]
            )
        ;

        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
