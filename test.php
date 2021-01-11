<!doctype html>
<html lang="en">

    <style>
          .link {
            cursor: pointer;
            color: blue !important;
          }
    </style>

      <title>Omar Aly's Learning Project</title>

      <script language="JavaScript" >

      function callServer (methodName, parameters, callback) {
        var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                  callback(this.responseText);
              }
          };

          xmlhttp.open("POST", "api.php", true);
          xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          xmlhttp.send('methodName='+methodName+'&'+parameters);
      }
      </script>

      <?php

      require("database.php");

      if(isset($_POST['fName']) && isset($_POST['lName'])){

      $query = $pdo->prepare("INSERT INTO customers (cust_fname,cust_lname) VALUES(:fname,:lname)");
      $query->execute(array(
        "fname" => $_POST['fName'],
        "lname" => $_POST['lName']
        ));
      $results = $query->fetchAll(PDO::FETCH_ASSOC);
        }

      if(isset($_POST['provSelect'])){

    // echo"<pre>";print_r($_POST);echo"</pre>";

      $query = $pdo->prepare("INSERT INTO addresses (addr_cust,addr_desc,addr_number,addr_street,addr_city,addr_prov,addr_postal) VALUES(:custid,:description,:num,:street,:city,:prov,:postal)");
      $query->execute(array(
        "custid" => $_POST['custSelect'],
        "description" => $_POST['desc'],
        "num" => $_POST['num'],
        "street" => $_POST['street'],
        "city" => $_POST['city'],
        "prov" => $_POST['provSelect'],
        "postal" => $_POST['postal']
        ));
      $results = $query->fetchAll(PDO::FETCH_ASSOC);
        }

      ?>

      <head>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script> 
      </head>
  <body>
  
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

        <br/>
        <h1 class="text-center">Our Customers</h1>
        

      <div class="text-center"> 
        <button type="button" class="btn btn-outline-primary"  data-toggle="modal" data-target="#add-customer">Add Customer</button>
        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#add-address">Add Address</button>
      </div>

    <div class="modal fade" id="add-customer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New Customer</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

      <form method="POST">
      <div class="modal-body">
        <div class="form-group row">
          <label for="fname" class="col-sm-3 col-form-label">First Name</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="fName" id="fName">
          </div>
        </div>
        <div class="form-group row">
          <label for="lname" class="col-sm-3 col-form-label">Last Name</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="lName" id="lName">
          </div>
        </div>
      </div>
              
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="custButton">Add Customer</button>
      </div>
      </form> 
      </div>
    </div>
    </div>

    <div class="modal fade" id="add-address" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add an Address</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <form method="POST">
          <div class="modal-body">

            <div class="form-group row">
                <label for="fname" class="col-sm-3 col-form-label">Customer</label>
                <div class="col-sm-9">
                  <select class =form-control name="custSelect" id="custSelect">
                    <option hidden>---Please Select a Customer---</option>

                    <?php

                      $query  = $pdo->prepare("SELECT * FROM customers ORDER BY cust_lname");
                      $query->execute();

                      $results = $query->fetchAll(PDO::FETCH_ASSOC);

                      foreach ($results as $c){
                        echo "<option value='".$c['cust_id']."'>".$c['cust_lname'].", ".$c['cust_fname']."</option>";
                      }

                    ?>
                  </select>
              </div>
            </div>

            <div class="form-group row">
                <label for="desc" class="col-sm-3 col-form-label">Description</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="desc" id="desc">
                </div>
            </div>
            <div class="form-group row">
                <label for="num" class="col-sm-3 col-form-label">Number</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="num" id="num">
                </div>
            </div>
            <div class="form-group row">
                <label for="street" class="col-sm-3 col-form-label">Street</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="street" id="street">
                </div>
            </div>
            <div class="form-group row">
                <label for="city" class="col-sm-3 col-form-label">City</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="city" id="city">
                </div>
            </div>

            <div class="form-group row">
                <label for="prov" class="col-sm-3 col-form-label">Province</label>
                <div class="col-sm-9">
                  <select class =form-control name="provSelect" id="provSelect">
                    <option hidden>---Select a Province---</option>
                    <option value="AB">Alberta</option>
                    <option value="BC">British Columbia</option>
                    <option value="MB">Manitoba</option>
                    <option value="NB">New Bruswick</option>
                    <option value="NL">Newfoundland and Labrador</option>
                    <option value="NS">Nova Scotia</option>
                    <option value="NT">Northwest Territories</option>
                    <option value="NU">Nunavut</option>
                    <option value="ON">Ontario</option>
                    <option value="PE">PEI</option>
                    <option value="QC">Quebec</option>
                    <option value="SK">Saskatchewan</option>
                    <option value="YK">Yukon</option>
                  </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="city" class="col-sm-3 col-form-label">Postal Code</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="postal" id="postal">
                </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add Address</button>
          </form>
          </div>
        </div>
      </div>
    </div>

    <ul id="customerList">
    </ul>
    <script language="javascript">


        callServer ('list', 'Omar', function (response) {
          var customers  = JSON.parse (response);

          var ul = document.getElementById ('customerList');

          for (var index =0; index < customers.length; index++) {
            var li = document.createElement('li');
            li.innerHTML = '<a class="link" onclick="editCust(' + customers[index].cust_id + ')">' + customers[index].cust_lname + ', ' + customers[index].cust_fname + '</a> -- ';
            ul.appendChild (li);
          }
        });

    </script>

  </body>


  <!-- YOU NEED THESE FILES -->
  


  <script>

  
    
    function editCust(custID){

      }


}



 

  </script>




</html>

