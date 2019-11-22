/**
 * @author  Tihomir Mladenov, 29.07.2019
 * @version 1.0
 * 
 * Gets the selected values from the selected checkboxes and creates two
 * arrays with parameters, which are then sent to the BE to make a DB
 * query. The results are then returned in json format and passed to
 * @see parseResponse()
 * 
 * @param {object}  chk1_parent     References the parent DOM object of all
 *                                  checkboxes.
 * @param {array}   qry_arr         Stores the query parameters for the first
 *                                  three checkboxes.
 * @param {array}   qry_arr_second  Stores the query parameters from the fourth
 *                                  checkbox.
 * @param {string}  obj_loc         URL to the script to handle the request.
 *
 * @return {JSON}
 */
function getCheckOptions() {
    var chk1_parent = document.getElementById("chk_parent").childNodes;
    var qry_arr = [];
    var qry_arr_second = [];
    for(var i = 0; i < chk1_parent.length; i++) {
        if(chk1_parent[i].nodeName != '#text') {
            if(chk1_parent[i].checked == true){
                if(i >= 13) {
                    qry_arr_second.push(chk1_parent[i].getAttribute("name").split(" "));
                } else {
                    qry_arr.push(chk1_parent[i].getAttribute("name").split(" "));
                }
            }
        }
    }
    if(qry_arr.length <= 0 && qry_arr_second.length <=0) {
        alert("Please select search criteria!");
        return false;
    }
    qry_arr = qry_arr.toString();
    qry_arr_second = qry_arr_second.toString();

    var obj_loc = window.location.origin;
    obj_loc = obj_loc + '/'+ 'admin.php';

    $.ajax({
        type : 'POST',
        url : obj_loc,
        data : {
            'control' : 'browse',
            'query_one' : qry_arr,
            'query_two' : qry_arr_second
        },
        async : false,
        success : function(result) {
            parseResponse(result);
        }
    });
    
}

/**
 * @author  Tihomir Mladenov, 29.07.2019
 * @version 1.0
 * 
 * Receives a JSON formatted string which it then parses to a
 * JSON object. Loops through the JSON, gets the data for
 * each object and populates the UI with it.
 * 
 * @see getCkeckOptions()
 * 
 * @param {string}  result                  JSON formatted string with the fetched
 *                                          data from the BE.
 * @param {JSON}    json                    JSON object from the result variable.
 * @param {JSON}    main_content            Stores the data from the first query, if
 *                                          returned.
 * @param {json}    secondary_content       Stores the data from the second query, if
 *                                          returned.
 * @param {object}  table_visitor_data      References the table for the visitor data
 * @param {object}  table_shop_data         References the table for the shop data
 * @param {object}  table_price_range_data  References the table for the price range data
 * @param {object}  table_unique_visit_data References the table for the unique user visits
 * @param {string}  content                 Used to store each new row before it is added to the
 *                                          DOM parent element.
 */
function parseResponse(result) {
    var json = JSON.parse(result);
    var main_content = json.main;
    var secondary_content = json.secondary;

    var table_visitor_data = document.getElementById("table_visitor_body");
    var table_shop_data = document.getElementById("table_shop_body");
    var table_price_range_data = document.getElementById("table_price_range_body");
    var table_unique_visit_data = document.getElementById("table_unique_visit_body");
    table_visitor_data.innerHTML = "";
    table_shop_data.innerHTML = "";
    table_price_range_data.innerHTML = "";
    table_unique_visit_data.innerHTML = "";
    
    var content = "";

    for(var i = 0; i < main_content.length; i++) {
        if(main_content[i].product_name != null && main_content[i].product_category != null) {
            if(main_content[i].product_category_visits == null) {
                main_content[i].product_category_visits = 0;
            }
            content = "<tr><th scope=\"row\">"+(i+1)+"</th><td>"+main_content[i].product_name+"</td><td>"+main_content[i].product_category+"</td><td>"+main_content[i].product_category_visits+"</td></tr>";
            table_visitor_data.innerHTML += content;
        }
        if(main_content[i].shop_name != null) {
            for(var j = i + 1; j < main_content.length; j++) {
                if(i == 0) {
                    if(main_content[i].shop_visits == null) {
                        main_content[i].shop_visits = 0;
                    }
                    content = "<tr><th scope=\"row\">"+j+"</th><td>"+main_content[i].shop_name+"</td><td>"+main_content[i].shop_visits+"</td></tr>";
                    table_shop_data.innerHTML += content;
                    break;
                } else {
                    if(main_content[i].shop_name != main_content[j].shop_name) {
                        if(main_content[i].shop_visits == null) {
                            main_content[i].shop_visits = 0;
                        }
                        content = "<tr><th scope=\"row\">"+(i+1)+"</th><td>"+main_content[i].shop_name+"</td><td>"+main_content[i].shop_visits+"</td></tr>";
                        table_shop_data.innerHTML += content;
                    }
                }
            }
        }
        if(main_content[i].price != null) {
            if(main_content[i].price_range_visits == null) {
                main_content[i].price_range_visits = 0;
            }

            var price = parseInt(main_content[i].price);
            var price_range = "";

            var range_visits = 0;
            var element = null;
            // Checks the price value. Adds it to the respectable price range
            if(price >= 0 && price < 5) {
                price_range = "0 - 5";
            } else if(price >= 5 && price < 10) {
                price_range = "5 - 10";
            } else if(price >= 10 && price < 20) {
                price_range = "10 - 20";
            } else if(price >= 20 && price < 40) {
                price_range = "20 - 40";
            } else if(price >= 40 && price < 80) {
                price_range = "40 - 80";
            } else if(price >= 80 && price < 160) {
                price_range = "80 - 160";
            } else if(price >= 160) {
                price_range = "160 - ...";
            }
            content = "<tr><th scope=\"row\">"+(i+1)+"</th><td>"+main_content[i].product_name+"</td><td>"+main_content[i].price+"</td><td>"+main_content[i].price_curr+"</td>\
            <td>"+price_range+"</td><td>"+main_content[i].price_range_visits+"</td></tr>";
            table_price_range_data.innerHTML += content;

            if(main_content[i].price_range_visits > 0) {
                setPriceRangeVisitsTotal(price_range, main_content[i].price_range_visits);
            }
        }
    }
    if (secondary_content != "" && secondary_content != null) {
        for(var i = 0; i < secondary_content.length; i++) {
            content = "<tr><th scope=\"row\">"+(i+1)+"</th><td>"+secondary_content[i].product_id+"</td><td>"+secondary_content[i].product_name+"</td><td>"+secondary_content[i].product_visits+"</td></tr>"
            table_unique_visit_data.innerHTML += content;
        }
    }
}

// Sets the total visit count for each price-range.
function setPriceRangeVisitsTotal(price_range, content_visits) {
    element = document.getElementById(price_range);
    range_visits = parseInt(element.innerText);
    range_visits = range_visits + content_visits;
    element.innerText = range_visits;
}