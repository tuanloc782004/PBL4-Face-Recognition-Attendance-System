import face_recognition
import cv2
import numpy as np
from picamera2 import Picamera2
import time
import pickle

# Tải các mã hóa khuôn mặt đã được huấn luyện trước
print("[INFO] loading encodings...")
with open("encodings.pickle", "rb") as f:
    data = pickle.loads(f.read())  # Đọc và giải mã tệp pickle chứa thông tin khuôn mặt
known_face_encodings = data["encodings"]  # Lấy danh sách các mã hóa khuôn mặt đã biết
known_face_names = data["names"]  # Lấy danh sách các tên tương ứng với các mã hóa

# Khởi tạo camera
picam2 = Picamera2()
picam2.configure(picam2.create_preview_configuration(main={"format": 'XRGB8888', "size": (1080, 720)}))
picam2.start()  # Bắt đầu chụp từ camera

# Khởi tạo các biến cần thiết
cv_scaler = 4  # Đây là tỉ lệ thu nhỏ ảnh để tăng tốc độ xử lý (giảm độ phân giải ảnh, xử lý ít điểm ảnh hơn)

face_locations = []  # Danh sách các vị trí khuôn mặt phát hiện
face_encodings = []  # Danh sách các mã hóa khuôn mặt
face_names = []  # Danh sách tên của các khuôn mặt phát hiện
frame_count = 0  # Đếm số khung hình đã xử lý
start_time = time.time()  # Lưu thời gian bắt đầu để tính FPS
fps = 0  # Biến lưu trữ FPS (Frames per second)


# Hàm xử lý từng khung hình
def process_frame(frame):
    global face_locations, face_encodings, face_names

    # Thu nhỏ khung hình để tăng hiệu suất (giảm số lượng pixel cần xử lý)
    resized_frame = cv2.resize(frame, (0, 0), fx=(1 / cv_scaler), fy=(1 / cv_scaler))

    # Chuyển đổi ảnh từ BGR sang RGB vì thư viện face_recognition yêu cầu RGB, trong khi OpenCV sử dụng BGR
    rgb_resized_frame = cv2.cvtColor(resized_frame, cv2.COLOR_BGR2RGB)

    # Phát hiện tất cả các khuôn mặt và mã hóa khuôn mặt trong khung hình hiện tại
    face_locations = face_recognition.face_locations(rgb_resized_frame)  # Phát hiện vị trí khuôn mặt
    face_encodings = face_recognition.face_encodings(rgb_resized_frame, face_locations,
                                                     model='large')  # Mã hóa khuôn mặt

    face_names = []  # Khởi tạo danh sách tên khuôn mặt phát hiện
    for face_encoding in face_encodings:
        # Kiểm tra xem khuôn mặt có khớp với các khuôn mặt đã biết không
        matches = face_recognition.compare_faces(known_face_encodings,
                                                 face_encoding)  # So sánh với các khuôn mặt đã biết
        name = "Unknown"  # Mặc định là "Unknown" nếu không có sự khớp

        # Tính toán độ tương đồng giữa khuôn mặt phát hiện và các khuôn mặt đã biết
        face_distances = face_recognition.face_distance(known_face_encodings, face_encoding)
        best_match_index = np.argmin(face_distances)  # Chọn khuôn mặt có độ tương đồng nhỏ nhất
        # Tính toán độ chính xác (percentage)
        similarity_percentage = (1 - face_distances[best_match_index]) * 100  # Độ chính xác (%) từ 0 đến 100

        # Nếu độ chính xác dưới 65%, gán là "Unknown"
        if similarity_percentage < 65:
            name = "Unknown"
        else:
            if matches[best_match_index]:
                name = known_face_names[best_match_index]  # Nếu có sự khớp, lấy tên từ danh sách đã biết

        # In ID và độ chính xác
        print(f"ID: {name}, Accuracy: {similarity_percentage:.2f}%")

        face_names.append(name)  # Thêm tên vào danh sách

    return frame  # Trả về khung hình đã xử lý


# Hàm vẽ kết quả lên khung hình
def draw_results(frame):
    # Duyệt qua các vị trí khuôn mặt và tên để vẽ lên khung hình
    for (top, right, bottom, left), name in zip(face_locations, face_names):
        # Quy đổi lại vị trí khuôn mặt về kích thước gốc (vì ảnh đã bị thu nhỏ)
        top *= cv_scaler
        right *= cv_scaler
        bottom *= cv_scaler
        left *= cv_scaler

        # Vẽ hình chữ nhật xung quanh khuôn mặt
        cv2.rectangle(frame, (left, top), (right, bottom), (244, 42, 3), 3)

        # Vẽ nhãn tên dưới khuôn mặt
        cv2.rectangle(frame, (left - 3, top - 35), (right + 3, top), (244, 42, 3), cv2.FILLED)
        font = cv2.FONT_HERSHEY_DUPLEX
        cv2.putText(frame, name, (left + 6, top - 6), font, 1.0, (255, 255, 255), 1)  # Vẽ tên người lên ảnh

    return frame  # Trả về khung hình đã vẽ kết quả


# Hàm tính toán FPS
def calculate_fps():
    global frame_count, start_time, fps
    frame_count += 1  # Tăng số khung hình đã xử lý
    elapsed_time = time.time() - start_time  # Tính thời gian đã trôi qua
    if elapsed_time > 1:  # Nếu đã qua 1 giây
        fps = frame_count / elapsed_time  # Tính FPS
        frame_count = 0  # Đặt lại bộ đếm khung hình
        start_time = time.time()  # Đặt lại thời gian bắt đầu
    return fps  # Trả về FPS tính được


# Vòng lặp chính để đọc khung hình từ camera và xử lý
while True:
    # Chụp một khung hình từ camera
    frame = picam2.capture_array()

    # Xử lý khung hình bằng hàm đã định nghĩa
    processed_frame = process_frame(frame)

    # Vẽ các kết quả (như hộp quanh khuôn mặt và tên) lên khung hình
    display_frame = draw_results(processed_frame)

    # Tính toán và cập nhật FPS
    current_fps = calculate_fps()

    # Vẽ FPS lên khung hình
    cv2.putText(display_frame, f"FPS: {current_fps:.1f}", (display_frame.shape[1] - 150, 30),
                cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255, 0), 2)

    # Hiển thị khung hình đã vẽ kết quả và FPS
    cv2.imshow('Video', display_frame)

    # Thoát khỏi vòng lặp nếu nhấn phím 'q'
    if cv2.waitKey(1) == ord("q"):
        break

# Sau khi thoát khỏi vòng lặp, đóng tất cả cửa sổ và dừng camera
cv2.destroyAllWindows()
picam2.stop()
