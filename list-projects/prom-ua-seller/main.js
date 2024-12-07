$('#data_submit_parselizer').click(function (e) {
  e.preventDefault();
  const url_product = $('#parser').val();
  var description;

  if (!url_product) {
    $(".form-input-error").css('display', 'block').html("Вставьте URL товара...");
    exit();
  } else {
    $(".form-input-error").fadeOut();
  }

  $("#data_submit_parselizer .spinner-border").fadeIn();
  $("#data_submit_parselizer").attr('disabled', 'disabled').css('opacity', '.65');;
  $("#data_submit_parselizer .loading-button").html("Loading...");

  $.post("./product_description.php",
    {
      parser_data: url_product
    },
    function (data) {
      var parser_data_desc = data;

      $.post("./product_getDetails.php",
        {
          parser_data: url_product,
          parser_data_desc: window.parser_data_desc
        },
        function (data) {

          $.post("./product_parser_template.php",
            {
              parser_data: url_product,
              parser_data_desc: parser_data_desc,
              parser_data_details: data
            },
            function (data) {

              if (!data) {
                alert("XML не создано");
                $("#table2excel tbody tr").fadeOut().remove();
                $("#data_submit_parselizer .spinner-border").fadeOut();
                $("#data_submit_parselizer .loading-button").html("Створити XML");
                $("#table2excel caption").fadeIn().html('Table is empty...');
                // $("#data_submit_parselizer .loading-button").html("Create table");
                $("#data_submit_parselizer").css('opacity', '1');
                $("#data_submit_parselizer").removeAttr('disabled');
                // $("#data_submit_parselizer .spinner-border").fadeOut();
              } else {
                $("#table2excel caption").fadeOut().remove();
                $("#exampleFormControlTextarea1").html(data);
                // console.log(data);

                $("#data_submit_parselizer .spinner-border").fadeOut();
                $("#data_submit_parselizer .loading-button").html("Finish");
              }

              $.ajax({
                url: './load_data_categories.php'
              }).done(function (data) {
                $("#exampleFormControlTextarea_cat").html(data);
              });

            }
          )
        }
      )


    }
  );

  $.post("./product_categories.php",
    {
      // parser_data: url_product
    },
    function (data) {
      if (!data) {
        // alert("Файл XML_categories пустой.");
        $("#table2excel_cat tbody tr").fadeOut().remove();
        $("#data_submit_parselizer_cat .spinner-border").fadeOut();
        $("#data_submit_parselizer_cat .loading-button").html("Створити XML categories");
        $("#table2excel_cat caption").fadeIn().html('Table is empty...');
        // $("#data_submit_parselizer_cat .loading-button").html("Create table");
        $("#data_submit_parselizer_cat").css('opacity', '1');
        $("#data_submit_parselizer_cat").removeAttr('disabled');
        // $("#data_submit_parselizer_cat .spinner-border").fadeOut();
      } else {
        $("#table2excel_cat caption").fadeOut().remove();
        $("#exampleFormControlTextarea_cat").html(data);
        // console.log(data);

        $("#data_submit_parselizer_cat .spinner-border").fadeOut();
        $("#data_submit_parselizer_cat .loading-button").html("Finish");
      }
    }
  );
});


$('#data_submit_parselizer_cat').click(function (e) {
  e.preventDefault();

  $("#data_submit_parselizer_cat .spinner-border").fadeIn();
  $("#data_submit_parselizer_cat").attr('disabled', 'disabled').css('opacity', '.65');;
  $("#data_submit_parselizer_cat .loading-button").html("Loading...");

  $.post("./product_categories.php",
    {
      // parser_data: url_product
    },
    function (data) {
      if (!data) {
        alert("Файл XML_categories пустой.");
        $("#table2excel_cat tbody tr").fadeOut().remove();
        $("#data_submit_parselizer_cat .spinner-border").fadeOut();
        $("#data_submit_parselizer_cat .loading-button").html("Створити XML categories");
        $("#table2excel_cat caption").fadeIn().html('Table is empty...');
        // $("#data_submit_parselizer_cat .loading-button").html("Create table");
        $("#data_submit_parselizer_cat").css('opacity', '1');
        $("#data_submit_parselizer_cat").removeAttr('disabled');
        // $("#data_submit_parselizer_cat .spinner-border").fadeOut();
      } else {
        $("#table2excel_cat caption").fadeOut().remove();
        $("#exampleFormControlTextarea_cat").html(data);
        // console.log(data);

        $("#data_submit_parselizer_cat .spinner-border").fadeOut();
        $("#data_submit_parselizer_cat .loading-button").html("Finish");
      }
    }
  );

});






$(document).ready(function () {
  $(".delete-table").click(function () {
    $("#table2excel tbody tr").fadeOut().remove();
    $("#table2excel caption").fadeIn().html('Table is empty...');
    $("#data_submit_parselizer .loading-button").text("Создать XML");
    $("#data_submit_parselizer").css('opacity', '1');
    $("#data_submit_parselizer").removeAttr('disabled');

    $('#exampleFormControlTextarea1').html('');
  });

  $("#data_copy").click(function () {
    $("#exampleFormControlTextarea1").select();
    document.execCommand('copy');
  });

  // second block categories
  $(".delete-table-cat").click(function () {
    // $("#table2excel_cat tbody tr").fadeOut().remove();
    // $("#table2excel_cat caption").fadeIn().html('Table is empty...');
    $("#data_submit_parselizer_cat .loading-button").text("Создать XML categories");
    $("#data_submit_parselizer_cat").css('opacity', '1');
    $("#data_submit_parselizer_cat").removeAttr('disabled');

    $('#exampleFormControlTextarea_cat').html('');
  });

  $("#data_copy_cat").click(function () {
    $("#exampleFormControlTextarea_cat").select();
    document.execCommand('copy');
  });

  $("#delete-file-categories").click(function () {
    $.post("./delete-file-categories.php",
      {
        // parser_data: url_product
      },
      function (data) {
        if (!data) {
          alert("Все категории XML удалено с файла.");
          $("#table2excel_cat tbody tr").fadeOut().remove();
          $("#data_submit_parselizer_cat .spinner-border").fadeOut();
          $("#data_submit_parselizer_cat .loading-button").html("Створити XML categories");
          $("#table2excel_cat caption").fadeIn().html('Table is empty...');
          // $("#data_submit_parselizer_cat .loading-button").html("Create table");
          $("#data_submit_parselizer_cat").css('opacity', '1');
          $("#data_submit_parselizer_cat").removeAttr('disabled');
          // $("#data_submit_parselizer_cat .spinner-border").fadeOut();
          location.reload();

        } else {
          $("#table2excel_cat caption").fadeOut().remove();
          $("#exampleFormControlTextarea_cat").html(data);
          // console.log(data);

          $("#data_submit_parselizer_cat .spinner-border").fadeOut();
          $("#data_submit_parselizer_cat .loading-button").html("Finish");
        }
      }
    );
  })
});


// $(document).ready(function () {
//   $.ajax({
//     url: './load_data_categories.php'
//   }).done(function (data) {
//     $("#exampleFormControlTextarea_cat").html(data);
//   });
// });