@extends('layout.admin')

@section('title')
    Admin Profile
@endsection

@section('content')
<div class="w-100 p-4">
    <div class="container-fluid">
        <div class="d-flex" style="gap: 20px;">

            <!-- Left Column (Stacked Cards) -->
            <div class="d-flex flex-column" style="flex: 1.5; gap: 20px;">
                <div class="card p-3">
                    <div class="card-body">
                        <h5 class="card-title">Unavailable Trains</h5>
                        <p class="card-text">
                            Total {{ count($unavailableTrains) }} train(s) are not scheduled, reschedule them 
                            <a href="#" data-toggle="modal" data-target=".bd-example-modal-xl">now!</a>
                        </p>
                    </div>
                </div>

                <div class="card p-3 flex-grow-1">
                    <div class="card-body">
                        <h5 class="card-title">Extra Card</h5>
                        <p class="card-text">Some more content on the right side.</p>
                    </div>
                </div>
            </div>

            <!-- Middle Column (Main Content) -->
            <div class="card p-3 flex-grow-1">
                <div class="card-body">
                    <h5 class="card-title">Mid</h5>
                    <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                </div>
            </div>

            <!-- Right Column (Single Full-Row Card) -->
            <div class="card p-3" style="flex: 1.5; max-width: 300px; width: 100%;"> <!-- Adjust flex and added max-width -->
    <div class="card-body">
        <div class="calendar">
            <div class="calendar-header">
                <span class="month-picker" id="month-picker">February</span>
                <div class="year-picker">
                    <span class="year-change" id="prev-year">
                        <pre><</pre>
                    </span>
                    <span id="year">2021</span>
                    <span class="year-change" id="next-year">
                        <pre>></pre>
                    </span>
                </div>
            </div>
            <div class="calendar-body">
                <div class="calendar-week-day">
                    <div>Sun</div>
                    <div>Mon</div>
                    <div>Tue</div>
                    <div>Wed</div>
                    <div>Thu</div>
                    <div>Fri</div>
                    <div>Sat</div>
                </div>
                <div class="calendar-days"></div>
            </div>
            <div class="month-list"></div>
        </div>
    </div>
</div>
        </div>

        <!-- Card Deck Section (Cards in Rows) -->
        <div class="d-flex mt-4" style="gap: 20px;">
            <div class="card col-8">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                </div>
            </div>

            <div class="card p-3" style="flex: 2.5;">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="col-12">
                    @if($unavailableTrains->isEmpty())
                        <p>No unavailable trains found.</p>
                    @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Train Name</th>
                                <th>Departure Time</th>
                                <th>Arrival Time</th>
                                <th>Source</th>
                                <th>Destination</th>
                                <th style="text-align: center;">Reschedule</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unavailableTrains as $train)
                                @foreach($train->trainupdowns as $index => $updown)
                                    @php
                                        $departureTime = \Carbon\Carbon::parse($updown->tdepdate . ' ' . $updown->tdeptime);
                                        $status = $departureTime->isPast() ? 'Unavailable' : 'Available';
                                    @endphp

                                    @if($status === 'Unavailable')
                                        <tr>
                                            <td>{{ $train->trainname }}</td>
                                            <td>{{ \Carbon\Carbon::parse($updown->tdepdate)->format('d-m-Y') }} 
                                                {{ \Carbon\Carbon::parse($updown->tdeptime)->format('h:i A') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($updown->tarrdate)->format('d-m-Y') }} 
                                                {{ \Carbon\Carbon::parse($updown->tarrtime)->format('h:i A') }}</td>
                                            <td>{{ $updown->tsource }}</td>
                                            <td>{{ $updown->tdestination }}</td>
                                            <td>
                                                <a style="position: relative; left: 50%; transform: translateX(-50%); text-align: center;" 
                                                    href="{{ route('train.edit', $train->trainid) }}" 
                                                    class="btn update-btn btn-sm">
                                                    Reschedule
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Base Colors for Light and Dark Mode */
:root {
  --bg-main: #ffffff; /* Light Mode Background */
  --bg-second: #f0f0f0; /* Light Mode Secondary Background */
  --color-txt: #333333; /* Light Mode Text Color */
  --color-hover: #f0f0f0; /* Light Mode Hover Color */
  --light-hover: #f5f5f5; /* Light Mode Hover Color */
  --dark-hover: #555555; /* Dark Mode Hover Color */
  --shadow: 0px 2px 6px rgba(0, 0, 0, 0.1); /* Light Mode Shadow */

  --bg-main-dark: #333333; /* Dark Mode Background */
  --bg-second-dark: #444444; /* Dark Mode Secondary Background */
  --color-txt-dark: #ffffff; /* Dark Mode Text Color */
  --color-hover-dark: #555555; /* Dark Mode Hover Color */
  --light-hover-dark: #666666; /* Dark Mode Light Hover */
  --dark-hover-dark: #888888; /* Dark Mode Dark Hover */
}

/* Dark Mode Styles */
.dark {
  --bg-main: var(--bg-main-dark);
  --bg-second: var(--bg-second-dark);
  --color-txt: var(--color-txt-dark);
  --color-hover: var(--color-hover-dark);
  --light-hover: var(--light-hover-dark);
  --dark-hover: var(--dark-hover-dark);
  --shadow: 0px 2px 6px rgba(0, 0, 0, 0.4); /* Dark Mode Shadow */
}

/* Light Mode Calendar */
.card .calendar {
  width: 100%;
  background-color: var(--bg-main);
  border-radius: 8px;
  padding: 6px; /* Reduced padding */
  position: relative;
  overflow: hidden;

  font-size: 10px; /* Smaller font size */
}

.card-body {
  padding: 0;
}

.calendar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 12px; /* Smaller header text */
  font-weight: 600;
  color: var(--color-txt);
  padding: 2px 0; /* Reduced padding */
}

.calendar-week-day {
  height: 28px; /* Smaller row height */
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  font-weight: 600;
  font-size: 9px; /* Smaller font size */
}

.calendar-week-day div {
  display: grid;
  place-items: center;
  color: #FF4B4B;
}

.calendar-days {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 1px;
  color: var(--color-txt);
}

.calendar-days div {
  width: 28px; /* Smaller day cell width */
  height: 28px; /* Smaller day cell height */
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1px; /* Smaller padding */
  position: relative;
  cursor: pointer;
  animation: to-top 1s forwards;
}

.calendar-days div:hover {
  background-color: var(--light-hover);
  border-radius: 8px;
  transition: background-color 0.3s ease;
}

.dark .calendar-days div:hover {
  background-color: var(--dark-hover);
}

/* Highlight current date */
.calendar-days div.curr-date {
  background-color: #FF4B4B;
  color: white;
  border-radius: 5px;  
  width: 28px; /* Ensure width and height are equal for square */
  height: 28px;
  padding: 4px; /* Smaller padding */
}

.calendar-days div.curr-date:hover {
  background-color: #FF6B6B;
}

.month-picker {
  padding: 2px 4px; /* Reduced padding */
  border-radius: 8px;
  cursor: pointer;
  color: #FF4B4B;
  font-size: 9px; /* Smaller font size */
  transition: background-color 0.3s ease;
}

.month-picker:hover {
  background-color: #ffffff !important;
  color: #FF4B4B;
}

.year-picker {
  display: flex;
  align-items: center;
}

.year-change {
  height: 22px; /* Smaller button size */
  width: 22px; /* Smaller button size */
  border-radius: 50%;
  display: grid;
  place-items: center;
  margin: 0 3px; /* Reduced margin */
  cursor: pointer;
}

.year-change:hover {
  background-color: var(--color-hover);
}

.calendar-footer {
  padding: 2px 0; /* Reduced padding */
  display: flex;
  justify-content: flex-end;
  align-items: center;
}

.toggle {
  display: flex;
}

.toggle span {
  margin-right: 4px; /* Reduced margin */
  color: var(--color-txt);
}

.dark-mode-switch {
  position: relative;
  width: 30px; /* Smaller switch width */
  height: 15px; /* Smaller switch height */
  border-radius: 10px;
  background-color: var(--bg-second);
  cursor: pointer;
}

.dark-mode-switch-ident {
  width: 14px; /* Smaller switch knob */
  height: 14px; /* Smaller switch knob */
  border-radius: 50%;
  background-color: var(--bg-main);
  position: absolute;
  top: 1px;
  left: 1px;
  transition: left 0.2s ease-in-out;
}

.dark .dark-mode-switch .dark-mode-switch-ident {
  left: calc(1px + 50%);
}

.month-list {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  background-color: var(--bg-main);
  padding: 6px; /* Reduced padding */
  grid-template-columns: repeat(3, auto);
  gap: 2px; /* Reduced gap */
  display: grid;
  transform: scale(1.1);
  visibility: hidden;
  pointer-events: none;
}

.month-list.show {
  transform: scale(1);
  visibility: visible;
  pointer-events: visible;
  transition: all 0.2s ease-in-out;
}

.month-list > div {
  display: grid;
  place-items: center;
}

.month-list > div > div {
  width: 100%;
  padding: 4px 8px; /* Reduced padding */
  border-radius: 8px;
  text-align: center;
  cursor: pointer;
  color: var(--color-txt);
}

.month-list > div > div:hover {
  background-color: var(--color-hover);
}

@keyframes to-top {
  0% {
    transform: translateY(100%);
    opacity: 0;
  }
  100% {
    transform: translateY(0);
    opacity: 1;
  }
}


</style>
<script>
 let calendar = document.querySelector('.calendar')

const month_names = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']

isLeapYear = (year) => {
    return (year % 4 === 0 && year % 100 !== 0 && year % 400 !== 0) || (year % 100 === 0 && year % 400 ===0)
}

getFebDays = (year) => {
    return isLeapYear(year) ? 29 : 28
}

generateCalendar = (month, year) => {
    let calendar_days = calendar.querySelector('.calendar-days')
    let calendar_header_year = calendar.querySelector('#year')

    let days_of_month = [31, getFebDays(year), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]

    calendar_days.innerHTML = ''

    let currDate = new Date()
    if (!month) month = currDate.getMonth()
    if (!year) year = currDate.getFullYear()

    let curr_month = `${month_names[month]}`
    month_picker.innerHTML = curr_month
    calendar_header_year.innerHTML = year

    let first_day = new Date(year, month, 1)

    for (let i = 0; i <= days_of_month[month] + first_day.getDay() - 1; i++) {
        let day = document.createElement('div')
        if (i >= first_day.getDay()) {
            day.classList.add('calendar-day-hover')
            day.innerHTML = i - first_day.getDay() + 1
            day.innerHTML += `<span></span>`

            // Highlight current date
            if (i - first_day.getDay() + 1 === currDate.getDate() && year === currDate.getFullYear() && month === currDate.getMonth()) {
                day.classList.add('curr-date')
            }
        }
        calendar_days.appendChild(day)
    }
}

let month_list = calendar.querySelector('.month-list')

month_names.forEach((e, index) => {
    let month = document.createElement('div')
    month.innerHTML = `<div data-month="${index}">${e}</div>`
    month.querySelector('div').onclick = () => {
        month_list.classList.remove('show')
        curr_month.value = index
        generateCalendar(index, curr_year.value)
    }
    month_list.appendChild(month)
})

let month_picker = calendar.querySelector('#month-picker')

month_picker.onclick = () => {
    month_list.classList.add('show')
}

let currDate = new Date()

let curr_month = {value: currDate.getMonth()}
let curr_year = {value: currDate.getFullYear()}

generateCalendar(curr_month.value, curr_year.value)

document.querySelector('#prev-year').onclick = () => {
    --curr_year.value
    generateCalendar(curr_month.value, curr_year.value)
}

document.querySelector('#next-year').onclick = () => {
    ++curr_year.value
    generateCalendar(curr_month.value, curr_year.value)
}

</script>

@endsection
