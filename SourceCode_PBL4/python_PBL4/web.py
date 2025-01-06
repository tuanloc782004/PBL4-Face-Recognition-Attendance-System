import asyncio
import websockets
import os
import cv2
import time
import numpy as np
import face_recognition
from picamera2 import Picamera2  # Thư viện để điều khiển camera trên Raspberry Pi
import pickle

# Tải mã hóa khuôn mặt đã biết từ file pickle
print("[INFO] loading encodings...")
with open("encodings.pickle", "rb") as f:
    data = pickle.loads(f.read())  # Đọc dữ liệu từ file pickle
known_face_encodings = data["encodings"]  # Mảng chứa các mã hóa khuôn mặt
known_face_names = data["names"]  # Mảng chứa các tên tương ứng với các khuôn mặt

# Khởi tạo camera
picam2 = Picamera2()  # Khởi tạo camera Raspberry Pi
picam2.configure(picam2.create_preview_configuration(main={"format": 'XRGB8888', "size": (640, 480)}))  # Cấu hình độ phân giải camera
picam2.start()  # Bắt đầu quay video
time.sleep(2)  # Đảm bảo camera sẵn sàng

# Hàm lấy ID kế tiếp khi tạo thư mục cho ảnh
def get_next_id():
    dataset_folder = "dataset"  # Thư mục chứa dữ liệu
    if not os.path.exists(dataset_folder):  # Nếu thư mục chưa tồn tại, tạo mới
        os.makedirs(dataset_folder)
    existing_ids = [int(folder) for folder in os.listdir(dataset_folder) if folder.isdigit()]  # Lấy các thư mục đã có ID là số
    return str(max(existing_ids, default=0) + 1)  # Trả về ID kế tiếp

# Hàm tạo thư mục mới để lưu ảnh cho người mới
def create_folder():
    person_id = get_next_id()  # Lấy ID kế tiếp
    folder_path = os.path.join("dataset", person_id)  # Tạo đường dẫn thư mục
    os.makedirs(folder_path, exist_ok=True)  # Tạo thư mục nếu chưa có
    return folder_path

# Hàm xử lý từng khung hình từ camera
def process_frame(frame):
    resized_frame = cv2.resize(frame, (0, 0), fx=0.25, fy=0.25)  # Thu nhỏ ảnh để xử lý nhanh hơn
    rgb_frame = cv2.cvtColor(resized_frame, cv2.COLOR_BGR2RGB)  # Chuyển đổi ảnh sang định dạng RGB

    face_locations = face_recognition.face_locations(rgb_frame)  # Phát hiện vị trí các khuôn mặt trong ảnh
    face_encodings = face_recognition.face_encodings(rgb_frame, face_locations)  # Tạo mã hóa khuôn mặt từ ảnh
    face_names = []  # Danh sách chứa tên của các khuôn mặt

    for face_encoding in face_encodings:
        matches = face_recognition.compare_faces(known_face_encodings, face_encoding)  # So sánh khuôn mặt với các khuôn mặt đã biết
        name = "Unknown"  # Mặc định là "Unknown"
        similarity_percentage = 0  # Độ chính xác mặc định

        if True in matches:  # Nếu tìm thấy khuôn mặt tương ứng
            best_match_index = np.argmin(face_recognition.face_distance(known_face_encodings, face_encoding))  # Tìm khuôn mặt phù hợp nhất
            similarity_percentage = (1 - face_recognition.face_distance(known_face_encodings, face_encoding)[best_match_index]) * 100  # Tính độ tương đồng

            if similarity_percentage >= 65:  # Nếu độ tương đồng trên 65%, xác nhận danh tính
                name = known_face_names[best_match_index] + ' - ' + f"{similarity_percentage:.2f}" + '%'
            else:
                name = "Unknown"

        face_names.append((name, similarity_percentage))  # Lưu tên và độ chính xác của khuôn mặt

    # Vẽ khung bao quanh khuôn mặt và hiển thị tên
    for (top, right, bottom, left), (name, _) in zip(face_locations, face_names):
        top, right, bottom, left = top * 4, right * 4, bottom * 4, left * 4  # Nhân tỉ lệ vì ảnh đã được thu nhỏ
        cv2.rectangle(frame, (left, top), (right, bottom), (0, 0, 255), 2)  # Vẽ khung bao quanh khuôn mặt
        cv2.rectangle(frame, (left, top - 35), (right, top), (0, 0, 255), cv2.FILLED)  # Vẽ nền cho tên
        font = cv2.FONT_HERSHEY_DUPLEX
        cv2.putText(frame, name, (left + 6, top - 6), font, 1.0, (255, 255, 255), 1)  # Hiển thị tên

    return frame, face_names  # Trả về khung hình đã xử lý và danh sách tên khuôn mặt

# Hàm xử lý kết nối WebSocket từ client
async def handle_client(websocket):
    folder_path = None  # Biến lưu đường dẫn thư mục ảnh

    async def send_frames():
        while True:
            frame = picam2.capture_array()  # Lấy khung hình từ camera
            processed_frame, face_names = process_frame(frame)  # Xử lý khung hình

            # Gửi ID của người nhận diện qua WebSocket nếu độ chính xác > 65%
            for name, similarity in face_names:
                if "Unknown" not in name and similarity >= 65:
                    person_id = name.split(' - ')[0]  # Lấy ID người từ tên
                    await websocket.send(person_id)  # Gửi ID qua WebSocket

            _, buffer = cv2.imencode('.jpg', processed_frame)  # Mã hóa khung hình thành định dạng JPEG
            await websocket.send(buffer.tobytes())  # Gửi khung hình qua WebSocket
            await asyncio.sleep(0.1)  # Dừng một chút trước khi gửi khung hình tiếp theo

    async def handle_messages():
        nonlocal folder_path
        try:
            while True:
                message = await websocket.recv()  # Nhận thông điệp từ client
                if message == "capture":  # Khi nhận được yêu cầu capture, tạo thư mục và lưu ảnh
                    folder_path = create_folder()
                    for i in range(20):  # Lưu 20 ảnh
                        filename = os.path.join(folder_path, f"{i}.jpg")
                        picam2.capture_file(filename)  # Lưu ảnh vào thư mục
                    await websocket.send("captured")  # Gửi thông báo đã capture ảnh xong
                elif message == "end":  # Khi nhận được yêu cầu kết thúc
                    await websocket.send("stopped")  # Gửi thông báo dừng
                    break  # Kết thúc vòng lặp

        except:
            pass  # Bỏ qua lỗi nếu có

    await asyncio.gather(send_frames(), handle_messages())  # Chạy cả hai hàm send_frames và handle_messages

# Hàm khởi chạy server WebSocket
async def main():
    async with websockets.serve(handle_client, "0.0.0.0", 8765):  # Mở server WebSocket trên cổng 8765
        print("WebSocket server running at ws://0.0.0.0:8765")  # In thông báo
        await asyncio.Future()  # Đợi server chạy mãi mãi

if __name__ == "__main__":
    asyncio.run(main())  # Chạy server WebSocket
