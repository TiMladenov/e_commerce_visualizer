<?php

// use function PHPSTORM_META\type;

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

/**
 * @author      Tihomir Mladenov, 01.08.2019
 * @version     1.0
 * 
 * @access      public
 * 
 * @var Int     $rowid                The rowid from the DB.
 * @var Int     $product_id           The product id. Same as supplied by shop's API.
 * @var String  $product_name         The name of the product.
 * @var Float   $product_price        The product price.
 * @var String  $product_price_curr   The currency of the product price.
 * @var String  $product_image        The URL to the product image.
 * @var String  $product_description  The description of the product.
 * @var String  $product_shop_vendor  The name of the shop that sells the product.
 * @var String  $product_is_promo     True or False depending on whether the product is promo.
 * @var String  $product_category     The category of the product.
 */

class productClass {
    private $rowid = 0;
    private $product_id = 0;
    private $product_name = "";
    private $product_price = 0.0;
    private $product_price_curr = "";
    private $product_image = "";
    private $product_description = "";
    private $product_shop_vendor = "";
    private $product_is_promo = "";
    private $product_category = "";

    
    /**
     * Sets the $rowid value for the object.
     *
     * @param  Int $rowid
     */
    function setRowid($rowid) { $this->rowid = $rowid; }
    
    /**
     * Returns the $rowid value for the object.
     *
     * @return Int
     */
    function getRowid() { return $this->rowid; }
    
    /**
     * Sets the $product_id for the object.
     *
     * @param  Int $product_id
     */
    function setProduct_id($product_id) { $this->product_id = $product_id; }
    /**
     * Returns the $product_id for the object.
     *
     * @return  Int $product_id
     */
    function getProduct_id() { return $this->product_id; }
    
    /**
     * Sets the $product_name of the object.
     *
     * @param string $product_name
     */
    function setProduct_name($product_name) { $this->product_name = $product_name; }
    /**
     * Returns the $product_name of the object.
     *
     * @return string $product_name
     */
    function getProduct_name() { return $this->product_name; }
    
    /**
     * Sets the $product_price of the object.
     *
     * @param float $product_price
     */
    function setProduct_price($product_price) { $this->product_price = $product_price; }
    /**
     * Returns the $product_price of the object.
     *
     * @return float $product_price
     */
    function getProduct_price() { return $this->product_price; }
    /**
     * Sets the $product_price_curr for the object
     *
     * @param  string $product_price_curr
     */
    function setProduct_price_curr($product_price_curr) { $this->product_price_curr = $product_price_curr; }
    /**
     * Returns the $product_price_curr for the object
     *
     * @return  string $product_price_curr
     */
    function getProduct_price_curr() { return $this->product_price_curr; }
    /**
     * Sets the $product_image for the object
     *
     * @param  string $product_image
     */
    function setProduct_image($product_image) { $this->product_image = $product_image; }
    /**
     * Returns the $product_image for the object
     *
     * @return  string $product_image
     */
    function getProduct_image() { return $this->product_image; }
    /**
     * Sets the $product_description of the object
     *
     * @param  string $product_description
     */
    function setProduct_description($product_description) { $this->product_description = $product_description; }
    /**
     * Returns the $product_description of the object
     *
     * @return  string $product_description
     */
    function getProduct_description() { return $this->product_description; }
    /**
     * Sets the $product_shop_vendor for the object
     *
     * @param  string $product_shop_vendor
     */
    function setProduct_shop_vendor($product_vendor) { $this->product_shop_vendor = $product_vendor; }
    /**
     * Returns the $product_shop_vendor for the object
     *
     * @return  string $product_shop_vendor
     */
    function getProduct_shop_vendor() { return $this->product_shop_vendor; }
    /**
     * Sets the $product_is_promo of the object
     *
     * @param  string $product_is_promo
     */
    function setProduct_is_promo($is_promo) { $this->product_is_promo = $is_promo; }
    /**
     * Returns the $product_is_promo of the object
     *
     * @return  string $product_is_promo
     */
    function getProduct_is_promo() { return $this->product_is_promo; }
    /**
     * Sets the $product_category of the object
     *
     * @param  string $product_category
     */
    function setProduct_category($cat) { $this->product_category = $cat; }
    /**
     * Returns the $product_category of the object
     *
     * @return  string $product_category
     */
    function getProduct_category() { return $this->product_category; }
}


/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * Creats JSON formatted string, appens the status_code (true or false) and sends it to the front end.
 *
 * @param  boolean  $_status     True, if data was fetched, false if no data was fetched from the DB.
 * @param  array    $_data       The array with the fetched data from the DB.
 *
 * @return json     $returnArr   JSON formatted string with the data from the DB.
 */
function msgReturn ($_status = null, $_data = null) {
    if($_status == true) {
        $returnArr = json_encode(
            array(
                'status_code' => $_status,
                'data' => $_data,
            )
        );
        echo $returnArr;
    } else if($_status == false) {
        $returnArr = json_encode(
            array(
                'status_code' => $_status,
                'data' => $_data,
            )
        );
        echo $returnArr;
    }
}

/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * Removes the special characters from the search text string entered by the user and
 * coverts it to an array.
 *
 * @param  string   $_remove_special_char   Removes the speacial characters from the search text entered by the user.
 * @param  string   $_search_text           Search text string. 
 * 
 * @return array    $_return_arr
 */
function getSearchArr($_search_text = null) {
    $_remove_special_char = preg_replace('/[^a-z0-9]/i', ' ', $_search_text);
    $_search_text = explode(" ", $_remove_special_char);
    $_return_arr = array();

    foreach($_search_text as $_word) {
        if (ctype_alnum($_word)) {
            $_return_arr [] = $_word;
        }
    }
    return $_return_arr;
}


/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * Returns the number of existing DB rows for product by product_id.
 *
 * @param  SQLite3 $_db_conn  Instance of the active DB connection.
 * @param  int     $_id       Id of the specific product.
 *
 * @return int     $result    Returns the number of existing product rows with this Id.
 */
function get_row_count($_db_conn, $_id) {
    $query = $_db_conn->query('SELECT COUNT(*) FROM e_product WHERE product_id='.$_id);
    $result = $query->fetchArra();
    return $result['count'];
}

/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * Sends a query to the DB where the result should be either 50 or 30 results after the provided product_id.
 * Creates instance to 'productClass' and fills it with the info from each returned row from the DB.
 * Adds each 'productClass' instance to an array. When the loop through the DB results is finished, the array of
 * objects is returned.
 *
 * @param  Sqlite3      $_db_conn    Instance of the active DB connection.
 * @param  int          $_min        The product_id from which 30 more rows should be added.
 * @param  int          $_max        Controls the max number of rows to be returned. 50 on start. 30 after that on scroll end.
 * 
 * @param  string       $query       The query to be sent to the DB.
 * @param  productClass $product     An instance of productClass to structurally save the data from each query.
 * @param  int          $index       Used to insert productClass object in each index of $product_arr
 * 
 * @return array        $product_arr Array with the data fetched from each query.
 */
function get_generic_content($_db_conn, $_min, $_max) {
    $query = $_db_conn->query('SELECT rowid, product_id, product_name, product_price, product_price_curr, product_image, product_is_promo, product_category,
    product_description, product_shop_url FROM e_product WHERE product_id >'.$_min.' LIMIT '.$_max);
    
    $product_arr = array();
    $index = 0;
    while($row = $query->fetchArray(SQLITE3_ASSOC)) {
        $product = new productClass;
        foreach ($row as $key => $value) {
            switch($key) {
                case "rowid":
                    $product->setRowid($value);
                    break;
                case "product_id":
                    $product->setProduct_id($value);
                    break;
                case "product_name":
                    $product->setProduct_name($value);
                    break;
                case "product_price":
                    $product->setProduct_price($value);
                    break;
                case "product_price_curr":
                    $product->setProduct_price_curr($value);
                    break;
                case "product_is_promo":
                    $product->setProduct_is_promo($value);
                    break;
                case "product_category":
                    $product->setProduct_category($value);
                    break;
                case "product_image":
                    $product->setProduct_image($value);
                    break;
                case "product_description":
                    $product->setProduct_description($value);
                    break;
                case "product_shop_url":
                    $product->setProduct_shop_vendor($value);
                default:
                    continue;
            }
        }
        $product_arr[$index] = $product;
        $index = $index + 1;
    }
    return $product_arr;
}


/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * Sends a query to the DB where the results are limited to 30 after the provided product_id. Used to fetch additional results
 * to be added to the front-end when the bottom of the page is reached.
 *
 * @param  SQLite3      $_db_conn   Reference to the DB connection.
 * @param  int          $_min       The product_id from which 30 more results should be returned.
 * @param  productClass $product    Stores in a structured manner the column information to each returned row from the DB.
 *
 * @return productClass $product
 */
function add_content($_db_conn, $_min) {
    $qry_builder = "SELECT rowid, product_id, product_name, product_price, product_price_curr, product_is_promo, product_category, 
    product_image, product_description, product_shop_url FROM e_product WHERE product_id>".$_min." LIMIT 30";
    $query = $_db_conn->query($qry_builder);
    $product = "";
    while($row = $query->fetchArray(SQLITE3_ASSOC)) {
        $product = new productClass;
        foreach ($row as $key => $value) {
            switch($key) {
                case "rowid":
                    $product->setRowid($value);
                    break;
                case "product_id":
                    $product->setProduct_id($value);
                    break;
                case "product_name":
                    $product->setProduct_name($value);
                    break;
                case "product_price":
                    $product->setProduct_price($value);
                    break;
                case "product_price_curr":
                    $product->setProduct_price_curr($value);
                    break;
                case "product_is_promo":
                    $product->setProduct_is_promo($value);
                    break;
                case "product_category":
                    $product->setProduct_category($value);
                    break;
                case "product_image":
                    $product->setProduct_image($value);
                    break;
                case "product_description":
                    $product->setProduct_description($value);
                    break;
                case "product_shop_url":
                    $product->setProduct_shop_vendor($value);
                default:
                    continue;
            }
        }
    }
    return $product;
}

/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * Used to fetch 50 results after the provided product_id. Adds the data from each returned row to an instance of 
 * @see productClass.
 *
 * @param  SQLite3      $_db_conn    Reference to the DB connection object.
 * @param  mixed        $_id         The provided product_id.
 * @param  mixed        $_max        The number of results to be returned after the product_id.
 *
 * @return productClass @product
 */
function get_content($_db_conn, $_id, $_max) {
    $qry_builder = "SELECT rowid, product_id, product_name, product_price, product_price_curr, product_is_promo, product_category,
    product_image, product_description, product_shop_url FROM e_product WHERE product_id=".$_id." LIMIT ".$_max;
    $query = $_db_conn->query($qry_builder);
    $product = "";
    while($row = $query->fetchArray(SQLITE3_ASSOC)) {
        $product = new productClass;
        foreach ($row as $key => $value) {
            switch($key) {
                case "rowid":
                    $product->setRowid($value);
                    break;
                case "product_id":
                    $product->setProduct_id($value);
                    break;
                case "product_name":
                    $product->setProduct_name($value);
                    break;
                case "product_price":
                    $product->setProduct_price($value);
                    break;
                case "product_price_curr":
                    $product->setProduct_price_curr($value);
                    break;
                case "product_is_promo":
                    $product->setProduct_is_promo($value);
                    break;
                case "product_category":
                    $product->setProduct_category($value);
                    break;
                case "product_image":
                    $product->setProduct_image($value);
                    break;
                case "product_description":
                    $product->setProduct_description($value);
                    break;
                case "product_shop_url":
                    $product->setProduct_shop_vendor($value);
                default:
                    continue;
            }
        }
    }
    return $product;
}


/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 *
 * Fetches the information from each data member of a @see productClass object
 * and returns in as an array.
 * 
 * @param  productClass $object
 *
 * @return array
 */
function makeObjArray($object) {
    return array(
        "rowid" => $object->getRowid(),
        "product_id" => $object->getProduct_id(),
        "product_name" => $object->getProduct_name(),
        "product_price" => $object->getProduct_price(),
        "price_curr" => $object->getProduct_price_curr(),
        "product_is_promo" => $object->getProduct_is_promo(),
        "product_category" => $object->getProduct_category(),
        "product_image" => $object->getProduct_image(),
        "product_descr" => $object->getProduct_description(),
        "product_shop_url" => $object->getProduct_shop_vendor()
    );
}


/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * The function is separated into two parts. Flag == New or Flag == Add.
 * If the flag is 'New', such search is initiated, as to return brand new results.
 * If the flag is 'Add', such search is initiated, as to attempt to return additional 
 * results to the already added valid search results.
 * 
 * Calls @see makeObjArray  and provides it with productClass object to restructure its data as an array.
 * Calls @see msgReturn     and provides it with the array of found results.
 *
 * @param  Sqlite3  $_db_conn           References the active DB connection.
 * @param  string   $_search_filter     The value from the search filter. Either 0 'Name' or 1 'Description'.
 * @param  string   $_search_criteria   The search criteria string as entered by the user.
 * @param  int      $_min_index         The minimum index (product_id) from which search should be initiated.
 * @param  string   $flag               'New' for new search or 'Add' for additional search attempt.
 */
function find_products($_db_conn, $_search_filter, $_search_criteria, $_min_index, $flag) {
    // If new data fetch needs to be performed.
    if ($flag == "new") {
        // If the user didn't provide any search criteria (text), do a random search.
        if($_search_criteria[0] == "empty") {
            $product = get_generic_content($_db_conn, $_min_index, 50);
            $arr = array();
            $index = 0;
            foreach($product as $item) {
                $arr[$index] = makeObjArray($item);
                $index = $index + 1;
            }
            msgReturn(true, $arr);
        } 
        // If the user did provide a search criteria, do a specific search.
        else {
            // 1. If the selected filter is 'Name'
            // 2. Get the product_id and product_name of all DB product entries.
            // 3. Go through the results and compare if any part from a product's name equals a word from user's search text.
            // 4. If yes, add the product_id of that product to an array of results. 
            if($_search_filter == 0) {
                $find_items = $_db_conn->query("SELECT product_id, product_name FROM e_product");
                $result_arr = array();
                while($result = $find_items->fetchArray(SQLITE3_ASSOC)) {
                    foreach($_search_criteria as $word) {
                        $prod_name = strtolower($result["product_name"]);
                        $word = strtolower($word);
                        if(strpos($prod_name, $word)) {
                            $result_arr[] = $result['product_id'];
                        }
                    }
                }
                // 1. If there's some result found from the code block above.
                // 2. Go through the array of results and get the product_id,
                // call 'get_content' to get all of the information for the specified
                // product_id. Make a limit to get 50 results as a whole.
                // 3. Get the data as an array from the returned 'productClass' instance
                // by calling the 'makeObjArray' function.
                // 4. Call msgReturn and pass it the array of arrays (with row data each).
                if(sizeof($result_arr) > 0) {
                    $arr = array();
                    $index = 0;
                    foreach($result_arr as $result_id) {
                        if($index <= 50) {
                            $product = get_content($_db_conn, $result_id, 50);
                            $arr[$index] = makeObjArray($product);
                            $index = $index + 1;
                        } else {
                            break;
                        }
                    }
                    msgReturn(true, $arr);
                } 
                // If no results were found, send a warning message.
                else {
                    msgReturn(false, "No results via name found");
                }
            } 
            // 1. If the selected filter is 'Description'
            // 2. Get the product_id and product_description of all DB product entries.
            // 3. Go through the results and compare if any part from a product's description equals a word from user's search text.
            // 4. If yes, add the product_id of that product to an array of results. 
            else if ($_search_filter == 1) {
                $find_items = $_db_conn->query("SELECT product_id, product_description FROM e_product");
                $result_arr = array();
                while($result = $find_items->fetchArray(SQLITE3_ASSOC)) {
                    foreach($_search_criteria as $word) {
                        $prod_name = strtolower($result["product_description"]);
                        $word = strtolower($word);
                        if(strpos($prod_name, $word)) {
                            $result_arr[] = $result['product_id'];
                        }
                    }
                }
                // 1. If there's some result found from the code block above.
                // 2. Go through the array of results and get the product_id,
                // call 'get_content' to get all of the information for the specified
                // product_id. Make a limit to get 50 results as a whole.
                // 3. Get the data as an array from the returned 'productClass' instance
                // by calling the 'makeObjArray' function.
                // 4. Call msgReturn and pass it the array of arrays (with row data each).
                if(sizeof($result_arr) > 0) {
                    $arr = array();
                    $index = 0;
                    foreach($result_arr as $result_id) {
                        if($index <= 50) {
                            $product = get_content($_db_conn, $result_id, 50);
                            $arr[$index] = makeObjArray($product);
                            $index = $index + 1;
                        } else {
                            break;
                        }
                    }
                    msgReturn(true, $arr);
                } 
                // Return a warning if no results were found.
                else {
                    msgReturn(false, "No results via description found");
                }
            }
        }
    } 
    // If additional data fetch needs to be performed.
    else if($flag == "add") {
        // If the user didn't provide any search criteria, try to add 30 more rows with generic content.
        if($_search_criteria[0] == "empty") {
            $product = get_generic_content($_db_conn, $_min_index,30);
            // If there's a valid row with data returned, fetch the returned
            // data to an array format and add it to a parent array, which at the
            // end pass to 'msgReturn'.
            if($product != "") {
                $arr = array();
                $index = 0;
                $min_ind = $_min_index;
                foreach($product as $item) {
                    if($min_ind != $item->getProduct_id()) {
                        $arr[$index] = makeObjArray($item);
                        $index = $index + 1;
                        $min_ind = $item->getProduct_id();
                    }
                }
                msgReturn(true, $arr);
            }
        } 
        // If the user did provide a search criteria
        else {
            // If the search filter is search by 'Name', get the product_id and product_name of all DB product entries,
            // if any part of each db product name matches anything from the search text of the user, add the product_id
            // to an array of results.
            if($_search_filter == 0) {
                $find_items = $_db_conn->query("SELECT product_id, product_name FROM e_product");
                $result_arr = array();
                while($result = $find_items->fetchArray(SQLITE3_ASSOC)) {
                    foreach($_search_criteria as $word) {
                        $prod_name = strtolower($result["product_name"]);
                        $word = strtolower($word);
                        if(strpos($prod_name, $word)) {
                            $result_arr[] = $result['product_id'];
                        }
                    }
                }
                // If any unique search matches are present, add the whole DB row data for the specific match product_id that
                // is greater than the last product_id loaded in the Front-End. Limit results to 30.
                if(sizeof($result_arr) > 0) {
                    $arr = array();
                    $index = 0;
                    $min_ind = $_min_index;
                    foreach($result_arr as $result_id) {
                        $product = add_content($_db_conn, $min_ind);
                        if($product != "") {
                            if ($min_ind != $product->getProduct_id()) {
                                $arr[$index] = makeObjArray($product);
                                $index = $index + 1;
                                $min_ind = $product->getProduct_id();
                            }
                        }
                    }
                    msgReturn(true, $arr);
                } else {
                    msgReturn(false, "No additional results via name found");
                }
            } 
            // If the search filter is search by 'Description', get the product_id and product_description of all DB product entries,
            // if any part of each db product description matches anything from the search text of the user, add the product_id
            // to an array of results.
            else if ($_search_filter == 1) {
                $find_items = $_db_conn->query("SELECT product_id, product_description FROM e_product");
                $result_arr = array();
                while($result = $find_items->fetchArray(SQLITE3_ASSOC)) {
                    foreach($_search_criteria as $word) {
                        $prod_name = strtolower($result["product_description"]);
                        $word = strtolower($word);
                        if(strpos($prod_name, $word)) {
                            $result_arr[] = $result['product_id'];
                        }
                    }
                }
                // If any unique search matches are present, add the whole DB row data for the specific match product_id that
                // is greater than the last product_id loaded in the Front-End. Limit results to 30.
                if(sizeof($result_arr) > 0) {
                    $arr = array();
                    $index = 0;
                    $min_ind = $_min_index;
                    foreach($result_arr as $result_id) {
                        
                        $product = add_content($_db_conn, $min_ind);
                        if($product != "") {
                            if ($min_ind != $product->getProduct_id()) {
                                $arr[$index] = makeObjArray($product);
                                $index = $index + 1;
                                $min_ind = $product->getProduct_id();
                            }
                        }
                    }
                    msgReturn(true, $arr);
                } 
                // If no results were found, return a warning.
                else {
                    msgReturn(false, "No additional results via description found");
                }
            }
        }
    }
}

/*========================================== SCRIPT BEGIN ==========================================*/

/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * The start point of the main.php script. Handles the provided input data, does basic consistency check of the
 * call parameters, creates a SQLite3 db connection instance and provides all the data to @see find_products
 * function.
 * 
 * @param  string $search_filter    The search filter - 0 is for search by Name, 1 is for search by Description.
 * @param  string $search_criteria  The search text that is entered by the user for a specific search.
 * @param  int    $min_index        The minimum index (product_id) from which a search should be initiated.
 * @param  string $flag             The flag used to denote if a new or additional search is being initiated.
 */

if(isset($_REQUEST["search_field"]) && isset($_REQUEST["search_filter"])) {
    $search_filter = $_REQUEST["search_filter"];
    $min_index = (int)$_REQUEST["min_index"];
    $flag = $_REQUEST["flag"];

    $db_conn = new SQLite3('e_commerce.db');

    if($min_index <=0 || $min_index == null) {
        $min_index = 1;
    }
    if($flag =="" || $flag == null) {
        $flag = "new";
    }
    $search_criteria = $_REQUEST["search_field"];
    if ($search_criteria == "" || $search_criteria == null) {
        $search_criteria = "empty";
        $search_criteria_arr = getSearchArr($search_criteria);
        find_products($db_conn, $search_filter, $search_criteria_arr, $min_index, $flag);
    } else {
        $search_criteria = strtolower($_REQUEST["search_field"]);
        $search_criteria_arr = getSearchArr($search_criteria);
        find_products($db_conn, $search_filter, $search_criteria_arr, $min_index, $flag);
    }
} 
// If something's not right with the provided initial paramenters, a warning is returned.
else {
    msgReturn(false, "Please enter search criteria.");
}
?>