# Web Project - Blog Application

Bài tập giữa kỳ: Thiết kế Blog Web

Họ và tên: Nguyễn Đình Đức Trung

Mã sinh viên:22010448

---

## Mục lục

* [Giới thiệu](#giới-thiệu)
* [Sơ đồ cấu trúc](#sơ-đồ-cấu-trúc)
* [Sơ đồ cơ sở dữ liệu](#sơ-đồ-cơ-sở-dữ-liệu)
* [Sơ đồ thuật toán](#sơ-đồ-thuật-toán)
* [Giao diện thực tế](#giao-diện-thực-tế)
* [Code minh họa](#code-minh-họa)
* [Link Repo](#link-repo)
* [Link Deploy](#link-deploy)

---

##  Giới thiệu

Dự án **Blog Web** được xây dựng giúp cho người dùng có thể đăng bài, chia sẻ ý kiến hoặc câu hỏi của mình với cộng đồng.
Mục tiêu là xây dựng một môi trường học tập, khám phá những kiến thức mới và cùng nhau giải quyết vấn đề.

### 🔧 Công nghệ sử dụng:

* **Backend:** PHP, Laravel
* **Database:** MySQL
* **Frontend:** Blade, HTML, CSS

### Định hướng phát triển:

* Đảm bảo trải nghiệm người dùng mượt mà.
* Bảo mật thông tin cá nhân.
* Mở rộng thêm các tính năng như:

  * Thông báo khi có bình luận mới.
  * Phân loại bài viết.
  * Tìm kiếm bài viết theo danh mục hoặc từ khóa.

---

## Sơ đồ cấu trúc

![image](https://github.com/user-attachments/assets/16ef7095-fde7-42c4-af8b-adceddcfec56)

---

## Sơ đồ cơ sở dữ liệu

Dự án sử dụng cơ sở dữ liệu quan hệ gồm **3 bảng chính** và **1 bảng phụ**:

* `users` (Người dùng)
* `stories` (Bài viết)
* `comments` (Bình luận)
* `roles` (Vai trò)

### 🔸 Bảng `users`

* **Key:** `user_id (int)`
* **Các trường:**

  * `name (string)`
  * `email (string)`
  * `password (string)`
  * `role (string)`
* **Mô tả:** Lưu trữ thông tin người dùng.

### 🔸 Bảng `stories`

* **Key chính:** `story_id`
* **Key phụ:** `user_id`
* **Các trường:**

  * `title (string)`
  * `content (string)`
  * `image (string)`
* **Mô tả:** Lưu trữ bài viết của người dùng.

### 🔸 Bảng `comments`

* **Key:** `comment_id`
* **Các trường:**

  * `user_id`
  * `story_id`
  * `content`
* **Mô tả:** Lưu trữ các bình luận.

### 🔸 Bảng `roles`

* **Key:** `role_id`
* **Trường:** `role_name`
* **Mô tả:** Phân vai trò (user, admin, ...).

---

## Sơ đồ thuật toán

### Sơ đồ hoạt động trang chủ

![image](https://github.com/user-attachments/assets/eb121894-43b1-48e0-abd0-bbeef3b3aa0b)

**Mô tả quy trình lấy dữ liệu trang chủ:**

1. Kiểm tra người dùng đã đăng nhập hay chưa.
2. Nếu chưa → Chuyển hướng về trang đăng nhập.
3. Nếu đã đăng nhập → Lấy thông tin user, stories, comments.
4. Render dữ liệu lên giao diện trang chủ.

---

### Thuật toán thêm stories và comment

![image](https://github.com/user-attachments/assets/66b54fd1-6342-4480-bc04-bebaed78a8da)

**Chi tiết các bước:**

1. **Kiểm tra đăng nhập:**

   * Nếu chưa → Chuyển đến `/login`.
   * Đăng nhập thành công → Kiểm tra role.
2. **Thao tác:**

   * `User`: Đăng bài, bình luận.
   * `Admin`: Đăng bài, bình luận, chỉnh sửa, xóa bài.
3. **Xử lý bài viết:**

   * Kiểm tra dữ liệu.
   * Thêm, sửa hoặc xóa trong database.
4. **Xử lý bình luận:**

   * Kiểm tra dữ liệu.
   * Lưu comment vào database.

---

## Giao diện thực tế

### 🔹 Giao diện màn hình chính

![image](https://github.com/user-attachments/assets/85acaced-edfd-4440-92d6-f1a9c18d41cf)
![image](https://github.com/user-attachments/assets/a7668759-d09f-4c85-bccc-6d8174fea955)

### 🔹 Giao diện admin panel

![image](https://github.com/user-attachments/assets/3c65c246-d18f-4b8f-b552-f3a9542cca75)

### 🔹 Giao diện tạo stories

![image](https://github.com/user-attachments/assets/610154dd-0f6e-49cb-9517-5511b42cf04b)

### 🔹 Giao diện comment

![image](https://github.com/user-attachments/assets/58dc6833-3d2c-48c9-b942-f38b5f32e860)

### 🔹 Giao diện sửa bài viết

![image](https://github.com/user-attachments/assets/44783b3b-35f2-4785-84a8-b9d4f6871de8)

---

## Code minh họa

> Chi tiết đầy đủ code tại repository dưới đây.

---

## Link Repo

👉 [GitHub Repository](https://github.com/DaiKenja1318/Web-project.github.io)

---

## Link Deploy

 [Xem Website tại đây](https://blog-app-gk.laravel.cloud/)

---

