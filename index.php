<?php
#We obtain the data which is contained in the post url on our server.

$text = $_GET['USSD_STRING'];
$phonenumber = $_GET['MSISDN'];
$serviceCode = $_GET['serviceCode'];


$level = explode("*", $text);
if (isset($text)) {


  if ($text == "") {
    $response = "CON Welcome to the registration portal.\nPlease enter you full name";
  }

  if (isset($level[0]) && $level[0] != "" && !isset($level[1])) {

    $response = "CON Hi " . $level[0] . ", enter your ward name";
  } else if (isset($level[1]) && $level[1] != "" && !isset($level[2])) {
    $response = "CON Please enter you national ID number\n";
  } else if (isset($level[2]) && $level[2] != "" && !isset($level[3])) {
    //Save data to database
    $data = array(
      'phonenumber' => $phonenumber,
      'fullname' => $level[0],
      'electoral_ward' => $level[1],
      'national_id' => $level[2]
    );



    $response = "END Thank you " . $level[0] . " for registering.\nWe will keep you updated";
  }

  header('Content-type: text/plain');
  echo $response;
}

?>


<?php

# DATA IS FROM THE POST URL ON THE SERVER

// $ussdcode = $_GET['ussdRequestString'];
// $phonenumber = $_GET['msisdn'];
// $serviceCode = $_GET['ussdServiceCode'];

// $ussdcode = $_GET['ussdRequestString'];
// $phonenumber = $_GET['msisdn'];
// $serviceCode = $_GET['ussdServiceCode'];
// $transactionid = $_GET['transactionId'];
// $transactiontime = $_GET['transactionTime'];

// // UssdTransactionID
// $level = explode("*", $ussdcode);

// if (isset($ussdcode)) {

//     switch ($ussdcode):
//         case "": // FIRST MENU SCREEN
//             $response = "Welcome to Multiplex Street Parking...\n";
//             $response .= "1. Pay outstanding parking\n";
//             $response .= "2. Pay contravation charge\n";
//             $response .= "3. Purchase Sticker\n";
//             $response .= "4. Purchase Ticket\n";
//             $response .= "5. View vehicle balance\n";
//             $response .= "0. Back\n";
//             $response .= "00. Main menu\n";

//             break;
//         case "1":
//         case "2":
//         case "3":
//         case "4":
//         case "5": // SECOND MENU SCREEN
//             $response = "Enter vehicle Plate number...\n";
//             $response .= "0. Back\n";
//             $response .= "00. Main menu\n";

//             break;
//         case (isset($level[1]) && $level[1] != "" && !isset($level[2])):
//             //Save data to database
//             $data = array(
//                 'msisdn' => $phonenumber,
//                 'transaction_id' => $transactionid,
//                 'plate_number' => $level[1],
//                 'main' => $level[2]
//             );
//             $response = "END Thank you " . $level[0] . " for registering.\nWe will keep you updated";
//             $response = "Congradulations, you have made a payment";

//             break;
//         default:
//             $response = "Wrong ussdCode, please enter correct one...";

//     endswitch;

//     header('Content-type: text/plain');
//     echo $response;
// }

?>


<?php


// header('Access-Control-Allow-Origin: *');
// header('Content-Type: application/json');
// header('Access-Control-Allow-Methods: POST');
// header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, ContentType, Access-Control-Allow-Methods, Authorization, X-Requested-With');
//include("../modal/App.php");


/*******
 * user registers new account
 */

/*
$data=json_decode(file_get_contents("php://input"));

if(!isset($data->phone) || !isset($data->role_id))
{
    $info=array(
        'status' => 'Fail',
        'details' => array("Paramaters were not specified")
    );
    print_r(json_encode($info));

    exit;
}

$phone=clean($data->phone);
$role_id=clean($data->role_id);

$phoneUser=new User;
$phoneUser->RoleID=$role_id;
$sent=$phoneUser->__send_verification_code($phone);

if($sent)
{
    $info=array(
        'status' => 'OK',
        'details' => array(
            $phoneUser->Success            
            )
    );
    print_r(json_encode($info));
}
else
{
  
    $info=array(
        'status' => 'Fail',
        'details' => array($phoneUser->Error)
    );
    print_r(json_encode($info));

}

*/


# CHECK IF MENU CODE EXISTS

// $code_exists = $this->__get_ussd_code();
// if (!$code_exists) {
//     $this->Error = "Sorry! this main menu code doesn't exist";
//     return false;
// }

// $input_code = $code_exists["menu"];

// if ($input_code == "5") {

//     $response = "Enter vehicle Plate number...\n";
//     $response .= "0. Back\n";
//     $response .= "00. Main menu\n";

// switch ($ussdcode):
//     case $ussdcode:
//         echo "plate number here@@@@@@@@@@@@";
//         break;
//     case "0":
//         echo "you are trying to go back";
//         break;
//     default:
//         echo "something went wrong here";

// endswitch;

//     $NewInvoiceItemList = new InvoiceItem;
//     $outsandingBalance = $NewInvoiceItemList->__get_vehicle_outstanding_balance($ussdcode);

//     $response = "Your plate number is" . $ussdcode . "\n";
//     $response .= "Your outstanding balance is:\n";
//     $response .= $outsandingBalance["amount_c"];

// }

// if (isset($input_code)) {

//     switch ($ussdcode):
//         case $ussdcode:
//             echo "plate number here@@@@@@@@@@@@";
//             break;
//         case "0":
//             echo "you are trying to go back";
//             break;
//         default:
//             echo "something went wrong here";

//     endswitch;

// }

// break;
// case "4": // PROMPT TO ENTER VEHICLE NUMBER

# CHECK IF PLATE NUMBER EXISTS
// $this->PlateNumber="UBB772Q";

// $plate_number_exists = $this->__get_ussd_by_plate_number();
// if ($plate_number_exists) {
//     $ussdcodeplate = $this->PlateNumber;
//     echo var_dump($plate_number_exists);
//     echo var_dump($ussdcodeplate);
// }

# SAVE PLATE NUMBER IN THE DB

// $NewUssd = new Ussd;
// $NewUssd->UssdID = $this->UssdID;
// $update_menu = $NewUssd->__update_any_main('plate_number', $ussdcode);

// if (!$update_menu) {
//     $this->Error = $NewUssd->Error;
//     return false;
// }

// $NewInvoiceItemList = new InvoiceItem;
// $outsandingBalance = $NewInvoiceItemList->__get_vehicle_outstanding_balance($ussdcode);

// $response = "Your plate number is" . $ussdcode . "\n";
// $response .= "Your outstanding balance is:\n";
// $response .= $outsandingBalance["amount_c"];

// break;


// $plate_number = $this->__get_submain1();
// $amount=$this→__get_submenu(‘submenu2’);
// if($plate_number===”0”)

// exit;

// $plate_number_exists = $this->__get_ussd_by_plate_number();
// if ($plate_number_exists && $plate_number_exists != NULL) {
// $add_to_db = $this->__create_transaction_id();
// if (!$add_to_db) {
//     $response = $this->Error;
//     return $response;
// }
// $ussdcodeplate = $plate_number_exists;
// echo var_dump($ussdcodeplate);
// }

// $ussdcodeplate = $plate_number_exists;
// echo var_dump($ussdcodeplate);

// $response = "Welcome to Multiplex Street Parking...\n";
// $response .= "1. Pay outstanding parking\n";
// $response .= "2. Pay contravation charge\n";
// $response .= "3. Purchase Sticker\n";
// $response .= "4. Purchase Ticket\n";
// $response .= "5. View vehicle balance\n";
// $response .= "0. Back\n";
// $response .= "00. Main menu\n";


// protected function __get_menu($menu)
// {
# CHECK DB FOR THE SUBMAIN USSD CODE

// $query_check_submain1 = "SELECT * FROM `tbl_ussd` WHERE $menu='$this->Menu'";
// $result = mysqli_work($query_check_submain1);
// if ($result->num_rows == 0) {
//     $this->Error = "Sorry! This code does not exist.";
//     return false;
// }
// $row = $result->fetch_assoc();
// return true;
// extract($row);
// $this->Menu=$value;
// $this->UssdID = $row['id'];
// return $this->__get_ussd_transaction_info();
// }






// public function __get_transaction_id()
// {
//     $query = "SELECT * FROM `tbl_ussd` WHERE `transaction_id`='$this->ussdTransactionId' ";
//     $result = mysqli_work($query);
//     if ($result->num_rows == 0) {

//         #create transaction_id in the database
//         $data = array(
//             "transaction_id" => $this->ussdTransactionId,
//             // "created_by" => $this->SystemUser,
//         );

//         $created = mysqli_insert("tbl_ussd", $data);
//         if ($created) {
//             $list = array(
//                 // "user_id" => $created,
//                 "transaction_id" => $this->ussdTransactionId
//             );
//             $this->Success = "transaction_id created successfully!";
//             return $list;
//         }
//         $this->Error = "Error has occurred";
//         return false;
//     }

//     $row = $result->fetch_assoc();
//     extract($row);
//     $this->ussdTransactionId = $row['id'];
//     return $this->__get_ussd_transaction_info();
// }


?>


<?php

# DATA IS FROM THE POST URL ON THE SERVER

// $ussdcode = $_GET['ussdRequestString'];
// $phonenumber = $_GET['msisdn'];
// $serviceCode = $_GET['ussdServiceCode'];
// $transactionid = $_GET['transactionId'];
// $transactiontime = $_GET['transactionTime'];

// $level = explode("*", $ussdcode);

// if (isset($ussdcode)) {

//   if ($ussdcode == "") {
//     $response = "Welcome to Multiplex Street Parking...\n";
//     $response .= "1. View vehicle balance\n";
//     $response .= "0. Back\n";
//     $response .= "00. Main menu\n";
//   }

//   // if (isset($level[0]) && $level[0] != "" && !isset($level[1])) {
//   if ($ussdcode == "1") {
//     $response = "CON Please enter your vehicle number";
//   } else if (isset($level[1]) && $level[1] != "" && !isset($level[2])) {
//     $response = "CON Hi " . $level[1] . ", Please enter you national ID number";
//   } else if (isset($level[2]) && $level[2] != "" && !isset($level[3])) {
//     //Save data to database
//     $data = array(
//       'msisdn' => $phonenumber,
//       'transaction_id' => $transactionid,
//       'plate_number' => $level[1],
//       'main' => $level[2]
//     );
//     $response = "END Thank you " . $level[2] . " for registering.\nWe will keep you updated";
//   }

//   header('Content-type: text/plain');
//   // echo $response;
// }

?>



