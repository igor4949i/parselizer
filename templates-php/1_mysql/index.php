<?php

$pdo = require 'Connection.php';

/////////////////////////////////////////////            1                 /////////////////////////
// // create a table using PDO
// $statements = [
//     'CREATE TABLE authors( 
//         author_id   INT AUTO_INCREMENT,
//         first_name  VARCHAR(100) NOT NULL, 
//         middle_name VARCHAR(50) NULL, 
//         last_name   VARCHAR(100) NULL,
//         PRIMARY KEY(author_id)
//     );',
//     'CREATE TABLE book_authors (
//         book_id   INT NOT NULL, 
//         author_id INT NOT NULL, 
//         PRIMARY KEY(book_id, author_id), 
//         CONSTRAINT fk_book 
//             FOREIGN KEY(book_id) 
//             REFERENCES books(book_id) 
//             ON DELETE CASCADE, 
//             CONSTRAINT fk_author 
//                 FOREIGN KEY(author_id) 
//                 REFERENCES authors(author_id) 
//                 ON DELETE CASCADE
//     )'
// ];

// // execute SQL statements
// foreach ($statements as $statement) {
// 	$pdo->exec($statement);
// }
/////////////////////////////////////////////            End 1                 /////////////////////////



/////////////////////////////////////////////            2                 /////////////////////////

// // Inserting a row into a table example
// $name = 'Macmillan';

// $sql = 'INSERT INTO publishers(name) VALUES(:name)';
// $statement = $pdo->prepare($sql);

// $statement->execute([
// 	':name' => $name
// ]);

// // print last inset id
// $publisher_id = $pdo->lastInsertId();
// echo 'The publisher id ' . $publisher_id . ' was inserted';
/////////////////////////////////////////////            End 2                 /////////////////////////



/////////////////////////////////////////////            3                 /////////////////////////
// // Inserting multiple rows into a table example
// $names = [
//     'Penguin/Random House',
//     'Hachette Book Group',
//     'Harper Collins',
//     'Simon and Schuster'
// ];

// $sql = 'INSERT INTO publishers(name) VALUES(:name)';

// $statement = $pdo->prepare($sql);


// foreach ($names as $name) {
//     $statement->execute([
//         ':name' => $name
//     ]);
// }

/////////////////////////////////////////////            End 3                 /////////////////////////



/////////////////////////////////////////////            4                 /////////////////////////
// // Updating data from PHP using PDO
// $publisher = [
// 	'publisher_id' => 1,
// 	'name' => 'McGraw-Hill Education'
// ];

// $sql = 'UPDATE publishers
//         SET name = :name
//         WHERE publisher_id = :publisher_id';

// // prepare statement
// $statement = $pdo->prepare($sql);

// // bind params
// $statement->bindParam(':publisher_id', $publisher['publisher_id'], PDO::PARAM_INT);
// $statement->bindParam(':name', $publisher['name']);

// // execute the UPDATE statment
// if ($statement->execute()) {
// 	echo 'The publisher has been updated successfully!';
// }
/////////////////////////////////////////////            End 4                 /////////////////////////




/////////////////////////////////////////////            5                 /////////////////////////
// // Selecting data from a table
// $sql = 'SELECT publisher_id, name 
// 		FROM publishers';

// $statement = $pdo->query($sql);

// // get all publishers
// $publishers = $statement->fetchAll(PDO::FETCH_ASSOC);

// if ($publishers) {
// 	// show the publishers
// 	foreach ($publishers as $publisher) {
// 		echo $publisher['name'] . '<br>';
// 	}
// }

/////////////////////////////////////////////            End 5                 /////////////////////////




/////////////////////////////////////////////            6                 /////////////////////////
// Using a prepared statement to query data
// $publisher_id = 1;
// $sql = 'SELECT publisher_id, name 
// 		FROM publishers
//         WHERE publisher_id = :publisher_id';

// $statement = $pdo->prepare($sql);
// $statement->bindParam(':publisher_id', $publisher_id, PDO::PARAM_INT);
// $statement->execute();
// $publisher = $statement->fetch(PDO::FETCH_ASSOC);

// if ($publisher) {
// 	echo $publisher['publisher_id'] . ' - ' . $publisher['name'];
// } else {
// 	echo "The publisher with id $publisher_id was not found.";
// }

/////////////////////////////////////////////            End 6                 /////////////////////////


/////////////////////////////////////////////            7                 /////////////////////////
// // Deleting data from a table
// $publisher_id = 2;
// $sql = 'DELETE FROM publishers
//         WHERE publisher_id = :publisher_id';

// $statement = $pdo->prepare($sql);
// $statement->bindParam(':publisher_id', $publisher_id, PDO::PARAM_INT);

// if ($statement->execute()) {
//     echo 'publisher id ' . $publisher_id . ' was deleted successfully.';
// }

/////////////////////////////////////////////            End 7                 /////////////////////////




////////////////////////////////////
////////////////////////////////////                    Fetching data
////////////////////////////////////



/////////////////////////////////////////////            8                 /////////////////////////
// // fetch() – fetch a row from a result set associated with a PDOStatement object.
// $sql = 'SELECT publisher_id, name FROM publishers';
// $statement = $pdo->query($sql);
// while (($row = $statement->fetch(PDO::FETCH_ASSOC)) !== false) {
//     echo $row['name'] . '<br>';
// }
/////////////////////////////////////////////            End 8                 /////////////////////////


/////////////////////////////////////////////            9                 /////////////////////////
// // Using the fetch() method with a prepared statement
// $sql = 'SELECT publisher_id, name
//         FROM publishers 
//         WHERE publisher_id =:publisher_id';
// // prepare the query for execution
// $statement = $pdo->prepare($sql);
// $statement->execute([
//     ':publisher_id' => 5
// ]);
// // fetch the next row
// while (($row = $statement->fetch(PDO::FETCH_ASSOC)) !== false) {
//     echo $row['name'] . PHP_EOL;
// }
/////////////////////////////////////////////            End 9                 /////////////////////////



/////////////////////////////////////////////            10                 /////////////////////////
// // fetchAll() – fetch all rows from a result set object into an array.
// $sql = 'SELECT publisher_id, name 
//         FROM publishers
//         WHERE publisher_id > :publisher_id';
// // execute a query
// $statement = $pdo->prepare($sql);
// $statement->execute([
//     ':publisher_id' => 7
// ]);
// // fetch all rows
// $publishers = $statement->fetchAll(PDO::FETCH_ASSOC);
// // display the publishers
// foreach ($publishers as $publisher) {
//     echo $publisher['publisher_id'] . '.' . $publisher['name'] . '<br>';
// }
/////////////////////////////////////////////            End 10                 /////////////////////////
