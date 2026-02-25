document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const statusDiv = document.getElementById('user_email_status');


    emailInput.addEventListener('input', async function() {
        const email = emailInput.value.trim();

        // Если поле пустое — очищаем статус и выходим
        if (!email) {
            statusDiv.textContent = '';
            statusDiv.className = 'alarm';
            return;
        }

        if (!email.includes('@')) {
            statusDiv.textContent = '';
            statusDiv.classList.remove('available', 'error');
            statusDiv.classList.add('alarm');
            return;
        }

        try {
            const response = await fetch('/api/email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email: email })
            });

            if (!response.ok) {
                const data = await response.json();

                statusDiv.textContent = data.details['user.email']
                statusDiv.className = 'alarm error';
                return;
            }

            statusDiv.textContent = 'Email свободен!'
            statusDiv.className = 'alarm available';

        } catch (error) {
            statusDiv.textContent = '⚠️ Ошибка сети или сервера, соединение потеряно';
            statusDiv.className = 'alarm error';
        }
    });
});
