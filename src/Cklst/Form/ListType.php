<?php
 
namespace Cklst\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;
 
class ListType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array('constraints' => new Constraints\NotBlank(array('message' => 'Mandatory Field'))))
				->add('save', 'submit')
       ;
    }
 
    public function getName()
    {
        return 'list';
    }
}