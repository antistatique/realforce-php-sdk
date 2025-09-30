<?php
/**
 * Index file for the examples of the SDK.
 */
\error_reporting(E_ALL);

include_once 'templates/base.php';
?>

<?php if (!isWebRequest()) { ?>
  To view this example, run the following command from the root directory of this repository:

    php -S localhost:8000 -t examples/

  And then browse to "localhost:8000" in your web browser
<?php return; ?>
<?php } ?>

<?php echo pageHeader('Realforce API SDK Examples'); ?>

<h2>Resources</h2>

<ul>
    <li><a href="resources/properties/public/list.php">Listing of Properties</a></li>
    <li><a href="resources/labels/public/locations.php">Labels - Locations</a></li>
    <li><a href="resources/labels/public/categories.php">Labels - Categories</a></li>
    <li><a href="resources/labels/public/amenities.php">Labels - Amenities</a></li>
</ul>

<?php echo pageFooter(); ?>
