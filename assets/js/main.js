document.addEventListener('DOMContentLoaded', function() {
    // Функция для обработки отправки формы
    function handleFormSubmit(formId, modalId) {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Предотвращаем стандартную отправку

                // Собираем данные формы
                const formData = new FormData(this);
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                // Показываем лоадер
                this.querySelector('.form-content').style.display = 'none';
                this.querySelector('.form-loader').style.display = 'flex';

                // Отправляем данные через AJAX (без ожидания ответа)
                fetch('https://zemlirusi.ru/send_to_telegram.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                // Сразу показываем сообщение "Спасибо"
                this.querySelector('.form-loader').style.display = 'none';
                this.querySelector('.form-thanks').style.display = 'flex';
                this.reset(); // Очищаем форму

                // Закрываем модальное окно через 5 секунд и перезагружаем страницу
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
                    if (modal) {
                        modal.hide();
                    }
                    // Перезагружаем страницу
                    window.location.reload();
                }, 5000);
            });
        }
    }

    // Применяем обработчик к обеим формам
    handleFormSubmit('callModalForm', 'callModal');
    handleFormSubmit('pdfModalForm', 'pdfModal');

    // Сбрасываем состояние формы при открытии модального окна
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('show.bs.modal', function() {
            const form = this.querySelector('form');
            if (form) {
                form.querySelector('.form-content').style.display = 'flex';
                form.querySelector('.form-loader').style.display = 'none';
                form.querySelector('.form-error').style.display = 'none';
                form.querySelector('.form-thanks').style.display = 'none';
                form.reset();
            }
        });
    });
});