<?php

namespace Emeka\Potato\Model;

use PDO;
use Emeka\Potato\Base\BaseClass;
use Emeka\Potato\Database\Connections\Connect;

class Save extends Connect
{

    public static function executeInsertQuery ( $properties, $table, $primaryKey )
    {
        $total_properties_count = count($properties);
        $x = 0;

        $sql = "INSERT INTO " . $table . " (";
        $sqlSetColumns = "";
        $sqlSetValues = "";

        foreach($properties as $key => $value){
            $x++;
            
            if($key == $primaryKey) 
            {
                continue;
            }
            
            $sqlSetColumns .= $key;
            $sqlSetValues .= ":" . $key;
            if($x != $total_properties_count) 
            {
                $sqlSetColumns .= ", ";
                $sqlSetValues .= ", ";
            }
        }

        $sql .= $sqlSetColumns . " ) VALUES ( " . $sqlSetValues . " )";

        try 
        {
            $query =  self::getDataInstance();
            $query = $query->prepare($sql);
            foreach($properties as $key => $value)
            {
              $query->bindValue(':' . $key, $value);
            }
            return $query->execute();
        } 
        catch(PDOException $e) 
        {
            return $e->getMessage();
        }
    }

    public static function executeUpdateQuery( $properties, $table, $primaryKey )
    {
        try {
             $query =  self::getDataInstance();
             $count    =    0;
             $sql      =    "UPDATE ".$table." SET ";
             foreach ($properties as $key => $value) {
                 $count++;
                 if ($key == 'id') {
                     continue;
                 }
                 $sql .= "$key = ?";
                 if ($count < count($properties)) {
                     $sql .= ", ";
                 }
             }
             $sql .= " WHERE " .$primaryKey ." = ?";
             $statement = $query->prepare($sql);
             $indexCount = 0;
             foreach ($properties as $key => $value) {
                 if ($key === 'id') {
                     continue;
                 }
                 ++$indexCount;
                 $statement->bindValue($indexCount, $value);
             }
             $statement->bindValue(++$indexCount, $properties['id']);
             $result = $statement->execute();
         } catch (PDOException $e) {
             return $e->getMessage();
         }
         return $result;
    }





}
