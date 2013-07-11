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
						'placeholder' => 'Firstname',
						'class' => 'pull-left',
					),
				))
				->add('lastname', 'text', array(
					'label' => 'Lastname',
					'required' => true,
					'translation_domain' => 'FOSUserBundle',
					'attr' => array(
						'placeholder' => 'Lastname',
						'class' => 'pull-left',
					),
				))
				->add('email', 'repeated', array(
					'type' => 'email',
					'options' => array('translation_domain' => 'FOSUserBundle'),
					'first_options' => array('label' => 'Email',
						'attr' => array(
							'placeholder' => 'Email',
						)),
					'second_options' => array('label' => 'Email confirm',
						'attr' => array(
							'placeholder' => 'Email confirm',
						))
				))
				->add('plainPassword', 'repeated', array(
					'type' => 'password',
					'options' => array('translation_domain' => 'FOSUserBundle'),
					'first_options' => array('label' => 'Password',
						'attr' => array(
							'placeholder' => 'Password',
						)),
					'second_options' => array('label' => 'Password confirm',
						'attr' => array(
							'placeholder' => 'Password confirm',
						))
		));
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