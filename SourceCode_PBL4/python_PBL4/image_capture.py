import cv2
import os
from datetime import datetime
from picamera2 import Picamera2
import time

# Hàm để lấy ID tiếp theo tự động tăng dần (tạo ID cho người)
def get_next_id():
    dataset_folder = "dataset"  # Đặt thư mục lưu trữ dataset
    if not os.path.exists(dataset_folder):  # Kiểm tra nếu thư mục dataset chưa tồn tại
        os.makedirs(dataset_folder)  # Nếu chưa có, tạo thư mục dataset
    # Lấy danh sách các thư mục trong dataset có tên là số (ID của người)
    existing_ids = [int(folder) for folder in os.listdir(dataset_folder) if folder.isdigit()]
    next_id = max(existing_ids, default=0) + 1  # Lấy ID lớn nhất + 1 để tạo ID mới
    return str(next_id)  # Trả về ID mới dưới dạng chuỗi

# Hàm tạo thư mục mới để lưu ảnh cho người, với tên thư mục là ID của người đó
def create_folder():
    person_id = get_next_id()  # Lấy ID tiếp theo
    folder_path = os.path.join("dataset", person_id)  # Tạo đường dẫn thư mục cho người có ID đó
    os.makedirs(folder_path, exist_ok=True)  # Tạo thư mục nếu chưa tồn tại
    return folder_path  # Trả về đường dẫn thư mục đã tạo

# Hàm chụp ảnh và lưu ảnh vào thư mục của người dùng
def capture_photos():
    folder = create_folder()  # Tạo thư mục cho người mới

    # Khởi tạo camera Pi
    picam2 = Picamera2()  # Khởi tạo camera
    picam2.configure(picam2.create_preview_configuration(main={"format": 'XRGB8888', "size": (640, 640)}))  # Cấu hình camera
    picam2.start()  # Bắt đầu quay video

    # Để camera có thời gian khởi động trước khi bắt đầu chụp
    time.sleep(2)

    photo_count = 0  # Biến đếm số lượng ảnh đã chụp
    
    print(f"Đang chụp ảnh cho ID {folder}. Nhấn C để chụp 20 tấm, 'q' để thoát.")  # Thông báo hướng dẫn sử dụng
    
    while True:  # Vòng lặp chính, chạy liên tục cho đến khi nhấn phím 'q'
        # Chụp một khung hình từ camera
        frame = picam2.capture_array()
        
        # Hiển thị khung hình lên cửa sổ
        cv2.imshow('Capture', frame)
        
        key = cv2.waitKey(1) & 0xFF  # Đọc phím người dùng nhấn
        
        # Nếu phím 'c' được nhấn, chụp liên tiếp 20 tấm ảnh
        if key == ord('c'):  # Phím 'c' để chụp ảnh
            for i in range(20):  # Lặp lại 20 lần để chụp 20 tấm
                photo_count += 1  # Tăng số lượng ảnh đã chụp lên 1
                timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")  # Lấy thời gian hiện tại để tạo dấu thời gian
                filename = f"{timestamp}_{i + 1}.jpg"  # Tạo tên file cho ảnh với thời gian và số thứ tự
                filepath = os.path.join(folder, filename)  # Đường dẫn file ảnh
                cv2.imwrite(filepath, frame)  # Lưu ảnh vào thư mục đã tạo
                print(f"Đã lưu ảnh {photo_count}: {filepath}")  # In ra thông báo đã lưu ảnh
                time.sleep(0.04)  # Đợi 0.04 giây giữa các lần chụp để tránh các ảnh bị giống nhau
        
        # Nếu phím 'q' được nhấn, thoát khỏi vòng lặp chụp ảnh
        elif key == ord('q'):  # Phím 'q' để thoát
            break
    
    # Đóng tất cả các cửa sổ hiển thị
    cv2.destroyAllWindows()
    # Dừng camera
    picam2.stop()
    print(f"Đã hoàn thành chụp ảnh. Đã lưu {photo_count} ảnh cho ID {folder}.")  # Thông báo kết thúc

# Điểm khởi đầu của chương trình, gọi hàm chụp ảnh
if __name__ == "__main__":
    capture_photos()  # Gọi hàm để bắt đầu chụp ảnh
