<?php

namespace API\Models;

use API\Core\Db;
use PDO;

class Model extends Db{

    protected string $table;

    private PDO $db;

    public function sendQuery(string $sql, array $attr = null): object|array
    {

        $this->db = Db::getInstance();

        if($attr !== null){
            $query = $this->db->prepare($sql);
            $query->execute($attr);
            return $query;
        }else {
            return $this->db->query($sql);
        }
    }

    public function hydrate(object|array $data)
    {

        foreach($data as $fieldName => $value){

            $setter = 'set' . ucfirst(htmlspecialchars($fieldName));

            if(method_exists($this, $setter)){
                $this->$setter(htmlspecialchars($value));
            }
        }
        
        return $this;
    }

    public function create(): object
    {

        $fields = [];
        $questMarks = [];
        $values = [];

        foreach($this as $field => $value){
            if($value !==null && $field !=='db' && $field !== 'table'){
                $fields[] = htmlspecialchars($field);
                $questMarks[] = '?';
                $values[] = htmlspecialchars($value);
            }
        }

        $fields_list = implode(', ', $fields);
        $questMarks_list = implode(', ', $questMarks);

        return $this->sendQuery("INSERT INTO $this->table ($fields_list) VALUES ($questMarks_list)", $values);
    }

    public function getAll(): array|false
    {

        $query = $this->sendQuery("SELECT * FROM $this->table");

        return $query->fetchAll();
    }

    public function getBy(array $crits, string $orderField = null): array|false
    {
        $fields = [];
        $values = [];

        foreach($crits as $field => $value){
            $fields[] = htmlspecialchars("$field = ?");
            $values[] = htmlspecialchars($value);
        }

        $fields_list = implode(' AND ', $fields);

        $orderField !== null ? $order_by = htmlspecialchars("ORDER BY $orderField") : $order_by = '';

        return $this->sendQuery("SELECT * FROM $this->table WHERE $fields_list $order_by", $values)->fetchAll();
    }

    public function getOneBy(string $field, $value): object|false
    {
        $field = htmlspecialchars($field);
        $value = htmlspecialchars($value);
        return $this->sendQuery("SELECT * FROM $this->table WHERE $field = '$value'")->fetch();
    }

    public function update(): object
    {
        $fields = [];
        $values = [];

        foreach($this as $field => $value){
            if($value !== null && $field !== 'db' && $field !== 'table'){
                $fields[] = htmlspecialchars("$field = ?");
                $values[] = htmlspecialchars($value);
            }
        }
        $fields_list = implode(', ', $fields);
        $values[] = $this->id;

        return $this->sendQuery('UPDATE ' . $this->table . ' SET ' . $fields_list . ' WHERE id = ?', $values);
    }

    public function delete(): object|false
    {
        return $this->sendQuery('DELETE FROM ' . $this->table . ' WHERE id = ?', [$this->id]);
    }
}