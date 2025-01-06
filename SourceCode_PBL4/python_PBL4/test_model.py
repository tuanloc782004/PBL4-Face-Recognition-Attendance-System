import os
from imutils import paths
import pickle
import cv2
import numpy as np
from sklearn.metrics import precision_score, recall_score, f1_score, confusion_matrix, ConfusionMatrixDisplay, \
    accuracy_score
import face_recognition
import matplotlib.pyplot as plt

# In thông báo bắt đầu kiểm thử
print("[INFO] Start testing model...")

# Tải dữ liệu encodings đã được huấn luyện
if not os.path.exists("encodings.pickle"):
    print("[ERROR] Encodings file not found! Run model_training.py first.")
    exit()

with open("encodings.pickle", "rb") as f:
    data = pickle.load(f)
    knownEncodings = data["encodings"]
    knownNames = data["names"]

# Lấy danh sách các đường dẫn ảnh trong thư mục test_images
test_imagePaths = list(paths.list_images("test_images"))

y_true = []  # Nhãn thật
y_pred = []  # Nhãn dự đoán

# Duyệt qua từng ảnh trong tập kiểm thử
for imagePath in test_imagePaths:
    # Lấy tên thật từ thư mục
    true_name = imagePath.split(os.path.sep)[-2]
    y_true.append(true_name)

    # Đọc và xử lý ảnh
    image = cv2.imread(imagePath)
    if image is None:
        print(f"[WARNING] Unable to read image: {imagePath}")
        y_pred.append("Unknown")
        continue

    rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
    boxes = face_recognition.face_locations(rgb, model="hog")
    encodings = face_recognition.face_encodings(rgb, boxes)

    # Dự đoán tên dựa trên khoảng cách encoding
    name = "Unknown"
    if len(encodings) > 0:
        matches = face_recognition.compare_faces(knownEncodings, encodings[0])
        if True in matches:
            matchedIdxs = [i for i, b in enumerate(matches) if b]
            counts = {}
            for i in matchedIdxs:
                counts[knownNames[i]] = counts.get(knownNames[i], 0) + 1
            name = max(counts, key=counts.get)

    y_pred.append(name)

# Tính các chỉ số đánh giá
accuracy = accuracy_score(y_true, y_pred) * 100  # Tính accuracy (%)
precision = precision_score(y_true, y_pred, average="weighted", zero_division=1)
recall = recall_score(y_true, y_pred, average="weighted", zero_division=1)
f1 = f1_score(y_true, y_pred, average="weighted", zero_division=1)
conf_matrix = confusion_matrix(y_true, y_pred, labels=np.unique(y_true))

# In các chỉ số
print(f"[INFO] Accuracy: {accuracy:.2f}%")
print(f"[INFO] Precision: {precision:.2f}")
print(f"[INFO] Recall: {recall:.2f}")
print(f"[INFO] F1-Score: {f1:.2f}")

# Vẽ Confusion Matrix
plt.figure(figsize=(10, 8))
disp = ConfusionMatrixDisplay(confusion_matrix=conf_matrix, display_labels=np.unique(y_true))
disp.plot(cmap=plt.cm.Blues, values_format="d")
plt.title("Confusion Matrix")
plt.show()

# Vẽ biểu đồ Accuracy, Precision, Recall và F1-Score
metrics = [accuracy / 100, precision, recall, f1]  # Accuracy chuyển đổi sang [0, 1] cho đồng nhất
labels = ["Accuracy", "Precision", "Recall", "F1-Score"]
plt.figure(figsize=(8, 6))
plt.bar(labels, metrics, color=['purple', 'blue', 'green', 'orange'])
plt.title("Model Performance Metrics")
plt.ylabel("Score")
plt.ylim(0, 1)
plt.grid(axis="y")
plt.show()
