<!doctype html>
<html lang="en">

<style>
    .link {
        cursor: pointer;
        color: blue !important;
    }
    
    .error {
        color: red;
    }
</style>

<title>Omar Aly's Learning Project</title>

<?php

  require ("database.php");

  function getAllCustomers()
  {
      global $pdo;

      $query = $pdo->prepare("SELECT * FROM customers ORDER BY cust_lname");
      $query->execute();

      $custs = $query->fetchAll(PDO::FETCH_ASSOC);

      return $custs;
  }

  function getAllAddresses()
  {
      global $pdo;

      $query = $pdo->prepare("SELECT * FROM addresses");
      $query->execute();

      $addr = $query->fetchAll(PDO::FETCH_ASSOC);

      return $addr;
  }

  function updateInsertAddress($isUpdate)
  {
      global $pdo;

      $queryParam = array(
          "custid" => $_POST['custSelect'],
          "description" => $_POST['desc'],
          "num" => $_POST['num'],
          "street" => $_POST['street'],
          "city" => $_POST['city'],
          "prov" => $_POST['provSelect'],
          "postal" => $_POST['postal']
      );

      $queryStr = "INSERT INTO addresses (addr_cust,addr_desc,addr_number,addr_street,addr_city,addr_prov,addr_postal) VALUES(:custid,:description,:num,:street,:city,:prov,:postal)";

      if ($isUpdate == true)
      {
          $queryParam["id"] = $_POST['addr_id'];
          $queryStr = "UPDATE addresses SET addr_cust = :custid, addr_desc = :description, addr_number = :num, addr_street = :street, addr_city = :city, addr_prov = :prov, addr_postal = :postal WHERE addr_id = :id";
      }

      $query = $pdo->prepare($queryStr);
      $query->execute($queryParam);

  }

  function updateInsertCustomer($isUpdate)
  {
      global $pdo;

      $queryParam = array(
          "fname" => $_POST['fName'],
          "lname" => $_POST['lName']
      );

      $queryStr = "INSERT INTO customers (cust_fname,cust_lname) VALUES(:fname,:lname)";

      if ($isUpdate == true)
      {
          $queryParam["id"] = $_POST['cust_id'];
          $queryStr = "UPDATE customers SET cust_fname = :fname, cust_lname = :lname WHERE cust_id = :id";
      }

      $query = $pdo->prepare($queryStr);
      $query->execute($queryParam);

  }

  if (isset($_POST['addrFunc']))
  {
      switch ($_POST['addrFunc'])
      {
          case "addrAdd":

              updateInsertAddress(false);

          break;

          case "addrEdit":

              updateInsertAddress(true);

          break;

      }
  }

  if (isset($_POST['custFunc']))
  {
      switch ($_POST['custFunc'])
      {
          case "custAdd":

              updateInsertCustomer(false);

          break;

          case "custEdit":

              updateInsertCustomer(true);

          break;

      }
  }

  ?>

    <head></head>

    <body>

        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

        <br/>
        <h1 class="text-center">Our Customers</h1>

        <div class="text-center">
            <button type="button" onclick="editCust(-1)" class="btn btn-outline-primary" data-toggle="modal" data-target="#add-customer">Add Customer</button>
            <button type="button" onclick="editAddr(-1)" class="btn btn-outline-primary" data-toggle="modal" data-target="#add-address">Add Address</button>
        </div>

        <div class="modal fade" id="add-customer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="custLabel">Add New Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="POST">
                        <input type='hidden' id='custFunc' name='custFunc' value='custAdd' />
                        <input type='hidden' id='custID' name='cust_id' value='' />
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="fname" class="col-sm-3 col-form-label">First Name<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="fName" id="fName" onkeyup="customerStoppedTyping()">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lname" class="col-sm-3 col-form-label">Last Name<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="lName" id="lName" onkeyup="customerStoppedTyping()">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <span id="requiredCust" class="error mr-auto">*Required</span>
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
                        <h5 class="modal-title" id="addrLabel">Add an Address</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="POST">
                        <input type='hidden' id='addrFunc' name='addrFunc' value='addrAdd' />
                        <input type='hidden' id='addrID' name='addr_id' value='' />
                        <div class="modal-body">

                            <div class="form-group row">
                                <label for="fname" class="col-sm-3 col-form-label">Customer<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <select class=form-control name="custSelect" id="custSelect" onchange="addrStoppedTyping()">
                                        <option disabled selected value hidden>--- Select a Customer ---</option>

                                        <?php

                                        $custs = getAllCustomers();

                                        foreach ($custs as $c){
                                          echo "<option  value='".$c['cust_id']."'>".$c['cust_lname'].", ".$c['cust_fname']."</option>";
                                        }

                                        ?>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="desc" class="col-sm-3 col-form-label">Description<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="desc" id="desc" onkeyup="addrStoppedTyping()">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="num" class="col-sm-3 col-form-label">Number<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="num" id="num" onkeyup="addrStoppedTyping()">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="street" class="col-sm-3 col-form-label">Street<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="street" id="street" onkeyup="addrStoppedTyping()">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="city" class="col-sm-3 col-form-label">City<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="city" id="city" onkeyup="addrStoppedTyping()">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="prov" class="col-sm-3 col-form-label">Province<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <select class=form-control name="provSelect" id="provSelect" onchange="addrStoppedTyping()">
                                        <option disabled selected value='' hidden>--- Select a Province ---</option>
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
                                <label for="city" class="col-sm-3 col-form-label">Postal Code<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="postal" id="postal" onkeyup="addrStoppedTyping()">
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <span id="requiredAddr" class="error mr-auto">*Required</span>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" id="addrButton" class="btn btn-primary">Add New Address</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>

        <?php

        $addr = getAllAddresses();

        echo"<div class='container'>";
        echo"<ul id='customer-list'>";

        foreach ($custs as $row){

        echo "<li><a class='link' onclick='editCust(".$row['cust_id'].")' >".$row['cust_lname'].", ".$row['cust_fname']."</a> -- ";

          foreach ($addr as $desc){

            if ($row['cust_id'] == $desc['addr_cust']){

              echo"<a class='link' onclick='editAddr(".$desc['addr_id'].")' > ".$desc['addr_desc']."</a>";

            }
          }

          echo"</li>"; 
      }

      echo"</ul>";
      echo"</div>";

      ?>
    </body>

    <!-- YOU NEED THESE FILES -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

  <script>

    var customers = <?php echo(JSON_encode($custs)); ?>;
    var addresses = <?php echo(JSON_encode($addr)); ?> ;

    var fmodalCust = document.getElementById("fName");
    var lmodalCust = document.getElementById("lName");
    var custButton = document.getElementById("custButton");
    var addrButton = document.getElementById("addrButton");
    var addrFunc = document.getElementById("addrFunc");
    var custFunc = document.getElementById("custFunc");

    function stoppedTyping(inputIds, button) {

        var buttonShouldBeDisabled = false;
        for (var index = 0; index < inputIds.length; index++) {
            if (document.getElementById(inputIds[index]).value.length == 0) {
                buttonShouldBeDisabled = true;
            }
        }

        document.getElementById(button).disabled = buttonShouldBeDisabled;

    }

    function customerStoppedTyping() {

        var inputIds = ['lName', 'fName'];
        var button = "custButton";
        stoppedTyping(inputIds, button);

    }

    function addrStoppedTyping() {
        var inputIds = ['custSelect', 'desc', 'num', 'street', 'city', 'provSelect', 'postal'];
        var button = "addrButton";
        stoppedTyping(inputIds, button);

    }

    function editCust(custID) {

        if (custID == -1) {

            document.getElementById("custLabel").innerHTML = "Add New Customer";
            document.getElementById("custButton").innerHTML = "Add New Customer";

            custFunc.value = "custAdd";

             //empty all feilds in a modal for adding a new customer

            document.getElementById("custID").value = -1;
            document.getElementById("fName").value = "";
            document.getElementById("lName").value = "";

            custButton.disabled = true;

        }

         else {

            for (x = 0; x < customers.length; x++) {
 
                if (customers[x].cust_id == custID) {

                    document.getElementById("custLabel").innerHTML = "Update Customer";

                    document.getElementById("custID").value = customers[x].cust_id;

                    custFunc.value = "custEdit";

                    //updates all fields for editing

                    fmodalCust.innerHTML = escapeHTML(customers[x].cust_fname);
                    fmodalCust.value = escapeHTML(customers[x].cust_fname);

                    lmodalCust.innerHTML = escapeHTML(customers[x].cust_lname);
                    lmodalCust.value = escapeHTML(customers[x].cust_lname);

                    custButton.innerHTML = "Update Customer";

                    //sets submit button enabled by default when editing since fields are already populated
                    custButton.disabled = false;

                    $("#add-customer").modal("show");

                }
            }
        }
    }

    function editAddr(addrID) {

        if (addrID == -1) {

          //empty all feilds in a modal for adding a new address

            document.getElementById("addrLabel").innerHTML = "Add New Address";
            document.getElementById("addrButton").innerHTML = "Add New Address";
            addrFunc.value = "addrAdd";

            document.getElementById("custSelect").value = "";
            document.getElementById("addrID").value = -1;
            document.getElementById("desc").value = "";
            document.getElementById("num").value = "";
            document.getElementById("street").value = "";
            document.getElementById("city").value = "";
            document.getElementById("provSelect").value = "";
            document.getElementById("postal").value = "";

            addrButton.disabled = true;
        } else {

            for (x = 0; x < addresses.length; x++) {

                if (addresses[x].addr_id == addrID) {

                    document.getElementById("addrLabel").innerHTML = "Update Address";
                    document.getElementById("addrID").value = addresses[x].addr_id;
                    addrFunc.value = "addrEdit";

                    //updates all fields for editing

                    document.getElementById("custSelect").value = escapeHTML(addresses[x].addr_cust);
                    document.getElementById("desc").value = escapeHTML(addresses[x].addr_desc);
                    document.getElementById("num").value = escapeHTML(addresses[x].addr_number);
                    document.getElementById("street").value = escapeHTML(addresses[x].addr_street);
                    document.getElementById("city").value = escapeHTML(addresses[x].addr_city);
                    document.getElementById("provSelect").value = escapeHTML(addresses[x].addr_prov);
                    document.getElementById("postal").value = escapeHTML(addresses[x].addr_postal);

                    addrButton.innerHTML = "Update Address";

                    addrButton.disabled = false;

                    $("#add-address").modal("show");

                }
            }
        }
    }

    //Replaces HTML special char codes with respective char
    function escapeHTML(text) {

        return text
            .replace(/&amp;/g, '&')
            .replace(/&lt;/g, '<')
            .replace(/&gt;/g, '>')
            .replace(/&quot;/g, '"')
            .replace(/&#039;/g, "'");
    }
  </script>

</html>