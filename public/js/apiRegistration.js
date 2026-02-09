
function registerUser(userData) {
    // URL вашего API-эндпоинта
    const apiUrl = '/api/registration'; // замените на реальный URL

    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(userData)
    })
        .then(response => {
            // Проверяем статус ответа
            if (!response.ok) {
                return response.json().then(data => {
                    // Если ошибка — возвращаем данные для обработки
                    throw new Error(JSON.stringify(data));
                });
            }
            return response.json();
        })
        .then(data => {
            // Успех: показываем alert
            if (data.success === true) {
                alert(data.message); // 'Пользователь успешно зарегистрирован'

                // Можно дополнительно перенаправить пользователя
                // window.location.href = '/dashboard';
            }
        })
        .catch(error => {
            // Обработка ошибок
            try {
                // Если ошибка из API (JSON)
                const errorData = JSON.parse(error.message);
                console.error('Ошибки валидации:', errorData.errors);
                // Здесь можно отобразить ошибки на странице
                // Например: показать всплывающее окно с ошибками
            } catch (e) {
                // Сетевые ошибки или другие проблемы
                console.error('Сетевая ошибка или сервер недоступен:', error);
                alert('Произошла ошибка сети. Проверьте подключение и повторите попытку.');
            }
        });
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Отменяем стандартную отправку формы

        // Собираем данные из полей формы
        const userData = {
            email: document.getElementById('user_registration_email').value,
            password: document.getElementById('user_registration_password').value,
            passwordRepeat: document.getElementById('user_registration_passwordRepeat').value
        };

        // Отправляем данные на API
        registerUser(userData);
    });
});
