/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global window */
/*global $ */
/*global SET_TextColumnTypeHandler */
/*global SET_OverviewTable */

// ---------------------------------------------------------------------------------------------------------------------
function SET_TextAreaControlColumnTypeHandler() {
  "use strict";
}

// ---------------------------------------------------------------------------------------------------------------------
SET_TextAreaControlColumnTypeHandler.prototype = Object.create(SET_TextColumnTypeHandler.prototype);
SET_TextAreaControlColumnTypeHandler.constructor = SET_TextAreaControlColumnTypeHandler;

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Returns the text content of the input box in a table_cell.
 *
 * @param table_cell
 *
 * @returns string
 */
SET_TextAreaControlColumnTypeHandler.prototype.extractForFilter = function (table_cell) {
  "use strict";
  return SET_OverviewTable.toLowerCaseNoAccents($(table_cell).find('textarea').val());
};

// ---------------------------------------------------------------------------------------------------------------------
SET_TextAreaControlColumnTypeHandler.prototype.getSortKey = function (table_cell) {
  "use strict";
  return SET_OverviewTable.toLowerCaseNoAccents($(table_cell).find('textarea').val());
};

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Register column type handler.
 */
SET_OverviewTable.registerColumnTypeHandler('control-text-area', SET_TextAreaControlColumnTypeHandler);

// ---------------------------------------------------------------------------------------------------------------------
