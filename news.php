<?PHP
class news
{
	private $connection;
	private $database;
	private $username;
	private $password;
	private $host;
	private $table;
	private $result;
	private $tableSchema;
	
	public function __construct() {
       	$this->username = "root";
	    $this->password = "ittunnan";
		$this->host = "localhost";
		$this->database = "AppCenter";
		$this->table = "news";
		$this->tableSchema="(description,link,image,priority,status)";
		#Example: 
		#$this->tableSchema="(description,link,image,priority,status)";
		$this->createConnection();
    }
	
	private function createConnection(){
		$this->connection = mysql_connect($this->host,$this->username,$this->password);
		if (!$this->connection)
        {
		   die('Error: '.mysql_error());
	    }
	}
	
	private function sqlExec($query)
	{
		$result = mysql_query($query,$this->connection);
		return $result;
	}
	
	public function getLast()
	{
		mysql_select_db($this->database,$this->connection) or die('Error: '.mysql_error());
		$query = "select * from ".$this->table." order by date desc limit 1";
		$this->result = $this->sqlExec($query);
		return mysql_fetch_assoc($this->result);
	}
	
	public function getId($id)
	{
		mysql_select_db($this->database,$this->connection) or die('Error: '.mysql_error());
		$query = "select * from ".$this->table." where id=".$id;
		$this->result = $this->sqlExec($query);
		return mysql_fetch_assoc($this->result);
	}
	
	public function getPage($pageNum,$rows)
	{
		mysql_select_db($this->database,$this->connection) or die('Error: '.mysql_error());
		$final = array();
		$offset = ($pageNum - 1) * $rows;
		$query = "select * from ".$this->table." LIMIT ".$offset.", ".$rows;
		$this->result = $this->sqlExec($query);
		while($row = mysql_fetch_assoc($this->result))
		{
			array_push($final,$row);
		}
		return $final;
	}
	
	public function getTotal()
	{
		mysql_select_db($this->database,$this->connection) or die('Error: '.mysql_error());
		$query = "select count(*) from ".$this->table;
		$this->result = $this->sqlExec($query);
		$row = mysql_fetch_assoc($this->result);
		return $row['count(*)'];
	}
	
	public function addNews($data)
	{
		mysql_select_db($this->database,$this->connection) or die('Error: '.mysql_error());
		$query  = "insert into ".$this->table.$this->tableSchema." values(".$data[0].",".$data[1].",".$data[2].",".$data[3].")";
		$this->result = $this->sqlExec($query);
		$query = "select * from ".$this->table." order by date desc limit 1";
		$this->result = $this->sqlExec($query);
		return mysql_fetch_assoc($this->result);
	}
	
	public function deleteNews($id)
	{
		mysql_select_db($this->database,$this->connection) or die('Error: '.mysql_error());
		$query = "delete from ".$this->table." id=".$id;
		$this->result = $this->sqlExec($query);
		$query = "select count(*) from ".$this->table." where id=".$id;
		$this->result = $this->sqlExec($query);
		return mysql_fetch_assoc($this->result);
	}
	
   function __destruct() {
       mysql_close($this->connection);
	   foreach ($this as $key => $value) {
            unset($this->$key);
       } 
   }
	
}
?>