<!---  
Скрипт создан для движка RAPTOR GAME ENGINE
Автор: SanX https://vk.com/sanxaiv
--->
<!DOCTYPE html>
<html>
	<head>
	<title>Установка RAPTOR GAME ENGINE</title>
		 <style type="text/css">
	html, body {
	position: fixed;		
    background-image: url(./storage/predef/bg1.jpg);
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

input {
	height: 25px;
	width: 250px;
	color: #000000;
	font-family: Tahoma;
	border: 1px solid #cccccc;
	border-radius: 3px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px; 
	-khtml-border-radius: 3px; 
}

button{
	height: 25px;
	width: 200px;
	color: #000000;
	font-family: Tahoma;
	border: 1px solid #cccccc;
	border-radius: 3px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px; 
	-khtml-border-radius: 3px; 
}

TD{
   font-weight: bold;
   font-size: 15px;
   font-family: Tahoma; 
   color: #cccccc;
}

footer {
   font-weight: bold;
   font-size: 15px;
   font-family: Tahoma; 
   color: #cccccc;
   vertical-align: bottom;
}
	 </style>
	</head>
	
	
	<body>
	<div id="head">
	<img src="./storage/predef/logo1.png" width="100%" height="100%">
	</div>
	<div id="body" align="center" >
	<form action="install.php" method="post"> 
		<table cellspacing="5">
		<tr>
		<td>
      Название игры
	  </td>
	  <td>
	  <input type="text" name="name" />
	  </td>
	  </tr>
	  		<tr>
		<td>
		Хост БД
	  </td>
	  <td>
	  <input type="text" name="host" />
	  </td>
	  </tr>
	  		<tr>
		<td>
      База данных
	  </td>
	  <td>
	  <input type="text" name="db" />
	  </td>
	  </tr>
	  		<tr>
		<td>
      Пользователь БД
	  </td>
	  <td>
	  <input type="text" name="user" />
	  </td>
	  </tr>
	  		<tr>
		<td>
      Пароль БД
	  </td>
	  <td>
	  <input type="password" name="pass" />
	  </td>
	  </tr>
		</table>
		<br>
		<button  type="submit" name="button" value="button">Установить движок</button>
		</form>
		</div>



<?php
ini_set('display_errors','On');
error_reporting('E_ALL');
if( isset( $_POST['button'] ) )
    {
$game_name = $_POST['name'];
$host_db = $_POST['host'];
$name_db = $_POST['db'];
$user_db = $_POST['user'];
$pass_db = $_POST['pass'];

$data = 
"<?php 

namespace Raptor;
	
	class Config
	{
		
		const ROOT = __DIR__; // DO NOT TOUCH
		const debug = true; // show error reportings
		
		// Game config
		const game_title = '$game_name';
		
		// Database
		const db_type = 'Mysql'; // current database type (may be Mongo, Mysql)
		const db_host = '$host_db';
		const db_database = '$name_db';
		const db_user = '$user_db';
		const db_password = '$pass_db';
		
		// Socket IO
		const secret_key = ''; // super secret password for socket.io server controlling
		const rsync_server = '127.0.0.1:8080'; // Raptor Sync Server ip and port
		const rchat_server = '127.0.0.1:8581'; // Raptor Chat Server ip and port
		
		// Cache
		// const cache_type = 'Memcache'; -- Is not available now, using only memcache
		const cache_host = '127.0.0.1';
		const cache_port = 11211;
		// const cache_prefix = 'raptor_'; // prefix will be added to all cache variable names -- Is not available now
		
	}
";
file_put_contents('./engine/config.php', $data);
$db = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (mysqli_connect_errno()) {
    printf("<p style='color:white;'>Не удалось подключиться к БД, проверьте правильность данных</p>");
    exit();
} else {	

 $dump=file_get_contents('./engine/cache/mysql-db.sql');
    $q=''; $state=0; $coco=0;
    for($i=0;$i<strlen($dump);$i++){
        switch($dump{$i}){
            case '"': if($state==0) $state=1; elseif($state==1) $state=0; break;
            case "'": if($state==0) $state=2; elseif($state==2) $state=0; break;
            case "`": if($state==0) $state=3; elseif($state==3) $state=0; break;
            case ";":
                if($state==0) {
                    //echo $q."\n;\n";
                    mysqli_query($db,$q);
                    $q='';
                    $state=4;
                    $coco++;
                }
                break;
            case "\\": if(in_array($state,array(1,2,3))) $q.=$dump[$i++]; break;
        }
        if($state==4) $state=0; else $q.=$dump{$i};
    }
	?>
			<table cellspacing="5">
		<tr>
		<td>
	<? echo '<a href="./">Вернуться на главную страницу</a>'; ?>
	<td>
	</tr>
	<tr>
		<td>
	<? echo'База данных обновлена! Файл конфигурации обновлён!'; ?>
	<td>
	</tr>
	<tr>
		<td>
    <? echo'Выполнено запросов: '.$coco; ?>
	<td>
	</tr>
	<tr>
		<td>
	<? echo'Движок RGE успешно установлен!'; ?>
	<td>
	</tr>
	<tr>
		<td>
	<? echo'Не забудьте удалить файл install.php!'; ?>
	<td>
	</tr>
	<?
	}
	}
	ini_set('display_errors','Off');
	?>
		<footer>
		<center> Powered by SanXAIV RGE©</center>
		</footer>
	</body>
<html>