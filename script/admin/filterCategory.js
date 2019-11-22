/**
 * @author  Tihomir Mladenov, 29.07.2019
 * @version 1.0
 * 
 * Called when the user clicks the checkbox to hide the entries with 0 visits.
 * 
 * @param {object} container    References the DOM tbody element that stores all tr's.
 * @param {array}  nodes        The tr elements of container.
 *
 * @param {object} checkbox
 */
function filterCategory(checkbox) {
    //If the checkbox is checked
    if(checkbox.checked) {
        var container = document.getElementById("table_visitor_body");
        var nodes = container.childNodes;

        // Hides all table rows that have price in them, which is less or equal to 0
        for(var i = 0; i < nodes.length; i++) {
            (parseInt(nodes[i].childNodes[3].innerHTML) <= 0) ? nodes[i].setAttribute("hidden", true) : false;
        }
    //If the checkbox is unchecked
    } else {
        //Unhides all table rows
        var container = document.getElementById("table_visitor_body");
        var nodes = container.childNodes;
        for(var i = 0; i < nodes.length; i++) {
            nodes[i].removeAttribute("hidden");
        }
    }
}