$('#data_submit_parselizer').click(function (e) {
  e.preventDefault();
  const url_product = $('#planshetka').val();
  if (!url_product) {
    $(".form-input-error").css('display', 'block').html("Enter URL");
    exit();
  } else {
    $(".form-input-error").css('display', 'none')
  }
  $.post("category_prom_ua_planshetka.php",
      {
          planshetka: url_product
      },
      function(data) {
          if (!data) {
              // window.location.replace("admin.php");
              alert("No data return");
          } else {
              $("#table2excel tbody").append(data);
          }
      }
  );
});

$("#data_create_excel").click(function () {
  $("#table2excel").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    name: "Worksheet Name",
    filename: "Parselizer_template_Prom_ua", //do not include extension
    fileext: ".csv" // file extension
  });
});
