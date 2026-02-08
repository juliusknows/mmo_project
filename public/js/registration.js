document.addEventListener('DOMContentLoaded', () => {
    // Элементы формы
    const emailInput = document.getElementById('user_registration_email');
    const emailStatus = document.getElementById('user_email_status');

    const passwordInput = document.getElementById('user_registration_password');
    const passwordStatus = document.getElementById('user_password_status');

    const passwordRepeatInput = document.getElementById('user_registration_passwordRepeat');
    const passwordRepeatStatus = document.getElementById('user_passwordRepeat_status');

    // Кнопка отправки
    const submitBtn = document.querySelector('.btn');

    // Состояние валидности полей
    const validationState = {
        email: false,
        password: false,
        passwordRepeat: false
    };

    // Общий fetch‑запрос
    async function apiRequest(url, data) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (!response.ok) console.error(`HTTP ${response.status}`);
            return await response.json();
        } catch (error) {
            console.error(`Ошибка запроса к ${url}:`, error);
            return { success: false, messageMail: 'Не удалось выполнить запрос.' };
        }
    }

    function setupValidation(input, statusEl, validateFn, delay = 500) {
        let timeoutId;

        input.addEventListener('input', (e) => {
            const value = e.target.value.trim();
            statusEl.textContent = '';
            statusEl.style.color = '';

            if (value) {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(async () => {
                    const result = await validateFn(value);

                    // 1. Выбираем сообщение в зависимости от поля
                    let message;
                    if (input === emailInput) {
                        message = result.messageMail || 'Проверка...';
                    } else if (input === passwordInput) {
                        message = result.messagePass || 'Проверка пароля...';
                    } else if (input === passwordRepeatInput) {
                        message = result.messageRepeat || result.messagePass || 'Проверка повтора...';
                    }

                    statusEl.textContent = message;

                    // 2. Анализируем data для цветовой индикации
                    if (
                        result.data &&
                        typeof result.data === 'object' &&
                        !Array.isArray(result.data)
                    ) {
                        if (input === passwordInput && 'password' in result.data) {
                            statusEl.style.color = result.data.password === true ? 'green' : 'red';
                        } else if (input === passwordRepeatInput && 'passwordRepeat' in result.data) {
                            statusEl.style.color = result.data.passwordRepeat === true ? 'green' : 'red';
                        } else {
                            // Для email или других полей — используем общий success
                            statusEl.style.color = result.success ? 'grey' : 'red';
                        }
                    } else {
                        // Если data нет или некорректна — полагаемся на success
                        statusEl.style.color = result.success ? 'green' : 'red';
                    }
                }, delay);
            }
        });
    }

    // Функция обновления состояния кнопки
    function updateSubmitButton() {
        const isValid =
            validationState.email &&
            validationState.password &&
            validationState.passwordRepeat;

        submitBtn.disabled = !isValid;

        // Визуальная обратная связь
        if (isValid) {
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
            submitBtn.style.backgroundColor = '#4CAF50'; // зелёный
        } else {
            submitBtn.style.opacity = '0.6';
            submitBtn.style.cursor = 'not-allowed';
            submitBtn.style.backgroundColor = ''; // сброс
        }
    }

    // 1. Валидация email
    if (emailInput && emailStatus) {
        setupValidation(emailInput, emailStatus, async (email) => {
            // Если @ ещё нет — очищаем статус и отмечаем как невалидное
            if (!email.includes('@')) {
                emailStatus.textContent = '';
                emailStatus.style.color = '';
                validationState.email = false;
                updateSubmitButton();
                return null;
            }

            // Отправляем запрос на сервер
            const result = await apiRequest('/check-email', { email });


            // Обновляем состояние валидности email
            validationState.email = result.success && result.messageMail === 'Этот email свободен!';
            updateSubmitButton();

            // Выводим сообщение и цвет
            let message = result.messageMail || 'Проверка...';
            emailStatus.textContent = message;
            emailStatus.style.color = validationState.email ? 'green' : 'red';


            return result;
        });
    }

    // 2. Валидация пароля
    if (passwordInput && passwordStatus) {
        setupValidation(passwordInput, passwordStatus, async () => {
            const password = passwordInput.value.trim();
            const passwordRepeat = passwordRepeatInput.value.trim();

            const result = await apiRequest('/check-password', { password, passwordRepeat });


            // Обновляем состояние пароля
            validationState.password = result.data?.password === true;
            updateSubmitButton();

            // Выводим сообщение и цвет
            let message = result.messagePass || 'Проверка пароля...';
            passwordStatus.textContent = message;
            passwordStatus.style.color = validationState.password ? 'green' : 'red';

            return result;
        });
    }

    // 3. Валидация повтора пароля
    if (passwordRepeatInput && passwordRepeatStatus) {
        setupValidation(passwordRepeatInput, passwordRepeatStatus, async () => {
            const password = passwordInput.value.trim();
            const passwordRepeat = passwordRepeatInput.value.trim();

            const result = await apiRequest('/check-password', { password, passwordRepeat });


            // Обновляем состояние повтора
            validationState.passwordRepeat = result.data?.passwordRepeat === true;
            updateSubmitButton();

            // Выводим сообщение и цвет
            let message = result.messageRepeat || result.messagePass || 'Проверка повтора...';
            passwordRepeatStatus.textContent = message;
            passwordRepeatStatus.style.color = validationState.passwordRepeat ? 'green' : 'red';

            return result;
        });
    }
});
