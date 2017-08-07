<?php

class MysqlSession{
	
	private $HOST;
	private $USER;
	private $PASSWORD;
	private $DATABASE;
	
	public function setTestSession(){
		$this->HOST="localhost";
		$this->USER="toweb";
		$this->PASSWORD="password";
		$this->DATABASE = "database";
	}
	public function getTestSession(){
		$array = array($this->HOST,$this->USER,$this->PASSWORD,$this->DATABASE);
		return $array;
	}
	
	public function setProductiveSession(){
		$this->HOST="localhost";
		$this->USER="grupolu1_luxury";
		$this->PASSWORD="password";
		$this->DATABASE = "database";
	}
	public function getProductiveSession(){
		$array = array($this->HOST,$this->USER,$this->PASSWORD,$this->DATABASE);
		return $array;
	}
	public function getSession(){
		/**********************************/
		
		$this->setTestSession();
		$session = $this->getTestSession();
		/*********************************/
		/*
		$this->setProductiveSession();
		$session = $this->getProductiveSession();
		/*********************************/
		return $session;
	}
	public function getHost(){
		return $this->HOST;
	}
	public function getDataBase(){
		return $this->DATABASE;
	}
	public function getUser(){
		return $this->USER;
	}
	public function getPassword(){
		return $this->PASSWORD;
	}
}
?>
