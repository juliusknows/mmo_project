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
                    'Accept': 'application/json',
                },
                body: JSON.stringify(formData)
            });
            console.log('Получен ответ от сервера. HTTP-статус:', response.status);

            if (!response.ok) {
                if (response.status === 500) {
                    alert('У нас что то сломалось, но мы вам не покажем!');
                    return;
                }
                const result = await response.json();
                showAlertErrors(result);
                return;
            }
            document.getElementById('email').value = '';
            document.getElementById('password').value = '';
            document.getElementById('passwordRepeat').value = '';
            const result = await response.json();
            alert(result.message);

        } catch (error) {
            console.error('Сетевая ошибка сервера:', error);
            alert('Произошла ошибка но это не наша вина, а вашего провайдера.. я надеюсь...');
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
                const fieldValue = errorResponse.details[field];

                if (Array.isArray(fieldValue)) {
                    // Для ошибок валидации (массивы сообщений)
                    fieldValue.forEach(errorText => {
                        alertMessage += `- ${errorText}\n`;
                    });
                } else {
                    // Для системных ошибок (строки/числа)
                    alertMessage += `- ${field}: ${fieldValue}\n`;
                }
            });
        }

        if (alertMessage.trim() !== 'Внимание:') {
            alert(alertMessage);
        } else {
            alert('Произошла ошибка, но детали недоступны.');
        }
    }
});
