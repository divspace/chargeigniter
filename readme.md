# ChargeIgniter

Chargify API for CodeIgniter.

## Installation

Download `Chargify.php` and place it in your `libraries` folder and load the class using the following code:

````php
$this->load->library('chargify');
````

Run whatever function you need to, e.g.:

````php
$this->chargify->get_customers();
````