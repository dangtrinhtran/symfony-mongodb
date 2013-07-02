<?php
// src/Likipe/BackendBundle/Form/CommentType.php

namespace Likipe\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		
		$builder->add('name', 'text', array(
			'label' => 'Name: '
		));
		
		$builder->add('email', 'email', array(
			'label' => 'Email: '
		));

		$builder->add('content', 'textarea', array(
			'label' => 'Message: ',
			'attr'	=> array('class'	=> 'message-comment span6')
		));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'Likipe\BackendBundle\Document\Comment'
		));
	}

	public function getName() {
		return 'comment';
	}
}