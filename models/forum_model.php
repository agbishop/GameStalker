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
			':id' => $id
		));
		$gL = $gameList->fetch();
		//$gL = json_decode($gL['xboxGames']);
		//print_r($gL);
		//$data = array('xboxGames'=>$gL);
		//echo json_encode($data);
		//print_r($data);				
	}
	
	public function queryGame(){
		// Grab information	
		$gameID = $_POST['gameID'];
		
		// Unset $_POST
		unset($_POST['gameID']);
		
		// Find game if in table
		$exists = $this->db->prepare("SELECT * FROM xbox_forums WHERE Game_ID=:Game_ID");
		$exists->execute(array(
			':Game_ID' => $gameID
		));
		
		// Count the number of entries in the table
		$num = $exists->rowCount();
	}
			
	// Load forum (Load Central Hub, load threads, load posts)
	public function loadDB_Hub(){
		// Grab information
		$gameName = $_POST['gameName'];
		$gameID = $_POST['gameID'];
		
		// unset $_POST
		unset($_POST['gameName']);
		unset($_POST['gameID']);
				
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
			
		}
				
		// Exists - move to next phase
		$categories = "<div class=\"contentBox item forum\"><button id=\"weapons\">Weapons</button><button id=\"walkthrough\">Walkthrough</button><button id=\"maps\">Maps</button><button id=\"spoilers\">Spoilers</button><button id=\"misc\">Misc</button></div>";		
		echo $categories;
		
		sleep(1);
		return true;			
	}
	
	// Create the game table if the table doesn't exist else return
	public function createThreadTable($gameID){
		// Concatenate string
		$gID = $gameID . "_threads";
		
		$threadTable = $this->db->prepare("CREATE TABLE IF NOT EXISTS :gID (threadName VARCHAR(180) NOT NULL, tid INT(11) NOT NULL AUTO_INCREMENT, sub_ID INT(11) NOT NULL, PRIMARY KEY (tid))");
		$threadTable->execute(array(
			':gID' => $gID
		));
	}

	// Add thread
	public function addThread(){
		$gameID = $_POST['gameID'];
		$threadName = $_POST['threadName'];
		$category = $_POST['category'];
		
		unset($_POST['gameID']);
		unset($_POST['threadName']);
		unset($_POST['category']);
		
		$gID = $gameID . "_threads";
		$arr2 = array('threadName' => $threadName, 'sub_ID' => $category);
		$this->db->insert($gID, $arr2);
	}	
	
	// Delete thread
	public function deleteThread(){
		// Grab information	
		$gameID = $_POST['gameID'];
		$tid = $_POST['tid'];
		
		// unset post
		unset($_POST['gameID']);
		unset($_POST['tid']);
		
		$gID = $gameID . "_threads";
		
		$del = $this->db->prepare("DELETE FROM :gID WHERE tid =:tid");
		$del->execute(array(
			':gID' => $gID,
			':tid' => $tid
		));
	}
	
	// Post to thread
	public function addPost(){
		// Grab information	
		$gameID = $_POST['gameID'];
		$tid = $_POST['tid'];
		$content = $_POST['content']; 
		
		// unset $_POST
		unset($_POST['gameID']);
		unset($_POST['tid']);
		unset($_POST['content']);
		
		Session::init();
		$author = Session::get('id');	
		
		// Set up thread post table name
		$threadPost = $gameID . "_" . $tid . "_" . "Post";
		
		// Create table if it doesn't exist
		$threadPostTable = $this->db->prepare("CREATE TABLE IF NOT EXISTS :threadPost (post_ID INT(11) NOT NULL AUTO_INCREMENT, content TEXT NOT NULL, author INT(11), PRIMARY KEY (post_ID))");
		$threadPostTable->execute(array(
			':threadPost' => $threadPost
		));
		
		// Insert post to table			
		$arr = array('content' => $content, 'author' => $author);
		$this->db->insert($threadPost, $arr);
	}
	
	// Delete thread post
	public function deletePost(){
		// Grab information	
		$gameID = $_POST['gameID'];
		$tid = $_POST['tid'];
		$tpid = $_POST['tpid'];
		
		// Unset $_POST
		unset($_POST['gameID']);
		unset($_POST['tid']);
		unset($_POST['tpid']);
				
		// Set up thread post table name to delete from
		$threadPost = $gameID . "_" . $tid . "_" . "Post";
		
		// Prepare statement to delete from table
		$delP = $this->db->prepare("DELETE FROM :threadPost WHERE post_ID =:tpid");
		$delP->execute(array( 
			':threadPost' => $threadPost,
			':tpid' => $tpid
		));
	}
	
	// View game threads
	public function viewGameThreads(){
		$gameID = $_POST['gameID'];
		
		// unset $_POST
		unset($_POST['gameID']);
		
		$gID = $gameID . "_threads";
		
		// Find threads	
		$exists = $this->db->prepare("SELECT * FROM :gID");
		$exists->execute(array(
			':gID' => $gID,
		));
		
		$row = $exists->rowCount();
		print_r($exists);
		
		$threads = "";
		
		for($i = 0; $i < $row; $i++){
			$threads .= "<div class=\"contentBox item forum " . $exists[i] . "</div>";
		}
		
	}
	
	// View game thread posts
	public function viewGameThreadPosts(){
		$gameID = $_POST['gameID'];
		$tid = $_POST['tid'];
		
		// unset $_POST
		unset($_POST['gameID']);
		unset($_POST['tid']);
		
		$threadPosts = $gameID . "_" . $tid ."_Post";
		
		$exists = $this->db->prepare("SELECT * FROM :threadPosts");
		$exists->execute(array(
			':threadPosts' => $threadPosts,
		));
		
		$row = $exists->rowCount();
		
		for($i = 0; $i < $row; $i++){
			
		}
	}
	
	public function viewGameThreadReply(){
		
	}
	
	// View game category 
	
	// View game category threads
	
	// View game category thread posts
	
	// View game category thread reply

}
