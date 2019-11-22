<?php
// use function PHPSTORM_META\type;

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * The script relies on a simple 'control' variable passed from the front-end to know
 * what action is needed.
 * - Select - Simply selects the product_id, product_name, product_category from the e_product DB, adds it
 * to an array, which is then encoded to a JSON formatted string and returned to the front-end.
 * 
 * - Update - If the user provided a valid input for a new category, the category for the specific
 * product_id is updated in the DB. A result output as a boolean is returned to the front-end
 * to inform the user if the product category was sucessfully updated.
 * 
 * - Browse - If the user wishes to observe specific tracking data for the items, he can choose so 
 * by using the checkboxes provided in the front-end, which would be used to build programmatically a DB query to his
 * liking, the results of which would be returned to the front-end in a JSON formatted string.
 * 
 * @param   SQLite3     $db_conn        Connection to the DB.
 * @param   string      $db_query       The query that is to be send to the DB.
 * @param   array       $result         The returned result array from the DB.
 * @param   array       $row_arr        The data from each row in $result is restructures as array and added to row_arr.
 * @param   string      $root_json      JSON formatted string with row_arr to be send to the front-end.
 * @param   int         $index          Used to keep track of the inserted data in row_arr.
 * 
 * @param   int         $prod_id        The product_id.
 * @param   string      $new_category   The new name for a category for a specific product as provided by the user.
 * 
 * @param   string      $query_main     The string with DB column parameters from the first 3 checkboxes.
 * @param   string      $query_second   The string with DB column parameters from the last 4th checkbox.
 * Decided to go this way as it was simpler. First 3 checkboxes get their info from the same DB table, where the 4th checkbox from a different table.
 * 
 * @param   array       $main_response  Contains the structured array of arrays data for the first 3 checkbox search criteria.
 * @param   array       $query_second   Contains the structured array of arrays data for the 4th checkbox search criteria.
 * 
 * @return  mixed
 */
if(isset($_REQUEST['control']) && $_REQUEST['control'] == 'select') {
    $db_conn = new SQLite3("e_commerce.db");
    $db_query = "SELECT product_id, product_name, product_category from e_product_visit_data";
    $result = $db_conn->query($db_query);

    $row_arr = array();
    $root_json = null;
    $index = 0;
    while($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $tmp_arr = array(
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'product_category' =>$row['product_category']
        );
        $row_arr[$index] = $tmp_arr;
        $index = $index + 1;
    }
    $root_json = json_encode($row_arr);
    echo $root_json;
} else if(isset($_REQUEST['control']) && $_REQUEST['control'] == 'update') {
    $prod_id = $_REQUEST['product_id'];
    $new_category = $_REQUEST['category'];

    $db_conn = new SQLite3("e_commerce.db");
    $db_query = 'UPDATE e_product_visit_data SET product_category = '.'"'.$new_category.'"'.' WHERE product_id='.$prod_id;
    $result = $db_conn->exec($db_query);
    if($db_conn->changes() > 0) {
        echo true;
    } else {
        echo false;
    }
} else if(isset($_REQUEST['control']) && $_REQUEST['control'] == 'browse') {
    $query_main = $_REQUEST['query_one'];
    $query_second = $_REQUEST['query_two'];
    $main_response = array();
    $secondary_response = array();

    $db_conn = new SQLite3("e_commerce.db");

    if ($query_main != "" && $query_main != null) {
        $db_query = 'SELECT '.$query_main.' FROM e_product_visit_data ORDER BY product_category';
        $db_data = $db_conn->query($db_query);
        
        $index = 0;
        while($row = $db_data->fetchArray(SQLITE3_ASSOC)) {
            $main_response[$index] = $row;
            $index = $index + 1;
        }   
    }

    if($query_second != "" && $query_second != null) {
        $db_query = 'SELECT '.
        $query_second.'
        FROM
        e_product_visits_unique as a
        INNER JOIN e_product_visit_data as b ON a.product_id = b.product_id';

        $db_data = $db_conn->query($db_query);
    
        $index = 0;
        while($row = $db_data->fetchArray(SQLITE3_ASSOC)) {
            $secondary_response[$index] = $row;
            $index = $index + 1;
        }
    }

    if(sizeof($secondary_response) > 0) {
        echo json_encode(array(
            'main' => $main_response,
            'secondary' => $secondary_response
        ));
    } else {
        echo json_encode(array(
            'main' => $main_response
        ));
    }

} else {
    echo false;
}
?>