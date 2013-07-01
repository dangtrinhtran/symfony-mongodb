<?php

namespace Likipe\BackendBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * BlogRepository
 *
 * Likipe\BackendBundle\Repository\BlogRepository;
 */
class BlogRepository extends DocumentRepository {
	
	public function getActiveBlogs($iLimit = null, $iOffset = null) {

		$oSql = $this->createQueryBuilder('Blog')
				->field('delete')->equals(false)
				->sort('created', 'DESC')
				->limit($iLimit)
				->skip($iOffset)
				->getQuery()
				->execute();
		
		return $oSql;
	}
}