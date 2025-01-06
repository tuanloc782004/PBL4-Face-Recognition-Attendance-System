<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chụp hình học viên</title>
    <style>
        body {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
            top: 0;
            right: 0;
            left: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .wapper {
            width: 800px;
            height: 580px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #ece9e9;
        }

        .btn-list {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn-list .btnChup, .btnLuuAnh {
            width: 80px;
            height: auto;
            background-color: #DD2F6E;
            font-size: 18px;
            border: 1px solid #fff;
            padding: 7px;
            border-radius: 10px;
            color: #fff;
            font-family: sans-serif;

        }

        .btn-list .btnChup:focus, .btnLuuAnh:focus {
            background-color: #db2727;
        }

        #cameraFeed {
            width: 640px;
            height: 480px;
            border: 1px solid black;
        }

    </style>
</head>
<body>
    <section class="wapper">
        <div class="wapper-cature">
            <div class="camera">
                <img id="cameraFeed" alt="Camera Feed" style="max-width: 100%; height: auto;"/>
            </div>
            <div class="btn-list">
                <button class="btnChup" id="captureButton">Chụp</button>
            </div>
        </div>
    </section>

    <script>
        const ws = new WebSocket("ws://192.168.127.48:8765");
        ws.onmessage = (event) => {
            if (typeof event.data === "string") {
                if (event.data === "captured") {
                    alert("Đã chụp 20 ảnh thành công!");
                } else if (event.data === "stopped") {
                    alert("Đã dừng kết nối WebSocket");
                    window.location.href = "classView.php";
                    alert("Lưu học sinh thành công");
                }
            } else {
                const blob = new Blob([event.data], { type: "image/jpeg" });
                const url = URL.createObjectURL(blob);
                document.getElementById("cameraFeed").src = url;
            }
        };
        document.getElementById("captureButton").onclick = () => {
            ws.send("capture");
            ws.send("end");
        };
    </script>

    <!-- <script>
        const ws = new WebSocket("ws://192.168.4.48:8765");

        ws.onopen = () => {
            console.log("Kết nối WebSocket đã mở");
            ws.send("start");
        };

        ws.onmessage = (event) => {
            if (typeof event.data === "string" && event.data === "stopped") {
                console.log("Đã dừng kết nối");
                window.location.href = "anotherpage.php";
            } else if (event.data === "captured") {
                alert("Đã chụp 20 ảnh thành công!");
            } else {
                const blob = new Blob([event.data], { type: 'image/jpeg' });
                const url = URL.createObjectURL(blob);
                document.getElementById("cameraFeed").src = url;
            }
        };

        document.getElementById("captureButton").onclick = () => {
            ws.send("capture");
        };

        document.getElementById("endButton").onclick = () => {
            ws.send("end");
        };

        ws.onclose = () => {
            console.log("Kết nối WebSocket đã đóng");
        };

        ws.onerror = (error) => {
            console.log("Lỗi WebSocket: " + error);
        };
    </script> -->
</body>
</html>