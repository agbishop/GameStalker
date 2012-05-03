<?php

class forum_model extends model {
	
	public function __construct() {
		parent::__construct();
	}
	
	// Gets the game list from user to see if forum needs to be updated
	public function pullXboxGamesList(){
		Session::init();
		$id = Session::get('id');
		$gameList = $this->db->prepare("SELECT xboxGames FROM user WHERE UserId=:id");
		$gameList->execute(array(
			'id' => $id
		));
		$gL = $gameList->fetch();
		//$gL = json_decode($gL['xboxGames']);
		//print_r($gL);
		//$data = array('xboxGames'=>$gL);
		//echo json_encode($data);
		//print_r($data);				
	}
	
	public function queryGame($gameID){
		// Find game if in table
		$exists = $this->db->prepare("SELECT * FROM xbox_forums WHERE Game_ID=:Game_ID");
		$exists->execute(array(
			':Game_ID' => $gameID
		));
		
		// Count the number of entries in the table
		$num = $exists->rowCount();
	}
			
	// Load forum (Load Central Hub, load threads, load posts)
	public function loadDB_Hub($gameName, $gameID){
				
		// Find game if in table
		$exists = $this->db->prepare("SELECT * FROM xbox_forums WHERE Game_ID=:Game_ID");
		$exists->execute(array(
			':Game_ID' => $gameID
		));
		
		// Count the number of entries in the table
		$num = $exists->rowCount();
		
		if($num == 0){ // Insert into table and create others accordingly
			
			// Insert into first table
			$arr = array('GameName' => $gameName, 'Game_ID' => $gameID);
			$this->db->insert('xbox_forums', $arr);
			
			// Create thread table
			$this->createThreadTable($gameID);
			
			/*
			// Add thread
			$this->addThread($gameID, "Awesome", 3);
			
			// Add post
			$this->addPost($gameID, 1, "This is a test", 1);
			$this->addPost($gameID, 1, "Test 2", 1);
			$this->addPost($gameID, 1, "Snatch back", 2);
			
			// Delete post
			$this->deletePost($gameID, 1, 1);
			
			// Delete thread
			$this->deleteThread($gameID, 1);
			*/
		}
				
		// Exists - move to next phase
//////////////////////////////////////////////////		
		
		sleep(1);
		return true;			
	}
	
	// Create the game table if the table doesn't exist else return
	public function createThreadTable($gameID){
		// Concatenate string
		$gID = $gameID . "_threads";
		
		$threadTable = $this->db->prepare("CREATE TABLE IF NOT EXISTS $gID (threadName VARCHAR(180) NOT NULL, tid INT(11) NOT NULL AUTO_INCREMENT, sub_ID INT(11) NOT NULL, PRIMARY KEY (tid))");
		$threadTable->execute();
	}

	// Add thread
	public function addThread($gameID, $threadName, $category){
		$gID = $gameID . "_threads";
		$arr2 = array('threadName' => $threadName, 'sub_ID' => $category);
		$this->db->insert($gID, $arr2);
	}	
	
	// Delete thread
	public function deleteThread($gameID, $tid){		
		$gID = $gameID . "_threads";
		
		$del = $this->db->prepare("DELETE FROM $gID WHERE tid =:tid");
		$del->execute(array(
			':tid' => $tid
		));
	}
	
	// Post to thread
	public function addPost($gameID, $tid, $content, $author){
		// Set up thread post table name
		$threadPost = $gameID . "_" . $tid . "_" . "Post";
		
		// Create table if it doesn't exist
		$threadPostTable = $this->db->prepare("CREATE TABLE IF NOT EXISTS $threadPost (post_ID INT(11) NOT NULL AUTO_INCREMENT, content TEXT NOT NULL, author INT(11), PRIMARY KEY (post_ID))");
		$threadPostTable->execute();
		
		// Insert post to table			
		$arr = array('content' => $content, 'author' => $author);
		$this->db->insert($threadPost, $arr);
	}
	
	// Delete thread post
	public function deletePost($gameID, $tid, $tpid){
		// Set up thread post table name to delete from
		$threadPost = $gameID . "_" . $tid . "_" . "Post";
		
		// Prepare statement to delete from table
		$delP = $this->db->prepare("DELETE FROM $threadPost WHERE post_ID =:tpid");
		$delP->execute(array( 
			':tpid' => $tpid
		));
	}
	
	// View game categories
	
	// View game category threads
	
	// View game category thread posts
	
	// View game category thread reply

}
