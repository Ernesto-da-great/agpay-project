<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="styles.scss">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <title>Search result</title>
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
      <p><a href="index.php">Home</a></p>
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

      $search_val = $_POST['search'];
      $tableName = 'CountryInfo';
      $tableName_2 = 'CurrencyInfo';
      $sql = "SELECT continent_code, currency_code, iso2_code, iso3_code, iso_numeric_code, fips_code, calling_code, common_name, official_name, endonym, demonym FROM $tableName WHERE common_name = '$search_val'";
      $sql_2 = "SELECT iso_code, iso_numeric_code, common_name, official_name, symbol FROM $tableName_2 WHERE common_name = '$search_val'";
      $result = $conn->query($sql);
      $result_2 = $conn->query($sql_2);

      $conn->close();
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
         <?php
         if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
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
         } else {
            echo "<br><p>0 results</p>";
         }

         ?>
         <table id="currency-table">
            <tbody>
               <thead>
                  <tr>
                     <th>ISO CODE</th>
                     <th>ISO NUMERIC CODE</th>
                     <th>COMMON NAME</th>
                     <th>OFFICIAL NAME</th>
                     <th>SYMBOL NAME</th>
                  </tr>
               </thead>
               <?php
               if ($result_2->num_rows > 0) {
                  // output data of each row
                  while ($row = $result_2->fetch_assoc()) {
                     $val_1 = $row['iso_code'];
                     $val_2 = $row['iso_numeric_code'];
                     $val_3 = $row['common_name'];
                     $val_4 = $row['official_name'];
                     $val_5 = $row['symbol'];
                     echo "<tr> <td>" . $val_1 . "</td> <td>" . $val_2 . "</td> <td>" . $val_3 . "</td> <td>" . $val_4 . "</td> <td>" . $val_5 . "</td> </tr>";
                  }
               } else {
                  echo "<p>0 results</p>";
               }

               ?>
            <tbody>
         </table>
   </div>
</body>

</html>