<?php
 
namespace Cklst\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;
 
class TodoType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array('constraints' => new Constraints\NotBlank(array('message' => 'Mandatory Field'))))
				->add('list_id', 'hidden')
				->add('save', 'submit')
       ;
    }
 
    public function getName()
    {
        return 'todo';
    }
}