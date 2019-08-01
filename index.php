<?php
include 'QueryBuilder.php';

$config = include ("config.php");
$query = new QueryBuilder($config);


$query->select("*")->from("sometable")->where("id", ">", 10)->execute(); //SELECT * FROM sometable WHERE id > 10 
$query->insert("anotherTable")->values("one", "two", "three")->execute(); //INSERT INTO anotherTable VALUES (?, ?, ?)
$query->update("table", ["one-column", "second-column", "third-column"], [100, 200, 300])->between(400, 500)->execute() //UPDATE table SET one-column = 100, second-column = 200, third-column = 300 BETWEEN 400 AND 500