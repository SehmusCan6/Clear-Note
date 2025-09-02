const calendar = document.getElementById("calendar");
const monthLabel = document.getElementById("monthLabel");
const today = new Date();
let viewMonth = today.getMonth();
let viewYear = today.getFullYear();

const planPanel = document.getElementById("planPanel");
const selectedDateText = document.getElementById("selectedDate");

function drawCalendar(month, year) {
    calendar.innerHTML = "";
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const now = new Date();
    const currentDay = now.getDate();
    const currentMonth = now.getMonth();
    const currentYear = now.getFullYear();

    monthLabel.textContent = `${new Date(year, month).toLocaleString('default', { month: 'long' })} ${year}`;

    for (let i = 1; i <= daysInMonth; i++) {
        const box = document.createElement("div");
        box.classList.add("day");
        box.textContent = i;

        const isPast = (year < currentYear) || (year === currentYear && month < currentMonth) || (year === currentYear && month === currentMonth && i < currentDay);

        if (isPast) {
            box.classList.add("disabled");
        } else {
            if (!isPast) {
                box.addEventListener("click", () => {
                    const selectedDate = `${year}-${(month+1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
                    document.getElementById("formDate").value = selectedDate;
                    selectedDateText.textContent = `Selected Date: ${selectedDate}`;
                    planPanel.classList.add("active");

                });


            }
        }

        calendar.appendChild(box);
    }
}

function changeMonth(offset) {
    viewMonth += offset;
    if (viewMonth > 11) {
        viewMonth = 0;
        viewYear++;
    } else if (viewMonth < 0) {
        viewMonth = 11;
        viewYear--;
    }
    drawCalendar(viewMonth, viewYear);
}
drawCalendar(viewMonth, viewYear);
