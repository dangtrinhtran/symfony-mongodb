<?php

namespace Likipe\BackendBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * PostRepository
 *
 * Likipe\BackendBundle\Repository\PostRepository;
 */
class PostRepository extends DocumentRepository {
	
	public function getActivePosts($iLimit = null, $iOffset = null) {

		$oSql = $this->createQueryBuilder('Post')
				->field('delete')->equals(false)
				->sort('created', 'DESC')
				->limit($iLimit)
				->skip($iOffset)
				->getQuery()
				->execute();
		
		return $oSql;
	}
}