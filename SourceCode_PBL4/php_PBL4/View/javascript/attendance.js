function getCurrentTime() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

const detectedIDs = [],
    detectedNameElement = document.getElementById("detectedName"),
    detectedTimeElement = document.getElementById("detectedTime"),
    containerVideo = document.querySelector(".containerVideo");

const ws = new WebSocket("ws://192.168.127.48:8765"); // Đổi IP theo Raspberry Pi

ws.onopen = () => {
    console.log("Kết nối WebSocket đã mở");
};

const studentsPresentIDs = [];
const allStudents = [];
window.onload = () => {
    const studentCards = document.querySelectorAll(".studentCard");
    studentCards.forEach(card => {
        const idElement = card.querySelector("#idStudent");
        const studentID = idElement.getAttribute("data-id");
        allStudents.push(studentID); 
    });
};

ws.onmessage = (event) => {
    if (typeof event.data === "string") {
        if (event.data === "stopped") {
            alert("Đã dừng kết nối WebSocket");
            containerVideo.style.display = "none";
        } else {
            const detectedTime = getCurrentTime();
            const detectedID = event.data.trim(); // Lấy ID từ dữ liệu nhận được

            detectedNameElement.innerText = "ID: " + detectedID;
            detectedTimeElement.innerText = "Time: " + detectedTime;

            if (!detectedIDs.includes(detectedID)) {
                detectedIDs.push(detectedID);
                const studentCards = document.querySelectorAll(".studentCard");
                studentCards.forEach(card => {
                    const idElement = card.querySelector("#idStudent");
                    const checkButton = card.querySelector("#check");
                    const studentID = idElement.getAttribute("data-id");
                    if (studentID === detectedID) {
                        if (!studentsPresentIDs.some(student => student.id === studentID)) {
                            studentsPresentIDs.push({ id: studentID });
                            checkButton.classList.add("active");
                            checkButton.querySelector("i").classList.remove("las", "la-times");
                            checkButton.querySelector("i").classList.add("las", "la-check");
                        }
                    }
                });
            }
        }
    } else {
        const blob = new Blob([event.data], { type: "image/jpeg" });
        const url = URL.createObjectURL(blob);
        const imgElement = document.getElementById("cameraFeed");
        imgElement.src = url;
        imgElement.onload = () => {
            console.log("Frame loaded");
        };
    }
};
ws.onclose = () => {
    console.log("Kết nối WebSocket đã đóng");
};
ws.onerror = (error) => {
    alert("Lỗi WebSocket: " + error.message);
};
function getAbsentIDs() {
    const absentIDs = allStudents.filter(studentID =>
        !studentsPresentIDs.some(presentStudent => presentStudent.id === studentID)
    );
    return absentIDs;
}
document.getElementById("sendEmailButton").addEventListener("click", function (event) {
    event.preventDefault();
    const presentIDsJSON = JSON.stringify(studentsPresentIDs.map(student => student.id));
    const absentIDsJSON = JSON.stringify(getAbsentIDs()); 
    const allIDsJSON = JSON.stringify(allStudents);
    console.log("Present IDs:", presentIDsJSON);
    console.log("All IDs:", allIDsJSON);
    console.log("Absent IDs:", absentIDsJSON);
    fetch('http://localhost:3000/View/sendMail.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            presentIDs: studentsPresentIDs.map(student => student.id),
            absentIDs: getAbsentIDs()
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        console.log('Response from server:', data);
        alert('Email đã được gửi thành công!');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi gửi email. Vui lòng thử lại.');
    });
});