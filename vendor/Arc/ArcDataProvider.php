<?php

namespace Arc\Data;

/**
 * Abstract DataProvider class
 */
abstract class DataProvider {

    // Unique Identifier
    public $id;
    // Database table
    public $table;
    // Database table columns to properties map
    public $map;

    private $db = [];

    /**
     * Default constructor
     */
    public function __construct() {

        // Create database connection
        try {
            if (ARCDBTYPE != "sqlite") {
                $this->db["database"] = new Medoo([
                    "database_type" => DB_TYPE,
                    "database_name" => DB_NAME,
                    "server" => DB_HOST,
                    "username" => DB_USER,
                    "password" => DB_PASS,
                    'charset' => DB_CHARSET,
                    'logging' => false,
                    'option' => [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                    ]
                ]);
            } else {
                $this->db["database"] = new Medoo([
                    'database_type' => DB_TYPE,
                    'database_file' => DB_HOST
                ]);
            }
        } catch (\Exception $e) {
            Arc\ArcSystem::setError($e->getMessage(), 200);
        }


        // Initilise ID
        $this->id = 0;
        // Initilise table name
        $this->table = '';
        // Initilise map
        $this->map = [];
    }

    /**
     * Get the columns from the map of the object
     * @return array Columns array
     */
    protected function getColumns() {
        // Create array to hold columns
        $columns = [];
        // Go through each item in the object map
        foreach ($this->map as $property => $column) {
            // Add the column to the array
            $columns[] = $column;
        }
        // Return the array
        return $columns;
    }

    /**
     * Get data from database and fill the object
     * @param string $where Query to execute against the database
     */
    public function get($where) {
        // Run query against the database
        $data = $this->db->get($this->table, $this->getColumns(), $where);
        // Fill the object
        $this->fill($data);
    }

    /**
     * Get data from database and fill the object with random row
     * @param string $where Query to execute against the database
     */
    public function rand($where) {
        // Run query against the database
        $data = $this->db->rand($this->table, $this->getColumns(), $where);
        // Create array to hold the objects
        $collection = array();
        // Check we have an array from the database
        if (is_array($data)) {
            // Go through each item in the array
            foreach ($data as $item) {
                // Get the class needed to create the object
                $className = get_class($this);
                // Create a new object from its class
                $newObject = new $className;
                // Fill the new object
                $newObject->fill($item);
                // Add the object to the collection
                $collection[] = $newObject;
            }
        }
        // Return the collection
        return $collection;
    }

    /**
     * Get a collection of objects filled from the database
     * @param string $where Query to execute against the database
     * @return array Collection of objects
     */
    public function getCollection($where) {
        // Run query against the database
        $data = $this->db->select($this->table, $this->getColumns(), $where);
        // Create array to hold the objects
        $collection = array();
        // Check we have an array from the database
        if (is_array($data)) {
            // Go through each item in the array
            foreach ($data as $item) {
                // Get the class needed to create the object
                $className = get_class($this);
                // Create a new object from its class
                $newObject = new $className;
                // Fill the new object
                $newObject->fill($item);
                // Add the object to the collection
                $collection[] = $newObject;
            }
        }
        // Return the collection
        return $collection;
    }

    /**
     * Count the number of objects returned by query
     * @param string $where Query to execute against the database
     * @return int Number of rows matching query
     */
    public function getCount($where) {
        // Execute query against database
        $count = $this->db->count($this->table, $where);
        // Return number of rows
        return $count;
    }

    /**
     * Update/create the data from object in database using mapped fields
     * If force is true, the function will insert rather then update. Used when needing to insert IDs.
     */
    public function update($force = false) {
        // Create array to hold column to property data
        $dataColumns = [];
        // Get the properties of the object
        $properties = get_object_vars($this);
        // Go through each item in the map
        foreach ($this->map as $property => $column) {
            // Add the column to the array and set the value
            $dataColumns[$column] = $properties[$property];
        }
    
        try {
            // Is this a new object or something already in the database?
            if ($this->id == 0 || $force == true) {
                // This is a new object, so we insert it
                $this->db->insert($this->table, $dataColumns);
                // Set the ID of the object
                $this->id = $this->db->id();
            } else {
                // This is a old object, we just need to update it
                $this->db->update($this->table, $dataColumns, ['id' => $this->id]);
            }
        } catch (PDOException $ex) {
            // Something has gone wrong, kill the application and report it
            Arc\ArcSystem::setError("Error:<br />" . $ex->getMessage() . "<br /><br />Trace:<br />" . $ex->getTraceAsString()
                    . "<br /><br />Last query:<br />" . $this->db->last());
        }
    }

    /**
     * Delete an object from the database
     * @param int $id ID of the object to remove from database
     */
    public function delete() {
        // Execute query against database, if we have an ID
        if ($this->id > 0) {
            $this->db->delete($this->table, ['id' => $this->id]);
        }
    }

    /**
     * Fill and object with data from database using mapped fields
     * @param array $data Database data
     */
    protected function fill($data) {
        // Check we have a valid array
        if (is_array($data)) {
            // Go through each map item
            foreach ($this->map as $property => $column) {
                // Remove any datatype declaration from column map
                $actualColumn = preg_replace("/ \[.+?\]/", "", $column);
                // Check we have data for the column
                if (isset($data[$actualColumn])) {
                    // Populate object property with matching column data
                    $this->$property = $data[$actualColumn];
                }
            }
        }
    }

    /**
     * Support for the last query function.
     */
    public function last() {
        return $this->db->last();
    }
}