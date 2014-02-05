/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global window */
/*global $ */
/*global SET_OverviewTable */
/*global SET_TextColumnTypeHandler */

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
 * Returns the numeric content of a table cell.
 *
 * @param {jquery} table_cell The table cell.
 *
 * @returns {Number}
 */
SET_HtmlControlColumnTypeHandler.prototype.getSortKey = function (table_cell) {
  "use strict";
  return $(table_cell).text();
};

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Register column type handlers.
 */
SET_OverviewTable.registerColumnTypeHandler('control-div', SET_HtmlControlColumnTypeHandler);
SET_OverviewTable.registerColumnTypeHandler('control-span', SET_HtmlControlColumnTypeHandler);
SET_OverviewTable.registerColumnTypeHandler('control-link', SET_HtmlControlColumnTypeHandler);

// ---------------------------------------------------------------------------------------------------------------------
