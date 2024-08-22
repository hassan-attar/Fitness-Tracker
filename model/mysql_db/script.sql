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

INSERT INTO Users (firstName, lastName, email, birthday, gender, `password`, isActive)
VALUES
    ('John', 'Nadoe', 'johndoe@example.com', '1990-01-01', 'M', 'test1234', 1),
    ('Jane', 'Smith', 'janesmith@example.com', '1995-05-10', 'F', 'test1234', 1),
    ('David', 'Johnson', 'davidjohnson@example.com', '1988-07-15', 'M', 'test1234', 1),
    ('Emily', 'Williams', 'emilywilliams@example.com', '1992-03-20', 'F', 'test1234', 1),
    ('Michael', 'Brown', 'michaelbrown@example.com', '1997-11-05', 'M', 'test1234', 1);

INSERT INTO Recipes (title, `description`, instruction, averageRating, cookTime, calories, imageURL)
VALUES
    ('Grilled Chicken Salad', 'Savor the freshness of grilled chicken atop mixed greens with cherry tomatoes, cucumber slices, and vinaigrette.', '1. Season chicken breast with salt and pepper. 2. Grill until cooked through. 3. Slice chicken and arrange on a bed of mixed greens. 4. Add cherry tomatoes and cucumber slices. 5. Drizzle with your favorite vinaigrette dressing.', 4.7, 20, 300, 'grilled-chicken-salad.jpg'),
    ('Quinoa-Stuffed Bell Peppers', 'Colorful bell peppers stuffed with quinoa, onions, garlic, and fresh herbs. Baked to perfection.', '1. Preheat oven to 375°F. 2. Cut off the tops of bell peppers and remove the seeds. 3. Cook quinoa according to package instructions. 4. Sauté onions and garlic until fragrant. 5. Mix cooked quinoa, sautéed onions, garlic, diced tomatoes, and fresh herbs. 6. Stuff the bell peppers with the quinoa mixture. 7. Bake for about 30 minutes until the peppers are tender.', 4.5, 40, 250, 'quinoa-stuffed-bell-peppers.jpg'),
    ('Salmon with Roasted Vegetables', 'Baked salmon fillet served with broccoli, carrots, and cauliflower. A delightful and healthy meal.', '1. Preheat oven to 400°F. 2. Season salmon fillet with salt, pepper, and your favorite herbs. 3. Arrange salmon on a baking sheet. 4. Toss vegetables such as broccoli, carrots, and cauliflower with olive oil, salt, and pepper. 5. Place the vegetables around the salmon. 6. Bake for about 20-25 minutes until the salmon is cooked and the vegetables are tender.', 4.9, 25, 350, 'salmon-with-roasted-vegetables.jpg'),
    ('Greek Yogurt Parfait', 'Layers of Greek yogurt, mixed berries, and granola for a delightful and nutritious treat.', '1. In a glass or bowl, alternate layers of Greek yogurt, mixed berries, and granola. 2. Repeat the layers until desired amount. 3. Top with a final dollop of Greek yogurt and a sprinkle of granola. 4. Enjoy!', 4.3, 10, 200, 'greek-yogurt-parfait.jpg'),
    ('Vegetable Stir-Fry', 'Assorted vegetables stir-fried in a light sauce. A quick and healthy dish for any day.', '1. Heat oil in a wok or large pan. 2. Add sliced bell peppers, broccoli florets, carrot strips, and snap peas. 3. Stir-fry for a few minutes until vegetables are crisp-tender. 4. In a small bowl, whisk together soy sauce, ginger, garlic, and a pinch of sugar. 5. Pour the sauce over the vegetables. 6. Stir-fry for another minute. 7. Serve hot with steamed rice or noodles.', 4.6, 15, 180, 'vegetable-stir-fry.jpg'),
    ('Lentil Soup', 'Hearty soup made with lentils, vegetables, and spices. A comforting and nutritious choice.', '1. Heat olive oil in a large pot. 2. Add diced onions, carrots, and celery. 3. Sauté until the vegetables are softened. 4. Add rinsed lentils, vegetable broth, diced tomatoes, and spices. 5. Bring to a boil, then reduce heat and simmer for about 30 minutes until the lentils are tender. 6. Serve hot and enjoy with a side of crusty bread.', 4.2, 30, 220, 'lentil-soup.jpg'),
    ('Oven-Baked Sweet Potato Fries', 'Crispy sweet potato fries baked in the oven. A guilt-free and delicious snack.', '1. Preheat oven to 425°F. 2. Cut sweet potatoes into thin strips. 3. Toss the sweet potato strips with olive oil, salt, pepper, and your favorite spices. 4. Arrange in a single layer on a baking sheet. 5. Bake for about 25-30 minutes, flipping once halfway through, until the fries are golden and crispy. 6. Serve hot and enjoy!', 4.4, 35, 180, 'sweet-potato-fries.jpg'),
    ('Avocado Toast', 'Toasted bread topped with mashed avocado, salt, pepper, and red pepper flakes for a quick and healthy snack.', '1. Toast bread slices until golden brown. 2. In a bowl, mash ripe avocados with a fork. 3. Spread the mashed avocado on the toasted bread slices. 4. Sprinkle with salt, pepper, and your favorite toppings such as red pepper flakes or sesame seeds. 5. Drizzle with a squeeze of fresh lemon or lime juice. 6. Enjoy!', 4.8, 10, 150, 'avocado-toast.jpg'),
    ('Veggie Wrap', 'Whole wheat wrap filled with sliced cucumbers, bell peppers, carrots, and lettuce leaves. A delightful and healthy meal.', '1. Spread a generous amount of hummus on a whole wheat wrap. 2. Layer sliced cucumbers, bell peppers, carrots, and lettuce leaves on top. 3. Sprinkle with your favorite herbs and seasonings. 4. Roll up the wrap tightly and secure with toothpicks if needed. 5. Slice in half and enjoy!', 4.5, 10, 220, 'veggie-wrap.jpg'),
    ('Quinoa Salad with Lemon Dressing', 'Refreshing salad with quinoa, diced cucumbers, halved cherry tomatoes, and chopped fresh parsley. Tossed with zesty lemon dressing.', '1. Cook quinoa according to package instructions and let it cool. 2. In a large bowl, combine cooked quinoa, diced cucumbers, halved cherry tomatoes, chopped fresh parsley, and diced red onions. 3. In a separate small bowl, whisk together olive oil, lemon juice, garlic, salt, and pepper to make the dressing. 4. Pour the dressing over the quinoa mixture and toss until well coated. 5. Serve chilled and enjoy!', 4.7, 25, 280, 'quinoa-salad.jpg'),
    ('Baked Chicken Breast', 'Tender and juicy chicken breast baked in the oven. A simple and delicious protein-packed meal.', '1. Preheat oven to 450°F. 2. Season chicken breasts with salt, pepper, and your favorite spices. 3. Place the seasoned chicken breasts on a baking sheet. 4. Bake for about 20-25 minutes until the chicken is cooked through and juices run clear. 5. Let the chicken rest for a few minutes before slicing. 6. Serve hot and enjoy!', 4.6, 30, 200, 'baked-chicken-breast.jpg'),
    ('Fruit Smoothie Bowl', 'Thick smoothie served in a bowl with assorted fruits and toppings. A delightful and refreshing breakfast.', '1. In a blender, combine frozen fruits, yogurt, and a splash of liquid (such as almond milk or coconut water). 2. Blend until smooth and creamy. 3. Pour the thick smoothie into a bowl. 4. Top with sliced fresh fruits, granola, chia seeds, or other desired toppings. 5. Enjoy with a spoon!', 4.4, 10, 220, 'fruit-smoothie-bowl.jpg'),
    ('Vegetable Omelette', 'Fluffy omelette filled with sautéed onions, bell peppers, and any other desired vegetables. A hearty and tasty breakfast.', '1. In a bowl, whisk eggs until well beaten. 2. Heat oil or butter in a non-stick skillet over medium heat. 3. Sauté diced onions, bell peppers, and any other desired vegetables until softened. 4. Pour the beaten eggs into the skillet and let them cook until set around the edges. 5. Sprinkle shredded cheese on one side of the omelette. 6. Fold the other side of the omelette over the cheese. 7. Cook for another minute until the cheese is melted. 8. Slide the omelette onto a plate and serve hot.', 4.3, 15, 180, 'vegetable-omelette.jpg'),
    ('Quinoa Buddha Bowl', 'Nourishing bowl with cooked quinoa, roasted sweet potatoes, Brussels sprouts, and cauliflower. Drizzled with a creamy dressing.', '1. Cook quinoa according to package instructions and let it cool. 2. Preheat oven to 425°F. 3. Toss your favorite vegetables (such as sweet potatoes, Brussels sprouts, and cauliflower) with olive oil, salt, and pepper. 4. Roast the vegetables in the oven until tender and slightly caramelized. 5. In a bowl, layer cooked quinoa, roasted vegetables, leafy greens, and any desired toppings. 6. Drizzle with a creamy dressing or sauce. 7. Enjoy!', 4.7, 35, 320, 'quinoa-buddha-bowl.jpg'),
    ('Caprese Salad', 'Classic salad with ripe tomatoes, fresh mozzarella, and basil. Drizzled with olive oil and balsamic glaze.', '1. Slice ripe tomatoes and fresh mozzarella into rounds. 2. Arrange tomato and mozzarella slices on a platter. 3. Scatter fresh basil leaves over the slices. 4. Drizzle with olive oil and balsamic glaze. 5. Season with salt and pepper. 6. Serve as a refreshing salad or appetizer.', 4.5, 10, 180, 'caprese-salad.jpg'),
    ('Roasted Brussels Sprouts', 'Crispy roasted Brussels sprouts with a touch of balsamic glaze. A flavorful and nutritious side dish.', '1. Preheat oven to 425°F. 2. Trim the tough ends of Brussels sprouts and cut them in half. 3. Toss the halved Brussels sprouts with olive oil, salt, and pepper. 4. Arrange them on a baking sheet in a single layer. 5. Roast for about 20-25 minutes until the sprouts are golden and crispy. 6. Drizzle with balsamic glaze before serving. 7. Enjoy!', 4.3, 20, 150, 'roasted-brussels-sprouts.jpg'),
    ('Mediterranean Quinoa Salad', 'Bright and flavorful salad with quinoa, cucumbers, tomatoes, olives, and crumbled feta. Tossed in zesty dressing.', '1. Cook quinoa according to package instructions and let it cool. 2. In a large bowl, combine cooked quinoa, diced cucumbers, cherry tomatoes, sliced Kalamata olives, chopped fresh parsley, and crumbled feta cheese. 3. In a separate small bowl, whisk together olive oil, lemon juice, garlic, salt, and pepper to make the dressing. 4. Pour the dressing over the quinoa mixture and toss until well combined. 5. Serve chilled and enjoy!', 4.6, 25, 240, 'mediterranean-quinoa-salad.jpg'),
    ('Egg White Scramble', 'Healthy scramble made with egg whites and sautéed vegetables. A protein-rich and satisfying breakfast.', '1. In a bowl, whisk egg whites until frothy. 2. Heat a non-stick skillet over medium heat. 3. Add diced onions, bell peppers, and any other desired vegetables to the skillet. 4. Sauté until the vegetables are softened. 5. Pour the whisked egg whites into the skillet. 6. Gently scramble the eggs until cooked through. 7. Season with salt, pepper, and any preferred herbs or spices. 8. Serve hot and enjoy!', 4.2, 15, 160, 'egg-white-scramble.jpg'),
    ('Chickpea Salad', 'Protein-packed salad with chickpeas, cherry tomatoes, fresh parsley, and diced red onions. Tossed in zesty dressing.', '1. In a bowl, combine drained chickpeas, halved cherry tomatoes, diced cucumber, chopped fresh parsley, and diced red onions. 2. In a separate small bowl, whisk together olive oil, lemon juice, garlic, salt, and pepper to make the dressing. 3. Pour the dressing over the chickpea mixture and toss until well coated. 4. Serve chilled and enjoy!', 4.4, 10, 200, 'chickpea-salad.jpg'),
    ('Roasted Cauliflower', 'Crispy roasted cauliflower florets with flavorful spices. A delicious and healthy side dish or snack.', '1. Preheat oven to 425°F. 2. Break the cauliflower into florets and place them on a baking sheet. 3. Drizzle with olive oil, and sprinkle with salt, pepper, and your favorite spices (such as cumin, paprika, or garlic powder). 4. Toss the cauliflower until evenly coated. 5. Roast for about 20-25 minutes until the florets are golden and crispy. 6. Serve hot as a tasty side dish or snack.', 4.3, 25, 120, 'roasted-cauliflower.jpg');


INSERT INTO Ingredients (recipeID, ingredientName)
VALUES
    (1, 'Chicken breast'),
    (1, 'Mixed greens'),
    (1, 'Cherry tomatoes'),
    (1, 'Cucumber'),
    (2, 'Bell peppers'),
    (2, 'Quinoa'),
    (2, 'Onions'),
    (2, 'Garlic'),
    (2, 'Diced tomatoes'),
    (3, 'Salmon fillet'),
    (3, 'Assorted vegetables'),
    (3, 'Olive oil'),
    (4, 'Greek yogurt'),
    (4, 'Mixed berries'),
    (4, 'Granola'),
    (5, 'Assorted vegetables'),
    (5, 'Hummus'),
    (6, 'Lentils'),
    (6, 'Vegetable broth'),
    (6, 'Diced tomatoes'),
    (6, 'Onions'),
    (6, 'Carrots'),
    (7, 'Sweet potatoes'),
    (7, 'Olive oil'),
    (8, 'Avocado'),
    (8, 'Bread slices'),
    (9, 'Assorted vegetables'),
    (9, 'Whole wheat wrap'),
    (9, 'Hummus'),
    (10, 'Quinoa'),
    (10, 'Cucumbers'),
    (10, 'Tomatoes'),
    (11, 'Chicken breast'),
    (12, 'Frozen fruits'),
    (12, 'Yogurt'),
    (13, 'Egg whites'),
    (13, 'Assorted vegetables'),
    (14, 'Chickpeas'),
    (14, 'Cherry tomatoes'),
    (14, 'Cucumber'),
    (14, 'Fresh parsley'),
    (14, 'Red onions'),
    (15, 'Cauliflower florets'),
    (15, 'Olive oil'),
    (16, 'Leafy greens'),
    (16, 'Cucumber'),
    (16, 'Tomatoes'),
    (16, 'Olives'),
    (16, 'Feta cheese'),
    (17, 'Brussels sprouts'),
    (17, 'Olive oil'),
    (18, 'Quinoa'),
    (18, 'Roasted vegetables'),
    (18, 'Creamy dressing'),
    (19, 'Tomatoes'),
    (19, 'Mozzarella cheese'),
    (19, 'Basil'),
    (19, 'Olive oil'),
    (19, 'Balsamic glaze'),
    (20, 'Quinoa'),
    (20, 'Cucumbers'),
    (20, 'Tomatoes'),
    (20, 'Olives'),
    (20, 'Fresh parsley'),
    (20, 'Feta cheese');

INSERT INTO Comments (userID, recipeID, `comment`, rate)
VALUES
    (1, 1, 'This grilled chicken salad is delicious! The flavors are amazing.', 5),
    (1, 2, 'The quinoa-stuffed bell peppers are so flavorful and satisfying.', 4),
    (2, 3, 'Salmon with roasted vegetables is my favorite go-to dinner.', 5),
    (2, 4, 'The Greek yogurt parfait is a refreshing and healthy breakfast option.', 4),
    (3, 5, 'I love the vegetable stir-fry! It\'s packed with colorful and nutritious veggies.', 5),
    (3, 6, 'The lentil soup is a comforting and hearty dish, perfect for chilly days.', 4),
    (4, 7, 'Oven-baked sweet potato fries are my guilt-free indulgence.', 4),
    (4, 8, 'Avocado toast is my favorite quick and easy snack.', 5),
    (5, 9, 'The veggie wrap is a satisfying and filling meal for lunch.', 4),
    (5, 10, 'The quinoa salad with lemon dressing is tangy and refreshing.', 5),
    (1, 11, 'Baked chicken breast is a healthy and flavorful protein option.', 4),
    (2, 12, 'The fruit smoothie bowl is a great way to start the day with a burst of fruity flavors.', 5),
    (3, 13, 'The vegetable omelette is a delicious and nutritious breakfast choice.', 4),
    (4, 14, 'The quinoa Buddha bowl is packed with wholesome ingredients and tastes amazing.', 5),
    (5, 15, 'Caprese salad is a classic and elegant dish that never disappoints.', 5),
    (1, 5, 'I can never get enough of the vegetable stir-fry. So yummy!', 5),
    (1, 6, 'The lentil soup is my go-to comfort food on cold winter nights.', 4),
    (2, 10, 'The quinoa salad with lemon dressing is a party for the taste buds.', 5),
    (3, 14, 'The quinoa Buddha bowl is my favorite lunch option for a healthy and satisfying meal.', 5),
    (4, 15, 'Caprese salad is my all-time favorite salad. I can eat it every day!', 5);
