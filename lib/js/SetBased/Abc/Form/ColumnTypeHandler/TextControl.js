/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Form/ColumnTypeHandler/TextControl',

  ['jquery',
    'SetBased/Abc/OverviewTable',
    'SetBased/Abc/OverviewTable/ColumnTypeHandler/Text'],

  function ($, OverviewTable, Text) {
    "use strict";
    //------------------------------------------------------------------------------------------------------------------
    /**
     * Prototype for column handlers for columns with a text input form control.
     * @constructor
     */
    function TextControl() {
      // Use parent constructor.
      Text.call(this);
    }

    //------------------------------------------------------------------------------------------------------------------
    TextControl.prototype = Object.create(Text.prototype);
    TextControl.constructor = TextControl;

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the text content of the input box in a table_cell.
     *
     * @param table_cell
     *
     * @returns string
     */
    TextControl.prototype.extractForFilter = function (table_cell) {
      return OverviewTable.toLowerCaseNoAccents($(table_cell).find('input').val());
    };

    //------------------------------------------------------------------------------------------------------------------
    TextControl.prototype.getSortKey = function (table_cell) {
      return OverviewTable.toLowerCaseNoAccents($(table_cell).find('input').val());
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Register column type handlers.
     */
    OverviewTable.registerColumnTypeHandler('control-text', TextControl);
    OverviewTable.registerColumnTypeHandler('control-button', TextControl);
    OverviewTable.registerColumnTypeHandler('control-submit', TextControl);

    //------------------------------------------------------------------------------------------------------------------
    return TextControl;
  }
);

//------------------------------------------------------------------------------------------------------------------
