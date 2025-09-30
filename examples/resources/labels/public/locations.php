<?php
/**
 * Example to Fetch "locations" labels linked to the public data you retrieve from the public API endpoints.
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
Make the API call to return locations' labels.
 ********************************/

$query = new Antistatique\Realforce\Request\LocationsRequest();
$query
  ->isQuarter()
  ->lang(['de']);
$response = $rf->publicLabels()->locations($query);

?>

<?php echo renderResponse('German public Quarters', $response); ?>
