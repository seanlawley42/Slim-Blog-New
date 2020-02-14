<?php

namespace app\classes;
use \PDO;

class Comments{

    protected $db;

    public function __construct(\PDO $db){
        $this->db=$db;
    }
    // Create a Comment
	public function addComment($name, $comment, $postId) {
        
        $sql = "INSERT INTO comments (name, body, postId) VALUES(:name, :body, :postId)"; 
				
		$results = $this->db->prepare($sql);
		$results->bindParam(':name', $name, PDO::PARAM_STR);
		$results->bindParam(':body', $comment, PDO::PARAM_STR);
		$results->bindParam(':postId', $postId, PDO::PARAM_INT);
		$results->execute();
		return true;
    }
    
    // Collection of all Comments
	public function getAllComments($postId) {
		$sql = "SELECT * FROM comments WHERE postId = :postId";
 
			$results = $this->db->prepare($sql); 
			$results->bindParam(':postId', $postId, PDO::PARAM_INT);
			$results->execute();
			return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
}