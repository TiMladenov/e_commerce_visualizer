/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 * 
 * Allows the user to filter the returned results by various criteria, like promotions, category,
 * price, etc.
 * 
 * @param {object}  element     References the DOM element (i.e. select) that initiates the script. 
 */
function filterBy(element) {
    var filter_value = element.value;
    // Hides all rows that do NOT equal the selected by the user category.
    // Also unhides the elements if the user wishes to see them all again.
    if(element === document.getElementById("category_filter")) {
        if(filter_value != "0") {
            var span = document.getElementById("span");
            var children = span.childNodes;
            for(var i = 1; i < children.length; i++) {
                if(children[i].nodeName != "#text") {
                    if(children[i].getAttribute("data-product-category") != filter_value) {
                        children[i].setAttribute("hidden", true);
                    } else {
                        children[i].removeAttribute("hidden");
                    }
                }
            }
        } else {
            var span = document.getElementById("span");
            var children = span.childNodes;
            for(var i = 1; i < children.length; i++) {
                if(children[i].nodeName != "#text") {
                    children[i].removeAttribute("hidden");
                }
            }
        }
    // Hides all rows that do NOT equal the selected by the user option: 1 - Promotion, 0 - Not promotion
    // Unhides the hidden rows if the user wishes to see them all again.
    } else if(element == document.getElementById("category_promotions")) {
        var span = document.getElementById("span");
        var children = span.childNodes;
        for(var i = 0; i < children.length; i++) {
            if(children[i].nodeName != "#text") {
                if(children[i].getAttribute("data-is-promo") != filter_value) {
                        children[i].setAttribute("hidden", true);
                    } else {
                        children[i].removeAttribute("hidden");
                }
            }
        }
    // Hides the rows that have price below or above the price range selected by the user.
    // Unhides the hidden rows if the user wishes to see them all again.
    } else if(element == document.getElementById("category_price_filter")) {
        filter_value = parseInt(filter_value);
        if(filter_value == 0) {
            var span = document.getElementById("span");
            var children = span.childNodes;
            for(var i = 0; i < children.length; i++) {
                if(children[i].nodeName != "#text") {
                    if(parseFloat(children[i].getAttribute("data-product-price")) >= 0 && parseFloat(children[i].getAttribute("data-product-price")) < 5) {
                        children[i].removeAttribute("hidden");
                    } else {
                        children[i].setAttribute("hidden", true);
                    }
                }
            }
        } else if(filter_value >= 80 && filter_value < 160) {
            var span = document.getElementById("span");
            var children = span.childNodes;
            for(var i = 0; i < children.length; i++) {
                if(children[i].nodeName != "#text") {
                    if(parseFloat(children[i].getAttribute("data-product-price")) >= 80 && parseFloat(children[i].getAttribute("data-product-price")) < parseInt(filter_value) * 2) {
                        children[i].removeAttribute("hidden");
                    } else {
                        children[i].setAttribute("hidden", true);
                    }
                }
            }
        } else if(filter_value >= 160) {
            var span = document.getElementById("span");
            var children = span.childNodes;
            for(var i = 0; i < children.length; i++) {
                if(children[i].nodeName != "#text") {
                    if(parseFloat(children[i].getAttribute("data-product-price")) >= 160) {
                        children[i].removeAttribute("hidden");
                    } else {
                        children[i].setAttribute("hidden", true);
                    }
                }
            }
        }
        
        else if(filter_value > 0) {
            var span = document.getElementById("span");
            var children = span.childNodes;
            for(var i = 0; i < children.length; i++) {
                if(children[i].nodeName != "#text") {
                    if(parseFloat(children[i].getAttribute("data-product-price")) >= parseInt(filter_value) && parseFloat(children[i].getAttribute("data-product-price")) < (parseInt(filter_value) * 2)) {
                        children[i].removeAttribute("hidden");
                    } else {
                        children[i].setAttribute("hidden", true);
                    }
                }
            }
        } else {
            var span = document.getElementById("span");
            var children = span.childNodes;
            for(var i = 0; i < children.length; i++) {
                if(children[i].nodeName != "#text") {
                    children[i].removeAttribute("hidden");
                }
            }
        }
    }
}