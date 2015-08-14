/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Form/ColumnTypeHandler/CheckboxControl',

  ['jquery',
    'SetBased/OverviewTable',
    'SetBased/OverviewTable/ColumnTypeHandler'],

  function ($, OverviewTable, ColumnTypeHandler) {
    "use strict";
    //------------------------------------------------------------------------------------------------------------------
    /**
     * Prototype for column handlers for columns with a text input form control.
     */
    function CheckboxControl() {
      // Use parent constructor.
      ColumnTypeHandler.call(this);
    }

    //------------------------------------------------------------------------------------------------------------------
    CheckboxControl.prototype = Object.create(ColumnTypeHandler.prototype);
    CheckboxControl.constructor = CheckboxControl;

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the text content of the input box in a table_cell.
     *
     * @param table_cell
     *
     * @returns string
     */
    CheckboxControl.prototype.extractForFilter = function (table_cell) {
      if ($(table_cell).find('input:checkbox').prop('checked')) {
        return '1';
      }

      return '0';
    };

    //------------------------------------------------------------------------------------------------------------------
    CheckboxControl.prototype.getSortKey = function (table_cell) {
      if ($(table_cell).find('input:checkbox').prop('checked')) {
        return '1';
      }

      return '0';
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Register column type handlers.
     */
    OverviewTable.registerColumnTypeHandler('control-checkbox', CheckboxControl);

    //------------------------------------------------------------------------------------------------------------------
    return CheckboxControl;
  }
);

//------------------------------------------------------------------------------------------------------------------
