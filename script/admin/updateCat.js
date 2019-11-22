/**
 * @author  Tihomir Mladenov, 29.07.2019
 * @version 1.0
 * 
 * Receives the new category name for a product as provided by the user.
 * If the string is empty, a warning is triggered, else also gets the 
 * ID of the item and sends the text and id to the BE for processing.
 * Returns "true" or "false" depending on successful update or not.
 * 
 * @param {object}      selectedField   References the field with the 
 *                                      selected category.
 * @param {object}      selected        References the selected dropdown
 *                                      element.
 * @param {string}      text            Contains the text of the selected
 *                                      dropdown element.
 * @param {string}      id              Contains the id of the edited element
 * @param {object}      new_category    The input for the new category text.
 * @param {string}      obj_loc         The URL to the BE script.
 * 
 * @return {boolean}
 */
function updateCat() {
    var selectedField = document.getElementById("category_selected")
    if(selectedField.innerHTML == "" || selectedField.innerHTML == null) {
        alert("Please select a category that you want to update first!");
        return false;
    } else {
        var selected = document.getElementById("category_edit_id");
        var text = selected.options[selected.selectedIndex];
        var id = text.getAttribute("value");
        var new_category = document.getElementById("category_new").value;
        if (new_category == "" || new_category == null) {
            alert("Please provide a valid category name");
        } else {
            var obj_loc = window.location.origin;
            obj_loc = obj_loc + '/'+ 'admin.php';
            
            $.ajax({
            type : 'POST',
            url : obj_loc,
            data : {
                'control' : 'update',
                'product_id' : id,
                'category' : new_category
            },
            async : false,
            success : function(result) {
                if(result == true) {
                    alert("The category was successfully updated.");
                    window.location.reload(true);
                } else {
                    alert("Ooops, the category could not be updated...");
                }
            }
        });
    }
}
}