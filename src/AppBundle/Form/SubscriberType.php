<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class SubscriberType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
            ->add('firstname', TextType::class, array(
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'First Name',
                    'class' => 'form-control'
                )))
            ->add('lastname', TextType::class, array(
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Last Name',
                    'class' => 'form-control'
                )))
            ->add('emailaddress', EmailType::class, array(
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Email Address',
                    'pattern'     => '.{2,}',//minlength
                    'class' => 'form-control'
                )))  
            ->add('phone', TextType::class, array(
                'label' => false,
                'required' => true,
                'error_bubbling' => true,
                'attr' => array(
                    'placeholder' => 'Mobile Phone',
                    'pattern'     => '.{2,}', //minlength
                    'class' => 'form-control'
                )))
            ->add('education_level_id', ChoiceType::class, array(
                'choices' => array(
                    'High School or equivalent' => 1, 
                    'Certification' => 2, 
                    'Vocational' => 3,
                    'Associate Degree' => 4,
                    'Bachelors Degree' => 5,
                    'Masters Degree' => 6,
                    'Doctorate' => 7,
                    'Professional' => 8,
                    'Some College Coursework Completed' => 9,
                    'Vocational - High School' => 10,
                    'Vocational - Degree' => 11,
                    'Some High School Coursework' => 12,
                    ),
                'label' => false,
                'required' => true,
                'error_bubbling' => true,
                'placeholder' => 'Current Education Level',
                'attr' => array(
                    'class' => 'form-control'
                )
                ))
            ->add('agreeterms', CheckboxType::class, array(
                'label' => '',
                'required' => true))
            ->add('agreeemails', CheckboxType::class, array(
                'label' => '',
                'required' => true))
            ->add('agreepartners', CheckboxType::class, array(
                'label' => '',
                'required' => true))
            ->add('submit', SubmitType::class, array(
                'label' => 'Sign Up'
                ))
             ;
    }
    
    /**
    * @param OptionsResolverInterface $resolver
    */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Subscriber'
        ));
    }
    /**
     * @return string
     */
    public function getName() {
        return 'subscriber';
    }
}
