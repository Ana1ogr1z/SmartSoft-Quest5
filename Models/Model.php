<?php

namespace Models;

use mysqli;
use Cfg\Config;

abstract class Model extends Config
{
    /**
     * @var array
     */
    private const DIFFERENT_PROPERTY = [
        'table' => null,
        'connect' => null,
        'user' => null,
        'db_password' => null,
        'server' => null,
        'db_name' => null,
        'id' => null,
    ];

    /**
     * @var mysqli
     */
    protected $connect;

    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var int
     */
    public $id = 0;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->connect = mysqli_connect($this->server, $this->user, $this->db_password, $this->db_name);

        if (!$this->connect) {
            die("Connection fail");
        }

        mysqli_set_charset($this->connect, "utf8");
    }

    /**
     *
     * @param int $id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $select = "SELECT * FROM `" . $this->table . "`";

        $result = mysqli_query($this->connect, $select);

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $rows;
    }

    /**
     *
     * @return void
     */
    public function delete(): void
    {
        $id = $this->getId();

        if ($id) {
            $delete = "DELETE FROM `" . $this->table . "` where `id` = '" . $id . "'";

            mysqli_query($this->connect, $delete);
        }
    }

    /**
     * @param array
     * @return void
     */
   public function update(): void
{
    $properties = array_diff_key(get_object_vars($this), self::DIFFERENT_PROPERTY);

    error_log("Object properties before update: " . print_r(get_object_vars($this), true));
    error_log("Filtered properties: " . print_r($properties, true));

    $properties_implode = [];

    foreach ($properties as $key => $value) {
        $properties_implode[] = "`" . $key . "` = '" . mysqli_real_escape_string($this->connect, $value) . "'";
    }

    error_log("Properties for update: " . print_r($properties_implode, true));
    error_log("ID for update: " . $this->getId());

    if ($properties_implode && $this->getId()) {
        $update = "UPDATE `" . $this->table . "` SET " . implode(', ', $properties_implode) . " WHERE `id` = '" . $this->getId() . "'";
        
        error_log("SQL Update: " . $update);

        $result = mysqli_query($this->connect, $update);
        
        if (!$result) {
            error_log("Update error: " . mysqli_error($this->connect));
        } else {
            error_log("Update successful, affected rows: " . mysqli_affected_rows($this->connect));
        }
    } else {
        error_log("No properties or ID for update");
    }
}

    /**
     *
     * @param string $id
     * @return array
     */
    public function findOne(string $id): array
    {
        $select = "SELECT * FROM `" . $this->table . "` where id = '" . $id . "'";

        $result = mysqli_query($this->connect, $select);

        return  mysqli_fetch_assoc($result);
    }

   public function insert(): void
{
    $properties = array_diff_key(get_object_vars($this), self::DIFFERENT_PROPERTY);

    $properties_implode = [];

    foreach ($properties as $key => $value) {
        $properties_implode[] = "`" . $key . "` = '" . mysqli_real_escape_string($this->connect, $value) . "'";
    }
    
    $insert = "INSERT INTO `" . $this->table . "` SET " . implode(', ', $properties_implode);
    error_log("SQL: " . $insert);
    
    $result = mysqli_query($this->connect, $insert);

}

    /**
     * 
     * @param array
     * @return void
     */
    public function create(): void
{
    $properties = array_diff_key(get_object_vars($this), self::DIFFERENT_PROPERTY);
    
    error_log("Properties for insert: " . print_r($properties, true));
    error_log("All object vars: " . print_r(get_object_vars($this), true));
    
    $columns = [];
    $values = [];
    
    foreach ($properties as $key => $value) {
        if ($value !== null && $value !== '') {
            $columns[] = "`$key`";
            $values[] = "'" . mysqli_real_escape_string($this->connect, $value) . "'";
        }
    }
    
    error_log("Columns: " . print_r($columns, true));
    error_log("Values: " . print_r($values, true));
    
    if (!empty($columns)) {
        $insert = "INSERT INTO `{$this->table}` (" . implode(', ', $columns) . ") 
                  VALUES (" . implode(', ', $values) . ")";
        
        error_log("SQL Query: " . $insert);
        
        $result = mysqli_query($this->connect, $insert);
        
        if (!$result) {
            error_log("Database error: " . mysqli_error($this->connect));
        } else {
            error_log("Insert successful, ID: " . mysqli_insert_id($this->connect));
        }
    } else {
        error_log("No properties to insert");
    }
}
}

?>