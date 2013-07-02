<?php

namespace Likipe\BackendBundle\Listeners;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Likipe\BackendBundle\Document\Post;

class LikipeBackendListener {

	public function prePersist(LifecycleEventArgs $eventArgs) {
		$document = $eventArgs->getDocument();
		
		if ($document instanceof Post) {
			
			$sSlug = $document->getSlug();
			$sTitle = $document->getTitle();
			if (empty($sSlug)) :
				$sToSlug = $sTitle . ' ' . time('now');
			else :
				$sToSlug = $sSlug . ' ' . time('now');
			endif;
			$slug = $this->slugify($sToSlug);
			$document->setSlug($slug);
			
			$document->setCreated(new \DateTime('now'));
			$document->setUpdated(new \DateTime('now'));
			$document->setDelete(FALSE);
		}
	}
	
	public function slugify($string) {
		return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
	}

}