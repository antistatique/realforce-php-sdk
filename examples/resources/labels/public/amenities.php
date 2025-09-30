<?php
/**
 * Example to Fetch "amenities" labels linked to the public data you retrieve from the public API endpoints.
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
 * Make the API call to return amenities' labels.
 * *******************************
 */

$query = new Antistatique\Realforce\Request\I18nRequest();
$query->lang(['fr']);
$response = $rf->publicLabels()->amenities($query);

?>

<?php echo renderResponse('French public amenities', $response); ?>
