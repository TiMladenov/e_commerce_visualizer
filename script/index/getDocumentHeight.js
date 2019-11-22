/**
 * @author  Tihomir Mladenov, 01.08.2019
 * @version 1.0
 *
 * Gets the height of the client's browser window
 * 
 * @return {int} window_max_h
 */
function getDocumentHeight() {
    var doc = document;
    window_max_h = Math.max(
        doc.body.scrollHeight, doc.documentElement.scrollHeight,
        doc.body.offsetHeight, doc.documentElement.offsetHeight,
        doc.body.clientHeight, doc.documentElement.clientHeight
    )
    return window_max_h;
}