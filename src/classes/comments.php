<?php

namespace app\classes;

class Comments{

    protected $db;

    public function __construct(\PDO $db){
        $this->db=$db;
    }
    // Create a Comment
	public function addComment($name, $comment, $postId, $date) {
        
        $sql = "INSERT INTO comments (name, body, post_id, date) VALUES(:name, :body, :postId, :date)"; 
				
		$results = $this->db->prepare($sql);
		$results->bindParam(':name', $name, PDO::PARAM_STR);
		$results->bindParam(':body', $comment, PDO::PARAM_STR);
		$results->bindParam(':postId', $postId, PDO::PARAM_INT);
		$results->bindParam(':date', $date, PDO::PARAM_STR);
		$results->execute();
		return true;
    }
    // Remove a Comment 
	public function deleteComment($postId) {
        
        $results = $this->db->prepare("DELETE FROM comments WHERE post_id = :postId"); 
		$results->bindParam(':postId', $postId, PDO::PARAM_INT);
		$results->execute();
		return true;
    }
    // Collection of all Comments
	public function getAllComments($postId) {
		$sql = "SELECT * FROM comments WHERE post_id = :postId";
 
			$results = $this->db->prepare($sql); 
			$results->bindParam(':postId', $postId, PDO::PARAM_INT);
			$results->execute();
			return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
}