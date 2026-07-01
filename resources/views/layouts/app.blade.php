<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AdSpot — Рекламные площадки Молдовы')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('img/fav.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/fav.jpg') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @livewireStyles
</head>
<body>

@auth
    <livewire:auth.legal-modal />
@endauth

@include('layouts.header')

<main>
    @yield('content')
</main>

@include('layouts.footer')


<script>
    function spotCalendar({ occupied, minDays, dateFrom, dateTo }) {
        return {
            occupied,
            minDays,
            dateFrom,
            dateTo,
            currentYear: new Date().getFullYear(),
            currentMonth: new Date().getMonth(),
            selecting: 'from', // 'from' или 'to'

            init() {
                // Если есть предзаполненные даты (продление) — показываем их месяц
                if (this.dateFrom) {
                    const d = new Date(this.dateFrom);
                    this.currentYear = d.getFullYear();
                    this.currentMonth = d.getMonth();
                }
            },

            monthTitle() {
                const months = ['Январь','Февраль','Март','Апрель','Май','Июнь',
                    'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'];
                return months[this.currentMonth] + ' ' + this.currentYear;
            },

            prevMonth() {
                if (this.currentMonth === 0) {
                    this.currentMonth = 11;
                    this.currentYear--;
                } else {
                    this.currentMonth--;
                }
            },

            nextMonth() {
                if (this.currentMonth === 11) {
                    this.currentMonth = 0;
                    this.currentYear++;
                } else {
                    this.currentMonth++;
                }
            },

            calendarCells() {
                const cells = [];
                const year  = this.currentYear;
                const month = this.currentMonth;
                const today = new Date();
                today.setHours(0,0,0,0);

                const firstDay = new Date(year, month, 1);
                // Понедельник = 0
                let startDow = firstDay.getDay() - 1;
                if (startDow < 0) startDow = 6;

                // Пустые ячейки в начале
                for (let i = 0; i < startDow; i++) {
                    cells.push({ key: 'empty-' + i, day: '', date: null, selectable: false, occupied: false, empty: true });
                }

                const daysInMonth = new Date(year, month + 1, 0).getDate();

                for (let d = 1; d <= daysInMonth; d++) {
                    const date     = new Date(year, month, d);
                    const dateStr  = this.formatDate(date);
                    const isPast   = date < today;
                    const isOcc    = this.occupied.includes(dateStr);

                    cells.push({
                        key:       dateStr,
                        day:       d,
                        date:      dateStr,
                        occupied:  isOcc,
                        past:      isPast,
                        selectable: !isPast && !isOcc,
                        inRange:   this.isInRange(dateStr),
                        isFrom:    dateStr === this.dateFrom,
                        isTo:      dateStr === this.dateTo,
                    });
                }

                return cells;
            },

            isInRange(dateStr) {
                if (!this.dateFrom || !this.dateTo) return false;
                return dateStr > this.dateFrom && dateStr < this.dateTo;
            },

            cellStyle(cell) {
                if (cell.empty) return 'background:transparent;';
                if (cell.isFrom || cell.isTo) return 'background:#5B21B6; color:white; cursor:pointer; font-weight:700;';
                if (cell.inRange)  return 'background:#EDE9FE; color:#5B21B6; cursor:pointer;';
                if (cell.occupied) return 'background:#FEE2E2; color:#FCA5A5; cursor:not-allowed;';
                if (cell.past)     return 'background:#f3f4f6; color:#d1d5db; cursor:not-allowed;';
                return 'background:#DCFCE7; color:#15803D; cursor:pointer;';
            },

            selectDate(dateStr) {
                if (!dateStr) return;

                if (!this.dateFrom || this.selecting === 'from' || (this.dateFrom && this.dateTo)) {
                    // Начинаем новый выбор
                    this.dateFrom  = dateStr;
                    this.dateTo    = '';
                    this.selecting = 'to';
                    this.syncToLivewire();
                    return;
                }

                // Выбираем конечную дату
                if (dateStr <= this.dateFrom) {
                    // Если кликнули раньше start — начинаем заново
                    this.dateFrom  = dateStr;
                    this.dateTo    = '';
                    this.selecting = 'to';
                    this.syncToLivewire();
                    return;
                }

                // Проверяем нет ли занятых дат в диапазоне
                const hasConflict = this.occupied.some(occ => occ > this.dateFrom && occ <= dateStr);
                if (hasConflict) {
                    alert('В выбранном периоде есть занятые даты. Выберите другой период.');
                    return;
                }

                // Проверяем минимальный срок
                const fromDate = new Date(this.dateFrom);
                const toDate   = new Date(dateStr);
                const days     = Math.round((toDate - fromDate) / (1000 * 60 * 60 * 24)) + 1;

                if (days < this.minDays) {
                    alert('Минимальный срок аренды: ' + this.minDays + ' дн.');
                    return;
                }

                this.dateTo    = dateStr;
                this.selecting = 'from';
                this.syncToLivewire();
            },

            syncToLivewire() {
                this.$nextTick(() => {
                    $wire.set('date_from', this.dateFrom);
                    $wire.set('date_to',   this.dateTo);
                });
            },

            formatDate(date) {
                const y = date.getFullYear();
                const m = String(date.getMonth() + 1).padStart(2, '0');
                const d = String(date.getDate()).padStart(2, '0');
                return `${y}-${m}-${d}`;
            }
        };
    }
</script>

@livewireScripts
</body>
</html>
