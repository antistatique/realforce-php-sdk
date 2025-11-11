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
$responseAmenities = $rf->publicLabels()->amenities($query);
$responseCategories = $rf->publicLabels()->amenitiesCategories($query);
$responseGroups = $rf->publicLabels()->amenitiesGroups($query);

?>

<?php echo renderResponse('French public amenities', $responseAmenities); ?>

<?php echo renderResponse('French Amenities categories', $responseCategories); ?>

<?php echo renderResponse('Amenities groups', $responseGroups); ?>
