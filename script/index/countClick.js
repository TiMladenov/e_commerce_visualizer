
/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * When an item's URL is visited, triggers a counter script in the BE, which
 * counts the visits by various criteria.
 * 
 * @param {object}  element             References the <a> element with link for the corresponding item for each row.
 * 
 * @param {object}  parent              The parent DOM element of the <a> element.
 * @param {object}  parent_container    The container which encapsulates the parent of element.
 * @param {int}     obj_id              The product_id for that specific parent row.
 * @param {string}  obj_loc             The URL of the current page. Used to build a link to the BE script.
 * @param {string}  ip                  The ip of the client.
 */
function countClick(element) {
    var parent = element.parentElement;
    var parent_container = parent.parentElement;
    
    var obj_id = parent_container.getAttribute("name");
    var obj_loc = window.location.origin;

    var ip = "";

    $.getJSON('http://gd.geobytes.com/GetCityDetails?callback=?', {}, function(data) {
        var json = JSON.parse(JSON.stringify(data, null, 2));
        ip = json.geobytesipaddress;
        $.ajax({
            type:"POST",
            url:obj_loc +"/"+ "counter.php",
            data: {
                'product_id': obj_id,
                'client_ip' : ip
            },
            async: false,
            success : function (result) {
                console.log(result);
            }
        });
    });
}