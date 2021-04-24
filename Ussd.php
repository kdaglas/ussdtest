<?php
include_once("Payment.php");
include_once("InvoiceItem.php");
include_once("Vehicle.php");
include_once("MobileMoney.php");
include_once("ParkingTicket.php");


class Ussd extends Payment
{

  public $MobileMoneyID;
  public $TransactionID;
  public $RequestType;
  public $MSISDN;
  public $AppID;
  public $USSDString;
  public $ShortCode;
  public $token;
  public $uuid;
  public $status;
  public $request;

  public $sessionId;
  public $serviceCode;
  public $text;
  public $response;

  public $UssdID;
  public $UssdTransactionID;
  public $UssdServiceCode;
  public $UssdPhoneNumber;
  public $UssdCreationTime;
  public $UssdRequestString;

  public $Submain1;
  public $Submain2;
  public $Main;


  public function __construct()
  {
    parent::__construct();
    $ussdcode = $this->UssdRequestString;
  }


  protected function __get_ussd_transaction_info()
  {
    # GET THE WHOLE USSD TRANSACTION INFORMATION

    if (
      len($this->UssdID)
    ) {
      $response = "USSD transaction_id required\n";
      return $response;

      exit;
    }
    $query = "SELECT * FROM `tbl_ussd` WHERE `id`='$this->UssdID'";
    $result = mysqli_work($query);
    if ($result->num_rows == 0) {
      $response = "Sorry! This transaction_id does not exist\n";
      return $response;

      exit;
    }
    $row = $result->fetch_assoc();
    extract($row);

    $list = array(
      "ussd_id" => $id,
      "ussd_transaction_id" => $ussd_transaction_id,
      "ussd_creation_time" => $ussd_creation_time,
      "msisdn" => $msisdn,
      "main" => $main,
      "submain1" => $submain1,
      "submain2" => $submain2,
      "created_at" => array(
        'short_date' => picker_format($created_at),
        'date' => tell_when_no_time($created_at),
        'time' => user_time($created_at)
      )
    );
    return $list;
  }


  protected function __get_ussd_by_transaction_id()
  {
    # CHECK THE DB FOR TRANSACTION_ID

    $query_check_transaction_id = "SELECT * FROM `tbl_ussd` WHERE `ussd_transaction_id` = '$this->UssdTransactionID'";
    $result = mysqli_work($query_check_transaction_id);
    if ($result->num_rows == 0) {
      $response = "Sorry! This transaction_id does not exist.\n";
      return false;

      exit;
    }
    $row = $result->fetch_assoc();
    extract($row);
    $this->UssdID = $row['id'];
    $this->Main = $main;
    $this->Submain1 = $submain1;
    $this->Submain2 = $submain2;
    return $this->__get_ussd_transaction_info();
  }


  protected function __main_menu()
  {
    # PROMPT USER SELECTION FROM MAIN MENU

    $response = "Welcome to Multiplex Street Parking.\n\n";

    $response .= "-----------------\n\n";

    $response .= "1. Pay outstanding charges\n";
    $response .= "1. Pa\n";
    $response .= "3. Make purchases\n\n";

    $response .= "0. Back\n";
    $response .= "00. Main menu\n";
    return $response;

    exit;
  }


  protected function __prompt_purchase_input()
  {
    # PROMPT USER TO CHOOSE WHICH PURCHASE TO MAKE

    $response = "Choose which purchase to make.\n\n";

    $response .= "1. Buy sticker(s)\n";
    $response .= "2. Purchase ticket(s)\n";
    $response .= "3. Make purchases\n\n";

    $response .= "0. Back\n";
    $response .= "00. Main menu\n";
    return $response;

    exit;
  }


  protected function __prompt_vehicle_input()
  {
    # PROMPT USER TO PUT VEHICLE PLATE NUMBER

    $response = "Enter vehicle Plate number.\n\n";

    $response .= "0. Back\n";
    $response .= "00. Main menu\n";
    return $response;

    exit;
  }


  protected function __prompt_amount_input()
  {
    # PROMPT USER TO PUT AMOUNT TO PAY

    $response = "Enter amount being paid.\n\n";

    $response .= "0. Back\n";
    $response .= "00. Main menu\n";
    return $response;

    exit;
  }


  protected function __prompt_pin_input()
  {
    # PROMPT USER TO PUT PIN TO ALLOW

    $response = "Enter your pin.\n\n";

    $response .= "0. Back\n";
    $response .= "00. Main menu\n";
    return $response;

    exit;
  }


  public function __create_transaction_id()
  {
    # CREATE TRANSACTION IN THE DATABASE

    if (
      len($this->UssdTransactionID)
    ) {
      $response = "Complete all fields and try again.\n";
      return $response;

      exit;
    }

    # CHECK FOR EXISTING USSD TRANSACTION ID

    $transaction_exists = $this->__get_ussd_by_transaction_id();
    if ($transaction_exists) {
      $response = "Sorry! transaction id already exists.\n";
      return $response;

      exit;
    }

    $data = array(
      'ussd_transaction_id' => $this->UssdTransactionID,
      'ussd_creation_time' => $this->UssdCreationTime,
      'msisdn' => $this->UssdPhoneNumber,
    );

    $created = mysqli_insert("tbl_ussd", $data);
    if ($created) {
      $list = array(
        "ussd_id" => $created,
        'transaction_id' => $this->UssdTransactionID,
        'transaction_time' => $this->UssdCreationTime,
        'msisdn' => $this->UssdPhoneNumber,
      );
      // $this->Success = "Transaction id created successfully!";
      // return $list;
      $response = "Transaction id created successfully!\n";
      return $response;

      exit;
    }
    // $this->Error = "Error occurred while creating transaction id";
    // return false;
    $response = "Error occurred while creating transaction id\n";
    return $response;

    exit;
  }


  public function __update_any_main($menu, $value)
  {
    # METHOD TO UPDATE ANY MAIN

    $data = array(
      $menu => $value,
    );

    $updated = mysqli_update("tbl_ussd", $data, $this->UssdID);
    if (!$updated) {
      $response = "Failed to update the " . $menu . "field\n";
      return $response;
    }
    $response = "Updated the " . $menu . "succesfully\n";
    return $response;
  }


  public function __index()
  {
    # CHECK IF REQUIRED PARAMETERS ARE GIVEN

    if (
      len($this->UssdTransactionID) ||
      len($this->UssdCreationTime) ||
      len($this->UssdPhoneNumber) ||
      len($this->UssdServiceCode)
    ) {
      $response = "Some network provided fields are missing\n";
      return $response;
    }

    $ussdcode = $this->UssdRequestString;

    if (isset($ussdcode)) {

      # CHECK IF TRANSACTION EXISTS

      $transaction_exists = $this->__get_ussd_by_transaction_id();

      if (!$transaction_exists) {
        $add_to_db = $this->__create_transaction_id();
        if (!$add_to_db) {
          $response = $this->Error;
          return $response;

          exit;
        }
      }

      if ($ussdcode == "") {

        # WELCOME MENU USSD

        return $this->__main_menu();
        exit;
      } else {

        if ($this->Main == 0) {

          #CHECK IF USERINPUT IS PART OF THE CASES

          $list = [1, 2, 3];
          if (!in_array($ussdcode, $list)) {
            $response = "Wrong USSD input code...\n";
            return $response;

            exit;
          }

          $NewUssd = new Ussd;
          $NewUssd->UssdID = $this->UssdID;
          $NewUssd->__update_any_main('main', $ussdcode);

          $this->__get_ussd_by_transaction_id();
        }

        switch ($this->Main):

          case "1": // LEADS TO FIRST SUBMENU SCREEN

            # CHECK IF USERINPUT IS NON OTHER THAN 1

            if ($ussdcode == "1") {

              # PROMPT USER TO PUT VEHICLE PLATE NUMBER

              return $this->__prompt_vehicle_input();
              exit;
            }

            # SAVE THE PLATE NUMBER IN THE DB

            $NewUssd = new Ussd;
            $NewUssd->UssdID = $this->UssdID;
            $NewUssd->__update_any_main('submain1', $ussdcode);

            # CHECK IF PLATE NUMBER EXISTS

            $NewVehicle = new Vehicle;
            $NewVehicle->PlateNumber = $ussdcode;
            $plate_number = $NewVehicle->__get_vehicle_by_plate_number();

            if (!$plate_number) {
              $response = "Invalid Plate number!\n";
              return $response;

              exit;
            }

            // if ($ussdcode == $this->Submain1) {
            //     return $this->__prompt_pin_input();
            //     exit;
            // }

            # CHECK FOR VEHICLE BALANCE

            $NewInvoiceItemList = new InvoiceItem;
            $NewInvoiceItemList->VehicleID = $NewVehicle->VehicleID;
            $NewInvoiceItemList->__get_vehicle_outstanding_balance();

            if ($NewInvoiceItemList->TotalVehicleBalance == 0) {
              $response = "Vehicle has no outstanding balance!\n";
              return $response;

              exit;
            }

            # RETURN THE AMOUNT FOR THAT VEHICLE NUMBER

            $response = "Your plate number is " . $NewVehicle->PlateNumber . "\n";
            $response .= "and your outstanding balance is:\n";
            $response .= $NewInvoiceItemList->TotalVehicleBalance . "\n\n";
            return $response;

            exit;

            break;

          case "2":

            # CHECK IF USERINPUT IS NON OTHER THAN 2

            if ($ussdcode == "2") {

              # PROMPT USER TO PUT VEHICLE PLATE NUMBER

              return $this->__prompt_vehicle_input();
              exit;
            }

            # CHECK SUBMAIN INPUT

            if ($this->Submain1 == "0") {

              # SAVE THE PLATE NUMBER IN THE DB

              $NewUssd = new Ussd;
              $NewUssd->UssdID = $this->UssdID;
              $NewUssd->__update_any_main('submain1', $ussdcode);

              $this->__get_ussd_by_transaction_id();

              # CHECK IF PLATE NUMBER EXISTS

              $NewVehicle = new Vehicle;
              $NewVehicle->PlateNumber = $ussdcode;
              $plate_number = $NewVehicle->__get_vehicle_by_plate_number();

              if (!$plate_number) {
                $response = "Invalid Plate number!\n";
                return $response;

                exit;
              }
            }

            if ($ussdcode == $this->Submain1) {
              return $this->__prompt_amount_input();
              exit;
            }

            # SAVE THE AMOUNT IN THE DB

            $NewUssd = new Ussd;
            $NewUssd->UssdID = $this->UssdID;
            $NewUssd->__update_any_main('submain2', $ussdcode);

            $this->__get_ussd_by_transaction_id();

            # MAKE USSD MOBILE MONEY PAYMENT

            $NewMMPayment = new MobileMoney;

            $NewMMPayment->SystemUser = 1;
            $NewMMPayment->AmountPaid = $this->Submain2;
            $NewMMPayment->DepositorContact = $this->UssdPhoneNumber;
            $NewMMPayment->DepositorName = "user";
            $NewMMPayment->PlateNumber = $this->Submain1;

            $pay = $NewMMPayment->__make_bulk_mm_payment();

            if (!$pay) {
              $response = "mobile money payment unsuccessful!\n";
              return $response;

              exit;
            }

            # RETURN THE AMOUNT FOR THAT VEHICLE NUMBER

            $response = "Your plate number is " . $this->Submain1 . "\n";
            $response .= "and your payment of " . $this->Submain2 . "\n";
            $response .= "has been received\n";
            return $response;

            exit;

            break;

          case "3":

            # CHECK IF USERINPUT IS NON OTHER THAN 3

            if ($ussdcode == "3") {

              # PROMPT USER TO PUT VEHICLE PLATE NUMBER

              return $this->__prompt_vehicle_input();
              exit;
            }

            # CHECK SUBMAIN INPUT

            if ($this->Submain1 == "0") {

              # SAVE THE PLATE NUMBER IN THE DB

              $NewUssd = new Ussd;
              $NewUssd->UssdID = $this->UssdID;
              $NewUssd->__update_any_main('submain1', $ussdcode);

              $this->__get_ussd_by_transaction_id();

              # CHECK IF PLATE NUMBER EXISTS

              $NewVehicle = new Vehicle;
              $NewVehicle->PlateNumber = $ussdcode;
              $plate_number = $NewVehicle->__get_vehicle_by_plate_number();

              if (!$plate_number) {
                $response = "Invalid Plate number!\n";
                return $response;

                exit;
              }
            }

            if ($ussdcode == $this->Submain1) {
              return $this->__prompt_amount_input();
              exit;
            }

            # SAVE THE AMOUNT IN THE DB

            $NewUssd = new Ussd;
            $NewUssd->UssdID = $this->UssdID;
            $NewUssd->__update_any_main('submain2', $ussdcode);

            $this->__get_ussd_by_transaction_id();

            # MAKE USSD TICKET PAYMENT

            $NewMMTicketPurchase = new ParkingTicket;

            $NewMMTicketPurchase->SystemUser = 1;
            $NewMMTicketPurchase->AmountPaid = $this->Submain2;
            $NewMMTicketPurchase->DepositorContact = $this->UssdPhoneNumber;
            $NewMMTicketPurchase->DepositorName = "user";
            $NewMMTicketPurchase->PlateNumber = $this->Submain1;

            $purchase = $NewMMTicketPurchase->__buy_ticket($NewMMTicketPurchase->PlateNumber);

            if (!$purchase) {
              $response = "mobile money ticket purchase unsuccessful!\n";
              return $response;

              exit;
            }

            # RETURN THE AMOUNT FOR THAT VEHICLE NUMBER

            $response = "Your plate number is " . $this->Submain1 . "\n";
            $response .= "and your payment of " . $this->Submain2 . "\n";
            $response .= "for Ticket purchase has been received\n";
            return $response;

            exit;

            break;

          default:

            $response = "the ussd code entered is " . $ussdcode . "\n";
            $response .= "Something ain`t right check your code";

        endswitch;
      }

      return $response;
    }
  }
}
