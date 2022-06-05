<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
  <link rel="shortcut icon" type="image/jpg" href="./images/favicon.ico" />
  <link rel="stylesheet" href="style.css">


  <title>Parser Wildberries</title>

</head>

<body>
  <div class="container">
    <div class="row result-row">
      <div class="col-6">
        <h4>Парсинг общей таблицы <a href="https://docs.google.com/spreadsheets/d/1eSFcG5XhLtV0d58EefpDqYEuL5yNQG7U5ftNMtY0-IU/edit?usp=sharing" class="btn btn-outline-success" target="_blank">Google Sheets</a></h4>
        <br>
        <a href="./index-ytro.php" class="parselizer-button link-parselizer" target="_blank">Парсить</a>
      </div>
      <div class="col-6">
        <h4>Парсинг Остаток по артикулам <a href="https://docs.google.com/spreadsheets/d/1Vt7rb5_fLj5mVF1HtUHzI2vkj6RkYXs_lrUhjTldXUo/edit?usp=sharing" class="btn btn-outline-success" target="_blank">Google Sheets</a></h4>
        <br>
        <a href="./index-quantity_products.php" class="parselizer-button link-parselizer" target="_blank">Парсить</a>
      </div>
    </div>
    <br>
    <hr>
    </div>
  </div>
  <div class="container mt-3">
    <h4>Добавление новых категорий <span style="font-size: 0.6em">(по умолчанию статус Включен)</span></h4>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text"><img src="images/wildberries.svg" alt=""></span>
      </div>
      <input type="text" class="form-control" placeholder="Введите ссылку на Wildberries | Например: https://www.wildberries.ru/brands/ytro"
         id="url_category" name="url_category" required>
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text"><img src="images/google-sheets.svg" alt=""></span>
      </div>
      <input type="text" class="form-control" placeholder="Введите название листа Google Sheets (*только текст, без символов) | Например: ytro"
      id="name_letter_google_sheet" name="name_letter_google_sheet" required>
    </div>
    <button type="submit" class="parselizer-button add-url-category">Добавить в таблицу</button>
  </div>
  <div class="container">
    <div class="row result-row">
      <div class="col-6">
        <h4>Удалить с таблицы:</h4>
        <div class="form-group">
          <select class="form-control" id="delete_item">

          </select>
          <button type="submit" class="parselizer-button delete-item-google-sheets">Удалить с таблицы</button>
        </div>
      </div>
      <div class="col-6">
        <h4>Включить / выключить:</h4>
        <div class="form-group">
          <!-- <label for="change_status_item">Включить / выключить:</label> -->
          <select class="form-control" id="change_status_item">

          </select>
          <button type="submit" class="parselizer-button change-status-item">Переключить статус</button>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row result-row">
      <div class="col-12">
        <h2>Таблица всех категорий</h2>
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover table-sm" id="table2excel">
            <thead class="thead-dark">
              <tr>
                <th class="number">№</th>
                <th class="url_wildberries">Ссылка на Wildberries</th>
                <th class="name_google_sheets"><img src="images/google-sheets.svg" alt="" width="25px"> Google Sheets
                </th>
                <th class="status">Статус</th>
                <!-- <th class="delete_url noExl">Удаление</th> -->
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <a id="button"><i class="fas fa-arrow-up"></i></a>

  <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
    crossorigin="anonymous"></script>
  </script>
  <script src="./jquery.table2excel.js"></script>
  <script src="./main.js"></script>

</body>

</html>