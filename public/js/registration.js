document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
        // Проверка на наличие поля
    if (!form) {
        console.error('Форма с ID "registrationForm" не найдена');
        alert('Форма с ID "registrationForm" не найдена');
        return;
    }
        // Слушатель кнопки, запускается после нажатия
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Loading...';
        // Записывает в значение содержание полей
        const formData = {
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            passwordRepeat: document.getElementById('passwordRepeat').value
        };
        // Подключение к бэкэнду
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });
            console.log('Получен ответ от сервера. HTTP-статус:', response.status);

            if (!response.ok) {
                const result = await response.json();
                showAlertErrors(result);
                return;
            }
            const result = await response.json();
            alert(result.message);

        } catch (error) {
            console.error('Сетевая ошибка сервера:', error);
            alert('Извините, ошибка на нашей стороне, попробуйте повторить регистрацию позже!');
        } finally {
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Зарегистрироваться';
            }, 100);
        }
    });

    // Ошибки бэкэнда во всплывающем окне
    function showAlertErrors(errorResponse) {
        let alertMessage = 'Внимание: ';

        if (errorResponse.message) {
            alertMessage += errorResponse.message + '\n\n';
        }

        if (errorResponse.details) {
            Object.keys(errorResponse.details).forEach(field => {
                errorResponse.details[field].forEach(errorText => {
                    alertMessage += `- ${errorText}\n`;
                });
            });
        }
        if (alertMessage.trim()) {
            alert(alertMessage);
        } else {
            alert('message пустое, Караул!');
        }
    }
});
