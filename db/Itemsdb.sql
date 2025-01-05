CREATE TABLE items (
    ItemID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    Gender ENUM('Men', 'Women') NOT NULL,
    Size ENUM('XS', 'S', 'M', 'L', 'XL') NOT NULL,
    Quantity INT NOT NULL,
    ImageURL VARCHAR(255) DEFAULT NULL
);
