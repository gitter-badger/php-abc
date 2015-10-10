/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Form/ColumnTypeHandler/TextAreaControl',

  ['jquery',
    'SetBased/Abc/OverviewTable',
    'SetBased/Abc/OverviewTable/ColumnTypeHandler/Text'],

  function ($, OverviewTable, Text) {
    "use strict";
    //------------------------------------------------------------------------------------------------------------------
    /**
     * Prototype for column handlers for columns with a textarea form control.
     * @constructor
     */
    function TextAreaControl() {
      // Use parent constructor.
      Text.call(this);
    }

    //------------------------------------------------------------------------------------------------------------------
    TextAreaControl.prototype = Object.create(Text.prototype);
    TextAreaControl.constructor = TextAreaControl;

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the text content of the input box in a table_cell.
     *
     * @param table_cell
     *
     * @returns string
     */
    TextAreaControl.prototype.extractForFilter = function (table_cell) {
      return OverviewTable.toLowerCaseNoAccents($(table_cell).find('textarea').val());
    };

    //------------------------------------------------------------------------------------------------------------------
    TextAreaControl.prototype.getSortKey = function (table_cell) {
      return OverviewTable.toLowerCaseNoAccents($(table_cell).find('textarea').val());
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Register column type handler.
     */
    OverviewTable.registerColumnTypeHandler('control-text-area', TextAreaControl);

    //------------------------------------------------------------------------------------------------------------------
    return TextAreaControl;
  }
);

//----------------------------------------------------------------------------------------------------------------------
