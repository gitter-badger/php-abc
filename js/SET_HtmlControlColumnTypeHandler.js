/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global window */
/*global $ */
/*global SET_TextColumnTypeHandler */
/*global SET_OverviewTable */

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Prototype for column handlers for columns with a spn, div,
 * @constructor
 */
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
  return SET_OverviewTable.toLowerCaseNoAccents($(table_cell).children().text());
};

// ---------------------------------------------------------------------------------------------------------------------
SET_HtmlControlColumnTypeHandler.prototype.getSortKey = function (table_cell) {
  "use strict";
  return SET_OverviewTable.toLowerCaseNoAccents($(table_cell).children().text());
};

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Register column type handlers.
 */
SET_OverviewTable.registerColumnTypeHandler('control-div', SET_HtmlControlColumnTypeHandler);
SET_OverviewTable.registerColumnTypeHandler('control-span', SET_HtmlControlColumnTypeHandler);
SET_OverviewTable.registerColumnTypeHandler('control-link', SET_HtmlControlColumnTypeHandler);

// ---------------------------------------------------------------------------------------------------------------------
