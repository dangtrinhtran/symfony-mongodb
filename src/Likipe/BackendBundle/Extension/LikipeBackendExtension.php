<?php

namespace Likipe\BackendBundle\Extension;

class LikipeBackendExtension extends \Twig_Extension {

	public function getFunctions() {
		return array(
			'formatDateTime'  => new \Twig_Function_Method($this, 'formatDateTime')
		);
	}

	public function formatDateTime($value) {
		if ($value === null) {
			return null;
		}
		
		if ($value instanceof \DateTime) {
			$date = $value->getTimestamp();
			$dateCurrent = $date + 7*3600;
			$sDate = gmdate("l, d F Y h:i:s A", $dateCurrent);
		}
		return $sDate;
	}

	public function getName() {
		return 'LikipeBackendBundle_TwigExtension';
	}

}