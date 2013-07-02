<?php

namespace Likipe\BackendBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Likipe\BackendBundle\Document\Comment;

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
	
	public function getActiveComments($iPostId) {
		
		$oPost = $this->find($iPostId);
		
		$aComments = $oPost->getComments();
		
		foreach ($aComments as $oComment) {
			if (FALSE === $oComment->getIsActive()) {
				$aCommentsActive[$oComment->getId()] = $oComment;
			}
		}
		return $aCommentsActive;
	}
	
	public function getCommentById($iPostId, $iCommentId) {
		
		$oPost = $this->find($iPostId);
		
		$aComments = $oPost->getComments();
		
		foreach ($aComments as $oComment) {
			if ($iCommentId == $oComment->getId()) {
				return $oComment;
			}
		}
	}
}