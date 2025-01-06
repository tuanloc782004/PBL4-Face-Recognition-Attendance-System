document.addEventListener("DOMContentLoaded", function () {
    const todayInput = document.getElementById("today");
    const btnClassInDay = document.getElementById("btnClassInDay");
    const classList = document.getElementById("classList");

    const newToday = new Date();
    const setDay = newToday.toISOString().split("T")[0];
    console.log("Ngày hiện tại từ client:", setDay); 
    todayInput.value = setDay;

    btnClassInDay.addEventListener("click", function (event) {
        event.preventDefault();
        fetch("attendanceView.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "today=" + setDay
        })
        .then(response => response.text())
        .then(data => {
            console.log("Dữ liệu từ server:", data);
            classList.innerHTML = data;
            const checkButtons = document.querySelectorAll(".btnCheck");
            checkButtons.forEach(button => {
                button.addEventListener("click", function (e) {
                    e.preventDefault();

                    const startTime = this.getAttribute("data-start-time");
                    const endTime = this.getAttribute("data-end-time");
                    const currentTime = new Date();

                    // Chuyển startTime và endTime thành đối tượng Date
                    const [startHours, startMinutes] = startTime.split(":").map(Number);
                    const [endHours, endMinutes] = endTime.split(":").map(Number);
                    const startDate = new Date();
                    const endDate = new Date();
                    startDate.setHours(startHours, startMinutes, 0, 0);
                    endDate.setHours(endHours, endMinutes, 0, 0);

                    // Kiểm tra nếu giờ hiện tại nằm trong khoảng thời gian
                    if (currentTime < startDate) {
                        alert("Chưa đến giờ học. Vui lòng quay lại sau!");
                    } else if (currentTime > endDate) {
                        alert("Đã hết thời gian điểm danh!");
                    } else {
                        console.log("Đã đến giờ học. Tiến hành submit.");
                        this.closest("form").submit(); // Tiến hành gửi form
                    }
                });
            });
            // const checkForms = document.querySelectorAll(".classes form");
            // checkForms.forEach(form => {
            //     form.addEventListener("submit", function (e) {
            //         e.preventDefault(); // Ngăn hành vi submit mặc định của form
            //         console.log("Đã nhấn nút btnCheck", form);
            //         // Nếu cần thêm xử lý AJAX hoặc điều hướng:
            //         form.submit(); // hoặc xử lý bằng JavaScript
            //     });
            // });
        })
        .catch(error => {
            console.error("Lỗi khi gửi dữ liệu:", error);
            classList.innerHTML = '<tr><td colspan="5">Có lỗi xảy ra, vui lòng thử lại sau.</td></tr>';
        });
    });
});