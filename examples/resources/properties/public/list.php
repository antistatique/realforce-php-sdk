<?php
/**
 * Example to Fetch a list of published properties' public data.
 */
include_once '../../base.php';

/********************************
Create the Realforce object
 ********************************/
$rf = getRealforce();
$envs = getEnvVariables();

/********************************
Authenticate the following calls.
 ********************************/
$rf->setApiToken($envs['REALFORCE_API_TOKEN']);

/********************************
Make the API call to return properties.
 ********************************/

$query = new Antistatique\Realforce\Request\PropertiesListRequest();
$query
  ->lang(['fr', 'en'])
  ->page(0)
  ->perPage(10)
;
$response = $rf->publicProperties()->list($query);

?>

<?php echo renderResponse('10 published public properties on page index 0', $response); ?>
