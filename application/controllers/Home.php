<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$tables=$this->db->query("SHOW DATABASES")->result_array(); 
		foreach($tables as $key => $val) {
		  	echo '<a href="database/'.$tables[$key]['Database'].'">'.$tables[$key]['Database'].'</a>';
			echo "<br/>";
		}
	}
	public function dbconnect(){
		$this->load->view('dbconnect');
	}
	public function dbname(){

	}
	public function connect($slug=null){
		$userDb = array();
		$userDb['hostname'] = $this->db->hostname;
        $userDb['username'] = $this->db->username;
        $userDb['password'] = '';
        $userDb['database'] = $slug;
        $userDb['dbdriver'] = 'mysqli';
        $userDb['dbprefix'] = '';
        $userDb['pconnect'] = FALSE;
        $userDb['db_debug'] = TRUE;
        $userDb['cache_on'] = FALSE;
        $userDb['cachedir'] = '';
        $userDb['char_set'] = 'utf8';
        $userDb['dbcollat'] = 'utf8_general_ci';
        $userDb['swap_pre'] = '';
        $userDb['autoinit'] = TRUE;
        $userDb['stricton'] = FALSE;
        
        $this->load->database($userDb, TRUE); 
        $this->session->set_userdata("db_name",$slug);
        $db_name = $slug;
		$tables=$this->db->query("SHOW TABLES FROM `$db_name`")->result_array();    
		foreach($tables as $key => $val) {
		  echo '<a href="'.base_url().'table/'.$val['Tables_in_'.$db_name].'">'.$val['Tables_in_'.$db_name].'</a>';
		echo "<br/>";
		}
		echo '<a href="'.base_url().'add-table">Add New Table</a>';
	}
	public function table($slug=null){
		//$this->load->database($_SESSION['db_data']->CI_DB_mysqli_driver, TRUE); 

		$this->db->query('use '.$_SESSION['db_name']);
		$column = $this->db->list_fields($slug);
		//print_r($column);
	}
	public function add_table(){
		$this->db->query('use '.$_SESSION['db_name']);
		// define table fields
		$this->load->dbforge();
		$fields = array(
		  'memid' => array(
		    'type' => 'INT',
		    'constraint' => 9,
		    'unsigned' => TRUE,
		    'auto_increment' => TRUE
		  ),
		  'firstname' => array(
		    'type' => 'VARCHAR',
		    'constraint' => 30
		  ),
		  'lastname' => array(
		    'type' => 'VARCHAR',
		    'constraint' => 30
		  ),
		  'email' => array(
		    'type' => 'VARCHAR',
		    'constraint' => 60,
		    'unique' => TRUE
		  ),
		  'password' => array(
		    'type' => 'VARCHAR',
		    'constraint' => 40
		  )
		 );

		$this->dbforge->add_field($fields);

		// define primary key
		$this->dbforge->add_key('memid', TRUE);

		// create table
		$this->dbforge->create_table('Members');
		//
	}
	public function drop_table(){
		$this->db->query('use '.$_SESSION['db_name']);
		$this->load->dbforge();
		 $this->dbforge->drop_table('Members');
	}
}
