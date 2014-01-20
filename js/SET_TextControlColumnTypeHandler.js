/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global window */
/*global $ */
/*global set_to_lower_case_no_accents */
/*global SET_TextColumnTypeHandler */
/*global SET_OverviewTable */

// ---------------------------------------------------------------------------------------------------------------------
function SET_TextControlColumnTypeHandler() {
  "use strict";
}

// ---------------------------------------------------------------------------------------------------------------------
SET_TextControlColumnTypeHandler.prototype = Object.create(SET_TextColumnTypeHandler.prototype);
SET_TextControlColumnTypeHandler.constructor = SET_TextControlColumnTypeHandler;

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Returns the text content of the input box in a table_cell.
 *
 * @param table_cell
 *
 * @returns string
 */
SET_TextControlColumnTypeHandler.prototype.extractForFilter = function (table_cell) {
  "use strict";
  return set_to_lower_case_no_accents($(table_cell).find('input').val());
};

// ---------------------------------------------------------------------------------------------------------------------
SET_TextControlColumnTypeHandler.prototype.getSortKey = function (table_cell) {
  "use strict";
  return set_to_lower_case_no_accents($(table_cell).find('input').val());
};

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Register column type handler.
 */
SET_OverviewTable.registerColumnTypeHandler('control_text', SET_TextControlColumnTypeHandler);
SET_OverviewTable.registerColumnTypeHandler('control_button', SET_TextControlColumnTypeHandler);
SET_OverviewTable.registerColumnTypeHandler('control_submit', SET_TextControlColumnTypeHandler);

// ---------------------------------------------------------------------------------------------------------------------
