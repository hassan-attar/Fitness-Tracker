CREATE DATABASE IF NOT EXISTS fitnessTracker;
USE fitnessTracker;

CREATE TABLE Users (
    userID INT AUTO_INCREMENT,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    birthday DATE NOT NULL,
    gender CHAR(1) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    passwordChangedAt DATE,
    authConfirmKey VARCHAR(128),
    authKeyExpiresAt DATETIME,
    forgetPasswordToken VARCHAR(128),
    forgetPasswordSelector VARCHAR(128),
    forgetTokenExpiresAt DATETIME,
    isActive BOOLEAN,
    createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME ON UPDATE CURRENT_TIMESTAMP,
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
    PRIMARY KEY (recipeID)
);

CREATE TABLE Ingredients (
    ingredientID INT AUTO_INCREMENT,
    recipeID INT,
    ingredientName VARCHAR(50) NOT NULL,
    PRIMARY KEY (ingredientID, recipeID),
    FOREIGN KEY (recipeID) REFERENCES Recipes(recipeID) ON DELETE CASCADE
);

CREATE TABLE Comments (
    userID INT,
    recipeID INT,
    `comment` VARCHAR(255) NOT NULL,
    `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    rate DECIMAL(2,1) NOT NULL,
    PRIMARY KEY (userID, recipeID),
    FOREIGN KEY (userID) REFERENCES Users(userID) ON DELETE CASCADE,
    FOREIGN KEY (recipeID) REFERENCES Recipes(recipeID) ON DELETE CASCADE
);
