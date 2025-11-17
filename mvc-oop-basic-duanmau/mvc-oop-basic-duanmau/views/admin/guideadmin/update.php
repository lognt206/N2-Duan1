<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Hướng dẫn viên</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ffc107;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        .required {
            color: red;
        }
        input[type="text"],
        input[type="date"],
        input[type="number"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #ffc107;
        }
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        .btn-warning {
            background: #ffc107;
            color: black;
        }
        .btn-warning:hover {
            background: #e0a800;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #545b62;
        }
        .form-actions {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            display: flex;
            gap: 10px;
        }
        .file-info {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .preview-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 5px;
        }
        .current-file {
            padding: 8px 12px;
            background: #e7f3ff;
            border-radius: 4px;
            margin-top: 5px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Sửa thông tin Hướng dẫn viên</h1>

        <form action="index.php?act=guideadmin_update" method="POST" enctype="multipart/form-data">
            
            <input type="hidden" name="guide_id" value="<?php echo $guide['guide_id']; ?>">

            <div class="form-row">
                <div class="form-group">
                    <label>Tài khoản <span class="required">*</span></label>
                    <select name="user_id" required>
                        <option value="<?php echo $guide['user_id']; ?>" selected>
                            <?php echo htmlspecialchars($guide['username']); ?>
                        </option>
                        <?php foreach ($availableUsers as $user): ?>
                            <option value="<?php echo $user['user_id']; ?>">
                                <?php echo htmlspecialchars($user['username']); ?> - 
                                <?php echo htmlspecialchars($user['full_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Họ và tên <span class="required">*</span></label>
                    <input type="text" name="full_name" 
                           value="<?php echo htmlspecialchars($guide['full_name']); ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Ngày sinh <span class="required">*</span></label>
                    <input type="date" name="birth_date" 
                           value="<?php echo $guide['birth_date']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Liên hệ <span class="required">*</span></label>
                    <input type="text" name="contact" 
                           value="<?php echo htmlspecialchars($guide['contact']); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Ảnh đại diện</label>
                <input type="file" name="photo" accept="image/*" onchange="previewImage(this)">
                <div class="file-info">Định dạng: JPG, PNG, GIF. Để trống nếu không đổi.</div>
                <?php if ($guide['photo']): ?>
                    <img src="<?php echo $guide['photo']; ?>" class="preview-image" id="photoPreview">
                <?php else: ?>
                    <img id="photoPreview" class="preview-image" style="display:none;">
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Chứng chỉ</label>
                <input type="file" name="certificate" accept=".pdf,.jpg,.jpeg,.png">
                <div class="file-info">Định dạng: PDF, JPG, PNG. Để trống nếu không đổi.</div>
                <?php if ($guide['certificate']): ?>
                    <div class="current-file">
                        📄 File hiện tại: <a href="<?php echo $guide['certificate']; ?>" target="_blank">Xem</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Ngôn ngữ <span class="required">*</span></label>
                    <input type="text" name="languages" 
                           value="<?php echo htmlspecialchars($guide['languages']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Loại HDV</label>
                    <select name="category">
                        <option value="1" <?php echo ($guide['category'] == 1) ? 'selected' : ''; ?>>
                            Cấp 1 - Quốc tế
                        </option>
                        <option value="2" <?php echo ($guide['category'] == 2) ? 'selected' : ''; ?>>
                            Cấp 2 - Nội địa
                        </option>
                        <option value="3" <?php echo ($guide['category'] == 3) ? 'selected' : ''; ?>>
                            Cấp 3 - Địa phương
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Đánh giá (0-5)</label>
                <input type="number" name="rating" min="0" max="5" step="0.1" 
                       value="<?php echo $guide['rating']; ?>">
            </div>

            <div class="form-group">
                <label>Kinh nghiệm</label>
                <textarea name="experience"><?php echo htmlspecialchars($guide['experience']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Tình trạng sức khỏe</label>
                <textarea name="health_condition"><?php echo htmlspecialchars($guide['health_condition']); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-warning">💾 Cập nhật</button>
                <a href="index.php?act=guideadmin" class="btn btn-secondary">❌ Hủy</a>
            </div>
        </form>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('photoPreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>