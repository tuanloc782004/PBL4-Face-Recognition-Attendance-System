const btnView = document.querySelectorAll(".btnView"),
      btntrash = document.querySelectorAll(".btntrash"),
      btnClose = document.querySelector(".btnClose"),
      btnClose1 = document.querySelector(".btnClose1"),
      btnClose2 = document.querySelector(".btnClose2"),
      btnUpdate = document.querySelector(".btnUpdate"),
      btnSave = document.querySelector(".btnSave"),
      btnNew =document.querySelector(".btnNew"),

      confirmBox = document.getElementById("confirmBox"),
      btnYes = document.getElementById("btnYes"),
      btnNo = document.getElementById("btnNo");

      btnThoat = document.querySelectorAll(".btnThoat"),
      mainCard = document.querySelector(".main-card"),
      attendanceList = document.querySelector(".attendance-list");
      xemthongtin = document.querySelector(".xemthongtin"),
      suathongtin = document.querySelector(".suathongtin"),
      themhocvien =document.querySelector(".themhocvien"),
      user = document.querySelector(".user");

btnView.forEach(button => {
    button.addEventListener("click", function (event) {
        event.preventDefault(); 
        const form = button.closest("form"); 
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');
            const studentsTable = doc.querySelector('.attendance-list tbody');
            attendanceList.querySelector('tbody').innerHTML = studentsTable.innerHTML;
            mainCard.style.display = "none";
            attendanceList.style.display = "block"; 
        })
        .catch(error => console.error('Error:', error));
    });
});

btnThoat.forEach(button => {
    button.addEventListener("click", function (event) {
        event.preventDefault(); 

        attendanceList.style.display = "none";
        mainCard.style.display = "block";
    });
});

attendanceList.addEventListener("click", function (event) {
    event.preventDefault();
    const button = event.target.closest('button');
    if (!button) return;

    if (button.classList.contains("btninfo")) {
        const studentID = button.getAttribute("data-idxembtn");
        fetch(`getInfoStudent.php?id_hv=${studentID}`)
            .then(response => { 
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`); 
                }
                return response.json()})
            .then(data => {
                if (data.success) {  
                    const studentData = data.student; 
                    document.getElementById('idxem').value = studentData.ID;
                    document.getElementById('tenxem').value = studentData.Ten;
                    document.getElementById('datexem').value = studentData.NgaySinh;
                    document.getElementById('emailxem').value = studentData.Email;
                    document.getElementById('diachixem').value = studentData.DiaChi;
                    document.getElementById(studentData.GioiTinh === 'Nam' ? 'genderxemnam' : 'genderxemnu').checked = true;
                    // document.querySelector('.img-face img').src = studentData.HinhAnh || 'default-image.png'; 
                    
                    attendanceList.classList.add("dimmed");
                    user.classList.add("dimmed");
                    xemthongtin.style.display = "block";
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error('Error fetching student info:', error));
    }
    
    else if (button.classList.contains("btnedit")) {
        const studentID = button.getAttribute("data-idsuabtn");
        fetch(`getInfoStudent.php?id_hv=${studentID}`)
            .then(response => { 
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`); 
                }
                return response.json()})
            .then(data => {
                if (data.success) {  
                    const studentData = data.student; 
                    document.getElementById('idsua').value = studentData.ID;
                    document.getElementById('tensua').value = studentData.Ten;
                    document.getElementById('datesua').value = studentData.NgaySinh;
                    document.getElementById('emailsua').value = studentData.Email;
                    document.getElementById('diachisua').value = studentData.DiaChi;
                    document.getElementById(studentData.GioiTinh === 'Nam' ? 'gendersuanam' : 'gendersuanu').checked = true;
                    // document.querySelector('.img-face img').src = studentData.HinhAnh || 'default-image.png'; 
                    
                    attendanceList.classList.add("dimmed");
                    user.classList.add("dimmed");
                    suathongtin.style.display = "block";
                } else {
                    console.error(data.message);
                } 
            })
            .catch(error => console.error('Error fetching student info:', error));
    } 

    else if (button.classList.contains("btntrash")) {
        attendanceList.classList.add("dimmed");
        user.classList.add("dimmed");
        confirmBox.classList.remove("hidden");
        const id = button.getAttribute("data-idxoabtn");
        btnYes.setAttribute("data-idHV", id);
    }
});

btnNew.addEventListener("click", function(event){
        event.preventDefault();
        mainCard.classList.add("dimmed");
        user.classList.add("dimmed");
        themhocvien.style.display = "block"; 
    }
)

btnNo.addEventListener("click", function () {
    attendanceList.classList.remove("dimmed"); 
    user.classList.remove("dimmed"); 
    confirmBox.classList.add("hidden");
});

btnYes.addEventListener("click", function () {
    const id = btnYes.getAttribute("data-idHV");
    fetch(`deleteStudent.php?id_hv=${id}`)
        .then(response => { 
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`); 
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Xóa thành công.');
                window.location.reload(); // Reload the page after successful deletion
            } else {
                alert(data.message || 'Có lỗi xảy ra khi xóa học viên.'); // Show error message if provided
            }
        })
        .catch(error => console.error('Error fetching student info:', error));

    attendanceList.classList.remove("dimmed"); 
    user.classList.remove("dimmed"); 
    confirmBox.classList.add("hidden");
});

btnSave.addEventListener("click", function(event) {
    document.getElementById("addForm").submit();
})

btnClose.addEventListener("click", function () {
    xemthongtin.style.display = "none";
    attendanceList.classList.remove("dimmed"); 
    user.classList.remove("dimmed"); 
});

btnClose1.addEventListener("click", function () {
    suathongtin.style.display = "none";
    attendanceList.classList.remove("dimmed"); 
    user.classList.remove("dimmed"); 
});

btnClose2.addEventListener("click", function () {
    themhocvien.style.display = "none";
    mainCard.classList.remove("dimmed"); 
    user.classList.remove("dimmed"); 
});