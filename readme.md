DOCUMENTATION

http://chargeigniter.com/


INSTALLATION

1. Download Chargify.php and place it in your 'libraries' folder.

2. Load the class using the following:

$this->load->library('chargify');

3. Run whatever function you need to, e.g.:

$this->chargify->get_customers();


NON-CODEIGNITER USE

If you're not using CodeIgniter, simply remove the following lines of code:

13, 22, 24, 680, 682

Include the library and load the class using the following:

require_once('Chargify.php');

$chargify = new Chargify();

Then run whatever function you need to, e.g.:

$chargify->get_customers();