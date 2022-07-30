<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="styles.scss">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <title>Countries</title>
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
      <p><a href="index.php">See currency list</a></p>
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
      $tableName = 'CountryInfo';

      //table fields
      $createTable = "CREATE TABLE $tableName (
      id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      continent_code VARCHAR(30) NOT NULL,
      currency_code VARCHAR(30) NOT NULL,
      iso2_code VARCHAR(30) NOT NULL,
      iso3_code VARCHAR(30) NOT NULL,
      iso_numeric_code VARCHAR(30) NOT NULL,
      fips_code VARCHAR(30) NOT NULL,
      calling_code VARCHAR(30) NOT NULL,
      common_name VARCHAR(30) NOT NULL,
      official_name VARCHAR(30) NOT NULL,
      endonym VARCHAR(30) NOT NULL,
      demonym VARCHAR(30) NOT NULL
      )";

      //check and create 1st table
      if ($result = $conn->query("SHOW TABLES LIKE '" . $tableName . "'")) {
         if ($result->num_rows == 1) {
            echo "<p class='pass'>$tableName table is available</p>";
            //insertData();
         } else {
            echo "<p class='error'>Table does not exist</p>";
            echo "<p class='pass'>Creating table - - -  </p>";
            if ($conn->query($createTable) === TRUE) {
               insertData();
               echo "<p class='pass'>Country Info table created successfully</p>";
            }
         }
      }

      function insertData()
      {
         global $conn;
         global $tableName;
         if (($opn = fopen("02-Countries.csv", "r")) !== FALSE) {
            fgetcsv($opn);
            while (($lineData = fgetcsv($opn, 1000, ",")) !== FALSE) {
               $insert = "INSERT INTO $tableName (continent_code, currency_code, iso2_code, iso3_code, iso_numeric_code, fips_code, calling_code, common_name, official_name, endonym, demonym) 
               VALUES ('$lineData[0]', '$lineData[1]', '$lineData[2]', '$lineData[3]', '$lineData[4]', '$lineData[5]', '$lineData[6]', '$lineData[7]', '$lineData[8]', '$lineData[9]','$lineData[10]')";

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
               <th>CONTINENT CODE</th>
               <th>CURRENCY CODE</th>
               <th>ISO2 CODE</th>
               <th>ISO3 CODE</th>
               <th>ISO NUMERIC CODE</th>
               <th>FIPS CODE</th>
               <th>CALLING CODE</th>
               <th>COMMON NAME</th>
               <th>OFFICIAL NAME</th>
               <th>ENDONYM</th>
               <th>DEMONYM</th>
            </tr>
         </thead>
         <tbody>
            <?php
            if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
               $page_no = $_GET['page_no'];
            } else {
               $page_no = 1;
            }
            $total_records_per_page = 8;
            $offset = ($page_no - 1) * $total_records_per_page;
            $previous_page = $page_no - 1;
            $next_page = $page_no + 1;
            $adjacents = "2";

            $result_count = mysqli_query(
               $conn,
               "SELECT COUNT(*) As total_records FROM `$tableName`"
            );
            $total_records = mysqli_fetch_array($result_count);
            $total_records = $total_records['total_records'];
            $total_no_of_pages = ceil($total_records / $total_records_per_page);
            $seconnd_last = $total_no_of_pages - 1; // total pages minus 1

            $result = mysqli_query(
               $conn,
               "SELECT * FROM `$tableName` LIMIT $offset, $total_records_per_page"
            );
            while ($row = mysqli_fetch_array($result)) {
               $val1 = $row['continent_code'];
               $val2 = $row['currency_code'];
               $val3 = $row['iso2_code'];
               $val4 = $row['iso3_code'];
               $val5 = $row['iso_numeric_code'];
               $val6 = $row['fips_code'];
               $val7 = $row['calling_code'];
               $val8 = $row['common_name'];
               $val9 = $row['official_name'];
               $val10 = $row['endonym'];
               $val11 = $row['demonym'];
               echo "<tr> <td>" . $val1 . "</td> <td>" . $val2 . "</td> <td>" . $val3 . "</td> <td>" . $val4 . "</td> <td>" . $val5 . "</td> <td>" . $val6 . "</td>  <td>" . $val7 . "</td>  <td>" . $val8 . "</td>  <td>" . $val9 . "</td>  <td>" . $val10 . "</td>  <td>" . $val11 . "</td> </tr>";
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