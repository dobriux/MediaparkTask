<?php

namespace App\Form;

use App\Entity\HolidaySearch;
use App\Service\FetchHolidaysData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HolidaySearchType extends AbstractType
{
    private $holidaysData;

    public function __construct(FetchHolidaysData $fetchHolidaysData){

        $this->holidaysData = $fetchHolidaysData;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('country', ChoiceType::class, [
            'choices' =>
                $this->holidaysData->getSupportedCountriesArray()
            ,
        ])
            ->add('year', IntegerType::class, [
            'label' => 'Year',
            'attr' => [
                'placeholder' => 'Year',
            ]
        ])
            ->add('search', SubmitType::class, [
                'label' => 'Search',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HolidaySearch::class,
        ]);
    }
}
