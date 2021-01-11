<?php

      require("database.php");
    
      if (isset($_POST['methodName'])) {

        if ($_POST['methodName'] == 'list') {

            $query  = $pdo->prepare("SELECT * FROM customers ORDER BY cust_lname");
            $query->execute();

            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            echo JSON_encode($results);
      }

    }

?>
