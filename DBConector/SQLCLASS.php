<?php 
/****
Version: 0.1.0 
Date: 21_08_2015
Author: Lavsystems
note: fields in the database should be called user and password
note: log file now record time exeption or error.
last-modification:07-08-2015
*****/
class Sql{ 
var $SERVER; 
var $DATABASE; 
var $USER; 
var $PASSWORD;
var $EX;
var $AFFECTED_ROWS;
var $LOGFILE = '../log/sql_log.log';
var $DUMP_FILE ='../sql_respaldos/';

function _construct($server,$database,$user,$password){
$this->SERVER = $server;
$this->DATABASE = $database;
$this->USER= $user;
$this->PASSWORD = $password;
}

function Sql(array $array){
	  $this->SERVER = $array[0];
	  $this->DATABASE = $array[3];
	  $this->USER= $array[1];
	  $this->PASSWORD = $array[2];
	}
/////////////////////////////////////CONEXION/////////////////////////////////////////////////
/**Este metodo Te conecta a una base de datos Mysql
*Inicializando Primero correctamente tu Objecto Sql
*Con los parametros de DB or schema, Servidor(ruta),User & Password
**/
function Conectar(){
	//echo $this->SERVER.$this->USER.$this->PASSWORD;
 	  if (!($link=mysql_connect($this->SERVER, $this->USER, $this->PASSWORD))){ 
  			 echo "SQL EXCEPTION ERROR CONNECTING TO DATABASE."; 
  			 exit(); 
 		  } 
  	  if (!mysql_select_db($this->DATABASE,$link)){ 
    	  echo "SQL EXCEPTION NOT FOUND DATABASE ".$this->DATABASE.""; 
    	  exit(); 
  		  } 
  		 return $link; 
	}  
	/**Este metodo Te conecta a una base de datos Mysql
*Inicializando Primero correctamente tu Objecto Sql
*Con los parametros de DB or schema, Servidor(ruta),User & Password
**/
function ConectarPDO(){
	//echo $this->SERVER.$this->USER.$this->PASSWORD;
 	  if (!($link=mysql_connect($this->SERVER, $this->USER, $this->PASSWORD))){ 
  			 echo "SQL EXCEPTION ERROR CONNECTING TO DATABASE."; 
  			 exit(); 
 		  } 
  		 return $link; 
	}
/////////////////////////////////////END CONEXION/////////////////////////////////////////////////

/////////////////////////////////////SELECTS/////////////////////////////////////////////////
/**
*Este Metodo recibe como parametro la consulta que se hara a la base de datos
*Retorna el ResultSet
**/
function SelectQuery($query){ 
		$ResultSet = NULL;
		$link=$this->Conectar();
		$ResultSet =  mysql_query($query,$link);	
		$this->AFFECTED_ROWS = mysql_affected_rows();	
		 if (mysql_errno()) { 
  		$error = "MySQL error ".mysql_errno().": ".mysql_error()."\t\t When executing:<br>$query<br>"; 
  		$this->Log($error." Time: ".date('Y-m-dTH:i:s')."\n");
 		 echo $error;		 
	 }
		mysql_close($link);
		return $ResultSet;	 
} 
/**Este Metodo recibe como parametro:
*El Nombre de la tabla $table
*El nombre de los Campos $fields
*Y una Condicion si asi lo requiere, si esta viene null 
*Se forma una consulta a la base de datos sin where (condicion)
**/
function Select($table,$fields,$condition){ 
		$ResultSet = NULL;
		$link=$this->Conectar();
		if($condition==NULL){
		$query ="select $fields from $table";
		}else{
		$query ="select $fields from $table where $condition";
		}	
		//echo $query;	
		$ResulSet =  mysql_query($query,$link);
		$this->AFFECTED_ROWS = mysql_affected_rows();		
		 if (mysql_errno()) { 
  		$error = "MySQL error ".mysql_errno().": ".mysql_error()."\t\t When executing:<br>$query<br>"; 
  		$this->Log($error." Time: ".date('Y-m-dTH:i:s')."\n");
 		 echo $error;
	 }
		mysql_close($link);
	return $ResulSet;	
}

/**Este Metodo recibe como parametro:
*El Nombre de la tabla $table
*El nombre de los Campos $fields
*Y una Condicion si asi lo requiere, si esta viene null 
*Se forma una consulta a la base de datos sin where (condicion)
**/
function SelectTop($table,$fields,$condition,$topp){ 
		$ResultSet = NULL;
		$link=$this->Conectar();
		if($condition==NULL){
		$query ="select $fields from $table";
		}else{
		$query ="select $fields from $table where $condition limit 0,$topp";
		}		
		$ResulSet =  mysql_query($query,$link);
		$this->AFFECTED_ROWS = mysql_affected_rows();		
		 if (mysql_errno()) { 
  		$error = "MySQL error ".mysql_errno().": ".mysql_error()."\t\t When executing:<br>$query<br>"; 
  		$this->Log($error." Time: ".date('Y-m-dTH:i:s')."\n");
 		 echo $error;
	 }
		mysql_close($link);
	return $ResulSet;	
}

/////////////////////////////////////END SELECTS/////////////////////////////////////////////////

/////////////////////////////////////INSERTS/////////////////////////////////////////////////
/**
*Este Metodo recibe como parametro
*el nombre de la @tabla,
*el arreglo de  @campos,
*el arreglo de @valores.
*que se insertaran en la base de datos
**/
function Insert($table,$fields,$values){ 
        $arg=false;
		$result = NULL;
		$link=$this->Conectar();
		$query = "Insert into $table ".$this->ConvineValsInsert($fields,$values);	  
		$result =  mysql_query($query,$link);		 
      $this->AFFECTED_ROWS = mysql_affected_rows();
	  if (mysql_errno()) { 
  		$error = "MySQL error ".mysql_errno().": ".mysql_error()."\t\t When executing:<br>$query<br>"; 
  		$this->Log($error." Time: ".date('Y-m-dTH:i:s')."\n");
 		 echo $error;
	 }else if(!mysql_errno()){	
	 //echo $rows." Insert query convenivals<br>";
	  if($this->AFFECTED_ROWS>0){
		  $arg=true;
	  }	 
	
	 }
	mysql_close($link);
	return $arg;
} 

/**
*Este Metodo recibe como parametro
*un Query ya generado, For example "insert into table (campo)values('datonuevo')"
**/
function InsertQuery($query){ 
$arg=false;
$result = NULL;
		$link=$this->Conectar();
		$result =  mysql_query($query,$link);	
		$this->AFFECTED_ROWS = mysql_affected_rows();
		if (mysql_error()) { 
  		$error = "MySQL error ".mysql_errno().": ".mysql_error()."\t\t When executing:<br>$query<br>"; 
  		$this->Log($error." Time: ".date('Y-m-dTH:i:s')."\n");
 		 echo $error;
	 }else if(!mysql_errno()){	
		//echo $rows." rows afected Insert <br>";
	  if($this->AFFECTED_ROWS>0){
		   $arg=true;
	  }	 
 }
	mysql_close($link);

return $arg;
} 
/////////////////////////////////////END INSERTS/////////////////////////////////////////////////

////////////////////////////////////////UPDATES/////////////////////////////////////////////////
/**Este Metodo recibe como parametro una consulta ya generada,
For Example , $query = "update table set campo1='valormodificado', campo2='valormodificado2' where clave='MX'"
**/
function UpdateQuery($query){ 
$arg=false;
$result = NULL;
$link=$this->Conectar();
$result =  mysql_query($query,$link);	
$this->AFFECTED_ROWS = mysql_affected_rows();
if (mysql_errno()) { 
  $error = "MySQL error ".mysql_errno().": ".mysql_error()."\t\t When executing:<br>$query<br>"; 
  $this->Log($error." Time: ".date('Y-m-dTH:i:s')."\n");
  echo $error;
 }else if(!mysql_errno()){	
	//echo $rows." rows afected Update <br>";
	  if($this->AFFECTED_ROWS>0){
		   $arg=true;
	  }	 
 }
	mysql_close($link);
return $arg;
} 

/** Este Metodo recibe como parametro:
*EL nombre de la tabla ->$table
*un arreglo de campos $fields[]
*un arreglo de valores $values[]
*y una condicion si asi lo requiere, si esta viene null 
*Se forma una consulta a la base de datos sin where (condicion)
**/
function Update($table,$fields,$values,$condition){ 
$arg=false;
$ResultSet = NULL;
$link=$this->Conectar();
if($condition==NULL){
	$query = "update $table set ".$this->ConvineValsUpdate($fields,$values);
}else{
    $query = "update $table set ".$this->ConvineValsUpdate($fields,$values)." where $condition";
}
$ResulSet =  mysql_query($query,$link);	
//echo $query;
$this->AFFECTED_ROWS = mysql_affected_rows();
	  if (mysql_errno()) { 
  		$error = "MySQL error ".mysql_errno().": ".mysql_error()."\t\t When executing:<br>$query<br>"; 
  		$this->Log($error." Time: ".date('Y-m-dTH:i:s')."\n");
 		 echo $error;
	 }else if(!mysql_errno()){	
	//  echo $rows." Update query convenivals<br>";
	  if($this->AFFECTED_ROWS>0){
		  $arg=true;
	  }	 
	
	 }
	mysql_close($link);

return $arg;
}
////////////////////////////////////////END UPDATES/////////////////////////////////////////////////


	
////////////////////////////////////////UTILS SQL/////////////////////////////////////////////////	
/**Este Metodo recibe como parametro:
*El Nombre de la tabla $table
*El nombre de los Campos $fields
*Y una Condicion si asi lo requiere, si esta viene null 
*Se forma una consulta a la base de datos sin where (condicion)
**/
function Exist($table,$field,$value){ 
		$ResultSet = NULL;
		$buf=FALSE;
		$link=$this->Conectar();
		$query ="select $field from $table where $field='$value'";
		$ResultSet =  mysql_query($query,$link);		
		 if (mysql_errno()) { 
		  $error = "MySQL error ".mysql_errno().": ".mysql_error()."\t\t When executing:<br>$query<br>"; 
		  $this->Log($error." Time: ".date('Y-m-dTH:i:s')."\n");
		   echo $error;
		 }
	 	while($row = mysql_fetch_array($ResultSet)){
				$buf=TRUE;
			}
		mysql_close($link);
	return $buf;	
}
/**
*Convina los valores y campos para la realizacion de Insert´s
**/
function ConvineValsInsert($fields,$data){
	$size = count($data);				       
	$fd="";
	$val="";			
		for($i=0;$i<$size;$i++){
			  if($i==$size-1){		
			    $fd = $fd . $fields[$i];
				$val = $val. "'".$data[$i]."'";
			   }else{				 
				$fd = $fd . $fields[$i].",";
				$val = $val. "'".$data[$i]."',";	
			 }		 
		}					 
					
		$arg="($fd) values ($val)";
		return $arg;				
	}
	////////chekar este metodo
function ConvineValsUpdate($fields,$data){
	$size = count($data);
	$arg = "";
	for($i=0;$i<$size;$i++){
		if($i==$size-1){
		$arg = $arg . $fields[$i]."='".$data[$i]."'";
		//$val = $val. "'".$data[$i]."'";
		}else{
		$arg = $arg. $fields[$i]."='".$data[$i]."',";
		//$val = $val. "'".$data[$i]."',";
		}
	}
	return $arg;
}
function SetExeption($Mensage){
 $this->EX = $Mensage;
}
/*
*
*
*/
function ShowFields($table){ 
		$ResultSet = NULL;
		$link=$this->Conectar();
		$ResulSet =  mysql_query("DESCRIBE ".$table,$link);		
		 if (mysql_errno()) { 
  		$error = "MySQL error ".mysql_errno().": ".mysql_error()."\t\t When executing:<br>$query<br>"; 
  		$this->Log($error." Time: ".date('Y-m-dTH:i:s')."\n");
 		 echo $error;		 
	 }
		mysql_close($link);
		return $ResulSet;	 
} 
/*
*
*
*/
function ShowTables(){ 
		$ResultSet = NULL;
		$link=$this->Conectar();
		$ResulSet =  mysql_query("Show Tables",$link);		
		 if (mysql_errno()) { 
  		$error = "MySQL error ".mysql_errno().": ".mysql_error()."\t\t When executing:<br>$query<br>"; 
  		$this->Log($error." Time: ".date('Y-m-dTH:i:s')."\n");
 		 echo $error;		 
	 }
		mysql_close($link);
		return $ResulSet;	 
} 
/*
*
*
*/
function ShowDataBases(){ 
		$ResultSet = NULL;
		$link=$this->ConectarPDO();
		$ResulSet =  mysql_query("SHOW DATABASES",$link);		
		 if (mysql_errno()) { 
  		$error = "MySQL error ".mysql_errno().": ".mysql_error()."\t\t When executing:<br>$query<br>"; 
  		$this->Log($error." Time: ".date('Y-m-dTH:i:s')."\n");
 		 echo $error;		 
	 }
		mysql_close($link);
		return $ResulSet;	 
} 
/*
*
*
*/
function CreateTable($table,$fields,$type,$Null){
$query="CREATE TABLE `".$table."` (id".$table." INT NOT NULL AUTO_INCREMENT, ";
	for($i=0;$i<count($fields);$i++){
		$query .= $fields[$i]." ".strtolower($type[$i]).$Null[$i].", ";
	}
$query .= " PRIMARY KEY(id".$table.")";
$query.=");";
$ResulSet = $this->SelectQuery($query);
if (mysql_errno()) { 
  		$error = "MySQL error ".mysql_errno().": ".mysql_error()."\t\t When executing:<br>$query<br>"; 
  		$this->Log($error." Time: ".date('Y-m-dTH:i:s')."\n");
 		 echo $error;		 
	 }
		mysql_close($link);
		return $ResulSet;
}
/****************************************************************************************************/
function mysqldump(){
	$ex = system('mysqldump --opt --user=$this->USER --password=$this->PASSWORD --host=$this->SERVER $this->DATABASE > '.$this->DUMP_FILE.'dump_'.date('Y-m-dTH:i:s').'.sql');
	return $ex;
}

/****************************************************************************************************/
////////////////////////////////////////END UTILS SQL/////////////////////////////////////////////////
function Log($text){
		$fp = fopen($this->LOGFILE, 'c');
		$text = "SQL: ".$text."\n";
		fwrite($fp, $text);
		fclose($fp);
	}
/********************************************************************************/
}
?>