<?php
require_once 'App/Domain/Users/UserEntity.php'; use App\Domain\Users\UserEntity;
require_once 'App/Infrastructure/sdbh.php'; use sdbh\sdbh;
$dbh = new sdbh();
$user = new UserEntity();
if (!$user->isAdmin) die('Доступ закрыт');
?>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          crossorigin="anonymous">
    <link href="assets/css/style.css" rel="stylesheet"/>
    <link href="assets/css/admin.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>
</head>
<body>
<h1>Админка</h1>
<div class="container">
    <div class="row row-header">
        <div class="col-12" id="count">
            <img src="assets/img/logo.png" alt="logo" style="max-height:50px"/>
            <h1>Форма добавления продуктов</h1>
        </div>
    </div>

    <div class="row row-form">
        <div class="col-12">
            <form iaction="App/Application/AdminService.php" method="POST" id="form">
                <div class="form-group">
                    <label for="name">Название товара:</label>
                    <input type="text" id="name" name="name" class="form-control" required><br><br>
                </div>
                <div class="form-group">
                    <label for="price">Цена</label>
                    <input type="number" id="price" name="price" class="form-control" min="0" required><br><br>
                </div>

                <label>Тарифы</label>
                <div id="tariffsContainer">
                    
                </div>
                <button type="button" id="addTariffButton" class="tariffBUtton">Добавить тариф</button><br><br>

                <input type="submit" value="Добавить товар">
            </form>
            <div id="result"></div>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
        document.getElementById('addTariffButton').addEventListener('click', function() {
            const tariffsContainer = document.getElementById('tariffsContainer');
            const newTariffPair = document.createElement('div');
            newTariffPair.className = 'tariff-pair';
            newTariffPair.innerHTML = `
                <input type="number" name="threshold[]" placeholder="Порог" required min="0">
                <input type="number" name="tariff[]" placeholder="Цена" step="0.01" required min="0">
                <button class="removeButton" type="button" onclick="this.parentElement.remove()">Удалить</button>
            `;
            tariffsContainer.appendChild(newTariffPair);
        });
    </script>
<script>
    $(document).ready(function() {
        $("#form").submit(function(event) {
            event.preventDefault();


            $.ajax({
                url: 'App/Application/AdminService.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $("#result").text(response);
                },
                error: function() {
                    $("#result").text('Ошибка при добавлении');
                }
            });
        });
    });
</script>


</body>
</html>