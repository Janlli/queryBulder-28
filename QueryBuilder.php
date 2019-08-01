<?php


class QueryBuilder
{
    private $connection;
    private $query = "";
    private $values = [];
    
    public function __construct(array $dbparams)
    {
        $this->connection = new PDO($dbparams["dbType"] . ":host=" . $dbparams["host"] .
            ";" . "dbname=" . $dbparams["dbName"], $dbparams["login"], $dbparams["password"]);
    }
    
    public function select(...$args)
    {
        $this->query = "SELECT " . implode(", ", $args);
        return $this;
    }
    
    public function insert(string $tableName, array $columns = [])
    {
        $columnString = "";
        if ($columns != []){
            $columnString = "(" . implode(", ", $columns) . ")";
        }
        $this->query = "INSERT INTO $tableName $columnString ";
        
        return $this;
    }
    
    public function values(...$args)
    {
        $string = " VALUES (";
        for ($i = 0; $i < count($args); $i++){
            $string .= "?, ";
            array_push($this->values, $args[$i]);
        }
        $string = rtrim($string, ", ") . ")";
        $this->query .= $string;
        return $this;
    }
    
    public function update(string $table, array $columns = [], array $newValues = [])
    {
        $this->query = "UPDATE $table SET ";
        $string = "";
        for ($i = 0; $i < count($columns); $i++) {
            $string .= "$columns[$i] = $newValues[$i], ";
        }
        $this->query .= rtrim($string, ", ");
        return $this;
    }
    
    public function delete(string $table)
    {
        $this->query = "DELETE FROM $table";
        return $this;
    }
    
    public function from(string $table)
    {
        $this->query .= " FROM $table";
        return $this;
    }
    
    public function where($someValue, string $sign = "", $anotherValue = "")
    {
        $this->query .= " WHERE $someValue $sign $anotherValue ";
        return $this;
    }
    
    public function and($someValue, string $sign, $anotherValue)
    {
        $this->query .= " AND $someValue $sign $anotherValue ";
        return $this;
    }
    
    public function or($someValue, string $sign, $anotherValue)
    {
        $this->query .= " OR $someValue $sign $anotherValue ";
        return $this;
    }
    
    public function orderBy(...$args)
    {
        $this->query .= " ORDER BY";
        for ($i = 0; $i < count($args); $i++){
            if (preg_match("/^(desc|asc)$/i", $args[$i])){
                $this->query .= " $args[$i], ";
            } else {
                $this->query .= " $args[$i] ";
            }
        }
        $this->query = rtrim($this->query, ", ");
        return $this;
    }
    
    public function limit(int $value, int $value2 = 0){
        if ($value2 === 0){
            $this->query .= " LIMIT $value";
            return $this;
        } else {
            $this->query .= " LIMIT $value, $value2";
            return $this;
        }
        
    }
    
    public function like(string $pattern)
    {
        $this->query .= " LIKE $pattern";
        return $this;
    }
    
    public function between($someValue, $anotherValue)
    {
        $this->query .=  " BETWEEN $someValue AND $anotherValue";
        return $this;
    }
    
    public function in(...$args)
    {
        $this->query .= " IN (" . implode(", ", ...$args) . ")";
        return $this;
    }
    
    public function execute()
    {
        $stmt = $this->connection->prepare($this->query);
        $stmt->execute($this->values);
    }
    
}

