<?php

// use function PHPSTORM_META\type;

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

/**
 * If the provided $property and $val are of an array type, builds property and
 * value string respectively before executing the query.
 * If $property and $val are of a simple data type, executes the query as usual.
 * 
 * Inserts new item into the tracking database tables.
 *
 * @param  SQLite3 $db_conn     References the DB connection.
 * @param  string  $table_name  The name of the table where the intem will be inserted.
 * @param  mixed   $property    The property(-ies) to be inserted.
 * @param  mixed   $val         The value(-s) to be inserted with the property(-ies).
 */
function insert($db_conn, $table_name, $property, $val) {
    if(gettype($val) == 'array') {
        $format_property = "";
        $format_val = "";
        for($i = 0; $i < sizeof($val); $i++) {
            if($format_property == "") {
                $format_property = $property[$i];
            } else {
                $format_property = $format_property.",".$property[$i];
            }
        }

        for($i = 0; $i < sizeof($val); $i++) {
            if(gettype($val[$i]) == 'string') {
                if($format_val == "") {
                    $format_val = '"'.$val[$i].'"';
                } else {
                    $format_val = $format_val.",".'"'.$val[$i].'"';
                }
            } else {
                if($format_val == "") {
                    $format_val = strval($val[$i]);
                } else {
                    $format_val = $format_val.",".strval($val[$i]);
                }
            }
        }
        $db_query = 'INSERT INTO '.$table_name.' ('.$format_property.') VALUES ('.$format_val.')';
        $db_conn->exec($db_query);
    } else {
        $db_query = 'INSERT INTO '.$table_name.' ('.$property.') VALUES ("'.$val.'")';
        $db_conn->exec($db_query);
    }
}

/**
 * Updates property in a tracking table, by id (optional).
 *
 * @param  SQLite3 $db_conn     References the DB connection.
 * @param  string  $table_name  The table name where the update is to take place.
 * @param  string  $property    The property that is to be updated.
 * @param  mixed   $val         The new value of the property.
 * @param  int     $id          The id of a specific product for the update to take place.
 */
function update_by_id($db_conn, $table_name, $property, $val, $id) {
    if($id != null) {
        $db_query = 'UPDATE '.$table_name.' SET '.$property.'='.$val.' WHERE product_id ='.$id;
        $db_conn->exec($db_query);
    } else {
        $db_query = 'UPDATE '.$table_name.' SET '.$property.'='.$val;
        $db_conn->exec($db_query);
    }
}

/**
 * Updates property by category.
 *
 * @param  SQLite3 $db_conn     References the DB connection.
 * @param  string  $table_name  The table where the property is.
 * @param  string  $property    The property that is to be updated.
 * @param  mixed   $val         The new value of the property.
 * @param  string  $category    The specific category for the update to take place.
 */
function update_by_category($db_conn, $table_name, $property, $val, $category) {
    $db_query = 'UPDATE '.$table_name.' SET '.$property.'='.$val.' WHERE product_category ='.$category;
    $db_conn->exec($db_query);
}

/**
 * Updates property with a value. If the value is of an array type, only updates property for
 * specific product_id.
 *
 * @param  SQLite3 $db_conn     Connection to the DB.
 * @param  string  $table_name  The name of the table.
 * @param  string  $property    The property that is to be updated.
 * @param  mixed   $val         The value that is to be entered.
 */
function update_unique($db_conn, $table_name, $property, $val) {
    if(gettype($val) == 'array') {
        $db_query = 'UPDATE '.$table_name.' SET '.$property.'='.$val[0].' WHERE product_id='.$val[1];
        $db_conn->exec($db_query);;
    } else {
        $db_query = 'UPDATE '.$table_name.' SET '.$property.'='.$val;
        $db_conn->exec($db_query);
    }
}

/**
 * Selects a property from a table for a specific product_id.
 *
 * @param  SQLite3 $db_conn     References the DB connection.
 * @param  string  $table_name  The name of the table from which data will be fetched.
 * @param  string  $property    The property that will be fetched.
 * @param  mixed   $value       The value by which the property will be searched.
 *
 * @return mixed $result
 */
function select_by_id($db_conn, $table_name, $property, $value) {
    $db_query = 'SELECT '.$property.' FROM '.$table_name.' WHERE product_id='.$value;
    $result = $db_conn->querySingle($db_query);
    return $result;
}

/**
 * Selects an entry from the e_product_visits_unique table where there's an entry matching the product_id and client's ip
 *
 * @param  SQLite3 $db_conn     Reference to the DB connection.
 * @param  string  $table_name  The name of the table from which data will be fetched.
 * @param  mixed   $property    The property(-ies) of which the values will be compared.
 * @param  mixed   $values      The values for the search properties.
 *
 * @return mixed $result
 */
function select_unique($db_conn, $table_name, $property, $values) {
    $db_query = 'SELECT '.$property.' FROM '.$table_name.' WHERE '.$property.'='.$values[0].' AND product_visitor_ip='.$values[1];
    $result = $db_conn->querySingle($db_query);
    return $result;
}

/**
 * Selects and returns a single value for a property.
 *
 * @param  SQLite3 $db_conn     Reference to the DB connection object.
 * @param  string  $table_name  The name of the table with tracking data.
 * @param  mixed   $property    The value of a property that is needed.
 *
 * @return mixed $result
 */
function select_distinct($db_conn, $table_name, $property) {
    $db_query = "SELECT DISTINCT ".$property." FROM ".$table_name;
    $result = $db_conn->querySingle($db_query);
    return $result;
}

/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * Used to get a specific user property value from the tracking database tables.
 *
 * @param  string    $table             The table where the property value is stored.
 * @param  mixed     $byDataProperty    The property/properties of which the value is needed.
 * @param  mixed     $value             The value/values that are needed.
 * @param  SQLite3   $db_conn           Connection to the DB.
 * 
 * @return mixed
 */
function getProduct($table, $byDataProperty, $value) {
    $db_conn = new SQLite3("e_commerce.db");
    switch($byDataProperty) {
        case "product_id":
            $product_id = select_by_id($db_conn, $table, $byDataProperty, $value);
            return $product_id;
            break;
        case "product_visits":
            $product_visits = select_by_id($db_conn, $table, $byDataProperty, $value);
            return $product_visits;
            break;
        case "price_range_visits":
            $price_range_visits = select_by_id($db_conn, $table, $byDataProperty, $value);
            return $price_range_visits;
            break;
        case "product_id_unique":
            $visited_by_user = select_unique($db_conn, $table, "product_id", $value);
            return $visited_by_user;
            break;
        case "product_category":
            $prod_cat = select_by_id($db_conn, $table, $byDataProperty, $value);
            return $prod_cat;
            break;
        case "product_category_visits":
            $prod_cat_visits = select_by_id($db_conn, $table, $byDataProperty, $value);
            return $prod_cat_visits;
            break;
        case "price_range_visits_unique":
            $prod_cat_visits_unique = select_by_id($db_conn, $table, $byDataProperty, $value);
            return $prod_cat_visits_unique;
            break;
        case "shop_visits":
            $shop_visits = select_distinct($db_conn, $table, $byDataProperty, $value);
            return $shop_visits;
            break;
        case "shop_visits_unique":
            $shop_visits_unique = select_distinct($db_conn, $table, $byDataProperty, $value);
            return $shop_visits_unique;
            break;
    }
}

/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * Used to update product tracking properties in the tracking database tables.
 * Calls:
 * @see update_by_id
 * @see update_by_category
 *
 * @param  string  $table            The name of the table where the tracking item property will be edited. 
 * @param  string  $byDataProperty   The name of the property (used for filtering).
 * @param  mixed   $value            The value of the property (used for filtering).
 * @param  string  $product_property The new value that is to be entered.
 * @param  SQLite3 $db_conn          Connection to the DB.
 */
function updateProduct($table, $byDataProperty, $value, $product_property) {
    $db_conn = new SQLite3("e_commerce.db");
    switch($table) {
        case "e_product_visits": {
            update_by_id($db_conn, $table, $byDataProperty, $value, $product_property);
            break;
        }
        case "e_product_visit_data" : {
            update_by_id($db_conn, $table, $byDataProperty, $value, $product_property);
            break;
        }
        case "e_product_visit_data_visits" : {
            update_by_category($db_conn, "e_product_visit_data", $byDataProperty, $value, $product_property);
            break;
        } case "e_product_visits_unique" : {
            update_by_id($db_conn, $table, $byDataProperty, $value, $product_property);
            break;
        }
        case "e_product_visit_data_shop" : {
            update_by_id($db_conn, "e_product_visit_data", $byDataProperty, $value, $product_property);
            break;
        }
    }
}

/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 *
 * Inserts new product entries for the e_product_visits and e_product_visits_unique tracking
 * tables in the database.
 * 
 * Calls:
 * @see insert
 * 
 * @param  string  $table            The table where the product tracking data will be written.
 * @param  mixed   $byDataProperty   The property which will be written.
 * @param  mixed   $data             The provided data for the used property.
 * @param  SQLite3 $db_conn          Instance to the connection to the DB.
 *
 * @return void
 */
function insertProduct($table, $byDataProperty, $data) {
    $db_conn = new SQLite3("e_commerce.db");
    switch($table) {
        case "e_product_visits":
            insert($db_conn, $table, $byDataProperty, $data);
            break;
        case "e_product_visits_unique":
            insert($db_conn, $table, $byDataProperty, $data);
            break;
    }
}

/*================================================= SCRIPT BEGIN =================================================*/
/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * Counts the visits for each product, price range, shop, category.
 * Also counts the unique such visits by comparing the IP of the client.
 * This script is called when the user clicks on the link to the original shop that published the item for sale.
 * 
 * Calls @see insertProduct     to insert a product that previously didn't exist in the tracking db tables.
 * Calls @see getProduct        to get a specific visit tracking property for a product.
 * Calls @see updateProduct     to update the visit tracking data for an already existing product in the tracking db tables.
 * 
 * @param   string  $product_id                         The ID of the product that has been clicked on.
 * @param   string  $client_ip                          The IP of the client that initiated the script.
 * 
 * @param   int     $db_product_id                      The DB product ID. Used to track if tracking data has been entered for this product previously.
 * @param   int     $db_product_visits(unique)          The unflitered total visits for the specific product_id.
 * @param   int     $db_price_range_visits(unique)      The unfiltered total visits for the price range of the product.
 * @param   int     $db_shop_visits(unique)             The unfiltered total visits for the shop that sells the product.
 * @param   int     $db_product_category_visits(unique) The unfiltered total visits for the category of the product.
 * 
 * @param   string  $db_product_category                The category of the provided product_id.
 */
if (isset($_REQUEST['product_id']) && isset($_REQUEST['client_ip'])) {
    $product_id = $_REQUEST['product_id'];
    $client_ip = $_REQUEST['client_ip'];
    
    $db_product_id = getProduct("e_product_visits", "product_id", $product_id);
    if($db_product_id == "" || $db_product_id == null) {
        $db_product_id = $product_id;
        insertProduct("e_product_visits", "product_id", $db_product_id);
    }

    $db_product_visits = getProduct("e_product_visits", "product_visits", $product_id);
    if($db_product_visits == "" || $db_product_visits == null) {
        $db_product_visits = 0;
    }
    
    $db_price_range_visits = getProduct("e_product_visit_data", "price_range_visits", $product_id);
    if($db_price_range_visits == "" || $db_price_range_visits == null) {
        $db_price_range_visits = 0;
    }

    $db_shop_visits = getProduct("e_product_visit_data", "shop_visits", $product_id);
    if($db_shop_visits == "" || $db_shop_visits == null) {
        $db_shop_visits = 0;
    }

    $db_product_category_visits = getProduct("e_product_visit_data", "product_category_visits", $product_id);
    if($db_product_category_visits == "" || $db_product_category_visits == null) {
        $db_product_category_visits = 0;
    }

    $db_product_category = getProduct("e_product_visit_data", "product_category", $product_id);
    $db_product_category = '"'.$db_product_category.'"';

    $db_product_visits = $db_product_visits + 1;
    $db_price_range_visits = $db_price_range_visits + 1;
    $db_shop_visits = $db_shop_visits + 1;
    $db_product_category_visits = $db_product_category_visits + 1;
    updateProduct("e_product_visits", "product_visits", $db_product_visits, $product_id);
    updateProduct("e_product_visit_data", "price_range_visits", $db_price_range_visits, $product_id);
    updateProduct("e_product_visit_data", "shop_visits", $db_shop_visits, null);
    updateProduct("e_product_visit_data_visits", "product_category_visits", $db_product_category_visits, $db_product_category);

    // If there's an IP provided. Sometimes the API used to get user's IP lock up and don't provide an IP.
    if($client_ip != "" || $client_ip != null) {
        $db_visited_by_user = getProduct("e_product_visits_unique", "product_id_unique", array($product_id, '"'.$client_ip.'"'));
        
        // Insert / Update anything only if no entry with this client IP exists for this specific product_id.
        if($db_visited_by_user == "" || $db_visited_by_user == null) {
            insertProduct("e_product_visits_unique", array("product_id", "product_visitor_ip"), array($db_product_id, $client_ip));

            $db_product_visits_unique = getProduct("e_product_visits_unique", "product_visits", $db_product_id);
            if($db_product_visits_unique == "" || $db_product_visits_unique == null) {
                $db_product_visits_unique = 0;
                $db_product_visits_unique = $db_product_visits_unique + 1;
            } else {
                $db_product_visits_unique = $db_product_visits_unique + 1;
            }

            $db_price_range_visits_unique = getProduct("e_product_visit_data", "price_range_visits_unique", $product_id);
            if($db_price_range_visits_unique == "" || $db_price_range_visits_unique == null) {
                $db_price_range_visits_unique = 0;
                $db_price_range_visits_unique = $db_price_range_visits_unique + 1;
            } else {
                $db_price_range_visits_unique = $db_price_range_visits_unique + 1;
            }

            $db_shop_visits_unique = getProduct("e_product_visit_data", "shop_visits_unique", $product_id);
            if($db_shop_visits_unique == "" || $db_shop_visits_unique == null) {
                $db_shop_visits_unique = 0;
                $db_shop_visits_unique = $db_shop_visits_unique + 1;
            } else {
                $db_shop_visits_unique = $db_shop_visits_unique + 1;
            }

            $db_product_category_visits_unique = getProduct("e_product_visit_data", "product_category_visits_unique", $product_id);
            if($db_product_category_visits_unique == "" || $db_product_category_visits_unique == null) {
                $db_product_category_visits_unique = 0;
                $db_product_category_visits_unique = $db_product_category_visits_unique + 1;
            } else {
                $db_product_category_visits_unique = $db_product_category_visits_unique + 1;
            }

            updateProduct("e_product_visits_unique", "product_visits", $db_product_visits_unique, $db_product_id);
            updateProduct("e_product_visit_data", "price_range_visits_unique", $db_price_range_visits_unique, $product_id);
            updateProduct("e_product_visit_data_shop", "shop_visits_unique", $db_shop_visits_unique, null);
            updateProduct("e_product_visit_data_visits", "product_category_visits_unique", $db_product_category_visits_unique, $db_product_category);
        }
    }
} else {
    echo ("No data");
}
?>