/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * Compares current scroll height to window's max height. If the end of window scroll is met it makes a call 
 * to a php script that fetches data from the DB to load 30 more rows, if available,
 * and returns them to the front end in JSON format.
 * Calls @see sendData for new data to be fetched.
 *
 * @param {object}  last_loaded     Gets the last added element in the span of added elements.
 * @param {int}     ind             Gets the index of the last added element, which is stored in the
 *                                  'name' html attribute.
 */
$(document).ready (
    function() {
        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() == getDocumentHeight()) {
                
                var last_loaded = document.getElementById("span").lastChild;
                var ind = last_loaded.getAttribute("name");
                sendData(0, parseInt(ind), "add");
            }
        });
    }
);

// Simple function reset the main price range filter when new data is loaded.
function resetFilters() {
    $("#category_price_filter")[0].selectedIndex = 0;
    $("#category_price_filter").trigger("onchange");
}


/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * Sends the data and criteria entered in the front-end form to the back-end for processing.
 * If there's data returned, populates the UI, if not, displays an error message. Calls:
 * @see parseJson       to parse the returned JSON formatted string.
 * @see sortObjects     to sort returned rows in ascending order by product_id.
 * @see setCategories   to load the returned categories in the dropdown used for fitlering the rows by category.
 *
 * @param {boolean}  _new_search    If true - initiates new search, if false - just adds rows to existing ones.
 * @param {int}      min_index      The minimum index from which the search should be initiated.
 * @param {string}   _flag          A flag to point the function to the needed action: Add (to existing rows) or New (only add new rows).
 * 
 * @param {string}   searchField    Stores the text entered by the user in the search field.
 * @param {string}   searchFilter   Stores the selected filter option by the user - (name or description search).
 * 
 * @param {object}  http_req        Instance of createXMLHttpInstance to send an async request to the back-end.
 * @param {object}  form            Instance of FormData to store all the data that is to be sent to the back-end
 *                                  through the 'http_req'.
 * 
 * @param {string}  resp            The JSON formatted string returned by the back-end.
 * @param {JSON}    json            JSON object from the data in 'resp' varialbe.
 * @param {boolean} status_code     The status code returned from the server. Only processed if true, everything else is considered as false (problem).
 * @param {JSON}    data            JSON object with the actual row data from @see json.
 * 
 * @param {object}  span            References the span DOM element where all the returned rows will loaded to the front-end.
 * 
 */
function sendData(_new_search, min_index, _flag) {
    var new_search = _new_search;
    var flag = _flag;
    var searchField = document.forms["submitForm"].elements['search_field'].value;
    var searchFilter = document.forms["submitForm"].elements['search_filter'].value;

    // var searchCategory = document.getElementById("category_filter");
    // var searchPromo = document.getElementById("category_promotions");
    // var priceFilter = document.getElementById("category_price_filter");

    // If text was entered by the user in the search field
    if(searchField != null) {
        var http_req = new createXMLHttpInstance();
        var form = new FormData(document.getElementById("submitForm"));

        http_req.onreadystatechange = function(){
            if(this.readyState==4 && this.status==200) {
                var resp = this.responseText;
                var json = parseJson(resp);
                
                var status_code = json.status_code;
                var data = json.data;
                data = sortObjects(data);

                var span = document.getElementById("span");
                if(new_search == 1) {
                    span.innerHTML = "";
                }
                
                var search_button = document.getElementById("search_button");
                
                // Loads the data from the JSON into the front-end, if there was actual data returned.
                if(status_code == true) {
                    if(data.length > 0) {
                        var categories = new Array(data.length);
                        for(var i = 0; i < data.length; i++) {
                            var my_module = "\
                            <article data-is-promo="+data[i].product_is_promo+" data-product-category="+data[i].product_category+" \
                            data-product-price="+data[i].product_price+" name="+data[i].product_id+" class=\"row mt-2 border rounded\">\
                                <div class=\"item col-2 text-left border-right align-items-center\">"+data[i].product_name+"</div>\
                                <div class=\"d-flex item col-2 text-left border-right align-items-center\">\
                                <img src="+data[i].product_image+" class=\"img-thumbnail\" alt=\"Product image\" onclick=\"window.open(this.src)\">\
                                </div>\
                                <div class=\"d-flex item col-3 text-left border-right align-items-center\">"+data[i].product_price+', '+data[i].price_curr +"</div>\
                                <div class=\"d-flex item description col-3 text-justify border-right\">"+data[i].product_descr+"</div>\
                                <div class=\"d-flex item col-2 text-left border-left align-items-center justify-content-center\">\
                                <a href="+data[i].product_shop_url+" class=\"visit_vendor\" title=\"Visit vendor's website\" onclick=\"countClick(this)\">Vendor</a>\
                                </div>\
                            </article>"
                            span.innerHTML += my_module;
                            categories.push(data[i].product_category);
                            search_button.setAttribute("onClick", "(sendData(1,"+data[i].product_id+", 'new'))");
                            visit_links = document.getElementsByClassName("visit_vendor");
                            for (var visit_ind = 0; visit_ind < visit_links.length; visit_ind++) {
                                visit_links[i].setAttribute("onclick", "countClick(this)");
                            }
                        }
                        resetFilters();
                        setCategories(categories);
                    }
                } else if(status_code == false) {
                    span.innerHTML = "<p>Ooops, we couldn't find anything related to your search.</p>";
                }
            }
        }
        http_req.open("POST", "main.php", true);
        form.append('search_field', searchField);
        form.append('search_filter', searchFilter);
        if((min_index != 1 && new_search == 1) || min_index == null) {
            min_index = 1;    
        }
        if(flag == null || flag == "") {
            flag == "new";
        }
        form.append('min_index', min_index);
        form.append('flag', flag);
        http_req.send(form);
    } else {
        alert("Please provide a search criteria.");
    }
}


/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * Before loading the returned json objects into the FE, this function is called to compare their ID's and sorts
 * them in an ascending order with Bubble sort.
 *
 * @param   {array} data    json object with the returned rows by the back-end
 * @return {array}
 */
function sortObjects(data) {
    var arr = data;
    for(var i = 0; i < arr.length; i++) {
        for(var j = i + 1; j < arr.length; j++) {
            if(arr[i].product_id > arr[j].product_id) {
                var tmp_obj = arr[i];
                arr[i] = arr[j];
                arr[j] = tmp_obj;
            }
        }
    }
    return arr;
}

// Sets the categories in the categories dropdown, which is used by the user
// to filter through the content. Filters the duplicates
function setCategories(categories) {
    var category_filter = document.getElementById("category_filter");
    category_filter.innerHTML = "<option selected value=\"0\"></option>";
    var tmp = "";
    categories = categories.sort();
    for (var i = 0; i < categories.length - 1; i++) {
        var j = i + 1;
        if(categories[i] !== categories[j]){
            if(categories[i] != null) {
                tmp = "<option value="+categories[i]+">"+categories[i]+"</option>";
                category_filter.innerHTML += tmp;
            }
        }
    }
}

// Creates an HttpInstance for async call to the BE.
function createXMLHttpInstance() {
    var request = null;
    if (window.XMLHttpRequest) {
        request = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        try {
            request = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                alert("XHR not created");
            }
        }
    }
    return request;
}

// Parses the returned json formatted string to json object
function parseJson(text) {
    try {
        var json = JSON.parse(text);
    } catch(e) {
        return false;
    }
    return json;
}