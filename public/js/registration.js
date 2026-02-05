document.addEventListener('DOMContentLoaded', function() {

    const emailInput = document.getElementById('user_registration_email');
    const emailStatus = document.getElementById('user_email_status');

    if (!emailInput) {
        console.error('Элемент #user_registration_email не найден!');
        return;
    }
    if (!emailStatus) {
        console.error('Элемент #user_email_status не найден!');
        return;
    }

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
                return true
            }

            const data = await response.json();

            return { success: true, ...data };
        } catch (error) {
            console.error('Ошибка проверки email:', error);
            return true;
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

                if (isTaken.error) {
                    emailStatus.textContent = isTaken.error;
                    emailStatus.style.color = 'grey';
                } else if (isTaken.exists) {
                    emailStatus.textContent = 'Этот Email занят!';
                    emailStatus.style.color = 'red';
                } else {
                    emailStatus.textContent = 'Email свободен!';
                    emailStatus.style.color = 'green';
                }
            }, 500);
        }
    });
});
