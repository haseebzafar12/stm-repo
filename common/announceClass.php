<?php
class announceClass{
 
    private $conn;
 
    public function __construct($db){
        $this->conn = $db;
    }

    function stm_add_annoucement($title,$detail,$status,$createdBy){
    
        $query = $this->conn->prepare("INSERT INTO stm_announcements (title,detail,status,createdOn) 
                VALUES ('$title','$detail','$status','$createdBy')");
        
        $ex = $query->execute();
        return $query;
    }

    function stm_update_annouce($id,$detail,$title){
        $query = $this->conn->prepare("UPDATE stm_announcements SET 
            title ='$title',detail ='$detail' 
            WHERE id='$id'");
        $ex = $query->execute();
        return $query;
    }

    function stm_update_annoucement($id,$statusID){
        $query = $this->conn->prepare("UPDATE stm_announcements SET 
            status ='$statusID'
            WHERE id='$id'");
        $ex = $query->execute();
        return $query;
    }

    function stm_remove_annoucement($id){
        $query = $this->conn->prepare("DELETE from stm_announcements WHERE id='$id'");
        $ex = $query->execute();
        return $query;
    }

}