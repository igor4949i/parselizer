$(document).ready(function () {
  $.ajax({
    url: './load_data_table.php'
  }).done(function (data) {
    $("#table2excel tbody").append(data);
  });
});

$(document).ready(function () {
  $.ajax({
    url: './load_data_select_delete.php'
  }).done(function (data) {
    $("#delete_item").append(data);
  });
});

$(document).ready(function () {
  $.ajax({
    url: './load_data_select_switch_status.php'
  }).done(function (data) {
    $("#change_status_item").append(data);
  });
});

$('.add-url-category').click(function (e) {
  e.preventDefault();
  const url_category = $('#url_category').val();
  const name_letter_google_sheet = $('#name_letter_google_sheet').val();
  const status = "on";
  const index = $('table tr .number:last').text();
  let add_new_item = [index, url_category, name_letter_google_sheet, status];
  $.post("./control_url_category_add_new.php",
    {
      parser_data: add_new_item
    },
    function (data) {
      $("#table2excel tbody").append(data);
    }
  );
});

$('.delete-item-google-sheets').click(function (e) {
  e.preventDefault();
  const delete_item_google_sheets = $('#delete_item option:selected').val();
  $.post("./control_url_category_delete.php",
    {
      parser_data: delete_item_google_sheets
    },
    function () {
      location.reload();
    }
  );
});

$('.change-status-item').click(function (e) {
  e.preventDefault();
  const switch_status = $('#change_status_item option:selected').val();
  $.post("./control_url_category_swith_status.php",
    {
      parser_data: switch_status
    },
    function () {
      
    }
  );
  location.reload();
});


var btn = $('#button');

$(window).scroll(function() {
  if ($(window).scrollTop() > 300) {
    btn.addClass('show');
  } else {
    btn.removeClass('show');
  }
});

btn.on('click', function(e) {
  e.preventDefault();
  $('html, body').animate({scrollTop:0}, '300');
});

