<?php
/**
 * Example to Fetch "categories" labels linked to the public data you retrieve from the public API endpoints.
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
Make the API call to return categories' labels.
 ********************************/

$query = new Antistatique\Realforce\Request\I18nRequest();
$query->lang(['en']);
$response = $rf->publicLabels()->categories($query);

?>

<?php echo renderResponse('English public categories', $response); ?>
