<?php

namespace Likipe\FrontendBundle\Form;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MemberRegistrationType extends BaseType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('firstname', 'text', array(
					'label' => 'Firstname',
					'required' => true,
					'translation_domain' => 'FOSUserBundle',
					'attr' => array(
						'placeholder' => 'Firstname'
					),
				))
				->add('lastname', 'text', array(
					'label' => 'Lastname',
					'required' => true,
					'translation_domain' => 'FOSUserBundle',
					'attr' => array(
						'placeholder' => 'Lastname'
					),
				))
				->add('gender', 'choice', array(
					'choices' => array(
						'Male' => 'Male',
						'Female' => 'Female'
					),
					'empty_value' => false,
				));
		
		parent::buildForm($builder, $options);
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'Likipe\FrontendBundle\Document\Member'
		));
	}

	public function getName() {
		return 'likipe_member_registration';
	}

}