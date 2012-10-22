<?php
	/**
	 * Создаем основной класс для соединения с базой данных 
	 */ 
	/*$dataBase = new dataBaseController(
						$_SERVER["PHP_SELF"],
						"testengelsdb",//пользователь
						"HJhjg4vtqw",//пароль
						"testengelsdb",//имя базы 
						"testengelsdb.db.9813554.hostedresource.com",//сервер
						"",
						"utf8",//кодировка
						"pr"//кодировка
					);*/
	$dataBase = new dataBaseController(
						$_SERVER["PHP_SELF"],
						"engelsdb",//пользователь
						"HJhjg4vtqw",//пароль
						"engelsdb",//имя базы 
						"engelsdb.db.9813554.hostedresource.com",//сервер
						"",
						"utf8",//кодировка
						"pr"//кодировка
					);
	