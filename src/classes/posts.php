<?php

namespace app\classes;

class Posts
{
    protected $db;

    public function __construct(\PDO $db){
        $this->db=$db;
    }
    // Create a Journal Entry
    public function addEntry($title, $date, $body) {
        
        $sql = "INSERT INTO posts (title, date, body) VALUES(:title, :date, :body)";

        $results = $this->db->prepare($sql); 
        $results->bindParam(':title', $title, PDO::PARAM_STR);
        $results->bindParam(':date', $date, PDO::PARAM_STR);
        $results->bindParam(':body', $body, PDO::PARAM_STR);
        $results->execute();
		return true;
	}
	// Remove a Journal Entry
	public function deleteEntry($id) {
		
		$results = $this->db->prepare("DELETE FROM posts WHERE id = :id"); 
		$results->bindParam(':id', $id, PDO::PARAM_INT);
		$results->execute();
		return true;
	}
	// Edit a Journal Entry
	public function editEntry($id, $title, $date, $body) {
        
        $sql = "UPDATE posts SET title = :title, date = :date, body = :body WHERE id = :id";

		$results = $this->db->prepare($sql); 
		$results->bindParam(':id', $id, PDO::PARAM_INT);
		$results->bindParam(':title', $title, PDO::PARAM_STR);
		$results->bindParam(':date', $date, PDO::PARAM_STR);
		$results->bindParam(':body', $body, PDO::PARAM_STR);
		$results->execute();
		return true;
	}
	// Collection of all Journal Entries
	public function getAllEntries() {
		
		$results = $this->db->prepare("SELECT * FROM posts ORDER BY date DESC"); 
		$results->execute();
		return $results->fetchAll(PDO::FETCH_ASSOC);
	}
	// Review a single Journal Entry
	public function getEntry($id) {
        
        $sql = "SELECT posts.id, posts.title, posts.date, posts.body FROM posts WHERE id = :id";

		$results = $this->db->prepare($sql);
		$results->bindParam(':id', $id, PDO::PARAM_INT);
		$results->execute();
		return $results->fetch(PDO::FETCH_ASSOC);
	}
}