<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="styles.scss">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <title>Currency</title>
</head>

<body>
   <header>
      <h1>
         Global Base
      </h1>
      <form action="index3.php" method="POST">
         <input type="text" name="search" placeholder="Search country...">
         <button type="submit" value="submit"><i class="fa fa-search"></i></button>
      </form>
   </header>

   <div>
      <p><a href="index2.php">See countries list</a></p>
      <?php
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "mydb";

      //create connection
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check connection
      if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
      }
      echo "<p class='pass'>Database connection successful</p>";

      //input table name into variable
      $tableName_2 = 'CurrencyInfo';

      //table fields
      $createTable_2 = "CREATE TABLE $tableName_2 (
      id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      iso_code VARCHAR(30) NOT NULL,
      iso_numeric_code VARCHAR(30) NOT NULL,
      common_name VARCHAR(30) NOT NULL,
      official_name VARCHAR(30) NOT NULL,
      symbol VARCHAR(30) NOT NULL
      )";

      //check and create 2st table
      if ($result_2 = $conn->query("SHOW TABLES LIKE '" . $tableName_2 . "'")) {
         if ($result_2->num_rows == 1) {
            echo "<p class='pass'>$tableName_2 table is available";
         } else {
            echo "<p class='error'>$tableName_2 table does not exist</p>";
            echo "<p>Creating $tableName_2 table - - - </p>";
            if ($conn->query($createTable_2) === TRUE) {
               insertData_2();
               echo "<p class='pass'>Country Info table created successfully</p>";
            }
         }
      }

      //function to add data to currency table
      function insertData_2()
      {
         global $conn;
         global $tableName_2;
         if (($opn = fopen("01-Currencies.csv", "r")) !== FALSE) {
            fgetcsv($opn);
            while (($lineData = fgetcsv($opn, 1000, ",")) !== FALSE) {
               $insert = "INSERT INTO $tableName_2 (iso_code, iso_numeric_code, common_name, official_name, symbol) 
               VALUES ('$lineData[0]', '$lineData[1]', '$lineData[2]', '$lineData[3]', '$lineData[4]')";

               if ($conn->query($insert) === TRUE) {
                  echo "<p>New record created successfully</p>";
               } else {
                  echo "Error: " . $insert . "<br>" . $conn->error;
               }
            }
            fclose($opn);
         }
      }
      ?>

      <table id="currency-table">
         <thead>
            <tr>
               <th>ISO CODE</th>
               <th>ISO NUMERIC CODE</th>
               <th>COMMON NAME</th>
               <th>OFFICIAL NAME</th>
               <th>SYMBOL NAME</th>
            </tr>
         </thead>
         <tbody>
            <?php
            if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
               $page_no = $_GET['page_no'];
            } else {
               $page_no = 1;
            }
            $total_records_per_page = 10;
            $offset = ($page_no - 1) * $total_records_per_page;
            $previous_page = $page_no - 1;
            $next_page = $page_no + 1;
            $adjacents = "2";

            $result_count = mysqli_query(
               $conn,
               "SELECT COUNT(*) As total_records FROM `$tableName_2`"
            );
            $total_records = mysqli_fetch_array($result_count);
            $total_records = $total_records['total_records'];
            $total_no_of_pages = ceil($total_records / $total_records_per_page);
            $seconnd_last = $total_no_of_pages - 1; // total pages minus 1

            $result = mysqli_query(
               $conn,
               "SELECT * FROM `$tableName_2` LIMIT $offset, $total_records_per_page"
            );
            while ($row = mysqli_fetch_array($result)) {
               $val1 = $row['iso_code'];
               $val2 = $row['iso_numeric_code'];
               $val3 = $row['common_name'];
               $val4 = $row['official_name'];
               $val5 = $row['symbol'];
               echo "<tr> <td>" . $val1 . "</td> <td>" . $val2 . "</td> <td>" . $val3 . "</td> <td>" . $val4 . "</td> <td>" . $val5 . "</td> </tr>";
            }
            $conn->close();
            ?>
         </tbody>
      </table>

      <div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
         <strong>Page <?php echo $page_no . " of " . $total_no_of_pages; ?></strong>
      </div>

      <ul>
         <?php if ($page_no > 1) {
            echo "<li><a href='?page_no=1'>First Page</a></li>";
         } ?>

         <li <?php if ($page_no <= 1) {
                  echo "class='disabled'";
               } ?>>
            <a <?php if ($page_no > 1) {
                  echo "href='?page_no=$previous_page'";
               } ?>>Previous</a>
         </li>

         <li <?php if ($page_no >= $total_no_of_pages) {
                  echo "class='disabled'";
               } ?>>
            <a <?php if ($page_no < $total_no_of_pages) {
                  echo "href='?page_no=$next_page'";
               } ?>>Next</a>
         </li>

         <?php if ($page_no < $total_no_of_pages) {
            echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
         } ?>
      </ul>
   </div>
</body>

</html>