document.addEventListener('DOMContentLoaded', function() {

    const emailInput = document.getElementById('user_registration_email');
    const emailStatus = document.getElementById('user_email_status');


    async function checkEmailAvailability(email) {
        try {
            const response = await fetch('/check-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email: email })
            });

            if (!response.ok) {
                console.error('Ошибка проверки email:', response.status);
                return {
                    success: false,
                    error: `Сервер вернул ошибку 400-500!`,
                };
            }

            return await response.json();
        } catch (error) {
            console.error('Ошибка проверки email:', error);
            return {
                success: false,
                error: 'Не удалось проверить Email! Непредвиденная ошибка!'
            }
        }
    }

    let timeoutId;

    emailInput.addEventListener('input', (e) => {

        const email = e.target.value.trim();

        emailStatus.textContent = '';

        if (email && email.includes('@')) {

            clearTimeout(timeoutId);

            timeoutId = setTimeout(async () => {

                const isTaken = await checkEmailAvailability(email);

                if (isTaken.success === false) {
                    emailStatus.textContent = isTaken.message;
                    emailStatus.style.color = 'grey';
                } else if (isTaken.data) {
                    emailStatus.textContent = isTaken.message;
                    emailStatus.style.color = 'red';
                } else {
                    emailStatus.textContent = isTaken.message;
                    emailStatus.style.color = 'green';
                }
            }, 500);
        }
    });

    const passwordInput = document.getElementById('user_registration_password');
    const passwordStatus = document.getElementById('user_password_status');


    async function checkPasswordStrength(password) {
        try {
            const response = await fetch('/check-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ password: password })
            });

            if (!response.ok) {
                console.error('Ошибка проверки пароля:', response.status);
                return {
                    success: false,
                    error: `Сервер вернул статус с ошибкой!`,
                }
            }

            return await response.json();
        } catch (error) {
            console.error('Ошибка проверки пароля:', error);
            return {
                success: false,
                error: 'Произошла ошибка при проверке пароля.' };
        }
    }

    passwordInput.addEventListener('input', (e) => {
        const password = e.target.value.trim();
        passwordStatus.textContent = '';

        if (password.length > 0) {
            clearTimeout(timeoutId);

            timeoutId = setTimeout(async () => {
                const result = await checkPasswordStrength(password);

                if (result.error) {
                    passwordStatus.textContent = result.message;
                    passwordStatus.style.color = 'red';
                } else {
                    passwordStatus.textContent = result.message;
                    passwordStatus.style.color = 'green';
                }
            }, 500);
        }
    });
});


