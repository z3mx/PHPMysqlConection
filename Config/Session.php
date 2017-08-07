<?php
session_start();

class SESION_PHP{
	
	private $active;
	private $username;
	private $password;
	
	public function setActive($active){
	$_SESSION["Active"] = $active;
	}
	public function getActive(){
	return $_SESSION["Active"];
	}
	public function setUsername($username){
	$_SESSION["Username"]= $username;
	}
	public function getUsername(){
	return $_SESSION["Username"];
	}
	public function setPassword($password){
	$_SESSION["Password"]= $password;
	}
	public function getPassword(){
	return $_SESSION["Password"];
	}
}
?>