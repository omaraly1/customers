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

    //function that gets all the customers data using an SQL query

    function getAllCustomers() {

        //"global" allows access to the PDO variable within this function, which is declared in database.php 

        global $pdo;

        //gets customer data sorted alphabetically by last name

        $query = $pdo->prepare("SELECT * FROM customers ORDER BY cust_lname");
        $query->execute();

        $custs = $query->fetchAll(PDO::FETCH_ASSOC);

        return $custs;
    }

    //function that gets all the addresses data using an SQL query

    function getAllAddresses() {
      
        global $pdo;

        $query = $pdo->prepare("SELECT * FROM addresses");
        $query->execute();

        $addr = $query->fetchAll(PDO::FETCH_ASSOC);

        return $addr;
    }

    //function that either updates or inserts customer data given boolean to determine if updating or inserting

    function updateInsertCustomer($isUpdate) {

        global $pdo;

        $queryParam = array(
            "fname" => $_POST['fName'],
            "lname" => $_POST['lName']
        );

        $queryStr = "INSERT INTO customers (cust_fname,cust_lname) VALUES(:fname,:lname)";

        //if the query is an update query, it adds cust_id into the excute array to allow the appropriate customer to be edited

        if ($isUpdate == true) {

            $queryParam["id"] = $_POST['cust_id'];
            $queryStr = "UPDATE customers SET cust_fname = :fname, cust_lname = :lname WHERE cust_id = :id";
        }

        $query = $pdo->prepare($queryStr);
        $query->execute($queryParam);

    }

    //function that either updates or inserts addresses data given boolean to determine if updating or inserting

    function updateInsertAddress($isUpdate) {

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

        //if the query is an update query, it adds addr_id into the excute array to allow the appropriate address to be edited 

        if ($isUpdate == true) {

            $queryParam["id"] = $_POST['addr_id'];
            $queryStr = "UPDATE addresses SET addr_cust = :custid, addr_desc = :description, addr_number = :num, addr_street = :street, addr_city = :city, addr_prov = :prov, addr_postal = :postal WHERE addr_id = :id";
        }

        $query = $pdo->prepare($queryStr);
        $query->execute($queryParam);

    }
    
    //checks if postFunc was sent (which means POST array is populated), so checks which function it is to either update or insert for address or customer

    if (isset($_POST['postFunc'])) {

        switch ($_POST['postFunc']) {

            case "addrAdd":

                updateInsertAddress(false);

            break;

            case "addrEdit":

                updateInsertAddress(true);

            break;

            case "custAdd":

                updateInsertCustomer(false);

            break;

            case "custEdit":

                updateInsertCustomer(true);

            break;

        }
    }

    //get customer and address data from the database

    $custs = getAllCustomers();
    $addr = getAllAddresses();

    ?>

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    </head>

    <body>
        <br/>
        <h1 class="text-center">Our Customers</h1>

        <div class="text-center">
            <button type="button" onclick="editCust(-1)" class="btn btn-outline-primary" data-toggle="modal" data-target="#add-customer">Add Customer</button>
            <button type="button" onclick="editAddr(-1)" class="btn btn-outline-primary" data-toggle="modal" data-target="#add-address">Add Address</button>
        </div>

        <!-- Customer Modal -->
        <!-- max length set for each input field respective to database limits -->
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
                        <input type='hidden' id='custFunc' name='postFunc' value='custAdd' />
                        <input type='hidden' id='custID' name='cust_id' value='' />
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="fname" class="col-sm-3 col-form-label">First Name<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="128" name="fName" id="fName" onkeyup="customerStoppedTyping()">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lname" class="col-sm-3 col-form-label">Last Name<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="128" name="lName" id="lName" onkeyup="customerStoppedTyping()">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <span class="error mr-auto">*Required</span>
                            <button type="submit" class="btn btn-primary" id="custButton">Add Customer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Address Modal -->
        <!-- max length set for each input field respective to database limits -->
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
                        <input type='hidden' id='addrFunc' name='postFunc' value='addrAdd' />
                        <input type='hidden' id='addrID' name='addr_id' value='' />
                        <div class="modal-body">

                            <div class="form-group row">
                                <label for="fname" class="col-sm-3 col-form-label">Customer<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <select class=form-control name="custSelect" id="custSelect" onchange="addrStoppedTyping()">
                                        <option disabled selected value hidden>--- Select a Customer ---</option>

                                        <?php

                                        //using a foreach loop, it appends each customer as an option in the select option in the address modal

                                        foreach ($custs as $custOption) {

                                          echo "<option value='".$custOption['cust_id']."'>".$custOption['cust_lname'].", ".$custOption['cust_fname']."</option>";

                                        }

                                        ?>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="desc" class="col-sm-3 col-form-label">Description<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="128" name="desc" id="desc" onkeyup="addrStoppedTyping()">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="num" class="col-sm-3 col-form-label">Number<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="32" name="num" id="num" onkeyup="addrStoppedTyping()">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="street" class="col-sm-3 col-form-label">Street<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="128" name="street" id="street" onkeyup="addrStoppedTyping()">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="city" class="col-sm-3 col-form-label">City<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" maxlength="64" name="city" id="city" onkeyup="addrStoppedTyping()">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="prov" class="col-sm-3 col-form-label">Province<span class="error">*</span></label>
                                <div class="col-sm-9">
                                    <select class=form-control name="provSelect" id="provSelect" onchange="addrStoppedTyping()">
                                        <option disabled selected value hidden>--- Select a Province ---</option>
                                        <option value="AB">Alberta</option>
                                        <option value="BC">British Columbia</option>
                                        <option value="MB">Manitoba</option>
                                        <option value="NB">New Brunswick</option>
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
                                    <input type="text" class="form-control" maxlength="16" name="postal" id="postal" onkeyup="addrStoppedTyping()">
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <span class="error mr-auto">*Required</span>
                            <button type="submit" id="addrButton" class="btn btn-primary">Add New Address</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>

        <?php

        //print the customers in an unordered list, checks if each customer has an address associated
        //with their id, and if so, prints the addresses' desciption(s) beside their name

        echo"<div class='container'>";

        echo"<ul>";

        foreach ($custs as $listItem) {

        echo "<li><a class='link' onclick='editCust(".$listItem['cust_id'].")' >".$listItem['cust_lname'].", ".$listItem['cust_fname']."</a> -- ";

          foreach ($addr as $desc) {

            if ($listItem['cust_id'] == $desc['addr_cust']) {

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

    var customers = <?php echo(JSON_encode($custs));?>;
    var addresses = <?php echo(JSON_encode($addr));?>;

    var fmodalCust = document.getElementById("fName");
    var lmodalCust = document.getElementById("lName");

    var custButton = document.getElementById("custButton");
    var addrButton = document.getElementById("addrButton");

    var addrFunc = document.getElementById("addrFunc");
    var custFunc = document.getElementById("custFunc");

    //Replaces HTML special char codes with respective char

    function escapeHTML(text) { 
      
      return text
          .replace(/&amp;/g, '&')
          .replace(/&lt;/g, '<')
          .replace(/&gt;/g, '>')
          .replace(/&quot;/g, '"')
          .replace(/&#039;/g, "'");
  }

    //function that is called when user stops typing (onkeyup, where it is called) and checks that when any field in a modal is empty, 
    //it sets the submit button for the respective modal to be disabled 

    function stoppedTyping(inputIds, button) {

        var buttonShouldBeDisabled = false;

        for (var index = 0; index < inputIds.length; index++) {

            //removes whitespaces in input to make sure there is actually something there

            var string = document.getElementById(inputIds[index]).value.trim()

            if (string.length == 0) {

                buttonShouldBeDisabled = true;
            }
        }

        document.getElementById(button).disabled = buttonShouldBeDisabled;

    }

    //function that calls the stoppedTyping function for the customers modal

    function customerStoppedTyping() {

        var inputIds = ['lName', 'fName'];
        var button = "custButton";

        stoppedTyping(inputIds, button);
    }

    //function that calls the stoppedTyping function for the addresses modal

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

            document.getElementById("custID").value = -1;
            document.getElementById("fName").value = "";
            document.getElementById("lName").value = "";

            //set the button to be disabled by default since fields are empty

            custButton.disabled = true;
        } 
        
        else {

            for (x = 0; x < customers.length; x++) {

              //finds appropriate customer data to populate modal 

                if (customers[x].cust_id == custID) {

                    document.getElementById("custLabel").innerHTML = "Update Customer";

                    document.getElementById("custID").value = customers[x].cust_id;

                    custFunc.value = "custEdit";

                    //update fields for editing (populate with proper customer names)

                    fmodalCust.innerHTML = escapeHTML(customers[x].cust_fname);
                    fmodalCust.value = escapeHTML(customers[x].cust_fname);

                    lmodalCust.innerHTML = escapeHTML(customers[x].cust_lname);
                    lmodalCust.value = escapeHTML(customers[x].cust_lname);

                    custButton.innerHTML = "Update Customer";

                    //update the status of the button incase an empty field is already present

                    customerStoppedTyping();

                    $("#add-customer").modal("show");

                }
            }
        }
    }

    function editAddr(addrID) {

        if (addrID == -1) {

            //set modal title and button text for adding

            document.getElementById("addrLabel").innerHTML = "Add New Address";
            document.getElementById("addrButton").innerHTML = "Add New Address";
            addrFunc.value = "addrAdd";

             //empty all fields for adding

            document.getElementById("custSelect").value = "";
            document.getElementById("addrID").value = -1;
            document.getElementById("desc").value = "";
            document.getElementById("num").value = "";
            document.getElementById("street").value = "";
            document.getElementById("city").value = "";
            document.getElementById("provSelect").value = "";
            document.getElementById("postal").value = "";

            //set the button to be disabled by default since fields are empty

            addrButton.disabled = true;

        } 

        else {

            for (x = 0; x < addresses.length; x++) {

              //finds appropriate address data to populate modal 

                if (addresses[x].addr_id == addrID) {

                    //set modal title and button text for editing

                    document.getElementById("addrLabel").innerHTML = "Update Address";
                    document.getElementById("addrID").value = addresses[x].addr_id;
                    addrFunc.value = "addrEdit";

                    //updates all fields for editing

                    document.getElementById("desc").value = addresses[x].addr_desc;
                    document.getElementById("custSelect").value = addresses[x].addr_cust;
                    document.getElementById("num").value = addresses[x].addr_number;
                    document.getElementById("street").value = addresses[x].addr_street;
                    document.getElementById("city").value = addresses[x].addr_city;
                    document.getElementById("provSelect").value = addresses[x].addr_prov;
                    document.getElementById("postal").value = addresses[x].addr_postal;

                    addrButton.innerHTML = "Update Address";

                    //update the status of the button incase an empty field is already present

                    addrStoppedTyping();

                    $("#add-address").modal("show");

                }
            }
        }
    }

  </script>

</html>