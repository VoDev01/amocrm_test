<!DOCTYPE html>
<html lang="en" style="height: 100%;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amocrm форма</title>
</head>

<body style="height: 100%;">
    <form action="handleForm.php" method="post"
        style="height: 100%; margin: auto; width: 300px; display:flex; flex-direction: column; justify-content: center;">
        <div style="margin-bottom: 10px;">
            <label for="name">Имя</label>
            <input type="text" name="name" id="name" required />
        </div>
        <div style="margin-bottom: 10px;">
            <label for="name">Телефон</label>
            <input type="text" name="phone" id="phone" required />
        </div>
        <div style="margin-bottom: 10px;">
            <label for="name">Комментарий</label>
            <input type="text" name="comment" id="comment" required />
        </div>
        <button style="width: 100px;" type="submit">Отправить</button>
    </form>
</body>

</html>