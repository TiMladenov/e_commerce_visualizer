/**
 * @author Tihomir Mladenov, 29.07.2019
 * @version 1.0
 * 
 * Displays the selected category from the category dropdown.
 * 
 * @param {object} selectedField    References the DOM element where the selected
 *                                  category and its properties will be displayed.
 * @param {object} selected         References the selected element from the dropdown
 *                                  list with all categories and properties.
 * @param {String} text             Stores the text from the selected category in the
 *                                  dropdown.
 *
 */
function setSelected() {
    var selectedField = document.getElementById("category_selected");
    selectedField.innerHTML = "";
    
    var selected = document.getElementById("category_edit_id");
    var text = selected.options[selected.selectedIndex].value + " " + selected.options[selected.selectedIndex].text;
    selectedField.innerHTML = text;
}