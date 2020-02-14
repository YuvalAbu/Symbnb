<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnonceType extends AbstractType
{
    /**
     * Permet d'avoir la conf de base d'un champ !
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    private function getConfiguration($label, $placeholder, $options = []){
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',
                TextType::class,
                $this->getConfiguration('Titre', 'Titre de l\'annonce')
            )
            // ->add('slug',
            // TextType::class,
            // $this->getConfiguration('Slug', 'Votre titre sans espace', [ 'required' => false])
            // )
            ->add('intro',
                TextType::class,
                $this->getConfiguration('Introduction', 'Description global de l\'annonce')
            )
            ->add('content',
                TextareaType::class,
                $this->getConfiguration('Description', 'Description détaillé de l\'annonce')
            )
            ->add('coverImage',
                UrlType::class,
                $this->getConfiguration('Image de Couverture', 'Url de l\'image')
            )
            ->add('rooms',
                IntegerType::class,
                $this->getConfiguration('Chambres', 'Nombre de chambre(s)')
            )
            ->add('price',
                MoneyType::class,
                $this->getConfiguration('Prix', 'Prix par nuit')
            )
            ->add('images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
