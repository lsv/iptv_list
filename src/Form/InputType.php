<?php

namespace App\Form;

use App\FormModel\InputForm;
use App\Parser\Parsers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InputType extends AbstractType
{

    /**
     * @var Parsers
     */
    private $parsers;

    public function __construct(Parsers $parsers)
    {
        $this->parsers = $parsers;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('parser', ChoiceType::class, [
                'label' => 'Choose parser',
                'required' => true,
                'choices' => $this->parsers->getChoices(),
            ])
            ->add('inputData', TextareaType::class, [
                'label' => 'Data',
                'required' => false,
            ])
            ->add('uploadedFile', FileType::class, [
                'label' => 'Upload file',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InputForm::class,
        ]);
    }
}
