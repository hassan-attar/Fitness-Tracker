CREATE DATABASE IF NOT EXISTS fitnessTracker;
USE fitnessTracker;


CREATE TABLE Users (
	userID INT auto_increment, 
	firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    birthday DATE NOT NULL,
    gender CHAR(1) NOT NULL,-- F, M, N, O
    `password` VARCHAR(255) NOT NULL,
    passwordChangedAt DATE,
    isActive BOOLEAN,
    createdAt DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATE,
    
    CHECK(LENGTH(password) >= 8),
    CHECK (gender = "F" OR gender = "M" OR gender = "O"),
    CHECK (birthday <= DATE_SUB(CURDATE(), INTERVAL 14 YEAR)),
    
    PRIMARY KEY (userID)
);

CREATE TABLE Recipes (
	recipeID INT AUTO_INCREMENT,
    title VARCHAR(50) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    instruction TEXT,
    averageRating DECIMAL(2,1),
    cookTime INT NOT NULL,
    calories INT NOT NULL,
    imageURL VARCHAR(512) NOT NULL,
    
    CHECK(calories >= 1),
    CHECK(cookTime >= 1),
    CHECK(averageRating >= 0 && averageRating <= 5),
    PRIMARY KEY (recipeID)
);

CREATE TABLE Ingredients (
	ingredientID INT AUTO_INCREMENT,
    recipeID INT,
	ingredientName VARCHAR(50) NOT NULL,
    
    PRIMARY KEY (ingredientID, recipeID),
    FOREIGN KEY (recipeID) REFERENCES Recipes(recipeID) ON DELETE CASCADE
);

CREATE TABLE Ratings (
	ratingID INT AUTO_INCREMENT,
    userID INT,
    recipeID INT,
    rate DECIMAL(2,1) NOT NULL,
    
    CHECK(rate >= 0 && rate <= 5),
    PRIMARY KEY (ratingID),
    FOREIGN KEY (userID) REFERENCES Users(userID) ON DELETE SET NULL,
    FOREIGN KEY (recipeID) REFERENCES Recipes(recipeID) ON DELETE CASCADE
);


CREATE TABLE Comments (
	userID INT,
    recipeID INT,
    `comment` VARCHAR(255) NOT NULL,
    `date` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (userID, recipeID),
    FOREIGN KEY (userID) REFERENCES Users(userID) ON DELETE CASCADE,
    FOREIGN KEY (recipeID) REFERENCES Recipes(recipeID) ON DELETE CASCADE
);