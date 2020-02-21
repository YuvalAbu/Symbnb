<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType {

     /**
     * Permet d'avoir la conf de base d'un champ !
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    protected function getConfiguration($label, $placeholder, $options = []){
        // le récursive permet de ne pas ecrasé une valeur si elle est déja utilisé (par exemple dans le tableau option avoir un autre attr)
        return array_merge_recursive([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }

}