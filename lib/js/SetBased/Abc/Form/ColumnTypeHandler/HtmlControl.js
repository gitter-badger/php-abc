/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Form/ColumnTypeHandler/HtmlControl',

  ['jquery',
    'SetBased/Abc/OverviewTable',
    'SetBased/Abc/OverviewTable/ColumnTypeHandler/Text'],

  function ($, OverviewTable, Text) {
    "use strict";
    //------------------------------------------------------------------------------------------------------------------
    /**
     * Prototype for column handlers for columns with a span, div, or a element.
     * @constructor
     */
    function HtmlControl() {
      // Use parent constructor.
      Text.call(this);
    }

    //------------------------------------------------------------------------------------------------------------------
    HtmlControl.prototype = Object.create(Text.prototype);
    HtmlControl.constructor = HtmlControl;

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the text content of the input box in a table_cell.
     *
     * @param table_cell
     *
     * @returns string
     */
    HtmlControl.prototype.extractForFilter = function (table_cell) {
      return OverviewTable.toLowerCaseNoAccents($(table_cell).children().text());
    };

    //------------------------------------------------------------------------------------------------------------------
    HtmlControl.prototype.getSortKey = function (table_cell) {
      return OverviewTable.toLowerCaseNoAccents($(table_cell).children().text());
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Register column type handlers.
     */
    OverviewTable.registerColumnTypeHandler('control-div', HtmlControl);
    OverviewTable.registerColumnTypeHandler('control-span', HtmlControl);
    OverviewTable.registerColumnTypeHandler('control-link', HtmlControl);

    //------------------------------------------------------------------------------------------------------------------
    return HtmlControl;
  }
);

//----------------------------------------------------------------------------------------------------------------------
