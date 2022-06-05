$('#data_submit_parselizer_count').click(function (e) {
  e.preventDefault();
  const url_seller = $('#parser_count_products').val();

  if (!url_seller) {
    $(".form-input-error_count").css('display', 'block').html("Вставьте URL продавца...");
    exit();
  } else {
    $(".form-input-error").fadeOut();
  }

  $("#data_submit_parselizer_count .spinner-border").fadeIn();
  $("#data_submit_parselizer_count").css('opacity', '.65');
  $("#data_submit_parselizer_count .loading-button").html("Loading...");

  $.post("./count_url_products.php",
    {
      parser_data: url_seller
    },
    function (data) {
      if (!data) {
        // window.location.replace("admin.php");

        alert("Нет данных");
        // $("#table2excel caption").fadeOut().remove();
        // $("#parser_count_products .spinner-border").fadeOut();;
        // $("#parser_count_products .loading-button").html("Finish");
      } else {
        // changeGlobalUrlData(data);
        // globalDataUrl = data;
        $(".count_products .count_pr").html(data);
        $("#data_submit_parselizer_count").css('opacity', '1');
        // $("#table2excel caption").fadeOut().remove();
        // $("#parser_count_products .spinner-border").fadeOut();;
        $("#data_submit_parselizer_count .loading-button").html("Посчитать количество URL");
      }
    }
  );
  
})

$('#data_submit_parselizer').click(function (e) {
  e.preventDefault();
  // const url_product = $('#parser').val();

  // if (!url_product) {
  //   $(".form-input-error").css('display', 'block').html("Вставьте URL товара...");
  //   exit();
  // } else {
  //   $(".form-input-error").fadeOut();
  // }

  const start = $('#parser_start').val();
  const end = $('#parser_end').val();

  if (!start) {
    $(".form-input-error-start-end").css('display', 'block').html("Вставьте start / end...");
    exit();
  } else {
    $(".form-input-error-start-end").fadeOut();
  }
  if (!end) {
    $(".form-input-error-start-end").css('display', 'block').html("Вставьте start / end...");
    exit();
  } else {
    $(".form-input-error-start-end").fadeOut();
  }

  $("#data_submit_parselizer .spinner-border").fadeIn();
  $("#data_submit_parselizer").attr('disabled', 'disabled').css('opacity', '.65');;
  $("#data_submit_parselizer .loading-button").html("Loading...");

  for (let index = start; index <= end; index++) {
    var count = index;
    $.post("./products_url_seller.php",
      {
        count_num_product: count
      },
      function (data) {
        parse_product(data);
      }
    );
  }


  function parse_product(url_product) {
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
                  // alert("XML не создано");
                  // $("#table2excel tbody tr").fadeOut().remove();
                  // $("#data_submit_parselizer .spinner-border").fadeOut();
                  // $("#data_submit_parselizer .loading-button").html("Створити XML");
                  // $("#table2excel caption").fadeIn().html('Table is empty...');
                  // $("#data_submit_parselizer .loading-button").html("Create table");
                  $("#data_submit_parselizer").css('opacity', '1');
                  $("#data_submit_parselizer .loading-button").html("Создать XML");
                  $("#data_submit_parselizer").removeAttr('disabled');
                  $("#data_submit_parselizer .spinner-border").fadeOut();
                } else {
                  $("#table2excel caption").fadeOut().remove();
                  // $("#exampleFormControlTextarea1").html(data);
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
  }

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
  });

  $("#delete-file-url-list").click(function () {
    $.post("./delete-file-url-list.php",
      {
        // parser_data: url_product
      },
      function (data) {
        if (!data) {
          alert("Все ссылки удалено с файла.");
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


$(document).ready(function () {
  $.ajax({
    url: './load_data_categories.php'
  }).done(function (data) {
    $("#exampleFormControlTextarea_cat").html(data);
  });
});