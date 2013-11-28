/*jslint browser: true, onevar: false, indent: 2 */
/*global window */
/*global $ */

// ---------------------------------------------------------------------------------------------------------------------
function set_to_lower_case_no_accents(text) {
  function remove_accent(char) {
    switch (char) {
    case 'à':
    case 'á':
    case 'â':
    case 'ã':
    case 'ä':
    case 'å':
      return 'a';
    case 'æ':
      return 'ae';
    case 'ç':
      return 'c';
    case 'è':
    case 'é':
    case 'ê':
    case 'ë':
    case 'ě':
    case 'ę':
      return 'e';
    case 'ð':
      return 'd';
    case 'ì':
    case 'í':
    case 'î':
    case 'ï':
      return 'i';
    case 'ł':
      return 'l';
    case 'ñ':
    case 'ń':
    case 'ň':
      return 'n';
    case 'ò':
    case 'ó':
    case 'ô':
    case 'õ':
    case 'ö':
    case 'ø':
      return 'o';
    case 'ù':
    case 'ú':
    case 'û':
    case 'ü':
      return 'u';
    case 'ş':
    case 'š':
      return 's';
    case 'ý':
    case 'ÿ':
      return 'y';
    case 'ž':
      return 'z';
    case 'þ':
      return 'th';
    case 'ß':
      return 'ss';
    default:
      return char;
    }
  }

  if (text) {
    return text.toLowerCase().replace(/\w/g, remove_accent);
  }
  else {
    return text;
  }
}

// ---------------------------------------------------------------------------------------------------------------------
function SET_OverviewTable(table) {
  this.myTable = table;

  // Display the row with table filters.
  table.find('.filter').each(function () {
    $(this).css('display', 'table-row');
  });

  var that = this;
  table.children('thead').find('.filter').find('input').each(function (index, input) {

    // Clear the filter boxes (some browsers preserve the values on page reload).
    $(input).val('');

    // Install event handler for this input box.
    $(input).keyup({ that: that, element: $(input)}, SET_OverviewTable.filterTrigger);

    // Resize the input box.
    $(input).width($(input).closest('td').width() - 2);
    $(input).css('visibility', 'visible');
  });
}

// ---------------------------------------------------------------------------------------------------------------------
SET_OverviewTable.filterTrigger = function (event) {
  event.data.that.filter(event, event.data.element);
};

// ---------------------------------------------------------------------------------------------------------------------
SET_OverviewTable.prototype.filter = function (event, input) {
  var filters = [];
  var row_index;


  // If the ESC-key was pressed or nothing is entered clear the value of the search box.
  if (event.keyCode === 27 || input.val() === '') {
    input.val('');
  }

  // Find all search boxes with non-empty values.
  this.myTable.children('thead').find('.filter').find('input').each(function () {
    var cell;
    var col;
    var input;

    input = $(this);

    if ($(input).val() !== '') {
      cell = $(input).closest('td');
      col = cell.parent('tr').children().index(cell);

      filters[filters.length] = {
        column_index: col,
        value: set_to_lower_case_no_accents($(input).val())
      };
    }
  });

  if (filters.length === 0) {
    // All search boxes are empty. Show all rows.
    this.myTable.children('tbody').children('tr').show();

    // Apply zebra theme on all rows.
    this.myTable.children('tbody').children('tr:odd').removeClass('odd').addClass('even');
    this.myTable.children('tbody').children('tr:even').removeClass('even').addClass('odd');
  }
  else {
    // One or more search boxes are not empty.

    // Hide all rows.
    this.myTable.children('tbody').children('tr').hide();

    // Apply filters.
    row_index = 0;
    this.myTable.children('tbody').children('tr').each(function () {
      var i;
      var show = 1;

      for (i = 0; i < filters.length; i += 1) {
        if (set_to_lower_case_no_accents($(this.cells[filters[i].column_index]).text()).indexOf(filters[i].value) === -1) {
          // Cell does not contain the value of the filter. Don't show the row and there is no need to apply
          // other filters on this row.
          show = 0;
          break;
        }
      }

      if (show === 1) {
        // The row matches all filters. Show the row.
        $(this).show();

        // Apply zebra theme on visible rows.
        row_index = row_index + 1;
        if ((row_index % 2) === 1) {
          $(this).removeClass('even').addClass('odd');
        }
        else {
          $(this).removeClass('odd').addClass('even');
        }
      }
    });
  }
};

// ---------------------------------------------------------------------------------------------------------------------
$(window).load(function () {
  $('div.overview_table table').each(function () {
    var table = new SET_OverviewTable($(this));
  });
});

// ---------------------------------------------------------------------------------------------------------------------
