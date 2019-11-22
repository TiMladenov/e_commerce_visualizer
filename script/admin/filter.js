/**
 * @author  Tihomir Mladenov, 29.07.2019
 * @version 1.0
 * 
 * Called when the user clicks the checkbox to hide the entries with 0 visits.
 * 
 * @param {object} container    References the tr container the holds td's
 * @param {int}    visits       Stores the visit count for particular tr
 *
 * @param {object} checkbox
 */
function filter(checkbox) {
    //The checkbox is checked
    if(checkbox.checked) {
        var container = document.getElementById("table_price_range_body").childNodes;
        for(var i = 0; i < container.length; i++) {
            var visits = parseInt(container[i].childNodes[6].innerHTML);
            //Hides the table row if the price in it is 0
            if(visits <= 0) {
                container[i].setAttribute("hidden", true);
            }
        }
    // The checkbox is unchecked
    } else {
        //Unhides all table rows
        var container = document.getElementById("table_price_range_body").childNodes;
        for(var i = 0; i < container.length; i++) {
            container[i].removeAttribute("hidden");
        }
    }
}