/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Table/OverviewPackage',

  ['SetBased/Abc/Table/OverviewTable',
    'SetBased/Abc/Table/OverviewTable/ColumnTypeHandler/DateTime',
    'SetBased/Abc/Table/OverviewTable/ColumnTypeHandler/Ipv4',
    'SetBased/Abc/Table/OverviewTable/ColumnTypeHandler/None',
    'SetBased/Abc/Table/OverviewTable/ColumnTypeHandler/Numeric',
    'SetBased/Abc/Table/OverviewTable/ColumnTypeHandler/Text',
    'SetBased/Abc/Table/OverviewTable/ColumnTypeHandler/Uuid'],

  function (OverviewTable) {
    "use strict";

    return OverviewTable;
  }
);

//----------------------------------------------------------------------------------------------------------------------
