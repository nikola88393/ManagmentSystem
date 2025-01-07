<?php
class Item {
    private $conn;
    private $table_name = "items";

    public $ItemID;
    public $Name;
    public $Price;
    public $Gender;
    public $Quantity_XS;
    public $Quantity_S;
    public $Quantity_M;
    public $Quantity_L;
    public $Quantity_XL;
    public $ImageURL;
    public $user_id;
    public $CategoryID;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new item
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (Name, Price, Gender, Quantity_XS, Quantity_S, Quantity_M, Quantity_L, Quantity_XL, ImageURL, user_id, CategoryID) VALUES (:Name, :Price, :Gender, :Quantity_XS, :Quantity_S, :Quantity_M, :Quantity_L, :Quantity_XL, :ImageURL, :user_id, :CategoryID)";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->Name = htmlspecialchars(strip_tags($this->Name));
        $this->Price = htmlspecialchars(strip_tags($this->Price));
        $this->Gender = htmlspecialchars(strip_tags($this->Gender));
        $this->Quantity_XS = htmlspecialchars(strip_tags($this->Quantity_XS));
        $this->Quantity_S = htmlspecialchars(strip_tags($this->Quantity_S));
        $this->Quantity_M = htmlspecialchars(strip_tags($this->Quantity_M));
        $this->Quantity_L = htmlspecialchars(strip_tags($this->Quantity_L));
        $this->Quantity_XL = htmlspecialchars(strip_tags($this->Quantity_XL));
        $this->ImageURL = htmlspecialchars(strip_tags($this->ImageURL));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->CategoryID = htmlspecialchars(strip_tags($this->CategoryID));

        // bind values
        $stmt->bindParam(":Name", $this->Name);
        $stmt->bindParam(":Price", $this->Price);
        $stmt->bindParam(":Gender", $this->Gender);
        $stmt->bindParam(":Quantity_XS", $this->Quantity_XS);
        $stmt->bindParam(":Quantity_S", $this->Quantity_S);
        $stmt->bindParam(":Quantity_M", $this->Quantity_M);
        $stmt->bindParam(":Quantity_L", $this->Quantity_L);
        $stmt->bindParam(":Quantity_XL", $this->Quantity_XL);
        $stmt->bindParam(":ImageURL", $this->ImageURL);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":CategoryID", $this->CategoryID);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Read all items for a specific user with optional gender filter, category filter, and search query
    public function readAllByUser($user_id, $gender = '', $category = '', $search = '') {
        $query = "SELECT items.*, categories.Name as CategoryName FROM " . $this->table_name . " 
                  LEFT JOIN categories ON items.CategoryID = categories.CategoryID 
                  WHERE items.user_id = :user_id";
        if ($gender) {
            $query .= " AND items.Gender = :Gender";
        }
        if ($category) {
            $query .= " AND items.CategoryID = :CategoryID";
        }
        if ($search) {
            $query .= " AND items.Name LIKE :search";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        if ($gender) {
            $stmt->bindParam(':Gender', $gender);
        }
        if ($category) {
            $stmt->bindParam(':CategoryID', $category);
        }
        if ($search) {
            $search = "%$search%";
            $stmt->bindParam(':search', $search);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read a single item by ID
    public function readSingle($id) {
        $query = "SELECT items.*, categories.Name as CategoryName FROM " . $this->table_name . " 
                  LEFT JOIN categories ON items.CategoryID = categories.CategoryID 
                  WHERE items.ItemID = :ItemID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ItemID', $id);
        $stmt->execute();
        return $stmt;
    }

    // Update an item
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET Name = :Name, Price = :Price, Gender = :Gender, Quantity_XS = :Quantity_XS, Quantity_S = :Quantity_S, Quantity_M = :Quantity_M, Quantity_L = :Quantity_L, Quantity_XL = :Quantity_XL, ImageURL = :ImageURL, CategoryID = :CategoryID 
                  WHERE ItemID = :ItemID";
    
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->Name = htmlspecialchars(strip_tags($this->Name));
        $this->Price = htmlspecialchars(strip_tags($this->Price));
        $this->Gender = htmlspecialchars(strip_tags($this->Gender));
        $this->Quantity_XS = htmlspecialchars(strip_tags($this->Quantity_XS));
        $this->Quantity_S = htmlspecialchars(strip_tags($this->Quantity_S));
        $this->Quantity_M = htmlspecialchars(strip_tags($this->Quantity_M));
        $this->Quantity_L = htmlspecialchars(strip_tags($this->Quantity_L));
        $this->Quantity_XL = htmlspecialchars(strip_tags($this->Quantity_XL));
        $this->ImageURL = htmlspecialchars(strip_tags($this->ImageURL));
        $this->CategoryID = htmlspecialchars(strip_tags($this->CategoryID));
        $this->ItemID = htmlspecialchars(strip_tags($this->ItemID));
    
        // bind values
        $stmt->bindParam(":Name", $this->Name);
        $stmt->bindParam(":Price", $this->Price);
        $stmt->bindParam(":Gender", $this->Gender);
        $stmt->bindParam(":Quantity_XS", $this->Quantity_XS);
        $stmt->bindParam(":Quantity_S", $this->Quantity_S);
        $stmt->bindParam(":Quantity_M", $this->Quantity_M);
        $stmt->bindParam(":Quantity_L", $this->Quantity_L);
        $stmt->bindParam(":Quantity_XL", $this->Quantity_XL);
        $stmt->bindParam(":ImageURL", $this->ImageURL);
        $stmt->bindParam(":CategoryID", $this->CategoryID);
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