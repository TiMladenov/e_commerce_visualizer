/**
 * @author Tihomir Mladenov, 29.07.2019
 * @version 1.0
 * 
 * Makes an AJAX call to the back-end, which returns JSON
 * with product_id, product_name and product_category for
 * each row from the DB. Adds it to the front-end.
 * 
 * @param {String}  obj_loc     Stores current domain and directory of the page.
 *                              Used to access the script.
 * @param {JSON}    json        Stores the JSON formatted returned data from the
 *                              back-end.
 * @param {object}  categ_root  References the DOM element where the categories
 *                              will be loaded.
 *
 * @return {JSON}
 */
function getCategories() {
    var obj_loc = window.location.origin;
    obj_loc = obj_loc + '/'+ 'admin.php';

    $.ajax({
        type : 'POST',
        url : obj_loc,
        data : {
            'control' : 'select'
        },
        async : false,
        success : function(result) {
            var json = JSON.parse(result);
            var categ_root = document.getElementById('category_edit_id');
            if(categ_root.childNodes > 0) {
                categ_root.innerHTML = "";
            }
            for(var i = 0; i < json.length; i++) {
                var option = "<option value="+json[i].product_id+">"+json[i].product_name.substr(0, 100)+"...| "+json[i].product_category+"</option>"
                categ_root.innerHTML += option;
            }
        }
    });
}