@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Lắng nghe sự kiện StoryCreated
        window.Echo.channel('stories')
            .listen('StoryCreated', (e) => {
                // KIỂM TRA 1: Luôn kiểm tra xem dữ liệu nhận về có hợp lệ không
                if (!e || !e.story || !e.story.user) {
                    console.error('Dữ liệu story nhận được không hợp lệ:', e);
                    return; // Dừng lại nếu dữ liệu lỗi
                }

                console.log('Đã nhận story mới:', e.story);
                
                // KIỂM TRA 2: Tìm phần tử HTML để thêm story mới vào
                const storiesList = document.getElementById('stories-list');
                if (!storiesList) {
                    console.error('Không tìm thấy phần tử #stories-list trên trang.');
                    return; // Dừng lại nếu không tìm thấy
                }

                // Xử lý ảnh một cách an toàn
                const imageUrl = e.story.image ? `/storage/${e.story.image}` : '';
                const imageTag = e.story.image ? `<img src="${imageUrl}" alt="${e.story.title}" class="w-full h-auto object-cover rounded-lg mb-4">` : '';

                // Tạo HTML cho story mới
                const newStoryHtml = `
                    <div class="p-6 border-b border-gray-200" id="story-${e.story.id}">
                        <div class="flex items-center mb-4">
                            <div class="font-bold text-lg">${e.story.user.name}</div>
                            <div class="text-gray-500 text-sm ml-2">Vừa xong</div>
                        </div>
                         <a href="/stories/${e.story.id}">
                            ${imageTag}
                            <h3 class="text-2xl font-bold mb-2">${e.story.title}</h3>
                        </a>
                        <p class="text-gray-700">${e.story.content.substring(0, 150)}</p>
                    </div>
                `;

                // Thêm story mới vào đầu danh sách
                storiesList.insertAdjacentHTML('afterbegin', newStoryHtml);
            });
    });
</script>
@endpush