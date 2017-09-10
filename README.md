# tickets test

Simple implementation of API for saving listings with tickets.

Business rules:
- It should not be possible to create a listing with duplicate barcodes in it.
- It should not be possible to create a listing with duplicate barcodes within another listing.
- Though, it should be possible for the last buyer of a ticket, to create a listing with that ticket (based on barcode).

No framework is used. All application logic is in src/ directory.

**Install dependencies:**
    
     composer install
     
**Run tests:**

    ./vendor/bin/phpunit tests 

**Run application using build-in webserver:**
    
    php -S localhost:8000

**API endpoints usage**
    
_Authenticate/create user_

    curl localhost:8000/endpoints/authenticate.php -d '{"id":123}' -H 'Content-Type: application/json'

_For generating curl command look at /examples/authenticate.php_
    
    
_Create new listing_

    curl localhost:8000/endpoints/create.php -d '{"description":"some concert","price":12345,"tickets":[{"barcodes":["barcode1"]},{"barcodes":["barcode2","barcode3"]},{"barcodes":["barcode4","barcode5","barcode6"]}],"userId":12345}' -H 'Content-Type: application/json'

_For generating curl command look at /examples/createListing.php_

_Show all listings with all tickets and barcodes in browser_    
    
    http://localhost:8000/endpoints/show.php 
    
**Possible improvements for listings part of application**

- service provider
- unified config
- combined validation messages
- more named exceptions
- separate validators for listing and tickets
- save valid tickets and show messages about invalid tickets
- tests for repositories
- endpoint for buying to test the whole flow

_Improvements for user part of application is not needed because implementation is just a hack ;)_