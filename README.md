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


Chi tiết các bước
1.Kiểm tra đăng nhập:
    Hệ thống kiểm tra người dùng đã đăng nhập chưa (isLogined?).
        - Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập (route to "/login").
        - Sau khi đăng nhập xong sẽ tùy thuộc vào role của tài khoản thì người dùng sẽ được               dùng chức năng nào.

2. Thao tác
- Nếu là tài khoản có role user thì có chức năng tạo và comment bài viết, với admin sẽ            có 2 chức năng trên và thêm 1 chức năng là xóa hoặc sửa bài viết.
- Thêm stories:
  + lấy dữ liệu từ request.
  + Kiểm tra xác thực dữ liệu.
  + tạo stories mới vào database.
- Comment bài viết:
  + Truy vấn stories từ user_id và story_id.
  + Kiểm tra và xác thực dữ liệu.
  + Cập nhật comment mới trong csdl.
- Xóa stories với role admin:
  + Truy vấn stories từ user_id và story_id.
  + thực hiện xóa stories từ csdl.
- Chỉnh sửa stories với role admin:
  + Truy vấn stories từ user_id và story_id.
  + Kiểm tra dữ liệu sửa đổi hợp lệ.
  + Cập nhật stories với dữ liệu đã xác thực.
 
Giao diện thực tế
Giao diện màn hình chính
![image](https://github.com/user-attachments/assets/85acaced-edfd-4440-92d6-f1a9c18d41cf)

![image](https://github.com/user-attachments/assets/a7668759-d09f-4c85-bccc-6d8174fea955)

Giao diện admin panel
![image](https://github.com/user-attachments/assets/3c65c246-d18f-4b8f-b552-f3a9542cca75)

Giao diện tạo stories
![image](https://github.com/user-attachments/assets/610154dd-0f6e-49cb-9517-5511b42cf04b)

Giao diện comment
![image](https://github.com/user-attachments/assets/58dc6833-3d2c-48c9-b942-f38b5f32e860)

Giao diện sửa bài viết
![image](https://github.com/user-attachments/assets/44783b3b-35f2-4785-84a8-b9d4f6871de8)


Code minh họa
Link Repo
https://github.com/DaiKenja1318/Web-project.github.io

Link Deploy
https://blog-app-gk.laravel.cloud/











   
    
        




