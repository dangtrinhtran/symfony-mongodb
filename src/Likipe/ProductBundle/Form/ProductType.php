<?php
// src/Likipe/ProductBundle/Form/ProductType.php

namespace Likipe\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		
		$builder->add('name', 'text', array(
			'label' => 'Name: ',
			'attr'	=> array('class'	=> 'form-control'),
		));
		
		$builder->add('price', 'money', array(
			'label' => 'Price: ',
			'currency' => 'USD',
			'attr'	=> array('class'	=> 'form-control'),
		));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'Likipe\ProductBundle\Document\Product'
		));
	}

	public function getName() {
		return 'product';
	}
}