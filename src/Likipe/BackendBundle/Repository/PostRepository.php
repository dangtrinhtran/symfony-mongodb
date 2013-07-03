<?php

namespace Likipe\BackendBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * PostRepository
 *
 * Likipe\BackendBundle\Repository\PostRepository;
 */
class PostRepository extends DocumentRepository {
	
	/**
	 * getActivePosts
	 * @author Rony <rony@likipe.se>
	 * @param type $iLimit
	 * @param type $iOffset
	 * @return Object
	 */
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
	
	/**
	 * getAllComments
	 * @author Rony <rony@likipe.se>
	 * @param type $iPostId
	 * @return array
	 */
	public function getAllComments($iPostId) {
		
		$oPost = $this->find($iPostId);
		if (empty($oPost))
			return;
		$aComments = $oPost->getComments();
		
		if (!empty($aComments)) {
			$aCommentsActive = array();
			foreach ($aComments as $oComment) {
				if (!empty($oComment)) {
					$aCommentsActive[$oComment->getId()] = $oComment;
				}
			}
			if (!empty($aCommentsActive))
				arsort($aCommentsActive);
			return $aCommentsActive;
		} else 
			return;
	}
	
	/**
	 * getCommentById
	 * @author Rony <rony@likipe.se>
	 * @param type $iPostId, $iCommentId
	 * @return Object
	 */
	public function getCommentById($iPostId, $iCommentId) {

		$oPost = $this->find($iPostId);
		if (empty($oPost))
			return;
		$aComments = $oPost->getComments();
		if (!empty($aComments)) {
			foreach ($aComments as $oComment) {
				if ($iCommentId == $oComment->getId()) {
					return $oComment;
				}
			}
		} else 
			return;
	}
}