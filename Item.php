<?php
class Item {
    private $conn;
    private $table_name = "items";

    public $ItemID;
    public $Name;
    public $Price;
    public $Gender;
    public $Quantity;
    public $Size;
    public $ImageURL;
    public $user_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new item
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (Name, Price, Gender, Quantity, Size, ImageURL, user_id) VALUES (:Name, :Price, :Gender, :Quantity, :Size, :ImageURL, :user_id)";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->Name = htmlspecialchars(strip_tags($this->Name));
        $this->Price = htmlspecialchars(strip_tags($this->Price));
        $this->Gender = htmlspecialchars(strip_tags($this->Gender));
        $this->Quantity = htmlspecialchars(strip_tags($this->Quantity));
        $this->Size = htmlspecialchars(strip_tags($this->Size));
        $this->ImageURL = htmlspecialchars(strip_tags($this->ImageURL));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        // bind values
        $stmt->bindParam(":Name", $this->Name);
        $stmt->bindParam(":Price", $this->Price);
        $stmt->bindParam(":Gender", $this->Gender);
        $stmt->bindParam(":Quantity", $this->Quantity);
        $stmt->bindParam(":Size", $this->Size);
        $stmt->bindParam(":ImageURL", $this->ImageURL);
        $stmt->bindParam(":user_id", $this->user_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Read all items for a specific user with optional gender filter
    public function readAllByUser($user_id, $gender = '') {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id";
        if ($gender) {
            $query .= " AND Gender = :Gender";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        if ($gender) {
            $stmt->bindParam(':Gender', $gender);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read a single item by ID
    public function readSingle($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE ItemID = :ItemID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ItemID', $id);
        $stmt->execute();
        return $stmt;
    }

    // Update an item
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET Name = :Name, Price = :Price, Gender = :Gender, Quantity = :Quantity, Size = :Size, ImageURL = :ImageURL 
                  WHERE ItemID = :ItemID";
    
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->Name = htmlspecialchars(strip_tags($this->Name));
        $this->Price = htmlspecialchars(strip_tags($this->Price));
        $this->Gender = htmlspecialchars(strip_tags($this->Gender));
        $this->Quantity = htmlspecialchars(strip_tags($this->Quantity));
        $this->Size = htmlspecialchars(strip_tags($this->Size));
        $this->ImageURL = htmlspecialchars(strip_tags($this->ImageURL));
        $this->ItemID = htmlspecialchars(strip_tags($this->ItemID));
    
        // bind values
        $stmt->bindParam(":Name", $this->Name);
        $stmt->bindParam(":Price", $this->Price);
        $stmt->bindParam(":Gender", $this->Gender);
        $stmt->bindParam(":Quantity", $this->Quantity);
        $stmt->bindParam(":Size", $this->Size);
        $stmt->bindParam(":ImageURL", $this->ImageURL);
        $stmt->bindParam(":ItemID", $this->ItemID);
    
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }

    // Delete an item
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE ItemID = :ItemID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ItemID', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>