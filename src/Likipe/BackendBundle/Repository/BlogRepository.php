<?php

namespace Likipe\BackendBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * BlogRepository
 *
 * Likipe\BackendBundle\Repository\BlogRepository;
 */
class BlogRepository extends DocumentRepository {
	
	/**
	 * getActiveBlogs
	 * @author Rony <rony@likipe.se>
	 * @param type $iLimit
	 * @param type $iOffset
	 * @return Object
	 */
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