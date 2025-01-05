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

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new parfum
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (Name, Price, Gender, Quantity, Size, ImageURL) VALUES (:Name, :Price, :Gender, :Quantity, :Size, :ImageURL)";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->Name = htmlspecialchars(strip_tags($this->Name));
        $this->Price = htmlspecialchars(strip_tags($this->Price));
        $this->Gender = htmlspecialchars(strip_tags($this->Gender));
        $this->Quantity = htmlspecialchars(strip_tags($this->Quantity));
        $this->Size = htmlspecialchars(strip_tags($this->Size));
        $this->ImageURL = htmlspecialchars(strip_tags($this->ImageURL));

        // bind values
        $stmt->bindParam(":Name", $this->Name);
        $stmt->bindParam(":Price", $this->Price);
        $stmt->bindParam(":Gender", $this->Gender);
        $stmt->bindParam(":Quantity", $this->Quantity);
        $stmt->bindParam(":Size", $this->Size);
        $stmt->bindParam(":ImageURL", $this->ImageURL);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Read all parfums
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read a single parfum by ID
    public function readSingle($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE ItemID = :ItemID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ItemID', $id);
        $stmt->execute();
        return $stmt;
    }

    // Update a parfum
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

    // Delete a parfum
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
