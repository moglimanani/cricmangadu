<?php
ini_set('display_errors', '1');
include_once '../config.php';
include_once '../db.php';
include_once '../utility.php';

class PLAYERS
{
    private $results;
    private $db;

    public function __construct($host, $user, $password, $database)
    {
        $this->db = new DB($host, $user, $password, $database);
        $this->results = new stdClass();
    }
    public function resetInsertId($table)
    {
        $qry = 'TRUNCATE TABLE ' . $table;
        $stmt = $this->db->query($qry);
    }
    public function getAllPlayers($table)
    {
        $select_all_cat_query = 'SELECT * FROM ' . $table;
        $players = $this->db->query($select_all_cat_query)->fetchAll();
        $this->results->error = false;
        $this->results->category = $players;
        return $this->results;
        // echo json_encode($this->results);
    }
    public function getPlayerByID($table, $playerId)
    {
        $select_query = 'SELECT * FROM ' . $table . ' WHERE `player_id` =' . $playerId;
        $player = $this->db->query($select_query)->fetchAll();
        $this->results->error = false;
        $this->results->player = $player;
        return $this->results;
    }
    public function insertPlayer($table, $params, $types)
    {
        $question = str_repeat("?, ", count($params));
        $question = substr($question, 0, -2);

        $insert_query = 'INSERT INTO ' . $table . '(' . implode(', ', array_keys($params)) . ') VALUES (' . $question . ') ';
        $data = $this->db->connection->prepare($insert_query);
        $data->bind_param($types, ...array_values($params));

        $result = new stdClass();
        try {
            $data->execute();
            $addedId = $this->db->lastInsertID();
            $LastId = $this->getPlayerByID($table, $addedId);
            $result->error = false;
            $result->result = new stdClass();
            $result->result->count = 1;
            $result->result->player = new stdClass();
            $result->result->player = $LastId->player;
        } catch (Exception $e) {
            // echo $e->getMessage();
            $result->error = true;
            $result->message = strip_tags($e->getMessage());
        }
        return $result;
    }
    public function updatePlayer($table, $updateByColumn, $updateByColumnTypes, $where)
    {
        $updatecol = '';
        $updateColKey = array_keys($updateByColumn);
        $updateColValues = array_values($updateByColumn);

        foreach ($updateColKey as $key => $value) {
            $updatecol = $updatecol . $value . '=?, ';
        }
        $updateColKey = substr($updatecol, 0, -2);
        $query = 'UPDATE ' . $table . ' SET ' . $updateColKey . ' WHERE ' . $where;
        $data = $this->db->connection->prepare($query);
        $data->bind_param($updateByColumnTypes, ...$updateColValues);
        $result = new stdClass();

        try {
            $data->execute();
            $result->error = false;
            $res = new stdClass();
            $res->count = $this->db->query_count;
            $result->result = $res;
        } catch (Exception $e) {
            $result->error = true;
            $result->message = strip_tags($e->getMessage());
        }
        return $result;
    }
    public function deletePlayer($table, $id)
    {
        $sqlQuery = 'DELETE FROM ' . $table . ' WHERE player_id=?';
        $statement = $this->db->connection->prepare($sqlQuery);
        $statement->bind_param("i", $id);
        $result = new stdClass();
        try {
            $statement->execute();
            $result->error = false;
        } catch (Exception $e) {
            $result->error = true;
            $result->message = strip_tags($e->getMessage());
        }
        return $result;
    }
    // ********************** //
};
