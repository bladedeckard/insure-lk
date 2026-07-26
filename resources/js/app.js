import './bootstrap';
import AirDatepicker from 'air-datepicker';
import 'air-datepicker/air-datepicker.css';
import 'air-datepicker/locale/ru';

document.addEventListener('livewire:load', function() {
    initAirDatepickers();
});

document.addEventListener('livewire:updated', function() {
    setTimeout(initAirDatepickers, 100);
});

function initAirDatepickers() {
    document.querySelectorAll('[data-air-datepicker]:not([data-air-initialized])').forEach(function(el) {
        el.setAttribute('data-air-initialized', 'true');
        new AirDatepicker(el, {
            locale: 'ru',
            dateFormat: 'yyyy-MM-dd',
            autoClose: true,
            selectedDates: el.value ? [el.value] : [],
            onSelect: function({dateStr, date}) {
                el.value = dateStr;
                el.dispatchEvent(new Event('input', { bubbles: true }));
                el.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });
}
