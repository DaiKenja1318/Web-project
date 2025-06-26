Web-project

Bài tập giữa kì: Thiết kế Blog web

Mã sinh viên: 22010448

Mục lục:
 - Giới thiệu
 - Sơ đồ cấu trúc
 - Giao diện thực tế
 - Code minh họa
 - Link Repo
 - Link Deploy


Giới thiệu:
-Dự án thiếu kế Blog web được xây dựng giúp cho người dùng có thể đăng bài cho những ý kiến hoặc câu hỏi của mình cho những người dùng khác. Nhằm xây dựng 1 môi trường học tập những cái mới và giải quyết vấn đề của mình gặp phải.

Phạm vi dự án bao gồm xây dựng ứng dụng web sử dụng PHP và Lavarel. Web được xây dựng nhằm đến trải nghiệm mượt mà, thông tin cá nhân được bảo mật. Trong tương lai, Blog web có khả năng mở rộng vs nhiều tính năng mới như thông báo comment từ người dùng khác, phân loại các stories để người dùng có thể tìm kiếm các bài viết mình quan tâm.

Sơ đồ cấu trúc

![image](https://github.com/user-attachments/assets/16ef7095-fde7-42c4-af8b-adceddcfec56)











Mô tả sơ đồ cơ sở dữ liệu
- Dự án sử dụng cơ sở dữ liệu quan hệ gồm 3 bảng chính và 1 bảng phụ: user(Người dùng), stories(Bài viết), comment(bình luận) và bảng phụ role(Vai trò), các bảng được liên kết với nhau thông qua user_id


  Bảng user
    - Key: user_id(int)
    - Các trường:
      + name(string)
      + email(string)
      + password(string)
      + role(string)
    - Mô tả: lưu trữ thông tin của người dùng và liên kết với bảng qua user_id

  Bảng stories
    - Key: user_id
    - Key phụ: story_id
    - Các trường:
      + title(string)
      + Content(string)
      + image(string)
    - Mô tả: lưu trữ các bài viết của người, liên kết vs bảng thông qua user_id
 
  Sơ đồ thuật toán
  ![image](https://github.com/user-attachments/assets/eb121894-43b1-48e0-abd0-bbeef3b3aa0b)











  Sơ đồ hoạt động: Lấy dữ liệu cho Trang chủ
    A[Bắt đầu] --> B{Người dùng đã đăng nhập?}
    B -- Không --> C[Chuyển hướng đến trang đăng nhập]
    B -- Có --> D[Lấy thông tin người dùng hiện tại]
    D --> E[Lấy danh sách (stories) thuộc về người dùng]
    E --> F[Lấy danh sách (commnet) thuộc về các danh sách]
    F --> G[Chuẩn bị dữ liệu để render]
    G --> H[Render trang chủ với]
    H --> I[Kết thúc]



Thuật toán thêm stories và comment

![image](https://github.com/user-attachments/assets/66b54fd1-6342-4480-bc04-bebaed78a8da)






