<?php
require_once('../config.php');
Class Users extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function save_users(){
		$id = isset($_POST['id']) && is_numeric($_POST['id']) ? (int)$_POST['id'] : 0;
		$username = trim($_POST['username'] ?? '');
		if($username === ''){
			return json_encode(['status' => 'failed', 'msg' => 'Username is required.']);
		}
		if($id === 0 && empty($_POST['password'])){
			return json_encode(['status' => 'failed', 'msg' => 'Password is required for new users.']);
		}
		$stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
		$stmt->bind_param('si', $username, $id);
		$stmt->execute();
		$check = $stmt->get_result();
		if($check && $check->num_rows > 0){
			return json_encode(['status' => 'failed', 'msg' => 'Username already exists.']);
		}

		$fields = ['firstname', 'middlename', 'lastname', 'username'];
		$data = [];
		foreach($fields as $field){
			$value = $_POST[$field] ?? '';
			$value = $this->conn->real_escape_string($value);
			$data[] = "`{$field}` = '{$value}'";
		}
		if(isset($_POST['type'])){
			$type = (int)$_POST['type'];
			$data[] = "`type` = '{$type}'";
		}
		if(!empty($_POST['password'])){
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$data[] = "`password` = '{$password}'";
		}
		$data = implode(' , ', $data);

		if(empty($id)){
			$qry = $this->conn->query("INSERT INTO users set {$data}");
			if($qry){
				$id=$this->conn->insert_id;
				$this->settings->set_flashdata('success','User Details successfully saved.');
				if(!empty($_FILES['img']['tmp_name'])){
					if(!is_dir(base_app."uploads/avatars"))
						mkdir(base_app."uploads/avatars");
					$ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
					$fname = "uploads/avatars/$id.png";
					$accept = array('image/jpeg','image/png');
					if(!in_array($_FILES['img']['type'],$accept)){
						$err = "Image file type is invalid";
					}
					if($_FILES['img']['type'] == 'image/jpeg')
						$uploadfile = imagecreatefromjpeg($_FILES['img']['tmp_name']);
					elseif($_FILES['img']['type'] == 'image/png')
						$uploadfile = imagecreatefrompng($_FILES['img']['tmp_name']);
					if(!$uploadfile){
						$err = "Image is invalid";
					}
					$temp = imagescale($uploadfile,200,200);
					if(is_file(base_app.$fname))
					unlink(base_app.$fname);
					$upload =imagepng($temp,base_app.$fname);
					if($upload){
						$this->conn->query("UPDATE `users` set `avatar` = CONCAT('{$fname}', '?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}'");
						if($this->settings->userdata('id') == $id)
						$this->settings->set_userdata('avatar',$fname."?v=".time());
					}

					imagedestroy($temp);
				}
				return json_encode(['status' => 'success']);
			}else{
				return json_encode(['status' => 'failed', 'msg' => 'Failed to save user: '.$this->conn->error]);
			}

		}else{
			$qry = $this->conn->query("UPDATE users set $data where id = {$id}");
			if($qry){
				$this->settings->set_flashdata('success','User Details successfully updated.');
				foreach(['firstname','middlename','lastname','username','type'] as $k){
					if((int)$this->settings->userdata('id') === $id)
						$this->settings->set_userdata($k, $_POST[$k] ?? '');
				}
				if(!empty($_FILES['img']['tmp_name'])){
					if(!is_dir(base_app."uploads/avatars"))
						mkdir(base_app."uploads/avatars");
					$ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
					$fname = "uploads/avatars/$id.png";
					$accept = array('image/jpeg','image/png');
					if(!in_array($_FILES['img']['type'],$accept)){
						$err = "Image file type is invalid";
					}
					if($_FILES['img']['type'] == 'image/jpeg')
						$uploadfile = imagecreatefromjpeg($_FILES['img']['tmp_name']);
					elseif($_FILES['img']['type'] == 'image/png')
						$uploadfile = imagecreatefrompng($_FILES['img']['tmp_name']);
					if(!$uploadfile){
						$err = "Image is invalid";
					}
					$temp = imagescale($uploadfile,200,200);
					if(is_file(base_app.$fname))
					unlink(base_app.$fname);
					$upload =imagepng($temp,base_app.$fname);
					if($upload){
						$this->conn->query("UPDATE `users` set `avatar` = CONCAT('{$fname}', '?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}'");
						if($this->settings->userdata('id') == $id)
						$this->settings->set_userdata('avatar',$fname."?v=".time());
					}

				imagedestroy($temp);
				}

				return json_encode(['status' => 'success']);
			}else{
				return json_encode(['status' => 'failed', 'msg' => 'Failed to update user: '.$this->conn->error]);
			}
			
		}
	}
	public function delete_users(){
		extract($_POST);
		if(empty($id) || !is_numeric($id)){
			return 0;
		}
		if((int)$this->settings->userdata('id') === (int)$id){
			return 0;
		}
		$id = (int)$id;
		$qry = $this->conn->query("DELETE FROM users where id = {$id}");
		if($qry){
			$this->settings->set_flashdata('success','User Details successfully deleted.');
			if(is_file(base_app."uploads/avatars/$id.png"))
				unlink(base_app."uploads/avatars/$id.png");
			return 1;
		}else{
			return false;
		}
	}
	
}

$users = new users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'save':
		echo $users->save_users();
	break;
	case 'delete':
		echo $users->delete_users();
	break;
	default:
		// echo $sysset->index();
		break;
}
