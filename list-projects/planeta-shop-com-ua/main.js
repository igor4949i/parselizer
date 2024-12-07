$('#data_submit_parselizer').click(function (e) {
  e.preventDefault();
  const url_product = $('#parser').val();
  if (!url_product) {
    $(".form-input-error").css('display', 'block').html("Input field is empty...Enter URL");
    exit();
  } else {
    $(".form-input-error").fadeOut();
  }
  $("#data_submit_parselizer .spinner-border").fadeIn();
  $("#data_submit_parselizer").attr('disabled', 'disabled').css('opacity', '.65');;
  $("#data_submit_parselizer .loading-button").html("Loading...");
  $.post("./category_parser_template.php",
      {
          parser_data: url_product
      },
      function(data) {
          if (!data) {
              // window.location.replace("admin.php");
              
              alert("No data return");
              $("#table2excel caption").fadeOut().remove();
              $("#data_submit_parselizer .spinner-border").fadeOut();;
              $("#data_submit_parselizer .loading-button").html("Finish");
          } else {
            $("#table2excel tbody").append(data);
            $("#table2excel caption").fadeOut().remove();
            $("#data_submit_parselizer .spinner-border").fadeOut();;
            $("#data_submit_parselizer .loading-button").html("Finish");
          }
      }
  );
});

$(document).ready(function(){
  $(".delete-table").click(function(){
    $("#table2excel tbody tr").fadeOut().remove();
    $("#table2excel caption").fadeIn().html('Table is empty...');
    $("#data_submit_parselizer .loading-button").html("Create table");
    $("#data_submit_parselizer").css('opacity', '1');
    $("#data_submit_parselizer").removeAttr('disabled');
  });
});

$("#data_excel").click(function () {
  $("#table2excel").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    name: "Worksheet Name",
    filename: "Parselizer_Table", //do not include extension
    fileext: ".csv" // file extension
  });
});
