import os
from imutils import paths
import face_recognition
import pickle
import cv2
import matplotlib.pyplot as plt

# In thông báo khi bắt đầu xử lý ảnh
print("[INFO] Start processing faces...")

# Lấy danh sách tất cả các đường dẫn ảnh trong thư mục 'dataset'
imagePaths = list(paths.list_images("dataset"))

# Kiểm tra xem có tệp pickle đã tồn tại không
if os.path.exists("encodings.pickle"):
    print("[INFO] Loading previous encodings...")
    with open("encodings.pickle", "rb") as f:
        data = pickle.load(f)
        knownEncodings = data["encodings"]
        knownNames = data["names"]
else:
    knownEncodings = []
    knownNames = []

# Chia ảnh thành 10 nhóm (10 giai đoạn)
num_stages = 10
batch_size = len(imagePaths) // num_stages
accuracy_per_stage = []
loss_per_stage = []

for stage in range(num_stages):
    print(f"[INFO] Processing stage {stage + 1}/{num_stages}")
    start_idx = stage * batch_size
    end_idx = (stage + 1) * batch_size if stage < num_stages - 1 else len(imagePaths)
    stage_imagePaths = imagePaths[start_idx:end_idx]

    success_count = 0  # Đếm số lượng ảnh mã hóa thành công

    for imagePath in stage_imagePaths:
        name = imagePath.split(os.path.sep)[-2]
        image = cv2.imread(imagePath)
        if image is None:
            print(f"[WARNING] Unable to read image: {imagePath}")
            continue

        rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
        boxes = face_recognition.face_locations(rgb, model="hog")
        encodings = face_recognition.face_encodings(rgb, boxes)

        if len(encodings) > 0:
            success_count += 1

        for encoding in encodings:
            knownEncodings.append(encoding)
            knownNames.append(name)

    # Tính accuracy và loss cho giai đoạn hiện tại
    stage_accuracy = (success_count / len(stage_imagePaths)) * 100
    stage_loss = 100 - stage_accuracy  # Loss giả lập
    accuracy_per_stage.append(stage_accuracy)
    loss_per_stage.append(stage_loss)

    print(f"[INFO] Stage {stage + 1} - Accuracy: {stage_accuracy:.2f}%, Loss: {stage_loss:.2f}%")

# Lưu lại encodings sau khi hoàn thành tất cả giai đoạn
print("[INFO] Serializing encodings...")
data = {"encodings": knownEncodings, "names": knownNames}
with open("encodings.pickle", "wb") as f:
    f.write(pickle.dumps(data))
print("[INFO] Training complete. Encodings saved to 'encodings.pickle'")

# Vẽ đồ thị accuracy và loss
plt.figure(figsize=(12, 6))

# Accuracy plot
plt.subplot(1, 2, 1)
plt.plot(range(1, num_stages + 1), accuracy_per_stage, marker='o', label="Accuracy")
plt.title("Accuracy per Stage")
plt.xlabel("Stage")
plt.ylabel("Accuracy (%)")
plt.grid()
plt.legend()

# Loss plot
plt.subplot(1, 2, 2)
plt.plot(range(1, num_stages + 1), loss_per_stage, marker='o', label="Loss", color='red')
plt.title("Loss per Stage")
plt.xlabel("Stage")
plt.ylabel("Loss (%)")
plt.grid()
plt.legend()

plt.tight_layout()
plt.show()