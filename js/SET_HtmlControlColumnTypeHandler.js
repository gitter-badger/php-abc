/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global window */
/*global $ */
/*global set_to_lower_case_no_accents */
/*global SET_TextColumnTypeHandler */
/*global SET_OverviewTable */

// ---------------------------------------------------------------------------------------------------------------------
function SET_HtmlControlColumnTypeHandler() {
  "use strict";
}

// ---------------------------------------------------------------------------------------------------------------------
SET_HtmlControlColumnTypeHandler.prototype = Object.create(SET_TextColumnTypeHandler.prototype);
SET_HtmlControlColumnTypeHandler.constructor = SET_HtmlControlColumnTypeHandler;

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Returns the text content of the input box in a table_cell.
 *
 * @param table_cell
 *
 * @returns string
 */
SET_HtmlControlColumnTypeHandler.prototype.extractForFilter = function (table_cell) {
  "use strict";
  return set_to_lower_case_no_accents($(table_cell).children().text());
};

// ---------------------------------------------------------------------------------------------------------------------
SET_HtmlControlColumnTypeHandler.prototype.getSortKey = function (table_cell) {
  "use strict";
  return set_to_lower_case_no_accents($(table_cell).children().text());
};

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Register column type handler.
 */
SET_OverviewTable.registerColumnTypeHandler('control_div',  SET_HtmlControlColumnTypeHandler);
SET_OverviewTable.registerColumnTypeHandler('control_link', SET_HtmlControlColumnTypeHandler);
SET_OverviewTable.registerColumnTypeHandler('control_span', SET_HtmlControlColumnTypeHandler);

// ---------------------------------------------------------------------------------------------------------------------
