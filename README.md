# 2checkout
2checkout payment gateway https://www.2checkout.com/

## Installation
```bash
composer require samir-hussein/2checkout
```

## Usage in php native
###### Step 1 : create token create index.php file like this
```html
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form id="myCCForm" action="payment.php" method="post">
        <input id="token" name="token" type="hidden" value="">
        <div>
            <label>
                <span>Card Number</span>
            </label>
            <input id="ccNo" type="text" size="20" value="" autocomplete="off" required />
        </div>
        <div>
            <label>
                <span>Cardplaceholder Name</span>
            </label>
            <input type="text" name="Cardplaceholder" size="20" value="" autocomplete="off" required />
        </div>
        <div>
            <label>
                <span>Expiration Date (MM/YYYY)</span>
            </label>
            <input type="text" size="2" id="expMonth" required />
            <span> / </span>
            <input type="text" size="2" id="expYear" required />
        </div>
        <div>
            <label>
                <span>CVC</span>
            </label>
            <input id="cvv" size="4" type="text" value="" autocomplete="off" required />
        </div>
        <input type="submit" value="Submit Payment">
    </form>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>

    <script>
        // Called when token created successfully.
        var successCallback = function(data) {
            var myForm = document.getElementById('myCCForm');

            // Set the token as the value for the token input
            myForm.token.value = data.response.token.token;

            // IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
            myForm.submit();
        };

        // Called when token creation fails.
        var errorCallback = function(data) {
            if (data.errorCode === 200) {
                tokenRequest();
            } else {
                alert(data.errorMsg);
            }
        };

        var tokenRequest = function() {
            // Setup token request arguments
            var args = {
                sellerId: "your_merchantCode",
                publishableKey: "your_publishableKey",
                ccNo: $("#ccNo").val(),
                cvv: $("#cvv").val(),
                expMonth: $("#expMonth").val(),
                expYear: $("#expYear").val()
            };

            // Make the token request
            TCO.requestToken(successCallback, errorCallback, args);
        };

        $(function() {
            // Pull in the public encryption key for our environment
            TCO.loadPubKey();

            $("#myCCForm").submit(function(e) {
                // Call our token request function
                tokenRequest();

                // Prevent form from submitting
                return false;
            });
        });
    </script>

</body>

</html>
```

###### Step 2 : create payment.php file like this
```php
<?php

use TwoCheckout\TwoCheckOut;

require_once "vendor/autoload.php";

$config = [
    'merchantCode' => 'merchantCode',
    'privateKey' => 'privateKey',
    'demo' => true,
];

$init = new TwoCheckOut($config);

$sale = TwoCheckOut::createSale([
    "merchantOrderId" => "123",
    "currency" => "EGP",
    "token" => $_POST['token'],
    "lineItems" => [
        [
            "name" => "watch",
            "price" => "4.99",
            "type" => "product",
            "quantity" => "1",
            "recurrence" => "4 Year",
            "startupFee" => "0"
        ]
    ],
    "billingAddr" => [
        "name" => $_POST['Cardplaceholder'],
        "addrLine1" => "123 test blvd",
        "city" => "nasr city",
        "state" => "cairo",
        "zipCode" => "43123",
        "country" => "EGY",
        "email" => "example@2co.com",
        "phoneNumber" => "123456789"
    ],
]);
```
###### TwoCheckOut::createSale() function will return object like this
```php
object(stdClass)#4 (3) {
  ["validationErrors"]=>
  NULL
  ["exception"]=>
  NULL
  ["response"]=>
  object(stdClass)#5 (13) {
    ["type"]=>
    string(12) "AuthResponse"
    ["currencyCode"]=>
    string(3) "EGP"
    ["recurrentInstallmentId"]=>
    NULL
    ["responseMsg"]=>
    string(48) "Successfully authorized the provided credit card"
    ["lineItems"]=>
    array(1) {
      [0]=>
      object(stdClass)#6 (11) {
        ["duration"]=>
        string(7) "Forever"
        ["options"]=>
        array(0) {
        }
        ["price"]=>
        string(4) "4.99"
        ["quantity"]=>
        string(1) "1"
        ["recurrence"]=>
        string(6) "4 Year"
        ["startupFee"]=>
        string(4) "0.00"
        ["productId"]=>
        string(0) ""
        ["tangible"]=>
        string(1) "N"
        ["name"]=>
        string(5) "watch"
        ["type"]=>
        string(7) "product"
        ["description"]=>
        string(0) ""
      }
    }
    ["transactionId"]=>
    string(12) "250763260588"
    ["billingAddr"]=>
    object(stdClass)#7 (10) {
      ["addrLine1"]=>
      string(13) "123 test blvd"
      ["addrLine2"]=>
      NULL
      ["city"]=>
      string(9) "nasr city"
      ["zipCode"]=>
      string(5) "43123"
      ["phoneNumber"]=>
      string(9) "123456789"
      ["phoneExtension"]=>
      NULL
      ["email"]=>
      string(15) "example@2co.com"
      ["name"]=>
      string(8) "John Doe"
      ["state"]=>
      string(5) "cairo"
      ["country"]=>
      string(3) "EGY"
    }
    ["shippingAddr"]=>
    object(stdClass)#8 (10) {
      ["addrLine1"]=>
      NULL
      ["addrLine2"]=>
      NULL
      ["city"]=>
      NULL
      ["zipCode"]=>
      NULL
      ["phoneNumber"]=>
      NULL
      ["phoneExtension"]=>
      NULL
      ["email"]=>
      NULL
      ["name"]=>
      NULL
      ["state"]=>
      NULL
      ["country"]=>
      NULL
    }
    ["merchantOrderId"]=>
    string(3) "123"
    ["orderNumber"]=>
    string(12) "250763260589"
    ["responseCode"]=>
    string(8) "APPROVED"
    ["total"]=>
    string(4) "4.99"
    ["errors"]=>
    NULL
  }
}
```
## card information testing
card number : 4111111111111111\
card placeholder name : John Doe\
exp month : 12\
exp year : 2023\
CVV : 123

## Usage In Laravel
###### Step 1 : in config/app.php
```php
// in providers
TwoCheckOut\TwoCheckoutServiceProvider::class,
// in aliases
'TwoCheckOut' => TwoCheckOut\Facades\TwoCheckOut::class,
```

###### Step 2 : in .env file
```bash
TwoCheckout_MerchantCode="Your_MerchantCode"
TwoCheckout_PrivateKey="Your_PrivateKey"
TwoCheckout_PublishableKey="Your_PublishableKey"
TwoCheckout_Demo=true
```

###### Step 3 : run command 
```bash
php artisan vendor:publish --provider="TwoCheckOut\TwoCheckoutServiceProvider"
```

###### Step 4 : create view 2checkout.blade.php to create token like this
```php
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form id="myCCForm" action="/payment" method="post">
        @csrf
        <input id="token" name="token" type="hidden" value="">
        <div>
            <label>
                <span>Card Number</span>
            </label>
            <input id="ccNo" type="text" size="20" value="" autocomplete="off" required />
        </div>
        <div>
            <label>
                <span>Cardplaceholder Name</span>
            </label>
            <input type="text" name="Cardplaceholder" size="20" value="" autocomplete="off" required />
        </div>
        <div>
            <label>
                <span>Expiration Date (MM/YYYY)</span>
            </label>
            <input type="text" size="2" id="expMonth" required />
            <span> / </span>
            <input type="text" size="2" id="expYear" required />
        </div>
        <div>
            <label>
                <span>CVC</span>
            </label>
            <input id="cvv" size="4" type="text" value="" autocomplete="off" required />
        </div>
        <input type="submit" value="Submit Payment">
    </form>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>

    <script>
        // Called when token created successfully.
        var successCallback = function(data) {
            var myForm = document.getElementById('myCCForm');

            // Set the token as the value for the token input
            myForm.token.value = data.response.token.token;

            // IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
            myForm.submit();
        };

        // Called when token creation fails.
        var errorCallback = function(data) {
            if (data.errorCode === 200) {
                tokenRequest();
            } else {
                alert(data.errorMsg);
            }
        };

        var tokenRequest = function() {
            // Setup token request arguments
            var args = {
                sellerId: "{{config('2checkout.merchantCode')}}",
                publishableKey: "{{config('2checkout.publishableKey')}}",
                ccNo: $("#ccNo").val(),
                cvv: $("#cvv").val(),
                expMonth: $("#expMonth").val(),
                expYear: $("#expYear").val()
            };

            // Make the token request
            TCO.requestToken(successCallback, errorCallback, args);
        };

        $(function() {
            // Pull in the public encryption key for our environment
            TCO.loadPubKey();

            $("#myCCForm").submit(function(e) {
                // Call our token request function
                tokenRequest();

                // Prevent form from submitting
                return false;
            });
        });
    </script>

</body>

</html>
```

###### Step 5 : create your controller like this
```php
<?php

namespace App\Http\Controllers;

use TwoCheckOut\Facades\TwoCheckOut;
use Illuminate\Http\Request;

class TwoCheckOutController extends Controller
{
    public function index(Request $request)
    {
        $sale = TwoCheckOut::createSale([
            "merchantOrderId" => "123",
            "currency" => "EGP",
            "token" => $request->token,
            "lineItems" => [
                [
                    "name" => "watch",
                    "price" => "4.99",
                    "type" => "product",
                    "quantity" => "1",
                    "recurrence" => "4 Year",
                    "startupFee" => "0"
                ]
            ],
            "billingAddr" => [
                "name" => $request->Cardplaceholder,
                "addrLine1" => "123 test blvd",
                "city" => "nasr city",
                "state" => "cairo",
                "zipCode" => "43123",
                "country" => "EGY",
                "email" => "example@2co.com",
                "phoneNumber" => "123456789"
            ],
        ]);
    }
}
```
###### TwoCheckout::createSale() function will return object like this
```php
{#444 ▼
  +"validationErrors": null
  +"exception": null
  +"response": {#450 ▼
    +"type": "AuthResponse"
    +"currencyCode": "EGP"
    +"recurrentInstallmentId": null
    +"responseMsg": "Successfully authorized the provided credit card"
    +"lineItems": array:1 [▼
      0 => {#454 ▼
        +"duration": "Forever"
        +"options": []
        +"price": "4.99"
        +"quantity": "1"
        +"recurrence": "4 Year"
        +"startupFee": "0.00"
        +"productId": ""
        +"tangible": "N"
        +"name": "watch"
        +"type": "product"
        +"description": ""
      }
    ]
    +"transactionId": "250763328730"
    +"billingAddr": {#451 ▼
      +"addrLine1": "123 test blvd"
      +"addrLine2": null
      +"city": "nasr city"
      +"zipCode": "43123"
      +"phoneNumber": "123456789"
      +"phoneExtension": null
      +"email": "example@2co.com"
      +"name": "John Doe"
      +"state": "cairo"
      +"country": "EGY"
    }
    +"shippingAddr": {#455 ▼
      +"addrLine1": null
      +"addrLine2": null
      +"city": null
      +"zipCode": null
      +"phoneNumber": null
      +"phoneExtension": null
      +"email": null
      +"name": null
      +"state": null
      +"country": null
    }
    +"merchantOrderId": "123"
    +"orderNumber": "250763328731"
    +"responseCode": "APPROVED"
    +"total": "4.99"
    +"errors": null
  }
}
```
